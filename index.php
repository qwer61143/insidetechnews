<?php
    require_once "method/connect.php";

    // 設置緩存文件
    $cache_file = "cache/article_data.json";
    $views_cache_file = "cache/views_data.json";
   
    $all_articles = json_decode(file_get_contents($cache_file), true);
    $views_data = json_decode(file_get_contents($views_cache_file), true);
 
    // 格式化日期
    function formatDate($dateString) {
        $date = new DateTime($dateString);
        $year = $date->format('Y');
        $month = str_pad($date->format('m'), 2, '0', STR_PAD_LEFT);
        $day = str_pad($date->format('d'), 2, '0', STR_PAD_LEFT);
        return "$year-$month-$day";
    }

    // 設定要幾天內的熱門文章
    $day_limit = 7;
    $current_date = new DateTime();
    $days_ago = $current_date->sub(new DateInterval("P{$day_limit}D"));
    // 取得上面設定天數內的文章
    $hot_articles = array_filter($all_articles, function($article) use($days_ago){
    $article_published_date = new DateTime($article['published_at']);
    return $article_published_date >= $days_ago;
    });

    require_once("method/bootstrap5.html");
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsideTechNews</title>
    <link rel="stylesheet" href="method/css.css" type="text/css">
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
                        <form class="d-flex search-form" method="GET" action="article/search_articles.php">
                            <input class="form-control me-2" type="search" placeholder="搜尋文章" aria-label="Search" name="keyword">
                            <button class="btn" type="submit"><img src="img/icons8-search-30.png" alt="search"></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
        <!-- 透過緩存取得AI類最熱門的文章 -->
    <?php
        // 按照瀏覽量進行排序
        usort($hot_articles, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        // 取出瀏覽量前4高文章
        $hot_articles = array_slice($hot_articles, 0, 3);
    ?>

    <div class="container mt-5">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?php echo $hot_articles[0]['pic'] ?>" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-md-block">
                        <a href="article/article.php?id=<?php echo $hot_articles[0]['id'] ?>&category=<?php echo $hot_articles[0]['category'] ?>" class="custom-a">
                            <h5><?php echo $hot_articles[0]['title'] ?></h5>
                            <div class="carousel-content d-none d-md-block"><?php echo mb_substr($hot_articles['0']['content'], 0, 40, 'UTF-8') ?></div>
                        </a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?php echo $hot_articles[1]['pic'] ?>" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-md-block">
                        <a href="article/article.php?id=<?php echo $hot_articles[1]['id'] ?>&category=<?php echo $hot_articles[1]['category'] ?>" class="custom-a">    
                            <h5><?php echo $hot_articles[1]['title'] ?></h5>
                            <div class="carousel-content d-none d-md-block"><?php echo mb_substr($hot_articles['1']['content'], 0, 40, 'UTF-8') ?></div>
                        </a>    
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?php echo $hot_articles[2]['pic'] ?>" class="d-block w-100" alt="...">
                    <div class="carousel-caption  d-md-block">
                        <a href="article/article.php?id=<?php echo $hot_articles[2]['id'] ?>&category=<?php echo $hot_articles[2]['category'] ?>" class="custom-a">    
                            <h5><?php echo $hot_articles[2]['title'] ?></h5>
                            <div class="carousel-content d-none d-md-block"><?php echo mb_substr($hot_articles['2']['content'], 0, 40, 'UTF-8') ?>...</div>
                        </a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="text-center mt-5 mb-1 mb-md-3">
                <div class="custom-border-top pt-3">
                    <a href="article/AI.php" class="custom-a">
                        <h2 class="mt-3">A.I-人工智慧</h2>
                    </a>    
                </div>
            </div>
        </div>
    </div>

    <!-- 透過緩存取得AI分類的文章 -->
    <?php
        
    // 過濾出 ai 子分類的文章
    $ai_articles = array_filter($all_articles, function($article) use($days_ago) {
    $article_published_date = new DateTime($article['published_at']);
    return $article['category'] == "AI" && $article_published_date >= $days_ago;
    });
    // 並按照瀏覽量進行排序
    usort($ai_articles, function($a, $b) {
        return $b['views'] - $a['views'];
    });
    // 取出瀏覽量前4高文章
    $top_ai_articles = array_slice($ai_articles, 0, 4);
    ?>

    <!-- ai_article -->
    <div class="container">
        <div class="row">
            <?php foreach ($top_ai_articles as $ai) : ?>
                <?php
                    $published_date = new DateTime($ai['published_at']);
                ?>
                <div class="col-12 col-md-3 mt-3 mt-md-3">
                    <div class="card custom-card-article">
                    <img src="<?php echo $ai['pic'] ?>" class="card-img-top custom-card-img-article" title="<?php echo $ai['pic_title'] ?>" alt="<?php echo $ai['pic_alt'] ?>">
                        <div class="card-body card-body-custom text-start">
                            <a href="article/article.php?id=<?php echo $ai['id'] ?>&category=<?php echo $ai['category'] ?>" class="custom-a">
                                <h5 class="card-title mt-3"><?php echo $ai['title'] ?></h5>
                                <p class="card-text d-inline d-md-block">瀏覽量:<?php echo $views_data[$ai['id']] ?></p>
                                <p class="card-text d-inline d-md-block ms-3 ms-md-0"><?php echo $published_date->format('Y-m-d'); ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="text-center mt-5 mb-3">
                <div class="custom-border-top pt-3">
                    <a href="article/Hardware.php" class="custom-a">
                        <h2 class="mt-3">電腦硬體</h2>
                    </a>    
                </div>
            </div>
        </div>
    </div>

    <!-- 透過緩存取得hardware子分類的文章 -->
    <?php
        // 過濾出 hardware 子分類的文章
        $hardware_articles = array_filter($all_articles, function($article) use($days_ago) {
        $article_published_date = new DateTime($article['published_at']);
        return $article['category'] == "hardware" && $article_published_date >= $days_ago;
        });
        // 並按照瀏覽量進行排序
        usort($hardware_articles, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        // 取出瀏覽量前4高文章
        $hardware_articles = array_slice($hardware_articles, 0, 4);
    ?>

    <!-- Hardware_article -->
    <div class="container">
        <div class="row">
            <?php foreach ($hardware_articles as $Hardware) :?>
                <?php
                    $published_date = new DateTime($Hardware['published_at']);
                ?>
                <div class="col-12 col-md-3">
                    <div class="card custom-card-article mt-3">
                    <img src="<?php echo $Hardware['pic'] ?>" class="card-img-top custom-card-img-article" title="<?php echo $Hardware['pic_title'] ?>" alt="<?php echo $Hardware['pic_alt'] ?>">
                        <div class="card-body card-body-custom text-start">
                            <a href="article/article.php?id=<?php echo $Hardware['id'] ?>&category=<?php echo $Hardware['category'] ?>" class="custom-a">
                                <h5 class="card-title mt-3"><?php echo $Hardware['title'] ?></h5>
                                <p class="card-text d-inline d-md-block">瀏覽量:<?php echo $views_data[$Hardware['id']] ?></p>
                                <p class="card-text d-inline d-md-block ms-3 ms-md-0"><?php echo $published_date->format('Y-m-d'); ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="text-center mt-5 mb-3">
                <div class="custom-border-top pt-3">
                    <a href="article/Software.php" class="custom-a">
                        <h2 class="mt-3">軟體資訊</h2>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 透過緩存取得software子分類的文章 -->
    <?php
        // 過濾出software子分類的文章
        $software_articles = array_filter($all_articles, function($article) use($days_ago) {
        $article_published_date = new DateTime($article['published_at']);
        return $article['category'] == "software" && $article_published_date >= $days_ago;
        });
        // 並按照瀏覽量進行排序
        usort($software_articles, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        // 取出瀏覽量前4高文章
        $top_software_articles = array_slice($software_articles, 0, 4);
    ?>

    <!-- software_generator_article -->
    <div class="container">
        <div class="row">
            <?php foreach($top_software_articles as $software) :?>
                <?php
                    $published_date = new DateTime($software['published_at']);
                ?>
                <div class="col-12 col-md-3 mt-3">
                    <div class="card custom-card-article">
                    <img src="<?php echo $software['pic'] ?>" class="card-img-top custom-card-img-article" title="<?php echo $software['pic_title'] ?>" alt="<?php echo $software['pic_alt'] ?>">
                        <div class="card-body card-body-custom text-start">
                            <a href="article/article.php?id=<?php echo $software['id'] ?>&category=<?php echo $software['category'] ?>" class="custom-a">
                                <h5 class="card-title mt-3"><?php echo $software['title'] ?></h5>
                                <p class="card-text d-inline d-md-block">瀏覽量:<?php echo $views_data[$software['id']] ?></p>
                                <p class="card-text d-inline d-md-block ms-3 ms-md-0"><?php echo $published_date->format('Y-m-d'); ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="text-center mt-5 mb-3">
                <div class="custom-border-top pt-3">
                    <a href="article/search_articles.php" class="custom-a">
                        <h2 class="mt-3">點我看最新文章</h2>
                    </a>
                </div>
            </div>
        </div>
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


    </body>
</html>