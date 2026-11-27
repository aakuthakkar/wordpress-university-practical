const dialog = document.getElementById('interest-dialog');
const form = document.getElementById('interest-form');
const thanks = document.getElementById('thanks');
let opener;
document.querySelectorAll('[data-dialog]').forEach(button => button.addEventListener('click', event => { event.preventDefault(); opener=button; form.hidden=false; thanks.hidden=true; form.reset(); dialog.showModal(); }));
dialog.querySelector('.close').addEventListener('click', () => dialog.close());
dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
dialog.addEventListener('close', () => { if(opener) opener.focus(); });
form.addEventListener('submit', event => { event.preventDefault(); if(!form.reportValidity()) return; form.hidden=true; thanks.hidden=false; });
const menu=document.querySelector('.menu-toggle'); const nav=document.querySelector('#nav');
menu.addEventListener('click',()=>{const open=nav.classList.toggle('open');menu.setAttribute('aria-expanded',String(open));});

// Only one original hero photo was supplied. Other positions are placeholders.
const hero = document.querySelector('.hero');
if (hero) {
  const photo = hero.querySelector('.hero-image');
  const dots = [...hero.querySelectorAll('.dots button')];
  const status = hero.querySelector('.slide-status');
  const slides = [
    { src: photo.src, position: 'center 58%' },
    { src: photo.src, position: 'left 58%' },
    { src: photo.src, position: 'right 58%' }
  ];
  let current = 0;
  let timer;
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  function showSlide(index) {
    current = (index + slides.length) % slides.length;
    photo.src = slides[current].src;
    photo.style.objectPosition = slides[current].position;
    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === current);
      if (i === current) dot.setAttribute('aria-current', 'true');
      else dot.removeAttribute('aria-current');
    });
    status.textContent = `Slide ${current + 1} of ${slides.length}`;
  }
  function stop() { window.clearInterval(timer); timer = undefined; }
  function start() {
    stop();
    if (!reducedMotion.matches && !hero.matches(':hover') && !hero.matches(':focus-within')) {
      timer = window.setInterval(() => showSlide(current + 1), 5000);
    }
  }
  hero.querySelector('.hero-prev').addEventListener('click', () => { showSlide(current - 1); start(); });
  hero.querySelector('.hero-next').addEventListener('click', () => { showSlide(current + 1); start(); });
  dots.forEach((dot, index) => dot.addEventListener('click', () => { showSlide(index); start(); }));
  hero.addEventListener('mouseenter', stop);
  hero.addEventListener('mouseleave', start);
  hero.addEventListener('focusin', stop);
  hero.addEventListener('focusout', () => window.setTimeout(start, 0));
  start();
}
