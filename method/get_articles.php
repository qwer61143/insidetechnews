<?php
/**
 * 獲取文章列表
 *
 * @param string $category 類別
 * @param int $records_per_page 每頁顯示的文章數量，預設12
 * @param int $db_update_duration 控制間隔多久才將瀏覽數寫入資料庫
 * @return array 返回陣列[頁數,文章,瀏覽量]
 */
function get_articles($category, $records_per_page = 12)
{
    require_once "connect.php";

    // 取得目前頁數
    $pages = 1;
    if (isset($_GET['page'])) {
        $pages = $_GET['page'];
    }

    $cache_file = "../cache/article_data_{$category}.json";
    $views_cache_file = "../cache/views_data.json";

    $all_articles = json_decode(file_get_contents($cache_file), true);
    $views_data = json_decode(file_get_contents($views_cache_file), true);

    // 計算總頁數
    if (is_array($all_articles)) {
        $total_pages = ceil(count($all_articles) / $records_per_page);
    } else {
        $total_pages = 0;
    }

    // 返回包含分页信息和文章列表的数组
    return [
        'pages' => $pages,
        'all_articles' => $all_articles,
        'views_data' => $views_data,
        'total_pages' => $total_pages,
    ];
}