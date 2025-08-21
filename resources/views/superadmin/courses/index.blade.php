@extends('layouts.dashboard')

@section('title', 'Course Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Course Management</h3>
                    <a href="{{ route('superadmin.courses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Course
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Programme</th>
                                    <th>Level</th>
                                    <th>Semester</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                    <tr>
                                        <td>{{ $course->id }}</td>
                                        <td><strong>{{ $course->code }}</strong></td>
                                        <td>{{ $course->title }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $course->programme->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $course->level->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $course->semester->semester_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('superadmin.courses.show', $course) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('superadmin.courses.edit', $course) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('superadmin.courses.destroy', $course) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No courses found</h5>
                                                <p class="text-muted">Start by creating your first course.</p>
                                                <a href="{{ route('superadmin.courses.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Add New Course
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($courses->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $courses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush