@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Create New User</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('superadmin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('superadmin.users.index') }}">User Management</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Create User</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">New User Information</h4>
                            <div class="ms-auto">
                                <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.users.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Enter full name"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Enter email address"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="userRole" class="form-label">User Role <span class="text-danger">*</span></label>
                                        <select class="form-select @error('userRole') is-invalid @enderror" 
                                                id="userRole" 
                                                name="userRole" 
                                                required>
                                            <option value="">Select Role</option>
                                            <option value="Superadmin" {{ old('userRole') === 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                                            <option value="Department Admin" {{ old('userRole') === 'Department Admin' ? 'selected' : '' }}>Department Admin</option>
                                            <option value="Admission Officer" {{ old('userRole') === 'Admission Officer' ? 'selected' : '' }}>Admission Officer</option>
                                        </select>
                                        @error('userRole')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter password (min. 8 characters)"
                                               required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Password must be at least 8 characters long.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirm password"
                                               required>
                                        <small class="form-text text-muted">Re-enter the password to confirm.</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i>
                                        <strong>Note:</strong> The new user will be created with the specified role and permissions. 
                                        They will be able to log in immediately with the provided credentials.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create User
                                </button>
                                <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Roles</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-danger">Superadmin</h6>
                            <small class="text-muted">Full system access including user management, system settings, and all administrative functions.</small>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-warning">Department Admin</h6>
                            <small class="text-muted">Administrative access to department-specific functions, student management, and academic operations.</small>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-info">Admission Officer</h6>
                            <small class="text-muted">Access to admission processes, application reviews, and student enrollment functions.</small>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Password Requirements</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-check text-success"></i> Minimum 8 characters</li>
                            <li><i class="fa fa-check text-success"></i> Must be confirmed</li>
                            <li><i class="fa fa-info text-info"></i> Consider using a mix of letters, numbers, and symbols</li>
                            <li><i class="fa fa-info text-info"></i> User can change password after first login</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });
    
    document.getElementById('password').addEventListener('input', function() {
        const confirmPassword = document.getElementById('password_confirmation');
        if (confirmPassword.value) {
            confirmPassword.dispatchEvent(new Event('input'));
        }
    });
</script>
@endpush
@endsection