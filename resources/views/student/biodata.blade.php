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
                    <li class="nav-item"><a href="#">Biodata</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <!-- Profile Picture Card -->
            <div class="card card-round">
                <div class="card-body text-center">
                    <div class="avatar avatar-xxl mx-auto mb-3">
                        <img src="{{ asset('assets/img/profile.jpg') }}" alt="Profile" class="avatar-img rounded-circle">
                    </div>
                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-3">
                       
                        @if($authUser->userRole == 'Student')
                            {{ $student->matric_number ?? 'Matric Number Not Assigned' }}
                        @else
                            Student Profile Incomplete
                        @endif
                      
                    </p>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                        <i class="fas fa-camera me-1"></i> Change Photo
                    </button>
                </div>
            </div>

            <!-- Quick Info Card -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Quick Information</div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-5"><strong>Status:</strong></div>
                        <div class="col-7">
                            @if($authUser->userRole == 'Student')
                                <span class="badge bg-success">Active Student</span>
                            @else
                                <span class="badge bg-warning">Incomplete Profile</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Level:</strong></div>
                        <div class="col-7">{{ $student->level->name ?? 'Not Set' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Programme:</strong></div>
                        <div class="col-7">{{ $student->programme->name ?? 'Not Set' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Faculty:</strong></div>
                        <div class="col-7">{{ $student->programme->faculty->faculty_name ?? 'Not Set' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Personal Information Card -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Personal Information</div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="editBiodataBtn">
                                <i class="fas fa-edit me-1"></i> Edit Information
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="biodataForm" method="POST" action="{{ route('student.biodata.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ old('first_name', $student->applicant_name ?? '') }}" 
                                           readonly required>
                                    @error('first_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ old('last_name', $student->last_name ?? '') }}" 
                                           readonly required>
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                           value="{{ old('middle_name', $student->middle_name ?? '') }}" 
                                           readonly>
                                    @error('middle_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $authUser->email) }}" 
                                           readonly required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                           value="{{ old('phone_number', $student->phone_number ?? '') }}" 
                                           readonly required>
                                    @error('phone_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}" 
                                           readonly required>
                                    @error('date_of_birth')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control" id="gender" name="gender" disabled required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $student->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nationality">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" 
                                           value="{{ old('nationality', $student->nationality ?? '') }}" 
                                           readonly required>
                                    @error('nationality')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state_of_origin">State of Origin <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="state_of_origin" name="state_of_origin" 
                                           value="{{ old('state_of_origin', $student->state_of_origin ?? '') }}" 
                                           readonly required>
                                    @error('state_of_origin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lga">Local Government Area</label>
                                    <input type="text" class="form-control" id="lga" name="lga" 
                                           value="{{ old('lga', $student->lga ?? '') }}" 
                                           readonly>
                                    @error('lga')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Home Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" 
                                      readonly required>{{ old('address', $student->address ?? '') }}</textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Emergency Contact Information -->
                        <hr>
                        <h5 class="mb-3">Emergency Contact Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name', $student->emergency_contact_name ?? '') }}" 
                                           readonly required>
                                    @error('emergency_contact_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_phone">Emergency Contact Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" 
                                           value="{{ old('emergency_contact_phone', $student->emergency_contact_phone ?? '') }}" 
                                           readonly required>
                                    @error('emergency_contact_phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_relationship">Relationship <span class="text-danger">*</span></label>
                                    <select class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship" disabled required>
                                        <option value="">Select Relationship</option>
                                        <option value="parent" {{ old('emergency_contact_relationship', $student->emergency_contact_relationship ?? '') == 'parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="guardian" {{ old('emergency_contact_relationship', $student->emergency_contact_relationship ?? '') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                                        <option value="sibling" {{ old('emergency_contact_relationship', $student->emergency_contact_relationship ?? '') == 'sibling' ? 'selected' : '' }}>Sibling</option>
                                        <option value="spouse" {{ old('emergency_contact_relationship', $student->emergency_contact_relationship ?? '') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="other" {{ old('emergency_contact_relationship', $student->emergency_contact_relationship ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('emergency_contact_relationship')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_address">Emergency Contact Address</label>
                                    <input type="text" class="form-control" id="emergency_contact_address" name="emergency_contact_address" 
                                           value="{{ old('emergency_contact_address', $student->emergency_contact_address ?? '') }}" 
                                           readonly>
                                    @error('emergency_contact_address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4" id="formActions" style="display: none;">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Photo Modal -->
    <div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePhotoModalLabel">Change Profile Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('student.biodata.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="profile_photo">Select New Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" 
                                   accept="image/*" required>
                            <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                        </div>
                        <div class="mt-3">
                            <img id="photoPreview" src="#" alt="Photo Preview" 
                                 style="max-width: 200px; max-height: 200px; display: none;" class="img-thumbnail">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Edit biodata functionality
    $('#editBiodataBtn').click(function() {
        // Enable form fields
        $('#biodataForm input, #biodataForm select, #biodataForm textarea').prop('readonly', false).prop('disabled', false);
        
        // Show form actions
        $('#formActions').show();
        
        // Hide edit button
        $(this).hide();
    });
    
    // Cancel edit functionality
    $('#cancelEditBtn').click(function() {
        // Disable form fields
        $('#biodataForm input, #biodataForm select, #biodataForm textarea').prop('readonly', true);
        $('#biodataForm select').prop('disabled', true);
        
        // Hide form actions
        $('#formActions').hide();
        
        // Show edit button
        $('#editBiodataBtn').show();
        
        // Reset form to original values
        $('#biodataForm')[0].reset();
    });
    
    // Photo preview functionality
    $('#profile_photo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Form validation
    $('#biodataForm').submit(function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endpush