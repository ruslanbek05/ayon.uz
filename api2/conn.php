<?php

$DatabaseName = "abc";
$HostUser = "root";
$HostPass = "ogogogog+-";
$HostName = "localhost";

// Create connection
$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
$conn->set_charset("utf8");


/*if ($conn){
	echo "connection success";
}else{
	echo "connection not success";
}*/

?>