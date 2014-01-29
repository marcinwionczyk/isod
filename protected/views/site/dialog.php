<style type="text/css">
.no-close .ui-dialog-titlebar-close {
display: none;
}
</style>
<?php
if($flashes = Yii::app()->user->getFlashes()) {
    foreach($flashes as $key => $message) {
        if($key != 'counters') {
        	if ($key == Dialog::Message)
        	{
        		$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        				'id'=>$key,
        				'options'=>array(
        						'show' => 'blind',
        						'hide' => 'explode',
        						'width' => '33%',
        						'modal' => 'true',
        						'title' => $message['title'],
        						'autoOpen'=>true,
        						'buttons' => array(
        								array('text'=>'Ok','click'=> 'js:function(){$(this).dialog("close");')
        								)
        				),
        		));
        		
        	}
        	if ($key == Dialog::Confirmation)
        	{
        		$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        				'id'=>$key,
        				'options'=>array(
        						'dialogClass'=>'no-close',
        						'show' => 'blind',
        						'hide' => 'explode',
        						'width' => '33%',
        						'modal' => 'true',
        						'resizable' => 'false',
        						'autoOpen'=>true,
        						'title' => $message['title'],
        						'buttons' => array(
     								'Ok'=>'js:function(){alert("ok")}',
     								'Anuluj'=>'js:function(){alert("cancel")}',
								))));
        	}
        	if ($key == Dialog::Cookies)
        	{
        		$target1 = 'window.location='."'https://isod.pwr.wroc.pl/site/acceptcookie'";
        		$target2 = 'window.location='."'http://www.if.pwr.wroc.pl'";
        		$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        				'id'=>$key,
        				'options'=>array(
        						'show' => 'blind',
        						'hide' => 'explode',
        						'width' => '33%',
        						'modal' => 'true',
        						'resizable' => 'false',
        						'autoOpen'=>true,
        						'title' => $message['title'],
        						'buttons' => array(
        								array('text'=>'Ok','click'=> 'js:function(){$(this).dialog("close");'.$target1.'}'),
        								array('text'=>'Nie zgadzam się','click'=> 'js:function(){$(this).dialog("close");'.$target2.'}'),
        								
        						))));
        	}
        	printf('<span class="dialog no-close">%s</span>', $message['content']);
        	$this->endWidget('zii.widgets.jui.CJuiDialog');
        }
    }
}
?>