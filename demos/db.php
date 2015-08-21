<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Database\Driver\MySql;
    use Libre\Database\Driver\SqLite;

    try{

        $mySql       = array(
            'localhost',
            'Tests',
            'root',
            'root'
        );
        $sqlite      = './assets/db/valid/data.sqlite3';
        $sqlite2      = './assets/db/data.sqlite3';
        $querySelect =  'SELECT * FROM tests' ;
        $sql = new MySql(
            $mySql[0],
            $mySql[1],
            $mySql[2],
            $mySql[3]
        );

        //$sql->toStdClass();
        $statement = $sql->query( $querySelect );

        var_dump( $statement->count() );
        var_dump( $statement->first() );
        //var_dump( $statement->all() );
        var_dump( $statement->last() );

        var_dump($sql->toStdClass()->query($querySelect)->getOffset(1));

        $sqlite = new SQLite($sqlite);

        $infos = $sqlite->toStdClass()->query($querySelect)->first();
        var_dump($infos);

        class Mock extends \Libre\Database\Entity {

        }

        Mock::binder($sqlite,'id', 'tests');
        $mock = Mock::load(1);
        echo $mock->name;
        $mock->name = 'test';
        //$mock->save();
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}


