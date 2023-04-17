<?php
    require_once "../method/connect.php";

    if (isset($_GET['id']) && isset($_GET['category'])) {
        $article_id = intval($_GET['id']);
        $category = $_GET['category'];
        
        $cache_file = "../cache/article_data_{$category}.json";
        $views_cache_file = "../cache/views_data.json";
        
        //取出這分類的文章瀏覽量資料
        $views_data = json_decode(file_get_contents($views_cache_file), true);
        //該文章瀏覽量+1
        $views_data[$article_id]++;
        //將增加後的資料寫入緩存
        file_put_contents($views_cache_file, json_encode($views_data));

        //取得並過濾出這篇文章
        $articles = json_decode(file_get_contents($cache_file), true);
        //返回的是二元陣列
        $article= array_filter($articles, function($article) use($article_id) {
            return $article['id'] === $article_id;
        });
        $article = array_values($article)[0];
    }

    $all_cache_file = "../cache/article_data.json";
    //取出所有文章
    $hot_articles = json_decode(file_get_contents($all_cache_file), true);
    //排列出前10熱門文章
    usort($hot_articles, function($a, $b) {
        return $b['views'] - $a['views'];
    });
    //取出瀏覽量前10文章
    $top_article = array_slice($hot_articles, 0, 10);
    // 下方foreach用
    $i = 1;
    require_once "../method/bootstrap5.html"
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="InsideTechNews - 提供最新的人工智慧、PC硬體、軟體資訊、科技新聞和幣圈動態等資訊的網站。">
    <title><?php echo $article['title'] ?></title>
    <link rel="stylesheet" href="/method/css.css" type="text/css">
    <script src="/method/share.js"></script>
    <script src="/method/adsense.js"></script>
    <script src="/method/ad-content.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
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

    <!-- 文章標題 -->
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-3 mt-5">
                <h1 class="fw-bold article-title"><?php echo $article['title'] ?></h1>
            </div>
            <div class="articles-divider-horizontal"></div>
        </div>
    </div>
    <!-- 作者，瀏覽量 -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-9 pr-4 mb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex mt-3 mb-3 align-items-center">
                            <p class="custom-article-p">
                                <?php 
                                    if(isset($article['author']) && $article['author'] != "") {
                                        echo "作者:".$article['author'];
                                    }
                                ?>
                                <span class="mx-3"></span>
                                <?php echo "瀏覽數:".$views_data[$article_id]; ?>
                                <span class="mx-3"></span>
                                <?php echo "日期".$article['published_at']; ?>
                            </p>
                            <!-- 社群媒體 -->
                            <div class="ms-auto">
                                <div class="line-it-button" data-lang="zh_Hant" data-type="share-b" data-env="REAL" data-url="http://localhost/article/AI.php" data-color="default" data-size="small" data-count="false" data-ver="3"></div>
                                <div class="ms-1" id="fb-root"></div>
                            </div>
                        </div>
                        
                        <div class="articles-divider-horizontal-2 mb-3"></div>
                    
                        <div>
                            <img src="<?php echo $article['pic'] ?>" alt="<?php echo $article['pic_alt'] ?>" class="article_img img-fluid" title="<?php echo $article['pic_title'] ?>">
                        </div>
                    </div>
                    <div class="mt-3">
                        <p>
                        <?php 
                        if(isset($article['pic_title']) && $article['pic_title'] != "") {
                            echo $article['pic_title'];
                        }
                        ?>
                        <span class="mx-3"></span>
                        <?php
                        if(isset($article['pic_source']) && $article['pic_source'] != "") {
                            echo "圖片來源:".$article['pic_source'];
                        }
                        ?>
                        </p>
                    </div>
                </div>
                <div class="articles-divider-horizontal mx-auto mb-3"></div>
                <div class="col-12 mt-2 article-text">
                    <?php 
                        echo "&nbsp;&nbsp;".$article['content'];
                        echo "<br><br>";
                        echo $article['article_text'] 
                    ?>
                </div>
            </div>        
            <div class="col-12 col-md-3 pl-4 mb-3 mt-1 mt-md-5">
                <div class="today-hot mb-3">
                    <div class="d-flex justify-content-center align-items-center">
                    <div class="today-hot-title">本日熱門</div>
                        <img src="/img/top10icon.png" alt="Mostpopulartoday" title="Mostpopulartoday" class="top10-image">
                       
                    </div>
                    <ol class="list-group custom-list-style vertical-list">
                        <?php foreach($top_article as $top) : ?>
                            <li class="list-group-item border-bottom">
                                <div class="number-container">
                                    <span class="number"><?php echo $i ?></span>
                                </div>
                                <a href="/article/article.php?id=<?php echo $top['id'] ?>&category=<?php echo $top['category'] ?>" class="custom-a">
                                    <span class="title"><?php echo $top['title'] ?></span>
                                </a>
                            </li>
                            <?php $i++ ?>
                        <?php endforeach ?>
                    </ol>
                </div>
                <div class="articles-divider-horizontal mx-auto mt-1 mt-md-5 mb-1 mb-md-5"></div>
                <!-- 電腦版廣告 -->
                <div class="ads-area d-none d-md-block"></div>
                <!-- 手機板廣告 -->
                <div class="your-ad-class d-md-none"></div>
            </div>
        </div>
    </div>

    <div class="articles-divider-horizontal-2 mb-3 d-none d-md-block"></div>

    <!-- 排序找出相似標題文章 -->

    <?php
        $article_title = $article['title'];
    
        usort($articles, function($a,$b) use($article_title){
            // 計算標題相似度
            $percent_a = similar_text($a['title'], $article_title);
            $percent_b = similar_text($b['title'], $article_title);
            
            if ($percent_a === $percent_b) {
                return 0;
            }

            return ($percent_a > $percent_b) ? -1 : 1;
        });

        $similar_article = array_slice($articles, 0, 8);
    ?>

    <!-- 相似文章 -->
    <div class="container">
        <div class="row">
            <h1>相似文章:</h1>
            <div class="col-12 col-md-8 mt-1 mt-md-3 similar-article">
                <?php foreach($similar_article as $similar) : ?>
                    <div class="card mb-3 w-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <a href="article.php?id=<?php echo $similar['id'] ?>&category=<?php echo $similar['category'] ?>">
                                <img src="<?php echo $similar['pic'] ?>" class="img-fluid card-img" alt="<?php echo $similar['pic_alt'] ?>" title="<?php echo $similar['pic_title'] ?>">
                                </a>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                <div class="title-container">
                                    <h5 class="card-title"><?php echo $similar['title'] ?></h5>
                                </div>
                                <div class="text-container d-none d-md-block">
                                    <p class="card-text"><?php echo mb_substr($similar['content'], 0, 60, 'UTF-8') ?>...</p>
                                </div>
                                <p class="card-text d-none d-mb-block">
                                    <small class="text-muted">瀏覽量:<?php echo $views_data[$similar['id']] ?></small>
                                </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="search-divider-horizontal mx-auto mb-3 mt-1"></div>
                <?php endforeach ?>
                <!-- ad -->
            </div>
                <div class="col-12 col-md-3 d-none d-md-block">
                    <!-- 電腦版廣告 -->
                    <div class="ads-area-2"></div>
                    <!-- 手機板廣告 -->
                    <div class="your-ad-class d-md-none">AD HERE</div>
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

    <script>
        $(document).ready(function() {
        // 廣告的高度（包括邊距等）
        const adHeight = 250;

        // 取得內容區域的高度
        const contentHeight = $(".article-text").height();

        const similarContentHeight = $(".similar-article").height();

        // 根據內容區域的高度計算可以插入多少廣告
        const numberOfAds = Math.floor(contentHeight / adHeight);

        const numberOfAds2 = Math.floor(similarContentHeight / adHeight);
        // 根據計算結果，插入相應數量的廣告
        for (let i = 0; i < numberOfAds; i++) {
            // 插入廣告的代碼，例如：
            $(".ads-area").append('<div class="your-ad-class custom-article-ad"></div>');
        }

        for (let i = 0; i < numberOfAds2; i++) {
            // 插入廣告的代碼，例如：
            $(".ads-area-2").append('<div class="your-ad-class custom-article-ad"></div>');
        }
        });
    </script>
</body>
</html>