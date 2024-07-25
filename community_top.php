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
    .picture {
        text-align: center;
        margin-bottom: 20px;
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .button1, .button2, .button3, .button4 {
        height: 50px;
        width: 100px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .button1 {
        background: #Fdd35c;
    }

    .button2 {
        background: #66cdaa;
    }

    .button3 {
        background: #c9c9c9;
    }
    
    .button4 {
        background: #ff6347; /* 赤色 */
    }


    .exipo{
        text-align:center;
    }
</style>
<body>
<?php
        $user_id = null; // 初期値を設定する

        if (isset($_SESSION['User']['user_id'])) {
            // ログインしている場合の処理
            $user_id = $_SESSION['User']['user_id']; // ユーザーIDを取得する
        }

        $pdo = new PDO($connect, USER, PASS);
        $sql = $pdo->prepare('SELECT * FROM community WHERE community_id = ?');
        $sql->execute([$_GET['id']]);
        foreach ($sql as $row) {
            $id = $row['community_id'];
            echo '<div class="picture">';
            echo '<p><img alt="image" src="img/', $row['jpg'], '.jpg" height="250" width="300"></p>';
            echo '</div>';
            echo '<div class="exipo"><h1>', $row['community_name'], '</h1>';
            echo 'コミュニティの説明：', $row['exipo'],'</div>';
            
            echo '<div class="button-container">';
            if ($user_id !== null) {
                // ログインしている場合の処理
                $sql_check = $pdo->prepare('SELECT * FROM community_joinuser WHERE user_id = ? AND community_id = ?');
                $sql_check->execute([$user_id, $_GET['id']]);
                if ($sql_check->rowCount() == 0) {
                    echo '<form action="community_insert.php" method="get">';
                    echo '<input type="hidden" name="id" value="', $id, '">';
                    echo '<input type="submit" value="参加する" class="button4">';
                    echo '</form>';
                }else{
                    echo '<form action="leave_community.php" method="post">';
                    echo '<input type="hidden" name="community_id" value="', $id, '">';
                    echo '<input type="submit" value="退会する" class="button4">';
                    echo '</form>';
                }
            }

            echo '<form action="join_user.php" method="post">';
            echo '<input type="hidden" name="community_id" value="', $id, '">';
            echo '<input type="submit"  value="参加メンバー" class="button1">';
            echo '</form>';

            echo '<form action="community_chat.php" method="post">';
            echo '<input type="hidden" name="id" value="', $id, '">';
            echo '<input type="submit" value="チャットへ" class="button2">';
            echo '</form>';
            echo '</div>';
        }
    ?>
    <form action="communitys.php" method="post">
        <input type="submit" class="button3" value="一覧へ戻る">
    </form>
</body>
</html>