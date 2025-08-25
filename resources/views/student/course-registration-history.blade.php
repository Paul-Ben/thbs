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
    
    @if(session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Summary Statistics -->
    @if(!empty($registrationHistory) && isset($student) && $student)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <i class="fas fa-calendar-alt fa-2x text-primary me-2"></i>
                            <h4 class="text-primary mb-0">{{ $totalSessions }}</h4>
                        </div>
                        <p class="text-muted mb-0">Academic Sessions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <i class="fas fa-book fa-2x text-success me-2"></i>
                            <h4 class="text-success mb-0">{{ $totalCourses }}</h4>
                        </div>
                        <p class="text-muted mb-0">Total Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <i class="fas fa-calculator fa-2x text-info me-2"></i>
                            <h4 class="text-info mb-0">{{ $totalUnits }}</h4>
                        </div>
                        <p class="text-muted mb-0">Total Units</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-round">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <i class="fas fa-chart-line fa-2x text-warning me-2"></i>
                            <h4 class="text-warning mb-0">{{ $averageUnitsPerSemester }}</h4>
                        </div>
                        <p class="text-muted mb-0">Avg Units/Semester</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <!-- Registration History -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">
                            Course Registration History
                            @if(isset($student) && $student)
                                <small class="text-muted d-block">{{ $student->user->name ?? $student->applicant_name }} - {{ $student->matric_number ?? 'No Matric Number' }}</small>
                            @endif
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('student.course-registration.current') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> New Registration
                            </a>
                            <button class="btn btn-secondary btn-sm" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($student) && $student)
                        @forelse($registrationHistory as $sessionName => $semesters)
                            <div class="mb-4">
                                <h5 class="text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-calendar-alt me-2"></i>{{ $sessionName }}
                                </h5>
                                
                                @foreach($semesters as $semesterName => $registration)
                                    <div class="card mb-3 border shadow-sm">
                                        <div class="card-header bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">
                                                    <i class="fas fa-book-open me-2"></i>{{ $semesterName }}
                                                </h6>
                                                <div>
                                                    <span class="badge bg-info me-2">{{ $registration['course_count'] }} Courses</span>
                                                    <span class="badge bg-primary me-2">{{ $registration['total_units'] }} Units</span>
                                                    <span class="badge bg-{{ $registration['status'] == 'approved' ? 'success' : ($registration['status'] == 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($registration['status']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>Registration Date:</strong> 
                                                        <span class="text-muted">{{ $registration['registration_date'] }}</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>Level:</strong> 
                                                        <span class="badge bg-secondary">{{ $registration['level'] }}</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="mb-1"><strong>Actions:</strong>
                                                        @if(isset($registration['semester_id']))
                                                            <a href="{{ route('student.results.semester', $registration['semester_id']) }}" class="btn btn-outline-success btn-sm">
                                                                <i class="fas fa-chart-line me-1"></i> View Results
                                                            </a>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th width="15%">Course Code</th>
                                                            <th width="45%">Course Title</th>
                                                            <th width="12%">Units</th>
                                                            <th width="15%">Type</th>
                                                            <th width="13%">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($registration['courses'] as $course)
                                                            <tr>
                                                                <td><strong class="text-primary">{{ $course['code'] }}</strong></td>
                                                                <td>{{ $course['title'] }}</td>
                                                                <td>
                                                                    <span class="badge bg-primary">{{ $course['units'] }} Units</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-{{ strtolower($course['type']) === 'compulsory' ? 'danger' : 'info' }}">
                                                                        {{ $course['type'] }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-{{ $course['status'] === 'approved' ? 'success' : ($course['status'] === 'pending' ? 'warning' : 'secondary') }}">
                                                                        {{ ucfirst($course['status']) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-light">
                                                        <tr>
                                                            <th colspan="2" class="text-end">Total:</th>
                                                            <th><span class="badge bg-success">{{ $registration['total_units'] }} Units</span></th>
                                                            <th colspan="2">{{ $registration['course_count'] }} Courses</th>
                                                        </tr>
                                                    </tfoot>
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-times fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Student Profile Not Found</h4>
                            <p class="text-muted">Please contact the administration to complete your student registration.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    @if(isset($student) && $student)
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Quick Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('student.course-registration.current') }}" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="fas fa-plus me-1"></i> New Registration
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('student.results') }}" class="btn btn-outline-success w-100 mb-2">
                                    <i class="fas fa-chart-line me-1"></i> Academic Results
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('student.payments.fees') }}" class="btn btn-outline-warning w-100 mb-2">
                                    <i class="fas fa-credit-card me-1"></i> Fee Payments
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('student.support') }}" class="btn btn-outline-info w-100 mb-2">
                                    <i class="fas fa-headset me-1"></i> Get Support
                                </a>
                            </div>
                        </div>
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

        // Search functionality
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.card-body .card').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Print specific semester registration
        $('.print-semester').on('click', function(e) {
            e.preventDefault();
            var semesterCard = $(this).closest('.card').clone();
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Course Registration - ${$(this).data('semester')}</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .card { border: 1px solid #ddd; margin: 20px; padding: 15px; }
                            .card-header { background-color: #f8f9fa; padding: 10px; margin-bottom: 10px; }
                            .badge { padding: 2px 6px; font-size: 11px; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f2f2f2; }
                        </style>
                    </head>
                    <body>
                        <h2>Course Registration History</h2>
                        ${semesterCard.html()}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        });
    });
</script>

<style>
    @media print {
        .card-tools, .btn, .breadcrumbs {
            display: none !important;
        }
        
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
        
        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
        }
    }
</style>
@endsection