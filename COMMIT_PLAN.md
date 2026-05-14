# Commit Plan — WordPress White Label MVP

Each commit is scoped to a single feature module. Prefix convention: `feat:`, `fix:`, `chore:`, `style:`, `refactor:`.

---

## Phase 1 — Scaffold
| # | Commit Message | Files |
|---|---|---|
| 1 | `chore: init project — plugin scaffold, README, .gitignore` | `wordpress-white-label.php`, `README.md`, `.gitignore`, `COMMIT_PLAN.md` |
| 2 | `feat: add plugin core loader and module autoloader` | `includes/class-plugin.php`, `includes/class-loader.php` |

---

## Phase 2 — Branding & Admin Cleanup
| # | Commit Message | Files |
|---|---|---|
| 3 | `feat(branding): custom Howdy text, footer text, dashboard headline, hide WP version` | `modules/branding/class-branding.php` |
| 4 | `feat(cleanup): remove Help tab, Screen Options tab, default dashboard widgets` | `modules/cleanup/class-cleanup.php` |
| 5 | `feat(admin-bar): hide admin bar per user role` | `modules/admin-bar/class-admin-bar.php` |
| 6 | `feat(welcome-panel): replace default WordPress welcome panel` | `modules/welcome-panel/class-welcome-panel.php` |

---

## Phase 3 — Login Customizer
| # | Commit Message | Files |
|---|---|---|
| 7 | `feat(login-customizer): register WP Customizer panel and sections` | `modules/login-customizer/class-login-customizer.php` |
| 8 | `feat(login-customizer): logo, background, and form color controls` | `modules/login-customizer/class-login-customizer.php` (extend) |
| 9 | `feat(login-customizer): inject live preview CSS on login page` | `modules/login-customizer/assets/login-preview.js`, `login-styles.php` |
| 10 | `feat(login-customizer): filter login_headerurl and login_headertext` | `modules/login-customizer/class-login-customizer.php` (extend) |

---

## Phase 4 — Login Redirect
| # | Commit Message | Files |
|---|---|---|
| 11 | `feat(login-redirect): settings page for per-role redirect URLs` | `modules/login-redirect/class-login-redirect.php` |
| 12 | `feat(login-redirect): block unauthorized wp-admin access by role` | `modules/login-redirect/class-login-redirect.php` (extend) |

---

## Phase 5 — Custom Widgets
| # | Commit Message | Files |
|---|---|---|
| 13 | `feat(widgets): register custom-widget post type` | `modules/widget/class-widget-post-type.php` |
| 14 | `feat(widgets): icon widget and text widget rendering` | `modules/widget/class-widget-renderer.php` |
| 15 | `feat(widgets): widget admin list and meta box UI` | `modules/widget/views/widget-metabox.php` |

---

## Phase 6 — Custom Admin Pages
| # | Commit Message | Files |
|---|---|---|
| 16 | `feat(admin-pages): register custom admin page post type` | `modules/admin-page/class-admin-page-post-type.php` |
| 17 | `feat(admin-pages): render top-level and sub-menu pages dynamically` | `modules/admin-page/class-admin-page-renderer.php` |

---

## Phase 7 — Custom CSS
| # | Commit Message | Files |
|---|---|---|
| 18 | `feat(custom-css): settings textarea for admin and login custom CSS` | `modules/custom-css/class-custom-css.php` |
| 19 | `feat(custom-css): enqueue custom CSS on admin and login screens` | `modules/custom-css/class-custom-css.php` (extend) |

---

## Phase 8 — Import / Export
| # | Commit Message | Files |
|---|---|---|
| 20 | `feat(tools): export all settings to JSON file` | `modules/tools/class-tools.php` |
| 21 | `feat(tools): import settings from uploaded JSON file` | `modules/tools/class-tools.php` (extend) |

---

## Phase 9 — Settings & Polish
| # | Commit Message | Files |
|---|---|---|
| 22 | `feat(settings): unified settings page with tabbed navigation` | `modules/settings/class-settings.php`, `views/settings-page.php` |
| 23 | `style: add admin CSS for settings pages and widget list` | `assets/css/admin.css` |
| 24 | `chore: finalize plugin header, version bump to 1.0.0` | `wordpress-white-label.php` |
