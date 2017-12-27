<?php
/**
 * Inwebo
 */

namespace Libre\Database\Driver;

/**
 * Class IDriver
 */
interface DriverInterface
{
    /**
     * @param string $table
     *
     * @return mixed
     */
    public function getTableInfos($table);

    /**
     * @param string $table
     *
     * @return mixed
     */
    public function getColsName($table);

    /**
     * @param string $table
     *
     * @return mixed
     */
    public function getPrimaryKey($table);

    /**
     * @return DriverInterface
     */
    public function getDriver();

    /**
     * @param string $name
     * @param string $query
     *
     * @return mixed
     */
    public function setNamedStoredProcedure($name, $query);

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getNamedStoredProcedure($name);

    /**
     * @param string      $queryString
     * @param null|string $params
     *
     * @return mixed
     */
    public function query($queryString, $params = null);
}
