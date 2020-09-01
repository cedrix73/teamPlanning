<?php 
declare(strict_types=1);

//namespace TeamPlanning\Classes\Db\Repository;

interface DbInterface
{
    public function connect($conInfos, $no_msg = 0);

    public function selectDb($link, $db);

    public function execQuery($link, $query);

    public function fetchRow($result);

    public function numRows($result);

    public function fetchArray($result);

    public function escapeString($link, $arg);

    public function getTableDatas($link, $query);

}