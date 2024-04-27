<?php

?>
<style>
    .hide {
        display: none;
    }
</style>

<form id="addTenantForm">
    <div class="form-group">
        <label for="firstName">First Name:</label>
        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
        <div class="invalid-feedback">Please enter first name.</div>
    </div>

    <div class="form-group">
        <label for="lastName">Last Name:</label>
        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
        <div class="invalid-feedback">Please enter last name.</div>
    </div>

    <div class="form-group">
        <label for="contactNumber">Contact Number:</label>
        <input type="text" class="form-control" id="contactNumber" name="contactNumber"
            placeholder="Enter contact number" pattern="[0-9]+" title="Please enter only numerical values" required>
        <div class="invalid-feedback">Please enter contact number.</div>
    </div>

    <div class="form-group">
        <label for="gender">Gender:</label>
        <select class="form-control" id="gender" name="gender" required>
            <option value="" disabled selected>Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <div class="invalid-feedback">Please select gender.</div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveChangesBtn">
            <span class="loading spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
            <span class="btn-text">Save Changes</span>
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</form>

<script>
    $(document).ready(function () {

        $('#saveChangesBtn').click(function (event) {
            event.preventDefault();
            if (validateForm()) {
                saveChanges();
            }
        });

        function validateForm() {
            var isValid = true;
            $('#addTenantForm input, #addTenantForm select').each(function () {
                if ($(this).prop('required') && !$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return isValid;
        }

        function saveChanges() {
            $('.loading').removeClass('hide');
            $('.btn').attr('disabled', true);
            $('.btn-text').text('Saving...');
            var formData = $('#addTenantForm').serialize();
            $.ajax({
                url: '../../server/app/actionsHandler.php?action=add-tenant',
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
                        $('.btn-text').text('Save Changes');
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
                    $('.btn-text').text('Save Changes');
                }
            });
        }
    });
</script>