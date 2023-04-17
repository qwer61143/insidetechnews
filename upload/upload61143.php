<?php

require_once "../method/connect.php";

if($_SERVER['REQUEST_METHOD'] === "POST") {
   
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub_category'];
    $pic_link = "";
    $pic = "";
    $pic_alt = $_POST['pic_alt'];
    $pic_title = $_POST['pic_title'];
    $pic_source = $_POST['pic_source'];

    if(isset($_POST['author'])) {
        $author = $_POST['author'];
    }else {
        $author = "";
    }

    // 組合輸入的內文段落
    $article_text = "";
    // 遍歷POST(上傳後儲存資料的地方)
    foreach ($_POST as $key => $value) {
        // 如果找的到article_text這個key就繼續
        if (strpos($key, "article_text") !== false) {
            // 如果value不是只有空白字元(有內容)
            if (trim($value) !== '') {
                //首段加兩個空白字元
                $article_text .= "&nbsp;&nbsp;" . $value;
                //末加上兩個換行字符
                $article_text .= "<br><br>";
            }
        }
    }

    var_dump($article_text);

    if($_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['pic']['tmp_name'];
        $handle = fopen($image, "rb");
        $image = base64_encode(fread($handle,filesize($image)));
        $curl_post = array('image' => $image, 'title' => $title);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image');
        curl_setopt($curl, CURLOPT_TIMEOUT, '30');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID c5f40afed18aaa5'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 'true');
        
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result, true);

        if($response['success'] == 'true')  {
            $pic_link = $response['data']['link'];
        }else {
            echo $response['status'];
        }
    }else {
        echo $_FILES['pic']['error'];
    }

    if(isset($title,$content,$category)) {
        $insert = $conn->prepare("INSERT INTO article (title, content, category, sub_category, article_text, author, pic, pic_source, pic_title, pic_alt, published_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $insert->execute(array($title, $content, $category, $sub_category, $article_text, $author, $pic_link, $pic_source, $pic_title, $pic_alt));
    }
    
    require_once "../method/bootstrap5.html";
}

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上傳文章到資料庫</title>
    <script>
        function updateSubCategories() {
            const categorySelect = document.querySelector('#category');
            const subCategorySelect = document.querySelector('#sub_category');
            const selectedCategory = categorySelect.value;

            let subCategories = [];

            if (selectedCategory === 'AI') {
                subCategories = ['GPT', 'image'];
            }
            if (selectedCategory === 'hardware') {
                subCategories = ['cpu', 'graphics'];
            }
            if (selectedCategory === 'software') {
                subCategories = ['app', 'programming'];
            }

            subCategorySelect.innerHTML = '';
            subCategories.forEach(subCategory => {
                const option = document.createElement('option');
                option.value = subCategory;
                option.textContent = subCategory;
                subCategorySelect.appendChild(option);
            });
        }
    </script>
</head>
<body onload="updateSubCategories()">
    <form action="" method="POST" enctype="multipart/form-data" class="container">
        <div class="mb-3">
            <label for="title" class="form-label">文章標題</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="title">
        </div>

        <div class="input-group mb-3">
            <label for="content" class="form-label">前文,導語(顯示在主頁面)</label>
            <input type="text" name="content" class="form-control" placeholder="content">
        </div>

        <div class="input-group mb-3">
            <label for="content1" class="form-label">內文1</label>
            <input type="text" name="article_text" class="form-control" placeholder="內文1">
        </div>

        <div class="input-group mb-3">
            <label for="content2" class="form-label">內文2</label>
            <input type="text" name="article_text1" class="form-control" placeholder="內文2">
        </div>

        <div class="input-group mb-3">
            <label for="content3" class="form-label">內文3</label>
            <input type="text" name="article_text2" class="form-control" placeholder="內文3">
        </div>

        <div class="input-group mb-3">
            <label for="content4" class="form-label">內文4</label>
            <input type="text" name="article_text3" class="form-control" placeholder="內文4">
        </div>
        <!-- Content inputs ... -->
        <div class="mb-3">
            <label for="author" class="form-label">作者</label>
            <input type="text" name="author" id="author" class="form-control" placeholder="author">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">主類別</label>
            <select name="category" id="category" class="form-select" onchange="updateSubCategories()">
                <option value="hardware">Hardware</option>
                <option value="software">Software</option>
                <option value="AI">AI</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="sub_category" class="form-label">副分類</label>
            <select name="sub_category" id="sub_category" class="form-select">
                <!-- Sub-categories will be populated by JavaScript -->
            </select>
        </div>
        <div class="mb-3">
            <label for="pic" class="form-label">照片上傳</label>
            <input type="file" name="pic" id="pic" class="form-control">
        </div>
        <div class="mb-3">
            <label for="pic_source" class="form-label">圖片來源</label>
            <input type="text" name="pic_source" id="pic_source" class="form-control" placeholder="pic_source">
        </div>
        <div class="mb-3">
            <label for="pic_title" class="form-label">Picture Title</label>
            <input type="text" name="pic_title" id="pic_title" class="form-control" placeholder="pic_title">
        </div>
        <div class="mb-3">
            <label for="pic_alt" class="form-label">Picture Alt</label>
            <input type="text" name="pic_alt" id="pic_alt" class="form-control" placeholder="pic_alt">
        </div>
        <button type="submit" class="btn btn-primary">送出</button>
    </form>

</body>
</html>