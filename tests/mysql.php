<?php
namespace Libre\Database\Driver\tests\units {

    require_once '/home/inwebo/www/Libre/tests//home/inwebo/www/Libre/tests/atoum.phar';
    include('../Libre/database/autoload.php');

    use mageekguy\atoum;
    use Libre\Database;

    class Mock extends Database\Entity {
        public $id;
        public $name;
        public static function build($name) {
            $o = new self();
            $o->name = $name;
            return $o;
        }
    }

    class MySQL extends  atoum\test {

        protected $_config =  array(
            'localhost',
            'Tests',
            'root',
            'root'
        );

        protected $_driver;

        protected $_query = 'SELECT * FROM tests';

        private function getMysql() {
            return new Database\Driver\MySql(
                $this->_config[0],
                $this->_config[1],
                $this->_config[2],
                $this->_config[3]
            );
        }

        public function testException() {
            $this->exception(
                function() {
                    new Database\Driver\MySql('','','','');
                }
            );
        }

        public function testConnexion() {
            $this->object($this->getMysql())->isInstanceOf('\LibreMVC\DataBase\Driver\MySql');
        }

        public function testIsResults() {
            $driver = $this->getMysql();
            $results = $driver->query($this->_query);
            $this->object($results)->isInstanceOf('\LibreMVC\DataBase\Results');
            $this->array($results->first())->hasKey(0)->hasKey('id');
            $driver->toAssoc();
            $results = $driver->query($this->_query)->first();
            $this->array($results)->notHasKey(0);
        }

        public function testInitEntity() {
            $this->exception(
                function(){
                    Mock::load(5);
                }
            );
        }

        public function testBindEntityDefault() {
            /**
             * Recheche la table par default Mocks
             */
            $this->exception(
                function(){
                    Mock::binder($this->getMysql());
                    Mock::load(1);
                }
            );
        }

        public function testBoundEntity() {

            $default = "test";
            $updated = "test updated";

            Mock::binder($this->getMysql(),null,'tests');

            $entity = Mock::load(1);
            $this->string($entity->name)->isEqualTo($default);

            $entity->name = $updated;
            $entity->save();

            $entity = Mock::load(1);
            $this->string($entity->name)->isEqualTo($updated);

            $entity->name = $default;
            $entity->save();

        }

        public function testDeleteEntity() {
            Mock::binder($this->getMysql(),null,'tests');
            $name = 'new name';
            $o = Mock::build($name);
            $o->save();
            $msql = $this->getMysql();
            $msql->toStdClass();
            $r = $msql->query('SELECT * FROM tests')->last();
            $this->string($r->name)->isEqualTo($name);
            $n = Mock::load($r->id);
            $n->delete();
        }

        public function testIsObject() {
            Mock::binder($this->getMysql(),null,'tests');
            $mysql = $this->getMysql();
            $mysql->toObject('Libre\Database\Driver\tests\units\Mock');
            $r = $mysql->query('SELECT * FROM tests')->last();
            $this->object($r)->isInstanceOf('Libre\Database\Driver\tests\units\Mock');
        }

    }

}