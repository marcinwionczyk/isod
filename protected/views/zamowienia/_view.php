<div class="view">
<?php 
$this->widget('zii.widgets.CDetailView', array(
		'data'=>$data,
		'attributes'=>array(
				array('name'=>'DateFrom','value'=>Yii::app()->dateFormatter->format("H:mm", strtotime($data->DateFrom)),'label'=>'Godzina rozpoczęcia demonstracji'),
				array('name'=>'DateTo','value'=>Yii::app()->dateFormatter->format("H:mm", strtotime($data->DateTo)), 'label'=>'Godzina zakończenia demonstracji'),
				'room.Building',
				'room.Number',
				'user.name',
				'demonstration.Name',
				'demonstration.Description:html'
				),
		));
?>
</div>
