<?php 
    $id=$_SESSION['User']['user_id'];
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('select * from user where user_id=?');
    $sql->execute([$id]);
    foreach ($sql as $row) {
        if (password_verify($_POST['password'], $row['pass'])) {
            $_SESSION['User'] = [
                
                'user_icon' => $row['icon']
            ];

            if (isset($_SESSION['error']['login'])) {
                unset($_SESSION['error']['login']);
            }
        } else {
            $_SESSION['check'] = ['count' => 0];
        }
    }

?>