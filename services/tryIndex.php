<?php
session_write_close();
G::LoadClass('pmFunctions');
require 'env.php';
$filter = [
    "            APP_DELEGATION.DEL_THREAD_STATUS='OPEN' AND",
];
$order = [
    "APPLICATION.APP_NUMBER DESC",
];

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
echo $sql;
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
    echo "<tr>";
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
    echo "<td>".strtoupper($row['Message'])."</td>";
    echo "</tr>";
}
echo "</table>";


// poor or missing index -> kill system
// look for covening index opportunities
// good selectivity (unique isthe best one)
// miulti column index: order
// if DB grows -> take care distribution
// remove redundant indexes
