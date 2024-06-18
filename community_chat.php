<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link type="text/css" rel="stylesheet" href="css/bmesse.css" />
    <title>Document</title>
</head>
    <style>
        <style>
        body {
            font-family: Arial, sans-serif;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }
        .chat-container {
            height: 500px;
            overflow-y: auto; /* スクロール可能にする */
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
            background-color: #ffcc4d;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            margin-right: 10px;
        }
        img.chat {
            width: 40px;
            height: 40px;
            object-fit: none;
            border-radius: 50%;
            object-position: 50% 50%;
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
    </style>
<body>
    
    <?php
        $pdo = new PDO($connect, USER, PASS);
        if (isset($_SESSION['User']['user_id'])) {
            $user_id = $_SESSION['User']['user_id'];

            $id = $_POST['id'];
            $sql = $pdo->prepare("select community_name from community where community_id=?");
            $sql->execute([$id]);
            echo '<div class="frame">';
            foreach($sql as $com){
                echo '<h1>', $com['community_name'], '</h1>';
            }
            echo '</div>';

            $sql = "SELECT * FROM community_joinuser WHERE user_id = :user_id AND community_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chat_submit'])) {
                    $record = $_POST['record'];
                    $chat_date = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO chat (community_id, user_id, record, chat_date)
                            VALUES (:id, :user_id, :record, :chat_date)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':id' => $id, ':user_id' => $user_id, ':record' => $record, ':chat_date' => $chat_date]);
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
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if ($user_id == $row['user_id']) {
                            echo '<div class="chat-message message-right">';
                            echo '<div class="message-bubble">' . $row['record'] . '</div>';
                            echo '</div>';
                            echo '<div class="my_timestamp">' . $row['chat_date'] . '</div><br>';
                        } else {
                            echo '<div class="chat-message message-left">';
                            if (!empty($row['icon'])) {
                                echo '<a class="message-avatar" href="user_profile.php?user_id=' . $user_id . '"><img class="chat" src="icon/' . $row['icon'] . 'jpg" alt="User Icon"></a>';
                            } else {
                                echo '<a class="message-avatar" href="profile.php2?user_id=' . $user_id . '"><img class="chat" src="icon/default_icon.jpg" alt="Default Icon"></a>';
                            }
                            echo '<div class="message-bubble">' . $row['record'] . '</div>';
                            echo '</div>';
                            echo '<div class="timestamp">' . $row['chat_date'] . '</div>';
                        }
                    }
                    echo '</div>';
                }
            }
        }
    ?>
    
    <div id="bms_send">
        <form action="community_chat.php" method="post">
            <input type="hidden" name="id" value="1">
            <textarea id="bms_send_message" name="record" placeholder="コメントを入力してください"></textarea>
            <div id="bms_send_btn">
                <?php
                if (isset($_SESSION['User']['user_id'])) {
                    $user_id = $_SESSION['User']['user_id'];
                    $sql = "SELECT * FROM community_joinuser WHERE user_id = :user_id AND community_id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        echo '<input type="submit" name="chat_submit" value="投稿">';
                    } else {
                        echo '</div>';
                        echo '<br><br><p>チャットに参加するにはこのコミュニティに参加する必要があります。</p>';
                    }
                } else {
                    echo '</div>';
                    echo '<br><br><p>チャットに参加するにはログインしてください。</p>';
                }
                ?>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const chatContainer = document.getElementById('chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
</body>
</html>