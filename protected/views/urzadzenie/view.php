<?php
$this->breadcrumbs=array(
	'Urządzenie'=>array('index'),
	$model->Name,
);

$this->menu=array(
	array('label'=>'Lista urządzeń', 'url'=>array('index')),
	array('label'=>'Wstaw urządzenie', 'url'=>array('wstaw')),
	array('label'=>'Aktualizuj dane urządzenia', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Usuń urządzenie z listy', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id),'confirm'=>'Czy na pewno chcesz usunąć to urządzenie?')),
	array('label'=>'Zarządzanie urządzeniami', 'url'=>array('admin')),
);
?>

<h1>Dane urządzenia o Id = <?php echo $model->Id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id',
		'EvidenceId',
		'Name',
		'Description',
		'Instruction',
		'Place',
	),
)); ?>
