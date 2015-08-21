<?php
namespace Libre;

    include_once 'header.php';
    use Libre\Helpers\Upload;
    use Libre\Helpers\Upload\File;
    try{
        if( isset($_FILES) && !empty($_FILES) ) {
            $upload = new Upload("fichier",$_FILES,ASSETS."/up/",array("image/jpeg"));
            $upload->send();
        }
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
?>
<html>
<head></head>
<style>
    #holder {
        width: 50%;
        height: 20%;
        background-color: red;
    }
</style>
<body>
    <h1>Upload</h1>
    <?php if(!Upload::isSubmitted()){  ?>
    <form name="envoyer" action="sandbox.php" method="post" enctype='multipart/form-data' target="_self" >
        <fieldset>
            <legend>Fichier : Taille max du fichier <?php print(Upload::getMaxSize()); ?>o</legend>
            <ul>
                <li>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php print( Upload::maxSizeToBytes() ); ?>" />
                    <input name="fichier[]" type="file" />
                    <input name="fichier[]" type="file" />
                </li>
            </ul>
        </fieldset>
        <input name="soumis" type="hidden" value="1" />
        <input name="go" type="submit" value="Envoyer" />
    </form>
    <?php } else { ?>
    <p>
        <?php
        $uploaded = $upload->getUploadedFiles(File::STATEMENT_DONE);
        while( $uploaded->valid() ) {
            // var_dump($uploaded->current());
            $uploaded->next();
        }
        $uploaded = $upload->getUploadedFiles(File::STATEMENT_FILTERED);
        while( $uploaded->valid() ) {
            // var_dump($uploaded->current());
            $uploaded->next();
        }
        ?>
    </p>
    <?php } ?>
    <p id="holder">

    </p>
<script>

</script>
</body>
</html>