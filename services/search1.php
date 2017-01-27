<?php
session_write_close();
G::LoadClass('pmFunctions');

$sql = "SELECT APP_CACHE_VIEW.APP_UID,
APP_CACHE_VIEW.DEL_INDEX,
APP_CACHE_VIEW.DEL_LAST_INDEX,
APP_CACHE_VIEW.APP_NUMBER,
APP_CACHE_VIEW.APP_STATUS,
APP_CACHE_VIEW.USR_UID,
APP_CACHE_VIEW.PREVIOUS_USR_UID,
APP_CACHE_VIEW.TAS_UID,
APP_CACHE_VIEW.PRO_UID,
APP_CACHE_VIEW.DEL_DELEGATE_DATE,
APP_CACHE_VIEW.DEL_INIT_DATE, APP_CACHE_VIEW.DEL_FINISH_DATE, APP_CACHE_VIEW.DEL_TASK_DUE_DATE, APP_CACHE_VIEW.DEL_RISK_DATE, APP_CACHE_VIEW.DEL_THREAD_STATUS, APP_CACHE_VIEW.APP_THREAD_STATUS, APP_CACHE_VIEW.APP_TITLE, APP_CACHE_VIEW.APP_PRO_TITLE, APP_CACHE_VIEW.APP_TAS_TITLE, APP_CACHE_VIEW.APP_CURRENT_USER, APP_CACHE_VIEW.APP_DEL_PREVIOUS_USER, APP_CACHE_VIEW.DEL_PRIORITY, APP_CACHE_VIEW.DEL_DURATION, APP_CACHE_VIEW.DEL_QUEUE_DURATION, APP_CACHE_VIEW.DEL_DELAY_DURATION, APP_CACHE_VIEW.DEL_STARTED, APP_CACHE_VIEW.DEL_FINISHED, APP_CACHE_VIEW.DEL_DELAYED, APP_CACHE_VIEW.APP_CREATE_DATE, APP_CACHE_VIEW.APP_FINISH_DATE, APP_CACHE_VIEW.APP_UPDATE_DATE, APP_CACHE_VIEW.APP_OVERDUE_PERCENTAGE, APP_CACHE_VIEW.DEL_INIT_DATE, APP_CACHE_VIEW.TAS_UID, APP_CACHE_VIEW.PRO_UID,
CU.USR_UID AS USR_UID,
CU.USR_FIRSTNAME AS USR_FIRSTNAME,
CU.USR_LASTNAME AS USR_LASTNAME,
CU.USR_USERNAME AS USR_USERNAME,
APPDELCR.APP_TAS_TITLE AS APPDELCR_APP_TAS_TITLE,
USRCR.USR_UID AS USRCR_USR_UID,
USRCR.USR_FIRSTNAME AS USRCR_USR_FIRSTNAME,
USRCR.USR_LASTNAME AS USRCR_USR_LASTNAME,
USRCR.USR_USERNAME AS USRCR_USR_USERNAME
FROM APP_CACHE_VIEW
LEFT JOIN TASK ON (APP_CACHE_VIEW.TAS_UID=TASK.TAS_UID)
LEFT JOIN USERS CU ON (APP_CACHE_VIEW.USR_UID=CU.USR_UID)
LEFT JOIN APP_CACHE_VIEW APPDELCR ON (APP_CACHE_VIEW.APP_UID=APPDELCR.APP_UID AND APPDELCR.DEL_LAST_INDEX=1)
LEFT JOIN USERS USRCR ON (APPDELCR.USR_UID=USRCR.USR_UID)
WHERE (((((((APP_CACHE_VIEW.APP_STATUS='TO_DO' AND APP_CACHE_VIEW.DEL_FINISH_DATE IS NULL )
AND APP_CACHE_VIEW.APP_THREAD_STATUS='OPEN')
AND APP_CACHE_VIEW.DEL_THREAD_STATUS='OPEN')
 OR ((APP_CACHE_VIEW.APP_STATUS='DRAFT'
 AND APP_CACHE_VIEW.APP_THREAD_STATUS='OPEN')
 AND APP_CACHE_VIEW.DEL_THREAD_STATUS='OPEN'))
 OR (APP_CACHE_VIEW.APP_STATUS IN ('DRAFT','TO_DO')
 AND APP_CACHE_VIEW.APP_UID IN
 (SELECT DISTINCT APP_DELAY.APP_UID
 FROM   APP_DELAY
 WHERE  APP_DELAY.APP_UID = APP_CACHE_VIEW.APP_UID
 AND                       APP_DELAY.APP_DEL_INDEX = APP_CACHE_VIEW.DEL_INDEX
 AND                       (APP_DELAY.APP_DISABLE_ACTION_USER
 IS NULL OR APP_DELAY.APP_DISABLE_ACTION_USER = '0')
 AND                       APP_DELAY.APP_DELAY_UID
 IS NOT NULL
 AND                       APP_DELAY.APP_TYPE = 'PAUSE')))
 OR ((APP_CACHE_VIEW.APP_STATUS='CANCELLED'
 AND APP_CACHE_VIEW.DEL_THREAD_STATUS='CLOSED')
 AND APP_CACHE_VIEW.DEL_LAST_INDEX=1))
 OR (APP_CACHE_VIEW.APP_STATUS='COMPLETED'
 AND APP_CACHE_VIEW.DEL_LAST_INDEX=1))
 AND TASK.TAS_TYPE NOT IN ('WEBENTRYEVENT','END-MESSAGE-EVENT','START-MESSAGE-EVENT','INTERMEDIATE-THROW-MESSAGE-EVENT','INTERMEDIATE-CATCH-MESSAGE-EVENT')
 GROUP BY APP_CACHE_VIEW.APP_UID,APP_CACHE_VIEW.DEL_INDEX,APPDELCR.APP_TAS_TITLE, USRCR.USR_UID
 ORDER BY APP_CACHE_VIEW.APP_NUMBER DESC LIMIT 25
";
$t = microtime(true);
executeQuery($sql);
echo (microtime(true)-$t)*1000;
