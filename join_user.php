<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <title>コミュニティ参加ユーザー</title>
</head>
<body>
    <h1>コミュニティ参加ユーザー一覧</h1>
    <?php
        $pdo=new PDO($connect, USER, PASS);
        // $sql=$pdo->prepare('select * from community_joinuser where community_id=?');
        $id=$_POST['community_id'];
        //コミュニティの名前を取ってくる
        $SQL = $pdo  ->prepare('select community_name from community where community_id=?');
        $SQL->execute([$id]);
        foreach($SQL as $row){
            echo '<h2>',$row['community_name'],'<h2>';
        }

        //community_joinuserからuser_idをforeachで取る→userでIDと一致するユーザー名を表示

        $sql1 = $pdo->prepare('select user.user_name, user.icon, community_joinuser.id from user 
        left outer join community_joinuser
        on user.user_id = community_joinuser.user_id
        where community_joinuser.community_id=:id;');
        $sql1 ->execute([':id' => $id]);
        foreach($sql1 as $inf){
            if (!empty($inf['icon'])){
                echo '<a href="user_profile.php?user_id=' . $user_id . '"><img class="chat" src="img/' . $inf['icon'] . 'jpg" alt="User Icon" height="100" width="100"></a>';
            }else{
                echo '<img class="chat" src="img/default_icon.jpg" alt="Default Icon" height="100" width="100">';
            }
            echo '<span class="username">' . $inf['user_name'] . '</span>';
            echo '<br>';
        }
    ?>
</body>
</html>