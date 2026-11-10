# Bootstrap reference homepage + PHP downloads

A responsive homepage following the supplied Bootstrap screenshot, with a single **Download** button in the top navbar. The button downloads the supplied EXAM_READY_SYSTEMS archive; no file cards or file descriptions appear on the homepage. Bootstrap 5.3.8 CSS/JS, Geist fonts, reference SVG icons, and the reference newsletter image are stored locally: page styling and interactions work offline. No Composer or npm dependencies are needed. External documentation and newsletter links require internet access. The hero's v6 alpha text and install command reproduce the supplied reference; the local site's UI runs on stable Bootstrap 5.3.8.

The header includes working version/theme dropdowns, a documentation topic search with Ctrl+K (Cmd+K on Mac), and clipboard buttons for both displayed commands. Search filters a curated set of documentation links. It does not search the entire remote Bootstrap documentation.

## Files

```text
index.php                 XAMPP homepage entry
download.php              XAMPP handler entry
api/index.php             Shared homepage / Vercel entry
api/catalog.php           Strict file allowlist and metadata
api/download.php          Streaming PHP handler
assets/css/style.css      Responsive theme
assets/css/reference.css  Screenshot layout, gradient, fonts, responsive overrides
assets/js/main.js         Saved light/dark/system preference
assets/vendor/            Local Bootstrap CSS/JS and Geist font files
assets/images/            Reference SVG icons and newsletter image
downloads/.htaccess       Apache download headers
downloads/EXAM_READY_SYSTEMS (2).zip
downloads/purple-starter.zip
downloads/component-recipes.zip
vercel.json               Runtime, routes, static attachment headers
tests/verify.py            Download integration checks
```

## Run with XAMPP on Windows

1. Install XAMPP with PHP 8.x from https://www.apachefriends.org/ if needed. Start Apache in its control panel.
2. Copy this project into `C:\xampp\htdocs\PHP_SkillsTest`. Keep the `api`, `assets`, and `downloads` folders together.
3. Open `http://localhost/PHP_SkillsTest/`. Use HTTP rather than opening PHP files directly.
4. Click **Download** in the top navbar to download your `EXAM_READY_SYSTEMS (2).zip` unchanged. The button is also visible beside the mobile menu toggle. The two earlier starter archives remain in the downloads folder but are not listed on the homepage.

Alternatively, run directly in the current workspace without copying or starting Apache:

```powershell
Set-Location 'C:\Users\govan\OneDrive\Desktop\PHP_SkillsTest'
& 'C:\xampp\php\php.exe' -S localhost:8000 -t .
```

Visit `http://localhost:8000/`. Press Ctrl+C to stop. If PHP is on PATH, `php -S localhost:8000 -t .` also works. Open the project in VS Code with `code .`.

## How downloads work

Your archive is already installed at `downloads/EXAM_READY_SYSTEMS (2).zip`, with the allowlist ID `exam-ready-systems`. Clicking **Download** in the navbar downloads that exact archive. Its URL uses percent encoding for spaces and parentheses automatically. The optional PHP URL is `download.php?file=exam-ready-systems`. Keep this ZIP in the project when copying to XAMPP or pushing to GitHub/Vercel; a link to your Windows Downloads folder would not work for website visitors.

The navbar uses a same-origin link to the selected ZIP with the HTML `download` attribute. Public ZIPs are served as static files, avoiding function response-size limits. Vercel sets `Content-Disposition: attachment` on `/downloads/*`; Apache uses the supplied `.htaccess` when overrides and mod_headers are enabled. Browser settings determine whether saving is automatic or a Save As dialog appears.

The optional handler is `download.php?file=starter` (or `api/download.php?file=starter`). It only accepts catalog IDs, checks canonical file paths, sends attachment/type/length headers, and streams the archive. Unknown IDs and missing files return 404; unsupported methods return 405. It supports GET and HEAD; it does not implement resumable range requests. Use direct static links for large archives, particularly on Vercel.

These archives are public. The allowlist protects the PHP handler from arbitrary filesystem reads; it does not make the static downloads private. Do not place secrets or private archives in `downloads`.

## Add a ZIP file

1. Create a genuine ZIP, for example using PowerShell:

   ```powershell
   Compress-Archive -LiteralPath '.\my-kit' -DestinationPath '.\downloads\my-kit-v1.zip'
   ```

2. Prefer a simple lowercase filename with letters, numbers, dots, and hyphens, ending in `.zip`. Existing names containing spaces and parentheses, including your supplied archive, also work and are encoded automatically in links.
3. Add a unique entry inside the array returned by `catalog()` in `api/catalog.php`:

   ```php
   'my-kit' => [
       'file' => 'my-kit-v1.zip',
       'title' => 'My project kit',
       'description' => 'Explain exactly what this archive contains.',
       'label' => 'PROJECT RESOURCES',
       'version' => '1.0',
   ],
   ```

4. To point the single navbar button to the new archive, change `$download = catalog()['exam-ready-systems'];` in `api/index.php` to `$download = catalog()['my-kit'];`. Registering another archive alone does not add visible cards or buttons.
5. Refresh the homepage and click **Download**. Extract the downloaded archive to confirm its contents. The button is omitted if its selected file is missing. Additional allowlisted files remain downloadable using their PHP handler IDs, such as `download.php?file=my-kit`.
6. Commit the ZIP and catalog change and redeploy. Prefer a new versioned filename when replacing an archive to avoid stale browser or CDN caches.

## Deploy to Vercel

Configuration follows the community runtime's documented `vercel-php@0.9.0` example (PHP 8.5). Vercel does not officially support this runtime; its maintainers now recommend a container approach for new projects. Documentation: https://github.com/vercel-community/php and https://vercel.com/docs/project-configuration/vercel-json . This configuration is prepared, but a live Vercel deployment has not been verified from this workspace.

1. Create an empty repository on GitHub, then run these commands from this project. Replace `YOUR-USER` with your GitHub username and use your actual repository name.

   ```powershell
   git init
   git add .
   git commit -m "Add PHP Bootstrap download site"
   git branch -M main
   git remote add origin https://github.com/YOUR-USER/PHP_SkillsTest.git
   git push -u origin main
   ```

2. Sign in at https://vercel.com and import that repository.
3. Select **Other** as framework preset. Use this repository's root directory. Leave Build Command and Output Directory overrides disabled; there is no frontend build. Use Node.js 22.x for the documented runtime environment. No environment variables are required.
4. Deploy. `vercel.json` sends homepage and handler requests to `api/`, while `assets/` and `downloads/` remain static. `.vercelignore` excludes the root PHP wrappers so their source is never served as static text; the PHP functions are under `api/`.
5. Verify the live homepage, mobile menu, all downloads, and `download.php?file=unknown` (404). Use browser Network tools or `curl.exe -I https://YOUR-DOMAIN/downloads/purple-starter.zip` to check `Content-Disposition: attachment` and the ZIP content type. Compare a downloaded file's SHA-256 with `Get-FileHash .\downloads\purple-starter.zip`.
6. Later changes deploy automatically after `git add .`, `git commit -m "Update downloads"`, and `git push`.

If a Vercel runtime build fails, inspect its logs and the runtime documentation rather than guessing version numbers. A standard PHP 8.x Apache host is an alternative: upload this project to its document root and use `index.php`; `vercel.json` is only for Vercel. The ZIPs must be committed deployment assets: adding files to a running function is not persistent storage.

## Verification and troubleshooting

Run `python tests/verify.py` to check every allowlisted archive using an isolated local PHP server. It reads the catalog automatically, validates ZIP integrity, and checks that static and handler downloads match the stored archives byte for byte.

For PHP errors, check XAMPP Apache logs or the PHP server terminal. For missing ZIPs, check spelling and case (production paths are case-sensitive), the catalog, and whether Git includes the file. If styles or the mobile menu fail, inspect the browser Network panel for missing files in `assets/vendor/` and hard refresh with Ctrl+F5. If Apache reports a 500 due to restricted `.htaccess` directives, ask the host to allow them or remove that file and use the PHP handler links for attachment headers. The HTML download attribute still requests direct downloads in compatible browsers.

Browser QA passed at 1902, 1440, 1024, 768, 390, and 320 pixels, including no horizontal overflow, clipboard actions, dropdowns, Ctrl+K search, theme persistence, and mobile navigation. Local download integration tests use PHP's built-in server. Apache-specific headers and live Vercel behavior still need checking in their target environments.

Reference visuals are reproduced from the user-supplied saved HTML and screenshot. Bootstrap and Bootstrap Icons: https://github.com/twbs/bootstrap/blob/main/LICENSE and https://github.com/twbs/icons/blob/main/LICENSE . Geist and Geist Mono: https://github.com/vercel/geist-font (SIL Open Font License). The newsletter image is the supplied reference's design.dev artwork; no advertising or tracking scripts are included.
