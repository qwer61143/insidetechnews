<?php

    require_once "connect.php";

    $categorys = ['AI','hardware','software'];

    $views_cache_file = __DIR__."/../cache/views_data.json";
   
    // 更新瀏覽數緩存資料
    if(file_exists($views_cache_file)) {
        
        $views_data = json_decode(file_get_contents($views_cache_file), true);

        // 遍歷取出鍵值以更新資料庫
        foreach ($views_data as $article_id => $view_count) {
            $update = $conn->prepare("UPDATE `article` SET `views` = :view_count WHERE `id` = :article_id");
            $update->bindParam(":view_count", $view_count, PDO::PARAM_INT);
            $update->bindParam(":article_id", $article_id, PDO::PARAM_INT);
            $update->execute();
        }

        //取得所有文章的瀏覽數
        $query = $conn->prepare("SELECT id,views FROM article");
        $query->execute();
        $all_articles_views = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // 更新 views_data
        $views_data = [];
        if (is_array($all_articles_views)) {
            foreach ($all_articles_views as $article) {
                if (isset($article['id']) && array_key_exists('views', $article)) {
                    $views_data[$article['id']] = $article['views'];
                }
            }
        }
    }else {
        //取得所有文章的瀏覽數
        $query = $conn->prepare("SELECT id,views FROM article");
        $query->execute();
        $all_articles_views = $query->fetchAll(PDO::FETCH_ASSOC);

        // 獲取瀏覽量並且寫進緩存檔
        $views_data = [];
        if (is_array($all_articles_views)) {
            foreach ($all_articles_views as $article) {
                if (isset($article['id']) && array_key_exists('views', $article)) {
                    $views_data[$article['id']] = $article['views'];
                }
            }
        }
        file_put_contents($views_cache_file, json_encode($views_data));
    }

    // 更新
    foreach($categorys as $category) {
        
        $cache_file = __DIR__ ."/../cache/article_data_{$category}.json";

        if(file_exists($cache_file)) {
            // 從數據庫重新獲取文章數據
            $query = $conn->prepare("SELECT * FROM article WHERE category = :category");
            $query->bindParam(':category', $category, PDO::PARAM_STR);
            $query->execute();
            $all_articles = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // 將更新後的數據寫回緩存檔案
            file_put_contents($cache_file, json_encode($all_articles));
        }else {
            // 將數據庫中的文章放進緩存文件中
            $query = $conn->prepare("SELECT * FROM article WHERE category = :category");
            $query->bindParam(':category', $category, PDO::PARAM_STR);
            $query->execute();
            $all_articles = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        // 將獲取的資料寫入緩存
        file_put_contents($cache_file, json_encode($all_articles));
    }
?>