<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<div class="content_wrapper">
    <div class="container-fluid">
        <!-- breadcrumb -->

        <!-- breadcrumb_End -->

        <!-- Section -->
        <section class="chart_section">

          <div class="row">

    <div class="col-12 mt-1 mb-2">
        <a class="btn-primary btn-sm" href="<?= base_url('executive/dashboard'); ?>">Back</a>
    </div>


 <?php if (!empty($archive)) : ?>

<?php
$monthly_target      = (int) ($archive['monthly_target'] ?? 0);
$executive_achieved  = (int) ($archive['executive_target'] ?? 0);
$freelancer_achieved = (int) ($archive['target_freelancer'] ?? 0);


$remaining_targetfree    = max(0, $monthly_target - $freelancer_achieved);
$remaining_targetexc   = max(0, $monthly_target - $executive_achieved);
$vendor_type = $archive['vendor_type'] ?? '';
?>

<div class="col-12 mb-4">
    <a href="<?= base_url('executive/myarchive'); ?>">
        <div class="card card-shadow">
            <div class="card-body">
                <div class="row">

                    <div class="col-3">
                        <span class="bg-success text-center wb-icon-box">
                            <i class="icon-graph text-light f24"></i>
                        </span>
                    </div>

                    <div class="col-9">
                        <h6 class="mt-1 mb-1">My Archive</h6>

                        <p class="mb-0 small">
                             ID:
                            <strong><?= $archive['executive_id']; ?></strong>
                        </p>

                        <!-- EMPLOYER -->
                        <?php if ($vendor_type === 'employer') : ?>

                            <p class="mb-0 small">
                                Executive Achieved:
                                <strong class="text-success">
                                    <?= $executive_achieved; ?>
                                </strong>
                            </p>
                      <!-- COMMON FOR BOTH -->
                        <p class="mb-0 small">
                            Monthly Target:
                            <strong><?= $monthly_target; ?></strong>
                        </p>

                        <p class="mb-0 small">
                            Total Achieved:
                            <strong><?= $total_achieved; ?></strong>
                        </p>

                        <p class="mb-0 small">
                            Remaining Target:
                            <strong class="text-danger">
                                <?= $remaining_targetexc; ?>
                            </strong>
                        </p>
                        <?php endif; ?>

                        <!-- FREELANCER -->
                        <?php if ($vendor_type === 'freelancer') : ?>

                            <p class="mb-0 small">
                                Freelancer Achieved:
                                <strong class="text-info">
                                    <?= $freelancer_achieved; ?>
                                </strong>
                            </p>
                             <!-- COMMON FOR BOTH -->
                        <p class="mb-0 small">
                            Monthly Target:
                            <strong><?= $monthly_target; ?></strong>
                        </p>

                        <p class="mb-0 small">
                            Total Achieved:
                            <strong><?= $total_achieved; ?></strong>
                        </p>
                    <p class="mb-0 small">
                            Remaining Target:
                            <strong class="text-danger">
                                <?= $remaining_targetfree; ?>
                            </strong>
                        </p>
                        <?php endif; ?>

                        

                    </div>

                </div>
            </div>
        </div>
    </a>
</div>

<?php endif; ?>

    
</div>





        </section>
        <!-- Section_End -->

    </div>
</div>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>