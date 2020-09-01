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
    
    
}
