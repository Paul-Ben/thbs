<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('assets/img/favicon_io/favicon.ico') }}" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSUTH - Application Portal</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .background-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 90vw;
            max-width: 600px;
            opacity: 0.09;
            transform: translate(-50%, -50%) scale(1);
            z-index: 0;
            pointer-events: none;
        }

        .login-card {
            position: relative;
            z-index: 2;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.18);
            padding: 3rem 2rem 2.5rem 2rem;
            width: 100%;
            animation: fadeIn 0.6s ease-in-out;
        }

        @media (max-width: 767px) {
            .background-logo {
                display: none;
            }

            .login-card {
                padding: 2rem 0.7rem 1.5rem 0.7rem;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body,
        html {
            height: 100%;
            background: #f8f9fa;
        }

        .left-image-container {
            position: relative;
            height: 100vh;
            overflow: hidden;
            background: #f0f2f5;
            background: transparent;
        }

        .logo-bg {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%) translateY(0);
            width: 65%;
            opacity: 0.15;
            z-index: 1;
        }

        .student-img {
            position: relative;
            z-index: 3;
            width: 80%;
            object-fit: cover;
            object-position: 0 80px;
            /* move image content down to show face */
        }

        .wave {
            font-size: 2.2rem;
            margin-right: 0.5rem;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .brand-heading {
            color: #1a2035;
            font-weight: 600;
        }

        .form-label {
            color: #1a2035;
            font-weight: 500;
        }

        .form-control {
            border-radius: 12px;
            background: transparent !important;
            color: #1a2035;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #f96332;
        }

        .btn-signin {
            background-color: #1a2035;
            color: #fff;
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-signin:hover {
            background-color: #f96332;
            color: #fff;
        }

        .btn-outline-primary {
            border-color: #f96332 !important;
            color: #f96332 !important;
            font-weight: 500;
            border-radius: 12px;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: #f96332 !important;
            color: #fff !important;
        }

        .btn-outline-secondary {
            border-color: #1a2035 !important;
            color: #1a2035 !important;
            font-weight: 500;
            border-radius: 12px;
        }

        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus {
            background-color: #1a2035 !important;
            color: #fff !important;
        }

        .extra-links {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.95rem;
        }

        .extra-links a {
            color: #1a2035;
            font-weight: 500;
            margin: 0 8px;
        }

        .extra-links a:hover {
            color: #f96332;
        }
    /* Responsive improvements for mobile */
    @media (max-width: 767.98px) {
        .login-container {
            min-height: 100vh;
            justify-content: flex-start !important;
            padding-top: 40px !important;
            padding-bottom: 40px !important;
            background: #fff;
            box-shadow: none;
        }
        .school-title, .school-subtitle {
            font-size: 1em !important;
        }
        .school-badge {
            font-size: 1em !important;
            padding: 2px 8px !important;
        }
        .logo-bg, .student-img {
            display: none !important;
        }
    }
    .school-title {
        font-size: 1.2em;
        font-weight: bold;
        color: #900;
    }
    .school-subtitle {
        font-size: 1em;
        font-weight: bold;
    }
            .school-badge {
            margin: 0.5em auto;
            background-color: #000;
            color: #fff;
            font-size: 1.1em;
            font-weight: bold;
            display: inline-block;
            padding: 2px 16px;
            border-radius: 4px;
        }

        /* Modal Styles */
        .modal-lg {
            max-width: 900px;
        }

        .modal-header.bg-primary {
            background: linear-gradient(135deg, #1a2035 0%, #f96332 100%) !important;
        }

        .modal-title {
            font-weight: 600;
        }

        .alert-info {
            background-color: #e3f2fd;
            border-color: #2196f3;
            color: #0d47a1;
        }

        .alert-warning {
            background-color: #fff3e0;
            border-color: #ff9800;
            color: #e65100;
        }

        .card.bg-primary {
            background: linear-gradient(135deg, #1a2035 0%, #f96332 100%) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1a2035 0%, #f96332 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .form-control:focus {
            border-color: #f96332;
            box-shadow: 0 0 0 0.2rem rgba(249, 99, 50, 0.25);
        }

        .extra-links .btn {
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .extra-links .btn:hover {
            transform: translateY(-2px);
        }

        /* Enhanced form styling */
        .modal-body .form-control {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .modal-body .form-control:focus {
            border-color: #f96332;
            box-shadow: 0 0 0 0.2rem rgba(249, 99, 50, 0.25);
        }

        .modal-body .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .modal-body .form-control.is-valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }
    </style>
</head>

<body>
    <div class="container-fluid g-0">
        <div class="row g-0 min-vh-100 align-items-center">
           
            <div class="col-md-6 d-none d-md-flex align-items-end justify-content-center left-image-container">
                <img src="{{ asset('assets/img/bsth-logo.jpeg') }}" alt="Logo" class="logo-bg">
                <img src="{{ asset('assets/img/student_smiling1.png') }}" alt="Smiling Student"
                    class="student-img mb-4">
            </div>
           
            <div class="col-md-6 login-container d-flex flex-column align-items-center justify-content-center p-4">
                <div class="w-100" style="max-width: 500px;">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="{{ asset('assets/img/bsth-logo.jpeg') }}" alt="BSTH Logo" class="img-fluid" style="width:60px; max-width:100%;">
                    </div>
                    <div class="text-center mb-2">
                        <div class="school-title">BENUE STATE UNIVERSITY TEACHING HOSPITAL MAKURDI</div>
                        <div class="school-subtitle">INSTITUTE OF HEALTH AND TECHNOLOGY</div>
                        <div class="school-badge">
                            Welcome back <span style="font-size:1.2em;">👋</span>
                        </div>
                    </div>
                    <form method="POST" action="#" class="mt-4 mb-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-signin w-100 mb-3">Sign In</button>
                    </form>
                    <div class="extra-links mt-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#applyModal">
                                    <i class="fas fa-edit me-2"></i>Apply Now
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#continueApplicationModal">
                                    <i class="fas fa-arrow-right me-2"></i>Continue Application
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#retrieveApplicationModal">
                                    <i class="fas fa-search me-2"></i>Retrieve Application
                                </a>
                            </div>
                            <div class="col-12 mt-3">
                                <a href="#" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#aptitudeTestModal">
                                    <i class="fas fa-graduation-cap me-2"></i>Aptitude Test
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="applyModalLabel">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Start Your Application Journey
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-4">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Information</h6>
                        <ul class="mb-0">
                            <li>Application fee varies by programme (non-refundable)</li>
                            <li>Select your programme to see the exact fee amount</li>
                            <li>You'll be redirected to a secure payment gateway</li>
                            <li>After successful payment, you can complete your application</li>
                            <li>Keep your payment reference for future reference</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('payment.initialize') }}" id="applyForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="surname" class="form-label">Surname *</label>
                                    <input type="text" class="form-control" 
                                           id="surname" name="surname" value="{{ old('surname') }}" required>
                                </div>
                            </div>
                            <input type="hidden" name="payment_type" value="application_fee">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="othernames" class="form-label">Other Names *</label>
                                    <input type="text" class="form-control" 
                                           id="othernames" name="othernames" value="{{ old('othernames') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="programme_id" class="form-label">Select Programme *</label>
                                    <select class="form-control" id="programme_id" name="programme_id" required>
                                        <option value="">Choose your programme...</option>
                                        @foreach($programmes as $programme)
                                            <option value="{{ $programme->id }}" 
                                                    data-fee="{{ $programme->applicationFees->first()->amount ?? 0 }}"
                                                    {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                                                {{ $programme->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info" id="fee-display" style="display: none;">
                                    <strong>Application Fee: ₦<span id="fee-amount">0</span></strong>
                                    <small class="d-block">This fee is non-refundable and must be paid before proceeding with your application.</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" form="applyForm" class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Continue Application Modal -->
    <div class="modal fade" id="continueApplicationModal" tabindex="-1" aria-labelledby="continueApplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%) !important;">
                    <h5 class="modal-title" id="continueApplicationModalLabel">
                        <i class="fas fa-arrow-right me-2"></i>
                        Continue Your Application
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <h6><i class="fas fa-info-circle me-2"></i>Important Information</h6>
                        <ul class="mb-0">
                            <li>You must have completed payment to continue your application</li>
                            <li>Check your email for the payment reference number</li>
                            <li>The payment reference starts with "THBS-" followed by a unique identifier</li>
                            <li>If you haven't received the email, check your spam folder</li>
                            <li>Contact support if you need assistance</li>
                        </ul>
                    </div>

                    <form method="GET" action="#" id="continueForm">
                        <div class="mb-3">
                            <label for="payment_reference" class="form-label">Payment Reference Number *</label>
                            <input type="text" class="form-control @error('payment_reference') is-invalid @enderror" 
                                   id="payment_reference" name="payment_reference" 
                                   placeholder="e.g., THBS-1234567890" required>
                            <div class="form-text">Enter the payment reference number from your email</div>
                          
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-arrow-right me-2"></i>Continue Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Retrieve Application Modal -->
    <div class="modal fade" id="retrieveApplicationModal" tabindex="-1" aria-labelledby="retrieveApplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important; color: white !important;">
                    <h5 class="modal-title" id="retrieveApplicationModalLabel">
                        <i class="fas fa-search me-2"></i>
                        Retrieve Your Application
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-4">
                        <h6><i class="fas fa-info-circle me-2"></i>Important Information</h6>
                        <ul class="mb-0">
                            <li>You must have submitted an application to retrieve it</li>
                            <li>Check your email for the application number</li>
                            <li>The application number is unique to your application</li>
                            <li>If you haven't received the email, check your spam folder</li>
                            <li>Contact support if you need assistance</li>
                        </ul>
                    </div>

                    <form method="GET" action="#" id="retrieveForm">
                        <div class="mb-3">
                            <label for="application_number" class="form-label">Application Number *</label>
                            <input type="text" class="form-control @error('application_number') is-invalid @enderror" 
                                   id="application_number" name="application_number" 
                                   placeholder="e.g., BSUTH-001432-2024" required>
                            <div class="form-text">Enter the application number from your email</div>
                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-search me-2"></i>Retrieve Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Aptitude Test Modal -->
    <div class="modal fade" id="aptitudeTestModal" tabindex="-1" aria-labelledby="aptitudeTestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" style="background: linear-gradient(135deg, #1a2035 0%, #2c3e50 100%) !important;">
                    <h5 class="modal-title" id="aptitudeTestModalLabel">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Aptitude Test Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary mb-4">
                        <h6><i class="fas fa-info-circle me-2"></i>Important Information</h6>
                        <ul class="mb-0">
                            <li>You must have a valid application to proceed with aptitude test payment</li>
                            <li>Enter your application number to continue</li>
                            <li>The aptitude test fee is ₦5,000</li>
                            <li>Payment confirmation will be sent to your registered email</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('payment.initialize') }}" id="aptitudeTestForm">
                        @csrf
                        <input type="hidden" name="payment_type" value="{{ \App\Constants\PaymentType::APTITUDE_TEST_FEE }}">
                        
                        <div class="mb-3">
                            <label for="application_number" class="form-label">Application Number *</label>
                            <input type="text" class="form-control @error('application_number') is-invalid @enderror" 
                                   id="application_number" name="application_number" 
                                   placeholder="Enter your application number (e.g., THBS-APP-2024-001)" required>
                            @error('application_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-lightbulb text-warning me-1"></i>
                                Your application number was provided when you completed your application form
                            </div>
                            
                        </div>

                    
                            <div class="alert alert-info">
                                    <strong>Application Test Fee: ₦<span>5,000</span></strong>
                                    <small class="d-block">This fee is non-refundable and must be paid before proceeding.</small>
                            </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Proceed to Payment (₦5,000.00)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <!-- SweetAlert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    
    <!-- Form Submission Handlers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Apply Form Validation and Submission Handler
            const applyForm = document.getElementById('applyForm');
            if (applyForm) {
                applyForm.addEventListener('submit', function(e) {
                    // Let the form submit normally - validation will happen on the backend
                    // No need to prevent default or add client-side validation
                });
            }
            
            // Continue Application Form Handler
            const continueForm = document.getElementById('continueForm');
            if (continueForm) {
                continueForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const paymentRef = document.getElementById('payment_reference').value.trim();
                    if (!paymentRef) return;
                    
                    // Show loading state
                    const submitBtn = continueForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
                        submitBtn.disabled = true;
                    }
                    
                    // Redirect to continue application route
                    window.location.href = "{{ route('application.continue', '') }}/" + paymentRef;
                });
            }
            
            // Retrieve Application Form Handler
            const retrieveForm = document.getElementById('retrieveForm');
            if (retrieveForm) {
                retrieveForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const appNumber = document.getElementById('application_number').value.trim();
                    if (!appNumber) return;
                    
                    // Show loading state
                    const submitBtn = retrieveForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Retrieving...';
                        submitBtn.disabled = true;
                    }
                    
                    // Redirect to retrieve application route
                    window.location.href = "{{ route('application.retrieve', '') }}/" + appNumber;
                });
            }
            
            // Programme selection handler
            const programmeSelect = document.getElementById('programme_id');
            const feeDisplay = document.getElementById('fee-display');
            const feeAmount = document.getElementById('fee-amount');
            const proceedBtn = document.querySelector('button[form="applyForm"]');
            
            if (programmeSelect && feeDisplay && feeAmount) {
                programmeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const fee = selectedOption.getAttribute('data-fee');
                    
                    if (fee && parseFloat(fee) > 0) {
                        feeAmount.textContent = parseFloat(fee).toLocaleString('en-NG', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        feeDisplay.style.display = 'block';
                        
                        // Enable proceed button
                        if (proceedBtn) {
                            proceedBtn.disabled = false;
                            proceedBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Proceed to Payment (₦' + 
                                parseFloat(fee).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ')';
                        }
                    } else {
                        feeDisplay.style.display = 'none';
                        
                        // Disable proceed button if no programme selected
                        if (proceedBtn) {
                            proceedBtn.disabled = true;
                            proceedBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Proceed to Payment';
                        }
                    }
                });
                
                // Initially disable proceed button
                if (proceedBtn) {
                    proceedBtn.disabled = true;
                }
            }
            
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
                icon: "error",
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
