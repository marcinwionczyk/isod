<?php
$this->breadcrumbs=array(
	'Moje zamówienia'=>array('zamowienie/index'), 
	 $model->demonstration->Name,
);?>
<?php
$this->menu=array(
	array('label'=>'Lista zamówień', 'url'=>array('index')),
	array('label'=>'Podgląd zamówienia', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Usuń zamówienie', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id))),
);
?>
<?php echo  $this->renderPartial('_demonstration',array('model'=>$model)); ?>
<div class="view">
<h4>Aktualizacja zamówienia</h4>
	<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-update-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Pola oznaczone <span class="required">*</span> są wymagane.</p>
	<?php echo $form->errorSummary($model); ?>

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
			 'options' => array( 'dateFormat' => 'yy-mm-dd','timeFormat'=>'hh:mm', 'hourMin' => 7, 'stepMinute' => 15, 'hourMax' => 20, 'minDate'=>$minDate),
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
			 'options' => array( 'dateFormat' => 'yy-mm-dd', 'timeFormat'=>'hh:mm', 'hourMin' => 7, 'stepMinute' => 15, 'hourMax' => 20, 'minDate'=>$minDate),
			 'htmlOptions' => array( 'style' => 'height:20px;' ), ));
	?>
	<?php echo $form->error($model,'DateTo'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Aktualizuj'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div><!-- view -->
