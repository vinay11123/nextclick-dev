<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!-- General JS Scripts -->
<script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
<!-- JS Libraies -->
<script src="<?php echo base_url(); ?>assets/bundles/chartjs/chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/apexcharts/apexcharts.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/bootstrap.min.cssbootstrap.min.css.sparkline.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="<?php echo base_url(); ?>assets/bundles/jqvmap/dist/maps/jquery.vmap.indonesia.js"></script>

<!-- Page Specific JS File -->
<script src="<?php echo base_url(); ?>assets/js/page/index2.js"></script>
<script src="<?php echo base_url(); ?>assets/js/page/todo.js"></script>

<script src="<?php echo base_url() ?>assets/bundles/prism/prism.js"></script>
<!-- Template JS File -->
<script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
<!-- Custom JS File -->
<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
<!-- Master JS File -->
<script src="<?php echo base_url(); ?>assets/js/master.js"></script>
<!-- multiselect JS file -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap-multiselect.js"></script>
<script src="<?php echo base_url(); ?>assets/js/init-multiselect.js"></script>

<!-- bootstrap min JS file -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap-3.3.2.min.js"></script>

<!-- bootstrap toogle button -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap4-toggle.min.js"></script>

<!-- Ckeditor library -->
<script src="<?php echo base_url(); ?>assets/bundles/ckeditor/ckeditor.js"></script>

<!-- <script src="https://cdn.ckeditor.com/4.13.0/standard-all/ckeditor.js"></script> -->
<script src="<?php echo base_url(); ?>assets/js/init-ckeditor.js?<?= time(); ?>"></script>

<!-- Drag and Drop image -->
<script src="<?php echo base_url(); ?>assets/js/dropzone.js"></script>

<!-- Gijgo Datepicker -->
<script src="<?php echo base_url(); ?>assets/js/gijgo-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/init-datepicker.js?<?= time(); ?>"></script>


<!-- Datatables -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/page/datatables.js"></script>

<script type="text/javascript">
  /*http://www.soundjay.com/misc/sounds/bell-ringing-01.mp3*/
  var audioElement = document.createElement('audio');
  audioElement.setAttribute('src', '<?= base_url('assets/deduction.mp3'); ?>');

  audioElement.addEventListener('ended', function() {
    this.play();
  }, false);

  function order_bell() {
    audioElement.play();
  }

  $(document).ready(function() {

    $('#OrderDatatable').DataTable({
      "lengthMenu": [
        [5, 10, 25, 50, 100, -1],
        [5, 10, 25, 50, 100, "All"]
      ],
      dom: '<"top"Blfi<"clear">>rt<"bottom"ip<"clear">>',
      buttons: [{
          extend: 'pdf',
          title: 'Orders List',
          orientation: 'landscape',
          pageSize: 'A4',
          footer: true,
          exportOptions: {

            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        },
        {
          extend: 'csv',
          title: 'Orders List',
          exportOptions: {
            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        }
      ],
      columnDefs: [{
        targets: 0,
        render: function(data, type) {

          return type === 'export' ? number++ : data;
        }
      }]
    });
    $('#BannerDatatable').DataTable({
      "lengthMenu": [
        [5, 10, 25, 50, 100, -1],
        [5, 10, 25, 50, 100, "All"]
      ],
      dom: '<"top"Blfi<"clear">>rt<"bottom"ip<"clear">>',
      buttons: [{
          extend: 'pdf',
          title: 'Admin Banners List',
          orientation: 'landscape',
          pageSize: 'A4',
          footer: true,
          exportOptions: {

            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        },
        {
          extend: 'csv',
          title: 'Admin Banners List',
          exportOptions: {
            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        }
      ],
      columnDefs: [{
        targets: 0,
        render: function(data, type) {

          return type === 'export' ? number++ : data;
        }
      }]
    });
    $('#PaymentDatatable').DataTable({
      "lengthMenu": [
        [5, 10, 25, 50, 100, -1],
        [5, 10, 25, 50, 100, "All"]
      ],
      dom: '<"top"Blfi<"clear">>rt<"bottom"ip<"clear">>',
      buttons: [{
          extend: 'pdf',
          title: 'Transaction List',
          orientation: 'landscape',
          pageSize: 'A4',
          footer: true,
          exportOptions: {

            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        },
        {
          extend: 'csv',
          title: 'Transaction List',
          exportOptions: {
            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        }
      ],
      columnDefs: [{
        targets: 0,
        render: function(data, type) {

          return type === 'export' ? number++ : data;
        }
      }]
    });
    $('#PickupOrderDatatable').DataTable({
      "lengthMenu": [
        [5, 10, 25, 50, 100, -1],
        [5, 10, 25, 50, 100, "All"]
      ],
      dom: '<"top"Blfi<"clear">>rt<"bottom"ip<"clear">>',
      buttons: [{
          extend: 'pdf',
          title: 'Pick up Orders List',
          orientation: 'landscape',
          pageSize: 'A4',
          footer: true,
          exportOptions: {

            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        },
        {
          extend: 'csv',
          title: 'Orders List',
          exportOptions: {
            orthogonal: "export",
            rows: function(idx, data, node) {
              number = 1;
              return true;
            }
          }
        }
      ],
      columnDefs: [{
        targets: 0,
        render: function(data, type) {

          return type === 'export' ? number++ : data;
        }
      }]
    });
    deleteAllCookies();
    $('.pay_status').change(function() {
      var id = $(this).attr('id');
      var status = $(this).val();
      var txn_id = prompt("Please enter Transaction Number");
      if (txn_id != null) {
        $.ajax({
          url: base_url + 'wallet_transactions/change_status',
          type: 'post',
          data: {
            id: id,
            status: status,
            txn_id: txn_id
          },
          success: function(data) {
            window.location.reload();
          }
        });
      }
    });
  });

  function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i];
      var eqPos = cookie.indexOf("=");
      var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
      document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
    return true;
  }
</script>


<script type="text/javascript">
  function showAjaxModal(url) {
    // SHOWING AJAX PRELOADER IMAGE
    jQuery('#modal_ajax .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="assets/images/preloader.gif" style="height:25px;" /></div>');

    // LOADING THE AJAX MODAL
    jQuery('#modal_ajax').modal('show', {
      backdrop: 'true'
    });

    // SHOW AJAX RESPONSE ON REQUEST SUCCESS
    $.ajax({
      url: url,
      success: function(response) {
        jQuery('#modal_ajax .modal-body').html(response);
      }
    });
  }
</script>

<!-- (Ajax Modal)-->

<div class="modal fade" id="modal_ajax">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <header class="card-header">
        <h2 class="card-title"><?php echo $system_name; ?><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></h2>
      </header>
      <div class="card-body">
        <div class="modal-body" style="height:400px; overflow:auto;">
        </div>
        <div class="modal-wrapper">
          <div class="modal-icon">
            <i class="fas fa-question-circle"></i>
          </div>
          <div class="modal-text">
            <h4>Are you sure want to Update this information.?</h4>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
        <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo 'Cancel'; ?></button>
      </div>
    </div>
  </div>
</div>


<script>
  function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
  }
</script>

<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'> </script>
<script type="text/javascript">
  function initialize() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(p) {
        var LatLng = new google.maps.LatLng(p.coords.latitude, p.coords.longitude);
        $("#latitude").val(p.coords.latitude);
        $("#logitude").val(p.coords.longitude);
        $("#location_name").val(p.coords.latitude + ', ' + p.coords.longitude);
      });
    } else {
      alert('Geo Location feature is not supported in this browser.');
    }
  }

  var geocoder = new google.maps.Geocoder;

  function geocodeLatLng(lat, lng) {

    var latlng = {
      lat: lat,
      lng: lng
    };

    geocoder.geocode({
      'location': latlng
    }, function(results, status) {
      if (status === 'OK') {
        console.log(result);
        if (results[0]) {

          //This is yout formatted address
          window.alert(results[0].formatted_address);

        } else {
          window.alert('No results found');
        }
      } else {
        window.alert('Geocoder failed due to: ' + status);
      }
    });

  }
</script>