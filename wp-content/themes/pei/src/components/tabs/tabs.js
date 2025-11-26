/**
 * Tabs (PEI) – stacked panes with advanced animation
 *
 * - Panes are stacked and cross-fade / slide in
 * - A gold indicator bar slides between active tabs
 * - Container height is fixed to the tallest pane to avoid page jump
 */

(function () {
    function initTabs(root) {
        if (!root || root.__peiTabsBound) return;
        root.__peiTabsBound = true;

        const nav = root.querySelector('.pei-tabs__nav');
        const buttons = nav ? Array.from(nav.querySelectorAll('.pei-tabs__tab')) : [];
        const panesWrap = root.querySelector('.pei-tabs__panes');
        const panes = Array.from(root.querySelectorAll('.pei-tabs__pane'));

        if (!buttons.length || !panes.length || !panesWrap) return;

        // Create sliding indicator bar
        let indicator = nav.querySelector('.pei-tabs__indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'pei-tabs__indicator';
            nav.appendChild(indicator);
        }

        let activeIndex = 0;

        // Read initial active from data attribute
        const fromAttr = parseInt(root.getAttribute('data-active-index') || '0', 10);
        if (!isNaN(fromAttr)) {
            activeIndex = Math.max(0, Math.min(fromAttr, panes.length - 1));
        }

        // Measure tallest pane and lock min-height so page doesn't jump
        function measureMaxHeight() {
            let maxHeight = 0;

            panes.forEach(function (pane) {
                const prevPos = pane.style.position;
                const prevVis = pane.style.visibility;
                const prevDisp = pane.style.display;

                pane.style.position = 'relative';
                pane.style.visibility = 'hidden';
                pane.style.display = 'block';

                const h = pane.offsetHeight;
                if (h > maxHeight) maxHeight = h;

                pane.style.position = prevPos;
                pane.style.visibility = prevVis;
                pane.style.display = prevDisp;
            });

            if (maxHeight > 0) {
                panesWrap.style.minHeight = maxHeight + 'px';
            }
        }

        function positionIndicator(index) {
            if (!indicator || !nav) return;
            const btn = buttons[index];
            if (!btn) return;

            const navRect = nav.getBoundingClientRect();
            const btnRect = btn.getBoundingClientRect();

            const left = btnRect.left - navRect.left;
            const width = btnRect.width;

            indicator.style.transform = 'translateX(' + left + 'px)';
            indicator.style.width = width + 'px';
        }

        function activate(index) {
            if (index === activeIndex) return;
            if (index < 0 || index >= panes.length) return;

            // Update tab button states
            buttons.forEach(function (btn, i) {
                const isActive = i === index;
                btn.classList.toggle('is-active', isActive);
                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                btn.setAttribute('tabindex', isActive ? '0' : '-1');
            });

            // Update panes (stacked, animated)
            panes.forEach(function (pane, i) {
                const isActive = i === index;
                pane.classList.toggle('is-active', isActive);
                pane.setAttribute('aria-hidden', isActive ? 'false' : 'true');

                // Restart animation on the newly active pane
                pane.classList.remove('pei-tabs__pane--anim-in');
                if (isActive) {
                    void pane.offsetWidth; // force reflow
                    pane.classList.add('pei-tabs__pane--anim-in');
                }
            });

            activeIndex = index;
            root.setAttribute('data-active-index', String(index));
            positionIndicator(index);
        }

        // Initial setup
        measureMaxHeight();

        panes.forEach(function (pane, i) {
            const isActive = i === activeIndex;
            pane.classList.toggle('is-active', isActive);
            pane.setAttribute('aria-hidden', isActive ? 'false' : 'true');
        });

        buttons.forEach(function (btn, i) {
            const isActive = i === activeIndex;
            btn.classList.toggle('is-active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
            btn.setAttribute('tabindex', isActive ? '0' : '-1');
            btn.setAttribute('role', 'tab');
            btn.setAttribute('data-index', String(i));
        });

        if (nav) {
            nav.setAttribute('role', 'tablist');
        }

        positionIndicator(activeIndex);

        // Click handling
        if (nav) {
            nav.addEventListener('click', function (e) {
                var target = e.target.closest('.pei-tabs__tab');
                if (!target) return;
                var idx = parseInt(target.getAttribute('data-index') || '-1', 10);
                if (idx >= 0) {
                    e.preventDefault();
                    activate(idx);
                }
            });
        }

        // On resize, recompute heights & indicator position
        function handleResize() {
            measureMaxHeight();
            positionIndicator(activeIndex);
        }

        window.addEventListener('resize', handleResize);
    }

    function initAll() {
        document.querySelectorAll('.pei-tabs').forEach(initTabs);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Observe dynamically injected content (editor / AJAX)
    if ('MutationObserver' in window) {
        var observer = new MutationObserver(function (mutations) {
            for (var i = 0; i < mutations.length; i++) {
                var m = mutations[i];
                if (m.type === 'childList' && m.addedNodes) {
                    m.addedNodes.forEach(function (n) {
                        if (n.nodeType !== 1) return;
                        if (n.matches && n.matches('.pei-tabs')) {
                            initTabs(n);
                        }
                        if (n.querySelectorAll) {
                            n.querySelectorAll('.pei-tabs').forEach(initTabs);
                        }
                    });
                }
            }
        });
        observer.observe(document.documentElement, { childList: true, subtree: true });
    }
})();
