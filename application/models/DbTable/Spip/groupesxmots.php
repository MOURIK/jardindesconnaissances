<?php
/**
 * Ce fichier contient la classe Spip_groupes_mots.
 *
 * @copyright  2008 Gabriel Malkas
 * @copyright  2010 Samuel Szoniecky
 * @license    "New" BSD License
*/


/**
 * Classe ORM qui représente la table 'spip_groupes_mots'.
 *
 * @copyright  2014 Samuel Szoniecky
 * @license    "New" BSD License
 */
class Model_DbTable_Spip_groupesxmots extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'spip_groupes_mots';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'id_groupe';
	
    
    /**
     * Vérifie si une entrée Spip_groupes_mots existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('id_groupe'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->id_groupe; else $id=false;
        return $id;
    } 
        
    /**
     * Ajoute une entrée Spip_groupes_mots.
     *
     * @param array 		$data
     * @param boolean 	$existe
     * @param boolean 	$rs
     *  
     * @return integer
     */
    public function ajouter($data, $existe=true, $rs=false)
    {
	    	$id=false;
	    	if($existe)$id = $this->existe($data);
	    	if(!$id){
	    	 	$id = $this->insert($data);
	    	}
	    	if($rs) return $this->findById_groupe($id);
	    else return $id;
    } 
           
    /**
     * Recherche une entrée Spip_groupes_mots avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
   	
    	$this->update($data, 'spip_groupes_mots.id_groupe = ' . $id);
    }
    
    /**
     * Recherche une entrée Spip_groupes_mots avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {
    	$this->delete('spip_groupes_mots.id_groupe = ' . $id);
    }
    
    /**
     * Récupère toutes les entrées Spip_groupes_mots avec certains critères
     * de tri, intervalles
     */
    public function getAll($order=null, $limit=0, $from=0)
    {
   	
    	$query = $this->select()
                    ->from( array("spip_groupes_mots" => "spip_groupes_mots") );
                    
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
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param bigint $id_groupe
     *
     * @return array
     */
    public function findById_groupe($id_groupe)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.id_groupe = ?", $id_groupe );

		$arr = $this->fetchAll($query)->toArray();
        return count($arr) ? $arr[0] : false; 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param text $titre
     *
     * @return array
     */
    public function findByTitre($titre)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.titre = ?", $titre );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param text $descriptif
     *
     * @return array
     */
    public function findByDescriptif($descriptif)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.descriptif = ?", $descriptif );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param longtext $texte
     *
     * @return array
     */
    public function findByTexte($texte)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.texte = ?", $texte );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $unseul
     *
     * @return array
     */
    public function findByUnseul($unseul)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.unseul = ?", $unseul );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $obligatoire
     *
     * @return array
     */
    public function findByObligatoire($obligatoire)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.obligatoire = ?", $obligatoire );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param text $tables_liees
     *
     * @return array
     */
    public function findByTables_liees($tables_liees)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.tables_liees = ?", $tables_liees );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $minirezo
     *
     * @return array
     */
    public function findByMinirezo($minirezo)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.minirezo = ?", $minirezo );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $comite
     *
     * @return array
     */
    public function findByComite($comite)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.comite = ?", $comite );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param varchar $forum
     *
     * @return array
     */
    public function findByForum($forum)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.forum = ?", $forum );

        return $this->fetchAll($query)->toArray(); 
    }
    	/**
     * Recherche une entrée Spip_groupes_mots avec la valeur spécifiée
     * et retourne cette entrée.
     *
     * @param timestamp $maj
     *
     * @return array
     */
    public function findByMaj($maj)
    {
        $query = $this->select()
                    ->from( array("s" => "spip_groupes_mots") )                           
                    ->where( "s.maj = ?", $maj );

        return $this->fetchAll($query)->toArray(); 
    }
    
    
}
