<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティ</title>
</head>
<body>
    <form action="community_chat.php" method="post" onsubmit="return validate()" name="form">
    名前<input type="text" name="n">
        <textarea name="m"></textarea>
        <input type="submit" value="投稿" name="submit">
    </form>



    <?php
        // if(isset($_POST['m'])){
        //     $my_nam=htmlspecialchars($_POST["n"], ENT_QUOTES);
        //     $my_mes=htmlspecialchars($_POST["m"], ENT_QUOTES);

        //     try{
        //         $pdo=new PDO($connect,USER,PASS);

        //     }catch(){

        //     }
        // }
    ?>
</body>
</html>