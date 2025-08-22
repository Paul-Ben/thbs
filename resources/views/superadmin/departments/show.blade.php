@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
  
<div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="{{ route('bursar.dashboard') }}"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('bursar.dashboard') }}">Dashboard</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('superadmin.departments.index') }}">Department Management</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('superadmin.departments.index') }}">Department Details</a></li>
                </ul>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title">Department Information</h4>
                        <div class="ms-auto">
                            <a href="{{ route('superadmin.departments.edit', $department) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> Edit Department
                            </a>
                            <a href="{{ route('superadmin.departments.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Code</label>
                                <p class="form-control-static"><span class="badge bg-info">{{ $department->code }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Name</label>
                                <p class="form-control-static">{{ $department->name }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Created</label>
                                <p class="form-control-static">{{ $department->created_at?->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Updated</label>
                                <p class="form-control-static">{{ $department->updated_at?->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Quick Actions</h4></div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('superadmin.departments.edit', $department) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit Department
                            </a>

                            <form action="{{ route('superadmin.departments.destroy', $department) }}" method="POST"
                                  onsubmit="return confirm('Delete this department? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa fa-trash"></i> Delete Department
                                </button>
                            </form>

                            <a href="{{ route('superadmin.departments.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div> 
</div>
@endsection
