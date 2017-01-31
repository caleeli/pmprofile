<?php
session_write_close();
G::LoadClass('pmFunctions');
require 'env.php';
set_time_limit(-1);
//print_r(executeQuery('select PRO_UID, TAS_UID from TASK where TAS_START="TRUE"'));
for ($i = 0, $l = empty($_GET['c']) ? 1 : $_GET['c'] * 1; $i < $l; $i++) {
    $newCaseId = PMFNewCase($processId, $userId, $taskId, $variables);
    $n = 1;//rand(1,4);
    switch ($n) {
	    case 1: //Todo
	    	PMFDerivateCase($newCaseId, 1);
	        break;
	    case 2: //Completed
	    	PMFDerivateCase($newCaseId, 1);
	    	PMFDerivateCase($newCaseId, 2);
	        break;
	    case 3: //Cancelled
	    	PMFDerivateCase($newCaseId, 1);
	    	PMFCancelCase($newCaseId, 2, $userId);
	        break;
	    default:
	    	break;
	}
}
