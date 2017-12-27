<?php

namespace Libre\Database\Driver;

use Libre\Database\Driver;

class SqliteDriverException extends \Exception
{
}

class SqLite extends AbstractDriver implements DriverInterface
{
    const COLS_NAME = "name";
    const COLS_TYPE = "type";
    const COLS_NULLABLE = "notnull";
    const COLS_DEFAULT = "dflt_value";
    const COLS_PRIMARY_KEY = "pk";
    const COLS_PRIMARY_VALUE = "1";

    /** @var string */
    protected $dbFile;
    /** @var string */
    protected $dsn;
    /** @var bool */
    protected $toMemory;
    /** @var int */
    protected $version;

    /**
     * SqLite constructor.
     *
     * @param string $dbFile
     * @param bool   $toMemory
     * @param int    $version
     *
     * @throws \Exception
     */
    public function __construct($dbFile, $toMemory = false, $version = 3)
    {
        parent::__construct();
        try {
            $this->dbFile = $dbFile;
            $this->toMemory = $toMemory;
            $this->version = $version;
            $this->dsn = $this->prepareDSN();
            $this->driver = ($this->toMemory) ? new \PDO(
                $this->dsn, null, null, [\PDO::ATTR_PERSISTENT => true]
            ) : new \PDO($this->dsn);
            $this->driver->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->isValidDataBaseFile();
        } catch (\Exception $e) {
            throw $e;
        }

        return $this->driver;
    }

    /**
     * @throws SqliteDriverException
     */
    public function isValidDataBaseFile()
    {
        $dir = dirname(realpath($this->dbFile)).\DIRECTORY_SEPARATOR;
        if (!is_writable($dir)) {
            throw new SqliteDriverException(sprintf('Database file\'s root folder : %s must be writable', $dir));
        }
    }

    /**
     * @return string
     */
    protected function prepareDSN()
    {
        $dsn = "sqlite";
        switch ($this->version) {
            default:
            case 3:
                $dsn .= ':';
                break;

            case 2:
                $dsn .= "2:";
                break;
        }

        $dsn .= ($this->toMemory) ? ':memory:' : $this->dbFile;

        return $dsn;
    }

    /**
     * @param string $table
     *
     * @return array
     */
    public function getTableInfos($table)
    {
        $table = explode('\\', $table);
        $table = $table[count($table) - 1];
        $statement = $this->driver->query('PRAGMA table_info('.$table.');');
        $statement->setFetchMode(\PDO::FETCH_ASSOC);

        return $statement->fetchAll();
    }

    /**
     * @param string $table
     *
     * @return array
     */
    public function getColsName($table)
    {
        $buffer = [];
        $cols = $this->getTableInfos($table);
        foreach ($cols as $col) {
            $buffer[] = $col[self::COLS_NAME];
        }

        return $buffer;
    }

    /**
     * @param string $table
     *
     * @return mixed
     */
    public function getPrimaryKey($table)
    {
        $cols = $this->getTableInfos($table);
        foreach ($cols as $col) {
            foreach ($col as $k => $v) {
                if (self::COLS_PRIMARY_KEY === $k && self::COLS_PRIMARY_VALUE === $v) {
                    return $col[self::COLS_NAME];
                }
            }
        }
    }
}
