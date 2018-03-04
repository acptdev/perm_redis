<?php
/*
Plugin Name: Reedistribuição e permuta
Plugin URI: http://exemplo.org/o-meu-plugin
Description: Um plugin para o controle de redistribuição e permuta de funcionários
Version: 1.0
Author: Givailson de Souza Neves
Author URI: http://acptdev.com.br
License: GPLv2
*/

define( 'PERM_REDIS', '1.0' );
define( 'PERM_REDIS_MENU_POSITION', 5 );
define( 'PERM_REDIS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

add_filter('init', array('PermRedis','inicializar'));
register_activation_hook( __FILE__, array( 'PermRedis', 'pluginActivation' ) );
register_deactivation_hook( __FILE__, array( 'PermRedis', 'pluginDeactivation' ) );
add_action("admin_menu", array("PermRedisAdmin","adicionarMenu"));

require_once( PERM_REDIS__PLUGIN_DIR . 'class.perm_redis_model.php' );
require_once( PERM_REDIS__PLUGIN_DIR . 'class.perm_redis_services.php' );
require_once( PERM_REDIS__PLUGIN_DIR . 'class.perm_redis_dao.php' );
require_once( PERM_REDIS__PLUGIN_DIR . 'class.perm_redis.php' );
require_once( PERM_REDIS__PLUGIN_DIR . 'class.perm_redis_admin.php' );


?>