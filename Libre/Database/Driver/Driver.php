<?php

namespace Libre\Database {

    use Libre\Database\Driver\BaseDriver;

    abstract class Driver extends BaseDriver{

        public function query($query, $params = array() ) {
            $pdoStatement = $this->_driver->prepare($query);
            if( isset($this->_toObject) ) {
                // Init constructor params.
                $reflection = new \ReflectionMethod($this->_toObject, '__construct');
                $parameters = $reflection->getParameters();
                $pdoStatement->setFetchMode(\PDO::FETCH_CLASS, $this->_toObject,$parameters);
            }
            try {
                (!is_null($params) && is_array($params)) ?
                    $pdoStatement->execute($params) :
                    $pdoStatement->execute();
            }
            catch(\Exception $e) {
                throw $e;
            }
            return new Results($pdoStatement);
        }
    }
}