<?php
namespace Libre\View\Parser\Logic;

/**
 * Class métier à appliqué sur un operateur de comparaison.
 * 
 * A noté que le typage est conservé dans le view bag. Et que par conséquent l'
 * operateur indentique === est diponible.
 *
 * @category   Libre
 * @package    View
 * @subpackage Template
 * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @link       https://github.com/inwebo/Template
 * @since      File available since Beta
 */

use Libre\View\Parser\Logic;

class LogicComparison extends Logic {

    /**
     * Pile de tâches à effectuer.
     * @vars SplObjectStorage
     */
    public $tasksCollection;

    /**
     * Operateur de comparaison en cours.
     * @vars SplObjectStorage
     */
    public $operator;

    /**
     * La chaine d'entrée a traiter.
     * @vars string
     */
    public $subject;

    /**
     * Les operands à comparer
     * @vars SplObjectStorage
     */
    public $operands;

    /**
     * Applique une classe métier LogicComparison au Tag if
     *
     * Retourne le resultat de la comparaison de deux variables selon un opérateur.
     *
     * @param array $subject Un tableau de retour de preg_match_all
     * @return bool|void Le contenu fichier template modifié par une fonction pcre
     */
    public function process($subject) {
        $this->subject = $subject;
        $this->tasksCollection = new TasksComparison(new LogicVar());
        foreach ($this->tasksCollection as $task) {
            if (preg_match($task->tags->pattern, $this->subject, $this->operator)) {
                $this->operator = $this->operator[1];
                $this->operands = explode($this->operator, $this->subject);
                $i = 0;
                foreach ($this->operands as $operand) {
                    $memberName = preg_replace_callback(PATTERN_VAR, array($task->logic, "getMemberName"), $operand);
                    $this->operands[$i] = self::$ViewBag->$memberName;
                    $i++;
                }

                switch ($this->operator) {
                    case '<' :
                        return $this->operands[0] < $this->operands[1];
                        break;

                    case '>' :
                        return $this->operands[0] > $this->operands[1];
                        break;

                    case '==' :
                        return $this->operands[0] == $this->operands[1];
                        break;

                    case '===' :
                        return $this->operands[0] === $this->operands[1];
                        break;

                    case '>=' :
                        return $this->operands[0] >= $this->operands[1];
                        break;

                    case '<=' :
                        return $this->operands[0] <= $this->operands[1];
                        break;

                    case '!=' :
                        return $this->operands[0] != $this->operands[1];
                        break;
                    case '!==' :
                        return $this->operands[0] !== $this->operands[1];
                        break;

                    default :
                        Parser::$trace[] = "Comparison operator $this->operator unknow";
                        break;
                }
                return true;
            }
        }
    }

}