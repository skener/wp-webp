/**
 LazyLoad with IntersectionObserver
 */
window.Lazyload = {
    total_images: 0,
    started_loading: 0,
    finished_loading: 0,
    percent_of_loaded_images: 0,
    init: function () {
        var self = this;
        var lazyloadImages = document.querySelectorAll('.lazy');
        document.querySelectorAll('.lazy');

        this.total_images += lazyloadImages.length;

        //fallback
        if (!('IntersectionObserver' in window)) {
            Array.from(lazyloadImages).forEach(function (image) {
                Lazyload.load(image);
            });
        } else {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.intersectionRatio > 0) {
                        observer.unobserve(entry.target);
                        Lazyload.load(entry.target);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            lazyloadImages.forEach(function (image) {
                observer.observe(image);
            });
        }
    },
    load: function (image) {
        var self = this;
        this.started_loading++;
        image.src = image.dataset.src;

        this.percent_of_loaded_images = Math.round(this.finished_loading / this.started_loading * 100);

        image.onload = function () {
            setTimeout(function () {
                image.classList.remove('lazy');
                self.finished_loading++;
                self.percent_of_loaded_images = Math.round(self.finished_loading / self.started_loading * 100);
            }, 1000);
        }
    }
};

(function () {
    var webp = new Image();
    webp.onerror = function () {
        console.log('Your browser does not suppoert WebP');
        return true;
    };
    webp.onload = function () {
        //console.log('Your browser supports WebP!');
        return true;
    };
    webp.src = 'data:image/webp;base64,UklGRjIAAABXRUJQVlA4ICYAAACyAgCdASoBAAEALmk0mk0iIiIiIgBoSygABc6zbAAA/v56QAAAAA==';
    if (true !== webp.onload() && true === webp.onerror()) {
        return;
    } else {
        var Img = document.querySelectorAll('img.lazy');
        Img.forEach((val) => {
            var src = val.getAttribute('data-src');
            var webpSrc = src.replace('jpg', 'webp');
             val.setAttribute('data-src', webpSrc);
        });
        Lazyload.init();
    }
})();
