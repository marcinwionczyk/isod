<?php
$this->breadcrumbs=array(
	'Urządzenie'=>array('index'),
	'wstaw',
);

$this->menu=array(
	array('label'=>'Lista urządzeń', 'url'=>array('index')),
	array('label'=>'Zarządzaj urządzeniami', 'url'=>array('admin')),
	
);
?>

<h1>Wstaw nowe urządzenie</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>