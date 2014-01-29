<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id), array('view', 'id'=>$data->Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('EvidenceId')); ?>:</b>
	<?php echo CHtml::encode($data->EvidenceId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Name')); ?>:</b>
	<?php echo CHtml::encode($data->Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Description')); ?>:</b>
	<?php echo CHtml::encode($data->Description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Instruction')); ?>:</b>
	<?php echo CHtml::encode($data->Instruction); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Place')); ?>:</b>
	<?php echo CHtml::encode($data->Place); ?>
	<br />


</div>