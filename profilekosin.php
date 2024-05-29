<?php
require 'db-connect.php';

// データベース接続を確立する関数
function getDbConnection() {
    try {
        $dsn = 'mysql:host=' . SERVER . ';dbname=' . DBNAME . ';charset=utf8';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, USER, PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        echo 'データベース接続に失敗しました: ' . $e->getMessage();
        exit;
    }
}

// ユーザー情報を更新する関数
function updateUserProfile($pdo, $user_id, $mail_address, $pass, $user_name, $introduction, $gender) {
    $sql = 'UPDATE user SET mail_address = :mail_address, pass = :pass, user_name = :user_name, introduction = :introduction, gender = :gender WHERE user_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mail_address', $mail_address, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
    $stmt->bindParam(':introduction', $introduction, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

$pdo = getDbConnection();
$userId = 1; // ユーザーIDを適切に設定する（例：セッションから取得）

$mail_address = $_POST['mail_address'];
$pass = $_POST['pass'];
$user_name = $_POST['user_name'];
$introduction = $_POST['introduction'];
$gender = $_POST['gender'];

updateUserProfile($pdo, $userId, $mail_address, $pass, $user_name, $introduction, $gender);
echo 'プロフィールが更新されました。';
?>