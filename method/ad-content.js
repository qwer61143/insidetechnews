window.addEventListener("DOMContentLoaded", () => {
  const adClasses = document.querySelectorAll(".your-ad-class");

  adClasses.forEach((adClass) => {
    const adElement = document.createElement("ins");
    adElement.className = "adsbygoogle";
    adElement.style.display = "block";
    adElement.setAttribute("data-ad-client", "ca-pub-5881900148002626");
    adElement.setAttribute("data-ad-slot", "3915237190");
    adElement.setAttribute("data-ad-format", "auto");
    adElement.setAttribute("data-full-width-responsive", "true");

    const adScript = document.createElement("script");
    adScript.innerHTML = "(adsbygoogle = window.adsbygoogle || []).push({});";

    adClass.appendChild(adElement);
    adClass.appendChild(adScript);
  });
});