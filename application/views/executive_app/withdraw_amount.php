<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<main class="content_wrapper">
    <!--page title start-->

    <!--page title end-->
    <div class="container-fluid">
        <!-- state start-->
        <div class="row">
            <div class="col-12 mt-1 mb-2">
                <a class="btn-primary btn-sm" href="<?php echo base_url('executive/wallet'); ?>">Back</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white bg-success border-0">
                        <div class="media ">
                            <div class="media-body text-white text-center">

                                <h3 class="text-white">Wallet Amount</h3>

                                <h2 class="text-white f30 mt-2">
                                    Rs.<?= isset($total_all_amount) ? $total_all_amount : 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-white p-4">
                        <div class="row">
                            <div class="col">
                                <h4 class="text-success weight-600">
                                    Rs.<?= isset($total_vendor_amount) ? $total_vendor_amount : 0; ?></h4>
                                <span class="small">Vendors</span>
                            </div>
                            <div class="col">
                                <h4 class="text-success weight-600">
                                    Rs.<?= isset($total_delivery_boy_amount) ? $total_delivery_boy_amount : 0; ?></h4>
                                <span class="small">Delivery Boy's</span>
                            </div>
                            <div class="col">
                                <h4 class="text-success weight-600">
                                    Rs.<?= isset($total_user_amount) ? $total_user_amount : 0; ?></h4>
                                <span class="small">Users</span>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card card-shadow mb-4">
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-today" role="tabpanel"
                                aria-labelledby="pills-today-tab">

                                <h3 class="text-primary mb-15">Withdraw Amount</h3>

                                <form>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">From</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">Vendor Amount</option>
                                            <option value="">Delivery Boy Amount</option>
                                            <option value="">User Amount</option>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Amount</label>
                                        <input type="text" class="form-control">
                                    </div>


                                    <button type="submit" id="submitLoaderButton"
                                        class="btn btn-primary">Submit</button>
                                </form>





                            </div>


                        </div>
                    </div>

                </div>
            </div>


        </div>
        <!-- state end-->
    </div>
</main>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>