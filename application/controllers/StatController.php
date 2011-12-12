<?php

/**
 * StatController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class StatController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$this->view->title = "Statistiques disponibles";
	    $this->view->headTitle($this->view->title, 'PREPEND');
	    $this->view->stats = array("tags pour un utilisateur", "utilisateurs en relation");
	}

    public function tagusernetworkAction()
    {
	    $this->view->stats = "";
    	if($this->_getParam('tag', 0) && $this->_getParam('uti', 0)){
			$s = new Flux_Stats;
			$this->view->stats = $s->GetTagUserNetwork(utf8_encode($this->_getParam('tag', 0)), $this->_getParam('uti', 0));	    
	    }
    }	

    public function updateAction()
    {
	    $this->view->stats = "";
    	if($this->_getParam('code', 0)){
			$s = new Flux_Stats;
			$this->view->stats = $s->update($this->_getParam('code', 0));	    
	    }
    }	
    
}