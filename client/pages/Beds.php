<?php
include ('../../server/database/models/beds_model.php');
include ('../../server/database/models/tenants_model.php');
include ('../../server/database/models/rooms_model.php');
$roomModel = new RoomModel();
$tenantsModel = new TenantsModel();
$bedModel = new BedModel();
$roomId = $_GET['room_id'] ?? null;

if (isset($roomId)) { //If has RoomID then fetch the beds from that room
    try {
        $beds = $bedModel->getBedsByRoomId($roomId);
    } catch (Exception $e) {
        $error = 'Error fetching bed data: ' . $e->getMessage();
    }
} else { //If none then fetch allbeds
    try {
        $beds = $bedModel->getAllBeds();

    } catch (Exception $e) {
        $error = 'Error fetching bed data: ' . $e->getMessage();
    }
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
                        <b>List of Beds</b>
                    </div>
                    <?php include_once ('../includes/messageHandler.php'); ?>
                    <div class="table-responsive" style="height: 400px; width: 100%; overflow-y: auto;">
                        <table class="table table-bordered" style="width: =50%;">
                            <thead>
                                <tr class="text-center">
                                    <th>Bed ID</th>
                                    <th>Room Name</th>
                                    <th>Tenant</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Output all the beds that has been fetch -->
                                <?php foreach ($beds as $bed): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $bed['beds_id']; ?></td>
                                        <td class="text-center">
                                            <?php
                                            $rID = $bed['room_id'] ?? null;
                                            $roomData = $roomModel->getRoomByID($rID);
                                            echo $roomData['room_name'];
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if (isset($bed['customer_id'])) {
                                                $tID = $bed['customer_id'];
                                                $getData = $tenantsModel->getTenantsByID($tID);
                                                $getName = $getData[0];
                                                echo ucwords($getName['first_name'] . ' ' . $getName['last_name']);
                                            } else {
                                                echo 'Not Set';
                                            }
                                            ?>
                                        </td class="text-center">
                                        <td class="text-center">
                                            <?php if ($bed['customer_id']): ?>
                                                <!-- open the bed info modal -->
                                                <button class="btn btn-sm btn-outline-primary bed_info" type="button"
                                                    data-id="<?php echo $bed['customer_id']; ?>"> Info</button>
                                                <!-- open the remove modal -->
                                                <button class="btn btn-sm btn-outline-danger remove_tenant" type="button"
                                                    data-id="<?php echo $bed['beds_id']; ?>"> Remove</button>
                                            <?php else: ?>
                                                <!-- open the add modal -->
                                                <button class="btn btn-sm btn-secondary assign_tenant" type="button"
                                                    data-id="<?php echo $bed['beds_id']; ?>">Add</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <!-- end of the loop -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <script type="text/javascript">
                    // bed modal tigger
                    $(document).on('click', '.bed_info', function () {
                        var tenantID = $(this).data('id');
                        $.ajax({
                            url: '../sub-pages/manage_info_bed.php',
                            type: 'post',
                            data: { tenantID: tenantID },
                            success: function (response) {
                                $('.modal-body').html(response);
                                $('.modal-title').text('Bed Info');
                                $('#empModal').modal('show');
                            }
                        });
                    });
                    // add modal trigger
                    $(document).on('click', '.assign_tenant', function () {
                        var bedID = $(this).data('id');
                        $.ajax({
                            url: '../sub-pages/manage_assign_bed.php',
                            type: 'post',
                            data: { bedID: bedID },
                            success: function (response) {
                                $('.modal-body').html(response);
                                $('.modal-title').text('Assign Tenant');
                                $('#empModal').modal('show');
                            }
                        });
                    });
                    // remove modal trigger
                    $(document).on('click', '.remove_tenant', function () {
                        var bedID = $(this).data('id');
                        console.log(bedID);
                        $.ajax({
                            url: '../sub-pages/manage_removeTenant_bed.php',
                            type: 'post',
                            data: { bedID: bedID },
                            success: function (response) {
                                $('.modal-body').html(response);
                                $('.modal-title').text('Assign Tenant');
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
    </div>
</body>

</html>