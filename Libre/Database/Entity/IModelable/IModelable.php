<?php
namespace Libre\Database\Entity {

    use Libre\Database\Driver\IDriver;

    interface IModelable {
        static public function binder(IDriver $iDriver, $primaryKey = null, $tableName = null, $tableDesc = null);
        static public function load($id, $colName);
    }

    interface _IModelable {
        static public function binder(IDriver $iDriver, $primaryKey = null, $tableName = null, $tableDesc = null);
        static public function load($id, $colName);
        static public function delete();
        static public function update();
    }
}