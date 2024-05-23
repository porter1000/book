<?php
if (isset($_FILES['file'])) {
    $errors = [];
    $path = 'uploads/user_images/';
    $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['file']['name'])));

    $file = $path . uniqid() . '.' . $file_ext;
    if (!in_array($file_ext, $extensions)) {
        $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
    }

    if (empty($errors)) {
        move_uploaded_file($file_tmp, $file);
        echo $file;
    } else {
        print_r($errors);
    }
}
?>
