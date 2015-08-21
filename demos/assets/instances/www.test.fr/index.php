<h1>layout : index.php</h1>
<p>
    <?php $this->renderPartial('body'); ?>
</p>
<p>
    <?php var_dump($this->foo);?>
</p>
<p>
    <?php var_dump(\Libre\Session::this());?>
</p>