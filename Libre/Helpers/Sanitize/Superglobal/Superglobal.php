<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 12/10/13
 * Time: 19:22
 * To change this template use File | Settings | File Templates.
 */

namespace Libre\Helpers\Sanitize;


class SuperGlobal {

    protected $subject;

    public function __construct( $subject ) {
        $this->subject = $subject;
    }

    protected function iterator() {
        foreach($this->subject as $k=>$v) {
            $this->subject->$k = $this->sanitize($v);
        }
    }

    protected function sanitize( $member ) {
        return filter_var($member, FILTER_SANITIZE_ENCODED);
    }

    public function get() {
        return $this->subject;
    }

}