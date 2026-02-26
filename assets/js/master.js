function delete_record(id, uri) {
    if (confirm('Do you want to delete..?')) {

        $.ajax({

            url: base_url + uri + '/d',
            type: 'post',
            data: { id: id },
            success: function(data) {

                window.location.reload();
            }
        });
    }
}


function delete_record1(id, uri) {
    if (confirm('Do you want to delete..?')) {

        $.ajax({

            url: base_url + uri + '/0/d',
            type: 'post',
            data: { id: id },
            success: function(data) {
                window.location.reload();
            }
        });
    }
}

function delete_recordvehicle(id, uri) {
    if (confirm('Do you want to delete..?')) {

        $.ajax({

            url: base_url + uri + '/d/0',
            type: 'post',
            data: { id: id },
            success: function(data) {
                window.location.reload();
            }
        });
    }
}





function admin_item_delete_record(id, uri) {
    if (confirm('Do you want to delete..?')) {
        $.ajax({
            url: base_url + uri + '/ven_item',
            type: 'post',
            data: { id: id },
            success: function(data) {
                window.location.reload();
            }
        });
    }
}


$(() => {
    $('.food_product_status').change(function() {

        if (confirm('Do You Want To Change  Status..?')) {

            let vendor_id = $(this).attr('vendor_id');
            let user_id = $(this).attr('user_id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'foodproducttogglestatus/changestatus',
                type: 'post',
                dataType: 'json',
                data: { vendor_id: vendor_id, user_id: user_id, is_checked: is_checked },
                success: function(data) {

                    console.log(data);
                }
            });
        }
    })
});


$(() => {
    $('.food_product_toggle').change(function() {

        if (confirm('Do You Want To Change  Status..?')) {

            let vendor_id = $(this).attr('vendor_id');
            let user_id = $(this).attr('user_id');
            let is_checked = $(this).is(':checked');


            $.ajax({
                url: base_url + 'foodproductstatus/change__st',
                type: 'post',
                dataType: 'json',
                data: { vendor_id: vendor_id, user_id: user_id, is_checked: is_checked },
                success: function(data) {
                    alert(data);
                    console.log(data);
                }
            });
        }
    })
});


$(() => {
    $('.approve_toggle').change(function() {

        if (confirm('Do You Want To Change Approve Status..?')) {

            let vendor_id = $(this).attr('vendor_id');
            let user_id = $(this).attr('user_id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'vendors/change_status',
                type: 'post',
                dataType: 'json',
                data: { vendor_id: vendor_id, user_id: user_id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                }
            });
        }
    })
});

$(() => {
    $('.approve_product').change(function() {

        if (confirm('Do You Want To Change Approve Status..?')== true) {
   let vendor_id = $(this).attr('vendor_id');
            let item_id = $(this).attr('item_id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'food/food/inventory/change_status/0',
                type: 'post',
                dataType: 'json',
                data: { vendor_id: vendor_id, item_id: item_id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                }
            });

        }else{
		location.reload();
		}
    })
});



$(() => {
    $('.approve_product1').change(function() {

        if (confirm('Do You Want To Change Approve Status..?')== true) {
   let vendor_id = $(this).attr('vendor_id');
            let item_id = $(this).attr('item_id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'food_product/0/foodapprovestatus',
                type: 'post',
                dataType: 'json',
                data: { item_id: item_id},
                success: function(data) {
                    console.log(data);
                }
            });

        }else{
		location.reload();
		}
    })
});

$(() => {
    $('.approve_banners').change(function() {
        if (confirm('Do You Want To Changes Approve Status..?')) {
            let id = $(this).attr('id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'bannerstatus/change_status',
                type: 'post',
                dataType: 'json',
                data: { id: id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                }
            });
        }
    })
});

$(() => {
    $('.approve_admin_banners').change(function() {
        if (confirm('Do You Want To Change  Admin Approve Status..?')) {
            let id = $(this).attr('id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'admin_banners/admin_banner_status',
                type: 'post',
                dataType: 'json',
                data: { id: id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                }
            });
        }
    })
});


$(() => {
    $('.approve_executive').change(function() {
        if (confirm('Do You Want To Change Approve Status..?')) {
            let id = $(this).attr('id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'executivestatus/change_status',
                type: 'post',
                dataType: 'json',
                data: { id: id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                }
            });
        }
    })
});


$(() => {
    $('#addhar_button').click(function() {

        if (confirm('Do You Want To Change Adhar Card Status..?')) {
            let user_id = $('.adhar_card_toggle').attr('user_id');
            let is_checked = $('.adhar_card_toggle').is(':checked');
            let aadhar_reason = $("#aadhar_reason").val();
            $.ajax({
                url: base_url + 'adhar_card/change__st',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked, aadhar_reason: aadhar_reason },
                success: function(data) {
                    window.location.reload();
                    console.log(data);
                }
            });
        }
    })
});




$(() => {
    $('#pan_card_button').click(function() {

        if (confirm('Do You Want To Change Pan Card Status..?')) {
            let user_id = $('.pan_card_toggle').attr('user_id');
            let is_checked = $('.pan_card_toggle').is(':checked');
            let pan_card_reason = $("#pan_card_reason").val();

            $.ajax({
                url: base_url + 'pan_card/change__st',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked, pan_card_reason: pan_card_reason },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    })
});


$(() => {
    $('#cancel_cheque_button').click(function() {

        if (confirm('Do You Want To Change Cheque Status..?')) {
            let user_id = $('.cancel_cheque_toggle').attr('user_id');
            let is_checked = $('.cancel_cheque_toggle').is(':checked');
            let cancel_cheque_reason = $("#cancel_cheque_reason").val();

            $.ajax({
                url: base_url + 'cancel_cheque/change__st',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked, cancel_cheque_reason: cancel_cheque_reason },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    })
});


$(() => {
    $('#driving_licence_button').click(function() {

        if (confirm('Do You Want To Change Driving Licence Status..?')) {
            let user_id = $('.driving_licence_toggle').attr('user_id');
            let is_checked = $('.driving_licence_toggle').is(':checked');
            let driving_licence_reason = $("#driving_licence_reason").val();

            $.ajax({
                url: base_url + 'driving_licence/change__st',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked, driving_licence_reason: driving_licence_reason },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    })
});


$(() => {
    $('#pass_book_button').click(function() {

        if (confirm('Do You Want To Change Pass Book Status..?')) {
            let user_id = $('.pass_book_toggle').attr('user_id');
            let is_checked = $('.pass_book_toggle').is(':checked');
            let pass_book_reason = $("#pass_book_reason").val();

            $.ajax({
                url: base_url + 'pass_book/change__st',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked, pass_book_reason: pass_book_reason },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    })
});

$(() => {
    $('#rc_button').click(function() {

        if (confirm('Do You Want To Change Pass Book Status..?')) {
            let user_id = $('.rc_toggle').attr('user_id');
            let rc_reason = $("#rc_reason").val();
            let is_checked = $('.rc_toggle').is(':checked');

            $.ajax({
                url: base_url + 'rc/change__st',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked, rc_reason: rc_reason },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    })
});


$(() => {
    $('.delivery_toggle').change(function() {

        if (confirm('Do You Want To Change Approve Status..?')) {

            let vendor_id = $(this).attr('vendor_id');
            let user_id = $(this).attr('user_id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'deliveryboy/change__st',
                type: 'post',
                dataType: 'json',
                data: { vendor_id: vendor_id, user_id: user_id, is_checked: is_checked },
                success: function(data) {
                   // alert(data);
                    console.log(data);
                    location.reload();
                }
            });
        }else {
            location.reload();
        }
    })
});


$(() => {
    $('.delivery_toggle_status').change(function() {
        if (confirm('Do You Want To Change  Status..?')) {
            let user_id = $(this).attr('user_id');
            let is_checked = $(this).is(':checked');


            $.ajax({
                url: base_url + 'deliveryboystatus/change__st_active',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked },
                success: function(data) {
                   // window.location.reload();
                   console.log(data);
                   location.reload();
                }
            });
        }else {
            location.reload();
        }
    })
});





// $(() => {
//     $('.featured_brand_toggle').change(function() {
//     	if(confirm('Do You Want To Add Featured Brand..?')){
//     		let brand_id = $(this).attr('brand_id');
//     		let is_checked = $(this).is(':checked');
//     		$.ajax({
//     			url: base_url+'brands/change_status',
//     			type: 'post',
//     			dataType: 'json',
//     			data: {brand_id : brand_id, is_checked : is_checked},
//     			success: function(data){
//     		 		console.log(data);
//     				location.reload();
//     			}
//     		});
//     	}
//     })
//   });

$(() => {
    $('.featured_brand_toggle').change(function() {

        var msg = confirm('Do You Want To Stop Woring..?');
        let cat_id = $(this).attr('cat_id');
        let is_checked = $(this).is(':checked');
        if (msg) {
            $.ajax({
                // url: base_url+'category/change_status',
                url: base_url + 'category/lead_mng_status',
                type: 'post',
                dataType: 'json',
                data: { cat_id: cat_id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                    location.reload();
                }
            });
        } else {
            location.reload();
        }
    })
});

$(() => {
    $('.featured_toggle').change(function() {

        var msg = confirm('Do You Want To Stop Woring..?');
        let brand_id = $(this).attr('brand_id');
        let is_checked = $(this).is(':checked');
        if (msg) {
            $.ajax({
                url: base_url + 'brands/change_status',
                type: 'post',
                dataType: 'json',
                data: { brand_id: brand_id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                    location.reload();
                }
            });
        } else {
            location.reload();
        }
    })
});

$(() => {
    $('.coming_soon_toggle').change(function() {

        var msg1 = confirm('Do You Want To Stop Woring..?');
        let cat_id = $(this).attr('cat_id');
        let is_checked = $(this).is(':checked');
        if (msg1) {
            $.ajax({
                //url: base_url+'category/lead_mng_status',
                url: base_url + 'category/change_status',
                type: 'post',
                dataType: 'json',
                data: { cat_id: cat_id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                    location.reload();
                }
            });
        } else {
            location.reload();
        }
    })
});

// $(() => {
//     $('.coming_soon_toggle').change(function() {
//     	if(confirm('Do You Want To Stop Woring..?')){
//     		let cat_id = $(this).attr('cat_id');
//     		let is_checked = $(this).is(':checked');
//     		$.ajax({
//     			url: base_url+'category/change_status',
//     			type: 'post',
//     			dataType: 'json',
//     			data: {cat_id : cat_id, is_checked : is_checked},
//     			success: function(data){
//     				console.log(data);
//     				location.reload();
//     			}
//     		});
//     	}
//     })
//   });

$(() => {
    $('.lead_management_toggle').change(function() {
        if (confirm('Do You Want To Stop Woring..?')) {
            let cat_id = $(this).attr('cat_id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                //url: base_url+'category/lead_mng_status',
                url: base_url + 'category/lead_mng_status',
                type: 'post',
                dataType: 'json',
                data: { cat_id: cat_id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                    location.reload();
                }
            });
        } else {
            location.reload();
        }
    })
});

$(() => {
    $('.approve_news').change(function() {
        if (confirm('Do You Want To Change News Status..?')) {
            let user_id = $(this).attr('user_id');
            let is_checked = $(this).is(':checked');
            $.ajax({
                url: base_url + 'local_news/status',
                type: 'post',
                dataType: 'json',
                data: { user_id: user_id, is_checked: is_checked },
                success: function(data) {
                    console.log(data);
                    //location.reload();
                }
            });
        }
    })
});

function state_changed() {    
    var state_id = document.getElementById("state").value;
    var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEiLCJ1c2VyZGV0YWlsIjp7InVzZXJuYW1lIjoiYWRtaW5pc3RyYXRvciIsImVtYWlsIjoiYWRtaW5AYWRtaW4uY29tIiwicGhvbmUiOiIwIn0sInRpbWUiOjE1Njk1MDY5Njd9.-5N8CdYYitPW_eGE-U9FyZHSliaXspErZvb1wUhHWpY';
    $.ajax({
        url: base_url + 'general/api/master/states/' + state_id,
        type: 'get',
        beforeSend: function(xhr) { xhr.setRequestHeader('X_AUTH_TOKEN', token); },
        success: function(data) {
            console.log(data);
			console.log(data.data.districts);
            var options = '';
            for (var i = 0; i < data.data.districts.length; i++) {
                options += '<option value="' + data.data.districts[i].id + '">' + data.data.districts[i].name + '</option>'
            }
            document.getElementById("district").innerHTML = options;
        }
    });
}

function showDiv(pageid)
{
    
    $.ajax({
        url:base_url + 'vendor/vendorNotifyStatusChange/26',    //the page containing php script
        type: "post",    //request type,
        dataType: 'json',
        success:function(result){
            console.log(result.abc);
        }
    });
}

function changePartnerStatus(pageid)
{
    alert();
    $.ajax({
        url:base_url + 'vendor/vendorNotifyStatusChange/27',    //the page containing php script
        type: "post",    //request type,
        dataType: 'json',
        success:function(result){
            console.log(result.abc);
        }
    });
}

function district_changed() {    
    var district_id = document.getElementById("district").value;
    var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEiLCJ1c2VyZGV0YWlsIjp7InVzZXJuYW1lIjoiYWRtaW5pc3RyYXRvciIsImVtYWlsIjoiYWRtaW5AYWRtaW4uY29tIiwicGhvbmUiOiIwIn0sInRpbWUiOjE1Njk1MDY5Njd9.-5N8CdYYitPW_eGE-U9FyZHSliaXspErZvb1wUhHWpY';
    $.ajax({
        url: base_url + 'general/api/master/districts/' + district_id,
        type: 'get',
        beforeSend: function(xhr) { xhr.setRequestHeader('X_AUTH_TOKEN', token); },
        success: function(data) {
            console.log(data);
            var options = '';
            for (var i = 0; i < data.data.constituenceis.length; i++) {
                options += '<option value="' + data.data.constituenceis[i].id + '">' + data.data.constituenceis[i].name + '</option>'
            }
            document.getElementById("constituency").innerHTML = options;
        }
    });
}


function shop_by_brands_changed() {
    var cat_id = document.getElementById("sub_cat_id").value;
    $.ajax({
        url: base_url + 'food/menu_by_brands',
        type: 'post',
        data: { cat_id: cat_id },
        dataType: 'json',
        success: function(data) {

            console.log(data);
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("brand_id").innerHTML = options;
        }
    });
}


function shop_by_category_changed() {
    var cat_id = document.getElementById("sub_cat_id").value;
    $.ajax({
        url: base_url + 'food/menu_by_category',
        type: 'post',
        data: { cat_id: cat_id },
        dataType: 'json',
        success: function(data) {
            //console.log(data.length);
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("menu_id").innerHTML = options;
        }
    });
}


function shop_by_category_changed1() {
    var sub_cat_id = document.getElementById("sub_cat_id").value;
    $.ajax({
        url: base_url + 'food/menu_by_category1',
        type: 'post',
        data: { sub_cat_id: sub_cat_id },
        dataType: 'json',
        success: function(data) {
            if(data==1){
                data=null;
            }
            var options = '<option value="0" selected disabled>--select--</option><option value="all">All</option>';
            if(data){
                for (var i = 0; i < data[0].length; i++) {
                    options += '<option value="' + data[0][i].id + '">' + data[0][i].name + '</option>'
                }
            }

            document.getElementById("menu_id").innerHTML = options;

            var options1 = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data[1].length; i++) {
                options1 += '<option value="' + data[1][i].id + '">' + data[1][i].name + '</option>'
            }
            document.getElementById("brand_id").innerHTML = options1;

        }
    });
}



function menu_changed() {
    var menu_id = document.getElementById("menu_id").value;
    $.ajax({
        url: base_url + 'food/items_by_menu',
        type: 'post',
        data: { menu_id: menu_id },
        dataType: 'json',
        success: function(data) {
            //console.log(data.length);
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("item_id").innerHTML = options;
        }
    });
}

function item_changed() {
    var item_id = document.getElementById("item_id").value;
    $.ajax({
        url: base_url + 'food/sections_by_item',
        type: 'post',
        data: { item_id: item_id },
        dataType: 'json',
        success: function(data) {
            //console.log(data.length);
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("sec_list").innerHTML = options;
        }
    });
}

function product_changed() {
    var product_id = document.getElementById("product_id").value;
    $.ajax({
        url: base_url + 'food/sections_by_product',
        type: 'post',
        data: { product_id: product_id },
        dataType: 'json',
        success: function(data) {
            //console.log(data.length);
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("varient_id").innerHTML = options;
        }
    });
}


function category_changed1() {  
    var cat_id = document.getElementById("cat_id").value;
    $.ajax({
        url: base_url + 'food/category_changed',
        type: 'post',
        data: { cat_id: cat_id },
        dataType: 'json',

        success: function(data) {
            //console.log(data.length);
            var options = '<option value="0" selected disabled>--select--</option><option value="all">All</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("sub_cat_id").innerHTML = options;
            document.getElementById("sub_cat_id2").innerHTML = options;
        }
    });
}


function sub_category_changed() {
    var sub_cat_id = document.getElementById("sub_cat_id").value;
    $.ajax({
        url: base_url + 'ecom_brands/list',
        type: 'post',
        data: { sub_cat_id: sub_cat_id },
        dataType: 'json',
        success: function(data) {
            console.log(data[0]);
            let options = '<option value="0" selected disabled>--select--</option>';
            $.each(data[0].brands, function(index, element) {
                options += '<option value="' + element.id + '">' + element.name + '</option>';
            });
            document.getElementById("brand_id").innerHTML = options;

            var options2 = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data[0].ecom_sub_sub_categories.length; i++) {
                options2 += '<option value="' + data[0].ecom_sub_sub_categories[i].id + '">' + data[0].ecom_sub_sub_categories[i].name + '</option>'
            }
            document.getElementById("sub_id").innerHTML = options2;

        }
    });
}


function category_changedsub() {
    var cat_id = document.getElementById("category").value;
    $.ajax({
        url: base_url + 'ecom_sub_category/list',
        type: 'post',
        data: { cat_id: cat_id },
        dataType: 'json',
        success: function(data) {
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("subcat").innerHTML = options;


        }
    });
}

function sub_ocategory_changed() {
    var sub_cat_id = document.getElementById("sub_cat_id").value;
    $.ajax({
        url: base_url + 'ecom_options/list',
        type: 'post',
        data: { sub_cat_id: sub_cat_id },
        dataType: 'json',
        success: function(data) {
            console.log(data[0]);


            var options2 = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data[0].ecom_sub_sub_categories.length; i++) {
                options2 += '<option value="' + data[0].ecom_sub_sub_categories[i].id + '">' + data[0].ecom_sub_sub_categories[i].name + '</option>'
            }
            document.getElementById("sub_id").innerHTML = options2;

        }
    });
}

function gro_category_changed(cat_id) {
    /*var cat_id = document.getElementById("category").value;*/
    $.ajax({
        url: base_url + 'grocery_sub_category/list',
        type: 'post',
        data: { cat_id: cat_id },
        dataType: 'json',
        success: function(data) {
            //console.log(data.length);
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("gro_sub_cat_id").innerHTML = options;
        }
    });
}


function gro_sub_category_changed(sub_cat_id) {
    /*var sub_cat_id = document.getElementById("sub_cat_id").value;*/
    $.ajax({
        url: base_url + 'grocery_brands/list',
        type: 'post',
        data: { sub_cat_id: sub_cat_id },
        dataType: 'json',
        success: function(data) {
            console.log(data[0]);
            let options = '<option value="0" selected disabled>--select--</option>';
            $.each(data[0].brands, function(index, element) {
                options += '<option value="' + element.id + '">' + element.name + '</option>';
            });
            document.getElementById("gro_brand_id").innerHTML = options;

            /*     var options2 = '<option value="0" selected disabled>--select--</option>';
                 for(var i = 0; i < data[0].ecom_sub_sub_categories.length; i++){
                     options2 += '<option value="'+data[0].ecom_sub_sub_categories[i].id+'">'+data[0].ecom_sub_sub_categories[i].name+'</option>'
                 }
                 document.getElementById("sub_id").innerHTML = options2;*/

        }
    });
}


function gro_category_changedsub() {
    var cat_id = document.getElementById("category").value;
    $.ajax({
        url: base_url + 'grocery_sub_category/list',
        type: 'post',
        data: { cat_id: cat_id },
        dataType: 'json',
        success: function(data) {
            var options = '<option value="0" selected disabled>--select--</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].id + '">' + data[i].name + '</option>'
            }
            document.getElementById("gro_subcat").innerHTML = options;
        }
    });
}

// function banners_changed() {
//     var cat_id = document.getElementById("cat_id").value;
//     $.ajax({
//         url: base_url + 'promotion_banners/banner_images',
//         type: 'post',
//         data: { cat_id: cat_id },
//         dataType: 'json',
//         success: function(data) {
//             var options = '';
//             for (var i = 0; i < data.length; i++) {
//                 options += '<div ><img src="' + base_url + 'uploads/promotion_banner_category_image/promotion_banner_category_' + data[i].id + '.jpg" width="100%"></div>';
//             }
//             document.getElementById("image").innerHTML = options;
//         }
//     });
// }

function clear_form(id) {
    $('#' + id).find('input:text, input:password, input:file, select, textarea').val('');
    $("#" + id).find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
}

$(function() {
    $("#btnSubmit").click(function() {
        var password = $("#Password").val();
        var confirmPassword = $("#ConfirmPassword").val();
        if (password != confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    });
});

function readURL(input, width, height) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#blah')
                .attr('src', e.target.result)
                .width(width)
                .height(height);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function readURL1(input, width, height) {

    var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    };

    $('#gallery-photo-add').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
}


$(document).ready(function() {
    $('#state_id').change(function() {

        var state_id = $('#state_id').val();

        var data = {
            state_id: state_id
        };

        $.ajax({
            url: base_url + 'admin/master/fetchdisdata',
            type: 'POST',
            data: data,
            success: function(result) {

                $("#district_id").html(result);
            }
        });
    });
});

$(document).ready(function() {
    
    $('#district_id').change(function() {

        var district_id = $('#district_id').val();

        var data = {
            district_id: district_id
        };

        $.ajax({
            url: base_url + 'admin/master/fetchcondata',
            type: 'POST',
            data: data,
            success: function(result) {

                $("#constituancy_id").html(result);
            }
        });
    });
});

$(document).ready(function() {
    /*modify category for vendor*/
    $("#category").change(function() {
        var cat_id = $(this).val();
        var list_id = $('#list_id').val();
        $.ajax({
            url: base_url + "modify_category",
            method: "POST",
            data: { cat_id: cat_id, list_id: list_id },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                if (data == 200) {
                    location.reload();
                    $(".loader").hide();
                } else {
                    alert(data);
                }
            }
        });
    });

    $('#upload_form').on('submit', function(e) {
        e.preventDefault();
        if ($('#userfile').val() == '') {
            alert("Please Select the File");
        } else {
            $.ajax({
                url: "<?php echo base_url(); ?>master",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.success == true) {
                        $('#result').find('img').attr('src', data.file);
                    } else {
                        alert(data.msg);
                    }
                }
            });
        }
    });

    /*news module post type*/
    if (url == '' || url == undefined || url == null) {
        $(".link").hide();
    } else {
        $("#link").val(url);
        $(".link").show();
    }
    $("#type").change(function() {
        let type = $(this).val();
        if (type == 2) {
            if (url == '' || url == undefined || url == null) {
                $("#link").val('');
            } else {
                $("#link").val(url);
            }
            $(".link").show();
        } else {
            $("#link").val('');
            $(".link").hide();
        }
    });

});


$(document).ready(function() {
    $("img").bind("error", function() {
        // Set the default image
        $(this).attr("src", base_url + "assets/img/no.png");
    });
});

$(document).ready(function() {
    $('.approve_manual_payment').click(function() {

        var paymentRef = $(this).attr('payment_ref');
        var data = {
            payment_ref: paymentRef,
            action: 'approve'
        };

        $.ajax({
            url: base_url + 'admin/master/process_payment',
            type: 'POST',
            data: data,
            success: function(result) {
                location.href = location.href;
            }
        });
    });

    $('.reject_manual_payment').click(function() {
        var paymentRef = $(this).attr('payment_ref');
        var data = {
            payment_ref: paymentRef,
            action: 'reject'
        };

        $.ajax({
            url: base_url + 'admin/master/process_payment',
            type: 'POST',
            data: data,
            success: function(result) {
                location.href = location.href;
            }
        });
    });
});


function Validate() {
    var e = document.getElementById("brands_multiselect");
    var strUser = e.options[e.selectedIndex].value;
    //if you need text to be compared then use
    var strUser1 = e.options[e.selectedIndex].text;
    if (strUser == 0) //for text use if(strUser1=="Select")
    {
        alert("Please select a brands");
    }


    var d = document.getElementById("categorys_multiselect");
    var strUsers = d.options[d.selectedIndex].value;
    //if you need text to be compared then use
    var strUser2 = d.options[d.selectedIndex].text;
    if (strUsers == 0) //for text use if(strUser1=="Select")
    {
        alert("Please select a catagorys");
    }

}