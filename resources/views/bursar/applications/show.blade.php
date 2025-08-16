@extends('bursar.layout')

@section('content')
<div class="container-fluid">
    

        <div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Dashboard</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Payments</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Payment Details</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">{{ $payment->reference }}</a></li>
                </ul>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Payment Information</h4>
                            <div class="ms-auto">
                                <a href="{{ route('bursar.dashboard') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Payment Reference</label>
                                    <div class="d-flex align-items-center">
                                        <p class="form-control-static mb-0 me-2" id="payment-reference">{{ $payment->reference }}</p>
                                        <button type="button" class="btn btn-sm text-muted" style="border: none; background: none; padding: 4px 8px;" onclick="copyToClipboard('payment-reference')" title="Copy Reference">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Transaction ID</label>
                                    <p class="form-control-static">{{ $payment->transaction_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Amount</label>
                                    <p class="form-control-static">
                                        <span class="fw-bold text-success fs-4">₦{{ number_format($payment->amount, 2) }}</span>
                                        <small class="text-muted d-block">{{ $payment->currency }}</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Payment Status</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-{{ $payment->status === 'successful' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }} badge-lg">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Payment Method</label>
                                    <p class="form-control-static">{{ ucfirst($payment->payment_method) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Payment Date</label>
                                    <p class="form-control-static">
                                        {{ $payment->payment_date ? $payment->payment_date->format('F d, Y \a\t g:i A') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Description</label>
                                    <p class="form-control-static">{{ $payment->description ?? 'No description available' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Created At</label>
                                    <p class="form-control-static">{{ $payment->created_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Last Updated</label>
                                    <p class="form-control-static">{{ $payment->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Information Card -->
                @if($payment->application)
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Related Application Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Application Number</label>
                                    <p class="form-control-static">{{ $payment->application->application_number ?? 'Not Generated' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Applicant Name</label>
                                    <p class="form-control-static">
                                        {{ $payment->application->applicant_surname }} {{ $payment->application->applicant_othernames }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="form-control-static">{{ $payment->application->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Programme</label>
                                    <p class="form-control-static">
                                        {{ $payment->application->programme ? $payment->application->programme->name : 'Not Assigned' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Application Status</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-info">{{ ucfirst($payment->application->status ?? 'pending') }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Form Completed</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-{{ $payment->application->is_filled ? 'success' : 'warning' }}">
                                            {{ $payment->application->is_filled ? 'Yes' : 'No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($payment->status === 'successful')
                            <button class="btn btn-success" disabled>
                                <i class="fa fa-check"></i> Payment Completed
                            </button>
                            @elseif($payment->status === 'pending')
                            <button class="btn btn-warning">
                                <i class="fa fa-clock"></i> Payment Pending
                            </button>
                            @else
                            <button class="btn btn-danger" disabled>
                                <i class="fa fa-times"></i> Payment Failed
                            </button>
                            @endif
                            
                            <a href="#" class="btn btn-info">
                                <i class="fa fa-print"></i> Print Receipt
                            </a>
                            
                            <a href="#" class="btn btn-secondary">
                                <i class="fa fa-download"></i> Download Receipt
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Statistics Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-primary">{{ $payment->id }}</h3>
                                    <small class="text-muted">Payment ID</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h3 class="text-success">{{ $payment->created_at->diffForHumans() }}</h3>
                                    <small class="text-muted">Payment Made</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent || element.innerText;
    
    // Create a temporary textarea element
    const textarea = document.createElement('textarea');
    textarea.value = text.trim();
    document.body.appendChild(textarea);
    
    // Select and copy the text
    textarea.select();
    textarea.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        
        // Show success message
        showCopyMessage('Payment reference copied to clipboard!');
        
        // Change icon temporarily
        const button = document.querySelector(`button[onclick="copyToClipboard('${elementId}')"]`);
        const icon = button.querySelector('i');
        const originalClass = icon.className;
        
        // Change icon to checkmark
        icon.className = 'fas fa-check text-success';
        
        // Reset after 2 seconds
        setTimeout(() => {
            icon.className = originalClass;
        }, 2000);
        
    } catch (err) {
        console.error('Failed to copy: ', err);
        showCopyMessage('Failed to copy to clipboard', 'error');
    }
    
    // Remove the temporary element
    document.body.removeChild(textarea);
}

function showCopyMessage(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        animation: slideIn 0.3s ease-out;
    `;
    
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
            if (style.parentNode) {
                document.head.removeChild(style);
            }
        }, 300);
    }, 3000);
}
</script>

@endsection