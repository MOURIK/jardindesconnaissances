<?php

/**
 * DeleuzeController
 * 
 * @author : samuel szoniecky
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class DeleuzeController extends Zend_Controller_Action {
	
	var $dbNom = "flux_DeleuzeSpinoza";
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		try {
			$this->view->title = "Affichage des flux Deleuze";
		    $this->view->headTitle($this->view->title, 'PREPEND');
		    $site = new Flux_Site();
		    $db = $site->getDb($this->dbNom);
			$dbUTD = new Model_DbTable_Flux_UtiTagDoc($db);
		    if($this->_getParam('tag', 0)){
		    	$arr = $dbUTD->findByTag($this->_getParam('tag', 0));
		        $this->view->arr = $arr;
		    }elseif ($this->_getParam('url', 0)){
		    	$arr = $dbUTD->findByUrl($this->_getParam('url', 0));
		        $this->view->arr = $arr;
		    }else{
			    $arr = $dbUTD->getAllInfo();
		        $this->view->arr = $arr;
		    }
		}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	}

	/**
	 * affichage des positions d'un term
	 */
	public function positionAction() {
		
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
		    // l'identité existe ; on la récupère
		    $this->view->identite = $auth->getIdentity();
		    $ssExi = new Zend_Session_Namespace('exi');
		    $this->view->idExi = $ssExi->idExi;
		}else{
		    $this->_redirect('/auth/login');
		}
		
	    $this->view->resultats = "";
    	if($this->_getParam('term', 0)){
			$oD = new Flux_Deleuze($this->dbNom);
    		$arrPosis = $oD->getTermPositions($this->_getParam('term', 0));
			$this->view->resultats = $arrPosis;
			$this->view->term = $this->_getParam('term', 0);
	    }
	}	
	
	public function ajoutAction() {
		try {
			$auth = Zend_Auth::getInstance();
			if ($auth->hasIdentity()) {
			    // l'identité existe ; on la récupère
			    $this->view->identite = $auth->getIdentity();
				$ssExi = new Zend_Session_Namespace('exi');
				//enregistre les positions
				$oD = new Flux_Deleuze($this->dbNom);
				$this->view->data = $oD->saveTermPosition($ssExi->idExi, $this->getRequest()->getParams());
			}else{
			    $this->_redirect('/auth/login');
			}
			
		}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	}

	public function suppAction() {
		try {
			$auth = Zend_Auth::getInstance();
			if ($auth->hasIdentity()) {
				//supprime la position
				$oD = new Flux_Deleuze($this->dbNom);
				$this->view->data = $this->getRequest()->getParams();
				$oD->suppTermPosition($this->_getParam('idDoc', 0));
			}else{
			    $this->_redirect('/auth/login');
			}
		}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	}
	
	public function modifAction() {
		try {
			$auth = Zend_Auth::getInstance();
			if ($auth->hasIdentity()) {
				//supprime la position
				$oD = new Flux_Deleuze($this->dbNom);
				$oD->modifTermPosition($this->getRequest()->getParams());
			}else{
			    $this->_redirect('/auth/login');
			}
		}catch (Zend_Exception $e) {
	          echo "Récupère exception: " . get_class($e) . "\n";
	          echo "Message: " . $e->getMessage() . "\n";
		}
	}
	
}
