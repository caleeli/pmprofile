<?php
session_write_close();
G::LoadClass('pmFunctions');
require 'env.php';

function doQuery($SqlStatement, $count, $DBConnectionUID = 'workflow')
{
    $con = Propel::getConnection($DBConnectionUID);
    $rs = $con->executeQuery($SqlStatement);

    $result = Array();
    for ($i = 0; $i < $count; $i++) {
        if (!$rs->next()) break;
        $result[] = $rs->getRow();
    }
    return $result;
}
$sql = "SELECT *
FROM  `LIST_PARTICIPATED_HISTORY`
";

$t = microtime(true);
$res = doQuery($sql, 30);
$time = (microtime(true)-$t)*1000;

var_dump($time, $res);