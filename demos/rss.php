<?php
namespace Libre;

    include_once 'header.php';
    use Libre\Web\Rss\Reader;
    try{
        $channel = new \Libre\Web\Rss\Channel(array('title'=>'test'));
        //var_dump($channel);
        $channel = new \Libre\Web\Rss(array('title'=>'test'));
        $item = new \Libre\Web\Rss\Item(array('title'=>'arf'));
        $channel->addItem($item);
        $channel->toDom();
        //var_dump($item);

        //echo $channel->getDom()->saveXML();


        $reader = new Reader('http://www.smashingmagazine.com/feed/');
        //$reader->getItems();

        $channel = $reader->getChannel();
        //var_dump($channel);
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }

?>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h1><a href="<?php echo $channel->getElement('link') ?>"><?php echo $channel->getElement('title') ?></a>, <?php echo $channel->getElement('lastBuildDate') ?> (<?php echo $channel->getElement('language') ?>)</h1>
<p>
    <?php echo $channel->getElement('description') ?>
</p>
<ul>
    <?php
        while($channel->getItems()->valid())
        {
            $current = $channel->getItems()->current();
            ?>
            <!-- <li><?php var_dump($current) ?></li> -->
            <li>
                <h2><a href="<?php echo $current->getElement('link') ?>"><?php echo $current->getElement('title') ?></h2></a>
                <p>
                    <?php echo $current->getElement('description') ?>
                </p>
            </li>
    <?php
            //var_dump($current);
            $channel->getItems()->next();
        }
    ?>
</ul>
</body>
</html>