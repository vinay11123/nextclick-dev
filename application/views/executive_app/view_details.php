<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Details</title>
    <meta charset="UTF-8">
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:0;
        }
        .container{
            width:90%;
            max-width:1000px;
            margin:30px auto;
            background:#fff;
            padding:25px;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
        h2{
            margin-bottom:20px;
            color:#333;
            border-bottom:2px solid #eee;
            padding-bottom:10px;
        }
        .row{
            display:flex;
            margin-bottom:15px;
        }
        .label{
            width:30%;
            font-weight:bold;
            color:#555;
        }
        .value{
            width:70%;
            color:#333;
        }
        .badge{
            padding:5px 10px;
            border-radius:5px;
            font-size:13px;
            color:#fff;
        }
        .active{ background:green; }
        .inactive{ background:red; }
        .pending{ background:orange; }

        .section{
            margin-top:25px;
        }
        .service-box{
            display:inline-block;
            padding:8px 15px;
            background:#007bff;
            color:#fff;
            border-radius:20px;
            font-size:13px;
            margin-right:10px;
            margin-bottom:10px;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Vendor Details</h2>

    <div class="row">
        <div class="label">Business Name</div>
        <div class="value"><?= $vendor['business_name'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">Owner Name</div>
        <div class="value"><?= $vendor['owner_name'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">Email</div>
        <div class="value"><?= $vendor['email'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">WhatsApp No</div>
        <div class="value"><?= $vendor['whats_app_no'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">Category</div>
        <div class="value"><?= $vendor['category']['name'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">Address</div>
        <div class="value"><?= $vendor['location']['address'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">Subscription From</div>
        <div class="value"><?= $vendor['from'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">Subscription To</div>
        <div class="value"><?= $vendor['to'] ?? '-' ?></div>
    </div>

    <div class="row">
        <div class="label">Status</div>
        <div class="value">
            <?php
                if($vendor['status'] == 1){
                    echo '<span class="badge active">Active</span>';
                }elseif($vendor['status'] == 2){
                    echo '<span class="badge pending">Pending</span>';
                }else{
                    echo '<span class="badge inactive">Inactive</span>';
                }
            ?>
        </div>
    </div>

    <!-- Services Section -->
    <?php if(!empty($vendor['services'])): ?>
        <div class="section">
            <h2>Services</h2>
            <?php foreach($vendor['services'] as $service): ?>
                <span class="service-box"><?= $service['name']; ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<a href="<?php echo base_url(); ?>/executive/vendor_checklist" class="back-btn">← Back</a>
</div>

</body>
</html>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>