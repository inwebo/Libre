<?php
namespace Libre {
    use Libre\System\LiveExec;
    include_once 'header.php';
    //$cmd = "lss";
    //$cmd = "wkhtmltopdf https://fr.wikipedia.org/wiki/Wikip%C3%A9dia:Accueil_principal /home/inwebo/tmp/test.pdf";
    //$cmd = "wkhtmltoimage https://fr.wikipedia.org/wiki/Wikip%C3%A9dia:Accueil_principal /home/inwebo/tmp/test.png";
    //$cmd = "wget http://download.gna.org/wkhtmltopdf/0.12/0.12.3/wkhtmltox-0.12.3_linux-generic-amd64.tar.xz --directory-prefix=/home/inwebo/tmp/";
    $cmd = "ping -c 3 127.0.0.1";



    $exec = new LiveExec($cmd);

    $exec->onChange(function($string){
        echo $string;
    });
    $exec->onError(function($string){
        echo $string;
    });
    $exec->exec();
    var_dump($exec);
}
