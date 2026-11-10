(() => {
  const system = matchMedia('(prefers-color-scheme: dark)');
  let preference = 'light';
  try { preference = localStorage.getItem('purple-theme') || 'light'; } catch {}
  if (!['light', 'dark', 'auto'].includes(preference)) preference = 'light';
  const apply = () => {
    document.documentElement.setAttribute('data-bs-theme', preference === 'auto' ? (system.matches ? 'dark' : 'light') : preference);
    document.querySelectorAll('[data-theme]').forEach(button => {
      button.classList.toggle('active', button.dataset.theme === preference);
      button.setAttribute('aria-pressed', String(button.dataset.theme === preference));
    });
  };
  apply();
  document.querySelectorAll('[data-theme]').forEach(button => button.addEventListener('click', () => {
    preference = button.dataset.theme;
    try { localStorage.setItem('purple-theme', preference); } catch {}
    apply();
  }));
  system.addEventListener('change', apply);

  let statusTimer;
  async function copy(text) {
    const status = document.getElementById('copy-status');
    try {
      await navigator.clipboard.writeText(text);
      status.textContent = 'Copied to clipboard';
    } catch {
      status.textContent = 'Select and copy this command: ' + text;
    }
    status.classList.add('visible');
    clearTimeout(statusTimer);
    statusTimer = setTimeout(() => status.classList.remove('visible'), 4500);
  }
  document.getElementById('copy-command').addEventListener('click', () => copy(document.getElementById('install-command').textContent));
  document.getElementById('copy-skill').addEventListener('click', () => copy('npx skills add twbs/bootstrap'));

  const topics = [
    ['Getting started', 'https://getbootstrap.com/docs/6.0/getting-started/install'],
    ['Grid and layout', 'https://getbootstrap.com/docs/5.3/layout/grid/'],
    ['Buttons', 'https://getbootstrap.com/docs/5.3/components/buttons/'],
    ['Cards', 'https://getbootstrap.com/docs/5.3/components/card/'],
    ['Forms', 'https://getbootstrap.com/docs/5.3/forms/overview/'],
    ['Color modes and themes', 'https://getbootstrap.com/docs/5.3/customize/color-modes/'],
    ['Bootstrap & AI', 'https://getbootstrap.com/docs/6.0/getting-started/ai'],
  ];
  const query = document.getElementById('docs-query');
  const results = document.getElementById('search-results');
  const modal = document.getElementById('search-modal');
  const render = () => {
    results.replaceChildren();
    const matches = topics.filter(([title]) => title.toLowerCase().includes(query.value.trim().toLowerCase()));
    matches.forEach(([title, href]) => {
      const li = document.createElement('li');
      li.className = 'list-group-item';
      const link = document.createElement('a');
      link.href = href;
      link.textContent = title;
      if (href.startsWith('#')) link.addEventListener('click', () => window.bootstrap.Modal.getInstance(modal)?.hide());
      li.append(link);
      results.append(li);
    });
    if (!matches.length) {
      const li = document.createElement('li');
      li.className = 'list-group-item text-body-secondary';
      li.textContent = 'No matching topics. Try grid, buttons, or forms.';
      results.append(li);
    }
  };
  query.addEventListener('input', render);
  modal.addEventListener('shown.bs.modal', () => { render(); query.focus(); });
  document.addEventListener('keydown', event => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k' && window.bootstrap) {
      event.preventDefault();
      window.bootstrap.Modal.getOrCreateInstance(modal).show();
    }
  });
})();
