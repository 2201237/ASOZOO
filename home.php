<?php  session_start();
    require 'header.php';
?>



<?php
// session_start(); // セッションを開始する

// セッションが存在するか確認する
if (isset($_SESSION['User']['user_id'])) {
    // $_SESSION['User']が存在する場合の処理
    $user_id = $_SESSION['User']['user_id']; // ユーザーIDを取得する
    echo $user_id;
    // その他の処理...
    echo 'success';
} else {
    echo 'error';
    // $_SESSION['User']が存在しない場合の処理
    // エラー処理やリダイレクトなど...
}
?>





</body>
</html>
