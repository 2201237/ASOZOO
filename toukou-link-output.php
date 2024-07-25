<?php ob_start(); ?>
<?php session_start(); ?>
<?php require 'db-connect.php' ?>

<?php

// データベース接続情報の定義

$user_id = $_SESSION['User']['user_id'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]); // 修正点：$user_idを配列として渡す

    $cate = 2;
    $day = date('Y-m-d H:i:s'); // 現在の日付と時間を格納

    if (!empty($sql->fetchAll())) {
        // 投稿をデータベースに挿入
        $sql = $pdo->prepare('INSERT INTO post (title, content, picture, link, post_day, user_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $sql->execute([
            $_POST['title'],
            $_POST['content'],
            null, // 画像がない場合はnullを渡す
            $_POST['link'],
            $day,
            $user_id, // 正しいユーザーIDを渡す
            $cate
        ]);
        
        // 挿入された投稿のIDを取得
        $post_id = $pdo->lastInsertId();
        
        // nice_postテーブルにデータを挿入
        $sql = $pdo->prepare('INSERT INTO nice_post (post_id, nice_point) VALUES (?, 0)');
        $sql->execute([$post_id]);

        // タグの処理
        if (isset($_POST['tags']) && is_array($_POST['tags'])) {
            foreach ($_POST['tags'] as $tag_name) {
                // タグが既に存在するか確認
                $sql = $pdo->prepare('SELECT tag_id FROM tag WHERE tag_name = ?');
                $sql->execute([$tag_name]);

                if ($sql->rowCount() > 0) {
                    // タグが既に存在する場合、そのIDを取得
                    $tag_id = $sql->fetchColumn();
                    // タグのカウントをインクリメント
                    $sql = $pdo->prepare('UPDATE tag SET count = count + 1 WHERE tag_id = ?');
                    $sql->execute([$tag_id]);
                } else {
                    // タグが存在しない場合、新規挿入
                    $sql = $pdo->prepare('INSERT INTO tag (tag_name, count) VALUES (?, 1)');
                    $sql->execute([$tag_name]);
                    $tag_id = $pdo->lastInsertId();
                }

                // タグマップに挿入
                $sql = $pdo->prepare('INSERT INTO tag_map (tag_id, post_id) VALUES (?, ?)');
                $sql->execute([$tag_id, $post_id]);
            }
        }

        echo '<center>';
        echo '投稿しました';
        echo '<meta http-equiv="refresh" content="10;url=home.php">';
        echo '10秒後に<a href="home.php">ホーム画面</a>へ戻ります';
        echo '</center>';
    } else {
        echo '<center>';
        echo '入力エラーがあります';
        echo '<meta http-equiv="refresh" content="10;url=touroku-output.php">';
        echo '10秒後に<a href="tourokuAll.php">新規登録画面</a>へ戻ります';
        echo '</center>';
    }
} catch (PDOException $e) {
    echo 'データベースエラー: ' . $e->getMessage();
}

?>
<img src="../app/List.png" alt="画像">
