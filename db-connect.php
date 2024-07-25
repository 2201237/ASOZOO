<?php
    const SERVER = 'mysql304.phy.lolipop.lan';
    const DBNAME = 'LAA1516821-asozoo';
    const USER = 'LAA1516821';
    const PASS = 'Passpass';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
    try {
        $pdo = new PDO('mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8', USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'データベース接続失敗: ' . $e->getMessage();
        exit();
    }
?>
