# Architecture Note: IRNIS School Prototype (Alia Islamic School)

## 1. Prototype Core Principles
- **No-Database Boundary**: Aplikasi berjalan sepenuhnya tanpa koneksi database, skema, tabel, migration, atau Eloquent persistent database models.
- **Session-Backed Demo State**: Mutasi data prototipe disimpan pada session Laravel (`SESSION_DRIVER=file`). Perubahan state (tambah data, ubah data, approval, filter, hapus) bertahan selama demonstrasi berlangsung antar navigasi halaman.
- **Deterministic Seeded Data**: Inisialisasi data menggunakan Faker (`id_ID`) dengan deterministic seed agar seluruh relasi entitas (Siswa, Kelas, Wali, Guru, Tagihan, Asrama, Tahfidz) memiliki ID yang stabil dan konsisten di setiap modul dan portal.
- **Demo State Reset**: Disediakan endpoint/tombol reset session untuk mengembalikan demo data ke kondisi awal (baseline deterministic seed).

## 2. Authentication & Role Scaffolding
- Sesuai PRD dan AGENTS.md, tidak menggunakan auth database atau kompleks OAuth.
- Pengalihan peran (Yayasan, Kepala Sekolah, Guru, Bendahara, Pembina Asrama, Wali Siswa) dilakukan melalui fitur switcher/impersonasi peran di session (contoh: dropdown switcher di header navigasi).

## 3. Dependency & Template Compatibility
- **AdminLTE**: 2.4.2 (berbasis Bootstrap 3.3.7 dan jQuery 3.2.1).
- **Styling**: Tidak mencampur kelas Bootstrap 4/5 (`d-flex`, `me-*`, `ms-*`, `fs-*`, dll dilarang). Menggunakan helper Bootstrap 3 (`pull-left`, `pull-right`, `clearfix`, `.row`, `.col-md-*`) dan custom utility namespaced `.irnis-*`.
- **Palet Warna**: Identitas Alia Islamic School (Royal Blue `#1a365d` / `#2b6cb0` dan Ungu/Violet `#44337a` / `#6b46c1`).
- **Komponen Feedback**: Karena SweetAlert tidak terpasang, feedback interaktif menggunakan modal konfirmasi standar Bootstrap 3 (`#modal-confirm`), flash alerts dismissible, dan banner status.

