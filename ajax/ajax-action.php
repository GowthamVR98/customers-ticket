<?php
    include('../includes/admin-config.php');
    if(isset($_POST['TicketAction'])){
        include '../includes/refresh-token.php';  // API Call function inclded
        include '../includes/emp-crypto-graphy.php';
        $SafeCrypto = new SafeCrypto();
        $ticketId = $SafeCrypto->decrypt(trim($_POST['ticketId']));
        $resP = '';
        $get_data = callAPI('GET', 'https://desk.zoho.in/api/v1/tickets/'.$ticketId.'?include=contacts,products,assignee,departments,team', false); // API Call  
        $response = json_decode($get_data, true); // Decode json to array 
        if(!empty($response['errorCode'])){
            echo json_encode(array('responseData' => $resP,'msg' => $response['message'],'status' => false));
        }else{
            $resP .= '
            <table class="table table-bordered">
                <thead></thead>
                <tbody>
                    <tr>
                        <th>Ticket Id</th>
                        <td>'.$response['ticketNumber'].'</td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td>'.$response['subject'].'</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>'.(!empty($response['phone']) ? $response['phone'] : '-').'</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>'.$response['email'].'</td>
                    </tr>
                        <th>Description</th>
                        <td>'.(!empty($response['description']) ? $response['description'] : '-').'</td>
                    </tr>
                </tbody>
            </table>
            ';
            echo json_encode(array('responseData' => $resP,'msg' => '','status' => true));
        }
    }
?>