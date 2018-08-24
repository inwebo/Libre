<?php

namespace Libre\Traits;

trait Modifiable
{

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function isWritable(string $filename)
    {
        if (is_file($filename) && is_writable(dirname($filename))) {
            $f = fopen($filename, "r+");
            $result = (is_writable($filename) && $f);
            fclose($f);

            return $result;
        }
        if (is_dir($filename)) {
            return is_writable($filename);
        }
        if (is_dir(dirname($filename))) {
            return is_writable(dirname($filename));
        }
    }

    /**
     * @param string $filename
     * @param string $content
     *
     * @return bool
     */
    public function write(string $filename, string $content)
    {
        if ($this->isWritable($filename)) {
            $handle = fopen($filename, 'w+');
            if (flock($handle, \LOCK_EX)) {
                fwrite($handle, $content);
                flock($handle, \LOCK_UN);
                fclose($handle);

                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param string $filename
     * @param string $content
     *
     * @return bool
     */
    public function append(string $filename, string $content)
    {
        if ($this->isWritable($filename)) {
            $handle = fopen($filename, 'a');
            if (flock($handle, \LOCK_EX)) {
                fwrite($handle, $content);
                flock($handle, \LOCK_UN);
                fclose($handle);

                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function unlink(string $filename)
    {
        if ($this->isWritable($filename)) {
            return unlink($filename);
        }
    }

}
