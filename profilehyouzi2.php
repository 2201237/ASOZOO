<?php
ob_start();
session_start();
include('header.php');

if (!isset($_SESSION['User'])) {
    header('Location: login-input.php');
    exit();
}

require 'db-connect.php';

$user_id = $_SESSION['User']['user_id'];

try {
    $stmt = $pdo->prepare('SELECT mail_address, user_name, gender, introduction, icon FROM user WHERE user_id = :user_id');
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
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
    <div class="mora">
    <h1><?php echo isset($user['user_name']) ? htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') : ''; ?>さんのプロフィール</h1>

    <?php if (isset($user['icon']) && $user['icon'] !== 'ban.png'): ?>
        <img src="icon/<?php echo htmlspecialchars($user['icon'], ENT_QUOTES, 'UTF-8'); ?>" alt="アイコン" class="responsive-img">
    <?php elseif($user['icon'] == "ban.png"): ?>
        <img src="icon/ban.png" alt="アイコン">
        <p>このアカウントは停止されています</p>
    <?php else: ?>
        <img src="icon/user.jpeg" alt="アイコン">
    <?php endif; ?>

    <p>メールアドレス: <?php echo isset($user['mail_address']) ? htmlspecialchars($user['mail_address'], ENT_QUOTES, 'UTF-8') : ''; ?></p>

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
    
    <?php if ($user['icon'] !== 'ban.png'): ?>
        <a href="profile-update.php">プロフィールを更新する</a>
    <?php endif; ?>
    
    <a href="home.php">ホームに戻る</a>
    </div>
</body>
</html>

<?php
// 出力バッファリングを終了してバッファの内容を出力
ob_end_flush();
?>
