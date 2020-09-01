<?php

/**
 * @name DbAccess
 * @author cvonfelten
 * Classe créant une couche d'abstraction mysql et  gérant les accès à la BD
 */
require_once realpath(dirname(__FILE__)).'/../config.php';
require_once realpath(dirname(__FILE__)).'/DbPdo.php';


//namespace base;
// fichier appelant: use base as DbAccess;
// \DbAccess\Mysqli->methode
class DbMySqli {

	/* Etablit une connexion à un serveur de base de données et retourne un identifiant de connexion
	   L'identifiant est positif en cas de succès, FALSE sinon.
	   On pourrait se connecter avec un utilisateur lambda
	   */
	public function connect($no_msg = 0)
	{
		$link = @mysqli_connect($this->host, $this->username, $this->password, $this->dbase, $this->port);
		if (mysqli_connect_errno())
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
	
	public function database_error(){
			echo mysqli_connect_error();
			exit;
	}

	/* Ferme la connexion au serveur MySQL associée à l'identifiant $hcon
	   Retourne TRUE en cas de succès, FALSE sinon */
	public function close() {
		return @mysqli_close($this->link);
	}

	/**
         * Sélectionne la base de données $db
         * --> Sans objet pour mysqli. 
         */
        
	public function select_db($db) {
            return mysqli_select_db($this->link, $bd);
	}

	/**
         *  Envoie la requête SQL $req pour son execution
         * parametrer $many à true pour bcp de resultats attendus:
         * si $many est true, ne pas oublier de libérer la requete par
         * $db->free_result($result)
         */
	public function execQuery($req, $many=false) {
            $param = MYSQLI_STORE_RESULT;
            if($many){
                $param = MYSQLI_USE_RESULT;
            }
            return mysqli_query($this->link, $req, $param);
	}

    /* Retourne un tableau énulméré qui correspond à la ligne demandée, ou FALSE si il ne reste plus de ligne
	   Chaque appel suivant retourne la ligne suivante dans le résultat, ou FALSE si il n'y a plus de ligne disponible */
	public function fetch_row($result) {
            return mysqli_fetch_row($result);
	}
	
	public function num_rows($result) {
            return mysqli_num_rows($result);
	}
        
        public function fetch_field($result){
            return mysqli_fetch_fields($result);
        }
        
        public function fetch_assoc($result){
            return mysqli_fetch_assoc($result);
        }
        
        /**
         * fetch_array()
         * @param type $result
         * @return type
         * Retourne un tableau associatif par clé qui correspond à la ligne demandée, 
         * Chaque appel suivant retourne la ligne suivante dans le résultat, 
         * ou FALSE si il n'y a plus de ligne disponible
         */
	public function fetch_array($result) {
		return mysqli_fetch_assoc($result);
	}
	
	public function escape_string($donnee){
		return mysqli_real_escape_string($donnee);
	}
        
        public function multiple_queries($req){
            $tabResults = array();
            if (mysqli_multi_query($this->link, $query)) {
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
            mysqli_free_result($result);
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
                $stmt = mysqli_prepare($this->link, $query);
                return $stmt;
            }else{
                $myrow = array();
                mysqli_stmt_bind_param($stmt, "s", $var);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result();
                
                while ($myrow = $this->fetchArray($result)) {

                    // use your $myrow array as you would with any other fetch
                    printf("%s is in district %s\n", $city, $myrow['district']);

                }
                return $myrow;
            }
        }
}

?>
