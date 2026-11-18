<?php
$conn = mysqli_connect("localhost","root","","kknicdb");

$id = $_POST['id'];
$action = $_POST['action'];

if($action=="score"){
    $point = intval($_POST['point']);

    $q = mysqli_query($conn,"
      SELECT a.point FROM act_logs al
      JOIN acts a ON al.acts_id=a.id
      WHERE al.id=$id
    ");
    $max = mysqli_fetch_assoc($q)['point'];

    if($point >= 0 && $point <= $max){
        mysqli_query($conn,"
          UPDATE act_logs 
          SET point_earn=$point, status='checked'
          WHERE id=$id
        ");
        echo "ok";
    }else{
        echo "error";
    }
}
?>