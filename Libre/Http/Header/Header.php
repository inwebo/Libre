<?php
namespace Libre\Http {

    /**
     * Class Header
     * @see http://en.wikipedia.org/wiki/List_of_HTTP_header_fields
     * @package Libre\Http
     */
    class Header {

        /**
         * @param bool $global Allow X-domain request from *
         * @param array $allowedVerbs
         */
        public static function allowXDomain($global = true, $allowedVerbs = array('POST','GET', 'OPTIONS', 'DELETE', 'PUT')) {
            ($global) ? header('Access-Control-Allow-Origin: *') : header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Credentials: true");
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
            header('Access-Control-Allow-Methods: ' . implode(', ', $allowedVerbs));
        }

        public static function disableCache() {
            header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
            header('Expires: Thu, 14 Apr 1982 05:00:00 GMT');
            header('Pragma: no-cache');
            header('ETag: ' . md5( time() ) );
        }


        public static function redirect( $url, $delay = 0, $message = "You will be redirected" ) {
            if( $delay > 0 ) {
                header('Refresh: '. $delay .'; url=' . $url);
                print $message;
                exit;
            }
            else {
                header("Status: 200");
                header('Location: ' . $url);
                exit;
            }
        }

        public static function poweredBy( $name ) {
            header('X-Powered-By: '. $name);
        }

        public static function contentLanguage( $cl ) {
            header('Content-language: '. $cl);
        }

        public static function lastModified($birth) {
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', $birth).' GMT');
        }

        /**
         * Connexion persistente pour les imgages, SOAP sont inutiles...
         */
        public static function disableKeepAlive() {
            header('Connection: Close');
        }

        public static function contentLength($size) {
            header('Content-Length: '.$size);
        }

        public static function expires( $birth, $life ) {
            $life = $birth + $life;
            header('Expires: ' . gmdate('D, d M Y H:i:s', $life));
        }

        public static function neverExpires() {
            $then = gmstrftime("%a, %d %b %Y %H:%M:%S GMT", time() + 365*86440);
            header('HTTP/1.1 304 Not Modified');
            header("Expires: $then");
        }

        public static function fromCache($birth,$life,$content) {
            self::lastModified($birth);
            self::expires($birth, $life);
            self::contentLength(strlen($content));
            self::notModified();
        }

        public static function noCache() {
            self::disableCache();
            self::disableKeepAlive();
        }

        public static function hideInfos() {
            header('Server: ');
            header('X-Powered-By: ');
        }

        public static function set($key,$value) {
            $_key = $key;
            $_key = ( !is_null($_key) ) ? ucfirst( strtolower($_key) ) : null;
            header($_key . ': ' . $value);
        }

        public static function movedPermanently() {
            header('HTTP/1.1 301 Moved Permanently');
        }

        public static function forbidden() {
            header('HTTP/1.1 403 Forbidden');
        }

        public static function notFound() {
            header('HTTP/1.1 404 Not Found');
        }

        public static function notModified() {
            header('HTTP/1.1 304 Not Modified');
        }

        public static function unauthorized() {
            header('HTTP/1.1 401 Unauthorized');
        }

        public static function badRequest() {
            header('HTTP/1.1 400 Bad Request');
        }

        public static function methodNotAllowed() {
            header('HTTP/1.1 405 Bad Request');
        }

        public static function serverError() {
            header('HTTP/1.1 500 Server Error');
        }
        public static function error($httpErrorNumber) {
            switch( $httpErrorNumber ) {
                default:
                case '404':
                    header("HTTP/1.0 404 Not Found");
                    break;
            }
        }

        public static function json() {
            header('Content-type: application/json; charset=utf-8');
        }

        public static function xml() {
            header('Content-type: text/xml; charset=utf-8');
        }

        public static function html() {
            header('Content-type: text/html; charset=utf-8');
        }

        public static function textPlain() {
            header('Content-type: text/plain; charset=utf-8');
        }

        public static function javascript() {
            header('Content-type: application/javascript');
        }

        public static function csv() {
            header('Content-type: text/csv');
        }

        public static function css() {
            header('Content-type: text/css');
        }

        // Type Default
        public static function octetStream() {
            header('Content-type: application/octet-stream');
        }

        // Type Images
        public static function gif() {
            header('Content-type: image/gif');
        }

        public static function jpeg() {
            header('Content-type: image/jpg');
        }

        public static function png() {
            header('Content-type: image/png');
        }

        public static function tiff() {
            header('Content-type: image/tiff');
        }

        public static function ico() {
            header('Content-type: image/vnd.microsoft.icon');
        }

        public static function svg() {
            header('Content-type: image/svg+xml');
        }

        public static function authBasic() {

        }

    }
}