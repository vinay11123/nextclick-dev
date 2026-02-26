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
                                <li class="breadcrumb-item">Ecommerce</li>
                                <li class="breadcrumb-item">Orders</li>
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
                                        <h4 class="ven subcategory">Pickup Order Filter</h4>
                                        <div class="row align-items-center">
                                            <div class="col-md-10">
                                                <form class="search-form" novalidate="" action="#" method="post"
                                                    enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label for="q"
                                                                class="col-form-label font-weight-bold">Search
                                                                By:</label>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <select name="q" id="q" class="form-control border">
                                                                <option value="">Select</option>
                                                                <option value="track_id">Track ID</option>
                                                                <option value="customer_name">Customer Name</option>
                                                                <option value="first_name">Delivery Captain Name
                                                                </option>
                                                                <option value="payment_mode">Payment Mode</option>
                                                                <option value="txn_id">Payment ID</option>
                                                                <option value="status">Status</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4" id="qdropdownContainer"
                                                            style="display:none;">
                                                            <select name="qdropdown" id="qdropdown"
                                                                class="form-control border">
                                                                <!-- Customer names will be populated here -->
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4" id="searchContainer"
                                                            style="display:none;">
                                                            <input type="text" id="search" name="search"
                                                                class="form-control" placeholder="Search Value">
                                                        </div>

                                                        <div class="col-md-1">
                                                            <button type="submit" name="submit" id="search-btn"
                                                                value="Apply" class="btn btn-primary">
                                                                <i class="feather icon-search newserch"
                                                                    aria-hidden="true"></i>&nbsp;Search
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="q-error"></div>
                                                    <div id="search-error"></div>
                                                    <div id="qdropdown-error"></div>
                                                </form>
                                            </div>

                                            <div class="col-md-1">
                                                <form class="clear-form" novalidate="" action="" method="post"
                                                    enctype="multipart/form-data">
                                                    <button type="submit" name="submit" id="clear-btn" value="Apply"
                                                        class="btn btn-danger">
                                                        <i class="feather icon-x newserch"></i>&nbsp;Clear
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="ven text-center">List of Pickup Orders</h4>
                                        <div class="table-responsive">
                                            <table id="PickupOrderDatatable" class="table table-striped table-hover"
                                                style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>S.no</th>
                                                        <th>Order Info</th>
                                                        <th>Delivery Info</th>
                                                        <th>Distance & Charges Info</th>
                                                        <th>Order Status</th>
                                                        <th>Created At</th>
                                                        <th>Action</th>

                                                        <th>Category Name</th>
                                                        <th>Track ID</th>
                                                        <th>Customer Name</th>
                                                        <th>Customer Phone</th>
                                                        <th>Delivery Boy ID</th>
                                                        <th>Delivery Boy Name</th>
                                                        <th>Delivery Boy Phone</th>
                                                        <th>Pickup Address</th>
                                                        <th>Delivery Address</th>

                                                        <th>Pl Latitude</th>
                                                        <th>Pl Longitude</th>
                                                        <th>Dl Latitude</th>
                                                        <th>Dl Longitude</th>

                                                        <th>Delivery Fee</th>
                                                        <th>Delivery Gst Percentage</th>
                                                        <th>Delivery Fee Without Gst</th>
                                                        <th>Delivery Fee Gst Value</th>
                                                        <th>Delivery Boy Delivery Fee</th>
                                                        <th>Delivery Boy Delivery Fee Without Gst</th>
                                                        <th>Delivery Boy Delivery Fee Gst Value</th>
                                                        <th>Nc Delivery Fee</th>
                                                        <th>Nc Delivery Fee Without Gst</th>
                                                        <th>Nc Delivery Fee Gst Value</th>

                                                        <th>Actual Distance</th>
                                                        <th>Gmap Distance</th>
                                                        <th>Flat Distance</th>
                                                        <th>Flat Rate</th>
                                                        <th>Nc Flat Rate</th>
                                                        <th>Per KM</th>
                                                        <th>Nc Per KM</th>

                                                        <th>Payment Id</th>
                                                        <th>Payment Method</th>
                                                        <th>Vehicle Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>

                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 pagination-links" style="margin-top: 10px;">
                                                <?= $pagination; ?>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var qSelect = document.getElementById("q");
        var qDropdownContainer = document.getElementById("qdropdownContainer");
        var qDropdown = document.getElementById("qdropdown");

        qSelect.addEventListener("change", function () {
            var selectedValue = qSelect.value;

            if (selectedValue === "first_name") {
                qDropdownContainer.style.display = "block";
                qDropdown.innerHTML = "<option value=''>Select</option>";

                <?php foreach ($deliveryResult as $delivery): ?>
                    var option = document.createElement("option");
                    option.value = "<?php echo $delivery['id']; ?>";
                    option.textContent = "<?php echo $delivery['first_name'] . ' - (' . $delivery['phone'] . ')'; ?>";
                    qDropdown.appendChild(option);
                <?php endforeach; ?>
            } else if (selectedValue === "customer_name") {
                qDropdownContainer.style.display = "block";
                qDropdown.innerHTML = "<option value=''>Select</option>";
                <?php foreach ($customerResult as $customer): ?>
                    var option = document.createElement("option");
                    option.value = "<?php echo $customer['id']; ?>";
                    option.textContent = "<?php echo $customer['first_name'] . ' - (' . $customer['phone'] . ')'; ?>";
                    qDropdown.appendChild(option);
                <?php endforeach; ?>
            } else if (selectedValue === "payment_mode") {
                qDropdownContainer.style.display = "block";
                qDropdown.innerHTML = "<option value=''>Select</option>";
                <?php foreach ($paymentResult as $payment): ?>
                    var option = document.createElement("option");
                    option.value = "<?php echo $payment['id']; ?>";
                    option.textContent = "<?php echo $payment['name']; ?>";
                    qDropdown.appendChild(option);
                <?php endforeach; ?>
            } else if (selectedValue === "status") {
                qDropdownContainer.style.display = "block";
                qDropdown.innerHTML = "<option value=''>Select</option>";
                <?php foreach ($statusResult as $status): ?>
                    var option = document.createElement("option");
                    option.value = "<?php echo $status['status']; ?>";
                    option.textContent = "<?php echo $status['status']; ?>";
                    qDropdown.appendChild(option);
                <?php endforeach; ?>
            } else {
                qDropdownContainer.style.display = "none";
            }
        });
    });

    $(document).ready(function () {

        $('#q').change(function () {
            $('#q-error').html('');
            var selectedOption = $(this).val();
            if (selectedOption === 'customer_name' || selectedOption === 'first_name' || selectedOption === 'payment_mode' || selectedOption === 'status') {
                $('#search').val('');
                $('#searchContainer').hide();
                $('#qdropdownContainer').show();
            } else if (selectedOption === 'track_id') {
                $('#search').val('');
                $('#searchContainer').show();
                $('#qdropdownContainer').hide();
            } else if (selectedOption === 'txn_id') {
                $('#search').val('');
                $('#searchContainer').show();
                $('#qdropdownContainer').hide();
            }

            $('#search').on('input', function () {
                $('#search-error').html('');
            });

            $('#qdropdown').change(function () {
                $('#qdropdown-error').html('');
            });
        });

        var dataTableInitialized = false;
        var formData = '';

        $('.search-form').submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();

            var submitValue = $(this).find('button[name="submit"]').val();

            if (submitValue === 'Apply') {
                var formDataArray = formData.split('&');
                var formDataVal = {};
                for (var i = 0; i < formDataArray.length; i++) {
                    var pair = formDataArray[i].split('=');
                    formDataVal[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
                }
                var qExists = 'q' in formDataVal;
                var searchExists = 'search' in formDataVal;
                var qdropdownExists = 'qdropdown' in formDataVal;

                if (qExists && (searchExists || qdropdownExists)) {
                    if (qExists && formDataVal['q'] === '') {
                        $('#q-error').html('<div class="text-danger">Please select dropdown value</div>');
                        $('#search-error').html('');
                        $('#qdropdown-error').html('');
                        return;
                    } else if (qdropdownExists && $('#qdropdownContainer').is(':visible') && formDataVal['qdropdown'] === '') {
                        $('#q-error').html('');
                        $('#search-error').html('');
                        $('#qdropdown-error').html('<div class="text-danger">Please select an option from dropdown</div>');
                        return;
                    } else if (searchExists && $('#searchContainer').is(':visible') && formDataVal['search'] === '') {
                        $('#q-error').html('');
                        $('#search-error').html('<div class="text-danger">Please enter a search term</div>');
                        $('#qdropdown-error').html('');
                        return;
                    }
                }

            }

            $(this).data('submitted', true);
            if (dataTableInitialized) {
                // If DataTable is already initialized, destroy it before reinitialization
                $('#PickupOrderDatatable').DataTable().destroy();
            }
            initializeDataTable(formData);
            dataTableInitialized = true;
        });


        if (!$('.search-form').data('submitted')) {
            initializeDataTable('');
            dataTableInitialized = true;
        }


        function initializeDataTable(formData) {
            $('#PickupOrderDatatable').DataTable({
                dom: 'Bflritip',
                buttons: [{
                    extend: 'excel',
                    exportOptions: {
                        columns: [0, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22,
                            23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 4, 5
                        ]
                    },
                }],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('epickup_orders/r'); ?>",
                    type: 'post',
                    data: {
                        formData: formData
                    }
                },
                columns: [{
                    data: "sno"
                },
                {
                    data: "order_info"
                },
                {
                    data: "delivery_info"
                },
                {
                    data: "distance_info"
                },
                {
                    data: "order_status"
                },
                {
                    data: "created_at"
                },
                {
                    data: "action"
                },

                {
                    data: "category_name"
                },
                {
                    data: "track_id"
                },
                {
                    data: "customer_name"
                },
                {
                    data: "customer_phone"
                },
                {
                    data: "delivery_boy_id"
                },
                {
                    data: "delivery_boy_name"
                },
                {
                    data: "delivery_boy_phone"
                },
                {
                    data: "pickup_address"
                },
                {
                    data: "delivery_address"
                },

                {
                    data: "pl_latitude"
                },
                {
                    data: "pl_longitude"
                },
                {
                    data: "dl_latitude"
                },
                {
                    data: "dl_longitude"
                },
                {
                    data: "delivery_fee"
                },
                {
                    data: "delivery_gst_percentage"
                },
                {
                    data: "delivery_fee_without_gst"
                },
                {
                    data: "delivery_fee_gst_value"
                },
                {
                    data: "delivery_boy_delivery_fee"
                },
                {
                    data: "delivery_boy_delivery_fee_without_gst"
                },
                {
                    data: "delivery_boy_delivery_fee_gst_value"
                },
                {
                    data: "nc_delivery_fee"
                },
                {
                    data: "nc_delivery_fee_without_gst"
                },
                {
                    data: "nc_delivery_fee_gst_value"
                },

                {
                    data: "actual_distance"
                },
                {
                    data: "gmap_distance"
                },
                {
                    data: "flat_distance"
                },
                {
                    data: "flat_rate"
                },
                {
                    data: "nc_flat_rate"
                },
                {
                    data: "per_km"
                },
                {
                    data: "nc_per_km"
                },

                {
                    data: "payment_id"
                },
                {
                    data: "payment_mode"
                },
                {
                    data: "vehicle_name"
                }
                ],
                order: [
                    [5, "desc"]
                ],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                columnDefs: [{
                    visible: false,
                    targets: [
                        4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
                        31, 32, 33, 34, 35, 36, 37, 38, 39
                    ]
                },
                {
                    "targets": [0, 1, 2, 3, 6],
                    "orderable": false,
                },
                ],
                initComplete: function (settings, json) {
                    $('#PickupOrderDatatable_filter').show();
                }
            });
        }


        // Function to reset the form fields
        function resetFormFields() {
            $('#q').val('');
            $('#search').val('');
            $('#qdropdown').val('');
        }

        // Clear form when "Clear" button is clicked
        $('#clear-btn').click(function (event) {
            event.preventDefault();
            resetFormFields();
        });

    });
</script>
<?php $this->load->view('vendorCrm/footer'); ?>