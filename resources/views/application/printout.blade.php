<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('assets/img/favicon_io/favicon.ico') }}" type="image/x-icon" />
    <meta charset="UTF-8">
    <title>Application Printout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fa;
            color: #111;
            margin: 0;
            padding: 0;
        }

        .printout-container {
            background: #fff;
            max-width: 900px;
            margin: 40px auto 40px auto;
            padding: 32px 32px 24px 32px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(26, 32, 53, 0.14);
            border: 1.5px solid #eee;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 2px solid #900;
            margin-bottom: 1.5em;
            padding-bottom: 1em;
            background: none;
            border-radius: 12px 12px 0 0;
        }

        .logo {
            width: 80px;
            margin-right: 18px;
        }

        .header-center {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .passport-box {
            width: 100px;
            height: 120px;
            border: none;
            text-align: center;
            font-size: 0.9em;
            background: #fafafa;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(26, 32, 53, 0.07);
            overflow: hidden;
            margin-left: 18px;
        }

        .passport-box img {
            border-radius: 8px;
        }

        .clearfix {
            clear: both;
        }

        .section-title {
            font-weight: bold;
            margin-top: 2em;
            margin-bottom: 0.7em;
            font-size: 1.13em;
            color: #900;
            letter-spacing: 0.5px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.3em;
            background: #fcfcfc;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(26, 32, 53, 0.06);
        }

        .form-table td,
        .form-table th {
            border: 1px solid #ddd;
            padding: 7px 10px;
            font-size: 1em;
            text-align: center;
        }

        .form-table th {
            background: #f4f6fa;
            color: #900;
            font-weight: 600;
        }

        .btns {
            margin: 2em 0 1em 0;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 26px;
            margin: 0 12px;
            background: #900;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.08em;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(26, 32, 53, 0.08);
            transition: background 0.2s;
        }

        .btn:hover {
            background: #c00;
        }

        @media print {
            .btns {
                display: none;
            }

            .printout-container {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="printout-container">
        <div class="header" style="padding-bottom:1em; border-bottom:2px solid #900; margin-bottom:1.5em;">
            <table width="100%" style="border:none;">
                <tr>
                    <td style="width:90px;vertical-align:top;">
                        <img src="{{ isset($isPdf) && $isPdf ? public_path('assets/img/bsth-logo.jpeg') : asset('assets/img/bsth-logo.jpeg') }}"
                            alt="BSTH Logo" style="width:80px;">
                    </td>
                    <td style="text-align:center;">
                        <div style="font-size:1.2em; font-weight:bold; color:#900;">BENUE STATE UNIVERSITY TEACHING
                            HOSPITAL MAKURDI</div>
                        <div style="font-size:1em; font-weight:bold;">INSTITUTE OF HEALTH AND TECHNOLOGY</div>
                        <div
                            style="margin:0.5em auto; background-color:#000; color:#fff; font-size:1.1em; font-weight:bold; display:inline-block; padding:2px 16px; border-radius:4px;">
                            APPLICATION FORM</div>
                    </td>
                    <td style="width:110px;vertical-align:top;">
                        <div
                            style="width:100px;height:120px;background:#fafafa;border-radius:8px;box-shadow:0 2px 8px #1a20351a;overflow:hidden;text-align:center;margin-left:auto;">
                            @if ($application->passport)
                                <img src="{{ isset($isPdf) && $isPdf ? public_path('storage/' . $application->passport) : asset('storage/' . $application->passport) }}"
                                    alt="Passport" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                            @else
                                <span style="font-size:0.9em;line-height:120px;">PASSPORT</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-bottom:1em;">
            <b>FORM NO:</b> {{ $application->application_number ?? 'N/A' }}
        </div>

        <div class="section-title">APPLICANT'S PERSONAL DETAILS:</div>
        <table class="form-table">
            <tr>
                <td>Surname:</td>
                <td>{{ $application->applicant_surname }}</td>
                <td>Other Names:</td>
                <td>{{ $application->applicant_othernames }}</td>
            </tr>
            <tr>
                <td>Date of Birth:</td>
                <td>{{ $application->date_of_birth }}</td>
                <td>Gender:</td>
                <td>{{ $application->gender }}</td>
            </tr>
            <tr>
                <td>State:</td>
                <td>{{ $application->state_of_origin }}</td>
                <td>LGA:</td>
                <td>{{ $application->lga }}</td>
            </tr>
            <tr>
                <td>Nationality:</td>
                <td>{{ $application->nationality }}</td>
                <td>Religion:</td>
                <td>{{ $application->religion }}</td>
            </tr>
            <tr>
                <td>Marital Status:</td>
                <td>{{ $application->marital_status }}</td>
                <td colspan="2"></td>
            </tr>
        </table>
        <div class="section-title">APPLICANT'S CONTACT INFORMATION:</div>
        <table class="form-table">
            <tr>
                <td>Home Town:</td>
                <td>{{ $application->home_town }}</td>
                <td>E-Mail:</td>
                <td>{{ $application->email }}</td>
            </tr>
            <tr>
                <td>Phone No.:</td>
                <td>{{ $application->phone }}</td>
                <td>Correspondence Address:</td>
                <td>{{ $application->correspondence_address }}</td>
            </tr>
            <tr>
                <td>Employment Status:</td>
                <td>{{ $application->employment_status }}</td>
                <td>Permanent Home Address:</td>
                <td>{{ $application->permanent_home_address }}</td>
            </tr>
        </table>
        <div class="section-title">GUARDIAN AND SPONSOR'S DETAILS:</div>
        <table class="form-table">
            <tr>
                <td>Parent/Guardian Name:</td>
                <td>{{ $application->parent_guardian_name }}</td>
                <td>Phone No.:</td>
                <td>{{ $application->parent_guardian_phone }}</td>
            </tr>
            <tr>
                <td>Parent/Guardian Address:</td>
                <td>{{ $application->parent_guardian_address }}</td>
                <td>Occupation:</td>
                <td>{{ $application->parent_guardian_occupation }}</td>
            </tr>
        </table>
        <div class="section-title">ACADEMIC RECORD DETAILS:</div>
        <table class="form-table">
            <tr>
                <th>S/N</th>
                <th>Name of School</th>
                <th>Exam type</th>
                <th>Exam Year</th>
                <th>Subjects</th>
                <th>Grade</th>
                <th>No. of sittings</th>
            </tr>
            @forelse($oLevelRecords as $index => $rec)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $rec->school_name }}</td>
                    <td>{{ $rec->exam_type }}</td>
                    <td>{{ $rec->exam_year }}</td>
                    <td>{{ $rec->subject }}</td>
                    <td>{{ $rec->grade }}</td>
                    <td>{{ $rec->number_of_sittings }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No O/LEVEL records found.</td>
                </tr>
            @endforelse
        </table>
        <table class="form-table">
            <tr>
                <th>S/N</th>
                <th>Other Qualifications</th>
                <th>Year of Graduation</th>
                <th>Certificate Obtained</th>
                <th>Grade</th>
            </tr>
            @forelse($aLevelRecords as $index => $rec)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $rec->other_qualification }}</td>
                    <td>{{ $rec->graduation_year }}</td>
                    <td>{{ $rec->certificate_obtained }}</td>
                    <td>{{ $rec->alevel_grade }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">No A/LEVEL records found.</td>
                </tr>
            @endforelse
        </table>
        <div class="section-title">DECLARATION:</div>
        <div style="margin-bottom:1em;">
            I attest to the fact the information provided above is true and if found to be false, my application should
            be disqualified.
        </div>
        @if (isset($isPdf) && $isPdf)
        @else
            @if (!isset($isPdf) || !$isPdf)
                <div class="btns">
                    <a href="{{ route('application.downloadPrintout', $application->application_number) }}"
                        class="btn">
                        <i class='bx bx-download' style="font-size:1.1em;vertical-align:middle;margin-right:6px;"></i>
                        Download PDF
                    </a>
                    <button type="button" class="btn" onclick="window.print()">
                        <i class='bx bx-printer' style="font-size:1.1em;vertical-align:middle;margin-right:6px;"></i>
                        Print
                    </button>
                    <a href="{{ route('application.landing') }}" type="button" class="btn">
                        <i class='bx bx-arrow-back' style="font-size:1.1em;vertical-align:middle;margin-right:6px;"></i>
                        Back
                    </a>
                </div>
            @endif
        @endif
    </div> 
    <!-- end printout-container -->
    @if (!isset($isPdf) || !$isPdf)
        <!--  Notice Modal -->
        <div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title text-danger fw-semibold" id="noticeModalLabel">
                            📌 Important Notice!!!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-secondary" style="line-height: 1.6;">
                        Dear, {{ $application->applicant_surname }} {{ $application->applicant_othernames }}<br><br>
                        Kindly be informed that you are required to print a copy of your completed application form and
                        submit it to the Office of
                        the Registrar for official processing.<br><br>
                        This step is essential to ensure that your application is formally recorded and considered for
                        further administrative action.
                        We advise that you complete this submission promptly to avoid any delays in the processing of
                        your application.<br><br>
                        Thank you for your cooperation.
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button"
                            class="btn btn-primary d-flex align-items-center justify-content-center gap-2"
                            data-bs-dismiss="modal">
                            <i class='bx bx-check-circle' style="font-size:1.2em;"></i>
                            Acknowledge
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show Modal on Page Load -->
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('noticeModal'));
                modal.show();
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
