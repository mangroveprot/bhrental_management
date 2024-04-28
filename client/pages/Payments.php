<?php
include ('../../server/database/models/payments_model.php');
include ('../../server/database/models/tenants_model.php');
$tenantsModel = new TenantsModel();
$paymentsModel = new PaymentsModel();
$allTransactions = $paymentsModel->getAllPayments();

//print_r(json_encode($allTransactions));
?>
<?php include_once ('../includes/header.php'); ?>

<body>
    <div class="header">
        <h1>Boarding House Rental Management System</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <?php include_once ('../includes/sidebar.php'); ?>
        </div>
        <div class="content_wrapper">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Payments</b>
                        <span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right"
                                href="javascript:void(0)" id="new_transaction">
                                <i class="fa fa-plus"></i> New Transaction
                            </a></span>
                    </div>
                    <?php include_once ('../includes/messageHandler.php'); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Invoice</th>
                                    <th>Tenant</th>
                                    <th>Amount</th>
                                    <th> Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allTransactions as $allTransaction): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $allTransaction['transaction_id']; ?></td>
                                        <td class="text-center">
                                            <?php
                                            if (isset($allTransaction['customer_id'])) {
                                                $tID = $allTransaction['customer_id'];
                                                $getData = $tenantsModel->getTenantsByID($tID);
                                                $getName = $getData[0];
                                                echo ucwords($getName['first_name'] . ' ' . $getName['last_name']);
                                            } else {
                                                echo 'Not Set';
                                            }
                                            ?>
                                        </td class="text-center">
                                        <td class="text-center">₱<?php echo $allTransaction['amount']; ?>.00</td>
                                        <td class="text-center"><?php echo $allTransaction['date_transaction']; ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary edit_payments" type="button"
                                                data-id="<?php echo $allTransaction['transaction_id']; ?>">
                                                Edit</button>
                                            <button class="btn btn-sm btn-outline-danger delete_payments" type="button"
                                                data-id="<?php echo $allTransaction['transaction_id']; ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <script>

                $(document).on('click', '#new_transaction', function () {
                    $.ajax({
                        url: '../sub-pages/manage_new_transaction.php',
                        type: 'post',
                        success: function (response) {
                            $('.modal-body').html(response);
                            $('.modal-title').text('New Transaction');
                            $('#empModal').modal('show');
                        }
                    });
                });
                $(document).on('click', '.delete_payments', function () {
                    var transaction = $(this).data('id');
                    $.ajax({
                        url: '../sub-pages/manage_delete_transaction.php',
                        type: 'post',
                        data: { transaction: transaction },
                        success: function (response) {
                            $('.modal-body').html(response);
                            $('.modal-title').text('');
                            $('#empModal').modal('show');
                        }
                    });
                });
                $(document).on('click', '.edit_payments', function () {
                    var transactionID = $(this).data('id');
                    $.ajax({
                        url: '../sub-pages/manage_edit_transaction.php',
                        type: 'post',
                        data: { transactionID: transactionID },
                        success: function (response) {
                            $('.modal-body').html(response);
                            $('.modal-title').text('');
                            $('#empModal').modal('show');
                        }
                    });
                });
            </script>

            <!-- Modal -->
            <div class="modal fade" id="empModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>