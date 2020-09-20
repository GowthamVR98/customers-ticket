<?php
include 'includes/admin-config.php';
include_once 'auth.php';
include 'includes/emp-crypto-graphy.php';
$SafeCrypto = new SafeCrypto();
include 'includes/refresh-token.php';  // API Call function inclded
// $get_data = callAPI('GET', 'https://desk.zoho.in/api/v1/tickets', false); // API Call  
$get_data = callAPI('GET', 'https://desk.zoho.in/api/v1/associatedTickets?include=contacts', false); // API Call  
$response = json_decode($get_data, true); // Decode json to array
if(!empty($response['errorCode'])){
    $_SESSION['error'] = $response['message']; // if invalid token
    header('Location:dashboard');
    exit(0); 
}
function getDepartment($deptId){
    $department = array(
        '7189000000051431' => 'PWSLab DevOps Support',
        '7189000001062045' => 'iSupport',
        '7189000001896319' => 'Naveena',
        '7189000002187084' => 'omjit',
    ); 
    $departmentName = '-';
    if(!empty($department[$deptId])){
        $departmentName = $department[$deptId];
    }
    return ucfirst($departmentName);
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
             <!-- Basic Examples -->
             <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Manage Tickets
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example" id="ticketTable">
                                    <thead>
                                        <tr>
                                            <th>Ticket Id</th>
                                            <th>Subject</th>
                                            <th>Department</th>
                                            <th>Category</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(!empty($response['data'])){
                                            foreach($response['data'] as $ticketData){
                                                echo '<tr>';
                                                    echo '<td>'.$ticketData['ticketNumber'].'</td>';
                                                    echo '<td>'.ucfirst($ticketData['subject']).'</td>';
                                                    echo '<td>'.getDepartment($ticketData['departmentId']).'</td>';
                                                    echo '<td>'.(!empty($ticketData['category']) ? ucfirst($ticketData['category']) : '-').'</td>';
                                                    echo '<td>'.$ticketData['email'].'</td>';
                                                    echo '<td>'.(!empty($ticketData['phone']) ? $ticketData['phone'] : '-').'</td>';
                                                    echo '<td>'.$ticketData['status'].'</td>';
                                                    echo '<td style="text-align:center;">
                                                    <a href="javascript:void(0)" class="ticketAction" id="'.$SafeCrypto->encrypt($ticketData['id']).'"><i class="material-icons" title="View" style=" font-size:21px;">visibility</i></a>
                                                    <a href="edit-ticket.php?ticketId='.$SafeCrypto->encrypt($ticketData['id']).'"><i class="material-icons" title="Edit" id="'.$ticketData['id'].'" style=" font-size:21px;">edit</i></a>';
                                                    echo '</td>';
                                                echo '</tr>';
                                            }
                                        }else{
                                            echo '<tr>';
                                                echo '<td colspan="8" style="text-align:center;">No Record Found</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->
        </div>
        <div class="modal fade" id="TicketViewModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="TicketViewModal">View Ticket Details</h4>
                    </div>
                    <div class="modal-body"></div> 
                </div>
            </div>
        </div>
    </section>
    <?php include ALUM_TEMPLATES.'footer.php';?> 
    <script>
        $(document).ready(function(){ 
            $('body').on('click','.ticketAction',function(e){
                e.preventDefault();
                var id = $(this).attr('id'); 
                $.ajax({
                    type:'POST',
                    url:'ajax/ajax-action.php',
                    data:'TicketAction=true&ticketId'+'='+id,
                    success: function(response){
                        let responseObj = JSON.parse(response);
                        if(responseObj.status == true){
                            $('#TicketViewModal').modal();
                            $('#TicketViewModal .modal-body').html(responseObj.responseData);
                        }else{
                            showNotification('alert-danger', responseObj.msg, 'top', 'center', '', '');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
