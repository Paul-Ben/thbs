@extends('student.layout')
@section('content')
    <div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="{{ route('student.dashboard') }}"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="{{ route('student.dashboard') }}">Student</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Course Registration History</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <!-- Registration History -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Course Registration History</div>
                        <div class="card-tools">
                            <a href="{{ route('student.course-registration.current') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> New Registration
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($registrationHistory as $sessionName => $semesters)
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>{{ $sessionName }}
                            </h5>
                            
                            @foreach($semesters as $semesterName => $registration)
                                <div class="card mb-3 border">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $semesterName }}</h6>
                                            <div>
                                                <span class="badge bg-info me-2">{{ count($registration['courses']) }} Courses</span>
                                                <span class="badge bg-primary">{{ $registration['total_units'] }} Units</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Registration Date:</strong> {{ $registration['registration_date'] }}</p>
                                                <p class="mb-0"><strong>Level:</strong> {{ $registration['level'] }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Status:</strong> 
                                                    <span class="badge bg-{{ $registration['status'] == 'approved' ? 'success' : ($registration['status'] == 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($registration['status']) }}
                                                    </span>
                                                </p>
                                                <p class="mb-0"><strong>Total Units:</strong> {{ $registration['total_units'] }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Course Code</th>
                                                        <th>Course Title</th>
                                                        <th>Units</th>
                                                        <th>Type</th>
                                                        <th>Grade</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($registration['courses'] as $course)
                                                        <tr>
                                                            <td><strong>{{ $course['code'] }}</strong></td>
                                                            <td>{{ $course['title'] }}</td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $course['units'] }} Units</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-{{ $course['type'] == 'compulsory' ? 'danger' : 'info' }}">
                                                                    {{ ucfirst($course['type']) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if(isset($course['grade']))
                                                                    <span class="badge bg-{{ $course['grade_color'] ?? 'secondary' }}">
                                                                        {{ $course['grade'] }}
                                                                    </span>
                                                                @else
                                                                    <small class="text-muted">Not Available</small>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Registration History</h4>
                            <p class="text-muted mb-4">You haven't registered for any courses yet.</p>
                            <a href="{{ route('student.course-registration.current') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Start Course Registration
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    @if(!empty($registrationHistory))
        <div class="row">
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <h4 class="text-primary mb-1">{{ $totalSessions }}</h4>
                        <p class="text-muted mb-0">Academic Sessions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <h4 class="text-success mb-1">{{ $totalCourses }}</h4>
                        <p class="text-muted mb-0">Total Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <h4 class="text-info mb-1">{{ $totalUnits }}</h4>
                        <p class="text-muted mb-0">Total Units</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <h4 class="text-warning mb-1">{{ number_format($averageUnitsPerSemester, 1) }}</h4>
                        <p class="text-muted mb-0">Avg Units/Semester</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Add smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });
    });
</script>
@endsection