<?php
$this->breadcrumbs=array(
	'Użytkownicy'=>array('index'),
	'Stwórz',
);

$this->menu=array(
	array('label'=>'Lista użytkowników', 'url'=>array('index')),
	array('label'=>'Zarządzaj użytkownikami', 'url'=>array('admin')),
);
?>

<h1>Nowy użytkownik</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'roles'=>$roles)); ?>