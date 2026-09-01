<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "kknicdb");

// กันเข้าตรง
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT firstname, phone FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $firstname, $phone);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt); 

if(isset($_POST['update'])){
    $newname = $_POST['firstname'];
    $newphone = $_POST['phone'];
    $newpass = $_POST['password'];

    if(!empty($newpass)){
        $hash = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE users SET firstname=?, phone=?, password=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssi", $newname, $newphone, $hash, $id);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET firstname=?, phone=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssi", $newname, $newphone, $id);
    }

    mysqli_stmt_execute($stmt);

    $_SESSION['firstname'] = $newname;

    echo "<script>alert('อัปเดตสำเร็จ'); window.location='profile.php';</script>";
    exit();
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
        <a class="navbar-brand" href="home.php">KKNIC GREEN GAME</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
	      <ul class="navbar-nav">
            <li class="nav-item">
			<?php if(isset($_SESSION['user_id'])): ?>
				<a href="profile.php" class="btn btn-sm text-white">
					สวัสดี, <?php echo $_SESSION['firstname']; ?>
				</a>
				<a href="logout.php">ออกจากระบบ</a>
			<?php else: ?>
				<a href="login.php">เข้าสู่ระบบ</a>
			<?php endif; ?>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<br>
    <div class="container-lg">
	  <div class="card">
		<h5 class="text-center">ข้อมูลส่วนตัว</h5>
		<form method="post">
			<br>
			<input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="ชื่อ-สกุล" required>

			<input type="text" name="phone" value="<?php echo $phone; ?>" placeholder="หมายเลขโทรศัพท์" required>

			<input type="password" name="password" placeholder="รหัสผ่านใหม่">

			<div class="button-group">
				<input type="submit" name="update" value="ยืนยันการแก้ไข">
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