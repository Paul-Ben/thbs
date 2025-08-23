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
    
    @if(session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            @if(isset($feePaymentRequired) && $feePaymentRequired)
                <hr>
                <a href="{{ route('student.payments.fees') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-credit-card me-1"></i> Pay School Fees
                </a>
            @endif
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Warning!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Registration Deadline Notice -->
    @if(isset($currentSemester) && $currentSemester && $currentSemester->registration_end_date)
        @php
            $daysRemaining = \Carbon\Carbon::now()->diffInDays($currentSemester->registration_end_date, false);
        @endphp
        <div class="alert {{ $daysRemaining >= 0 ? 'alert-info' : 'alert-danger' }} alert-dismissible fade show" role="alert">
            <i class="fas fa-clock me-2"></i>
            <strong>Registration Deadline:</strong> 
            Course registration closes on {{ \Carbon\Carbon::parse($currentSemester->registration_end_date)->format('F j, Y') }}
            @if($daysRemaining >= 0)
                ({{ $daysRemaining }} day{{ $daysRemaining != 1 ? 's' : '' }} remaining)
            @else
                <span class="text-danger">(Registration Closed)</span>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Course Registration Form -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">
                            Current Semester Course Registration
                            @if(isset($currentSemester) && $currentSemester)
                                <small class="text-muted d-block">{{ $currentSemester->semester_name }} - {{ $currentSemester->schoolSession->session_name ?? 'Current Session' }}</small>
                            @endif
                        </div>
                        <div class="card-tools">
                            <a href="{{ route('student.course-registration.history') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-history me-1"></i> View History
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($student) && $student)
                        <!-- Student Information -->
                        <div class="row mb-4 p-3 bg-light rounded">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Student:</strong> {{ $student->user->name ?? $student->applicant_name }}</p>
                                <p class="mb-1"><strong>Matric Number:</strong> {{ $student->matric_number ?? 'Not Assigned' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Programme:</strong> {{ $student->programme->name ?? 'Not Set' }}</p>
                                <p class="mb-1"><strong>Level:</strong> {{ $student->level->name ?? 'Not Set' }}</p>
                            </div>
                        </div>

                        @if(!isset($feePaymentRequired) || !$feePaymentRequired)
                            @if(isset($canRegister) && $canRegister)
                                <form method="POST" action="{{ route('student.course-registration.store') }}" id="courseRegistrationForm">
                                    @csrf
                                    
                                    <!-- Available Courses -->
                                    <div class="mb-4">
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-book me-2"></i>Available Courses
                                        </h5>
                                        
                                        @if($availableCourses->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="50px">
                                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                                            </th>
                                                            <th>Course Code</th>
                                                            <th>Course Title</th>
                                                            <th>Credit Units</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($availableCourses as $course)
                                                            <tr class="course-row">
                                                                <td>
                                                                    <input type="checkbox" 
                                                                           name="courses[]" 
                                                                           value="{{ $course->id }}" 
                                                                           class="form-check-input course-checkbox"
                                                                           data-units="{{ $course->credit_units }}"
                                                                           @if(in_array($course->id, $registeredCourseIds)) checked @endif>
                                                                </td>
                                                                <td><strong>{{ $course->code }}</strong></td>
                                                                <td>{{ $course->title }}</td>
                                                                <td>
                                                                    <span class="badge bg-primary">{{ $course->credit_units }} Units</span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-{{ isset($course->course_type) && strtolower($course->course_type) === 'compulsory' ? 'danger' : 'info' }}">
                                                                        {{ $course->course_type ?? 'Elective' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if(in_array($course->id, $registeredCourseIds))
                                                                        <span class="badge bg-success">Registered</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Available</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center mt-4">
                                                <div>
                                                    <span class="text-muted">Selected: </span>
                                                    <span id="selectedCount" class="badge bg-info">{{ count($registeredCourseIds) }}</span>
                                                    <span class="text-muted ms-3">Total Units: </span>
                                                    <span id="totalUnits" class="badge bg-success">{{ $totalUnits ?? 0 }}</span>
                                                </div>
                                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                                    <i class="fas fa-save me-1"></i> Update Registration
                                                </button>
                                            </div>

                                            <!-- Unit Guidelines -->
                                            <div class="mt-3 p-3 bg-light rounded">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    <strong>Guidelines:</strong> 
                                                    Minimum: 15 units | Maximum: 24 units | 
                                                    <span class="text-warning">Warning shown for units below 15 or above 24</span>
                                                </small>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-book fa-4x text-muted mb-3"></i>
                                                <h4 class="text-muted">No Courses Available</h4>
                                                <p class="text-muted">No courses are currently available for registration in your programme and level.</p>
                                                <div class="mt-3">
                                                    <a href="{{ route('student.support') }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-headset me-1"></i> Contact Support
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-lock fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">Registration Not Available</h4>
                                    <p class="text-muted">Course registration is currently not available.</p>
                                </div>
                            @endif
                        @endif
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

        <div class="col-md-4">
            <!-- Registration Summary -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Registration Summary</div>
                </div>
                <div class="card-body">
                    @if(isset($student) && $student && isset($registeredCourses))
                        <div class="mb-3">
                            <h6 class="text-muted">Currently Registered Courses</h6>
                            @if($registeredCourses->count() > 0)
                                <div class="max-height-200" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($registeredCourses as $registration)
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                            <div>
                                                <small class="fw-bold">{{ $registration->course->code }}</small>
                                                <div class="text-muted small">{{ Str::limit($registration->course->title, 25) }}</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-primary d-block mb-1">{{ $registration->course->credit_units }} Units</span>
                                                <span class="badge bg-{{ $registration->status == 'approved' ? 'success' : ($registration->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($registration->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total Courses:</strong>
                                    <span class="badge bg-info">{{ $registeredCourses->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <strong>Total Units:</strong>
                                    <span class="badge bg-success">{{ $totalUnits ?? 0 }} Units</span>
                                </div>
                            @else
                                <p class="text-muted small">No courses registered yet.</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted">Registration Guidelines</h6>
                            <ul class="list-unstyled small text-muted">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Select all required courses for your level</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Minimum units per semester: 15</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Maximum units per semester: 24</li>
                                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Ensure fees are paid before registration</li>
                                <li><i class="fas fa-clock text-info me-2"></i>Registration deadline must be observed</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Quick Actions</div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.course-registration.history') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-history me-1"></i> Registration History
                        </a>
                        <a href="{{ route('student.payments.fees') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-credit-card me-1"></i> Fee Payments
                        </a>
                        <a href="{{ route('student.results') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-chart-line me-1"></i> Academic Results
                        </a>
                        <a href="{{ route('student.support') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-headset me-1"></i> Get Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Calculate initial total units
        updateRegistrationSummary();
        
        // Select/Deselect all courses
        $('#selectAll').on('change', function() {
            $('.course-checkbox').prop('checked', $(this).is(':checked'));
            updateRegistrationSummary();
        });
        
        // Individual course selection
        $('.course-checkbox').on('change', function() {
            updateRegistrationSummary();
            
            // Update select all checkbox
            var totalCheckboxes = $('.course-checkbox').length;
            var checkedCheckboxes = $('.course-checkbox:checked').length;
            $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
            $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
        });
        
        // Form submission validation
        $('#courseRegistrationForm').on('submit', function(e) {
            var selectedCourses = $('.course-checkbox:checked').length;
            var totalUnits = parseInt($('#totalUnits').text()) || 0;
            
            if (selectedCourses === 0) {
                e.preventDefault();
                alert('Please select at least one course to register.');
                return false;
            }
            
            if (totalUnits < 15) {
                if (!confirm('You have selected ' + totalUnits + ' units which is below the recommended minimum of 15 units. Do you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (totalUnits > 24) {
                if (!confirm('You have selected ' + totalUnits + ' units which exceeds the recommended maximum of 24 units. Do you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
        });
        
        function updateRegistrationSummary() {
            var selectedCount = $('.course-checkbox:checked').length;
            var totalUnits = 0;
            
            $('.course-checkbox:checked').each(function() {
                totalUnits += parseInt($(this).data('units')) || 0;
            });
            
            $('#selectedCount').text(selectedCount);
            $('#totalUnits').text(totalUnits);
            
            // Update submit button state
            if (selectedCount > 0) {
                $('#submitBtn').prop('disabled', false);
            } else {
                $('#submitBtn').prop('disabled', true);
            }
            
            // Color coding for units
            var unitsElement = $('#totalUnits');
            unitsElement.removeClass('bg-success bg-warning bg-danger');
            
            if (totalUnits < 15) {
                unitsElement.addClass('bg-warning');
            } else if (totalUnits > 24) {
                unitsElement.addClass('bg-danger');
            } else {
                unitsElement.addClass('bg-success');
            }
        }
        
        // Auto-refresh page if registration deadline passes
        @if(isset($currentSemester) && $currentSemester && $currentSemester->registration_end_date)
            var deadlineTime = new Date('{{ $currentSemester->registration_end_date }}').getTime();
            var now = new Date().getTime();
            
            if (deadlineTime > now) {
                var timeToDeadline = deadlineTime - now;
                setTimeout(function() {
                    location.reload();
                }, timeToDeadline + 1000); // Refresh 1 second after deadline
            }
        @endif
    });
</script>
@endsection