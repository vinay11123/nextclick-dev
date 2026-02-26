    <!DOCTYPE html>
    <html>
    <head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css" integrity="sha512-6lLUdeQ5uheMFbWm3CP271l14RsX1xtx+J5x2yeIDkkiBpeVTNhTqijME7GgRKKi6hCqovwCoBTlRBEC20M8Mg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <style>
            .img-bor{
                border: 3px solid #333;
                border-radius: 10px;
            }
/*
             .colors {
         padding: 2px;
         color: #fff;
         display: none;
         }
*/
            i.gj-icon {
         display: none;
         }
        </style>

    </head>
    <body>
        
 <div class="row pb-4">
    <div class="col-md-12">
	<a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('promotion_banners/r/0');?>">Promotions
<i class="fa fa-angle-double-left"></i> 
Promotion Banners</a> 
   
    </div>
    </div>

    <div class="row">
    <div class="col-12">
    <h4 class="ven">Edit Promotion Banners</h4>
    <form class="needs-validation" novalidate=""
    action="<?php echo base_url('promotion_banners/u/0');?>" method="post"
    enctype="multipart/form-data">
        <div class="card-header">
            <div class="form-row">
                <div class="form-group mb-0 col-md-6">
                    <label>Previous Banner Image</label> 
                    <img name ="p_image"  src="<?php echo base_url();?>uploads/promotion_banner_image/promotion_banner_<?php echo $promotion_banners['id'];?>.jpg?>"
                        class="img-thumb" style="width: 37%;padding:3px">
                </div>
                <div class="form-group mb-0 col-md-6">
                    <label>Previous Banner position</label>
                    <img name ="p_position" src="<?php echo base_url();?>uploads/promotion_banner_positions/promotion_banner_positions_<?php echo $promotion_banners['promotion_banner_position_id'];?>.jpg?>"
                        class="img-thumb" style="width: 37%;padding:3px">
                </div>
            </div>

            <div class="form-row">
        
                <input type="hidden" name="id" value="<?php echo $promotion_banners['id'] ; ?>">

                <div class="form-group mb-0 col-md-6">
                    <label>Title</label> 
                    <input type="text" name="title" id="title" class="form-control"  value="<?php echo $promotion_banners['title'];?>">
                    <div class="invalid-feedback">Give some Title</div>
                </div>
                <div class="form-group col-md-6">
                    <label>Category</label>
                        <select class="form-control" id = "cat_id" name="cat_id" onChange="category_changed1(this.value);" required=""  >
                            <option value="0" selected>--select--</option>
                            <?php foreach ($categories as $category):?>
                            <option value="<?php echo $category['id'];?>" <?php echo ($category['id'] == $promotion_banners['cat_id'])? 'selected': '';?>><?php echo $category['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Category Name?</div>
                </div>
                <!-- <div class="form-group col-md-4">
                    <label>Shop by Category</label>
                    <select id="district" class="form-control" name="sub_cat_id"  id = "sub_cat_id" required="">
                        <option value="0" selected disabled>--select--</option>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <?php if ($subcategory['id'] == $promotion_banners['sub_cat_id']):?>
                                <option value="<?php echo $subcategory['id'];?>" <?php echo ($subcategory['id'] == $promotion_banners['sub_cat_id'])? 'selected': '';?>><?php echo $subcategory['name']?></option>
                            <?php echo $district['name']?>
                                </option>
                            <?php endif;?>
                                <?php endforeach;?>
                    </select>
                    <div class="invalid-feedback">Belongs to the Sub Category?</div>
                </div> -->
            </div>
            <!-- <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Banner Images</label>

                    <div  id="image"></div>
                </div>
            </div>
            <input type="hidden" name="imgvalue" id="imgvalue"> -->
            
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>State</label>
                        <select class="form-control" id='state' onchange="state_changed()" name="state_id" required="">
                            <option value="0" selected disabled>--select--</option>
                            <?php foreach ($states as $state):?>
                                <option value="<?php echo $state['id'];?>" <?php echo ($state['id'] == $promotion_banners['constituency']['state_id'])? 'selected': '';?>><?php echo $state['name']?></option>
                                <?php echo $state['name']?>
                                </option>
                            <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback">Select valid state?</div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>District</label>
                        <select id="district" class="form-control" onchange="district_changed()" name="dist_id" required="">
                            <option value="0" selected disabled>--select--</option>
                            <?php foreach ($districts as $district): ?>
                                <?php if ($district['state_id'] == $promotion_banners['constituency']['state_id']):?>
                                    <option value="<?php echo $district['id'];?>" <?php echo ($district['id'] == $promotion_banners['constituency']['district_id'])? 'selected': '';?>><?php echo $district['name']?></option>
                                <?php echo $district['name']?>
                                    </option>
                                <?php endif;?>
                                    <?php endforeach;?>
                        </select>
                        <div class="invalid-feedback">Belongs to the District?</div>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Constituency</label>
                            <select class="form-control" id = "constituency" name="constituency">
                                <option value="0" selected>--select--</option>
                                <?php foreach ($constituencies as $constituency):?>
                                <option value="<?php echo $constituency['id'];?>" <?php echo ($constituency['id'] == $promotion_banners['constituency_id'])? 'selected': '';?>><?php echo $constituency['name']?></option>
                                <?php endforeach;?>
                            </select>
                        <div class="invalid-feedback">Select Discount type?</div>
                    </div>
                </div>



            <div class="form-row">
                <div class="form-group col-md-4">
                <label>Upload Banner</label> <input type="file"  accept="image/jpeg, image/png" name="file" id="file"
                         value="<?php echo set_value('file')?>"
                        class="form-control" onchange="readURL(this);"> <br> 
                </div>
                <div class="form-group mb-0 col-md-4">
                    <label>Get Published ON</label> 
                    <input type="date" name="start_date" id="start_date" class="form-control"  value="<?php echo $promotion_banners['published_on'];?>">
                    <div class="invalid-feedback">Give some Published ON</div>
                </div>
                <div class="form-group mb-0 col-md-4">
                    <label>Expired ON</label> 
                    <input type="date" name="end_date" id="end_date" class="form-control"  value="<?php echo $promotion_banners['expired_on'];?>">
                    <div class="invalid-feedback">Give some Expired ON</div>
                </div>
            </div>
            
            <div class="form-row">
            <lable>Banner Positions</lable>
                <div class = "form-group mb-0 col-md-12">
                    <?php foreach ($positions as $position): ?>
                        <img name ="position" id="image-position-<?php echo $position['id'];?>" value="" onclick="positionfunction('<?php echo $position['id'];?>')" src="<?php echo base_url();?>uploads/promotion_banner_positions/promotion_banner_positions_<?php echo $position['id'];?>.jpg?>"
                        class="img-thumb" style="width: 15%;">
                    <?php endforeach;?>
                    <input type="hidden" name="image-position" id="image-position">
                </div>
            </div>
            <div class ="form-row">
                
    <!--           
<div class="form-group col-md-4">
                    <label>Discount type</label>
                        <select class="form-control" id = "discount_type" onchange="return discount_to_check(this.value)" name="discount_type">
                            <option value="0" selected>--select--</option>
                            <?php foreach ($discount_type as $discount):?>
                            <option value="<?php echo $discount['id'];?>" <?php echo ($discount['id'] == $promotion_banners['promotion_banner_discount_type_id'])? 'selected': '';?>><?php echo $discount['name']?></option>
                            <?php endforeach;?>
                        </select>
                    <div class="invalid-feedback">Select Discount type?</div>
                </div>
                
                <div class="form-group mb-0 col-md-4" id="discount">
                    <label>Discount</label>
                    <input type="number" name="discount" class="form-control"  value="<?php echo $promotion_banners['discount'];?>">
                    <div class="invalid-feedback">Give some Discount</div>
                </div>
                
                <div class="form-group mb-0 col-md-4" id="discount">
                    <label>Max Offer Discount</label>
                    <input type="number" id='max_offer_steps' name="max_offer_steps" class="form-control"  value="<?php echo $promotion_banners['max_offer_steps'];?>">
                    <div class="invalid-feedback">Give some Discount</div>
                </div>
                
-->
                
                 
                <div class="form-group col-md-4">
					<label>Discount Type</label>
					<select class="form-control" id="discount_type" onchange="return discount_to_check(this.value)" name="discount_type" required="">
						<option value="" selected disabled>--select--</option>
							<?php
							if (! empty($discount_type)){
								foreach ($discount_type as $item):?>
								<option value="<?php echo $item['id'];?>"<?php echo ($item['id'] == $promotion_banners['promotion_banner_discount_type_id'])? 'selected': '';?>><?php echo $item['name'];?></option>
								<?php endforeach;
							}
							?>
						</select>
						<div class="invalid-feedback">Discount Type?</div>
							<?php echo form_error('discount_type','<div style="color:red>"','</div>');?>
				</div>
             
               <div class="form-group col-md-4">
                  <label>Max Offer Discount</label>
                    <input type="number" id='max_offer_steps' name="max_offer_steps" class="form-control"  value="<?php echo $promotion_banners['max_offer_steps'];?>">
                    <div class="invalid-feedback">Give some Discount</div>
                   
                   
               </div>
               
            
            </div>
          <!--ram start -->
                 <div class="output">
               <div id="one" class="colors">
                  <div class="form-row">
                    
                        <div class="form-group col-md-11" id="discount">
<!--
                           <label>Discount</label>
                           <input type="number" class="form-control" name="discount" value="<?php echo set_value('discount')?>" class="form-control">
                           <div class="invalid-feedback">discount?</div>
                           <?php echo form_error('discount', '<div style="color:red">', '</div>');?>
-->
                    <label>Discount</label>
                    <input type="number" name="discount" class="form-control"  value="<?php echo $promotion_banners['discount'];?>">
                    <div class="invalid-feedback">Give some Discount</div>                      
                            
                        </div>
                     
                  </div>
                     <div class="multi-field-wrapper">
                        <div class="multi-fields">
                           <div class="multi-field">
                              <div class="form-row">
                                 <div class="form-group col-md-6">
                                    <label>Shop By categories</label>
                                    <select class="form-control" id="sub_cat_id" name="sub_cat_id[]">
                                       <option value="" selected disabled>--select--</option>
                                       <?php
                                            if (! empty($subcategories)){
                                                foreach ($subcategories as $item):?>
                                                <option value="<?php echo $item['id'];?>"<?php echo ($item['id'] == $promotion_banners['sub_cat_id'])? 'selected': '';?>><?php echo $item['name'];?></option>
                                                <?php endforeach;
                                            }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
                                    <?php echo form_error('sub_cat_id','<div style="color:red>"','</div>');?>
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>Upload Image</label>
                                    <input class="form-control" type="file" accept="image/jpeg, image/png" name="img[]" id="img" class="form-control"> <br>
                                 </div>
                              </div>
                              <button type="button" class="remove-field" style="background-color:red;color:white;transform: translate(481px, -72px);">Remove</button>
<!--                              <button type="button" class="add-field" style="background-color:green;color:white;transform: translate(689px, -72px);">Add field</button>-->
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
                                    <div class="form-group col-md-6">
                                       <label>Shop By categories</label>
                                       <select class="form-control" id="sub_cat_id2" name="sub_cat_id2[]">
                                          <option value="" selected disabled>--select--</option>
                                         
                                       </select>
                                       <div class="invalid-feedback"><?=(($this->ion_auth->is_admin())? 'New Menu Name' : $this->category_model->get_cat_desc_account_name($vendor_category_id,'item_menu'));?>?</div>
                                       <?php echo form_error('sub_cat_id2','<div style="color:red>"','</div>');?>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label>Upload Image</label>
                                       <input class="form-control" type="file" accept="image/jpeg, image/png" name="imag[]" id="image" class="form-control"> <br>
                                    </div>
                                 </div>
                                 <button type="button" class="remove-field" style="background-color:red;color:white;transform: translate(481px, -72px);">Remove</button>
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
             
        <!--ram ends-->
                
                
        
        
            <div class="form-row">
                <div class="form-group  mb-0 col-md-12">
                    <button class="btn btn-primary mt-27 ">Update</button>
                </div>
            </div>

        </div>
    </form>



    </div>
    </div>
        <script>
            $(document).ready(function() {
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
    ​
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
                    responsive: [
                        {
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
        </script>
        <script>
            function banners_changed() {
                var cat_id = document.getElementById("cat_id").value;
                $.ajax({
                    url: base_url + 'promotion_banners/banner_images',
                    type: 'post',
                    data: { cat_id: cat_id },
                    dataType: 'json',
                    success: function(data) {
                        var options = '<div class="row">';
                        for (var i = 0; i < data.length; i++) {
                            options += '<div class="col-md-3"><a href="#" onclick="myfunction(' + data[i].id + ')"><img img-data="' + data[i].id + '" id="img' + data[i].id + '" src="' + base_url + 'uploads/promotion_banner_image/promotion_banner_' + data[i].id + '.jpg" width="100%" id="img-border"></a></div>';
                        }
                        options += '</div>';
                        // $('.responsive').slick('slickAdd', options);
                        document.getElementById("image").innerHTML = options;
                    }
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
            function positionfunction(t){
			if($('#image-position-'+t).hasClass('img-bor')){
				$('#image-position-'+t).removeClass('img-bor')
			}else{
				$('#image-position-'+t).addClass('img-bor')
			}
			// $("#img-border").removeClass("img-bor");
			$("#img-border").addClass('img-bor');
			$("#image-position").val(t);
		}     
        </script>
        <!-- <script type="text/javascript">
        function discount_to_check(discount_type) {
            $('#discount').hide();
            if(discount_type==1 || discount_type==2){
                $('#discount').show();
            }
        }
        </script> -->

<!--rama add-->


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
<!--rama remove -->


    </body>
    </html>