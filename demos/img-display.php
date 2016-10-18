<?php
    include_once('header.php');
    use Libre\Img;
    $path = 'assets/pics/';
    $pics = $_GET['f'];

    $img = new Img($path . $pics);
    $img->display();