<!-- header_End -->
<!-- Content_right -->
<div class="container_full">

    <div class="side_bar dark_blue side_bg_img scroll_auto">
        <ul id="dc_accordion" class="sidebar-menu tree">
            <li class="menu_title"><a href="<?php echo base_url('executive/dashboard'); ?>">Dashboard</a></li>
            <li class="menu_title"><a href="<?php echo base_url('executive/profile'); ?>">My ID</a></li>
            <li class="menu_title"><a href="<?php echo base_url('executive/referral_video'); ?>">Referral Video</a></li>
            <li class="menu_title"><a href="<?php echo base_url('executive/executive_terms'); ?>">Terms & Conditions</a></li>
            
            <!-- <li class="menu_title"><a href="<?php echo base_url('executive/terms'); ?>">Terms & Conditions</a></li> -->
            <li class="menu_title"><a href="#" data-toggle="modal" data-target="#exampleModal">Logout</a></li>
            <!-- DropDown_Inbox -->

        </ul>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel5">Would you like to stay?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You might miss updates from Next Click
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Stay</button>
                    <a href="<?php echo base_url('executive_app/authorize/logout'); ?>" type="button"
                        class="btn btn-primary">Logout</a></li>
                </div>
            </div>
        </div>
    </div>