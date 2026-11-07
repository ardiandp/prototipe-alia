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
-   [ ] T05.3 Assignments.
-   [ ] T05.4 Submission monitoring.
-   [ ] T05.5 Quiz demo.
-   [ ] T05.6 Learning progress.
-   [ ] T06.1 Tahfidz programs/targets.
-   [ ] T06.2 Memorization records.
-   [ ] T06.3 Murojaah.
-   [ ] T06.4 Tahfidz progress.
-   [ ] T06.5 Diniyah subjects/schedules.
-   [ ] T06.6 Tahfidz report.
-   [ ] T07.1 Violations/guidance points.
-   [ ] T07.2 Achievements.
-   [ ] T07.3 Counseling (BK).
-   [ ] T07.4 Extracurricular.
-   [ ] T07.5 UKS.
-   [ ] T07.6 Student mutation.

## Batch E --- Boarding, finance, HRIS

-   [ ] T08.1 Boarding buildings/rooms.
-   [ ] T08.2 Resident assignment.
-   [ ] T08.3 Daily activity schedule.
-   [ ] T08.4 Leave permission workflow.
-   [ ] T08.5 Attendance/monitoring.
-   [ ] T08.6 Meals/consumption.
-   [ ] T08.7 Boarding reports.
-   [ ] T09.1 Finance dashboard.
-   [ ] T09.2 Fee master.
-   [ ] T09.3 Student invoices.
-   [ ] T09.4 Payment simulation.
-   [ ] T09.5 Scholarship/discount.
-   [ ] T09.6 Arrears.
-   [ ] T09.7 Cash in/out.
-   [ ] T09.8 Finance reports.
-   [ ] T10.1 Employee profiles/organization.
-   [ ] T10.2 Staff attendance.
-   [ ] T10.3 Leave/permission.
-   [ ] T10.4 Overtime.
-   [ ] T10.5 Performance evaluation.
-   [ ] T10.6 Payroll simulation.
-   [ ] T10.7 Competency development.

## Batch F --- Facilities, inventory, portals

-   [ ] T11.1 Facility registry.
-   [ ] T11.2 Room usage/booking.
-   [ ] T11.3 Maintenance/damage reports.
-   [ ] T11.4 Procurement requests.
-   [ ] T12.1 Item master.
-   [ ] T12.2 Stock receipt.
-   [ ] T12.3 Stock issue.
-   [ ] T12.4 Procurement simulation.
-   [ ] T12.5 Maintenance.
-   [ ] T12.6 Inventory reports.
-   [ ] T13.1 Parent Portal shell.
-   [ ] T13.2 Parent dashboard.
-   [ ] T13.3 Parent academic/attendance.
-   [ ] T13.4 Parent tahfidz/finance.
-   [ ] T13.5 Parent permission/announcements.
-   [ ] T13.6 Student Portal shell.
-   [ ] T13.7 Student dashboard/schedule.
-   [ ] T13.8 Student LMS pages.

## Batch G --- AI demo, reports, settings

-   [ ] T14.1 Chat interface.
-   [ ] T14.2 Predefined demo responses.
-   [ ] T14.3 Shared-data responses.
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
