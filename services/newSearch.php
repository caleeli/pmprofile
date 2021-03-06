<?php
$sqlBase = "SELECT
    NOW(),
    APP_NUMBER,
    APP_TITLE,
    APP_PRO_TITLE,
    APP_TAS_TITLE,
    CONCAT(DEL_CURRENT_USR_LASTNAME,' ',DEL_CURRENT_USR_FIRSTNAME, ' (', DEL_CURRENT_USR_USERNAME, ')') as DEL_CURRENT_USER,
    (SELECT APP_UPDATE_DATE FROM APPLICATION WHERE APPLICATION.APP_UID=LIST_PARTICIPATED_HISTORY.APP_UID) as APP_UPDATE_DATE,
    DEL_DELEGATE_DATE,
    DEL_DUE_DATE,
    (SELECT APP_STATUS FROM APPLICATION WHERE APPLICATION.APP_UID=LIST_PARTICIPATED_HISTORY.APP_UID) as STATUS
FROM  `LIST_PARTICIPATED_HISTORY`
WHERE
  PRO_UID = '9388875235852ac30cbf367053242568'
  AND USR_UID IN('00000000000000000000000000000001')
  AND (
      APP_TITLE LIKE '%Test%' OR
      APP_PRO_TITLE LIKE '%Test%' OR
      APP_TAS_TITLE LIKE '%Test%' OR
      DEL_CURRENT_USR_USERNAME LIKE '%Test%' OR
      DEL_CURRENT_USR_FIRSTNAME LIKE '%Test%' OR
      DEL_CURRENT_USR_LASTNAME LIKE '%Test%'
  )
  AND DEL_DELEGATE_DATE >= '2017-01-01 00:00:00'
  AND DEL_DELEGATE_DATE <= '2017-12-01 00:00:00'
ORDER BY APP_NUMBER ASC
LIMIT 31, 30
";
$sql = empty($_GET['sql']) ? $sqlBase : $_GET['sql'];
?>
<form method="GET">
    <textarea name="sql" cols="160" rows="25"><?= $sql ?></textarea>
    <button>submit</button>
</form>
<pre>
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
//SET SESSION query_cache_type=0;
//,NOW()

    $t = microtime(true);
    $res = doQuery($sql, 30);
    $time = (microtime(true) - $t) * 1000;

    var_dump($time, $res);
