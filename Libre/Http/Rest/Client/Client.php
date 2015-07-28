<?php

namespace Libre\Http\Rest {

    use Libre\Http\Rest\Response;
    use mageekguy\atoum\tests\units\asserters\template\parser\exception;


    /**
     * Libre
     *
     * LICENCE
     *
     * You are free:
     * to Share ,to copy, distribute and transmit the work to Remix —
     * to adapt the work to make commercial use of the work
     *
     * Under the following conditions:
     * Attribution, You must attribute the work in the manner specified by
     * the author or licensor (but not in any way that suggests that they
     * endorse you or your use of the work).
     *
     * Share Alike, If you alter, transform, or build upon
     * this work, you may distribute the resulting work only under the
     * same or similar license to this one.
     *
     *
     * @category  Libre
     * @package   Http
     * @subpackage Rest
     * @subpackage Client
     * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
     * @license   http://http://creativecommons.org/licenses/by-nc-sa/3.0/
     * @version   $Id:$
     * @link      https://github.com/inwebo/RESTfulClient
     * @since     File available since 06-04-2013
     */

    /**
     * Simple client RESTfull.
     *
     * @category  Libre
     * @package   Http
     * @subpackage Rest
     * @subpackage Client
     * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
     * @license    http://http://creativecommons.org/licenses/by-nc-sa/3.0/
     * @version    $Id:$
     * @link       https://github.com/inwebo/RESTfulClient
     * @since     File available since 06-04-2013
     */

    class RestClientException extends \Exception{}


    class Client {

        //region Attributs
        /**
         * @var string MUST END WITH FINAL /
         */
        protected $_serviceUrl;

        /**
         * @var array
         */
        protected $_defaultStreamOptions;

        /**
         * @var array
         */
        protected $_headers = array();

        /**
         * @var array
         */
        protected $_callbackValidators = array();
        //endregion Attributs

        //region Getters / Setters
        /**
         * @return array
         */
        public function getDefaultStreamOptions()
        {
            return $this->_defaultStreamOptions;
        }

        /**
         * @param array $defaultStreamOptions
         */
        public function setDefaultStreamOptions($defaultStreamOptions)
        {
            $this->_defaultStreamOptions = $defaultStreamOptions;
        }

        public function getHeadersAsString()
        {
            $buffer = '';
            foreach($this->getHeaders() as $key => $value)
            {
                $buffer .= trim($key, ':') . ':' . ' '.trim($value, ' '). "\r\n";
            }
            return $buffer;
        }

        /**
         * @return array
         */
        public function getHeaders()
        {
            return $this->_headers;
        }

        /**
         * @param string $name|array
         * @param string $value
         * @param bool $erase
         */
        public function setHeaders($name, $value, $erase = false)
        {
            if( is_array($name) )
            {
                $this->_headers = array_merge($this->_headers, $name);
            }
            elseif((isset($this->_headers[$name]) && $erase) || !isset($this->_headers[$name]))
            {
                $this->_headers[$name] = $value;
            }
        }

        /**
         * @return string
         */
        public function getServiceUrl()
        {
            return $this->_serviceUrl;
        }

        /**
         * @param string $serviceUrl
         */
        protected function setServiceUrl($serviceUrl)
        {
            $this->_serviceUrl = $serviceUrl;
        }

        /**
         * @return array
         */
        public function getCallbackValidators()
        {
            return $this->_callbackValidators;
        }

        public function setCallbackValidators(\Closure $callbackValidators)
        {
            $this->_callbackValidators[] = $callbackValidators;
        }
        //endregion Getters / Setters

        //region URL
        public function parseUrl()
        {
            return parse_url($this->getServiceUrl());
        }
        public function getProtocol()
        {
            $url = $this->parseUrl();
            return $url['scheme'];
        }
        public function getHost()
        {
            $url = $this->parseUrl();
            return $url['host'];
        }
        public function getPath()
        {
            $url = $this->parseUrl();
            return $url['path'];
        }
        //endregion

        /**
         * Le code status attendus par defaut DOIT être 300 <cite>The requested resource corresponds to any one of a set of representations, each with its own specific location, and agent- driven negotiation information (section 12) is being provided so that the user (or user agent) can select a preferred representation and redirect its request to that location. </cite>
         * pour la réponse HTTP de l'url <code>$serviceUrl</code>.
         *
         * @param string MUST END WITH FINAL /
         * @throws RestClientException Si url invalid
         * @throws RestClientException Si le status code de l'en tête HTTP n'est pas égale à 300, rfc2616.
         * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
         * @link https://developer.att.com/application-resource-optimizer/docs/best-practices/http-300-status-codes
         * @todo Digest auth
         */
        public function __construct($serviceUrl)
        {
            $validUrl = filter_var(rtrim($serviceUrl,'/'), \FILTER_VALIDATE_URL);
            if($validUrl)
            {
                $this->setServiceUrl($serviceUrl);
                $this->isValidService();
                $this->setHeaders('Content-type', 'application/x-www-form-urlencoded');
            }
        }

        /**
         * pour être une source valide.
         * @return bool
         * @todo Le status code de la réponse devrait renvoyé le code 300.
         */
        public function isValidService()
        {
            $this->head();
        }

        /**
         * @param string $verb
         * @return array
         */
        protected function getDefaultStreamContext($verb, $header=array(),$content=array())
        {
            $this->setHeaders($header);
            $options = array(
                $this->getProtocol() => array(
                    'header'    => $this->getHeadersAsString(),
                    'method'    => strtoupper($verb),
                    'content'   => http_build_query($content)
                )
            );
            $context = stream_context_create($options);
            return $context;
        }

        /**
         * @param $verb
         * @param string $uri
         * @param null $options
         * @return Response
         * @throws RestClientException
         * @throws RestResponseException
         */
        protected function open($verb, $uri='', $options=null)
        {
            $queryUrl = $this->getServiceUrl() . $uri;
            $handler = @fopen($queryUrl,'r',false,$this->getDefaultStreamContext($verb));

            if( $handler === false )
            {
                throw new RestClientException($queryUrl . ': HTTP request failed! HTTP/1.1 404 Not Found');
            }
            elseif(!is_resource($handler))
            {
                throw new RestResponseException('Is not a resource');
            }
            else
            {
                return new Response($handler);
            }
        }

        /**
         * @param Response $response
         * @return bool
         */
        public function callCallbacksValidator(Response $response)
        {
            $valid = true;
            if( !empty($this->getCallbackValidators()) )
            {
                foreach($this->getCallbackValidators() as $closure)
                {
                    $valid &= $closure->__invoke($response);
                }
            }
            return (bool)$valid;
        }

        //region Auth
        public function authBasic($base64String)
        {
            $this->setHeaders('Authorization', 'Basic ' . $base64String);
        }
        public function authBasicLoginPwd($user, $password)
        {
            $this->setHeaders('Authorization', 'Basic ' . base64_encode($user . ':' . $password));
        }
        /**
         * @todo
         */
        public function authDigest()
        {

        }
        /**
         * @todo
         */
        public function authDigestLoginPwd()
        {

        }
        //endregion Auth

        //region VERBS
        /**
         * @param string $uri
         * @return Response
         * @throws RestClientException
         * @throws RestResponseException
         */
        public function get($uri='')
        {
            return $this->open('GET', $uri);
        }
        public function post($uri='', $params)
        {
            return $this->open('POST', $uri, $params);
        }
        public function update($uri='', $params)
        {
            return $this->open('UPDATE', $uri, $params);
        }
        public function delete($uri='', $params)
        {
            return $this->open('DELETE', $uri, $params);
        }
        public function head($uri='')
        {
            return $this->open('HEAD', $uri);
        }
        public function options($uri='')
        {
            return $this->open('OPTIONS', $uri);
        }
        public function patch($uri='')
        {
            return $this->open('PATCH', $uri);
        }
        //endregion VERBS
    }
}
