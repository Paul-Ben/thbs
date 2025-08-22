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
                    <li class="nav-item"><a href="#">Course Registration</a></li>
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

    <!-- Registration Information -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Course Registration - Current Semester</div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" id="submitRegistrationBtn">
                                <i class="fas fa-check me-1"></i> Submit Registration
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="courseRegistrationForm" method="POST" action="{{ route('student.course-registration.store') }}">
                        @csrf
                        
                        <!-- Student Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Student Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                <p class="mb-1"><strong>Matric Number:</strong> {{ Auth::user()->student->matric_number ?? 'Not Assigned' }}</p>
                                <p class="mb-1"><strong>Programme:</strong> {{ $student->programme->name ?? 'Not Set' }}</p>
                                <p class="mb-0"><strong>Level:</strong> {{ $student->current_level ?? '100 Level' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Registration Summary</h6>
                                <p class="mb-1"><strong>Total Courses:</strong> <span id="totalCourses">0</span></p>
                                <p class="mb-1"><strong>Total Units:</strong> <span id="totalUnits">0</span></p>
                                <p class="mb-1"><strong>Min Units Required:</strong> 15</p>
                                <p class="mb-0"><strong>Max Units Allowed:</strong> 24</p>
                            </div>
                        </div>

                        <!-- Course Selection -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Units</th>
                                        <th>Type</th>
                                        <th>Semester</th>
                                        <th>Prerequisites</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($availableCourses as $course)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input course-checkbox" 
                                                           type="checkbox" 
                                                           name="courses[]" 
                                                           value="{{ $course->id }}" 
                                                           id="course_{{ $course->id }}"
                                                           data-units="3"
                                                           {{ in_array($course->id, $registeredCourseIds) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="course_{{ $course->id }}"></label>
                                                </div>
                                            </td>
                                            <td><strong>{{ $course->code }}</strong></td>
                                            <td>{{ $course->title }}</td>
                                            <td>
                                                <span class="badge bg-primary">3 Units</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    Compulsory
                                                </span>
                                            </td>
                                            <td>{{ $course->semester->semester_name ?? 'Current' }}</td>
                                            <td>
                                                <small class="text-muted">None</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No Courses Available</h5>
                                                    <p class="text-muted">No courses are available for registration at this time.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($canRegister ?? true)
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-1"></i> Save Registration
                                </button>
                                <button type="button" class="btn btn-secondary" id="clearSelectionBtn">
                                    <i class="fas fa-times me-1"></i> Clear Selection
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Registration Guidelines -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Registration Guidelines</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i> Important Notes:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Minimum {{ $minUnits ?? 15 }} units required</li>
                            <li>Maximum {{ $maxUnits ?? 24 }} units allowed</li>
                            <li>All compulsory courses must be selected</li>
                            <li>Check prerequisites before selecting courses</li>
                            <li>Registration deadline: {{ $registrationDeadline ?? 'TBA' }}</li>
                        </ul>
                    </div>
                    
                    <div class="mt-3">
                        <h6>Course Types:</h6>
                        <div class="mb-2">
                            <span class="badge bg-danger me-1">Compulsory</span>
                            <small class="text-muted">Must be taken</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-info me-1">Elective</span>
                            <small class="text-muted">Optional courses</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration History -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Recent Registrations</div>
                        <div class="card-tools">
                            <a href="{{ route('student.course-registration.history') }}" class="btn btn-link btn-sm">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($recentRegistrations ?? [] as $registration)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-title bg-{{ $registration['status_color'] ?? 'primary' }} rounded-circle">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $registration['semester'] ?? 'Unknown Semester' }}</h6>
                                <small class="text-muted">{{ $registration['courses_count'] ?? 0 }} courses, {{ $registration['total_units'] ?? 0 }} units</small>
                            </div>
                            <span class="badge bg-{{ $registration['status_color'] ?? 'secondary' }}">
                                {{ $registration['status'] ?? 'Unknown' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No previous registrations</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmRegistrationModal" tabindex="-1" aria-labelledby="confirmRegistrationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRegistrationModalLabel">Confirm Course Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit your course registration?</p>
                    <div class="alert alert-warning">
                        <strong>Note:</strong> Once submitted, you may not be able to modify your registration without approval.
                    </div>
                    <div id="registrationSummary">
                        <h6>Registration Summary:</h6>
                        <p><strong>Total Courses:</strong> <span id="modalTotalCourses">0</span></p>
                        <p><strong>Total Units:</strong> <span id="modalTotalUnits">0</span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmitBtn">
                        <i class="fas fa-check me-1"></i> Confirm Registration
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const minUnits = {{ $minUnits ?? 15 }};
    const maxUnits = {{ $maxUnits ?? 24 }};
    
    // Update totals when checkboxes change
    function updateTotals() {
        let totalCourses = 0;
        let totalUnits = 0;
        
        $('.course-checkbox:checked').each(function() {
            totalCourses++;
            totalUnits += parseInt($(this).data('units'));
        });
        
        $('#totalCourses').text(totalCourses);
        $('#totalUnits').text(totalUnits);
        $('#modalTotalCourses').text(totalCourses);
        $('#modalTotalUnits').text(totalUnits);
        
        // Validate units
        if (totalUnits < minUnits) {
            $('#totalUnits').removeClass('text-success text-danger').addClass('text-warning');
        } else if (totalUnits > maxUnits) {
            $('#totalUnits').removeClass('text-success text-warning').addClass('text-danger');
        } else {
            $('#totalUnits').removeClass('text-warning text-danger').addClass('text-success');
        }
    }
    
    // Course checkbox change event
    $('.course-checkbox').change(function() {
        updateTotals();
    });
    
    // Select all functionality
    $('#selectAll').change(function() {
        $('.course-checkbox').prop('checked', $(this).is(':checked'));
        updateTotals();
    });
    
    // Clear selection
    $('#clearSelectionBtn').click(function() {
        $('.course-checkbox, #selectAll').prop('checked', false);
        updateTotals();
    });
    
    // Submit registration button
    $('#submitRegistrationBtn').click(function() {
        const totalUnits = parseInt($('#totalUnits').text());
        
        if (totalUnits < minUnits) {
            alert(`You must register for at least ${minUnits} units.`);
            return;
        }
        
        if (totalUnits > maxUnits) {
            alert(`You cannot register for more than ${maxUnits} units.`);
            return;
        }
        
        $('#confirmRegistrationModal').modal('show');
    });
    
    // Confirm submit
    $('#confirmSubmitBtn').click(function() {
        $('#courseRegistrationForm').append('<input type="hidden" name="submit_registration" value="1">');
        $('#courseRegistrationForm').submit();
    });
    
    // Form validation
    $('#courseRegistrationForm').submit(function(e) {
        const totalUnits = parseInt($('#totalUnits').text());
        
        if (totalUnits < minUnits || totalUnits > maxUnits) {
            e.preventDefault();
            alert(`Please select courses between ${minUnits} and ${maxUnits} units.`);
        }
    });
    
    // Initialize totals
    updateTotals();
});
</script>
@endpush