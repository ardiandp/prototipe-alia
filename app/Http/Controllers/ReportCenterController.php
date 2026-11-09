<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\DemoData\DemoDataManager;

class ReportCenterController extends Controller
{
    /**
     * T15.1 - T15.8: Pusat Laporan & Ekspor Terpadu Alia Islamic School
     */
    public function index(Request $request)
    {
        $demo = DemoDataManager::getInstance();

        $activeTab = $request->get('tab', 'akademik'); // akademik, ppdb, keuangan, hris, tahfidz_asrama, sarpras_logistik
        $format = $request->get('format'); // print, export_excel_sim

        // 1. Data Ringkasan Eksekutif
        $students = $demo->getStudents();
        $teachers = $demo->getTeachers();
        $ppdb = $demo->getPpdbApplicants();
        $items = $demo->getInventoryItems();
        $rooms = $demo->getBoardingRooms();
        $maintenances = $demo->getInventoryMaintenances();

        // Metrik
        $totalStudents = count($students);
        $totalTeachers = count($teachers);
        $totalPpdbLulus = count(array_filter($ppdb, fn($p) => ($p['status_kelulusan'] ?? '') === 'lulus'));
        $totalAssetValue = array_sum(array_map(fn($it) => ($it['stock'] ?? 0) * ($it['unit_price'] ?? 0), $items));
        $totalTahfidzJuz = array_sum(array_column($students, 'tahfidz_juz'));

        // Jika request format simulasi export Excel/CSV
        if ($format === 'excel') {
            return redirect()->back()->with('success', "Simulasi ekspor file laporan (format .xlsx / spreadsheet) untuk modul \"{$activeTab}\" berhasil diunduh!");
        }

        return view('laporan.index', compact(
            'activeTab',
            'students',
            'teachers',
            'ppdb',
            'items',
            'rooms',
            'maintenances',
            'totalStudents',
            'totalTeachers',
            'totalPpdbLulus',
            'totalAssetValue',
            'totalTahfidzJuz'
        ));
    }

    /**
     * T15.8: Lembar Cetak Dokumen Eksekutif Berkop Alia
     */
    public function printReport(Request $request, string $module)
    {
        $demo = DemoDataManager::getInstance();
        $students = $demo->getStudents();
        $teachers = $demo->getTeachers();
        $ppdb = $demo->getPpdbApplicants();
        $items = $demo->getInventoryItems();

        return view('laporan.print', compact(
            'module',
            'students',
            'teachers',
            'ppdb',
            'items'
        ));
    }
}

