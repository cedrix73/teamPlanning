<?php

require_once ABS_CLASSES_PATH.'DbInterface.php';

/**
 * @name DbAccess
 * @author cvonfelten
 * Classe créant une couche d'abstraction mysql et  gérant les accès à la BD
 */

class DbPdo implements DbInterface 
{

    /**
     * Etablit une connexion à un serveur de base de données et retourne un identifiant de connexion
     * L'identifiant est positif en cas de succès, FALSE sinon.
     * On pourrait se connecter avec un utilisateur lambda
     */
	public function connect($conInfos, $no_msg = 0)
	{
		$host = $conInfos['host'];
		$dbname = $conInfos['dbase'];
		$dbh=$dsn='';
		try {
			$dsn = "mysql:host=$host;dbname=$dbname";
			$dbh = new PDO($dsn, $conInfos['username'], $conInfos['password']);
			$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Failed: ' . $e->getMessage();
		}
		return $dbh;
	}
	

	/* Sélectionne la base de données $db
	   Retourne TRUE en cas de succès, FALSE sinon */
	public function selectDb($link, $db) {
		$retour = mysqli_select_db($link, $db);
		return $retour;
	}

	/* Envoie la requête SQL $req pour son execution
	   Retourne TRUE ou FALSE, pour indiquer le succès ou l'échec de la requête */
	public function execQuery($link, $query) {
		$rs = false;
		try {
			$rs = $link->prepare($query);
			$rs->execute();
		} catch (PDOException $e) {
			echo 'Problème lors de l\'execution de la requête: ' . $e->getMessage();
		}
		return $rs;
	}

    /* Retourne un tableau énulméré qui correspond à la ligne demandée, ou FALSE si il ne reste plus de ligne
	   Chaque appel suivant retourne la ligne suivante dans le résultat, ou FALSE si il n'y a plus de ligne disponible */
	public function fetchRow($resultSet) 
	{
		$results = false;
		try {
			$results = $resultSet->fetchAll(PDO::FETCH_NUM);
		} catch (PDOException $e) {
			echo 'Problème lors du traitement du résultat de la requête ' 
			   . ' en tableau numérique: ' . $e->getMessage();
		}
		return $results;
	}
	
	public function numRows($result) {
		return mysqli_num_rows($result);
	}
    /* Retourne un tableau associatif par clé qui correspond à la ligne demandée, ou FALSE si il ne reste plus de ligne
	   Chaque appel suivant retourne la ligne suivante dans le résultat, ou FALSE si il n'y a plus de ligne disponible */
	public function fetchArray($resultSet) 
	{
		$results = false;
		try {
			$results = $resultSet->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			echo 'Problème lors du traitement du résultat de la requête ' 
			   . ' en tableau associatif: ' . $e->getMessage();
		}
		return $results;
	}
	
	public function escapeString($link, $arg)
	{
		return $link->quote($arg);
	}

	public function getTableDatas($link, $query)
	{
		return $this->execQuery($link, $query);
	}
}

?>
