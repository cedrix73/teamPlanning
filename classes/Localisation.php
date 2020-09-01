<?php


/**
 * Event
 * Classe gérant les types d'événements
 * CRUD
 * @author CV170C7N
 */
class Localisation {
    
    private $_dbaccess;
    private $_sql;
    private $_type;
    
    public function __construct($dbaccess, $type = 'site') 
    {
        $this->_dbaccess = $dbaccess;
        $this->_sql = '';
        $this->_type = $type;
        $this->_requeteJointures = " INNER JOIN service on ressource.service_id = service.id " 
                               . " INNER JOIN departement on service.departement_id = departement.id " 
                               . " INNER JOIN site on departement.site_id = site.id ";
    }
    
    public function getSql()
    {
        return $this->_sql;
    }

    public function setType($type) 
    {
        $this->_type = $type;
    }
    
    public function getAll()
    {
        $results = array();
        $this->_sql = 'SELECT DISTINCT * '.
                      ' FROM ' . $this->_type;
        $reponse = $this->_dbaccess->execQuery($this->_sql);
        $results=$this->_dbaccess->fetchArray($reponse);
        return $results;
    }


    
    
    public function update($typeId, $typeLibelle, $typeDescription, $key = null) {
        $requeteSup = '';
        switch($this->type) {
            case 'departement':
                $requeteSup = ', site_id = \'' . $key . '\'';
            break;

            case 'site':
                $requeteSup = ', departement_id = \'' . $key . '\'';
            break;
        }
        
        $this->_sql = 'UPDATE ' . $this->_type . ' set libelle  = \''.$typeLibelle
                   . '\', description = \''.$typeDescription.'\''. $requeteSup
                   . ' WHERE id = '.$typeId;
        try{
            $retour = $this->_dbaccess->execQuery($this->_sql);
        }catch(Exception $e){
            $retour = false;
        }
        return $retour;
    }
    
    public function create($tabInsert)
    {
        $sqlData = 'VALUES (';
            // fonction "raccourci" qui effectue une simple reconversion d'une chaîne
        $sqlInsert = 'INSERT INTO ' . $this->_type . ' (';
        $i = 0;
        $max = count($tabInsert)-1;
        foreach ($tabInsert as $key=>$value) {
            $sqlInsert .= $key;
            $sqlData .= '\''.$value.'\'';
            if ($i<$max) {
                $sqlInsert .= ', ';
                $sqlData .= ', ';
            } else {
                $sqlInsert .= ') ';
                $sqlData .= ') ';
            }
            $i++;
        }
        
        $sql = $sqlInsert . $sqlData;
        $this->sql = ' ' . $sql;

        

        try{
            $retour = $this->_dbaccess->execQuery($sql);
            //$retour = TRUE;
        }catch(Exception $e){
            $retour = false;
        }
        return $retour;
        
            
    }

    /**
     * GetDepartementsBySite
     * retourne un tableau de tous les libellés de departements
     * ou ceux en fonction d'un site donné ($sieId)
     * 
     * @param int $siteId 
     * 
     * @return array  
     */
    public function getDepartementsBySite($site = null){
        $rs = false;
        $requete = "SELECT DISTINCT departement.libelle "
                . " FROM departement ";
                
        $tabDepartements = array();
        if($site != null){
            $requete .=  " INNER JOIN site on departement.site_id = site.id "
                      . " WHERE site.libelle = '" . $site ."'" 
                      . " ORDER BY departement.libelle";
        }
        
	    $rs = $this->_dbaccess->execQuery($requete);
        $results=$this->_dbaccess->fetchRow($rs);
        $i=0;
        foreach ($results as $ligne) {
            $tabDepartements[$i]=$ligne[0];
            $i++;
        }
        
        return $tabDepartements;
    }
    
    /**
     * GetServicesByDepartement
     * retourne un tableau de tous les libellés de departements
     * ou ceux en fonction d'un site donné ($sieId)
     * 
     * @param int    $siteId 
     * @param string $departementLibelle 
     * 
     * @return array  
     */
    public function getServicesByDepartement($site = null, $departementLibelle = '')
    {
        $rs = false;
        $requete = "SELECT DISTINCT service.libelle "
                . " FROM service ";
        $requete .= $this->_requeteJointures;
                
        $tabServices = array();

        if($site != null && $site != 'Tous*'){
            $requete.= " AND site.libelle = '" . $site ."'";
        }

        if($departementLibelle != ''){

            $requete.=  " WHERE departement.libelle = '" . $departementLibelle ."'";
        }
        $requete.= " ORDER BY service.libelle";



	    $rs = $this->_dbaccess->execQuery($requete);
        $results=$this->_dbaccess->fetchRow($rs);
        $i=0;
        foreach ($results as $ligne) {
            $tabServices[$i]=$ligne[0];
            $i++;
        }
        return $tabServices;
    }
    
    
}
