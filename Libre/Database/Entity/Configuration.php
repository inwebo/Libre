<?php
/**
 * Inwebo
 */
namespace Libre\Database\Entity;

use Libre\Database\Driver\DriverInterface;

/**
 * Class Configuration
 */
class Configuration
{

    /**
     * @var DriverInterface
     */
    protected $driver;
    /**
     * @var string La clef primaire courante de la table $_table
     */
    protected $primaryKey;
    /**
     * @var string Le nom de la table courante
     */
    protected $table;

    /**
     * @var array Nom des colonnes
     */
    protected $tableDescription;

    /**
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param DriverInterface $driver
     */
    protected function setDriver(DriverInterface $driver)
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
     * @param DriverInterface $iDriver
     * @param string          $primaryKey
     * @param string          $table
     */
    public function __construct(DriverInterface $iDriver, $primaryKey, $table)
    {
        $this->setDriver($iDriver);
        $this->setPrimaryKey($primaryKey);
        $this->setTable($table);
    }

    /**
     * @return string
     */
    public function getColsName()
    {
        return $this->getDriver()->getColsName($this->getTable());
    }

}
