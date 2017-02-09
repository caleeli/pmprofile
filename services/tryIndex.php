<?php
session_write_close();
G::LoadClass('pmFunctions');
require 'env.php';
require 'Log.php';
set_time_limit(0);

// CONFIG AND CATCH WORKFLOW RO CONNECTION
$name='workflow_ro';
//$configuration['datasources'][$name]
$c=Propel::getConfiguration();
$c['datasources']['workflow_ro']=$c['datasources']['workflow'];
if(DB_NAME==='wf_mellow') {
    //$c['datasources']['workflow_ro']['connection'] = 'mysql://wf_5852ab279ab22:j01F9JPC*%f.s$Y@localhost/wf_mellow?encoding=utf8';
} else {
    $c['datasources']['workflow_ro']['connection'] = "mysql://wf_workflow:qpf2ygjdh5nm@benchmark-db-2.processmaker.net/wf_workflow?encoding=utf8";
}
//Creole::registerDriver( '*', 'creole.contrib.DebugConnection' );
Propel::initConfiguration($c);
$logger = Log::singleton( 'file', __FILE__.'.log');
///////////////////
/**
 *
 * @param type $sort APP_NUMBER, APP_TITLE, APP_PRO_TITLE, APP_TAS_TITLE, APP_CURRENT_USER, APP_UPDATE_DATE, DEL_DELEGATE_DATE, DEL_TASK_DUE_DATE, APP_STATUS_LABEL
 * @param type $search "Search text"
 * @param type $process 2
 * @param type $category 451007284589106bcb93dd4032868430
 * @param type $filterStatus null,1,2,3,4
 * @param type $user 1
 * @param type $dateFrom 1996-02-09T00:00:00
 * @param type $dateTo 2020-02-10T00:00:00
 * @return type
 */
function getAdvancedSearchQuery($sort=null, $search=null, $process=null, $category=null, $filterStatus=null, $user=null, $dateFrom=null, $dateTo=null) {
    $con_ro = Propel::getConnection( 'workflow_ro' );
    //$callback = isset( $_REQUEST["callback"] ) ? $_REQUEST["callback"] : "stcCallback1001";
    $_REQUEST["dir"] = 'DESC';
    $_REQUEST["sort"] = $sort;
    //$start = isset( $_REQUEST["start"] ) ? $_REQUEST["start"] : "0";
    //$limit = isset( $_REQUEST["limit"] ) ? $_REQUEST["limit"] : "25";
    //$_REQUEST["filter"] = $filter;
    $_REQUEST["process"] = $process;
    $_REQUEST["category"] = $category;
    //$_REQUEST["status"] = $status;
    $_REQUEST["filterStatus"] = $filterStatus;
    $_REQUEST["user"] = $user;
    $_REQUEST["search"] = $search;
    //$action = isset( $_GET["action"] ) ? $_GET["action"] : (isset( $_REQUEST["action"] ) ? $_REQUEST["action"] : "todo");
    //$type = isset( $_GET["type"] ) ? $_GET["type"] : (isset( $_REQUEST["type"] ) ? $_REQUEST["type"] : "extjs");
    $_REQUEST["dateFrom"] = $dateFrom;
    $_REQUEST["dateTo"] = $dateTo;
    //$first = isset( $_REQUEST["first"] ) ? true :false;
    //$openApplicationUid = (isset($_REQUEST['openApplicationUid']) && $_REQUEST['openApplicationUid'] != '')?
    //$_REQUEST['openApplicationUid'] : null;

    ob_start();
    include(realpath("../engine/methods/cases/proxyCasesList.php"));
    ob_end_clean();
    return $con_ro->lastQuery;
}
//
$aSort = ['APP_NUMBER', 'APP_TITLE', 'APP_PRO_TITLE', 'APP_TAS_TITLE', 'APP_CURRENT_USER', 'APP_UPDATE_DATE', 'DEL_DELEGATE_DATE', 'DEL_TASK_DUE_DATE', 'APP_STATUS_LABEL'];
$aSearch = [null, 'e'];
$aProcess = [null, 2];
$aCatagory = [null, '451007284589106bcb93dd4032868430'];
$aFilterStatus = [null,1,2,3,4];
$aUser = [null, 1];
$aDateFrom = [null, '1996-02-09T00:00:00'];
$aDateTo = [null, '2018-02-09T00:00:00'];

foreach($aSort as $sort)
foreach($aSearch as $search)
foreach($aProcess as $process)
foreach($aCatagory as $category)
foreach($aFilterStatus as $filterStatus)
foreach($aUser as $user)
foreach($aDateFrom as $dateFrom)
foreach($aDateTo as $dateTo) {
    echo "<hr><div style='background-color:lightyellow;font-weight:bold;'>";
    echo json_encode(array_combine(['$sort', '$search', '$process', '$category', '$filterStatus', '$user', '$dateFrom', '$dateTo'],
        [$sort, $search, $process, $category, $filterStatus, $user, $dateFrom, $dateTo]));
    echo "</div>";
    $sql = getAdvancedSearchQuery($sort, $search, $process, $category, $filterStatus, $user, $dateFrom, $dateTo);
    echo "<hr><div style='background-color:lightgreen;font-weight:bold;'>".$sql."</div><hr>";
    analize($sql);
}

function analize($sql) {
        executeQuery("SET GLOBAL query_cache_size = 0");
        echo "<table border='1'>";
            echo "<tr>";
            echo "<th>table</th>";
            echo "<th>possible_keys</td>";
            echo "<th>key</th>";
            echo "<th>rows</th>";
            echo "<th>joined</th>";
            echo "</tr>";
        foreach(executeQuery("EXPLAIN EXTENDED ".$sql) as $row) {
            echo "<tr style='".(!$row['key']?'background-color:red;color white;':'')."'>";
            echo "<td>".$row['table']."</td>";
            echo "<td>".$row['possible_keys']."</td>";
            echo "<td>".$row['key']."</td>";
            echo "<td>".$row['rows']."</td>";
            echo "<td>".($row['rows']*$row['filtered']/100)."</td>";
            echo "</tr>";
        }
        echo "<table border='1'>";
        foreach(executeQuery("SHOW WARNINGS") as $row) {
            echo "<tr>";
            echo "<th>".$row['Level']." (".$row['Level'].")</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>".strtoupper(str_replace('`'.DB_NAME.'`.','',$row['Message']))."</td>";
            echo "</tr>";
        }
        echo "</table>";
}

return;

$filters = [
    "            APP_DELEGATION.DEL_THREAD_STATUS='OPEN' AND",
    "            APP_DELEGATION.PRO_UID = '9388875235852ac30cbf367053242568' AND",
    "            USERS.USR_UID = '00000000000000000000000000000001' AND",
    "            (APPLICATION.APP_TITLE LIKE '%%' OR PROCESS.PRO_TITLE LIKE '%%' OR TASK.TAS_TITLE LIKE '%%') AND",
    "            (USERS.USR_LASTNAME LIKE '%%' OR USERS.USR_FIRSTNAME LIKE '%%' OR USERS.USR_USERNAME LIKE '%%') AND",
    "            APP_DELEGATION.DEL_DELEGATE_DATE >= '1990-01-01 00:00:00' AND APP_DELEGATION.DEL_DELEGATE_DATE <= '2020-12-31 00:00:00' AND",
];
$orders = [
    "APPLICATION.APP_NUMBER DESC",
    "APPLICATION.APP_TITLE DESC",
    "PROCESS.PRO_TITLE DESC",
    "TASK.TAS_TITLE DESC",
    "CONCAT(USERS.USR_LASTNAME,' ',USERS.USR_FIRSTNAME) DESC",
    "APPLICATION.APP_UPDATE_DATE DESC",
    "APP_DELEGATION.DEL_DELEGATE_DATE DESC",
    "APP_DELEGATION.DEL_TASK_DUE_DATE DESC",
    "APP_DELEGATION.DEL_THREAD_STATUS DESC",
];
foreach($filters as $f) {
    foreach($orders as $o) {
        $filter = [$f];
        $order = [$o];
        $sql = "SELECT
                            STRAIGHT_JOIN APPLICATION.APP_NUMBER,
                            APPLICATION.APP_UID,
                            APPLICATION.APP_STATUS,
                            APPLICATION.APP_STATUS AS APP_STATUS_LABEL,
                            APPLICATION.PRO_UID,
                            APPLICATION.APP_CREATE_DATE,
                            APPLICATION.APP_FINISH_DATE,
                            APPLICATION.APP_UPDATE_DATE,
                            APPLICATION.APP_TITLE,
                            APP_DELEGATION.USR_UID,
                            APP_DELEGATION.TAS_UID,
                            APP_DELEGATION.DEL_INDEX,
                            APP_DELEGATION.DEL_LAST_INDEX,
                            APP_DELEGATION.DEL_DELEGATE_DATE,
                            APP_DELEGATION.DEL_INIT_DATE,
                            APP_DELEGATION.DEL_FINISH_DATE,
                            APP_DELEGATION.DEL_TASK_DUE_DATE,
                            APP_DELEGATION.DEL_RISK_DATE,
                            APP_DELEGATION.DEL_THREAD_STATUS,
                            APP_DELEGATION.DEL_PRIORITY,
                            APP_DELEGATION.DEL_DURATION,
                            APP_DELEGATION.DEL_QUEUE_DURATION,
                            APP_DELEGATION.DEL_STARTED,
                            APP_DELEGATION.DEL_DELAY_DURATION,
                            APP_DELEGATION.DEL_FINISHED,
                            APP_DELEGATION.DEL_DELAYED,
                            APP_DELEGATION.DEL_DELAY_DURATION,
                            TASK.TAS_TITLE AS APP_TAS_TITLE,
                            USERS.USR_LASTNAME,
                            USERS.USR_FIRSTNAME,
                            USERS.USR_USERNAME,
                            PROCESS.PRO_TITLE AS APP_PRO_TITLE
                        FROM APP_DELEGATION
                 LEFT JOIN APPLICATION ON (APP_DELEGATION.APP_NUMBER=APPLICATION.APP_NUMBER) LEFT JOIN TASK ON (APP_DELEGATION.TAS_ID=TASK.TAS_ID) LEFT JOIN USERS ON (APP_DELEGATION.USR_ID=USERS.USR_ID) LEFT JOIN PROCESS ON (APP_DELEGATION.PRO_ID=PROCESS.PRO_ID)
                 WHERE
        ".implode("\n",$filter)."
                    TASK.TAS_TYPE NOT IN ('WEBENTRYEVENT','END-MESSAGE-EVENT','START-MESSAGE-EVENT','INTERMEDIATE-THROW-MESSAGE-EVENT','INTERMEDIATE-CATCH-MESSAGE-EVENT') AND
                    APP_DELEGATION.DEL_LAST_INDEX = 1
                 ORDER BY ".implode(", ", $order)." LIMIT 25
        ";
        echo "<hr><div style='background-color:lightgreen;font-weight:bold;'>".$sql."</div><hr>";
        echo "<div style='background-color:lightyellow;font-weight:bold;'>".json_encode($filter).','.json_encode($order)."</div>";
        executeQuery("SET GLOBAL query_cache_size = 0");
        echo "<table border='1'>";
            echo "<tr>";
            echo "<th>table</th>";
            echo "<th>possible_keys</td>";
            echo "<th>key</th>";
            echo "<th>rows</th>";
            echo "<th>joined</th>";
            echo "</tr>";
        foreach(executeQuery("EXPLAIN EXTENDED ".$sql) as $row) {
            echo "<tr style='".(!$row['key']?'background-color:red;color white;':'')."'>";
            echo "<td>".$row['table']."</td>";
            echo "<td>".$row['possible_keys']."</td>";
            echo "<td>".$row['key']."</td>";
            echo "<td>".$row['rows']."</td>";
            echo "<td>".($row['rows']*$row['filtered']/100)."</td>";
            echo "</tr>";
        }
        echo "<table border='1'>";
        foreach(executeQuery("SHOW WARNINGS") as $row) {
            echo "<tr>";
            echo "<th>".$row['Level']." (".$row['Level'].")</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>".strtoupper(str_replace('`'.DB_NAME.'`.','',$row['Message']))."</td>";
            echo "</tr>";
        }
        echo "</table>";


        // poor or missing index -> kill system
        // look for covening index opportunities
        // good selectivity (unique isthe best one)
        // miulti column index: order
        // if DB grows -> take care distribution
        // remove redundant indexes
    }
}
