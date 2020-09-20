<?php
    include 'includes/admin-config.php';
    include_once 'auth.php';
	$dbobj = new GetCommomOperation();
    $loginSessionId = $dbobj->getSessionId();
    if(isset($_POST['txtUpdate'])){
        $usrData = $dbobj->getUserData($dbh, $loginSessionId);
        if(!empty($_FILES['empProfilepic']['name'])){
            $fileUpload = $dbobj->fileUpload(ALUM_ABS_PATH.ALUM_COMMON_UPLOADS.ALUM_PROFILE, $_FILES['empProfilepic']);
        }else{
             $fileUpload = $usrData['values'][0]['profile_pic'];
        }
        $values = array(trim($_POST['txtfname']),trim($_POST['txtlname']),
                        trim($_POST['gender']),SystemDate::date(trim($_POST['txtDob'])),
                        trim($_POST['txtmobile']),$fileUpload,
                        trim($_POST['txtProfession']),trim($_POST['sltblood']),
                        trim($_POST['sltCourse']),implode(',', $_POST['sltSkills']),
                        trim($_POST['txtPresent']),trim($_POST['txtPermanent']),
                        trim($_POST['txtCity']),trim($_POST['txtState']),trim($_POST['txtCountry']),
                        trim($_POST['txtPincode']),trim($_POST['txtMaritalStatus']),
                        CURRENT_DT,$loginSessionId,$_SERVER['REMOTE_ADDR'],$loginSessionId);
        $result = $dbobj->UpdatetData($dbh, TBL_PRIFIX.'user_login_details', 
                        'fname = ?,lname = ?,gender = ?,dob = ?,mobile_number = ?,
                        profile_pic = ?,profession = ?,blood_group = ?,course_id = ?,
                        skills_id = ?,present_address = ?,permanent_address = ?,city = ?,
                        state = ?,country = ?,pincode = ?,merital_status = ?,date_modified = ?,
                        modified_by = ?,modified_ip = ?','user_id = ?', $values);
        if($result){
            $_SESSION['success'] = 'Profile details has been added successfully.';
            header('location:profile.php');
            exit(0);
        }else{
             $_SESSION['warning'] = 'No changes Done.';
            header('location:profile.php');
            exit(0);
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php include (ALUM_TEMPLATES.'metatag.php');?>
   <!-- Bootstrap Select Css -->
   <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
   <style>
        .image-view {
        height: 90px;
        width: 100%;
        border-radius: 50px;
        }
        .profile-image {
        border-radius: 50px;
        border: 5px solid rgba(224, 233, 239, 0.4);
        height: 100px;
        width: 100px;
        background-position: center center;
        background-size: cover;
        margin: 0 auto 5px auto;
        }
        .profile-image:hover .upload{
            display: block;
        }
        .hover {
        border: 5px solid rgba(224, 233, 239, 0.9);
        width: 100px;
        border-radius: 50px;
        position: relative;
        overflow: hidden;
        }
        .upload {
        opacity: 0.8;
        background: gainsboro;
        height: 25px;
        margin: 65px auto;
        text-align: center;
        color: black;
        position: absolute;
        display: none;
        width: 100%;
        }
   </style>
</head>

<body class="theme-red">
    <?php
        // <!-- Page Loader -->
        include ALUM_TEMPLATES.'loader.php';
        // <!-- #END# Page Loader -->
        // <!-- Top Bar -->
            include ALUM_TEMPLATES.'top-navigation.php';
        // <!-- #Top Bar -->
        // <!-- Left Sidebar -->
            include ALUM_TEMPLATES.'left-links.php';
        // <!-- #Left Sidebar -->
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12">
                    <div class="card">
                        <div class="body">
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#profile_settings" aria-controls="settings" role="tab" data-toggle="tab"><strong>Profile Details</strong></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                            <div class="col-sm-12">
                                            <div class="profile-image hover">
                                                <p class="upload">Upload</p>
                                                <img id="yourBtn" src="<?php echo (!empty($getLoggedData['profile_pic']) && $getLoggedData['profile_pic'] != NULL ? ALUM_WS_UPLOADS.ALUM_PROFILE.$getLoggedData['profile_pic'] : ALUM_WS_IMAGES.'user.png');?>" class="image-view" alt="Invalid Image" />
                                                <input class="my-images" value="" id="upfile" name="empProfilepic" mandatory="false" type="file" onchange="readURL(this);">
                                            </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="txtfname" value="<?php echo $getLoggedData['fname'];?>" title="First Name" required maxlength="30">
                                                        <label class="form-label">First Name</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="txtlname" value="<?php echo $getLoggedData['lname'];?>" title="Last Name" maxlength="30">
                                                        <label class="form-label">Last Name</label>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-sm-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="email" class="form-control" title="Email" disabled value="<?php echo (!empty($getLoggedData['email_id']) ? $getLoggedData['email_id'] : '');?>" name="txtEmail" required maxlength="35">
                                                        <label class="form-label">Mail Id</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" title="Mobile Number" value="<?php echo (!empty($getLoggedData['mobile_number']) ? $getLoggedData['mobile_number'] : '');?>" name="txtmobile" required maxlength="15">
                                                        <label class="form-label">Mobile Number</label>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="txtUpdate"class="btn btn-success pull-right">UPDATE</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include ALUM_TEMPLATES.'footer.php';?>
    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var regex = /^([a-zA-Z0-9\-\)\(\}\s_\\.\-:])+(.jpg|.png|.jpeg)$/;
                console.log(input.files);
                var fileSizeValue = 10485760;
                if (regex.test(input.files[0].name.toLowerCase())) {
                    if (input.files[0].size < fileSizeValue) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#yourBtn').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
            }
        }
        $('#yourBtn').click(function(){
            $('#upfile').trigger('click');
        });
        $(function () {
            $('[name="sltSkills"]').selectpicker();
            $('[name="sltblood"]').selectpicker();
            $('[name="sltCourse"]').selectpicker();
            $('#bs_datepicker_container input').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                container: '#bs_datepicker_container'
            });
            $('.NotVisiblePaassword').click(function(){
                $(this).hide().parent().parent().find('.VisiblePaassword').show().parent().parent().parent().parent().find('input').attr('type','text');
            });
            $('.VisiblePaassword').click(function(){
                $(this).hide().parent().parent().find('.NotVisiblePaassword').show().parent().parent().parent().parent().find('input').attr('type','password');
            });
            form.validate({
                highlight: function (input) {
                    $(input).parents('.form-line').addClass('error');
                },
                unhighlight: function (input) {
                    $(input).parents('.form-line').removeClass('error');
                },
                errorPlacement: function (error, element) {
                    $(element).parents('.form-group').append(error);
                },
                rules: {
                    'confirm': {
                        equalTo: '#password'
                    }
                }
            });
        });
    </script>
</body>

</html>
