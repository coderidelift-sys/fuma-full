@extends('layouts.app')
@section('title', 'Dashboard')

{{-- @section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Card Statistics -->
        <div class="row g-4 mb-4">
            @php
                $stats = [
                    [
                        'label' => 'Total Sekolah',
                        'count' => $totalSchools,
                        'icon' => 'ri-building-line',
                        'color' => 'primary',
                        'growth' => '+5.2%',
                    ],
                    [
                        'label' => 'Total Pengguna',
                        'count' => $totalUsers,
                        'icon' => 'ri-user-3-line',
                        'color' => 'success',
                        'growth' => '+8.4%',
                    ],
                    [
                        'label' => 'Admin Aktif',
                        'count' => $activeAdmins,
                        'icon' => 'ri-admin-line',
                        'color' => 'info',
                        'growth' => '+1.1%',
                    ],
                    [
                        'label' => 'Siswa Terdaftar',
                        'count' => $totalStudents,
                        'icon' => 'ri-team-line',
                        'color' => 'warning',
                        'growth' => '+10.3%',
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-border-shadow-{{ $stat['color'] }} h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded-3 bg-label-{{ $stat['color'] }}">
                                        <i class="{{ $stat['icon'] }} ri-24px"></i>
                                    </span>
                                </div>
                                <h4 class="mb-0">{{ $stat['count'] }}</h4>
                            </div>
                            <h6 class="mb-0 fw-normal">{{ $stat['label'] }}</h6>
                            <p class="mb-0">
                                <span class="me-1 fw-medium">{{ $stat['growth'] }}</span>
                                <small class="text-muted">dari minggu lalu</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chart -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Distribusi Admin per Sekolah</h5>
                        <small class="text-muted">Data real-time</small>
                    </div>
                    <div class="card-body">
                        <div id="userDistributionChart" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
