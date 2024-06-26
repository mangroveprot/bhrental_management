<?php
session_start();
$tenantsID = $_POST['tenantsID'] ?? null;
?>
<style>
    .hide {
        display: none;
    }
</style>
<span>Are you sure you want to delete this tenant?</span>
<div class="modal-footer">
    <button type="button" class="btn btn-danger deleteTenant">
        <span class="loading spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
        <span class="btn-text" data-id="<?php echo $tenantsID; ?>">Delete</span>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).ready(function () {
        var tenantsID = $(this).data('id');
        $('.deleteTenant').click(function (event) {
            event.preventDefault();
            var tenantsID = $(this).find('.btn-text').data('id');
            $('.loading').removeClass('hide');
            $('.btn').attr('disabled', true);
            $('.btn-text').text('Deleting...');
            $.ajax({
                url: '../../server/app/actionsHandler.php?action=delete-tenant',
                type: 'post',
                data: { tenantID: tenantsID },
                success: function (response) {
                    console.log(response);
                    if (response == 0) {
                        $.toast({
                            heading: 'Success',
                            text: 'Deleted Succesfully!',
                            showHideTransition: 'slide',
                            icon: 'success',
                            position: 'top-right',
                            stack: false
                        })
                        setTimeout(function () {
                            location.reload()
                        }, 2500)
                    } else {
                        $.toast({
                            heading: 'Notice',
                            text: 'Please Remove This Tenant In Bed, Before Proceeding!',
                            showHideTransition: 'slide',
                            icon: 'info',
                            position: 'top-right',
                            stack: false
                        });

                        $('.loading').addClass('hide');
                        $('.btn').attr('disabled', false);
                        $('.btn-text').text('Save Changes');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    $.toast({
                        heading: 'Success',
                        text: 'Error while saving. Please Try Again!',
                        showHideTransition: 'slide',
                        icon: 'error',
                        position: 'top-right',
                        stack: false
                    })
                }
            });
        });
    });
</script>