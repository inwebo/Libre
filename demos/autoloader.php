<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Autoloader\ClassInfos;
    use Libre\Autoloader\BaseDir;
    use Libre\Autoloader\Handler;
    use Libre\Http\Url;
    use Libre\Modules;

    try{
/*
        echo '<h6>IsNamespaced class === true</h6>';
        $class = new ClassInfos("\\test");
        var_dump(assert($class->isNamespaced()===true));
        echo '<hr>';

        echo '<h6>IsNamespaced class === false</h6>';
        $class = new ClassInfos("test");
        var_dump(assert($class->isNamespaced()===false));
        echo '<hr>';

        echo '<h6>Vendor is null</h6>';
        $class = new ClassInfos("test");
        var_dump(assert($class->getVendor()===null));
        echo '<hr>';

        echo '<h6>Vendor is Libre</h6>';
        $class = new ClassInfos("Libre\\test");
        var_dump(assert($class->getVendor()==="Libre"));
        echo '<hr>';

        echo '<h6>Class === \\test</h6>';
        $class = new ClassInfos("test");
        var_dump(assert($class->toAbsolute()==="\\test"));
        echo '<hr>';

        echo '<h6>Class toArray() === array(test)</h6>';
        $class = new ClassInfos("\\test");
        var_dump(assert($class->toArray()===array('test')));
        echo '<hr>';

        echo '<h6>Class toArray() === array(Libre, test)</h6>';
        $class = new ClassInfos("\\Libre\\test");
        var_dump(assert($class->toArray()===array('Libre','test')));
        echo '<hr>';

        echo '<h6>Class name === test</h6>';
        $class = new ClassInfos("\\Libre\\test");
        var_dump(assert($class->getClassName()==='test'));
        echo '<hr>';
*/
        // Decorator
        $d = new BaseDir('../Libre');
        Handler::addDecorator($d);
/*
        $class = new ClassInfos('Libre\\Modules\\Foo');
        var_dump(assert($class->getVendor(2)==="Libre\\Modules"));

        $e = new BaseDir('/home/inwebo/www/libre',2);
        Handler::addDecorator($e);
        Handler::handle("Libre\\Modules\\Foo");
*/
        spl_autoload_register("\\Libre\\Autoloader\\Handler::handle");

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}



