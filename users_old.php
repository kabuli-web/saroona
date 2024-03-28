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

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18"><?php echo $language["users"]; ?></h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="index.php"><?php echo $language["Dashboard"]; ?></a></li>
                                    <li class="breadcrumb-item"><a href="users.php"><?php echo $language["users"];; ?></a></li>
                                    <!-- <li class="breadcrumb-item active"><?php echo $title; ?></li> -->
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <?php
                if (isset($_GET["card_deleted"])) {
                    if ($_GET["card_deleted"] == 1) {
                        $card_deleted_success_message = $language['card_deleted_succesfully'];
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="mdi mdi-check-all me-2"></i>
                                                         ' . $card_deleted_success_message . '
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                    } else {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="mdi mdi-check-all me-2"></i>
                                                         لم نستطيع حذف العميل
                                                         We couldnt Delete the card
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                    }
                }
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- <div class="card-header">
                                <h4 class="card-title">Buttons example</h4>
                                <p class="card-title-desc">The Buttons extension for DataTables
                                    provides a common set of options, API methods and styling to display
                                    buttons on a page that will interact with a DataTable. The core library
                                    provides the based framework upon which plug-ins can built.
                                </p>
                            </div> -->


                            <?php

                            require_once "layouts/config.php";

                            // Replace 'YOUR_TABLE_NAME' with the actual table name where user data is stored
                            $tableName = 'users';
                            $page_error;
                            // Get the user's ID from the session
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
                                    // API endpoint URL
                                    $apiUrl = "https://api.loopyloyalty.com/v1/card/cid/{$campaignId}";

                                    $campaignId = $_SESSION['loopy_card_id'];
                                    $api = "https://api.loopyloyalty.com/v1/campaign/id/$campaignId";

                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => $api,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'GET',
                                        CURLOPT_HTTPHEADER => array(
                                            'Authorization: ' . $_SESSION["loopy_token"]
                                        ),
                                    ));

                                    $response = curl_exec($curl);

                                    curl_close($curl);
                                    if ($response === false) {

                                        // cURL error occurred
                                        $error = curl_error($curl);
                                        $page_error = "cURL Error: " . $error;
                                    } else {

                                        // cURL request was successful
                                        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                        if ($http_status == 200) {

                                            // Success - process the response
                                            $decoded_response = json_decode($response);
                                            if ($decoded_response === null) {
                                                // JSON decoding error
                                                $page_error =  "Error decoding JSON response";
                                            } else {
                                                // JSON decoded successfully, process the data

                                                $campaign_details = $decoded_response;
                                                $_SESSION["campaign_details"] = $campaign_details;

                                                // Debugging: Print the structure of $campaign_details after modification
                                                // echo "<script>console.log('After Modification:');</script>";
                                                // echo "<script>console.log(" . json_encode($campaign_details) . ");</script>";
                                            }
                                            // You can parse and manipulate the response data here
                                        } else {
                                            // HTTP status code indicates an error
                                            $page_error =  "HTTP Error for Card Fetching: " . $http_status;
                                        }
                                    }

                            ?>
                                    <?php if (!isset($page_error)) {
                                    ?>
                                        <div class="card-body">
                                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $language['users_table_name']; ?></th>
                                                        <th><?php echo $language['users_table_phone']; ?></th>
                                                        <th><?php echo $language['users_table_status']; ?></th>
                                                        <th><?php echo $language['users_table_stamps']; ?></th>
                                                        <th><?php echo $language['users_table_rewards_earned']; ?></th>
                                                        <th><?php echo $language['users_table_rewards_radeemed']; ?></th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>

                                    <?php } else {

                                        echo '<div class="alert alert-danger alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                                        <i class="mdi mdi-block-helper d-block display-4 mt-2 mb-3 text-danger"></i>
                                        <h5 class="text-danger">' . $page_error . '</h5></div>';
                                                                            } ?>

                            <?php

                                } else {
                                    echo "User not found in the database.";
                                }
                            } else {
                                echo "User ID not found in the session.";
                            }

                            // Close the database connection
                            mysqli_close($link);

                            ?>

                        </div>
                        <!-- end cardaa -->
                    </div> <!-- end col -->
                </div> <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include 'layouts/footer.php'; ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?php include 'layouts/right-sidebar.php'; ?>

<?php include 'layouts/vendor-scripts.php'; ?>

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
                    "url": "<?php echo $apiUrl; ?>",
                    "type": "POST",
                    "contentType": "application/json",
                    "headers": {
                        "Authorization": "<?php echo $loopyToken; ?>"
                    },
                    "data": function(params) {
                        // Calculate page number based on current start position and page length
                        var page = Math.floor(params.start / params.length) + 1;

                        // Construct the request body
                        var requestBody = {
                            "dt": {
                                "draw": params.draw,
                                "start": params.start,
                                "length": params.length,
                                "search": params.search.value,
                                "order": {
                                    "column": "created",
                                    "dir": "desc"
                                }
                            }
                        }
                        return JSON.stringify(requestBody);
                    }
                },
                "columns": [{
                        "data": function(row) {
                            // Access the first value of customerDetails
                            return row.customerDetails[Object.keys(row.customerDetails)[0]];
                        }
                    },
                    {
                        "data": function(row) {
                            // Access the second value of customerDetails
                            return row.customerDetails[Object.keys(row.customerDetails)[1]];
                        }
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "totalStampsEarned"
                    },
                    {
                        "data": "totalRewardsEarned"
                    },
                    {
                        "data": "totalRewardsRedeemed"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<a href="user-details.php?id=' + row["id"] + '"><button type="button" class="btn btn-primary waves-effect waves-light">' +
                                '<i class="bx bx-info-circle font-size-16 align-middle me-2"></i>' +
                                '<?php echo $language['users_table_view_user']; ?>' +
                                '</button> </a>';
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