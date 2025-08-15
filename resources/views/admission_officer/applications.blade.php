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
                                <li class="nav-item"><a href="#">Application Management</a></li>
                                <li class="separator"><i class="icon-arrow-right"></i></li>
                                <li class="nav-item"><a href="#">All Applications</a></li>
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
                <div class="card-header">
                    <h4 class="card-title">Applicants List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Fullname</th>
                                    <th>Course Applied</th>
                                    <th>State</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $application->applicant_surname." ".$application->applicant_othernames }}</td>
                                        <td>{{ $application->programme ? $application->programme->name : 'Not Assigned' }}</td>
                                        <td>{{ $application->status }}</td>
                                        <td><span class="badge bg-success">Success</span></td>
                                        <td>
                                            <a href="{{ route('admissions.application.show', $application) }}" class="btn btn-link btn-info btn-sm" title="View"><i
                                                    class="fa fa-eye fa-lg"></i></a>
                                            <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                    class="fa fa-edit fa-lg"></i></button>
                                            <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                    class="fa fa-trash fa-lg"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No applications found.</td>
                                    </tr>
                                @endforelse
                                {{-- <tr>
                                    <td>1</td>
                                    <td>Chinedu Okafor</td>
                                    <td>chinedu.okafor@example.com</td>
                                    <td>Lagos</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr> --}}
                                {{-- <tr>
                                    <td>2</td>
                                    <td>Amina Bello</td>
                                    <td>amina.bello@example.com</td>
                                    <td>Kano</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Babatunde Adedayo</td>
                                    <td>babatunde.adedayo@example.com</td>
                                    <td>Ogun</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Yetunde Afolabi</td>
                                    <td>yetunde.afolabi@example.com</td>
                                    <td>Oyo</td>
                                    <td><span class="badge bg-danger">Failed</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Emeka Eze</td>
                                    <td>emeka.eze@example.com</td>
                                    <td>Enugu</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Fatima Suleiman</td>
                                    <td>fatima.suleiman@example.com</td>
                                    <td>Kaduna</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Olumide Johnson</td>
                                    <td>olumide.johnson@example.com</td>
                                    <td>Ekiti</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Grace Udo</td>
                                    <td>grace.udo@example.com</td>
                                    <td>Akwa Ibom</td>
                                    <td><span class="badge bg-danger">Failed</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>Samuel Abubakar</td>
                                    <td>samuel.abubakar@example.com</td>
                                    <td>Niger</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Linda Nwosu</td>
                                    <td>linda.nwosu@example.com</td>
                                    <td>Anambra</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>Abdullahi Musa</td>
                                    <td>abdullahi.musa@example.com</td>
                                    <td>Katsina</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>Chiamaka Obi</td>
                                    <td>chiamaka.obi@example.com</td>
                                    <td>Imo</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>13</td>
                                    <td>Peter Oche</td>
                                    <td>peter.oche@example.com</td>
                                    <td>Benue</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td>Bose Salami</td>
                                    <td>bose.salami@example.com</td>
                                    <td>Osun</td>
                                    <td><span class="badge bg-danger">Failed</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-sm" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>15</td>
                                    <td>Maryam Danjuma</td>
                                    <td>maryam.danjuma@example.com</td>
                                    <td>Plateau</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>
                                        <button class="btn btn-link btn-info btn-lg" title="View"><i
                                                class="fa fa-eye fa-lg"></i></button>
                                        <button class="btn btn-link btn-primary btn-sm" title="Edit"><i
                                                class="fa fa-edit fa-lg"></i></button>
                                        <button class="btn btn-link btn-danger btn-sm" title="Delete"><i
                                                class="fa fa-trash fa-lg"></i></button>

                                    </td>
                                </tr>
                                </tr> --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
