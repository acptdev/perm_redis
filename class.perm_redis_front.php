<?php 
  //referência http://www.wpexplorer.com/create-widget-plugin-wordpress/
  class PermRedisFront{  
        
    // Main constructor
    public function __construct() {
      add_shortcode(PERM_REDIS_FRONT_SHORT, array($this, 'makeForm'));
    }

    public function makeForm()
    {
      $templateVars['{PATH_PLUGIN}'] = plugins_url("perm_redis");
      $templateVars['{PATH_SERVICE}'] = home_url();
      $frontTPL = file_get_contents(PERM_REDIS__PLUGIN_DIR."view/front_tpl.html");
      return strtr($frontTPL,$templateVars);
    }

  }

?>
