<?php

?>

<style>
    .hide {
        display: none;
    }
</style>

<form id="addRoomForm">
    <div class="form-group">
        <label for="roomName">Room Name:</label>
        <input type="text" class="form-control" id="roomName" name="roomName" placeholder="Enter room name" required>
        <div class="invalid-feedback">Please enter room name.</div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="beds_capacity">Beds Capacity</label>
            <input type="text" class="form-control" id="beds_capacity" name="beds_capacity" placeholder="0" required>
            <div class="invalid-feedback">
                Please enter a valid number for beds capacity.
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="room_price">Price</label>
            <input type="text" class="form-control" id="room_price" name="room_price" placeholder="0" required>
            <div class="invalid-feedback">
                Please enter a valid number for price.
            </div>
        </div>
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
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('beds_capacity').addEventListener('input', function (event) {
            var bedsCapacity = event.target.value;

            if (isNaN(bedsCapacity) || bedsCapacity <= 0) {
                event.target.classList.add('is-invalid');
            } else {
                event.target.classList.remove('is-invalid');
            }
        });

        document.getElementById('room_price').addEventListener('input', function (event) {
            var roomPrice = event.target.value;

            if (isNaN(roomPrice) || roomPrice <= 0) {
                event.target.classList.add('is-invalid');
            } else {
                event.target.classList.remove('is-invalid');
            }
        });
    });

    $(document).ready(function () {

        $('#saveChangesBtn').click(function (event) {
            event.preventDefault();
            if (validateForm()) {
                saveChanges();
            }
        });

        function validateForm() {
            var isValid = true;
            $('#addRoomForm input, #addRoomForm select').each(function () {
                if ($(this).prop('required') && !$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            var bedsCapacity = $('#beds_capacity').val();
            var roomPrice = $('#room_price').val();

            if (isNaN(bedsCapacity) || bedsCapacity <= 0) {
                $('#beds_capacity').addClass('is-invalid');
                isValid = false;
            } else {
                $('#beds_capacity').removeClass('is-invalid');
            }

            if (isNaN(roomPrice) || roomPrice <= 0) {
                $('#room_price').addClass('is-invalid');
                isValid = false;
            } else {
                $('#room_price').removeClass('is-invalid');
            }

            return isValid;
        }

        function saveChanges() {
            $('.loading').removeClass('hide');
            $('.btn').attr('disabled', true);
            $('.btn-text').text('Saving...');
            var formData = $('#addRoomForm').serialize();
            $.ajax({
                url: '../../server/app/actionsHandler.php?action=add-room',
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