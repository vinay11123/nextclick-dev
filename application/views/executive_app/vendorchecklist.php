<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>

<style>
body{
    background:#f5f6f8;
}

/* Page Title */
.page-title{
    font-size:20px;
    font-weight:600;
    margin-bottom:20px;
}

/* Card Design */
.visitor-card{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:15px;
    background:#fff;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    margin-bottom:15px;
    transition:0.3s;
}

.visitor-card:hover{
    transform:translateY(-3px);
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

/* Left Section */
.card-left{
    display:flex;
    align-items:center;
    flex:1;
}

/* Avatar */
.avatar{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#e0e0e0;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    font-size:20px;
    color:#555;
    margin-right:15px;
}

/* Info */
.visitor-info h5{
    margin:0;
    font-size:17px;
    font-weight:600;
}

.visitor-info p{
    margin:4px 0;
    font-size:13px;
    color:#666;
}

/* Status Badge */
.status{
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:500;
    margin-left:8px;
}

.green{
    background:#e6f4ea;
    color:#1e7e34;
}

.red{
    background:#fdecea;
    color:#c82333;
}

/* View Button */
.view-btn{
    background:#007bff;
    color:#fff;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
    font-weight:500;
    transition:0.3s;
}

.view-btn:hover{
    background:#0056b3;
    color:#fff;
}
</style>

<div class="container mt-4">

    <div class="page-title">
        Vendor Checklist
    </div>

    <?php if(!empty($executives)): ?>
        <?php foreach ($executives as $executive): ?>

            <div class="visitor-card">

                <!-- LEFT SIDE -->
                <div class="card-left">

                    <div class="avatar">
                        <?= strtoupper(substr($executive['name'], 0, 1)); ?>
                    </div>

                    <div class="visitor-info">
                        <h5>
                            <?= $executive['name']; ?>

                            <?php if($executive['status'] == 1): ?>
                                <span class="status green">Approved</span>
                            <?php else: ?>
                                <span class="status red">Disapproved</span>
                            <?php endif; ?>
                        </h5>

                        <p><strong>Executive ID:</strong> <?= $executive['id']; ?></p>
                        <p><strong>Email:</strong> <?= $executive['email']; ?></p>
                        <p>
                            <strong>Created On:</strong> 
                            <?= date('d-m-Y h:i A', strtotime($executive['created_at'])); ?>
                        </p>
                    </div>

                </div>

                <!-- RIGHT SIDE BUTTON -->
                <div>
                    <a href="<?= base_url('executive/view_details/'.$executive['id']); ?>" class="view-btn">
                        View
                    </a>
                </div>

            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">
            No Records Found
        </div>
    <?php endif; ?>

</div>

<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>