<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>

<?php require "header.php";  ?>

<?php 

// データベース接続設定
const HOST = 'mysql304.phy.lolipop.lan';
const DBNAME = 'LAA1516821-asozoo'; 
const USER = 'LAA1516821';
const PASSWORD = 'Passpass';

try {
  $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, USER, PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch(PDOException $e){
  echo "接続エラー: " . $e->getMessage();
  exit();  
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $mail_address = $_POST['mail_address'];
  $gender = $_POST['gender'];

  $sql = "INSERT INTO user (user_name, pass, mail_address, gender) VALUES (:username, :password, :mail_address, :gender)";
  
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  $stmt->bindParam(':mail_address', $mail_address);
  $stmt->bindParam(':gender', $gender);

  if($stmt->execute()) {
    echo '登録に成功しました!';
  } else {
    echo '登録に失敗しました'; 
  }

}

?>

<form method="post">
  <label>ユーザー名:</label>
  <input type="text" name="username">

  <label>パスワード:</label>
  <input type="password" name="password">

  <label>メールアドレス:</label>
  <input type="text" name="mail_address">

  <label>性別:</label>
  <select name="gender">
    <option value="1">男性</option>
    <option value="2">女性</option>
    <option value="3">その他</option>  
  </select>

  <button type="submit">登録</button> 
</form>

</body>
</html>
