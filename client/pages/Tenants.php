<?php
session_start();
include_once ('../../server/database/models/tenants_model.php');

$tenantsModel = new TenantsModel();

$error = "";

try {
    $tenants = $tenantsModel->getAllTenants();
} catch (Exception $e) {
    $error = 'Error fetching tenants data: ' . $e->getMessage();
}
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
                        <b>List of Tenant</b>
                        <span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right"
                                href="javascript:void(0)" id="new_tenant">
                                <i class="fa fa-plus"></i> New Tenant
                            </a></span>
                    </div>
                    <?php include_once ('../includes/messageHandler.php'); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Contact Number</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tenants as $tenant): ?>
                                    <tr>
                                        <td><?php echo $tenant['customer_id']; ?></td>
                                        <td><?php echo $tenant['first_name']; ?></td>
                                        <td><?php echo $tenant['last_name']; ?></td>
                                        <td><?php echo $tenant['contact_number']; ?></td>
                                        <td><?php echo $tenant['gender']; ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary edit_tenant" type="button"
                                                data-id="<?php echo $tenant['customer_id']; ?>"> Edit</button>
                                            <button class="btn btn-sm btn-outline-danger delete_tenant" type="button"
                                                data-id="<?php echo $tenant['customer_id']; ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).on('click', '.edit_tenant', function () {
                        var tenantsID = $(this).data('id');
                        $.ajax({
                            url: '../sub-pages/manage_edit_tenant.php',
                            type: 'post',
                            data: { tenantsID: tenantsID },
                            success: function (response) {
                                $('.modal-body').html(response);
                                $('.modal-title').text('Update Info');
                                $('#empModal').modal('show');
                            }
                        });
                    });

                    $(document).on('click', '.delete_tenant', function () {
                        var tenantsID = $(this).data('id');
                        console.log(tenantsID);
                        $.ajax({
                            url: '../sub-pages/manage_delete_tenant.php',
                            type: 'post',
                            data: { tenantsID: tenantsID },
                            success: function (response) {
                                $('.modal-body').html(response);
                                $('.modal-title').text('Confirmation');
                                $('#empModal').modal('show');
                            }
                        });
                    });

                    $(document).on('click', '#new_tenant', function () {
                        var tenantsID = $(this).data('id');
                        console.log(tenantsID);
                        $.ajax({
                            url: '../sub-pages/manage_add_tenant.php',
                            type: 'post',
                            data: { tenantsID: tenantsID },
                            success: function (response) {
                                $('.modal-body').html(response);
                                $('.modal-title').text('New Tenant');
                                $('#empModal').modal('show');
                            }
                        });
                    });
                </script>

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
    </div>
</body>

</html>