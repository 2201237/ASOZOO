<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_path = $_FILES['user_file_name']['tmp_name']; // 一時ファイルのパス
    $file_name = $_FILES['user_file_name']['name']; // ファイル名

    $webdav_url = 'https://noor-aso2201219.webdav-lolipop.jp/Asozoo/app/' . $file_name; // アップロード先URL
    $username = 'noor.jp-aso2201219'; // WebDAVサーバーのユーザー名
    $password = 'Kawa0719'; // WebDAVサーバーのパスワード

    // cURLセッションを初期化
    $ch = curl_init();

    // アップロードするファイルを指定
    curl_setopt($ch, CURLOPT_URL, $webdav_url);
    curl_setopt($ch, CURLOPT_PUT, 1);
    curl_setopt($ch, CURLOPT_INFILE, fopen($file_path, 'r'));
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file_path));

    // ベーシック認証情報を設定
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

    // cURLセッションを実行
    $response = curl_exec($ch);

    // cURLセッションをクローズ
    curl_close($ch);

    // アップロードが成功したかどうかをチェック
    if ($response) {
        echo $file_name . "をアップロードしました。";
    } else {
        echo "ファイルをアップロードできませんでした。";
    }

    $filePath = 'https://noor-aso2201219.webdav-lolipop.jp/Asozoo/app/'.$file_name;
    
    

    
}
?>
<img src="../app/List.png" alt="画像">