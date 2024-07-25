<?php
session_start();
require 'header.php';
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

$data = [];

$query = $pdo->query('SELECT * FROM post where category_id=2 ');
$data = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>YouTube動画一覧</title>
</head>
<body>
    <h2>YouTube動画一覧</h2>
    <button onclick="location.href='toukou-link-input.php'">共有</button>



    <?php foreach ($data as $post): ?>
        <div class="post">
            <h3><?php echo $post['title']; ?></h3>
            <p><?php echo $post['content']; ?></p>
            <?php if ($post['link']): ?>
                <div class="youtube-video">
                    <?php
                    // YouTubeのリンクから埋め込みコードを生成
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $post['link'], $matches);
                    $youtubeVideoId = isset($matches[1]) ? $matches[1] : '';
                    $embedCode = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $youtubeVideoId . '" frameborder="0" allowfullscreen></iframe>';
                    echo $embedCode;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</body>
</html>
