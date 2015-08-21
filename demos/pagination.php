<?php
namespace Libre{
    use Libre\Helpers\Pagination;
    include_once 'header.php';

    try{
        $a = array_fill(1,70,"");

        $p = new Pagination($a);

        var_dump($p->total());
        var_dump($p->page(3));

        class Pag
        {
            /**
             * @var int
             */
            protected $_size;
            /**
             * @var int
             */
            protected $_index;
            /**
             * @var int
             */
            protected $_limit;

            /**
             * @return int
             */
            public function getSize()
            {
                return $this->_size;
            }

            /**
             * @param int $size
             */
            public function setSize($size)
            {
                $this->_size = $size;
            }

            /**
             * @return int
             */
            public function getIndex()
            {
                return $this->_index;
            }

            /**
             * @param int $index
             */
            public function setIndex($index)
            {
                $this->_index = $index;
            }

            /**
             * @return int
             */
            public function getLimit()
            {
                return $this->_limit;
            }

            /**
             * @param int $limit
             */
            public function setLimit($limit)
            {
                $this->_limit = $limit;
            }

            /**
             * @param int $size
             * @param int $index
             * @param int $limit
             */
            public function __construct($size = 100, $index = 1, $limit = 25)
            {
                $this->setSize($size);
                $this->setIndex($index);
                $this->setLimit($limit);
            }

            /**
             * @return int Nombre de page disponible
             */
            public function total()
            {
                return (int) ceil($this->getSize() / $this->getLimit());
            }

            /**
             * Le nombre d'elements présent dans l'offset courant.
             */
            public function count()
            {
                $array = array_chunk(array_fill(0, $this->getSize(), null),$this->getLimit());
                return (count($array[$this->getIndex()]));
            }

            public function getMin()
            {
                return 1;
            }

            public function getMax()
            {
                return $this->total();
            }

            public function next()
            {
                return ($this->getIndex() * $this->getLimit()) < $this->getSize();
            }

            public function prev()
            {
                return ($this->getIndex() > 1 );
            }

            public function getNextIndex()
            {
                if($this->next())
                {
                    return $this->getIndex()+($this->total() - $this->getIndex());
                }
            }

            public function getPrevIndex()
            {
                if($this->prev())
                {
                    return $this->getIndex()-($this->total() - $this->getIndex());
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
                $_limit['start'] = $this->getLimit() * $this->getIndex() - $this->getLimit();
                $_limit['end'] = $this->getLimit() * $this->getIndex() - 1;
                return $_limit;
            }

            public function index($index)
            {
                $this->setIndex($index);
            }

            /**
             * @param $index
             */
            public function getIndexesByOffset($index)
            {
                if($index === $this->total())
                {

                }
            }
        }

        $page = new Pag(133,5);
        //var_dump($page);
        //echo $page->getIndexesByOffset(4);
        var_dump($page->count());
        //var_dump($page->total());
        //var_dump($page->next());
        //var_dump($page->prev());
        //var_dump($page->getNextIndex());
        //var_dump($page->getPrevIndex());
        //var_dump($page->sqlLimit());
        //var_dump($page->getIndexesByOffset(1));

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}