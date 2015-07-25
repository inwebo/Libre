<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 19/07/15
 * Time: 18:27
 */

namespace Libre\Web\Rss;


use Libre\Web\Base;

/**
 * Class Item
 *
title	Titre de l'item
link	URL de l'item
description	Description de l'item
author	Mail de l'auteur de l'item
category	Catégorie à laquelle l'item appartient
comments	Lien vers une page de ccommentaires sur l'item
enclosure	Objet media attaché à l'item
guid	Texte qui identifie de manière unique cet item
pubDate	Date de publication
source	Channel auquel l'item appartient

 * @package Libre\Web\Rss
 */
class Item extends Base{

    public function __construct($itemsArray)
    {
        $this->initElements(array('title','link','description','author','category','comments','enclosure','guid','pubDate','source'));
        if( is_array($itemsArray) ){
            foreach($itemsArray as $item=>$value)
            {
                $this->setElement($item, $value);
            }
        }

    }

}