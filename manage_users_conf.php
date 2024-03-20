<?php  
//Connect to database
require'connectDB.php';
require'send_sms_register.php'; 


// select passenger 
if (isset($_GET['select'])) {

    $Finger_id = $_GET['Finger_id'];

    $sql = "SELECT fingerprint_select FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select";
        exit();
    }
    else{
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {

            $sql="UPDATE users SET fingerprint_select=0";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_Select";
                exit();
            }
            else{
                mysqli_stmt_execute($result);

                $sql="UPDATE users SET fingerprint_select=1 WHERE fingerprint_id=?";
                $result = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error_select_Fingerprint";
                    exit();
                }
                else{
                    mysqli_stmt_bind_param($result, "s", $Finger_id);
                    mysqli_stmt_execute($result);

                    echo "User Fingerprint selected";
                    exit();
                }
            }
        }
        else{
            $sql="UPDATE users SET fingerprint_select=1 WHERE fingerprint_id=?";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_select_Fingerprint";
                exit();
            }
            else{
                mysqli_stmt_bind_param($result, "s", $Finger_id);
                mysqli_stmt_execute($result);

                echo "User Fingerprint selected";
                exit();
            }
        }
    } 
}
if (isset($_POST['Add'])) {
     
    $Uname = $_POST['name'];
    $IdNumber = $_POST['idcardnumber'];
    $Address = $_POST['address'];
    $DOB = $_POST['birthday'];
    $Gender = $_POST['gender'];
    $Phone = $_POST['phonenumber'];
    $WhatsappNo = $_POST['whatsappno'];
    $Height = $_POST['userheight'];
    $Weight = $_POST['userweight'];
    $RegDate = $_POST['registerdate'];
    $MemberNo = $_POST['memberno'];


    $sql = "SELECT memberno FROM users WHERE memberno=?";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error";
        exit();
    } else {
        mysqli_stmt_bind_param($result, "s", $MemberNo);
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {
            echo "Duplicate MemberNo is not allowed!";
            exit();
        }
    }
    
    $sql = "SELECT username FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
      echo "SQL_Error";
      exit();
    }
    else{
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {

            if (empty($row['username'])) {

                if (!empty($Uname) && !empty($Address) && !empty($DOB) && !empty($Gender) && !empty($Phone) &&!empty($WhatsappNo) && !empty($RegDate) && !empty($MemberNo)){
                    //check if there any user had already the Serial Number
                    $sql = "SELECT idcardnumber FROM users WHERE idcardnumber=?";

                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    }

                    else{

                        
                        mysqli_stmt_bind_param($result, "d", $Number);
                        mysqli_stmt_execute($result);
                        $resultl = mysqli_stmt_get_result($result);
                        if (!$row = mysqli_fetch_assoc($resultl)) {
                            $sql="UPDATE users SET username=?, idcardnumber=?, useraddress=?, birthday=?,gender=?,phonenumber=?,whatsappno=?,userheight=?,userweight=?, registerdate=? ,memberno=? WHERE fingerprint_select=1";
                            $result = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($result, $sql)) {
                                echo "SQL_Error_select_Fingerprint";
                                exit();
                            }


                            else{
                                mysqli_stmt_bind_param($result, 'ssssssiiisi', $Uname, $IdNumber, $Address, $DOB,$Gender, $Phone ,$WhatsappNo ,$Height ,$Weight ,$RegDate ,$MemberNo );
                                mysqli_stmt_execute($result);


                                 // Send SMS
                                 $message_sms = "Hi, $Uname ! ,Welcome to the Colour Fitness Club.Its time to train. your member no is $MemberNo.Thank you. ";
                                 sendSMSToMember($MemberNo, $message_sms,$Phone);


                                echo "A new User has been added!";
                                exit();
                            }
                        }
                        else {
                            echo "The serial number is already taken!";
                            exit();
                        }
                    }
                }
                else{
                    echo "Please fill the all fields correctly";
                    exit();
                }
            }
            else{
                echo "This Fingerprint is already added";
                exit();
            }    
        }
        else {
            echo "There's no selected Fingerprint!";
            exit();
        }
    }
}
//Add user Fingerprint
if (isset($_POST['Add_fingerID'])) {

    $fingerid = $_POST['fingerid'];

    if ($fingerid == 0) {
        echo "Enter a Fingerprint ID!";
        exit();
    }
    else{
        if ($fingerid > 0 && $fingerid < 1000) {
            $sql = "SELECT fingerprint_id FROM users WHERE fingerprint_id=?";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
              echo "SQL_Error";
              exit();
            }
            else{
                mysqli_stmt_bind_param($result, "i", $fingerid );
                mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
                if (!$row = mysqli_fetch_assoc($resultl)) {

                    $sql = "SELECT add_fingerid FROM users WHERE add_fingerid=1";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                      echo "SQL_Error";
                      exit();
                    }
                    else{
                        mysqli_stmt_execute($result);
                        $resultl = mysqli_stmt_get_result($result);
                           if (!is_array($fingerid)) {
                     $fingerid = [$fingerid];
}

                    $sql = "INSERT INTO users (fingerprint_id, add_fingerid) VALUES (?, 1)";
                    $result = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($result, $sql)) {
                    echo "SQL_Error";
                    exit();
                  } else {
                    // Loop through each $fingerid and insert it into the database
                    foreach ($fingerid as $id) {
                    mysqli_stmt_bind_param($result, "i", $id);
                    mysqli_stmt_execute($result);
                    }

                  echo "The IDs are ready to get new fingerprints";
                  exit();
}
                       
                    }   
                }
                else{
                    echo "This ID is already exist!";
                    exit();
                }
            }
        }
        else{
            echo "The Fingerprint ID must be between 1 & 1000";
            exit();
        }
    }
}
// Update an existance user 
if (isset($_POST['Update'])) {

      
    $Uname = $_POST['name'];
    $IdNumber = $_POST['idcardnumber'];
    $Address = $_POST['address'];
    $DOB = $_POST['birthday'];
    $Gender = $_POST['gender'];
    $Phone = $_POST['phonenumber'];
    $WhatsappNo = $_POST['whatsappno'];
    $Height = $_POST['userheight'];
    $Weight = $_POST['userweight'];
    $RegDate = $_POST['registerdate'];
    $MemberNo = $_POST['memberno'];

    if ($IdNumber == 0) {
        $IdNumber = -1;
    }
    //check if there any selected user
    $sql = "SELECT * FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
      echo "SQL_Error";
      exit();
    }
    else{
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {

            if (empty($row['username'])) {
                echo "First, You need to add the User!";
                exit();
            }
            else{
                if (!empty($Uname) && !empty($IdNumber) && !empty($Address) && !empty($DOB) &&!empty($Gender) && !empty($Phone) && !empty($WhatsappNo) && !empty($Height) && !empty($Weight) && !empty($RegDate) && !empty($MemberNo)){
                    echo "Empty Fields";
                    exit();
                }
                else{
                    //check if there any user had already the Serial Number
                    $sql = "SELECT idcardnumber FROM users WHERE idcardnumber=?";
                    $result = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($result, $sql)) {
                        echo "SQL_Error";
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($result, "s", $IdNumber);
                        mysqli_stmt_execute($result);
                        $resultl = mysqli_stmt_get_result($result);

                            if (!empty($Uname) && !empty($IdNumber) && !empty($Address) && !empty($DOB) && !empty($Phone) && !empty($WhatsappNo) && !empty($Height) && !empty($Weight) && !empty($RegDate) && !empty($MemberNo)) {

                                $sql="UPDATE users SET username=?, idcardnumber=?, useraddress=?, birthday=?,gender=?, phonenumber=? ,whatsappno=?,userheight=?,userweight=?,registerdate=? ,memberno=? WHERE fingerprint_select=1";
                                $result = mysqli_stmt_init($conn);
                                if (!mysqli_stmt_prepare($result, $sql)) {
                                    echo "SQL_Error_select_Fingerprint";
                                    exit();
                                }
                                else{
                                    mysqli_stmt_bind_param($result, 'ssssssiiisi', $Uname, $IdNumber, $Address, $DOB,$Gender, $Phone,$WhatsappNo ,$Height ,$Weight ,$RegDate,$MemberNo );
                                    mysqli_stmt_execute($result);

                                    echo "The selected User has been updated!";
                                    exit();
                                }
                            }
                            else{
                                if (empty($registerdate)) {
                                    $sql="UPDATE users SET username=?, idcardnumber=?, useraddress=?, birthday=?,gender=?, phonenumber=? ,whatsappno=?, userheight=?,userweight=?,registerdate=? ,memberno=? WHERE fingerprint_select=1";
                                    $result = mysqli_stmt_init($conn);
                                    if (!mysqli_stmt_prepare($result, $sql)) {
                                        echo "SQL_Error_select_Fingerprint";
                                        exit();
                                    }
                                    else{
                                        mysqli_stmt_bind_param($result, 'sssssiiisi', $Uname, $IdNumber, $Address, $DOB, $Gender, $Phone ,$WhatsappNo,$Height ,$Weight ,$RegDate ,$MemberNo );
                                        mysqli_stmt_execute($result);

                                        echo "Now You can selected User has been update!";
                                        exit();
                                    }
                                }
                                else{
                                    echo "The User Time-In is empty!";
                                    exit();
                                }    
                            }  
                       
                    }
                }
            }    
        }
        else {
            echo "There's no selected User to update!";
            exit();
        }
    }
}
// delete user 
if (isset($_POST['delete'])) {

    $sql = "SELECT fingerprint_select FROM users WHERE fingerprint_select=1";
    $result = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($result, $sql)) {
        echo "SQL_Error_Select";
        exit();
    }
    else{
        mysqli_stmt_execute($result);
        $resultl = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($resultl)) {
            $sql="UPDATE users SET username='',idcardnumber='', useraddress='', birthday='',gender='', phonenumber='',whatsappno='' ,userheight='',userweight='',registerdate='', memberno='', del_fingerid=1 WHERE fingerprint_select=1";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo "SQL_Error_delete";
                exit();
            }
            else{
                mysqli_stmt_execute($result);
                echo "The User Fingerprint has been deleted";
                exit();
            }
        }
        else{
            echo "Select a User to remove";
            exit();
        }
    }
}
?>
