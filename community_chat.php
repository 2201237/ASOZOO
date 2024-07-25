<?php 
session_start(); 
require 'db-connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link type="text/css" rel="stylesheet" href="css/bmesse.css" />
    <title>Community Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            justify-content: center;
            align-items: center;
        }
        .chat-container {
            height: 500px;
            overflow-y: auto;
            background-color: #e5e5e5;
            border-radius: 10px;
            padding: 10px;
        }
        .chat-header {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .chat-message {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .message-left {
            justify-content: flex-start;
        }
        .message-right {
            justify-content: flex-end;
        }
        .message-bubble {
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            position: relative;
        }
        .message-left .message-bubble {
            background-color: #ffffff;
            color: #333;
            margin-left: 10px;
        }
        .message-right .message-bubble {
            background-color: #84c1ff;
            color: #ffffff;
            margin-right: 10px;
        }
        .message-left .message-bubble::before {
            content: "";
            position: absolute;
            top: 10px;
            left: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-right: 10px solid #ffffff;
            border-bottom: 10px solid transparent;
        }
        .message-right .message-bubble::before {
            content: "";
            position: absolute;
            top: 10px;
            right: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-left: 10px solid #84c1ff;
            border-bottom: 10px solid transparent;
        }
        .message-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            /* background-color: #ffcc4d; */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            margin-right: 10px;
        }
        img.chat {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .my_timestamp {
            text-align: right;
            color: #808080;
        }
        .timestamp {
            text-align: left;
            color: #808080;
        }
        .frame {
            text-align: center;
        }
        .center-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: black;
            font-size: 18px;
        }
        .back-link {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 14px;
            text-decoration: none;
            color: #007bff;
            background-color: #a0eefc;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .back-link:hover {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <?php
        $pdo = new PDO($connect, USER, PASS);

        $id = $_POST['id'];
        $sql = $pdo->prepare("SELECT community_name FROM community WHERE community_id = ?");
        $sql->execute([$id]);
        echo '<div class="frame">';
        foreach($sql as $com){
            echo '<h1>', $com['community_name'], '</h1>';
        }
        echo '</div>';

        $sql = "SELECT * FROM community_joinuser WHERE user_id = :user_id AND community_id = :id";
        if (isset($_SESSION['User']['user_id'])) {
            $user_id = $_SESSION['User']['user_id'];
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $is_user_in_community = $stmt->rowCount() > 0;
        } else {
            $is_user_in_community = false;
        }

        // チャット送信フォームを表示する条件を修正
        if ($is_user_in_community) {
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chat_submit'])) {
                $record = $_POST['record'];
                $chat_date = date("Y-m-d H:i:s");
                $sql = "INSERT INTO chat (community_id, user_id, record, chat_date)
                        VALUES (:id, :user_id, :record, :chat_date)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id, ':user_id' => $user_id, ':record' => $record, ':chat_date' => $chat_date]);
            }
        }

        $sql = "SELECT chat.record, chat.chat_date, user.user_id, user.user_name, user.icon
                FROM chat
                INNER JOIN user ON chat.user_id = user.user_id
                WHERE chat.community_id = :id
                ORDER BY chat.chat_date ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo '<div class="chat-container" id="chat-container">';
            echo '<a href="community_top.php?id=', htmlspecialchars($id), '" class="back-link">← 戻る</a>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (isset($_SESSION['User']['user_id']) && $_SESSION['User']['user_id'] == $row['user_id']) {
                    echo '<div class="chat-message message-right">';
                    echo '<div class="message-bubble">' . $row['record'] . '</div>';
                    echo '</div>';
                    echo '<div class="my_timestamp">' . $row['chat_date'] . '</div><br>';
                } else {
                    echo '<div class="chat-message message-left">';
                    if (!empty($row['icon'])) {
                        echo '<a class="message-avatar" href="user_profile.php?user_id=' . $row['user_id'] . '"><img class="chat" src="icon/' . $row['icon'] . '" alt="User Icon"></a>';
                    } else {
                        echo '<a class="message-avatar" href="profile.php2?user_id=' . $row['user_id'] . '"><img class="chat" src="icon/default_icon.jpg" alt="Default Icon"></a>';
                    }
                    echo '<div class="message-bubble">' . $row['record'] . '</div>';
                    echo '</div>';
                    echo '<div class="timestamp">' . $row['chat_date'] . '</div>';
                }
            }
            echo '</div>';
        }
    ?>

    <?php if (isset($_SESSION['User']['user_id']) && $is_user_in_community): ?>
    <div id="bms_send">
        <form action="community_chat.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <textarea id="bms_send_message" name="record" placeholder="コメントを入力してください"></textarea>
            <div id="bms_send_btn">
                <input type="submit" name="chat_submit" value="投稿">
            </div>
        </form>
    </div>
    <?php elseif (isset($_SESSION['User']['user_id'])): ?>
    <div class="center-message"><p>チャットを送信するにはコミュニティに参加してください。</p></div>
    <?php else: ?>
    <div class="center-message"><p>ログインしてください。</p></div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const chatContainer = document.getElementById('chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
</body>
</html>