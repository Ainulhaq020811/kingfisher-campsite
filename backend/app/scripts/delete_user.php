<?php
include '/config/db_connection.php';
include '/Models/UsersModel.php';
include '/Models/User.php';
include '/Controllers/Users.php';

function deleteUser($id) {
// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the ID from the URL

    // Prepare delete query
    $deleteQuery = "DELETE FROM customer WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Call reorder_ids procedure to reorder IDs
        $reorderQuery = "CALL reorder_ids()";
        if ($conn->query($reorderQuery)) {
            echo "User deleted and IDs reordered successfully.";
        } else {
            echo "Error reordering IDs: " . $conn->error;
        }
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "User ID not provided.";
}

$conn->close();
}
//      *
//      * @return ResponseInterface
//      */
//     public function new()
//     {
//         //
//     }
