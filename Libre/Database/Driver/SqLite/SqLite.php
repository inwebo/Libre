<?php
namespace Libre\Database\Driver {

    use Libre\Database\Driver;

    class SqliteDriverException extends \Exception
    {
    }

    class SqLite extends BaseDriver implements IDriver
    {

        const COLS_NAME = "name";
        const COLS_TYPE = "type";
        const COLS_NULLABLE = "notnull";
        const COLS_DEFAULT = "dflt_value";
        const COLS_PRIMARY_KEY = "pk";
        const COLS_PRIMARY_VALUE = "1";

        /**
         * @var string
         */
        protected $_dbFile;
        protected $_dsn;
        protected $_toMemory;
        protected $_version;


        public function __construct($dbFile, $toMemory = false, $version = 3)
        {
            parent::__construct();
            try {
                $this->_dbFile = $dbFile;
                $this->_toMemory = $toMemory;
                $this->_version = $version;
                $this->_dsn = $this->prepareDSN();
                $this->_driver = ($this->_toMemory) ? new \PDO($this->_dsn, null, null, array(\PDO::ATTR_PERSISTENT => true)) : new \PDO($this->_dsn);
                $this->_driver->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->isValidDataBaseFile();
            } catch (\Exception $e) {
                throw $e;
            }
            return $this->_driver;
        }


        public function isValidDataBaseFile()
        {
            $dir = dirname(realpath($this->_dbFile)) . \DIRECTORY_SEPARATOR;
            if (!is_writable($dir)) {
                throw new SqliteDriverException('Database file\'s root folder : ' . $dir . ' must be writable');
            }
        }

        protected function prepareDSN()
        {
            $dsn = "sqlite";
            switch ($this->_version) {
                default:
                case 3:
                    $dsn .= ':';
                    break;

                case 2:
                    $dsn .= "2:";
                    break;
            }

            $dsn .= ($this->_toMemory) ? ':memory:' : $this->_dbFile;
            return $dsn;
        }

        public function getTableInfos($table)
        {
            $table = explode('\\', $table);
            $table = $table[count($table) - 1];
            $statement = $this->_driver->query('PRAGMA table_info(' . $table . ');');
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            return $statement->fetchAll();
        }

        public function getColsName($table)
        {
            $buffer = array();
            $cols = $this->getTableInfos($table);
            foreach ($cols as $col) {
                $buffer[] = $col[self::COLS_NAME];
            }

            return $buffer;
        }

        public function getPrimaryKey($table)
        {
            $cols = $this->getTableInfos($table);
            foreach ($cols as $col) {
                foreach ($col as $k => $v) {
                    if ($k === self::COLS_PRIMARY_KEY && $v === self::COLS_PRIMARY_VALUE) {
                        return $col[self::COLS_NAME];
                    }
                }
            }
        }

    }
}