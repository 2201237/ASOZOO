<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['User'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['User']['user_id'];

// ユーザー情報の取得
try {
    $stmt = $pdo->prepare('SELECT mail_address, user_name, gender, introduction, icon FROM user WHERE user_id = :user_id');
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo 'ユーザー情報が見つかりません。';
        exit();
    }
} catch (PDOException $e) {
    echo 'エラーが発生しました: ' . $e->getMessage();
    exit();
}

// フォームからのデータの受け取りとデータベースの更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_name = $_FILES['icon']['name'];
    $file_tmp = $_FILES['icon']['tmp_name'];

    if (!empty($file_name)) {
        // 画像の保存先URLとBasic認証情報
        $webdav_url = 'https://zombie-aso2201177.webdav-lolipop.jp/kaihatu2/icon/' . $user_id . '.jpg';
        $username = 'zombie.jp-aso2201177';
        $password = 'Pass0109';

        // cURLセッションを初期化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $webdav_url);
        curl_setopt($ch, CURLOPT_PUT, 1);
        curl_setopt($ch, CURLOPT_INFILE, fopen($file_tmp, 'r'));
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_tmp));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            echo "ファイルをアップロードできませんでした。";
            exit();
        }
    }

    // アップロードされた場合とされなかった場合でアイコンのファイルパスを設定
    $fileNameWithoutExtension = (!empty($file_name)) ? $user_id . '.jpg' : $user['icon'];

    try {
        $stmt = $pdo->prepare('UPDATE user SET mail_address = :mail_address, user_name = :user_name, gender = :gender, introduction = :introduction, icon = :icon WHERE user_id = :user_id');
        $stmt->bindParam(':mail_address', $_POST['mail_address'], PDO::PARAM_STR);
        $stmt->bindParam(':user_name', $_POST['user_name'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $_POST['gender'], PDO::PARAM_STR);
        $stmt->bindParam(':introduction', $_POST['introduction'], PDO::PARAM_STR);
        $stmt->bindParam(':icon', $fileNameWithoutExtension, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $update_success = true;
    } catch (PDOException $e) {
        echo 'エラーが発生しました: ' . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール更新</title>
    <link rel="stylesheet" href="css/profile-update.css">
</head>
<body>
    <h1>プロフィール更新</h1>

    <?php if (isset($update_success) && $update_success): ?>
        <p>プロフィールの更新が完了しました。</p>
        <a href="profilehyouzi2.php">プロフィールに戻る</a>
    <?php else: ?>
        <form action="profile-update.php" method="post" enctype="multipart/form-data">
            <label for="icon">ユーザーアイコン:</label>
            <input type="file" id="icon" name="icon" accept="image/*">

            <label for="user_name">ユーザー名:</label>
            <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user['user_name'], ENT_QUOTES); ?>" required>

            <label for="mail_address">メールアドレス:</label>
            <input type="email" id="mail_address" name="mail_address" value="<?php echo htmlspecialchars($user['mail_address'], ENT_QUOTES); ?>" required>

            <label for="gender">性別:</label>
            <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($user['gender'], ENT_QUOTES); ?>" required>

            <label for="introduction">自己紹介:</label>
            <textarea id="introduction" name="introduction" required><?php echo htmlspecialchars($user['introduction'] ?? '', ENT_QUOTES); ?></textarea>

            <!-- <button type="submit">更新</button> -->
        </form>
    <?php endif; ?>
</body>
</html>
