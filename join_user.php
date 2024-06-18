<?php session_start(); ?>
<?php require 'header.php'; ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>コミュニティ参加ユーザー</title>
</head>
<style>
    body {
    background-color: #c3faf5c0; /* 背景色を薄いグレーに設定 */
}


.member{
    width: auto;
}

img.image{
    width: 220px;
    height: 220px;
    object-fit: none;
    border-radius:50%;
    object-position:70% 40%;
}

img.chat{
    width: 110px;
    height: 110px;
    object-fit: none;
    border-radius:50%;
    object-position:46% 50%;
}

.picture{
    text-align:center;
}

.user_inf{
    display: flex;
}

.inf{
    width: auto;
}

.frame{
    text-align: center;
}

.community{
    display: flex;
}

.com_inf{
    width: auto;
}
</style>
<body>
    <h1>コミュニティ参加ユーザー一覧</h1>
    <?php
        if (isset($_SESSION['User']['user_id'])) {
            // $_SESSION['User']が存在する場合の処理
            $user_id = $_SESSION['User']['user_id']; // ユーザーIDを取得する
        }

        $pdo=new PDO($connect, USER, PASS);
        // $sql=$pdo->prepare('select * from community_joinuser where community_id=?');
        $id=$_POST['community_id'];
        //コミュニティの名前を取ってくる
        $SQL = $pdo  ->prepare('select community_name,jpg from community where community_id=?');
        $SQL->execute([$id]);
        echo '<div class="frame">';
        foreach($SQL as $row){
            echo '<p><img alt="image" src="img/', $row['jpg'], '.jpg" height="250" width="300"></p>';
            $sql = $pdo->prepare('SELECT * FROM community_joinuser WHERE user_id=? AND community_id=?');
            $sql->execute([$user_id,$id]);
            if($sql->rowCount() == 0){
                echo '<a href="community_insert.php?id=' . $id . '">参加する</a>';
            } 
            echo '<h2>',$row['community_name'],'</h2>';
        }
        echo '</div>';

            //community_joinuserからuser_idをforeachで取る→userでIDと一致するユーザー名を表示

        $sql1 = $pdo->prepare('select user.user_id,user.user_name,user.introduction, user.icon, community_joinuser.id from user 
        left outer join community_joinuser
        on user.user_id = community_joinuser.user_id
        where community_joinuser.community_id=:id;');
        $sql1 ->execute([':id' => $id]);
        foreach($sql1 as $inf){
            $user_id=$inf['user_id'];
            echo '<div class="user_inf">';
                if (!empty($inf['icon'])){
                    echo '<a href="user_profile.php?user_id=' . $user_id . '"><img class="chat" src="icon/' . $inf['icon'] . '.jpg" alt="User Icon" height="100" width="100"></a>';
                }else{
                    echo '<a href="user_profile.php?user_id=' . $user_id . '"><img class="chat" src="icon/default_top.jpg" alt="Default Icon" height="100" width="100"></a>';
                }
                echo '<div class="inf">';
                    echo '<a href="user_profile.php?user_id=' . $user_id . '">' . $inf['user_name'] . '</a><br>';
                    if(empty($inf['introduction'])){
                        echo '紹介がありません。';
                    }else{
                        echo $inf['introduction'];
                    }
                echo '</div>';
            echo '</div>';
        }
    ?>
</body>
</html>