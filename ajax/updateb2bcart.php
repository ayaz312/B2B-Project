<?php
    include_once("../../admin/includes/config.php");
    include_once("../../admin/includes/connection.php");
    include_once("../../admin/classes/admin.cls.php");
    $admin= new Admin();
    // echo BASE_URL;

    extract($_POST);

    if(empty($pid) || empty($qty) )
    {
        echo json_encode(array("status" => 2,"msg" => "qty or item id looks empty" , "response" => "fail" ));
        exit;
    }
    
    
    // $b2b_cart_data = $this->session->userdata('b2b_cart_data');
    $b2b_cart_data = $_SESSION['b2b_cart_data'];

    
    if(isset($b2b_cart_data) and !empty($b2b_cart_data))
    {

            $index = '';
            // check if item allready exist in cart
            foreach($b2b_cart_data as $mykey=>$myval)
            {
                $val = (object) $myval;

                if($mykey == $pid)
                {
                    $index = $mykey;
                    break;
                }
            }

            if(!empty($index))
            {
                $b2b_cart_data[$index]['item_qty'] = $qty;
                // $this->session->set_userdata('b2b_cart_data', $b2b_cart_data);
                $_SESSION['b2b_cart_data'] = $b2b_cart_data;
            }

            $total_amount = b2b_count_total_amount();


            echo json_encode(array("status" => 1,"msg" => "done" , "b2b_total_amount" => $total_amount ,  "response" => "success" ));
            exit;
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