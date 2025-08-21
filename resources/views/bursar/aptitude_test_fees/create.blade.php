@extends('bursar.layout')

@section('title', 'Create Aptitude Test Fee')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Create New Aptitude Test Fee</h3>
                    <a href="{{ route('bursar.aptitude-test-fees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('bursar.aptitude-test-fees.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">Fee Name</label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', 'Aptitude Test Fee') }}" 
                                           maxlength="255" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter a descriptive name for this fee.</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount" class="required">Amount</label>
                                    <input type="number" name="amount" id="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount') }}" 
                                           step="0.01" min="0" max="999999.99" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter the fee amount (max: 999,999.99).</small>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency" class="required">Currency</label>
                                    <select name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror" required>
                                        <option value="">Select Currency</option>
                                        <option value="NGN" {{ old('currency') == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Select the currency for this fee.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              rows="3" maxlength="1000" 
                                              placeholder="Optional description for this aptitude test fee...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Provide additional details about this fee (optional, max 1000 characters).</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="is_active" id="is_active" 
                                               class="custom-control-input" value="1" 
                                               {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Set as Active Fee
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        If checked, this will become the active aptitude test fee. 
                                        Any existing active fee will be deactivated.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Aptitude Test Fee
                                    </button>
                                    <a href="{{ route('bursar.aptitude-test-fees.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.required::after {
    content: ' *';
    color: red;
}
</style>
@endpush

@push('scripts')
<script>
    // Character counter for description
    document.getElementById('description').addEventListener('input', function() {
        const maxLength = 1000;
        const currentLength = this.value.length;
        const remaining = maxLength - currentLength;
        
        // Find or create counter element
        let counter = document.getElementById('description-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.id = 'description-counter';
            counter.className = 'form-text text-muted';
            this.parentNode.appendChild(counter);
        }
        
        counter.textContent = `${currentLength}/${maxLength} characters`;
        
        if (remaining < 50) {
            counter.className = 'form-text text-warning';
        } else {
            counter.className = 'form-text text-muted';
        }
    });
    
    // Format amount input
    document.getElementById('amount').addEventListener('input', function() {
        let value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = value.toFixed(2);
        }
    });
</script>
@endpush