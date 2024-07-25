<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>コミュニティ退会</title>
    <style>
        .body{
            text-align:center;
        }
    </style>
</head>
<body>
<?php
if (isset($_SESSION['User']['user_id']) && isset($_POST['community_id'])) {
    $user_id = $_SESSION['User']['user_id'];
    $community_id = $_POST['community_id'];

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // コミュニティからユーザーを削除するクエリ
        $sql_delete = $pdo->prepare('DELETE FROM community_joinuser WHERE user_id = ? AND community_id = ?');
        $sql_delete->execute([$user_id, $community_id]);

        // 削除が成功した場合のメッセージ
        echo "退会できました";

        // コミュニティの参加者数を取得するクエリ
        $sql_count_users = $pdo->prepare('SELECT COUNT(*) FROM community_joinuser WHERE community_id = ?');
        $sql_count_users->execute([$community_id]);
        $num_users = $sql_count_users->fetchColumn();

        if ($num_users == 0) {
            // 参加者が0人の場合、コミュニティを削除するクエリ
            $sql_delete_community = $pdo->prepare('DELETE FROM community WHERE community_id = ?');
            $sql_delete_community->execute([$community_id]);
            echo "<br>参加者が0人のため、コミュニティを削除しました。";
        }

    } catch (PDOException $e) {
        // エラーが発生した場合の処理
        echo "エラー：退会処理中に問題が発生しました。";
        error_log('PDOException: ' . $e->getMessage());
    }

} else {
    // ユーザーがログインしていないか、コミュニティIDが送信されていない場合のエラーメッセージ
    echo "エラー：退会処理に失敗しました。";
}

// コミュニティ一覧に戻るボタン
echo '<br><br><a href="communitys.php">コミュニティ一覧に戻る</a>';
?>
</body>
</html>