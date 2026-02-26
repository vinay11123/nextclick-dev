<!DOCTYPE html>
<html>
   <head>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css" integrity="sha512-6lLUdeQ5uheMFbWm3CP271l14RsX1xtx+J5x2yeIDkkiBpeVTNhTqijME7GgRKKi6hCqovwCoBTlRBEC20M8Mg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
      <style>
         .img-bor {
         border: 3px solid #333;
         border-radius: 10px;
         }
         .colors {
         padding: 2px;
         color: #fff;
         display: none;
         }
         button.remove-field.btn-remove-customer.btn.btn-danger {
         /* position: relative; */
         /* float: right; */
         transform: translate(689px, -77px);
         }
         i.gj-icon {
         display: none;
         }
      </style>
   </head>
   <body>
      <div class="row">
      <div class="col-12">
      <h4 class="">Promotion Banners</h4>
      <form class="needs-validation" novalidate="" action="<?php echo base_url('promotion_banners/s/0');?>" method="post" enctype="multipart/form-data">
         <div class="card-header">
            <div class="form-row">
               <div class="form-group col-md-6">
                  <label>Title</label>
                  <input type="text" class="form-control" id="title" name="title" required="" placeholder="title" <?php echo set_value('title')?>>
                  <div class="invalid-feedback">Give some Title</div>
                  <?php echo form_error('title','<div style="color:red">','</div>');?>
               </div>
               <div class="form-group col-md-6">
                  <label>Category</label>
                  <select class="form-control" onChange="category_changed1(this.value);" id="cat_id" name="cat_id" required="">
                     <option value="" selected disabled>--select--</option>
                     <?php foreach ($categories as $category):?>
                     <option value="<?php echo $category['id'];?>"><?php echo $category['name']?></option>
                     <?php endforeach;?>
                  </select>
                  <div class="invalid-feedback">New Category Name?</div>
                  <?php echo form_error('cat_id','<div style="color:red>"','</div>');?>
               </div>
            </div>
            <div class="form-row">
               <div class="form-group col-md-4">
                  <input type="hidden" name="imgvalue" id="imgvalue">
                  <label>State</label> 
                  <select class="form-control" id='state' onchange="state_changed()" name="state_id" required="">
                     <option value="" selected disabled>--select--</option>
                     <?php foreach ($states as $state):?>
                     <option value="<?php echo $state['id'];?>"><?php echo $state['name']?></option>
                     <?php endforeach;?>
                  </select>
                  <div class="invalid-feedback">state?</div>
                  <?php echo form_error('state','<div style="color:red">','</div>');?>
               </div>
               <div class="form-group col-md-4">
                  <label>District</label>
                  <select class="form-control " id="district" onchange="district_changed()" name="district" required="">
                     <option value="" selected disabled>--select--</option>
                  </select>
                  <div class="invalid-feedback">District?</div>
                  <?php echo form_error('district','<div style="color:red">','</div>');?>
               </div>
               <div class="form-group col-md-4">
                  <label>Constituency</label>
                  <select class="form-control " id="constituency" name="constituency" required="">
                     <option value="" selected disabled>--select--</option>
                  </select>
                  <div class="invalid-feedback">Constituency?</div>
                  <?php echo form_error('constituency','<div style="color:red">','</div>');?>
               </div>
            </div>
            <div class="form-row">
               <div class="form-group col-md-4">
                  <label>Get Published On</label>
                  <input type="date" name="start_date" class="form-control" id="start_date" value="<?php echo set_value('start_date')?>">
                  <div class="invalid-feedback">Start Date?</div>
                  <?php echo form_error('start_date', '<div style="color:red">', '</div>');?>
               </div>
               <div class="form-group col-md-4">
                  <label>Expired On</label>
                  <input type="date" name="end_date" class="form-control" id="end_date" value="<?php echo set_value('end_date')?>">
                  <div class="invalid-feedback">End Date?</div>
                  <?php echo form_error('end_date', '<div style="color:red">', '</div>');?>
               </div>
               <div class="form-group col-md-4">
                  <label>Upload Banner</label> <input type="file" accept="image/jpeg, image/png" name="file" id="file" required="" class="form-control" onchange="readURL(this);"> <br>
               </div>
            </div>
            <div class="form-row">
               <div class="form-group col-md-4">
                  <lable>Banner Positions</lable>
                  <div class="form-group">
                     <?php foreach ($positions as $position): ?>
                     <img name="position" id="image-position-<?php echo $position['id'];?>" value="" onclick="positionfunction('<?php echo $position['id'];?>')" src="<?php echo base_url();?>uploads/promotion_banner_positions/promotion_banner_positions_<?php echo $position['id'];?>.jpg?>" class="img-thumb" style="width: 15%;">
                     <?php endforeach;?>
                     <input type="hidden" name="image-position" id="image-position">
                  </div>
               </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
					<label>Discount Type</label>
					<select class="form-control" id="discount_type" onchange="return discount_to_check(this.value)" name="discount_type" required="">
						<option value="" selected disabled>--select--</option>
							<?php
							if (! empty($discount_type)){
								foreach ($discount_type as $item):?>
								<option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
								<?php endforeach;
							}
							?>
						</select>
						<div class="invalid-feedback">Discount Type?</div>
							<?php echo form_error('discount_type','<div style="color:red>"','</div>');?>
				</div>
               <!-- <div class="form-group col-md-6">
                  <label>Discount Type</label>
                  <select id="colorselector" class="form-control" name="discount_type" required="">
                     <option value="" selected="" disabled="">--select--</option>
                     <option value="one">Percentage of products price</option>
                     <option value="one">Fixed amount discount</option>
                     <option value="two">Buy 1 get 1 free</option>
                     <option value="two">Buy X get Y free</option>
                     <option value="two">Buy X get Y or Z free</option>
                  </select>
                  <div class="invalid-feedback">Discount Type?</div>
                  <?php echo form_error('discount_type','<div style="color:red>"','</div>');?>
               </div> -->
               <div class="form-group col-md-6">
                  <label>Max Offer Quantity</label> <input type="number" name="max_offer_steps" required="" value="<?php echo set_value('max_offer_steps')?>" class="form-control" min="1">
                  <div class="invalid-feedback">Max Offer Quantity?</div>
                  <?php echo form_error('max_offer_steps', '<div style="color:red">', '</div>');?>
               </div>
            </div>
            <div class="output">
               <div id="one" class="colors">
                  <div class="form-row">
                     <div class="col-md-12">
                        <div class="form-group " id="discount">
                           <label>Discount</label>
                           <input type="number" class="form-control" name="discount" value="<?php echo set_value('discount')?>" class="form-control">
                           <div class="invalid-feedback">discount?</div>
                           <?php echo form_error('discount', '<div style="color:red">', '</div>');?>
                        </div>
                     </div>
                  </div>
                     <div class="multi-field-wrapper">
                        <div class="multi-fields">
                           <div class="multi-field">
                              <div class="form-row">
                                 <div class="form-group col-md-4">
                                    <label>Shop By categories</label>
                                    <select class="form-control" id="sub_cat_id" name="sub_cat_id[]" required="">
                                       <option value="" selected disabled>--select--</option>
                                    </select>
                                    <div class="invalid-feedback">Select Shop By categories</div>
                                    <?php echo form_error('sub_cat_id[]','<div style="color:red>"','</div>');?>
                                 </div>
                                 <div class="form-group col-md-4">
                                    <label>Upload Image</label>
                                    <input class="form-control" type="file" accept="image/jpeg, image/png" name="img[]" id="img" class="form-control"> <br>
                                 </div>
                              </div>
                              <button type="button" class="remove-field" style="background-color:red;color:white;transform: translate(689px, -72px);">Remove</button>
                              <button type="button" class="add-field" style="background-color:green;color:white;transform: translate(689px, -72px);">Add field</button>
                           </div>
                        </div>
                     </div>
                  </div>
                </div>
                  <div class="output">
                     <div id="two" class="colors">
                        <div class="multi-field-wrapper">
                           <div class="multi-fields">
                              <div class="multi-field">
                                 <div class="form-row">
                                    <div class="form-group col-md-4">
                                       <label>Shop By categories</label>
                                       <select class="form-control" id="sub_cat_id2" name="sub_cat_id2[]">
                                          <option value="" selected disabled>--select--</option>
                                       </select>
                                       <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
                                       <?php echo form_error('sub_cat_id2[]','<div style="color:red>"','</div>');?>
                                    </div>
                                    <div class="form-group col-md-4">
                                       <label>Upload Image</label>
                                       <input class="form-control" type="file" accept="image/jpeg, image/png" name="img[]" id="image" class="form-control"> <br>
                                    </div>
                                 </div>
                                 <button type="button" class="remove-field" style="background-color:red;color:white;transform: translate(689px, -72px);">Remove</button>
                                 <button type="button" class="add-field" style="background-color:green;color:white;transform: translate(689px, -72px);">Add field</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-row">
                  </div>
                  <div class="form-row">
                     <div class="form-group col-md-4">
                        <button type="submit" name="upload" id="upload" value="Apply" class="btn btn-primary mt-27 ">Submit</button>
                     </div>
                  </div>
               </div>
      </form>
      </div>
      </div>
      </section>
      <script>
         $(document).ready(function() {
             $("#myInput").on("keyup", function() {
                 var value = $(this).val().toLowerCase();
                 $("#myTable tr").filter(function() {
                     $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                 });
             });
         });
      </script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script type="text/javascript">
         function slickCarousel() {
             $('.responsive').slick({
                 dots: true,
                 infinite: false,
                 speed: 300,
                 slidesToShow: 2,
                 slidesToScroll: 1,
                 responsive: [{
                         breakpoint: 1024,
                         settings: {
                             slidesToShow: 2,
                             slidesToScroll: 3,
                             infinite: true,
                             dots: true
                         }
                     },
                     {
                         breakpoint: 600,
                         settings: {
                             slidesToShow: 2,
                             slidesToScroll: 2
                         }
                     },
                     {
                         breakpoint: 480,
                         settings: {
                             slidesToShow: 1,
                             slidesToScroll: 1
                         }
                     }
         
                 ]
             });
         }
         
         
         function myfunction(t) {
             if ($('#img' + t).hasClass('img-bor')) {
                 $('#img' + t).removeClass('img-bor')
             } else {
                 $('#img' + t).addClass('img-bor')
             }
             // $("#img-border").removeClass("img-bor");
             $("#img-border").addClass('img-bor');
             $("#imgvalue").val(t);
         }
         
         function positionfunction(t) {
             if ($('#image-position-' + t).hasClass('img-bor')) {
                 $('#image-position-' + t).removeClass('img-bor')
             } else {
                 $('#image-position-' + t).addClass('img-bor')
             }
             // $("#img-border").removeClass("img-bor");
             $("#img-border").addClass('img-bor');
             $("#image-position").val(t);
         }
      </script>

      <script type="text/javascript">
         function discount_to_check(discount_type) {
             
            $('.colors').hide();
            if (discount_type == 1 || discount_type == 2) {
                $('#one').show();
            } else{
                $('#two').show();
            }
         }
      </script>
      <script>
         $('.multi-field-wrapper').each(function() {
         var $wrapper = $('.multi-fields', this);
         $(".add-field", $(this)).click(function(e) {
             $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
         });
         $('.multi-field .remove-field', $wrapper).click(function() {
             if ($('.multi-field', $wrapper).length > 1)
                 $(this).parent('.multi-field').remove();
         });
         });
         
      </script>
   </body>
</html>