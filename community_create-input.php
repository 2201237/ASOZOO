<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>コミュニティ作成</title>
    <?php require 'header.php'; ?>
    <style>
        .button1 {
            height: 50px;
            width: 100px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #Fdd35c;
            font-size: 15px;
        }
        .button3 {
            height: 50px;
            width: 100px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            background: #c9c9c9;
        }
    </style>
</head>
<body>
    <h2>コミュニティ創設！</h2>
    <?php
    if (!isset($_SESSION['User']['user_id'])) {
        echo "<h1>ログインしてください</h1>";
        echo '<form action="communitys.php" method="post">';
        echo '<input type="submit" name="return" value="戻る" class="button3">';
        echo '</form>';
    } else {
        $user_id = $_SESSION['User']['user_id'];
    }
    ?>
    <?php if (isset($_SESSION['User']['user_id'])): ?>
        <form enctype="multipart/form-data" action="community_create-output.php" method="post" name="form">
            <p>コミュニティのアイコン<input name="user_file_name" type="file" required /></p>
            <p>コミュニティの名称<input type="text" name="name" required /></p>
            <p>コミュニティの説明<textarea name="exipo" required></textarea></p>
            <p>
                <input type="submit" name="insert" value="登録へ" class="button1">
            </p>
        </form>
        <form action="communitys.php" method="post">
            <input type="submit" name="return" value="戻る" class="button3">
        </form>
    <?php endif; ?>
</body>
</html>