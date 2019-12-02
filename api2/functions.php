<?php

require_once("conn.php");



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