<?php
include 'includes/admin-config.php';
include_once 'auth.php';
include 'includes/refresh-token.php';  // API Call function inclded
if(isset($_GET['ticketId'])){
    include 'includes/emp-crypto-graphy.php';
    $SafeCrypto = new SafeCrypto();
    $ticketId = $SafeCrypto->decrypt(trim($_GET['ticketId']));
    $get_data = callAPI('GET', 'https://desk.zoho.in/api/v1/tickets/'.$ticketId.'?include=contacts,products,assignee,departments,team', false); // API Call  
    $response = json_decode($get_data, true); // Decode json to array 
    if(!empty($response['errorCode'])){
        $_SESSION['error'] = $response['message']; // if invalid token
        if($response['errorCode'] == 'URL_NOT_FOUND'){
            header('Location:manage-ticket');
        }else{
            header('Location:dashboard');
        }
        exit(0); 
    }
}else{
    $_SESSION['error'] = 'Invalid url.'; // Invalid url
    header('Location:manage-ticket');
    exit(0);
}
if(isset($_POST['btnAddTicket'])){
    $postData =  array(
        "subject" => trim($_POST['txtSubject']),
        "category" => $_POST['sltCategory'],
        "priority"=> trim($_POST['txtPriority']),
        "description"=> trim($_POST['txtDescription']),
        "email"=> trim($_POST['txtEmail']),
        "phone"=> trim($_POST['txtPhone']),
    );
    $get_data = callAPI('PUT', 'https://desk.zoho.in/api/v1/tickets/'.$ticketId.'', json_encode($postData)); // API Call  
    $response = json_decode($get_data, true); // Decode json to array 
    error_log(json_encode($response['errorCode']));
    if(!empty($response['errorCode'])){
        if($response['errorCode'] == 'INVALID_DATA'){
            $_SESSION['error'] = $response['message'].' at '.$response['errors'][0]['fieldName']; // if invalid data
        }else{
            $_SESSION['error'] = $response['message']; // if invalid token 
        }
    }else if(!empty($response['modifiedTime'])){
        $_SESSION['success'] = 'Ticket has been updated successfully.'; // if success
    }else{
        $_SESSION['error'] = 'Something went wrong, Please try again later.'; // if connection failed
    }
    header('Location:edit-ticket.php?ticketId='.$_GET['ticketId']);
    exit(0);
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
                                <h2>Edit Ticket</h2>
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <button class="btn btn-warning waves-effect" name="btnAddTicket" type="reset">Back</button>
                                        <button class="btn btn-success waves-effect" name="btnAddTicket" type="submit">UPDATE</button>
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
                                            <select class="form-control" name="sltDepartment" title="Select Department" required disabled>
                                                <optgroup label="Select Department">
                                                <option value="7189000000051431" <?php echo ($response['departmentId'] == '7189000000051431' ? 'selected' : '')?>>PWSLab DevOps Support</option>
                                                <option value="7189000001062045" <?php echo ($response['departmentId'] == '7189000001062045' ? 'selected' : '')?>>iSupport</option>
                                                <option value="7189000001896319" <?php echo ($response['departmentId'] == '7189000001896319' ? 'selected' : '')?>>Naveena</option>
                                                <option value="7189000002187084" <?php echo ($response['departmentId'] == '7189000002187084' ? 'selected' : '')?>>omjit</option>
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
                                                <option value="NEW Project CI/CD Pipeline Setup" <?php echo ($response['category'] == 'NEW Project CI/CD Pipeline Setup' ? 'selected' : '')?> >NEW Project CI/CD Pipeline Setup</option>
                                                <option value="CI/CD pipeline failure" <?php echo ($response['category'] == 'CI/CD pipeline failure' ? 'selected' : '')?> >CI/CD pipeline failure</option>
                                                <option value="Automated Deployment failure" <?php echo ($response['category'] == 'Automated Deployment failure' ? 'selected' : '')?> >Automated Deployment failure</option>
                                                <option value="Kubernetes Deployments (like EKS/GCP)" <?php echo ($response['category'] == 'Kubernetes Deployments (like EKS/GCP)' ? 'selected' : '')?> >Kubernetes Deployments (like EKS/GCP)</option>
                                                <option value="Kubernetes Deployments (like EKS/GCP)" <?php echo ($response['category'] == 'Kubernetes Deployments (like EKS/GCP)' ? 'selected' : '')?> >Kubernetes Deployments (like EKS/GCP)</option>
                                                <option value="general" <?php echo ($response['category'] == 'general' ? 'selected' : '')?> >General</option>
                                                <option value="update_project" <?php echo ($response['category'] == 'update_project' ? 'selected' : '')?> >Update Project</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="txtSubject" value="<?php echo (!empty($response['subject']) ? $response['subject'] : '')?>" required maxlength="60">
                                            <label class="form-label">Subject</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" value="<?php echo (!empty($response['priority']) ? $response['priority'] : '')?>" class="form-control" name="txtPriority" required maxlength="60">
                                            <label class="form-label">Priority</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group clearfix">
                                        <label class="form-label"> Description </label>
                                        <div class="for m-line">
                                            <textarea class="form-control" name="txtDescription" required maxlength="60"><?php echo (!empty($response['description']) ? $response['description'] : '')?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div style="font-weight: 600;color: #000;padding: 1.25rem 0.625rem;">Contact Details</div>
                                <hr>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" value="<?php echo (!empty($response['email']) ? $response['email'] : '')?>" name="txtEmail" required maxlength="60">
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float clearfix">
                                        <div class="form-line">
                                            <input type="text" class="form-control" value="<?php echo (!empty($response['phone']) ? $response['phone'] : '')?>" name="txtPhone" required maxlength="60">
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
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/js/froala_editor.pkgd.min.js"></script>
    <script>
        $(document).ready(function(){
            var editor = new FroalaEditor('[name="txtDescription"]');
            $('[name="sltCourseType"]').selectpicker();
        });
    </script>
</body>

</html>
