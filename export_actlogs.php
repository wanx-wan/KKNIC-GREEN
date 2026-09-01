<?php
$conn = mysqli_connect("localhost","root","","kknicdb");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=act_logs.xls");

echo "<table border='1'>";
echo "<tr>
<th>ชื่อ</th>
<th>กิจกรรม</th>
<th>วันที่</th>
<th>คะแนน</th>
</tr>";

$sql = "
SELECT u.firstname, a.name AS act_name, al.joined_at, al.point_earn
FROM act_logs al
JOIN users u ON al.user_id = u.id
JOIN acts a ON al.acts_id = a.id
";

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)){
  echo "<tr>
  <td>{$row['firstname']}</td>
  <td>{$row['act_name']}</td>
  <td>{$row['joined_at']}</td>
  <td>{$row['point_earn']}</td>
  </tr>";
}

echo "</table>";