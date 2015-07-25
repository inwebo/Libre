<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 19/07/15
 * Time: 18:27
 */

namespace Libre\Web\Rss;


use Libre\Web\Base;
use Libre\Web\Rss;
use Libre\Web\Rss\Item;

/**
 * Class Channel
    title	Titre du channel
    link	URL du site contenant le channel
    description	Description du channel
    language	Langue du channel
    copyright	Info sur le copyright du channel
    managingEditor	Mail de la personne responsable du contenu
    webMaster	Mail du webmaster
    pubDate	Date de publication
    lastBuildDate	Date de la dernière publication
    category	Catégorie à laquelle le channel appartient
    generator	Programme utilisé pour générer le channel
    docs	Lien vers la documentation du format utilisé dans le fichier RSS
    cloud	Permet à un programme de s'enregistrer pour être notifié des modifications de ce channel
    ttl	Time to live, avant le prochain rafraîchissement
    image	Image affichée avec le channel
    rating	note PICS
    textInput	Ajouter une zone de saisie de texte
    skipHours	Heures que les agrégateurs peuvent ignorer
    skipDays	Jours que les agrégateurs peuvent ignorer
 * @package Libre\Web\Rss
 */
class Channel extends Base{

    /**
     * @var \SplObjectStorage
     */
    protected $_items;

    /**
     * @return \SplObjectStorage
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param Item $items
     */
    public function setItems(Item $items)
    {
        $this->getItems()->attach($items);
    }

    protected function initItems()
    {
        $this->_items = new \SplObjectStorage();
    }

    /**
     * @param array $itemsArray
     */
    public function __construct($itemsArray)
    {
        $this->initItems();
        $this->initElements(array('title','link','description','language','copyright','managingEditor','webMaster','pubDate','lastBuildDate','category','generator','docs','cloud','ttl','image','rating','textInput','skipHours','skipDays'));
        if( is_array($itemsArray) ){
            foreach($itemsArray as $item=>$value)
            {
                $this->setElement($item, $value);
            }
        }
    }

}