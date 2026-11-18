<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_database = "kknicdb";

$db = new mysql($db_host, $db_user, $db_pass, $db_database);
	
/* ตรวจสอบการเชื่อมต่อ */
if ($db->connect_error) {
	die("ไม่สามารถเชื่อมต่อฐานข้อมูลสำเร็จ ". $db->connect_error);
}

$db->set_charset("utf8");

echo "เชื่อมต่อฐานข้อมูลสำเร็จ";
?>