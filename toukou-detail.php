<?php
// 出力バッファリングを開始
ob_start();
// セッションの開始
session_start();

require 'db-connect.php'; // データベース接続のインクルード

$logged_in = isset($_SESSION['User']['user_id']);
$already_liked = false;

if ($logged_in) {
    $user_id = $_SESSION['User']['user_id'];
}

// データベース接続エラーチェック
if (!$pdo) {
    die("データベース接続に失敗しました。");
}

// いいねを処理する部分
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like']) && isset($_POST['post_id']) && $logged_in) {
    $post_id = intval($_POST['post_id']);
    
    // すでにいいねしているかどうか確認
    $check_sql = "SELECT COUNT(*) FROM user_nice WHERE user_id = :user_id AND post_id = :post_id";
    $stmt = $pdo->prepare($check_sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $already_liked = $stmt->fetchColumn();

    if (!$already_liked) {
        // いいねを追加
        $like_sql = "INSERT INTO user_nice (user_id, post_id, date) VALUES (:user_id, :post_id, NOW())";
        $stmt = $pdo->prepare($like_sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        // いいねポイントを更新
        $update_sql = "UPDATE nice_post SET nice_point = nice_point + 1 WHERE post_id = :post_id";
        $stmt = $pdo->prepare($update_sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        $already_liked = true; // いいねを追加した後にフラグを更新
    }
}

// 投稿詳細を取得
$toukou_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($toukou_id > 0) {
    // 投稿の詳細を取得するSQLクエリ
    $sql = "SELECT user_id, title, content, picture, link, post_day, category_id FROM post WHERE post_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $toukou_id, PDO::PARAM_INT);
    $stmt->execute();
    $toukou = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($toukou) {
        // 投稿者のユーザー情報を取得するSQLクエリ
        $user_sql = "SELECT user_name, icon FROM user WHERE user_id = :user_id";
        $stmt_user = $pdo->prepare($user_sql);
        $stmt_user->bindParam(':user_id', $toukou['user_id'], PDO::PARAM_INT);
        $stmt_user->execute();
        $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
    }

    // いいね数を取得するSQLクエリ
    $sql_nice = "SELECT nice_point FROM nice_post WHERE post_id = :post_id";
    $stmt_nice = $pdo->prepare($sql_nice);
    $stmt_nice->bindParam(':post_id', $toukou_id, PDO::PARAM_INT);
    $stmt_nice->execute();
    $nice_point = $stmt_nice->fetchColumn();

    // すでにいいねしているかどうか確認
    if ($logged_in) {
        $check_sql = "SELECT COUNT(*) FROM user_nice WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $pdo->prepare($check_sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $toukou_id, PDO::PARAM_INT);
        $stmt->execute();
        $already_liked = $stmt->fetchColumn();
    }

    // 投稿に紐づくタグを取得するSQLクエリ
    $tag_sql = "SELECT t.tag_name FROM tag_map tm JOIN tag t ON tm.tag_id = t.tag_id WHERE tm.post_id = :post_id";
    $stmt_tag = $pdo->prepare($tag_sql);
    $stmt_tag->bindParam(':post_id', $toukou_id, PDO::PARAM_INT);
    $stmt_tag->execute();
    $tags = $stmt_tag->fetchAll(PDO::FETCH_ASSOC);
} else {
    $toukou = false;
    $nice_point = 0;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿の詳細</title>
    <style>
        .media-detail {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .media-detail img, .media-detail video {
            width: 100%; /* 画像の幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin-bottom: 20px;
        }
        .toukou {
            margin-bottom: 20px;
        }
        .toukou-user, .toukou-title, .toukou-content, .toukou-time {
            margin-bottom: 10px;
        }
        .like-button {
            display: flex;
            align-items: center;
        }
        .like-button button {
            margin-right: 10px;
        }
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .user-info img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .tag-links a {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<?php 
require "header.php";  
?>

<div class="media-detail">
    <?php if ($toukou): ?>
        <div class="toukou">
            <div class="user-info">
                <?php if (!empty($user['icon'])): ?>
                    <a href="user_profile.php?user_id= <?= htmlspecialchars($toukou["user_id"]) ?> "><img src="icon/<?= htmlspecialchars($user['icon']) ?>" alt="アイコン"></a>
                <?php else : ?>
                <a href="user_profile.php?user_id= <?= htmlspecialchars($toukou["user_id"]) ?> "><img src="icon/user.jpeg" alt="アイコン"></a>
                <?php endif; ?>
                <strong><a href="user_profile.php?user_id= <?= htmlspecialchars($toukou["user_id"]) ?> "><?= htmlspecialchars($user['user_name']) ?></a></strong>
            </div>
            <div class="toukou-title"><strong>タイトル:</strong> <?= htmlspecialchars($toukou["title"]) ?></div>
            <div class="toukou-content"><strong>内容:</strong> <?= nl2br(htmlspecialchars($toukou["content"])) ?></div>
            <?php if (isset($toukou["category_id"])): ?>
                <?php if ($toukou["category_id"] == 0 && !empty($toukou["picture"])): ?>
                    <div class="toukou-picture"><img src="img/<?= htmlspecialchars($toukou["picture"]) ?>" alt="画像"></div>
                <?php elseif ($toukou["category_id"] == 1 && !empty($toukou["picture"])): ?>
                    <div class="toukou-video"><video src="movies/<?= htmlspecialchars($toukou["picture"]) ?>" controls></video></div>
                <?php elseif ($toukou["category_id"] == 2 && !empty($toukou["link"])): ?>
                    <div class="toukou-link">
                        <?php
                        $youtube_id = '';
                        if (strpos($toukou["link"], 'youtu.be/') !== false) {
                            // 短縮URL形式 (https://youtu.be/VIDEO_ID)
                            $youtube_id = substr(parse_url($toukou["link"], PHP_URL_PATH), 1);
                        } elseif (strpos($toukou["link"], 'youtube.com/watch') !== false) {
                            // 通常URL形式 (https://www.youtube.com/watch?v=VIDEO_ID)
                            parse_str(parse_url($toukou["link"], PHP_URL_QUERY), $youtube_params);
                            if (isset($youtube_params['v'])) {
                                $youtube_id = $youtube_params['v'];
                            }
                        }

                        if (!empty($youtube_id)): ?>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?= htmlspecialchars($youtube_id) ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="toukou-time"><strong>投稿日時:</strong> <?= htmlspecialchars($toukou["post_day"]) ?></div>
            <div class="like-button">
                <?php if ($logged_in && !$already_liked): ?>
                    <form method="post">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($toukou_id) ?>">
                        <button type="submit" name="like">いいね</button>
                    </form>
                <?php endif; ?>
                <div>いいね数: <?= htmlspecialchars($nice_point) ?></div>
            </div>
            <div class="tag-links">
                <strong>タグ:</strong>
                <?php foreach ($tags as $tag): ?>
                    <a href="toukouAll.php?tag=<?= urlencode($tag['tag_name']) ?>"><?= htmlspecialchars($tag['tag_name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <p>投稿が見つかりませんでした。</p>
    <?php endif; ?>

    <a href="toukouAll.php">つぶやき一覧に戻る</a>
</div>

</body>
</html>

<?php
// 出力バッファリングを終了してバッファの内容を出力
ob_end_flush();
?>
