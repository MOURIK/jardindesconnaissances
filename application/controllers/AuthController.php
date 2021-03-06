<?php
class AuthController extends Zend_Controller_Action
{
    public function loginAction()
    {    	
		$this->view->erreur = false;    		
		$this->view->code = 1;
		
		$ssExi = new Zend_Session_Namespace('uti');
		if($this->_getParam('idBase'))$ssExi->dbNom = $this->_getParam('idBase');	
		$s = new Flux_Site($ssExi->dbNom);
		
		if($this->_getParam('redir', 0)){
			$ssExi->redir='/'.$this->_getParam('redir', 0);
		}
		
    		// Obtention d'une référence de l'instance du Singleton de Zend_Auth
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();

    		if($this->_getParam('ajax', 0)){
    			$this->view->ajax = true;
            $adapter = new Zend_Auth_Adapter_DbTable(
                $s->db,
                'flux_uti',
                'login',
                'mdp'
                );		                
            $adapter->setIdentity($this->_getParam('login'));
            $adapter->setCredential($this->_getParam('mdp'));
            $login = $this->_getParam('login');
            // Tentative d'authentification et stockage du résultat
			if($login)$result = $auth->authenticate($adapter);
    		}elseif ($this->_getParam('login')) {
	    		$adapter = new Zend_Auth_Adapter_DbTable(
	                $s->db,
	                'flux_uti',
	                'login',
	                'mdp'
	                );		                
	        $login = $this->_getParam('login');
	        $adapter->setIdentity($login);
	        $adapter->setCredential($this->_getParam('mdp'));
	        // Tentative d'authentification et stockage du résultat
			$result = $auth->authenticate($adapter);			
			$this->view->erreur = "login calculé";			
    		}else{
    			return;
    		}     		
    		
    		if ($result->isValid()) {		            	
			//met en sessions les informations de l'existence
			$dbUti = new Model_DbTable_Flux_Uti($s->db);
			$rs = $dbUti->findByLogin($login);
    			$ssExi->uti = $rs;
			$ssExi->idUti = $rs["uti_id"];		            	
	        	
			if($this->view->ajax){
				$this->view->rs = $ssExi->uti;
	        }else{
		    		$this->_redirect($ssExi->redir);
		    		return;
	        }
	    }else{
		    	$this->view->code = $result->getCode();
	    		switch ($result->getCode()) {
            		case 0:
						$this->view->erreur = "Problème d'identification. Veuillez contacter le webmaster.";		            	
	            		break;		            		
            		case -1:
						$this->view->erreur = "Le login n'a pas été trouvé. Veillez vous inscrire.";		            	
	            		break;		            		
            		case -2:
						$this->view->erreur = "Le login est ambigue.";		            	
	            		break;		            		
            		case -3:
						$this->view->erreur = "Le login et/ou le mot de passe ne sont pas bons.";		            	
	            		break;		            		
            	}
	   	}
    }

    public function inscriptionAction()
    {
    		if($this->_getParam('ajax', 0)){
			$formData['ip_inscription'] = $_SERVER['REMOTE_ADDR'];
			$formData['date_inscription'] = date('Y-m-d H:i:s');
			$formData['login'] = $this->_getParam('login');
			$formData['mdp'] = $this->_getParam('mdp');
			$formData['email'] = $this->_getParam('email');
			$ssExi = new Zend_Session_Namespace('uti');
			if($this->_getParam('idBase'))$ssExi->dbNom = $this->_getParam('idBase');				
			//echo "ssExi->dbNom = ".$ssExi->dbNom;
			$s = new Flux_Site($ssExi->dbNom);
			$dbUti = new Model_DbTable_Flux_Uti($s->db);
			$this->view->rs = $dbUti->ajouter($formData, true, true);
			$this->view->message = "Votre compte est créer.";
			$this->view->redir = $ssExi->redir;			
			$this->view->ajax=true;
    			$ssExi->uti = $this->view->rs;
			$ssExi->idUti = $this->view->rs["uti_id"];		            				
    		}else{    		
	    		$this->view->form = $form = new Form_Auth_Inscription();
	   	    	if($this->getRequest()->isPost()){
					$formData = $this->getRequest()->getPost();
					$this->view->ajax = isset($formData['ajax']);
					if(isset($formData['idBase']))$ssExi->dbNom=$formData['idBase'];						
					if($form->isValid($formData)){
						//supprime les données superflux
						unset($formData['envoyer']);					
						unset($formData['ajax']);					
						unset($formData['idBase']);					
						
						$formData['ip_inscription'] = $_SERVER['REMOTE_ADDR'];
						$formData['date_inscription'] = date('Y-m-d H:i:s');
						//print_r($formData);
						$ssExi = new Zend_Session_Namespace('uti');
						//echo "ssExi->dbNom = ".$ssExi->dbNom;
						if(isset($ssExi->dbNom)){
							$site = new Flux_Site();
							$db = $site->getDb($ssExi->dbNom);						
							$dbUti = new Model_DbTable_Flux_Uti($db);
						}else $dbUti = new Model_DbTable_Flux_Uti();					
						$idUti = $dbUti->ajouter($formData);
						if($this->view->ajax)
							$this->view->idUti = $idUti;
						else
							$this->_redirect('auth/login');
					}else{
						if($this->view->ajax)
							$this->view->idUti = -1;
						else
							$form->populate($formData);
					}
					
				}    			        
    		}
    }
    
    public function deconnexionAction()
    {
		$this->clearConnexion();
    }
    
    function clearConnexion(){
		$ssExi = new Zend_Session_Namespace('uti'); 		   	
		$redir = $ssExi->redir;
		Zend_Session::namespaceUnset('uti');
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
	    $this->_redirect($redir);            	
    }

	/**
	 * Controller pour une authentification google
	 * 
	 * les scopes valides
	 * https://developers.google.com/gdata/faq#AuthScopes
	 */
    var $googleScopes = array(
		"Analytics"=>"https://www.google.com/analytics/feeds/",
		"Sites"=>"https://sites.google.com/feeds/",
		"Blogger"=>"http://www.blogger.com/feeds/",
		"Book"=>"http://www.google.com/books/feeds/",
		//"Calendar"=>"https://www.google.com/calendar/feeds/",
	    "Calendar"=>"https://www.googleapis.com/auth/calendar",
		"Contacts"=>"https://www.google.com/m8/feeds/",
		"Shopping"=>"https://www.googleapis.com/auth/structuredcontent",
		"Documents"=>"https://docs.google.com/feeds/",
		"Finance"=>"http://finance.google.com/finance/feeds/",
		"Gmail"=>"https://mail.google.com/mail/feed/atom/",
		"Health"=>"https://www.google.com/health/feeds/",
		"Maps"=>"http://maps.google.com/maps/feeds/",
		"Picasa"=>"http://picasaweb.google.com/data/",
		"Portable"=>"http://www-opensocial.googleusercontent.com/api/people",
		"Sidewiki"=>"http://www.google.com/sidewiki/feeds/",
		"Spreadsheets"=>"https://spreadsheets.google.com/feeds/",
		"Webmaster"=>"http://www.google.com/webmasters/tools/feeds/",
		"YouTube"=>"http://gdata.youtube.com",
    	"Drive"=>"https://www.googleapis.com/auth/drive",
    	"Profil"=>"https://www.googleapis.com/auth/userinfo.profile"	
    );
    
    
	public function googleAction() {

		$ssGoogle = new Zend_Session_Namespace('google');
		//print_r($ssGoogle);
		//echo $ssGoogle->redir;
		
		if(!$ssGoogle->client){
			$client = new Google_Client();
			$client->setClientId(KEY_GOOGLE_CLIENT_ID);
			$client->setClientSecret(KEY_GOOGLE_CLIENT_SECRET);
			$client->setRedirectUri('http://' .$this->getRequest()->getHttpHost().$this->view->baseUrl()."/auth/google");
			$scopes = $this->_getParam('scopes',array("Drive"));
			foreach ($scopes as $s) {
				$client->addScope($this->googleScopes[$s]);
			}
			$ssGoogle->client = $client;
			$ssGoogle->redir = $this->_getParam('redir',"flux/google");
		}else
			$client = $ssGoogle->client;			
		
		/************************************************
		  If we're logging out we just need to clear our
		  local access token in this case
		  pour zend suppression de la session
		************************************************/
		if ($this->_getParam('logout')) {
			unset($_SESSION['upload_token']);		
			$redir = 'http://' .$this->getRequest()->getHttpHost().$this->view->baseUrl().urldecode($ssGoogle->redir);
			Zend_Session::namespaceUnset('google');
			$this->_redirect($redir);				
		}else{
					
			/************************************************
			  If we have a code back from the OAuth 2.0 flow,
			  we need to exchange that with the authenticate()
			  function. We store the resultant access token
			  bundle in the session, and redirect to ourself.
			 ************************************************/
			if ($this->_getParam('code')) {
			  $client->authenticate($this->_getParam('code'));
			  $ssGoogle->token = $client->getAccessToken();
			  $this->_redirect('/auth/google');
			}
			
			if (isset($_SESSION['upload_token']) && $_SESSION['upload_token']) {
			  $client->setAccessToken($_SESSION['upload_token']);
			  if ($client->isAccessTokenExpired()) {
			    unset($_SESSION['upload_token']);
			  }
			} else {
				$authUrl = $client->createAuthUrl();
			}
					
			/************************************************
			  If we have an access token, we can make
			  requests, else we generate la liste des plannings.
			 ************************************************/
			if ($ssGoogle->token) {
			  	//echo "token=".$ssPlan->token;
			  	$this->verifExpireToken($ssGoogle);
			  	$this->_redirect('http://' .$this->getRequest()->getHttpHost().$this->view->baseUrl().urldecode($ssGoogle->redir));		  	
			}else{
			  	$this->view->authUrl = $client->createAuthUrl();						
			}					
		}
			
		//print_r($ssGoogle);
		//echo $ssGoogle->redir;
		
	}

	private function verifExpireToken($ss){
		$ss->client->setAccessToken($ss->token);
		if ($ss->client->isAccessTokenExpired()) {
		    $ss->token=false;
		    $this->_redirect('/auth/google');
		}
	}    

    public function finsessionAction()
    {
		
    }
	
}