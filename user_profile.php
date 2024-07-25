<?php
session_start(); // session_start()を先頭に移動
include('header.php'); // session_start()の後にincludeを移動

// if (!isset($_SESSION['User'])) {
//     // ユーザーがログインしていない場合の処理
//     header('Location: login.php');
//     exit();
// }

require 'db-connect.php'; // データベース接続ファイルを含む

$user_id = $_REQUEST['user_id'];

try {
    // ユーザー情報を取得するためのクエリを準備（introductionカラムを追加）
    $stmt = $pdo->prepare('SELECT mail_address, user_name, gender, introduction, icon FROM user WHERE user_id = :user_id');
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // ユーザーが見つからない場合の処理
        echo 'ユーザー情報が見つかりません。';
        exit();
    }
} catch (PDOException $e) {
    echo 'エラーが発生しました: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/profile.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール</title>
    <style>
        /* 画像がウィンドウサイズに応じて調整されるようにするCSS */
        .responsive-img {
            width: 100%;
            height: auto; /* アスペクト比を保つ */
            max-width: 450px; /* 最大サイズの制限（必要に応じて変更） */
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?>さんのプロフィール</h1>
    <?php if (isset($user['icon']) && !empty($user['icon'])): ?>
        <img src="icon/<?php echo htmlspecialchars($user['icon'], ENT_QUOTES, 'UTF-8'); ?>" alt="アイコン" class="responsive-img">
    <?php else: ?>
        <img src="icon/user.jpeg" alt="デフォルトアイコン">
    <?php endif; ?>
    <p>メールアドレス: <?php echo htmlspecialchars($user['mail_address'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>性別:
    <?php
    $gender = isset($user['gender']) ? htmlspecialchars($user['gender'], ENT_QUOTES, 'UTF-8') : '';
    switch ($gender) {
        case 1:
            echo '男性';
            break;
        case 2:
            echo '女性';
            break;
        case 3:
            echo '秘密';
            break;
        default:
            echo '不明';
            break;
    }
    ?>
    </p>
    <!-- 自己紹介の表示を追加 -->
    <p>自己紹介: 
        <?php 
        if (!empty($user['introduction'])) {
            echo htmlspecialchars($user['introduction'], ENT_QUOTES, 'UTF-8');
        } else {
            echo '紹介がありません。';
        }
        ?>
    </p>
    
    <a href="home.php">ホームに戻る</a> 
    <a href="communitys.php">コミュニティ画面</a> 
</body>
</html>
