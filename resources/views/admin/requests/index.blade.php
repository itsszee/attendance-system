@extends('layouts.admin')

@section('title', 'Kelola Pengajuan Izin & Cuti')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengajuan Karyawan</li>
@endsection

@section('content')
<div class="row mb-5 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold m-0 h2" style="color: var(--dark);">Pengajuan Karyawan</h1>
        <p class="text-muted mb-0">Kelola izin, sakit, dan cuti seluruh karyawan Anda dalam satu panel.</p>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: var(--radius-lg); margin-top: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="vertical-align: middle;">
                <thead style="background: #f8fafc; color: #64748b; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">
                    <tr>
                        <th class="border-0 px-4 py-4" width="5%">No</th>
                        <th class="border-0 py-4">Karyawan</th>
                        <th class="border-0 py-4">Tipe</th>
                        <th class="border-0 py-4">Tgl Pengajuan</th>
                        <th class="border-0 py-4">Periode</th>
                        <th class="border-0 py-4">Alasan</th>
                        <th class="border-0 py-4">Status</th>
                        <th class="border-0 py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr style="transition: all 0.2s ease;">
                            <td class="px-4 text-muted small font-weight-bold">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-soft-primary mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                                        {{ substr($req->user->name, 0, 1) }}
                                    </div>
                                    <div class="font-weight-bold" style="color: var(--dark);">{{ $req->user->name }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge border-0" style="background: #f1f5f9; color: #475569; padding: 5px 10px;">{{ strtoupper($req->type) }}</span>
                            </td>
                            <td class="small text-muted">{{ $req->created_at->format('d M Y') }}<br>{{ $req->created_at->format('H:i') }}</td>
                            <td>
                                <div class="small">
                                    <span class="font-weight-bold">{{ \Carbon\Carbon::parse($req->start_date)->format('d M') }}</span> - 
                                    <span class="font-weight-bold">{{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="small text-muted" title="{{ $req->reason }}">{{ \Illuminate\Support\Str::limit($req->reason, 40) }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge" style="background: #fef3c7; color: #92400e;">PENDING</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge" style="background: #dcfce7; color: #166534;">APPROVED</span>
                                @else
                                    <span class="badge" style="background: #fee2e2; color: #991b1b;">REJECTED</span>
                                @endif
                                
                                @if($req->admin_notes)
                                    <div class="small mt-1 text-muted" style="font-style: italic;">
                                        <i class="fas fa-reply mr-1"></i>{{ \Illuminate\Support\Str::limit($req->admin_notes, 15) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 text-center">
                                @if($req->status === 'pending')
                                    <button type="button" class="btn btn-sm shadow-sm" style="background: var(--primary); color: white; border-radius: 8px;" data-toggle="modal" data-target="#actionModal{{ $req->id }}">
                                        <i class="fas fa-gavel mr-1"></i> Respon
                                    </button>
                                @else
                                    <span class="small text-muted disabled"><i class="fas fa-check-double mr-1 text-success"></i> Selesai</span>
                                @endif
                            </td>
                        </tr>

                        @if($req->status === 'pending')
                        <div class="modal fade" id="actionModal{{ $req->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0" style="border-radius: var(--radius-lg); overflow: hidden;">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title font-weight-bold" style="color: var(--dark);">Respon Pengajuan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.requests.update', $req->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body pt-4">
                                            <div class="p-3 mb-4 rounded-lg" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="font-weight-bold mr-2">{{ $req->user->name }}</div>
                                                    <span class="badge badge-secondary small">{{ strtoupper($req->type) }}</span>
                                                </div>
                                                <div class="small text-muted mb-2">
                                                    <i class="far fa-calendar-alt mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                                </div>
                                                <div class="p-2 bg-white rounded border small mt-2" style="font-style: italic; color: #64748b;">
                                                    "{{ $req->reason }}"
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="font-weight-bold small text-muted text-uppercase">Tentukan Keputusan</label>
                                                <div class="d-flex gap-3 mt-2">
                                                    <div class="custom-control custom-radio custom-control-inline flex-fill">
                                                        <input type="radio" id="approve{{ $req->id }}" name="status" value="approved" class="custom-control-input" required>
                                                        <label class="custom-control-label w-100 p-2 border rounded text-center cursor-pointer" for="approve{{ $req->id }}" style="border-color: #dcfce7 !important; color: #166534;">Setuju</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline flex-fill">
                                                        <input type="radio" id="reject{{ $req->id }}" name="status" value="rejected" class="custom-control-input" required>
                                                        <label class="custom-control-label w-100 p-2 border rounded text-center cursor-pointer" for="reject{{ $req->id }}" style="border-color: #fee2e2 !important; color: #991b1b;">Tolak</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="font-weight-bold small text-muted text-uppercase">Catatan Admin <small>(Opsional)</small></label>
                                                <textarea name="admin_notes" class="form-control" rows="3" style="border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc;" placeholder="Tinggalkan pesan untuk karyawan..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 10px;">Batal</button>
                                            <button type="submit" class="btn" style="background: var(--primary); color: white; border-radius: 10px; padding-left: 30px; padding-right: 30px;">Kirim Keputusan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data pengajuan/request masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

