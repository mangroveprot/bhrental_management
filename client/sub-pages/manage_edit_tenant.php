<?php
$tenantsID = $_POST['tenantsID'];
include_once ('../../server/database/models/tenants_model.php');

$tenantsModel = new TenantsModel();

try {
    $tenant = $tenantsModel->getTenantsByID($tenantsID);
    $tenant = $tenant[0];
} catch (Exception $e) {
    echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
    exit;
}
?>

<style>
    .hide {
        display: none;
    }
</style>

<form id="editTenantForm" class="needs-validation" novalidate>
    <input type="hidden" name="tenantID" value="<?php echo $tenantsID; ?>">

    <div class="form-group">
        <label for="firstName">First Name:</label>
        <input type="text" class="form-control" id="firstName" name="firstName"
            value="<?php echo $tenant['first_name']; ?>" required>
        <div class="invalid-feedback">Please enter first name.</div>
    </div>

    <div class="form-group">
        <label for="lastName">Last Name:</label>
        <input type="text" class="form-control" id="lastName" name="lastName"
            value="<?php echo $tenant['last_name']; ?>" required>
        <div class="invalid-feedback">Please enter last name.</div>
    </div>

    <div class="form-group">
        <label for="contactNumber">Contact Number:</label>
        <input type="text" class="form-control" id="contactNumber" name="contactNumber"
            value="<?php echo $tenant['contact_number']; ?>" required>
        <div class="invalid-feedback">Please enter contact number.</div>
    </div>

    <div class="form-group">
        <label for="gender">Gender:</label>
        <select class="form-control" id="gender" name="gender" required>
            <option value="" disabled>Select gender</option>
            <option value="Male" <?php echo ($tenant['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($tenant['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>
        <div class="invalid-feedback">Please select gender.</div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" data-id="<?php echo $tenant['customer_id']; ?>">
            <span class="loading spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
            <span class="btn-text">Save Changes</span>
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>

</form>

<script>
    $(document).ready(function () {

        $('#editTenantForm').submit(function (event) {
            event.preventDefault();
            if (this.checkValidity() === false) {
                event.stopPropagation();
            } else {
                $('.loading').removeClass('hide');
                $('.btn').attr('disabled', true);
                $('.btn-text').text('Saving...');
                var formData = $(this).serialize();
                $.ajax({
                    url: '../../server/app/actionsHandler.php?action=update-tenant',
                    type: 'post',
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        if (response == 0) {
                            $.toast({
                                heading: 'Success',
                                text: 'Change Successfully!',
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
                                text: 'Error while saving. Please Try Again!',
                                showHideTransition: 'slide',
                                icon: 'error',
                                position: 'top-right',
                                stack: false
                            });

                            $('.loading').addClass('hide');
                            $('.btn').attr('disabled', false);
                            $('.btn-text').text('Save');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        $.toast({
                            heading: 'Error',
                            text: 'Syntax: Error while saving. Please Try Again!',
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
            }
            $(this).addClass('was-validated');
        });
    });

</script>