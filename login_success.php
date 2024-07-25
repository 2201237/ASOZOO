<?php
session_start();
if (!isset($_SESSION['User'])) {
    // ユーザーがログインしていない場合の処理
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login_success.css">
    <title>ログイン成功</title>
</head>
<body>
    <div class="container">
        <h1>ログインが完了しました</h1>
        <p><?php echo htmlspecialchars($_SESSION['User']['user_name'], ENT_QUOTES, 'UTF-8'); ?>さん、ようこそ！</p>
        <a href="home.php">ホームに戻る</a>
    </div>
</body>
</html>
