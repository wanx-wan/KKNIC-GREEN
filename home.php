<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "kknicdb");

if(isset($_SESSION['user_id'])){
  $user_id = $_SESSION['user_id'];
}else{
  $user_id = 0;
}

$q = isset($_GET['q']) ? $_GET['q'] : '';
$q = mysqli_real_escape_string($conn, $q);

$resultact = mysqli_query($conn, "
SELECT a.id, a.name, a.point,
DATE(a.start_at) AS start_at,
DATE(a.end_at) AS end_at,
al.id AS joined
FROM acts a
LEFT JOIN act_logs al 
  ON a.id = al.acts_id AND al.user_id = $user_id
WHERE a.name LIKE '%$q%'
");
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
          <ul class="navbar-nav mr-auto">
			<?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
			<li class="nav-item">
			  <a class="nav-link" href="admin.php">มุมมองผู้ดูแลระบบ</a>
			</li>
			<?php endif; ?>
          </ul>
			<form class="form-inline my-2 my-lg-0" method="GET">
			  <input class="form-control mr-sm-2" type="search" name="q" placeholder="ค้นหากิจกรรม...">
			  <button class="btn btn-outline-success" type="submit">ค้นหา</button>
			</form>
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
    <div class="container mt-3">
      <div class="row">
        <div class="col-12">
          <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleControls" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleControls" data-slide-to="1"></li>
              <li data-target="#carouselExampleControls" data-slide-to="2"></li>
            </ol>
			<div class="carousel-inner">
			<?php
			$files = glob("contents/*.{jpg,png,gif,jpeg}", GLOB_BRACE);
			$i = 0;

			foreach($files as $img):
			?>
			  <div class="carousel-item <?= $i==0 ? 'active' : '' ?>">
				<img class="d-block w-100" src="<?= $img ?>" alt="slide">
			  </div>
			<?php
			$i++;
			endforeach;
			?>
			</div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">ก่อนหน้า</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">ถัดไป</span>
            </a>
          </div>
        </div>
      </div>
      <hr>
    </div>
    <h2 class="text-center">กิจกรรม</h2>
    <hr>
	<div class="container">
	  <div class="row text-center">
		<?php
		// ตรวจสอบว่ามีข้อมูลหรือไม่
		if($resultact && mysqli_num_rows($resultact) > 0){
			while($row = mysqli_fetch_assoc($resultact)){
				$actName = $row['name'];
				$actPoint = $row['point'];
				$startAt = $row['start_at'];
				$endAt = $row['end_at'];
		?>
		<div class="col-md-4 pb-1 pb-md-0">
		  <div class="card">
			<div class="card-body">
			  <h5 class="card-title"><?php echo htmlspecialchars($actName); ?></h5>
			  <p>คะแนน: <?php echo $actPoint; ?></p>
			  <p>เวลา: <?php echo $startAt . " - " . $endAt; ?></p>
			  <?php if(!$row['joined']): ?>
			  <form class="joinForm" enctype="multipart/form-data">
				  <input type="hidden" name="acts_id" value="<?= $row['id'] ?>">
				  <input type="file" name="file" required>
				  <button class="btn btn-primary btn-sm">เข้าร่วม</button>
			  </form>
			  <?php else: ?>
			      <button class="btn btn-success btn-sm" disabled>เข้าร่วมแล้ว</button>
			  <?php endif; ?>
			</div>
		  </div>
		</div>
		<?php
			}
		} else {
			echo "<p class='mx-auto'>ไม่มีข้อมูลกิจกรรม</p>";
		}
		?>
	  </div>
	</div>
	<script>
	document.querySelectorAll(".joinForm").forEach(f=>{
	  f.onsubmit = e=>{
		e.preventDefault();
		const fd = new FormData(f);

		fetch("join_action.php",{
		  method:"POST",
		  body:fd
		})
		.then(r=>r.text())
		.then(alert);
	  }
	});
	</script>
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