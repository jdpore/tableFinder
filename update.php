<?php
include 'db_conn.php';

if (isset($_POST['name']) && isset($_POST['status'])) {
    $name = $_POST['name'];
    $status = $_POST['status'];

    // SQL update query
    $sql = "UPDATE attendees SET status = '$status' WHERE name = '$name'";

    if (mysqli_query($conn, $sql)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $id = $_POST['id'];
    $newName = $_POST['editedName'];

    // Perform update query to update the name in your database
    $sql = "UPDATE attendees SET name = '$newName' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Name updated successfully
        echo json_encode(['success' => true]);
    } else {
        // Error occurred while updating name
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
} else {
    // Handle invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>