<?php
$this->breadcrumbs=array(
	'Demonstracje'=>array('demonstracje/kategoria'), 
	 $category=>array('demonstracje/kategoria','id'=>$categoryId),
	 $name
);?>
<div class="view">
<h2><?php echo $name ?></h2>
<h4>Opis</h4>
	<p><?php echo $description ?></p>
</div>
<div class="view">
<h2>Formularz zamówienia demonstracji</h2>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-view-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
)); ?>

	<p class="note">Pola oznaczone <span class="required">*</span> są wymagane. Domyślną datą zamówienia jest dzień jutrzejszy lub data podawana we wcześniejszych zamówieniach od chwili zalogowania.</p>
	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model, 'Room_Id'); ?>
		<?php echo $form->DropDownList($model, 'Room_Id', $rooms); ?>
	</div>
	
	<div class="row">
	<?php echo $form->labelEx($model,'DateFrom'); ?>
	<?php 
		$this->widget('ext.jui.EJuiDateTimePicker', array(
			'model'     => $model,
			'attribute' => 'DateFrom',
			// additional javascript options for the datetime picker plugin
			 'options' => array( 'dateFormat' => 'yy-mm-dd', 'timeFormat' => 'hh:mm', 'hourMin' => 7, 'stepMinute' => 15, 'hourMax' => 20, 'hourGrid'=> 2, 'minuteGrid'=>15, 'minDate'=>$minDate),
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
			 'options' => array( 'dateFormat' => 'yy-mm-dd', 'timeFormat' => 'hh:mm', 'hourMin' => 7, 'stepMinute' => 15, 'hourMax' => 20, 'hourGrid'=> 2, 'minuteGrid'=>15, 'minDate'=>$minDate),
			 'htmlOptions' => array( 'style' => 'height:20px;' ), ));
	?>
	<?php echo $form->error($model,'DateTo'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Zapisz'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>