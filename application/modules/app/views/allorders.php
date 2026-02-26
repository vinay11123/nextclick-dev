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
            Top Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#uninstalled">
            Low Orders
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
    <th>UserID</th>
       <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Total Orders</th>
</tr>
</thead>
<tbody>

<?php 
$sno = 1;
if(!empty($toporders)):
foreach ($toporders as $order):
//if(isset($executive['app_status']) && $executive['app_status'] == 1):
?>
<tr>
    <td><input type="checkbox" class="rowCheckbox installedCheckbox " value="<?php echo $order['id']; ?>"></td>
    <td><?php echo $sno++;?></td>
    <td><?php echo $order['created_user_id'];?></td>
       <td><?php echo $order['first_name'] .''.$order['last_name']?></td>
    <td><?php echo $order['email'];?></td>
    <td><?php echo $order['phone'];?></td>
    <td><a href="<?php echo base_url(); ?>app/orderdetails?uid=<?php echo $order['created_user_id'] ?>"><?php echo $order['total_orders'];?></a></td>

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
    <th>UserID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Total Orders</th>
</tr>
</thead>
<tbody>

<?php 
$sno = 1;
if(!empty($loworders)):
foreach ($loworders as $order):
//if(isset($executive['app_status']) && $executive['app_status'] == 0):
?>
<tr>
    <td><input type="checkbox" class="rowCheckbox  uninstalledCheckbox" ></td>
    <td><?php echo $sno++;?></td>
    <td><?php echo $order['created_user_id'];?></td>
    <td><?php echo $order['first_name'] .''.$order['last_name']?></td>
    <td><?php echo $order['email'];?></td>
    <td><?php echo $order['phone'];?></td>
    <td><a href="<?php echo base_url(); ?>app/orderdetails?uid=<?php echo $order['created_user_id'] ?>"><?php echo $order['total_orders'];?></a></td>
    
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
                title: 'Orders List',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'pdf',
                title: 'Orders List',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'print',
                title: 'Orders List',
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
                title: 'Orders List',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'pdf',
                title: 'Orders List',
                exportOptions: {
                    rows: selectedRows()
                }
            },
            {
                extend: 'print',
                title: 'Orders List',
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
