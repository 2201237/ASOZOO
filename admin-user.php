<?php
// 出力バッファリングを開始
ob_start();
// セッションの開始
session_start();

require 'header.php';
require 'db-connect.php';

// 管理者権限チェック
if (!isset($_SESSION['User']['user_id']) || $_SESSION['User']['user_id'] != 5) {
    echo 'アクセス権限がありません。';
    exit();
}

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit();
}

// アカウント停止処理
if (isset($_POST['ban_user_id'])) {
    $ban_user_id = intval($_POST['ban_user_id']);

    try {
        // user テーブルの icon を ban.png に変更
        $ban_user_query = $pdo->prepare('UPDATE user SET icon = "ban.png" WHERE user_id = ?');
        $ban_user_query->execute([$ban_user_id]);
        echo 'ユーザーが停止されました。';
    } catch (PDOException $e) {
        echo 'アカウント停止に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}

// アカウント復帰処理
if (isset($_POST['restore_user_id'])) {
    $restore_user_id = intval($_POST['restore_user_id']);

    try {
        // user テーブルの icon を user_id.jpg に変更
        $restore_user_query = $pdo->prepare('UPDATE user SET icon = CONCAT(user_id, ".jpg") WHERE user_id = ?');
        $restore_user_query->execute([$restore_user_id]);
        echo 'ユーザーが復帰されました。';
    } catch (PDOException $e) {
        echo 'アカウント復帰に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}

// ユーザーデータを取得するクエリ
$query = $pdo->query('SELECT * FROM user ORDER BY user_id ASC');
$data = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>管理者ページ</title>
</head>
<body>

<h1>管理者ページ</h1>

<table border="1">
    <tr>
        <th>ユーザーID</th>
        <th>ユーザー名</th>
        <th>メールアドレス</th>
        <th>性別</th>
        <th>紹介文</th>
        <th>アイコン</th>
        <th>操作</th>
    </tr>
    <?php foreach ($data as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user['mail_address'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user['gender'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user['introduction'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($user['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <?php if ($user['icon'] === 'ban.png'): ?>
                    <form method="post" action="">
                        <input type="hidden" name="restore_user_id" value="<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit">アカウント復帰</button>
                    </form>
                <?php else: ?>
                    <form method="post" action="">
                        <input type="hidden" name="ban_user_id" value="<?php echo htmlspecialchars($user['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit">アカウント停止</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

<?php
// 出力バッファリングを終了してバッファの内容を出力
ob_end_flush();
?>