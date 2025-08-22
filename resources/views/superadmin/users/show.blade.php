@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">User Details</h3>
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
                    <a href="#">{{ $user->name }}</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">User Information</h4>
                            <div class="ms-auto">
                                <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i> Edit User
                                </a>
                                <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <p class="form-control-static">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">User Role</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-{{ $user->userRole === 'Superadmin' ? 'danger' : ($user->userRole === 'Department Admin' ? 'warning' : 'info') }} badge-lg">
                                            {{ $user->userRole }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Account Status</label>
                                    <p class="form-control-static">
                                        @if(isset($user->active))
                                            <span class="badge badge-{{ $user->active ? 'success' : 'secondary' }} badge-lg">
                                                {{ $user->active ? 'Active' : 'Inactive' }}
                                            </span>
                                        @else
                                            <span class="badge badge-success badge-lg">Active</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Account Created</label>
                                    <p class="form-control-static">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Last Updated</label>
                                    <p class="form-control-static">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($user->email_verified_at)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Email Verified</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-success badge-lg">
                                            <i class="fa fa-check"></i> Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
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
                            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit User Details
                            </a>
                            
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
                            <div class="alert alert-info">
                                <small><i class="fa fa-info-circle"></i> You cannot delete your own account.</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- User Statistics Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-primary">{{ $user->id }}</h3>
                                    <small class="text-muted">User ID</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-success">{{ $user->created_at->diffForHumans() }}</h3>
                                    <small class="text-muted">Member Since</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection