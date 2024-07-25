<?php
// 出力バッファリングを開始
ob_start();
// セッションの開始
session_start();

// 必要なファイルを読み込む
require 'db-connect.php';
require 'header.php';

try {
    // データベース接続を確立する
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 今日の日付を取得
    $today = date('Y-m-d');

    // 今日のMVP投稿を取得（その日にいいねされた投稿）
    $stmt = $pdo->prepare("
        SELECT p.*, u.user_name, u.icon, COUNT(un.id) AS like_count 
        FROM post p
        JOIN user u ON p.user_id = u.user_id
        JOIN user_nice un ON p.post_id = un.post_id
        WHERE DATE(un.date) = ?
        GROUP BY p.post_id
        ORDER BY like_count DESC, un.date DESC
        LIMIT 1
    ");
    $stmt->execute([$today]);
    $mvpPost = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mvpPost) {
        // その日にいいねされた投稿がない場合、一番新しい投稿を取得
        $stmt = $pdo->prepare("
            SELECT p.*, u.user_name, u.icon 
            FROM post p
            JOIN user u ON p.user_id = u.user_id
            ORDER BY p.post_day DESC
            LIMIT 1
        ");
        $stmt->execute();
        $mvpPost = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 使用されているタグのトップ10を取得
    $stmt = $pdo->prepare("
        SELECT t.tag_name, t.count
        FROM tag t
        ORDER BY t.count DESC
        LIMIT 10
    ");
    $stmt->execute();
    $topTags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // すべてのタグを取得
    $allTagsQuery = "SELECT t.tag_name, t.count FROM tag t ORDER BY t.tag_name ASC";
    if (isset($_GET['search'])) {
        $searchTerm = '%' . $_GET['search'] . '%';
        $allTagsQuery = "SELECT t.tag_name, t.count FROM tag t WHERE t.tag_name LIKE :searchTerm ORDER BY t.tag_name ASC";
        $stmt = $pdo->prepare($allTagsQuery);
        $stmt->execute([':searchTerm' => $searchTerm]);
    } else {
        $stmt = $pdo->prepare($allTagsQuery);
        $stmt->execute();
    }
    $allTags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
        .user-icon { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
        .user-info { display: flex; align-items: center; margin-bottom: 10px; }
        .post-title { font-size: 1.2em; font-weight: bold; }
        .post-content { margin: 10px 0; }
        .post-info { color: #777; font-size: 0.9em; }
        .tags { margin-top: 20px; }
        .media { margin: 10px 0; }
        .toukou { 
            width: 100%; 
            height: auto; 
            max-width: 500px; 
            max-height: 500px; 
            object-fit: cover; /* アスペクト比を保ちながらコンテンツをカバー */
        }
        .toukou-video {
            width: 100%; 
            max-width: 500px; 
            max-height: 500px; 
            object-fit: cover; /* アスペクト比を保ちながらコンテンツをカバー */
        }
    </style>
</head>
<body>
    <h1>今日のMVP</h1>
    <?php if ($mvpPost): ?>
        <div class="post">
            <div class="user-info">
                <?php if (!empty($mvpPost['icon'])): ?>
                    <a href="user_profile.php?user_id=<?= htmlspecialchars($mvpPost["user_id"]) ?>">
                        <img src="icon/<?= htmlspecialchars($mvpPost['icon']) ?>" alt="アイコン" class="user-icon">
                    </a>
                <?php else: ?>
                    <a href="user_profile.php?user_id=<?= htmlspecialchars($mvpPost["user_id"]) ?>">
                        <img src="icon/user.jpeg" alt="アイコン" class="user-icon">
                    </a>
                <?php endif; ?>
                <strong>
                    <a href="user_profile.php?user_id=<?= htmlspecialchars($mvpPost["user_id"]) ?>">
                        <?= htmlspecialchars($mvpPost['user_name']) ?>
                    </a>
                </strong>
            </div>
            <div class="post-title"><?= htmlspecialchars($mvpPost['title']) ?></div>
            <div class="post-content"><?= nl2br(htmlspecialchars($mvpPost['content'])) ?></div>
            <div class="media">
                <?php if (!empty($mvpPost['picture'])): ?>
                    <?php
                    $file_extension = pathinfo($mvpPost['picture'], PATHINFO_EXTENSION);
                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="img/<?= htmlspecialchars($mvpPost['picture']) ?>" alt="画像" class="toukou">
                    <?php elseif (in_array($file_extension, ['mp4', 'webm', 'ogg'])): ?>
                        <video controls class="toukou-video">
                            <source src="movies/<?= htmlspecialchars($mvpPost['picture']) ?>" type="video/<?= htmlspecialchars($file_extension) ?>">
                            お使いのブラウザは動画タグに対応していません。
                        </video>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!empty($mvpPost['link'])): ?>
                    <?php
                    $youtube_id = '';
                    if (strpos($mvpPost['link'], 'youtu.be/') !== false) {
                        $youtube_id = substr(parse_url($mvpPost['link'], PHP_URL_PATH), 1);
                    } elseif (strpos($mvpPost['link'], 'youtube.com/watch') !== false) {
                        parse_str(parse_url($mvpPost['link'], PHP_URL_QUERY), $youtube_params);
                        if (isset($youtube_params['v'])) {
                            $youtube_id = $youtube_params['v'];
                        }
                    }

                    if (!empty($youtube_id)): ?>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?= htmlspecialchars($youtube_id) ?>" frameborder="0" allowfullscreen></iframe>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="post-info">
                投稿者: <?= htmlspecialchars($mvpPost['user_name']) ?> | 
                投稿日時: <?= htmlspecialchars($mvpPost['post_day']) ?> | 
                本日のいいね数: <?= htmlspecialchars($mvpPost['like_count'] ?? 0) ?>
            </div>
        </div>
    <?php else: ?>
        <p>今日はまだ投稿がありません。</p>
    <?php endif; ?>

    <h2>トップ10のタグ</h2>
    <div class="tags">
        <?php foreach ($topTags as $tag): ?>
            <a href="toukouAll.php?tag=<?= urlencode($tag['tag_name']) ?>">
                <span><?= htmlspecialchars($tag['tag_name']) ?> (<?= htmlspecialchars($tag['count']) ?>)</span>
            </a><br>
        <?php endforeach; ?>
    </div>

    <h2>すべてのタグ</h2>
    <form method="get" action="">
        <input type="text" name="search" placeholder="タグを検索" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">検索</button>
    </form>
    <div class="tags">
        <?php if (empty($allTags)): ?>
            <p>タグが見つかりませんでした。</p>
        <?php else: ?>
            <?php foreach ($allTags as $tag): ?>
                <a href="toukouAll.php?tag=<?= urlencode($tag['tag_name']) ?>">
                    <span><?= htmlspecialchars($tag['tag_name']) ?> (<?= htmlspecialchars($tag['count']) ?>)</span>
                </a><br>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
