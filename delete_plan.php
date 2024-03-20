
<?php
error_reporting(0);
ob_start();
session_start();

if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SESSION['Role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}
?>





<!DOCTYPE html>
<html>
<head>
    <title>Delete Plan</title>
    <link rel="stylesheet" type="text/css" href="css/Users.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function(){
            $('button.delete-btn').click(function(){
                var id = $(this).data('id');
                var row = $(this).closest('tr');
                
                // Use SweetAlert for confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This record will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'delete_membership.php',
                            data: { id: id },
                            success: function(response) {
                                console.log(response); // Log the response for debugging

                                if(response.status === 'success') {
                                    row.remove();
                                    Swal.fire('Deleted!', 'The record has been deleted.', 'success');
                                } else {
                                    Swal.fire('Deletion failed!', 'An error occurred during deletion.', 'error');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error(textStatus, errorThrown); // Log the error for debugging
                                Swal.fire('An error occurred!', 'Please check the console for details.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(window).on("load resize ", function() {
            var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
            $('.tbl-header').css({'padding-right':scrollWidth});
        }).resize();
    </script>
    <style>
         .delete-btn {
        background-color: #cc3333;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: #ff4d4d; /* Change color on hover */
    }
    </style>
</head>
<body style="background-image: url('images/pic12.jpg'); background-size: cover; background-position: center;">
    <?php include 'header.php'; ?> 
    <main>
        <section>
            <!-- User table -->
            <h1 class="slideInDown animated" style="color: white; font-weight: bold;">Existing Plans Details</h1>
            <div class="tbl-header slideInRight animated">
                <table cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>Plan ID</th>
                            <th>Membership Name</th>
                            <th>Installment Plan Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="tbl-content slideInRight animated">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <?php
                            // Connect to database
                            require 'connectDB.php';

                            $sql = "SELECT * FROM payment_plans ORDER BY plan_id DESC";
                            $result = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($result, $sql)) {
                                echo '<p class="error">SQL Error</p>';
                            } else {
                                mysqli_stmt_execute($result);
                                $resultl = mysqli_stmt_get_result($result);
                                if (mysqli_num_rows($resultl) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultl)) {
                        ?>
                                        <tr>
                                            <td><?php echo $row['plan_id']; ?></td>
                                            <td><?php echo $row['membership_name']; ?></td>
                                            <td><?php echo $row['installment_plan']; ?></td>
                                            <td>
                                                <button class="delete-btn" data-id="<?php echo $row['plan_id']; ?>" style="background-color: #cc3333;">Delete</button>
                                            </td>
                                        </tr>
                        <?php
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
