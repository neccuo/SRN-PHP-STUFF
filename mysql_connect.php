<?php
    $user = 'root';
    $password = 'root';
    $db = 'unityaccess';
    $host = 'localhost';
    $port = 3307;

    $link = mysqli_init();
    $success = mysqli_real_connect(
    $link,
    $host,
    $user,
    $password,
    $db,
    $port
    );

    if (mysqli_connect_errno())
    {
        echo "1: connection failed " . mysqli_connect_error(); // error code #1 = connection failed
        exit();
    }
?>