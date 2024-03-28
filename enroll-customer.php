<?php include 'layouts/main.php'; ?>

<head>

    <title><?php echo $language["Dashboard"]; ?> Kabuli</title>

    <?php include 'layouts/head.php'; ?>

    <?php include 'layouts/head-style.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?php
    session_start();
    require_once "layouts/config.php";
    $campaign_details;

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['campaign_details'])) {
        $customerData = new stdClass();
        $campaign_details = $_SESSION['campaign_details'];

        // Loop through each field in the form
        foreach ($campaign_details->fieldsToCollect as $field) {
            $fieldNameWithoutUnderscore = str_replace(' ', '_', $field->name);
            // Check if the field exists in the form submission
            if (isset($_POST[$fieldNameWithoutUnderscore])) {
                // Add the field data to the customerData object
                if ($field->type == "tel") {

                    $customerData->{$field->name} = "+966" . $_POST[$fieldNameWithoutUnderscore];
                } else {
                    $customerData->{$field->name} = $_POST[$fieldNameWithoutUnderscore];
                }
            } else {
                // If the field is not set in the form submission, add it to customerData with an empty value
                $customerData->{$fieldNameWithoutUnderscore} = "";
            }
        }
        $cid = $campaign_details->id;
        $customerDataJson = json_encode($customerData);

        $postFields = json_encode(array(
            "campaignId" => $cid,
            "customerData" => $customerData
        ));

        echo "<script>console.log('Post data:');</script>";
        echo "<script>console.log(" . json_encode($_POST) . ");</script>";

        echo "<script>console.log('After creating customer data:');</script>";
        echo "<script>console.log(" . json_encode($customerData) . ");</script>";

        echo "<script>console.log('Curl post fields:');</script>";
        echo "<script>console.log(" . $postFields . ");</script>";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.loopyloyalty.com/v1/enrol/$cid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                "campaignId" => $cid,
                "customerData" => $customerData
            )),
            CURLOPT_HTTPHEADER => array(
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
            if ($http_status == 201) {

                // Success - process the response
                $decoded_response = json_decode($response);

                if ($decoded_response === null) {
                    // JSON decoding error
                    $page_error =  "Error decoding JSON response";
                } else {

                    header("Location: " . $decoded_response->url);
                    exit(); // Ensure that further code is not executed
                }
                // You can parse and manipulate the response data here
            } else {
                header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . '?campaign_id=' . $campaign_details->id . '&enrolled=0&error=HTTP Error enrolling customer: ' . $http_status . '');
                exit(); // Ensure that further code is not executed
            }
        }

        // Perform further processing or validation as needed
        // For example, you can validate the data, sanitize it, or store it in a database
    }

    if (isset($_GET['campaign_id']) || isset($_SESSION['campaign_details'])) {

        $campaignId = isset($_GET['campaign_id']) ? $_GET['campaign_id'] : $_SESSION["campaign_details"]->id;
        $api = "https://api.loopyloyalty.com/v1/campaign/public/$campaignId";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
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
                    $_SESSION['campaign_details'] = $campaign_details;

                    echo "<script>console.log(" . json_encode($campaign_details) . ");</script>";
                }
                // You can parse and manipulate the response data here
            } else {
                // HTTP status code indicates an error
                $page_error =  "HTTP Error for Card Fetching: " . $http_status;
            }
        }
    } else {
        $page_error =  "No Campaign Id Found to fetch";
    }





    ?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content" style="margin:0px;">

        <div class="page-content">

            <?php
            if (!isset($page_error)) {

            ?>

                <div class="container-fluid w-50  d-flex flex-column align-items-center">
                    <?php


                    if (isset($_GET["enrolled"])) {
                        if ($_GET["enrolled"] == 1) {
                            $success_message = $language['card_updated_success_alert'];


                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-all me-2"></i>
                    ' . $success_message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                        } else {
                            $error = $_GET["error"];
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-all me-2"></i>
                ' . $error . '' . $_GET["error"] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                        }
                    }

                    ?>
                    <div class="row">
                        <div class="col-12">
                            <h2><?php echo $campaign_details->business->name ?></h2>
                        </div>
                    </div>
                    <?php
                    $post_url_current_with_id = htmlspecialchars($_SERVER["PHP_SELF"]);
                    ?>

                    <form class="needs-validation" novalidate action="<?php echo $post_url_current_with_id ?>" method="post">
                        <?php
                        foreach ($campaign_details->fieldsToCollect as $field) {
                            if (!isset($field->required)) {
                                $field->required = false;
                            }
                        ?>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <p class="text-muted fs-5 fw-bold mb-2"><?php echo $field->type; ?></p>
                                        <input maxLength="100" placeholder="<?php echo ($field->type == "tel") ? "0553608481" : (($field->type == "email") ? "name@gmail.com" : ""); ?>" <?php echo $field->required ? "required" : ""; ?> type="<?php echo $field->type; ?>" name="<?php echo $field->name; ?>" class="form-control" id="formrow-message-input" />
                                        <div class="invalid-feedback"><?php echo $language['form_input_invalid_feedback_required']; ?> </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if (!$campaign_details->disableTerms) {

                        ?>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card border border-primary">
                                        <div class="card-header bg-transparent border-primary">
                                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i><?php echo $language['terms_and_conditions_title'] ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <p><?php echo $campaign_details->terms; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input required class="form-check-input" type="checkbox" id="autoSizingCheck2">
                                        <label class="form-check-label" for="autoSizingCheck2">
                                            <?php echo $language["terms_and_conditions_agree"]; ?>
                                        </label>
                                        <div class="invalid-feedback"><?php echo $language['form_input_invalid_feedback_required']; ?> </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }

                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-4 mb-4">
                                    <button type="submit" class="btn btn-primary w-md"><?php echo $language['save_card_button']; ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div> <!-- container-fluid -->

            <?php } else {

                echo '<div class="alert alert-danger alert-dismissible fade show px-4 mb-0 text-center" role="alert">
<i class="mdi mdi-block-helper d-block display-4 mt-2 mb-3 text-danger"></i>
<h5 class="text-danger">' . $page_error . '</h5></div>';
            } ?>
        </div>



        <!-- End Page-content -->


    </div>
    <!-- end main content-->

</div>


</body>
<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {



            // Add an event listener to all select elements with the class form-select
            document.querySelectorAll('.form-select').forEach(function(select) {
                // Add change event listener to each select element
                select.addEventListener('change', function() {
                    // Get the closest input element with the class card-links
                    var input = this.closest('tr').querySelector('.card-links');

                    // Check the value of the selected option
                    switch (this.value) {
                        case 2: // If the selected value is '2' (email)
                            input.setAttribute('type', 'email'); // Change the input type to 'email'
                            break;
                        case 1: // If the selected value is '1' (phone)
                            input.setAttribute('type', 'tel'); // Change the input type to 'tel'
                            break;
                        case 0: // If the selected value is '0' (url)
                            input.setAttribute('type', 'url'); // Change the input type to 'url'
                            break;
                        default: // For any other value, change the input type to 'text'
                            input.setAttribute('type', 'text');
                    }
                });
            });



            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');

                }, false);
            });
        }, false);
    })();
</script>

</html>