<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Document</title>
</head>
<body>
<?php

// 参加する押下したら
// community_joinuserのテーブルにコミュニティIDのとユーザーID
    if (isset($_SESSION['User']['user_id'])){

        $pdo = new PDO($connect, USER, PASS);
        // $user_id= "8" ;
        $user_id=$_SESSION['User']['user_id'];
        
        $sql = "INSERT INTO community_joinuser (community_id, user_id) VALUES (:community_id, :user_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':community_id', $_GET['id']);
        $stmt->bindParam(':user_id', $user_id);

        if($stmt->execute()) {
          echo '登録に成功しました!';
          exit;
        } else {
          echo '登録に失敗しました'; 
          exit;
        }
        
        $sql=$pdo->prepare('select * from community where community_id=?');
        $sql->execute([$_GET['id']]);
    }else{
        echo 'ログインしてください';
    }
?>
<form action="communitys.php" method="post">
<input type="submit" value="一覧へ戻る" class="button2">
</form>
</body>
</html>