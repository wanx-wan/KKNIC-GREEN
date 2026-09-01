<?php
session_start();
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
			<li class="nav-item">
              <a class="nav-link" href="#">กิจกรรม</a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="#">กระดานจัดอันดับ</a>
            </li>
          </ul>
	      <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#">เข้าสู่ระบบ</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<br>
    <h2 class="text-center">เกี่ยวกับเรา</h2>
	<p class="text-center">เปลี่ยนการอนุรักษ์พลังงานด้วยเกม</p>
    <hr>
    <div class="container">
      	<h3>ผู้พัฒนาระบบ</h3>
		<p>นายนิติพงศ์ สอลี</p>
		<p>นายหัตถพล กาญจนะ</p>
		<p>นางสาวอนันตญา อุธาทอง</p>
		<p>นางสาววิชุดา นารมณ์</p>
    </div>
	<div class="container">
      	<h3>ครูที่ปรึกษา</h3>
		<p>นายวันเจริญ อุปมัย</p>
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