<?php
require_once("conn.php");

// Create connection
$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
$conn->set_charset("utf8");


$request_body  = file_get_contents('php://input');
$request = json_decode($request_body, true);
//print_r($request);die;

$result = null; 
$error_code = null;
$error_message = null;

$id = $request['id'];
$method = $request['method'];


//Идентификатор транзакции Payme Business.
if (isset($request['params']['id'])){
	$params_id = $request['params']['id'];
	
}else
{
	$params_id = null;
}

//Время создания транзакции Payme Business. 
if (isset($request['params']['time'])){
	$params_time = $request['params']['time'];
} else
{
	$params_time = null;
}

if (isset($request['params']['amount'])){
	$amount = $request['params']['amount']/100;
} else
{
	$amount = null;
}

if (isset($request['params']['reason'])){
	$params_reason = $request['params']['reason'];
} else
{
	$params_reason = null;
}

if (isset($request['params']['account']['phone'])){
	$params_account_phone = $request['params']['account']['phone'];
}else{
	$params_account_phone = null;
}

if (isset($request['params']['account']['order'])){
	$params_account_order = $request['params']['account']['order'];
}else{
	$params_account_order = null;
}

//print_r($request['method']);die;

if (isset($request['params']['from'])){
	$params_from = $request['params']['from'];
} else
{
	$params_from = null;
}

//Время создания транзакции Payme Business. 
if (isset($request['params']['to'])){
	$params_to = $request['params']['to'];
} else
{
	$params_to = null;
}

if (isset($request['params']['password'])){
	$params_password = $request['params']['password'];
} else
{
	$params_password = null;
}





if($method == "CheckPerformTransaction"){
	$result =  CheckPerformTransaction ($params_account_order, $amount, $conn, $id);
	//javob($id, $result);
}
if($method == "CreateTransaction"){
	$result =  CreateTransaction ($conn, $params_account_order,$params_id,$params_time,$amount, $id);
	//javob($id, $result);
}
if($method == "PerformTransaction"){
	$result =  PerformTransaction ($conn, $params_id, $id);
	//javob($id, $result);
}
if($method == "CheckTransaction"){
	$result =  CheckTransaction ($conn, $params_id, $id);
	//javob($id, $result);
}
if($method == "CancelTransaction"){
	$result =  CancelTransaction ($conn, $params_id, $params_reason, $id);
	//javob($id, $result);
}
if($method == "GetStatement"){
	$result =  GetStatement ($conn, $params_from, $params_to);
	//javob($id, $result);
}
if($method == "ChangePassword"){
	$result =  ChangePassword ($params_password);
	//javob($id, $result);
}
//Время создания транзакции Payme Business. 








function ChangePassword ($params_password){


		$result['success'] = true;
		
		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "Password change errorS.";
		
  		javob(null, $result, $error_code, $error_message);
  		

	}











function GetStatement ($conn, $params_from, $params_to){
	
	//читаем из базы

	$sql = "SELECT * FROM `transactions` WHERE `paycom_time`>='".$params_from."' AND `paycom_time`<='".$params_to."'";
	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	
  	if ($number_of_rows == 0){
  		
  		//за период не найдено ни одной транзакции
  		
		$result['transactions'] = [];
		$error_code = -32504; //Insufficient privilege to perform this method.
		//$error_code = null; //Insufficient privilege to perform this method.
		$error_message = "There is no such order or amount1.";
		//$error_message = null; 
		
  		javob(null, $result, $error_code, $error_message);
  		
  		
	}elseif(($number_of_rows >= 1)){
		
	

		
		while($row = mysqli_fetch_assoc($query)) {
			//print_r("11111111111111111111111111111");die;
		        //echo "nomi: " . $row["nomi"]. " - narxi: " . $row["narxi"]. " - изо?: " . $row["izoh"];
		        //echo $row["name"];
		        $transactions['id'] = $row["id"];
		        $transactions['time'] = $row["paycom_time"];
		        $transactions['amount'] = $row["amount"];
		        $transactions['account']['phone'] = $row["order_id"];
		        $transactions['create_time'] = $row["create_time"];
		        $transactions['perform_time'] = $row["perform_time"];
		        $transactions['cancel_time'] = $row["cancel_time"];
		        $transactions['transaction'] = $row["paycom_transaction_id"];
		        $transactions['state'] = $row["state"];
		        $transactions['reason'] = $row["reason"];
		        $transactions['receivers']['id'] = $row["order_id"];
		        $transactions['receivers']['amount'] = $row["amount"];

		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = $number_of_rows." results found.";
		        
				$result['transactions'][] =   $transactions;

		    }
		javob(null, $result, $error_code, $error_message);
	}
		
	
	return true;
	
	
}












function CancelTransaction ($conn, $params_id, $params_reason, $id){
	
	//читаем из базы

	$sql = "SELECT * FROM `transactions` WHERE `paycom_transaction_id`='".$params_id."'";
	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	
  	if ($number_of_rows == 0){
  		
  		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "There is no such order or amount1.";
		//$result['allow'] = -32504;
		
		
  		//$result['reason'] =  -31003;
  		$result = null ;
  		javob($id, $result, $error_code, $error_message);
  		
  		
	}elseif(($number_of_rows == 1)){
	
	
	//посмотрим текущее состояние
	$sql = "SELECT * FROM `transactions` WHERE `paycom_transaction_id`='".$params_id."'";
	$query=mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($query)) {
				$current_state =  (int)$row["state"];
	}
	if($current_state>0){
		$current_state = $current_state * (-1);
		
		//записываем в базу
		$cancel_time = date("Y-m-d H:i:s"); // 2015-04-07 07:12:51
		$sql = "UPDATE `transactions` SET `cancel_time` = '$cancel_time', `reason` = '$params_reason', `state` = '$current_state' WHERE `transactions`.`paycom_transaction_id` = '$params_id'";
		
		$query=mysqli_query($conn, $sql);
	}
	
	
	
	
	//читаем из базы

	$sql = "SELECT * FROM `transactions` WHERE `paycom_transaction_id`='".$params_id."'";
	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	//print_r($number_of_rows);die;
  	if ($number_of_rows == 0){
  		print_r("00000000000000000000000000");die;
	}elseif(($number_of_rows == 1)){
		
		while($row = mysqli_fetch_assoc($query)) {
			//print_r("11111111111111111111111111111");die;
		        //echo "nomi: " . $row["nomi"]. " - narxi: " . $row["narxi"]. " - изо?: " . $row["izoh"];
		        //echo $row["name"];
		        $result['cancel_time'] = strtotime($row["cancel_time"]) * 1000;
				$result['transaction'] =  $row["id"];
				$result['reason'] =  (int)$row["reason"];
				$result['state'] =  (int)$row["state"];

		    }
		
	}
		javob($id, $result);
	}
	return $result;
	
	
}












function CheckTransaction ($conn, $params_id, $id){
	
	
	//читаем из базы

	$sql = "SELECT * FROM `transactions` WHERE `paycom_transaction_id`='".$params_id."'";
	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	
  	if ($number_of_rows == 0){
  		
  		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "There is no such order or amount1.";
		//$result['allow'] = -32504;
		
		
  		//$result['reason'] =  -31003;
  		$result = null;
  		javob($id, $result, $error_code, $error_message);
  		
  		
	}elseif(($number_of_rows == 1)){
		
		while($row = mysqli_fetch_assoc($query)) {
			//print_r("11111111111111111111111111111");die;
		        //echo "nomi: " . $row["nomi"]. " - narxi: " . $row["narxi"]. " - изо?: " . $row["izoh"];
		        //echo $row["name"];
		        $result['create_time'] = strtotime($row["create_time"]) * 1000;
		        $result['perform_time'] = strtotime($row["perform_time"]) * 1000;
		        $result['cancel_time'] = strtotime($row["cancel_time"]) * 1000;
				$result['transaction'] =  $row["id"];
				$result['state'] =  (int)$row["state"];
				if ($row["reason"]==null){
					$result['reason'] =  $row["reason"];
				}else{
					$result['reason'] =  (int)$row["reason"];
				}
				

		    }
		javob($id, $result);
	}

	
	return $result;
	
	
}











function PerformTransaction ($conn, $params_id, $id){


	//читаем из базы

	$sql = "SELECT * FROM `transactions` WHERE `paycom_transaction_id`='".$params_id."'";
	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	
  	if ($number_of_rows == 0){
  		
  		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "There is no such order.";
		//$result['transaction'] = "";
		
		
  		//$result['reason'] =  -31003;
 		$result =  null;
  		
  		javob($id, $result, $error_code, $error_message);
  		
  		
	}elseif(($number_of_rows == 1)){
		
		while($row = mysqli_fetch_assoc($query)) {
				$state =  (int)$row["state"];
				$transactions_order_id = $row["order_id"];
				$transactions_amount = $row["amount"];
//print_r($state);die;
		    }
		    if ($state == 1){
				
			

	
	//записываем в базу
	$perform_time = date("Y-m-d H:i:s"); // 2015-04-07 07:12:51
	$sql = "UPDATE `transactions` SET `perform_time` = '$perform_time', `state` = '2' WHERE `transactions`.`paycom_transaction_id` = '$params_id'";
		
	$query=mysqli_query($conn, $sql);
	
				//$transactions_order_id = $row["order_id"];
				//$transactions_amount = $row["amount"];
//				include_once('functions.php');
				tuplangan_mablagni_oshirish ($transactions_order_id, $transactions_amount, $conn);

	
	}
	//читаем из базы

	$sql = "SELECT * FROM `transactions` WHERE `paycom_transaction_id`='".$params_id."'";
	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	
  	//print_r($number_of_rows);die;
  	if ($number_of_rows == 0){
  		print_r("00000000000000000000000000");die;
	}elseif(($number_of_rows == 1)){
		//echo "YYYY";die;
		while($row = mysqli_fetch_assoc($query)) {
			//print_r("11111111111111111111111111111");die;
		        //echo "nomi: " . $row["nomi"]. " - narxi: " . $row["narxi"]. " - изо?: " . $row["izoh"];
		        //echo $row["name"];
		        $result['perform_time'] = strtotime($row["perform_time"]) * 1000;
				$result['transaction'] =  $row["id"];
				$result['state'] =  (int)$row["state"];
				

		    }
	javob($id, $result);	
	}

	
	return $result;
	
	
	
	}
}













/*
function CheckTransaction ($conn, $params_id){
	
	//поиск транзакции по id, если нет создадим
	$sql = "SELECT * FROM `transactions` WHERE `paycom_transaction_id`='".$params_id."'";
	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	//print_r($number_of_rows);die;
  	if ($number_of_rows == 0){
  		print_r("00000000000000000000000000");die;
	}elseif(($number_of_rows == 1)){
		
		while($row = mysqli_fetch_assoc($query)) {
			//print_r("11111111111111111111111111111");die;
		        //echo "nomi: " . $row["nomi"]. " - narxi: " . $row["narxi"]. " - изо?: " . $row["izoh"];
		        //echo $row["name"];
		        $result['create_time'] = strtotime($row["create_time"]) * 1000;
		        $result['perform_time'] = 0;
		        $result['cancel_time'] = 0;
				$result['transaction'] =  $row["order_id"];
				$result['state'] =  (int)$row["state"];
				$result['reason'] =  null;
		    }
		
	}
	
	
	
	return $result;
}

*/











function CreateTransaction ($conn, $params_account_order,$params_id,$params_time,$amount, $id){
	
	/*$create_time = 1553623583;
	$time_create_time = date('Y-m-d h:i:s', $create_time);
	$params_time = 1553624045;
	$params_time_sss = date('Y-m-d h:i:s', $params_time);
	
	echo "create_time: ".$time_create_time."w                     ";
	echo "params_time: ".$params_time_sss."w";die;*/


	if ($params_account_order==null){
  		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "There is no such order or amount1.";
		//$result['allow'] = -32504;
		$result = null;
		
		
		
		javob($id, $result, $error_code, $error_message);
	}else{


	$sql = "SELECT * FROM `orders` WHERE `id`=".$params_account_order;
  	$query=mysqli_query($conn, $sql);
  	if(empty($query)){
  		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "There is no such order.";
		//$result['allow'] = -32504;
		$result = null;
		javob($id, $result, $error_code, $error_message);

}else{
	

  	$number_of_rows = mysqli_num_rows($query);
  	if ($number_of_rows == 0){
		$error_code = -31050; //Insufficient privilege to perform this method.
		$error_message = "There is no such order.";
		//$result['allow'] = -31050;
		$result = null;
		javob($id, $result, $error_code, $error_message);

	}
	else{
		
	


	//check amount
	$sql = "SELECT * FROM `orders` WHERE `amount`=".$amount;
	$sql = "SELECT * FROM `orders` WHERE `id`=".$params_account_order." AND `amount`=".$amount;
	$query=mysqli_query($conn, $sql);
	$number_of_rows = mysqli_num_rows($query);
	if ($number_of_rows == 0){
		$error_code = -31001; //Insufficient privilege to perform this method.
		$error_message = "There is no such amount.";
		//$result['allow'] = -31001;
		$result = null;
		javob($id, $result, $error_code, $error_message);
	}else
	{
		
		
	
	
	//поиск транзакции по id, если нет создадим
	$sql = "SELECT * FROM `transactions` WHERE `order_id`=".$params_account_order;
  	$query=mysqli_query($conn, $sql);
  	$number_of_rows = mysqli_num_rows($query);
  	if ($number_of_rows == 0){
  		
  		//транзакция не создан
  		//создадим транзакцию
  		$create_time = date('Y-m-d H:i:s');
  		//print_r($create_time);die;
  		$perform_time = date("d-m-Y h:i:s");
  		$cancel_time = date("d-m-Y h:i:s");
  		//print_r($create_time);die;
  		
		$sql = "INSERT INTO `transactions` (`id`, `paycom_transaction_id`, `paycom_time`, `paycom_time_datetime`, `create_time`, `perform_time`, `cancel_time`, `amount`, `state`, `reason`, `receivers`, `order_id`) VALUES (NULL, '$params_id', '$params_time', '$params_time', '$create_time', '$perform_time', '$cancel_time', '$amount', '1', NULL, NULL, '$params_account_order');";
		
		$sql = "INSERT INTO `transactions` (`paycom_transaction_id`, `paycom_time`, `paycom_time_datetime`, `create_time`, `perform_time`, `cancel_time`, `amount`, `state`, `reason`, `receivers`, `order_id`) VALUES ('$params_id', '$params_time', CURRENT_TIMESTAMP, '$create_time', NULL, NULL, '$amount', '1', NULL, NULL, '$params_account_order')";
		
		$query=mysqli_query($conn, $sql);
		
		$result['create_time'] = strtotime($create_time)*1000;
		//$result['create_time'] = $params_time;
		$result['transaction'] = $params_account_order;
		$result['state'] = 1;
		
		javob($id, $result);
	}elseif(($number_of_rows == 1)){
		
		while($row = mysqli_fetch_assoc($query)) {
		        //echo "nomi: " . $row["nomi"]. " - narxi: " . $row["narxi"]. " - изо?: " . $row["izoh"];
		        $result['create_time'] = strtotime($row["create_time"]) * 1000;
				$result['transaction'] =  $row["order_id"];
				$result['state'] =  (int)$row["state"];
				
				
				  		$error_code = -31050; //Insufficient privilege to perform this method.
		$error_message = "V ojidanii oplati.";
		//$result['allow'] = -31050;
		//$result = null;
		javob($id, $result, $error_code, $error_message);
		    }
		    
		/*$sql = "UPDATE `transactions` SET `paycom_transaction_id` = '$params_id' WHERE `transactions`.`order_id` = '$params_account_order'";
		
		$query=mysqli_query($conn, $sql);
*/
		
	}
	
	
	}
	
	
	}}}
	return $result;
	
	
	
}








function CheckPerformTransaction ($params_account_order, $amount, $conn, $id){
	
	$funktsiya_javobi = true;
	$result['allow'] = true;
	$error_code = null;
	$error_message = null;
	
	//поиск клиента
	
	$sql = "SELECT * FROM `orders` WHERE `id`=".$params_account_order." AND `amount`=".$amount;
	$sql = "SELECT * FROM `orders` WHERE `id`=".$params_account_order;

if (is_null($params_account_order)){
	
	  	$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "There is no such order.";
		//$result['allow'] = false;
		javob($id, null, $error_code, $error_message);
}else
{
	

	
  	$query=mysqli_query($conn, $sql);
  	if(empty($query)){
  		
  		$error_code = -32504; //Insufficient privilege to perform this method.
		$error_message = "There is no such order.";
		//$result['allow'] = false;
		javob($id, null, $error_code, $error_message);

}else{
	

  	$number_of_rows = mysqli_num_rows($query);
  	if ($number_of_rows == 0){
  		
		$error_code = -31050; //Insufficient privilege to perform this method.
		$error_message = "There is no such order.";
		//$result['allow'] = false;
		$result = null;
		javob($id, $result, $error_code, $error_message);

	}
	elseif($number_of_rows == 1){
		
		$sql = "SELECT * FROM `orders` WHERE `amount`=".$amount;
		$sql = "SELECT * FROM `orders` WHERE `id`=".$params_account_order." AND `amount`=".$amount;
		
		$query=mysqli_query($conn, $sql);
		
		$number_of_rows = mysqli_num_rows($query);
		
		if ($number_of_rows == 0){
		$error_code = -31001; //Insufficient privilege to perform this method.
		$error_message = "There is no such amount.";
		$result['allow'] = false;
		//print_r($result);die;
		javob($id, $result, $error_code, $error_message);

	}else{
		javob($id, $result);
	}
		
		
	}

	//проверка клиента на возможность приема платежей
	
	//проверка суммы
	
	
	//javob($result, $error_code, $error_message);
}	
}
	return $result;
}









/*
$error_code = -32504; //Insufficient privilege to perform this method.
$error_message = "Insufficient privilege to perform this method.";

$error_code = -31001; //Insufficient privilege to perform this method.
$error_message = "incorrect sum.";
*/









    function javob($id = null, $result = null, $error_code = null, $error_message = null)
    {
        header('Content-Type: application/json; charset=UTF-8');

        $response['jsonrpc'] = '2.0';
        $response['id']      = $id;
        if(is_null($result)){}
		else{
			$response['result']  = $result;
		}
		
        
		if (is_null($error_code)) {}
		else{
		$response['error']['code']   = $error_code;
        $response['error']['message']   = $error_message;
		}

        echo json_encode($response);
    }




function tuplangan_mablagni_oshirish ($transactions_order_id, $summa, $conn){
	
	
	
	$sql = "SELECT * FROM `orders` WHERE `id`='".$transactions_order_id."'";
	$query=mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($query)) {
		$project_id = $row["product_ids"];
    }
	
	
	
	//читаем из базы
	
	$sql = "SELECT * FROM `abc_bp_bizplans` WHERE `id`='".$project_id."'";
	$query=mysqli_query($conn, $sql);

  	$number_of_rows = mysqli_num_rows($query);
  	//echo $number_of_rows;die;
  	
	while($row = mysqli_fetch_assoc($query)) {
		$tuplangan_mablag = $row["tuplangan_mablag"];
    }
    
    $tuplangan_mablag = $tuplangan_mablag + $summa;
    
    $sql = "UPDATE `abc_bp_bizplans` SET `tuplangan_mablag` = '$tuplangan_mablag' WHERE `abc_bp_bizplans`.`id` = '$project_id'";
		
		$query=mysqli_query($conn, $sql);
    
    //echo $tuplangan_mablag;
	
	return true;
	
	
}




 ?>