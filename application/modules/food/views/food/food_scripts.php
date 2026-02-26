<script type="text/javascript">
	function get_sub_item(item_id) {
		 $.ajax({
            url: '<?php echo base_url();?>food/get_sub_item_list/' + item_id ,
            type: 'get',
            success: function(response)
            {
            	$('#sub_items').html(response);
            }
    	});
	}
    function get_food_sections(item_id) {
         $.ajax({
            url: '<?php echo base_url();?>food/get_food_sections_list/' + item_id ,
            type: 'get',
            success: function(response)
            {
                $('#sections_list').html(response);
            }
        });
    }
</script>
<script type="text/javascript">
    function get_orders_list(type) {
        $.ajax({
            url: '<?php echo base_url();?>food/get_orders_list/' + type,
            type: 'get',
            success: function(response)
            {
                //alert(response);
                return orders_align(response);
            }
        });
    }
    function orders_align(response) {
        
    }
</script>

    <script type="text/javascript">
    function showAjaxModal(url)
    {
        // SHOWING AJAX PRELOADER IMAGE
        jQuery('#modal_ajax .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/preloader.gif" /></div>');
        
        // LOADING THE AJAX MODAL
        jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
        
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: url,
            success: function(response)
            {
                jQuery('#modal_ajax .modal-body').html(response);
            }
        });
    }
    </script>
    
    <!-- (Ajax Modal)-->
    <div class="modal fade" id="modal_ajax">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $system_name;?></h4>
                </div>
                
                <div class="modal-body" style="height:500px; overflow:auto;">
                
                    
                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>