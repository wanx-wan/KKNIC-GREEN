<?php
$conn = mysqli_connect("localhost","root","","kknicdb");

$action = $_POST['action'];

if($action=="add"){
    $name = $_POST['name'];
    $point = $_POST['point'];
    $start = $_POST['start_at'];
    $end = $_POST['end_at'];

    mysqli_query($conn,"
        INSERT INTO acts (name, point, start_at, end_at, created_at)
        VALUES ('$name','$point','$start','$end',NOW())
    ");

    echo "ok";
}