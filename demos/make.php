<?php
try {
    /**
     * php -d phar.readonly=0 make.php
     */
    @unlink('../phar/Libre.phar');
    $phar = new Phar( '../phar/Libre.phar' );
    $phar->setDefaultStub('index.php');
//    var_dump($phar->getStub());
    //var_dump(is_dir("./../Html5DragAndDrop/"));
    $phar->buildFromDirectory('../Libre/', '/^.+\..+$/');
    copy('../phar/Libre.phar','../../Libre-Base/Libre.phar');

}
catch(Exception $e) {
    var_dump($e);
    echo $e->getMessage();
}
