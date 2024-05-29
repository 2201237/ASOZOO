<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティ</title>
</head>
<body>

    <?php
        $pdo=new PDO($connect, USER, PASS);
        $sql=$pdo->prepare('select * from community where community_id=?');
        $sql->execute([$_GET['id']]);
        foreach ($sql as $row){
            $id=$row['community_id'];
            echo '<h1>', $row['community_name'], '</h1>';
            echo '<p><img alt="image" src="img/', $row['jpg'], '.jpg" height="100" width="120"></p>';
        }
    ?>

    <form action="community_chat.php" method="post" onsubmit="return validate()" name="form">
    名前<input type="text" name="n">
        <textarea name="m"></textarea>
        <input type="submit" value="投稿" name="submit">
    </form>



    
</body>
</html>