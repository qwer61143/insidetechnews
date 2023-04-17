(function() {
    function initInfiniteScroll() {
        // 取得頁數
        let pages = parseInt($('#scroll-anchor').attr('data-pages'));
        // 類別，用於下方ajax請求
        let category = $('#scroll-anchor').attr('data-category') || 'AI';
        // 標記目前沒有ajax請求
        let isLoading = false;
        // 標記是否可以加載更多文章
        let loadMore = true;

        // 格式化日期
        function formatDate(dateString) {
            let date = new Date(dateString);
            let year = date.getFullYear();
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let day = ("0" + date.getDate()).slice(-2);
            return `${year}-${month}-${day}`;
        }

        // 加載文章
        async function loadArticles() {
            // 如果目前正在進行 ajax 請求，或者不能加載更多文章，就直接返回
            if (isLoading || !loadMore) return;
            // 標記ajax正在請求
            isLoading = true;

            $.ajax({
                url: "load_articles.php",
                type: "GET",
                data: { page: pages, category: category },
                dataType: "json",
                success: async function (data) {
                    
                    if (data.length != 0) {
                        for (const article of data) {
                            let formattedDate = formatDate(article.published_at);
                            let articleHtml =
                            `<div class="card mb-3 w-100 article">
                                <div class="row g-0">
                                <div class="col-md-4">
                                <a href="article.php?id=${article.id}&category=${article.category}" class="custom-a">
                                    <img src="${article.pic}" class="img-fluid card-img" alt="${article.pic_alt}" title="${article.pic_title}">
                                </a>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                    <div class="title-container">
                                        <h5 class="card-title">${article.title}</h5>
                                    </div>
                                    <div class="text-container">
                                        <p class="card-text">${article.content.substring(0, 60)}...</p>
                                    </div>
                                    <p class="card-text">
                                        <small class="text-muted">${formattedDate}</small>
                                    </p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="divider-horizontal mx-auto mb-3"></div>`;

                            let articleElement = $(articleHtml);
                            $('#articles-container').append(articleElement);

                            // 逐步顯示文章
                            setTimeout(() => {
                                articleElement.addClass('visible');
                            }, 600);

                            // 等待一段時間再顯示下一篇文章
                            await new Promise((resolve) => setTimeout(resolve, 300));
                        }
                        loadMore = false;
                    } else {
                        // 解除滾動事件
                        $(window).off('scroll');
                    }
                    // 標記該ajax請求已經結束
                    isLoading = false;
                },
            });
        }

        // 檢查滾動至哪個位置，通過return來判斷是否開始加載，offset設置離錨點位置
        function checkScrollToAnchor() {
            let anchor = $('#scroll-anchor');
            let scrollTop = $(window).scrollTop();
            let windowHeight = $(window).height();
            let anchorTop = anchor.offset().top;
            let offset = 950;
    
            return scrollTop + windowHeight - offset >= anchorTop;
        }

        // 第二頁之後直接，第一頁則要滾動至錨點才會開始加載
        if(pages >= 2) {
            loadArticles();
        } else {
            $(window).on('scroll', function() {
                if (checkScrollToAnchor()) {
                    loadArticles();
                }
            });
        }
    }

    $(document).ready(function() {
        initInfiniteScroll();
    });

})();
