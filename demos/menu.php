<?php
namespace Libre {

    use Libre\Helpers\Menu;
    use Libre\Helpers\Menu\Item;

    include_once 'header.php';

    try {

        $menu = new Menu('main',null,null);

        $menu->addItem('Bonjour','/admin',1);
        $menu->addItem('Le monde', 'http://www.inwebo.dev', 0);

        var_dump($menu->getQueue()->count());
        $generator = $menu->generator();
        foreach($generator as $g)
        {
            echo '<a href="'.$g->getUri().'">'.$g->getLabel().'</a><br>';
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}