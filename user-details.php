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

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content" >
<!-- <script>
    console.log("<?php echo $_GET["id"] ?>")
</script> -->
        <div class="page-content">
            <?php 

                $page_error;
                $customer_details;
                if(isset($_GET['id']) && isset($_SESSION["loopy_token"])){
                    $clientId = $_GET['id'];
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.loopyloyalty.com/v1/card/$clientId?includeEvents=true",
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
                            $decoded_response = json_decode($response, true);
                            if ($decoded_response === null) {
                                // JSON decoding error
                                $page_error =  "Error decoding JSON response";
                            } else {
                                // JSON decoded successfully, process the data
                                $user_data = $decoded_response["card"];
                                $customer_details = array_values($user_data["customerDetails"]);
                            }
                            // You can parse and manipulate the response data here
                        } else {
                            // HTTP status code indicates an error
                            $page_error =  "HTTP Error: " . $http_status;
                        }
                    }
                    }else{
                            $page_error =  "No Token Or Card Id Found";
                }

                
                
                if ($_SERVER["REQUEST_METHOD"] == "POST"  ) {
                    if(isset($_POST["post_type"]) && $_POST["post_type"]=="send_message"){
                        ?>
                        <script>
                            console.log("<?php echo $_GET["id"]; ?>")
                            console.log("<?php echo $_POST["message"]; ?>")

                        </script> 
                        <?php

                        $message_sent_successfully=null;
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.loopyloyalty.com/v1/card/push',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                        "message": "'.$_POST["message"].'",
                        "cardID": "'.$_POST["id"].'"
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
                                    if($decoded_response["success"]){
                                         // Redirect to the same page to prevent form resubmission
                                        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $_GET["id"] . '&message_sent=1');
                                        exit(); // Ensure that further code is not executed
                                        
                                    }else{
                                        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $_GET["id"] . '&message_sent=0');
                                        exit(); // Ensure that further code is not executed;
                                    }
                                }
                                // You can parse and manipulate the response data here
                            } else {
                                // HTTP status code indicates an error
                                $page_error =  "HTTP Error: " . $http_status;
                            }
                        }
                     


                    }


                    if(isset($_POST["post_type"]) && $_POST["post_type"]=="add_points"){
                        ?>
                        <script>
                            console.log("<?php echo $_GET["id"]; ?>")
                            console.log("<?php echo $_POST["number_of_points"]; ?>")

                        </script> 
                        <?php

                        $points_added_successfully=null;
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.loopyloyalty.com/v1/card/cid/{$_POST['id']}/addStamps/{$_POST['number_of_points']}",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
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
                                    if($decoded_response["success"]){
                                         // Redirect to the same page to prevent form resubmission
                                        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $_GET["id"] . '&points_added=1');
                                        exit(); // Ensure that further code is not executed
                                    }else{
                                         // Redirect to the same page to prevent form resubmission
                                        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $_GET["id"] . '&points_added=0');
                                        exit(); // Ensure that further code is not executed
                                    }
                                }
                                // You can parse and manipulate the response data here
                            } else {
                                // HTTP status code indicates an error
                                $page_error =  "HTTP Error: " . $http_status;
                            }
                        }
                       
  
                        ?>
                        <script>
                            console.log("<?php echo $points_added_successfully ?>")
                         

                        </script> 
                        <?php

                    }

                }



                    if(!isset($page_error)){
                ?>
                      <style>
                        /* Basic styling for the loyalty card */
                        .loyalty-card {
                            width: 300px;
                            min-height: 140px;
                            padding: 2px;
                            background-color: <?php echo $user_data['stripImage']['backgroundColor'] ?>; /* Background color from the provided data */
                            display: flex;
                            align-items: center;

                        }

                        .stamps-container {
                            width:100%;
                            height:100%;
                            display:flex;
                            flex-direction:row;
                            justify-content: space-evenly;
                        }

                        .stamp {
                            width: 20%;
                            height: 40%;
                        }
                        .stamp img {
                            width: 100%;
                            height: 100%;
                        }
                    </style>  
                    <!-- container-fluid -->
                    <div class="container-fluid">
      
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $language["users"]; ?></h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="index.php"><?php echo $language["Dashboard"]; ?></a></li>
                                            <li class="breadcrumb-item"><a href="users.php"><?php echo $language["users"]; ?></a></li>
                                            <li class="breadcrumb-item"><?php echo $language["users_details"]; ?></a></li>
                                            <!-- <li class="breadcrumb-item active"><?php echo $title; ?></li> -->
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        
                                            <div class="d-flex align-items-center">
                                                    <!-- <div>
                                                        <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-lg rounded-circle img-thumbnail">
                                                    </div> -->
                                                    <div class="flex-1 ms-3">
                                                        <h5 class="font-size-15 mb-1"><a href="#" class="text-dark"><?php echo $customer_details[0]; ?></a></h5>
                                                    </div>
                                            </div>
                                            <div class="mt-3 pt-1">
                                                <a href="tel:<?php echo $customer_details[1]; ?>">
                                                <p class="text-muted mb-0"><i class="mdi mdi-phone font-size-15 align-middle pe-2 text-primary"></i>
                                                <?php echo $customer_details[1]; ?></p></a>
                                            </div>
                                            <div class="mt-3 pt-1">
                                                <a href="https://loyalty.is/<?php echo $user_data['id']; ?>">
                                                <p class="text-muted fs-md-5 mb-0"><i class="mdi mdi-card font-size-15 align-middle pe-2 text-primary"></i>رابط البطاقة:  <?php echo "https://loyalty.is/{$user_data['id']}" ?>  </p>
                                                </a>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-12 col-md-4 mb-3 mb-sm-0 d-flex justify-content-center align-items-start">
                                <div class="loyalty-card  rounded-2">
                                        <!-- Loyalty card background image -->
                                        <!-- Stamps section -->
                                            <div class="stamps-container">
                                                <?php
                                                $totalStamps = $user_data['stripImage']['totalStamps']; 
                                                $current_stamps = $user_data['totalStampsEarned'] - ($user_data['totalRewardsEarned'] * $totalStamps);
                                                  try{
                                                    if (!isset($user_data['stripImage']['unstampOpacity']) || !isset($user_data['stripImage']['unstampImageURL'])) {
                                                        throw new Exception("Error: Stamps data not available");
                                                    }
                                                      // Generate stamps based on the total stamps value
                                                      // Example value, replace this with your dynamic value
                                                      for ($i = 1; $i <= $totalStamps; $i++) {
                                                          if($i<=$current_stamps){
                                                              echo "<div class='stamp'><img src='{$user_data['stripImage']['stampImageURL']}'></div>";
                                                          }else{
                                                              echo "<div class='stamp'><img style='o: {$user_data['stripImage']['unstampOpacity']};' src='{$user_data['stripImage']['unstampImageURL']}'></div>";
                                                          }
                                                      }
                                                  }catch(Exception $e){
                                                    echo "<h3>عدد النقاط </h3></br>";
                                                    echo "<h3>$current_stamps</h3> / <h3>$totalStamps</h3>";
                                                  }
                                                ?>
                                            </div>
                                        </div>
                            </div>
                        <div class="user-tabs">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="nav nav-pills  flex-sm-row nav-justified" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
                                        <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" tabindex="-1">
                                        <i class="bx bx-message-rounded-dots d-block check-nav-icon mt-2 mb-1"></i>
                                            <p class="fw-bold mb-1 text-sm text-md-md text-lg-lg"><?php echo $language['users_details_tabs_message']; ?></p>
                                        </a>
                                        <a class="nav-link" id="v-pills-points-tab" data-bs-toggle="pill" href="#v-pills-points" role="tab" aria-controls="v-pills-points" aria-selected="false" tabindex="-1">
                                            <i class="bx bx-star d-block check-nav-icon mt-2 mb-1"></i>
                                            <p class="fw-bold mb-1"><?php echo $language['users_details_tabs_points']; ?></p>
                                        </a>
                                        <a class="nav-link active" id="v-pills-history-tab" data-bs-toggle="pill" href="#v-pills-history" role="tab" aria-controls="v-pills-history" aria-selected="true">
                                            <i class="bx bx-history d-block check-nav-icon mt-2 mb-1"></i>
                                            <p class="fw-bold mb-1"><?php echo $language['users_details_tabs_history']; ?></p>
                                        </a>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <?php 
                                                if(isset($_GET["message_sent"])){
                                                    if($_GET["message_sent"]==1){
                                                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="mdi mdi-check-all me-2"></i>
                                                         تم ارسال الرسالة
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                    }else{
                                                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="mdi mdi-check-all me-2"></i>
                                                         لم نستطيع ارسال الرساله
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                    }
                                                }
                                            ?>
                                            <?php 
                                                    if(isset($_GET["points_added"])){
                                                        if($_GET["points_added"]==1){
                                                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="mdi mdi-check-all me-2"></i>
                                                         تم اضافة النقاط
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                    }else{
                                                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <i class="mdi mdi-check-all me-2"></i>
                                                         لم نستطيع اضافة النقاط
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                    }
                                                }
                                            ?>
                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                                    <div>
                                                        <?php 
                                                            $post_url_current_with_id = htmlspecialchars($_SERVER["PHP_SELF"]) .'?id='.$_GET["id"];
                                                        ?>
                                                        <form method="post" action="<?php echo $post_url_current_with_id ?>">
                                                            <div class="mb-3">
                                                            <p class="text-muted fs-5 fw-bold mb-2"><?php echo $language['users_details_message_description']; ?></p>
                                                                <textarea type="text" name="message" style="height:100px;" class="form-control" id="formrow-message-input"> </textarea>
                                                            </div>
                                                            <div class="mt-4">
                                                                <button type="submit" class="btn btn-primary w-md"><?php echo $language['users_details_send_button']; ?></button>
                                                            </div>
                                                            <input type="hidden" name="post_type" value="send_message">
                                                            <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="v-pills-points" role="tabpanel" aria-labelledby="v-pills-points-tab">
                                                    <div>
                                                        
                                                    <form method="post" action="<?php echo $post_url_current_with_id ?>">
                                                        <div class="mb-3">
                                                        <p class="text-muted fs-5 fw-bold mb-2"><?php echo $language['users_details_points_description']; ?></p>
                                                            <input type="number" min="1" max="10" name="number_of_points" class="form-control" id="formrow-number_of_points-input">
                                                        </div>
                                                        <div class="mt-4">
                                                            <button type="submit" class="btn btn-primary w-md"><?php echo $language['users_details_add_button']; ?></button>
                                                        </div>
                                                        <input type="hidden" name="post_type" value="add_points">
                                                        <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
                                                    </form>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade active show" id="v-pills-history" role="tabpanel" aria-labelledby="v-pills-history-tab">
                                                    <div class="card shadow-none border mb-0">
                                                        <div class="card-body">
                                                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo $language['users_details_table_text']; ?></th>
                                                                <th><?php echo $language['users_details_table_quantity']; ?></th>
                                                                <th><?php echo $language['users_details_table_date']; ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                           if(isset($decoded_response['events'])){
                                                            foreach($decoded_response['events'] as $event){
                                                                $type_of_event;
                                                                switch($event['type']){
                                                                    case 0:
                                                                        $type_of_event = $language['event_type_enrolled'];
                                                                        break;
                                                                    case 1:
                                                                        $type_of_event = $language['event_type_stamp'];
                                                                        break;
                                                                    case 2:
                                                                        $type_of_event = $language['event_type_recieved_reward'];
                                                                        break;
                                                                    case 3:
                                                                        $type_of_event = $language['event_type_radeemed_reward'];
                                                                        break;
                                                                    case 4:
                                                                        $type_of_event = $language['event_type_tap_link'];
                                                                        break;
                                                                    case 5:
                                                                        $type_of_event = $language['event_type_wallet_register'];
                                                                        break;
                                                                    case 6:
                                                                        $type_of_event = $language['event_type_wallet_deregister'];
                                                                        break;
                                                                    case 7:
                                                                        $type_of_event = $language['event_type_wallet_register'];
                                                                        break;
                                                                    case 8:
                                                                        $type_of_event = $language['event_type_deleted_card'];
                                                                        break;
                                                                    case 9:
                                                                        $type_of_event = $language['event_type_forfeit_reward'];
                                                                        break;
                                                                }
                                                                $date = new DateTime($event['created']);
                                                                $formatedDate= $date->format('Y-m-d H:i:s');
                                                                echo "<tr>
                                                                <td>{$type_of_event}</td>
                                                                <td>{$event['quantity']}</td>
                                                                <td>{$formatedDate}</td>
                                                                </tr>";
                                                            }
                                                           }

                                                            ?>
                                                        </tbody>
                                                    </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                    </div> <!-- container-fluid -->

                <?php }else{

                    echo '<div class="alert alert-danger alert-dismissible fade show px-4 mb-0 text-center" role="alert">
                    <i class="mdi mdi-block-helper d-block display-4 mt-2 mb-3 text-danger"></i>
                    <h5 class="text-danger">'. $page_error .'</h5></div>';

                } ?>

            </div>
            <!-- End Page-content -->

                    <?php include 'layouts/footer.php'; ?>
                    </div>
                    <!-- end main content-->

                    </div>
<!-- END layout-wrapper -->

<?php include 'layouts/right-sidebar.php'; ?>

<?php include 'layouts/vendor-scripts.php'; ?>
<!-- apexcharts -->
<!-- <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="assets/js/pages/allchart.js"></script> -->
<!-- Plugins js-->
<!-- <script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script> -->
<!-- dashboard init -->
<!-- <script src="assets/js/pages/dashboard.init.js"></script> -->

</body>
</html>
<?php ob_end_flush(); ?>