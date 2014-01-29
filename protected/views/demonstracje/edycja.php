<div class="form">
<h1>Nowa demonstracja</h1>
<?php echo CHtml::beginForm(); ?>
<?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<b>Kategoria: </b> <?php echo $model->category->Name ?>
		<br/>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'name'); ?>
		<?php echo CHtml::activeTextField($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'description'); ?>
		<?php $this->widget('ext.kindeditor.KindEditorWidget',
                    array(
                        'model'=>$model,
                        'attribute'=>'description',
                    	'items' => array('fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            'insertunorderedlist', '|', 'image', 'link'))); ?>
		<?php echo CHtml::activeTextArea($model,'description',array('rows'=>25, 'cols'=>80)); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Zapisz'); ?>
	</div>

<?php echo CHtml::endForm(); ?>
</div>