<?php
/**
 *  php ./tests/atoum.phar -d ../tests/
 */
namespace Libre\Database\Driver\tests\units {

    require_once '/home/inwebo/www/Libre/tests//home/inwebo/www/Libre/tests/atoum.phar';
    include_once('../Libre/database/autoload.php');

    use mageekguy\atoum;
    use Libre\Database;

    class Sqlite extends  atoum\test {

        protected $_config =  './assets/db/data.sqlite3';
        protected $_configWritable =  './assets/db/valid/data.sqlite3';

        public function testException() {
            $this->exception(
                function() {
                    new Database\Driver\Sqlite('./none/arf.test');
                }
            );
        }

        public function testExceptionWritable() {
            $this->exception(
                function() {
                    new Database\Driver\Sqlite($this->_config);
                }
            );
        }

    }

}