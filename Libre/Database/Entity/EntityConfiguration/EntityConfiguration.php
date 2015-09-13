<?php
namespace Libre\Database\Entity {

    use Libre\Database\Driver\IDriver;

    class EntityConfiguration {

        /**
         * @var IDriver
         */
        protected $driver;
        /**
         * @var string
         */
        protected $primaryKey;
        /**
         * @var string
         */
        protected $table;
        /**
         * @var string
         */
        protected $tableDescription;

        /**
         * @return IDriver
         */
        public function getDriver()
        {
            return $this->driver;
        }

        /**
         * @param IDriver $driver
         */
        public function setDriver($driver)
        {
            $this->driver = $driver;
        }

        /**
         * @return string
         */
        public function getPrimaryKey()
        {
            return $this->primaryKey;
        }

        /**
         * @param string $primaryKey
         */
        public function setPrimaryKey($primaryKey)
        {
            $this->primaryKey = $primaryKey;
        }

        /**
         * @return string
         */
        public function getTable()
        {
            return $this->table;
        }

        /**
         * @param string $table
         */
        public function setTable($table)
        {
            $this->table = $table;
        }

        /**
         * @return string
         */
        public function getTableDescription()
        {
            return $this->tableDescription;
        }

        /**
         * @param string $tableDescription
         */
        public function setTableDescription($tableDescription)
        {
            $this->tableDescription = $tableDescription;
        }

        public function __construct(IDriver $iDriver, $primaryKey, $table, $tableDesc) {
            $this->driver           = $iDriver;
            $this->primaryKey       = $primaryKey;
            $this->table            = $table;
            $this->tableDescription = $tableDesc;
        }

        public function intersectObj($obj) {
            $cols   = array_keys($this->tableDescription);
            $values = array_keys((array)$obj);
            $bind   = array_intersect($cols,$values);
            //var_dump($cols,$values,$bind);
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