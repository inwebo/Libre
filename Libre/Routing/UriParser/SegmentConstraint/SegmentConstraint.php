<?php
namespace Libre\Routing\UriParser {

    use Libre\Routing\ISegmentComparable;

    class SegmentConstraint implements ISegmentComparable {

        protected $valid;
        protected $segmentUri;
        protected $segmentRoute;

        public function __construct( Segment $segmentUri, Segment $segmentRoute ) {
            $this->valid        = false;
            $this->segmentUri   = $segmentUri;
            $this->segmentRoute = $segmentRoute;
        }

        public function isValidMandatory() {
            return ($this->segmentRoute->isMandatory() && ($this->segmentUri->getSegment() === $this->segmentRoute->getSegment()) );
        }

        public function isValidSegment(){
            return $this->valid;
        }

        public function isStatic() {
            return ($this->segmentRoute->getSegment() === ":static");
        }

        public function isController() {
            return ($this->segmentRoute->getSegment() === ":controller");
        }

        public function isAction() {
            return ($this->segmentRoute->getSegment() === ":action");
        }

        public function isParam() {
            return ( ( strstr($this->segmentRoute->getSegment(),':id') !== false ) ? true : false );
        }

        public function isInstance() {
            return ($this->segmentRoute->getSegment() === ":instance");
        }

        public function isModule() {
            return ($this->segmentRoute->getSegment() === ":module");
        }

        public function getController() {
            return ($this->isController()) ? $this->segmentUri->getSegment() : null;
        }

        public function getAction() {
            return ($this->isAction()) ? $this->segmentUri->getSegment() : null;
        }

        public function getStatic() {
            return ($this->isStatic()) ? $this->segmentUri->getSegment() : null;
        }

        public function getParam() {
            return ($this->isParam()) ? $this->segmentUri->getSegment() : null;
        }

    }
}