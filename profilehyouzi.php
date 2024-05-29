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

// ユーザー情報を取得する関数
function getUserInfo($pdo, $userId) {
    $sql = 'SELECT mail_address, pass, gender, user_name FROM user WHERE user_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
}

$pdo = getDbConnection();
$userId = 1; // ユーザーIDを適切に設定する（例：セッションから取得）
$userInfo = getUserInfo($pdo, $userId);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/profile.css">
  <title>プロフィール画面</title>
</head>

<body>
<?php include('header.php'); ?>

  <main class="profile-container">
    <div class="header-inner">
      <h1>プロフィール</h1>
    </div>
    <section class="profile-header">
      <img src="path/to/icon.png" alt="アイコン" class="profile-icon">
      <h2 class="profile-name"><?php echo htmlspecialchars($userInfo['user_name']); ?></h2>
    </section>

    <section class="profile-details">
        <h3>メールアドレス</h3>
        <p><?php echo htmlspecialchars($userInfo['mail_address']); ?></p>

        <h3>パスワード</h3>
        <p><?php echo htmlspecialchars($userInfo['pass']); ?></p>

        <h3>性別</h3>
        <p><?php echo htmlspecialchars($userInfo['gender']); ?></p>
    </section>
  </main>
</body>

</html>