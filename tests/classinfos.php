<?php
namespace Libre\Autoloader\tests\units {

    require_once 'atoum.phar';
    require_once __DIR__ .'/../Libre/index.php';

    use mageekguy\atoum;

    class ClassInfos extends atoum\test {

        public function testNamespacedTrue() {
            $class = new \Libre\Autoloader\ClassInfos("\\test");
            $this->boolean($class->isNamespaced())->isTrue();
        }

        public function testNamespacedFalse() {
            $class = new \Libre\Autoloader\ClassInfos("test");
            $this->boolean($class->isNamespaced())->isFalse();
        }

        public function testVendorIsNull() {
            $class = new \Libre\Autoloader\ClassInfos("test");
            $this->variable($class->getVendor())->isNull();
        }

        public function testVendorDefault() {
            $class = new \Libre\Autoloader\ClassInfos("Libre\\Foo\\Bar");
            $this->variable($class->getVendor(1))->isEqualTo('Libre');
        }
        public function testAbsoluteVendorDefault() {
            $class = new \Libre\Autoloader\ClassInfos("\\Libre\\Foo\\Bar");
            $this->variable($class->getVendor(1))->isEqualTo('Libre');
        }

        public function testVendorOffset() {
            $class = new \Libre\Autoloader\ClassInfos("\\Libre\\Foo\\Bar");
            $this->variable($class->getVendor(2))->isEqualTo('Libre\\Foo');
        }

        public function testToAbsolute() {
            $class = new \Libre\Autoloader\ClassInfos("Libre\\Foo\\Bar");
            $this->variable($class->toAbsolute())->isEqualTo('\\Libre\\Foo\\Bar');
        }

        public function testGetClassName() {
            $class = new \Libre\Autoloader\ClassInfos("Libre\\Foo\\Bar");
            $this->variable($class->getClassName())->isEqualTo('Bar');
        }

        public function testToArray() {
            $class = new \Libre\Autoloader\ClassInfos("Libre\\Foo\\Bar");
            $this->variable($class->toArray())->isEqualTo(array('Libre','Foo','Bar'));
        }

        public function testToPsr0() {
            $class = new \Libre\Autoloader\ClassInfos("Libre\\Foo\\Bar_Bar_Arf");
            $this->variable($class->toPSR0('./'))->isEqualTo('./Libre/Foo/Bar/Bar/Arf.php');
        }

        public function testIsLoaded() {
            $class = new \Libre\Autoloader\ClassInfos("Libre\\Foo\\Bar_Bar_Arf");
            $this->boolean($class->isLoaded())->isFalse();
        }

    }

}