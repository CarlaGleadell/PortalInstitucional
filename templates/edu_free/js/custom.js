/* CARRUSEL DE NOTICIAS UNPA */

document.addEventListener('DOMContentLoaded', function () {
  const newsContainer = document.querySelector('#sp-position1 ul.mod-articles-items');
  const moduleContainer = document.querySelector('#sp-position1 .uk-panel');

  if (!newsContainer) return;

  const items = newsContainer.querySelectorAll('li');
  if (items.length === 0) return;

  let currentSlide = 0;
  let autoplayInterval;

  function initCarousel() {
    items.forEach(item => item.classList.remove('active', 'next'));
    items[0].classList.add('active');
    createNavigationControls();
    createIndicators();
    loadRealImages();
    setupEventListeners();
    formatDatesAndButtons();
    startAutoplay();
  }

  async function loadRealImages() {
    const baseUrl = window.location.origin;
    items.forEach((item, i) => {
      const img = item.querySelector('img');
      if (img && img.getAttribute('src')) {
        let imageSrc = img.getAttribute('src');
        if (imageSrc.startsWith('images/')) {
          imageSrc = `${baseUrl}/portalUARG/${imageSrc}`;
        } else if (imageSrc.startsWith('/')) {
          imageSrc = `${baseUrl}${imageSrc}`;
        }
        item.style.backgroundImage = `url('${imageSrc}')`;
      } else {
        applyDefaultImage(item);
      }
    });
  }

  function applyDefaultImage(item) {
    const baseUrl = window.location.origin;
    const defaultImage = `${baseUrl}/portalUARG/images/10ingresocampus.jpg`;
    item.style.backgroundImage = `url('${defaultImage}')`;
  }

  function createNavigationControls() {
    const container = document.querySelector('#sp-position1 .uk-panel');

    const prevBtn = document.createElement('button');
    prevBtn.className = 'carousel-nav prev';
    prevBtn.innerHTML = '&#8249;';
    prevBtn.setAttribute('aria-label', 'Noticia anterior');
    prevBtn.addEventListener('click', () => {
      goToSlide(currentSlide - 1);
      restartAutoplay();
    });

    const nextBtn = document.createElement('button');
    nextBtn.className = 'carousel-nav next';
    nextBtn.innerHTML = '&#8250;';
    nextBtn.setAttribute('aria-label', 'Siguiente noticia');
    nextBtn.addEventListener('click', () => {
      goToSlide(currentSlide + 1);
      restartAutoplay();
    });

    container.appendChild(prevBtn);
    container.appendChild(nextBtn);
  }

  function createIndicators() {
    const container = newsContainer.parentElement;
    const indicatorsContainer = document.createElement('div');
    indicatorsContainer.className = 'carousel-indicators';

    items.forEach((_, index) => {
      const indicator = document.createElement('button');
      indicator.className = 'carousel-indicator';
      if (index === 0) indicator.classList.add('active');
      indicator.setAttribute('aria-label', `Ir a noticia ${index + 1}`);
      indicator.addEventListener('click', () => {
        goToSlide(index);
        restartAutoplay();
      });
      indicatorsContainer.appendChild(indicator);
    });

    container.appendChild(indicatorsContainer);
  }

  function goToSlide(slideIndex) {
    if (slideIndex >= items.length) slideIndex = 0;
    if (slideIndex < 0) slideIndex = items.length - 1;

    items.forEach(item => item.classList.remove('active', 'next'));
    items[slideIndex].classList.add('active');

    const indicators = document.querySelectorAll('.carousel-indicator');
    indicators.forEach(indicator => indicator.classList.remove('active'));
    if (indicators[slideIndex]) {
      indicators[slideIndex].classList.add('active');
    }

    currentSlide = slideIndex;
  }

  function startAutoplay() {
    autoplayInterval = setInterval(() => {
      goToSlide(currentSlide + 1);
    }, 5000);
  }

  function restartAutoplay() {
    clearInterval(autoplayInterval);
    startAutoplay();
  }

  function pauseAutoplay() {
    clearInterval(autoplayInterval);
  }

  function setupEventListeners() {
    if (moduleContainer) {
      moduleContainer.addEventListener('mouseenter', pauseAutoplay);
      moduleContainer.addEventListener('mouseleave', startAutoplay);
      moduleContainer.setAttribute('tabindex', '0');
      moduleContainer.setAttribute('role', 'region');
      moduleContainer.setAttribute('aria-label', 'Carrusel de noticias UNPA');

      moduleContainer.addEventListener('keydown', (e) => {
        switch (e.key) {
          case 'ArrowLeft':
            e.preventDefault();
            goToSlide(currentSlide - 1);
            restartAutoplay();
            break;
          case 'ArrowRight':
            e.preventDefault();
            goToSlide(currentSlide + 1);
            restartAutoplay();
            break;
          case 'Enter':
          case ' ':
            e.preventDefault();
            const link = items[currentSlide].querySelector('.mod-articles-category-title');
            if (link) openArticle(link.href);
            break;
        }
      });

      let startX = 0;
      moduleContainer.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
      }, { passive: true });

      moduleContainer.addEventListener('touchend', (e) => {
        if (!startX) return;
        const endX = e.changedTouches[0].clientX;
        const diffX = startX - endX;

        if (Math.abs(diffX) > 50) {
          if (diffX > 0) {
            goToSlide(currentSlide + 1);
          } else {
            goToSlide(currentSlide - 1);
          }
          restartAutoplay();
        }
        startX = 0;
      }, { passive: true });
    }

    document.addEventListener('click', (e) => {
      const link = e.target.closest('#sp-position1 a');
      if (link && link.href) {
        e.preventDefault();
        e.stopPropagation();
        openArticle(link.href);
      }
    });
  }

  function openArticle(url) {
    try {
      window.location.assign(url);
    } catch {
      window.location.href = url;
    }
  }

  function formatDatesAndButtons() {
    setTimeout(() => {
      const readMoreLinks = document.querySelectorAll('#sp-position1 .mod-articles-category-readmore a');
      readMoreLinks.forEach(link => {
        link.textContent = 'Leer más';
      });

      const dateElements = document.querySelectorAll('#sp-position1 .mod-articles-category-date');
      dateElements.forEach(dateEl => {
        const dateText = dateEl.textContent;
        const parts = dateText.split('-');
        if (parts.length === 3) {
          dateEl.textContent = `${parts[0]}-${parts[1]}`;
        }
      });
    }, 100);
  }

  if (items.length > 0) {
    window.NewsCarousel = {
      next: () => goToSlide(currentSlide + 1),
      prev: () => goToSlide(currentSlide - 1),
      goto: (index) => goToSlide(index),
      current: () => currentSlide,
      total: () => items.length,
      reload: () => location.reload()
    };
  }

  initCarousel();
});
