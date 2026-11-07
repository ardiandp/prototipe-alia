# AGENTS.md --- IRNIS School Prototype

## 1. Mission

Build a polished, demonstrable **IRNIS School Management System
prototype** for Alia Islamic School. This is a UI-first demo, not a
production school information system.

The repository already contains an existing Laravel project and an
AdminLTE **2.x** template. Work inside that project. Preserve its
current conventions and dependencies unless inspection proves a change
is necessary.

Primary goals: - Deliver coherent, navigable screens for the modules in
`IMPLEMENTATION_PLAN.md`. - Make tables, forms, filters, tabs, modals,
charts, and demo workflows interactive. - Use consistent Faker-generated
fictional data across modules. - Require no database, migrations, schema
design, or external service credentials. - Keep the interface visually
consistent with Alia Islamic School's blue/purple identity and Irnis Dev
branding.

## 2. Mandatory first action: inspect before changing

Before editing files: 1. Inspect repository tree, `composer.json`,
`package.json` if present, `routes/web.php`, existing controllers, Blade
layouts, public assets, and AdminLTE assets. 2. Identify Laravel, PHP,
AdminLTE, Bootstrap, jQuery, DataTables, Chart.js, Select2, and
SweetAlert versions actually installed. 3. Identify whether AdminLTE is
already integrated or only copied into the repository. 4. Identify
existing naming conventions, route structure, authentication
scaffolding, and CSS/JS build process. 5. Record findings in
`docs/PROGRESS.md`. 6. Do not overwrite, reinstall, upgrade, or replace
the existing AdminLTE package or frontend dependencies merely for
convenience.

AdminLTE 2 is commonly paired with Bootstrap 3 and jQuery, but **verify
the exact bundled versions**. Do not use Bootstrap 4/5 markup,
utilities, or APIs unless the inspected project explicitly includes
them.

## 3. Product scope and non-goals

### In scope

-   Dashboard views for Yayasan, Kepala Sekolah, Guru, Bendahara, and
    Asrama.
-   Master data, PPDB, Akademik, LMS, Tahfidz & Diniyah, Kesiswaan,
    Boarding, Finance, HRIS, Sarpras, Inventory, Parent/Student Portal,
    AI Assistant demo, Reports, and Settings.
-   Faker datasets with stable IDs and coherent cross-module references.
-   Session-backed prototype interactions where practical.
-   Search, filters, sorting, pagination, create/edit/detail/delete
    simulations.
-   Responsive layouts and demo-ready seeded content.
-   Print-friendly report views and simulated exports where feasible.

### Out of scope

-   Database schema, migrations, Eloquent persistence, production
    authentication/authorization.
-   Real payment gateway, WhatsApp, email, fingerprint, GPS attendance,
    or AI API integrations.
-   Real student/employee personal data.
-   Claims that a simulated feature is production-connected or secure.

Never add a database dependency or database-backed feature without
explicit approval.

## 4. Repository and implementation discipline

-   Keep the current Laravel structure; do not reorganize the whole
    repository.
-   Prefer small, focused changes and reusable Blade partials/components
    compatible with the installed Laravel version.
-   Follow existing PHP style and route/controller patterns.
-   Use named routes and route groups where consistent with the project.
-   Keep demo data generation separate from controllers and views.
-   Avoid huge controller methods and duplicated table/form markup.
-   Use `@csrf` and appropriate method spoofing for Laravel forms even
    when data is session-backed.
-   Escape user-entered output with Blade `{{ }}`. Use `{!! !!}` only
    for trusted, deliberately authored HTML.
-   Validate prototype form input server-side with Laravel validation
    where supported.
-   Do not put secrets or real personal data in the repository.
-   Do not add packages unless the existing stack cannot meet a
    requirement; explain any proposed package first.

## 5. AdminLTE 2 compatibility rules

-   Use the installed AdminLTE 2 layout and its existing asset paths.
-   Respect Bootstrap 3 grid and component conventions.
-   Verify plugin availability before referencing it.
-   Do not assume modern AdminLTE 3/4 classes such as `content-wrapper`
    variants, Bootstrap 5 utilities, or newer component APIs without
    checking the actual template.
-   Keep custom CSS namespaced (for example `.irnis-*`) to avoid
    collisions.
-   Use Font Awesome icon names supported by the installed Font Awesome
    version.
-   Check jQuery plugin initialization after partial/page loads.
-   Avoid loading jQuery or Bootstrap more than once.
-   Use the template's existing sidebar/header/footer where possible.

## 6. Brand and UX requirements

-   Product name: **IRNIS School Management System**.
-   Client/institution: **Alia Islamic School**.
-   Developer: **Irnis Dev**.
-   Main palette: navy/royal blue + purple/violet, with restrained
    neutral backgrounds.
-   Use supplied official Alia and Irnis Dev logos and school building
    image if those assets exist in the repository. If they are not
    present, use clearly identified placeholders; do not invent
    replacement logos.
-   UI language: Bahasa Indonesia.
-   Use consistent labels, breadcrumbs, page titles, status badges,
    empty states, validation messages, and action buttons.
-   Admin interface is desktop-first but must remain usable on
    tablet/mobile. Parent and Student Portals must be mobile-friendly.
-   Avoid excessive gradients, clutter, tiny text, and decorative
    controls that do nothing.

## 7. Demo data architecture

Create one central demo-data layer (for example `app/Support/DemoData/`)
and keep it compatible with the existing Laravel version.

Requirements: - Use FakerPHP already available through Laravel where
possible; verify dependency availability first. - Use deterministic
seeding (fixed Faker seed) so the demo starts with repeatable records. -
Generate stable IDs and consistent relationships in memory: students
reference valid units/classes/guardians; classes reference valid
teachers; invoices reference valid students; boarding residents
reference students. - Generate data once per demo session/request
lifecycle as appropriate; do not regenerate unrelated records on every
page render. - Keep datasets sufficiently sized for table
demonstrations, but paginate them. - Use fictional names, contact
details, addresses, and documents only. - Include a reset-demo action
that restores baseline data. - Prefer session storage for mutations
shared across pages during a demo. If the existing session driver
requires a database, inspect and configure a non-database session driver
only if safe for this existing project; do not silently alter production
configuration. - If persistence across browser sessions is not feasible
without a database, document the limitation.

Suggested providers (adapt to existing conventions): - `UnitData` -
`StudentData` - `StaffData` - `AcademicData` - `AdmissionData` -
`AttendanceData` - `TahfidzData` - `BoardingData` - `FinanceData` -
`InventoryData` - `DashboardData`

Avoid independent Faker generation in each controller/view. Cross-module
consistency is a core demo requirement.

## 8. Interaction requirements

Every visible control must have a meaningful action: - Add/Create opens
a form or modal. - View opens a detail page/modal. - Edit opens a
populated form. - Delete asks for confirmation and removes the demo
record from session state where implemented. -
Search/filter/sort/pagination affect visible rows. - Tabs switch
content. - Approval buttons change status and show feedback. - Chart
filters update chart data where practical. - Export/print buttons
perform a real local demo action or clearly state the feature is
simulated/unavailable.

Do not leave dead buttons or fake links (`href="#"`) without handlers.
Use accessible labels and clear feedback.

## 9. Cross-module workflows

Implement and verify these connected demo journeys: 1. PPDB applicant →
verification → selection → accepted → re-registration → student appears
in student list. 2. Student/class → teacher attendance and grade entry →
e-report → Parent Portal reflects the result. 3. Tahfidz entry → student
progress summary → Parent Portal progress. 4. Invoice → Parent Portal
invoice → simulated payment → invoice status and finance dashboard
update. 5. Boarding resident → exit permission → caregiver approval →
return status/history. 6. Employee leave request → approval → HRIS
summary. 7. Inventory receipt/issue → stock balance and inventory report
update.

Keep demo values coherent. If a workflow step is not implemented, label
it as a prototype limitation rather than implying completion.

## 10. Quality gates

After each phase: - Run relevant Laravel route/listing and syntax checks
available in the repository. - Run tests if a test suite exists; add
focused tests for critical demo-data/workflow logic when practical. -
Check browser console and server logs if runtime access is available. -
Check route names, broken links, missing assets, form validation, and
empty states. - Verify AdminLTE 2 compatibility. - Verify responsive
behavior. - Update `docs/PROGRESS.md` with completed tasks, changed
files, checks run, known issues, and next task.

Do not claim a check passed unless it was actually run.

## 11. Definition of done

A module is done only when: - Required list, create/edit, detail, and
relevant report views exist. - Faker data is present and coherent. -
Search/filter/pagination work where specified. - Form validation and
success/error feedback work. - All visible actions work or are
explicitly marked as simulated/unavailable. - Layout follows the
existing AdminLTE 2 template. - No database is required. - Responsive
and navigation checks are complete. - `docs/PROGRESS.md` is updated.

## 12. OpenCode working protocol

-   Treat `IMPLEMENTATION_PLAN.md` as the source of truth for task
    order.
-   Work on **one task at a time**, in sequence. Do not skip ahead.
-   Before each task, summarize the target files and acceptance
    criteria.
-   Inspect relevant files before editing.
-   Implement the smallest complete slice.
-   Run checks and report actual results.
-   Update `docs/PROGRESS.md`.
-   Stop and ask before introducing a database, changing major
    dependencies, replacing AdminLTE assets, or expanding scope.
-   If a requirement conflicts with the existing repository, explain the
    conflict and propose the least disruptive option.
