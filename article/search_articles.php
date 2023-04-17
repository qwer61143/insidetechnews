<?php

    require_once "../method/connect.php";
    // 設定排序方式
    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : "date";

    if(isset($_GET['page']) && $_GET['page'] != "") {
        $pages = $_GET['page'];
    }else {
        $pages = 1;
    }

    // 設定一頁有幾筆資料，緩存持續時間，當前頁面顯示的資料從第幾筆開始
    $records_per_page = 12;
    $cache_duration = 30;
    $offset = ($pages - 1) * $records_per_page;

    // 設置緩存文件
    $cache_file = "../cache/article_data.json";
    $views_cache_file = "../cache/views_data.json";
    $views_data = json_decode(file_get_contents($views_cache_file), true);
    // 檢查緩存文件是否存在並有無過期
    if(!file_exists($cache_file)||time() - filemtime($cache_file) > $cache_duration) {
        // 將數據庫中的文章放進緩存文件中
        $query = $conn->prepare("SELECT * FROM article ");
        $query->execute();
        $all_articles = $query->fetchAll(PDO::FETCH_ASSOC);

        // 將獲取的資料寫入緩存
        file_put_contents($cache_file, json_encode($all_articles));
    }else {
        $all_articles = json_decode(file_get_contents($cache_file), true);
    }
    
    if(isset($_GET['keyword'])) {
        // 如果輸入了關鍵字，則過濾出包含關鍵字的文章
        $keyword = ($_GET['keyword'] != "") ? $_GET['keyword'] : "";
        $articles = array_filter($all_articles, function($article) use ($keyword) {
            // 檢查標題和內容是否包含關鍵字
            return strpos($article['title'], $keyword) !== false || strpos($article['content'], $keyword) !== false;
        });
    } else {
        // 如果沒有輸入關鍵字，則顯示所有文章
        $articles = $all_articles;
    }

       // 計算總頁數
       if (is_array($articles)) {
        $total_pages = ceil(count($articles) / $records_per_page);
    } else {
        $total_pages = 1;
    }
    
    // 並按照瀏覽量進行排序
    if ($sort_by === "views") {
        usort($articles, function($a, $b) {
            return $b['views'] - $a['views'];
        });
    } else {
        usort($articles, function($a, $b) {
            return strtotime($b['published_at']) - strtotime($a['published_at']);
        });
    }

    $filtered_articles = array_slice($articles, $offset, $records_per_page);

    // 格式化日期
    function formatDate($dateString) {
        $date = new DateTime($dateString);
        $year = $date->format('Y');
        $month = str_pad($date->format('m'), 2, '0', STR_PAD_LEFT);
        $day = str_pad($date->format('d'), 2, '0', STR_PAD_LEFT);
        return "$year-$month-$day";
    }

    require_once "../method/bootstrap5.html"
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="InsideTechNews - 提供最新的人工智慧、PC硬體、軟體資訊、科技新聞和幣圈動態等資訊的網站。">
    <title>
        <?php if(isset($keyword))
        { 
            echo $keyword;
        }else {
            echo "文章列表";
        }?>
    </title>
    <link rel="stylesheet" href="/method/css.css" type="text/css">
  
</head>
<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light align-items-center position-relative border-bottom border-2 border-secondary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php">InsideTechNews</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/article/AI.php">人工智慧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/article/Hardware.php">PC硬體</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/article/Software.php">軟體資訊</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <form class="d-flex search-form" method="GET" action="search_articles.php">
                            <input class="form-control me-2" type="search" placeholder="搜尋文章" aria-label="Search" name="keyword">
                            <button class="btn" type="submit"><img src="../img/icons8-search-30.png" alt="search"></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- title -->
    <div class="container">
        <div class="row">
            <div class="col-12 text-center my-3 mt-5">
                <h1 class="fw-bold">
                您的搜尋:
                    <?php if(isset($keyword) && $keyword != "") {
                        echo $keyword;
                    }else {
                        echo "所有文章";
                    }
                    ?>
                </h1>
            </div>
            <div class="row">
                <form class="d-flex search-form mt-2 mb-2 col-8 col-md-10" method="GET" action="search_articles.php">
                    <input class="form-control me-2" type="search" placeholder="<?php if(isset($_GET['keyword'])) { echo $_GET['keyword'];}?>" aria-label="Search" name="keyword">
                    <button class="btn" type="submit"><img src="../img/icons8-search-30.png" alt="search"></button>
                </form>
                <div class="dropdown d-flex ml-auto col-4 col-md-2">
                    <button class="btn btn-drak dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] != "") { 
                            if($_GET['sort_by'] == "date") {
                                echo "最新";
                            }else {
                                echo "最熱門";
                            }
                        }else {
                            echo "排序方式";
                        }
                        ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a href="?sort_by=date&keyword=<?php echo $keyword ?>" class="custom-a">最新</a></li>
                        <li><a href="?sort_by=views&keyword=<?php echo $keyword ?>">最熱門</a></li>
                        <!-- 保留未來開發相關性排序 -->
                        <!-- <li><a class="dropdown-item" href="#"></a></li> -->
                    </ul>
                </div>
            </div>
            <div class="search-divider-horizontal mx-auto mt-3 mb-3"></div>
        </div>
    </div>

    <!-- all_article -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 mt-3">
                <?php foreach($filtered_articles as $article) : ?>
                    <div class="card mb-3 w-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <a href="article.php?id=<?php echo $article['id'] ?>&category=<?php echo $article['category'] ?>">
                                <img src="<?php echo $article['pic'] ?>" class="img-fluid card-img" alt="<?php echo $article['pic_alt'] ?>" title="<?php echo $article['pic_title'] ?>">
                                </a>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                <div class="title-container">
                                    <h5 class="card-title"><?php echo $article['title'] ?></h5>
                                </div>
                                <div class="text-container">
                                    <p class="card-text"><?php echo mb_substr($article['content'], 0, 60, 'UTF-8') ?>...</p>
                                </div>
                                <p class="card-text">
                                    <small class="text-muted"><?php echo formatDate($article['published_at']) ?></small>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">瀏覽量:<?php echo $views_data[$article['id']] ?></small>
                                </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="search-divider-horizontal mx-auto mb-3"></div>
                <?php endforeach ?>
            </div>
            <!-- ad here -->
            <div class="col-12 col-md-4">
                <div class="your-ad-class d-flex justify-content-center align-items-center">
                    <!-- Your ad content goes here -->
                </div>
                <div class="your-ad-class d-flex justify-content-center align-items-center">
                    <!-- Your ad content goes here -->
                </div>
                <div class="your-ad-class d-flex justify-content-center align-items-center">
                    <!-- Your ad content goes here -->
                </div>
            </div>
        </div>
    </div> 

    <div class="page-transfer d-flex justify-content-center mt-5">
        <?php if($pages>1) {?>
            <a href="?page=<?php echo $pages-1 ?>&keyword=<?php echo $keyword ?>&sort_by=<?php echo $sort_by ?>" style="margin-right: 5px;">&#60;</a>
        <?php }else { ?>

        <?php } ?>

        <?php
            for ($i=1; $i <= $total_pages; $i++) { 
                 if((int)$pages === (int)$i) { ?>
                    <span style="margin-right: 5px;"><?php echo $i; ?></span>
                <?php }else { ?>
                    <a href="?page=<?php echo $i ?>&keyword=<?php echo $keyword ?>&sort_by=<?php echo $sort_by ?>" style="margin-right: 5px;"><?php echo $i ?></a>
                <?php } ?>
            <?php } ?>
        <?php if($pages<$total_pages) { ?>
            <a href="?page=<?php echo $pages + 1 ?>&keyword=<?php echo $keyword ?>&sort_by=<?php echo $sort_by ?>">&#62;</a>
        <?php }else{ ?>
            &#62;
        <?php } ?>
    </div>

    <div class="footer-divider-horizontal mx-auto mt-3 mb-3"></div>
    <!-- 頁尾 -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center">
                    <img src="/img/logo.png" alt="InsideTechNews" title="InsideTechNews" class="custom-logo-image">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="slogan-container">
                    <p>精選資訊，一目了然，時刻更新，永不落後，瞭解未來，簡單不繁!</p>
                    <p>Email: qwer506@gmail.com</p>
                </div>
            </div>
        </div>
    </div>
   
    <footer>
        <div class="custom-footer p-3">
            <div class="text-center mb-2">
                InsideTechNews 版權所有不得轉載 &copy; 2023 InsideTechNews. All rights reserved.
            </div>
            <div class="text-center mt-2">
                <a class="text-dark" href="#">Privacy Policy</a> | <a class="text-dark" href="#">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="/method/adsense.js"></script>
    <script src="/method/ad-content.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</body>
</html>