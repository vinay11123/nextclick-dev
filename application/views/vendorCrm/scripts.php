<script>
  function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
  }

  $(document).ready(function () {
    $('#tableExport').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });


    $('#ordered_user_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#unordered_user_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#pending_vendor_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#subscribed_vendor_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#unsubscribed_vendor_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#target_achieved_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#target_not_achieved_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#pending_captain_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#tableExportNoPaging').DataTable({
      dom: 'Bfrtip',
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
      paging: false, // Disable pagination
      columnDefs: [
        { targets: 'not-export-column', orderable: false }
      ]
    });


    $('.statusToggle').change(function () {

      var confirmation = confirm("Are you sure you want to change the status?");

      if (confirmation) {
        let id = $(this).attr('id');
        let is_checked = $(this).is(':checked');
        $.ajax({
          url: '<?php echo base_url("executivestatus/change_status"); ?>',
          type: 'post',
          dataType: 'json',
          data: { id: id, is_checked: is_checked },
          success: function (response) {
            console.log('Status updated successfully');
          }
        });
      } else {
        this.checked = !this.checked;
      }

    });


    $('#bank_details_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#wallet_vendor_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#wallet_user_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#wallet_captain_table').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#vendor_all_orders').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#vendor_ongoing_orders').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#vendor_out_for_delivery_orders').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#vendor_rejected_orders').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#vendor_cancelled_orders').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

    $('#vendor_rejected_by_delivey_partner_orders').DataTable({
      dom: 'Bfrtip',
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
        { targets: 'not-export-column', orderable: false }
      ]
    });

  });



</script>