<?php
namespace Libre{
    use Libre\View;
    use Libre\View\ViewObject;
    use Libre\View\Template;
    use Libre\View\Template\FromString;
    const TEST = "CONST";
    include_once 'header.php';

    $layout = ASSETS.'views/index.php';
    $partial = ASSETS.'views/partial.php';
    $demo = ASSETS.'views/demo.php';
    $fromString = "<html><body><h1>From string</h1><p>{dump}</p></body></html>";

    try{
        // View classic
        /**
        $view = new View(
            new FromString($fromString),
            new ViewObject()
        );*/

        //var_dump($view);
        //echo $view->render();
        /**
        $viewObject = new ViewObject();

        $_layout = new Template($layout);

        $viewLayout = new View($_layout, $viewObject);

        $viewObject->viewObject = "From ViewObject !";


        $viewLayout->attachPartial('body', $partial);
        $p1 = $viewLayout->getPartial('body');
        $p1->attachPartial('demo', $demo);
        echo $viewLayout->render();
        */
        $vo = new ViewObject();
        $vo->viewObject = "From ViewObject !";
        $layout = new View(new Template($layout), $vo);
        $layout->attachPartial('partial', $partial);
        $layout->attachPartial('body', $demo);

        echo $layout->render(false);
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}
