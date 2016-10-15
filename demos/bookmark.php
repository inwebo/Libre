<?php
namespace Libre{

    include_once 'header.php';

    include_once '../preprod/Modules/Bookmarks/Models/Bookmark/Tags/Tags.php';
    include_once '../preprod/Modules/Bookmarks/Models/Bookmark/Bookmark.php';


    use Libre\Database\Driver\MySql;
    use Libre\Database\Entity;

    use Libre\Modules\Bookmarks\Models\Bookmark;
    use Libre\Modules\Bookmarks\Models\Bookmark\tags;

    try{

        $mySql       = array(
            'localhost',
            'www.inwebo.net',
            'root',
            'root'
        );

        $querySelect =  'SELECT * FROM Bookmarks' ;

        $sql = new MySql(
            $mySql[0],
            $mySql[1],
            $mySql[2],
            $mySql[3]
        );

        Bookmark::setConfiguration($sql, 'id', 'Bookmarks');

        $b = Bookmark::load(2701);
        var_dump($b);

        $c = Bookmark::build('http://www3.tests.fr', 'titre', 'essai arf', '<p>!</p>');
        var_dump($c);
        $c->save();

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}


