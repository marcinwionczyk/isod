<?php
$this->breadcrumbs=array(
	'Użytkownicy',
);

$this->menu=array(
	array('label'=>'Nowy użytkownik', 'url'=>array('create')),
	array('label'=>'Zarządzaj użytkownikami', 'url'=>array('admin')),
);
?>

<h1>Użytkownicy</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
