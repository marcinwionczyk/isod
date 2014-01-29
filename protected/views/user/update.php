<?php
$this->breadcrumbs=array(
	'Użytkownicy'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Aktualizuj',
);

$this->menu=array(
	array('label'=>'Lista użytkowników', 'url'=>array('index')),
	array('label'=>'Nowy użytkownik', 'url'=>array('create')),
	array('label'=>'Aktualizuj dane użytkownika', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Zarządzaj użytkownikami', 'url'=>array('admin')),
);
?>

<h1>Aktualizuj dane użytkownika <?php echo $model->id; ?></h1>
<?php echo $this->renderPartial('_form', array('model'=>$model,'roles'=>$roles)); ?>