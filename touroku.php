<?php ob_start(); // バッファリングを開始します ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/touroku.css">
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

  // メールアドレスの重複チェック
  $sql = "SELECT COUNT(*) FROM user WHERE mail_address = :mail_address";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':mail_address', $mail_address);
  $stmt->execute();
  $count = $stmt->fetchColumn();

  if ($count > 0) {
    echo "このメールアドレスは既に登録されています。別のメールアドレスを使ってください。";
  } else {
    $sql = "INSERT INTO user (user_name, pass, mail_address, gender) VALUES (:username, :password, :mail_address, :gender)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':mail_address', $mail_address);
    $stmt->bindParam(':gender', $gender);

    if($stmt->execute()) {
      header("Location: login-input.php"); // リダイレクトを行います
      exit(); // リダイレクト後にスクリプトの実行を停止します
    } else {
      echo '登録に失敗しました'; 
    }
  }

}

ob_end_flush(); // バッファリングを終了し、出力を送信します
?>

<form name="tourokuform" method="post">

  <h2>新規登録</h2>
  <label>ユーザー名:</label>
  <input type="text" name="username" required>

  <label>パスワード:</label>
  <input type="password" name="password" required>

  <label>メールアドレス:</label>
  <input type="text" name="mail_address" required>

  <label>性別:</label>
  <select name="gender" required>
    <option value="1">男性</option>
    <option value="2">女性</option>
    <option value="3">その他</option>  
  </select>

  <button name="touroku" type="submit">登録</button> 
</form>

</body>
</html>
