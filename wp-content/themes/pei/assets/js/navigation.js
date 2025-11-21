// PEI Lean Theme navigation interactions (mobile-first)
document.addEventListener('DOMContentLoaded', function () {
  var nav = document.querySelector('.main-navigation');
  var toggle = document.querySelector('.menu-toggle');
  var menu = document.getElementById('primary-menu');

  if (!nav || !toggle) return;

  // Ensure aria-controls is set to menu id for a11y
  if (menu && !toggle.getAttribute('aria-controls')) {
    toggle.setAttribute('aria-controls', menu.id);
  }

  // Main hamburger toggle
  toggle.addEventListener('click', function () {
    var isOpen = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  // Submenu toggles (mobile)
  var parentItems = nav.querySelectorAll('.menu-item-has-children');
  parentItems.forEach(function (li) {
    var link = li.querySelector(':scope > a');
    // Add a small caret button after the link for mobile
    var btn = document.createElement('button');
    btn.className = 'submenu-toggle';
    btn.setAttribute('aria-expanded', 'false');
    btn.setAttribute('type', 'button');
    btn.setAttribute('aria-label', 'Toggle submenu');
    btn.innerHTML = '<span class="caret" aria-hidden="true"></span><span class="screen-reader-text">Toggle submenu</span>';
    // Insert after link
    if (link && link.nextSibling) {
      li.insertBefore(btn, link.nextSibling);
    } else if (link) {
      li.appendChild(btn);
    }

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      // Only act like an accordion on small screens
      if (window.matchMedia('(min-width: 768px)').matches) return;
      var isOpen = li.classList.toggle('submenu-open');
      btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      // Optional: close sibling submenus for cleanliness
      if (isOpen) {
        var siblings = li.parentElement ? li.parentElement.children : [];
        Array.prototype.forEach.call(siblings, function (sib) {
          if (sib !== li && sib.classList && sib.classList.contains('submenu-open')) {
            var sibBtn = sib.querySelector(':scope > .submenu-toggle');
            sib.classList.remove('submenu-open');
            if (sibBtn) sibBtn.setAttribute('aria-expanded', 'false');
          }
        });
      }
    });

    // First tap opens, second tap follows link behavior (mobile)
    if (link) {
      link.addEventListener('click', function (e) {
        if (!window.matchMedia('(min-width: 768px)').matches) {
          if (!li.classList.contains('submenu-open')) {
            e.preventDefault();
            li.classList.add('submenu-open');
            btn.setAttribute('aria-expanded', 'true');
          }
        }
      });
    }
  });

  // Close open mobile submenus when switching to desktop
  var mql = window.matchMedia('(min-width: 768px)');
  function handleBreakpoint(e) {
    if (e.matches) {
      // desktop: reset mobile-only states
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      nav.querySelectorAll('.menu-item-has-children.submenu-open').forEach(function (li) {
        li.classList.remove('submenu-open');
      });
      nav.querySelectorAll('.submenu-toggle').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
    }
  }
  if (mql.addEventListener) mql.addEventListener('change', handleBreakpoint);
  else if (mql.addListener) mql.addListener(handleBreakpoint); // Safari
});
