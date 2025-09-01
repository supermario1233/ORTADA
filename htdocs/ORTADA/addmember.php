<?php
$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name       = mysqli_real_escape_string($db, $_POST['name']);
    $address    = mysqli_real_escape_string($db, $_POST['address']);
    $contact_no = mysqli_real_escape_string($db, $_POST['contact_no']);
    $age        = mysqli_real_escape_string($db, $_POST['age']);
    $sex        = mysqli_real_escape_string($db, $_POST['sex']);
    $vehicle    = mysqli_real_escape_string($db, $_POST['vehicle']);
    $email      = mysqli_real_escape_string($db, $_POST['email']);
    $elementary = mysqli_real_escape_string($db, $_POST['elementary']);
    $secondary  = mysqli_real_escape_string($db, $_POST['secondary']);
    $college    = mysqli_real_escape_string($db, $_POST['college']);
    $municipality    = mysqli_real_escape_string($db, $_POST['municipality']);
    $incident   = mysqli_real_escape_string($db, $_POST['incident']);
    $month   = mysqli_real_escape_string($db, $_POST['month']);
    $totalIncident    = mysqli_real_escape_string($db, $_POST['totalIncident']);
    

    
    $orc_path = '';
    $license_path = '';

    if (isset($_FILES['orc_file']) && $_FILES['orc_file']['error'] == 0) {
        $orc_path = 'uploads/orc_' . time() . '_' . basename($_FILES["orc_file"]["name"]);
        move_uploaded_file($_FILES["orc_file"]["tmp_name"], $orc_path);
    }

    if (isset($_FILES['license_file']) && $_FILES['license_file']['error'] == 0) {
        $license_path = 'uploads/license_' . time() . '_' . basename($_FILES["license_file"]["name"]);
        move_uploaded_file($_FILES["license_file"]["tmp_name"], $license_path);
    }

  
    $sql = "INSERT INTO addmembers (name, address, contact_no, age, sex, vehicle, email, elementary, secondary, college, orc_file, license_file, municipality, incident, month, totalIncident, created_at) 
            VALUES ('$name', '$address', '$contact_no', '$age', '$sex', '$vehicle', '$email', '$elementary', '$secondary', '$college', '$orc_path', '$license_path','$municipality','$incident', '$month', '$totalIncident', NOW())";

    if (mysqli_query($db, $sql)) {
        $_SESSION['success'] = "Member added successfully!";
        header("Location: addmember.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Member</title>
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

        form {
            max-width: 800px;
            margin: 30px auto 60px;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.1);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2c3e50;
        }

        form label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #34495e;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="number"],
        form select,
        form input[type="file"] {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }

        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form input[type="number"]:focus,
        form select:focus,
        form input[type="file"]:focus {
            outline: none;
            border-color: #2c3e50;
            box-shadow: 0 0 5px rgba(44, 62, 80, 0.3);
        }

        
        .full-width {
            grid-column: 1 / -1;
        }

        form button[type="submit"] {
            grid-column: 1 / -1;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px 0;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        form button[type="submit"]:hover {
            background-color: #1a2733;
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

  <form method="POST" enctype="multipart/form-data" novalidate>
      <label>Full Name</label>
      <input type="text" name="name" required>

      <label>Address</label>
      <input type="text" name="address" required>

      <label>Contact Number</label>
      <input type="text" name="contact_no" required>

      <label>Age</label>
      <input type="number" name="age" required>

      <label>Sex</label>
      <select name="sex" required>
          <option value="" disabled selected>Select</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
      </select>

      <label>Registered Vehicle</label>
      <input type="text" name="vehicle" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Elementary Education</label>
      <input type="text" name="elementary">

      <label>Secondary Education</label>
      <input type="text" name="secondary">

      <label>College Education</label>
      <input type="text" name="college">

      <label>Municipality</label>
      <select name="municipality" required>
          <option value="" disabled selected>Select</option>
          <option value="N/A">N/A</option>
          <option value="Magsaysay">Magsaysay</option>
          <option value="San Jose">San Jose</option>
          <option value="Rizal">Rizal</option>
          <option value="Calintaan">Calintaan</option>
          <option value="Sablayan">Sablayan</option>
          <option value="Santa Cruz">Santa Cruz</option>
          <option value="Mamburao">Mamburao</option>
          <option value="Abra">Abra</option>
          <option value="Paluan">Paluan</option>
      </select>

      <label>Incident</label>
      <select name="incident" required>
          <option value="" disabled selected>Select</option>
          <option value="N/A">N/A</option>
          <option value="Overspeeding">Overspeeding</option>
      </select>

      <label>Month</label>
      <select name="month" required>
          <option value="" disabled selected>Select</option>
          <option>January</option>
          <option>February</option>
          <option>March</option>
          <option>April</option>
          <option>May</option>
          <option>June</option>
          <option>July</option>
          <option>August</option>
          <option>September</option>
          <option>October</option>
          <option>November</option>
          <option>December</option>
      </select>

      <label>Total Incident</label>
      <select name="totalIncident" required>
          <option value="" disabled selected>Select</option>
          <option value="N/A">N/A</option>
          <option value="1">1</option>
          <option value="2">2</option>
      </select>

      <label class="full-width">Upload OR/CR (Image or PDF)</label>
      <input type="file" name="orc_file" accept=".jpg,.jpeg,.png,.pdf" class="full-width">

      <label class="full-width">Upload License (Image or PDF)</label>
      <input type="file" name="license_file" accept=".jpg,.jpeg,.png,.pdf" class="full-width">

      <button type="submit" class="full-width">Add Member</button>
  </form>

</body>
</html>
