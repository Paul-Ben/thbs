@extends('student.layout')
@section('content')
    <div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Student</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl me-3">
                            <img src="{{ asset('assets/img/profile.jpg') }}" alt="Profile" class="avatar-img rounded-circle">
                        </div>
                        <div>
                            <h3 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                            <p class="text-muted mb-0">
                                @if($authUser->userRole == 'Student')
                                    Matric Number: {{ $student->matric_number ?? 'Not Assigned' }} | 
                                    Programme: {{ $student->programme->name ?? 'Not Assigned' }}
                                @else
                                    Complete your profile to get started
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Registered Courses</p>
                                <h4 class="card-title">{{ $registeredCourses ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Current CGPA</p>
                                <h4 class="card-title">{{ $currentCGPA ?? 'N/A' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Outstanding Fees</p>
                                <h4 class="card-title">₦{{ number_format($outstandingFees ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Current Level</p>
                                <h4 class="card-title">{{ $currentLevel ?? 'N/A' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Quick Actions</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('student.course-registration.current') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-book me-2"></i>
                                Course Registration
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('student.payments.fees') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-credit-card me-2"></i>
                                Pay Fees
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('student.results') }}" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-chart-line me-2"></i>
                                View Results
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('student.biodata') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-user me-2"></i>
                                Update Biodata
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Academic Calendar</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Course Registration</h6>
                                <p class="timeline-text">Registration opens for new semester</p>
                                <small class="text-muted">{{ $registrationDate ?? 'TBA' }}</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Semester Begins</h6>
                                <p class="timeline-text">Classes commence</p>
                                <small class="text-muted">{{ $semesterStartDate ?? 'TBA' }}</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Mid-Semester Exams</h6>
                                <p class="timeline-text">Continuous assessment period</p>
                                <small class="text-muted">{{ $midSemesterDate ?? 'TBA' }}</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Final Exams</h6>
                                <p class="timeline-text">End of semester examinations</p>
                                <small class="text-muted">{{ $finalExamDate ?? 'TBA' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Recent Activities</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('student.payments.history') }}">View All Payments</a>
                                    <a class="dropdown-item" href="{{ route('student.course-registration.history') }}">View Registration History</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Activity</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" class="text-end">Date</th>
                                    <th scope="col" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities ?? [] as $activity)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <div class="avatar-title bg-{{ $activity['color'] ?? 'primary' }} rounded-circle">
                                                        <i class="{{ $activity['icon'] ?? 'fas fa-info' }}"></i>
                                                    </div>
                                                </div>
                                                <span class="fw-bold">{{ $activity['type'] ?? 'Activity' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $activity['description'] ?? 'No description' }}</td>
                                        <td class="text-end">{{ $activity['date'] ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $activity['status_color'] ?? 'secondary' }}">
                                                {{ $activity['status'] ?? 'Unknown' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No Recent Activities</h5>
                                                <p class="text-muted">Your recent activities will appear here.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Announcements</div>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($announcements ?? [] as $announcement)
                        <div class="alert alert-{{ $announcement['type'] ?? 'info' }} alert-dismissible fade show" role="alert">
                            <strong>{{ $announcement['title'] ?? 'Announcement' }}</strong>
                            {{ $announcement['message'] ?? 'No message' }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @empty
                        <div class="alert alert-info" role="alert">
                            <strong>Welcome!</strong> No announcements at this time. Check back later for updates.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-dismiss alerts after 10 seconds
        setTimeout(function() {
            $('.alert-dismissible').alert('close');
        }, 10000);
    });
</script>
@endpush