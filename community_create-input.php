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
</head>
<style>
    body {
        background-color: #c3faf5c0; /* 背景色を薄いグレーに設定 */
    }

    .button1{
        height:50px;
        width: 100px;
        text-align: center;
        display:flex;
        align-items: center;
        justify-content: center;
        background: #Fdd35c;
        font-size:15px;
    }

    .button3{
        height:50px;
        width: 100px;
        text-align: center;
        display:flex;
        align-items: center;
        justify-content: center;
        font-size:15px;
        background: #c9c9c9;
    }
</style>
<body>
    <h2>コミュニティ創設！</h2>
   
    <?php
        if (isset($_SESSION['User']['user_id'])) {
            // $_SESSION['User']が存在する場合の処理
            $user_id = $_SESSION['User']['user_id']; // ユーザーIDを取得する
    ?>
            <form enctype="multipart/form-data" action="community_create-output.php" method="post" name="form">
                <p>コミュニティのアイコン<input name="user_file_name" type="file" /></p>
                <p>コミュニティの名称<input type="text" name="name" /></p>
                <p>コミュニティの説明<textarea name="exipo"></textarea></p>
                <p>
                    <input type="submit" name="insert" value="登録へ" class="button1">
                </p>
            </form>
            <form action="communitys.php" method="post">
                <input type="submit" name="return" value="戻る" class="button3">
            </form>
    <?php
        } else {
            echo "<h1>ログインしてください</h1>";
        }
    ?>
</body>
</html>
