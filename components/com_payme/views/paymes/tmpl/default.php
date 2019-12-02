<?php
// No direct access
defined('_JEXEC') or die;

$jinput = JFactory::getApplication()->input;
		$summa = $jinput->getFloat('summa', '0')*100;
		$product_ids = $jinput->getint('product_ids', '0');
		$order = $jinput->getint('order', '0');
		
		$user = JFactory::getUser();
		$user_id=$user->id;
		
//print_r($foo);die;
?>

<form method="POST" action="https://checkout.paycom.uz">

    <!-- Идентификатор WEB Кассы -->
    <input type="hidden" name="merchant" value="5c8801d719d7624c7a8d20f6"/>

    <!-- Сумма платежа в тийинах -->
    <input type="hidden" name="amount" value="<?php echo $summa;?>"/>

    <!-- Поля Объекта Account -->
    <input type="hidden" name="account[user_id]" value="<?php echo $user_id;?>"/>
    <input type="hidden" name="account[order]" value="<?php echo $order;?>"/>

  

    <button type="submit">Оплатить с помощью <b>Payme</b></button>
</form>