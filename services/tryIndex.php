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
    $t = microtime(true);
    ob_start();
    include(realpath("../engine/methods/cases/proxyCasesList.php"));
    ob_end_clean();
    $t = microtime(true) - $t;
    return [$t,$con_ro->lastQuery];
}
//
$aSort = ['APP_NUMBER', 'APP_TITLE', 'APP_PRO_TITLE', 'APP_TAS_TITLE', /*'APP_CURRENT_USER',*/ 'APP_UPDATE_DATE', 'DEL_DELEGATE_DATE', 'DEL_TASK_DUE_DATE', 'APP_STATUS_LABEL'];
$aSearch = [null, 'e'];
$aProcess = [null, 2];
$aCatagory = [null, '451007284589106bcb93dd4032868430'];
$aFilterStatus = [null,1,2,3,4];
$aUser = [null, 1];
$aDateFrom = [null, '1996-02-09T00:00:00'];
$aDateTo = [null, '2018-02-09T00:00:00'];

$f = fopen("./css/indexes2.html", "w");
foreach($aSort as $sort)
foreach($aSearch as $search)
foreach($aProcess as $process)
foreach($aCatagory as $category)
foreach($aFilterStatus as $filterStatus)
foreach($aUser as $user)
foreach($aDateFrom as $dateFrom)
foreach($aDateTo as $dateTo) {
    fwrite($f,"<hr><div style='background-color:lightyellow;font-weight:bold;'>");
    fwrite($f,json_encode(array_combine(['$sort', '$search', '$process', '$category', '$filterStatus', '$user', '$dateFrom', '$dateTo'],
        [$sort, $search, $process, $category, $filterStatus, $user, $dateFrom, $dateTo])));
    fwrite($f,"</div>");
    list($t, $sql) = getAdvancedSearchQuery($sort, $search, $process, $category, $filterStatus, $user, $dateFrom, $dateTo);
    fwrite($f,"<hr><div style='background-color:lightred;font-weight:bold;'><h1>".$t."</h1></div><hr>");
    fwrite($f,"<hr><div style='background-color:lightgreen;font-weight:bold;'>".$sql."</div><hr>");
    analize($sql);
}
fclose($f);
readfile("./css/indexes2.html");

function analize($sql) {
    global $f;
        executeQuery("SET GLOBAL query_cache_size = 0");
        fwrite($f,"<table border='1'>");
            fwrite($f,"<tr>");
            fwrite($f,"<th>table</th>");
            fwrite($f,"<th>possible_keys</td>");
            fwrite($f,"<th>key</th>");
            fwrite($f,"<th>rows</th>");
            fwrite($f,"<th>joined</th>");
            fwrite($f,"</tr>");
        foreach(executeQuery("EXPLAIN EXTENDED ".$sql) as $row) {
            fwrite($f,"<tr style='".(!$row['key']?'background-color:red;color white;':'')."'>");
            fwrite($f,"<td>".$row['table']."</td>");
            fwrite($f,"<td>".$row['possible_keys']."</td>");
            fwrite($f,"<td>".$row['key']."</td>");
            fwrite($f,"<td>".$row['rows']."</td>");
            fwrite($f,"<td>".($row['rows']*$row['filtered']/100)."</td>");
            fwrite($f,"</tr>");
        }
        fwrite($f, "<table border='1'>");
        foreach(executeQuery("SHOW WARNINGS") as $row) {
            fwrite($f,"<tr>");
            fwrite($f,"<th>".$row['Level']." (".$row['Level'].")</th>");
            fwrite($f,"</tr>");
            fwrite($f,"<tr>");
            fwrite($f,"<td>".strtoupper(str_replace('`'.DB_NAME.'`.','',$row['Message']))."</td>");
            fwrite($f,"</tr>");
        }
        fwrite($f, "</table>");
}

