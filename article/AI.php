<?php
    require_once '../method/get_articles.php';

    $category = 'AI';
    $data = get_articles($category);

    $pages = $data['pages'];
    $all_articles = $data['all_articles'];
    $views_data = $data['views_data'];
    $total_pages = $data['total_pages'];

    // 設定要幾天內的熱門文章
    $day_limit = 7;
    $current_date = new DateTime();
    $days_ago = $current_date->sub(new DateInterval("P{$day_limit}D"));
    // 取得上面設定天數內的文章
    $all_articles = array_filter($all_articles, function($article) use($days_ago){
    $article_published_date = new DateTime($article['published_at']);
    return $article_published_date >= $days_ago;
    });

    require_once("../method/bootstrap5.html");
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="InsideTechNews - 提供最新的人工智慧、PC硬體、軟體資訊、科技新聞和幣圈動態等資訊的網站。">
    <title>人工智慧</title>
    <link rel="stylesheet" href="/method/css.css" type="text/css">
    <script src="/method/adsense.js"></script>
    <script src="/method/ad-content.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="infinite_scroll.js"></script>
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
                <h1 class="fw-bold">A.I 人工智慧</h1>
            </div>
        </div>
    </div>

<?php if($pages<=1) { ?>

    <!-- 透過緩存取得AI類最熱門的文章 -->
    <?php
        // 按照瀏覽量進行排序
        usort($all_articles, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        // 取出瀏覽量前4高文章
        $top_ai_articles = array_slice($all_articles, 0, 3);
    ?>

    <!-- headlinehere -->
    <div class="container mt-4 d-none d-md-block">
        <div class="row d-flex">
            <div class="col-md-8">
                <div class="card card-headline">
                    <a href="article.php?id=<?php echo $top_ai_articles[0]['id'] ?>&category=<?php echo $category ?>" class="custom-a">
                        <img src="<?php echo $top_ai_articles[0]['pic'] ?>" class="card-img-top" title="<?php echo $top_ai_articles[0]['pic_title'] ?>" alt="<?php echo $top_ai_articles[0]['pic_alt'] ?>">
                    </a>
                    <div class="card-body">
                        <p class="card-title card-title-headline"><?php echo $top_ai_articles[0]['title'] ?></p>
                        <p class="card-text"><?php $top_ai_articles[0]['content'] ?></p>
                    </div>
                </div>
            </div>
            <!-- 分隔線 -->
            <div class="col-md-1 no-padding">
                <div class="divider mx-auto"></div>
            </div>
            <!-- subheadline -->
            <div class="col-md-3 flex-grow-1">
                <div class="subheadline">
                    <div class="subheadline-item mb-3">
                        <a href="article.php?id=<?php echo $top_ai_articles[1]['id'] ?>&category=<?php echo $category ?>" class="custom-a">
                            <img src="<?php echo $top_ai_articles[1]['pic'] ?>" class="card-img-top custom-subheadline-img" title="<?php echo $top_ai_articles[1]['pic_title'] ?>" alt="<?php echo $top_ai_articles[1]['pic_alt'] ?>">
                        </a>
                        <div class="subheadline-title mt-3">
                            <h5 class="card-title card-title-subheadline-1">A.<?php echo $top_ai_articles[1]['title'] ?></h5>
                        </div> 
                    </div>
                    <div class="subheadline-item mb-3">
                        <a href="article.php?id=<?php echo $top_ai_articles[2]['id'] ?>&category=<?php echo $category ?>" class="custom-a">
                            <img src="<?php echo $top_ai_articles[2]['pic'] ?>" class="card-img-top custom-subheadline-img" title="<?php echo $top_ai_articles[2]['pic_title'] ?>" alt="<?php echo $top_ai_articles[2]['pic_alt'] ?>">
                        </a>
                        <div class="subheadline-title mt-3">
                            <h5 class="card-title card-title-subheadline-2"><?php echo $top_ai_articles[2]['title'] ?></h5>
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>

    <!-- title -->
    <div class="container">
        <div class="row">
            <div class="text-center mt-2 mb-3">
                <div class="custom-border-top pt-3">
                    <h2 class="mt-3">語言模型</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- 透過緩存取得GPT子分類的文章 -->
    <?php

        // 過濾出 GPT 子分類的文章
        $gpt_articles = array_filter($all_articles, function($article) use($days_ago) {
        $article_published_date = new DateTime($article['published_at']);
        return $article['sub_category'] == "GPT" && $article_published_date >= $days_ago;
        });
        // 並按照瀏覽量進行排序
        usort($gpt_articles, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        // 取出瀏覽量前4高文章
        $top_gpt_articles = array_slice($gpt_articles, 0, 4);
    ?>

    <!-- gpt_article -->
    <div class="container">
        <div class="row">
            <?php foreach ($top_gpt_articles as $gpt) :?>
                <?php
                    $published_date = new DateTime($gpt['published_at']);
                ?>
                <div class="col-12 col-md-3 mt-3">
                    <div class="card custom-card-article">
                    <img src="<?php echo $gpt['pic'] ?>" class="card-img-top custom-card-img-article" title="<?php echo $gpt['pic_title'] ?>" alt="<?php echo $gpt['pic_alt'] ?>">
                        <div class="card-body card-body-custom text-start">
                            <a href="article.php?id=<?php echo $gpt['id'] ?>&category=<?php echo $category ?>" class="custom-a">
                                <h5 class="card-title mt-3"><?php echo $gpt['title'] ?></h5>
                                <p class="card-text">瀏覽量:<?php echo $views_data[$gpt['id']] ?></p>
                                <p class="card-text"><?php echo $published_date->format('Y-m-d'); ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- title -->
    <div class="container">
        <div class="row">
            <div class="text-center mt-5 mb-3">
                <div class="custom-border-top pt-3">
                    <h2 class="mt-3">AI繪圖</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- 透過緩存取得image子分類的文章 -->
    <?php
        // 過濾出 image 子分類的文章
        $image_articles = array_filter($all_articles, function($article) use($days_ago){
        $article_published_date = new DateTime($article['published_at']);
        return $article['sub_category'] == "image" && $article_published_date >= $days_ago;
        });
        // 並按照瀏覽量進行排序
        usort($image_articles, function($a, $b) {
            return $b['views'] - $a['views'];
        });
        // 取出瀏覽量前4高文章
        $top_image_articles = array_slice($image_articles, 0, 4);
    ?>

    <!-- image_generator_article -->
    <div class="container">
        <div class="row">
            <?php foreach($top_image_articles as $image) :?>
                <?php
                    $published_date = new DateTime($image['published_at']);
                ?>
                <div class="col-12 col-md-3 mt-3">
                    <div class="card custom-card-article">
                    <img src="<?php echo $image['pic'] ?>" class="card-img-top custom-card-img-article" title="<?php echo $image['pic_title'] ?>" alt="<?php echo $image['pic_alt'] ?>">
                        <div class="card-body card-body-custom text-start">
                            <a href="article.php?id=<?php echo $image['id'] ?>&category=<?php echo $category ?>" class="custom-a">
                                <h5 class="card-title mt-3"><?php echo $image['title'] ?></h5>
                                <p class="card-text">瀏覽量:<?php echo $views_data[$image['id']] ?></p>
                                <p class="card-text"><?php echo $published_date->format('Y-m-d'); ?></p>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- title -->
    <div class="container">
        <div class="row">
            <div class="text-center mt-5 mb-3">
                <div class="custom-border-top pt-3">
                    <h2 class="mt-3">所有文章</h2>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

    <!-- all_article -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 mt-3">
                <!-- 無限加載錨點-->
                <div id="scroll-anchor" data-pages="<?php echo $pages ?>" data-category="<?php echo $category ?>"></div>
                 <!-- 文章容器 -->
                <div id="articles-container"></div>
                <!-- 結束 -->
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
            <a href="?page=<?php echo $pages-1 ?>" style="margin-right: 5px;">&#60;</a>
        <?php }else { ?>

        <?php } ?>

        <?php
            for ($i=1; $i <= $total_pages; $i++) { 
                 if((int)$pages === (int)$i) { ?>
                    <span style="margin-right: 5px;"><?php echo $i; ?></span>
                <?php }else { ?>
                    <a href="?page=<?php echo $i ?>" style="margin-right: 5px;"><?php echo $i ?></a>
                <?php } ?>
            <?php } ?>
        <?php if($pages<$total_pages) { ?>
            <a href="?page=<?php echo $pages + 1 ?>">&#62;</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            (adsbygoogle = window.adsbygoogle || []).push({});
        });
    </script>
</body>
</html>