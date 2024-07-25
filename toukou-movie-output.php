<?php
ob_start();
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_path = $_FILES['user_video_file']['tmp_name']; // 一時ファイルのパス
    $file_name = $_FILES['user_video_file']['name']; // ファイル名

    // データベースに接続し、投稿を処理
    $user_id = $_SESSION['User']['user_id'];

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ユーザー情報のチェックと投稿処理
        $stmt = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $stmt->execute([$user_id]);

        if ($stmt->rowCount() > 0) {
            // ユーザーが存在する場合、投稿を処理
            $post_title = $_POST['title'];
            $post_content = $_POST['content'];
            $post_day = date('Y-m-d H:i:s');
            $category_id = 1; // 仮でカテゴリーIDを設定

            // 投稿をデータベースに挿入
            $sql = $pdo->prepare('INSERT INTO post (title, content, picture, link, post_day, user_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $sql->execute([$post_title, $post_content, '', null, $post_day, $user_id, $category_id]);

            // 最後に挿入された投稿のIDを取得
            $post_id = $pdo->lastInsertId();

            // 新しい動画名を post_id.mp4 として設定
            $new_video_file_name = $post_id . '.mp4';
            $webdav_url = 'https://zombie-aso2201177.webdav-lolipop.jp/kaihatu2/movies/' . $new_video_file_name;
            $video_username = 'zombie.jp-aso2201177'; // WebDAVサーバーのユーザー名
            $video_password = 'Pass0109'; // WebDAVサーバーのパスワード

            // cURLセッションを初期化
            $ch = curl_init();

            // アップロードするファイルを指定
            curl_setopt($ch, CURLOPT_URL, $webdav_url);
            curl_setopt($ch, CURLOPT_PUT, 1);
            curl_setopt($ch, CURLOPT_INFILE, fopen($file_path, 'r'));
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_path));

            // ベーシック認証情報を設定
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $video_username . ':' . $video_password);

            // cURLセッションを実行
            $response = curl_exec($ch);

            // cURLセッションをクローズ
            curl_close($ch);

            // アップロードが成功したかどうかをチェック
            if ($response) {
                echo $new_video_file_name . "をアップロードしました。";

                // postテーブルのlink列を更新
                $sql = $pdo->prepare('UPDATE post SET picture = ? WHERE post_id = ?');
                $sql->execute([$new_video_file_name, $post_id]);

            } else {
                echo "ファイルをアップロードできませんでした。";
            }

            // タグの処理（例）
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

            // nice_postテーブルにデータを挿入（例）
            $sql = $pdo->prepare('INSERT INTO nice_post (post_id, nice_point) VALUES (?, ?)');
            $sql->execute([$post_id, 0]); // 初期値として0を設定

            echo '<center>';
            echo '投稿しました<br>';
            echo '<meta http-equiv="refresh" content="10;url=home.php">';
            echo '10秒後に<a href="home.php">ホーム画面</a>へ戻ります';
            echo '</center>';
        } else {
            echo '<center>';
            echo '入力エラーがあります<br>';
            echo '<meta http-equiv="refresh" content="10;url=touroku-output.php">';
            echo '10秒後に<a href="tourokuAll.php">新規登録画面</a>へ戻ります';
            echo '</center>';
        }
    } catch (PDOException $e) {
        echo 'データベースエラー: ' . $e->getMessage();
    }
}
?>
