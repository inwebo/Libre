<?php
try {
    /**
     * php -d phar.readonly=0 make.php
     */
    @unlink('./phar/Libre.phar');
    $phar = new Phar('./phar/Libre.phar');
    $phar->setDefaultStub('index.php');
    $phar->buildFromDirectory('./Libre/', '/^.+\..+$/');
} catch (Exception $e) {
    echo $e->getMessage();
}
