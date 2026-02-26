<style>
    .page-item>a {
        position: relative;
        display: block;
        padding: .5rem .75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    a {
        color: #007bff;
        text-decoration: none;
        background-color: transparent;
    }

    .pagination>li.active>a {
        background-color: orange !important;
    }

    .dataTables_filter {
        display: none;
    }

    .or {
        text-align: center;
    }

    select.form-control {
        appearance: none;
    }
</style>

<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->

        <!-- [ navigation menu ] end -->
        <div class="pcoded-content">
            <div class="main-body">
                <div class="page-wrapper">

                    <!-- Page-body start -->
                    <div class="page-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-header">
                                    <h4 class="ven subcategory">Users Filter</h4>
                                    <form id="resetForm" novalidate action="<?php echo base_url('employee/r/0'); ?>" method="post"
                                        enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label for="q">Search</label>
                                                <input type="text" name="q" id="q" placeholder="Name or mobile number" value="<?php echo $q; ?>"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="exe">Unique Id</label>
                                                <input type="text" id="exe" name="unique_id" placeholder="Unique Id" class="form-control">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="status">Role</label>
                                                <select class="form-control" name="group">
                                                    <option value="0">All</option>
                                                    <?php foreach ($groups as $g): ?>
                                                        <option value="<?php echo $g['id']; ?>" <?php echo ($g['id'] == $group) ? "selected" : "" ?>>
                                                            <?php echo $g['description']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="noofrows">rows</label>
                                                <input type="text" id="noofrows" name="noofrows" placeholder="rows"
                                                    value="<?php echo $noofrows; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-primary mt">
                                                    <i class="fa fa-search newserch" aria-hidden="true"></i>&nbsp;Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="form-group col-md-12">
                                        <form class="needs-validation " novalidate action="<?php //echo base_url('vendors_filter/0'); ?>"
                                            method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="q" placeholder="Search" value="" class="form-control">
                                            <button type="submit" name="submit" id="upload" value="Apply" class="btn btn-danger mt7"><i
                                                    class="fas fa-eraser newserch"></i>&nbsp;Clear</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="col-10 ven1 ">List of Users</h4>
                                    <?php if ($this->ion_auth_acl->has_permission('all_users_add')): ?>
                                        <a class="btn btn-outline-dark col-2" href="<?php echo base_url('add_vehicle/c/0') ?>"
                                            style="float:right;"> Add Employee</a>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="tableExportNoPaging" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>User Id</th>
                                                    <th>User Name</th>
                                                    <th>Wallet(RS)</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Created On</th>
                                                    <th>Role</th>
                                                    <th class="not-export-column">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($this->ion_auth_acl->has_permission('all_users_view')): ?>
                                                    <?php if (!empty($users)): ?>
                                                        <?php $i = 1;
                                                        foreach ($users as $user): ?>
                                                            <tr>
                                                                <td><?php echo $i++; ?></td>
                                                                <td class="tdcolorone"><?php echo $user['id']; ?></td>
                                                                <td class="tdcolortwo"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                                                <td class="tdcolorone"><?php echo $user['wallet']; ?></td>
                                                                <td class="tdcolortwo"><?php echo $user['phone']; ?></td>
                                                                <td class="tdcolorone"><?php echo $user['email']; ?></td>
                                                                <td class="tdcolortwo"><?php echo $user['created_at']; ?></td>
                                                                <td>
                                                                    <ul>
                                                                        <?php foreach ($user['groups'] as $group): ?>
                                                                            <li><?php echo $group['name'] ?></li>
                                                                        <?php endforeach; ?>
                                                                    </ul>
                                                                </td>
                                                                <td>
                                                                    <?php if ($this->ion_auth_acl->has_permission('all_users_edit')): ?>
                                                                        <a href="<?php echo base_url() ?>employee/edit/0?id=<?php echo $user['id']; ?>"
                                                                            class="mr-2"> <i class="feather icon-edit"></i></a>
                                                                    <?php endif; ?>
                                                                    <?php if ($this->ion_auth_acl->has_permission('all_users_details')): ?>
                                                                        <a href="<?php echo base_url() ?>employee/eye/0?id=<?php echo $user['id']; ?>"
                                                                            class="mr-2" type="category"> <i class="feather icon-eye"></i></a>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <th colspan='8'>
                                                                <h3>
                                                                    <center>No users available!</center>
                                                                </h3>
                                                            </th>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <th colspan='8'>
                                                            <h3>
                                                                <center>No Access!</center>
                                                            </h3>
                                                        </th>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row  justify-content-center">
                <div class=" col-12" style='margin-top: 10px;'>
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
<script>
    function myFunction() {
        document.getElementById("resetForm").reset();
    } 
</script>
<?php $this->load->view('vendorCrm/scripts'); ?>
<?php $this->load->view('vendorCrm/footer'); ?>
