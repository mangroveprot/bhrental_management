<?php

?>

<style>
    .hide {
        display: none;
    }
</style>

<form id="addTenantForm">
    <div class="form-group">
        <label for="roomName">Room Name:</label>
        <input type="text" class="form-control" id="roomName" name="roomName" placeholder="Enter room name" required>
        <div class="invalid-feedback">Please enter room name.</div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="beds_capacity">Beds Capacity</label>
            <input type="text" class="form-control" id="beds_capacity" placeholder="0" pattern="[0-9]+"
                title="Please enter only numerical values" required>
        </div>
        <div class="form-group col-md-6">
            <label for="room_price">Price</label>
            <input type="text" class="form-control" id="room_price" placeholder="0" pattern="[0-9]+"
                title="Please enter only numerical values" required>
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