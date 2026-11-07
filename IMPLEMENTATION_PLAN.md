# IMPLEMENTATION_PLAN.md --- IRNIS School Prototype

## 1. Purpose

This plan turns the IRNIS School Prototype PRD into ordered, testable
coding tasks for OpenCode.

**Repository state:** Existing Laravel project\
**Admin template:** AdminLTE 2.x (must inspect exact bundled versions)\
**Data:** Faker / fictional demo data\
**Persistence:** No database; session/in-memory prototype state only\
**UI language:** Bahasa Indonesia\
**Brand:** Alia Islamic School blue-purple + Irnis Dev

## 2. Global implementation rules

1.  Read `AGENTS.md` before coding.
2.  Inspect the current repository before making changes.
3.  Preserve existing Laravel conventions and AdminLTE 2 assets.
4.  Do not upgrade AdminLTE or Bootstrap.
5.  No migrations, schema, database models, or database-backed
    persistence.
6.  Use one shared demo-data source with stable IDs and cross-module
    consistency.
7.  Complete each task and its acceptance checks before moving on.
8.  Update `docs/PROGRESS.md` after every task.
9.  Every visible UI control must work or be explicitly marked as a
    prototype limitation.
10. Use only fictional data.

## 3. Task status convention

Use these status values in `PROGRESS.md`: - `NOT STARTED` -
`IN PROGRESS` - `BLOCKED` - `DONE`

A task is not DONE merely because its page renders. Its acceptance
criteria must pass.

------------------------------------------------------------------------

# PHASE 00 --- REPOSITORY AUDIT & FOUNDATION

## T00.1 --- Inspect existing project

-   Inspect folder tree and Laravel version.
-   Inspect PHP version constraints and Composer dependencies.
-   Inspect `routes/web.php`, controllers, middleware, Blade views,
    public assets.
-   Locate AdminLTE 2 assets and identify Bootstrap/jQuery/plugin
    versions.
-   Check current session driver and whether it requires a database.
-   Record findings and risks in `docs/PROGRESS.md`.

**Acceptance:** No code changes before the audit is recorded. Actual
versions and asset paths are documented.

## T00.2 --- Establish prototype constraints

-   Confirm no database/migration/model work is needed.
-   Determine safe session-backed demo state strategy.
-   Identify existing auth scaffolding; do not replace it.
-   Document unavailable plugins and alternatives.

**Acceptance:** A short architecture note exists and no dependency
upgrade is introduced.

## T00.3 --- Integrate/reuse AdminLTE 2 base layout

-   Reuse existing layout if present; otherwise create a minimal Blade
    layout using installed assets.
-   Create header, sidebar, content wrapper, breadcrumb, page title, and
    footer partials.
-   Add Irnis/Alia brand identity using available assets.
-   Add responsive custom styles with `.irnis-*` names.
-   Verify no duplicate jQuery/Bootstrap includes.

**Acceptance:** Base layout renders, navigation works, assets load, and
Bootstrap 3/AdminLTE 2 conventions are respected.

## T00.4 --- Build reusable UI partials

Create reusable patterns for: - Page header and breadcrumb. - Statistic
cards. - Search/filter toolbar. - Data table wrapper. - Status badges. -
Form fields and validation. - Modal confirmation. - Empty state. -
Alert/toast feedback. - Tabs and activity timeline.

**Acceptance:** At least one sample page demonstrates reusable
components; no modern Bootstrap-only markup is used.

## T00.5 --- Establish route and navigation skeleton

-   Define named route groups following existing project conventions.
-   Add sidebar groups for all planned modules.
-   Add placeholder pages for modules not yet implemented.
-   Ensure active menu state and breadcrumbs are correct.

**Acceptance:** All planned modules are reachable from navigation;
placeholder pages are clearly marked.

## T00.6 --- Demo data foundation

-   Verify Faker availability.
-   Create central demo data provider/manager.
-   Seed stable IDs using a fixed Faker seed.
-   Build shared datasets for units, academic years, classes, students,
    guardians, staff, subjects.
-   Provide reset-demo behavior.
-   Use session state for mutations if safe and compatible.

**Acceptance:** Data is repeatable, shared across pages, fictional, and
available without a database.

------------------------------------------------------------------------

# PHASE 01 --- DASHBOARD & EXECUTIVE VIEW

## T01.1 --- Yayasan dashboard

-   Cards: students, staff, units, classes, attendance, PPDB,
    collections, arrears.
-   Charts: students by unit/level, attendance, finance, admissions.
-   Recent activity, announcements, pending approvals, academic
    calendar.
-   Unit and academic-year filters.

## T01.2 --- Role dashboard variants

Create dashboard variants for: - Kepala Sekolah - Guru - Bendahara -
Pengasuh Asrama

Use a demo role selector if existing authentication is not suitable.
Clearly label role switching as demo-only.

**Acceptance for Phase 01:** Dashboard numbers derive from shared demo
data; filters update relevant widgets; role variants have relevant
content; no chart is purely decorative.

------------------------------------------------------------------------

# PHASE 02 --- MASTER DATA SEKOLAH

## T02.1 --- Unit pendidikan

List, create/edit form, detail, status, search, filter.

## T02.2 --- Tahun ajaran and semester

List and form; active-year indicator.

## T02.3 --- Data kelas / rombongan belajar

List, class detail, unit/level/year filters, wali kelas, capacity,
student count.

## T02.4 --- Data ruangan

List, create/edit, type, location, capacity, status.

## T02.5 --- Mata pelajaran

List, create/edit, group, level, target/KKM.

## T02.6 --- Data siswa

-   Student table with NIS/NISN, name, unit, level, class, status.
-   Search and filters.
-   Create/edit/detail.
-   Detail tabs: identity, guardian, academic, attendance, tahfidz,
    finance, boarding.
-   Fictional photo/avatar and contact details.

## T02.7 --- Data guru dan pegawai

-   Staff list, create/edit/detail.
-   Position, unit, department, employment status.
-   Profile summary and activity.

## T02.8 --- Data orang tua/wali

-   Guardian list and detail.
-   Link to one or more demo students.
-   Contact information is fictional.

**Acceptance for Phase 02:** Master records use shared stable IDs.
Student, class, teacher, guardian, and unit references remain coherent.

------------------------------------------------------------------------

# PHASE 03 --- PPDB ONLINE

## T03.1 --- PPDB dashboard

Cards, charts, applicant funnel, status counts, unit/level filters.

## T03.2 --- Applicant list and detail

Search, filters, status badges, applicant profile, document checklist.

## T03.3 --- Applicant registration form

Personal, guardian, previous school, target unit/level, contact,
document placeholders.

## T03.4 --- Document verification

Checklist and status transitions for fictional documents.

## T03.5 --- Selection workflow

Administrative review, test/interview schedule, result entry, decision
status.

## T03.6 --- Announcement and re-registration

Accepted/waitlisted/rejected states; simulated re-registration and
payment status.

## T03.7 --- Convert accepted applicant to student

Create a student demo record from applicant data using a stable new ID;
show the student in Master Data.

**Acceptance for Phase 03:** End-to-end applicant-to-student journey
works without database persistence.

------------------------------------------------------------------------

# PHASE 04 --- AKADEMIK & PEMBELAJARAN

## T04.1 --- Class and learning-group management

Class roster, homeroom teacher, subjects, capacity, academic year.

## T04.2 --- Timetable

Table and weekly calendar views; day, time, class, subject, teacher,
room.

## T04.3 --- Academic calendar

Events, holidays, exams, report dates, school activities.

## T04.4 --- Student attendance

Daily class attendance, statuses Hadir/Izin/Sakit/Alpha/Terlambat,
summary and monthly view.

## T04.5 --- Grade input

Teacher selects class/subject/assessment; editable grade table;
validation and save feedback.

## T04.6 --- E-report

Student report detail, subject grades, attendance, homeroom notes,
extracurricular summary.

## T04.7 --- Promotion/grade advancement simulation

Student list, grade summary, recommendation, simulated decision.

**Acceptance for Phase 04:** Attendance and grade changes are reflected
in relevant summaries and Parent Portal demo views.

------------------------------------------------------------------------

# PHASE 05 --- LMS / E-LEARNING

## T05.1 --- Digital classes

Class list, teacher, subject, participants.

## T05.2 --- Learning materials

Create/edit/list/detail, attachment placeholder, publication state.

## T05.3 --- Assignments

Create assignment, class, subject, deadline, description.

## T05.4 --- Submission monitoring

Student submissions, timestamp, attachment placeholder, grading status.

## T05.5 --- Quiz demo

Quiz list, question preview, duration, score summary; no real exam
engine required unless easy within current scope.

## T05.6 --- Learning progress

Completion, pending tasks, quiz results.

**Acceptance for Phase 05:** Teacher can create a demo assignment and
Student Portal can display it.

------------------------------------------------------------------------

# PHASE 06 --- TAHFIDZ & DINIYAH

## T06.1 --- Tahfidz programs and targets

Program, level, target, period, supervisor.

## T06.2 --- Student memorization records

Student, surah, ayat range, date, supervisor, fluency/quality, notes.

## T06.3 --- Murojaah records

Schedule, repetitions, evaluation, supervisor notes.

## T06.4 --- Tahfidz progress

Progress bars, summary cards, chart, student-level detail.

## T06.5 --- Diniyah subjects and schedules

Subject, teacher, class, timetable.

## T06.6 --- Tahfidz report

Student summary, memorization progress, quality, discipline, supervisor
comments.

**Acceptance for Phase 06:** Adding a memorization record updates the
student's progress summary and parent-facing view.

------------------------------------------------------------------------

# PHASE 07 --- KESISWAAN & PEMBINAAN

## T07.1 --- Student violations and guidance points

Record, severity, points, reporter, action, status.

## T07.2 --- Student achievements

Achievement type, level, organizer, date, documentation placeholder.

## T07.3 --- Counseling (BK)

Fictional records, restricted-detail demo presentation, follow-up
status.

## T07.4 --- Extracurricular

Activities, coach, schedule, participants, attendance, evaluation.

## T07.5 --- UKS

Visit log, date, complaint, handling, staff; use fictional non-sensitive
examples.

## T07.6 --- Student transfer/mutation

Incoming/outgoing status, date, reason, document placeholder.

**Acceptance for Phase 07:** Relevant activities appear in the student
detail timeline/summary.

------------------------------------------------------------------------

# PHASE 08 --- ASRAMA / BOARDING

## T08.1 --- Boarding buildings and rooms

Building list, room list, capacity, supervisor, status.

## T08.2 --- Resident assignment

Assign student to building/room; occupancy count updates.

## T08.3 --- Daily activity schedule

Prayer, tahfidz, meals, study, cleaning, rest.

## T08.4 --- Leave permission workflow

Create request, reason, dates, approval by caregiver, return
confirmation, history.

## T08.5 --- Attendance and daily monitoring

Presence, lateness, absence, activity participation.

## T08.6 --- Meal schedule and consumption

Menu, meal schedule, resident count, summary.

## T08.7 --- Boarding reports

Residents, room occupancy, leave status, attendance, activity.

**Acceptance for Phase 08:** Room occupancy and leave approval statuses
update in the demo UI.

------------------------------------------------------------------------

# PHASE 09 --- KEUANGAN & SPP

## T09.1 --- Finance dashboard

Collections, expenses, balance, arrears, daily payments, unit filter.

## T09.2 --- Fee master

SPP, admission, re-registration, activities, boarding, uniforms, other
fees.

## T09.3 --- Student invoices

Create/list/detail invoice, due date, amount, status.

## T09.4 --- Payment simulation

Record payment, method, date, receipt placeholder, paid/partial/unpaid
status.

## T09.5 --- Scholarship and discount

Student, discount type, amount, period, approval.

## T09.6 --- Arrears

Outstanding amount, aging, filters, student detail.

## T09.7 --- Cash in/out

Category, date, description, amount, proof placeholder.

## T09.8 --- Finance reports

Collections, arrears, cash flow, unit and period summaries.

**Acceptance for Phase 09:** Invoice/payment changes update finance
dashboard and Parent Portal. Clearly label payments as simulated.

------------------------------------------------------------------------

# PHASE 10 --- SDM / HRIS

## T10.1 --- Employee profiles and organization

Employee list, detail, unit, department, position, employment status,
organization chart.

## T10.2 --- Staff attendance

Clock-in/out, status, monthly recap, simulated QR/GPS indicator.

## T10.3 --- Leave and permission

Request form, date range, reason, approval and status.

## T10.4 --- Overtime

Employee, date, duration, description, approval.

## T10.5 --- Performance evaluation

Indicators, targets, results, reviewer, period, score.

## T10.6 --- Payroll simulation

Salary components, allowance, deduction, incentive, net amount, payment
status.

## T10.7 --- Competency development

Training, certification, workshop, history.

**Acceptance for Phase 10:** Leave approval and payroll summary
workflows work with fictional data.

------------------------------------------------------------------------

# PHASE 11 --- SARANA PRASARANA

## T11.1 --- Facility registry

Facility code, name, location, condition, owner, status.

## T11.2 --- Room usage and booking

Room, requester, date/time, purpose, approval.

## T11.3 --- Maintenance and damage reports

Report, priority, date, assigned person, progress, completion.

## T11.4 --- Procurement requests

Request, items, quantity, requester, approval state.

**Acceptance:** Facility status and maintenance progress can be updated
and reported.

------------------------------------------------------------------------

# PHASE 12 --- INVENTARIS

## T12.1 --- Item master

Code, name, category, unit, location, stock, condition.

## T12.2 --- Stock receipt

Date, item, quantity, source, officer.

## T12.3 --- Stock issue

Date, item, quantity, destination, officer.

## T12.4 --- Procurement simulation

Request, approval, purchase order preview, receipt.

## T12.5 --- Maintenance

Item, date, condition, action, cost.

## T12.6 --- Inventory reports

Stock balance, receipts, issues, movements, asset condition.

**Acceptance:** Stock quantity changes consistently after receipt/issue
actions.

------------------------------------------------------------------------

# PHASE 13 --- PARENT & STUDENT PORTALS

## T13.1 --- Parent Portal shell

Separate responsive layout, child switcher, navigation, profile menu.

## T13.2 --- Parent dashboard

Child profile, attendance, grades, tahfidz, invoices, announcements,
calendar.

## T13.3 --- Parent academic and attendance views

Grades, report, attendance history.

## T13.4 --- Parent tahfidz and finance views

Memorization progress, invoices, payment history, simulated payment
state.

## T13.5 --- Parent permission and announcements

Permission request status, school announcements, messages demo.

## T13.6 --- Student Portal shell

Separate mobile-friendly layout.

## T13.7 --- Student dashboard and schedule

Schedule, assignments, grades, attendance, tahfidz, announcements.

## T13.8 --- Student LMS pages

Materials, tasks, submissions, quiz summaries.

**Acceptance:** Parent/student screens reflect the same selected demo
student and shared data as admin modules.

------------------------------------------------------------------------

# PHASE 14 --- AI SCHOOL ASSISTANT

## T14.1 --- Chat interface

History, message list, input, send, loading state, suggested prompts.

## T14.2 --- Predefined demo responses

Prepare responses for student count, low attendance, academic summary,
SPP recap, tahfidz progress, announcement draft.

## T14.3 --- Shared demo data answers

Where practical, derive response values from current demo data rather
than hardcoding contradictory totals.

**Acceptance:** Chat interaction works without external AI API and is
labelled as AI demo/simulation.

------------------------------------------------------------------------

# PHASE 15 --- REPORTS & ANALYTICS

## T15.1 --- Report center

Categories and report navigation.

## T15.2 --- Academic reports

Students, grades, attendance, promotion.

## T15.3 --- PPDB reports

Applicants, funnel, conversion.

## T15.4 --- Finance reports

Collections, arrears, expenses, cash flow.

## T15.5 --- HRIS reports

Staff, attendance, leave, payroll.

## T15.6 --- Tahfidz and boarding reports

Progress, residents, attendance, permissions.

## T15.7 --- Inventory and facility reports

Stock, movement, asset condition, maintenance.

## T15.8 --- Export/print

Implement print-friendly pages and local CSV export if feasible. Do not
imply a real Excel/PDF export if only browser print is implemented.

**Acceptance:** Filters work and printed/exported content matches the
displayed report.

------------------------------------------------------------------------

# PHASE 16 --- SYSTEM SETTINGS

## T16.1 --- Institution profile

School name, address, contact, logos.

## T16.2 --- Unit and academic year settings

View/edit demo configuration.

## T16.3 --- Demo role switcher

Select a demo role and show the appropriate menu/dashboard. Do not
present this as production access control.

## T16.4 --- Notification preferences demo

UI toggles and sample notification settings.

## T16.5 --- Demo data reset

Restore deterministic baseline data and confirm reset.

**Acceptance:** Reset action is confirmed, executes, and returns demo
data to baseline.

------------------------------------------------------------------------

# PHASE 17 --- INTEGRATION, RESPONSIVE QA & DEMO READINESS

## T17.1 --- Navigation and route audit

Verify all sidebar links, breadcrumbs, active states, and named routes.

## T17.2 --- Form audit

Verify validation, required fields, cancel/save actions, feedback, and
populated edit forms.

## T17.3 --- Table audit

Verify search, filters, sorting, pagination, empty states, and action
menus.

## T17.4 --- Workflow audit

Run all seven cross-module workflows described in `AGENTS.md`.

## T17.5 --- Data consistency audit

Check shared student, class, guardian, staff, invoice, room, and
inventory IDs.

## T17.6 --- AdminLTE 2 compatibility audit

Check Bootstrap 3 markup, plugin initialization, icon compatibility,
duplicate scripts, and asset paths.

## T17.7 --- Responsive audit

Desktop, laptop, tablet, and mobile. Prioritize mobile portal usability.

## T17.8 --- Error and performance audit

Check Laravel logs, browser console, missing assets, excessive Faker
regeneration, and slow tables.

## T17.9 --- Demo walkthrough

Prepare a scripted walkthrough: 1. Yayasan dashboard. 2. PPDB applicant
to student. 3. Academic attendance and grades. 4. Tahfidz progress. 5.
Boarding permission. 6. Invoice and simulated payment. 7. Parent Portal.
8. AI Assistant. 9. Executive reports.

**Acceptance:** No critical broken navigation/workflow, no unlabelled
fake integrations, and progress documentation reflects actual results.

------------------------------------------------------------------------

## 4. Suggested execution batches

For manageable OpenCode sessions, use these batches:

  Batch   Tasks          Goal
  ------- -------------- -----------------------------
  A       T00.1--T00.6   Audit and foundation
  B       T01.1--T02.8   Dashboard and master data
  C       T03.1--T04.7   PPDB and academic
  D       T05.1--T07.6   LMS, Tahfidz, Kesiswaan
  E       T08.1--T10.7   Boarding, Finance, HRIS
  F       T11.1--T13.8   Sarpras, Inventory, Portals
  G       T14.1--T16.5   AI demo, reports, settings
  H       T17.1--T17.9   Integration and QA

Do not start the next batch until the previous batch's acceptance
criteria are met.

## 5. OpenCode task prompt template

Use this prompt for each task:

> Read `AGENTS.md`, `IMPLEMENTATION_PLAN.md`, and `docs/PROGRESS.md`.
> Work only on task **\[TASK ID --- TASK NAME\]**. First inspect the
> relevant existing files and summarize the implementation approach and
> files to change. Preserve the existing Laravel project and AdminLTE 2
> dependencies. Do not add database schema, migrations, or database
> persistence. Use the shared Faker demo-data architecture. Implement
> the complete task, run relevant checks, report actual results and
> known issues, then update `docs/PROGRESS.md`. Do not start another
> task.

## 6. First OpenCode instruction

Start with **T00.1 --- Inspect existing project** only. Do not begin
implementation until the repository audit is documented and the actual
AdminLTE 2 asset/dependency versions are known.
