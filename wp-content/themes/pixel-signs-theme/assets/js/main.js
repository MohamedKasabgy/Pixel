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
  const accountMenus = Array.from(document.querySelectorAll('.account-menu'));

  if (accountMenus.length === 0) return;

  function closeMenu(accountMenu) {
    const trigger = accountMenu.querySelector('.account-trigger');
    accountMenu.classList.remove('open');
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'false');
    }
  }

  accountMenus.forEach(function (accountMenu) {
    const trigger = accountMenu.querySelector('.account-trigger');

    if (!trigger) return;

    trigger.addEventListener('click', function () {
      const wasOpen = accountMenu.classList.contains('open');
      accountMenus.forEach(closeMenu);
      accountMenu.classList.toggle('open', !wasOpen);
      trigger.setAttribute('aria-expanded', wasOpen ? 'false' : 'true');
    });
  });

  document.addEventListener('click', function (event) {
    accountMenus.forEach(function (accountMenu) {
      if (!accountMenu.contains(event.target)) {
        closeMenu(accountMenu);
      }
    });
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
