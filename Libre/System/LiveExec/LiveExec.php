<?php

namespace Libre\System {

    class LiveExec {

        /**
         *
         * File descriptor 1 is the standard output (stdout).
         * File descriptor 2 is the standard error (stderr).
         *
         * Here is one way to remember this construct (although it is not entirely accurate): at first,
         * 2>1 may look like a good way to redirect stderr to stdout. However, it will actually be interpreted as
         * "redirect stderr to a file named 1". & indicates that what follows is a file descriptor and not a filename.
         * So the construct becomes: 2>&1.
         *
         * @see http://stackoverflow.com/a/818284
         */
        const REDIRECT_STDERROR_TO_STDOUTPUT = ' 2>&1';

        /**
         * @var string
         */
        protected $_cmd;
        /**
         * @var int
         */
        protected $_exitCode;
        /**
         * @var string
         */
        protected $_buffer;
        /**
         * @var callable
         */
        protected $_onChange;
        /**
         * @var callable
         */
        protected $_onError;

        /**
         * @return string
         */
        public function getCmd()
        {
            return $this->_cmd;
        }
        /**
         * @param string $cmd
         */
        public function setCmd($cmd)
        {
            $this->_cmd = $cmd;
        }

        /**
         * @return int
         */
        public function getExitCode()
        {
            return $this->_exitCode;
        }
        /**
         * @param int $exitCode
         */
        public function setExitCode($exitCode)
        {
            $this->_exitCode = $exitCode;
        }

        /**
         * @return string
         */
        public function getBuffer()
        {
            return $this->_buffer;
        }
        /**
         * @param string $buffer
         */
        public function setBuffer($buffer)
        {
            $this->_buffer .= $buffer;
        }

        /**
         * @return callable
         */
        public function getOnChange()
        {
            return $this->_onChange;
        }
        /**
         * @param callable $onChange
         */
        public function setOnChange($onChange)
        {
            $this->_onChange = $onChange;
        }

        /**
         * @return callable
         */
        public function getOnError()
        {
            return $this->_onError;
        }
        /**
         * @param callable $onError
         */
        public function setOnError($onError)
        {
            $this->_onError = $onError;
        }

        /**
         * LiveExec constructor.
         * @param string $cmd A valid system command
         * @param bool $forceRedirect
         */
        public function __construct($cmd, $forceRedirect = true)
        {
            $cmd = $cmd . (($forceRedirect) ? LiveExec::REDIRECT_STDERROR_TO_STDOUTPUT : '') ;
            $this->setCmd( $cmd );
        }

        public function exec()
        {

            ob_end_flush();

            $process = popen($this->getCmd() . " ; echo Exit status : $?", 'r');

            while (!feof($process))
            {
                $output     = fread($process, 4096);
                // $buffer
                $this->setBuffer($output);

                // onChange
                if( !is_null($this->getOnChange()) && is_callable($this->getOnChange()) ) {

                    // $exitCode
                    if(strstr($output, "Exit status :") !== false) {
                        preg_match('/[0-9]+$/', $output, $matches);
                        $this->setExitCode((int)$matches[0]);
                        $output = str_replace("Exit status : " . $matches[0], '', $output);
                    }

                    if($this->getExitCode() !== 0 && !is_null($this->getExitCode())) {
                        if( !is_null($this->getOnError()) && is_callable($this->getOnError() )) {
                            $this->getOnError()->__invoke($output);
                        }
                    }
                    else {
                        $this->getOnChange()->__invoke($output);
                    }


                }
                @flush();
            }

            pclose($process);
        }

        /**
         * @param callable $callback
         */
        public function onChange(callable $callback)
        {
            $this->setOnChange($callback);
        }

        /**
         * @param callable $callback
         */
        public function onError($callback)
        {
            $this->setOnError($callback);
        }
    }
}