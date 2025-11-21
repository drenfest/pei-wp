// Main entry for theme-wide JS and styles
import './styles/main.scss';

// Example: expose a simple helper
window.pei = Object.assign(window.pei || {}, {
  ready: (fn) => (document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn))
});
