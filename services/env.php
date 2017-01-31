<?php
$userId = '00000000000000000000000000000001';
$sql = 'select TASK.PRO_UID, TAS_UID, PRO_ID from TASK left join PROCESS on (TASK.PRO_UID=PROCESS.PRO_UID) where TAS_START="TRUE" ';
if(!empty($_GET['proUid'])) {
    $sql = "$sql and PROCESS.PRO_UID='".$_GET['proUid']."'";
}
$task = executeQuery("$sql LIMIT 1");
$processId = $task[1]['PRO_UID'];
$processIdNum = $task[1]['PRO_ID'];
$taskId = $task[1]['TAS_UID'];
$variables = array();
