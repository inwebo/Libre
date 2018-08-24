<?php

namespace Libre\Files;

use Libre\Files\Filters\DirFilter;
use Libre\Files\Filters\FileFilter;

/**
 * Class Iterator
 * @package Libre\Files
 */
class Iterator
{
    /**
     * @var string
     */
    protected $_baseDir;
    /**
     * @var \FilesystemIterator|\RecursiveIteratorIterator
     */
    protected $_inodes;

    public function __construct($path, $isRecursive = true)
    {
        $this->_baseDir = $path;
        $this->_inodes = ($isRecursive) ? $this->recursiveDirs() : $this->flatDir();
    }

    public function getDirs()
    {
        return new DirFilter($this->_inodes);
    }

    public function getFiles()
    {
        return new FileFilter($this->_inodes);
    }

    public function getNodes()
    {
        return $this->_inodes;
    }

    public function count()
    {
        $this->getNodes()->rewind();

        return iterator_count($this->getNodes());
    }

    protected function recursiveDirs(
        $fileSystemOptions = \FilesystemIterator::SKIP_DOTS,
        $recursiveIteratorIteratorOptions = \RecursiveIteratorIterator::CHILD_FIRST
    ) {
        try {
            $recursiveDirectoryIterator = new \RecursiveDirectoryIterator(
                $this->_baseDir,
                $fileSystemOptions
            );
            $recursiveIteratorIterator = new \RecursiveIteratorIterator(
                $recursiveDirectoryIterator,
                $recursiveIteratorIteratorOptions
            );
        } catch (\Exception $e) {
            throw $e;
        }
        $recursiveIteratorIterator->rewind();

        return $recursiveIteratorIterator;
    }

    protected function flatDir()
    {
        try {
            $inodes = new \FilesystemIterator($this->_baseDir, \FilesystemIterator::SKIP_DOTS);
        } catch (\Exception $e) {
            throw $e;
        }
        $inodes->rewind();

        return $inodes;
    }

}