<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 13/01/14
 * Time: 23:18
 * To change this template use File | Settings | File Templates.
 */

namespace Libre\View\Parser\Logic\Loop;

use Libre\View\Parser\Logic;
use Libre\View\Parser\Tag;

class Informations {

    public $loop;
    public $header;
    protected $body;
    public $dataProvider;
    protected $key;
    protected $value;
    protected $recursive;

    protected function initialize($loop){

        // Representation sous forme de chaine d'une loop
        $this->loop = $loop['loop'];

        // Header
        $this->header = $loop['header'];

        // Body
        $this->body = $loop['body'];

        // DataProvider
        $this->dataProvider = $loop['dataProvider'];

        // Key value pair
        $this->key = $loop['key'];
        $this->value = $loop['value'];
        //$this->as = array("key"=>$loop['key'], "value"=>$loop['value']);

        // IsRecursive
        $this->recursive = (bool)preg_match(Tag::LOOP, $this->body);

    }

    public function toArray() {
        $array = array();
        foreach($this as $key => $value) {
            $array[$key]=$value;
        }
        return $array;
    }

    public function process( $loop ) {
        $this->initialize($loop);
        $results = new \StdClass();

        // Representation sous forme de chaine d'une loop
        $results->loop = $this->loop;

        // Header
        $results->header = $this->header;

        // Body
        $results->body = $this->body;

        // DataProvider
        $results->dataProvider = $this->dataProvider;

        // Key value pair
        //$results->as = array("key"=>$this->as['key'], "value"=>$this->as['value']);
        $results->key = $this->key;
        $results->value = $this->value;
        //$results->value = array("key"=>$this->as['key'], "value"=>$this->as['value']);

        // IsRecursive
        $results->recursive = (bool)preg_match(Tag::LOOP, $this->body);

        return $results;
    }

}