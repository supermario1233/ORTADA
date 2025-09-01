<?php
$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $age = intval($_POST['age']);
    $contact_no = mysqli_real_escape_string($db, $_POST['contact_no']);
    $vehicle = mysqli_real_escape_string($db, $_POST['vehicle']);

    $updateSql = "UPDATE addmembers SET 
                    name = '$name',
                    address = '$address',
                    email = '$email',
                    age = $age,
                    contact_no = '$contact_no',
                    vehicle = '$vehicle'
                  WHERE id = $id";

    if (mysqli_query($db, $updateSql)) {
        header("Location: manageAccount.php");
        exit();
    } else {
        echo "Error updating member: " . mysqli_error($db);
    }
} else {
    $query = "SELECT * FROM addmembers WHERE id = $id";
    $result = mysqli_query($db, $query);
    $member = mysqli_fetch_assoc($result);
    if (!$member) {
        die("Member not found.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            .form-container {
                margin: 20px;
                padding: 30px;
            }

            button {
                padding: 12px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Member</h2>
    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>

        <label>Address</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($member['address']); ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>

        <label>Age</label>
        <input type="number" name="age" value="<?php echo (int)$member['age']; ?>" required>

        <label>Contact No.</label>
        <input type="text" name="contact_no" value="<?php echo htmlspecialchars($member['contact_no']); ?>" required>

        <label>Vehicle</label>
        <input type="text" name="vehicle" value="<?php echo htmlspecialchars($member['vehicle']); ?>" required>

        <button type="submit">Update Member</button>
    </form>
</div>

</body>
</html>
