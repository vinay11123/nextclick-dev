<style>
    .space {
    position: relative;
    left: 78px;
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
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.8); /* Black w/ opacity */
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
  from {-webkit-atransform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
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
/* div#modal{
    z-index: 3333 !important;
} */
.modal-close:hover,
.modal-close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

.modal {
    
    z-index: 999 !important;
}
h2.card-title.ven.subcategory {
   
    transform: translate(334px, 10px);
}
</style> 
<div class="row pb-4">
    <div class="col-md-12">
   
       <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('vendors_filter/0');?>">User <i class="fa fa-angle-double-left"></i> Profile</a> 
   
    </div>
    </div>   

<div class="container">
    <div class="row">
        <div class="col-md-12" style="">
            <form id="form_site_settings" action="<?php echo base_url('vendor_profile/profile');?>" method="post" class="needs-validation reset"  enctype="multipart/form-data">
                <input type="hidden" name="page" value="<?php echo $this->input->get('page') ; ?>">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven subcategory">Vendor Profile</h2>
                    </header>
                    <div class="row bordercfw">
            <div class="col-md-3">
                <div class="profile-work"></div>
            </div>
            <div class="col-md-8">
            <div class="row bordercfw">

                    <div class="col-md-5">
                        <div class="profile-img ">
                            <h5 style="float:left">Profile Photo</h5><br />
                            <img class="zoom1 modal-target" id="mainimage5" style="width:33%;float: left;position: relative;left: -117px;" src="<?php echo base_url(); ?>uploads/profile_image/profile_<?php echo $vendor_details['vendor_user_id']?>.jpg?<?php echo time();?>">
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="profile-head">
                            <h5 style="">
                                <?php echo $vendor_details['name']; ?><span class="badge badge-secondary desprove"><?php echo ($vendor_details['status'] == 1)? 'APPROVED' : 'DISAPPROVED'?></span></h5>

                            <h6 style="">Vendor Partner Status</h6>
                        </div>

                    </div>
                </div>
              </div>
             </div>

                    <div class="card-body">
						
						<div class="form-group row">
                            <label class="col-sm-2">Shop Location<span class="required">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" name="location_name" id="location_name" class="form-control" placeholder="Locations Name" required="" value="<?php echo $vendor_details['location']['address']?>">
                            </div>
                            
							<?php if($user->id==1) { ?>
							<div class="col-sm-2">
                            	<button type="button" class="btn btn-sm btn-warning" onclick="initialize()">Get Location</button>
                            </div>
							<?php } ?>
                            <?php echo form_error('name','<div style="color:red">','</div>');?>
                        </div>
                        <div class="row">
                        	 <div class="form-group col-sm-6 ">
                        	 	<label class="">Latitude<span class="required">*</span></label>
                        	 	<input type="text" name="latitude" class="form-control"  id="latitude" value="<?php echo $vendor_details['location']['latitude']?>">
                        	 </div>
                        	 <div class="form-group col-sm-6 ">
                        	 	<label class="">Longitude<span class="required">*</span></label>
                        	 	<input type="text" name="longitude" class="form-control"  id="logitude" value="<?php echo $vendor_details['location']['longitude']?>">
                        	 </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Owner Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="owner_name" class="form-control" placeholder="Owner Name" required="" value="<?php echo $vendor_details['owner_name']?>">
                            </div>
                        </div>
                        <div class="form-group row">    
                            <label class="col-sm-3 ">Availability<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <select calss="form-control" name="availability" class="form-control">
                                	<option value="0" selected disabled>--select--</option>
                                	<option value="0" <?php echo ($vendor_details['availability'] == 0)? 'selected' : '';?>>Closed</option>
                                	<option value="1" <?php echo ($vendor_details['availability'] == 1)? 'selected' : '';?>>Open</option>
                                </select>
                            </div>
                            <?php //echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Unique Id</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" disabled class="form-control" placeholder="Vendor Id" required="" value="<?php echo $vendor_details['unique_id']?>">
                            </div>
                            <?php echo form_error('name','<div style="color:red">','</div>');?>
                                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Category</label>
                            <div class="col-sm-9">
                                <input type="text" name="c" disabled class="form-control" placeholder="Category" required="" value="<?php echo $vendor_details['category']['name']?>">
                            </div>
                            <?php echo form_error('name','<div style="color:red">','</div>');?>
                                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Business Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" placeholder="System Name" required="" value="<?php echo $vendor_details['name']?>">
                            </div>
                            <?php echo form_error('name','<div style="color:red">','</div>');?>
                                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                        </div>
						<div class="form-group row">
                            <label class="col-sm-3 mytxt">Business Description</label>
                            <div class="col-sm-9">
                                <textarea name="business_description" class="form-control" placeholder="Business Description"> <?php echo $vendor_details['business_description']?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-sm-3 mytxt">Email <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="email" class="form-control" placeholder="Email id" required="" value="<?php echo $vendor_details['email']?>">
                                </div>
                                <?php echo form_error('email','<div style="color:red">','</div>');?>
                            </div>
                        <!-- <div class="form-group row">
                            <label class="col-sm-3 ">State <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <select calss="form-control" name="state" class="form-control">
                                	<option value="0" selected disabled>--select--</option>
                                </select>
                            </div>
                            <?php //echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div>
                       <div class="form-group row">
                            <label class="col-sm-3 ">District <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <select calss="form-control" name="district" class="form-control">
                                	<option value="0" selected disabled>--select--</option>
                                </select>
                            </div>
                            <?php //echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Constituency <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <select calss="form-control" name="constituency" class="form-control">
                                	<option value="0" selected disabled>--select--</option>
                                </select>
                            </div>
                            <?php //echo form_error('system_title','<div style="color:red">','</div>');?>
                        </div> -->
                        <!-- <div class="form-group row">
                            <label class="col-sm-3 mytxt">Decription</label>
                            <div class="col-sm-9">
                                <textarea rows="5" cols="100" name="desc" class="form-control" ><?php echo $vendor_details['desc']?></textarea>
                            </div>
                            <?php echo form_error('desc','<div style="color:red ">','</div>');?>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Address</label>
                            <div class="col-sm-9">
                                <textarea rows="5" cols="100" name="address" class="form-control" ><?php echo $vendor_details['address']['line1']?></textarea>
                            </div>
                            <?php echo form_error('address','<div style="color:red ">','</div>');?>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">State</label>
                            <div class="col-sm-7">
                            <select class="form-control" id='state' name="state" onchange="state_changed()" required="">
                                <option value="0" selected disabled>--select--</option>
                                <?php foreach ($states as $state):?>
                                    <option value="<?php echo $state['id'];?>" <?php echo ($state['id'] == $vendor_details['constituency']['state_id'])? 'selected': '';?>><?php echo $state['name']?></option>
                                    <?php echo $state['name']?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                           </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">District</label>
                            <div class="col-sm-7">
                            <select id="district" class="form-control" name="district" onchange="district_changed()" required="">
                                <option value="0" selected disabled>--select--</option>
                                <?php foreach ($districts as $district): ?>
                                    <?php if ($district['state_id'] == $vendor_details['constituency']['state_id']):?>
                                        <option value="<?php echo $district['id'];?>" <?php echo ($district['id'] == $vendor_details['constituency']['district_id'])? 'selected': '';?>><?php echo $district['name']?></option>
                                    <?php echo $district['name']?>
                                        </option>
                                    <?php endif;?>
                                        <?php endforeach;?>
                            </select>
                           </div>
                        </div>
                       <div class="form-group row">
                            <label class="col-sm-3 mytxt">Constituency</label>
                            <div class="col-sm-7">
                                <select class="form-control" id = "constituency" name="constituency">
                                    <option value="0" selected>--select--</option>
                                    <?php foreach ($constituencies as $constituency):?>
                                    <option value="<?php echo $constituency['id'];?>" <?php echo ($constituency['id'] == $vendor_details['constituency']['id'])? 'selected': '';?>><?php echo $constituency['name']?></option>
                                    <?php endforeach;?>
                                </select>
                           </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">GST Number<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="gst_number" class="form-control" placeholder="Gst Number"  value="<?php echo $vendor_details['gst_number']?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Labour Certificate Number<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="labour_certificate_number" class="form-control" placeholder="Labour Certificate Number"  value="<?php echo $vendor_details['labour_certificate_number']?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">FSSAI Number<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="fssai_number" class="form-control" placeholder="Fssai Number"  value="<?php echo $vendor_details['fssai_number']?>">
                            </div>
                        </div>
                        
                        
                        <!-- <div class="form-group row">
                            <label class="col-sm-3 mytxt">Landmark</label>
                            <div class="col-sm-9">
                                <input type="text" name="landmark" class="form-control" placeholder="Land mark" value="<?php echo $vendor_details['landmark']?>">
                            </div>
                            <?php echo form_error('landmark','<div style="color:red">','</div>');?>
                        </div>



                        <?php //if(is_array($vendor_details['contacts'])){
                             //$key = array_search(2, array_column($vendor_details['contacts'], 'type'));?>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Land line number</label>
                            <div class="col-sm-2">
                                <input type="text" name="landline_code" class="form-control" placeholder="Code" value="<?php echo ($key !== FALSE)?$vendor_details['contacts'][$key]['std_code']: '';?>">
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="landline" class="form-control" placeholder="Land Line" value="<?php echo ($key !== FALSE)?$vendor_details['contacts'][$key]['number']: '';?>">
                            </div>
                        </div> -->
                        <?php // $key1 = array_search(1, array_column($vendor_details['contacts'], 'type'));?>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Mobile Number</label>
                             <!-- <div class="col-sm-2">
                                <input type="text" name="mobile_code" class="form-control" placeholder="Code" value="<?php echo ($key1 !== FALSE)?$vendor_details['contacts'][$key1]['std_code']: '';?>">
                            </div> -->
                            <div class="col-sm-7">
                                <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="<?php echo $vendor_details['users']['phone'];?>">
                            </div>
                        </div>
                        <?php //$key2 = array_search(4, array_column($vendor_details['contacts'], 'type'));?>
                        <div class="form-group row">
                            <label class="col-sm-3 mytxt">Alternate Number</label>
                             <!-- <div class="col-sm-2">
                                <input type="text" name="helpline_code" class="form-control" placeholder="Code" value="<?php echo ($key2 !== FALSE)?$vendor_details['contacts'][$key2]['std_code']: '';?>">
                            </div> -->
                            <div class="col-sm-7">
                                <input type="text" name="helpline" class="form-control" placeholder="Help line"  value="<?php echo ($vendor_details['secondary_contact'])?$vendor_details['secondary_contact']: '';?>">
                            </div>
                        </div>
                        <?php //$key3 = array_search(3, array_column($vendor_details['contacts'], 'type'));?>
                         <div class="form-group row">
                            <label class="col-sm-3 mytxt">Whatsapp Number</label>
                             <!-- <div class="col-sm-2">
                                <input type="text" name="whatsapp_code" class="form-control" placeholder="Code" value="<?php echo ($key3 !== FALSE)?$vendor_details['contacts'][$key3]['std_code']: '';?>">
                            </div> -->
                            <div class="col-sm-7">
                                <input type="text" name="whatsapp" class="form-control" placeholder="Whatsapp Number"  value="<?php echo ($vendor_details['whats_app_no'])?$vendor_details['whats_app_no']: '';?>">
                            </div>
                        </div>
                        <!-- ramakrishna start 11/11/2021 -->
                        <div class="form-group  row">
                            <label class="col-sm-3 mytxt">Subscription Plan</label>
                            <div class="col-sm-3">
                                <input type="text" name="name" disabled class="form-control" placeholder="Packages Not Found" required="" 
                                value="<?php echo $vendor_packages[0]['packages']['title']?>">
                            </div>
                           <label class="col-sm-3 mytxt">Validity Time Left </label>
                            <div class="col-sm-3">
                                    <?php if (!empty($vendor_packages)) { ?>
                                        <input type="text" name="Validity Time Left"
                                         disabled class="form-control" 
                                        placeholder="Validity Time Left" 
                                        value="<?php echo $vendor_packages[0]['days_left']?>">
                                    <?php } else { ?>
                                        <input type="text" name="Validity Time Left"
                                         disabled class="form-control" 
                                        placeholder="Validity Time Left" 
                                        value="No subscription plan">
                                    <?php } ?>
                           </div>
                        </div>
                         <?php if (!empty($vendor_packages)) { ?>

                            <div class="card mt-3 shadow-sm">
                                <div class="card-header bg-light">
                                    <strong class="text-primary" style="color: #f26b35 !important;"><b>Subscription Extension Details</b></strong>
                                </div>
                            
                                <div class="card-body">
                            
                                    <div class="form-group row align-items-center">
                            
                                        <!-- From Date -->
                                        <label class="col-sm-2 mytxt font-weight-bold">Extended From</label>
                                        <div class="col-sm-3">
                                            <input type="date" id="from_date" name="from_date"
                                            class="form-control"
                                            value="<?php echo !empty($vendor_details['from']) 
                                            ? date('Y-m-d', strtotime($vendor_details['from'])) 
                                            : ''; ?>">
                                        </div>
                            
                                        <!-- To Date -->
                                        <label class="col-sm-2 mytxt font-weight-bold">Extended To</label>
                                        <div class="col-sm-3">
                                            <input type="date" id="to_date" name="to_date"
                                            class="form-control"
                                            value="<?php echo !empty($vendor_details['to']) 
                                            ? date('Y-m-d', strtotime($vendor_details['to'])) 
                                            : ''; ?>">
                                        </div>
                            
                                    </div>
                            
                                    <div class="form-group row align-items-center mt-3">
                            
                                        <!-- Total Days -->
                                        <label class="col-sm-2 mytxt font-weight-bold">Extended Days</label>
                                        <div class="col-sm-3">
                                            <input type="text" id="total_days" name="total_days"
                                            class="form-control bg-warning text-dark font-weight-bold text-center"
                                            readonly
                                            value="<?php echo $vendor_details['extend_time_days'] ?? ''; ?>" style="background: #eee !important;"> 
                                        </div>
                                        
                                        <?php
                                        $extended_days = $vendor_details['extend_time_days'] ?? 0;
                                        $current_left  = $vendor_packages[0]['days_left'] ?? 0;
                                        
                                        $total_validity_left = $extended_days + $current_left;
                                        ?>
                                        <label class="col-sm-2 mytxt font-weight-bold">Total Validity Left Days</label>
                                        <div class="col-sm-3">
                                         <input type="text"
                                            id="total_validity_left"
                                            class="form-control bg-warning text-dark font-weight-bold text-center"
                                            readonly
                                            value="<?php echo $total_validity_left ?? 0; ?>" style="background: #eee  !important;">
                                        </div>
                            
                                    </div>
                            
                                </div>
                            </div>
                            
                            <?php } ?>
                        
                            
                        
                        <!-- ramakrishna ends 11/11/2021 -->
						<?php  //}?>
                    


                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onClick="clear_form('form_site_settings')" value="Reset" />
                            </div>
                        </div>

                    </div>
            
            </section>
            </form>
        </div>
       </div>
       
        <div class="row">
        <?php if($this->ion_auth_acl->has_permission('vendor_filters')){?>
            <div class="col-md-6">
                <form id="form_sms" action="<?php echo base_url('vendor_profile/filters');?>" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
                    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                            </div>
                            <h2 class="card-title ven">Filters</h2>
                        </header>
                        <div class="card-body">
    						<input type="hidden" name="vendor_user_id" value="<?php echo $vendor_details['vendor_user_id'];?>"/>
    						<input type="hidden" id="list_id" name="id" value="<?php echo $vendor_details['id'];?>"/>
    						<input type="hidden" id="cat_id" name="cat_id" value="<?php echo $vendor_details['category_id'];?>"/>
                           <div class="form-group row">
                                <label class="col-sm-3 Social1">Categories <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select  id="category" name="categories" class="form-control">
                                    	<?php foreach ($categories as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo ($vendor_details['category_id'] == $val['id']) ? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('categories','<div style="color:red">','</div>');?>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 Social1">Sub categories <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_sub_categories" name="sub_categories[]" class="form-control" multiple>
                                    	<?php foreach ($sub_categories as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'sub_categories']) && is_array($vendor_details[ 'sub_categories']) && in_array($val[ 'id'],array_column($vendor_details[ 'sub_categories'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('sub_categories','<div style="color:red">','</div>');?>
                            </div>
                             <div class="form-group row">
                                <label class="col-sm-3 Social1">On Demand categories <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_od_categories" name="od_categories[]" class="form-control" multiple>
                                        <?php foreach ($od_categories as $key => $val){?>
                                            <option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'on_demand_categories']) && is_array($vendor_details[ 'on_demand_categories']) && in_array($val[ 'id'],array_column($vendor_details[ 'on_demand_categories'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('sub_categories','<div style="color:red">','</div>');?>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 Social1">Brands <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_brands" name="brands[]" class="form-control" multiple>
                                    	<?php foreach ($brands as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'brands']) && is_array($vendor_details[ 'brands']) && in_array($val[ 'id'],array_column($vendor_details[ 'brands'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('sub_categories','<div style="color:red">','</div>');?>
                            </div>
                           <div class="form-group row">
                                <label class="col-sm-3 Social1">Amenities <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_amenities" name="amenities[]" class="form-control" multiple>
                                    	<?php foreach ($amenities as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'amenities']) && is_array($vendor_details[ 'amenities']) && in_array($val[ 'id'],array_column($vendor_details[ 'amenities'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('amenities','<div style="color:red">','</div>');?>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 Social1">Services <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_services" name="services[]" class="form-control" multiple>
                                    	<?php foreach ($services as $key => $val){?>
                                    		<option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'services']) && is_array($vendor_details[ 'services']) && in_array($val[ 'id'],array_column($vendor_details[ 'services'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('services','<div style="color:red">','</div>');?>
                            </div>
                             <div class="form-group row">
                                <label class="col-sm-3 Social1">Specialities <span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <select calss="form-control" id="profile_specialities" name="specialities[]" class="form-control" multiple>
                                        <?php foreach ($vendor_specialities as $key => $val){?>
                                            <option value="<?php echo $val['id']?>" <?php echo (isset($vendor_details[ 'specialities']) && is_array($vendor_details[ 'specialities']) && in_array($val[ 'id'],array_column($vendor_details[ 'specialities'], 'id')))? 'selected': '';?>><?php echo $val['name']?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <?php echo form_error('services','<div style="color:red">','</div>');?>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <button class="btn btn-primary">Submit</button>
                                    <input type="button" class="btn btn-default" onClick="clear_form('form_sms')" value="Reset" />
                                </div>
                            </div>
                        </div>
                
                </section>
            </form>
            </div>
            <?php } 
			if($user->primary_intent!='vendor'){ 
			?>
            <div class="">
                <form id="form-smtp" action="<?php echo base_url('vendor_profile/social');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                    <section class="card">
                        <header class="card-header">
                            <div class="card-actions">
                                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                            </div>
                            <h2 class="card-title ven">Social</h2>
                        </header>
                        <div class="card-body">
                        <?php if(is_array($vendor_details['links'])){ $social_key = array_search(1, array_column($vendor_details['links'], 'type'));?>
                            <div class="form-group row">
                                <label class="col-sm-3 Social1">Facebook link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="facebook" class="form-control" placeholder="Facebook Link" required="" value="<?php echo ($social_key !== FALSE)?$vendor_details['links'][$social_key]['url']: '';?>">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                                </div>
                                <?php echo form_error('facebook','<div style="color:red">','</div>');?>
                            </div>
                            <?php $social_key1 = array_search(2, array_column($vendor_details['links'], 'type'));?>
                            <div class="form-group row">
                                <label class="col-sm-3 Social1">Twitter link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="twitter" class="form-control" placeholder="Twitter link" required="" value="<?php echo ($social_key1 !== FALSE)?$vendor_details['links'][$social_key1]['url']: '';?>">
                                </div>
                                <?php echo form_error('twitter','<div style="color:red">','</div>');?>
                            </div>
                            <?php $social_key2 = array_search(3, array_column($vendor_details['links'], 'type'));?>
                            <div class="form-group row">
                                <label class="col-sm-3 Social1">Instagram link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="instagram" class="form-control" placeholder="Instagram link" required="" value="<?php echo ($social_key2 !== FALSE)?$vendor_details['links'][$social_key2]['url']: '';?>">
                                </div>
                                <?php echo form_error('instagram','<div style="color:red">','</div>');?>
                            </div>
                            <?php $social_key3 = array_search(4, array_column($vendor_details['links'], 'type'));?>
                            <div class="form-group row">
                                <label class="col-sm-3 Social1">Website link<span class="required">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="website" class="form-control" placeholder="Website link" required="" value="<?php echo ($social_key3 !== FALSE)?$vendor_details['links'][$social_key3]['url']: '';?>">
                                </div>
                                <?php echo form_error('website','<div style="color:red">','</div>');?>
                            </div>
    						<?php }?>
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <button class="btn btn-primary">Submit</button>
                                    <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                                </div>
                            </div>
                        </div>
                
                </section>
            </form>
            </div>
			<?php } ?>
        </div>
<!--         <div class="row"> -->
<!--         	<div class="col-md-12"> 
                <form id="form-smtp" action="<?php //echo base_url('settings/payment');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
<!--                     <section class="card"> -->
<!--                         <header class="card-header"> -->
<!--                             <div class="card-actions"> -->
<!--                                 <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a> -->
<!--                                 <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a> -->
<!--                             </div> -->
<!--                             <h2 class="card-title">Open Hour & Holidays</h2> -->
<!--                         </header> -->
<!--                         <div class="card-body"> -->
<!--                             <div class="form-group row"> -->
<!--                                 <label class="col-sm-2 ">In-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="in-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                  <label class="col-sm-2">Out-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="out-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                 <button class="btn btn-primary">+</button> -->
<!--                                 <div class="invalid-feedback">Pay per vendor?</div> -->
<!--                             </div> -->
<!--                             <div class="form-group row"> -->
<!--                                 <label class="col-sm-3 ">Holidays<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-9"> -->
<!--                                     <input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Sunday?</label> -->
<!--         							<input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Monday?</label> -->
<!--         							<input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Tuesday?</label> -->
<!--         							<input type="checkbox"  name="subscribe" value="newsletter"> -->
<!--         							<label for="subscribeNews">Wednseday?</label> -->
<!--                                 </div> -->
<!--                                 <div class="invalid-feedback">Vendor validation count?</div> -->
<!--                             </div> -->
<!--     						 <div class="form-group row"> -->
<!--                                 <label class="col-sm-2 ">In-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="in-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                  <label class="col-sm-2">Out-time<span class="required">*</span></label> -->
<!--                                 <div class="col-sm-3"> 
                                    <input type="time" name="pay_per_vendor" class="form-control" placeholder="out-time" required="" value="<?php echo $this->setting_model->where('key','pay_per_vendor')->get()['value']?>">
<!--                                 </div> -->
<!--                                 <div class="invalid-feedback">Pay per vendor?</div> -->
<!--                             </div> -->
<!--                             <div class="row justify-content-end"> -->
<!--                                 <div class="col-sm-9"> -->
<!--                                     <button class="btn btn-primary">Next</button> -->
<!--                                 </div> -->
<!--                             </div> -->
<!--                         </div> -->
                
<!--                 </section></form> -->
<!--             </div> -->
<!--     </div> -->

	<div class="row">
		<div class="col-12">
			<form id="form-smtp" action="<?php echo base_url('vendor_profile/u/bank_details');?>" class="needs-validation form" novalidate="" method="post">
				<section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                       
                        <h2 class="card-title ven ">Bank Details</h2>
                    </header>
                    <div class="card-body">
    				<div class="form-group row">
                        <label class="col-sm-3 ">A/C Holder Name<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_holder_name" class="form-control" placeholder="A/C Holder Name" required="" value="<?php echo $bank_details['ac_holder_name']?>">
                        </div>
                        <?php echo form_error('ac_holder_name','<div style="color:red">','</div>');?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">Bank Name<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" required="" value="<?php  echo $bank_details['bank_name']?>">
                        </div>
                        <?php echo form_error('bank_name','<div style="color:red">','</div>');?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">Bank Branch<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="bank_branch" class="form-control" placeholder="Bank Branch" required="" value="<?php echo $bank_details['bank_branch'] ?>">
                        </div>
                        <?php echo form_error('bank_branch','<div style="color:red">','</div>');?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">A/C Number<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_number" class="form-control" placeholder="A/C Number" required="" value="<?php echo $bank_details['ac_number'] ?>">
                        </div>
                        <?php echo form_error('ac_number','<div style="color:red">','</div>');?>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 ">IFSC Code<span class="required">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="ifsc" class="form-control" placeholder="IFSC Code" required="" value="<?php  echo $bank_details['ifsc']?>">
                        </div>
                        <?php echo form_error('ifsc','<div style="color:red">','</div>');?>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                        	<input type="hidden"  name="list_id" value="<?php echo $_GET['id']?>" />
                            <button class="btn btn-primary">Submit</button>
                            <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                        </div>
                    </div>
				</div>
            
            </section></form>
		</div>
	</div>
	<?php if($this->ion_auth_acl->has_permission('vendor_cover')):?>
    <div class="row">
    	<div class="col-md-6">
            <form id="form-smtp" action="<?php echo base_url('vendor_profile/cover');?>" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven ">Cover Image</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row col-md-12">
                        	<!-- <div class="col-md-6"> -->
                                
                             <div class="row">
                                    <div class="col-sm-6">
                                    <label class="">Cover Image</label>
                                <input type='file' name="file" class="form-control" onchange="news_image(this);" />
                                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                                    </div>
                               
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary mt">Submit</button>
                                        <input type="button" class="btn btn-default" onClick="clear_form('form-smtp')" value="Reset" />
                                    </div>
                                </div>
                        	<!-- </div> -->
                        	<div class="col-md-6">
                        		<img id="" class="zoom1 modal-target" src="<?php echo base_url(); ?>uploads/list_cover_image/list_cover_<?php echo $_GET['id']?>.jpg?<?php echo time();?>"  alt="Logo" />
                        	</div>
                        </div>
                    </div>
            
            </section></form>
        </div>
        <?php endif;?>
    <!-- </div> -->
    
    <!-- <div class="row">
    	<div class="col-md-12"> -->
    		
<!-- <div class="container"> -->
    <!-- <div class="row justify-content-center"> -->
    <?php if($this->ion_auth_acl->has_permission('vendor_banner')):?>
        <div class="col-md-6">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven"> Banners</h2>
                    </header>
                    <div class="card-body">
                         <form id="form_cover" action="<?php echo base_url('vendor_profile/banners');?>" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
                        <div class="row form-group ">
                           <div class="col-md-6">
                            <label>Upload Image</label> 
                            <input type="file" name="banner" required=""  class="form-control" onchange="readURL(this);">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
    
                             <div class="col-md-6">
                            <img id="blah" class="zoom1 modal-target" src="#" alt=""> </div>
                             </div> 
    
                            <div class="col-sm-2">
                                <button class="btn btn-primary mt-4 mt-2">Submit</button>
                            </div>
                        </div>
                       
                    </form>
                    <hr/>
                       
                    </div>
            
                </section>
        </div>
        <?php endif;?>

    <!-- </div> -->
<!-- </div> -->
</div>


                    <!-- The Modal -->
    <div id="modal" class="modal">
  <span id="modal-close" class="modal-close">&times;</span>
  <img id="modal-content" class="modal-content">
  <div id="modal-caption" class="modal-caption"></div>
</div>
					<!--end -->



<?php if($this->ion_auth_acl->has_permission('vendor_banner')):?>
<div class="row">
    <div class="col-md-12">
	<div class="card-body">
			<div class="card">
				<div class="card-header">
					<h4 class="ven">List of Banners</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Image</th>
									<th>Actions</th>

								</tr>
							</thead>
							<tbody>
								<?php if(!empty($vendor_details['banners'])):?>
    							<?php $sno = 1; foreach ($vendor_details['banners'] as $banner):?>
    								<tr>
    									<td><?php echo $sno++;?></td>
    									<td width="15%"><img class="zoom1 modal-target"
    										src="<?php echo base_url();?>uploads/list_banner_image/list_banner_<?php echo $banner['id'];?>.jpg?<?php echo time();?>"
    										width="50px"></td>
    									<td><a href="<?php echo base_url()?>vendor_profile/banner_edit?id=<?php echo $banner['id'];?>&list_id=<?php echo $_GET['id']?>" class=" mr-2  "  > <i class="fas fa-pencil-alt"></i>
    									</a> <a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $banner['id'] ?>, 'vendor_profile')"> <i
    											class="far fa-trash-alt"></i>
    									</a></td>
    
    								</tr>
    							<?php endforeach;?>
							<?php else :?>
							<tr ><th colspan='6'><h3><center>No Banners</center></h3></th>
                            </tr>
							<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
          </div>
        </div>
</div>
<?php endif;?>
</div>
<style>
    #editor{
  padding: 0.4em 0.4em 0.4em 0;

}
</style>

<script>

var modal = document.getElementById('modal');

var modalClose = document.getElementById('modal-close');
modalClose.addEventListener('click', function() { 
  modal.style.display = "none";
});

// global handler
document.addEventListener('click', function (e) { 
  if (e.target.className.indexOf('modal-target') !== -1) {
      var img = e.target;
      var modalImg = document.getElementById("modal-content");
      var captionText = document.getElementById("modal-caption");
      modal.style.display = "block";
      modalImg.src = img.src;
      captionText.innerHTML = img.alt;
   }
});

</script>

<script>

// Store current validity left from PHP
let currentLeftDays = <?php echo $current_left ?? 0; ?>;

// Set today's date as minimum selectable date
let today = new Date().toISOString().split('T')[0];
document.getElementById("from_date").setAttribute("min", today);
document.getElementById("to_date").setAttribute("min", today);

document.getElementById('from_date').addEventListener('change', function() {
    document.getElementById("to_date").setAttribute("min", this.value);
    calculateDays();
});

document.getElementById('to_date').addEventListener('change', calculateDays);

function calculateDays() {

    let from = document.getElementById('from_date').value;
    let to = document.getElementById('to_date').value;

    if (from && to) {

        let fromDate = new Date(from);
        let toDate = new Date(to);

        if (toDate < fromDate) {
            alert("To Date cannot be earlier than From Date");
            document.getElementById('total_days').value = '';
            document.getElementById('total_validity_left').value = currentLeftDays;
            return;
        }

        let diffTime = toDate - fromDate;
        let diffDays = (diffTime / (1000 * 60 * 60 * 24)) + 1;

        // Update Extended Days
        document.getElementById('total_days').value = diffDays;

        // Update Total Validity Left
        let newTotal = parseInt(currentLeftDays) + parseInt(diffDays);
        document.getElementById('total_validity_left').value = newTotal;
    }
}

</script>






