<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>

<!--main contents start-->
<main class="content_wrapper">
    <!--page title start-->

    <!--page title end-->
    <div class="container-fluid">
        <!-- state start-->
        <div class="row">

            <div class="col-12">
                <div class="panel">

                    <div class="panel-content panel-about">
                        <h6>Video<span class="pull-right"><a
                                    href="<?php echo base_url('executive/dashboard'); ?>">Back</a></span></h6>

                        <iframe width="100%" height="315"
                            src="https://www.youtube.com/embed/<?php echo $referral_video; ?>"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>

                </div>
            </div>
        </div>
        <!-- state end-->
    </div>
</main>
<!--main contents end-->
</div>

<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>