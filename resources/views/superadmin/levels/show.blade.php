@extends('layouts.dashboard')

@section('title', 'Level Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-layer-group"></i> Level Details
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.levels.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Level Name:</label>
                                <p class="form-control-static">{{ $level->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Semester:</label>
                                <p class="form-control-static">
                                    <span class="badge bg-info">{{ $level->semester->semester_name }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Created At:</label>
                                <p class="form-control-static">{{ $level->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated:</label>
                                <p class="form-control-static">{{ $level->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('superadmin.levels.edit', $level) }}" class="btn btn-warning me-2">
                                    <i class="fas fa-edit"></i> Edit Level
                                </a>
                                <form action="{{ route('superadmin.levels.destroy', $level) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this level? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete Level
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection