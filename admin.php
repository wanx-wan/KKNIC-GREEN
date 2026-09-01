<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: home.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "kknicdb");

$resultuser = mysqli_query($conn, "SELECT id, firstname, role, status FROM users");
$resultact = mysqli_query($conn, "
  SELECT id, name, point,
  DATE(start_at) AS start_at,
  DATE(end_at) AS end_at
  FROM acts
");

$resultact_log = mysqli_query($conn, "
SELECT 
  al.id,
  u.firstname,
  a.name AS act_name,
  a.point AS max_point,
  al.joined_at,
  al.files,
  al.point_earn
FROM act_logs al
JOIN users u ON al.user_id = u.id
JOIN acts a ON al.acts_id = a.id
");

$result_notify = mysqli_query($conn, "
SELECT COUNT(*) as total 
FROM act_logs 
WHERE point_earn IS NULL OR point_earn = 0
");
$notify = mysqli_fetch_assoc($result_notify)['total'];

// นับจำนวนสมาชิก
$total_users = mysqli_fetch_assoc(
  mysqli_query($conn, "SELECT COUNT(*) as c FROM users")
)['c'];

// นับจำนวนกิจกรรม
$total_acts = mysqli_fetch_assoc(
  mysqli_query($conn, "SELECT COUNT(*) as c FROM acts")
)['c'];

// นับจำนวนการเข้าร่วม
$total_logs = mysqli_fetch_assoc(
  mysqli_query($conn, "SELECT COUNT(*) as c FROM act_logs")
)['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>หน้าหลัก</title>
<link href="css/bootstrap-4.3.1.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="admin.php">KKNIC GREEN GAME</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="home.php">มุมมองผู้ใช้ทั่วไป</a>
        </li>
      </ul>
      <ul class="navbar-nav">
		 <li class="nav-item">
		  <button class="btn btn-warning btn-sm" onclick="scrollToLogs()">
			แจ้งเตือน (<?= $notify ?>)
		  </button>
		</li>
        <li class="nav-item">
        <?php if(isset($_SESSION['user_id'])): ?>
          <a class="btn btn-sm text-white">
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
<div class="container mb-3">
  <div class="row text-center">
    
    <div class="col-md-4">
      <div class="card p-2 bg-light">
        <h5>สมาชิก</h5>
        <h3><?= $total_users ?></h3>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-2 bg-light">
        <h5>กิจกรรม</h5>
        <h3><?= $total_acts ?></h3>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-2 bg-light">
        <h5>การเข้าร่วม</h5>
        <h3><?= $total_logs ?></h3>
      </div>
    </div>

  </div>
</div>
<div class="container">
  <h3>สมาชิก</h3>
  <table id="userTable" class="text-center" border="1">
    <thead>
      <tr>
        <th onclick="s(this,0)">ชื่อ-สกุล</th>
        <th onclick="s(this,1)">สถานะ</th>
        <th>จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($resultuser)): ?>
      <tr data-id="<?=$row['id']?>">
        <td class="fname"><?=htmlspecialchars($row['firstname'])?></td>
        <td class="role"><?=htmlspecialchars($row['role'])?></td>
        <td>
		  <button class="btn btn-sm btn-info toggle-status">
      			<?php echo $row['status']=='active'?'Active':'Inactive'; ?>
    	  </button>
          <button class="edit btn btn-sm btn-warning">แก้ไข</button>
          <button class="del-user btn btn-sm btn-danger">ลบ</button>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
let asc=true;
function s(t,c){
  const tb=document.querySelector("#userTable tbody");
  Array.from(tb.rows)
       .sort((a,b)=>asc?(a.cells[c].innerText>b.cells[c].innerText?1:-1)
                        :(a.cells[c].innerText<b.cells[c].innerText?1:-1))
       .forEach(r=>tb.appendChild(r));
  asc=!asc;
}

// edit
document.querySelectorAll("#userTable .edit").forEach(btn=>{
  btn.onclick = e => {
    const tr = e.target.closest("tr");
    const id = tr.dataset.id;
    const name = prompt("แก้ไขชื่อ:", tr.querySelector(".fname").innerText);
    const role = prompt("แก้ไขสถานะ:", tr.querySelector(".role").innerText);
    if(name && role){
      fetch("user_action.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`id=${id}&action=edit&name=${encodeURIComponent(name)}&role=${encodeURIComponent(role)}`
      }).then(r=>r.text()).then(r=>{
        if(r=="ok"){
          tr.querySelector(".fname").innerText = name;
          tr.querySelector(".role").innerText = role;

          // อัปเดต navbar ถ้าแก้ไขตัวเอง
          const currentUserId = <?= json_encode($_SESSION['user_id']) ?>;
          if(currentUserId == id){
            document.querySelector(".navbar .btn.btn-sm.text-white").innerText = `สวัสดี, ${name}`;
          }
        }
      });
    }
  };
});

// delete
document.querySelectorAll(".del-user").forEach(btn=>{
  btn.onclick = e => {
    const tr = e.target.closest("tr");
    const id = tr.dataset.id;
    if(confirm("คุณต้องการลบสมาชิกนี้หรือไม่?")){
      fetch("user_action.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`id=${id}&action=delete`
      }).then(r=>r.text()).then(r=>{
        if(r=="ok") tr.remove();
      });
    }
  };
});
	
// toggle status
document.querySelectorAll("#userTable .toggle-status").forEach(btn=>{
  btn.onclick = e => {
    const tr = e.target.closest("tr");
    const id = tr.dataset.id;
    fetch("user_action.php",{
      method:"POST",
      headers:{"Content-Type":"application/x-www-form-urlencoded"},
      body:`id=${id}&action=toggle`
    }).then(r=>r.text()).then(newStatus=>{
      tr.querySelector(".status").innerText = newStatus;
      btn.innerText = newStatus=='active'?'Active':'Inactive';
    });
  };
});
</script>

<br><hr>

<div class="container">
  <div class="header-admin">
    <h3>กิจกรรม</h3>
    <button id="addAct" class="btn btn-sm btn-success">เพิ่ม</button>
  </div>

  <table class="text-center" border="1">
  <tbody>
    <tr>
      <th>ชื่อกิจกรรม</th>
	  <th>คะแนน</th>
      <th>วันที่เริ่มต้น</th>
      <th>วันที่สิ้นสุด</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($resultact)): ?>
    <tr data-id="<?=$row['id']?>">
      <td><?= $row['name']; ?></td>
	  <td><?= $row['point']; ?></td>
      <td><?= $row['start_at']; ?></td>
      <td><?= $row['end_at']; ?></td>
    </tr>
    <?php endwhile; ?>

  </tbody>
  </table>
</div>
<script>
document.getElementById("addAct").onclick = ()=>{
  const name = prompt("ชื่อกิจกรรม:");
  const point = prompt("คะแนน:");
  const start = prompt("วันเริ่ม (YYYY-MM-DD):");
  const end = prompt("วันสิ้นสุด (YYYY-MM-DD):");

  if(!name || !point) return;

  fetch("acts_action.php",{
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:`action=add&name=${name}&point=${point}&start_at=${start}&end_at=${end}`
  }).then(r=>r.text()).then(()=>{
    location.reload();
  });
};

</script>

<br><hr>

<div class="container" id="logs">
  <div class="header-admin">
    <h3>การเข้าร่วมกิจกรรม</h3>
    <button onclick="window.location='export_actlogs.php'" 
        class="btn btn-success mb-2">
	  ดาวน์โหลดรายงาน
	</button>
  </div>
  <table class="text-center" border="1">
  <tbody>
	<tr>
	  <th>ชื่อ</th>
	  <th>กิจกรรม</th>
	  <th>วันที่</th>
	  <th>หลักฐาน</th>
	  <th>คะแนน</th>
	  <th>จัดการ</th>
	</tr>

	<?php while($row = mysqli_fetch_assoc($resultact_log)): ?>
	<tr data-id="<?=$row['id']?>" data-max="<?=$row['max_point']?>">
	  <td><?= $row['firstname']; ?></td>
	  <td><?= $row['act_name']; ?></td>
	  <td><?= $row['joined_at']; ?></td>
	  <td>
	  <img src="uploads/<?= $row['files']; ?>" width="80" 
		   style="cursor:pointer"
		   onclick="showImg(this.src)">
	  </td>
	  <td class="point"><?= isset($row['point_earn']) ? $row['point_earn'] : 0; ?></td>
	  <td>
		<button class="give btn btn-sm btn-success">ให้คะแนน</button>
	  </td>
	</tr>
	<?php endwhile; ?>
  </tbody>
  </table>
  <div class="modal fade" id="imgModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="imgPreview" src="" class="img-fluid">
      </div>
    </div>
  </div>
</div>
</div>
<script>
document.querySelectorAll(".give").forEach(btn=>{
  btn.onclick = e=>{
    const tr = e.target.closest("tr");
    const id = tr.dataset.id;
    const max = parseInt(tr.dataset.max);

    let score = prompt(`ให้คะแนน (0 - ${max})`);
    if(score===null) return;

    score = parseInt(score);
    if(score < 0 || score > max){
      alert("คะแนนไม่ถูกต้อง");
      return;
    }

    fetch("actlog_action.php",{
      method:"POST",
      headers:{"Content-Type":"application/x-www-form-urlencoded"},
      body:`id=${id}&action=score&point=${score}`
    }).then(r=>r.text()).then(r=>{
      if(r=="ok"){
        tr.querySelector(".point").innerText = score;
      }
    });
  }
});

	
function showImg(src){
  document.getElementById("imgPreview").src = src;
  $('#imgModal').modal('show');
}
	
function scrollToLogs(){
  document.getElementById("logs").scrollIntoView({behavior:"smooth"});
}
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

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap-4.3.1.js"></script>
</body>
</html>