<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<style>
    .exrmkwn1 {
        overflow-y: scroll;
        max-height: 200px !important;
        width: 290px !important;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
        /* Rounded slider */
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        /* Rounded slider */
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(16px);
    }
</style>

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
                                <li class="breadcrumb-item">Agreements</li>
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

                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="col-10 ven1 text-center">List of Agreements</h4>

                                            <div class="container">
                                                <div class="row justify-content-end">
                                                    <div class="col-auto">
                                                        <a class="btn btn-outline-dark"
                                                            href="<?php echo base_url('agreements/c') ?>">
                                                            <i class="feather icon-plus"></i> Add Agreement
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" id="tableExport"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Sno</th>
                                                            <th>App Details</th>
                                                            <th>Title</th>
                                                            <th>Description</th>
                                                            <th>Created At</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($aggrementDetails)): ?>
                                                            <?php $sno = 1;
                                                            foreach ($aggrementDetails as $tc): ?>

                                                                <tr>
                                                                    <td>
                                                                        <?php echo $sno++; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php switch ($tc->app_details_id) {
                                                                            case "1":
                                                                                echo "User app";
                                                                                break;
                                                                            case "2":
                                                                                echo "Vendor app";
                                                                                break;
                                                                            case "3":
                                                                                echo "Executive app";
                                                                                break;
                                                                            case "4":
                                                                                echo "Delivery Partner app";
                                                                                break;
                                                                        }
                                                                        ; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $tc->title; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="exrmkwn1">
                                                                            <?php echo $tc->description; ?>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo date('d-M-Y H:i A', strtotime($tc->created_at)); ?>
                                                                    </td>
                                                                    <!-- <td>
                                                                        <a href="<?php echo base_url() ?>agreements/edit?id=<?php echo $tc->id; ?>"
                                                                            class="mr-2" type="category"> <i
                                                                                class='feather icon-edit'></i>
                                                                        </a>
                                                                    </td> -->
                                                                    <td>
                                                                        <?php
                                                                        echo ($tc->status == 1) ? 'active' : 'inactive';
                                                                        ?>
                                                                    </td>

                                                                    <!-- <td>
                                                                        <label class="switch">
                                                                            <input type="checkbox" class="statusToggle"
                                                                                id="statusToggle_<?php echo $tc->id; ?>" <?php echo ($tc->status == 1) ? 'checked' : ''; ?>>
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </td> -->
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
        $('#tableExport').DataTable({
            dom: 'Bfrtip',
            paging: false,
            buttons: [
                {
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
            // columnDefs: [
            //     { "targets": [4], "orderable": false }
            // ]
        });
    });



    // $(document).ready(function () {
    //     $('.statusToggle').change(function () {
    //         var newStatus = $(this).prop('checked') ? 1 : 0;
    //         var agreementId = $(this).attr('id').split('_')[1];
    //         updateStatus(agreementId, newStatus);
    //     });

    //     function updateStatus(agreementId, newStatus) {
    //         $.ajax({
    //             url: '<?php echo base_url("update_status"); ?>',
    //             method: 'POST',
    //             data: {
    //                 agreementId: agreementId,
    //                 newStatus: newStatus
    //             },
    //             dataType: 'json',
    //             success: function (response) {
    //                 if (response.status === 'success') {
    //                     console.log('Status updated successfully');
    //                 } else {
    //                     console.error('Status update failed');
    //                 }
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('AJAX Error:', error);
    //             }
    //         });
    //     }
    // });


</script>


<?php $this->load->view('vendorCrm/footer'); ?>