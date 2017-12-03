<?php
/**
 * Inwebo
 */
namespace Libre;

use Libre\Cache\CacheException;

/**
 * Class Cache
 */
class Cache
{
    /**
     * @var string
     */
    protected $baseDir;
    /**
     * @var string
     */
    protected $file;
    /**
     * @var int
     */
    protected $birth;
    /**
     * @var int
     */
    protected $death;
    /**
     * @var int
     */
    protected $life;
    /**
     * @var bool
     */
    protected $updating = false;

    /**
     * @return string
     */
    protected function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @param string $baseDir
     */
    protected function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @return string
     */
    protected function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    protected function getBirth()
    {
        return $this->birth;
    }

    /**
     * @param int $birth
     */
    public function setBirth($birth)
    {
        $this->birth = $birth;
    }

    /**
     * @return int
     */
    protected function getDeath()
    {
        return $this->death;
    }

    /**
     * @param int $death
     */
    protected function setDeath($death)
    {
        $this->death = $death;
    }

    /**
     * @return int
     */
    protected function getLife()
    {
        return $this->life;
    }

    /**
     * @param int $life
     */
    protected function setLife($life)
    {
        $this->life = $life;
    }

    /**
     * @return boolean
     */
    protected function isUpdating()
    {
        return $this->updating;
    }

    /**
     * @param boolean $flagUpdating
     */
    protected function setUpdating($flagUpdating)
    {
        $this->updating = $flagUpdating;
    }

    /**
     * @param string $baseDir Place to save cache files. Must be readable & writable
     * @param string $file    Cached file name
     * @param int    $life    Seconds
     *
     * @throws \Exception
     */
    public function __construct($baseDir, $file, $life = 10)
    {
        try {
            $this->validatePaths($baseDir);
            $this->setBaseDir($baseDir);
            $this->setFile($file);
            $this->setLife($life);

            if (file_exists($this->toPathFile())) {
                $this->setBirth((int) filemtime($this->toPathFile()));
            } else {
                $this->setBirth((int) time());
            }
            $this->setDeath($this->getBirth() + $this->getLife());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Start cache until the $this->stop() method has been found.
     */
    public function start()
    {
        // Already cached ?
        if (file_exists($this->toPathFile())) {
            // Is up to date ?
            if ($this->isValidCache()) {
                readfile($this->toPathFile());
            } else {
                $this->setUpdating(true);
            }
        } else {
            $this->setUpdating(true);
        }
        ob_start();
    }

    /**
     * @return int
     */
    public function stop()
    {
        // Save
        if ($this->isUpdating()) {
            $f = fopen($this->toPathFile(), 'w+');
            fputs($f, ob_get_contents());
            fclose($f);
            ob_get_clean();

            return readfile($this->toPathFile());
        }
        ob_get_clean();
    }

    /**
     * @return bool
     */
    protected function isValidCache()
    {
        return ($this->death < time()) ? false : true;
    }

    /**
     * @return string
     */
    protected function toPathFile()
    {
        return $this->baseDir.$this->file;
    }

    /**
     * @param string $baseDir Base dir path, must exists & must be writable.
     *
     * @throws CacheException
     */
    protected function validatePaths($baseDir)
    {
        if (!file_exists($baseDir)) {
            throw new CacheException(sprintf('Dir %s doesn\'t exists ', $baseDir));
        }

        if (!is_writable($baseDir)) {
            throw new CacheException(sprintf('Dir %s is not writable', $baseDir));
        }
    }
}
