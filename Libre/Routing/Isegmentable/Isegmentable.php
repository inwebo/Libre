<?php
namespace Libre\Routing {
    interface ISegmentable {
        public function toSegments();
        public function countSegments();
    }
}