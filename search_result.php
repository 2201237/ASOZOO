<?php
session_start();
require 'header.php';
require 'db-connect.php';

if (isset($_POST['word'])) {
    $word = $_POST['word'];
} else {
    echo "検索ワードが設定されていません。";
    exit();
}

try {
    $pdo = new PDO($connect, USER, PASS);
    $stmt = $pdo->prepare('SELECT * FROM post WHERE title LIKE ? OR content LIKE ?');
    $searchWord = '%' . $word . '%';
    $stmt->execute([$searchWord, $searchWord]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'エラーが発生しました: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>検索結果</title>
    <style>
        /* 画像のサムネイルのサイズを調整するためのスタイル */
        .media-gallery img {
            width: 200px; /* 画像の幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin: 5px; /* 画像の間隔を指定 */
        }
        /* 動画のサムネイルのサイズを調整するためのスタイル */
        .media-gallery video {
            width: 200px; /* 動画の幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin: 5px; /* 動画の間隔を指定 */
        }
        /* YouTube iframeのサイズを調整するためのスタイル */
        .media-gallery iframe {
            width: 200px; /* iframeの幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin: 5px; /* iframeの間隔を指定 */
        }
        /* ドロップダウンメニューのスタイル */
        .sort-dropdown {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <center>
        <h2>検索ワード：<?php echo htmlspecialchars($word, ENT_QUOTES, 'UTF-8'); ?></h2>
    </center>
    <h5></h5>
    <?php
    $image_folder = 'img/';
    $video_folder = 'movies/';

    foreach ($data as $post):
        $title = isset($post['title']) ? htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') : '';
        $id = htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8');
        ?>
        <div class="post">
            <?php if (!empty($post['picture']) || !empty($post['link'])): ?>
                <?php if ($post['category_id'] == 0):
                    $image_path = $image_folder . htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8'); ?>
                    <a href="toukou-detail.php?id=<?php echo $id; ?>">
                        <p><?php echo $title; ?></p>
                        <div class="media-gallery">
                            <img src="<?php echo $image_path; ?>" alt="画像">
                        </div>
                    </a>
                <?php elseif ($post['category_id'] == 1):
                    $video_path = $video_folder . htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8'); ?>
                    <a href="toukou-detail.php?id=<?php echo $id; ?>">
                        <p><?php echo $title; ?></p>
                        <div class="media-gallery">
                            <video controls>
                                <source src="<?php echo $video_path; ?>" type="video/mp4">
                                動画を再生できません
                            </video>
                        </div>
                    </a>
                <?php elseif ($post['category_id'] == 2): ?>
                    <a href="toukou-detail.php?id=<?php echo $id; ?>">
                        <p><?php echo $title; ?></p>
                        <div class="media-gallery">
                            <?php
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $post['link'], $matches);
                            $youtubeVideoId = isset($matches[1]) ? $matches[1] : '';
                            $embedCode = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8') . '" frameborder="0" allowfullscreen></iframe>';
                            echo $embedCode;
                            ?>
                        </div>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <p>メディアがありません</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
