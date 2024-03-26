<?php include 'layouts/session.php';
?>
<?php include 'layouts/main.php'; ?>

<head>

    <title><?php echo $language["Dashboard"]; ?> Kabuli</title>

    <?php include 'layouts/head.php'; ?>
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <?php include 'layouts/head-style.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?php include 'layouts/menu.php'; ?>
    <?php

require_once "layouts/config.php";

// Replace 'YOUR_TABLE_NAME' with the actual table name where user data is stored
$tableName = 'users';

// Get the user's ID from the session
$page_error;
$total_stamps;
$total_users;
$total_rewards;
$rewards_used;
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    // Fetch user data from the database based on the user's ID
    $sql = "SELECT * FROM $tableName WHERE id = $userId";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        
        // API key and campaign ID from user data
        $loopyToken = $userData['loopy_token'];
        $campaignId = $userData['loopy_card_id'];

        $_SESSION["loopy_token"] = $userData['loopy_token'];
        $_SESSION["loopy_card_id"] = $userData['loopy_card_id'];

        $campaign_sql = "SELECT * FROM campaigns WHERE campaign_id = '$campaignId'";
        $campaign_result = mysqli_query($link, $campaign_sql);
        ?>
    <script>
        console.log("<?php echo $campaignId; ?>");
        console.log("<?php echo $loopyToken; ?>");
        console.log("<?php echo $campaign_sql; ?>");
    </script>
        <?php
        if ($campaign_result && mysqli_num_rows($campaign_result) > 0) {
            $campaignData = mysqli_fetch_assoc($campaign_result);
            
            // API key and campaign ID from user data
            $total_stamps = $campaignData['total_stamps'];
            $total_users = $campaignData['total_users'];
            $total_rewards = $campaignData['total_rewards'];
            $rewards_used = $campaignData['total_rewards_used'];

        } else {
            $page_error =  "Campaign not found";
        }
    

    } else {
        $page_error =  "User not found in the database";
    }

} else {
    $page_error =  "User ID not found in the session.";
}

// Close the database connection


?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content" >

        <div class="page-content">
            <?php  if(!isset($page_error)){
                ?>
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18"><?php echo $language["Dashboard"]; ?></h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo $language["Dashboard"];; ?></a></li>
                                    <!-- <li class="breadcrumb-item active"><?php echo $title; ?></li> -->
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate"><?php echo $language["number_of_total_points_given"]; ?></span>
                                        <h4 class="mb-3">
                                            <span class="fs-10" ><?php echo $total_stamps ?></span>
                                        </h4>
                                        
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate"><?php echo $language["number_of_clients_signed_up"]; ?></span>
                                        <h4 class="mb-3">
                                        <span class="fs-10" ><?php echo $total_users ?></span>
                                        </h4>
                                        
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate"><?php echo $language["number_of_rewards_given"]; ?></span>
                                        <h4 class="mb-3">
                                        <span class="fs-10" ><?php echo $total_rewards ?></span>
                                        </h4>
                                        
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate"><?php echo $language["number_of_rewards_used"]; ?></span>
                                        <h4 class="mb-3">
                                        <span class="fs-10" ><?php echo $rewards_used ?></span>
                                        </h4>
                                        
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>

                <div class="row">
                <div class="col-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1"><?php echo $language["transactions"]; ?></h4>
                               
                            </div><!-- end card header -->

                            <div class="card-body">
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>
                                                <?php echo $language['users_details_table_text']; ?>
                                                </th>
                                                <th>
                                                <?php echo $language['user'] ?>
                                                </th>
                                                <th>
                                                <?php echo $language['users_details_table_quantity'] ?>
                                                </th>
                                                <th>
                                                <?php echo $language['authorizer']; ?>
                                                </th>
                                                <th>
                                                <?php echo $language['users_details_table_date']; ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                         
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                </div>
            </div> <!-- container-fluid -->

        </div>

        
        <?php }else{

            echo '<div class="alert alert-danger alert-dismissible fade show px-4 mb-0 text-center" role="alert">
            <i class="mdi mdi-block-helper d-block display-4 mt-2 mb-3 text-danger"></i>
            <h5 class="text-danger">'. $page_error .'</h5></div>';

            } ?>
        <!-- End Page-content -->

        <?php include 'layouts/footer.php'; ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?php include 'layouts/right-sidebar.php'; ?>

<?php include 'layouts/vendor-scripts.php'; ?>
<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="assets/js/pages/allchart.js"></script>
<!-- Plugins js-->
<script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

<!-- Required datatable js -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/libs/jszip/jszip.min.js"></script>
<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<!-- Responsive examples -->
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>


<!-- Datatable init js -->
<script>
    $(document).ready(function() {
        var currentLanguage = localStorage.getItem('Dason-language') == null ? "ar" : localStorage.getItem('Dason-language');

        function initDataTable() {
          
            var dataTableMods = {
            "lengthChange": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "ajax": {
                "url": "https://api.loopyloyalty.com/v1/events/analytics/transactions/<?php echo $campaignId; ?>",
                "type": "POST",
                "contentType": "application/json",
                "headers": {
                    "Authorization": "<?php echo $loopyToken; ?>"
                    },
                "data":  function(params) {
                    // Calculate page number based on current start position and page length
                    var page = Math.floor(params.start / params.length) + 1;

                    // Construct the request body
                    var requestBody = {
                        "draw": params.draw,
                        "start": params.start,
                        "length": params.length,
                        "search": params.search.value,
                        "order": {
                            "column": "created",
                            "dir": "desc"
                        }
                    }
                    return JSON.stringify(requestBody);
                }
            },
            "columns": [
                { 
                    "data": function(row) {
                        // Access the first value of customerDetails
                        var type_of_event;
                        switch(row.type) {
                            case 0:
                                type_of_event = <?php echo json_encode($language['event_type_enrolled']); ?>;
                                break;
                            case 1:
                                type_of_event = <?php echo json_encode($language['event_type_stamp']); ?>;
                                break;
                            case 2:
                                type_of_event = <?php echo json_encode($language['event_type_recieved_reward']); ?>;
                                break;
                            case 3:
                                type_of_event = <?php echo json_encode($language['event_type_radeemed_reward']); ?>;
                                break;
                            case 4:
                                type_of_event = <?php echo json_encode($language['event_type_tap_link']); ?>;
                                break;
                            case 5:
                                type_of_event = <?php echo json_encode($language['event_type_wallet_register']); ?>;
                                break;
                            case 6:
                                type_of_event = <?php echo json_encode($language['event_type_wallet_deregister']); ?>;
                                break;
                            case 7:
                                type_of_event = <?php echo json_encode($language['event_type_wallet_register']); ?>;
                                break;
                            case 8:
                                type_of_event = <?php echo json_encode($language['event_type_deleted_card']); ?>;
                                break;
                            case 9:
                                type_of_event = <?php echo json_encode($language['event_type_forfeit_reward']); ?>;
                                break;
                        }
                        return type_of_event;
                    }
                },
                { 
                    "data": function(row) {
                        // Access the second value of customerDetails
                        return '<a href="user-details.php?id='+row["card_id"] +'"><?php echo $language["user"] ?></a>';
                    }
                },
                { "data": "quantity" },
                { "data": "username"},
                { "data": function(row){
                    var date = new Date(row.created);
                    var formattedDate = date.toISOString().slice(0, 19).replace('T', ' '); // Format date as 'YYYY-MM-DD HH:MM:SS'
                    return formattedDate;
                }
            }

                
            ],
            "dom": 'lBfrtip', // Specify the container for buttons
            "buttons": ['copy', 'excel', 'colvis']
            };           
        
            // Conditionally include the language URL only if the language is Arabic
            if (currentLanguage === 'ar') {
                dataTableMods.language = {
                    url: `https://cdn.datatables.net/plug-ins/1.11.5/i18n/${currentLanguage}.json`
                };
            }

            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
                $('#datatable-buttons').DataTable().destroy();
            }
            // console.log(dataTableMods);
            var table = $('#datatable-buttons').DataTable(dataTableMods);

            table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            }

        initDataTable();

        // Listen for changes in localStorage
        window.addEventListener('storage', function(e) {
            console.log("localstorage change triggered");
            if (e.key === 'Dason-language') {
                currentLanguage = e.newValue || 'en';
                initDataTable(); // Re-initialize DataTable with the new language
            }
        });

        $(".dataTables_length select").addClass('form-select form-select-sm');
       
    });
</script>


</body>

</html>