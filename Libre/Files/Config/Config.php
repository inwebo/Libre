<?php

namespace Libre\Files {
    class ConfigFileException extends \Exception{};

    use Libre\Traits\Modifiable;

    class Config
    {

        use Modifiable;

        /**
         * @var string
         */
        protected $_path;
        /**
         * @var \StdClass
         */
        protected $_data;

        /**
         * @return string
         */
        public function getPath()
        {
            return $this->_path;
        }

        /**
         * @param string $path
         */
        protected function setPath($path)
        {
            $this->_path = $path;
        }

        /**
         * @return StdClass
         */
        public function getData()
        {
            return $this->_data;
        }

        /**
         * @param StdClass $data
         */
        protected function setData($data)
        {
            $this->_data = $data;
        }

        public function __construct($path, $processSections = true, $asStandardClass = true)
        {
            if (($config = @parse_ini_file($path, $processSections)) == false) {
                throw new ConfigFileException('Config file ' . $path . ' not found.');
            } else {
                $this->setPath(realpath($path));
                $this->setData(($asStandardClass) ? (object)$config : $config);
            }
        }

        public function getSection($name)
        {
            if (isset($this->getData()->$name)) {
                return $this->getData()->$name;
            }
        }

        public function addToSection($name, $key, $value)
        {
            if (!is_null($this->getSection($name))) {
                //var_dump($this->getSection($name));
                //$this->getSection($name)->$name[$key] = $value;
            }
        }

        public function flatten()
        {
            $return = [];
            $iterator = new ArrayIterator($this->data);
            $iterator->rewind();
            while ($iterator->valid()) {
                $current = $iterator->current();
                if (is_array($current)) {
                    $return[] = $this->toSection($iterator->key());
                    $iterator2 = new ArrayIterator($current);
                    $iterator2->rewind();
                    while ($iterator2->valid()) {
                        $return[] = $this->toKeyValue($iterator2->key(), $iterator2->current());
                        $iterator2->next();
                    }
                } else {
                    if ($iterator->current() !== "") {
                        $return[] = $this->toKeyValue($iterator->key(), $iterator->current());
                    }
                }
                $iterator->next();
            }
            return $return;
        }

        public function toString()
        {
            return implode("\n", $this->flatten());
        }

        public function save()
        {
            $this->write($this->toString());
        }

        protected function toKeyValue($key, $value)
        {
            return $key . '=' . $value;
        }

        protected function toSection($string)
        {
            return '[' . $string . ']';
        }

    }
}