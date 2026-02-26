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

<!-- Tabs -->
<ul class="nav nav-tabs" id="userTabs">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#installed">
            Installed Vendors
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#uninstalled">
            Uninstalled Vendors
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
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Created On</th>
    <th>Status</th>
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
    <td><input type="checkbox" class="rowCheckbox installedCheckbox "></td>
    <td><?php echo $sno++;?></td>
    <td><?php echo $executive['id'];?></td>
    <td><?php echo $executive['first_name'].' '.$executive['last_name'];?></td>
    <td><?php echo $executive['email'];?></td>
    <td><?php echo $executive['phone'];?></td>
    <td><?php echo $executive['created_at'];?></td>
    <td>
        <?php if($executive['status'] == 1): ?>
            <button class="btn btn-success btn-sm">Active</button>
        <?php else: ?>
            <button class="btn btn-danger btn-sm">Inactive</button>
        <?php endif; ?>
        </td>
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
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Created On</th>
    <th>Status</th>
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
    <td><input type="checkbox" class="rowCheckbox  uninstalledCheckbox"></td>
    <td><?php echo $sno++;?></td>
    <td><?php echo $executive['id'];?></td>
    <td><?php echo $executive['first_name'].' '.$executive['last_name'];?></td>
    <td><?php echo $executive['email'];?></td>
    <td><?php echo $executive['phone'];?></td>
    <td><?php echo $executive['created_at'];?></td>
    <td>
<?php if($executive['status'] == 1): ?>
    <button class="btn btn-success btn-sm">Active</button>
<?php else: ?>
    <button class="btn btn-danger btn-sm">Inactive</button>
<?php endif; ?>
</td>
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
<button class="btn btn-primary mb-2" id="sendNotification">
    Send Notification
</button>
<script>
$(document).ready(function() {

    // Common export function
    function selectedRows(tableId) {
        return function(idx, data, node) {
            return $('input.rowCheckbox', node).prop('checked');
        };
    }

    var installedTable = $('#installedTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Installed Users',
                exportOptions: {
                    rows: selectedRows('#installedTable')
                }
            },
            {
                extend: 'pdf',
                title: 'Installed Users',
                exportOptions: {
                    rows: selectedRows('#installedTable')
                }
            },
            {
                extend: 'print',
                title: 'Installed Users',
                exportOptions: {
                    rows: selectedRows('#installedTable')
                }
            }
        ]
    });

    var uninstalledTable = $('#uninstalledTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Uninstalled Users',
                exportOptions: {
                    rows: selectedRows('#uninstalledTable')
                }
            },
            {
                extend: 'pdf',
                title: 'Uninstalled Users',
                exportOptions: {
                    rows: selectedRows('#uninstalledTable')
                }
            },
            {
                extend: 'print',
                title: 'Uninstalled Users',
                exportOptions: {
                    rows: selectedRows('#uninstalledTable')
                }
            }
        ]
    });

    // Fix tab width issue
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
    });

    // Select All Installed
    $('#selectAllInstalled').click(function(){
        $('#installedTable .rowCheckbox').prop('checked', this.checked);
    });

    // Select All Uninstalled
    $('#selectAllUninstalled').click(function(){
        $('#uninstalledTable .rowCheckbox').prop('checked', this.checked);
    });

});
</script>