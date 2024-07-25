<?php
// 出力バッファリングを開始
ob_start();
// セッションの開始
session_start();

require 'header.php';
require 'db-connect.php';

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit();
}

// デフォルトの並び順を設定
$order_by = 'post_day DESC'; // デフォルトは新しい順

// 初期化
$where_clause = '';

// GETパラメーターで並び替え条件を受け取る
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    switch ($sort) {
        case 'nice':
            $order_by = 'np.nice_point DESC'; // いいね順
            break;
        case 'new':
            $order_by = 'post_day DESC'; // 新しい順
            break;
        case 'old':
            $order_by = 'post_day ASC'; // 古い順
            break;
        case '3days':
            $order_by = 'np.nice_point DESC'; // 過去3日間のいいね順
            $where_clause = 'AND p.post_day >= DATE_SUB(NOW(), INTERVAL 3 DAY)';
            break;
        default:
            $order_by = 'post_day DESC'; // デフォルトは新しい順
            break;
    }
}

// メディアタイプで絞り込み
$media_type = '';
if (isset($_GET['media_type'])) {
    $media_type = $_GET['media_type'];
    switch ($media_type) {
        case 'image':
            $where_clause .= ' AND p.category_id = 0';
            break;
        case 'video':
            $where_clause .= ' AND p.category_id = 1';
            break;
        case 'youtube':
            $where_clause .= ' AND p.category_id = 2';
            break;
    }
}

// 検索クエリの処理
$search_word = '';
if (isset($_POST['word'])) {
    $search_word = htmlspecialchars($_POST['word'], ENT_QUOTES, 'UTF-8');
    $where_clause .= " AND (p.title LIKE '%$search_word%' OR p.content LIKE '%$search_word%')";
}

$data = [];

// 投稿データを取得するクエリ
$query = $pdo->prepare("SELECT p.*, np.nice_point 
                      FROM post p 
                      LEFT JOIN nice_post np ON p.post_id = np.post_id 
                      WHERE (p.category_id = 0 OR p.category_id = 1 OR p.category_id = 2) 
                      $where_clause
                      ORDER BY $order_by");
$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// タグが関連付けられた投稿のみを表示する
if (isset($_GET['tag'])) {
    $tag = $_GET['tag'];
    $query = $pdo->prepare("SELECT p.*, np.nice_point
                            FROM post p
                            LEFT JOIN tag_map tm ON p.post_id = tm.post_id
                            LEFT JOIN nice_post np ON p.post_id = np.post_id
                            WHERE tm.tag_id IN (SELECT tag_id FROM tag WHERE tag_name = :tag)
                            ORDER BY $order_by");
    $query->execute([':tag' => $tag]);
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/toukouAll.css">
    <style>
        /* 画像のサムネイルのサイズを調整するためのスタイル */
        .media-gallery img {
            width: 200px; /* 画像の幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin: 5px; /* 画像の間隔を指定 */
        }
        /* 動画のサムネイルのサイズを調整するためのスタイル */
        .media-gallery video {
            width: 200px; /* 動画の幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin: 5px; /* 動画の間隔を指定 */
        }
        /* YouTube iframeのサイズを調整するためのスタイル */
        .media-gallery iframe {
            width: 200px; /* iframeの幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin: 5px; /* iframeの間隔を指定 */
        }
        /* ドロップダウンメニューのスタイル */
        .sort-dropdown, .media-dropdown {
            margin-bottom: 10px;
        }
    </style>
    <title>メディアギャラリー</title>
</head>
<body>

<div class="media-gallery">
    <div class="sorting-options">
        <!-- 並び替えドロップダウンメニュー -->
        <form action="" method="get">
            <select name="sort" class="sort-dropdown" onchange="this.form.submit()">
                <option value="new" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'new') echo 'selected'; ?>>新しい順</option>
                <option value="old" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'old') echo 'selected'; ?>>古い順</option>
                <option value="nice" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'nice') echo 'selected'; ?>>いいね順</option>
                <option value="3days" <?php if(isset($_GET['sort']) && $_GET['sort'] == '3days') echo 'selected'; ?>>過去3日間のいいね順</option>
            </select>
        </form>
        <!-- メディアタイプドロップダウンメニュー -->
        <form action="" method="get">
            <select name="media_type" class="media-dropdown" onchange="this.form.submit()">
                <option value="">すべてのメディア</option>
                <option value="image" <?php if(isset($_GET['media_type']) && $_GET['media_type'] == 'image') echo 'selected'; ?>>画像</option>
                <option value="video" <?php if(isset($_GET['media_type']) && $_GET['media_type'] == 'video') echo 'selected'; ?>>動画</option>
                <option value="youtube" <?php if(isset($_GET['media_type']) && $_GET['media_type'] == 'youtube') echo 'selected'; ?>>YouTube</option>
            </select>
        </form>
    </div>
    <?php
    // メディアフォルダのパス
    $image_folder = 'img/';
    $video_folder = 'movies/';
    
    // 投稿データを表示
    foreach ($data as $post) {
        echo '<div class="post">';
    
        // タイトルをエスケープ処理する前に、null チェックを行う
        $title = isset($post['title']) ? htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') : '';
    
        if (!empty($post['picture']) || !empty($post['link'])) {
            if ($post['category_id'] == 0) {
                // 画像ファイルのパスを生成
                $image_path = $image_folder . htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8');
                $id = $post['post_id'];
                echo '<a href="toukou-detail.php?id=' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '">';
                echo '<p> ' . $title . '</p>';  // エスケープ済みのタイトルを表示
                echo '<img src="' . $image_path . '" alt="画像">';
                echo '</a>';
            } elseif ($post['category_id'] == 1) {
                // 動画ファイルのパスを生成
                $video_path = $video_folder . htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8');
                $id = $post['post_id'];
                echo '<a href="toukou-detail.php?id=' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '">';
                echo '<p> ' . $title . '</p>';  // エスケープ済みのタイトルを表示
                echo '<video controls>';
                echo '<source src="' . $video_path . '" type="video/mp4">';
                echo '動画を再生できません';
                echo '</video>';
                echo '</a>';
            } elseif ($post['category_id'] == 2) {
                $id = $post['post_id'];
                echo '<a href="toukou-detail.php?id=' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '">';
                echo '<p> ' . $title . '</p>';  // エスケープ済みのタイトルを表示
                // YouTubeのリンクから埋め込みコードを生成
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $post['link'], $matches);
                $youtubeVideoId = isset($matches[1]) ? $matches[1] : '';
                $embedCode = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $youtubeVideoId . '" frameborder="0" allowfullscreen></iframe>';
                echo $embedCode;
                echo '</a>';
            }
        } else {
            echo '<p>メディアがありません</p>';
        }
        echo '</div>';
    }
    ?>
</div>

</body>
</html>
<?php
// 出力バッファリングを終了してバッファの内容を出力
ob_end_flush();
?>
