@extends('layouts.user')

@section('title', 'Dompet Integritas')

@push('styles')
<style>
    .wallet-hero {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.3);
    }
    
    .wallet-hero::after {
        content: '\f555';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -40px;
        font-size: 200px;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    .balance-text {
        font-size: 4rem;
        font-weight: 800;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .level-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        padding: 8px 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .nav-tabs-wallet {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 30px;
    }

    .nav-tabs-wallet .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 15px 25px;
        position: relative;
        transition: all 0.3s ease;
        background: transparent;
    }

    .nav-tabs-wallet .nav-link:hover {
        color: #10b981;
    }

    .nav-tabs-wallet .nav-link.active {
        color: #10b981;
        background: transparent;
    }

    .nav-tabs-wallet .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: #10b981;
        border-radius: 3px 3px 0 0;
    }

    .transaction-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 15px;
        border-left: 5px solid transparent;
        transition: transform 0.2s;
    }
    
    .transaction-card:hover {
        transform: translateX(5px);
    }

    .transaction-earn { border-left-color: #22c55e; }
    .transaction-spend { border-left-color: #f59e0b; }
    .transaction-penalty { border-left-color: #ef4444; }

    .market-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .market-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .market-img {
        background: linear-gradient(135deg, #fdf4ff 0%, #f3e8ff 100%);
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a855f7;
    }
    
    .token-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Hero Section -->
    <div class="wallet-hero mb-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="text-white-50 font-weight-bold mb-3">SALDO INTEGRITAS</h5>
                <div class="d-flex align-items-end mb-4">
                    <div class="balance-text">{{ number_format($balance) }}</div>
                    <div class="h3 ml-2 mb-2">PTS</div>
                </div>
                <div class="level-badge">
                    <i class="fas fa-medal text-warning mr-2"></i> Level: {{ $level }}
                </div>
            </div>
            <div class="col-md-4 text-md-right mt-4 mt-md-0 position-relative" style="z-index: 1;">
                <p class="mb-0 text-white-50">Tingkatkan terus disiplin harian Anda</p>
                <h5 class="font-weight-bold">Tukar poin dengan kelonggaran khusus!</h5>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 12px;">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs nav-tabs-wallet" id="walletTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="history-tab" data-toggle="tab" href="#history" role="tab">
                <i class="fas fa-history mr-2"></i> Riwayat Mutasi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="market-tab" data-toggle="tab" href="#market" role="tab">
                <i class="fas fa-store mr-2"></i> Marketplace
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="inventory-tab" data-toggle="tab" href="#inventory" role="tab">
                <i class="fas fa-box-open mr-2"></i> My Inventory
                <span class="badge badge-success ml-2">{{ $tokens->where('status', 'AVAILABLE')->count() }}</span>
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="walletTabContent">
        
        <!-- Tab: Mutasi / Ledger -->
        <div class="tab-pane fade show active" id="history" role="tabpanel">
            <div class="row">
                <div class="col-lg-8" style="max-height: 500px; overflow-y: auto;">
                    @forelse($ledgers as $ledger)
                        <div class="transaction-card transaction-{{ strtolower($ledger->transaction_type) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle mr-3" style="width: 45px; height: 45px; border-radius: 50%; display: flex; justify-content: center; align-items: center; background: {{ $ledger->transaction_type == 'EARN' ? '#dcfce7' : ($ledger->transaction_type == 'SPEND' ? '#fef3c7' : '#fee2e2') }}; color: {{ $ledger->transaction_type == 'EARN' ? '#166534' : ($ledger->transaction_type == 'SPEND' ? '#92400e' : '#991b1b') }};">
                                        <i class="fas {{ $ledger->transaction_type == 'EARN' ? 'fa-arrow-down' : ($ledger->transaction_type == 'SPEND' ? 'fa-shopping-cart' : 'fa-arrow-up') }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold mb-1">{{ $ledger->description ?: 'Transaksi Integritas' }}</h6>
                                        <small class="text-muted">{{ $ledger->created_at->format('d M Y, H:i') }} | Trx ID: #{{ str_pad($ledger->id, 5, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="h5 mb-0 font-weight-bold" style="color: {{ $ledger->transaction_type == 'EARN' ? '#22c55e' : '#ef4444' }}">
                                        {{ $ledger->amount > 0 ? '+' : '' }}{{ $ledger->amount }}
                                    </div>
                                    <small class="text-muted">Balance: {{ $ledger->current_balance }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-file-invoice fa-3x mb-3 opacity-50"></i>
                            <h5>Belum ada riwayat poin.</h5>
                            <p>Tingkatkan disiplin absensi Anda untuk mendapatkan poin pertamamu!</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="card border-0 shadow-sm rounded-lg" style="background: #f8fafc;">
                        <div class="card-body text-center">
                            <i class="fas fa-info-circle text-primary fa-3x mb-3"></i>
                            <h5>Informasi Dompet</h5>
                            <p class="text-muted small text-left mt-3">
                                <i class="fas fa-check text-success mr-2"></i> Poin bertambah secara otomatis bila Anda datang tepat waktu, terutama datang lebih awal.<br><br>
                                <i class="fas fa-exclamation-triangle text-danger mr-2"></i> Poin akan dipotong setiap kali Anda terlambat atau lupa check-out.<br><br>
                                <i class="fas fa-gift text-warning mr-2"></i> Kumpulkan Poin dan tukar dengan token kemudahan operasional.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Marketplace -->
        <div class="tab-pane fade" id="market" role="tabpanel">
            <div class="row">
                @forelse($items as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="market-card">
                            <div class="market-img">
                                <i class="fas fa-ticket-alt fa-4x mb-2"></i>
                            </div>
                            <div class="card-body d-flex flex-column text-center">
                                <h5 class="font-weight-bold mb-3">{{ $item->item_name }}</h5>
                                <div class="d-flex justify-content-center align-items-center mb-4 mt-auto">
                                    <i class="fas fa-star text-warning mr-2"></i>
                                    <span class="h4 font-weight-bold mb-0">{{ $item->point_cost }}</span> <span class="text-muted ml-1">PTS</span>
                                </div>
                                <form action="{{ route('wallet.buy', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn font-weight-bold w-100 py-2" style="border-radius:12px; background: {{ $balance >= $item->point_cost ? '#f59e0b' : '#e2e8f0' }}; color: {{ $balance >= $item->point_cost ? 'white' : '#94a3b8' }};" {{ $balance < $item->point_cost ? 'disabled' : '' }}>
                                        <i class="fas fa-shopping-bag mr-1"></i> Tukar Poin
                                    </button>
                                </form>
                                @if($item->stock_limit !== null)
                                    <small class="text-muted mt-2 d-block">Stok Terbatas: {{ $item->stock_limit }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h5 class="text-muted">Katalog Flexibility Item saat ini sedang kosong.</h5>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Tab: Inventory -->
        <div class="tab-pane fade" id="inventory" role="tabpanel">
            <div class="row">
                @forelse($tokens as $token)
                    <div class="col-md-4 mb-4">
                        <div class="token-card position-relative" style="opacity: {{ $token->status == 'USED' ? '0.6' : '1' }}">
                            @if($token->status == 'AVAILABLE')
                                <span class="badge badge-success position-absolute" style="top: -10px; right: -10px; padding: 8px 15px; border-radius: 20px; font-size:12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"><i class="fas fa-bolt mr-1"></i> Siap Pakai</span>
                            @else
                                <span class="badge badge-secondary position-absolute" style="top: -10px; right: -10px; padding: 8px 15px; border-radius: 20px; font-size:12px;"><i class="fas fa-check mr-1"></i> Digunakan</span>
                            @endif
                            
                            <i class="fas fa-ticket-alt text-primary fa-3x mb-3"></i>
                            <h5 class="font-weight-bold">{{ $token->item->item_name }}</h5>
                            <small class="text-muted d-block mt-2">No. Seri: TKN-{{ str_pad($token->id, 4, '0', STR_PAD_LEFT) }}</small>
                            <small class="text-muted d-block">Dibeli pada: {{ $token->created_at->format('d M Y') }}</small>
                            
                            @if($token->status == 'USED')
                                <hr>
                                <small class="text-success font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Telah mencegah penalti sistem.</small>
                            @else
                                <hr>
                                <small class="text-primary font-weight-bold"><i class="fas fa-robot mr-1"></i> Sistem akan memakai token ini otomatis bila anda indisipliner!</small>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box-open fa-3x mb-3 text-muted opacity-50"></i>
                        <h5 class="text-muted">Inventory Kosong</h5>
                        <p class="text-muted">Beli token kelonggaran di Marketplace menggunakan Saldo Poin Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>
</div>
@endsection
