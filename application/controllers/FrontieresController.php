<?php

/**
 * FrontieresController
 * 
 * @author : samuel szoniecky
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class FrontieresController extends Zend_Controller_Action {
	
	var $dbNom = "flux_frontieres";

	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		try {
			//récupère les photos et les géolocalisations
		    $site = new Flux_Site();
		    $db = $site->getDb($this->dbNom);
			$dbUGD = new Model_DbTable_Flux_UtiGeoDoc($db);
	    	$where = null;
			if($this->_getParam('id', 0)){
		    	$where = "d.doc_id = ".$this->_getParam('id', 0);			
	    	}
			$this->view->arr = $dbUGD->getDataLiees("RAND()",$where); 
	    	

		}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	}
	
	public function ajoutAction() {
		try {
			$params = $this->getRequest()->getParams();
			$this->view->data = $params;
			if($params){
	    	   	//initialise les variables
				$d = new Zend_Date();		
				$site = new Flux_Site();
			    $db = $site->getDb($this->dbNom);
				$dbG = new Model_DbTable_Flux_Geos($db);
				$dbGUD = new Model_DbTable_Flux_UtiGeoDoc($db);
				//récupération de l'utilisateur	
				$idU = $site->getUser(array("login"=>$_SERVER['REMOTE_ADDR'],"flux"=>"frontières","date_inscription"=>$d->get("c")));
				//enregistre la position
				$idGeo = $dbG->ajouter(array("lat"=>$params['lat'],"lng"=>$params['lng'],"zoom_max"=>$params['zoom'],"maj"=>$d->get("c")));
	    		$dbGUD->ajouter(array("doc_id"=>$params['idDoc'],"uti_id"=>$idU,"geo_id"=>$idGeo,"maj"=>$d->get("c"), "note"=>$params['note']));
	    		//calcul le tag cloud
				$dbUTD = new Model_DbTable_Flux_UtiTagDoc($db);		
				$tagDoc = $dbUTD->GetDocTags($params['idDoc'],"" , "", "RAND()", "10");
				for ($i = 0; $i < count($tagDoc); $i++) {
					$tagDoc[$i]['on'] = 1;
				}
				$tagRand = $dbUTD->GetUtiTags(1 ,"" , "", "RAND()", "10");
				for ($i = 0; $i < count($tagRand); $i++) {
					$tagRand[$i]['on'] = 0;
				}
				$tags = array_merge($tagDoc, $tagRand);
				$this->view->data = $tags;
				
			}
		}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	}
	
	
}
