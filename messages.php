<?php ob_start(); ?>
<?php include 'layouts/session.php';
?>
<?php include 'layouts/main.php'; ?>

<head>

    <title><?php echo $language["Dashboard"]; ?> Kabuli</title>

    <?php include 'layouts/head.php'; ?>
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


    $page_error;
    $customer_details;
    // Get the user's ID from the session
    if (!isset($_SESSION['loopy_token']) || !isset($_SESSION['loopy_token'])) {
        $userId = $_SESSION['id'];
        // Fetch user data from the database based on the user's ID
        $sql = "SELECT * FROM $tableName WHERE id = $userId";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $userData = mysqli_fetch_assoc($result);

            // API key and campaign ID from user data
            $loopyToken = $userData['loopy_token'];
            $campaignId = $_GET['campaign_id'];

            $_SESSION["loopy_token"] = $userData['loopy_token'];
        } else {
            $page_error =  "User not found in the database.";
        }
    }

    // Close the database connection
    mysqli_close($link);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["post_type"]) && $_POST["post_type"] == "send_message") {


            $message_sent_successfully = null;
            $campaignId = $_GET['campaign_id'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.loopyloyalty.com/v1/card/cid/$campaignId/push",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
        "message": "' . $_POST["message"] . '",
        "cid": "' . $campaignId . '"
        }',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $_SESSION["loopy_token"],
                    'Content-Type: application/json'
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
                    $decoded_response = json_decode($response, true);
                    if ($decoded_response === null) {
                        // JSON decoding error
                        $page_error =  "Error decoding JSON response";
                    } else {
                        // JSON decoded successfully, process the data
                        if ($decoded_response["success"]) {
                            // Redirect to the same page to prevent form resubmission
                            header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . '?campaign_id=' . $campaign_id . '&message_sent=1');
                            exit(); // Ensure that further code is not executed

                        } else {
                            header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . '?campaign_id=' . $campaign_id . '&message_sent=0');
                            exit(); // Ensure that further code is not executed;
                        }
                    }
                    // You can parse and manipulate the response data here
                } else {
                    $error_ = json_decode($response);
                    $page_error =  "HTTP Error: " . $http_status . json_encode($error_);
                }
            }
        }
    }

    ?>
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
                            <h4 class="mb-sm-0 font-size-18"><?php echo $language["messages"]; ?></h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="index.php"><?php echo $language["Dashboard"];; ?></a></li>
                                    <li class="breadcrumb-item"><a href="messages.php"><?php echo $language["messages"];; ?></a></li>
                                    <!-- <li class="breadcrumb-item active"><?php echo $title; ?></li> -->
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <?php if (!isset($page_error)) {
                ?>
                    <div class="row">
                        <div class="col-12">
                            <?php
                            if (isset($_GET["message_sent"])) {
                                if ($_GET["message_sent"] == 1) {
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-check-all me-2"></i>
                                        تم ارسال الرسالة
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                                } else {
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-check-all me-2"></i>
                                        لم نستطيع ارسال الرساله
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                                }
                            }
                            ?>
                            <div>
                                <?php
                                $post_url_current_with_id = htmlspecialchars($_SERVER["PHP_SELF"]);
                                ?>
                                <form method="post" action="<?php echo $post_url_current_with_id . '?campaign_id=' . $campaign_id; ?>">
                                    <div class="mb-3">
                                        <p class="text-muted fs-5 fw-bold mb-2"><?php echo $language['users_details_message_description']; ?></p>
                                        <textarea type="text" name="message" style="height:100px;" class="form-control" id="formrow-message-input"></textarea>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary w-md"><?php echo $language['users_details_send_button']; ?></button>
                                    </div>
                                    <input type="hidden" name="post_type" value="send_message">
                                </form>
                            </div>
                        </div>
                    </div>

                <?php } else {

                    echo '<div class="alert alert-danger alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                    <i class="mdi mdi-block-helper d-block display-4 mt-2 mb-3 text-danger"></i>
                    <h5 class="text-danger">' . $page_error . '</h5></div>';
                } ?>
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


</body>

</html>
<?php ob_end_flush(); ?>