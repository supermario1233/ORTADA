<?php
$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Members List</title>
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

    .logo {
      max-width: 120px;
      display: block;
      margin: 0 auto 20px auto;
      border-radius: 8px;
    }

     .container {
        flex: 1;
        padding: 30px;
    }
      
        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 50px;
    }


    th, td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #2c3e50;
        color: white;
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

<div class = "container">
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Vehicle</th>
            <th>Incident</th>
            <th>Total Incident</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT name, vehicle, incident, totalIncident FROM addmembers ORDER BY created_at ASC";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)):
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['vehicle']); ?></td>
            <td><?php echo htmlspecialchars($row['incident']); ?></td>
            <td><?php echo htmlspecialchars($row['totalIncident']); ?></td>
        </tr>
        <?php 
            endwhile; 
        else: 
        ?>
            <tr><td colspan="8">No members found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

</body>
</html>