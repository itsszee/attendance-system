@extends('layouts.user')

@section('title', 'Penilaian Kinerja Saya')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #6366f1;
        --secondary: #64748b;
        --radius: 24px;
        --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .hero-section {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        padding: 40px 0 80px;
        color: white;
    }

    .page-content {
        margin-top: -50px;
        padding-bottom: 50px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 30px;
        height: 100%;
    }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--secondary);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
        margin-top: 10px;
    }

    .history-table th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        border: none;
        padding: 15px;
    }

    .history-table td {
        vertical-align: middle;
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #1e293b;
    }

    .score-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 30px;
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .period-text {
        color: var(--primary);
        font-weight: 700;
        font-size: 15px;
    }

    .evaluator-info {
        font-size: 12px;
        color: var(--secondary);
        margin-bottom: 20px;
        background: #f8fafc;
        padding: 10px 15px;
        border-radius: 12px;
    }
</style>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold mb-1">Penilaian Kinerja</h1>
                <p class="mb-0 opacity-75">Pantau perkembangan performa kerja Anda</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-pill px-4 font-weight-bold">
                <i class="fas fa-chevron-left mr-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="container page-content">
    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-5 mb-4">
            <div class="glass-card">
                <div class="card-title">
                    <i class="fas fa-chart-radar text-primary"></i> Grafik Performa Terakhir
                </div>
                
                @if($latestAssessment)
                    <div class="evaluator-info">
                        <div class="mb-1"><i class="fas fa-calendar-alt mr-1"></i> Periode: <strong>{{ $latestAssessment->period }}</strong></div>
                        <div><i class="fas fa-user-check mr-1"></i> Penilai: <strong>{{ $latestAssessment->evaluator->name }}</strong></div>
                    </div>
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-5 opacity-50">
                        <i class="fas fa-chart-area fa-4x mb-3"></i>
                        <p class="font-weight-bold">Data Belum Tersedia</p>
                        <p class="small">Belum ada penilaian kinerja untuk Anda.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- History Section -->
        <div class="col-lg-7 mb-4">
            <div class="glass-card">
                <div class="card-title">
                    <i class="fas fa-history text-primary"></i> Riwayat Penilaian Lengkap
                </div>
                
                @if($assessments->isEmpty())
                    <div class="text-center py-5 opacity-50">
                        <i class="fas fa-folder-open fa-4x mb-3"></i>
                        <p class="font-weight-bold">Tidak Ada Riwayat</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover history-table">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Penilai</th>
                                    <th>Catatan</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assessments as $assessment)
                                    @php
                                        $totalScore = $assessment->assessmentDetails->sum('score');
                                        $count = $assessment->assessmentDetails->count();
                                        $average = $count > 0 ? number_format($totalScore / $count, 1) : '0';
                                    @endphp
                                    <tr>
                                        <td class="period-text">{{ $assessment->period }}</td>
                                        <td>{{ $assessment->evaluator->name }}</td>
                                        <td>
                                            <span class="small text-muted d-block text-truncate" style="max-width: 180px;" title="{{ $assessment->general_notes }}">
                                                {{ $assessment->general_notes ?: 'Tidak ada feedback' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="score-badge">
                                                <i class="fas fa-star text-warning"></i> {{ $average }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($latestAssessment)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const rawLabels = {!! json_encode($chartData['labels']) !!};
        const rawData = {!! json_encode($chartData['data']) !!};
        
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: rawLabels,
                datasets: [{
                    label: 'Skor',
                    data: rawData,
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(0, 0, 0, 0.05)' },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        suggestedMin: 0,
                        suggestedMax: 5,
                        ticks: { stepSize: 1, display: false },
                        pointLabels: {
                            font: { family: "'Outfit', sans-serif", size: 11, weight: '600' },
                            color: '#64748b'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { family: "'Outfit', sans-serif", size: 14 },
                        bodyFont: { family: "'Outfit', sans-serif", size: 13, weight: 'bold' }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
