<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>BSUTH - Application Form</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/favicon_io/favicon.ico') }}" type="image/x-icon" />
    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    
    <style>
        body {
            background: #f4f6fa;
        }

        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step .circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1a2035;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem auto;
            font-weight: bold;
            font-size: 1.2rem;
            border: 3px solid #f96332;
            transition: background 0.3s, border 0.3s;
            z-index: 2;
        }

        .step.active .circle,
        .step.completed .circle {
            background: #f96332;
            color: #fff;
            border: 3px solid #1a2035;
        }

        .step .label {
            font-size: 0.95rem;
            color: #1a2035;
            font-weight: 600;
        }

        /* Remove stepper connecting lines */
        .step:not(:last-child)::after {
            display: none !important;
        }

        .step.completed:not(:last-child)::after {
            background: #f96332;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .btn-brand {
            background: #1a2035;
            color: #fff;
            border: none;
        }

        .btn-brand:hover,
        .btn-brand:focus {
            background: #f96332;
            color: #fff;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(26, 32, 53, 0.08);
        }

        .card-header {
            background: #1a2035;
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            border-radius: 1rem 1rem 0 0;
        }

        .form-section-title {
            color: #f96332;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .progress {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            margin-bottom: 2rem;
        }

        .progress-bar {
            background: #f96332;
        }

        /* Passport preview */
        .passport-preview {
            width: 160px;
            height: 160px;
            background: #e0e0e0;
            border: 2px dashed #1a2035;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            overflow: hidden;
            position: relative;
        }

        .passport-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .add-record-btn {
            background: #f96332;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-left: 8px;
            transition: background 0.2s;
            padding: 0;
        }

        .add-record-btn .fa-plus {
            font-size: 0.9em;
        }

        .remove-record-btn {
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-left: 8px;
            transition: background 0.2s;
            padding: 0;
        }

        .remove-record-btn .fa-minus {
            font-size: 0.9em;
        }

        /* Wider O/Level table inputs */
        #olevelTable input.form-control {
            min-width: 140px;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        @media (max-width: 767px) {
            #olevelTable input.form-control {
                min-width: 90px;
            }
        }

        .add-record-btn:hover {
            background: #1a2035;
            color: #fff;
        }

        .remove-record-btn:hover {
            background: #a71d2a;
            color: #fff;
        }

        /* Upload button hover: orange bg, white text */
        .btn-outline-primary.upload-passport-btn:hover,
        .btn-outline-primary.upload-passport-btn:focus {
            background: #f96332 !important;
            color: #fff !important;
            border-color: #f96332 !important;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header text-center">Application Form</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('application.store', ['tx_ref' => $payment->reference]) }}" enctype="multipart/form-data"
                            id="multiStepForm">
                            @csrf
                            <!-- Stepper -->
                            <div class="stepper mb-4">
                                <div class="step step-1 active">
                                    <div class="circle">1</div>
                                    <div class="label">Personal Details</div>
                                </div>
                                <div class="step step-2">
                                    <div class="circle">2</div>
                                    <div class="label">Contact Information</div>
                                </div>
                                <div class="step step-3">
                                    <div class="circle">3</div>
                                    <div class="label">Guardian/Sponsor</div>
                                </div>
                                <div class="step step-4">
                                    <div class="circle">4</div>
                                    <div class="label">Academic Records</div>
                                </div>
                                <div class="step step-5">
                                    <div class="circle">5</div>
                                    <div class="label">Declaration</div>
                                </div>
                            </div>
                            <div class="progress mb-4">
                                <div class="progress-bar" role="progressbar" style="width: 20%" id="formProgress"></div>
                            </div>
                            <!-- @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif -->
                            <!-- Step 1: Personal Details -->
                            <div class="form-step active" id="step-1">
                                <div class="form-section-title">APPLICANT'S PERSONAL DETAILS</div>
                                <div class="row">
                                    <div class="col-12 d-flex flex-column align-items-center mb-3">
                                        <div class="passport-preview" id="passportPreview">
                                            <span class="text-muted">Passport Preview</span>
                                        </div>
                                        <label class="btn btn-outline-primary mt-2 upload-passport-btn">
                                            <i class="fa fa-upload me-2"></i> Upload Passport
                                            <input type="file" class="d-none" name="passport" id="passportInput"
                                                accept="image/*">
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Surname</label>
                                        <input type="text" class="form-control" name="applicant_surname"
                                            value="{{ $applicant->applicant_surname }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Other Names</label>
                                        <input type="text" class="form-control" name="applicant_othernames"
                                            value="{{ $applicant->applicant_othernames }}" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth"
                                            value="{{ old('date_of_birth') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Gender</label>
                                        <select class="form-select" name="gender">
                                            <option value="">Select</option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>State</label>
                                        <select class="form-select" name="state_of_origin" id="stateSelect">
                                            <option value="">Select State</option>
                                            <!-- options populated by JS, but you can set selected via JS if needed -->
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>LGA</label>
                                        <select class="form-select" name="lga" id="lgaSelect">
                                            <option value="">Select LGA</option>
                                            <!-- options populated by JS, but you can set selected via JS if needed -->
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Nationality</label>
                                        <input type="text" class="form-control" name="nationality"
                                            value="{{ old('nationality') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Religion</label>
                                        <select class="form-select" name="religion">
                                            <option value="">Select Religion</option>
                                            <option value="Christianity"
                                                {{ old('religion') == 'Christianity' ? 'selected' : '' }}>Christianity
                                            </option>
                                            <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>
                                                Islam</option>
                                            <option value="Traditional"
                                                {{ old('religion') == 'Traditional' ? 'selected' : '' }}>Traditional
                                            </option>
                                            <option value="Other" {{ old('religion') == 'Other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Marital Status</label>
                                        <select class="form-select" name="marital_status">
                                            <option value="">Select</option>
                                            <option value="Single"
                                                {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single
                                            </option>
                                            <option value="Married"
                                                {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married
                                            </option>
                                            <option value="Divorced"
                                                {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced
                                            </option>
                                            <option value="Widowed"
                                                {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-brand next-step">Next</button>
                                </div>
                            </div>
                            <!-- Step 2: Contact Information -->
                            <div class="form-step" id="step-2">
                                <div class="form-section-title">APPLICANT'S CONTACT INFORMATION</div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Home Town</label>
                                        <input type="text" class="form-control" name="home_town"
                                            value="{{ old('home_town') }}" >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>E-Mail</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ $applicant->email }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Phone No.</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ $applicant->phone }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Correspondence Address</label>
                                        <input type="text" class="form-control" name="correspondence_address"
                                            value="{{ old('correspondence_address') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Employment Status</label>
                                        <select class="form-select" name="employment_status">
                                            <option value="">Select Status</option>
                                            <option value="Employed"
                                                {{ old('employment_status') == 'Employed' ? 'selected' : '' }}>Employed
                                            </option>
                                            <option value="Self-Employed"
                                                {{ old('employment_status') == 'Self-Employed' ? 'selected' : '' }}>
                                                Self-Employed</option>
                                            <option value="Unemployed"
                                                {{ old('employment_status') == 'Unemployed' ? 'selected' : '' }}>
                                                Unemployed</option>
                                            <option value="Student"
                                                {{ old('employment_status') == 'Student' ? 'selected' : '' }}>Student
                                            </option>
                                            <option value="Retired"
                                                {{ old('employment_status') == 'Retired' ? 'selected' : '' }}>Retired
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Permanent Home Address</label>
                                        <input type="text" class="form-control" name="permanent_home_address"
                                            value="{{ old('permanent_home_address') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                    <button type="button" class="btn btn-brand next-step">Next</button>
                                </div>
                            </div>
                            <!-- Step 3: Guardian/Sponsor Details -->
                            <div class="form-step" id="step-3">
                                <div class="form-section-title">GUARDIAN AND SPONSOR'S DETAILS</div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Parent/Guardian Name</label>
                                        <input type="text" class="form-control" name="parent_guardian_name"
                                            value="{{ old('parent_guardian_name') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Phone No.</label>
                                        <input type="text" class="form-control" name="parent_guardian_phone"
                                            value="{{ old('parent_guardian_phone') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Parent/Guardian Address</label>
                                        <input type="text" class="form-control" name="parent_guardian_address"
                                            value="{{ old('parent_guardian_address') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Occupation</label>
                                        <input type="text" class="form-control" name="parent_guardian_occupation"
                                            value="{{ old('parent_guardian_occupation') }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                    <button type="button" class="btn btn-brand next-step">Next</button>
                                </div>
                            </div>
                            <!-- Step 4: Academic Record Details -->
                            <div class="form-step" id="step-4">
                                <div class="form-section-title">ACADEMIC RECORD DETAILS</div>
                                <div class="mb-3">
                                    <label>Programme</label>
                                    <select class="form-select" name="programme_id">
                                        <option value="">-- Select Programme --</option>
                                        @foreach ($programmes as $programme)
                                            <option value="{{ $programme->id }}"
                                                {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                                                {{ $programme->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Upload Credentials (PDF)</label>
                                    <input type="file" class="form-control" name="credential"
                                        accept="application/pdf">
                                    <small class="form-text text-muted">Upload your credentials as a single PDF file
                                        (max 5MB). Only PDF files are accepted.</small>
                                </div>
                                <div class="mb-3">
                                    <label>O/LEVEL Academic Records</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle" id="olevelTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Name of School</th>
                                                    <th>Exam Type</th>
                                                    <th>Exam Year</th>
                                                    <th>Subjects</th>
                                                    <th>Grade</th>
                                                    <th>No. of sittings</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td><input type="text" class="form-control"
                                                            name="olevel_school[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="olevel_exam_type[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="olevel_exam_year[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="olevel_subjects[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="olevel_grade[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="olevel_sittings[]"></td>
                                                    <td><button type="button" class="add-record-btn"
                                                            id="addOlevelRow" title="Add O/Level Record"><i
                                                                class="fa fa-plus"></i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>A/LEVEL Academic Records</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle" id="alevelTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Other Qualifications</th>
                                                    <th>Year of Graduation</th>
                                                    <th>Certificate Obtained</th>
                                                    <th>Grade</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td><input type="text" class="form-control"
                                                            name="alevel_qualification[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="alevel_graduation_year[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="alevel_certificate[]"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="alevel_grade[]"></td>
                                                    <td><button type="button" class="add-record-btn"
                                                            id="addAlevelRow" title="Add A/Level Record"><i
                                                                class="fa fa-plus"></i></button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                    <button type="button" class="btn btn-brand next-step">Next</button>
                                </div>
                            </div>
                            <!-- Step 5: Declaration & Submission -->
                            <div class="form-step" id="step-5">
                                <div class="form-section-title">DECLARATION</div>
                                <div class="mb-3">
                                    <div class="form-check w-100">
                                        <input class="form-check-input" type="checkbox" name="declaration_check"
                                            id="declarationCheck" value="1" required
                                            {{ old('declaration_check') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="declarationCheck">
                                            <span class="text-wrap text-break d-block" style="word-break:break-word;">
                                                I hereby declare that the information provided is true and correct. I
                                                understand that any false information may lead to disqualification.
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                    <button type="submit" class="btn btn-success">Submit Application</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/gmaps/gmaps.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="{{ asset('assets/js/setting-demo2.js') }}"></script>
    <script>
        // Multi-step form logic
        let currentStep = 1;
        const totalSteps = 5;

        function showStep(step) {
            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById('step-' + i).classList.remove('active');
                document.querySelector('.step-' + i).classList.remove('active', 'completed');
            }
            document.getElementById('step-' + step).classList.add('active');
            for (let i = 1; i < step; i++) {
                document.querySelector('.step-' + i).classList.add('completed');
            }
            document.querySelector('.step-' + step).classList.add('active');
          
            document.getElementById('formProgress').style.width = (step * 20) + '%';
        }
        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', function() {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });
        // On page load
        showStep(currentStep);

        // Passport preview 
        document.getElementById('passportInput').addEventListener('change', function(e) {
            const preview = document.getElementById('passportPreview');
            preview.innerHTML = '';
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                preview.innerHTML = '<span class="text-muted">Passport Preview</span>';
            }
        });
        document.getElementById('multiStepForm').addEventListener('submit', function(e) {
            const olevel = Array.from(document.querySelectorAll('input[name="olevel_school[]"]')).map(i => i.value);
            console.log('OLEVEL:', olevel);
        });

        // O/Level dynamic row addition
        let olevelCount = 1;
        document.getElementById('addOlevelRow').addEventListener('click', function() {
            olevelCount++;
            const table = document.getElementById('olevelTable').getElementsByTagName('tbody')[0];
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${olevelCount}</td>
                <td><input type="text" class="form-control" name="olevel_school[]"></td>
                <td><input type="text" class="form-control" name="olevel_exam_type[]"></td>
                <td><input type="text" class="form-control" name="olevel_exam_year[]"></td>
                <td><input type="text" class="form-control" name="olevel_subjects[]"></td>
                <td><input type="text" class="form-control" name="olevel_grade[]"></td>
                <td><input type="text" class="form-control" name="olevel_sittings[]"></td>
                <td>
                    <button type="button" class="remove-record-btn" title="Remove O/Level Record"><i class="fa fa-minus"></i></button>
                </td>
            `;
            table.appendChild(row);
            row.querySelector('.remove-record-btn').addEventListener('click', function() {
                row.remove();
                updateOlevelSN();
            });
            updateOlevelSN();
        });

        function updateOlevelSN() {
            const rows = document.querySelectorAll('#olevelTable tbody tr');
            rows.forEach((row, idx) => {
                row.querySelector('td:first-child').textContent = idx + 1;
            });
        }
        // A/Level dynamic row addition
        let alevelCount = 1;
        document.getElementById('addAlevelRow').addEventListener('click', function() {
            alevelCount++;
            const table = document.getElementById('alevelTable').getElementsByTagName('tbody')[0];
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${alevelCount}</td>
                <td><input type="text" class="form-control" name="alevel_qualification[]"></td>
                <td><input type="text" class="form-control" name="alevel_graduation_year[]"></td>
                <td><input type="text" class="form-control" name="alevel_certificate[]"></td>
                <td><input type="text" class="form-control" name="alevel_grade[]"></td>
                <td>
                    <button type="button" class="remove-record-btn" title="Remove A/Level Record"><i class="fa fa-minus"></i></button>
                </td>
            `;
            table.appendChild(row);
            row.querySelector('.remove-record-btn').addEventListener('click', function() {
                row.remove();
                updateAlevelSN();
            });
            updateAlevelSN();
        });

        function updateAlevelSN() {
            const rows = document.querySelectorAll('#alevelTable tbody tr');
            rows.forEach((row, idx) => {
                row.querySelector('td:first-child').textContent = idx + 1;
            });
        }
    </script>
    <script type="module">
        import {
            setupStateLgaSelect
        } from "{{ asset('assets/js/main/state-lga-select.js') }}";
        document.addEventListener('DOMContentLoaded', function() {
            setupStateLgaSelect('stateSelect', 'lgaSelect');
        });
    </script>
    @if (session('success'))
        <script>
            swal("{{ session('success') }}", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: 'btn btn-success'
                    }
                }
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            swal("{{ session('error') }}", {
                icon: "danger",
                buttons: {
                    confirm: {
                        className: 'btn btn-danger'
                    }
                }
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            swal("{{ $errors->first() }}", {
                icon: "error",
                buttons: {
                    confirm: {
                        className: 'btn btn-danger'
                    }
                }
            });
        </script>
    @endif
</body>

</html>
