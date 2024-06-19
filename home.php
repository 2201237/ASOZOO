<?php
// 出力バッファリングを開始
ob_start();
// セッションの開始
session_start();

// 必要なファイルを読み込む
require 'header.php';
require 'db-connect.php';

try {
    // データベース接続を確立する
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 今日の日付を取得
    $today = date('Y-m-d');

    // 今日のMVP投稿を取得
    $stmt = $pdo->prepare("
        SELECT p.*, u.user_name, u.icon, COUNT(un.id) AS like_count 
        FROM post p
        JOIN user u ON p.user_id = u.user_id
        LEFT JOIN user_nice un ON p.post_id = un.post_id
        WHERE DATE(p.post_day) = :today
        GROUP BY p.post_id
        ORDER BY like_count DESC
        LIMIT 1
    ");
    $stmt->execute([':today' => $today]);
    $mvpPost = $stmt->fetch(PDO::FETCH_ASSOC);

    // 使用されているタグのトップ10を取得
    $stmt = $pdo->prepare("
        SELECT t.tag_name, t.count
        FROM tag t
        ORDER BY t.count DESC
        LIMIT 10
    ");
    $stmt->execute();
    $topTags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // エラーメッセージを表示してスクリプトを終了
    echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム</title>
    <style>
        /* スタイルをここに追加 */
        .post { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .user-icon { width: 50px; height: 50px; border-radius: 50%; }
        .post-title { font-size: 1.2em; font-weight: bold; }
        .post-content { margin: 10px 0; }
        .post-info { color: #777; font-size: 0.9em; }
        .tags { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>今日のMVP</h1>
    <?php if ($mvpPost): ?>
        <div class="post">
            <img src="icon/<?= htmlspecialchars($mvpPost['icon'] ? $mvpPost['user_id'] : 'user') ?>.jpg" alt="ユーザーアイコン" class="user-icon">
            <div class="post-title"><?= htmlspecialchars($mvpPost['title']) ?></div>
            <div class="post-content"><?= nl2br(htmlspecialchars($mvpPost['content'])) ?></div>
            <div class="post-info">
                投稿者: <?= htmlspecialchars($mvpPost['user_name']) ?> | 
                投稿日時: <?= htmlspecialchars($mvpPost['post_day']) ?> | 
                いいね数: <?= htmlspecialchars($mvpPost['like_count']) ?>
            </div>
        </div>
    <?php else: ?>
        <p>今日はまだ投稿がありません。</p>
    <?php endif; ?>

    <h2>トップ10のタグ</h2>
    <div class="tags">
        <?php foreach ($topTags as $tag): ?>
            <span><?= htmlspecialchars($tag['tag_name']) ?> (<?= htmlspecialchars($tag['count']) ?>)</span><br>
        <?php endforeach; ?>
    </div>
</body>
</html>
