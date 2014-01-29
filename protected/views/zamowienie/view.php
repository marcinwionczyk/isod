<?php
$this->breadcrumbs=array(
	'Moje zamówienia'=>array('zamowienie/index'), 
	 $model->demonstration->Name,
);?>
<?php
$this->menu=array(
	array('label'=>'Lista zamówień', 'url'=>array('index')),
	array('label'=>'Aktualizuj zamówienie', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Usuń zamówienie', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id))),
);
?>
<?php echo  $this->renderPartial('_demonstration',array('model'=>$model)); ?>
<div class="view">
	<h4>Dane zamówienia: </h4>
	<b>Budynek:</b> <?php echo $model->room->Building ?><br/>
	<b>Numer sali:</b> <?php echo $model->room->Number ?><br/>
	<b>Dzień i godzina wypożyczenia demonstracji: </b>
	<?php echo Yii::app()->dateFormatter->format("d MMMM y,  h:mm", strtotime($model->DateFrom)) ?><br/>
	<b>Dzień i godzina oddania demonstracji: </b>
	<?php echo Yii::app()->dateFormatter->format("d MMMM y,  h:mm", strtotime($model->DateTo)) ?>
</div>

