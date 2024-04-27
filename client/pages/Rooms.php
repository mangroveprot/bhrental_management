<?php
if (!isset($_SESSION)) {
    session_start();
}

include ('../../server/database/models/rooms_model.php');
$roomModel = new RoomModel();
$error = "";
$rooms = [];
try {
    $rooms = $roomModel->getRooms();
} catch (Exception $e) {
    $error = 'Error: ' . $e->getMessage();
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
                        <b>List of Rooms</b>
                        <span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right"
                                href="javascript:void(0)" id="new_room">
                                <i class="fa fa-plus"></i> New Room
                            </a></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Room Name</th>
                                    <th>Room Price</th>
                                    <th>Availability</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rooms as $room): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $room['room_id']; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $room['room_name']; ?>
                                        </td>
                                        <td class="text-center">
                                            ₱<?php echo $room['room_price']; ?>.00
                                        </td>
                                        <td class="text-center">
                                            <?php echo $room['availability']; ?>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary show-beds" type="button"
                                                data-room-id="<?php echo $room['room_id']; ?>">Show Beds</button>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var showBedsButtons = document.querySelectorAll('.show-beds');

                            showBedsButtons.forEach(function (button) {
                                button.addEventListener('click', function () {
                                    var roomId = this.getAttribute('data-room-id');
                                    window.location.href = 'beds.php?room_id=' + roomId;
                                });
                            });
                        });

                        $(document).on('click', '#new_room', function () {
                            var tenantsID = $(this).data('id');
                            $.ajax({
                                url: '../sub-pages/manage_add_room.php',
                                success: function (response) {
                                    $('.modal-body').html(response);
                                    $('.modal-title').text('Create New Room');
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
    </div>
</body>

</html>