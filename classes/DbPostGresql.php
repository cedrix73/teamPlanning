<?php

/**
 * @name DbAccess
 * @author cvonfelten
 * Classe créant une couche d'abstraction mysql et  gérant les accès à la BD
 */



//namespace base;
// fichier appelant: use base as DbAccess;
// \DbAccess\Mysqli->methode
class DbPostGresql {

	
	/* Etablit une connexion à un serveur de base de données et retourne un identifiant de connexion
	   L'identifiant est positif en cas de succès, FALSE sinon.
	   On pourrait se connecter avec un utilisateur lambda
	   */
	public function connect($no_msg = 0)
	{
		$link = pg_connect("host=".$this->host . " user=".$this->username . " password=".$this->password . " dbname=".$this->dbase . " port=". $this->port);
		if (!$link)
		{
                    if ($no_msg == 0){
                        echo "Erreur de connexion sur ".$this->host." par ".$this->username;
                    }
                    $this->database_error();
                    return FALSE;
		}else{
                    $this->link = $link;
                }
		
		return $link;
	}
	
	public function database_error($result){
            echo pg_result_error($result);
            exit;
	}

	/* Ferme la connexion au serveur MySQL associée à l'identifiant $hcon
	   Retourne TRUE en cas de succès, FALSE sinon */
	public function close() {
		return pg_close($this->link);
	}

	/**
         * Sélectionne la base de données $db
         * --> Sans objet pour mysqli. 
         */
        
	public function selectDb($db) {
            return mysqli_select_db($this->link, $bd);
	}

	/**
         *  Envoie la requête SQL $req pour son execution
         * parametrer $many à true pour bcp de resultats attendus:
         * si $many est true, ne pas oublier de libérer la requete par
         * $db->free_result($result)
         */
	public function execQuery($req, $many=false) {
           
            return pg_query($this->link, $req);
	}

    /* Retourne un tableau énulméré qui correspond à la ligne demandée, ou FALSE si il ne reste plus de ligne
	   Chaque appel suivant retourne la ligne suivante dans le résultat, ou FALSE si il n'y a plus de ligne disponible */
	public function fetchRow($result) {
            return pg_fetch_row($result);
	}
	
	public function numRows($result) {
            return pg_num_rows($result);
	}
        
    public function fetchField($result){
        return mysqli_fetch_fields($result);
    }

    public function fetchAssoc($result){
        return pg_fetch_assoc($result);
    }

    /**
     * fetch_array()
     * @param type $result
     * @return type
     * Retourne un tableau associatif par clé qui correspond à la ligne demandée, 
     * Chaque appel suivant retourne la ligne suivante dans le résultat, 
     * ou FALSE si il n'y a plus de ligne disponible
     */
	public function fetchArray($result) {
		return pg_fetch_assoc($result);
	}
	
	public function escapeString($link, $donnee){
		return pg_escape_string($donnee);
	}
        
        public function multipleQueries($req){
            $tabResults = array();
            if (pg_multi_query($this->link, $query)) {
                do {
                    /* Stockage du premier jeu de résultats */
                    if ($result = mysqli_use_result($link)) {
                        while ($row = mysqli_fetch_row($result)) {
                            $tabResults[] = $row;
                        }
                        mysqli_free_result($result);
                    }
                    /* Affichage d'une démarcation */
                    if (mysqli_more_results($this->link)) {
                        //printf("-----------------\n");
                    }
                } while (mysqli_next_result($this->link));
            }
            return $tabResults;
        }
        
        
        public function free_result($result){
            pg_freeresult($result);
        }
        
        /**
         * prepareExecute
         * Prepare puis execute des requêtes simples
         * On appelle une 1ère fois la fonction avec les champs $query rempli
         * --> Prepare
         * et une seconde fois avec les champs $stmt et $var completés et 
         * $query à null --> Execute
         * @param string $query genre "SELECT District FROM City WHERE Name=?"
         * @param type $var
         * @param type $stmt
         * @return type
         */
        public function prepareExecute($query=null,$var=null, $stmt=null){
            if(isset($query) && !is_null($query)){
                // mode preparation
                $stmt = pg_prepare($this->link, "my_query", $query);
                return $stmt;
            }else{
                $myrow = array();
                $result = pg_execute($this->link, "my_query", array("my_results_tab"));
                
                while ($myrow = $this->fetchArray($result)) {
                    
                }
                return $result;
            }
        }
}

?>
