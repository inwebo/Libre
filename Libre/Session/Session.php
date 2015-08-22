<?php

namespace Libre {
    /**
     * Class Session
     * @package Libre
     */
    class Session
    {

        /**
         * @var Session
         */
        static protected $_this;

        private function __construct(){}

        /**
         * Session courante
         * @return Session
         */
        static public function this()
        {
            if (!isset(static::$_this)) {
                $class = get_called_class();
                static::$_this = new $class();
            }
            return static::$_this;
        }

        /**
         * @param array $defaultValues Tableau associatif pour les valeurs par default de la session
         */
        static public function start($defaultValues = array())
        {
            self::this();
            if (intval(ini_get('session.auto_start')) === 0 && !isset($_SESSION)) {
                session_start();
            }
            elseif(!isset($_SESSION))
            {session_start();}
            foreach ($defaultValues as $k => $v) {
                $_SESSION[$k] = $v;
            }
        }

        static public function destroy()
        {
            session_destroy();
        }

        public function __set($key, $var)
        {
            $_SESSION[$key] = $var;
        }

        public function __get($key)
        {
            return $_SESSION[$key];
        }
    }

}