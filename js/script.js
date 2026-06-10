// слайдер на главном экране
var sliderTrack = document.querySelector('.slider-track');
var btnPrev = document.getElementById('slider-prev');
var btnNext = document.getElementById('slider-next');

if (sliderTrack && btnPrev && btnNext) {
    var currentSlide = 0;
    var totalSlides = document.querySelectorAll('.slide').length;

    function goToSlide(index) {
        currentSlide = index;
        sliderTrack.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';

        if (currentSlide === 0) {
            btnPrev.classList.add('dimmed');
        } else {
            btnPrev.classList.remove('dimmed');
        }

        if (currentSlide === totalSlides - 1) {
            btnNext.classList.add('dimmed');
        } else {
            btnNext.classList.remove('dimmed');
        }
    }

    btnPrev.addEventListener('click', function() {
        if (currentSlide > 0) {
            goToSlide(currentSlide - 1);
        }
    });

    btnNext.addEventListener('click', function() {
        if (currentSlide < totalSlides - 1) {
            goToSlide(currentSlide + 1);
        }
    });

    goToSlide(0);

    // свайп на мобилке
    var sliderWrap = document.querySelector('.slider-wrap');
    if (sliderWrap) {
        var touchStartX = 0;
        sliderWrap.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
        });
        sliderWrap.addEventListener('touchend', function(e) {
            var diff = touchStartX - e.changedTouches[0].clientX;
            if (diff > 50 && currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1);
            } else if (diff < -50 && currentSlide > 0) {
                goToSlide(currentSlide - 1);
            }
        });
    }
}

// аккордеон faq
var faqItems = document.querySelectorAll('.faq-item');

function openFaqItem(item) {
    var content = item.querySelector('.faq-content');
    item.classList.add('active');
    content.style.height = content.scrollHeight + 'px';
}

function closeFaqItem(item) {
    var content = item.querySelector('.faq-content');
    item.classList.remove('active');
    content.style.height = '0';
}

for (var i = 0; i < faqItems.length; i++) {
    var header = faqItems[i].querySelector('.faq-header');

    header.addEventListener('click', function() {
        var parentItem = this.parentElement;

        if (parentItem.classList.contains('active')) {
            closeFaqItem(parentItem);
        } else {
            openFaqItem(parentItem);
        }
    });

    // выставление высоты для уже открытых при загрузке
    if (faqItems[i].classList.contains('active')) {
        openFaqItem(faqItems[i]);
    }
}

// бургер-меню
var burger = document.getElementById('burger');
var mobileOverlay = document.getElementById('mobile-overlay');
var mobileMenuClose = document.getElementById('mobile-menu-close');

if (burger && mobileOverlay && mobileMenuClose) {
    function openMenu() {
        mobileOverlay.classList.add('open');
    }

    function closeMenu() {
        mobileOverlay.classList.remove('open');
    }

    burger.addEventListener('click', openMenu);
    mobileMenuClose.addEventListener('click', closeMenu);

    mobileOverlay.addEventListener('click', function(e) {
        if (e.target === mobileOverlay) {
            closeMenu();
        }
    });

    var menuLinks = mobileOverlay.querySelectorAll('a');
    for (var m = 0; m < menuLinks.length; m++) {
        menuLinks[m].addEventListener('click', closeMenu);
    }
}

// работа с модальным окном
var modal = document.getElementById('info-modal');
var openButtons = document.querySelectorAll('.open-modal-btn');
var closeButton = document.querySelector('.close-btn');

function openModal() {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

for (var k = 0; k < openButtons.length; k++) {
    openButtons[k].addEventListener('click', openModal);
}

if (closeButton) {
    closeButton.addEventListener('click', closeModal);
}

// закрытие по клику вне окна
window.addEventListener('click', function(event) {
    if (event.target === modal) {
        closeModal();
    }
});

// живые показатели: пульс и уровень страха
var currentPulse = 143;
var currentFear  = 42;

function nudge(val, delta, min, max) {
    var next = val + (Math.random() * delta * 2 - delta);
    next = Math.round(next);
    if (next < min) next = min;
    if (next > max) next = max;
    return next;
}

function tickVitals() {
    currentPulse = nudge(currentPulse, 4, 120, 162);
    currentFear  = nudge(currentFear, 2, 28, 62);

    var ph = document.getElementById('pulse-home');
    var pp = document.getElementById('pulse-profile');
    var fv = document.getElementById('fear-val');

    if (ph) ph.textContent = 'Пульс Исаака: ' + currentPulse + 'уд/мин';
    if (pp) pp.textContent = currentPulse + 'уд/мин';
    if (fv) fv.textContent = currentFear + '%';
}

tickVitals();
setInterval(tickVitals, 2000);

