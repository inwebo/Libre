<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 12/03/15
 * Time: 17:26
 */

namespace Libre\Mvc\Controller\Traits;


use Libre\Database\Driver\IDriver;

trait DataBase {

    protected $_driver;

    /**
     * @return IDriver
     */
    public function getDbDriver()
    {
        return $this->_driver;
    }

    /**
     * @param mixed $driver
     */
    public function setDbDriver($driver)
    {
        $this->_driver = $driver;
    }

}