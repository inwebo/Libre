<?php

namespace Libre\Helpers {

    class PaginationOutOfBondsIndex extends \Exception{}

    class Pagination
    {
        /**
         * @var int Total items
         */
        protected $_totalItems;
        /**
         * @var int Current page index (offset)
         */
        protected $_index;
        /**
         * @var int How many items by page
         */
        protected $_internalChunkSize;

        /**
         * @var int How many displayable pages
         */
        protected $_displayableIndexes;

        /**
         * @param int $index
         */
        public function index($index)
        {
            $this->setIndex($index);
        }

        /**
         * @return int
         */
        public function getTotalItems()
        {
            return $this->_totalItems;
        }

        /**
         * @param int $size
         */
        protected function setTotalItems($size)
        {
            $this->_totalItems = intval($size);
        }

        /**
         * @return int Current index
         */
        public function getIndex()
        {
            return $this->_index;
        }

        /**
         * Alias getIndex();
         * @return int
         */
        public function current()
        {
            return $this->getIndex();
        }

        /**
         * @param int $index
         */
        public function setIndex($index)
        {
            $this->_index = intval($index);
        }

        /**
         * @return int
         */
        public function getInternalChunkSize()
        {
            return $this->_internalChunkSize;
        }

        /**
         * @param int $limit
         */
        public function setInternalChunkSize($limit)
        {
            $this->_internalChunkSize = intval($limit);
        }

        /**
         * @return int
         */
        public function getDisplayableIndexes()
        {
            return $this->_displayableIndexes;
        }

        /**
         * @param int $displayableIndexes
         */
        public function setDisplayableIndexes($displayableIndexes)
        {
            $this->_displayableIndexes = intval($displayableIndexes);
        }

        /**
         * @param int $size
         * @param int $index
         * @param int $limit
         * @param int $displayableIndexes
         * @throws PaginationOutOfBondsIndex
         */
        public function __construct($size = 100, $index = 1, $limit = 25, $displayableIndexes = 21)
        {
            $this->setTotalItems($size);
            $this->setIndex($index);
            $this->setInternalChunkSize($limit);
            $this->setDisplayableIndexes($displayableIndexes);

            if( $this->current() > $this->getMax() )
            {
                throw new PaginationOutOfBondsIndex('Out of bounds index');
            }
        }

        /**
         * @return int Nombre de page disponible
         */
        public function total()
        {
            return (int) ceil($this->getTotalItems() / $this->getInternalChunkSize());
        }

        /**
         * Le nombre d'elements présent dans l'offset courant.
         */
        public function getCurrentChunkSize()
        {
            $array = array_chunk(array_fill(0, $this->getTotalItems(), null),$this->getInternalChunkSize());
            if( isset($array[$this->getIndex()]) )
            {
                return count($array[$this->getIndex()]);
            }
            else
            {
                return 0;
            }
        }

        /**
         * Min offset
         * @return int
         */
        public function getMin()
        {
            return 1;
        }

        /**
         * Max offset
         * @return int
         */
        public function getMax()
        {
            return $this->total();
        }

        /**
         * @return bool
         */
        public function gotNext()
        {
            return ($this->getIndex() * $this->getInternalChunkSize()) < $this->getTotalItems();
        }

        /**
         * @return bool
         */
        public function gotPrev()
        {
            return ($this->getIndex() > 1 && $this->getIndex() < $this->getMax());
        }

        /**
         * @return bool|int
         */
        public function getNextIndex()
        {
            if($this->gotNext())
            {
                return $this->getIndex()+1;
            }
            else
            {
                return false;
            }
        }

        /**
         * @return bool|int
         */
        public function getPrevIndex()
        {
            if($this->gotPrev())
            {
                return $this->getIndex()-1;
            }
            else
            {
                return false;
            }
        }

        /**
         * Calcul les limites de la requête sql
         *
         * <code>
         * SELECT * FROM table [LIMIT [offset,] lignes] ]
         * </code>
         *
         * @return mixed
         */
        public function sqlLimit() {
            $_limit['start'] = $this->getInternalChunkSize() * $this->getIndex() - $this->getInternalChunkSize();
            $_limit['end'] = $this->getInternalChunkSize() * $this->getIndex() - 1;
            return $_limit;
        }

        public function getPaginatedMenuBounds()
        {
            $result = new \StdClass();

            if($this->current() === $this->getMax())
            {
                $result->top = $this->getMax();
                $isBottom = $this->getMax() - $this->getDisplayableIndexes();
                if($isBottom >= 0)
                {
                    $result->bottom = $isBottom;
                }
                else
                {
                    $result->bottom = $this->getMin();
                }
            }
            elseif( $this->current() === $this->getMin() )
            {
                $result->bottom = $this->getMin();
                $isTop          = $this->getMin() + $this->getDisplayableIndexes();
                if($isTop < $this->getMax())
                {
                    $result->top = $isTop;
                }
                else
                {
                    $result->top = $this->getMax();
                }
            }
            else
            {
                $amplitude =
                    ($this->getDisplayableIndexes() % 2 === 0)?
                        ($this->getDisplayableIndexes() / 2 ):
                        (($this->getDisplayableIndexes()-1) / 2);

                $result->bottom = ($this->getIndex() - $amplitude > 0 ) ? $this->getIndex() - ($amplitude) : $this->getMin();
                $result->top    = ($this->getIndex() + $amplitude < $this->getMax() ) ? $this->getIndex() + $amplitude +1: $this->getMax();
            }
            return $result;
        }
    }
}