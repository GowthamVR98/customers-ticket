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
        header('Location:dashboard');
        exit(0); 
    }
}else{
    $_SESSION['error'] = 'Invalid url.'; // Invalid url
    header('Location:manage-ticket');
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
    <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#defaultModal">MODAL - DEFAULT SIZE</button>

                            <div class="header">
                                <h2>View Ticket</h2>
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
        <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan
                        vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper.
                        Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus
                        nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla.
                        Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link waves-effect">SAVE CHANGES</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include ALUM_TEMPLATES.'footer.php';?> 
    <script>
        $(document).ready(function(){
            var editor = new FroalaEditor('[name="txtDescription"]');
            $('[name="sltCourseType"]').selectpicker();
        });
    </script>
</body>

</html>
