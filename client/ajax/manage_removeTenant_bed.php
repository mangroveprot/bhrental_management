<?php
session_start();
$bedID = $_POST['bedID'] ?? null;
?>
<style>
    .hide {
        display: none;
    }
</style>
<span>Are you sure you want to remove this tenant?</span>
<div class="modal-footer">
    <button type="button" class="btn btn-danger deleteTenant">
        <span class="loading spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
        <span class="btn-text" data-id="<?php echo $bedID; ?>">Remove</span>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).on('click', '.deleteTenant', function () {
        var bedID = $(this).find('.btn-text').data('id');
        console.log(bedID);
        $('.btn-text').text('Deleting please wait...');
        $.ajax({
            url: '../../server/app/actionsHandler.php?action=remove-tenant-bed',
            type: 'post',
            data: { bedID: bedID },
            success: function (response) {
                if (response == 0) {
                    $.toast({
                        heading: 'Success',
                        text: 'Delete Successfully!',
                        showHideTransition: 'slide',
                        icon: 'success',
                        position: 'top-right',
                        stack: false
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 2500);
                } else {
                    $.toast({
                        heading: 'Error',
                        text: 'Error while removing. Please Try Again!',
                        showHideTransition: 'slide',
                        icon: 'error',
                        position: 'top-right',
                        stack: false
                    });

                    $('.loading').addClass('hide');
                    $('.btn').attr('disabled', false);
                    $('.btn-text').text('Add');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                $.toast({
                    heading: 'Error',
                    text: 'Syntax: Error while removing. Please Try Again!',
                    showHideTransition: 'slide',
                    icon: 'error',
                    position: 'top-right',
                    stack: false
                });

                $('.loading').addClass('hide');
                $('.btn').attr('disabled', false);
                $('.btn-text').text('Save');
            }
        });
    });
</script>