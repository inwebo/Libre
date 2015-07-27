<?php

namespace Libre\Files\Filters;

class DirFilter extends \FilterIterator {

    public function accept() {
        /* @var \SplFileInfo $current */
        $current = $this->getInnerIterator()->current();
        return $current->isDir();
    }

}