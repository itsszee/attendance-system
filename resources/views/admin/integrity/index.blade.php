@extends('layouts.admin')

@section('title', 'Dompet Integritas (Admin)')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Dompet Integritas</li>
@endsection

@section('content')
<div class="row">   
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0"><i class="fas fa-cogs mr-2"></i>Aturan Integritas</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.integrity.rules.store') }}" method="POST" id="ruleForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Nama Aturan (Rule Name)</label>
                            <input type="text" name="rule_name" class="form-control" placeholder="Contoh: Datang Lebih Awal" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Target Role (Opsional)</label>
                            <select name="target_role" class="form-control">
                                <option value="All">Semua</option>
                                <option value="Siswa">Karyawan Magang</option>
                                <option value="Karyawan">Karyawan Tetap</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Logika Kondisi (Condition)</label>
                            <div class="d-flex align-items-center bg-light p-3 rounded border">
                                <span class="mr-2 font-weight-bold text-muted">JIKA [Jam Kedatangan]</span>
                                <select name="condition_operator" class="form-control mx-2" style="width: auto;" required>
                                    <option value="<">Kurang Dari (<)</option>
                                    <option value=">">Lebih Dari (>)</option>
                                    <option value="<=">Kurang Dari Sama Dengan (<=)</option>
                                    <option value=">=">Lebih Dari Sama Dengan (>=)</option>
                                    <option value="=">Sama Dengan (=)</option>
                                </select>
                                <input type="time" name="condition_value" class="form-control" style="width: 150px;" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Modifikasi Poin (Modifier)</label>
                            <div class="d-flex align-items-center bg-light p-3 rounded border">
                                <span class="mr-2 font-weight-bold text-muted">MAKA DIBERIKAN POIN</span>
                                <input type="number" name="point_modifier" class="form-control" placeholder="Contoh: +5 atau -3" style="width: 150px;" required>
                            </div>
                            <small class="text-info mt-2 d-block"><i class="fas fa-info-circle"></i> Gunakan minus (-) untuk mengurangi poin (penalty).</small>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="fas fa-save mr-1"></i> Simpan Rule
                    </button>
                </form>
                
                <hr class="mt-5 mb-4">
                
                <h5 class="font-weight-bold"><i class="fas fa-list-ul mr-2"></i> Aturan Aktif</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama Aturan</th>
                                <th>Kondisi Jam</th>
                                <th>Point</th>
                                <th>Target</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rules as $rule)
                            <tr>
                                <td>{{ $rule->rule_name }}</td>
                                <td>
                                    <span class="badge badge-secondary p-2">Check-in {{ $rule->condition_operator }} {{ $rule->condition_value }}</span>
                                </td>
                                <td>
                                    @if($rule->point_modifier > 0)
                                        <span class="badge badge-success p-2">+{{ $rule->point_modifier }}</span>
                                    @else
                                        <span class="badge badge-danger p-2">{{ $rule->point_modifier }}</span>
                                    @endif
                                </td>
                                <td>{{ $rule->target_role ?: 'Semua' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editRuleModal{{ $rule->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.integrity.rules.destroy', $rule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada aturan yang dibuat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Marketplace Configuration & Leaderboard -->
    <div class="col-md-5">
        <div class="card mb-4" style="border-top: 4px solid #f59e0b;">
            <div class="card-header bg-white">
                <h3 class="card-title mb-0 font-weight-bold"><i class="fas fa-store text-warning mr-2"></i>Marketplace</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.integrity.items.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Nama Item Kelonggaran</label>
                            <input type="text" name="item_name" class="form-control" placeholder="Cth: Telat Bebas 30 Menit" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Harga Poin</label>
                            <input type="number" name="point_cost" class="form-control" placeholder="Cth: 50" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Stok Limit (Opsional)</label>
                            <input type="number" name="stock_limit" class="form-control" placeholder="Cth: 10">
                        </div>
                    </div>
                    <button class="btn btn-warning w-100" type="submit"><i class="fas fa-plus mr-1"></i> Tambah Item</button>
                </form>
                
                <hr class="my-4">
                
                <ul class="list-group">
                    @forelse($items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->item_name }}</strong><br>
                            <small class="text-muted">Stok: {{ $item->stock_limit ?: 'Unlimited' }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-warning badge-pill p-2 mr-3" style="font-size:14px;"><i class="fas fa-star text-white mr-1"></i>{{ $item->point_cost }}</span>
                            <button type="button" class="btn btn-sm btn-outline-info mr-1" data-toggle="modal" data-target="#editItemModal{{ $item->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.integrity.items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">Belum ada item di marketplace.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        <div class="card" style="border-top: 4px solid #22c55e;">
            <div class="card-header bg-white">
                <h3 class="card-title mb-0 font-weight-bold"><i class="fas fa-trophy text-success mr-2"></i> Papan Peringkat Integritas</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($leaderboard as $index => $ledger)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            @if($index == 0)
                                <i class="fas fa-medal text-warning fa-lg mr-2"></i>
                            @elseif($index == 1)
                                <i class="fas fa-medal text-secondary fa-lg mr-2"></i>
                            @elseif($index == 2)
                                <i class="fas fa-medal text-danger fa-lg mr-2"></i>
                            @else
                                <span class="text-muted font-weight-bold mr-3 ml-2">{{ $index + 1 }}</span>
                            @endif
                            <span class="font-weight-bold">{{ $ledger->user->name }}</span>
                        </div>
                        <span class="badge badge-success p-2">{{ $ledger->current_balance }} PTS</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted border-0 py-4">Belum ada data poin terkumpul.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Rules -->
@foreach($rules as $rule)
<div class="modal fade" id="editRuleModal{{ $rule->id }}" tabindex="-1" role="dialog" aria-labelledby="editRuleModalLabel{{ $rule->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.integrity.rules.update', $rule->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editRuleModalLabel{{ $rule->id }}">Edit Aturan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label>Nama Aturan (Rule Name)</label>
                        <input type="text" name="rule_name" class="form-control" value="{{ $rule->rule_name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Target Role (Opsional)</label>
                        <select name="target_role" class="form-control">
                            <option value="All" {{ $rule->target_role == 'All' || empty($rule->target_role) ? 'selected' : '' }}>Semua</option>
                            <option value="Siswa" {{ $rule->target_role == 'Siswa' ? 'selected' : '' }}>Karyawan Magang</option>
                            <option value="Karyawan" {{ $rule->target_role == 'Karyawan' ? 'selected' : '' }}>Karyawan Tetap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Logika Kondisi (Operator)</label>
                        <select name="condition_operator" class="form-control" required>
                            <option value="<" {{ $rule->condition_operator == '<' ? 'selected' : '' }}>Kurang Dari (<)</option>
                            <option value=">" {{ $rule->condition_operator == '>' ? 'selected' : '' }}>Lebih Dari (>)</option>
                            <option value="<=" {{ $rule->condition_operator == '<=' ? 'selected' : '' }}>Kurang Dari Sama Dengan (<=)</option>
                            <option value=">=" {{ $rule->condition_operator == '>=' ? 'selected' : '' }}>Lebih Dari Sama Dengan (>=)</option>
                            <option value="=" {{ $rule->condition_operator == '=' ? 'selected' : '' }}>Sama Dengan (=)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Waktu Kondisi</label>
                        <input type="time" name="condition_value" class="form-control" value="{{ $rule->condition_value }}" required>
                    </div>
                    <div class="form-group">
                        <label>Modifikasi Poin (Modifier)</label>
                        <input type="number" name="point_modifier" class="form-control" value="{{ $rule->point_modifier }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modals for Items -->
@foreach($items as $item)
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.integrity.items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="editItemModalLabel{{ $item->id }}">Edit Item Marketplace</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label>Nama Item Kelonggaran</label>
                        <input type="text" name="item_name" class="form-control" value="{{ $item->item_name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label>Harga Poin</label>
                            <input type="number" name="point_cost" class="form-control" value="{{ $item->point_cost }}" required>
                        </div>
                        <div class="col-6 form-group">
                            <label>Stok Limit (Opsional)</label>
                            <input type="number" name="stock_limit" class="form-control" value="{{ $item->stock_limit }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
