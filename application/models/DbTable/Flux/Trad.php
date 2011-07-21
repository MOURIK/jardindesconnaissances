<?php
/**
 * Ce fichier contient la classe Flux_Trad.
 *
 * @copyright  2011 Samuel Szoniecky
 * @license    "New" BSD License
*/


/**
 * Classe ORM qui représente la table 'flux_Trad'.
 *
 * @copyright  201=& Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_Flux_Trad extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'flux_Trad';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'trad_id';

    
    /**
     * Vérifie si une entrée Flux_Trad existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('trad_id'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->trad_id; else $id=false;
        return $id;
    } 
        
    /**
     * Ajoute une entrée Flux_Trad.
     *
     * @param array $data
     * @param boolean $existe
     *  
     * @return integer
     */
    public function ajouter($data, $existe=true)
    {
    	$id=false;
    	if($existe)$id = $this->existe($data);
    	if(!$id){
    	 	$id = $this->insert($data);
    	}
    	return $id;
    } 
           
    /**
     * Recherche une entrée Flux_Trad avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
        $this->update($data, 'flux_Trad.trad_id = ' . $id);
    }
    
    /**
     * Recherche une entrée Flux_Trad avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {
        $this->delete('flux_Trad.trad_id = ' . $id);
    }
    
    /**
     * Récupère toutes les entrées Flux_Trad avec certains critères
     * de tri, intervalles
     */
    public function getAll($order=null, $limit=0, $from=0)
    {
        $query = $this->select()
                    ->from( array("flux_Trad" => "flux_Trad") );
                    
        if($order != null)
        {
            $query->order($order);
        }

        if($limit != 0)
        {
            $query->limit($limit, $from);
        }

        return $this->fetchAll($query)->toArray();
    }

    /**
     * Récupère les spécifications des colonnes Flux_Trad 
     */
    public function getCols(){

    	$arr = array("cols"=>array(
    	   	array("titre"=>"trad_id","champ"=>"trad_id","visible"=>true),
    	array("titre"=>"ieml_id","champ"=>"ieml_id","visible"=>true),
    	array("titre"=>"tag_id","champ"=>"tag_id","visible"=>true),
    	array("titre"=>"trad_date","champ"=>"trad_date","visible"=>true),
    	array("titre"=>"trad_post","champ"=>"trad_post","visible"=>true),
        	
    		));    	
    	return $arr;
		
    }     
    
    /*
     * Recherche une entrée Flux_Trad avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $trad_id
     */
    public function findByTrad_id($trad_id)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_Trad") )                           
                    ->where( "f.trad_id = ?", $trad_id );

        return $this->fetchAll($query)->toArray(); 
    }
    /*
     * Recherche une entrée Flux_Trad avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $ieml_id
     */
    public function findByIeml_id($ieml_id)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_Trad") )                           
                    ->where( "f.ieml_id = ?", $ieml_id );

        return $this->fetchAll($query)->toArray(); 
    }
    /*
     * Recherche une entrée Flux_Trad avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param int $tag_id
     */
    public function findByTag_id($tag_id)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_Trad") )                           
                    ->where( "f.tag_id = ?", $tag_id );

        return $this->fetchAll($query)->toArray(); 
    }
    /*
     * Recherche une entrée Flux_Trad avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param date $trad_date
     */
    public function findByTrad_date($trad_date)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_Trad") )                           
                    ->where( "f.trad_date = ?", $trad_date );

        return $this->fetchAll($query)->toArray(); 
    }
    /*
     * Recherche une entrée Flux_Trad avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param tinyint $trad_post
     */
    public function findByTrad_post($trad_post)
    {
        $query = $this->select()
                    ->from( array("f" => "flux_Trad") )                           
                    ->where( "f.trad_post = ?", $trad_post );

        return $this->fetchAll($query)->toArray(); 
    }
    
    
}
