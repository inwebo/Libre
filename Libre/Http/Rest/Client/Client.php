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

        /**
         * @var string MUST END WITH FINAL /
         */
        protected $_serviceUrl;

        /**
         * @var array
         */
        protected $_defaultStreamOptions;

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
         * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
         * @see https://developer.att.com/application-resource-optimizer/docs/best-practices/http-300-status-codes
         */
        public function __construct($serviceUrl)
        {
            $validUrl = filter_var($serviceUrl, \FILTER_VALIDATE_URL, \FILTER_FLAG_PATH_REQUIRED);
            if($validUrl)
            {
                $this->setServiceUrl($serviceUrl);
                /*
                if($this->isValid())
                {
                    $this->setServiceUrl($serviceUrl);
                }
                else
                {
                    throw new RestClientException('"'.$serviceUrl . '", HTTP header status code is not 300!');
                }
                */
            }
            else {
                throw new RestClientException('"'.$serviceUrl . '" is not a valid URL, don\'t forget the final / !');
            }

        }

        /**
         * Le status code de l'en tête HTML de réponse sur l'url <code>$_serviceUrl</code> DOIT renvoyer 300, pour être une source valide.
         *
         * @return bool
         */
        public function isValid()
        {
            /*
            stream_context_set_default(
                array(
                    'http' => array(
                        'method' => 'HEAD'
                    )
                )
            );
            @get_headers($this->getServiceUrl());
            $response_code = http_response_code();
            */
            /*
            $valid = @fopen($this->getServiceUrl(), 'r', null, $this->getDefaultStreamContext('GET'));
            var_dump($valid);
            if($valid === false)
            {
                return false;
            }
            else {

            }
*/
            //return ( $response_code !== 300 );
        }

        /**
         * @param string $verb
         * @return array
         */
        protected function getDefaultStreamContext($verb, $header=array(),$content=array())
        {
            $options = array(
                $this->getProtocol() => array(
                    'header'    => 'Content-type: application/x-www-form-urlencoded',
                    'method'    => strtoupper($verb),
                    'content'   => http_build_query($content)
                )
            );
            $context = stream_context_create($options);
            return $context;
        }

        protected function open($verb, $uri='', $options=0)
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

        //region VERBS
        public function get($uri='')
        {
            $resource = $this->open('GET',$uri);

            return $resource;
        }
        //@todo ajouter les autres verb possible
        //endregion CRUD

    }

    class _Client {

        /**
         * @var string Url de l'hôte qui hébérge le service REST. Fonctionne pour les protocoles http et https.
         */
        private $host;

        /**
         * @var string L'url du service..
         */
        private $url;

        /**
         * @var string URI compléte du service.
         */
        private $uri;

        /**
         * @var bool Le Service REST nécessite t-il une autentification.
         */
        private $logged = false;

        /**
         * @var string $user L'utilisateur courant.
         */
        private $user;

        /**
         * @var string Signature unique de l'utilisateur courant.
         */
        private $signature;

        /**
         * @var bool Le server REST est il https.
         */
        public $isHttps;

        public function __construct( $host, $url = "" ) {
            $this->host = $host;
            $this->url = $url;
            $this->uri = $this->getUri();
            $this->isHttps = $this->isHttps();
        }

        /**
         * Requête http HEAD. Ne retourne que l'en tête HTTP de la requête courante.
         *
         * @param array $params Paramètres aditionnels à envoyer avec la requête.
         * @return bool|\StdClass False si l'url n'est pas valide, sinon un objet.
         */
        protected function head( $params = array() ) {
            return $this->exec( $this->context("HEAD", $params) );
        }

        /**
         * Requête http POST. Permet la création de nouveaux items sur le serveur REST.
         *
         * @param array $params Paramètres aditionnels à envoyer avec la requête.
         * @return bool|\StdClass False si l'url n'est pas valide, sinon un objet.
         */
        public function create( $params = array() ) {
            return  $this->exec( $this->context("POST", $params),$params );
        }

        /**
         * Requête http GET. Permet la lecture d'elements.
         *
         * @param array $params Paramètres aditionnels à envoyer avec la requête.
         * @return bool|\StdClass False si l'url n'est pas valide, sinon un objet.
         */
        public function read( $params = array() ) {
            return $this->exec( $this->context("GET " . $this->url, $params) );
        }

        /**
         * Requête http PUT. Permet la mise à jours d'éléments.
         *
         * @param array $params Paramètres aditionnels à envoyer avec la requête.
         * @return bool|\StdClass False si l'url n'est pas valide, sinon un objet.
         */
        public function update( $params = array() ) {
            return $this->exec( $this->context("PUT", $params) );
        }

        /**
         * Requête http DELETE. Permet la suppression d'éléments.
         *
         * @param array $params Paramètres aditionnels à envoyer avec la requête.
         * @return bool|\StdClass False si l'url n'est pas valide, sinon un objet.
         */
        public function delete( $params = array() ) {
            return $this->exec( $this->context("DELETE", $params) );
        }

        /**
         * Le serveur est il https ?
         *
         * @return bool <code>True</code> si le serveur est https sinon <code>False</code>.
         */
        protected function isHttps() {
            $url = parse_url($this->uri);
            return ( !strstr($url['scheme'], "https") ) ? false : true ;
        }

        /**
         * Construit l'uri. Concaténation du serveur et de l'url.
         *
         * @return string L'uri courante
         */
        protected function getUri() {
            return $this->host . $this->url;
        }

        /**
         * Création d'un nouveau client REST.
         *
         * @param string $host L'hôte à questionner.
         * @param string $url L'url à questionner.
         * @return Client Une instance d'un client,
         */
        static public function query( $host, $url = "" ) {
            return new Client( $host, $url );
        }

        /**
         * Setter host
         *
         * @param string $host Nouvel hote
         * @return object $this Instance client courante.
         */
        public function host($host) {
            $this->host = $host;
            $this->uri = $this->getUri();
            return $this;
        }

        /**
         * Setter url
         *
         * @param string $url Url à questionner
         * @return object $this Instance client courante.
         */
        public function url($url) {
            $this->url = $url;
            $this->uri = $this->getUri();
            return $this;
        }

        /**
         * Test si une uri est valide. C'est à dire si la réponse HTTP contient le code 200.
         *
         * @return bool Vrai si l'uri renvoit un code HTTP 200 sinon false.
         */
        public function isValidUrl() {
            stream_context_set_default(
                array(
                    'http' => array(
                        'method' => 'HEAD'
                    )
                )
            );
            @get_headers($this->uri);
            $response_code = http_response_code();

            return ( $response_code !== 200 ) ? false : true ;
        }

        /**
         * Signe l'en tête d'une requête HTTP pour permettre une reqête REST nécessitant une autentification.
         *
         * @param String $user
         * @param String $passPhrase
         * @return Object $this
         */
        public function login( $user, $passPhrase ) {
            $this->user = $user;
            $this->logged = true;
            $this->signature = self::signature( $user, $passPhrase, now() );
            return $this;
        }

        /**
         * Logout de l'utilisateur courant
         *
         * @return object $this
         */
        public function logout() {
            $this->logged = false;
            $this->signature = null;
            return $this;
        }

        /**
         * Création du context de flux de la requête courante.
         *
         * @param String $method La méthode HTTP souhaitée.
         * @param array $params Les paramètres à envoyés avec la requête.
         * @return resource
         * @todo Accept : text/plain text/json  etc
         * @todo Autorization  https://en.wikipedia.org/wiki/List_of_HTTP_header_fields
         */
        protected function context( $method, $params = array() ) {
            $streamOptions = array(
                'http'=>array(
                    'method'=>$method,
                    'header'=>'Content-type: application/x-www-form-urlencode',
                    'host'=>$this->host
                )
            );

            if( $this->logged ) {
                $params = array_merge($params, array( "token"=> $this->signature,"user"=>$this->user, "timestamp"=> time() ) );
            }

            if( is_array($params) && !empty($params) ) {
                $params = json_encode( $params);
                $streamOptions['http']['content'] = $params;
            }
            return stream_context_create($streamOptions);
        }

        /**
         * Envoit une requête vers l'uri courante avec comme context de flux $context.
         *
         * @param resource $context Le contexte de la requête à executer.
         * @return bool|\StdClass
         */
        public function exec( $context ) {
            $answer = new \StdClass();
            if ( ( $stream = fopen( $this->uri, 'r', false, $context ) ) !== false){
                $content = stream_get_contents($stream);
                $header = stream_get_meta_data($stream);
                fclose($stream);
                $answer->header = $header;
                $answer->content = $content;
                return $answer;
            }
            else {
                return false;
            }
        }

        /**
         * Permet de signer une requête HTTP.
         *
         * @param string $user L'utilisateur courant
         * @param string $passPhrase La pass phrase associée à l'utilisateur courant
         * @param int $timestamp
         * @return string
         */
        static public function signature( $user, $passPhrase, $timestamp ) {
            // @todo le md5 devrait provenir de la bdd
            return  base64_encode( hash_hmac( "sha256", $user , $passPhrase . $timestamp ) ) ;
        }

    }
}
