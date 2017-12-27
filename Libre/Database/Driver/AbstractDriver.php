<?php
/**
 * Inwebo
 */
namespace Libre\Database\Driver;

use Libre\Database\Results;

/**
 * Class AbstractDriver
 */
abstract class AbstractDriver implements DriverInterface
{
    //region Attibuts
    /**
     * @var \PDO
     */
    protected $driver;
    /**
     * @var \ArrayObject
     */
    protected $storedProcedures;
    /**
     * @var \ArrayObject
     */
    protected $namedStoredProcedures;
    //endregion

    /**
     * @return \PDO
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param \PDO $pdo
     */
    protected function setDriver($pdo)
    {
        $this->driver = $pdo;
    }

    /**
     * @return \ArrayObject
     */
    public function getStoredProcedures()
    {
        return $this->namedStoredProcedures;
    }

    /**
     * @param string $queryString
     *
     * @return \ArrayObject
     */
    public function getStoredProcedure($queryString)
    {
        $queryString = md5($queryString);

        if ($this->storedProcedures->offsetExists($queryString)) {
            return $this->storedProcedures->offsetGet($queryString);
        }
    }

    /**
     * @param string $queryString
     *
     * @return \PDOStatement
     */
    protected function setStoredProcedure($queryString)
    {
        $offset = md5($queryString);
        if ($this->storedProcedures->offsetExists($offset) === false) {
            $this->storedProcedures->offsetSet($offset, $this->getDriver()->prepare($queryString));
        }
    }

    /**
     * @return \ArrayObject
     */
    public function getNamedStoredProcedures()
    {
        return $this->namedStoredProcedures;
    }

    /**
     * @param string $name
     *
     * @return \PDOStatement
     */
    public function getNamedStoredProcedure($name)
    {
        if ($this->namedStoredProcedures->offsetExists($name)) {
            return $this->namedStoredProcedures->offsetGet($name);
        }
    }

    /**
     * @param string $name
     * @param string $queryString
     */
    public function setNamedStoredProcedure($name, $queryString)
    {
        if (!$this->namedStoredProcedures->offsetExists($name)) {
            $this->namedStoredProcedures->offsetSet($name, $this->getDriver()->prepare($queryString));
        }
    }

    public function __construct()
    {
        $this->namedStoredProcedures = new \ArrayObject();
        $this->storedProcedures = new \ArrayObject();
    }

    /**
     * @param string      $queryString Soit le nom d'une requete préparée soit une chaine de requête.
     * @param null|params $params
     *
     * @return Results
     */
    public function query($queryString, $params = null)
    {
        // Si est $queryString est la clef d'une procedure nommée
        if (!is_null($this->getNamedStoredProcedure($queryString))) {
            $pdoStatement = $this->getNamedStoredProcedure($queryString);
        } // Est une requete SQL préparée ?
        elseif (!is_null($this->getStoredProcedure($queryString))) {
            $pdoStatement = $this->getStoredProcedure($queryString);
        } else {
            $this->setStoredProcedure($queryString);
            $pdoStatement = $this->getStoredProcedure($queryString);
        }

        if (is_string($params) || is_int($params)) {
            $params = [$params];
        }

        (is_array($params)) ? $pdoStatement->execute($params) : $pdoStatement->execute();

        return new Results($pdoStatement);
    }

    /**
     * A surcharger pour chaques drivers
     *
     * @param string $table
     */
    public function getTableInfos($table)
    {
    }

    /**
     * A surcharger pour chaques drivers
     *
     * @param string $table
     */
    public function getColsName($table)
    {
    }

    /**
     * A surcharger pour chaques drivers
     *
     * @param string $table
     */
    public function getPrimaryKey($table)
    {
    }
}
