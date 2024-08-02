<?php
include 'config.php';


$user_id = $_GET['id'];

$sql = "DELETE FROM user WHERE user_id = '{$user_id}'";

if (mysqli_query($connection, $sql)) {
    header("Location: {$hostname}/admin/users.php");
} else {
    echo '<div class="alert alert-danger text-center" role="alert">
                Unable to delete user record.
                </div>';
}

mysqli_close($connection);

if ($_SESSION["user_role"] == 0) {
    header("Location: {$hostname}/admin/post.php");
}
