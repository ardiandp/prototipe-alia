@extends('layouts.app')

@section('title', 'Master Data Unit Pendidikan | IRNIS School Management System - Alia Islamic School')

@section('content')
<div class="row">
    <div class="col-md-12">
        {{-- Hero Header Alia Islamic --}}
        <div class="box box-solid" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 20px; border-top: none;">
            <div class="box-body" style="background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 60%, #6b46c1 100%); color: #ffffff; padding: 25px 30px;">
                <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
                    <div class="col-sm-8">
                        <span class="badge" style="background: rgba(255,255,255,0.2); font-weight: 600; padding: 5px 12px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fa fa-graduation-cap"></i> Struktur Jenjang Pendidikan
                        </span>
                        <h2 style="margin: 5px 0 10px 0; font-weight: 700; font-size: 26px; color: #ffffff;">
                            Unit Pendidikan Alia Islamic School
                        </h2>
                        <p style="margin: 0; font-size: 14px; opacity: 0.92; line-height: 1.6; max-width: 680px;">
                            Pusat tata kelola entitas akademik multi-jenjang dari tingkat dasar hingga menengah atas berasrama (boarding). Mengatur akreditasi, kepala madrasah/sekolah, kurikulum unggulan, serta kapasitas santri terintegrasi.
                        </p>
                    </div>
                    <div class="col-sm-4 text-right" style="padding-top: 10px;">
                        <button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#modalTambahUnit" style="font-weight: 600; box-shadow: 0 3px 10px rgba(0,0,0,0.2); border: none;">
                            <i class="fa fa-plus-circle"></i> Tambah Unit Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" style="border-radius: 6px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check-circle"></i> <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" style="border-radius: 6px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-exclamation-triangle"></i> <strong>Peringatan!</strong> {{ session('error') }}
            </div>
        @endif

        {{-- Statistics Row --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid #3c8dbc;">
                    <span class="info-box-icon bg-aqua" style="border-radius: 6px 0 0 6px;"><i class="fa fa-university"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted" style="font-size: 12px; font-weight: 600;">TOTAL UNIT KAMPUS</span>
                        <span class="info-box-number" style="font-size: 24px; font-weight: 700; color: #1a365d;">{{ count($units) }} <small style="font-size: 14px; color: #777;">Satuan</small></span>
                        <div class="progress" style="height: 3px; margin: 5px 0;">
                            <div class="progress-bar bg-aqua" style="width: 100%"></div>
                        </div>
                        <span class="progress-description" style="font-size: 11px; color: #666;">
                            SD, SMP, &amp; SMA Boarding
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid #00a65a;">
                    <span class="info-box-icon bg-green" style="border-radius: 6px 0 0 6px;"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted" style="font-size: 12px; font-weight: 600;">SANTRI TERDAFTAR</span>
                        <span class="info-box-number" style="font-size: 24px; font-weight: 700; color: #00a65a;">
                            {{ number_format(collect($units)->sum('total_students'), 0, ',', '.') }} <small style="font-size: 14px; color: #777;">Santri</small>
                        </span>
                        <div class="progress" style="height: 3px; margin: 5px 0;">
                            <div class="progress-bar bg-green" style="width: 88%"></div>
                        </div>
                        <span class="progress-description" style="font-size: 11px; color: #666;">
                            Kapasitas: {{ number_format(collect($units)->sum('capacity') ?? 850, 0, ',', '.') }} Kursi (88%)
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid #605ca8;">
                    <span class="info-box-icon bg-purple" style="border-radius: 6px 0 0 6px;"><i class="fa fa-cubes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted" style="font-size: 12px; font-weight: 600;">ROMBEL KELAS</span>
                        <span class="info-box-number" style="font-size: 24px; font-weight: 700; color: #605ca8;">
                            {{ collect($units)->sum('total_classes') }} <small style="font-size: 14px; color: #777;">Rombel</small>
                        </span>
                        <div class="progress" style="height: 3px; margin: 5px 0;">
                            <div class="progress-bar bg-purple" style="width: 100%"></div>
                        </div>
                        <span class="progress-description" style="font-size: 11px; color: #666;">
                            Rata-rata 25 santri / rombel
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid #f39c12;">
                    <span class="info-box-icon bg-yellow" style="border-radius: 6px 0 0 6px;"><i class="fa fa-star"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted" style="font-size: 12px; font-weight: 600;">STATUS AKREDITASI</span>
                        <span class="info-box-number" style="font-size: 24px; font-weight: 700; color: #f39c12;">Grade A <small style="font-size: 14px; color: #777;">Unggul</small></span>
                        <div class="progress" style="height: 3px; margin: 5px 0;">
                            <div class="progress-bar bg-yellow" style="width: 100%"></div>
                        </div>
                        <span class="progress-description" style="font-size: 11px; color: #666;">
                            100% Terakreditasi BAN-S/M
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & View Switcher Bar --}}
        <div class="box box-default" style="border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div class="box-body" style="padding: 15px 20px;">
                <form action="{{ route('master.unit') }}" method="GET" class="form-inline" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" name="search" class="form-control input-sm" placeholder="Cari nama unit, kode, atau kepala..." value="{{ request('search') }}" style="width: 250px;">
                        </div>

                        <select name="level" class="form-control input-sm">
                            <option value="">-- Semua Jenjang --</option>
                            <option value="SD" {{ request('level') == 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                            <option value="SMP" {{ request('level') == 'SMP' ? 'selected' : '' }}>SMP (Menengah Pertama)</option>
                            <option value="SMA" {{ request('level') == 'SMA' ? 'selected' : '' }}>SMA (Menengah Atas / Boarding)</option>
                        </select>

                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-filter"></i> Saring
                        </button>
                        @if(request('search') || request('level') || request('view'))
                            <a href="{{ route('master.unit') }}" class="btn btn-default btn-sm" title="Reset filter">
                                <i class="fa fa-undo"></i> Reset
                            </a>
                        @endif
                    </div>

                    {{-- View Switcher Buttons --}}
                    <div class="btn-group">
                        <a href="{{ route('master.unit', array_merge(request()->all(), ['view' => 'cards'])) }}" 
                           class="btn btn-sm {{ request('view', 'cards') === 'cards' ? 'btn-info active' : 'btn-default' }}" title="Tampilan Kartu Interaktif">
                            <i class="fa fa-th-large"></i> Kartu Unit
                        </a>
                        <a href="{{ route('master.unit', array_merge(request()->all(), ['view' => 'table'])) }}" 
                           class="btn btn-sm {{ request('view') === 'table' ? 'btn-info active' : 'btn-default' }}" title="Tampilan Tabel Detail">
                            <i class="fa fa-table"></i> Tabel Detail
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(request('view') === 'table')
            {{-- MODE TABEL DETAIL --}}
            <div class="box box-primary" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-list"></i> Tabel Data Unit Pendidikan</h3>
                    <div class="box-tools pull-right">
                        <span class="label label-primary">{{ count($units) }} Unit Terdaftar</span>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <thead style="background-color: #f8fafc;">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th style="width: 90px;">Kode Unit</th>
                                <th>Nama Unit Pendidikan</th>
                                <th style="width: 90px;" class="text-center">Jenjang</th>
                                <th>Kepala Sekolah</th>
                                <th style="width: 100px;" class="text-center">Akreditasi</th>
                                <th style="width: 100px;" class="text-center">Total Santri</th>
                                <th style="width: 90px;" class="text-center">Rombel</th>
                                <th style="width: 90px;" class="text-center">Status</th>
                                <th style="width: 130px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $idx => $u)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $idx + 1 }}</td>
                                    <td style="vertical-align: middle;">
                                        <span class="label label-default" style="font-family: monospace; font-size: 11px; padding: 4px 6px;">{{ $u['code'] }}</span>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div style="font-weight: 700; color: #1a365d; font-size: 14px;">{{ $u['name'] }}</div>
                                        <small class="text-muted"><i class="fa fa-map-marker"></i> {{ $u['location'] ?? 'Kampus Utama Alia Islamic School' }}</small>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        @if($u['level'] == 'SD')
                                            <span class="label label-info" style="font-weight: 600;">SD</span>
                                        @elseif($u['level'] == 'SMP')
                                            <span class="label label-primary" style="font-weight: 600;">SMP</span>
                                        @else
                                            <span class="label label-purple" style="background-color: #6b46c1; color: #fff; font-weight: 600;">SMA</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <i class="fa fa-user-circle text-muted"></i> <strong>{{ $u['headmaster'] }}</strong>
                                        @if(!empty($u['nip']))
                                            <br><small class="text-muted">NIP: {{ $u['nip'] }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-green" style="font-size: 12px; padding: 4px 8px;">{{ $u['accreditation'] ?? 'A' }}</span>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle; font-weight: bold; color: #2b6cb0;">
                                        {{ number_format($u['total_students'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-purple">{{ $u['total_classes'] ?? 0 }}</span>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <span class="label label-success">Aktif</span>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDetailUnit{{ $u['id'] }}" title="Lihat Profil">
                                                <i class="fa fa-eye text-blue"></i>
                                            </button>
                                            <a href="{{ route('master.kelas', ['unit_id' => $u['id']]) }}" class="btn btn-default btn-xs" title="Lihat Kelas">
                                                <i class="fa fa-cubes text-purple"></i>
                                            </a>
                                            <form action="{{ route('master.unit.destroy', $u['id']) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit {{ $u['name'] }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-default btn-xs" title="Hapus Unit">
                                                    <i class="fa fa-trash text-red"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center" style="padding: 40px;">
                                        <i class="fa fa-folder-open-o fa-3x text-muted" style="display: block; margin-bottom: 10px;"></i>
                                        <p class="text-muted">Tidak ada data unit pendidikan yang sesuai filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- MODE KARTU INTERAKTIF (DEFAULT) --}}
            <div class="row">
                @forelse($units as $idx => $u)
                    @php
                        $badgeBg = '#2b6cb0';
                        $cardHeaderGrad = 'linear-gradient(135deg, #1a365d 0%, #2b6cb0 100%)';
                        $iconClass = 'fa-graduation-cap';
                        if ($u['level'] == 'SD') {
                            $badgeBg = '#00a65a';
                            $cardHeaderGrad = 'linear-gradient(135deg, #0f5132 0%, #198754 100%)';
                            $iconClass = 'fa-child';
                        } elseif ($u['level'] == 'SMP') {
                            $badgeBg = '#0284c7';
                            $cardHeaderGrad = 'linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%)';
                            $iconClass = 'fa-book';
                        } elseif ($u['level'] == 'SMA') {
                            $badgeBg = '#6b46c1';
                            $cardHeaderGrad = 'linear-gradient(135deg, #4c1d95 0%, #7c3aed 100%)';
                            $iconClass = 'fa-university';
                        }
                    @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="box box-solid" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.07); transition: transform 0.2s, box-shadow 0.2s; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                            {{-- Card Header with Banner --}}
                            <div style="background: {{ $cardHeaderGrad }}; color: #ffffff; padding: 18px 20px; position: relative;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <span class="label" style="background: rgba(255,255,255,0.25); font-weight: 700; font-size: 11px; padding: 4px 8px; letter-spacing: 0.5px;">
                                        {{ $u['code'] }} &bull; JENJANG {{ $u['level'] }}
                                    </span>
                                    <span class="badge" style="background-color: #f39c12; color: #fff; font-weight: 700; font-size: 11px; padding: 4px 8px;">
                                        <i class="fa fa-star"></i> Akreditasi {{ $u['accreditation'] ?? 'A' }}
                                    </span>
                                </div>
                                <h3 style="margin: 12px 0 4px 0; font-size: 19px; font-weight: 700; color: #ffffff; line-height: 1.3;">
                                    {{ $u['name'] }}
                                </h3>
                                <p style="margin: 0; font-size: 12px; opacity: 0.85;">
                                    <i class="fa fa-map-marker"></i> {{ $u['location'] ?? 'Kampus Utama Alia Islamic School' }}
                                </p>
                            </div>

                            {{-- Card Body Details --}}
                            <div class="box-body" style="padding: 18px 20px; background-color: #ffffff;">
                                {{-- Headmaster Info --}}
                                <div style="display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px dashed #e2e8f0;">
                                    <div style="width: 42px; height: 42px; border-radius: 50%; background: #edf2f7; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 18px; color: #4a5568; flex-shrink: 0;">
                                        <i class="fa {{ $iconClass }}"></i>
                                    </div>
                                    <div style="overflow: hidden;">
                                        <small class="text-muted" style="display: block; font-size: 11px; text-transform: uppercase; font-weight: 600;">Kepala Satuan Pendidikan</small>
                                        <span style="font-weight: 700; color: #2d3748; font-size: 13px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $u['headmaster'] }}">
                                            {{ $u['headmaster'] }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Metrics 2-Columns --}}
                                <div class="row" style="margin-bottom: 12px;">
                                    <div class="col-xs-6" style="border-right: 1px solid #edf2f7;">
                                        <span class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">Total Santri</span>
                                        <div style="font-size: 20px; font-weight: 800; color: #1a365d; margin-top: 2px;">
                                            {{ number_format($u['total_students'] ?? 0, 0, ',', '.') }}
                                            <small style="font-size: 12px; color: #718096; font-weight: normal;">jiwa</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" style="padding-left: 18px;">
                                        <span class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">Rombel Aktif</span>
                                        <div style="font-size: 20px; font-weight: 800; color: #6b46c1; margin-top: 2px;">
                                            {{ $u['total_classes'] ?? 0 }}
                                            <small style="font-size: 12px; color: #718096; font-weight: normal;">kelas</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Info Badges --}}
                                <div style="background-color: #f8fafc; border-radius: 6px; padding: 10px 12px; font-size: 12px; color: #4a5568; margin-bottom: 15px;">
                                    <div style="margin-bottom: 5px;">
                                        <i class="fa fa-book text-muted" style="width: 16px;"></i> 
                                        <strong>Kurikulum:</strong> {{ $u['curriculum'] ?? 'Kurikulum Merdeka + Diniyah Khas Alia' }}
                                    </div>
                                    <div>
                                        <i class="fa fa-phone text-muted" style="width: 16px;"></i> 
                                        <strong>Kontak:</strong> {{ $u['phone'] ?? '(021) 8839-2021' }}
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div style="display: flex; gap: 8px; justify-content: space-between;">
                                    <button type="button" class="btn btn-default btn-sm" style="flex: 1;" data-toggle="modal" data-target="#modalDetailUnit{{ $u['id'] }}">
                                        <i class="fa fa-info-circle text-blue"></i> Profil Unit
                                    </button>
                                    <a href="{{ route('master.kelas', ['unit_id' => $u['id']]) }}" class="btn btn-primary btn-sm" style="flex: 1; background-color: #2b6cb0; border-color: #2b6cb0;">
                                        <i class="fa fa-cubes"></i> Lihat Kelas
                                    </a>
                                    <form action="{{ route('master.unit.destroy', $u['id']) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit {{ $u['name'] }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus Unit" style="background-color: #e53e3e; border-color: #e53e3e;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="well text-center" style="background: #fff; padding: 50px 20px; border-radius: 8px; border: 1px dashed #cbd5e0;">
                            <i class="fa fa-university fa-3x text-muted" style="margin-bottom: 15px;"></i>
                            <h4 style="font-weight: 700; color: #4a5568;">Tidak Ada Unit Pendidikan</h4>
                            <p class="text-muted">Tidak ada unit pendidikan yang sesuai dengan kata kunci pencarian atau filter jenjang yang Anda tentukan.</p>
                            <a href="{{ route('master.unit') }}" class="btn btn-default btn-sm" style="margin-top: 10px;">
                                <i class="fa fa-undo"></i> Bersihkan Filter
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- Section Panduan & Kebijakan Kurikulum --}}
        <div class="row">
            <div class="col-md-6">
                <div class="box box-solid" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <div class="box-header with-border" style="background-color: #f8fafc;">
                        <h3 class="box-title" style="font-size: 15px; font-weight: 700; color: #1a365d;">
                            <i class="fa fa-mortar-board text-blue"></i> Standar Kurikulum Terpadu Alia
                        </h3>
                    </div>
                    <div class="box-body" style="font-size: 13px; line-height: 1.6; color: #4a5568;">
                        <ul style="padding-left: 20px; margin-bottom: 0;">
                            <li><strong>Integrasi Kurikulum Nasional &amp; Kepesantrenan:</strong> Menggabungkan kurikulum Kemendikbudristek/Kemenag dengan kurikulum kepesantrenan terpadu (Tahfidz, Hadits Arba'in, Bahasa Arab Fusha).</li>
                            <li><strong>Target Capaian Tahfidz:</strong> SDIT (Juz 30 &amp; Juz 29), SMPIT (Minimal 5 Juz), SMA Islamic Boarding (Minimal 10 Juz &amp; Mutqin).</li>
                            <li><strong>Pengembangan Bahasa Asing:</strong> Bimbingan intensif dwibahasa (Arab &amp; Inggris) dengan pembiasaan lingkungan asrama.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-solid" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <div class="box-header with-border" style="background-color: #f8fafc;">
                        <h3 class="box-title" style="font-size: 15px; font-weight: 700; color: #1a365d;">
                            <i class="fa fa-building text-purple"></i> Fasilitas &amp; Daya Tampung Kampus
                        </h3>
                    </div>
                    <div class="box-body" style="font-size: 13px; line-height: 1.6; color: #4a5568;">
                        <ul style="padding-left: 20px; margin-bottom: 0;">
                            <li><strong>Gedung Kampus Terpadu:</strong> Ruang kelas ber-AC, proyektor interaktif, laboratorium sains, laboratorium komputer terpadu, dan perpustakaan digital.</li>
                            <li><strong>Asrama Boarding Putri &amp; Putra:</strong> Hunian santri terpisah dengan pengawasan musyrif/musyrifah 24 jam serta klinik kesehatan sekolah.</li>
                            <li><strong>Sarana Olahraga &amp; Masjid:</strong> Masjid jami' pusat peribadatan santri, lapangan futsal/basket, dan area memanah.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODALS SECTION - Placed OUTSIDE the table and cards to prevent DOM rendering issues --}}

{{-- Modal Tambah Unit Baru --}}
<div class="modal fade" id="modalTambahUnit" tabindex="-1" role="dialog" aria-labelledby="modalTambahUnitLabel">
    <div class="modal-dialog" role="document">
        <form action="{{ route('master.unit.store') }}" method="POST">
            @csrf
            <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 100%); color: #ffffff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalTambahUnitLabel" style="font-weight: 700;">
                        <i class="fa fa-plus-circle"></i> Tambah Unit Pendidikan Baru
                    </h4>
                </div>
                <div class="modal-body" style="padding: 20px 25px;">
                    <div class="form-group">
                        <label for="code">Kode Unit <span class="text-red">*</span></label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Contoh: SDIT, SMPIT, SMA-B" required>
                        <small class="help-block text-muted">Kode unik singkatan untuk penamaan berkas &amp; kelas.</small>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Lengkap Unit Pendidikan <span class="text-red">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: SMA Islamic Boarding School Alia" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="level">Jenjang Pendidikan <span class="text-red">*</span></label>
                                <select name="level" id="level" class="form-control" required>
                                    <option value="SD">SD (Sekolah Dasar)</option>
                                    <option value="SMP">SMP (Menengah Pertama)</option>
                                    <option value="SMA">SMA (Menengah Atas)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="accreditation">Akreditasi</label>
                                <select name="accreditation" id="accreditation" class="form-control">
                                    <option value="A">A (Unggul)</option>
                                    <option value="B">B (Baik)</option>
                                    <option value="C">C (Cukup)</option>
                                    <option value="Proses">Dalam Proses</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="headmaster">Kepala Satuan Pendidikan / Madrasah <span class="text-red">*</span></label>
                        <input type="text" name="headmaster" id="headmaster" class="form-control" placeholder="Nama lengkap beserta gelar" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">No. Telepon / Layanan</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Contoh: (021) 8839-2021">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="capacity">Estimasi Kapasitas Santri</label>
                                <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Contoh: 300" min="10" max="1000">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="curriculum">Fokus Kurikulum</label>
                        <input type="text" name="curriculum" id="curriculum" class="form-control" placeholder="Contoh: Kurikulum Merdeka & Tahfidz Quran">
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8fafc;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #1a365d; border-color: #1a365d; font-weight: 600;">
                        <i class="fa fa-save"></i> Simpan Unit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Detail Modals Loop --}}
@foreach($units as $u)
    <div class="modal fade" id="modalDetailUnit{{ $u['id'] }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailUnitLabel{{ $u['id'] }}">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #1a365d 0%, #2b6cb0 100%); color: #ffffff;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalDetailUnitLabel{{ $u['id'] }}" style="font-weight: 700;">
                        <i class="fa fa-info-circle"></i> Profil Lengkap: {{ $u['name'] }}
                    </h4>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <div class="row">
                        <div class="col-md-7">
                            <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                                <tr>
                                    <th style="width: 170px; background-color: #f8fafc;">Kode Resmi Unit</th>
                                    <td><span class="label label-primary" style="font-size: 12px;">{{ $u['code'] }}</span></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8fafc;">Nama Lengkap</th>
                                    <td><strong>{{ $u['name'] }}</strong></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8fafc;">Tingkat / Jenjang</th>
                                    <td>{{ $u['level'] }} (Sekolah / Madrasah)</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8fafc;">Akreditasi BAN-S/M</th>
                                    <td><span class="label label-success">Peringkat {{ $u['accreditation'] ?? 'A' }} (Unggul)</span></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8fafc;">Kepala Satuan</th>
                                    <td>
                                        <strong>{{ $u['headmaster'] }}</strong>
                                        @if(!empty($u['nip']))
                                            <br><small class="text-muted">NIP: {{ $u['nip'] }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8fafc;">Fokus Kurikulum</th>
                                    <td>{{ $u['curriculum'] ?? 'Kurikulum Merdeka + Diniyah Khas Alia' }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8fafc;">Lokasi Kampus</th>
                                    <td>{{ $u['location'] ?? 'Jl. Raya Pendidikan No. 45, Kompleks Alia Islamic School' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-5">
                            <div class="box box-solid box-primary" style="border-radius: 6px; box-shadow: none; border: 1px solid #d2d6de;">
                                <div class="box-header with-border">
                                    <h4 class="box-title" style="font-size: 14px; font-weight: 700;">Ringkasan Data Akademik</h4>
                                </div>
                                <div class="box-body text-center" style="padding: 20px;">
                                    <div class="row">
                                        <div class="col-xs-6" style="border-right: 1px solid #f4f4f4;">
                                            <div style="font-size: 26px; font-weight: 800; color: #1a365d;">{{ number_format($u['total_students'] ?? 0, 0, ',', '.') }}</div>
                                            <div class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">Santri Aktif</div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div style="font-size: 26px; font-weight: 800; color: #6b46c1;">{{ $u['total_classes'] ?? 0 }}</div>
                                            <div class="text-muted" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">Rombel Kelas</div>
                                        </div>
                                    </div>
                                    <hr style="margin: 15px 0;">
                                    <p style="font-size: 12px; color: #666; margin-bottom: 15px; text-align: left;">
                                        Unit ini terhubung langsung dengan sistem PPDB Online, Kurikulum Akademik, e-Rapor, dan Kepengasuhan Boarding.
                                    </p>
                                    <a href="{{ route('master.kelas', ['unit_id' => $u['id']]) }}" class="btn btn-primary btn-block" style="font-weight: 600;">
                                        <i class="fa fa-external-link"></i> Buka Manajemen Rombel &amp; Kelas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8fafc;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
