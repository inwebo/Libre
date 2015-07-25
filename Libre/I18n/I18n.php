<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 18/08/13
 * Time: 11:11
 * To change this template use File | Settings | File Templates.
 */

/*
$lang = 'fr_FR.utf8';
$filename = 'message';
putenv("LC_ALL=$lang");
setlocale(LC_ALL, $lang);
bindtextdomain($filename,"./locale");
bind_textdomain_codeset($filename, "UTF-8");
textdomain("messages");
 */

namespace Libre;

class Localisation {

    public $language;
    public $poFile;
    public $poDir;

    protected function __construct( $language, $poFile, $poDir ) {
        /*
        $this->language = $language;
        $this->poFile = $poFile;
        $this->poDir = $poDir;
        $lang = 'fr_FR.utf8';
        $filename = 'message';
        putenv("LC_ALL=$lang");
        setlocale(LC_ALL, $lang);
        bindtextdomain($filename,"./locale");
        bind_textdomain_codeset($filename, "UTF-8");
        textdomain("messages");
        */
    }

    static public function setup($language, $poFile, $poDir) {
        new self($language,$poFile,$poDir);
    }

}