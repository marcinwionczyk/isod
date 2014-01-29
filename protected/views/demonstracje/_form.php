<h4>Formularz dodania demonstracji do zamówienia</h4>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-view-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	)); ?>
	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'Demonstration_Id',array('value'=>$demonstrationid)) ?>
	<?php echo $form->hiddenField($model,'user_id',array('value'=>$userid))?>
	<div class="row">
	<?php echo $form->LabelEx($model, 'Room_Id'); ?>
	<?php echo $form->DropDownList($model, 'Room_Id', $rooms); ?>
	</div>
	<div class="row">
	<?php echo $form->LabelEx($model,'DateFrom'); ?>
	<?php 
		$this->widget('ext.jui.EJuiDateTimePicker', array(
			'model'     => $model,
			'attribute' => 'DateFrom',
			// additional javascript options for the datetime picker plugin
			 'options' => array( 'dateFormat' => 'yy-mm-dd', 'hourMin' => 7, 'stepMinute' => 15, 'hourMax' => 20),
			 'htmlOptions' => array( 'style' => 'height:20px;' ), ));
	?>
	<?php echo $form->error($model,'DateFrom'); ?>
	</div>
	<div class="row">
	<?php echo $form->LabelEx($model,'DateTo'); ?>
	<?php 
		$this->widget('ext.jui.EJuiDateTimePicker', array(
			'model'     => $model,
			'attribute' => 'DateTo',
			// additional javascript options for the datetime picker plugin
			 'options' => array( 'dateFormat' => 'yy-mm-dd', 'hourMin' => 7, 'stepMinute' => 15, 'hourMax' => 20),
			 'htmlOptions' => array( 'style' => 'height:20px;' ), ));
	?>
	<?php echo $form->error($model,'DateTo'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Zapisz'); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>