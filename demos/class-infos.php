<?php

namespace Libre {

    use Libre\Autoloader\ClassInfos;
    use Libre\Http\Authentification\ByPublicKey;
    include_once 'header.php';

    try {
        $classInfos = new ClassInfos(ByPublicKey::class);
        var_dump($classInfos->getClass());
        var_dump($classInfos->getVendor(2));
        var_dump($classInfos->getClassName());
        var_dump($classInfos->isNamespaced());
        var_dump($classInfos->toAbsolute());
        var_dump($classInfos->toArray());
        var_dump($classInfos->trim());
        var_dump($classInfos->toPSR0('../'));
        var_dump($classInfos->isLoaded());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}