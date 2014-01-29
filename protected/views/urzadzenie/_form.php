<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'device-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Pola oznaczone <span class="required">*</span> są wymagane.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'EvidenceId'); ?>
		<?php echo $form->textField($model,'EvidenceId',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'EvidenceId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Name'); ?>
		<?php echo $form->textField($model,'Name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Description'); ?>
		<?php echo $form->textArea($model,'Description',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'Description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Instruction'); ?>
		<?php echo $form->textField($model,'Instruction',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'Instruction'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Place'); ?>
		<?php echo $form->textField($model,'Place',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Place'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Wstaw' : 'Aktualizuj'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->