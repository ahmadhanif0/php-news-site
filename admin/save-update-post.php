<?php
include 'config.php';

if (empty($_FILES['new-image']['name'])) {
    $file_name = $_POST['old_image'];
} else {
    $errors = array();

    $file_name = $_FILES['new-image']['name'];
    $file_size = $_FILES['new-image']['size'];
    $file_tmp = $_FILES['new-image']['tmp_name'];
    $file_type = $_FILES['new-image']['type'];
    $file_parts = explode('.', $file_name);
    $file_ext = strtolower(end($file_parts));
    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "This extension file is not allowed. Please upload JPF or PNG file.";
    }

    if ($file_size > 2097152) {
        $errors[] = "File size should be less than 2MBs";
    }
    $new_name = time() . "-" . basename($file_name);
    $target = "upload/" . $new_name;
    $image_name = $new_name;

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
    } else {
        print_r($errors);
        die();
    }
}

// $sql = "UPDATE post SET title ='{$_POST["post_title"]}',description = '{$_POST["postdesc"]}', category ={$_POST["category"]}, post_img ='$file_name'
//     WHERE post_id = {$_POST["post_id"]}";

// if ($_POST['old_category'] != $_POST["category"]) {
//     $sql .= "UPDATE category SET post= post - 1 WHERE category_id = {$_POST['old_category']};";
//     $sql .= "UPDATE category SET post= post + 1 WHERE category_id = {$_POST["category"]};";
// }

$sql1 = "UPDATE post SET title ='{$_POST["post_title"]}', description = '{$_POST["postdesc"]}', category = {$_POST["category"]}, post_img = '$file_name' WHERE post_id = {$_POST["post_id"]}";

if ($_POST['old_category'] != $_POST["category"]) {
    $sql2 = "UPDATE category SET post = post - 1 WHERE category_id = {$_POST['old_category']}";
    $sql3 = "UPDATE category SET post = post + 1 WHERE category_id = {$_POST["category"]}";
}

if (mysqli_query($connection, $sql1)) {
    if (isset($sql2) && isset($sql3)) {
        mysqli_query($connection, $sql2);
        mysqli_query($connection, $sql3);
    }
    echo "Record updated successfully.";
} else {
    echo "Error updating record: " . mysqli_error($connection);
}


$result = mysqli_query($connection, $sql1);
if ($result) {
    header("Location: {$hostname}/admin/post.php");
} else {
    echo 'Query Failed.';
}
