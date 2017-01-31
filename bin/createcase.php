<?php
require './fixTimeZone.php';
set_time_limit(-1);

class CasesGenerator
{
    const LIMIT_INSERT_ROWS = 200;

    public $proUid = '9388875235852ac30cbf367053242568';
    public $tasUid = '26948945158764ccf716d86083238937';
    public $usrUid = '00000000000000000000000000000001';
    protected $inserts = -1;
    public $appNumber = 0;
    public $f1;
    public $f2;
    public $f3;
    public $f4;
    public $f5;
    public $f6;

    public function __construct($workspace, $appNumber)
    {
        $this->workspace = $workspace;
        $this->appNumber = $appNumber;
        $this->filename1 = uniqid("createcase");
        $this->filename2 = uniqid("createcase");
        $this->filename3 = uniqid("createcase");
        $this->filename4 = uniqid("createcase");
        $this->filename5 = uniqid("createcase");
        $this->filename6 = uniqid("createcase");
        $this->filename7 = uniqid("createcase");
        $this->f1 = fopen($this->filename1, 'w');
        $this->f2 = fopen($this->filename2, 'w');
        $this->f3 = fopen($this->filename3, 'w');
        $this->f4 = fopen($this->filename4, 'w');
        $this->f5 = fopen($this->filename5, 'w');
        $this->f6 = fopen($this->filename6, 'w');
        $this->f7 = fopen($this->filename7, 'w');
    }

    public function __destruct()
    {
        fclose($this->f1);
        fclose($this->f2);
        fclose($this->f3);
        fclose($this->f4);
        fclose($this->f5);
        fclose($this->f6);
        fclose($this->f7);
        readfile($this->filename1);
        unlink($this->filename1);
        readfile($this->filename2);
        unlink($this->filename2);
        readfile($this->filename3);
        unlink($this->filename3);
        readfile($this->filename4);
        unlink($this->filename4);
        readfile($this->filename5);
        unlink($this->filename5);
        readfile($this->filename6);
        unlink($this->filename6);
        readfile($this->filename7);
        unlink($this->filename7);
    }

    /**
     * Generate random number
     *
     * @author Fernando Ontiveros Lira <fernando@colosa.com>
     * @access public
     * @return int
     */
    public function generateUniqueID()
    {
        do {
            $sUID = str_replace('.', '0', uniqid(rand(0, 999999999), true));
        } while (strlen($sUID) != 32);
        return $sUID;
        //return strtoupper(substr(uniqid(rand(0, 9), false),0,14));
    }

    function generateRandomString($length = 4)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function createCase($isLast)
    {
        $this->inserts++;
        if ($this->inserts >= static::LIMIT_INSERT_ROWS) $this->inserts = 0;
        $last = ($this->inserts + 1) >= static::LIMIT_INSERT_ROWS;
        $last = $last || $isLast;
        $this->appUid = $this->generateUniqueID();
        $this->appNumber++;
        $this->appPin = $this->generateRandomString(4);
        $appData = array(
            'SYS_LANG'     => 'en',
            'SYS_SKIN'     => 'neoclassic',
            'SYS_SYS'      => $this->workspace,
            'APPLICATION'  => $this->appUid,
            'PROCESS'      => $this->proUid,
            'TASK'         => $this->tasUid,
            'INDEX'        => 1,
            'USER_LOGGED'  => $this->usrUid,
            'USR_USERNAME' => '',
            'APP_NUMBER'   => $this->appNumber,
            'PIN'          => $this->appPin,
        );
        $application = array(
            $this->appUid,
            '#'.$this->appNumber,
            '',
            $this->appNumber,
            '',
            'COMPLETED',
            3,
            $this->proUid,
            '',
            '',
            'N',
            $this->usrUid,
            $this->usrUid,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            serialize($appData),
            md5($this->appPin),
            0,
            0,
            ''
        );
        $appDelegation = array(
            $this->appUid,
            1,
            $this->appNumber, //autoinc
            $this->appNumber,
            0,
            1,
            $this->proUid,
            $this->tasUid,
            $this->usrUid,
            'NORMAL',
            1,
            'CLOSED',
            '3',
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            0,
            0,
            0,
            0,
            0,
            0,
            '',
            0,
            1,
            2,
            1
        );
        $appThread = array(
            $this->appUid,
            1,
            0,
            'CLOSED',
            1,
            $this->appNumber,
            0
        );
        $content1 = array(
            'APP_TITLE',
            '',
            $this->appUid,
            'en',
            '#'.$this->appNumber
        );
        $content2 = array(
            'APP_DESCRIPTION',
            '',
            $this->appUid,
            'en',
            ''
        );
        $listParticipatedHistory = array(
            $this->appUid,
            1,
            $this->usrUid,
            $this->tasUid,
            $this->proUid,
            $this->appNumber,
            '#'.$this->appNumber,
            'Test',
            'Task 1',
            $this->usrUid,
            'admin',
            'Administrator',
            'admin',
            'admin',
            'Administrator',
            'admin',
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            '3'
        );
        $listParticipatedLast = array(
            $this->appUid,
            $this->usrUid,
            1,
            $this->tasUid,
            $this->proUid,
            $this->appNumber,
            '#'.$this->appNumber,
            'Test',
            'Task 1',
            'COMPLETED',
            $this->usrUid,
            'admin',
            'Administrator',
            'admin',
            'admin',
            'Administrator',
            'admin',
            'Task 1',
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            '3',
            'CLOSED'
        );
        $listCompleted = array(
            $this->appUid,
            $this->usrUid,
            $this->tasUid,
            $this->proUid,
            $this->appNumber,
            '#'.$this->appNumber,
            'Test',
            'Task 1',
            Date('Y-m-d H:i:s'),
            Date('Y-m-d H:i:s'),
            1,
            '',
            'admin',
            'Administrator',
            'admin'
        );

        //INSERT INTO `APPLICATION` (`APP_UID`, `APP_TITLE`, `APP_DESCRIPTION`, `APP_NUMBER`, `APP_PARENT`, `APP_STATUS`, `APP_STATUS_ID`, `PRO_UID`, `APP_PROC_STATUS`, `APP_PROC_CODE`, `APP_PARALLEL`, `APP_INIT_USER`, `APP_CUR_USER`, `APP_CREATE_DATE`, `APP_INIT_DATE`, `APP_FINISH_DATE`, `APP_UPDATE_DATE`, `APP_DATA`, `APP_PIN`, `APP_DURATION`, `APP_DELAY_DURATION`, `APP_DRIVE_FOLDER_UID`) VALUES
        if ($this->inserts == 0) {
            fwrite($this->f1,
                   "INSERT INTO `APPLICATION` (`APP_UID`, `APP_TITLE`, `APP_DESCRIPTION`, `APP_NUMBER`, `APP_PARENT`, `APP_STATUS`, `APP_STATUS_ID`, `PRO_UID`, `APP_PROC_STATUS`, `APP_PROC_CODE`, `APP_PARALLEL`, `APP_INIT_USER`, `APP_CUR_USER`, `APP_CREATE_DATE`, `APP_INIT_DATE`, `APP_FINISH_DATE`, `APP_UPDATE_DATE`, `APP_DATA`, `APP_PIN`, `APP_DURATION`, `APP_DELAY_DURATION`, `APP_DRIVE_FOLDER_UID`) VALUES\n");
        }
        fwrite($this->f1, '(');
        foreach ($application as $i => $val)
            fwrite($this->f1, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f1, ")".($last ? ';' : ',')."\n");
        //INSERT INTO `APP_DELEGATION` (`APP_UID`, `DEL_INDEX`, `DELEGATION_ID`, `APP_NUMBER`, `DEL_PREVIOUS`, `DEL_LAST_INDEX`, `PRO_UID`, `TAS_UID`, `USR_UID`, `DEL_TYPE`, `DEL_THREAD`, `DEL_THREAD_STATUS`, `DEL_PRIORITY`, `DEL_DELEGATE_DATE`, `DEL_INIT_DATE`, `DEL_FINISH_DATE`, `DEL_TASK_DUE_DATE`, `DEL_RISK_DATE`, `DEL_DURATION`, `DEL_QUEUE_DURATION`, `DEL_DELAY_DURATION`, `DEL_STARTED`, `DEL_FINISHED`, `DEL_DELAYED`, `DEL_DATA`, `APP_OVERDUE_PERCENTAGE`, `USR_ID`, `PRO_ID`, `TAS_ID`) VALUES
        if ($this->inserts == 0) {
            fwrite($this->f2,
                   "INSERT INTO `APP_DELEGATION` (`APP_UID`, `DEL_INDEX`, `DELEGATION_ID`, `APP_NUMBER`, `DEL_PREVIOUS`, `DEL_LAST_INDEX`, `PRO_UID`, `TAS_UID`, `USR_UID`, `DEL_TYPE`, `DEL_THREAD`, `DEL_THREAD_STATUS`, `DEL_PRIORITY`, `DEL_DELEGATE_DATE`, `DEL_INIT_DATE`, `DEL_FINISH_DATE`, `DEL_TASK_DUE_DATE`, `DEL_RISK_DATE`, `DEL_DURATION`, `DEL_QUEUE_DURATION`, `DEL_DELAY_DURATION`, `DEL_STARTED`, `DEL_FINISHED`, `DEL_DELAYED`, `DEL_DATA`, `APP_OVERDUE_PERCENTAGE`, `USR_ID`, `PRO_ID`, `TAS_ID`) VALUES\n");
        }
        fwrite($this->f2, '(');
        foreach ($appDelegation as $i => $val)
            fwrite($this->f2, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f2, ")".($last ? ';' : ',')."\n");
        //INSERT INTO `APP_THREAD` (`APP_UID`, `APP_THREAD_INDEX`, `APP_THREAD_PARENT`, `APP_THREAD_STATUS`, `DEL_INDEX`, `APP_NUMBER`, `DELEGATION_ID`) VALUES
        if ($this->inserts == 0) {
            fwrite($this->f3,
                   "INSERT INTO `APP_THREAD` (`APP_UID`, `APP_THREAD_INDEX`, `APP_THREAD_PARENT`, `APP_THREAD_STATUS`, `DEL_INDEX`, `APP_NUMBER`, `DELEGATION_ID`) VALUES\n");
        }
        fwrite($this->f3, '(');
        foreach ($appThread as $i => $val)
            fwrite($this->f3, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f3, ")".($last ? ';' : ',')."\n");
        //INSERT INTO `CONTENT` (`CON_CATEGORY`, `CON_PARENT`, `CON_ID`, `CON_LANG`, `CON_VALUE`) VALUES
        if ($this->inserts == 0) {
            fwrite($this->f4,
                   "INSERT INTO `CONTENT` (`CON_CATEGORY`, `CON_PARENT`, `CON_ID`, `CON_LANG`, `CON_VALUE`) VALUES\n");
        }
        fwrite($this->f4, '(');
        foreach ($content1 as $i => $val)
            fwrite($this->f4, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f4, "),\n");
        fwrite($this->f4, '(');
        foreach ($content2 as $i => $val)
            fwrite($this->f4, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f4, ")".($last ? ';' : ',')."\n");
        //INSERT INTO `LIST_PARTICIPATED_HISTORY` (`APP_UID`, `DEL_INDEX`, `USR_UID`, `TAS_UID`, `PRO_UID`, `APP_NUMBER`, `APP_TITLE`, `APP_PRO_TITLE`, `APP_TAS_TITLE`, `DEL_PREVIOUS_USR_UID`, `DEL_PREVIOUS_USR_USERNAME`, `DEL_PREVIOUS_USR_FIRSTNAME`, `DEL_PREVIOUS_USR_LASTNAME`, `DEL_CURRENT_USR_USERNAME`, `DEL_CURRENT_USR_FIRSTNAME`, `DEL_CURRENT_USR_LASTNAME`, `DEL_DELEGATE_DATE`, `DEL_INIT_DATE`, `DEL_DUE_DATE`, `DEL_PRIORITY`) VALUES
        if ($this->inserts == 0) {
            fwrite($this->f5,
                   "INSERT INTO `LIST_PARTICIPATED_HISTORY` (`APP_UID`, `DEL_INDEX`, `USR_UID`, `TAS_UID`, `PRO_UID`, `APP_NUMBER`, `APP_TITLE`, `APP_PRO_TITLE`, `APP_TAS_TITLE`, `DEL_PREVIOUS_USR_UID`, `DEL_PREVIOUS_USR_USERNAME`, `DEL_PREVIOUS_USR_FIRSTNAME`, `DEL_PREVIOUS_USR_LASTNAME`, `DEL_CURRENT_USR_USERNAME`, `DEL_CURRENT_USR_FIRSTNAME`, `DEL_CURRENT_USR_LASTNAME`, `DEL_DELEGATE_DATE`, `DEL_INIT_DATE`, `DEL_DUE_DATE`, `DEL_PRIORITY`) VALUES\n");
        }
        fwrite($this->f5, '(');
        foreach ($listParticipatedHistory as $i => $val)
            fwrite($this->f5, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f5, ")".($last ? ';' : ',')."\n");
        //INSERT INTO `LIST_PARTICIPATED_LAST` (`APP_UID`, `USR_UID`, `DEL_INDEX`, `TAS_UID`, `PRO_UID`, `APP_NUMBER`, `APP_TITLE`, `APP_PRO_TITLE`, `APP_TAS_TITLE`, `APP_STATUS`, `DEL_PREVIOUS_USR_UID`, `DEL_PREVIOUS_USR_USERNAME`, `DEL_PREVIOUS_USR_FIRSTNAME`, `DEL_PREVIOUS_USR_LASTNAME`, `DEL_CURRENT_USR_USERNAME`, `DEL_CURRENT_USR_FIRSTNAME`, `DEL_CURRENT_USR_LASTNAME`, `DEL_CURRENT_TAS_TITLE`, `DEL_DELEGATE_DATE`, `DEL_INIT_DATE`, `DEL_DUE_DATE`, `DEL_PRIORITY`, `DEL_THREAD_STATUS`) VALUES
        if ($this->inserts == 0) {
            fwrite($this->f6,
                   "INSERT INTO `LIST_PARTICIPATED_LAST` (`APP_UID`, `USR_UID`, `DEL_INDEX`, `TAS_UID`, `PRO_UID`, `APP_NUMBER`, `APP_TITLE`, `APP_PRO_TITLE`, `APP_TAS_TITLE`, `APP_STATUS`, `DEL_PREVIOUS_USR_UID`, `DEL_PREVIOUS_USR_USERNAME`, `DEL_PREVIOUS_USR_FIRSTNAME`, `DEL_PREVIOUS_USR_LASTNAME`, `DEL_CURRENT_USR_USERNAME`, `DEL_CURRENT_USR_FIRSTNAME`, `DEL_CURRENT_USR_LASTNAME`, `DEL_CURRENT_TAS_TITLE`, `DEL_DELEGATE_DATE`, `DEL_INIT_DATE`, `DEL_DUE_DATE`, `DEL_PRIORITY`, `DEL_THREAD_STATUS`) VALUES\n");
        }
        fwrite($this->f6, '(');
        foreach ($listParticipatedLast as $i => $val)
            fwrite($this->f6, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f6, ")".($last ? ';' : ',')."\n");
        //INSERT INTO `LIST_COMPLETED` (`APP_UID`, `USR_UID`, `TAS_UID`, `PRO_UID`, `APP_NUMBER`, `APP_TITLE`, `APP_PRO_TITLE`, `APP_TAS_TITLE`, `APP_CREATE_DATE`, `APP_FINISH_DATE`, `DEL_INDEX`, `DEL_PREVIOUS_USR_UID`, `DEL_CURRENT_USR_USERNAME`, `DEL_CURRENT_USR_FIRSTNAME`, `DEL_CURRENT_USR_LASTNAME`) VALUES
        if ($this->inserts == 0) {
            fwrite($this->f7,
                   "INSERT INTO `LIST_COMPLETED` (`APP_UID`, `USR_UID`, `TAS_UID`, `PRO_UID`, `APP_NUMBER`, `APP_TITLE`, `APP_PRO_TITLE`, `APP_TAS_TITLE`, `APP_CREATE_DATE`, `APP_FINISH_DATE`, `DEL_INDEX`, `DEL_PREVIOUS_USR_UID`, `DEL_CURRENT_USR_USERNAME`, `DEL_CURRENT_USR_FIRSTNAME`, `DEL_CURRENT_USR_LASTNAME`) VALUES\n");
        }
        fwrite($this->f7, '(');
        foreach ($listCompleted as $i => $val)
            fwrite($this->f7, ($i === 0 ? '' : ',').$this->escape($val));
        fwrite($this->f7, ")".($last ? ';' : ',')."\n");
    }

    function escape($value)
    {
        if (is_string($value)) {
            return '"'.addslashes($value).'"';
        } else {
            return $value;
        }
    }
}

$cg = new CasesGenerator($argv[1], $argv[2]);
for ($i = 0, $l = $argv[3] * 1; $i < $l; $i++) {
    $cg->createCase($i === $l-1);
}
