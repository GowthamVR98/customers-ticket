<?php
include 'includes/admin-config.php';
include_once 'auth.php';
if(isset($_POST['btnAddTicket'])){
    $postData =  array(
        "subject" => trim($_POST['txtSubject']),
        "category" => $_POST['sltCategory'],
        "departmentId"=>$_POST['sltDepartment'],
        "contactId"=> "7189000001708001",
        "productId"=> null,
        "priority"=> trim($_POST['txtPriority']),
        "description"=> trim($_POST['txtDescription']),
        "email"=> trim($_POST['txtEmail']),
        "phone"=> trim($_POST['txtPhone']),
        "status"=> "Open"
    );
    include 'includes/refresh-token.php';  // API Call function inclded
    $get_data = callAPI('POST', 'https://desk.zoho.in/api/v1/tickets', json_encode($postData)); // API Call  
    $response = json_decode($get_data, true); // Decode json to array 
    if(!empty($response['errorCode'])){
        if($response['errorCode'] == 'INVALID_DATA'){
            $_SESSION['error'] = $response['message'].' at '.$response['errors'][0]['fieldName']; // if invalid data
        }else{
            $_SESSION['error'] = $response['message']; // if invalid token 
        }
    }else if(!empty($response['modifiedTime'])){
        $_SESSION['success'] = 'Ticket has been added successfully.'; // if success
    }else{
        $_SESSION['error'] = 'Something went wrong, Please try again later.'; // if connection failed
    }
    header('Location:add-ticket');
    exit(0);
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include (ALUM_TEMPLATES.'metatag.php');?>
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <style>
        .btn:not(.btn-link):not(.btn-circle) i{
            font-size:12px !important;
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
            <!-- Calender -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <form id="form_validation" method="POST">
                            <div class="header">
                                <h2>Add Ticket</h2>
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <button class="btn btn-success waves-effect" name="btnAddTicket" type="submit">SUBMIT</button>
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
                                                <option value="7189000000051431">PWSLab DevOps Support</option>
                                                <option value="7189000001062045">iSupport</option>
                                                <option value="7189000001896319">Naveena</option>
                                                <option value="7189000002187084">omjit</option>
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
                                                <option value="NEW Project CI/CD Pipeline Setup">NEW Project CI/CD Pipeline Setup</option>
                                                <option value="CI/CD pipeline failure">CI/CD pipeline failure</option>
                                                <option value="Automated Deployment failure">Automated Deployment failure</option>
                                                <option value="Kubernetes Deployments (like EKS/GCP)">Kubernetes Deployments (like EKS/GCP)</option>
                                                <option value="general">General</option>
                                                <option value="update_project">Update Project</option>
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
                                    <div class="form-group clearfix">
                                        <label class="form-label"> Description </label>
                                        <div class="form-line">
                                            <textarea class="form-control" name="txtDescription" required maxlength="60"></textarea>
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
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function(){
            $('[name="txtDescription"]').summernote({
                placeholder : "Description"
            });
            $('[name="sltCourseType"]').selectpicker();
        });
    </script>
</body>

</html>
