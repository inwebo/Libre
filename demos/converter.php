<?php
include_once ('modules/HtmlToPdf.php');
use \Libre\Modules\Converter\Models\HtmlToPdf as HtmlToPdf;

try  {
    /** @var HtmlToPdf\ $converter */
    $converter = new HtmlToPdf('wkhtmltopdf', 'http://www.google.fr', '/home/inwebo/tmp/', 'test');
    //$converter
        //->setDpi(96)
        //->enableGreyscale(false)
        /*->setImageDpi(600)
        ->setImageQuality(50)
        ->setMarginBottom('5mm')
        ->setMarginTop('5mm')
        ->setMarginLeft('5mm')
        ->setMarginRight('5mm')
        ->setOrientation('Portrait')*/
        //->enableBackground(true)
        //->enableJavascript(false)
        //->enableExternalLinks(false)
        //->noImages(true)
        //->enablePrintMediaType(true)
        //->enableTocBackLinks(false)
        //->setUserStyleSheet('http://www.google.fr/style.css')*/
    //;
    //$converter->enableJavascript(false);

    //var_dump($converter);
    //$attr = $converter->formatAttribute('_enableJavascript');

    //echo $converter->attributeToString($attr);

    //$pageOptions = $converter->getPageOptions();
    //var_dump($converter->toString());
    echo $converter->toString();

    //var_dump( unserialize(serialize($converter)));

    $array = array(
        '_pageNoImages' => true,
        'd' => true,
        '_globalGreyscale' => false
    );

    $json  = json_encode($array);
    var_dump($json);
    $instance = HtmlToPdf::factory('ls','','','', $json);
    var_dump($instance);
    //$converter->toString();
}
catch (Exception $e) {
    echo $e->getMessage();
}

