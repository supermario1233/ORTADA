<?php
$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM addmembers WHERE id = $id";
    if (mysqli_query($db, $sql)) {
        header("Location: manageAccount.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($db);
    }
} else {
    echo "Invalid request.";
}
?>
