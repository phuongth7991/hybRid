document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.vcex-justified-gallery').forEach(function (el) {
        imagesLoaded(el, function () {
            $(el).justifiedGallery({
                rowHeight: 200,
                margins: 8,
                lastRow: 'justify'
            });
        });
    });
    Fancybox.bind('[data-fancybox="gallery"]', {
        Thumbs: {
            autoStart: true
        }
    });
    new Swiper('.comment-swiper', {
        loop: true,
        autoplay: {
            delay: 5000
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });
    new Swiper('.media-swiper', {
        loop: true,
        autoplay: {
            delay: 5000
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    });

    $('#mobile-menu').on('click', '.mobile-menu-toggle', function (e) {
        e.preventDefault();
        const $toggleBtn = $(this);
        const isOpen = $toggleBtn.hasClass('wpex-active');
        const expanded = $toggleBtn.attr('aria-expanded') === 'true';
        const $icon = $toggleBtn.find('.wpex-hamburger-icon');
        const $ul = $('.mobile-toggle-nav .mobile-toggle-nav-ul');
        if (!isOpen) {
            const menu = $('#site-navigation-wrap #menu-main-menu').children().clone(true);
            menu.each(function () {
                const $a = $(this).find('a').first();
                if ($(this).hasClass('menu-item-has-children')) {
                    $a.append('<button class="wpex-open-submenu wpex-unstyled-button wpex-flex wpex-items-center wpex-justify-end wpex-absolute wpex-top-0 wpex-right-0 wpex-h-100 wpex-cursor-pointer wpex-opacity-80 wpex-overflow-hidden" aria-haspopup="true" aria-expanded="false" role="button" aria-label="Open submenu of Giới thiệu"><span class="wpex-open-submenu__icon wpex-transition-all wpex-duration-300 ticon ticon-angle-down" aria-hidden="true"></span></button>')
                }
            });

            $ul.html(menu);
        } else {
            $ul.empty();
        }
        $toggleBtn.toggleClass('wpex-active');
        $toggleBtn.attr('aria-expanded', String(!expanded));
        $icon.toggleClass('wpex-hamburger-icon--active wpex-hamburger-icon--inactive');
    });

    const mq = window.matchMedia('(min-width: 960px)');

    function handleMobileMenu(e) {
        const isDesktop = e.matches;
        const $toggleBtn = $('#mobile-menu .mobile-menu-toggle');
        const $icon = $toggleBtn.find('.wpex-hamburger-icon');

        if (isDesktop) {
            $('.mobile-toggle-nav .mobile-toggle-nav-ul').empty();
            $toggleBtn.removeClass('wpex-active').attr('aria-expanded', 'false');
            $icon.addClass('wpex-hamburger-icon--inactive').removeClass('wpex-hamburger-icon--active');
            $('.mobile-toggle-nav').removeClass('wpex-z-9999');
        }
    }

    handleMobileMenu(mq);

    if (mq.addEventListener) mq.addEventListener('change', handleMobileMenu);
    else if (mq.addListener) mq.addListener(handleMobileMenu);

    window.addEventListener('resize', () => handleMobileMenu(mq));

    $('.mobile-toggle-nav-ul').on('click', '.wpex-open-submenu__icon', function (e) {
        e.preventDefault();

        const $submenuIcon = $(this);
        const $subLi = $submenuIcon.closest('li');
        const $subMenu = $subLi.find('.sub-menu');
        const expanded = $submenuIcon.attr('aria-expanded') === 'true';
        $submenuIcon.attr('aria-expanded', String(String(!expanded)));
        $subLi.toggleClass('active');
        $subMenu.toggleClass('show');
    });

    // Scroll to top button functionality
    const $scrollTopBtn = $('#site-scroll-top');
    window.addEventListener("scroll", function () {
        if (window.scrollY > 100) {
            $scrollTopBtn.addClass('show').removeClass('wpex-invisible wpex-opacity-0');
        } else {
            $scrollTopBtn.removeClass('show').addClass('wpex-invisible wpex-opacity-0');
        }
    });

    $scrollTopBtn.on("click", function (e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
});
