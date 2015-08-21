<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Ftp\Config;
    use Libre\Ftp;

    try{
        $config = [
            'host'      => 'ftp.mozilla.org',
            'port'      => 21,
            'timeout'   => 90,
            'passive'   => true,
            'usr'       => 'anonymous',
            'pwd'       => null
        ];

        $configObj = new Config(
            $config['host'],
            $config['port'],
            $config['timeout']
        );

        $ftp = new Ftp();
        $valid = $ftp->addServer($configObj, $config['usr'], $config['pwd']);
        if($valid) {
            $server1 = $ftp->getServer('ftp.mozilla.org');
            $server1->setPassive(true);
            echo $server1->cwd();
            $server1->cd('/pub');
            $files = $server1->ls();
            var_dump($files);

        }

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}
