<?php
$this->breadcrumbs=array(
	'Devices'=>array('index'),
	$model->Name=>array('view','id'=>$model->Id),
	'Edycja',
);

$this->menu=array(
	array('label'=>'Lista urządzeń', 'url'=>array('index')),
	array('label'=>'Wstaw urządzenie', 'url'=>array('wstaw')),
	array('label'=>'Pokaż urządzenie', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Zarządzaj urządzeniami', 'url'=>array('admin')),
);
?>

<h1>Edycja urządzenia <?php echo $model->Id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>