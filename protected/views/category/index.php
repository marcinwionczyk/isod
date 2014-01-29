<?php
$this->widget('application.extensions.MTreeView.MTreeView',array(
		'collapsed'=>false,
		'animated'=>'fast',
		//---MTreeView options from here
		'table'=>'category',//what table the menu would come from
		'hierModel'=>'nestedSet',//hierarchy model of the table
		'htmlOptions' => array('class'=>'gray'),
		'fields'=>array(//declaration of fields
				'text'=>'Name',//no `text` column, use `title` instead
				'id'=>'Id',
				'tooltip'=>false,
				'alt'=>false,//skip using `alt` column
				'id_parent'=>'ParentId',//no `id_parent` column,use `parent_id` instead
				'task'=>false,
				'icon'=>false,
				'url'=>array('/demonstracje/kategoria',array('id'=>'id'),)
		)));
?>
		