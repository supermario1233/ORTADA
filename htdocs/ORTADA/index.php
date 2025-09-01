<?php
session_start();

if (!isset($_SESSION['username'])) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login.php');
  exit();
}
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header('location: login.php');
  exit();
}


$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

$municipalities = ['Magsaysay', 'San Jose', 'Rizal', 'Calintaan', 'Sablayan', 'Santa Cruz', 'Mamburao', 'Abra', 'Paluan'];


$data_points = [];
foreach ($municipalities as $mun) {
    $mun_esc = mysqli_real_escape_string($db, $mun);
    $q = "SELECT COUNT(*) AS total FROM addmembers WHERE municipality='$mun_esc' AND status='active'";
    $res = mysqli_query($db, $q);
    $r = mysqli_fetch_assoc($res);
    $data_points[] = "['$mun', {$r['total']}]";
}
$pie_data = implode(",\n", $data_points);


$months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
$incident_rows = [];
foreach ($months as $month) {
    $row = ["'$month'"];
    foreach ($municipalities as $mun) {
        $m = mysqli_real_escape_string($db, $mun);
        $month_esc = mysqli_real_escape_string($db, $month);
        $q = "SELECT COUNT(*) AS cnt FROM addmembers WHERE municipality='$m' AND month='$month_esc'";
        $res = mysqli_query($db, $q);
        $r = mysqli_fetch_assoc($res);
        $row[] = $r['cnt'] ?: 0;
    }
    $incident_rows[] = "[" . implode(", ", $row) . "]";
}
$incident_data = implode(",\n", $incident_rows);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ORTADA Dashboard</title>
  <link rel="stylesheet" href="style2.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script>
    google.charts.load('current', {packages:['corechart','bar']});
    google.charts.setOnLoadCallback(drawAll);

    function drawAll() {
      drawPie();
      drawIncidents();
    }

    function drawPie() {
  var data = google.visualization.arrayToDataTable([
    ['Municipality','Active Members'],
    <?php echo $pie_data; ?>
  ]);
  var options = {
    title: 'Active Members by Municipality',
    colors: ['#3498db', '#1abc9c', '#9b59b6', '#e67e22', '#f39c12', '#2ecc71', '#e74c3c', '#34495e', '#7f8c8d']
  };
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}

function drawIncidents() {
  var data = google.visualization.arrayToDataTable([
    ['Month', <?php foreach($municipalities as $mun) echo "'$mun',"; ?>],
    <?php echo $incident_data; ?>
  ]);
  var options = { 
    title: 'Incident Reports by Month & Municipality',
    isStacked: true,
    chartArea: {width:'70%'},
    hAxis: { title: 'Month' },
    vAxis: { title: 'Number of Reports' },
    colors: ['#3498db', '#1abc9c', '#9b59b6', '#e67e22', '#f39c12', '#2ecc71', '#e74c3c', '#34495e', '#7f8c8d']
  };
  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}

  </script>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f6fa;
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 240px;
      background-color: #2c3e50;
      color: white;
      display: flex;
      flex-direction: column;
      padding: 30px 0;
      position: sticky;
      top: 0;
      height: 100vh;
    }

    .sidebar h2 {
      text-align: center;
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .sidebar a,
    .sidebar form button {
      display: block;
      color: white;
      padding: 15px 25px;
      text-align: left;
      text-decoration: none;
      background: none;
      border: none;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.3s, padding-left 0.3s;
      width: 100%;
    }

    .sidebar a:hover,
    .sidebar form button:hover {
      background-color: #34495e;
      padding-left: 35px;
    }

    .sidebar form {
      margin-top: auto;
      padding: 0 20px;
    }

    .sidebar form button {
      background-color: #e74c3c;
      border-radius: 6px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .sidebar form button:hover {
      background-color: #c0392b;
    }

    .main {
      flex: 1;
      padding: 30px;
      display: flex;
      flex-direction: column;
    }

    .section {
      background-color: white;
      padding: 25px;
      border-radius: 12px;
      margin-bottom: 30px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .section h3 {
      margin-bottom: 20px;
      color: #2c3e50;
    }

    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .card {
      background-color: #3498db;
      color: white;
      padding: 18px;
      border-radius: 10px;
      text-align: center;
      font-size: 15px;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .card-san-jose { background-color: #1abc9c; }
    .card-magsaysay { background-color: #3498db; }
    .card-rizal { background-color: #9b59b6; }
    .card-calintaan { background-color: #e67e22; }
    .card-sablayan { background-color: #f39c12; }
    .card-santa-cruz { background-color: #2ecc71; }
    .card-mamburao { background-color: #e74c3c; }
    .card-abra { background-color: #34495e; }
    .card-paluan { background-color: #7f8c8d; }

    .logo {
      max-width: 120px;
      display: block;
      margin: 0 auto 20px auto;
      border-radius: 8px;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

    }
  </style>
</head>
<body>

 
  <div class="sidebar">
    <img src="ortada.png" alt="Logo" class="logo"/>
    <h2>ORTADA</h2>
    <a href="index.php">Dashboard</a>
    <a href="manageAccount.php">Manage Account</a>
    <a href="Members.php">Members</a>
    <a href="addmember.php">Add Member</a>
    <a href="incident.php">Incident Reports</a>
    <a href="active.php">Active Members</a>
    <a href="message.php">Messages</a>
    <form method="get" action="index.php"><button name="logout">Logout</button></form>
  </div>

  
  <div class="main">
    <div class="cards-container">
      <?php foreach ($municipalities as $mun): ?>
        <a href="members.php?municipality=<?php echo urlencode($mun); ?>">
  <div class="card card-<?php echo strtolower(str_replace(' ', '-', $mun)); ?>">
    <?php echo $mun; ?>
  </div>
</a>

      <?php endforeach; ?>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="section" style="background:#dff0d8; color:#3c763d;">
        <h3><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></h3>
      </div>
    <?php endif; ?>

    <div class="section">
      <div id="piechart" style="width:100%; height:500px;"></div>
    </div>

    <div class="section">
      <div id="chart_div" style="width:100%; height:500px;"></div>
    </div>
  </div>

</body>
</html>
