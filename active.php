<?php
$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle filters
$search = isset($_GET['search']) ? mysqli_real_escape_string($db, $_GET['search']) : '';
$municipality = isset($_GET['municipality']) ? mysqli_real_escape_string($db, $_GET['municipality']) : '';

$whereClauses = [];
if ($search !== '') {
    $whereClauses[] = "(name LIKE '%$search%' OR address LIKE '%$search%' OR email LIKE '%$search%' OR vehicle LIKE '%$search%')";
}
if ($municipality !== '') {
    $whereClauses[] = "municipality = '$municipality'";
}
$whereSQL = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT name, address, email, age, contact_no, vehicle, status FROM addmembers $whereSQL ORDER BY created_at ASC";
$result = mysqli_query($db, $sql);
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

        .filters {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between; 
            flex-wrap: wrap;
        }
        .filters input[type="text"],
        .filters select {
            padding: 8px;
            font-size: 16px;
            margin: 5px 0;
            width: 48%;
        }
        .filters button {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        .print-btn {
            float: right;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }

        function onMunicipalityChange() {
            document.getElementById("filterForm").submit();
        }
    </script>
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


<div class="container">
    <form method="GET" class="filters" id="filterForm">
        <input type="text" name="search" placeholder="Search by name, address, email..." value="<?php echo htmlspecialchars($search); ?>">

        <select name="municipality" onchange="onMunicipalityChange()">
            <option value="">-- Select Municipality --</option>
            <?php
            $municipalities = ['Magsaysay', 'San Jose', 'Rizal', 'Calintaan', 'Sablayan', 'Santa Cruz', 'Mamburao', 'Abra', 'Paluan'];
            foreach ($municipalities as $m) {
                $selected = ($municipality == $m) ? 'selected' : '';
                echo "<option value=\"$m\" $selected>$m</option>";
            }
            ?>
        </select>

        <button type="button" class="print-btn" onclick="printPage()">Generate Report</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Age</th>
                <th>Contact No.</th>
                <th>Vehicle</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo (int)$row['age']; ?></td>
                        <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['vehicle']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No members found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
