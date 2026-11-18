<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "kknicdb");

if (isset($_POST['login'])){
	$phone = $_POST['phone'];
	$password = $_POST['password'];
	
	$stmt = mysqli_prepare($conn, "SELECT id, firstname, password, role, status FROM users WHERE phone = ?");
	mysqli_stmt_bind_param($stmt, "s", $phone);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);

	if(mysqli_stmt_num_rows($stmt) > 0){
		mysqli_stmt_bind_result($stmt, $id, $firstname, $hashed_password, $role, $status);
		mysqli_stmt_fetch($stmt);

		if(password_verify($password, $hashed_password) && $status == 'active'){
			$_SESSION['user_id']=$id;
			$_SESSION['firstname']=$firstname;
			$_SESSION['role']=$role;
			header("Location: home.php");
			exit();
		}else{
			echo "ถูกระงับสิทธิ์หรือรหัสผิด";
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
        <a class="navbar-brand" href="login.php">KKNIC GREEN GAME</a>
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
              <a class="nav-link" href="register.php">สมัครสมาชิก</a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="resetpass.php">ลืมรหัสผ่าน</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<br>
    <div class="container-lg">
	  <div class="card">
		<h5 class="text-center">เข้าสู่ระบบ</h5>
		<form method="post" action="">
				<input type="text" name="phone" placeholder="หมายเลขโทรศัพท์" required>
				<input type="password" name="password" placeholder="รหัสผ่าน" required>
				<div class="button-group">
					<input type="submit" name="login" value="ยืนยัน">
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