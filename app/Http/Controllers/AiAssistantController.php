<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class AiAssistantController extends Controller
{
    /**
     * T14.1, T14.2, T14.3 - AI School Assistant Chat Interface & Intelligent Q&A
     */
    public function index(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        // Riwayat obrolan dari sesi demo
        $messages = session('demo_ai_chat_messages', $this->getDefaultGreeting());

        // Quick prompts rekomendasi
        $quickPrompts = [
            'Berapa total santri aktif di seluruh unit Alia saat ini?',
            'Bagaimana progres capaian Tahfidz Al-Qur\'an santri semester ini?',
            'Ringkasan arus kas keuangan dan status pembayaran SPP bulan ini',
            'Bagaimana tingkat kehadiran pegawai dan guru pekan ini?',
            'Berapa kamar asrama yang masih tersedia untuk santri baru?',
            'Apakah ada barang inventaris gudang yang stoknya menipis?',
            'Siapa saja santri peraih prestasi kejuaraan tingkat nasional?',
        ];

        return view('ai.assistant', compact('messages', 'quickPrompts'));
    }

    /**
     * Kirim Pesan & Generate Respon AI Berdasarkan Data Riil Sekolah
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = trim($validated['message']);
        $demo = DemoDataManager::getInstance();

        // 1. Simpan pesan pengguna ke sesi
        $messages = session('demo_ai_chat_messages', $this->getDefaultGreeting());
        $messages[] = [
            'sender' => 'user',
            'sender_name' => 'Anda (Pengguna Demo)',
            'time' => date('H:i'),
            'text' => $userMessage
        ];

        // 2. Analisis pertanyaan dan hasilkan respon berbasis data sekolah (T14.2 & T14.3)
        $aiReply = $this->generateAiResponse($userMessage, $demo);

        $messages[] = [
            'sender' => 'ai',
            'sender_name' => 'Alia AI School Assistant',
            'time' => date('H:i'),
            'text' => $aiReply['text'],
            'badge' => $aiReply['badge'] ?? null,
            'data_card' => $aiReply['data_card'] ?? null
        ];

        session(['demo_ai_chat_messages' => $messages]);

        return redirect()->route('ai.assistant')->with('success', 'Respon AI berhasil diperbarui!');
    }

    /**
     * Reset Riwayat Obrolan AI ke Kondisi Awal
     */
    public function resetChat()
    {
        session()->forget('demo_ai_chat_messages');
        return redirect()->route('ai.assistant')->with('info', 'Riwayat obrolan AI Assistant telah di-reset ke pengaturan awal.');
    }

    /**
     * Sambutan Awal AI Assistant
     */
    private function getDefaultGreeting(): array
    {
        return [
            [
                'sender' => 'ai',
                'sender_name' => 'Alia AI School Assistant',
                'time' => '07:30',
                'text' => "Assalamu'alaikum Warahmatullahi Wabarakatuh! 🌿\n\nSaya adalah **Alia AI School Assistant**, asisten cerdas terintegrasi untuk pimpinan Yayasan, Kepala Sekolah, dan staf manajemen **Alia Islamic School**.\n\nSaya dapat menganalisis data terpusat sekolah secara real-time: **Kesiswaan, Tahfidz, Keuangan, Asrama, SDM/HRIS, Sarpras, hingga Logistik Gudang**.\n\nAda yang dapat saya bantu analisis hari ini? Anda dapat memilih pertanyaan cepat di bawah atau mengetik pertanyaan Anda secara bebas.",
                'badge' => 'Asisten Cerdas Alia',
                'data_card' => null
            ]
        ];
    }

    /**
     * Logic Respon AI yang Cerdas dan Terkoneksi Data (T14.2 & T14.3)
     */
    private function generateAiResponse(string $query, DemoDataManager $demo): array
    {
        $q = strtolower($query);

        // 1. Topik: Santri / Siswa / Kesiswaan
        if (str_contains($q, 'santri') || str_contains($q, 'siswa') || str_contains($q, 'murid') || str_contains($q, 'total santri')) {
            $students = $demo->getStudents();
            $totalStudents = count($students);
            $boardingCount = count(array_filter($students, fn($s) => !empty($s['is_boarding'])));
            $nonBoardingCount = $totalStudents - $boardingCount;

            return [
                'text' => "Berdasarkan Buku Induk Kesiswaan Alia Islamic School saat ini:\n\n" .
                          "• **Total Santri Aktif**: {$totalStudents} orang santri terdaftar.\n" .
                          "• **Santri Mukim (Boarding)**: {$boardingCount} santri (asrama putra & putri).\n" .
                          "• **Santri Full Day (Non-Boarding)**: {$nonBoardingCount} santri.\n" .
                          "• **Tingkat Unit**: Tersebar pada jenjang SD Islam Alia, SMP Islam Alia, dan SMA Islam Alia Boarding School.\n\n" .
                          "Tingkat retensi santri semester ini mencapai **99.2%** dengan rasio kehadiran harian rata-rata **98.4%**.",
                'badge' => 'Data Kesiswaan Terpadu',
                'data_card' => [
                    'title' => 'Statistik Santri Alia',
                    'items' => [
                        'Total Santri' => $totalStudents . ' Santri',
                        'Boarding' => $boardingCount . ' Santri',
                        'Full Day' => $nonBoardingCount . ' Santri',
                        'Kehadiran Rata-rata' => '98.4%'
                    ]
                ]
            ];
        }

        // 2. Topik: Tahfidz Al-Qur'an & Diniyah
        if (str_contains($q, 'tahfidz') || str_contains($q, 'quran') || str_contains($q, 'juz') || str_contains($q, 'mutqin') || str_contains($q, 'halaqah')) {
            $students = $demo->getStudents();
            $totalJuz = array_sum(array_column($students, 'tahfidz_juz'));
            $topStudent = $students[0]['name'] ?? 'Santri';

            return [
                'text' => "Alhamdulillah, laporan capaian Program Unggulan **Tahfidz Al-Qur'an** menunjukkan akselerasi yang sangat baik:\n\n" .
                          "• **Akumulasi Hafalan Mutqin**: Santri sampel telah menghimpun total **{$totalJuz} Juz** hafalan mutqin bersanad.\n" .
                          "• **Halaqah Aktif**: Berjalan rutin ba'da Shubuh & ba'da Maghrib dibimbing musyrif bersertifikasi.\n" .
                          "• **Metode Ujian**: Menerapkan Tasmi' Bil Ghoib sekali duduk 1 - 5 Juz.\n" .
                          "• **Agenda Terdekat**: Wisuda Akbar Tahfidz dijadwalkan pada akhir semester ini di Aula Islamic Center.",
                'badge' => 'Capaian Tahfidz Quran',
                'data_card' => [
                    'title' => 'Ringkasan Tahfidz Santri',
                    'items' => [
                        'Total Hafalan' => $totalJuz . ' Juz Mutqin',
                        'Metode' => 'Ziyadah & Murojaah Mandiri',
                        'Predikat Dominan' => 'Mumtaz (Istimewa)',
                        'Target Kelulusan SMA' => 'Minimal 5 - 30 Juz'
                    ]
                ]
            ];
        }

        // 3. Topik: Keuangan, SPP, Arus Kas
        if (str_contains($q, 'uang') || str_contains($q, 'spp') || str_contains($q, 'kas') || str_contains($q, 'biaya') || str_contains($q, 'tagihan') || str_contains($q, 'bayar')) {
            $students = $demo->getStudents();
            $lunasCount = count(array_filter($students, fn($s) => ($s['spp_status'] ?? '') === 'lunas'));
            $belumLunasCount = count($students) - $lunasCount;
            $pctLunas = count($students) > 0 ? round(($lunasCount / count($students)) * 100, 1) : 0;

            return [
                'text' => "Laporan Keuangan & Rekapitulasi SPP Santri:\n\n" .
                          "• **Tingkat Kolektibilitas SPP**: **{$pctLunas}%** ({$lunasCount} santri lunas).\n" .
                          "• **Menunggu Pembayaran**: {$belumLunasCount} santri (surat tagihan telah otomatis diteruskan via WhatsApp Wali Santri).\n" .
                          "• **Sistem Pembayaran**: Seluruh transaksi terhubung dengan Virtual Account Bank Syariah Indonesia (BSI).\n" .
                          "• **Alokasi Subsidi & Beasiswa**: Program beasiswa tahfidz yatim dhuafa teralokasi stabil dari dana infak yayasan.",
                'badge' => 'Keuangan & SPP',
                'data_card' => [
                    'title' => 'Arus Kas & SPP Bulan Berjalan',
                    'items' => [
                        'Kolektibilitas SPP' => $pctLunas . '%',
                        'Status Lunas' => $lunasCount . ' Santri',
                        'Tunggakan Berjalan' => $belumLunasCount . ' Santri',
                        'Saluran VA' => 'BSI Virtual Account (Otomatis)'
                    ]
                ]
            ];
        }

        // 4. Topik: Pegawai, Guru, HRIS, Kehadiran
        if (str_contains($q, 'guru') || str_contains($q, 'pegawai') || str_contains($q, 'hris') || str_contains($q, 'karyawan') || str_contains($q, 'ustadz')) {
            $teachers = $demo->getTeachers();
            $totalTeachers = count($teachers);

            return [
                'text' => "Informasi Sumber Daya Manusia (SDM & Guru) Alia Islamic School:\n\n" .
                          "• **Total Pendidik & Tenaga Kependidikan**: {$totalTeachers} personil aktif tercatat di sistem HRIS.\n" .
                          "• **Tingkat Kehadiran Guru**: Pekan ini mencatatkan rerata presensi **98.2%**.\n" .
                          "• **Kualifikasi Pendidikan**: Mayoritas berpendidikan S1/S2 dan memiliki sertifikasi syahadah tahfidz untuk musyrif.\n" .
                          "• **Status Penggajian**: Payroll bulan berjalan telah diproses dan terdistribusi via rekening bank.",
                'badge' => 'SDM & Tenaga Pengajar',
                'data_card' => [
                    'title' => 'Statistik HRIS & Guru',
                    'items' => [
                        'Total Guru & Staf' => $totalTeachers . ' Personil',
                        'Presensi Pekanan' => '98.2% Tepat Waktu',
                        'Sistem Penggajian' => 'Payroll Terintegrasi',
                        'Pelatihan Mandiri' => 'Program Upgrading Rutin'
                    ]
                ]
            ];
        }

        // 5. Topik: Asrama, Kamar, Boarding, Perizinan
        if (str_contains($q, 'asrama') || str_contains($q, 'kamar') || str_contains($q, 'boarding') || str_contains($q, 'ranjang') || str_contains($q, 'izin')) {
            $rooms = $demo->getBoardingRooms();
            $buildings = $demo->getBoardingBuildings();
            $totalCapacity = array_sum(array_column($rooms, 'capacity'));

            return [
                'text' => "Data Terpadu Fasilitas Asrama Santri Alia:\n\n" .
                          "• **Gedung Asrama**: Terbagi atas Gedung Asrama Putra (Ali bin Abi Thalib & Umar bin Khattab) dan Putri (Khadijah & Maryam).\n" .
                          "• **Kapasitas Ranjang**: Tersedia {$totalCapacity} tempat tidur santri.\n" .
                          "• **Sistem Perizinan**: Alur izin pesiar/pulang diajukan oleh wali melalui Portal Orang Tua dan diverifikasi oleh Musyrif.\n" .
                          "• **Katering Pondok**: Menu 4 sehat 5 sempurna terjadwal 3 kali sehari bersertifikasi halal.",
                'badge' => 'Fasilitas & Hunian Asrama',
                'data_card' => [
                    'title' => 'Kapasitas Hunian Asrama',
                    'items' => [
                        'Total Gedung' => count($buildings) . ' Gedung Asrama',
                        'Total Kamar' => count($rooms) . ' Kamar',
                        'Total Bed' => $totalCapacity . ' Ranjang',
                        'Fasilitas' => 'AC, Lemari Pribadi, KM Dalam'
                    ]
                ]
            ];
        }

        // 6. Topik: Inventaris, Logistik, Gudang, Stok
        if (str_contains($q, 'inventaris') || str_contains($q, 'barang') || str_contains($q, 'stok') || str_contains($q, 'gudang') || str_contains($q, 'atk') || str_contains($q, 'logistik')) {
            $items = $demo->getInventoryItems();
            $totalItems = count($items);
            $lowStockItems = array_filter($items, fn($it) => ($it['stock'] ?? 0) <= ($it['min_stock'] ?? 5));
            $lowCount = count($lowStockItems);

            return [
                'text' => "Laporan Logistik & Inventaris Barang Sekolah:\n\n" .
                          "• **Total Katalog Barang**: {$totalItems} SKU terdaftar di 7 kategori aset.\n" .
                          "• **Peringatan Stok Minimum**: Terdapat **{$lowCount} barang** dengan stok fisik mendekati batas minimum buffer.\n" .
                          "• **Sistem Alur Keluar-Masuk**: Pencatatan inbound BASTB dan outbound bon pengeluaran BPPB tersinkronisasi otomatis.\n" .
                          "• **Pengadaan (PO)**: Pengadaan ATK dan perlengkapan asrama berjalan sesuai alur approval yayasan.",
                'badge' => 'Logistik & Inventaris',
                'data_card' => [
                    'title' => 'Monitoring Gudang Logistik',
                    'items' => [
                        'Total Jenis Barang' => $totalItems . ' SKU',
                        'Stok Minimum Alert' => $lowCount . ' Item Perlu Restock',
                        'Metode Valuasi' => 'FIFO Inventory',
                        'Kondisi Alat' => 'Mayoritas Layak Pakai Penuh'
                    ]
                ]
            ];
        }

        // 7. Topik: Prestasi, Penghargaan, Kejuaraan
        if (str_contains($q, 'prestasi') || str_contains($q, 'juara') || str_contains($q, 'lomba') || str_contains($q, 'kejuaraan')) {
            return [
                'text' => "Rekam Jejak Prestasi & Kejuaraan Santri Alia Islamic School:\n\n" .
                          "• **Bidang Al-Qur'an & MTQ**: Juara 1 MHQ 5 Juz Tingkat Provinsi Jawa Barat & Juara 2 Tilawah DKI Jakarta.\n" .
                          "• **Bidang Sains & Robotika**: Medali Emas Olimpiade Sains Nasional (OSN) Bidang Komputer & Robotika SMA.\n" .
                          "• **Bahasa & Pidato**: Juara Harapan Pidato Bahasa Arab & Inggris Tingkat Nasional Antar Pesantren Modern.\n\n" .
                          "Seluruh santri peraih medali mendapatkan beasiswa apresiasi SPP dari pihak Yayasan.",
                'badge' => 'Prestasi & Kejuaraan',
                'data_card' => [
                    'title' => 'Rekapitulasi Prestasi Santri',
                    'items' => [
                        'Kejuaraan MTQ' => 'Tingkat Provinsi & Nasional',
                        'Sains & Robotika' => 'Medali OSN Nasional',
                        'Sistem Apresiasi' => 'Poin Kehormatan & Beasiswa SPP',
                        'Target Semester Ini' => 'Kejuaraan Robotika ASEAN'
                    ]
                ]
            ];
        }

        // 8. Respon Default / Fallback Cerdas
        return [
            'text' => "Pertanyaan yang sangat baik mengenai: *\"{$query}\"*.\n\n" .
                      "Sistem AI Alia telah memindai basis data internal sekolah. Secara umum, seluruh indikator operasional **Alia Islamic School** berjalan stabil sesuai SOP kepesantrenan dan standar mutu yayasan.\n\n" .
                      "Anda dapat menanyakan hal yang lebih spesifik, seperti:\n" .
                      "1. Perkembangan akademik dan rapor santri\n" .
                      "2. Progres hafalan Tahfidz Al-Qur'an per halaqah\n" .
                      "3. Realisasi keuangan SPP dan kas sekolah\n" .
                      "4. Ketersediaan fasilitas sarpras dan kamar asrama\n" .
                      "5. Informasi stok inventaris dan pengadaan barang",
            'badge' => 'Analisis Sistem Terpadu',
            'data_card' => null
        ];
    }
}

