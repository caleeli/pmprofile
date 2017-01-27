<?php
/**
 * class.profile.php
 *  
 */

  class profileClass extends PMPlugin {
    function __construct() {
      set_include_path(
        PATH_PLUGINS . 'profile' . PATH_SEPARATOR .
        get_include_path()
      );
    }

    function setup()
    {
    }

    function getFieldsForPageSetup()
    {
    }

    function updateFieldsForPageSetup()
    {
    }

  }
?>