<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
        $pdo = new PDO($connect, USER, PASS);
        if (isset($_SESSION['User']['user_id'])) {
            $user_id = $_SESSION['User']['user_id'];

            //コミュニティIDを取得
            //echo $_GET['id']; 
            //ユーザーが指定されたコミュニティに参加しているかどうかを確認
          //echo $user_id;
           $id = $_POST['id'];
           echo $id;
            $sql = "SELECT * FROM community_joinuser WHERE user_id = :user_id AND community_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $user_id);
            // echo $sql;
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                // チャットを送信する
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chat_submit'])) {
                    $record = $_POST['record'];
                    $chat_date = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO chat (community_id, user_id, record, chat_date)
                    VALUES (:id, :user_id, :record, :chat_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':id' => $id, ':user_id' => $user_id, ':record' => $record, ':chat_date' => $chat_date]);
                    // チャットの送信が成功した場合の処理
                    // チャットを再読み込みして表示するためのリダイレクト
                     header("Location: test_a.php?id=".$id);
                    exit();
                }
                echo '<h1>チャット</h1>';
                echo '<form action="community_chat.php" method="post">';
                echo '<input type="hidden" name="id" value="', $id, '">';
                echo '<textarea name="record" placeholder="メッセージを入力"></textarea>';
                echo '<input type="submit" name="chat_submit" value="投稿">';
                echo '</form>';

                // チャットメッセージを取得するクエリ
                $sql = "SELECT chat.record, chat.chat_date, user.user_name, user.icon
                        FROM chat
                        INNER JOIN user ON chat.user_id = user.user_id
                        WHERE chat.community_id = :id
                        ORDER BY chat.chat_date DESC"; // 全てのチャットメッセージを取得
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($stmt->rowCount() > 0) {
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="chat-message">';
                        if ($row['icon'] !== NULL) {
                            echo '<a href="user_profile.php?user_id=' . $user_id . '"><img src="' . $row['icon'] . 'jpg" alt="User Icon"></a>';
                        }else{
                            echo '<img src="default_icon.jpg" alt="Default Icon">';
                        }
                        echo '<span class="username">' . $row['user_name'] . '</span>';
                        echo '<span class="timestamp">' . $row['chat_date'] . '</span>';
                        echo '<p>' . $row['record'] . '</p>';
                        echo '</div>';
                    }
                }else {
                    echo "チャットメッセージはありません";
                }
            }else{
                echo "コミュニティに参加してください";
            }
        }else{
            echo "ログインしてください";
        }
        $pdo = null;
    ?>
</body>
</html>