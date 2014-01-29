<?php
$this->breadcrumbs=array(
	'Moje zamówienia',
);?>
<h1>Moje aktualne zamówienia</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'order-grid',
	'dataProvider'=>$model,
	'cssFile'=>Yii::app()->baseUrl . '/css/gridview/styles.css',
	'columns'=>array(
		'demonstration.category.Name',
		'demonstration.Name',
		'room.Building',
		'room.Number',
		array('name'=>'DateFrom','value'=>'Yii::app()->dateFormatter->format("d MMMM y,  H:mm", strtotime($data->DateFrom))'),
		array('name'=>'DateTo','value'=>'Yii::app()->dateFormatter->format("d MMMM y,  H:mm", strtotime($data->DateTo))'),
		array('class'=>'CButtonColumn','header'=>'Działania'),
	),
)); ?>

