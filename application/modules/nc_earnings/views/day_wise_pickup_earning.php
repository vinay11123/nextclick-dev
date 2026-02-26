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

    #tableEcomEarnings,
    #ordersTable {
        border: none;
        border-radius: 0.25rem;
    }

    #tableEcomEarnings thead th,
    #ordersTable thead th {
        background-color: #f8f9fa;
        border-bottom: none;
        border-top: none;
    }

    #tableEcomEarnings tbody tr:hover,
    #ordersTable tbody tr:hover {
        background-color: #f0f0f0;
    }

    #tableEcomEarnings a,
    #ordersTable a {
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
                                <li class="breadcrumb-item">Day Wise Pickup Earnings</li>
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
                                        <h5 class="text-dark" style="margin-top: 20px;">NC Day Wise Pickup Earnings</h5>
                                    </div>


                                    <!-- Main-body start -->
                                    <div class="main-body">
                                        <div class="page-wrapper">

                                            <!-- Page-body start -->
                                            <div class="page-body">
                                                <div class="row">

                                                    <div class="col-xl-12 col-md-12">

                                                        <form
                                                            action="<?php echo base_url('day_wise_pickup_earnings'); ?>"
                                                            method="POST">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="from_date">Start Date</label>
                                                                        <input type="date" class="form-control"
                                                                            id="from_date" name="from_date"
                                                                            value="<?php echo isset ($_POST['from_date']) ? $_POST['from_date'] : date('Y-m-01'); ?>">

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
                                                                                <th>Order Date</th>
                                                                                <th>Order Count</th>
                                                                                <th>Total</th>
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
                                                                                    $sno = 1;
                                                                                    foreach ($pickupdata as $earning): ?>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <?php echo $sno++; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo date('d-m-Y', strtotime($earning->created_date)); ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <a href="#" class="btn btn-link"
                                                                                                    onclick="openOrderDetailsModal('<?php echo urlencode(date('Y-m-d', strtotime($earning->created_date))); ?>')">
                                                                                                    <?php echo $earning->order_count; ?>
                                                                                                </a>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->total_order_amount; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->gst_total_amount; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo $earning->without_gst_total_amount; ?>
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
                                                                                    <th id="totalCount"></th>
                                                                                    <th id="totalTotal"></th>
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

                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="chart-container">
                                                            <div class="container">


                                                                <div class="col-md-11">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div id="orderCountChart"
                                                                                style="margin-right: 20px;"></div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div id="orderTotalChart"
                                                                                style="margin-left: 20px;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <br>
                                                                <div class="col-md-11">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div id="orderGstChart"
                                                                                style="margin-right: 20px;"></div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div id="orderWithoutGstChart"
                                                                                style="margin-left: 20px;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <br>
                                                                <div class="col-md-11">
                                                                    <div id="fullChart"></div>
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
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 80%;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-center font-weight-bold" style="text-align: center;">Order Details</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <table class="table" id="ordersTable">
                        <thead>

                            <tr>
                                <th>S.no</th>
                                <th>Track id</th>
                                <th>Order Date</th>
                                <th>Total</th>
                                <th>GST (%)</th>
                                <th>GST</th>
                                <th style="max-width: 100px;">Without GST</th>
                            </tr>
                        </thead>
                        <tbody id="orderDetailsTableBody">
                            <!-- Order details rows will be added here dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total:</th>
                                <th></th>
                                <th></th>
                                <th id="mtotalTotal"></th>
                                <th></th>
                                <th id="mtotalGst"></th>
                                <th id="mtotalWithoutGst"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

    var gstTotalAmounts = <?= json_encode($chartData['gstTotalAmounts']) ?>;
    var withoutGstTotalAmounts = <?= json_encode($chartData['withoutGstTotalAmounts']) ?>;
    var labels = <?= json_encode($chartData['labels']) ?>;

    var optionsFullChart = {
        series: [{
            name: 'GST Total Amount',
            data: gstTotalAmounts,
        }, {
            name: 'Without GST Total Amount',
            data: withoutGstTotalAmounts
        }],
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            },
            events: {
                click(event, chartContext, config) {
                    event.preventDefault;
                    let date_val = config.config.xaxis.categories[config.dataPointIndex]
                    if (date_val != undefined) {
                        DateValue = formatGraphDate(date_val);
                        openOrderDetailsModal(DateValue);
                    }

                },
            },
        },
        title: {
            text: 'Total Amount',
            align: 'center',
            style: {
                fontSize: '36px',
                fontWeight: 'bold',
                color: '#333'
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'bottom',
                    offsetX: -10,
                    offsetY: 0
                }
            }
        }],
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 10,
                dataLabels: {
                    total: {
                        enabled: true,
                        style: {
                            fontSize: '13px',
                            fontWeight: 900
                        }
                    }
                }
            },
        },
        xaxis: {
            categories: <?= json_encode($chartData['labels']) ?>,
            title: {
                text: 'Order Date',
                style: {
                    fontSize: '14px',
                    fontWeight: 600,
                    color: '#333'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Amount',
                style: {
                    fontSize: '14px',
                    fontWeight: 600,
                    color: '#333'
                }
            }
        },
        legend: {
            position: 'right',
            offsetY: 40
        },
        fill: {
            opacity: 1
        }
    };


    var chartFull = new ApexCharts(document.querySelector("#fullChart"), optionsFullChart);
    chartFull.render();
</script>

<script>
    function get_chart(chartData, targetElement, columnColor, columnName, x_Title, y_Title, totalValue) {
        totalValue = totalValue.toFixed(2);
        var options = {
            series: [{
                name: columnName,
                data: chartData,
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                },
                events: {
                    click(event, chartContext, config) {
                        event.preventDefault;
                        let date_val = config.config.xaxis.categories[config.dataPointIndex]
                        if (date_val != undefined) {
                            DateValue = formatGraphDate(date_val);
                            openOrderDetailsModal(DateValue);
                        }

                    },
                },
            },
            title: {
                text: columnName + ': (' + totalValue + ')',
                align: 'center',
                style: {
                    fontSize: '24px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return (val === null || isNaN(val)) ? 0 : val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: <?= json_encode($chartData['labels']) ?>,
                title: {
                    text: x_Title,
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#333'
                    }
                }
            },
            yaxis: {
                title: {
                    text: y_Title,
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#333'
                    }
                }
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                colors: [columnColor],
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector(targetElement), options);
        chart.render();

        chart.updateOptions({
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                }
            }
        });
    }

    // Call the function for each chart
    get_chart(
        <?= json_encode($chartData['orderCounts']) ?>, '#orderCountChart', '#87CEEB', 'Total Order Count', 'Order Date', 'Order Count', <?= json_encode(array_sum($chartData['orderCounts'])) ?>
    );
    get_chart(
        <?= json_encode($chartData['orderTotalAmounts']) ?>, '#orderTotalChart', '#FEA64B', 'Total Amount', 'Order Date', 'Amount', <?= json_encode(array_sum($chartData['orderTotalAmounts'])) ?>
    );
    get_chart(
        <?= json_encode($chartData['gstTotalAmounts']) ?>, '#orderGstChart', '#90EE90', 'Total GST', 'Order Date', 'GST', <?= json_encode(array_sum($chartData['gstTotalAmounts'])) ?>
    );
    get_chart(
        <?= json_encode($chartData['withoutGstTotalAmounts']) ?>, '#orderWithoutGstChart', '#B5838D', 'Total Without GST', 'Order Date', 'Without GST', <?= json_encode(array_sum($chartData['withoutGstTotalAmounts'])) ?>
    );

</script>


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


    var totalWithoutGst = calculateColumnTotal(5);
    var totalGst = calculateColumnTotal(4);
    var totalTotal = calculateColumnTotal(3);
    var totalCount = calculateColumnTotal(2);

    document.getElementById('totalWithoutGst').innerText = totalWithoutGst.toFixed(2);
    document.getElementById('totalGst').innerText = totalGst.toFixed(2);
    document.getElementById('totalTotal').innerText = totalTotal.toFixed(2);
    document.getElementById('totalCount').innerText = totalCount;
</script>

<script>

    // Define the function to handle opening the modal
    function openOrderDetailsModal(date) {
        $.ajax({
            url: '<?php echo base_url('nc_day_wise_pickup_earnings_modal'); ?>',
            method: 'POST',
            data: { date: date },
            dataType: 'json',
            success: function (response) {
                $('#orderDetailsTableBody').empty();
                var sno = 1;

                var mtotalTotal = 0;
                var mtotalGst = 0;
                var mtotalWithoutGst = 0;

                $.each(response, function (index, order) {
                    var Id = order.id;
                    var trackId = order.track_id;
                    var encodedId = btoa(btoa(Id));
                    var url = '<?php echo base_url("food_orders/edit?id=") ?>' + encodedId;

                    $('#orderDetailsTableBody').append(
                        '<tr>' +
                        '<td>' + sno++ + '</td>' + // Increment sno for each order
                        '<td><a href="' + url + '" target="_blank">' + trackId + '</a></td>' +
                        '<td style="width: 150px;">' + formatDate(new Date(order.created_at)) + '</td>' +
                        '<td>' + order.nc_delivery_fee + '</td>' +
                        '<td>' + order.delivery_gst_percentage + '%' + '</td>' +
                        '<td>' + order.nc_delivery_fee_gst_value + '</td>' +
                        '<td>' + order.nc_delivery_fee_without_gst + '</td>' +
                        '</tr>'
                    );

                    // Update totals
                    mtotalTotal += isNaN(parseFloat(order.nc_delivery_fee)) ? 0 : parseFloat(order.nc_delivery_fee);
                    mtotalGst += isNaN(parseFloat(order.nc_delivery_fee_gst_value)) ? 0 : parseFloat(order.nc_delivery_fee_gst_value);
                    mtotalWithoutGst += isNaN(parseFloat(order.nc_delivery_fee_without_gst)) ? 0 : parseFloat(order.nc_delivery_fee_without_gst);
                });

                // Update footer totals
                $('#mtotalTotal').text(mtotalTotal.toFixed(2));
                $('#mtotalGst').text(mtotalGst.toFixed(2));
                $('#mtotalWithoutGst').text(mtotalWithoutGst.toFixed(2));

                // Show the modal after updating its content
                $("#orderDetailsModal").modal();
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function formatGraphDate(dateString) {
        // Split the date string into day, month, and year parts
        var parts = dateString.split('-');

        // Reconstruct the date in yyyy-mm-dd format
        var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];

        return formattedDate;
    }


    function formatDate(date) {

        var day = String(date.getDate()).padStart(2, '0');
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var year = date.getFullYear();
        var hours = String(date.getHours()).padStart(2, '0');
        var minutes = String(date.getMinutes()).padStart(2, '0');
        var ampm = hours >= 12 ? 'PM' : 'AM';

        // Convert to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12;

        // Construct formatted date string
        var formattedDate = day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ' ' + ampm;

        return formattedDate;
    }

</script>
<?php $this->load->view('vendorCrm/footer'); ?>