<?php
if (isset($_POST['imgBase64'])) {
    $data = $_POST['imgBase64'];
    $data = str_replace('data:image/jpeg;base64,', '', $data);
    $data = str_replace(' ', '+', $data);
    $data = base64_decode($data);
    $file = 'uploads/' . uniqid() . '_watermarked.jpg';
    file_put_contents($file, $data);
    echo $file;
}
?>
