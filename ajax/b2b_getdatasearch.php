<?php
    include_once("../../admin/includes/config.php");
    include_once("../../admin/includes/connection.php");
    include_once("../../admin/classes/admin.cls.php");
    $admin= new Admin();
    // echo BASE_URL;

        $_POST = json_decode(file_get_contents('php://input'), true);
        $fld= $_POST['searchText'];

       
        // $run=$admin->getb2bFulltextsearchData("tbl_products",$fld);
        $run=$admin->getFulltextsearchData("tbl_products",$fld);
       // $data[] = array();
       
         while ($row = mysqli_fetch_object($run)) {
            
            // $thumbnail = (!empty($row->prod_img2))? $row->prod_img2 : $row->prod_img1;
            $thumbnail = $row->prod_img1;
            $data[] = array("name"=>$row->prod_name,"id"=>$row->id,"p_code"=>$row->prod_code,"thumbnail"=>$thumbnail,"slug"=>$row->prod_slug ,"size" => $row->prod_dimension );
        }	
       
        echo json_encode($data);



?>