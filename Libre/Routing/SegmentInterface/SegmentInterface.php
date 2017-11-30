<?php
/**
 * Inwebo
 */
namespace Libre\Routing;

/**
 * Interface SegmentInterface
 */
interface SegmentInterface
{
    /**
     * @return array
     */
    public function toSegments();

    /**
     * @return int
     */
    public function countSegments();
}
