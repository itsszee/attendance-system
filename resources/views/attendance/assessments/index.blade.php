@extends('layouts.user')

@section('title', 'Penilaian Kinerja Saya')

@push('styles')
<style>
    .dashboard-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 30px 0;
    }
    
    .content-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .page-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h2 {
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        text-decoration: none;
    }
    
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
        display: flex;
        justify-content: center;
    }
    
    .history-table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border-top: none;
    }
    
    .history-table td {
        vertical-align: middle;
    }

    .score-badge {
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 20px;
        background: #eaf1ff;
        color: #3b82f6;
        font-weight: bold;
    }
</style>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-white font-weight-bold m-0"><i class="fas fa-star text-warning mr-2"></i> Penilaian Kinerja Saya</h3>
            <a href="{{ route('dashboard') }}" class="btn-back"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard</a>
        </div>

        <div class="row">
            <!-- Radar Chart Section -->
            <div class="col-lg-5 mb-4">
                <div class="content-card h-100">
                    <h5 class="font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-chart-pie text-primary mr-2"></i> Grafik Kinerja (Bulan Terakhir)
                    </h5>
                    
                    @if($latestAssessment)
                        <p class="text-muted small mb-3">
                            <i class="fas fa-calendar-alt mr-1"></i> Periode: <strong>{{ $latestAssessment->period }}</strong><br>
                            <i class="fas fa-user-tie mr-1"></i> Dinilai oleh: <strong>{{ $latestAssessment->evaluator->name }}</strong>
                        </p>
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-chart-area fa-3x mb-3 text-light"></i>
                            <p>Belum ada grafis kinerja yang tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- History Section -->
            <div class="col-lg-7 mb-4">
                <div class="content-card h-100">
                    <h5 class="font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-history text-primary mr-2"></i> Riwayat Penilaian
                    </h5>
                    
                    @if($assessments->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50" alt="No data">
                            <h6 class="font-weight-bold">Belum Ada Riwayat</h6>
                            <p class="small">Anda belum menerima penilaian kinerja apa pun.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover history-table">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Tanggal Penilaian</th>
                                        <th>Penilai</th>
                                        <th>Catatan / Feedback</th>
                                        <th>Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessments as $assessment)
                                        @php
                                            $totalScore = $assessment->assessmentDetails->sum('score');
                                            $count = $assessment->assessmentDetails->count();
                                            $average = $count > 0 ? number_format($totalScore / $count, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bold text-primary">{{ $assessment->period }}</td>
                                            <td class="small">{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('d M Y') }}</td>
                                            <td>{{ $assessment->evaluator->name }}</td>
                                            <td>
                                                <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $assessment->general_notes }}">
                                                    {{ $assessment->general_notes ?: '-' }}
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
                    label: 'Skor Kinerja (1-5)',
                    data: rawData,
                    backgroundColor: 'rgba(102, 126, 234, 0.4)',
                    borderColor: 'rgba(118, 75, 162, 1)',
                    pointBackgroundColor: 'rgba(118, 75, 162, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(118, 75, 162, 1)',
                    pointRadius: 4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        suggestedMin: 0,
                        suggestedMax: 5,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        pointLabels: {
                            font: {
                                size: 12,
                                family: "'Source Sans Pro', sans-serif",
                                weight: '600'
                            },
                            color: '#495057'
                        },
                        ticks: {
                            stepSize: 1,
                            backdropColor: 'transparent',
                            color: '#6c757d'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleFont: {
                            size: 14,
                            family: "'Source Sans Pro', sans-serif",
                        },
                        bodyFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return 'Skor: ' + context.raw + ' / 5';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
