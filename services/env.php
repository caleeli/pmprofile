<?php
$userId = '00000000000000000000000000000001';
$task = executeQuery('select PRO_UID, TAS_UID, PRO_ID from TASK where TAS_START="TRUE" LIMIT 1');
$processId = $task[1]['PRO_UID'];
$processIdNum = $task[1]['PRO_ID'];
$taskId = $task[1]['TAS_UID'];
$variables = array();
