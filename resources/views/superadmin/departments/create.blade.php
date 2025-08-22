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
                    <li class="nav-item"><a href="{{ route('superadmin.departments.create') }}">Create Department</a></li>
                </ul>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title">New Department Information</h4>
                        <div class="ms-auto">
                            <a href="{{ route('superadmin.departments.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.departments.store') }}" method="POST" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="code" class="form-label">Department Code <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="code"
                                       name="code"
                                       class="form-control @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}"
                                       placeholder="e.g., ENG, SCI"
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Short, unique identifier. Use uppercase letters and avoid spaces.</small>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="e.g., Department of Engineering"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter the full official name of the department.</small>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create Department
                                </button>
                                <a href="{{ route('superadmin.departments.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Guidelines</h4></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-info">Department Code</h6>
                            <small class="text-muted">
                                Use a short uppercase code (e.g., ENG for Engineering, SCI for Science).
                                This must be unique and will be used to reference the department across the system.
                            </small>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-info">Department Name</h6>
                            <small class="text-muted">
                                Enter the full official name (e.g., "Department of Engineering").
                                Avoid abbreviations in the name field.
                            </small>
                        </div>
                        <div>
                            <h6 class="text-warning">Validation</h6>
                            <small class="text-muted">
                                Both Code and Name are required and must be unique. You'll see validation messages
                                under the fields when something needs attention.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
     
    </div> 
</div>
@endsection
