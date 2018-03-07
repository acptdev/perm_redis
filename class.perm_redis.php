<?php
    class PermRedis{
        
        private static $services;
        private static $front;

        public static function inicializar()
        {
            PermRedis::$services = new PermRedisService();   
            PermRedis::$front = new PermRedisFront();         
        }
        
        public static function pluginActivation()
        {
        }

        public static function pluginDeactivation()
        {
            
        }

    }
?>