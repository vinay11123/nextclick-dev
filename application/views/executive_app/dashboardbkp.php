<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>

<style>
body {
    background: #f4f6f9;
}

/* ================= USER HEADER ================= */

.user-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 12px 18px;
    border-radius: 12px;
    margin: 15px 0;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

@media (max-width: 576px) {
    .user-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}

/* ================= SMALL DASHBOARD ROW ================= */

.small-dashboard-row {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    gap: 10px;
    margin-bottom: 20px;
}

.small-dashboard-row::-webkit-scrollbar {
    height: 4px;
}
.small-dashboard-row::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.small-dashboard-row .card {
    min-width: 130px;
    min-height: 85px;
    border-radius: 12px;
    padding: 12px 10px;
    transition: 0.3s ease;
}

.small-dashboard-row .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.small-dashboard-row i {
    font-size: 22px;
    margin-bottom: 5px;
}

/* ================= INFO BOXES ================= */

.info_items {
    height: 95px;
    padding: 14px 16px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 14px;
    color: #fff;
    transition: 0.3s ease;
}

.info_items:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.15);
}

.info_items_icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
}

.info_items_icon i {
    font-size: 20px;
}

.info_item_content {
    display: flex;
    flex-direction: column;
}

.info_items_text {
    font-size: 14px;
    font-weight: 500;
}

.info_items_number {
    font-size: 20px;
    font-weight: 700;
}

/* ================= COLOR THEMES ================= */

.bg_green { background: linear-gradient(45deg,#28a745,#20c997); }
.bg_yellow { background: linear-gradient(45deg,#ffc107,#ff9800); }
.bg_blue { background: linear-gradient(45deg,#007bff,#00bcd4); }
.bg_pink { background: linear-gradient(45deg,#e83e8c,#f06292); }

@media (max-width: 576px) {
    .info_items { height: 85px; }
    .info_items_text { font-size: 12px; }
    .info_items_number { font-size: 16px; }
}
</style>

<div class="content_wrapper">
<div class="container-fluid">

<?php if(!empty($vendors)) { ?>
    <div class="user-header">
        <span>
            Name : <?= $vendors['display_name'] ?: ($vendors['first_name'].' '.$vendors['last_name']); ?>
        </span>
        <span>
            ID : <?= $vendors['id']; ?>
        </span>
    </div>
<?php } else { ?>
    <div class="user-header">
        <span>Name : <?= $exc_roles['executive_name']; ?></span>
        <span>ID : <?= $exc_roles['executive_id']; ?></span>
    </div>
<?php } ?>

<!-- ================= SMALL TOP CARDS ================= -->

<div class="small-dashboard-row">

    <div class="card text-center shadow-sm">
        <a href="<?= base_url('executive/gatecheck'); ?>">
            <i class="ion-log-in text-primary"></i>
            <div>VendorCheck</div>
        </a>
    </div>

    <div class="card text-center shadow-sm">
        <a href="<?= base_url('executive/reports'); ?>">
            <i class="ion-ios-paper text-warning"></i>
            <div>Reports</div>
        </a>
    </div>

    <div class="card text-center shadow-sm">
        <a href="<?= base_url('executive/profile'); ?>">
            <i class="ion-person text-success"></i>
            <div>Profile</div>
        </a>
    </div>

    <div class="card text-center shadow-sm">
        <a href="<?= base_url('executive/myarchive'); ?>">
            <i class="ion-ios-people-outline text-info"></i>
            <div>My Archive</div>
        </a>
    </div>

    <div class="card text-center shadow-sm">
        <a href="<?= base_url('executive/vendors'); ?>">
            <i class="ion-android-people text-danger"></i>
            <div>Vendors</div>
        </a>
    </div>

</div>

<!-- ================= MAIN INFO BOXES ================= -->

<div class="row">

    <div class="col-md-3 col-6 mb-4">
        <a href="<?= base_url('executive/vendors'); ?>">
            <div class="info_items bg_green">
                <span class="info_items_icon">
                    <i class="ion-android-people"></i>
                </span>
                <div class="info_item_content">
                    <span class="info_items_text">Vendors</span>
                    <span class="info_items_number"><?= $vendor_count ?? 0; ?></span>
                </div>
            </div>
        </a>
    </div>
	<div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo base_url('executive/referral_link'); ?>">
                        <div class=" bg-blue info_items d-flex align-items-center">
                            <span class="info_items_icon">
                                <i class="ion-link"></i>
                            </span>
                            <div class="info_item_content">
                                <span class="info_items_text">Referral Links</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </a>
                </div>
             <div class="col-md-3 col-6 mb-3">
                    <a href="<?php echo base_url('executive/users'); ?>">
                        <div class="info_items bg_blue d-flex align-items-center">
                            <span class="info_items_icon">
                                <i class="ion-ios-book"></i>
                            </span>
                            <div class="info_item_content">
                                <span class="info_items_text">>Vendor → User Referra</span>
                                <span class="info_items_number">  ₹<?= number_format($users['vendor_touser_referral_amount'] ?? 0, 2); ?></span>
                            </div>
                        </div>
                    </a>
                </div>
    <div class="col-md-3 col-6 mb-4">
        <a href="<?= base_url('executive/delivery_boys'); ?>">
            <div class="info_items bg_yellow">
                <span class="info_items_icon">
                    <i class="ion-ios-person"></i>
                </span>
                <div class="info_item_content">
                    <span class="info_items_text">Delivery Captain's</span>
                    <span class="info_items_number"><?= $deliveryCaptain ?? 0; ?></span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 col-6 mb-4">
        <a href="<?= base_url('executive/users'); ?>">
            <div class="info_items bg_blue">
                <span class="info_items_icon">
                    <i class="ion-ios-book"></i>
                </span>
                <div class="info_item_content">
                    <span class="info_items_text">Users</span>
                    <span class="info_items_number"><?= $users_count ?? 0; ?></span>
                </div>
            </div>
        </a>
    </div>

    <?php if ($executive_address['executive_type_id'] == 1) { ?>
    <div class="col-md-3 col-6 mb-4">
        <a href="<?= base_url('executive/wallet'); ?>">
            <div class="info_items bg_pink">
                <span class="info_items_icon">
                    <i class="ion-ios-pricetags"></i>
                </span>
                <div class="info_item_content">
                    <span class="info_items_text">Wallet</span>
                    <span class="info_items_number"><?= $walletAmount ?? 0; ?></span>
                </div>
            </div>
        </a>
    </div>
    <?php } ?>
        <div class="col-md-4 col-12 mb-3">
            <div class="bg-blue info_items d-flex align-items-center p-3 rounded">
                <i class="ion-link mr-3"></i>
                <div>
                    <small>Referral Code</small>
                    <h5><?= $referral_code; ?></h5>
                </div>
            </div>
        </div>
</div>

</div>
</div>

<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>
