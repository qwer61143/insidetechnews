<?php
    header("Content-Type: application/json");

    require_once "../method/connect.php";

    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }else {
        $page = 1;
    }

    if(isset($_GET['category'])) {
        $category = $_GET['category'];
    }else {
        $category = 'AI';
    }

    //資料筆數
    $records_per_page = 12;
    //該頁的起始文章
    $offset = ($page - 1) * $records_per_page;
    //取得該類別下的文章
    $query = $conn->prepare("SELECT * FROM `article` WHERE `category` = :category ORDER BY `published_at` DESC LIMIT :offset, :limit");
    $query->bindValue(':category', $category, PDO::PARAM_STR);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $query->execute();

    $articles = $query->fetchAll(PDO::FETCH_ASSOC);
    //將取的資料以json格式返回infinite_scroll.js
    echo json_encode($articles);
?>