<?php
session_start();  // ต้องมีทุกครั้งถ้าใช้ $_SESSION

$conn = mysqli_connect("localhost","root","","kknicdb");
$id = intval($_POST['id']);
$action = $_POST['action'];

if($action == 'edit'){
    $name = $_POST['name'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET firstname='$name', role='$role' WHERE id=$id";
    if(mysqli_query($conn, $sql)){
        // อัปเดต session ถ้าแก้ไขตัวเอง
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id){
            $_SESSION['firstname'] = $name;
            $_SESSION['role'] = $role;
        }
        echo "ok";
    } else {
        echo "error";
    }
}

if($action=="delete"){
    mysqli_query($conn,"DELETE FROM users WHERE id=$id");
    echo "ok";
}


if($action=="toggle"){
    $cur = mysqli_query($conn,"SELECT status FROM users WHERE id=$id");
    $status = mysqli_fetch_assoc($cur)['status'];
    $new = $status=='active'?'inactive':'active';
    mysqli_query($conn,"UPDATE users SET status='$new' WHERE id=$id");
    echo $new;
}
?>
