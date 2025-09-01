<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('Location: login.php');
    exit();
}

$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    if (!empty($_POST['selected_members']) && !empty(trim($_POST['message']))) {
        $message = mysqli_real_escape_string($db, trim($_POST['message']));
        $selected = $_POST['selected_members'];

        foreach ($selected as $memberId) {
            $memberId = (int)$memberId;

           
            $insertQuery = "INSERT INTO messages (member_id, message) VALUES ($memberId, '$message')";
            if (!mysqli_query($db, $insertQuery)) {
                $feedback .= "<p style='color:red;'>Error sending to ID $memberId: " . mysqli_error($db) . "</p>";
                continue;
            }

           
            $contactRes = mysqli_query($db, "SELECT contact_no FROM addmembers WHERE id = $memberId");
            $contactRow = mysqli_fetch_assoc($contactRes);
            $contactNo = $contactRow['contact_no'];

            $apiKey = "YOUR_API_KEY"; 
            $apiParams = [
                'apikey'     => $apiKey,
                'number'     => $contactNo,
                'message'    => $message,
                'sendername' => 'ORTADA'
            ];

            $ch = curl_init('https://semaphore.co/api/v4/messages');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiParams));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            
            if (stripos($message, 'warning') !== false) {
                $warnQuery = "SELECT COUNT(*) as warning_count FROM messages WHERE member_id = $memberId AND message LIKE '%warning%'";
                $warnRes = mysqli_query($db, $warnQuery);
                $warnData = mysqli_fetch_assoc($warnRes);

                if ($warnData && $warnData['warning_count'] >= 3) {
                    mysqli_query($db, "UPDATE addmembers SET status = 'inactive' WHERE id = $memberId");
                }
            }
        }

        $feedback = "<p style='color:green;'>Message(s) sent successfully.</p>";
    } else {
        $feedback = "<p style='color:red;'>Please select members and write a message.</p>";
    }
}


$res = mysqli_query($db, "SELECT id, name, email, contact_no FROM addmembers ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Members & Message Broadcast</title>
    <link rel="stylesheet" href="style2.css">
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

<section>
    <h1>Members & Message Broadcast</h1>
    <?php echo $feedback; ?>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"> Select All</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact No.</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($res) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><input type="checkbox" name="selected_members[]" value="<?php echo $row['id']; ?>"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No members found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <label for="message">Write Message:</label>
        <textarea name="message" id="message" rows="5" required></textarea><br><br>
        <button type="submit" name="send_message">Send Message</button>
    </form>
</section>

<script>
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('input[name="selected_members[]"]').forEach(cb => cb.checked = this.checked);
    });
</script>

</body>
</html>
