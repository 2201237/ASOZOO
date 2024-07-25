<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php require 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/bmesse.css" />
    <!-- <link rel="stylesheet" href="css/header.css"> -->
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }
        .chat-container {
            /* width: 300px; */
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

        img.chat{
            width: 40px;
            height: 40px;
            object-fit: none;
            border-radius:50%;
            object-position:50% 50%;
        }

        .my_timestamp{
            text-align: right;
            color: #808080;
        }

        .timestamp{
            text-align: left;
            color: #808080;
        }
    </style>

</head>
<body>
    
    <br>
    <div id="your_container">

        <!-- チャットの外側部分① -->
        <div id="bms_messages_container">
            <!-- ヘッダー部分② -->
            

            <!-- タイムライン部分③ -->
            <div id="bms_messages">
            <?php
            // $user_id = $_SESSION['User']['user_id'];

                $user_id = $_SESSION['User']['user_id'];

                //コミュニティIDを取得
                //echo $_GET['id']; 
                //ユーザーが指定されたコミュニティに参加しているかどうかを確認
                //echo $user_id;
                $id = 1;
                $pdo = new PDO($connect, USER, PASS);
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
                        //  header("Location: test_chat.php?id=".$id);
                        
                    }
                    // チャットメッセージを取得するクエリ
                    $sql = "SELECT chat.record, chat.chat_date,user.user_id, user.user_name, user.icon
                        FROM chat
                        INNER JOIN user ON chat.user_id = user.user_id
                        WHERE chat.community_id = :id
                        ORDER BY chat.chat_date ASC"; // 全てのチャットメッセージを取得
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':id' => $id]);
                    if ($stmt->rowCount() > 0) {
                        echo '<div class="chat-container">';
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            if($user_id == $row['user_id']){
                                echo '<div class="chat-message message-right">';
                                    // if (!empty($row['icon'])) {
                                    //     echo '<a class = "message-avatar" href="user_profile.php?user_id=' . $user_id . '"><img class="chat" src="img/' . $row['icon'] . 'jpg" alt="User Icon" height="80" width="80"></a>';
                                    // }else{
                                    //     echo '<a class = "message-avatar" href="profile.php2?user_id=' . $user_id . '"><img class="chat" src="img/default_icon.jpg" alt="Default Icon" height="80" width="80"></a>';
                                    // }
                                echo '<div class="message-bubble">'.$row['record'].'</div>';
                                echo '</div>';
                                echo '<div class="my_timestamp">' . $row['chat_date'] . '</div><br>';
                            } else {
                            echo '<div class="chat-message message-left">';

                                if (!empty($row['icon'])) {
                                    echo '<a class = "message-avatar" href="user_profile.php?user_id=' . $user_id . '"><img class="chat" src="icon/' . $row['icon'] . 'jpg" alt="User Icon" height="80" width="80"></a>';
                                }else{
                                    echo '<a class = "message-avatar" href="profile.php2?user_id=' . $user_id . '"><img class="chat" src="icon/default_icon.jpg" alt="Default Icon" height="80" width="80"></a>';
                                }
                            // echo '<div class="message-avatar">'. $row['record'] .'</div>';
                            echo '<div class="message-bubble">'. $row['record'] .'</div>';
                            echo '</div>';
                            echo '<div class="timestamp">' . $row['chat_date'] . '</div>';
                            }
                        }
                        echo '</div>';
                    }
                }
            ?>
                

            <!-- テキストボックス、送信ボタン④ -->
            <div id="bms_send">
                <form action="test_chat.php" method="post">
                    <input type="hidden" name="id" value="1">
                    <textarea id="bms_send_message" name="record" placeholder="コメントを入力してください"></textarea>
                    <div id="bms_send_btn">
                        <input type="submit" name="chat_submit" value="投稿">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>