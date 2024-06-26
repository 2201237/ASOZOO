<?php
session_start();
ob_start(); // バッファリングの開始
require "header.php";
require "db-connect.php";

?>

<body>
    <?php
    unset($_SESSION['User']);

    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('select * from user where mail_address=?');
    $sql->execute([$_POST['email']]);
    foreach ($sql as $row) {

        

        if (password_verify($_POST['password'], $row['pass']) == true) {
            $_SESSION['User'] = [
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'user_pass' => $row['pass'],
                'user_address' => $row['mail_address'],
                'user_gender' => $row['gender'],

            ];

            if (isset($_SESSION['error']['login'])) {
                unset($_SESSION['error']['login']);
            }
        } else {
            $_SESSION['check'] = ['count' => 0];
        }
    }
    if (isset($_SESSION['User'])) {
        $redirect_url = 'https://aso2201177.zombie.jp/kaihatu2/login_success.php';
        header('Location: ' . $redirect_url);
        exit();
    } else {
        echo '<center>';
        echo '<br>';
        echo '<h2>メールアドレス又はパスワードが誤っています。</h2>';
        echo '<a href="login.php" class="login-output-button"><span class="login">ログイン画面へ</span></a>';
    
        echo '</center>';
        echo '<meta http-equiv="refresh" content="5;url=login.php">';
        $_SESSION['error'] = [
            'login' => '<p  class="login-miss">メールアドレス又はパスワードが誤っています</p>'
        ];
    }

    ob_end_flush(); // バッファの内容を送信
    ?>
</body>
</html>