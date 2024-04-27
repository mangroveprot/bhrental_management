<?php
include_once ('../../server/database/models/payments_model.php');
include_once ('../../server/database/models/tenants_model.php');
$paymentsModel = new PaymentsModel();
$tenantsModel = new TenantsModel();

$transactionID = $_POST['transactionID'] ?? null;
if (isset($transactionID)) {
    $tData = $paymentsModel->getTransactionByID($transactionID);
    $customerID = $tData['customer_id'];
    $tInfo = $tenantsModel->getTenantsByID($customerID);
}
$tenantName = $tInfo[0]['first_name'] . ' ' . $tInfo[0]['last_name'];

$allTransactions = $paymentsModel->getAllPayments();
$tenants = $tenantsModel->getAllTenants();

// $customerIdsWithTransactions = array();
// foreach ($allTransactions as $transaction) {
//     $customerIdsWithTransactions[] = $transaction['customer_id'];
// }

// $customersWithoutPayments = array();
// foreach ($tenants as $tenant) {
//     if (!in_array($tenant['customer_id'], $customerIdsWithTransactions)) {
//         $customersWithoutPayments[] = $tenant;
//     }
// }
?>
<style>
    .hide {
        display: none;
    }
</style>
<div class="container-fluid">
    <form action="" id="manage-payment">
        <input type="hidden" name="transactionID" value="<?php echo $transactionID; ?>">
        <input type="hidden" name="tenantID" value="<?php echo $customerID; ?>">
        <div id="msg"></div>
        <div class="form-group">
            <label for="tenant_id" class="control-label">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2">
                <option value="<?php echo $tenantName; ?>" selected disabled readonly><?php echo $tenantName; ?>
                </option>
            </select>
        </div>
        <div class="form-group">
            <div class="form-group col-md-6">
                <label for="amounts">Amount Pay</label>
                <input type="text" class="form-control" id="amounts" name="amounts"
                    value="<?php echo $tData['amount']; ?>" required>

                <div class="invalid-feedback">
                    Please enter a valid amount to pay.
                </div>
            </div>

            <div class="form-group col-md-6">
                <label for="datepicker">Date</label>
                <input id="datepicker" name="datepicker" width="276" value="<?php echo $tData['date_transaction'] ?>" />
                <div class="invalid-feedback">
                    Please enter a valid date.
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-primary">
        <span class="loading spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
        <span class="btn-text">Add</span>
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    function validateAmounts(input) {
        var amounts = input.value;
        if (isNaN(amounts) || amounts <= 0) {
            input.classList.add('is-invalid');
            return false;
        } else {
            input.classList.remove('is-invalid');
            return true;
        }
    }

    document.getElementById('amounts').addEventListener('input', function (event) {
        validateAmounts(event.target);
    });

    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
    });

    $(document).on('click', '.btn-primary', function () {
        var tenantID = $('input[name="tenantID"]').val();
        var transactionID = $('input[name="transactionID"]').val();
        var amountsInput = document.getElementById('amounts');
        var $datepicker = $('#datepicker').datepicker();
        var getDate = $datepicker.val();
        var parts = getDate.split('/');
        var dateInput = parts[2] + '-' + parts[0] + '-' + parts[1];
        var amountsValid = validateAmounts(amountsInput);
        // console.log("tenantID:", tenantID);
        // console.log("amountsValid:", amountsValid);
        // console.log("getDate:", getDate);
        // console.log("transactionID:", transactionID);

        if (!tenantID || !amountsValid || !getDate || getDate == undefined || !transactionID) {
            $('#msg').html('<div class="alert alert-danger">Please fill all the required fields correctly.</div>');
            return;
        }

        $('.btn-text').text('Please wait...');
        $.ajax({
            url: '../../server/app/actionsHandler.php?action=edit-transaction',
            type: 'post',
            data: {
                tenantID: tenantID,
                amounts: amountsInput.value,
                date: dateInput,
                transactionID: transactionID
            },
            success: function (response) {
                if (response == 0) {
                    $.toast({
                        heading: 'Success',
                        text: 'Update Payments Successfully!',
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
                        text: 'Error while changing. Please Try Again!',
                        showHideTransition: 'slide',
                        icon: 'error',
                        position: 'top-right',
                        stack: false
                    });
                    $('.btn-text').text('Add');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                $.toast({
                    heading: 'Error',
                    text: 'Server Error. Please Try Again!',
                    showHideTransition: 'slide',
                    icon: 'error',
                    position: 'top-right',
                    stack: false
                });
                $('.btn-text').text('Save');
            }
        });
    });
</script>