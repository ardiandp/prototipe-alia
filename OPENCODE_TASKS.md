# OPENCODE_TASKS.md --- Ordered Coding Task Queue

This is the short execution queue. The detailed scope and acceptance
criteria live in `IMPLEMENTATION_PLAN.md`.

## Operating rule

Execute one task at a time, in order. After each task: run checks,
update `docs/PROGRESS.md`, report changed files and blockers, then stop.

## Batch A --- Foundation

-   [x] T00.1 Inspect existing project.
-   [x] T00.2 Establish prototype constraints.
-   [x] T00.3 Reuse/integrate AdminLTE 2 base layout.
-   [x] T00.4 Build reusable UI partials.
-   [x] T00.5 Establish route and navigation skeleton.
-   [x] T00.6 Build deterministic shared Faker demo data.

## Batch B --- Dashboard and master data

-   [x] T01.1 Yayasan dashboard.
-   [x] T01.2 Role dashboard variants.
-   [x] T02.1 Unit pendidikan.
-   [x] T02.2 Tahun ajaran and semester.
-   [x] T02.3 Kelas / rombongan belajar.
-   [x] T02.4 Ruangan.
-   [x] T02.5 Mata pelajaran.
-   [x] T02.6 Data siswa.
-   [x] T02.7 Guru dan pegawai.
-   [x] T02.8 Orang tua/wali.

## Batch C --- PPDB and academics

-   [x] T03.1 PPDB dashboard.
-   [x] T03.2 Applicant list/detail.
-   [x] T03.3 Registration form.
-   [x] T03.4 Document verification.
-   [x] T03.5 Selection workflow.
-   [x] T03.6 Announcement/re-registration.
-   [x] T03.7 Convert applicant to student.
-   [x] T04.1 Class/learning-group management.
-   [x] T04.2 Timetable.
-   [x] T04.3 Academic calendar.
-   [x] T04.4 Student attendance.
-   [x] T04.5 Grade input.
-   [x] T04.6 E-report.
-   [x] T04.7 Promotion simulation.

## Batch D --- Learning, Islamic programs, student affairs

-   [x] T05.1 Digital classes.
-   [x] T05.2 Learning materials.
-   [x] T05.3 Assignments.
-   [x] T05.4 Submission monitoring.
-   [x] T05.5 Quiz demo.
-   [x] T05.6 Learning progress.
-   [x] T06.1 Tahfidz programs/targets.
-   [x] T06.2 Memorization records.
-   [x] T06.3 Murojaah.
-   [x] T06.4 Tahfidz progress.
-   [x] T06.5 Diniyah subjects/schedules.
-   [x] T06.6 Tahfidz report.
-   [x] T07.1 Violations/guidance points.
-   [x] T07.2 Achievements.
-   [x] T07.3 Counseling (BK).
-   [x] T07.4 Extracurricular.
-   [x] T07.5 UKS.
-   [x] T07.6 Student mutation.

## Batch E --- Boarding, finance, HRIS

-   [x] T08.1 Boarding buildings/rooms.
-   [x] T08.2 Resident assignment.
-   [x] T08.3 Daily activity schedule.
-   [x] T08.4 Leave permission workflow.
-   [x] T08.5 Attendance/monitoring.
-   [x] T08.6 Meals/consumption.
-   [x] T08.7 Boarding reports.
-   [x] T09.1 Finance dashboard.
-   [x] T09.2 Fee master.
-   [x] T09.3 Student invoices.
-   [x] T09.4 Payment simulation.
-   [x] T09.5 Scholarship/discount.
-   [x] T09.6 Arrears.
-   [x] T09.7 Cash in/out.
-   [x] T09.8 Finance reports.
-   [x] T10.1 Employee profiles/organization.
-   [x] T10.2 Staff attendance.
-   [x] T10.3 Leave/permission.
-   [x] T10.4 Overtime.
-   [x] T10.5 Performance evaluation.
-   [x] T10.6 Payroll simulation.
-   [x] T10.7 Competency development.

## Batch F --- Facilities, inventory, portals

-   [x] T11.1 Facility registry.
-   [x] T11.2 Room usage/booking.
-   [x] T11.3 Maintenance/damage reports.
-   [x] T11.4 Procurement requests.
-   [x] T12.1 Item master.
-   [x] T12.2 Stock receipt.
-   [x] T12.3 Stock issue.
-   [x] T12.4 Procurement simulation.
-   [x] T12.5 Maintenance.
-   [x] T12.6 Inventory reports.
-   [x] T13.1 Parent Portal shell.
-   [x] T13.2 Parent dashboard.
-   [x] T13.3 Parent academic/attendance.
-   [x] T13.4 Parent tahfidz/finance.
-   [x] T13.5 Parent permission/announcements.
-   [x] T13.6 Student Portal shell.
-   [x] T13.7 Student dashboard/schedule.
-   [x] T13.8 Student LMS pages.

## Batch G --- AI demo, reports, settings

-   [x] T14.1 Chat interface.
-   [x] T14.2 Predefined demo responses.
-   [x] T14.3 Shared-data responses.
-   [ ] T15.1 Report center.
-   [ ] T15.2 Academic reports.
-   [ ] T15.3 PPDB reports.
-   [ ] T15.4 Finance reports.
-   [ ] T15.5 HRIS reports.
-   [ ] T15.6 Tahfidz/boarding reports.
-   [ ] T15.7 Inventory/facility reports.
-   [ ] T15.8 Export/print.
-   [ ] T16.1 Institution profile.
-   [ ] T16.2 Unit/year settings.
-   [ ] T16.3 Demo role switcher.
-   [ ] T16.4 Notification preferences.
-   [ ] T16.5 Demo data reset.

## Batch H --- Final QA

-   [ ] T17.1 Navigation/route audit.
-   [ ] T17.2 Form audit.
-   [ ] T17.3 Table audit.
-   [ ] T17.4 Cross-module workflow audit.
-   [ ] T17.5 Data consistency audit.
-   [ ] T17.6 AdminLTE 2 compatibility audit.
-   [ ] T17.7 Responsive audit.
-   [ ] T17.8 Error/performance audit.
-   [ ] T17.9 Demo walkthrough.

## First instruction to OpenCode

Start with **T00.1 only**. Inspect the existing repository and record
exact versions and conventions. Do not start building screens until the
audit is complete.
