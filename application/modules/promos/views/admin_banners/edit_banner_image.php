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
            <a style="border: 1px solid #373435;border-radius: 3px;padding: 4px;background-color: #373435;color: white;" href="<?php echo base_url('admin_banners/r'); ?>">Admin
                <i class="fa fa-angle-double-left"></i>
                Banners</a>

        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <h4 class="ven">Edit Admin Banners</h4>
            <form class="needs-validation" novalidate="" action="<?php echo base_url('admin_banners/u'); ?>" method="post" enctype="multipart/form-data">
                <div class="card-header">


                    <div class="form-row">

                        <input type="hidden" name="id" value="<?php echo $banners['id']; ?>">

                        <div class="form-group col-md-12">
                            <label>Position</label>
                            <select class="form-control" id="promotion_banner_position_id" name="promotion_banner_position_id" onChange="category_changed1(this.value);" required="">
                                <?php foreach ($positions as $position) : ?>
                                    <option value="<?php echo $position['id']; ?>" <?php echo ($position['id'] == $banners['promotion_banner_position_id']) ? 'selected' : ''; ?>><?php echo $position['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- <div class="invalid-feedback">Please Select Position Banner</div> -->
                        </div>

                    </div>




                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Upload Banner</label> <input type="file" accept="image/jpeg, image/png" name="image" id="file" value="<?php echo set_value('banner_image') ?>" class="form-control" onchange="readURL(this);"> <br>
                            <img class="img-thumb" src="<?php echo base_url('uploads/admin_banners/' . $banners['banner_image']); ?>">
                        </div>


                    </div>
                    <div class="form-row">

                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <select class="form-control" name="pos_status" id="pos_status">
                                <option value="<?php echo $position['status']; ?>" <?php echo ($position['status'] == 0 ) ? 'selected' : ''; ?>>Active</option>
                                <option value="<?php echo $position['status']; ?>" <?php echo ($position['status'] == 1 ) ? 'selected' : ''; ?>>Inactive</option>

                            </select>
                        </div>

                    </div>


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
        });​
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
    </script>
    <script>
        function banners_changed() {
            var cat_id = document.getElementById("cat_id").value;
            $.ajax({
                url: base_url + 'promotion_banners/banner_images',
                type: 'post',
                data: {
                    cat_id: cat_id
                },
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
            } else {
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