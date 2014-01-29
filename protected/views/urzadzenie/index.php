<?php
$this->breadcrumbs=array(
	'Urządzenia',
);

$this->menu=array(
	array('label'=>'Wstaw urządzenie', 'url'=>array('wstaw')),
	array('label'=>'Zmień dane urządzenia', 'url'=>array('admin')),
);
?>

<h1>Urządzenia</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
