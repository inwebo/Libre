<?php

$directory = '../Libre/';

try
{
    /*** check if we have a valid directory ***/
    if( !is_dir($directory) )
    {
        throw new Exception('Directory does not exist!'."\n");
    }

    /*** check if we have permission to rename the files ***/
    if( !is_writable( $directory ))
    {
        throw new Exception('You do not have renaming permissions!'."\n");
    }

    /**
     * @param SplFileInfo $old
     * @return string
     */
    function rfile( $old )
    {
        $new = $old->getPath() . DIRECTORY_SEPARATOR . ucfirst(str_replace('class.','',$old->getBasename())) ;
        return $new;
    }

    function rdir( $old )
    {
        $new = $old;
        return $new;
    }

    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
        $directory,
        FilesystemIterator::SKIP_DOTS
        | FilesystemIterator::UNIX_PATHS
    ));
    /*** loop directly over the object ***/
    while($it->valid())
    {
                        /* @var SplFileInfo $current */
                $current = $it->current();
            //var_dump($current->isDir());
//                echo "<hr>";


                if( is_file($it->current()) )
                {
                    if( ($current->getExtension() === 'md') )
                    {
                        $old = $current;
                        var_dump($old->getFilename());
                        //$new = rfile($current);
                        //echo($old . ' > '.$new ."<hr>");
                        //rename ($old, $new);
                    }
                }

                if(is_dir($it->current())) {
                    //var_dump($current->getPath());
                    //var_dump($current->getBasename());
                    //var_dump($current->getFilename());
                    //var_dump($current->getExtension());
                }

                    /**
                    $old_file = $directory.'/'.$it->getSubPathName();
                    $new_file = $directory.'/'.$it->getSubPath().'/'.safe_names($it->current());
                    rename ($old_file, $new_file);
                    echo 'Renamed '. $directory.'/'.$it->getSubPathName() ."\n";
                     **/



        /*** move to the next iteration ***/
        $it->next();
    }

    /*** when we are all done let the user know ***/
    //echo 'Renaming of files complete'."\n";
}
catch(Exception $e)
{
    echo $e->getMessage()."\n";
}
$root = $directory;

$iter = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST,
    RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
);

/**
 * @param SplFileInfo $old
 * @return mixed
 */
/*
function r($old)
{

    //echo $old->getBasename().'<br>';
    $new = $old->getPath(). DIRECTORY_SEPARATOR . ucfirst($old->getBasename()) ;
    return $new;
}

$paths = array($root);
foreach ($iter as $path => $dir) {
    if ($dir->isDir()) {
        //$paths[] = $path;
        //var_dump($dir);
        echo $dir . ">" .
            r($dir) . '<br>';
        rename($dir,r($dir));
    }
}
*/
//print_r($paths);