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
<?php \Libre\Helpers::getJsAsTags(true, true); ?>
<?php new Libre\Modules\Foo\Core\Bar(); ?>
