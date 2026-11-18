<?php
session_start();
$conn = mysqli_connect("localhost","root","","kknicdb");

$user_id = $_SESSION['user_id'];
$acts_id = $_POST['acts_id'];

// upload file
$file = $_FILES['file'];
$filename = time()."_".$file['name'];
move_uploaded_file($file['tmp_name'], "uploads/".$filename);

// กันกดซ้ำ
$check = mysqli_query($conn,"SELECT id FROM act_logs WHERE user_id=$user_id AND acts_id=$acts_id");
if(mysqli_num_rows($check)>0){
    echo "เข้าร่วมแล้ว";
    exit();
}

// insert
mysqli_query($conn,"
  INSERT INTO act_logs (user_id, acts_id, joined_at, files, status)
  VALUES ($user_id, $acts_id, NOW(), '$filename', 'pending')
");

echo "ส่งหลักฐานสำเร็จ";
?>