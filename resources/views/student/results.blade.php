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
                    <li class="nav-item"><a href="#">Academic Results</a></li>
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
        <!-- Academic Summary -->
        <div class="col-md-12 mb-4">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Academic Performance Summary</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary mb-1">{{ number_format($overallCGPA, 2) }}</h3>
                                <p class="text-muted mb-0">Cumulative GPA</p>
                                <small class="text-muted">Out of 5.00</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success mb-1">{{ $totalUnitsEarned }}</h3>
                                <p class="text-muted mb-0">Units Earned</p>
                                <small class="text-muted">Total Credits</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info mb-1">{{ $currentLevel }}</h3>
                                <p class="text-muted mb-0">Current Level</p>
                                <small class="text-muted">Academic Standing</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-warning mb-1">{{ $academicStatus }}</h3>
                                <p class="text-muted mb-0">Academic Status</p>
                                <small class="text-muted">Current Standing</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="col-md-12 mb-4">
            <div class="card card-round">
                <div class="card-body">
                    <form method="GET" action="{{ route('student.results.all') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="session_filter" class="form-label">Academic Session</label>
                            <select class="form-select" id="session_filter" name="session">
                                <option value="">All Sessions</option>
                                @foreach($availableSessions as $session)
                                    <option value="{{ $session->id }}" {{ request('session') == $session->id ? 'selected' : '' }}>
                                        {{ $session->session_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="semester_filter" class="form-label">Semester</label>
                            <select class="form-select" id="semester_filter" name="semester">
                                <option value="">All Semesters</option>
                                @foreach($availableSemesters as $semester)
                                    <option value="{{ $semester->id }}" {{ request('semester') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->semester_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="level_filter" class="form-label">Level</label>
                            <select class="form-select" id="level_filter" name="level">
                                <option value="">All Levels</option>
                                @foreach($availableLevels as $level)
                                    <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>
                                        {{ $level->level_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('student.results.all') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results by Session/Semester -->
        <div class="col-md-12">
            @forelse($resultsBySemester as $sessionName => $semesters)
                <div class="mb-4">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-calendar-alt me-2"></i>{{ $sessionName }}
                    </h4>
                    
                    @foreach($semesters as $semesterName => $semesterData)
                        <div class="card card-round mb-3">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $semesterName }}</h5>
                                    <div>
                                        <span class="badge bg-primary me-2">GPA: {{ number_format($semesterData['gpa'], 2) }}</span>
                                        <span class="badge bg-info me-2">{{ $semesterData['total_units'] }} Units</span>
                                        <span class="badge bg-success">{{ count($semesterData['results']) }} Courses</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Title</th>
                                                <th>Units</th>
                                                <th>Score</th>
                                                <th>Grade</th>
                                                <th>Grade Point</th>
                                                <th>Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($semesterData['results'] as $result)
                                                <tr>
                                                    <td><strong>{{ $result['course_code'] }}</strong></td>
                                                    <td>{{ $result['course_title'] }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $result['units'] }}</span>
                                                    </td>
                                                    <td>
                                                        <strong class="text-{{ $result['score'] >= 70 ? 'success' : ($result['score'] >= 60 ? 'warning' : ($result['score'] >= 50 ? 'info' : 'danger')) }}">
                                                            {{ $result['score'] }}%
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $result['grade_color'] }}">
                                                            {{ $result['grade'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong>{{ number_format($result['grade_point'], 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="text-{{ $result['remark'] == 'PASS' ? 'success' : 'danger' }}">
                                                            {{ $result['remark'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="2">Semester Summary</th>
                                                <th><span class="badge bg-info">{{ $semesterData['total_units'] }}</span></th>
                                                <th>-</th>
                                                <th>-</th>
                                                <th><strong>{{ number_format($semesterData['gpa'], 2) }}</strong></th>
                                                <th>
                                                    <span class="text-{{ $semesterData['gpa'] >= 3.5 ? 'success' : ($semesterData['gpa'] >= 2.5 ? 'warning' : 'danger') }}">
                                                        {{ $semesterData['gpa'] >= 3.5 ? 'EXCELLENT' : ($semesterData['gpa'] >= 2.5 ? 'GOOD' : 'POOR') }}
                                                    </span>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="card card-round">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No Academic Results</h4>
                        <p class="text-muted mb-4">Your academic results will appear here once they are published by the institution.</p>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Grade Scale Modal -->
    <div class="modal fade" id="gradeScaleModal" tabindex="-1" aria-labelledby="gradeScaleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gradeScaleModalLabel">Grading Scale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Score Range</th>
                                    <th>Grade</th>
                                    <th>Grade Point</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>70 - 100</td>
                                    <td><span class="badge bg-success">A</span></td>
                                    <td>5.0</td>
                                    <td>Excellent</td>
                                </tr>
                                <tr>
                                    <td>60 - 69</td>
                                    <td><span class="badge bg-primary">B</span></td>
                                    <td>4.0</td>
                                    <td>Very Good</td>
                                </tr>
                                <tr>
                                    <td>50 - 59</td>
                                    <td><span class="badge bg-info">C</span></td>
                                    <td>3.0</td>
                                    <td>Good</td>
                                </tr>
                                <tr>
                                    <td>45 - 49</td>
                                    <td><span class="badge bg-warning">D</span></td>
                                    <td>2.0</td>
                                    <td>Fair</td>
                                </tr>
                                <tr>
                                    <td>40 - 44</td>
                                    <td><span class="badge bg-secondary">E</span></td>
                                    <td>1.0</td>
                                    <td>Pass</td>
                                </tr>
                                <tr>
                                    <td>0 - 39</td>
                                    <td><span class="badge bg-danger">F</span></td>
                                    <td>0.0</td>
                                    <td>Fail</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Grade Scale -->
    <div class="position-fixed" style="bottom: 20px; right: 20px; z-index: 1000;">
        <button type="button" class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#gradeScaleModal" title="View Grading Scale">
            <i class="fas fa-info"></i>
        </button>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Auto-submit form when filters change
        $('#session_filter, #semester_filter, #level_filter').on('change', function() {
            $(this).closest('form').submit();
        });
        
        // Add smooth scrolling for semester navigation
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });
        
        // Highlight current semester results
        $('.card').hover(
            function() {
                $(this).addClass('shadow-lg').css('transform', 'translateY(-2px)');
            },
            function() {
                $(this).removeClass('shadow-lg').css('transform', 'translateY(0)');
            }
        );
        
        // Print functionality
        window.printResults = function() {
            window.print();
        };
    });
</script>

<style>
    @media print {
        .btn, .breadcrumbs, .modal, .position-fixed {
            display: none !important;
        }
        
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
        
        .table {
            font-size: 12px;
        }
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .btn-round {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection