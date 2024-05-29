<?php
ob_start();
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_path = $_FILES['user_file_name']['tmp_name']; // 一時ファイルのパス
    $file_name = $_FILES['user_file_name']['name']; // ファイル名

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

    // cURLセッションをクローズ
    curl_close($ch);

    // アップロードが成功したかどうかをチェック
    if ($response) {
        echo $file_name . "をアップロードしました。";
    } else {
        echo "ファイルをアップロードできませんでした。";
    }

    // ファイル名から拡張子を除いた部分を取得
    $fileNameWithoutExtension = pathinfo($file_name, PATHINFO_FILENAME);

    $filePath = 'https://zombie-aso2201177.webdav-lolipop.jp/Asozoo/img/' . $file_name;

    $id = $_SESSION['User']['user_id'];

    try {
        $pdo = new PDO($connect, USER, PASS);
        $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $sql->execute([$id]); // 配列としてIDを渡す

        $day = date('Y-m-d H:i:s'); // 現在の日付と時間を格納

        if (!empty($sql->fetchAll())) {
            $sql = $pdo->prepare('INSERT INTO community (community_name, exipo, jpg) VALUES (?, ?, ?)');

            $sql->execute([
                $_POST['name'],
                $_POST['exipo'],
                $fileNameWithoutExtension // 拡張子を除いたファイル名を保存
            ]);

            
            $community_id=$pdo->query('select max(id) from community')->fetchColumn();
            echo $community_id;

            //community_joinuserに追加
            $sql = $pdo->prepare('INSERT INTO community_joinuser (community_id, user_id) VALUES (?, ?)');
            $sql->execute([
                $community_id,
                $id
            ]);

            echo '<center>';
            echo '作成しました';
            echo '<meta http-equiv="refresh" content="10;url=test1.php">';
            echo '</center>';
        } else {
            echo '<center>';
            echo '入力エラーがあります';
            echo '<meta http-equiv="refresh" content="10;url=touroku-output.php">';
            echo '10秒後に<a href="test2.php">新規登録画面</a>へ戻ります';
            echo '</center>';
        }
    } catch (PDOException $e) {
        echo 'データベースエラー: ' . $e->getMessage();
    }
}
?>
<img src="../app/List.png" alt="画像">
