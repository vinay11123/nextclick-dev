<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.elementToFadeInAndOut{
    display:block;
    animation: fadeinout 10s linear forwards;
}
@keyframes fadeinout{
    0%,100%{opacity:0}
    50%{opacity:1}
}
.mt-27{ margin-top:27px; }

</style>

<?php
$edit = isset($exec_edit) ? $exec_edit : null;
$form_action = $edit
    ? base_url('admin/exc_role/u')
    : base_url('admin/exc_role/c');
?>

<div class="row">
<div class="col-12">

<!-- ================= ADD / EDIT FORM ================= -->
<?php if ($this->ion_auth->is_admin() || $this->ion_auth_acl->has_permission('executive_add')): ?>

<h4 class="ven subcategory">
    <?= $edit ? 'Edit Marketing Executive' : 'Add Marketing Executive' ?>
</h4>

<form method="post" action="<?= $form_action ?>" class="needs-validation" novalidate>

<?php if($edit): ?>
<input type="hidden" name="id" value="<?= $edit->id ?>">
<?php endif; ?>

<div class="card-header">
<div class="form-row">

<div class="form-group col-md-3">
    <label>Executive Type</label>
    <select name="vendor_type"
            id="vendor_type"
            class="form-control"
            required>
        <option value="">--select--</option>
        <option value="freelancer" <?=($edit && $edit->vendor_type=='vendor')?'selected':''?>>vendor</option>
         <option value="freelancer" <?=($edit && $edit->vendor_type=='freelancer')?'selected':''?>>NXC Freelancer</option>
        <option value="employer" <?=($edit && $edit->vendor_type=='employer')?'selected':''?>>NXC Employer</option>
        <option value="intern" <?=($edit && $edit->vendor_type=='intern')?'selected':''?>>NXC Intern</option>
    </select>
</div>



<div class="form-group col-md-3">
<label>Executive Name</label>
<input type="text" name="executive_name" class="form-control"
value="<?= $edit->executive_name ?? '' ?>" required>
</div>

<div class="form-group col-md-3">
<label>Executive ID</label>
<input type="text" name="executive_id" class="form-control"
value="<?= $edit->executive_id ?? '' ?>" required>
</div>

<div class="form-group col-md-3">
<label>Amount</label>
<input type="number" name="amount" class="form-control"
value="<?= $edit->amount ?? '' ?>" required>
</div>

<div class="form-group col-md-3">
<label>Area Type</label>
<select name="area_type" class="form-control" required>
    <option value="">--select--</option>
    <option value="urban" <?=($edit && $edit->area_type=='urban')?'selected':''?>>Urban</option>
    <option value="tier1" <?=($edit && $edit->area_type=='tier1')?'selected':''?>>Tier-1</option>
    <option value="tier2" <?=($edit && $edit->area_type=='tier2')?'selected':''?>>Tier-2</option>
</select>
</div>

<div class="form-group col-md-3">
<label>City</label>
<select name="city_name" class="form-control" required>
<option value="">--select--</option>
<?php if(!empty($exc_cities)): foreach($exc_cities as $c): ?>
<option value="<?= $c->city_name ?>"
<?=($edit && $edit->city_name==$c->city_name)?'selected':''?>>
<?= $c->city_name ?>
</option>
<?php endforeach; endif; ?>
</select>
</div>

<div class="form-group col-md-3">
<label>Circle</label>
<select name="circle" class="form-control" required>
<option value="">--select--</option>
<?php if(!empty($exc_cities)): foreach($exc_cities as $c): ?>
<option value="<?= $c->circle ?>"
<?=($edit && $edit->circle==$c->circle)?'selected':''?>>
<?= $c->circle ?>
</option>
<?php endforeach; endif; ?>
</select>
</div>

<div class="form-group col-md-3">
<label>Ward</label>
<select name="ward" class="form-control" required>
<option value="">--select--</option>
<?php if(!empty($exc_cities)): foreach($exc_cities as $c): ?>
<option value="<?= $c->ward ?>"
<?=($edit && $edit->ward==$c->ward)?'selected':''?>>
<?= $c->ward ?>
</option>
<?php endforeach; endif; ?>
</select>
</div>

<div class="form-group col-md-3">
<label>Target Freelancer</label>
<input type="number" name="target_freelancer" class="form-control"
value="<?= $edit->target_freelancer ?? '' ?>" required>
</div>
<div class="form-group col-md-3">
<label>Target Executive</label>
<input type="number" name="executive_target" class="form-control"
value="<?= $edit->executive_target ?? '' ?>" required>
</div>
<div class="form-group col-md-3">
<label>Target Monthly</label>
<input type="number" name="monthly_target" class="form-control"
value="<?= $edit->monthly_target ?? '' ?>" required>
</div>


<!-- ✅ STATUS FIELD (DEFAULT PENDING) -->
<div class="form-group col-md-3">
<label>Status</label>
<select name="status" class="form-control" required>
    <option value="pending" <?=(!$edit || $edit->status=='pending')?'selected':''?>>Pending</option>
    <option value="approved" <?=($edit && $edit->status=='approved')?'selected':''?>>Approved</option>
    <option value="rejected" <?=($edit && $edit->status=='rejected')?'selected':''?>>Rejected</option>
</select>
</div>

<div class="form-group col-md-6">
<label>Team Members</label>

<div id="team_wrapper">
<?php
$team = $edit ? json_decode($edit->team_members, true) : [''];
foreach($team as $t):
?>
<div class="team_row mb-2">
<input type="text" name="team[]" class="form-control d-inline-block"
style="width:85%" value="<?= $t ?>">
<button type="button" class="btn btn-danger btn-sm remove_team">X</button>
</div>
<?php endforeach; ?>
</div>

<button type="button" id="add_team_btn" class="btn btn-success btn-sm mt-2">
+ Add Team
</button>
</div>
<?php if (!empty($edit)): ?>
 <div class="form-group row">
    <label class="col-sm-3 col-form-label">
        Roles Type <span class="text-danger">*</span>
    </label>

    <div class="col-sm-9">

        <?php 
        $roles = [
            'user_onboard'     => 'User Onboard',
            'vendor_onboard'   => 'Vendor Onboard',
            'delivery_onboard' => 'Delivery Onboard'
        ];

        $selected_roles = !empty($edit->role_type) 
            ? array_map('trim', explode(',', $edit->role_type)) 
            : [];

        foreach ($roles as $key => $label): 
        ?>
            <div class="form-check mb-2">
                <input 
                    class="form-check-input"
                    type="checkbox"
                    name="onboard_roles[]"
                    id="<?= $key ?>"
                    value="<?= $key ?>"
                    <?= in_array($key, $selected_roles, true) ? 'checked' : '' ?>
                    
                >
                <label class="form-check-label" for="<?= $key ?>">
                    <?= $label ?>
                </label>
            </div>
        <?php endforeach; ?>


    </div>
</div>

        <?php endif; ?>


<div class="form-group col-md-2">
<button class="btn btn-primary mt-27">
<?= $edit ? 'Update' : 'Submit' ?>
</button>
</div>

</div>
</div>
</form>
<?php endif; ?>

<!-- ================= FLASH MESSAGE ================= -->
<?php if($this->session->flashdata('upload_status')): ?>
<div class="alert alert-success elementToFadeInAndOut">
<?= $this->session->flashdata('upload_status'); ?>
</div>
<?php endif; ?>

<!-- ================= LIST ================= -->
<div class="card">
<div class="card-header">
<h4>List of Marketing Executives</h4>
</div>

<div class="card-body">
<div class="table-responsive">

<table class="table table-striped table-hover">
<thead>
<tr>
<th>#</th>
<th>Name</th>
<th>ID</th>
<th>Type</th>
<th>City</th>
<th>Executive Target</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php if(!empty($exec_list)): $i=1; foreach($exec_list as $e): ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $e->executive_name ?></td>
<td><?= $e->executive_id ?></td>
<td><?= ucfirst($e->vendor_type) ?></td>
<td><?= $e->city_name ?></td>
<td><?= $e->executive_target ?></td>

<td>
<?php if($e->status=='pending'): ?>
    <span class="badge badge-warning">Pending</span>
<?php elseif($e->status=='approved'): ?>
    <span class="badge badge-success">Approved</span>
<?php else: ?>
    <span class="badge badge-danger">Rejected</span>
<?php endif; ?>
</td>

<td>
<a href="<?= base_url('admin/exc_role/edit?id='.$e->id) ?>">
<i class="fas fa-pencil-alt"></i>
</a>
<a href="<?= base_url('admin/exc_role/delete/'.$e->id) ?>"
onclick="return confirm('Are you sure?')" class="text-danger ml-2">
<i class="far fa-trash-alt"></i>
</a>
</td>
</tr>
<?php endforeach; else: ?>
<tr>
<td colspan="8" class="text-center">No Records Found</td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</div>
</div>

</div>
</div>

<!-- ================= JS ================= -->
<script>
document.getElementById('add_team_btn').onclick = function () {
    let div = document.createElement('div');
    div.className = 'team_row mb-2';
    div.innerHTML =
        '<input type="text" name="team[]" class="form-control d-inline-block" style="width:85%">' +
        '<button type="button" class="btn btn-danger btn-sm remove_team">X</button>';
    document.getElementById('team_wrapper').appendChild(div);
};

document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove_team')){
        e.target.parentElement.remove();
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const vendorType = document.getElementById('vendor_type');
    const teamWrapper = document.getElementById('teamLeadWrapper');
    const teamInput  = document.getElementById('team_lead');

    function toggleTeamLead() {
        if (vendorType.value === 'employer') {
            teamWrapper.style.display = 'block';
            teamInput.required = true;
            teamInput.disabled = false;
        } else {
            teamWrapper.style.display = 'none';
            teamInput.required = false;
            teamInput.disabled = true;
            teamInput.value = '';
        }
    }

    // On change
    vendorType.addEventListener('change', toggleTeamLead);

    // On page load (EDIT case)
    toggleTeamLead();
});
</script>
