        <?php
            $notifications_read = $obj->getReadMessages($_SESSION['user_id']);
            $notifications_unread = $obj->getUnreadMessages($_SESSION['user_id']);
        ?>
        <div class="bg_wait" style="background-image: url(<?php echo $obj->base_url; ?>img/ajax-loader.gif);"></div>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-default navbar-fixed-top navbar-right">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img height="48" src="<?php echo $obj->base_url; ?>img/logo.png" alt="77beats.com" /></a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav_search_container">
                            <button class="btn btn-default btn-lg btn-link" style="font-size:24px;">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </li>
                        <li class="nav_notify">
                            <button class="btn btn-default btn-lg btn-link" style="font-size:24px;">
                                <i class="fa fa-bell" aria-hidden="true"></i>
                            </button>
                            <?php
                                echo '<span class="badge badge-notify notification_counter"'. (count($notifications_unread) < 1 ? ' style="display: none;">' : ">") .count($notifications_unread) .'</span>';
                            ?>
                        </li>
                        <li class="nav_schedule">Schedule A Plan</li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="<?php echo $obj->base_url . $_SESSION['image']; ?>" alt="<?php echo $_SESSION['first_name'] ." ". $_SESSION['last_name']; ?>" width="32" height="32" class="img-responsive profile_img img-rounded" />
                                <span class="profile_heading profile_name"><?php echo $_SESSION['first_name'] ." ". $_SESSION['last_name']; ?></span>
                                <span class="caret"></span>
                                <span class="clearfix"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="show_profile"><a href="#">Profile</a></li>
                                <li><a href="<?php echo $obj->base_url ?>logout/">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="main_data_container container-fluid">
            <div class="row">
                <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                    <ul class="side_bar">
                        <li id="manage_files" class="active" data-target-toggle="files_data_container">Manage Your Files</li>
                        <li id="manage_schedule" data-target-toggle="schedules_data_container">Manage Schedules</li>
                        <li id="manage_profile" data-target-toggle="profile_data_container">Manage Profile</li>
                        <li id="manage_personal_assistant_container">Personal Assistant</li>
                    </ul>
                </div>

                <div class="col-md-9 col-lg-9 col-xs-12 col-sm-12">
                    <!-- manage files container -->
                    <div id="files_data_container" class="container-fluid meta_main_container" style="overflow: hidden;">
                        <h3>Manage Your Files Here</h3>
                        <p>You have total <span class="file_count">0</span> files!</p>
                        <p><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#upload_files_modal">Upload Files</button></p>
                        <div class="table-responsive file_list_container">
                            <table class="table table-striped table-hover file_list_table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Added At</th>
                                        <th>Privacy</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody class="file_manager_files">
                                    <tr>
                                        <td colspan="4" class="default_file_row">Loading data...!</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- manage schedule container -->
                    <div id="schedules_data_container" class="container-fluid meta_main_container" style="display: none; overflow: hidden;">
                        <h3>Manage Your Schedule Here</h3>
                        <p>You have total <span class="schedule_count">0</span> upcoming events!</p>
                        <div class="table-responsive schedule_list_container">
                            <table class="table table-striped table-hover schedule_list">
                                <thead>
                                    <tr>
                                        <th>Schedule Title</th>
                                        <th>Start At</th>
                                        <th>End At</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody class="schedule_list_items">
                                    <tr>
                                        <td colspan="4" class="default_schedule_row">Loading data...!</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Profile container container -->
                    <div id="profile_data_container" class="container-fluid meta_main_container" style="display: none;">
                        <div class="profile_meta">
                            <h4>Profile Details</h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">Photo</div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4 col-xs-4 col-sm-4">
                                            <img class="img-responsive profile_img_ctn" src="<?php echo $obj->base_url . $_SESSION['image'] ?>" alt="<?php echo $_SESSION['first_name'] ." ".$_SESSION['last_name']; ?>" />
                                        </div>
                                        <div class="col-md-8 col-lg-8 col-xs-8 col-sm-8 text-right">
                                            <button class="btn btn-info" data-toggle="modal" data-target="#upload_p_img_modal">Change</button>
                                            <button class="btn btn-info" data-toggle="modal" data-target="#delete_profile_picture">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">Name</div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-md-7 col-lg-7 col-xs-7 col-sm-7 profile_name">
                                            <?php echo $_SESSION['first_name'] ." ". $_SESSION['last_name']; ?>
                                        </div>
                                        <div class="col-md-5 col-lg-5 col-xs-5 col-sm-5 text-right">
                                            <button class="btn btn-info" data-toggle="modal" data-target="#update_name_modal">Change</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">Email Address</div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-md-7 col-lg-7 col-xs-7 col-sm-7 email_address">
                                            <?php echo $_SESSION['email']; ?>
                                        </div>
                                        <div class="col-md-5 col-lg-5 col-xs-5 col-sm-5 text-right">
                                            <button class="btn btn-info" data-toggle="modal" data-target="#update_email_modal">Change</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">Account Type</div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-md-7 col-lg-7 col-xs-7 col-sm-7">
                                            <?php echo $_SESSION['type']; ?>
                                        </div>
                                        <div class="col-md-5 col-lg-5 col-xs-5 col-sm-5 text-right">
                                            <button class="btn btn-info" data-toggle="modal" data-target="#update_account_modal">Upgrade</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">Account Created At</div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6 text-right" style="margin-right: 5px;">
                                    <?php echo date("F j, Y g:i A", strtotime($_SESSION['joined_at'])); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">Account Status</div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6 text-right" style="margin-right: 5px;">
                                    <?php echo $_SESSION['account_status']; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">
                                    <p><b>Deactivate my account</b></p>
                                    <p class="help-block">If you deactivate your account, you need to contact us to activate it again.</p>
                                </div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6 text-right" style="margin-right: 5px;">
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#deactivate_account_modal">Deactivate Account</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5 col-lg-5 col-xs-6 col-sm-6">
                                    <p><b>Change Your Password</b></p>
                                </div>
                                <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6 text-right" style="margin-right: 5px;">
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#password_change">Change Password</button>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <!--<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12"></div>-->
            </div>
        </div>

        <!-- Fullscreen search container -->
        <div class="search_container" style="visibility: hidden;">
            <button type="button" class="search_box_close">&times;</button>
            <div class="search_input_container">
                <div class="input-group">
                    <input type="text" id="search_input_box" class="form-control" placeholder="Start Typing...">
                    <span class="input-group-btn">
                        <button class="btn btn-default search_btn" type="button"><i class="fa fa-search" aria-hidden="true"></i> </button>
                    </span>
                </div><!-- /input-group -->
            </div>
            <div class="row">
                <div id="search_result_container" class="container-fluid" style="padding: 20px 0;"></div>
            </div>
        </div>

        <!-- fullscreen personal assistant -->
        <div class="assist_search_container" style="visibility: hidden;">
            <h1 style="margin: 0;">What do you want to know about?</h1>
            <button type="button" class="assist_search_box_close">&times;</button>
            <div class="assist_search_input_container">
                <div class="input-group">
                    <input type="text" id="assist_search_input_box" class="form-control" placeholder="eg. google">
                    <span class="input-group-btn">
                        <button class="btn btn-default assist_search_btn" type="button"><i class="fa fa-search" aria-hidden="true"></i> </button>
                    </span>
                    <div class="keyword_suggestion" style="display: none;">
                        <ul class="keyword_suggestion_list"></ul>
                    </div>
                </div><!-- /input-group -->
            </div>
            <div class="row">
                <div id="assist_search_result_container" class="container-fluid"></div>
            </div>
        </div>

        <!-- Fullscreen notification container -->
        <div class="n_notification_container" style="display: none;">
            <button type="button" class="n_notification_box_close">&times;</button>
            <h1 style="text-align: center; margin-top: -55px;"><small>You have an upcoming event at</small> <br><span class="time_of_event_today"></span> today!</h1>
            <h3 class="notification_event_name">Event: Will watch Justice League</h3>
            <span class="bell fa fa-bell" style="font-size: 36px; margin: 0 auto;"></span>
            <audio src="<?php echo $obj->base_url; ?>/audio/alert.ogg" id="alert_tone" loop="loop"></audio>
        </div>

        <!-- Notification container -->
        <div class="notification_container">
            <h5>Notifications!</h5>
            <hr>
            <button type="button" class="notification_container_close">&times;</button>
            <div class="notify_list_item unread">
                <?php
                    if($notifications_unread) {
                        foreach($notifications_unread as $row) {
                            if($row['type'] === "S") {
                                echo '<div class="alert alert-success"><strong>Success!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            } else if($row['type'] === "I") {
                                echo '<div class="alert alert-info"><strong>Info!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            } else if($row['type'] === "W") {
                                echo '<div class="alert alert-warning"><strong>Warning!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            } else if($row['type'] === "D") {
                                echo '<div class="alert alert-danger"><strong>Failed!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            }
                        }
                    }
                ?>
            </div>
            <div class="notify_list_item read">
                <?php
                    if($notifications_read) {
                        foreach($notifications_read as $row) {
                            if($row['type'] === "S") {
                                echo '<div class="alert alert-success"><strong>Success!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            } else if($row['type'] === "I") {
                                echo '<div class="alert alert-info"><strong>Info!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            } else if($row['type'] === "W") {
                                echo '<div class="alert alert-warning"><strong>Warning!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            } else if($row['type'] === "D") {
                                echo '<div class="alert alert-danger"><strong>Failed!</strong> '. $row['information'] .' <small class="t_format_notification" data-original-time="'. $row['added_at'] .'">'. $obj->getFormatedTime($row['added_at']) .'</small></div>';
                            }
                        }
                    } else {
                        echo '<div class="alert alert-info"><strong>Info!</strong> No new update!</div>';
                    }
                ?>
            </div>
        </div>

        <!-- schedule slide container -->
        <div class="schedule_slide_container">
            <div class="bg_wait" style="background-image: url(<?php echo $obj->base_url; ?>img/ajax-loader.gif);"></div>
            <div class="schedule_slide_data">
                <h4>Schedule Your Plan!</h4>
                <button type="button" class="schedule_slide_close">&times;</button>
                <hr>
                <div class="row">
                    <div class="col-md-12 schedule_form_response" style="display: none;"></div>
                    <div class="col-md-12">
                        <label for="schedule_title">Schedule Title</label>
                        <input type="text" class="form-control" name="schedule_title" id="schedule_title" />
                    </div>
                    <div class="col-md-6 margin_top_10px">
                        <label for="start_at">Start At</label>
                        <input type="text" class="form-control" name="start_at" id="start_at" />
                    </div>
                    <div class="col-md-6 margin_top_10px">
                        <label for="end_at">End At</label>
                        <input type="text" class="form-control" name="end_at" id="end_at" />
                    </div>
                    <div class="col-md-12 margin_top_10px">
                        <label for="event_location">Event Location</label>
                        <input type="text" class="form-control" name="event_location" id="event_location" />
                    </div>
                    <div class="col-md-12 margin_top_10px">
                        <label for="event_description">Event Description</label>
                        <textarea class="form-control" name="event_description" id="event_description" cols="30" rows="5"></textarea>
                    </div>
                    <div class="col-md-6 margin_top_10px">
                        <input type="checkbox" name="show_notification" id="show_notification" checked="checked" /><label for="show_notification">&nbsp;&nbsp;Show Notification</label>
                    </div>
                    <div class="col-md-6 margin_top_10px">
                        <input type="checkbox" name="enable_sound" id="enable_sound" checked="checked" /><label for="enable_sound">&nbsp;&nbsp;Enable Sound</label>
                    </div>
                    <div class="col-md-12 margin_top_10px text-center">
                        <button class="btn btn-success save_new_schedule margin_top_10px">Save Schedule</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Pic Upload Modal -->
        <div id="upload_p_img_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Upload Your Profile Picture</h4>
                    </div>
                    <div class="modal-body">
                        <div class="drag_drop_file text-center">
                            <h3>Drag and drop here</h3>
                            <p class="helper-block">Picture width and height should be same. Eg. 100px X 100px</p>
                            <p class="helper-block">Supported formats: PNG, JPG, GIF, BMP</p>
                            <p class="helper-block">File size must be less than 512KB</p>
                            <input type="file" id="profile_picture_input" name="profile_picture_input" />
                        </div>
                        <div class="profile_pic_upload_progress text-center" style="display: none;">
                            <h3>Please wait...</h3>
                            <div class="progress">
                                <div class="profile_pic_upload_progress_bar progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
</div>
                        </div>
                        <div class="response_msg text-center" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Profile Pic Modal -->
        <div id="delete_profile_picture" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="content_body">
                            <h3>Delete profile picture?</h3>
                            <p class="helper-block"><b>Warning!</b> You can not undo this.</p>
                        </div>
                        <div class="response_msg text-center" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-default delete_profile_picture">Yes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Name Modal -->
        <div id="update_name_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3>Change Your Name</h3>
                    </div>
                    <div class="modal-body">
                        <div class="content_body">
                            <div class="bg_wait" style="background-image: url(<?php echo $obj->base_url; ?>img/ajax-loader.gif); display: none;"></div>
                            <div class="response_container"></div>
                            <div class="form-group">
                                <label for="first_name_m">First Name:</label>
                                <input type="text" class="form-control" id="first_name_m" placeholder="eg. John">
                            </div>
                            <div class="form-group">
                                <label for="last_name_m">Last Name:</label>
                                <input type="text" class="form-control" id="last_name_m" placeholder="eg. Cena">
                            </div>
                            <button type="button" class="btn btn-default change_name_m">Submit</button>
                        </div>
                        <div class="response_msg text-center" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Email address -->
        <div id="update_email_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3>Change Your Email</h3>
                    </div>
                    <div class="modal-body">
                        <div class="content_body">
                            <div class="bg_wait" style="background-image: url(<?php echo $obj->base_url; ?>img/ajax-loader.gif); display: none;"></div>
                            <div class="response_container"></div>
                            <p class="helper-block">Warning! You have to verify your email again!</p>
                            <div class="form-group">
                                <label for="email_m">New Email Address:</label>
                                <input type="text" class="form-control" id="email_m" placeholder="eg. email@example.com">
                            </div>
                            <button type="button" class="btn btn-default change_email_m">Submit</button>
                        </div>
                        <div class="response_msg text-center" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Account modal -->
        <div id="update_account_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3>Update your account</h3>
                    </div>
                    <div class="modal-body">
                        <div class="content_body">
                            <p>This feature is not available at this moment!</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account password change Modal -->
        <div id="password_change" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3>Change account password?</h3>
                    </div>
                    <div class="modal-body">
                        <div class="content_body">
                            <div class="form-group">
                                <p class="re_pass_err" style="color: red; font-weight: bold;"></p>
                                <label for="c_password">Current Password:</label>
                                <input type="password" class="form-control" id="c_password" autocomplete="off">
                                <label for="new_password">New Password:</label>
                                <input type="password" class="form-control" id="new_password" autocomplete="off">
                                <label for="re_new_password">Type New Password:</label>
                                <input type="password" class="form-control" id="re_new_password" autocomplete="off">
                            </div>
                            <button type="button" class="btn btn-default change_password">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deactivate Account Modal -->
        <div id="deactivate_account_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3>Deactivate my account?</h3>
                    </div>
                    <div class="modal-body">
                        <div class="content_body">
                            <p class="helper-block">Warning! You have to contact us to activate this account again!</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-default deactivate_account_m">Yes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="toast_message"></div>

        <!-- Upload Files Modal -->
        <div id="upload_files_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Upload Your Files</h4>
                    </div>
                    <div class="modal-body">
                        <div class="drag_drop_file text-center">
                            <h3>Drag and drop here</h3>
                            <p class="helper-block">Total File size must be less than 25MB</p>
                            <input type="file" id="files_input_box" name="files_input_box" multiple="multiple" />
                        </div>
                        <div class="profile_pic_upload_progress text-center" style="display: none;">
                            <h3>Please wait...</h3>
                            <div class="progress">
                                <div class="profile_pic_upload_progress_bar progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
</div>
                        </div>
                        <div class="response_msg text-center" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Files Modal -->
        <div id="file_details" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title f_name"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="row margin-top-bottom">
                                <div class="col-md-4">File Size <br><b class="f_size"></b></div>
                                <div class="col-md-4">File Type <br><b class="f_type"></b></div>
                                <div class="col-md-4">Privacy <br><b class="f_privacy"></b></div>
                            </div>
                            <div class="row preview margin-top-bottom">
                                <img class="f_preview" src="<?php echo $obj->base_url; ?>/img/no_preview.png" data-default="<?php echo $obj->base_url; ?>/img/no_preview.png" alt="" class="img-responsive" />
                            </div>
                            <div class="row margin-top-bottom">
                                File Uploaded at: <br><b class="f_uploaded_at"></b>
                            </div>
                            <div class="row margin-top-bottom">
                                <a class="btn btn-success f_dload">Download This File</a>
                                <a class="btn btn-danger f_del" data-toggle="collapse" data-target="#file_delete_confirm">Delete This File</a>
                                <a class="btn btn-info f_change_privacy" data-file-id="">Make IT <span class="file_pvc_text"></span></a>
                            </div>
                            <div id="file_delete_confirm" class="row margin-top-bottom collapse">
                                Are you sure, you want to delete it?<br><br>
                                <button class="btn btn-danger delete_confirm">Yes</button>
                                <button class="btn btn-success" data-toggle="collapse" data-target="#file_delete_confirm">No</button>
                            </div>
                        </div>
                        <div class="profile_pic_upload_progress text-center" style="display: none;">
                            <h3>Please wait...</h3>
                            <div class="progress">
                                <div class="profile_pic_upload_progress_bar progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
</div>
                        </div>
                        <div class="response_msg text-center" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule detail Modal -->
        <div id="schedule_details" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title s_title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-md-6 heading_st">Will Start At<br><b class="s_start_at"></b></div>
                                <div class="col-md-6 heading_st">Will End At<br><b class="s_end_at"></b></div>
                            </div>
                            <div class="row map_location margin_top_10px">
                                <div class="col-md-12">
                                    <iframe class="s_location_map" frameborder="0" style="border:0" src="" width="100%" height="250" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="row margin_top_10px">
                                <div class="col-md-12 s_description margin_top_10px">
                                    Will watch this movie in cineplex.
                                </div>
                            </div>
                            <hr>
                            <div class="row margin_top_10px">
                                <div class="col-md-6 margin_top_10px">
                                    <p><b>Show Notification</b></p>
                                    <label class="switch s_notification_container">
                                        <input type="checkbox" class="s_notification">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="col-md-6 margin_top_10px">
                                    <p><b>Enable Sound</b></p>
                                    <label class="switch s_enable_container">
                                        <input type="checkbox" class="s_enable">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="row margin_top_10px">
                                <button class="btn btn-danger" data-toggle="collapse" data-target="#schedule_delete_confirm">Delete Schedule</button>
                            </div>
                            <div id="schedule_delete_confirm" class="row margin-top-bottom collapse">
                                Are you sure, you want to delete it?<br><br>
                                <button class="btn btn-danger delete_confirm">Yes</button>
                                <button class="btn btn-success" data-toggle="collapse" data-target="#schedule_delete_confirm">No</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="toast_message"></div>
        <script> var security_token = "<?php echo $_SESSION['csrf_token']; ?>", load_fileManager = true; </script>