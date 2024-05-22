<?php 
      require 'header.php';
?>


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

<form action="login-output.php" name="login"  method="post">
  <label for="email">メールアドレス</label>
  <input type="email" name="email" id="email">
  <label for="password">パスワード</label>
  <input type="password" name="password" id="password">
  <button>ログイン</button>  
</form>

</body>
</html>

