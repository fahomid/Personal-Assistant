$(document).ready(function() {
    var ajaxing = false;
    var base_url = "http://localhost/WebTech/Project/";
    var googleApiKey = "https://www.google.com/maps/embed/v1/place?key=AIzaSyDqCjrkF8afASxMYA9XNkPXIAmmBX9s7J0&q=";
    var schedules_timers =[];
    var timeout = null;

    //signup form submit
    $(".signup_form").submit(function(e) {
        if($("#first_name").val() !== "" && $("#last_name").val() !== "" && $("#email").val() !== "" && $("#password").val() !== "" && $('#agree_terms').is(":checked")) {
            if(!ajaxing) {
                ajaxing = true;
                $(".bg_wait").show();
                $.ajax({
                    type: "POST",
                    url: "http://localhost/WebTech/Project/api.php",
                    dataType: "json",
                    data: {
                        action: "signup",
                        first_name: $("#first_name").val(),
                        last_name: $("#last_name").val(),
                        email: $("#email").val(),
                        password: $("#password").val(),
                        agree_terms: ($('#agree_terms').is(":checked") ? 1: 0),
                        security_token: security_token
                    },
                    success: function(data) {
                        if(data.response === "success") {
                            $(".data_response").empty();
                            $(".data_response").append('<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Success! '+ data.msg +'</strong></div>');
                            setTimeout(function() {
                                $(".signup_form").hide();
                                $(".bg_wait").hide();
                            }, 2000);
                            setTimeout(function() {
                                window.location.replace("http://localhost/WebTech/Project/login/");
                            }, 4000);
                        } else {
                            $(".data_response").empty();
                            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! '+ data.response +'</strong></div>');
                            ajaxing = false;
                            $(".bg_wait").hide();
                        }
                    }
                });
            }
        } else if(!$('#agree_terms').is(":checked")) {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must agree with our terms and conditions!</strong></div>');
        } else if($("#first_name").val() === "") {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must enter your first name!</strong></div>');
        } else if($("#last_name").val() === "") {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must enter your last name!</strong></div>');
        } else if($("#email").val() === "") {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must enter your email address!</strong></div>');
        } else if($("#password").val() === "") {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must enter your password!</strong></div>');
        } else {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must fill all the required fields!</strong></div>');
        }
        e.preventDefault();
    });

    //login form submit
    $(".login_form").submit(function(e) {
        if($("#email").val() !== "" && $("#password").val() !== "") {
            if(!ajaxing) {
                ajaxing = true;
                $(".bg_wait").show();
                $.ajax({
                    type: "POST",
                    url: "http://localhost/WebTech/Project/api.php",
                    dataType: "json",
                    data: {
                        action: "login",
                        email: $("#email").val(),
                        password: $("#password").val(),
                        remember_login: ($('#remember_login').is(":checked") ? 1: 0),
                        security_token: security_token
                    },
                    success: function(data) {
                        if(data.response === "success") {
                            $(".data_response").empty();
                            $(".data_response").append('<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Success! '+ data.msg +'</strong></div>');
                            setTimeout(function() {
                                $(".login_form").hide();
                                $(".bg_wait").hide();
                            }, 2000);
                            setTimeout(function() {
                                window.location.replace("http://localhost/WebTech/Project/dashboard/");
                            }, 4000);
                        } else {
                            $(".data_response").empty();
                            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! '+ data.response +'</strong></div>');
                            ajaxing = false;
                            $(".bg_wait").hide();
                        }
                    }
                });
            }
        } else if($("#email").val() === "") {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must enter your email address!</strong></div>');
        } else if($("#password").val() === "") {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must enter your password!</strong></div>');
        } else {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must fill all the required fields!</strong></div>');
        }
        e.preventDefault();
    });

    //search btn click
    $(".nav_search_container").on("click", function() {
        $(".search_container").css({ "visibility" : "visible", "opacity" : 1 });
        console.log("Clicked");
    });

    //search box close
    $(".search_box_close").on("click", function() {
        $(".search_container").css({ "visibility" : "hidden", "opacity" : 0 });
    });

    //show notification on btn click
    $(".nav_notify").on("click", function() {
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "seen_notification",
                security_token: security_token
            },
            success: function(data) {
                var response = data;
                if(response.response === "success") {
                    $(".notification_container").css({"right": 0});
                    $(".notification_counter").hide();
                    $(".notification_container .unread>div").prependTo(".notification_container .read");
                } else {
                    alert("Unknown error occurred!");
                }
            }
        });
    });

    //close notification
    $(".notification_container_close").on("click", function(){
        $(".notification_container").css({"right": "-200%"});
    });

    //schedule show
    $(".nav_schedule").on("click", function() {
        $(".schedule_slide_container").css({ "left": 0 });
    });

    //close schedule
    $(".schedule_slide_close").on("click", function(){
        $(".schedule_slide_container").css({"left": "-200%"});
        $(".schedule_form_response").slideUp();
    });

    $('#upload_p_img_modal').on('hidden.bs.modal', function () {
        $(".drag_drop_file").show();
        $(".profile_pic_upload_progress, .response_msg").hide();
    });

    $(".delete_profile_picture").on("click", function() {
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "profile_picture_delete",
                security_token: security_token
            },
            success: function(data) {
                var response = data;
                if(response.response === "success") {
                    d = new Date();
                    $(".profile_img, .profile_img_ctn").attr("src", response.image_url +"?"+ d.getTime());
                    $('#delete_profile_picture').modal('toggle');
                    $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> Profile picture deleted successfully!</div>');
                        $(".toast_message").show().delay(5000).fadeOut();
                } else {
                    $('#delete_profile_picture').modal('toggle');
                }
            }
        });
    });

    $(".change_name_m").on("click", function() {
        if($("#first_name_m").val() === "" || $("#last_name_m").val() === "") {
            $("#update_name_modal .response_container").empty();
            $("#update_name_modal .response_container").append('<div class="alert alert-danger"><strong>Error!</strong> You must enter first name and last name!</div>');
        } else {
            $("#update_name_modal .response_container").empty();
            $.ajax({
                type: "POST",
                url: "http://localhost/WebTech/Project/api.php",
                dataType: "json",
                data: {
                    action: "profile_name_update",
                    first_name: $("#first_name_m").val(),
                    last_name: $("#last_name_m").val(),
                    security_token: security_token
                },
                success: function(data) {
                    var response = data;
                    if(response.response === "success") {
                        $(".profile_name").text(data.name);
                        $('#update_name_modal').modal('toggle');
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> Name changed successfully!</div>');
                        $(".toast_message").show().delay(5000).fadeOut();
                    } else if(response.response === "failed"){
                        $("#update_name_modal .response_container").empty();
                        $("#update_name_modal .response_container").append('<div class="alert alert-danger"><strong>Error!</strong> '+ response.msg);
                    } else {
                        $('#update_name_modal').modal('toggle');
                    }
                }
            });
        }
    });

    $(".change_email_m").on("click", function() {
        if($("#email_m").val() === "") {
            $("#update_email_modal .response_container").empty();
            $("#update_email_modal .response_container").append('<div class="alert alert-danger"><strong>Error!</strong> You must enter an email address!</div>');
        } else {
            $("#update_email_modal .response_container").empty();
            $.ajax({
                type: "POST",
                url: "http://localhost/WebTech/Project/api.php",
                dataType: "json",
                data: {
                    action: "profile_email_update",
                    email: $("#email_m").val(),
                    security_token: security_token
                },
                success: function(data) {
                    var response = data;
                    if(response.response === "success") {
                        $(".email_address").text(data.email);
                        $("#update_email_modal").modal('toggle');
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> Email address changed successfully!</div>');
                        $(".toast_message").show().delay(5000).fadeOut();
                    } else if(response.response === "failed"){
                        $("#update_email_modal .response_container").empty();
                        $("#update_email_modal .response_container").append('<div class="alert alert-danger"><strong>Error!</strong> '+ response.msg);
                    } else {
                        $('#update_email_modal').modal('toggle');
                    }
                }
            });
        }
    });

    $('#update_email_modal').on('hidden.bs.modal', function () {
        $("#update_email_modal .response_container").empty();
        $("#update_email_modal #email_m").val("");
    });

    $('#update_name_modal').on('hidden.bs.modal', function () {
        $("#update_name_modal .response_container").empty();
        $("#first_name_m, #last_name_m").val("");
    });

    $(".deactivate_account_m").on("click", function() {
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "deactivate_profile",
                security_token: security_token
            },
            success: function(data) {
                window.location.href = base_url +'logout/?deactivate=1';
            }
        });
    });

    $(".show_profile").on("click", function() {
        $(".bg_wait").show();
        $(".meta_main_container").hide();
        setTimeout(function() {
            $("#profile_data_container").show();
            $(".side_bar>li").removeClass("active");
            $("#manage_profile").addClass("active");
            $(".bg_wait").hide();
        }, 1500);
    });

    $("#manage_files, #manage_schedule, #manage_profile, #manage_settings, #manage_privacy").on("click", function() {
        $(".bg_wait").show();
        $(".side_bar>li").removeClass("active");
        $(".meta_main_container").hide();
        $("#" + $(this).attr("data-target-toggle")).show();
        $(this).addClass("active");
        $(".toast_message").hide();
        $(".toast_message").empty();
        if($(this).attr("id") === "manage_files") {
            setupFileManager();
        } else if($(this).attr("id") === "manage_schedule") {
            setUpScheduleManager();
        } else {
            setTimeout(function() {
                $(".bg_wait").hide();
            }, 2000);
        }
    });


    //setup schedule manager
    var setUpScheduleManager = function() {
        $("#schedules_data_container").height($(window).height() - 110);
        //$("#schedules_data_container .schedule_list_container").height($("#schedules_data_container").height() - $("#schedules_data_container *:nth-child(1)").outerHeight() - $("#schedules_data_container *:nth-child(2)").outerHeight() - $("#schedules_data_container *:nth-child(3)").outerHeight() + 50);
        $(".bg_wait").show();
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "get_schedule_meta",
                security_token: security_token
            },
            success: function(data) {
                if(data.response === "success") {
                    $(".schedule_count").text(data.count);
                    if(data.count < 1) {
                        $("#schedules_data_container .default_schedule_row").text("No schedule found!");
                        setTimeout(function() {
                            $(".toast_message").empty();
                            $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Failed! </strong> You don\'t have any upcoming event at this moment!</div>');
                            $(".toast_message").delay(2000).show().fadeOut();
                            $(".bg_wait").hide();
                        }, 2000);
                    } else {
                        setupSchedules();
                    }
                } else if(data.response === "failed") {
                    $(".toast_message").show();
                    $(".bg_wait").delay(2000).hide();
                    setTimeout(function() {
                        $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Failed! </strong> '+ data.msg +'.</div>');
                    }, 2000);
                } else {
                    $(".toast_message").show();
                    setTimeout(function() {
                        $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Failed! </strong> '+ data.msg +'.</div>');
                    }, 2000);
                }
            }
        });
    };
    var setupSchedules = function() {
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "get_schedules",
                security_token: security_token
            },
            success: function(data) {
                if(data.response === "success") {
                    $(".schedule_list_items").empty();
                    destroyTimerArray();
                    $.each(data.data, function(index, value) {
                        if(value.show_notification === "1" && (moment(value.start_at).format("x") - moment(new Date()).format("x")) > 300000) {
                            var event_timer = setTimeout(function() {
                                if(value.enable_sound === "1") {
                                    document.getElementById("alert_tone").play();
                                }
                                $(".n_notification_container .time_of_event_today").text(moment(value.start_at).format("hh:mm A"));
                                $(".n_notification_container .notification_event_name").text("Event Title: "+ value.schedule_title);
                                $(".n_notification_container").fadeIn("slow");
                            }, moment(value.start_at).format("x") - moment(new Date()).format("x") - 299999);
                            schedules_timers.push(event_timer);
                        }
                        $(".schedule_list_items").empty();
                        $(".schedule_list_items").append('<tr><td>'+ value.schedule_title +'</td><td>'+ value.start_at +'</td><td>'+ value.end_at +'</td><td><button class="btn btn-info schedule_op_btn" data-title="'+ value.schedule_title +'" data-start-at="'+ value.start_at +'" data-end_at="'+ value.end_at +'" data-id="'+ value.id +'" data-location="'+ value.location +'" data-description="'+ value.description +'" data-added="'+ value.set_at +'" data-enable-sound="'+ value.enable_sound +'" data-show-notification="'+ value.show_notification +'">Options</button></td></tr>');
                    });
                    $(".bg_wait").delay(2000).fadeOut();
                } else if(data.response === "failed") {

                } else {

                }
            }
        });
    };

    var destroyTimerArray = function() {
        for(i = 0; i < schedules_timers.length; i++) {
            clearTimeout(schedules_timers[i]);
        }
    };

    $(".n_notification_box_close").on("click", function() {
        document.getElementById("alert_tone").pause();
        document.getElementById("alert_tone").currentTime = 0;
        $(".n_notification_container").fadeOut();
    });


    //search box
    $("#search_input_box").on("keyup", function() {
        if($(this).val() !== "") {
            if(!ajaxing) {
                ajaxing = true;
                $.ajax({
                    type: "POST",
                    url: "http://localhost/WebTech/Project/api.php",
                    dataType: "json",
                    data: {
                        action: "get_search_suggestion",
                        keyword: $("#search_input_box").val(),
                        security_token: security_token
                    },
                    success: function(data) {
                        if(data.response === "success") {
                            $("#search_result_container").empty();
                            $.each(data.results, function(index, value) {
                                $("#search_result_container").append('<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 card grid-item"><div class="thumbnail"><div class="caption"><h3 class="thumbnail-label">'+ value.file_name +'</h3><div class="row"><div class="col-md-12">Type: <b>'+ value.file_type +'</b></div><div class="col-md-12">Size: <b>'+ bytesToSize(value.file_size) +'</b></div><div class="col-md-12">Privacy: <b>'+ value.access_type +'</b></div><div class="col-md-12">Owner: <b>'+ (value.owner === data.u_token ? "You" : "Anoter User") +'</b></div></div></div><div class="caption card-footer"><a href="'+ base_url +"file/"+ value.id +"/"+ value.file_name +'" class="btn btn-success">Download</a></div></div></div></div></div>');
                            });
                        } else if(data.response === "failed") {
                            $("#search_result_container").empty();
                            $("#search_result_container").append('<p class="text-center">No result found!</p>');
                        } else {
                            $("#search_result_container").empty();
                            $("#search_result_container").append('<p class="text-center">No result found!</p>');
                        }
                        ajaxing = false;
                    }
                });
            }
        } else {
            $("#search_result_container").empty();
        }
    });



    //handle schedule options
    $(document).on('click', '#schedules_data_container .schedule_op_btn', function() {
        $("#schedule_details").modal("toggle");
        $("#schedule_details .s_title").text($(this).attr("data-title"));
        $("#schedule_details .s_start_at").text(moment($(this).attr("data-start-at")).format("DD MMMM, YYYY HH:mm A"));
        $("#schedule_details .s_end_at").text(moment($(this).attr("data-end_at")).format("DD MMMM, YYYY HH:mm A"));
        $("#schedule_details .s_description").text($(this).attr("data-description"));
        $("#schedule_details .s_notification_container .s_notification").attr("data-id", $(this).attr("data-id"));
        $("#schedule_details .s_enable_container .s_enable").attr("data-id", $(this).attr("data-id"));
        $("#schedule_details #schedule_delete_confirm .delete_confirm").attr("data-id", $(this).attr("data-id"));
        if($(this).attr("data-show-notification") === "1") $("#schedule_details .s_notification").prop('checked', true);
        else $("#schedule_details .s_notification").prop('checked', false);
        if($(this).attr("data-enable-sound") === "1") $("#schedule_details .s_enable").prop('checked', true);
        else $("#schedule_details .s_enable").prop('checked', false);
        $("#schedule_details .s_location_map").attr("src", googleApiKey + encodeURIComponent($(this).attr("data-location")));
    });

    //update notification and sound setting
    $(document).on('change', '#schedule_details .s_notification_container .s_notification', function() {
        $(".bg_wait").show();
        if(!ajaxing) {
            ajaxing = true;
            $.ajax({
                type: "POST",
                url: "http://localhost/WebTech/Project/api.php",
                dataType: "json",
                data: {
                    action: "set_notification",
                    set_on_off: ($(".s_notification:checkbox:checked").length > 0 ? 1 : 0),
                    schedule_id: $(this).attr("data-id"),
                    security_token: security_token
                },
                success: function(data) {
                    if(data.response === "success") {
                        $("#manage_schedule").trigger("click");
                        $(".bg_wait").delay(2000).fadeOut();
                    } else if(data.response === "failed") {
                        $(".bg_wait").delay(2000).fadeOut();
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                        $(".toast_message").delay(2000).show().fadeOut();
                    } else {
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                        $(".bg_wait").delay(2000).fadeOut();
                        $(".toast_message").delay(2000).show().fadeOut();
                    }
                    ajaxing = false;
                }
            });
        }
    });
    $(document).on('change', '#schedule_details .s_enable_container .s_enable', function() {
        $(".bg_wait").show();
        if(!ajaxing) {
            ajaxing = true;
            $.ajax({
                type: "POST",
                url: "http://localhost/WebTech/Project/api.php",
                dataType: "json",
                data: {
                    action: "set_enable_sound",
                    set_on_off: ($(".s_enable:checkbox:checked").length > 0 ? 1 : 0),
                    schedule_id: $(this).attr("data-id"),
                    security_token: security_token
                },
                success: function(data) {
                    if(data.response === "success") {
                        $("#manage_schedule").trigger("click");
                        $(".bg_wait").delay(2000).fadeOut();
                    } else if(data.response === "failed") {
                        $(".bg_wait").delay(2000).fadeOut();
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                        $(".toast_message").delay(2000).show().fadeOut();
                    } else {
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                        $(".bg_wait").delay(2000).fadeOut();
                        $(".toast_message").delay(2000).show().fadeOut();
                    }
                    ajaxing = false;
                }
            });
        }
    });


    //delete schedule
    $("#schedule_delete_confirm .delete_confirm").on("click", function() {
        $(".bg_wait").show();
        $(this).next().trigger("click");
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "delete_schedule",
                schedule_id: $(this).attr("data-id"),
                security_token: security_token
            },
            success: function(data) {
                if(data.response === "success") {
                    $("#manage_schedule").trigger("click");
                    $(".bg_wait").delay(2000).fadeOut();
                    $("#schedule_details").modal("toggle");
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                    $(".bg_wait").delay(7000).fadeOut();
                    $(".toast_message").delay(2000).show().fadeOut();
                } else if(data.response === "failed") {
                    $(".bg_wait").delay(2000).fadeOut();
                    $("#schedule_details").modal("toggle");
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                    $(".toast_message").delay(2000).show().fadeOut();
                } else {
                    $(".toast_message").empty();
                    $("#schedule_details").modal("toggle");
                    $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                    $(".bg_wait").delay(2000).fadeOut();
                    $(".toast_message").delay(2000).show().fadeOut();
                }
            }
        });
    });

    //save new schedule
    $("#event_location").on("change", function() {
        $(this).val("");
        event_location = "";
    });
    $(".save_new_schedule").on("click", function() {
        var start_date = moment($("#start_at").val()).format('YYYY-MM-DD HH:mm:ss');
        var end_date = moment($("#end_at").val()).format('YYYY-MM-DD HH:mm:ss');
        $(".schedule_form_response").slideUp();
        $(".schedule_form_response").empty();
        if($("#schedule_title").val() === "") {
            $(".schedule_form_response").append('<div class="alert alert-danger"><strong>Error!</strong> You must enter schedule title!</div>');
        } else if($("#start_at").val() === "") {
            $(".schedule_form_response").append('<div class="alert alert-danger"><strong>Error!</strong> You must enter start time!</div>');
        } else if($("#start_at").val() === "") {
            $(".schedule_form_response").append('<div class="alert alert-danger"><strong>Error!</strong> You must enter end time!</div>');
        } else if($("#start_at").val() === $("#end_at").val()) {
            $(".schedule_form_response").append('<div class="alert alert-danger"><strong>Error!</strong> Start and end time must be different!</div>');
        } else if(moment($("#start_at").val()).diff(moment($("#end_at").val())) > 0) {
            $(".schedule_form_response").append('<div class="alert alert-danger"><strong>Error!</strong> Start time must be before end time!</div>');
        } else if($("#event_location").val() === "" || event_location === "") {
            $(".schedule_form_response").append('<div class="alert alert-danger"><strong>Error!</strong> You must select location from dropdown menu!</div>');
        } else if($("#event_description").val() === "") {
            $(".schedule_form_response").append('<div class="alert alert-danger"><strong>Error!</strong> You must enter event description!</div>');
        } else {
            $(".schedule_slide_container .bg_wait").show();
            $.ajax({
                type: "POST",
                url: "http://localhost/WebTech/Project/api.php",
                dataType: "json",
                data: {
                    action: "save_new_schedule",
                    schedule_title: $("#schedule_title").val(),
                    start_at: start_date,
                    end_at: end_date,
                    event_location: event_location,
                    event_description: $("#event_description").val(),
                    show_notification: ($('#show_notification:checkbox:checked').length > 0 ? 1 : 0),
                    enable_sound: ($('#enable_sound:checkbox:checked').length > 0 ? 1 : 0),
                    security_token: security_token
                },
                success: function(data) {
                    if(data.response === "success") {
                        $(".schedule_slide_close").trigger("click");
                        $(".schedule_slide_container .bg_wait").hide();
                        $("#schedule_title, #start_at, #end_at, #event_location, #event_description").val("");
                        $("#manage_schedule").trigger("click");
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                        $(".toast_message").show().delay(5000).fadeOut();
                    } else if(data.response === "failed") {
                        $(".schedule_slide_container .bg_wait").hide();
                    } else {

                    }
                }
            });
        }
        $(".schedule_form_response").slideDown();
    });


    //setup file manager
    var setupFileManager = function () {
        $("#files_data_container").height($(window).height() - 110);
        $("#files_data_container .file_list_container").height($("#files_data_container").height() - $("#files_data_container *:nth-child(1)").outerHeight() - $("#files_data_container *:nth-child(2)").outerHeight() - $("#files_data_container *:nth-child(3)").outerHeight() - 50);
        $(".bg_wait").show();
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "get_files_meta",
                security_token: security_token
            },
            success: function(data) {
                if(data.response === "success") {
                    $(".file_count").text(data.count);
                    if(data.count < 1) {
                        $(".default_file_row").text("No file found!");
                        setTimeout(function() {
                            $(".toast_message").empty();
                            $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Failed! </strong> You haven\'t uploaded any file in your account!</div>');
                            $(".toast_message").delay(2000).show().fadeOut();
                            $(".bg_wait").hide();
                        }, 2000);
                    } else {
                        setUpFiles();
                    }
                } else if(data.response === "failed") {
                    $(".toast_message").show();
                    $(".bg_wait").delay(2000).hide();
                    setTimeout(function() {
                        $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Failed! </strong> '+ data.msg +'.</div>');
                    }, 2000);
                } else {
                    $(".toast_message").show();
                    setTimeout(function() {
                        $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Failed! </strong> '+ data.msg +'.</div>');
                    }, 2000);
                }
            }
        });
    };

    var setUpFiles = function() {
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "get_files",
                security_token: security_token
            },
            success: function(data) {
                if(data.response === "success") {
                    $(".file_manager_files").empty();
                    for(i = 0; i < data.data.length; i++) {
                        $(".file_manager_files").append('<tr><td>'+ data.data[i].file_name +'</td><td>'+ data.data[i].uploaded_at +'</td><td>'+ data.data[i].access_type +'</td><td><button class="btn btn-info file_op_btn" data-name="'+ data.data[i].file_name +'" data-type="'+ data.data[i].file_type +'" data-access="'+ data.data[i].access_type +'" data-id="'+ data.data[i].id +'" data-url="'+ data.data[i].file_url +'" data-size="'+ data.data[i].file_size +'" data-added="'+ data.data[i].uploaded_at +'">Options</button></td></tr>');
                    }
                    $(".bg_wait").delay(2000).fadeOut();
                } else if(data.response === "failed") {

                } else {

                }
            }
        });
    };


    //file options
    $(document).on('click', '.file_op_btn', function(){
        $("#file_details").modal("toggle");
        $("#file_details .f_name").text("File: "+ $(this).attr("data-name"));
        $("#file_details .f_size").text(bytesToSize($(this).attr("data-size")));
        $("#file_details .f_type").text($(this).attr("data-type"));
        $("#file_details .f_privacy").text($(this).attr("data-access"));
        $("#file_details .f_uploaded_at").text($(this).attr("data-added"));
        $("#file_details .delete_confirm").attr("data-file-id", $(this).attr("data-id"));
        $("#file_details .f_change_privacy").attr("data-file-id", $(this).attr("data-id"));
        $("#file_details .f_dload").attr("href", base_url +"file/"+ $(this).attr("data-id") +"/");
        $(".file_pvc_text").text(($(this).attr("data-access") === "Private" ? "Public" : "Private"));
        if($(this).attr("data-type").indexOf("image") >= 0) {
            console.log("Image!");
            $("#file_details .f_preview").attr("src", base_url +"file/"+ $(this).attr("data-id") +"/"+ $(this).attr("data-name"));
        } else {
            $("#file_details .f_preview").attr("src", $("#file_details .f_preview").attr("data-default"));
        }
    });

    //delete file
    $(document).on('click', '#file_details .delete_confirm', function() {
        $(".bg_wait").show();
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "delete_file",
                file_id: $(this).attr("data-file-id"),
                security_token: security_token
            },
            success: function(data) {
                if(data.response === "success") {
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-success alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'</div>');
                    $("#file_details").modal("toggle");
                    $(".toast_message").show().delay(5000).fadeOut();
                    setupFileManager();
                } else if(data.response === "failed") {
                    $("#file_details").modal("toggle");
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ + data.msg + +'</div>');
                    $(".toast_message").show().delay(5000).fadeOut();
                    setupFileManager();
                } else {
                    $("#file_details").modal("toggle");
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> Error occurred while deleting file!</div>');
                    $(".toast_message").show().delay(5000).fadeOut();
                    setupFileManager();
                }
            }
        });
    });

    $('#file_details').on('hidden.bs.modal', function () {
        $("#file_delete_confirm").removeClass("in");
    });

    //change file privacy
    $(document).on('click', '#file_details .f_change_privacy', function() {
        $(".bg_wait").show();
        $.ajax({
            type: "POST",
            url: "http://localhost/WebTech/Project/api.php",
            dataType: "json",
            data: {
                action: "change_file_privacy",
                file_id: $(this).attr("data-file-id"),
                access_type: $(".file_pvc_text").text(),
                security_token: security_token
            },
            success: function(data) {
                if(data.response === "success") {
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-success alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'!</div>');
                    $("#file_details").modal("toggle");
                    $(".toast_message").show().delay(5000).fadeOut();
                    setupFileManager();
                } else if(data.response === "failed") {
                    $("#file_details").modal("toggle");
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> '+ data.msg +'</div>');
                    $(".toast_message").show().delay(5000).fadeOut();
                    setupFileManager();
                } else {
                    $("#file_details").modal("toggle");
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-danger alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> Error occurred while deleting file!</div>');
                    $(".toast_message").show().delay(5000).fadeOut();
                    setupFileManager();
                }
            }
        });
    });

    //upload profile picture event handle
    $('#upload_p_img_modal').on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
    $('#upload_p_img_modal').on("dragenter", function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
    $('#upload_p_img_modal').on("drop", function(e){
        if(e.originalEvent.dataTransfer){
            var ext = e.originalEvent.dataTransfer.files[0].name.split('.').pop().toLowerCase();
            if(e.originalEvent.dataTransfer.files.length > 1) {
                $(".response_msg").empty();
                $(".response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> Please select one picture at a time!</div>');
                $(".response_msg").slideDown("slow");
            } else if($.inArray(ext, ['gif','png','jpg','jpeg', 'bmp']) == -1) {
                $(".response_msg").empty();
                $(".response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> Invalid file extension!</div>');
                $(".response_msg").slideDown("slow");
            } else if(e.originalEvent.dataTransfer.files[0].size > 524288) {
                $(".response_msg").empty();
                $(".response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> File size must be less than 512KB!</div>');
                $(".response_msg").slideDown("slow");
            } else if(e.originalEvent.dataTransfer.files.length === 1) {
                upload_profile_picture(e.originalEvent.dataTransfer.files[0]);
            }
            e.preventDefault();
            e.stopPropagation();
        }
    });
    $("#profile_picture_input").change(function () {
            var ext = this.files[0].name.split('.').pop().toLowerCase();
            if(this.files.length > 1) {
                $(".response_msg").empty();
                $(".response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> Please select one picture at a time!</div>');
                $(".response_msg").slideDown("slow");
            } else if($.inArray(ext, ['gif','png','jpg','jpeg', 'bmp']) == -1) {
                $(".response_msg").empty();
                $(".response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> Invalid file extension!</div>');
                $(".response_msg").slideDown("slow");
            } else if(this.files[0].size > 524288) {
                $(".response_msg").empty();
                $(".response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> File size must be less than 512KB!</div>');
                $(".response_msg").slideDown("slow");
            } else if(this.files.length === 1) {
                upload_profile_picture(this.files[0]);
            }
    });
    var upload_profile_picture = function(file) {
        var formData = new FormData();
        formData.append('action', 'profile_picture_upload');
        formData.append('security_token', security_token);
        formData.append('file', file);
        $.ajax({
            url: base_url + "api.php",
            type: "POST",
            xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress',function(e) {
                            $(".drag_drop_file").slideUp("slow");
                            $(".profile_pic_upload_progress").slideDown("slow");
                            $(".profile_pic_upload_progress_bar").attr("aria-valuenow", Math.round((e.loaded/e.total) * 100));
                            $(".profile_pic_upload_progress_bar").text(Math.round((e.loaded/e.total) * 100) +"%");
                            $(".profile_pic_upload_progress_bar").css({ "width" : Math.round((e.loaded/e.total) * 100)+"%" });
                        }, false);
                    }
                return myXhr;
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                var response = JSON.parse(data);
                if(response.response === "success") {
                    $(".response_msg").empty();
                    $(".response_msg").append('<div class="alert alert-success"><strong>Success!</strong> '+ response.msg +'</div>');
                    d = new Date();
                    $(".profile_img, .profile_img_ctn").attr("src", base_url + response.image_url +"?"+ d.getTime());
                    setTimeout(function() {
                        $('.progress-bar').attr('style', "width: 0%");
                        $(".profile_pic_upload_progress").slideUp();
                        $(".response_msg").slideDown("slow");
                        $(".toast_message").empty();
                        $(".toast_message").append('<div class="alert alert-info alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> Profile picture updated successfully!</div>');
                        $(".toast_message").show().delay(5000).fadeOut();
                    }, 1000);
                } else if(response.response === "failed") {
                    $(".response_msg").empty();
                    $(".response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> '+ response.msg +'</div>');
                    setTimeout(function() {
                        $('.progress-bar').attr('style', "width: 0%");
                        $(".profile_pic_upload_progress").slideUp();
                        $(".response_msg").slideDown("slow");
                    }, 1000);
                }
            },
            error: function(data) {
                alert("Unknown error occurred! Please try again later!");
            }
        });
    };

    //upload files event handle
    $('#upload_files_modal').on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
    $('#upload_files_modal').on("dragenter", function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
    $('#upload_files_modal').on("drop", function(e){
        if(e.originalEvent.dataTransfer) {
            $("#upload_files_modal .response_msg").empty();
            $("#upload_files_modal .response_msg").hide();
            if(getTotalFileSize(e.originalEvent.dataTransfer.files) > 26214400) {
                $("#upload_files_modal .response_msg").empty();
                $("#upload_files_modal .response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> Total file size must be less than 25MB!</div>');
                $("#upload_files_modal .response_msg").show();
            } else {
                upload_files(e.originalEvent.dataTransfer.files);
            }
            e.preventDefault();
            e.stopPropagation();
        }
    });
    $("#files_input_box").change(function () {
        $("#upload_files_modal .response_msg").empty();
        $("#upload_files_modal .response_msg").hide();
        if(getTotalFileSize(this.files) > 26214400) {
            $("#upload_files_modal .response_msg").empty();
            $("#upload_files_modal .response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> Total file size must be less than 25MB!</div>');
            $("#upload_files_modal .response_msg").show();
        } else {
            upload_files(this.files);
        }
    });
    var upload_files = function(file) {
        var formData = new FormData();
        formData.append('action', 'file_upload');
        formData.append('security_token', security_token);
        $(file).each(function(i, f) {
            formData.append('file_'+ i, f);
        });
        formData.append('file', file);
        $.ajax({
            url: base_url + "api.php",
            type: "POST",
            xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress',function(e) {
                            $(".drag_drop_file").slideUp("slow");
                            $(".profile_pic_upload_progress").slideDown("slow");
                            $(".profile_pic_upload_progress_bar").attr("aria-valuenow", Math.round((e.loaded/e.total) * 100));
                            $(".profile_pic_upload_progress_bar").text(Math.round((e.loaded/e.total) * 100) +"%");
                            $(".profile_pic_upload_progress_bar").css({ "width" : Math.round((e.loaded/e.total) * 100)+"%" });
                        }, false);
                    }
                return myXhr;
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                var response = JSON.parse(data);
                if(response.response === "success") {
                    $('.progress-bar').attr('style', "width: 0%");
                    $(".profile_pic_upload_progress").slideUp();
                    $("#upload_files_modal").modal('toggle');
                    setTimeout(function() {
                        $("#upload_files_modal .drag_drop_file").show();
                    }, 1000);
                    $(".toast_message").empty();
                    $(".toast_message").append('<div class="alert alert-success alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success! </strong> Files uploaded successfully!</div>');
                    $(".toast_message").show().delay(5000).fadeOut();
                    setupFileManager();
                } else if(response.response === "failed") {
                    $("#upload_files_modal .response_msg").empty();
                    $("#upload_files_modal .response_msg").append('<div class="alert alert-danger"><strong>Error!</strong> '+ response.msg +'</div>');
                    setTimeout(function() {
                        $('.progress-bar').attr('style', "width: 0%");
                        $(".profile_pic_upload_progress").slideUp();
                        $(".response_msg").slideDown("slow");
                    }, 1000);
                }
            },
            error: function(data) {
                alert("Unknown error occurred! Please try again later!");
            }
        });
    };

    //reset password
    $("#reset_password").on("click", function(e) {
        if($("#email").val() === "") {
            $(".data_response").empty();
            $(".data_response").append('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>Error! You must enter your email!</strong></div>');
        } else {
            $.ajax({
                url: base_url + "api.php",
                type: "POST",
                dataType: "json",
                data: {
                    action: "forget_password",
                    email: $("#email").val(),
                    security_token: security_token
                },
                success: function(data) {
                    location.replace(base_url+ "login/?msg=Please check your email for new password!");
                }
            });
        }
        e.preventDefault();
    });

    $(".change_password").on("click", function(e) {
        if($("#c_password").val() === "" || $("#new_password").val() === "" || $("#re_new_password").val() === "") {
            $(".re_pass_err").text("You must fill all the fields!");
        } else {
            $(".re_pass_err").text("");
            $(".bg_wait").show();
            $.ajax({
                type: "POST",
                url: "http://localhost/WebTech/Project/api.php",
                dataType: "json",
                data: {
                    action: "change_password",
                    current_password: $("#c_password").val(),
                    new_password: $("#new_password").val(),
                    re_new_password: $("#new_password").val(),
                    security_token: security_token
                },
                success: function(data) {
                    if(data.response === "success") {
                        alert("Password changed successfully! Please login again!");
                        location.replace(base_url +"logout/");
                    } else if(data.response === "failed") {
                        $(".re_pass_err").text(data.msg);
                    } else {
                        $(".re_pass_err").text("Unknown error occurred!");
                    }
                    $(".bg_wait").hide();
                }
            });
        }
        e.preventDefault();
    });

    //personal assistant
    $("#manage_personal_assistant_container").on("click", function() {
        $(".assist_search_container").css({ "visibility": "visible", "opacity": 1 });
    });

    $(".assist_search_box_close").on("click", function() {
        $(".assist_search_container").css({ "visibility": "hidden", "opacity": 0 });
    });

    $("#assist_search_input_box").on("keyup", function() {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            $.ajax({
                url: "http://en.wikipedia.org/w/api.php",
                dataType: "jsonp",
                data: {
                    'action': "opensearch",
                    'format': "json",
                    'search': $("#assist_search_input_box").val()
                },
                success: function(data) {
                    $(".keyword_suggestion_list").empty();
                    $(".keyword_suggestion").show();
                    if(typeof data[1] !== 'undefined') {
                        $.each(data[1], function(index, value) {
                            $(".keyword_suggestion_list").append('<li data-description="'+ data[2][index] +'" data-wiki="'+ data[3][index] +'">Tell me about '+ value +'</li>');
                        });
                    }
                }
            });
        }, 500);
    });

    $(document).on('click', '.keyword_suggestion_list>li', function() {
        $(".keyword_suggestion").hide();
        $("#assist_search_result_container").empty();
        $("#assist_search_result_container").append('<h3>'+ $(this).text() +'</h3><hr><p>'+ $(this).attr("data-description") +'</p><p><a target="_blank" href="'+ $(this).attr("data-wiki") +'">Click here</a> to know more about this.</p>');
    });

    //check and load filemanager
    if (typeof load_fileManager !== 'undefined' && load_fileManager !== null && load_fileManager === true) {
        setupFileManager();
    }
});
function getTotalFileSize(file) {
    var size = 0;
    for(i = 0; i < file.length; i++) {
        size += file[i].size;
    }
    return size;
}
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
window.onload = function() {
    $(".main_data_container").height($(this).height() - ($(".navbar").height() - $(".footer").height()));
    $(".bg_wait").delay(2000).hide();
    $(window).resize(function() {
        $(".main_data_container").height($(this).height() - ($(".navbar").height() - $(".footer").height()));
    });

    //customize moment js time string
    moment.updateLocale('en', {
        relativeTime: {
            future: "in %s",
            past:   "%s ago",
            s:  "seconds",
            m:  "1 minute",
            mm: "%d minutes",
            h:  "1 hour",
            hh: "%d hours",
            d:  "1 day",
            dd: "%d days",
            M:  "1 month",
            MM: "%d months",
            y:  "1 year",
            yy: "%d years"
        }
    });

    //get notification updates
    if (typeof load_fileManager !== 'undefined' && load_fileManager !== null && load_fileManager === true) {
        setInterval(function() {
            $.ajax({
                type: "POST",
                url: "http://localhost/WebTech/Project/api.php",
                dataType: "json",
                data: {
                    action: "get_notifications",
                    security_token: security_token
                },
                success: function(data) {
                    if(data.response === "success") {
                        if(data.count > 0) {
                            $(".notification_container .unread").empty();
                            for(i = 0; i < data.count; i++) {
                                if(data.notifications[i].type === "S") {
                                    $(".notification_container .unread").append('<div class="alert alert-success"><strong>Success!</strong> '+ data.notifications[i].information +' <small class="t_format_notification" data-original-time="'+ data.notifications[i].added_at +'">'+ moment(data.notifications[i].added_at).fromNow() +'</small></div>');
                                } else if(data.notifications[i].type === "I") {
                                    $(".notification_container .unread").append('<div class="alert alert-info"><strong>Info!</strong> '+ data.notifications[i].information +' <small class="t_format_notification" data-original-time="'+ data.notifications[i].added_at +'">'+ moment(data.notifications[i].added_at).fromNow() +'</small></div>');
                                } else if(data.notifications[i].type === "W") {
                                    $(".notification_container .unread").append('<div class="alert alert-warning"><strong>Warning!</strong> '+ data.notifications[i].information +' <small class="t_format_notification" data-original-time="'+ data.notifications[i].added_at +'">'+ moment(data.notifications[i].added_at).fromNow() +'</small></div>');
                                } else if(data.notifications[i].type === "D") {
                                    $(".notification_container .unread").append('<div class="alert alert-danger"><strong>Failed!</strong> '+ data.notifications[i].information +' <small class="t_format_notification" data-original-time="'+ data.notifications[i].added_at +'">'+ moment(data.notifications[i].added_at).fromNow() +'</small></div>');
                                }
                            }
                            $(".notification_counter").text(data.count);
                            $(".notification_counter").show();
                        }
                    }
                }
            });
        }, 8000);

        //update notification time
        setInterval(function() {
            $(".t_format_notification").each(function(){
                $(this).text(moment($(this).attr("data-original-time")).fromNow());
            });
        }, 3000);
    }
};