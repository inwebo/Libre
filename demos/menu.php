<?php
namespace Libre {

    use Libre\Helpers\Menu;
    use Libre\Helpers\Menu\Item;

    include_once 'header.php';

    try {

        $menu = new Menu(0,"main","http://www.test.fr");

        $menu->addItem('Bonjour', '/admin', 0);
        $menu->addItem('Le monde', '/test', 1);
        $menu->addItem('Le 5', '/arf', 5);
        $menu->addItem('Le 6', '/arf', 6);
        $menu->addItem('Le 7', '/arf', 7);
        $menu->addItem('Le 8', '/arf', 8);

        $menu2 = new Menu(1,"arf","http://www.2.test.fr");
        $menu2->addItem('Bonjour', '/admin', 0);
        $menu2->addItem('Le monde', '/test', 1);
        $menu2->addItem('Le 3', '/arf', 2);

        $menu->addMenu($menu2,4);

        function displayMenu(Menu\IMenu $menu)
        {
            $buffer = "<ul>";
            $generator = $menu->generator();
            foreach($generator as $g)
            {
                if($g instanceof Menu){
                    $buffer .= \Libre\displayMenu($g);
                }
                else {
                    $buffer .= '<li><a href="'.$menu->getBaseUrl() . $g->getUri().'">'.$g->getLabel().'</a><br></li>';
                }
            }
            $buffer .= "</ul>";
            return $buffer;
        }

        echo \Libre\displayMenu($menu);

    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}