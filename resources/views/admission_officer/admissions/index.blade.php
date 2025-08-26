@extends('admission_officer.layout')
@section('content')
    <div class="container-fluid px-0">
        <div class="row gx-0">
            <div class="col-md-12">
                <div class="card py-0"> <!-- Remove vertical padding -->
                    <div class="d-flex align-items-center ps-4" style="height: 80px;"> <!-- adjust height -->
                        <div class="page-header mb-0">
                            <ul class="breadcrumbs mb-0">
                                <li class="nav-home">
                                    <a href="#"><i class="icon-home"></i></a>
                                </li>
                                <li class="separator"><i class="icon-arrow-right"></i></li>
                                <li class="nav-item"><a href="#">Admission Management</a></li>
                                <li class="separator"><i class="icon-arrow-right"></i></li>
                                <li class="nav-item"><a href="#">All Admissions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Admissions List</h4>
                    <div>
                        <button type="button" class="btn btn-primary btn-round btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadAdmissionListModal">
                            <i class="fa fa-upload"></i>
                            Upload Admission List
                        </button>
                        <a href="{{ route('admissions.export') }}" class="btn btn-success btn-round btn-sm">
                            <i class="fa fa-file-excel"></i>
                            Export Admissions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Application No.</th>
                                    <th>Fullname</th>
                                    <th>Programme</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($admissions as $admission)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $admission->application_number }}</td>
                                        <td>{{ $admission->applicant_surname." ".$admission->applicant_othernames }}</td>
                                        <td>{{ $admission->programme ? $admission->programme->name : 'N/A' }}</td>
                                        <td><span class="badge bg-{{ $admission->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($admission->status) }}</span></td>
                                        <td>
                                            <a href="{{ route('admission_officer.admissions.show', $admission) }}" class="btn btn-link btn-info btn-sm" title="View"><i
                                                    class="fa fa-eye fa-lg"></i></a>
                                            <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                    class="fa fa-trash fa-lg"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No admissions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Admission List Modal -->
    <div class="modal fade" id="uploadAdmissionListModal" tabindex="-1" aria-labelledby="uploadAdmissionListModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadAdmissionListModalLabel">Upload Admission List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admission_officer.admissions.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Please ensure your file follows the specified template (CSV or XLSX). Download the template below:
                            <a href="{{ route('admissions.template.download') }}" class="btn btn-sm btn-info ms-2"><i class="fa fa-download"></i> Download Template</a>
                        </p>
                        <div class="alert alert-info" role="alert">
                            <strong>Instructions:</strong>
                            <ul>
                                <li>The file must contain the exact column headers as provided in the template.</li>
                                <li>Ensure all required fields are filled.</li>
                                <li>CSV or XLSX files are accepted.</li>
                                <li>For 'Programme Name' and 'Application Session', use existing names from the system.</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Select CSV or XLSX File</label>
                            <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv,.xlsx" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
