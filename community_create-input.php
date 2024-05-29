<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティ作成</title>
    <?php require 'header.php'; ?>
</head>
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
                    <input type="submit" name="insert" value="登録へ" class="button">
                    <input type="submit" name="return" value="戻る" class="button">
                </p>
            </form>
    <?php
        } else {
            echo "<h1>ログインしてください</h1>";
        }
    ?>
</body>
</html>
