<?php
$this->breadcrumbs=array(
	'Urzadzenia'=>array('index'),
	'Zarządzanie',
);

$this->menu=array(
	array('label'=>'Lista urządzeń', 'url'=>array('index')),
	array('label'=>'Wstaw urządzenie', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('device-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Zarządzanie urządzeniami</h1>

<p>
Aby określić jakie porównanie ma być zrobione, opcjonalnie można wpisać operator porównania (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> lub <b>=</b>) na początku każdego wyszukiwanego wyrazu.
</p>

<?php echo CHtml::link('Wyszukiwanie zaawansowane','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'device-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'Id',
		'EvidenceId',
		'Name',
		'Description',
		'Instruction',
		'Place',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
