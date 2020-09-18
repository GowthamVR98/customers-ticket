<?php
include 'includes/admin-config.php';
include_once 'auth.php';
include 'includes/emp-crypto-graphy.php';
$dbobj = new GetCommomOperation();
$SafeCrypto = new SafeCrypto();
if(isset($_POST['btnskill'])){
    $checkExists = $dbobj->check_exist($dbh, TBL_PRIFIX.'course_master',array('course_id'), 'course = ?', array(trim($_POST['txtSkill'])));
    if($checkExists){
        $_SESSION['error'] = 'course already exist.';
        header('location:alumni-add-course.php');
        exit(0);
    }else{
        $loginSessionId = $dbobj->getSessionId();
        $values = array(trim($_POST['txtCode']),trim($_POST['txtSkill']),trim($_POST['sltCourseType']),trim($_POST['txtCount']),CURRENT_DT,$loginSessionId,$_SERVER['REMOTE_ADDR']);
        $result = $dbobj->InsertQuery($dbh, TBL_PRIFIX.'course_master', array('course_code','course','course_type','year_sem_count','added_date','added_by','added_ip'), $values);
        if($result['status']){
            $_SESSION['success'] = 'New course has been added successfully.';
            header('location:alumni-add-course.php');
            exit(0);
        }else{
            $_SESSION['error'] = 'Something went to wrong.';
            header('location:alumni-add-course.php');
            exit(0);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include (ALUM_TEMPLATES.'metatag.php');?>
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
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
            <!-- Calender -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form id="form_validation" method="POST">
                            <div class="header">
                                <h2>Add Ticket</h2>
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <button class="btn btn-primary waves-effect" name="btnskill" type="submit">SUBMIT</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="body">
                                <div style="font-weight: 600;color: #000;padding: 1.25rem 0.625rem;">Ticket Information</div>
                                <hr>
                                <!-- Inline Layout -->
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <select class="form-control" name="sltDepartment" title="Select Department" required>
                                                <optgroup label="Select Department">
                                                <option value="Accounting">Accounting</option>
                                                <option value="Finance">Finance</option>
                                                <option value="Information Technology">Information Technology</option>
                                                <option value="Marketing">Marketing</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <select class="form-control" name="sltCategory" title="Select Category" required>
                                                <optgroup label="Select Category">
                                                <option value="Digital Marketing">Digital Marketing</option>
                                                <option value="Search Engine Marketing">Search Engine Marketing</option>
                                                <option value="Digital Marketing">Digital Marketing</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="txtSubject" required maxlength="60">
                                            <label class="form-label">Subject</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="txtPriority" required maxlength="60">
                                            <label class="form-label">Priority</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <textarea class="form-control" name="txtDescription" required maxlength="60"></textarea>
                                            <label class="form-label"> Description </label>
                                        </div>
                                    </div>
                                </div>
                                <div style="font-weight: 600;color: #000;padding: 1.25rem 0.625rem;">Contact Details</div>
                                <hr>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" value="<?php echo $getLoggedData['fullName'];?>" name="txtContName" required maxlength="60">
                                            <label class="form-label">Contact Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" value="<?php echo $getLoggedData['email_id'];?>" name="txtEmail" required maxlength="60">
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" value="<?php echo $getLoggedData['mobile_number'];?>" name="txtPhone" required maxlength="60">
                                            <label class="form-label">Phone</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!-- #END# Inline Layout -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- #END# Calender -->
        </div>
    </section>
    <?php include ALUM_TEMPLATES.'footer.php';?> 
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <script>
        $(document).ready(function(){
            $('[name="sltCourseType"]').selectpicker();
        });
    </script>
</body>

</html>
