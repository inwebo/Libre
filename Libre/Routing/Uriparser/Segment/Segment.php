<?php

namespace Libre\Routing\UriParser {

    class Segment {

        /**
         * @var string Segment de l'uri.
         */
        protected $rawSegment;

        /**
         * @var string Segment de l'uri sans les []
         */
        protected $segment;

        /**
         * @var bool Est il un segment obligatoire dans l'uri.
         */
        protected $mandatory;

        /**
         * @var bool Si est un paramétre ( pour être un parametre il doit commencer par [:id
         */
        protected $isParam;

        /**
         * @var mixed|null Si isNamed alors contient le nom du parametre
         */
        protected $paramName;

        /**
         * @var bool Il y a t il une contrainte de type sur le segment courant.
         */
        protected $isTyped;

        /**
         * @var null|string So isTyped alors le type doit être int|regex
         */
        protected $type;

        /**
         * @var bool La contrainte du segment est validé par une regex.
         */
        protected $isRegex;

        /**
         * @var string Une regex de validation du segment.
         */
        protected $regex;

        /**
         * @param string $rawSegment Un segment de l'URI.
         */
        public function __construct( $rawSegment ) {
            $this->rawSegment       = $rawSegment;
            $this->segment          = trim( $rawSegment, '[]' );
            $this->mandatory        = $this->isMandatory();
            $this->isParam          = $this->isParam();
            $this->paramName        = ( $this->isParam ) ? $this->setParamName() : null;
            $this->isTyped          = $this->isTypedParam();
            $this->type             = ( $this->isTyped ) ? $this->getParamType() : null ;
            $this->isRegex          = $this->isRegex();
            $this->regex            = ( $this->isRegex ) ? $this->setRegex() : null ;
        }

        public function getSegment() {
            return $this->segment;
        }

        public function getRawSegment() {
            return $this->rawSegment;
        }

        public function isMandatory() {
            return ($this->rawSegment[0] !== "[");
        }

        public function isParam() {
            return ( strpos(  $this->segment, ':id|' ) !== false ) ? true :false;
        }

        public function getParamName() {
            return $this->paramName;
        }

        public function isNamed(){
            return !is_null($this->paramName);
        }

        protected function setParamName() {
            $segmentAsArray = explode( '|', $this->segment );
            $segment        = $segmentAsArray[1];

            if( $this->isTypedParam() ) {
                $segment = preg_replace('#\({1}(regex|int)\){1}#','',$segment);
            }

            if( $this->isRegex() ) {
                $segment = preg_replace('#\#(.*)\##','',$segment);
            }

            return $segment;

        }

        public function isTypedParam() {
            return (preg_match('#\({1}(.*)\){1}#', $this->segment) === 0) ? false : true;
        }

        public function getParamType() {
            $_segment = preg_replace('#\#(.*)\##','',$this->segment);
            preg_match('#\((.*)\)#', $_segment,$match);
            if( isset($match[1]) ) {
                return $match[1];
            }
        }

        public function isRegex() {
            return (bool)preg_match('#\#{1}(.*)\#{1}#', $this->segment);
        }

        public function getRegex() {
            return $this->regex;
        }

        protected function setRegex() {
            if( $this->isRegex() ) {
                preg_match('#\#{1}(.*)\#{1}#', $this->segment, $match);
                return $match[0];
            }
        }

        public function validateData( $data ) {
            switch( $this->type ) {
                case 'int':
                    return !preg_match('#[a-zA-Z]#', $data );
                    break;

                case 'regex':
                    preg_match_all('#\#(.*)\##', $this->segment, $match);
                    if(isset($match[0][0])) {
                        $result = preg_match($match[0][0], $data );
                        $this->regex = $match[0][0];
                        return ($result === 0) ? false : true;
                    }
                    break;
            }
        }

    }
}