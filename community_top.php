<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <title>コミュニティトップ</title>
</head>
<body>
    <?php
        if (isset($_SESSION['User']['user_id'])) {
            // $_SESSION['User']が存在する場合の処理
            $user_id = $_SESSION['User']['user_id']; // ユーザーIDを取得する
        }

        $pdo=new PDO($connect, USER, PASS);
        $sql=$pdo->prepare('select * from community where community_id=?');
        $sql->execute([$_GET['id']]);
        foreach ($sql as $row){
            $id=$row['community_id'];
            echo '<p><img alt="image" src="img/', $row['jpg'], '.jpg" height="100" width="120"></p>';
            $sql = $pdo->prepare('SELECT * FROM community_joinuser WHERE user_id=? AND community_id=?');
            $sql->execute([$user_id,$_GET['id']]);
            if($sql->rowCount() == 0){
                echo '<a href="community_insert.php?id=' . $id . '">参加する</a>';
            } 
            echo '<h1>', $row['community_name'], '</h1>';
            echo $row['exipo'];
            


            echo '<a href="community_chat.php?id=', $id, '">チャットへ</a>';
        }
    ?>

    <form action="communitys.php" method="post">
        <input type="submit" value="一覧へ戻る" class="button2">
    </form>
</body>
</html>