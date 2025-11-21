// Hero component entry
import './hero.scss';

// Example interactive behavior
document.addEventListener('DOMContentLoaded', () => {
  const hero = document.querySelector('.pei-hero');
  if (!hero) return;
  hero.classList.add('mounted');
});
