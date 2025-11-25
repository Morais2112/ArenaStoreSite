document.addEventListener('DOMContentLoaded', () => {
    const mainImage = document.querySelector('.main-image-container img');
    const thumbs = document.querySelectorAll('.thumb-box');
  
    if (thumbs && mainImage) {
      thumbs.forEach(thumb => {
        thumb.addEventListener('click', (e) => {
          e.preventDefault();
          thumbs.forEach(t => t.classList.remove('active'));
          thumb.classList.add('active');
          const img = thumb.querySelector('img');
          if (img && img.src) {
            mainImage.style.opacity = '0';
            setTimeout(() => {
              mainImage.src = img.src;
              mainImage.style.opacity = '1';
            }, 160);
          }
        });
      });
    }
  
    const lightbox = document.createElement('div');
    lightbox.id = 'lightboxModal';
    lightbox.innerHTML = `<div class="lightbox-inner"><img src="" alt="Foto ampliada"><button class="lightbox-close" aria-label="Fechar" style="position:absolute;right:28px;top:20px;background:none;border:none;color:#fff;font-size:30px;cursor:pointer">×</button></div>`;
    document.body.appendChild(lightbox);
    const lbImg = lightbox.querySelector('img');
    const lbClose = lightbox.querySelector('.lightbox-close');
  
    if (mainImage) {
      mainImage.addEventListener('click', () => {
        lbImg.src = mainImage.src;
        lightbox.classList.add('open');
        document.body.style.overflow = 'hidden';
      });
    }
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox || e.target === lbClose) {
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
      }
    });

    const stickyBar = document.querySelector('.sticky-buybar');
    const pageBuy = document.querySelector('.p-actions-container');
    const qtdInput = document.getElementById('qtdInput');
    
    if (qtdInput) {
      qtdInput.addEventListener('change', () => {
          const stickyQtd = document.querySelector('.sticky-buybar .qtd');
          if (stickyQtd) stickyQtd.textContent = qtdInput.value;
      });
    }
    
    if (stickyBar && pageBuy) {
      const toggleSticky = () => {
        if (window.innerWidth <= 900) {
          const rect = pageBuy.getBoundingClientRect();
          if (rect.top > window.innerHeight || rect.bottom < 0 || rect.top > 220) {
            stickyBar.style.transform = 'translateY(0)';
            stickyBar.style.opacity = '1';
          } else {
            stickyBar.style.transform = 'translateY(8px)';
            stickyBar.style.opacity = '0';
          }
        } else {
          stickyBar.style.opacity = '0';
        }
      };
      window.addEventListener('scroll', toggleSticky);
      window.addEventListener('resize', toggleSticky);
      toggleSticky();
    }
  });