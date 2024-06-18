<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php require 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>コミュニティトップ</title>
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

    .button2{
        height:50px;
        width: 100px;
        text-align: center;
        display:flex;
        align-items: center;
        justify-content: center;
        background: #66cdaa;
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
            echo '<div class="picture">';
                echo '<p><img alt="image" src="img/', $row['jpg'], '.jpg" height="250" width="300"></p>';
            echo '</div>';
            $sql = $pdo->prepare('SELECT * FROM community_joinuser WHERE user_id=? AND community_id=?');
            $sql->execute([$user_id,$_GET['id']]);
            if($sql->rowCount() == 0){
                echo '<a href="community_insert.php?id=' . $id . '">参加する</a>';
            } 
            echo '<h1>', $row['community_name'], '</h1>';
            echo 'コミュニティの説明：',$row['exipo'];
            
            echo '<form action="join_user.php" method="post">';
            echo '<input type="hidden" name="community_id" value="',$id,'">';
            echo '<input type="submit"  value="参加メンバー" class="button1">';
            echo '</form>';

            echo '<form action="community_chat.php" method="post">';
            echo '<input type="hidden" name="id" value="', $id, '">';
            echo '<input type="submit" value="チャットへ" class="button2">';
            echo '</form>';
        }
    ?>
    <form action="communitys.php" method="post">
        <input type="submit" class="button3" value="一覧へ戻る" >
    </form>
</body>
</html>