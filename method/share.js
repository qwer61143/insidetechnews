(function() {
    // Line SDK
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "https://www.line-website.com/social-plugins/js/thirdparty/loader.min.js";
      js.async = true;
      js.defer = true;
      fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "line-sdk"));
  
    // Facebook SDK
    window.onload = function() {
      var fbScript = document.createElement("script");
      fbScript.async = true;
      fbScript.defer = true;
      fbScript.crossOrigin = "anonymous";
      fbScript.src = "https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v16.0";
      fbScript.setAttribute("nonce", "8bwJypwN");
  
      var fbRoot = document.createElement("div");
      fbRoot.id = "fb-root";
      document.body.appendChild(fbRoot);
  
      document.body.appendChild(fbScript);
  
      // Facebook Share Button
      var fbShareButton = document.createElement("div");
      fbShareButton.className = "fb-share-button";
      fbShareButton.setAttribute("data-href", window.location.href);
      fbShareButton.setAttribute("data-layout", "button_count");
  
      var targetElement = document.querySelector(".d-flex.mt-3.mb-3.align-items-center");
      if (targetElement) {
        targetElement.appendChild(fbShareButton);
      } else {
        console.error("目標元素未找到，請檢查您的選擇器。");
      }
    };
  })();