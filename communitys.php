<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>コミュニティ一覧</title>
    <?php require 'header.php'; ?>
</head>
<style>
    .make {
        height: 50px;
        width: 300px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FF4F50;
        font-size: 30px;
    }

    .right {
        display: flex;
        justify-content: flex-end;
    }

    

    .community-list {
        display: flex;
        flex-wrap: wrap; /* 複数行に対応するための設定 */
        gap: 20px; /* アイテム間のスペースを設定 */
    }

    .community {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 220px; /* 各コミュニティの幅を設定 */
        margin-bottom: 20px; /* 下部の余白を設定 */
    }

    .community img {
        width: 220px;
        height: 220px;
        border-radius: 50%;
    }

    .com_inf {
        text-align: center;
    }

</style>
<body>
    <u><p>コミュニティ一覧</p></u>
    <?php
        $pdo = new PDO($connect, USER, PASS);
        echo '<form action="community_create-input.php" method="post">';
        echo '<div class="right">';
            echo '<button type="submit" class="make">コミュニティ作成</button>';
        echo '</div>';
        echo '</form>';

        // 参加者数が多いコミュニティを1位から3位まで表示
        $stmt = $pdo->query('
            SELECT c.community_id, c.community_name, c.jpg, COUNT(cj.user_id) AS cnt
            FROM community c
            LEFT JOIN community_joinuser cj ON c.community_id = cj.community_id
            GROUP BY c.community_id, c.community_name, c.jpg
            ORDER BY cnt DESC
            LIMIT 3
        ');
        $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        
        echo '<h2>参加者数が多いコミュニティランキング</h2>';
        echo '<ol>';
        $i=1;
        foreach ($ranking as $rank => $community) {
            echo '<li>';
            if($i==1){
                echo '<img src="img/no1.png" height="100" width="120">';
            }else if($i==2){
                echo '<img src="img/no2.png" height="100" width="120">';
            }else{
                echo '<img src="img/no3.png" height="100" width="120">';
            }
            $image_folder = 'img/';
            $images = glob($image_folder . $community['jpg'] . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            // 画像が存在するか確認して表示
            if (!empty($images)) {
                echo '<img class="image" alt="image" src="' . $images[0] . '" style="width: 110px; height: 110px; border-radius: 50%;"> ';
            } else {
                echo '<img alt="no image" src="img/no_image.jpg" style="width: 30px; height: 30px; border-radius: 50%;"> ';
            }
            echo '<a href="community_top.php?id=' . $community['community_id'] . '">' . htmlspecialchars($community['community_name'], ENT_QUOTES, 'UTF-8') . '</a>';
            echo '  👤 ' . $community['cnt'];
            echo '</li>';
            $i++;
        }
        echo '</ol>';

        echo '<hr>'; // 区切り線

        echo '<div class="community-list">'; // コミュニティリストのコンテナを追加
        foreach ($pdo->query('SELECT * FROM community') as $row) {
            $id = $row['community_id'];
            $image_folder = 'img/';
            $images = glob($image_folder . $row['jpg'] . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            echo '<div class="community">';
                // 画像が存在するか確認して表示
                if (!empty($images)) {
                    echo '<a href="community_top.php?id=' . $id . '"><img class="image" alt="image" src="' . $images[0] . '"></a>';
                } else {
                    echo '<img alt="no image" src="img/no_image.jpg">';
                }

                $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM community_joinuser WHERE community_id = :community_id');
                $stmt->bindParam(':community_id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $count = $stmt->fetch();

                echo '<div class="com_inf">';
                    echo '<h3><a href="community_top.php?id=' . $id . '">' . htmlspecialchars($row['community_name'], ENT_QUOTES, 'UTF-8') . '</a></h3>';
                    echo '👤' . $count['cnt'];
                echo '</div>';
            echo '</div>';
        }
        echo '</div>'; // コミュニティリストのコンテナを閉じる
    ?>
</body>
</html>
