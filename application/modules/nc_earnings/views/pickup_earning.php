<style>
    .list {
        display: table;
        border-spacing: 0 10px;
        padding: 0.5em 0;
    }

    .list>li {
        background-color: #e0e0e1;
        border-radius: 5px;
        color: #6c777f;
        display: table-row;
        width: 100%;
    }

    .list>li>label {
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
        background-color: #a1aab0;
        color: white;
        display: table-cell;
        min-width: 40%;
        padding: .5em;
        text-transform: capitalize;
    }

    .list>li>span {
        border-radius: 0 5px 5px 0;
        background-color: #e0e0e1;
        display: table-cell;
        padding: .5em;
    }

    td:nth-child(3) {
        position: relative;
        width: 12%;
        min-height: 12px;
    }

    #tablePickupEarnings {
        border: none;
        border-radius: 0.25rem;
    }

    #tablePickupEarnings thead th {
        background-color: #f8f9fa;
        border-bottom: none;
        border-top: none;
    }

    #tablePickupEarnings tbody tr:hover {
        background-color: #f0f0f0;
    }

    #tablePickupEarnings a {
        color: blue !important;
    }
</style>

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
                                <li class="breadcrumb-item">NC Earnings</li>
                                <li class="breadcrumb-item">Pickup Earnings</li>
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

                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-header text-dark">
                                        <h5 class="text-dark" style="margin-top: 20px;">NC Pickup Earnings</h5>
                                    </div>


                                    <!-- Main-body start -->
                                    <div class="main-body">
                                        <div class="page-wrapper">

                                            <!-- Page-body start -->
                                            <div class="page-body">
                                                <div class="row">

                                                    <div class="col-xl-12 col-md-12">

                                                        <form action="<?php echo base_url('pickup_earnings'); ?>"
                                                            method="POST">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="from_date">Start Date</label>
                                                                        <input type="date" class="form-control"
                                                                            id="from_date" name="from_date"
                                                                            value="<?php echo isset ($_POST['from_date']) ? $_POST['from_date'] : date("Y-m-d"); ?>">

                                                                        <?php echo form_error('from_date', '<div class="text-danger">', '</div>'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="to_date">End Date</label>
                                                                        <input type="date" class="form-control"
                                                                            id="to_date" name="to_date"
                                                                            value="<?php echo isset ($_POST['to_date']) ? $_POST['to_date'] : date("Y-m-d"); ?>">
                                                                        <?php echo form_error('to_date', '<div class="text-danger">', '</div>'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <button type="submit"
                                                                        class="btn btn-primary mt-4">Submit</button>
                                                                </div>
                                                            </div>
                                                        </form>


                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped table-hover"
                                                                        id="tablePickupEarnings" style="width: 100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>S.no</th>
                                                                                <th>Track id</th>
                                                                                <th>Order Date</th>
                                                                                <th>Total</th>
                                                                                <th>GST (%)</th>
                                                                                <th>GST</th>
                                                                                <th style="max-width: 100px;">Without
                                                                                    GST</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $status = 0;
                                                                            if ($this->ion_auth->get_users_groups()->result()[0]->name == 'admin'):
                                                                                ?>
                                                                                <?php if (!empty ($pickupdata)):
                                                                                    $status = 1;
                                                                                    ?>
                                                                                    <?php $sno = 1;
                                                                                    foreach ($pickupdata as $earning): ?>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <?php echo $sno++; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->track_id; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo date('d-m-Y h:i A', strtotime($earning->created_at)); ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->nc_delivery_fee; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->delivery_gst_percentage . "%"; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->nc_delivery_fee_gst_value; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->nc_delivery_fee_without_gst; ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                <?php else: ?>
                                                                                    <tr>
                                                                                        <th colspan='6'>
                                                                                            <h5>
                                                                                                <center>No data available.
                                                                                                </center>
                                                                                            </h5>
                                                                                        </th>
                                                                                    </tr>
                                                                                <?php endif; ?>
                                                                                <?php
                                                                            else:
                                                                                ?>
                                                                                <tr>
                                                                                    <th colspan='6'>
                                                                                        <h3>
                                                                                            <center>No Access!</center>
                                                                                        </h3>
                                                                                    </th>
                                                                                </tr>
                                                                                <?php
                                                                            endif;
                                                                            ?>
                                                                        </tbody>
                                                                        <?php if ($status == 1): ?>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <th>Total:</th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th id="totalTotal"></th>
                                                                                    <th></th>
                                                                                    <th id="totalGst"></th>
                                                                                    <th id="totalWithoutGst"></th>
                                                                                </tr>
                                                                            </tfoot>
                                                                            <?php
                                                                        endif;
                                                                        ?>
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
                                <!-- Page-body end -->
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
        $('#tablePickupEarnings').DataTable({
            dom: 'Bfrtip',
            paging: false,
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });

    function calculateColumnTotal(columnIndex) {
        var table = document.getElementById('tablePickupEarnings');
        var total = 0;
        for (var i = 1; i < table.rows.length; i++) {
            var row = table.rows[i];
            var cell = row.cells[columnIndex];
            var cellValue = parseFloat(cell.innerText);
            if (!isNaN(cellValue)) {
                total += cellValue;
            }
        }
        return total;
    }


    var totalWithoutGst = calculateColumnTotal(6);
    var totalGst = calculateColumnTotal(5);
    var totalTotal = calculateColumnTotal(3);

    document.getElementById('totalWithoutGst').innerText = totalWithoutGst.toFixed(2);
    document.getElementById('totalGst').innerText = totalGst.toFixed(2);
    document.getElementById('totalTotal').innerText = totalTotal.toFixed(2);
</script>
<?php $this->load->view('vendorCrm/footer'); ?>