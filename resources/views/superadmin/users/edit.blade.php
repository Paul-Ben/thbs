@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Edit User</h3>
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
                    <a href="#">Edit {{ $user->name }}</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Edit User Information</h4>
                            <div class="ms-auto">
                                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> View User
                                </a>
                                <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}" 
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
                                               value="{{ old('email', $user->email) }}" 
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
                                            <option value="Superadmin" {{ old('userRole', $user->userRole) === 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                                            <option value="College Admin" {{ old('userRole', $user->userRole) === 'College Admin' ? 'selected' : '' }}>College Admin</option>
                                            <option value="Admission Officer" {{ old('userRole', $user->userRole) === 'Admission Officer' ? 'selected' : '' }}>Admission Officer</option>
                                        </select>
                                        @error('userRole')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Account Status</label>
                                        <div class="form-control-static">
                                            @if(isset($user->active))
                                                <span class="badge badge-{{ $user->active ? 'success' : 'secondary' }} badge-lg">
                                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            @else
                                                <span class="badge badge-success badge-lg">Active</span>
                                            @endif
                                            @if(isset($user->active))
                                                <small class="text-muted d-block mt-1">
                                                    Use the "Toggle Status" button to change account status
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i>
                                        <strong>Note:</strong> Changing the user role will update their permissions immediately. 
                                        To reset the password, use the "Reset Password" button which will send a new password to the user's email.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update User
                                </button>
                                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-secondary">
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
                        <h4 class="card-title">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <form action="{{ route('superadmin.users.reset-password', $user) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to reset this user\'s password? A new password will be sent to their email.')">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fa fa-key"></i> Reset Password
                                </button>
                            </form>
                            
                            @if(isset($user->active))
                            <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-{{ $user->active ? 'secondary' : 'success' }} w-100">
                                    <i class="fa fa-{{ $user->active ? 'ban' : 'check' }}"></i> 
                                    {{ $user->active ? 'Deactivate' : 'Activate' }} Account
                                </button>
                            </form>
                            @endif
                            
                            @if($user->id !== auth()->id())
                            <form action="{{ route('superadmin.users.destroy', $user) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa fa-trash"></i> Delete User
                                </button>
                            </form>
                            @else
                            <div class="alert alert-warning">
                                <small><i class="fa fa-exclamation-triangle"></i> You cannot delete your own account.</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- User Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Current Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">User ID</small>
                            <div class="fw-bold">{{ $user->id }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Created</small>
                            <div class="fw-bold">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Last Updated</small>
                            <div class="fw-bold">{{ $user->updated_at->format('M d, Y') }}</div>
                        </div>
                        @if($user->email_verified_at)
                        <div class="mb-3">
                            <small class="text-muted">Email Verified</small>
                            <div class="fw-bold text-success">
                                <i class="fa fa-check"></i> Yes
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection