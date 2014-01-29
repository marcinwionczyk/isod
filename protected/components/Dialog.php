<?php
class Dialog {
	const Message = 0;
	const Confirmation = 1;
	const Cookies = 2;
    public static function Show($title, $message, $id = Dialog::Message) {
    	Yii::app()->user->setflash($id, array('title' => $title, 'content' => $message) );      
    }
}
?>