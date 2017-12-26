<?php
/**
 * inwebo
 */
namespace Libre\Helpers\IO;

/**
 * Class Writer
 */
class Writer
{

    /**
     * @var string
     */
    protected $filename;
    /**
     * @var resource
     */
    protected $fileHandler;
    /**
     * @var string
     *
     * @see http://php.net/manual/fr/function.fopen.php
     */
    protected $mode;

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return resource
     */
    public function getFileHandler()
    {
        return $this->fileHandler;
    }

    /**
     * @param resource $fileHandler
     */
    public function setFileHandler($fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @param string $filename
     * @param string $mode
     *
     * @throws WriterException
     */
    public function __construct($filename, $mode = "w+b")
    {
        $this->setFilename($filename);
        $this->setMode($mode);
        $this->setFileHandler(@fopen($filename, $this->getMode()));

        if ($this->getFileHandler() === false) {
            throw new WriterException(sprintf('Filename : %s doesnt exists or is not writable', $this->getFilename()));
        }
    }

    /**
     * @param string $string
     */
    public function write($string)
    {
        if (flock($this->getFileHandler(), LOCK_EX)) {
            fwrite($this->getFileHandler(), $string.PHP_EOL);
            flock($this->getFileHandler(), LOCK_UN);
        }
    }

    /**
     * Close handler
     */
    public function close()
    {
        fclose($this->getFileHandler());
    }
}
