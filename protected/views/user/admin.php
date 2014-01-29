<?php
$this->breadcrumbs=array(
	'Użytkownicy'=>array('index'),
	'Zarządzaj',
);

$this->menu=array(
	array('label'=>'Lista Użytkowników', 'url'=>array('index')),
	array('label'=>'Nowy Użytkownik', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Zarządzaj Użytkownikami</h1>

<p>
Aby porównać wartości, możesz opcjonalnie na początku każdej wartości wprowadzić operator porównania (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
lub <b>=</b>).
</p>

<?php echo CHtml::link('Zaawansowane wyszukiwanie','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',
		'username_alt',
		'name',
		'role.name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
