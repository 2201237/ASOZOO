<?php ob_start(); ?>
<?php session_start(); ?>
<?php require 'db-connect.php'; ?>

<?php
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
        exit;
    }

    // アップロードしたファイルのURL
    $filePath = 'https://zombie-aso2201177.webdav-lolipop.jp/kaihatu2/img/' . $file_name;

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $sql->execute([$_SESSION['User']['user_id']]);

<<<<<<< HEAD
        $id=2;
        $cate = 1;
=======
        $tag = 1;
        $user_id = $_SESSION['User']['user_id'];
        $category_id = 1;
>>>>>>> 0c2994e1166f2f06920c76f1dbe7998d824f5065
        $day = date('Y-m-d H:i:s'); // 現在の日付と時間を格納

        if (!empty($sql->fetchAll())) {
            $sql = $pdo->prepare('INSERT INTO post (title, content, picture, link, post_day, user_id, category_id) VALUES (?,?,?,?,?,?,?,?)');
            $sql->execute([
                $_POST['title'],
                $_POST['content'],
                $filePath,
                null,
                $day,
<<<<<<< HEAD
                $id,
                $cate
=======
                $tag,
                $user_id,
                $category_id
>>>>>>> 0c2994e1166f2f06920c76f1dbe7998d824f5065
            ]);

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
}
?>
