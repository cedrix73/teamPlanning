<?php

/**
 * Classe gérant les ressources
 *
 * @author CV170C7N
 */
class Ressource {
    
    protected $dbaccess;
    protected $site;
    protected $service;
    protected $domaine;
    protected $tabRessources;
    protected $requeteRessources;
    protected $requeteJointures;
    
    public function __construct($dbaccess) 
    {
        $this->dbaccess = $dbaccess;
        $this->siteId = false;
        $this->departementLibelle = false;
        $this->serviceLibelle = false;
        $this->tabRessources =  array();
        $this->requeteSelect = "SELECT ressource.id as ressource_id, ressource.nom, ressource.prenom, "
        . " site.libelle, departement.libelle, service.libelle, fonction "
        . " FROM ressource ";
        $this->requeteJointures = " INNER JOIN service on ressource.service_id = service.id " 
                               . " INNER JOIN departement on service.departement_id = departement.id " 
                               . " INNER JOIN site on departement.site_id = site.id ";
        
        
    }
    
    /**
     * GetRessourcesBySelection
     * Sort les ressources en Ajax en fonction 
     * des valeur (chaine) sélectionnées à partir ds
     * combobox site, département et service du formulaire
     * 
     * @param int    $siteId             
     * @param string $departementLibelle 
     * @param string $serviceLibelle     
     * 
     * @return array
     */
    public function getRessourcesBySelection($siteId = null, $departementLibelle = '', $serviceLibelle = ''){
        $requete = $this->requeteSelect . $this->requeteJointures;
        $requete . " WHERE dateSortie IS NULL";
        // Traitement sites
        if($site != null && $site!='Tous*'){
            $this->siteId = $siteId;
            $requete.= " AND site.id = '" . $this->siteId ."'";
        }

        // Traitement departements
        if($departementLibelle != null && $departementLibelle != 'Tous*'){
            $this->departementLibelle = $departementLibelle;
            $requete.= " AND departement.libelle = '" . $this->departementLibelle ."'";
        }
        
        // Traitement services
        if($serviceLibelle != null && $serviceLibelle != 'Tous*'){
            $this->serviceLibelle = $serviceLibelle;
            $requete.= " AND service.libelle = '" . $this->serviceLibelle ."'";
        } 
        
        $requete.= " ORDER BY ressource.nom ";
	    $rs = $this->dbaccess->execQuery($requete);
        $results=$this->dbaccess->fetchRow($rs);
        
        foreach ($results as $ligne) {
            $id = $ligne['ressource_id'];
            unset($ligne['ressource_id']);
            $this->tabRessources[$id]=$ligne;
        }
        return $this->tabRessources;
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
    public function getDepartementsBySite($siteId = null){
        $rs = false;
        $requete = "SELECT DISTINCT departement.libelle "
                . " FROM departement ";
                
        $tabDepartements = array();
        if($site != ""){
            $requete.=  " INNER JOIN site on departement.site_id = site.id "
                      . " WHERE site.id = '" . $siteId ."'" 
                      . " ORDER BY site.id";
        }

	    $rs = $this->dbaccess->execQuery($requete);
        $results=$this->dbaccess->fetchRow($rs);
        $i=0;
        foreach ($results as $ligne) {
            $tabDepartements[$i]=$ligne[1];
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
    public function getServicesByDepartement($siteId = null, $departementLibelle = '')
    {
        $rs = false;
        $requete = "SELECT DISTINCT service.libelle "
                . " FROM service ";
        $requete .= $this->requeteJointures;
                
        $tabServices = array();

        if($site != null && $site!='Tous*'){
            $this->siteId = $siteId;
            $requete.= " AND site.id = '" . $this->siteId ."'";
        }

        if($departementLibelle != ''){

            $requete.=  " WHERE departement.libelle = '" . $departementLibelle ."'";
        }
        $requete.= " ORDER BY departement.id";

	    $rs = $this->dbaccess->execQuery($requete);
        $results=$this->dbaccess->fetchRow($rs);
        $i=0;
        foreach ($results as $ligne) {
            $tabServices[$i]=$ligne[1];
            $i++;
        }
        return $tabServices;
    }
    
    
    /**
     * GetRessourceById
     * Retourne l'id, le nom, prénom d'une ressource
     * ainsi que son affectation (site, département et service)
     * 
     * @param int $idRessource 
     * @return array
     */
    public function getRessourceById($idRessource)
    {
        $ressource = array();
        $requete = $this->requeteSelect . $this->requeteJointures;
        $requete .= " WHERE dateSortie IS NULL"
                 . " AND id = " . $idRessource;
        $rs = $this->dbaccess->execQuery($requete);
        $ressource=$this->dbaccess->fetchArray($rs);
        return $ressource;
    }

    /**
     * GetForm
     * Traite tous les champs de la table Ressource
     * pour retourner un formulaire
     * 
     */
    public function getForm()
    {
        $results = $this->dbaccess->getTableDatas('ressource');
        return $results;



    }
}
