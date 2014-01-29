<?php
class Ordering {
	
	public static function getCartContent() {
		if(is_string(Yii::app()->user->getState('cart')))
			return json_decode(Yii::app()->user->getState('cart'),true);
		else
			return Yii::app()->user->getState('cart');
	}
	
	public static function setCartContent($cart) {
		return Yii::app()->user->setState('cart',json_encode($cart));
	}
	
	public static function isAvailable(Order $order) {
		if(!(Order::model()->exists(
				'Demonstration_Id=:demonstrationId AND DateTo>:dateFrom',
				array(
						':demonstrationId'=>$order->Demonstration_Id, 
						':dateFrom'=>$order->DateFrom)
				)))
		{
			$order->addError('DateFrom', 'Demonstracja jest już w tym czasie zamówiona');
		}		
	}
	
	/* set a flash message to display after the request is done */
	public static function setFlash($message)
	{
		Yii::app()->user->setFlash('ordering',$message);
	}
	
	public static function hasFlash()
	{
		return Yii::app()->user->hasFlash('ordering');
	}
	
	/* retrieve the flash message again */
	public static function getFlash() {
		if(Yii::app()->user->hasFlash('ordering')) {
			return Yii::app()->user->getFlash('ordering');
		}
	}
	
	public static function renderFlashError()
	{
		if(Yii::app()->user->hasFlash('ordering')) {
			echo '<div class="flash-error">';
			echo Ordering::getFlash();
			echo '</div>';
			Yii::app()->clientScript->registerScript('fade',"
						setTimeout(function() { $('.flash-error').fadeOut('slow'); }, 5000);
						");
		}
	}
	public static function renderFlashSuccess()
	{
		if(Yii::app()->user->hasFlash('ordering')) {
			echo '<div class="flash-success">';
			echo Ordering::getFlash();
			echo '</div>';
			Yii::app()->clientScript->registerScript('fade',"
						setTimeout(function() { $('.flash-success').fadeOut('slow'); }, 5000);
						");
		}
	}
	public static function renderFlashNotice()
	{
		if(Yii::app()->user->hasFlash('ordering')) {
			echo '<div class="flash-notice">';
			echo Ordering::getFlash();
			echo '</div>';
			Yii::app()->clientScript->registerScript('fade',"
						setTimeout(function() { $('.flash-notice').fadeOut('slow'); }, 5000);
						");
		}
	}
}
?>