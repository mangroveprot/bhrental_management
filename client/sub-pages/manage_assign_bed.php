<?php
include_once ('../../server/database/models/beds_model.php');
include_once ('../../server/database/models/tenants_model.php');

$bedID = isset($_POST['bedID']) ? $_POST['bedID'] : null;

$tenantsModel = new TenantsModel();
$bedModel = new BedModel();

try {
    $beds = $bedModel->getAllBeds();
} catch (Exception $e) {
    $error = 'Error fetching bed data: ' . $e->getMessage();
}

$tenants = $tenantsModel->getAllTenants();

$occupiedCustomerIds = array_column(array_filter($beds, function ($bed) {
    return !empty ($bed['customer_id']);
}), 'customer_id');

$unoccupiedTenants = array_filter($tenants, function ($tenant) use ($occupiedCustomerIds) {
    return !in_array($tenant['customer_id'], $occupiedCustomerIds);
});

?>
<style>
    .hide {
        display: none;
    }
</style>
<div class="container-fluid">
    <form action="" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>
        <div class="form-group">
            <label for="tenant_id" class="control-label">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2">

                <option value="" disabled selected>Please choose a tenant</option>
                <?php
                foreach ($unoccupiedTenants as $tenantId => $tenant):
                    ?>
                    <option value="<?php echo $tenantId ?>" data-customer-id="<?php echo $tenant['customer_id']; ?>">
                        <?php echo ucwords($tenant['first_name'] . ' ' . $tenant['last_name']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" id="save-button" class="btn btn-primary" data-bed-id="<?php echo $bedID; ?>">
        <span class="loading spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
        <span class="btn-text">Add</span>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).on('click', '#save-button', function () {
        var selectedOption = $('#tenant_id option:selected');
        var tenantID = selectedOption.data('customer-id');
        var bedID = $(this).data('bed-id');
        console.log(tenantID);
        console.log(bedID);
        if (!tenantID) {
            $('#msg').html('<div class="alert alert-danger">Please select a tenant.</div>');
            return;
        }
        $('.btn-text').text('Please wait...');
        $.ajax({
            url: '../../server/app/actionsHandler.php?action=assign-bed',
            type: 'post',
            data: { tenantID: tenantID, bedID: bedID },
            success: function (response) {
                if (response == 0) {
                    $.toast({
                        heading: 'Success',
                        text: 'Assign Successfully!',
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
                        text: 'Error while assigning. Please Try Again!',
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
                    text: 'Syntax: Error while assigning. Please Try Again!',
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