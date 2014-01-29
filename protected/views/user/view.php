<?php
$this->breadcrumbs=array(
	'Użytkownicy'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Lista użytkowników', 'url'=>array('index')),
	array('label'=>'Nowy użytkownik', 'url'=>array('create')),
	array('label'=>'Aktualizuj dane użytkownika', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Usuń użytkownika', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Czy jesteś pewien (pewna), że chcesz usunąć tego użytkownika?')),
	array('label'=>'Zarządzaj użytkownikami', 'url'=>array('admin')),
);
?>

<h1>Dane użytkownika <?php echo $model->username; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'name',
		'role.name'
	),
)); ?>
