<?php $this->beginContent('//layouts/main'); ?>
<div class="span-7">
	<div id="sidebar">
		<?php
		$this->widget('CategoriesWidget');
		?>
	</div><!-- sidebar -->
</div>
<div class="span-18 last">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>