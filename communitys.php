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
        echo '<button type="submit">コミュニティ作成</button>';
        echo '</form>';
        foreach($pdo->query('select * from community') as $row){
            $id=$row['community_id'];
            echo '<h3><a href="community_top.php?id=', $id, '">', $row['community_name'], '</a></h3>';
        }
    ?>
</body>
</html>