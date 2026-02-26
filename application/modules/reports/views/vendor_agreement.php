<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>


<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url('vendor_crm/dashboard'); ?>">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Vendor Agreements</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- Main-body start -->
            <div class="main-body">
                <div class="page-wrapper">

                    <!-- Page-body start -->
                    <div class="page-body">



                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col-md-10">
                                                <form class="search-form" novalidate=""
                                                    action="<?php echo base_url('vendor_agreements/submitted'); ?>"
                                                    method="post" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label for="q"
                                                                class="col-form-label font-weight-bold">Vendor
                                                                Agreements:</label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="q" id="q" class="form-control border">
                                                                <option value="">Select</option>
                                                                <option value="accepted" <?php echo (isset($_POST['q']) && $_POST['q'] == 'accepted') || !isset($_POST['q']) ? 'selected' : ''; ?>>Accepted</option>
                                                                <option value="unaccepted" <?php echo (isset($_POST['q']) && $_POST['q'] == 'unaccepted') ? 'selected' : ''; ?>>Unaccepted</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button type="submit" name="submit" id="search-btn"
                                                                value="Apply" class="btn btn-primary">
                                                                <i class="feather icon-search newserch"
                                                                    aria-hidden="true"></i>&nbsp;Search
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">

                                        <div class="card-body">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="col-10 ven1 text-center">List of Vendor Agreements</h4>
                                                </div>


                                                <?php if (isset($vendorReports)): ?>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover" id="tableExportAccepted"
                                                                style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sno</th>
                                                                        <th>Vendor Name</th>
                                                                        <th>Agreement Title</th>
                                                                        <th>Phone Number</th>
                                                                        <th>Address</th>
                                                                        <th>Accepted At</th>
                                                                        <th class="not-export-column">Action</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    <?php $sno = 1;
                                                                    if (!empty($vendorReports)):
                                                                        foreach ($vendorReports as $tc): ?>

                                                                            <tr>
                                                                                <td>
                                                                                    <?php echo $sno++; ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo $tc->name; ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo $tc->title; ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php
                                                                                    echo $tc->whats_app_no;
                                                                                    ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php
                                                                                    echo $tc->vendor_address;
                                                                                    ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php
                                                                                    echo ($tc->agreement_id != 0) ? date('d-M-Y H:i A', strtotime($tc->agreement_accepted_at)) : '';
                                                                                    ?>
                                                                                </td>

                                                                                <td>
                                                                                    <a href="<?php echo base_url(); ?>exports/vendor_agreement_pdfs/<?php echo $tc->agreement_accepted_file; ?>"
                                                                                        target="_blank"><i
                                                                                            class='feather icon-eye'></i></a>
                                                                                    <a href="#" class="send-email"
                                                                                        data-agreement-id="<?php echo $tc->id; ?>"><i
                                                                                            class='feather icon-mail'></i></a>
                                                                                    <a href="<?php echo base_url(); ?>exports/vendor_agreement_pdfs/<?php echo $tc->agreement_accepted_file; ?>"
                                                                                        target="_blank" download><i
                                                                                            class='feather icon-download'></i></a>
                                                                                </td>

                                                                            </tr>

                                                                        <?php endforeach; ?>

                                                                    <?php else: ?>
                                                                        <tr>
                                                                            <th colspan="6">
                                                                                <h3>
                                                                                    <center>Sorry!! No Agreements Found!!!
                                                                                    </center>
                                                                                </h3>
                                                                            </th>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>


                                                <?php if (isset($vendorUnacceptedReports)): ?>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover" id="tableExportUnaccepted"
                                                                style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sno</th>
                                                                        <th>Vendor Name</th>
                                                                        <th>Phone Number</th>
                                                                        <th>Address</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $sno = 1;
                                                                    if (!empty($vendorUnacceptedReports)):
                                                                        foreach ($vendorUnacceptedReports as $tc): ?>

                                                                            <tr>
                                                                                <td>
                                                                                    <?php echo $sno++; ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo $tc->name; ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php
                                                                                    echo $tc->whats_app_no;
                                                                                    ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php
                                                                                    echo $tc->vendor_address;
                                                                                    ?>
                                                                                </td>

                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <tr>
                                                                            <th colspan="4">
                                                                                <h3>
                                                                                    <center>Sorry!! No Agreements Found!!!
                                                                                    </center>
                                                                                </h3>
                                                                            </th>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
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
            $(document).ready(function () {
                $('#tableExportUnaccepted').DataTable({
                    dom: 'Bfrtip',
                    paging: false,
                    buttons: [{
                        extend: 'excel',
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    }
                    ],
                });

                $('#tableExportAccepted').DataTable({
                    dom: 'Bfrtip',
                    paging: false,
                    buttons: [{
                        extend: 'excel',
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    }
                    ],
                    columnDefs: [
                        { "targets": [6], "orderable": false }
                    ]
                });
            });

            $(document).ready(function () {
                $('.send-email').click(function (e) {
                    e.preventDefault();

                    var agreementId = $(this).data('agreement-id');

                    var $button = $(this);
                    $button.attr('disabled', true).addClass('disabled');
                    $button.find('.feather').removeClass('icon-mail').addClass('icon-loader'); 

                    $.ajax({
                        url: '<?php echo base_url('vendor_agreements_email/'); ?>' + agreementId,
                        type: 'GET',
                        success: function (response) {
                            console.log('Email sent successfully');

                            $button.attr('disabled', false).removeClass('disabled');
                            $button.find('.feather').removeClass('icon-loader').addClass('icon-mail');
                        },
                        error: function (xhr, status, error) {
                            console.error('Error sending email:', error);

                            $button.attr('disabled', false).removeClass('disabled');
                            $button.find('.feather').removeClass('icon-loader').addClass('icon-mail');
                        }
                    });
                });
            });
        </script>


        <?php $this->load->view('vendorCrm/footer'); ?>