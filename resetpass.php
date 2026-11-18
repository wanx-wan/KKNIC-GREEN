<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "kknicdb");

if(isset($_POST['reset'])){
    $phone = $_POST['phone'];
    $newpass = $_POST['password'];
    $confirmpass = $_POST['confirm'];

    if($newpass != $confirmpass){
        echo "รหัสผ่านไม่ตรงกัน";
    } else {

        $hash = password_hash($newpass, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "UPDATE users SET password=? WHERE phone=?");
        mysqli_stmt_bind_param($stmt, "ss", $hash, $phone);
        mysqli_stmt_execute($stmt);

        if(mysqli_stmt_affected_rows($stmt) > 0){
            header("Location: login.php");
			exit();
        } else{
            echo "ไม่พบเบอร์นี้";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าหลัก</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap-4.3.1.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="resetpass.php">KKNIC GREEN GAME</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
			<li class="nav-item">
              <a class="nav-link" href="leaderboard.php">กระดานจัดอันดับ</a>
            </li>
          </ul>
	      <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="login.php">เข้าสู่ระบบ</a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="register.php">สมัครสมาชิก</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<br>
    <div class="container-lg">
	  <div class="card">
		<h5 class="text-center">ลืมรหัสผ่าน</h5>
		<form method="post">
			<input type="text" name="phone" placeholder="หมายเลขโทรศัพท์" required>
			<input type="password" name="password" placeholder="รหัสผ่านใหม่" required>
			<input type="password" name="confirm" placeholder="ยืนยันรหัสผ่านใหม่อีกครั้ง" required>

			<div class="button-group">
				<input type="submit" name="reset" value="ยืนยัน">
			</div>
		</form>
	  </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="js/jquery-3.3.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap-4.3.1.js"></script>
  </body>
</html>