<?php
include 'db_conn.php';

$condition = '';

if (isset($_POST['name'])) {
    $name = $_POST['name'];
}
if (isset($_POST['tableNumber'])) {
    $tableNumber = $_POST['tableNumber'];

}
if (!empty($name)) {
    $condition = "Where name LIKE '%$name%'";
}
if (!empty($tableNumber)) {
    $condition = "Where tableNumber = '$tableNumber'";
}

$sql = "SELECT * FROM attendees $condition";
$result = mysqli_query($conn, $sql);

$sql1 = "SELECT name FROM attendees WHERE status = 'Attended'";
$result1 = mysqli_query($conn, $sql1);
?>