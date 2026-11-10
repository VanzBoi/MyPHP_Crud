<?php
declare(strict_types=1);
require_once __DIR__ . '/catalog.php';
$download = catalog()['exam-ready-systems'];
$downloadAvailable = archive_path($download['file']) !== null;
// Relative links also work when XAMPP serves this project in a subfolder.
$base = str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/api/') ? '../' : './';
?><!doctype html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Bootstrap is the open source design system for everyone.">
  <title>Bootstrap · The open source design system for everyone</title>
  <link href="<?= $base ?>assets/vendor/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $base ?>assets/css/style.css" rel="stylesheet">
  <link href="<?= $base ?>assets/css/reference.css" rel="stylesheet">
  <script src="<?= $base ?>assets/js/main.js" defer></script>
</head>
<body>
<a class="visually-hidden-focusable skip" href="#main">Skip to content</a>
<nav class="navbar navbar-expand-lg sticky-top reference-nav" aria-label="Main navigation"><div class="nav-container">
  <a class="navbar-brand bootstrap-brand" href="<?= $base ?>" aria-label="Bootstrap home"><svg width="50" height="40" aria-hidden="true"><use href="<?= $base ?>assets/images/icons.svg#bootstrap"></use></svg></a>
  <?php if ($downloadAvailable): ?><a class="btn btn-purple navbar-download ms-auto ms-lg-3 order-lg-3" href="<?= $base ?>downloads/<?= rawurlencode($download['file']) ?>" download="<?= escape($download['file']) ?>">Download</a><?php endif; ?>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="navigation">
    <div class="navbar-nav primary-links"><a class="nav-link" href="https://getbootstrap.com/docs/6.0/">Docs</a><a class="nav-link" href="https://getbootstrap.com/docs/6.0/examples/">Examples</a><a class="nav-link" href="https://icons.getbootstrap.com/">Icons</a><a class="nav-link" href="https://blog.getbootstrap.com/">Blog</a></div>
    <button class="docs-search" type="button" data-bs-toggle="modal" data-bs-target="#search-modal"><svg class="icon" aria-hidden="true"><use href="<?= $base ?>assets/images/icons.svg#search"></use></svg><span>Search docs...</span><kbd>Ctrl K</kbd></button>
    <div class="nav-utilities"><a class="icon-link" href="https://github.com/twbs/bootstrap" aria-label="Bootstrap on GitHub"><svg class="icon" aria-hidden="true"><use href="<?= $base ?>assets/images/icons.svg#github"></use></svg></a>
      <div class="dropdown"><button class="utility-button" data-bs-toggle="dropdown" aria-expanded="false">v6.0 <span class="chevrons" aria-hidden="true">⌃<br>⌄</span></button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="https://getbootstrap.com/docs/6.0/">v6.0 alpha</a></li><li><a class="dropdown-item" href="https://getbootstrap.com/docs/5.3/">v5.3</a></li><li><a class="dropdown-item" href="https://getbootstrap.com/docs/versions/">All versions</a></li></ul></div>
      <div class="dropdown"><button class="utility-button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Choose color theme"><svg class="icon" aria-hidden="true"><use href="<?= $base ?>assets/images/icons.svg#circle-half"></use></svg><span class="chevrons" aria-hidden="true">⌃<br>⌄</span></button><div class="dropdown-menu dropdown-menu-end theme-menu" role="group" aria-label="Color theme"><button class="dropdown-item" data-theme="light" aria-pressed="true">☀ &nbsp; Light</button><button class="dropdown-item" data-theme="dark" aria-pressed="false">☾ &nbsp; Dark</button><button class="dropdown-item" data-theme="auto" aria-pressed="false">◐ &nbsp; Auto</button></div></div>
    </div>
  </div>
</div></nav>
<main id="main">
<header class="hero reference-hero"><div class="hero-inner text-center">
  <a class="security-badge" href="https://www.herodevs.com/support/nes-bootstrap" target="_blank" rel="noopener">Security updates for Bootstrap 3 &amp; 4 <span aria-hidden="true">→</span></a>
  <h1>Bootstrap is the open source<br class="desktop-break"> design system for everyone</h1>
  <p class="hero-description">Bootstrap 6 has been rebuilt for today's web as a framework-agnostic design toolkit that anyone—human or AI, novice or pro—can use to build anything. New standards, new components, an updated visual design, CSS-first theming, and more powerful JavaScript plugins.<br><a href="https://blog.getbootstrap.com/">Learn more on the blog.</a></p>
  <div class="hero-actions"><div class="install-command"><code id="install-command">npm i bootstrap@6.0.0-alpha.1</code><button type="button" id="copy-command" aria-label="Copy install command"><svg class="icon" aria-hidden="true"><use href="<?= $base ?>assets/images/icons.svg#copy"></use></svg></button></div>
    <div class="dropdown"><button class="agents-button" type="button" data-bs-toggle="dropdown" aria-expanded="false"><svg class="icon" aria-hidden="true"><use href="<?= $base ?>assets/images/icons.svg#rocket-takeoff-fill"></use></svg> For Agents <span class="chevrons" aria-hidden="true">⌃<br>⌄</span></button><ul class="dropdown-menu"><li><button class="dropdown-item" id="copy-skill">Copy v6 migration skill command</button></li><li><a class="dropdown-item" href="https://getbootstrap.com/docs/6.0/getting-started/ai">Bootstrap &amp; AI docs</a></li><li><a class="dropdown-item" href="https://getbootstrap.com/docs/6.0/getting-started/ai#skills">Explore all skills</a></li></ul></div>
    <a class="docs-button" href="https://getbootstrap.com/docs/6.0/getting-started/install"><svg class="icon" aria-hidden="true"><use href="<?= $base ?>assets/images/icons.svg#book-half"></use></svg> Read the docs</a>
  </div>
  <p class="release-links">Currently v6.0.0-alpha.1 <span>·</span> <a href="https://getbootstrap.com/docs/5.3/">Bootstrap 5 docs</a> <span>·</span> <a href="https://getbootstrap.com/docs/versions/">All releases</a></p>
  <aside class="reference-ad" aria-label="Featured newsletter"><a class="ad-card" href="https://design.dev/" target="_blank" rel="noopener"><img src="<?= $base ?>assets/images/design-newsletter.jpg" alt="design.dev — The Newsletter For Creators" width="163" height="125"><span>Get the Drop. Weekly front-end tools, tips, and resources. Plus a free icon pack to get you started.</span></a><a class="ad-attribution" href="https://www.carbonads.net/">ads via Carbon</a></aside>
</div></header>
</main>
<footer class="container py-4 d-flex flex-wrap gap-3 justify-content-between"><span><strong>Bootstrap</strong> &nbsp; Designed for everyone.</span><span>Built with PHP & Bootstrap · <a href="#main">Back to top ↑</a></span></footer>
<div class="modal fade" id="search-modal" tabindex="-1" aria-labelledby="search-title" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h2 class="modal-title fs-5" id="search-title">Search documentation</h2><button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><label for="docs-query" class="form-label">Find a topic</label><input id="docs-query" class="form-control" type="search" placeholder="Try grid, buttons, or forms" autocomplete="off"><ul id="search-results" class="list-group mt-3"></ul></div></div></div></div>
<div id="copy-status" class="copy-status" role="status" aria-live="polite"></div>
<script src="<?= $base ?>assets/vendor/bootstrap.bundle.min.js"></script>
</body></html>
