<?php
    class personal_assistant {
        //defined variables
        private $dbhost = "localhost";
        private $dbname = "personal_assistant";
        private $dbuser = "personal_assistant";
        private $dbpass = "3q13q112";
        public $base_url = "http://localhost/WebTech/Project/";
        public $base_directory = "/WebTech/Project/";
        public $file_base_url = "content_data/uploads";
        private $password_option = [ 'salt' => "1O9wmRjrwAVXD98HNOgsNpDczlqm3Jq7KnEd1" ];
        private $db = null;

        /**
         * Construct objects
        **/
        public function __construct() {
            date_default_timezone_set('Asia/Dhaka');
            try {
                $this->db = $this->connectDb();
                $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql1 = 'CREATE TABLE IF NOT EXISTS users(
                            id INT NOT NULL AUTO_INCREMENT,
                            email VARCHAR(255) NOT NULL,
                            password VARCHAR(255) NOT NULL,
                            type VARCHAR(15) NOT NULL,
                            first_name VARCHAR(100) NOT NULL,
                            last_name VARCHAR(100) NOT NULL,
                            account_status VARCHAR(12) NOT NULL DEFAULT "Active",
                            image_url VARCHAR(512) NOT NULL DEFAULT "/content_data/profile_pics/default.png",
                            joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (id)
                        ) ENGINE=InnoDB CHARACTER SET utf8';

                $sql2 = 'CREATE TABLE IF NOT EXISTS security_tokens(
                            id VARCHAR(8) NOT NULL,
                            fid INT NOT NULL,
                            password_hash VARCHAR(255) NOT NULL,
                            hast_meta_info LONGTEXT NOT NULL,
                            deleted ENUM("0", "1") DEFAULT "0" NOT NULL,
                            time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY(id),
                            FOREIGN KEY (fid) REFERENCES users(id)
                        ) ENGINE=InnoDB CHARACTER SET utf8';

                $sql3 = 'CREATE TABLE IF NOT EXISTS datatable(
                            id BIGINT(20) NOT NULL AUTO_INCREMENT,
                            data_key VARCHAR(255) NOT NULL,
                            data_value LONGTEXT NOT NULL,
                            PRIMARY KEY(id),
                            UNIQUE INDEX ukey(data_key)
                        ) ENGINE=InnoDB CHARACTER SET utf8';

                $sql4 = 'CREATE TABLE IF NOT EXISTS schedule_data(
                            id VARCHAR(8) NOT NULL,
                            fid INT NOT NULL,
                            schedule_title VARCHAR(255) NOT NULL,
                            start_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            end_at TIMESTAMP NOT NULL,
                            location VARCHAR(255) NOT NULL,
                            description LONGTEXT NOT NULL,
                            set_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            show_notification ENUM("1", "0") DEFAULT "1" NOT NULL,
                            enable_sound ENUM("1", "0") DEFAULT "1" NOT NULL,
                            deleted ENUM("0", "1") DEFAULT "0" NOT NULL,
                            PRIMARY KEY(id),
                            FOREIGN KEY (fid) REFERENCES users(id)
                        ) ENGINE=InnoDB CHARACTER SET utf8';

                $sql5 = 'CREATE TABLE IF NOT EXISTS data_file_info(
                            id VARCHAR(8) NOT NULL,
                            fid INT NOT NULL,
                            file_name VARCHAR(255) NOT NULL,
                            file_url VARCHAR(255) NOT NULL,
                            file_type VARCHAR(20) NOT NULL,
                            file_size VARCHAR(20) NOT NULL,
                            access_type VARCHAR(20) NOT NULL,
                            deleted ENUM("1", "0") DEFAULT "0" NOT NULL,
                            file_meta LONGTEXT NOT NULL,
                            uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY(id),
                            FOREIGN KEY (fid) REFERENCES users(id)
                        ) ENGINE=InnoDB CHARACTER SET utf8';

                $sql6 = 'CREATE TABLE IF NOT EXISTS notifications (
                            id VARCHAR(8) NOT NULL,
                            fid INT NOT NULL,
                            information TEXT NOT NULL,
                            type ENUM("S", "I", "W", "D") NOT NULL DEFAULT "W",
                            seen ENUM("Y", "N") NOT NULL DEFAULT "N",
                            added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            deleted ENUM("0","1") DEFAULT "0" NOT NULL,
                            PRIMARY KEY(id),
                            FOREIGN KEY (fid) REFERENCES users(id)
                        ) ENGINE=InnoDB CHARACTER SET utf8';

                $this->db->exec($sql1);
                $this->db->exec($sql2);
                $this->db->exec($sql3);
                $this->db->exec($sql4);
                $this->db->exec($sql5);
                $this->db->exec($sql6);
            } catch(Excertion $e) {
                die("<p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. We&rsquo;ll be back online shortly!</p>");
            }
        }

        //connect to database
        private function connectDb() {
            return new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser, $this->dbpass);
        }

        /**
         * Destruct objects
        **/
        public function __destruct() {
            $this->db = null;
        }

        //this function will be used to add new users to database
        public function addNewUser($first_name, $last_name, $email, $password) {
            $password = password_hash( $password, PASSWORD_DEFAULT, $this->password_option);
            $stmt = $this->db->prepare("INSERT INTO users(first_name, last_name, email, password, type) VALUES (:first_name, :last_name, :email, :password, :type)");
            if($stmt->execute(array(":first_name" => $first_name, ":last_name" => $last_name, ":email" => $email, ":password" => $password, ":type" => "Free"))) return true;
            else return false;
        }

        //check email if already exist in database
        public function checkEmailAddress($email) {
            $stmt = $this->db->prepare("SELECT id from users where email = :email LIMIT 1");
            $stmt->execute(array(":email" => $email));
            $chk = $stmt->fetch(PDO::FETCH_ASSOC);
            if($chk) return true;
            else return false;
        }

        //check user email and password combinations for login
        public function checkLoginDetails($email, $password) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND account_status = :account_status LIMIT 1");
            $stmt->execute(array(":email" => $email, ":account_status" => "Active"));
            $chk = $stmt->fetch(PDO::FETCH_ASSOC);
            //print_r($chk);
            if($chk) {
                if(password_verify($password, $chk['password'])) {
                    return $chk;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        //generate token for login
        public function generateLoginToken($id) {
            $randomId = $this->generateRandomUid(8);
            $new_secure_hash = hash('sha512', $randomId . $id . time());
            $u_id = sha1($id);
            $browser_type = $_SERVER['HTTP_USER_AGENT'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $hast_meta_info = json_encode(array("browser" => $browser_type, "ip" => $ip));
            $stmt = $this->db->prepare("INSERT INTO security_tokens(id, fid, password_hash, hast_meta_info) VALUES (:id, :fid, :password_hash, :hast_meta_info)");
            if($stmt->execute(array(":id" => $randomId, ":fid" => $id, ":password_hash" => $new_secure_hash, ":hast_meta_info" => $hast_meta_info))) {
                $this->addUserNotification($id, "New login detected from IP: ". $ip ."!", "W");
                return $new_secure_hash;
            }
            else return false;
        }

        //generates unique id. default length: 8
        public function generateRandomUid($length = 8) {
            $str = "";
            $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
            $max = count($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $str .= $characters[$rand];
            }
            return $str;
        }

        //validate security token
        public function validateSecurityToken($u_id, $s_token) {
            $stmt = $this->db->prepare("Select * from users where id in (SELECT fid from security_tokens where sha1(fid) = :u_id AND password_hash = :password_hash AND deleted = :status) LIMIT 1");
            $stmt->execute(array(":u_id" => $u_id ,":password_hash" => $s_token, ":status" => "0"));
            $chk = $stmt->fetch(PDO::FETCH_ASSOC);
            if($chk) {
                return $chk;
            } else {
                return false;
            }
        }

        //check if user logged in
        public function userLoggedIn() {
            if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true && isset($_SESSION['first_name']) && isset($_SESSION['last_name']) && isset($_SESSION['email']) && isset($_SESSION['type']) && isset($_SESSION['type']) && isset($_SESSION['joined_at']) && isset($_SESSION['user_id'])) return true;
            else return false;
        }

        //logout user
        public function logoutUserSession($u_id, $token) {
            //$stmt = $this->db->prepare("DELETE FROM security_tokens WHERE sha1(fid) = :fid AND password_hash = :password_hash");
            $stmt = $this->db->prepare("UPDATE security_tokens SET deleted = :status WHERE sha1(fid) = :fid AND password_hash = :password_hash");
            $stmt->execute(array(":status" => "1", ":fid" => $u_id ,":password_hash" => $token));
        }

        //upload user profile picture
        public function uploadProfilePicture($u_id, $file) {
            $imageMimeTypes = array (
                'image/png',
                'image/gif',
                'image/jpeg',
                'image/bmp',
                'image/jpg'
            );

            $fileMimeType = mime_content_type($file['tmp_name']);

            if (in_array($fileMimeType, $imageMimeTypes)) {
                if($file['size'] > 524288) {
                    return array("response" => "error", "msg" => "File must be less than 512KB!");
                } else {
                    $image_url = "content_data/profile_pics/". $u_id .".". pathinfo($file['name'], PATHINFO_EXTENSION);
                    if(move_uploaded_file($file['tmp_name'], $image_url)) {
                        $stmt = $this->db->prepare("UPDATE users SET image_url = :image_url WHERE id = :id");
                        if($stmt->execute(array(":image_url" => "/". $image_url ,":id" => $u_id))) {
                            $this->addUserNotification($u_id, "Profile picture changed successfully!", "I");
                            return array("response" => "success", "msg" => "Profile picture changed successfully!", "image_url" => $image_url);
                        }
                    } else {
                        return array("response" => "error", "msg" => "Unknown error occurred!");
                    }
                }
            } else {
                return array("response" => "error", "msg" => "Invalid file extension!");
            }
        }

        //add user notification
        public function addUserNotification($u_id, $information, $type = "I") {
            $randomId = $this->generateRandomUid(8);
            $stmt = $this->db->prepare("INSERT into notifications (id, fid, information, type) values(:id, :fid, :information, :type)");
            $stmt->execute(array(":id" => $randomId ,":fid" => $u_id, ":information" => $information, ":type" => $type));
        }

        //delete profile picture
        public function deleteProfilePicture($u_id, $file) {
            try {
                if(!@unlink($file)) return false;
                $stmt = $this->db->prepare("UPDATE users SET image_url = :image_url WHERE id = :id");
                $stmt->execute(array(":image_url" => "/content_data/profile_pics/default.png" ,":id" => $u_id));
                $this->addUserNotification($u_id, "Your profile picture deleted!", "W");
                return true;
            } catch(Exception $e) {
                return false;
            }
        }

        //update profile name
        public function updateProfileName($u_id, $new_first_name, $new_last_name) {
            try {
                $stmt = $this->db->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :id");
                $stmt->execute(array(":first_name" => $new_first_name, ":last_name" => $new_last_name ,":id" => $u_id));
                $this->addUserNotification($u_id, "Your profile name changed!", "W");
                return true;
            } catch(Exception $e) {
                return false;
            }
        }

        //update email address
        public function updateEmailAddress($u_id, $email) {
            try {
                $stmt = $this->db->prepare("UPDATE users SET email = :email WHERE id = :id");
                $stmt->execute(array(":email" => $email, ":id" => $u_id));
                $this->addUserNotification($u_id, "Your email address changed!", "W");
                return true;
            } catch(Exception $e) {
                return false;
            }
        }

        //deactivate account
        public function deactivateAccount($u_id) {
            try {
                $stmt = $this->db->prepare("UPDATE users SET account_status = :account_status WHERE id = :id");
                $stmt->execute(array(":account_status" => "Deactivate", ":id" => $u_id));
                $this->addUserNotification($u_id, "Account deactivated successfully!", "W");
                return true;
            } catch(Exception $e) {
                return false;
            }
        }

        //get file count for account
        public function getFileCount($u_id) {
            try {
                $stmt = $this->db->prepare("SELECT count(fid) as total FROM data_file_info WHERE fid = :fid AND deleted = :deleted");
                $stmt->execute(array(":fid" => $u_id, ":deleted" => "0"));
                return $stmt->fetchColumn(0);;
            } catch(Exception $e) {
                return false;
            }
        }

        //get schedule count for account
        public function getScheduleCount($u_id) {
            try {
                $stmt = $this->db->prepare("SELECT count(fid) as total FROM schedule_data WHERE fid = :fid AND deleted = :deleted AND start_at > now()");
                $stmt->execute(array(":fid" => $u_id, ":deleted" => "0"));
                return $stmt->fetchColumn(0);
            } catch(Exception $e) {
                return false;
            }
        }

        //upload files
        public function uploadFiles($u_id, $files) {
            if($this->checkFileSize($files) > 26214400) {
                return array("response" => "failed", "msg" => "File size exceed 25MB!");
            } else {
                $directoryName = str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $u_id;
                if(!is_dir($directoryName)){
                    mkdir($directoryName, 0755);
                }
                $stmt = $this->db->prepare("INSERT into data_file_info (id, fid, file_name, file_url, file_type, file_size, access_type, file_meta) values(:id, :fid, :file_name, :file_url, :file_type, :file_size, :access_type, :file_meta)");
                $flag_staus = false;
                foreach($files as $file) {
                    $file_url = $u_id ."/". time().uniqid(rand()) .".". pathinfo($file['name'], PATHINFO_EXTENSION);
                    if(move_uploaded_file($file['tmp_name'], "content_data/uploads/". $file_url)) {
                        $counter = 0;
                        while(!$stmt->execute(array(":id" => $this->generateRandomUid(8),":fid" => $u_id, ":file_name" => $file['name'], ":file_url" => $file_url, ":file_type" => $file['type'], ":file_size" => $file['size'], ":access_type" => "Private", ":file_meta" => $file['name']))) {
                            if($counter > 5) {
                                $flag_status = true;
                                break;
                            }
                            $counter++;
                        }
                    } else {
                        $flag_status = true;
                    }
                }
                if($flag_staus) {
                    $this->addUserNotification($u_id, "Files uploaded successfully!", "F");
                    return array("response" => "failed", "msg" => "File upload partially or completely failed!");
                } else {
                    $this->addUserNotification($u_id, "Files uploaded successfully!", "S");
                    return array("response" => "success", "msg" => "Files uploaded successfully!");
                }
            }
        }

        //get all file size from file array
        public function checkFileSize($files) {
            return array_sum(array_column($files, 'size'));
        }

        //get all files by user
        public function getFilesByUser($u_id) {
            $stmt = $this->db->prepare("SELECT id, file_name, file_url, file_type, file_size, access_type, uploaded_at FROM data_file_info WHERE fid = :fid and deleted = :deleted");
            $stmt->execute(array(":fid" => $u_id, ":deleted" => "0"));
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        //get all schedules by user
        public function getSchedulesByUser($u_id) {
            $stmt = $this->db->prepare("SELECT id, schedule_title, start_at, end_at, location, description, show_notification, enable_sound, set_at FROM schedule_data WHERE fid = :fid AND deleted = :deleted AND start_at > now()");
            $stmt->execute(array(":fid" => $u_id, ":deleted" => "0"));
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        //get file url by file id
        public function getFileById($file_id) {
            $stmt = $this->db->prepare("SELECT fid, file_name, file_url, file_type, access_type FROM data_file_info WHERE id = :file_id and deleted = :deleted");
            $stmt->execute(array(":file_id" => $file_id, ":deleted" => "0"));
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        //delete file by file id
        public function deleteFileById($file_id, $u_id) {
            try {
                //$stmt = $this->db->prepare("DELETE FROM data_file_info WHERE id = :id");
                $stmt = $this->db->prepare("UPDATE data_file_info SET deleted = :status WHERE id = :id AND fid = :fid");
                $res = $stmt->execute(array(":status" => "1", ":id" => $file_id, ":fid" => $u_id));
                $this->addUserNotification($u_id, "File deleted successfully!", "I");
                return $res;
            } catch(Exception $e) {
                return false;
            }
        }

        //delete file by file id
        public function changeFilePrivacy($u_id, $file_id, $access_type) {
            try {
                //$stmt = $this->db->prepare("DELETE FROM data_file_info WHERE id = :id");
                $stmt = $this->db->prepare("UPDATE data_file_info SET access_type = :access_type WHERE id = :id AND fid = :fid AND deleted = :deleted");
                $res = $stmt->execute(array(":access_type" => $access_type, ":id" => $file_id, ":fid" => $u_id, ":deleted" => "0"));
                $this->addUserNotification($u_id, "File privacy changed successfully!", "I");
                return $res;
            } catch(Exception $e) {
                return false;
            }
        }

        //add new schedule
        public function addNewSchedule($u_id, $schedule_title, $start_at, $end_at, $location, $description, $show_notification, $enable_sound) {
            $randomId = $this->generateRandomUid(8);
            $stmt = $this->db->prepare("INSERT INTO schedule_data(id, fid, schedule_title, start_at, end_at, location, description, show_notification, enable_sound) VALUES (:id, :fid, :schedule_title, :start_at, :end_at, :location, :description, :show_notification ,:enable_sound)");
            if($stmt->execute(array(":id" => $randomId, ":fid" => $u_id, ":schedule_title" => $schedule_title, ":start_at" => $start_at, ":end_at" => $end_at, ":location" => $location, ":description" => $description, ":show_notification" => $show_notification, ":enable_sound" => $enable_sound))) {
                $this->addUserNotification($u_id, "You have added new schedule successfully!", "S");
                return true;
            }
            else return false;
        }

        //set schedule notification on off
        public function setScheduleOnOff($u_id, $schedule_id, $status) {
            $stmt = $this->db->prepare("UPDATE schedule_data SET show_notification = :status WHERE id = :id AND fid = :fid");
            if($stmt->execute(array(":status" => $status, ":id" => $schedule_id,":fid" => $u_id))) {
                $this->addUserNotification($u_id, "Your schedule setting changed successfully!", "I");
                return true;
            }
            else return false;
        }

        //set sound on off
        public function setSoundOnOff($u_id, $schedule_id, $status) {
            $stmt = $this->db->prepare("UPDATE schedule_data SET enable_sound = :status WHERE id = :id AND fid = :fid");
            if($stmt->execute(array(":status" => $status, ":id" => $schedule_id,":fid" => $u_id))) {
                $this->addUserNotification($u_id, "Your schedule setting changed successfully!", "I");
                return true;
            }
            else return false;
        }

        //delete schedule
        public function deleteSchedule($u_id, $schedule_id) {
            try {
                //$stmt = $this->db->prepare("DELETE FROM schedule_data WHERE id = :id");
                $stmt = $this->db->prepare("UPDATE schedule_data SET deleted = :status WHERE id = :id AND fid = :fid");
                $res = $stmt->execute(array(":status" => "1", ":id" => $schedule_id, ":fid" => $u_id));
                $this->addUserNotification($u_id, "Your schedule deleted successfully!", "I");
                return $res;
            } catch(Exception $e) {
                return false;
            }
        }

        //get unread notifications
        public function getUnreadMessages($u_id) {
            $stmt = $this->db->prepare("SELECT information, type, added_at FROM notifications WHERE fid = :fid AND seen = :seen AND deleted = :deleted ORDER BY added_at DESC");
            $stmt->execute(array(":fid" => $u_id, ":seen" => "N",":deleted" => "0"));
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        //get already read notifications
        public function getReadMessages($u_id) {
            $stmt = $this->db->prepare("SELECT information, type, added_at FROM notifications WHERE fid = :fid AND seen = :seen AND deleted = :deleted ORDER BY added_at DESC");
            $stmt->execute(array(":fid" => $u_id, ":seen" => "Y",":deleted" => "0"));
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        //notifications seen
        public function notificationSeen($u_id) {
            $stmt = $this->db->prepare("UPDATE notifications SET seen = :seen WHERE fid = :fid AND seen = :n_seen AND deleted = :deleted");
            return $stmt->execute(array(":seen" => "Y", ":fid" => $u_id, ":n_seen" => "N",":deleted" => "0"));
        }

        //get search suggestion
        public function getSearchSuggestion($u_id, $keyword) {
            $stmt = $this->db->prepare("SELECT id, sha1(fid) as owner, file_name, file_url, file_type, file_size, access_type, uploaded_at from data_file_info WHERE file_name LIKE :keyword AND (fid = :fid OR access_type = :access_type) AND deleted = :deleted");
            if($stmt->execute(array(":keyword" => "%". $keyword ."%",":fid" => $u_id, ":access_type" => "Public", ":deleted" => "0"))) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        //reset password
        public function resetPassword($email) {
            $password = password_hash($this->generateRandomUid(8), PASSWORD_DEFAULT, $this->password_option);
            $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE email = :email AND account_status = :account_status");
            if($stmt->execute(array(":password" => $password, ":email" => $email, ":account_status" => "Active"))) return true;
            else return false;
        }

        //reset password
        public function changePassword($u_id, $password, $new_password) {
            $password = password_hash($password, PASSWORD_DEFAULT, $this->password_option);
            $stmt = $this->db->prepare("SELECT count(*) from users WHERE password = :password AND account_status = :account_status");
            $stmt->execute(array(":password" => $password, ":account_status" => "Active"));
            $r_p = $stmt->fetchColumn(0);
            if($r_p > 0) {
                $new_pass = password_hash($new_password, PASSWORD_DEFAULT, $this->password_option);
                $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id AND account_status = :account_status");
                if($stmt->execute(array(":password" => $new_pass, ":id" => $u_id , ":account_status" => "Active"))) {
                    $this->addUserNotification($u_id, "Your password changed successfully!", "W");
                    return array("response" => "success", "msg" => "Password changed successfully!");
                } else {
                    return array("response" => "failed", "msg" => "Password couldn't changed!");
                }
            } else {
                return array("response" => "failed", "msg" => "Current password didn't matched!");
            }
        }

        //get formatted time
        public function getFormatedTime($time) {
            try {
                if(is_numeric($time)) $time = date("Y-m-d H:i:s", $time);
                $date = new DateTime($time);
                $time = $date->getTimestamp();
                $time = time() - $time; // to get the time since that moment
                $time = ($time<1)? 1 : $time;
                $tokens = array (
                    31536000 => 'year',
                    2592000 => 'month',
                    604800 => 'week',
                    86400 => 'day',
                    3600 => 'hour',
                    60 => 'minute',
                    1 => 'second'
                );

                foreach ($tokens as $unit => $text) {
                    if ($time < $unit) continue;
                    $numberOfUnits = floor($time / $unit);
                    return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
                }
            } catch(Exception $e) {
                return "N/A";
            }
        }
    }
?>