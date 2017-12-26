<?php
/**
 * inwebo
 */
namespace Libre\Helpers;

// Aliases
use \Iterator;
use \Closure;

/**
 * Class CrossIterator
 *
 * Classe utilitaire de parcours d'objet implémentants l'interface Iterator.
 * Présente une seule méthode dont le métier est le parcours de la collection, pour chaque éléments de cette collection
 * exécute une fonction de callback dont le seul paramètre est l'objet courant de la collection.
 *
 * <code>
 *  // Create an iterable instance & populate it
 * $stack = new \SplQueue();
 * $stack->push(1);
 * $stack->push(2);
 *
 * // Cross our stack
 * // For each elements display its value
 * Libre\Helpers\CrossIterator::callback($stack, function($current){
 *      echo $current;
 * });
 * </code>
 *
 * @category   Libre
 *
 * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
 *
 * @license    http://framework.zend.com/license   BSD License
 *
 * @link       https://github.com/inwebo/Libre
 */
class CrossIterator
{
    /**
     * @param Iterator $iterator   Iterable object to cross over.
     * @param Closure  $callback   For each elements call callback function with one parameter : the current element.
     * @param bool     $autoRewind Rewind iterable object before the cross over.
     */
    static public function callback(Iterator $iterator, Closure $callback, $autoRewind = true)
    {
        ($autoRewind) ? $iterator->rewind() : null;
        while ($iterator->valid()) {
            $callback->__invoke($iterator->current());
            $iterator->next();
        }
        ($autoRewind) ? $iterator->rewind() : null;
    }
}
