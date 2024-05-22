<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティトップ</title>
</head>
<body>
    <?php
        $pdo=new PDO($connect, USER, PASS);
        $sql=$pdo->prepare('select * from community where community_id=?');
        $sql->execute([$_GET['id']]);
        foreach ($sql as $row){
            $id=$row['community_id'];
            echo '<a href="community_chat.php?id=', $id, '"></a>';
            echo '<form action="community.php" method="post">';
            echo '<tr><td><input type="submit" id="button5" value="参加する"></td>';
            echo '</form>';
            echo '<h1>', $row['community_name'], '</h1>';
            echo $row['exipo'];
        }


    ?>
</body>
</html>