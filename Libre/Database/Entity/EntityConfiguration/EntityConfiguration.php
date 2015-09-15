<?php
namespace Libre\Database\Entity {

    use Libre\Database\Driver\IDriver;

    class EntityConfiguration {

        /**
         * @var IDriver
         */
        protected $_driver;
        /**
         * @var string
         */
        protected $_primaryKey;
        /**
         * @var string
         */
        protected $_table;
        /**
         * @var string
         */
        protected $_tableDescription;

        /**
         * @return IDriver
         */
        public function getDriver()
        {
            return $this->_driver;
        }

        /**
         * @param IDriver $_driver
         */
        protected function setDriver(IDriver $_driver)
        {
            $this->_driver = $_driver;
        }

        /**
         * @return string
         */
        public function getPrimaryKey()
        {
            return $this->_primaryKey;
        }

        /**
         * @param string $_primaryKey
         */
        protected function setPrimaryKey($_primaryKey)
        {
            $this->_primaryKey = $_primaryKey;
        }

        /**
         * @return string
         */
        public function getTable()
        {
            return $this->_table;
        }

        /**
         * @param string $_table
         */
        public function setTable($_table)
        {
            $this->_table = $_table;
        }

        /**
         * @return string
         */
        public function getTableDescription()
        {
            return $this->_tableDescription;
        }

        /**
         * @param string $_tableDescription
         */
        public function setTableDescription($_tableDescription)
        {
            $this->_tableDescription = $_tableDescription;
        }

        public function __construct(IDriver $iDriver, $primaryKey, $table, $tableDesc) {
            $this->setDriver($iDriver);
            $this->setPrimaryKey($primaryKey);
            $this->setTable($table);
            $this->setTableDescription($tableDesc);
        }

        public function intersectObj($obj) {
            $cols   = array_keys($this->getTableDescription());
            $values = array_keys((array)$obj);
            $bind   = array_intersect($cols,$values);
            return $bind;
        }

        public function aggregateCols($array) {
            return '('. implode(',', $array) .')';
        }

        static public function getTokens($int, $token = '?') {
            return array_fill(0, $int, $token);
        }

        static public function toCols(&$var,$key,$char) {
            $var = $char . trim($var, $char) . $char;
        }

        public function toColsName($array) {
            $callback = __CLASS__.'::toCols';
            array_walk($array,$callback,'`');
            return $array;
        }

        public static function toUpdate($associativeArray) {
            $buffer = "";
            $i = 0;
            $loops = count((array)$associativeArray);
            foreach ($associativeArray as $key => $value) {
                $i++;
                $buffer .= $value . '=? ';
                $buffer .= ($i!==$loops) ? ", " : '';
            }
            return $buffer;
        }

    }

}