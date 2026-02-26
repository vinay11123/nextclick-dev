<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- JSZip (Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- pdfmake (PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<div class="card-body">
<div class="row mb-3">
    <div class="col-md-4">
        <label><b>App Type Filter</b></label>
        <select id="appTypeFilter" class="form-control">
            <option value="">-- All App Types --</option>
            <?php 
            $allowed_types = ['user', 'vendor', 'delivery_partner'];
            
            if(!empty($executives)){
                $types = array_unique(array_column($executives, 'primary_intent'));
            
                foreach($types as $type){
                    if(in_array(strtolower(trim($type)), $allowed_types)){
            ?>
                <option value="<?php echo $type; ?>">
                    <?php echo ucfirst($type); ?>
                </option>
            <?php 
                    }
                }
            }
            ?>
        </select>
    </div>
</div>
<!-- Tabs -->
<ul class="nav nav-tabs" id="userTabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#installed">
            Installed Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#uninstalled">
            Uninstalled Users
        </a>
    </li>
</ul>

<div class="tab-content mt-3">

<!-- ================= INSTALLED USERS ================= -->
<div class="tab-pane fade show active" id="installed">

<table class="table table-striped table-hover" id="installedTable" width="100%">
<thead>
<tr>
    <th><input type="checkbox" id="selectAllInstalled"></th>
    <th>Sno</th>
    <th>User Id</th>
    <th>App Type</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Created On</th>
</tr>
</thead>
<tbody>

<?php 
$sno = 1;
if(!empty($executives)):
foreach ($executives as $executive):
//if(isset($executive['app_status']) && $executive['app_status'] == 1):
?>
<tr>
    <td><input type="checkbox" class="rowCheckbox installedCheckbox " value="<?php echo $executive['id']; ?>"></td>
    <td><?php echo $sno++;?></td>
    <td><?php echo $executive['id'];?></td>
    <td><?php echo $executive['primary_intent'];?></td>
    <td><?php echo $executive['first_name'].' '.$executive['last_name'];?></td>
    <td><?php echo $executive['email'];?></td>
    <td><?php echo $executive['phone'];?></td>
    <td><?php echo $executive['created_at'];?></td>

</tr>
<?php 
//endif;
endforeach;
endif;
?>

</tbody>
</table>

</div>


<!-- ================= UNINSTALLED USERS ================= -->
<div class="tab-pane fade" id="uninstalled">

<table class="table table-striped table-hover" id="uninstalledTable" width="100%">
<thead>
<tr>
    <th><input type="checkbox" id="selectAllUninstalled"></th>
    <th>Sno</th>
    <th>User Id</th>
    <th>App Type</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Created On</th>
</tr>
</thead>
<tbody>

<?php 
$sno = 1;
if(!empty($executives)):
foreach ($executives as $executive):
//if(isset($executive['app_status']) && $executive['app_status'] == 0):
?>
<tr>
    <td><input type="checkbox" class="rowCheckbox  uninstalledCheckbox" ></td>
    <td><?php echo $sno++;?></td>
    <td><?php echo $executive['id'];?></td>
     <td><?php echo $executive['primary_intent'];?></td>
    <td><?php echo $executive['first_name'].' '.$executive['last_name'];?></td>
    <td><?php echo $executive['email'];?></td>
    <td><?php echo $executive['phone'];?></td>
    <td><?php echo $executive['created_at'];?></td>
    
</tr>
<?php 
//endif;
endforeach;
endif;
?>

</tbody>
</table>

</div>

</div>
</div>
<div class="form-group col-md-12">
    <label>Title</label>
    <input type="text" id="title" name="title" class="form-control" required>
</div>
<div class="form-group col-md-12"><label>Message</label>
	<textarea cols="80" id="message" class="ckeditor" name="message" rows="10"
		data-sample-short></textarea>
	<?php echo form_error('terms', '<div style="color:red">', '</div>'); ?>
</div>
<button class="btn btn-primary mb-2" id="sendNotification">
    Send Notification
</button>
<script>
$(document).ready(function() {

    function selectedRows() {
        return function(idx, data, node) {
            return $('input.rowCheckbox', node).prop('checked');
        };
    }

    // ================= INSTALLED TABLE =================
    var installedTable = $('#installedTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Installed Users',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'pdf',
                title: 'Installed Users',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'print',
                title: 'Installed Users',
                exportOptions: {
                    rows: selectedRows()
                }
            }
        ]
    });

    // ================= UNINSTALLED TABLE =================
    var uninstalledTable = $('#uninstalledTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Uninstalled Users',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'pdf',
                title: 'Uninstalled Users',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'print',
                title: 'Uninstalled Users',
                exportOptions: {
                    rows: selectedRows()
                }
            }
        ]
    });

    // ================= AUTO APP TYPE SEARCH =================
    $('#appTypeFilter').on('change', function() {

        var appType = $(this).val();

        // Column index 3 = App Type
        installedTable.column(3).search(appType).draw();
        uninstalledTable.column(3).search(appType).draw();
    });

    // ================= FIX TAB WIDTH ISSUE =================
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
    });

    // ================= SELECT ALL =================
    $('#selectAllInstalled').click(function(){
        $('#installedTable .rowCheckbox').prop('checked', this.checked);
    });

    $('#selectAllUninstalled').click(function(){
        $('#uninstalledTable .rowCheckbox').prop('checked', this.checked);
    });

});
</script>
<script>
$('#sendNotification').on('click', function (e) {

    e.preventDefault();

    var selectedIds = [];

    $('.rowCheckbox:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        alert("Please select at least one user");
        return false;
    }
    
        // Get title value
    var title = $('#title').val();

    // Get CKEditor message value
    var message = CKEDITOR.instances.message.getData();

    if (title.trim() === '' || message.trim() === '') {
        alert("Title and Message are required");
        return false;
    }

    $.ajax({
        url: "<?php echo base_url('app/sendPNotification'); ?>",
        type: "POST",
        dataType: "json",
        data: {
            user_ids: JSON.stringify(selectedIds),
            title: "New Offer 🔥",
            message: "You have received a new offer go through the nextclcik app",
            server_key: "App Users"
        },
        beforeSend: function () {
            $('#sendNotification').prop('disabled', true).text('Sending...');
        },
        success: function (response) {
            alert(response.message);
        },
        error: function () {
            alert("Something went wrong!");
        },
        complete: function () {
            $('#sendNotification').prop('disabled', false).text('Send Notification');
        }
    });

});
</script>