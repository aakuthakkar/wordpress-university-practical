# University Website and Programs Module

A custom WordPress theme for the University Website practical assignment.

## Requirements

- WordPress with Interactivity API and Script Modules support
- PHP and a working WordPress database
- Node.js and npm for building the React admin assets
- Apache configuration that protects the tracking log directory

## Installation

1. Install WordPress locally.
2. Copy the `university-theme` folder to `wp-content/themes/`.
3. Activate **University Theme** in Appearance → Themes.
4. Go to Settings → Permalinks and save the settings.
5. Import the supplied sample data if available.
6. Check that program featured images are available after import.

## Build the React admin assets

Run these commands from the repository root:

    npm install
    npm run build

The source files are in `university-theme/src/`. The compiled files are
committed in `university-theme/build/`. The `node_modules/` directory is
not committed.

## Theme structure

- `functions.php` — theme setup, Programs registration, metadata and tracking.
- `front-page.php` — home page.
- `archive-program.php` — programmes listing and filter.
- `taxonomy-program-type.php` — program type archive.
- `single-program.php` — program detail page.
- `template-parts/program-card.php` — reusable program card.
- `src/` — React admin source.
- `build/` — compiled React admin assets.
- `assets/` — frontend styles, scripts and images.

## Programs module

The `program` custom post type uses the `programmes` URL slug. The
`program-type` taxonomy contains Undergraduate and Postgraduate terms.

Program metadata includes Academic Year, Duration and repeatable
Content Sections. The admin interface is built with WordPress React
packages.

## Filtering

The programmes listing uses the WordPress Interactivity API to filter
programs without a page reload. The filter links also point to working
category URLs when JavaScript is unavailable.

## Click tracking

Tracked clicks are sent to the `programs/v1/track` REST endpoint. The
endpoint validates the request and appends JSON Lines using file locking.

The default daily log path is:

    wp-content/uploads/logs/YYYY-MM-DD/data-track.log

The log path is filterable with `university_tracking_log_path`. On the
local XAMPP Apache setup, direct access to the tested log URL returned
403 Forbidden. Server protection must also be checked on any other host.

The endpoint is public so that anonymous visitor clicks can be tracked.
It uses request-size and field validation and a basic rate limit.

Log files are not committed to Git and will be shared separately.

## Sample tracking events

Add at least 10 actual JSON log lines here before submission. Include
events from different elements.

{"timestamp":"2026-11-27T13:17:55+00:00","page_url":"\/wordpress\/programmes\/postgraduate\/","payload":{"type":"click","event":"link","link":{"label":"filter-all"},"content_data":{"sectionName":"programme-listing","uiComponent":"componentLink"}}}
{"timestamp":"2026-11-27T13:22:12+00:00","page_url":"\/wordpress\/programmes\/","payload":{"type":"click","event":"link","link":{"label":"filter-undergraduate"},"content_data":{"sectionName":"programme-listing","uiComponent":"componentLink"}}}
{"timestamp":"2026-11-27T13:22:13+00:00","page_url":"\/wordpress\/programmes\/undergraduate\/","payload":{"type":"click","event":"link","link":{"label":"program-card"},"content_data":{"sectionName":"programme-listing","uiComponent":"componentCard"}}}
{"timestamp":"2026-11-27T13:22:37+00:00","page_url":"\/wordpress\/programmes\/bachelor-of-business\/","payload":{"type":"click","event":"link","link":{"label":"apply-now"},"content_data":{"sectionName":"programme-overview","uiComponent":"componentLink"}}}
{"timestamp":"2026-11-27T13:22:39+00:00","page_url":"\/wordpress\/programmes\/bachelor-of-business\/","payload":{"type":"click","event":"button","button":{"label":"open-enquiry-form"},"content_data":{"sectionName":"programme-overview","uiComponent":"componentButton"}}}
{"timestamp":"2026-11-27T13:22:41+00:00","page_url":"\/wordpress\/programmes\/bachelor-of-business\/","payload":{"type":"click","event":"link","link":{"label":"back-to-programmes"},"content_data":{"sectionName":"programme-overview","uiComponent":"componentLink"}}}
{"timestamp":"2026-11-27T13:23:01+00:00","page_url":"\/wordpress\/programmes\/","payload":{"type":"click","event":"link","link":{"label":"program-card"},"content_data":{"sectionName":"programme-listing","uiComponent":"componentCard"}}}
{"timestamp":"2026-11-27T13:26:43+00:00","page_url":"\/wordpress\/programmes\/","payload":{"type":"click","event":"link","link":{"label":"program-card"},"content_data":{"sectionName":"programme-listing","uiComponent":"componentCard"}}}
{"timestamp":"2026-11-27T13:26:48+00:00","page_url":"\/wordpress\/programmes\/bachelor-of-business\/","payload":{"type":"click","event":"link","link":{"label":"apply-now"},"content_data":{"sectionName":"programme-overview","uiComponent":"componentLink"}}}

## Design and assumptions

- A classic theme was chosen for familiar PHP templates and custom
  theme development.
- The brief prohibits custom plugins, so the custom functionality is
  implemented in the theme.
- The brief requests both daily logs and the name `data-track.log`.
  This implementation uses a dated folder containing `data-track.log`.
- The lead form is a demonstration and does not currently send or save
  form data.
- Replace temporary screenshot-based images with original Figma assets
  where available.

## Git workflow

The required branch order is:

    main → uat → dev → feature/programs-module

Changes are developed on the feature branch. Pull requests must move
the work from feature to dev, then dev to uat, then uat to main.

## AI disclosure

AI assistance was used during planning and development. See the
accompanying AI prompts document for details.

## Plan vs Actual

Update this section before submission with any changes from the
original task plan and the reasons for those changes.

## Known limitations and final checks

Update this section after testing. Confirm all required tracking
elements, responsiveness, data import, REST validation, permissions,
and the log protection on the final hosting environment.