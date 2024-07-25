<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>コミュニティ作成</title>
</head>
<body>    
<?php
ob_start(); // 出力バッファリングを開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_path = $_FILES['user_file_name']['tmp_name']; // 一時ファイルのパス
    $original_file_name = $_FILES['user_file_name']['name']; // 元のファイル名

    $id = $_SESSION['User']['user_id'];

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーモードを例外に設定

        $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $sql->execute([$id]); // 配列としてIDを渡す

        if ($sql->fetch()) {
            // まずはコミュニティを作成し、コミュニティIDを取得
            $sql = $pdo->prepare('INSERT INTO community (community_name, exipo) VALUES (?, ?)');
            $sql->execute([
                $_POST['name'],
                $_POST['exipo']
            ]);

            // 最後に挿入されたIDを取得
            $community_id = $pdo->lastInsertId();

            // 画像名をコミュニティIDに基づいて設定
            $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);
            $file_name = $community_id . '.' . $file_extension;

            $webdav_url = 'https://zombie-aso2201177.webdav-lolipop.jp/kaihatu2/img/' . $file_name; // アップロード先URL
            $username = 'zombie.jp-aso2201177'; // WebDAVサーバーのユーザー名
            $password = 'Pass0109'; // WebDAVサーバーのパスワード

            // cURLセッションを初期化
            $ch = curl_init();

            // アップロードするファイルを指定
            curl_setopt($ch, CURLOPT_URL, $webdav_url);
            curl_setopt($ch, CURLOPT_PUT, 1);
            curl_setopt($ch, CURLOPT_INFILE, fopen($file_path, 'r'));
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_path));

            // ベーシック認証情報を設定
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

            // cURLセッションを実行
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // cURLセッションをクローズ
            curl_close($ch);

            // アップロードが成功したかどうかをチェック
            if ($response && ($http_code == 200 || $http_code == 201)) { // 200 OK または 201 Created を許可
                echo $file_name . "をアップロードしました。";

                $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
                $sql = $pdo->prepare('UPDATE community SET jpg = ? WHERE community_id = ?');
                $sql->execute([$file_name_without_extension, $community_id]); // 修正ポイント

                // community_joinuserに追加
                $sql = $pdo->prepare('INSERT INTO community_joinuser (community_id, user_id) VALUES (?, ?)');
                $sql->execute([
                    $community_id,
                    $id
                ]);

                echo '<center>';
                echo '作成しました';
                echo '<meta http-equiv="refresh" content="10;url=communitys.php">';
                echo '10秒後に<a href="communitys.php">コミュニティ一覧画面</a>へ戻ります';
                echo '</center>';
            } else {
                echo "ファイルをアップロードできませんでした。";
                exit; // エラーの場合、以降の処理を中断
            }
        } else {
            echo '<center>';
            echo '入力エラーがあります';
            echo '<meta http-equiv="refresh" content="10;url=community_create-input.php">';
            echo '10秒後に<a href="communitys.php">コミュニティ一覧画面</a>へ戻ります';
            echo '</center>';
        }
    } catch (PDOException $e) {
        echo 'データベースエラー: ' . $e->getMessage();
    }
}
ob_end_flush(); // 出力バッファリングを終了して出力する
?>
</body>
</html>