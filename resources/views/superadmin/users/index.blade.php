@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">User Management</h3>
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
                    <a href="#">User Management</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">All Users</h4>
                            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Add User
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @php
                                                    $roleClass = $user->userRole === 'Superadmin' ? 'danger' : ($user->userRole === 'College Admin' ? 'warning' : 'info');
                                                @endphp
                                                <span class="badge badge-{{ $roleClass }}">{{ $user->userRole }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = (isset($user->active) && $user->active) || !isset($user->active) ? 'success' : 'secondary';
                                                    $statusText = (isset($user->active) && $user->active) || !isset($user->active) ? 'Active' : 'Inactive';
                                                @endphp
                                                <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-link btn-info btn-lg" data-bs-toggle="tooltip" title="View User">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit User">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('superadmin.users.reset-password', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to reset this user\'s password? A new password will be sent to their email.')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link btn-warning btn-lg" data-bs-toggle="tooltip" title="Reset Password">
                                                            <i class="fa fa-key"></i>
                                                        </button>
                                                    </form>
                                                    @if($user->id !== Auth::id())
                                                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link btn-danger btn-lg" data-bs-toggle="tooltip" title="Delete User">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection