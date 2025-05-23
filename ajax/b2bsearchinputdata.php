<?php
    include_once("../../admin/includes/config.php");
    include_once("../../admin/includes/connection.php");
    include_once("../../admin/classes/admin.cls.php");
    $admin= new Admin();
    // echo BASE_URL;


    $_POST = json_decode(file_get_contents('php://input'), true);
    $slug= $_POST['slug'];
    echo $baseurl."product/".$slug;


?>