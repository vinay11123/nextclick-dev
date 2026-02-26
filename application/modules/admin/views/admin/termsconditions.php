<?php $this->load->view('vendorCrm/header'); ?>
<?php $this->load->view('vendorCrm/sidebar'); ?>
<style>
  .exrmkwn1 {
    overflow-y: scroll;
    max-height: 200px !important;
    width: 290px !important;
  }
</style>
<!--Add Sub_Category And its list-->
<div class="pcoded-main-container">
  <div class="pcoded-wrapper">
    <div class="pcoded-content">

      <div class="row">
        <div class="col-12">
          <div class="card-body">
            <div class="card">
              <div class="card-header">
                <h4 class="col-10 ven1">List of T&c's</h4>
                <div class="text-right">
                  <a class="btn btn-outline-dark" href="<?php echo base_url('terms_conditions/c') ?>">
                    <i class="feather icon-plus" aria-hidden="true"></i> Add T&C's
                  </a>
                </div>

              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover" id="tableExport" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Sno</th>
                        <th>App Details</th>
                        <th>Page</th>
                        <th>Title</th>
                        <th>T&c Description</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($termsconditions)): ?>
                        <?php $sno = 1;
                        foreach ($termsconditions as $tc): ?>

                          <tr>
                            <td>
                              <?php echo $sno++; ?>
                            </td>
                            <td>
                              <?php switch ($tc['app_details_id']) {
                                case "1":
                                  echo "User app";
                                  break;
                                case "2":
                                  echo "Vendor app";
                                  break;
                                case "3":
                                  echo "Executive app";
                                  break;
                                case "4":
                                  echo "Delivery app";
                                  break;
                              }
                              ; ?>
                            </td>
                            <td>
                              <?php switch ($tc['page_id']) {
                                case "1":
                                  echo "Registration Page";
                                  break;
                                case "2":
                                  echo "Login Page";
                                  break;
                                case "3":
                                  echo "Payment Page";
                                  break;
                                case "4":
                                  echo "App Terms&Condtions";
                                  break;
                              }
                              ; ?>
                            </td>
                            <td>
                              <div class="tdtitle">
                                <?php echo $tc['title']; ?>
                              </div>
                            </td>
                            <td>
                              <div class="exrmkwn1">
                                <?php echo $tc['desc']; ?>
                              </div>
                            </td>
                            <td>
                              <a href="<?php echo base_url() ?>terms_conditions/edit?id=<?php echo $tc['id']; ?>"
                                class="mr-2" type="category"> <i class="feather icon-edit"></i>
                              </a>
                              <!-- <a href="#" class="mr-2  text-danger "
                                onClick="delete_record(<?php echo $tc['id'] ?>, 'termsconditions')">
                                <i class="feather icon-trash"></i>
                              </a> -->
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <th colspan="5">
                            <h3>
                              <center>Sorry!! No Terms&Conditions Found!!!</center>
                            </h3>
                          </th>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<script>
  $(document).ready(function () {
    $('#tableExport').DataTable({
      dom: 'Bfrtip',
      paging: false,
      buttons: [
        {
          extend: 'excel',
          exportOptions: {
            columns: ":not(.not-export-column)"
          }
        },
        {
          extend: 'pdf',
          exportOptions: {
            columns: ":not(.not-export-column)"
          }
        },
        {
          extend: 'print',
          exportOptions: {
            columns: ":not(.not-export-column)"
          }
        }
      ],
      columnDefs: [
        { targets: [5], orderable: false },
      ]
    });
  });

</script>
<?php $this->load->view('vendorCrm/footer'); ?>