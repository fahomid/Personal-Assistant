<?php
//print_r($_POST);
//exit();
session_start();
include_once("lib/functions.php");
if(isset($_POST['action']) && $_POST['action'] === "signup") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token'])) {
            if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['agree_terms'])) {
                $obj = new personal_assistant();
                if($_POST['first_name'] === "") {
                    print_r(json_encode(array("response" => "You must enter your first name!")));
                    exit();
                } else if($_POST['last_name'] === "") {
                    print_r(json_encode(array("response" => "You must enter your last name!")));
                    exit();
                } else if($_POST['email'] === "") {
                    print_r(json_encode(array("response" => "You must enter your email address!")));
                    exit();
                } else if($_POST['password'] === "") {
                    print_r(json_encode(array("response" => "You must enter a password!")));
                    exit();
                } else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    print_r(json_encode(array("response" => "You must enter a valid email address!")));
                    exit();
                } else if(strlen($_POST['password']) < 8) {
                    print_r(json_encode(array("response" => "Your password length must be greater than or equal 8!")));
                    exit();
                } else if($obj->checkEmailAddress($_POST['email'])) {
                    print_r(json_encode(array("response" => "Email already exist in our database!")));
                    exit();
                } else if($_POST['agree_terms'] !== "1") {
                    print_r(json_encode(array("response" => "You must agree with our term and conditions!")));
                    exit();
                } else {
                    if($obj->addNewUser($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'])) {
                        print_r(json_encode(array("response" => "success", "msg" => "You've successfully signed up!")));
                        exit();
                    } else {
                        print_r(json_encode(array("response" => "Unknown error occurred!")));
                        exit();
                    }
                }
            } else {
                print_r(json_encode(array("response" => "You must fill all the required fields!")));
                exit();
            }
        } else {
            print_r(json_encode(array("response" => "Invalid request!")));
            exit();
        }
    } else {
        print_r(json_encode(array("response" => "Invalid request!")));
        exit();
    }
} else if(isset($_POST['action']) && $_POST['action'] === "login") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token'])) {
            if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['remember_login'])) {
                $obj = new personal_assistant();
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    print_r(json_encode(array("response" => "You must enter a valid email address!")));
                    exit();
                } else if($_POST['email'] === "") {
                    print_r(json_encode(array("response" => "You must enter your email address!")));
                    exit();
                } else if($_POST['password'] === "") {
                    print_r(json_encode(array("response" => "You must enter your password!")));
                    exit();
                } else {
                    $res = $obj->checkLoginDetails($_POST['email'], $_POST['password']);
                    if($res) {
                        $_SESSION['isLoggedIn'] = true;
                        $_SESSION['first_name'] = $res['first_name'];
                        $_SESSION['last_name'] = $res['last_name'];
                        $_SESSION['email'] = $res['email'];
                        $_SESSION['type'] = $res['type'];
                        $_SESSION['type'] = $res['type'];
                        $_SESSION['joined_at'] = $res['joined_at'];
                        $_SESSION['user_id'] = $res['id'];
                        $_SESSION['secure_hash'] = $obj->generateLoginToken($res["id"]);
                        $_SESSION['image'] = $res['image_url'];
                        $_SESSION['account_status'] = $res['account_status'];
                        if($_POST['remember_login'] == "1") {
                            setcookie("user_token", sha1($res["id"]), time() + 60*60*24*30*12, "/");
                            setcookie("secure_hash", $_SESSION['secure_hash'], time() + 60*60*24*30*12, "/");
                        }
                        print_r(json_encode(array("response" => "success", "msg" => "Logged in successfully!")));
                        exit();
                    } else {
                        print_r(json_encode(array("response" => "Combination of username and password not found!")));
                        exit();
                    }
                }
            } else {
                print_r(json_encode(array("response" => "You must fill all the required fields!")));
                exit();
            }
        } else {
            print_r(json_encode(array("response" => "Invalid request!")));
            exit();
        }
    } else {
        print_r(json_encode(array("response" => "Invalid request!")));
        //print_r($_POST);
        exit();
    }
} else if(isset($_POST['action']) && $_POST['action'] === "profile_picture_upload") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            if(isset($_FILES['file'])) {
                $obj = new personal_assistant();
                $res = $obj->uploadProfilePicture($_SESSION['user_id'], $_FILES['file']);
                if($res['response'] === "success") {
                    print_r(json_encode(array("response" => "success", "msg" => $res['msg'], "image_url" => $res['image_url'])));
                    $_SESSION['image'] = $res['image_url'];
                    exit();
                } else if($res['response'] === "error") {
                    print_r(json_encode(array("response" => "failed", "msg" => $res['msg'])));
                    exit();
                } else {
                    print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred!")));
                    exit();
                }
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "profile_picture_delete") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if(hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            $obj = new personal_assistant();
            $path = str_replace('\\', '/', __DIR__);
            if($_SESSION['image'] === "/content_data/profile_pics/default.png") {
                print_r(json_encode(array("response" => "success", "msg" => "Profile picture deleted successfully!", "image_url" => $obj->base_url . "content_data/profile_pics/default.png")));
            } else {
                if($obj->deleteProfilePicture($_SESSION['user_id'], $path ."/". $_SESSION['image'])) {
                    $_SESSION['image'] = "/content_data/profile_pics/default.png";
                    print_r(json_encode(array("response" => "success", "msg" => "Profile picture deleted successfully!", "image_url" => $obj->base_url . "content_data/profile_pics/default.png")));
                } else {
                    print_r(json_encode(array("response" => "failed", "msg" => "Unable to delete profile picture! Please try again later!")));
                }
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "profile_name_update") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if(hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            if(isset($_POST['first_name']) && isset($_POST['last_name'])) {
                if($_POST['first_name'] === "" || $_POST['last_name'] === "") {
                    print_r(json_encode(array("response" => "failed", "msg" => "You must enter your first and last name!")));
                    exit();
                } else if($_POST['first_name'] === $_SESSION['first_name'] && $_POST['last_name'] === $_SESSION['last_name']) {
                    print_r(json_encode(array("response" => "success", "msg" => "Name changed successfully!", "name" => $_POST['first_name'] ." ". $_POST['last_name'])));
                    exit();
                } else {
                    $obj = new personal_assistant();
                    if($obj->updateProfileName($_SESSION['user_id'], $_POST['first_name'], $_POST['last_name'])) {
                        $_SESSION['first_name'] = $_POST['first_name'];
                        $_SESSION['last_name'] = $_POST['last_name'];
                        print_r(json_encode(array("response" => "success", "msg" => "Name changed successfully!", "name" => $_POST['first_name'] ." ". $_POST['last_name'])));
                        exit();
                    } else {
                        print_r(json_encode(array("response" => "failed", "msg" => "Unable to update profile name! Please try again later!")));
                        exit();
                    }
                }
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unable to update profile name! Please try again later!")));
                exit();
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "profile_email_update") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if(hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            if(isset($_POST['email'])) {
                if($_POST['email'] === "") {
                    print_r(json_encode(array("response" => "failed", "msg" => "You must enter your email address!")));
                    exit();
                } else if($_POST['email'] === $_SESSION['email']) {
                    print_r(json_encode(array("response" => "success", "msg" => "Email changed successfully!", "email" => $_POST['email'])));
                    exit();
                } else {
                    $obj = new personal_assistant();
                    if($obj->updateEmailAddress($_SESSION['user_id'], $_POST['email'])) {
                        $_SESSION['email'] = $_POST['email'];
                        print_r(json_encode(array("response" => "success", "msg" => "Email changed successfully!", "email" => $_POST['email'])));
                        exit();
                    } else {
                        print_r(json_encode(array("response" => "failed", "msg" => "Unable to update email address! Please try again later!")));
                        exit();
                    }
                }
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unable to update email address! Please try again later!")));
                exit();
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "deactivate_profile") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if(hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            $obj = new personal_assistant();
            if($obj->deactivateAccount($_SESSION['user_id'])) {
                print_r(json_encode(array("response" => "success", "msg" => "Account deactivated successfully!")));
                exit();
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unable handle your request! Please try again later!")));
                exit();
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "get_files_meta") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if(hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            $obj = new personal_assistant();
            $count = $obj->getFileCount($_SESSION['user_id']);
            print_r(json_encode(array("response" => "success", "count" => $count)));
            exit();
        } else {
            print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred while fetching the data! Please try again later!")));
            exit();
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "get_schedule_meta") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if(hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            $obj = new personal_assistant();
            $count = $obj->getScheduleCount($_SESSION['user_id']);
            print_r(json_encode(array("response" => "success", "count" => $count)));
            exit();
        } else {
            print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred while fetching the data! Please try again later!")));
            exit();
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "file_upload") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            if(isset($_FILES)) {
                $obj = new personal_assistant();
                print_r(json_encode($obj->uploadFiles($_SESSION['user_id'], $_FILES)));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "get_files") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            if(isset($_FILES)) {
                $obj = new personal_assistant();
                print_r(json_encode(array("response" => "success", "data" => $obj->getFilesByUser($_SESSION['user_id']))));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "delete_file") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['file_id'])) {
            $obj = new personal_assistant();
            if($obj->deleteFileById($_POST['file_id'], $_SESSION['user_id'])) {
                print_r(json_encode(array("response" => "success", "msg" => "File deleted successfully!")));
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred!")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "change_file_privacy") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['file_id']) && isset($_POST['access_type'])) {
            $obj = new personal_assistant();
            if($obj->changeFilePrivacy($_SESSION['user_id'], $_POST['file_id'], $_POST['access_type'])) {
                print_r(json_encode(array("response" => "success", "msg" => "File privacy changed successfully!")));
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred!")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "save_new_schedule") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['schedule_title']) && isset($_POST['start_at']) && isset($_POST['end_at']) && isset($_POST['event_location']) && isset($_POST['event_description']) && isset($_POST['show_notification']) && isset($_POST['enable_sound'])) {
            $obj = new personal_assistant();
            if($obj->addNewSchedule($_SESSION['user_id'], $_POST['schedule_title'], $_POST['start_at'], $_POST['end_at'], $_POST['event_location'], $_POST['event_description'], $_POST['show_notification'], $_POST['enable_sound'])) {
                print_r(json_encode(array("response" => "success", "msg" => "Schedule added successfully!")));
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred!")));
            }
        } else {
            print_r("Error in parameters!");
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "get_schedules") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            if(isset($_FILES)) {
                $obj = new personal_assistant();
                print_r(json_encode(array("response" => "success", "data" => $obj->getSchedulesByUser($_SESSION['user_id']))));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "set_notification") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['set_on_off']) && isset($_POST['schedule_id'])) {
            $obj = new personal_assistant();
            if($obj->setScheduleOnOff($_SESSION['user_id'], $_POST['schedule_id'], $_POST['set_on_off'])) {
                print_r(json_encode(array("response" => "success", "msg" => "Notification setting changed successfully!")));
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred!")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "set_enable_sound") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['set_on_off']) && isset($_POST['schedule_id'])) {
            $obj = new personal_assistant();
            if($obj->setSoundOnOff($_SESSION['user_id'], $_POST['schedule_id'], $_POST['set_on_off'])) {
                print_r(json_encode(array("response" => "success", "msg" => "Notification setting changed successfully!")));
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred!")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "delete_schedule") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['schedule_id'])) {
            $obj = new personal_assistant();
            if($obj->deleteSchedule($_SESSION['user_id'], $_POST['schedule_id'])) {
                print_r(json_encode(array("response" => "success", "msg" => "Schedule deleted successfully!")));
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "Unknown error occurred!")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "get_notifications") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            $obj = new personal_assistant();
            $res = $obj->getUnreadMessages($_SESSION['user_id']);
            print_r(json_encode(array("response" => "success", "count" => count($res), "notifications" => $res)));
            exit();
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "seen_notification") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id'])) {
            $obj = new personal_assistant();
            if($obj->notificationSeen($_SESSION['user_id'])) {
                print_r(json_encode(array("response" => "success")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "get_search_suggestion") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['keyword'])) {
            $obj = new personal_assistant();
            $result = $obj->getSearchSuggestion($_SESSION['user_id'], $_POST['keyword']);
            if($result) {
                print_r(json_encode(array("response" => "success", "results" => $result, "u_token" => sha1($_SESSION['user_id']))));
            } else {
                print_r(json_encode(array("response" => "failed", "msg" => "No result found!")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "forget_password") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_POST['email'])) {
            $obj = new personal_assistant();
            if($obj->resetPassword($_POST['email'])) {
                print_r(json_encode(array("response" => "success", "msg" => "Please check your email for new password!")));
            } else {
                print_r(json_encode(array("response" => "success", "msg" => "Please check your email for new password!")));
            }
        }
    }
} else if(isset($_POST['action']) && $_POST['action'] === "change_password") {
    if(isset($_SESSION['csrf_token']) && isset($_POST['security_token'])) {
        if (hash_equals($_SESSION['csrf_token'], $_POST['security_token']) && isset($_SESSION['user_id']) && isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['re_new_password'])) {
            if($_POST['new_password'] !== $_POST['re_new_password']) {
                print_r(json_encode(array("response" => "failed", "msg" => "Your confirmation password does not match!"))); 
            } else {
                $obj = new personal_assistant();
                $result = $obj->changePassword($_SESSION['user_id'], $_POST['current_password'], $_POST['new_password']);
                if($result['response'] === "success") {
                    print_r(json_encode(array("response" => "success", "msg" => $result['msg'])));
                } else {
                    print_r(json_encode(array("response" => "failed", "msg" => $result['msg'])));
                }
            }
        } else {
            print_r(json_encode(array("response" => "failed", "msg" => "You must fill all the required fields!")));
        }
    }
}
?>