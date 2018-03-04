<?php
    class PermRedis{
        
        private static $services;

        public static function inicializar()
        {
            PermRedis::$services = new PermRedisService();            
        }
        
        public static function pluginActivation()
        {
        }

        public static function pluginDeactivation()
        {
            
        }

    }
?>