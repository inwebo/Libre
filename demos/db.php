<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Database\Driver\MySql;
    use Libre\Database\Driver\SqLite;

    use Libre\Database\Entity;

    try{

        $mySql       = array(
            'localhost',
            'Tests',
            'root',
            'root'
        );

        //$sqlite2     = './assets/db/data.sqlite3';
        $querySelect =  'SELECT * FROM tests' ;
        $sql = new MySql(
            $mySql[0],
            $mySql[1],
            $mySql[2],
            $mySql[3]
        );

        //var_dump($sql->getTableInfos('tests'));
        //var_dump($sql->getColsName('tests'));
        //var_dump($sql->getPrimaryKey('tests'));

        //$sql->toStdClass();
        $statement = $sql->query( $querySelect );

        //var_dump( $statement->count() );
        //var_dump( $statement->first() );
        //var_dump( $statement->all() );
        //var_dump( $statement->last() );

        //var_dump($sql->toStdClass()->query($querySelect)->getOffset(1));

        class Mock extends Entity {

            static protected $_configuration;

            public $foo;
            public $id;
            public $name;
            public $false;

            public function setName($name)
            {
                $this->name = $name;
            }
        }

        $sqlite      = './assets/db/valid/data.sqlite3';
        $sqlite = new SQLite($sqlite);

        Mock::setConfiguration($sqlite,'id', 'tests');


        $mock = Mock::load(1);
        var_dump($mock);
        $mock->setName('yo');
        var_dump($mock);
        $mock->save();
        $m = new Mock();
        $m->name = 'hello';
        $m->save();
        //var_dump(Mock::getShortName());
        //var_dump(Mock::getModelClassName());
        //var_dump($sqlite->getColsName('Tests'));
        //var_dump($sqlite->getTableInfos('Tests'));
        //var_dump($sqlite->getPrimaryKey('Tests'));
        //var_dump(Mock::getModelClassName());
        //echo $mock->name;
        //$mock->name = 'test2';
        //$mock->foo = 'bar';
        //$mock->save();
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}


