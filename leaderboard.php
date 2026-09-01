<?php
$conn = mysqli_connect("localhost", "root", "", "kknicdb");

$sql = "SELECT 
            u.firstname,
            u.lastname,
            SUM(a.point_earn) AS total_points
        FROM act_logs a
        JOIN users u ON a.user_id = u.id
        WHERE u.status = 'active'
        GROUP BY a.user_id
        ORDER BY total_points DESC
        LIMIT 10";

$result = mysqli_query($conn, $sql);
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
              <a class="nav-link" href="register.php">เข้าสู่ระบบ</a>
            </li>
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
    <div class="container">
	<h3>กระดานจัดอันดับ</h3>
      <div class="row">
		<?php 
		$rank = 1;
		while($row = mysqli_fetch_assoc($result)): 
		?>
		  <div class="col-4 mb-3">
			<div class="row">
			  <div class="col-lg-6 col-10 ml-1">
				<h5>
				  #<?php echo $rank++; ?> 
				  <?php echo $row['firstname'].' '.$row['lastname']; ?>
				</h5>
				<small>คะแนน: <?php echo $row['total_points']; ?></small>
			  </div>
			</div>
		  </div>
		<?php endwhile; ?>
		</div>
    </div>
    <hr>
    <footer class="text-center">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <p>2025  ลิขสิทธิ์ - <span>สาขาวิชาเทคโนโลยีธุรกิจดิจิทัล วิทยาลัยการอาชีพขอนแก่น</span></p>
          </div>
        </div>
      </div>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.3.1.js"></script>
  </body>
</html>