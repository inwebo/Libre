<?php
namespace Libre\Files\Filters;

class FileFilter extends \FilterIterator {

    public function accept() {
        /* @var \SplFileInfo $current */
        $current = $this->getInnerIterator()->current();
        return $current->isFile();
    }

}