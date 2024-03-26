 

<div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <!-- <li class="menu-title" data-key="t-menu"><?php echo $language['Menu'] ?> </li> -->

                        <li>
                            <a href="index.php">
                                <i data-feather="home"></i>
                                <!-- <span class="badge rounded-pill bg-success-subtle text-success float-end">9+</span> -->
                                <span data-key="t-dashboard"><?php echo $language['Dashboard'] ?> </span>
                            </a>
                        </li>

                        <li>
                            <a href="insights.php">
                                <i data-feather="trending-up"></i>
                                <!-- <span class="badge rounded-pill bg-success-subtle text-success float-end">9+</span> -->
                                <span data-key="t-insights"><?php echo $language['insights'] ?> </span>
                            </a>
                        </li>

                        <li>
                            <a href="users.php">
                                <i data-feather="users"></i>
                                <!-- <span class="badge rounded-pill bg-success-subtle text-success float-end">9+</span> -->
                                <span data-key="t-users"><?php echo $language['users'] ?> </span>
                            </a>
                        </li>

                        <li>
                            <a href="messages.php">
                                <i data-feather="message-square"></i>
                                <!-- <span class="badge rounded-pill bg-success-subtle text-success float-end">9+</span> -->
                                <span data-key="t-messages"><?php echo $language['messages'] ?> </span>
                            </a>
                        </li>

                        <li>
                            <a href="edit-card.php">
                                <i data-feather="map"></i>
                                <!-- <span class="badge rounded-pill bg-success-subtle text-success float-end">9+</span> -->
                                <span data-key="t-map"><?php echo $language['edit_card'] ?> </span>
                            </a>
                        </li>

                       
                        <!-- <li class="menu-title" data-key="t-pages"><?php echo $language['Pages'] ?> </li> -->
<!-- 
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="layers"></i>
                                <span data-key="t-authentication"><?php echo $language['Authentication'] ?> </span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="auth-login.php" data-key="t-login"><?php echo $language['Login'] ?> </a></li>
                                <li><a href="auth-register.php" data-key="t-register"><?php echo $language['Register'] ?> </a></li>
                                <li><a href="auth-recoverpw.php" data-key="t-recover-password"><?php echo $language['Recover_Password'] ?> </a></li>
                                <li><a href="auth-lock-screen.php" data-key="t-lock-screen"><?php echo $language['Lock_Screen'] ?>
                                    </a></li>
                                <li><a href="auth-logout.php" data-key="t-logout"><?php echo $language['Logout'] ?> </a></li>
                                <li><a href="auth-confirm-mail.php" data-key="t-confirm-mail"><?php echo $language['Confirm_Mail'] ?> </a></li>
                                <li><a href="auth-email-verification.php" data-key="t-email-verification"><?php echo $language['Email_Verification'] ?> </a></li>
                                <li><a href="auth-two-step-verification.php" data-key="t-two-step-verification"><?php echo $language['Two_Step_Verification'] ?> </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="file-text"></i>
                                <span data-key="t-pages"><?php echo $language['Pages'] ?> </span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="pages-starter.php" data-key="t-starter-page"><?php echo $language['Starter_Page'] ?> </a></li>
                                <li><a href="pages-maintenance.php" data-key="t-maintenance"><?php echo $language['Maintenance'] ?> </a></li>
                                <li><a href="pages-comingsoon.php" data-key="t-coming-soon"><?php echo $language['Coming_Soon'] ?> </a></li>
                                <li><a href="pages-404.php" data-key="t-error-404"><?php echo $language['Error_404'] ?> </a></li>
                                <li><a href="pages-500.php" data-key="t-error-500"><?php echo $language['Error_500'] ?> </a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="layouts-horizontal.php">
                                <i data-feather="layout"></i>
                                <span data-key="t-horizontal"><?php echo $language['Horizontal'] ?> </span>
                            </a>
                        </li>

                        <li class="menu-title mt-2" data-key="t-components"><?php echo $language['Components'] ?> </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="share-2"></i>
                                <span data-key="t-multi-level"><?php echo $language['Multi_Level'] ?> </span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="javascript: void(0);" data-key="t-level-1-1"><?php echo $language['Level_1_1'] ?> </a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow" data-key="t-level-1-2"><?php echo $language['Level_1_2'] ?> </a>
                                    <ul class="sub-menu" aria-expanded="true">
                                        <li><a href="javascript: void(0);" data-key="t-level-2-1"><?php echo $language['Level_2_1'] ?> </a></li>
                                        <li><a href="javascript: void(0);" data-key="t-level-2-2"><?php echo $language['Level_2_2'] ?> </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li> -->

                    </ul>

                    <div class="card sidebar-alert shadow-none text-center mx-4 mb-0 mt-5">
                        <div class="card-body">
                            <img src="assets/images/giftbox.png" alt="">
                            <div class="mt-4">
                                <h5 class="alertcard-title font-size-16"><?php echo $language['Unlimited_Access'] ?> </h5>
                                <p class="font-size-13 text-dark">Upgrade your plan from a Free trial, to select ‘Business Plan’.
                                </p>
                                <a href="#!" class="btn btn-primary mt-2"><?php echo $language['Upgrade_Now'] ?> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
            </div>
        </div>