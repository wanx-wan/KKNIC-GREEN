<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "kknicdb");

if(isset($_POST['register'])){
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$phone = $_POST["phone"];
	$password = $_POST["password"];
	
	$check =mysqli_prepare($conn, "SELECT id FROM users WHERE phone = ?");
	mysqli_stmt_bind_param($check, "s", $phone);
	mysqli_stmt_execute($check);
	mysqli_stmt_store_result($check);
	
	if(mysqli_stmt_num_rows($check) > 0){
		echo "phone นี้มีอยู่แล้ว";
	} else {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		
		$stmt = mysqli_prepare($conn,
							  "INSERT INTO users (firstname, lastname, phone, password)
							  VALUES (?, ?, ?, ?)");
		
		mysqli_stmt_bind_param($stmt, "ssss",
							  $firstname, $lastname, $phone, $hashed_password);
		
		mysqli_stmt_execute($stmt);
		
		echo"สมัครสมาชิกสำเร็จ";
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
        <a class="navbar-brand" href="register.php">KKNIC GREEN GAME</a>
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
              <a class="nav-link" href="resetpass.php">ลืมรหัสผ่าน</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	 <br>
    <div class="container-lg">
	  <div class="card">
		<h5 class="text-center">สมัครสมาชิก</h5>
		<form method="post" action="">
				<input type="text" name="firstname" placeholder="ชื่อ" required>
				<input type="text" name="lastname" placeholder="สกุล" required>
				<input type="text" name="phone" placeholder="เบอร์โทร" required>
				<input type="password" name="password" placeholder="รหัสผ่าน" required>
				<div class="button-group">
					<input type="submit" name="register" value="ยืนยัน">
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