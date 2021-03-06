<?php
    class PermRedisAdmin{
        
        public static function adicionarMenu()
        {
            add_menu_page(
                'Permuta & Redistribuição', 
                'Permuta & Redistribuição', 
                'manage_options',
                'perm_redis',
                array("PermRedisAdmin","abaOpcoes"),
                "dashicons-groups",
                PERM_REDIS_MENU_POSITION
            );

        }
        public static function abaOpcoes()
        {            
            $templateVars['{PATH_PLUGIN}'] = plugins_url("perm_redis");
            $templateVars['{PATH_SERVICE}'] = home_url();

            $admTpl = file_get_contents(PERM_REDIS__PLUGIN_DIR."view/admin_tpl.html");

            echo strtr($admTpl,$templateVars);
        }
    }
?>