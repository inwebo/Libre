<?php

class CurlLibraryMissingException extends \Exception{
    protected $_message ='Install or Enable Curl extension.';
}
class CurlInvalidArgException extends \Exception{}
class CurlException extends \Exception{}

/**
 * Class Curl
 * @see https://secure.php.net/manual/en/function.curl-setopt.php
 */
class Curl{
    /**
     * @var resource Curl session
     */
    protected $_resource;
    /**
     * @var array
     */
    protected $_options;

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->_resource;
    }

    protected function setResource()
    {
        if( function_exists('curl_init') ){
            $this->_resource = curl_init();
        }
        else {
            throw new CurlLibraryMissingException();
        }
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param array $options
     * @throws CurlInvalidArgException
     */
    public function setOptions($options)
    {
        foreach($options as $k=>$v)
        {
            if( @curl_setopt($this->getResource(), $k, $v) === false ){
                throw new CurlInvalidArgException($k . ' is unkonw curl const, see https://secure.php.net/manual/en/function.curl-setopt.php');

            }
            else {
                $this->_options[$k] = $v;
            }
        }
    }

    public function __construct($options = null)
    {
        if(!is_null($options) && is_array($options)) {
            $this->setOptions($options);
        }
        else {
            $this->setOptions(array());
        }
        $this->setResource();
    }

    public function exec( $returnTransferToString = true )
    {
        if( $returnTransferToString ){
            $this->setOptions(array(
                CURLOPT_RETURNTRANSFER => true
            ));
        }

        $isValid = curl_exec($this->getResource());

        if( $isValid !== false ) {
            return $isValid;
        }
        else {
            throw new CurlException($this->getErrors());
        }
    }

    public function reset()
    {
        $this->_options = array();
        curl_reset($this->getResource());
    }

    protected function getErrors()
    {
        return curl_error($this->getResource());
    }

    public function getInfo()
    {
        return curl_getinfo($this->getResource());
    }

    public function close()
    {
        curl_close($this->getResource());
    }

    public function __destruct()
    {
        curl_close($this->getResource());
    }

}

/**
 * Class MultiCurl
 *
 * Execute de facon asynchrone de multiple Curl object. Mets en cache les résultats.
 * Doit pouvoir tracer les erreurs et les gerer.
 */
class MultiCurl {
    /**
     * @var resource Curl session
     */
    protected $_resource;


    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->_resource;
    }

    protected function setResource()
    {
        if( function_exists('curl_multi_init') ){
            $this->_resource = curl_multi_init();
        }
        else {
            throw new CurlLibraryMissingException();
        }
    }
    /**
     * @var int
     */
    protected $_maxHandlers;
    /**
     * @var array
     */
    protected $_defaultHandlerOptions;

    /**
     * @return int
     */
    public function getMaxHandlers()
    {
        return $this->_maxHandlers;
    }

    /**
     * @param int $maxHandlers
     */
    public function setMaxHandlers($maxHandlers)
    {
        $this->_maxHandlers = $maxHandlers;
    }

    /**
     * @return array
     */
    public function getDefaultHandlerOptions()
    {
        return $this->_defaultHandlerOptions;
    }

    /**
     * @param array $defaultHandlerOptions
     */
    public function setDefaultHandlerOptions($defaultHandlerOptions)
    {
        if( !is_null($defaultHandlerOptions) && is_array($defaultHandlerOptions) ) {
            $this->_defaultHandlerOptions = $defaultHandlerOptions;
            foreach($defaultHandlerOptions as $k=>$option)
            {
                if(@curl_multi_setopt($this->getResource(),$k,$option)!==true)
                {
                    // Invalid
                }
            }

        }
    }

    public function __construct($maxHandlers, $defaultHandlerOptions = null)
    {
        $this->setMaxHandlers($maxHandlers);
        $this->setResource();
        if(!is_null($defaultHandlerOptions))
        {
            $this->setDefaultHandlerOptions($defaultHandlerOptions);
        }
    }

    /**
     * <code>
     *  $multiCurl->exec(
     *  array(CURLOPT_URL => "http://www.example.com/"),
     *  array(CURLOPT_URL => "http://www.inwebo.net/sleep.php"),
     * );
     * </code>
     * @param array $options
     * @throws Exception
     * @return array
     */
    public function exec($options)
    {
        $chunks = array_chunk($options,$this->getMaxHandlers());
        $return = array();

        foreach($chunks as $chunk)
        {
            $this->setResource();
            $handlers = array();
            foreach($chunk as $curlObjectOptions)
            {
                $handler = new Curl($this->getDefaultHandlerOptions());
                $handler->setOptions($curlObjectOptions);
                //$handler->setOptions(array(CURLOPT_RETURNTRANSFER => 1));
                $handlers[] = $handler;
                curl_multi_add_handle($this->getResource(), $handler->getResource());
            }
            $running = null;
            do {
                curl_multi_exec($this->getResource(), $running);
            } while($running > 0);

            foreach($handlers as $k=>$v)
            {
                $return[] = curl_multi_getcontent($v->getResource());
                curl_multi_remove_handle($this->getResource(), $v->getResource());
            }
            curl_multi_close($this->getResource());
        }

        return $return;
    }

    protected function setValidAssert(Closure $closure)
    {

    }

}

class XCurl {
    /**
     * @var resource
     */
    protected $_resource;

    /**
     * @var array
     */
    protected $_defaultHandlerOptions;
    /**
     * @var int
     */
    protected $_totalMultiCurl;

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->_resource;
    }

    /**
     * @throws CurlLibraryMissingException
     */
    protected function setResource()
    {
        if( function_exists('curl_multi_init') ){
            $this->_resource = curl_multi_init();
        }
        else {
            throw new \CurlLibraryMissingException();
        }
    }
    /**
     * @return array
     */
    public function getDefaultHandlerOptions()
    {
        return $this->_defaultHandlerOptions;
    }

    /**
     * @param array $defaultHandlerOptions
     */
    public function setDefaultHandlerOptions($defaultHandlerOptions)
    {
        $this->_defaultHandlerOptions = $defaultHandlerOptions;
    }

    /**
     * @return int
     */
    public function getTotalMultiCurl()
    {
        return $this->_totalMultiCurl;
    }

    /**
     * @param int $totalMultiCurl
     */
    public function setTotalMultiCurl($totalMultiCurl)
    {
        $this->_totalMultiCurl = $totalMultiCurl;
    }

    /**
     * @param int $totalMultiCurl
     * @param array $defaultHandlerOptions
     */
    public function __construct($totalMultiCurl, $defaultHandlerOptions)
    {
        $this->setResource();
        $this->setTotalMultiCurl($totalMultiCurl);
        $this->setDefaultHandlerOptions($defaultHandlerOptions);
    }

    /**
     * @param array $curlOptionsUrls
     * @return array
     * @throws CurlInvalidArgException
     * @throws Exception
     */
    public function exec($curlOptionsUrls)
    {
        if( $this->isValidCurlOptionsUrls($curlOptionsUrls) )
        {
            try{
                $handlers = $this->getHandlers();
            }
            catch(\Exception $e){
                throw $e;
            }

            $i = 0;

            foreach($handlers as $handler)
            {
                /* @var Curl $handler */
                if( $this->curlOptionExists($curlOptionsUrls, $i) )
                {
                    try {
                        $handler->setOptions($curlOptionsUrls[$i]);
                    }
                    catch(\Exception $e)
                    {
                        throw $e;
                    }
                    //var_dump($handler);
                    curl_multi_add_handle($this->getResource(), $handler->getResource());
                }
                else
                {
                    continue;
                }
                $i++;
            }
            $this->_exec();
            return $this->_getResults($handlers);

        }
        else
        {
            // Exception
        }
    }

    protected function _exec()
    {
        $isRunning = null;
        do {
            curl_multi_exec($this->getResource(), $isRunning);
        } while($isRunning > 0);
    }

    /**
     * @param array $handlers
     * @return array
     */
    protected function _getResults($handlers)
    {
        $return = array();
        foreach($handlers as $k=>$v)
        {
            $return[] = curl_multi_getcontent($v->getResource());
            curl_multi_remove_handle($this->getResource(), $v->getResource());
        }
        curl_multi_close($this->getResource());
        return $return;
    }

    /**
     * @param array $curlOptionsUrls
     * @return bool
     */
    protected function isValidCurlOptionsUrls($curlOptionsUrls)
    {
        if ( is_array($curlOptionsUrls) && !empty($curlOptionsUrls) )
        {
            foreach($curlOptionsUrls as $v)
            {
                if( !is_array($v) ){
                    return false;
                }
            }
        }
        else {
            return false;
        }
        return true;
    }

    /**
     * @param array $options
     * @param int $i
     * @return bool
     */
    protected function curlOptionExists($options, $i)
    {
        return (isset($options[$i]));
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getHandlers()
    {
        $handlers = array();
        $totalLoop = $this->getTotalMultiCurl();
        for( $totalLoop; $totalLoop > 0; $totalLoop-- )
        {
            try{
                $handler = new Curl($this->getDefaultHandlerOptions());
                $handlers[] = $handler;
            }
            catch(\Exception $e){
                throw $e;
            }

        }
        return $handlers;
    }

}


class MassCurl{
    /**
     * @var int
     */
    protected $_maxHandlers;
    /**
     * @var array
     */
    protected $_defaultOptions;
    /**
     * @var array
     */
    protected $_options;

    /**
     * @return int
     */
    public function getMaxHandlers()
    {
        return $this->_maxHandlers;
    }

    /**
     * @param int $maxHandlers
     */
    public function setMaxHandlers($maxHandlers)
    {
        $this->_maxHandlers = $maxHandlers;
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->_defaultOptions;
    }

    /**
     * @param array $defaultOptions
     */
    public function setDefaultOptions($defaultOptions)
    {
        $this->_defaultOptions = $defaultOptions;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->_options = $options;
    }

    /**
     * @param int $maxHandlers
     * @param array $defaultOptions
     * @param array $_options
     */
    public function __construct($maxHandlers, $defaultOptions, $_options)
    {
        $this->setMaxHandlers($maxHandlers);
        $this->setDefaultOptions($defaultOptions);
        $this->setOptions($_options);
    }

    public function exec()
    {
        //@todo Validate chunks
        $chunks = array_chunk($this->getOptions(), $this->getMaxHandlers());
        $return = array();
        foreach($chunks as $chunk)
        {
            $xCurl = new XCurl($this->getMaxHandlers(), $this->getDefaultOptions());
            $return[] = $xCurl->exec($chunk);
        }
        return $return;
    }

}

/**
 * Tests a faire : Pour le multi curl, est mieux d'avoir a chaque tour des nouvelles instance OU de reset les objets courants.
 */
try{
    //$curl = new Curl();
    $options = array(
        //CURLOPT_URL => "http://www.example.com/",
        //CURLOPT_URL => "http://www.inwebo.net/sleep.php",
        //CURLOPT_TIMEOUT => 1

    );
    //$curl->setOptions($options);
    // Test unitaire, les options sont setter correctement. avec le setter public ou le construct
    // Test unitaire, Exception sur les options.
    // $returnString = $curl->exec();
    // Test unitaire, verifier les exceptions sur le exec.
    // Test si la sortie est dans une chaine ou directement affichée
    // echo $returnString;
    // Test duréee execution trop long.

    /**
     * Multi
     */
    // Test si un des objets curl lance une exceptionn, ex : array(CURLOPT_TIMEOUT => 1)

    $multi = new XCurl(4, array(
        CURLOPT_TIMEOUT         => 1,
        CURLOPT_RETURNTRANSFER  => true
    ));
    var_dump($multi);
    $r = $multi->exec(array(
        array(
            CURLOPT_URL => "http://www.example.com/"
        ),
        array(
            CURLOPT_URL => "http://www.inwebo.net/"
        ),
        array(
            CURLOPT_URL => "http://www.julien-hannotin.fr/"
        ),
        array(
            CURLOPT_URL => "http://www.inwebo.net/sleep.php"
        )
    ));

    //var_dump($r);
}
catch (\Exception $e){

    var_dump($e);
}
