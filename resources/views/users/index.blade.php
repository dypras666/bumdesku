@extends('adminlte::page')

@section('title', 'Manajemen Pengguna')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Manajemen Pengguna</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pengguna</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <!-- User Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pengguna</span>
                    <span class="info-box-number">{{ $users->total() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Aktif</span>
                    <span class="info-box-number">{{ $users->where('email_verified_at', '!=', null)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-user-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Belum Verifikasi</span>
                    <span class="info-box-number">{{ $users->where('email_verified_at', null)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-user-shield"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Admin</span>
                    <span class="info-box-number">{{ $users->filter(function($user) { return $user->role && in_array($user->role->name, ['super_admin', 'admin']); })->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users mr-1"></i>
                Daftar Pengguna
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" onclick="createUser()">
                    <i class="fas fa-plus"></i> Tambah Pengguna
                </button>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="user-panel d-flex">
                                    <div class="image">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=007bff&color=fff&size=32" 
                                             class="img-circle elevation-2" alt="User Image">
                                    </div>
                                    <div class="info">
                                        <span class="d-block">{{ $user->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                    <span class="badge badge-primary">{{ $user->role->display_name }}</span>
                                @else
                                    <span class="badge badge-secondary">Tidak ada role</span>
                                @endif
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-warning">Belum Verifikasi</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm" onclick="viewUser({{ $user->id }})" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})" title="Edit Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" title="Hapus Pengguna">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_info">
                            Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} 
                            dari {{ $users->total() }} data pengguna
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers float-right">
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

<!-- Modal View User -->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">Detail Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="viewUserAvatar" src="" class="img-circle elevation-2" style="width: 80px; height: 80px;" alt="User Image">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td id="viewUserName"></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td id="viewUserEmail"></td>
                            </tr>
                            <tr>
                                <td><strong>Role:</strong></td>
                                <td id="viewUserRole"></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td id="viewUserStatus"></td>
                            </tr>
                            <tr>
                                <td><strong>Bergabung:</strong></td>
                                <td id="viewUserJoined"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create User -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createUserForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="createUserName">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="createUserName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="createUserEmail">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="createUserEmail" name="email" required>
                        <small class="form-text text-success">
                            <i class="fas fa-check-circle"></i> Email akan otomatis diverifikasi
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="createUserRole">Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="createUserRole" name="role_id" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="createUserPassword">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="createUserPassword" name="password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('createUserPassword', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Minimal 8 karakter</small>
                    </div>
                    <div class="form-group">
                        <label for="createUserPasswordConfirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="createUserPasswordConfirmation" name="password_confirmation" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('createUserPasswordConfirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editUserName">Nama</label>
                        <input type="text" class="form-control" id="editUserName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserRole">Role</label>
                        <select class="form-control" id="editUserRole" name="role_id" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editUserPassword">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="editUserPassword" name="password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('editUserPassword', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editUserPasswordConfirmation">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="editUserPasswordConfirmation" name="password_confirmation">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('editUserPasswordConfirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete User -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Hapus Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUser">Hapus</button>
            </div>
        </div>
    </div>
</div>

@section('css')
    <style>
        .user-panel .image img {
            width: 32px;
            height: 32px;
        }
        .user-panel .info {
            padding-left: 10px;
            line-height: 32px;
        }
    </style>
@stop

@section('js')
<script>
let currentUserId = null;

// Function to toggle password visibility
function togglePasswordVisibility(inputId, button) {
    const passwordInput = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        button.setAttribute('title', 'Sembunyikan password');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        button.setAttribute('title', 'Tampilkan password');
    }
}

function createUser() {
    $('#createUserForm')[0].reset();
    // Reset password visibility to hidden state
    const passwordInputs = ['createUserPassword', 'createUserPasswordConfirmation'];
    passwordInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const button = input.parentElement.querySelector('button');
        const icon = button.querySelector('i');
        
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        button.setAttribute('title', 'Tampilkan password');
    });
    $('#createUserModal').modal('show');
}

function viewUser(userId) {
    $.get(`/users/${userId}`, function(data) {
        $('#viewUserName').text(data.name);
        $('#viewUserEmail').text(data.email);
        $('#viewUserRole').html(data.role ? `<span class="badge badge-primary">${data.role.display_name}</span>` : '<span class="badge badge-secondary">Tidak ada role</span>');
        $('#viewUserStatus').html(data.email_verified_at ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-warning">Belum Verifikasi</span>');
        $('#viewUserJoined').text(new Date(data.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }));
        $('#viewUserAvatar').attr('src', `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}&background=007bff&color=fff&size=80`);
        $('#viewUserModal').modal('show');
    }).fail(function() {
        alert('Gagal memuat data pengguna');
    });
}

function editUser(userId) {
    $.get(`/users/${userId}/edit`, function(data) {
        currentUserId = userId;
        $('#editUserName').val(data.name);
        $('#editUserEmail').val(data.email);
        $('#editUserRole').val(data.role_id);
        $('#editUserPassword').val('');
        $('#editUserPasswordConfirmation').val('');
        
        // Reset password visibility to hidden state
        const passwordInputs = ['editUserPassword', 'editUserPasswordConfirmation'];
        passwordInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            const button = input.parentElement.querySelector('button');
            const icon = button.querySelector('i');
            
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            button.setAttribute('title', 'Tampilkan password');
        });
        
        $('#editUserModal').modal('show');
    }).fail(function() {
        alert('Gagal memuat data pengguna');
    });
}

function deleteUser(userId, userName) {
    currentUserId = userId;
    $('#deleteUserName').text(userName);
    $('#deleteUserModal').modal('show');
}

$('#createUserForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    $.ajax({
        url: '/users',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#createUserModal').modal('hide');
                location.reload();
            } else {
                alert(response.message || 'Terjadi kesalahan');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Validasi gagal:\n';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join(', ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert('Terjadi kesalahan saat membuat pengguna baru');
            }
        }
    });
});

$('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    $.ajax({
        url: `/users/${currentUserId}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-HTTP-Method-Override': 'PUT'
        },
        success: function(response) {
            if (response.success) {
                $('#editUserModal').modal('hide');
                location.reload();
            } else {
                alert(response.message || 'Terjadi kesalahan');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Validasi gagal:\n';
                for (const field in errors) {
                    errorMessage += `- ${errors[field].join(', ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert('Terjadi kesalahan saat menyimpan data');
            }
        }
    });
});

$('#confirmDeleteUser').on('click', function() {
    $.ajax({
        url: `/users/${currentUserId}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#deleteUserModal').modal('hide');
                location.reload();
            } else {
                alert(response.message || 'Terjadi kesalahan');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                alert(xhr.responseJSON.message || 'Tidak dapat menghapus pengguna');
            } else {
                alert('Terjadi kesalahan saat menghapus pengguna');
            }
        }
    });
});
</script>
@stop