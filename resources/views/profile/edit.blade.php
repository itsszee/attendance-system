@extends('layouts.user')

@section('title', 'Profile Settings')

@push('styles')
<style>
    .profile-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 30px 0;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .profile-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .info-table td {
        padding: 12px 0;
    }

    .info-table td:first-child {
        font-weight: 600;
        color: #667eea;
        width: 150px;
    }

    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <div class="container">
        
        <!-- Back to Dashboard -->
        <div class="mb-4">
             <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                <i class="fas fa-chevron-left mr-1"></i> Dashboard
            </a>
        </div>

        <!-- Data Karyawan (if exists) -->
        @if(isset($karyawan) && $karyawan)
        <div class="profile-card">
            <h3>
                <i class="fas fa-id-card"></i>
                Data Karyawan
            </h3>
            <table class="info-table">
                <tr>
                    <td>Nama</td>
                    <td>{{ $karyawan->nama }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>{{ $karyawan->nip }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>{{ $karyawan->jabatan }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>{{ $karyawan->departemen }}</td>
                </tr>
                <tr>
                    <td>No. Telepon</td>
                    <td>{{ $karyawan->no_telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $karyawan->alamat ?? '-' }}</td>
                </tr>
                @if($karyawan->shift)
                <tr>
                    <td>Shift</td>
                    <td>{{ $karyawan->shift->name }} ({{ $karyawan->shift->start_time->format('H:i') }} - {{ $karyawan->shift->end_time->format('H:i') }})</td>
                </tr>
                @endif
            </table>
        </div>
        @endif

        <!-- Update Profile Information -->
        <div class="profile-card">
            <h3>
                <i class="fas fa-user-edit"></i>
                Update Profile Information
            </h3>

            @if(session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> Profile updated successfully!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" 
                           id="name"
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email"
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-update">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- Update Password -->
        <div class="profile-card">
            <h3>
                <i class="fas fa-key"></i>
                Update Password
            </h3>

            @if(session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> Password updated successfully!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" 
                           id="current_password"
                           name="current_password" 
                           class="form-control @error('current_password') is-invalid @enderror"
                           required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" 
                           id="password"
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" 
                           id="password_confirmation"
                           name="password_confirmation" 
                           class="form-control"
                           required>
                </div>

                <button type="submit" class="btn btn-update">
                    <i class="fas fa-lock"></i> Update Password
                </button>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="profile-card">
            <h3>
                <i class="fas fa-trash-alt"></i>
                Delete Account
            </h3>

            <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                <i class="fas fa-exclamation-triangle"></i> Delete Account
            </button>
        </div>

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                
                <div class="modal-body">
                    <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>
                    
                    <div class="form-group mt-3">
                        <label for="delete_password">Password</label>
                        <input type="password" 
                               id="delete_password"
                               name="password" 
                               class="form-control"
                               required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection