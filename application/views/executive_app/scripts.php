<script>
    $(document).ready(function () {

        $("#submitLoaderButton").click(function () {
            $("#exec_loader").show();

            $(window).on('load', function () {
                $("#exec_loader").hide();
            });
        });

        $(".refresh-icon a").click(function (e) {
            e.preventDefault(); // Prevent the default anchor behavior
            location.reload(); // Reload the current page
        });

        $('.primary-account-radio').change(function () {
            var accountNumber = $(this).data('account-number');
            var ifsc = $(this).data('ifsc');
            var isConfirmed = confirm(`Account number ${accountNumber} will be made the primary account.\nDo you want to proceed?`);

            if (isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('executive/bank_account/update_primary_account'); ?>',
                    type: 'POST',
                    data: {
                        account_number: accountNumber,
                        ifsc: ifsc
                    },
                    success: function (response) {
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        alert('An error occurred while updating the primary account.');
                    }
                });
            } else {
                console.log("test1");
                location.reload();
            }
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



    });


    function copyReferralCode(element) {
        if (element.classList.contains('disabled')) {
            return; // If already disabled, return without copying
        }
        var referralCode = element.querySelector('.referral-code').innerText;
        var tempInput = document.createElement('input');
        tempInput.value = referralCode;
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        var copiedMessage = document.createElement('span');
        copiedMessage.innerText = 'Copied!';
        copiedMessage.classList.add('copied-message');
        element.appendChild(copiedMessage);
        element.classList.add('disabled');
        setTimeout(function () {
            element.removeChild(copiedMessage);
            element.classList.remove('disabled');
        }, 2000); // Remove the message and enable clicking after 2 seconds
    }


    function shareContent(referralCode, message) {
        if (navigator.share) {
            const shareContent = {
                title: 'Referral Code',
                text: message,
            };

            navigator.share(shareContent)
                .then(() => console.log('Successfully shared'))
                .catch((error) => console.error('Error sharing:', error));
        } else {
            alert('Web Share API is not supported on this browser.');
        }
    }

    // Add event listeners to all share icons
    document.querySelectorAll('.icon-share').forEach(icon => {
        icon.addEventListener('click', (event) => {
            const referralCode = event.target.getAttribute('data-referral-code');
            let message = event.target.getAttribute('data-message');
            let user_type = event.target.getAttribute('data-type');
            // message += '\n*-NEXT CLICK*';
            if (user_type == 'vendor') {
                message += '\n https://play.google.com/store/apps/details?id=com.nextclick.crm';
            } else if (user_type == 'user') {
                message += '\n https://play.google.com/store/apps/details?id=com.nextclick.user';
            } else if (user_type == 'deliveryboynew') {
                message += '\n https://play.google.com/store/apps/details?id=com.nextclick.deliveryboynew';
            }
            shareContent(referralCode, message);
        });
    });


</script>