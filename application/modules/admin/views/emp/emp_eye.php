<?php if($type == 'user'){?>
<div class="row">
	<div class="col-12">
		<h4 class="ven">User Details</h4>
		<div class="card-header">
				<div class="form-row">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>User Id</label>
                        <div class="col-md-6">
                        <p><?php echo $users['id']; ?></p>
                        </div>
                    </div>
					<div class="form-group col-md-6">
						<label>Name</label> 
                         <div class="col-md-6">
                         <p><?php echo $users['first_name'].'  '.$users['last_name']; ?></p>
                         </div>
					</div>
					
					<div class="form-group col-md-6">
						<label>Mobile No.</label> 
                        <div class="col-md-6">
                            <p><?php echo $users['phone']; ?></p>
                        </div>
					</div>
					<div class="form-group col-md-6">
						<label>Email ID</label>
                        <div class="col-md-6">
                            <p><?php echo $users['email']; ?></p>
                        </div>
					</div>
					<div class="form-group col-md-12">
						<label>Accepted T&C's</label> 
                            <?php $sno=1; foreach ($tc as $group): ?>
                               <p><?php echo $sno++ ?> <b>Title:</b> <?php echo $group['title']; ?></p>
                                <p><b>Description:</b> <?php echo $group['desc']; ?></p>
                            <?php endforeach;?>
                         
					</div>
					
				</div>


			</div>
	</div>
</div>
</div>
<?php }?>
