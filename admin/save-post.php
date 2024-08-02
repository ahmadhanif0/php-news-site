<?php
include 'config.php';
// Code for image upload
if (isset($_FILES['fileToUpload'])) {
    $errors = array();

    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_type = $_FILES['fileToUpload']['type'];
    $file_parts = explode('.', $file_name);
    $file_ext = strtolower(end($file_parts));
    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "This extension file is not allowed. Please upload JPF or PNG file.";
    }

    if ($file_size > 2097152) {
        $errors[] = "File size should be less than 2MBs";
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
    } else {
        print_r($errors);
        die();
    }
}
// Code end for image upload

session_start();
$title = mysqli_real_escape_string($connection, $_POST['post_title']);
$description = mysqli_real_escape_string($connection, $_POST['postdesc']);
$category = mysqli_real_escape_string($connection, $_POST['category']);
$date = date("d M, Y");
$author = $_SESSION['user_id'];

$sql = " INSERT INTO post(title,description,category,post_date,author,post_img)
VALUES('{$title}', '{$description}', {$category}, '{$date}', '{$author}', '{$file_name}');";

$sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$category}";

if (mysqli_multi_query($connection, $sql)) {
    header("Location: {$hostname}/admin/post.php");
} else {
    '<div class="alert alert-danger text-center" role="alert">
    Query Failed.
    </div>';
}
