# Task Understanding — WordPress Practical Test

## Summary
I will build a responsive WordPress university website based on the supplied Figma design.
The site will include the home page, related programmes screen, lead form popup and thank-you state.
I will register a Programs custom post type and a hierarchical program-type taxonomy.
A custom React-based admin panel will manage program metadata and repeatable content sections.
Visitors will be able to browse, filter and view individual programmes using the specified URLs.
An event-tracking system will capture clicks and write validated events to a protected log file.
I will follow the required Git branch and pull-request workflow and provide setup documentation.

## Order and time estimates
| Order | Work | Estimate |
| --- | --- | --- |
| 1 | Review brief and Figma, prepare understanding document and recording | 45 minutes |
| 2 | Local WordPress, repository and branches | 30 minutes |
| 3 | Register post type, taxonomy and metadata | 60 minutes |
| 4 | React admin fields, repeatable sections and media selection | 75 minutes |
| 5 | Responsive design, programme cards, routing and detail templates | 90 minutes |
| 6 | Interactivity API filtering and click tracking | 75 minutes |
| 7 | Sample data, testing, documentation and pull requests | 105 minutes |
| | Total | 8 hours |

## Architecture and folder structure
I plan to use a classic theme because it supports familiar PHP templates and makes the required custom templates and reusable components straightforward. The ground rules prohibit custom plugins, so all functionality will be implemented in the theme unless clarified otherwise. No third-party WordPress plugins will be used.

```text
university-theme/
  style.css
  functions.php
  index.php
  front-page.php
  archive-program.php
  single-program.php
  taxonomy-program-type.php
  inc/                 # registration, metadata, routing, tracking
  template-parts/      # reusable program card, breadcrumbs
  src/                 # admin and frontend source files
  build/               # compiled assets and asset metadata
  assets/              # theme styles and images
  package.json
```

The structure may change as implementation requires. Source and built assets will both be committed, as requested; node_modules and log files will not be committed.

## Technical approach
**Build:** Translate the accessible Figma screens into responsive theme templates. Register the post type and taxonomy, configure the requested URLs, and use a reusable card template. Register REST-enabled metadata with schemas and use a custom-styled React editor compiled with @wordpress/scripts. The repeater will support adding, removing, reordering, expanding, collapsing and media selection. Sanitize input, escape output, and check nonces and capabilities as appropriate.

**Data tracking:** A reusable PHP helper will encode and escape tracking attributes. A delegated frontend click listener will send events asynchronously to a public REST endpoint, with strict schema and size validation, unknown-key removal, and additional abuse protections. The endpoint will append JSON lines with locking to a protected, filterable log path. It will never block navigation or log sensitive information. Server configuration must be verified to ensure the log is not publicly accessible.

**Interactivity API:** Use an Interactivity API store and directives on the programme listing to filter pre-rendered cards, update browser history, support back/forward navigation, and display loading and empty states. Real links will provide a functional fallback when JavaScript is unavailable. REST fetching is optional.

## Assumptions and questions
1. The brief prohibits custom plugins but also asks for theme/plugin separation. **Assumption:** implement all custom functionality in the theme. **Question:** Is that the expected interpretation?
2. The brief asks both for daily log files and a specific filename `uploads/logs/data-track.log`. **Assumption:** use the specified path until the preferred rotation/naming convention is confirmed, document the chosen approach and protect the directory. **Question:** Which naming convention takes precedence?
3. The brief does not specify a lead delivery destination or submission backend. **Assumption:** implement the designed popup and a clearly documented demonstration thank-you state unless submission handling is confirmed. **Question:** Is actual lead storage or email delivery required?
4. **Question:** Are the Figma file and its assets publicly accessible, and are there mobile designs or should the responsive layout be inferred?
5. **Assumption:** The Undergraduate and Postgraduate route segments are reserved and cannot also be programme slugs; conflicts will be handled or rejected.
6. WordPress and the web server must support the Interactivity API and script modules; the minimum tested versions will be documented after testing.

If answers do not arrive when needed, I will proceed with the stated assumptions and document them.

## Risks and priorities
The React repeater, URL conflicts, exact Figma implementation, server-level log protection and integration testing could take longer than estimated. If time runs short, I will omit optional bonuses first, including caching, JSON-LD, REST-based filtering, seeding, and automated log cleanup. I will prioritize mandatory functionality, security, testing, the required Git workflow, and clearly document any remaining gaps.
