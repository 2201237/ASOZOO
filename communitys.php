<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティ一覧</title>
</head>
<body>
    <u><p>コミュニティ一覧</p></u>
    <?php
        $pdo=new PDO($connect, USER, PASS);
        echo '<form action="community_create.php" method="post">';
        echo '<button type="submit">クリックして遷移</button>';
        echo '</form>';
        foreach($pdo->query('select * from community') as $row){
            echo $row['community_name'];
            echo '<form action="community_top.php" method="post">';
            echo '<input type="hidden" name="id" value="',$row['id'],'">';
            echo '<button type="submit" class="button">コミュニティへ</button>';
            echo '</form>';
        }
    ?>
</body>
</html>