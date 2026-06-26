(function () {
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.primary-nav');

  if (!toggle || !nav) return;

  function setOpen(isOpen) {
    nav.classList.toggle('open', isOpen);
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  }

  toggle.addEventListener('click', function () {
    setOpen(!nav.classList.contains('open'));
  });

  // Close the mobile menu after tapping a link so navigation feels clean.
  nav.addEventListener('click', function (event) {
    if (event.target.closest('a')) {
      setOpen(false);
    }
  });
})();

(function () {
  const accountMenu = document.querySelector('.account-menu');
  const trigger = accountMenu ? accountMenu.querySelector('.account-trigger') : null;

  if (!accountMenu || !trigger) return;

  trigger.addEventListener('click', function () {
    const isOpen = accountMenu.classList.toggle('open');
    trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  document.addEventListener('click', function (event) {
    if (!accountMenu.contains(event.target)) {
      accountMenu.classList.remove('open');
      trigger.setAttribute('aria-expanded', 'false');
    }
  });
})();

(function () {
  const tabRoot = document.querySelector('[data-portal-tabs]');
  if (!tabRoot) return;

  const buttons = Array.from(tabRoot.querySelectorAll('[data-tab-target]'));
  const panels = Array.from(tabRoot.querySelectorAll('[data-tab-panel]'));

  buttons.forEach(function (button) {
    button.addEventListener('click', function () {
      const target = button.dataset.tabTarget;

      buttons.forEach(function (item) {
        const isActive = item === button;
        item.classList.toggle('active', isActive);
        item.setAttribute('aria-selected', isActive ? 'true' : 'false');
      });

      panels.forEach(function (panel) {
        panel.classList.toggle('active', panel.dataset.tabPanel === target);
      });
    });
  });
})();
