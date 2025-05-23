<?php
    include_once("../../admin/includes/config.php");
    include_once("../../admin/includes/connection.php");
    include_once("../../admin/classes/admin.cls.php");
    $admin= new Admin();
    // echo BASE_URL;

    if(isset($_POST['mykey']) and $_POST['mykey'] > 0 )
    {
        
        $my_key = $_POST['mykey'];
        $b2b_cart_data = $_SESSION['b2b_cart_data'];
        
        // remove item from session and reindex session array again
        
        if(isset($b2b_cart_data) and !empty($b2b_cart_data))
        {

           
            unset( $b2b_cart_data[$my_key]);
           // $this->session->set_userdata('b2b_cart_data', $b2b_cart_data);

           //  $b2b_cart_data = $this->session->userdata('b2b_cart_data');
            $_SESSION['b2b_cart_data'] =  $b2b_cart_data;
            $item_count = count($b2b_cart_data);
            
           // $total_amount = $this->b2b_count_total_amount();
           $total_amount = b2b_count_total_amount();

           if($total_amount == 0)
           {
                unset($_SESSION['carriage_amount']);
           }
            
            echo json_encode(array("status" => 1,"msg" => "done" , "b2b_item_count" => $item_count , "b2b_total_amount" => $total_amount ,  "response" => "success" ));
            exit;

            

        } else {

            echo json_encode(array("status" => 0,"msg" => "Item Id looks empty." , "response" => "fail" ));
            exit;
        }

    }


    // by ayaz 14 dec 2023 ,  total price of items
function b2b_count_total_amount()
{
    $total_amount = 0;
    $b2b_cart_data = $_SESSION['b2b_cart_data'];
    if(isset($b2b_cart_data) and !empty($b2b_cart_data))
    {

            $total_amount = 0;
            // check if item allready exist in cart
            foreach($b2b_cart_data as $mykey=>$myval)
            {
                $val = (object) $myval;

                $price = $val->item_qty*$val->item_price;
                $total_amount = $total_amount + $price;
            }
            $total_amount = number_format((float)$total_amount, 2, '.', '');
    }
    
    return $total_amount;
}



?>