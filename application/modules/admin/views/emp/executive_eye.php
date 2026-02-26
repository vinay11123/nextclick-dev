<style>

img.img-thumb{
    width: 18%;
    border-radius: 12px;
}

.zoom1 {
  transition: transform .2s; 
  margin: 0 auto;
}

.zoom1:hover {
  transform: scale(1.3);
  cursor: pointer;
}

.modal-target {
  width: 300px;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.modal-target:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; 
  position: fixed; 
  z-index: 1; 
  padding-top: 100px; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgba(0,0,0,0.8);
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 40%;
  opacity: 1 !important;
  max-width: 70%;
}

/* Caption of Modal Image */
.modal-caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 1200px;
  text-align: center;
  color: white;
  font-weight: 700;
  font-size: 1em;
  margin-top: 32px;
}

/* Add Animation */
.modal-content, .modal-caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0);} 
  to {-webkit-transform:scale(1);}
}

@keyframes zoom {
  from {transform:scale(0);} 
  to {transform:scale(1);}
}

/* The Close Button */
.modal-close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.modal-close:hover,
.modal-close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

div#modal{
    z-index: 999;
}
</style>

<?php 
if($type == 'executive'):

// Safe fallback initialization
$users = $users ?? [];
$edit = $edit ?? null;
$bank_details = $bank_details ?? [];
$exc_cities = $exc_cities ?? [];
$user_id = $users['id'] ?? $edit->id ?? 0;

?>

<div class="row">
    <div class="col-md-12">
       <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?= base_url('emp_list/executive');?>">User <i class="fa fa-angle-double-left"></i> Executive</a> 
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h4 class="ven">Employee Details</h4>
        <div class="card-header">
            <div class="form-row">
                <div class="row">

                    <div class="form-group col-md-6">
                        <label>User Id</label>
                        <div class="uid">
                            <p><?= $users['id'] ?? '' ?></p>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Profile</label>
                        <div class="prof">
                            <img src="<?= base_url("uploads/profile_image/profile_{$user_id}.jpg") ?>" class="img-thumb zoom1 modal-target">
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <div class="na">
                            <p><?= ($users['first_name'] ?? '') . ' ' . ($users['last_name'] ?? '') ?></p>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Mobile No.</label> 
                        <div class="mono">
                            <p><?= $users['phone'] ?? '' ?></p>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Email ID</label>
                        <div class="emailid">
                            <p><?= $users['email'] ?? '' ?></p>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Location</label>
                        <div class="plocation">
                            <p><?= $users['executive_address']['location'] ?? '' ?></p>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Aadhar Number</label>
                        <div class="aan">
                            <p><?= $users['executive_biometric']['aadhar'] ?? '' ?></p>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Aadhar Card Front</label>
                        <div class="aacf">
                            <img src="<?= base_url("uploads/aadhar_card_image/aadhar_card_front_{$user_id}.jpg") ?>" class="img-thumb zoom1 modal-target" style="width:80%">
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Aadhar Card Back</label>
                        <div class="adcback">
                            <img src="<?= base_url("uploads/aadhar_card_image/aadhar_card_back_{$user_id}.jpg") ?>" class="img-thumb zoom1 modal-target" style="width:80%">
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Bank Passbook</label>
                        <div class="bpbook">
                            <img src="<?= base_url("uploads/bank_passbook_image/bank_passbook_{$user_id}.jpg") ?>" class="img-thumb zoom1 modal-target" style="width:40%">
                        </div>
                    </div>

                    <!-- Executive Form -->
 <!-- Executive Form -->
 <form id="form-smtp" action="<?= base_url('vendor_profile/u/bank_details');?>" method="post" class="needs-validation form" novalidate>
<div class="card-header">
    <div class="form-row">

        <div class="form-group col-md-3">
            <label>Executive Type</label>
            <select name="vendor_type" id="vendor_type" class="form-control" required>
                <option value="">--select--</option>
                <option value="vendor" <?=($edit && $edit->vendor_type=='vendor')?'selected':''?>>Vendor</option>
                <option value="freelancer" <?=($edit && $edit->vendor_type=='freelancer')?'selected':''?>>NXC Freelancer</option>
                <option value="employer" <?=($edit && $edit->vendor_type=='employer')?'selected':''?>>NXC Employer</option>
                <option value="intern" <?=($edit && $edit->vendor_type=='intern')?'selected':''?>>NXC Intern</option>
            </select>
        </div>
 
        <input type="hidden" name="id" value="<?php echo $edit->id ; ?>">
        <div class="form-group col-md-3" id="teamLeadWrapper" style="display:none;">
            <label>Team Lead</label>
            <input type="text" name="team_lead" id="team_lead" class="form-control" value="<?= $edit->team_lead ?? '' ?>">
        </div>

        <div class="form-group col-md-3">
            <label>Executive Name</label>
            <input type="text" name="executive_name" class="form-control" value="<?= $edit->executive_name ?? '' ?>" required>
        </div>

        <div class="form-group col-md-3">
            <label>Executive ID</label>
            <input type="text" name="executive_id" class="form-control" value="<?= $edit->executive_id ?? '' ?>" required>
        </div>

        <div class="form-group col-md-3">
            <label>Amount</label>
            <input type="number" name="amount" class="form-control" value="<?= $edit->amount ?? '' ?>" required>
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

<!-- City -->
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

<!-- Circle -->
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

<!-- Ward -->
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
            <input type="number" name="target_freelancer" class="form-control" value="<?= $edit->target_freelancer ?? '' ?>" required>
        </div>
        <div class="form-group col-md-3">
            <label>Target Executive</label>
            <input type="number" name="executive_target" class="form-control" value="<?= $edit->executive_target ?? '' ?>" required>
        </div>
        <div class="form-group col-md-3">
            <label>Target Monthly</label>
            <input type="number" name="monthly_target" class="form-control" value="<?= $edit->monthly_target ?? '' ?>" required>
        </div>

        <!-- Status -->
        <div class="form-group col-md-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" <?=(!$edit || $edit->status=='pending')?'selected':''?>>Pending</option>
                <option value="approved" <?=($edit && $edit->status=='approved')?'selected':''?>>Approved</option>
                <option value="rejected" <?=($edit && $edit->status=='rejected')?'selected':''?>>Rejected</option>
            </select>
        </div>

        <!-- Team Members -->
        <div class="form-group col-md-6">
            <label>Team Members</label>
            <div id="team_wrapper">
                <?php
                $team = !empty($team_members) ? $team_members : [''];
                foreach($team as $t):
                ?>
                <div class="team_row mb-2">
                    <input type="text" name="team[]" class="form-control d-inline-block" style="width:85%" value="<?= htmlspecialchars($t) ?>">
                    <button type="button" class="btn btn-danger btn-sm remove_team">X</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add_team_btn" class="btn btn-success btn-sm mt-2">+ Add Team</button>
        </div>

        <!-- Roles Type -->
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

        <!--<div class="form-group col-md-2">-->
        <!--    <button class="btn btn-primary mt-27"><?= $edit ? 'Update' : 'Submit' ?></button>-->
        <!--</div>-->

    </div>
</div>


                    <!-- Modal -->
                    <div id="modal" class="modal">
                        <span id="modal-close" class="modal-close">&times;</span>
                        <img id="modal-content" class="modal-content">
                        <div id="modal-caption" class="modal-caption"></div>
                    </div>

                </div>
            </div>

        <!-- Bank Details Form -->
        <div class="row">
            <div class="col-12">
                
                    <section class="card">
                        <header class="card-header">
                            <h2 class="card-title ven">Bank Details</h2>
                        </header>
                        <div class="card-body">
                            <?php
                            $bank_fields = ['ac_holder_name'=>'A/C Holder Name','bank_name'=>'Bank Name','bank_branch'=>'Bank Branch','ac_number'=>'A/C Number','ifsc'=>'IFSC Code'];
                            foreach($bank_fields as $key=>$label): ?>
                            <div class="form-group row">
                                <label class="col-sm-3"><?= $label ?> <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="<?= $key ?>" class="form-control" placeholder="<?= $label ?>" required value="<?= $bank_details[$key] ?? '' ?>">
                                </div>
                                <?= form_error($key,'<div style="color:red">','</div>'); ?>
                            </div>
                            <?php endforeach; ?>
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <input type="hidden" name="list_id" value="<?= $_GET['eye_id'] ?? '' ?>">
                                    <button class="btn btn-primary">Submit</button>
                                    <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset">
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
// Modal
var modal = document.getElementById('modal');
var modalClose = document.getElementById('modal-close');
modalClose.addEventListener('click', function(){ modal.style.display = 'none'; });
document.addEventListener('click', function(e){
    if(e.target.className.indexOf('modal-target')!==-1){
        var img = e.target;
        var modalImg = document.getElementById('modal-content');
        var captionText = document.getElementById('modal-caption');
        modal.style.display = 'block';
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
    }
});

// Team Members Add/Remove
document.getElementById('add_team_btn').onclick = function () {
    let div = document.createElement('div');
    div.className = 'team_row mb-2';
    div.innerHTML = '<input type="text" name="team[]" class="form-control d-inline-block" style="width:85%"><button type="button" class="btn btn-danger btn-sm remove_team">X</button>';
    document.getElementById('team_wrapper').appendChild(div);
};
document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove_team')){
        e.target.parentElement.remove();
    }
});

// Toggle Team Lead Input
document.addEventListener('DOMContentLoaded', function(){
    const vendorType = document.getElementById('vendor_type');
    const teamWrapper = document.getElementById('teamLeadWrapper');
    const teamInput = document.getElementById('team_lead');
    function toggleTeamLead(){
        if(vendorType.value==='employer'){
            teamWrapper.style.display='block';
            teamInput.required = true;
            teamInput.disabled = false;
        }else{
            teamWrapper.style.display='none';
            teamInput.required=false;
            teamInput.disabled=true;
            teamInput.value='';
        }
    }
    vendorType.addEventListener('change', toggleTeamLead);
    toggleTeamLead();
});
</script>

<?php endif; ?>
