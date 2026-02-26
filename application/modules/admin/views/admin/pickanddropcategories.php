<div class="row">
  <div class="col-12">
    <div class="card-header">
				<h4 class="ven1 col-8">Categories For PickUp and Drop</h4>
				<?php if($this->ion_auth_acl->has_permission('category_create')):?>
					<a href="<?php echo base_url()?>pickanddropcategories/c" class="btn btn-primary widfldtd" style="flaot:right">Add Category</a> &nbsp;
				<?php endif;?>
		</div>
    <form class="needs-validation" novalidate="" action="<?php echo base_url('pickanddropcategories/u'); ?>" method="post" enctype="multipart/form-data">
      <table class="table table-bordered table-light">
        <thead>
          <tr>
		  <th>S.no</th>
		  <th>Category Name</th>
		  <th>Description</th>
		  <th>Flat Distance in km</th>
		  <th>DB Flat Rate</th>
		  <th>DB Per km Rate after flat distance</th>
		  <th>NC Flat Rate</th>
		  <th>NC Per km Rate after flat distance</th>
		  <th>Enable / Disable</th>
		  <th>Actions</th>
          </tr>
        </thead>
        <tbody>

          <?php
		  $sno = 1;
			foreach ($categories as $key => $category) {
          ?>
            <tr>
			<td><?php echo $sno++;?></td>
			<td class="tdcolorone"><?php echo $category['name'].'['.$category['id'].']';?></td>
			<td class="tdcolortwo"><?php echo $category['desc'];?></td>
			<td class="tdcolortwo"><?php echo $category['flat_distance'];?></td>
			<td class="tdcolortwo"><?php echo $category['flat_rate'];?></td>
			<td class="tdcolortwo"><?php echo $category['per_km'];?></td>
			<td class="tdcolortwo"><?php echo $category['nc_flat_rate'];?></td>
			<td class="tdcolortwo"><?php echo $category['nc_per_km'];?></td>
			<td><input type="checkbox"  name="<?php echo $category['id']; ?>" <?php $category['is_pickup_allowed'] == '1' ? print "checked='checked'" : "" ?> value=1></td>
      <td>
										<?php if($this->ion_auth_acl->has_permission('category_edit')):?>
    									<a href="<?php echo base_url()?>pickanddropcategories/edit?id=<?php echo $category['id']; ?>" class=" mr-2  " type="category"> <i class="fas fa-pencil-alt"></i></a>
    									<?php endif;?>
    									<?php if($this->ion_auth_acl->has_permission('category_delete')):?>
                        <a href="<?php echo base_url()?>pickanddropcategories/d?id=<?php echo $category['id']; ?>" class=" mr-2  " type="category"> <i class="far fa-trash-alt"></i></a>
    									<!-- <a href="#" class="mr-2  text-danger " onClick="delete_record(<?php echo $category['id'] ?>, 'category')"><i class="far fa-trash-alt"></i></a> -->
    									<?php endif;?>
				</td>
            </tr>
          <?php }
          ?>
        </tbody>
      </table>
  </div>
  <div class="form-group col-md-12 float-right m-r-10"><button class="btn btn-primary mt-27 ">Update</button>
    </form>
  </div>