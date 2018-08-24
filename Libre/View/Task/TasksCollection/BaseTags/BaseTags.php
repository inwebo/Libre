<?php

namespace Libre\View\Task\TasksCollection;

/**
 * Collection de tâches.
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

use Libre\View\Parser\Logic\VarDump;
use Libre\View\Task;
use Libre\View\Parser\Tag;
use Libre\View\Parser\Logic\Conditions;
use Libre\View\Parser\Logic\Constant;
use Libre\View\Parser\Logic\Escapement;
use Libre\View\Parser\Logic\Includer;
use Libre\View\Parser\Logic\Loop;
use Libre\View\Parser\Logic\Template;
use Libre\View\Parser\Logic\Vars;

class BaseTags extends \SplObjectStorage
{

    protected $dataProvider;

    /**
     * Setter des tâches de recherche des tags de bases
     */
    public function __construct($dataProvider)
    {

        $this->dataProvider = $dataProvider;

        // Var_dump
        $this->attach(new Task(new Tag(Tag::VAR_DUMP), new VarDump($this->dataProvider)));

        // No parse pattern {noparse}{/noparse}
        $this->attach(new Task(new Tag(Tag::ESCAPEMENT), new Escapement($this->dataProvider)));

        // Loop pattern {loop="array"}{/loop}
        $this->attach(new Task(new Tag(Tag::LOOP), new Loop($this->dataProvider)));

        // variable pattern {$vars}
        $this->attach(new Task(new Tag(Tag::VARS), new Vars($this->dataProvider)));

        // Constante pattern {CONSTANTE}
        $this->attach(new Task(new Tag(Tag::CONSTS), new Constant($this->dataProvider)));

        // Include pattern {includer="fileToInclude"}
        $this->attach(new Task(new Tag(Tag::INCLUDER), new Includer($this->dataProvider)));

        // Tpl pattern {tpl="tplToInclude"}
        $this->attach(new Task(new Tag(Tag::TEMPLATE), new Template($this->dataProvider)));

        // Href
        //$this->attach(new \Libre\Views\Template\Task(new \Libre\Views\Template\Tag(PATTERN_HREF), new \Libre\Views\Template\Logic\LogicHref()));

        // If pattern {if="condition"}true{else}false{fi}
        //$this->attach( new Task( new Tag(PATTERN_IF), new Conditions($this->dataProvider) ) );
    }

}
