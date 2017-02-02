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
WHERE
  PRO_UID IN (SELECT PRO_UID FROM PROCESS WHERE )
  AND USR_UID IN('00000000000000000000000000000001')
  AND (
      APP_TITLE LIKE '%Test%' OR
      APP_PRO_TITLE LIKE '%Test%' OR
      APP_TAS_TITLE LIKE '%Test%' OR
      DEL_CURRENT_USR_USERNAME LIKE '%Test%' OR
      DEL_CURRENT_USR_FIRSTNAME LIKE '%Test%' OR
      DEL_CURRENT_USR_LASTNAME LIKE '%Test%'
  )
LIMIT 0 , 30";

$t = microtime(true);
$res = doQuery($sql, 30);
$time = (microtime(true)-$t)*1000;

var_dump($time, $res);