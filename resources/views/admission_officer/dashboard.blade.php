@extends('admission_officer.layout')
@section('content')
    <div class="card py-0"> 
        <div class="d-flex align-items-center ps-4" style="height: 80px;">
            <div class="page-header mb-0">
                <ul class="breadcrumbs mb-0">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Home</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Applications</p>
                                <h4 class="card-title">1,294</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Admitted</p>
                                <h4 class="card-title">1,303</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Application Payment</p>
                                <h4 class="card-title">₦ 1,345,522</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-icon">
                            <div
                              class="icon-big text-center icon-secondary bubble-shadow-small"
                            >
                              <i class="far fa-check-circle"></i>
                            </div>
                          </div>
                          <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                              <p class="card-category">Acceptance Payment</p>
                              <h4 class="card-title">576</h4>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Transaction History</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S/N</th>
                                    <th scope="col">Fullname</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Payment Type</th>
                                    <th scope="col" class="text-end">Amount</th>
                                    <th scope="col" class="text-end">Status</th>
                                    <th scope="col" class="text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Boje Francis</td>
                                    <td>boje.francis@gmail.com</td>
                                    <td>Application fees</td>
                                    <td class="text-end">₦30,000.00</td>
                                    <td class="text-end"><span class="badge badge-success">Completed</span></td>
                                    <td class="text-end">2025-07-25 </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Dooshima Agbo</td>
                                    <td>dooshima.agbo@gmail.com</td>
                                    <td>Application fees</td>
                                    <td class="text-end">₦30,000.00</td>
                                    <td class="text-end"><span class="badge badge-warning">Pending</span></td>
                                    <td class="text-end">2025-07-25 </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Oche Ohimini</td>
                                    <td>ocheh.ohimini@gmail.com</td>
                                    <td>Application fees</td>
                                    <td class="text-end">₦30,000.00</td>
                                    <td class="text-end"><span class="badge badge-danger">Failed</span></td>
                                    <td class="text-end">2025-07-24 </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Obele Obago</td>
                                    <td>obele.obago@gmail.com</td>
                                    <td>Application fees</td>
                                    <td class="text-end">₦30,000.00</td>
                                    <td class="text-end"><span class="badge badge-success">Completed</span></td>
                                    <td class="text-end">2025-07-23 </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Obele Obago</td>
                                    <td>obele.obago@gmail.com</td>
                                    <td>Application fees</td>
                                    <td class="text-end">₦30,000.00</td>
                                    <td class="text-end"><span class="badge badge-success">Completed</span></td>
                                    <td class="text-end">2025-07-23 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
