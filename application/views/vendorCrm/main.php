<div class="loader"></div>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php $this->load->view('vendorCrm/header'); ?>
        <?php $this->load->view('vendorCrm/sidebar'); ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <?php $this->load->view($content); ?>
            </section>
        </div>
        <?php $this->load->view('vendorCrm/footer'); ?>
    </div>
</div>
</body>