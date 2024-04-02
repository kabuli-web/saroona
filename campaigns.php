<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>

<head>

    <title> Kabuli</title>

    <?php include 'layouts/head.php'; ?>
    <!-- swiper css -->
    <link rel="stylesheet" href="assets/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/iphone.css">
    <style>
        .vertical-menu {
            display: none;
        }
    </style>

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

    $campaigns = array();
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
        // Fetch user data from the database based on the user's ID
        $sql = "SELECT * FROM $tableName WHERE id = $userId";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $userData = mysqli_fetch_assoc($result);

            // API key and campaign ID from user data
            $loopyToken = $userData['loopy_token'];
            $_SESSION["loopy_token"] = $userData['loopy_token'];

            $campaign_sql = "SELECT * FROM campaigns WHERE user = '$userId'";
            $campaign_result = mysqli_query($link, $campaign_sql);

             // Initialize an empty array to store all campaign objects

            if ($campaign_result && mysqli_num_rows($campaign_result) > 0) {
                // Fetch each row as an associative array and store it in the $campaigns array
                while ($row = mysqli_fetch_assoc($campaign_result)) {
                    $campaigns[] = $row;
                }
            } else {
                $page_error =  "Campaigns not found";
            }
        } else {
            $page_error =  "User not found in the database";
        }
    } else {
        $page_error =  "User ID not found in the session.";
    }

    // Close the database connection


    ?>
    <div class="my-5 pt-5">
        <div class="container">
            <?php
            if (!isset($page_error)) {
            ?>
                <div class="row">
                    <?php
                    foreach ($campaigns as $campaign) {
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.loopyloyalty.com/v1/campaign/id/' . $campaign["campaign_id"],
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: ' . $loopyToken
                            ),
                        ));

                        $campaign_details = curl_exec($curl);
                        $campaign_details_decoded = json_decode($campaign_details);
                        $stripeTemplate = json_encode($campaign_details_decoded->baseStripImage);
                        curl_close($curl);

                        // Encode the decoded JSON data as URL-encoded format
                        $urlEncodedJson = http_build_query(["json" => $stripeTemplate]);
                    ?>
                        <div class="col-3 d-flex flex-column align-items-center">
                            <div class="iphone-container">
                                <img class="iphone-image" src="assets/images/iphone.png" alt="">
                                <div class="wallet-card" style="background-color: <?php echo $campaign_details_decoded->design->backgroundColor; ?>;">
                                    <div class="logo-container">
                                        <img src="https://s3.amazonaws.com/passkit-api-core-production/<?php echo $campaign_details_decoded->design->logoImageId ?>" alt="">
                                    </div>
                                    <img src="https://api.loopyloyalty.com/v1/images/?<?php echo $urlEncodedJson; ?>" class="stripe-image" alt="">

                                    <div class="body-container">
                                        <div class="stamps-details">
                                            <p style="color: <?php echo $campaign_details_decoded->design->textColor; ?>;"><?php echo $campaign_details_decoded->customFrontFields[0]->label ?> </p>
                                            <p style="color: <?php echo $campaign_details_decoded->design->textColor; ?>;">3</p>
                                        </div>
                                    </div>
                                    <div class="foot-container">
                                        <img src="assets/images/barcode.png" alt="">
                                    </div>
                                </div>
                            </div>
                            <a class="pt-2" href="index.php?campaign_id=<?php echo $campaign_details_decoded->id ?>"><button type="button" class="btn btn-primary waves-effect waves-light"><?php echo $language["manage_card"] ?></button></a>
                        </div>

                    <?php
                    }
                    ?>
                </div>
            <?php } else {

                echo '<div class="alert alert-danger alert-dismissible fade show px-4 mb-0 text-center" role="alert">
    <i class="mdi mdi-block-helper d-block display-4 mt-2 mb-3 text-danger"></i>
    <h5 class="text-danger">' . $page_error . '</h5></div>';
            } ?>
        </div>
        <!-- end container -->
    </div>
    <!-- end content -->
    <?php include 'layouts/right-sidebar.php'; ?>
    <?php include 'layouts/vendor-scripts.php'; ?>
    <!-- swiper js -->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <!-- Plugins js-->
    <script src="assets/libs/jquery-countdown/jquery.countdown.min.js"></script>

    </body>

    </html>