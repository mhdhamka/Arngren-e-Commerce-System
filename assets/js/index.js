document.addEventListener("DOMContentLoaded", function () {

    // Hero Slider
    let slides = document.querySelectorAll(".slide");
    let current = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove("active-slide"));
        slides[index].classList.add("active-slide");
    }

    const nextBtn = document.querySelector(".next");
    const prevBtn = document.querySelector(".prev");

    if (nextBtn && prevBtn && slides.length > 0) {

        nextBtn.onclick = function () {
            current = (current + 1) % slides.length;
            showSlide(current);
        };

        prevBtn.onclick = function () {
            current = (current - 1 + slides.length) % slides.length;
            showSlide(current);
        };

        setInterval(function () {
            current = (current + 1) % slides.length;
            showSlide(current);
        }, 5000);
    }

    // Bottom Navigation
    const nav = document.getElementById("categoryNav");
    const leftBtn = document.getElementById("leftBtn");
    const rightBtn = document.getElementById("rightBtn");

    if (nav && leftBtn && rightBtn) {
        rightBtn.onclick = function () {
            nav.scrollLeft += 200;
        };

        leftBtn.onclick = function () {
            nav.scrollLeft -= 200;
        };
    }

});