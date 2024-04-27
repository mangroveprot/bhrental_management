<?php
session_start();
$transactionID = $_POST['transaction'] ?? null;
?>
<style>
    .hide {
        display: none;
    }
</style>
<span>Are you sure you want to delete this transaction?</span>
<div class="modal-footer">
    <button type="button" class="btn btn-danger deleteTransaction">
        <span class="loading spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
        <span class="btn-text" data-id="<?php echo $transactionID; ?>">Delete</span>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).ready(function () {
        var tID = $(this).data('id');
        $('.deleteTransaction').click(function (event) {
            event.preventDefault();
            var tID = $(this).find('.btn-text').data('id');
            console.log(tID);
            $('.loading').removeClass('hide');
            $('.btn').attr('disabled', true);
            $('.btn-text').text('Deleting...');
            $.ajax({
                url: '../../server/app/actionsHandler.php?action=delete-transaction',
                type: 'post',
                data: { tID: tID },
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
                            heading: 'Error',
                            text: 'Error while deleting the transaction!!',
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
                        text: 'Server Error!',
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