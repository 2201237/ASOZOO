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
    <title>ログイン成功</title>
</head>
<body>
    <h1>ログインが完了しました</h1>
    <a href="home.php">ホームに戻る</a>
    <p><?php echo htmlspecialchars($_SESSION['User']['user_id'], ENT_QUOTES, 'UTF-8'); ?>さん、ようこそ！</p>
</body>
</html>
