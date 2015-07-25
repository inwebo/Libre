<?php
// https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir

namespace Libre\Files {

    Class Inode extends \SplFileInfo {

        public function __construct($file_name) {
            parent::__construct($file_name);
        }

    }
}