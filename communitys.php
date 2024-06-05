<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <title>コミュニティ一覧</title>
    <?php require 'header.php'; ?>
</head>
<style>
    .frame{
        position:relative;
    }
</style>
<body>
    <u><p>コミュニティ一覧</p></u>
    <?php
        $pdo = new PDO($connect, USER, PASS);
        echo '<form action="community_create-input.php" method="post">';
        echo '<button type="submit">コミュニティ作成</button>';
        echo '</form>';

        foreach ($pdo->query('SELECT * FROM community') as $row) {
            $id = $row['community_id'];
            $image_folder = 'img/';
            $images = glob($image_folder . $row['jpg'] . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            echo '<div class="frame">';
                // 画像が存在するか確認して表示
                if (!empty($images)) {
                    echo '<img class="image" alt="image" src="' . $images[0] . '" height="100" width="120">';
                } else {
                    echo '<img alt="no image" src="img/no_image.jpg" height="100" width="120">';
                }

                $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM community_joinuser WHERE community_id = :community_id');
                $stmt->bindParam(':community_id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $count = $stmt->fetch();
                
                echo '<h3><a href="community_top.php?id=' . $id . '">' . htmlspecialchars($row['community_name'], ENT_QUOTES, 'UTF-8') . '</a></h3>';
                echo '<div class="member">';
                    echo '👤' . $count['cnt'];
                echo '</div>';
            echo '</div>';
        }
    ?>
</body>
</html>
