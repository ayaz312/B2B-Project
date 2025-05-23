<?php
    include_once("../admin/includes/config.php");
    include_once("../admin/includes/connection.php");
    include_once("../admin/classes/admin.cls.php");
    $admin= new Admin();
    // echo BASE_URL;
?>

<!DOCTYPE html>
<html lang="en-IN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>B2B- Harrow Trading Limited </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <link  rel="canonical" href="https://www.harrowtrading.co.uk/" />
        <meta name="robots" content="noindex,nofollow"/>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
   
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <link rel="stylesheet" href="<?php echo SITE_URL;?>/b2b/css/style.css">    

</head>

<body class="body-bg">

	<!--==================== Upper header area ====================-->
	<div ng-app="rootapp" ng-cloak>
	<div class="upper_header d-none d-md-block">
		<div class="container-fluid container-fluid--cp-100">
			
		</div>
	</div>	<!--==================== Upper header area ====================-->



   <div class="site-wrapper-reveal">   
        <div class=""  style="">
            <div class="">
                <div class="container-fluid container-fluid--cp-100">
                    <div class="row top-r">
                    <div class="container1">
                     <div class="card">
                        <div class="parent">
                            <div class="top-div">
                            <img src="<?php SITE_URL;?>/b2b/img/htl_black_logo.png" class="del-img img-responsive">
                           
                            </div>
                           

                            <div class="search-section" id='searhmyapp'>
                                <div class="sear-w" ng-controller="fetchCtrl">
                                    <input type="text" placeholder="Search here" ng-keyup='fetchUsers()' ng-model='searchText' class="search-input b2b_search">
                                    <button type="button" class="sear-btn b2b_search_btn" ng-hide="searchText">Search</button>

                                    <input type="hidden" name="slug" ng-model="searchinsert.slug">
                                    <button type="submit" class="s-b search-icon b2b_search_icon" ng-click="searchData()">
                                    <img ng-show="searchText" src="<?php echo SITE_URL;?>/b2b/uploads/crossicon.png" class="cross-icon b2b_cross img-responsive">
                                    </button>
                                    
                                    <ul class="custom-result" id='searchResult' >
                                        <li  ng-repeat="result in searchResult" >
                                            <img  src="<?php echo SITE_URL;?>/admin/uploads/{{result.thumbnail}}" alt="" class="searchimg"> <span class="prodname"> {{ result.name }} </span> <span class="prodcode"> {{ result.p_code }} </span>
                                            <span class="ad-btn my_add_btn" data-id="{{result.id}}">Add</span>
                                            <span class="ad-btn my_right_btn" style="display:none;" >Added</span>
                                        </li>
                                    </ul> 

                                </div>
                            </div>



                            <ul class="progress-bar2">
                                <li class="active"></li>
                                <li></li>
                                <li></li>
                               
                            </ul>
                        </div> 
         
        <?php


         $my_b2b_cart_count = 0;
         $b2b_cart_data = $_SESSION['b2b_cart_data'];
        
         if(isset($b2b_cart_data) and count($b2b_cart_data)>0 )
         {
            $my_b2b_cart_count = count($b2b_cart_data);
         }
         ?>                   
                   
        <!-- Product Section -->
        <div class="main active">
        <div class="error_msg1" style="color:red; text-align:center;"></div>
      <div class="row">
            <div class="col-sm-12 total-table table-responsive-xs">
                <table class="productist-table table cart-table b2b_table">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Price</th>
                            <th scope="col" class="th-qty">Qty</th>                          
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="my_b2b_data" data-count="<?php echo $my_b2b_cart_count;?>">

                    <?php
                    
                    $b2b_cart_data = $_SESSION['b2b_cart_data'];
                    if(isset($b2b_cart_data) and count($b2b_cart_data)>0 )
                    {
                        $total_amount = 0;
                        foreach($b2b_cart_data as $mykey=>$myval)
                        {
                        
                            $val = (object) $myval;
                            $total_price = $val->item_price*$val->item_qty;
                            $total_price = number_format((float)$total_price, 2, '.', '');
                           
                            $preorder_text = (empty($val->item_db_qty)) ? " <span style='color:red;font-size:12px;'>(Pre order)</span>" : '';

                            echo '<tr class="b2b_tr">
									<td class="tdw-img">
									 <a href="javascript:void(0);"><img src="'. SITE_URL.'/admin/uploads/'.$val->thumbnail.'" class="pr-img img-responsive"></a>
									</td>
									<td style="text-align:left;padding-left:5px;"><span>'.$val->p_code.' - '.$val->product_name.''.$preorder_text.'</span></td>
									<td>
										<input type="text"  maxlength="10" data-price="'.$val->item_price.'" onkeypress="return onlyNumberKey1(event);" class="qty-text b2b_item_price"  value = "'.$val->item_price.'" placeholder="00" require>
									</td>
									<td>
										<div class="qty-sec prom">
											<ul>
												<li class="qtyli b2bdec qtybutton" data-id="'.$mykey.'">-</li>
		<li><input type="text" data-qty="'.$val->item_qty.'" maxlength="3" onkeypress="return onlyNumberKey(event);" id="pquantity'.$mykey.'" class="qty-text b2b_qty" value="'.$val->item_qty.'"  require></li>
												<li data-id="'.$mykey.'" class="qtyli b2binc qtybutton">+</li>
											</ul>
										</div>                             
									</td>                        
									<td>
                                            £<span class="b2b_total_price">'.$total_price.'</span>
									</td>
									<td><center><a href="javascript:void(0);"><img data-key="'.$mykey.'" src="'.SITE_URL.'/b2b/img/del.png" class="del-img img-responsive remove_item"></a></center></td>
								</tr>';
                               
								$total_amount = $total_amount+$total_price;

                        }

                        $vat_percent = 20;
                        $vat_amount = ($total_amount*$vat_percent)/100;

                        $total_amount = $total_amount+$vat_amount;
                        $carriage_amount = '';
								if(isset($_SESSION['carriage_amount']) and $_SESSION['carriage_amount'] > 0 )
								{
									$carriage_amount = $_SESSION['carriage_amount'];
                                    $total_amount =  $total_amount+$carriage_amount;
								}

                            echo 	'<tr class="b2b_vat_amount_area">
											<td  colspan="3" rowspan="1" class="tottxt-al total-head">Vat (20%) :</td>
											<td  colspan="3" rowspan="1" class="ambg totamt-al tamt-b">
                                                    £<span class="b2b_vat_amount">'.number_format((float)$vat_amount, 2, '.', '').'</span>
											</td>
										</tr>';

                            echo 	'<tr class="b2b_carriage_amount_area">
                                        <td  colspan="3" rowspan="1" class="tottxt-al total-head">Carriage Amount :</td>
                                        <td  colspan="3" rowspan="1" class="totamt-al tamt-b">
                                               <input type = "text" name="b2b_carriage_amount" onkeypress="return onlyNumberKey1(event);" placeholder="0" class="carragetxtf b2b_carriage_amount" value="'.$carriage_amount.'" >
                                        </td>
                                        
                                    </tr>';
                            echo 	'<tr class="b2b_total_amount_area">
                                        <td  colspan="3" rowspan="1" class="tottxt-al total-head">Grand Total :</td>
                                        <td  colspan="3" rowspan="1" class="ambg totamt-al tamt-b">
                                                £<span class="b2b_grand_total">'.number_format((float)$total_amount, 2, '.', '').'</span>
                                        </td>
                                    </tr>';



                    }
                    
                    
                    ?>

                    


                    </tbody>

                </table>
                
            </div>
        </div>
        <div class="row r-m1">
        <?php  $my_b2b_class = ($my_b2b_cart_count > 0) ? "b2b_show":"b2b_hide";?>
            
           <div class="col-sm-12">
                <div class="button">
                   
                    <button class="create-workspace b2b_proceed <?php echo $my_b2b_class;?> ">Proceed</button>
                </div>
            </div>
        </div>
    </div>
 <!-- //Product Section -->

        <!-- Second Page -->
        <div class="main">
            <div class="form-group1">
            <div class="row">
                <div class="col-sm-12 cust-detai">
                    <h3>Customer Details</h3>
                </div>
                <div class="error_msg" style="color:red;text-align:center;margin-left: 14px;"></div>
            </div>
            
            <form name="customer_detail_form" id="customer_detail_form" action="" method="POST" enctype="multipart/form-data">
            <div class="row custom-form">
                <div class="col-sm-6">
                    <div class="custom-fomtxt">
                        <input type="text" name="name" class="txt-field b2b_customer_name " maxlength="255"  value="" require>
                        <span>Name</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="custom-fomtxt">
                        <input type="text" name="email" class="txt-field b2b_customer_email " maxlength="255" value=""  >
                        <span>Email</span>
                    </div>
                </div>
           
                <div class="col-sm-6">
                    <div class="custom-fomtxt">
        <input type="tel" name="phone_number" maxlength="20" minlength="10" class="txt-field b2b_customer_phone" value="" onkeypress="return onlyNumberKey2(event);"  >
                        <span>Contact No.</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="custom-fomtxt">
                        <input type="text" name="business_name" class="txt-field b2b_customer_business_name" >
                        <span>Business Name</span>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="custom-fomtxt">
                        <textarea rows="30" cols="15"  style="width: 100%; height:100px;" class="txt-field b2b_customer_address" require name="address"></textarea>
                        <span>Address</span>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="custom-fomtxt">
                        <textarea rows="30" cols="15" maxlength="500"  style="width: 100%; height:100px;" class="txt-field b2b_customer_comment"  name="comment"></textarea>
                        <span>Comments</span>
                    </div>
                </div>

                

                <div class="col-sm-12"><span class="loader" style="display:none;text-align:center;"><img src="<?php echo SITE_URL;?>/b2b/img/loading.gif"></span></div>

            </div>
            </form>


        </div>
           
           <div class="btn-align">
            <!-- <div class="btn-width button button_gap">
                <button class="">Save</button>
                <button class="">Reset</button>             
            </div> -->

            <div class="btn-width button button_gap">
                <button data-tag="save_area" class="back-click">Back</button>
                <button data-tag="save_me" class="next-click save_click_btn">Save</button> 
                <!-- <button data-tag="save_me" class="finish-click">Save</button>  -->
            </div>
        </div>
            
        </div>

        
      

        <div class="main">
           
           <div class="content thanku">
                <h1>Thank you for placing order </h1>
           </div>

           <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
           
           <div class="row cus-rowd b2b_thankyou_area">
              
           </div>

           <div class="button button_gap2">
               <a class="btn" href="<?php echo SITE_URL;?>/b2b/" ><button   class="finish-click">Finish</button></a>
               <!-- <button class="finish-click">Finish</button> -->
           </div>
           
       </div>
        
       
        
    </div>
    
</div>
                    </div>
                </div>
            </div>           
        </div>
        <!-- Hero Slider Area End -->
    </div>

  


<style>
    .b2bfooter{
        padding-top: 90px !important;
        Padding-bottom: 25px !important;
    }
</style>


		<!--====================  footer area ====================-->
	
    <!-- <div class="footer-area-wrapper">
       
       
    </div>
   -->

<script>
var rootApp = angular.module('rootapp', ['mynapp','searhmyapp','prdctmyapp']);
 var prdctmyapp = angular.module('prdctmyapp', []);
 
 
 
 var application = angular.module('mynapp', []);
application.controller("formncontroller", function($scope, $http){
 
});


    // update cart on inc or dec ,  bya ayaz   
   function update_b2b_cart(pid , qty )
   {

    $.ajax({
            url: '<?php echo SITE_URL;?>/b2b/ajax/updateb2bcart.php',
            type: 'POST',
            dataType: 'json',
            data: {pid:pid,qty:qty},
            success: function(data) {
                
                // alert(data);
                if(data.status == 1)
                {
                    // $(".my_b2b_data").html(data.msg);
                    var b2b_total_amount = calculate_price_with_vat(data.b2b_total_amount);
                    $(".b2b_grand_total").html(b2b_total_amount);
                    

                } else if (data.status == 0) {

                    $(".error_msg").html(data.msg);

                }
                
            }
        });

   }
   
   
    // update cart on inc or dec ,  bya ayaz   
    function update_b2b_carriage(carriage_val )
   {

    $.ajax({
            url: '<?php echo SITE_URL;?>/b2b/ajax/updateb2bcarriage.php',
            type: 'POST',
            dataType: 'json',
            data: {carriage_val:carriage_val},
            success: function(data) {
                
                // alert(data);
                if(data.status == 1)
                {
                    // $(".my_b2b_data").html(data.msg);
                   var b2b_total_amount = calculate_price_with_vat(data.b2b_total_amount);
                   $(".b2b_grand_total").html(b2b_total_amount);
                    

                } else if (data.status == 0) {

                    $(".error_msg").html(data.msg);

                }
                
            }
        });

   }   
        
       
    $(document).on("click", '.b2binc', function(event) {  
            
            var pid = $(this).data("id");
            var oldValue = $("#pquantity"+pid).val();
            var oldValue = oldValue.replace(/[^0-9]/g, '');
            var oldval = parseFloat(oldValue);
            var price =  $(this).closest('tr').find('.b2b_item_price').val();
                
            if(oldval >= 1 && price > 0)
            {
                $(this).closest('tr').find('.b2b_item_price').removeClass("red_alert");
                var newVal = parseFloat(oldValue) + 1;
                // alert(pid);
                $("#pquantity"+pid).val(newVal);

                update_b2b_cart(pid , newVal);

                // b2b_total_price
                // b2b_item_price
                // b2b_qty

                //if(price == '')price = 0;
                var price = parseFloat(price).toFixed(2);
                var newprice = price*newVal;
                var newprice = parseFloat(newprice).toFixed(2);
                $(this).closest('tr').find('.b2b_total_price').html(newprice);

                $("#pquantity"+pid).attr("data-qty",newVal);
            } else{
                // alert();
                $(this).closest('tr').find('.b2b_item_price').addClass("red_alert");
            }

        });

        $(document).on("click", '.b2bdec', function(event) {  
            
            var pid = $(this).data("id");
            var price =  $(this).closest('tr').find('.b2b_item_price').val();
           if(price > 0)
           {


           
            $(this).closest('tr').find('.b2b_item_price').removeClass("red_alert");
        
            // alert(pid);
            var oldValue = $("#pquantity"+pid).val();
           
            if (oldValue > 1) {
                var newVal = parseFloat(oldValue) - 1;
            } else {

                $("#pquantity"+pid).val(1);
                newVal = 1;
            }
            
            $("#pquantity"+pid).val(newVal);
            update_b2b_cart(pid , newVal);
            // if(price == '')price = 0;
            var price = parseFloat(price).toFixed(2);
            var newprice = price*newVal;
            var newprice = parseFloat(newprice).toFixed(2);
            $(this).closest('tr').find('.b2b_total_price').html(newprice);
            $("#pquantity"+pid).attr("data-qty",newVal);

        }else {

           // $this.find( ".b2b_item_price" ).addClass("red_alert");
            $(this).closest('tr').find('.b2b_item_price').addClass("red_alert");
        }
        });



        $(document).on("change", '.b2b_carriage_amount', function(event) {  
            
            var val = $(this).val();
            // alert(val);
            if(val > 0)
            {
                update_b2b_carriage(val );
            }else{

                var val = 0;
                update_b2b_carriage(val )
            }
            
        });
        $(document).on("keyup", '.b2b_carriage_amount', function(event) {  
            
            var val = $(this).val();
            // alert(val);
            if(val > 0)
            {
                update_b2b_carriage(val);
            } else {

                var val = 0;
                update_b2b_carriage(val);
            }
            
        });
    </script>

<script>

$(document).ready(function(){
   
    // close modal on click outside at anywhere
    $(document).on('click',function(e){
        if(!(($(e.target).closest("#searhmyapp").length > 0 ) || ($(e.target).closest(".b2b_cross").length > 0))){
        // $("#modalBox").hide();
        $(".b2b_cross").trigger("click");
       
    }
    });
});

</script> 

<script>

function calculate_price_with_vat(amount)
{
        var amount = parseFloat(amount).toFixed(2);
        var vat_percent = 20;
        var vat_amount = (amount*vat_percent)/100;
       
        var total_amount = parseFloat(amount) + parseFloat(vat_amount);
        var total_amount = parseFloat(total_amount).toFixed(2);
        var vat_amount = parseFloat(vat_amount).toFixed(2);
        $(".b2b_vat_amount").html(vat_amount);

        var carr = $(".b2b_carriage_amount").val();
        if(carr == "")carr = 0;

        var carr_amount = parseFloat(carr);
        var total_amount = parseFloat(total_amount) + parseFloat(carr_amount);
        var total_amount = parseFloat(total_amount).toFixed(2);
        return total_amount;
}


$(document).ready(function () {

    $(document).on("keyup", '.b2b_qty', function(event) {

        var val = $(this).val();
        var obj = $(this);
        var b2b_price = $(this).closest('tr').find('.b2b_item_price').val();
            

        if(val > 0 && b2b_price > 0 )
        {
            var b2b_qty = parseInt(val);
            var b2b_price = $(this).closest('tr').find('.b2b_item_price').val();
            
        
            var total_price = b2b_price*b2b_qty;
            var total_price = parseFloat(total_price).toFixed(2);

            $(this).closest('tr').find('.b2b_total_price').html(total_price);
            
            var pid = $(this).closest('tr').find('.b2bdec').data("id");
            
        
        

            if(pid != "" && b2b_price != "")
            {

                // update session
                $.ajax({
                url: '<?php echo SITE_URL;?>/b2b/ajax/updateb2bcart.php',
                type: 'POST',
                dataType: 'json',
                data: {pid:pid , qty:b2b_qty},
                success: function(data) {
                    
                        // alert(data);
                        if(data.status == 1)
                        {
                            
                            var b2b_total_amount = calculate_price_with_vat(data.b2b_total_amount);
                            $(".b2b_grand_total").html(b2b_total_amount);
                           

                        } else if (data.status == 0) {

                            // $(".my_b2b_data").html(data.msg);

                        }
                    
                    }
                });
            }
        }
    });

    $(document).on("change", '.b2b_qty', function(event) {

        var val = $(this).val();
        var obj = $(this);
        if(val == 0 || val == "")
        {
            $(this).val($(this).data('qty')); 
        }
        var val = $(this).val();
        var b2b_price = $(this).closest('tr').find('.b2b_item_price').val();
            

        if(val > 0 && b2b_price > 0 )
        {
            var b2b_qty = parseInt(val);
            var b2b_price = $(this).closest('tr').find('.b2b_item_price').val();
            

            var total_price = b2b_price*b2b_qty;
            var total_price = parseFloat(total_price).toFixed(2);

            $(this).closest('tr').find('.b2b_total_price').html(total_price);
            
            var pid = $(this).closest('tr').find('.b2bdec').data("id");
            
            // alert(sum);

            if(pid != "" && b2b_price != "")
            {

                // update session
                $.ajax({
                url: '<?php echo SITE_URL;?>/b2b/ajax/updateb2bcart.php',
                type: 'POST',
                dataType: 'json',
                data: {pid:pid , qty:b2b_qty},
                success: function(data) {
                    
                        // alert(data);
                        if(data.status == 1)
                        {
                            
                            var b2b_total_amount = calculate_price_with_vat(data.b2b_total_amount);
                            
                            $(".b2b_grand_total").html(b2b_total_amount);
                          

                        } else if (data.status == 0) {

                            // $(".my_b2b_data").html(data.msg);

                        }
                    
                    }
                });
            }


        } else {

           $(this).val($(this).data("qty"));
          // $(this).val(1);
            
        }

});



    
 $(document).on("keyup", '.b2b_item_price', function(event) {

        var val = $(this).val();
        var obj = $(this);
       // alert(val);
        if(val > 0)
        {
            $(this).removeClass("red_alert");
            
            var price = parseFloat(val).toFixed(2);
            var b2b_qty = $(this).closest('tr').find('.b2b_qty').val();
            var total_price = price*b2b_qty;
            var total_price = parseFloat(total_price).toFixed(2);
           
            $(this).closest('tr').find('.b2b_total_price').html(total_price);
            
            var pid = $(this).closest('tr').find('.b2bdec').data("id");
            
                // alert(pid);


                var sum = 0;
            
               $('.b2b_tr').each(function() {
                var $this = $(this),
                qty = parseInt($this.find( ".b2b_qty" ).val()),
                price = parseFloat($this.find( ".b2b_item_price" ).val());
                var total_price = 0;
              
                if(price > 0)
                { 
                    var total_price = price*qty;
                    sum = sum+total_price;
                }
                // alert(sum);
                 return sum;
                });

           
           

            if(pid != "" && price != "")
            {

                var amount = calculate_price_with_vat(sum);
                 // update session
                $.ajax({
                url: '<?php echo SITE_URL;?>/b2b/ajax/update_b2bcart_price.php',
                type: 'POST',
                dataType: 'json',
                data: {pid:pid , price:price},
                success: function(data) {
                    
                        // alert(data);
                        if(data.status == 1)
                        {
                           
                           amount = parseFloat(amount).toFixed(2);
                           
                            $(".b2b_grand_total").html(amount);
                            

                        } else if (data.status == 0) {

                        // $(".my_b2b_data").html(data.msg);

                        }
                    
                    }
                });
            }

        }

 }); 
 
 
 $(document).on("change", '.b2b_item_price', function(event) {

        var val = $(this).val();
        var obj = $(this);
        if(val == 0 || val == "")
        {
            $(this).val($(this).data('price')); 
        }
        var val = $(this).val();

        if(val > 0)
        {

            $(this).closest('tr').find('.b2b_item_price').removeClass('red_alert');
            var price = parseFloat(val).toFixed(2);
            var b2b_qty = $(this).closest('tr').find('.b2b_qty').val();
            var total_price = price*b2b_qty;
            var total_price = parseFloat(total_price).toFixed(2);

            $(this).closest('tr').find('.b2b_total_price').html(total_price);
            
            var pid = $(this).closest('tr').find('.b2bdec').data("id");
            
            

            if(pid != "" && price != "")
            {

                // update session
                $.ajax({
                url: '<?php echo SITE_URL;?>/b2b/ajax/update_b2bcart_price.php',
                type: 'POST',
                dataType: 'json',
                data: {pid:pid , price:price},
                success: function(data) {
                    
                        // alert(data);
                        if(data.status == 1)
                        {
                            var amount = parseFloat(data.b2b_total_amount).toFixed(2);
                            var b2b_total_amount = calculate_price_with_vat(amount);
                           
                            $(".b2b_grand_total").html(b2b_total_amount);
                            

                        } else if (data.status == 0) {

                        // $(".my_b2b_data").html(data.msg);

                        }
                    
                    }
                });
            }


        } else {

            $(this).val($(this).data('price'));

        }

});




    $(document).on("click", '.my_add_btn', function(event) { 
            
        //  alert("new link clicked!");
        var obj = $(this);
        var pid = $(this).data("id");
        // alert(pid);
        // $.cookie('foo', 'Yes');
        //  console.log($.cookie('foo'));


        $.ajax({
            url: '<?php echo SITE_URL;?>/b2b/ajax/setb2bcookiesdata.php',
            type: 'POST',
            dataType: 'json',
            data: {pid:pid},
            success: function(data) {
                
                // alert(data);
                if(data.status == 1)
                {
                    
                    $(".my_b2b_data").html(data.msg);
                    obj.hide();
                    obj.closest("li").find('.my_right_btn').show();
                    obj.closest("li").hide('slow', function(){ obj.closest("li").remove(); });

                    $(".my_b2b_data").attr("data-count",data.total_b2b_count);

                    if(data.total_b2b_count > 0)
                    {
                        $(".b2b_proceed").show();
                    }

                } else if (data.status == 0) {

                    $(".error_msg1").html(data.msg);

                }
                
            }
        });
});

    $(document).on("click", '.remove_item', function(event) {
       
        var obj = $(this);
        var mykey = $(this).data("key");
       
        if(mykey >= 0)
        {
            var conf = confirm("Are you sure?");
            if(conf)
            {
               
                $(".b2b_cross").trigger("click");
               
                $.ajax({
                    url: '<?php echo SITE_URL;?>/b2b/ajax/removeb2bitem.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {mykey:mykey},
                    success: function(data) {
                        
                        // alert(data);
                        if(data.status == 1)
                        {
                            
                            // $(".my_b2b_data").html(data.msg);
                            $(".my_b2b_data").attr("data-count",data.b2b_item_count);
                           
                            var b2b_total_amount = calculate_price_with_vat(data.b2b_total_amount);
                           
                            $(".b2b_grand_total").html(b2b_total_amount);

                            // $(obj).closest('tr').remove();
                            obj.closest("tr").hide('slow', function(){ obj.closest("tr").remove(); });

                            if(data.b2b_item_count == 0)
                            {
                                $(".b2b_proceed").hide();
                                //$(".b2b_total_amount_area").remove();
                                $(".b2b_total_amount_area").hide('slow', function(){  $(".b2b_total_amount_area").remove(); });
                                $(".b2b_vat_amount_area").hide('slow', function(){  $(".b2b_vat_amount_area").remove(); });
                                $(".b2b_carriage_amount_area").hide('slow', function(){  $(".b2b_carriage_amount_area").remove(); });

                                
                                
                            }
                        
                        } else if (data.status == 0) {

                            $(".error_msg").html(data.msg);

                        }
                    }

                });
            }} else {

            alert("Item Id looks empty!");
            return false;
        }

    });

});


function onlyNumberKey1(evt) 
{
        
    // Only ASCII character in that range allowed
    let ASCIICode = (evt.which) ? evt.which : evt.keyCode
    
    if(ASCIICode == 46)
    {
    return true;

    } else {
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;

    }
}
function onlyNumberKey(evt) {
    
            // Only ASCII character in that range allowed
            let ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }


function onlyNumberKey2(evt) {
    
    // Only ASCII character in that range allowed
    let ASCIICode = (evt.which) ? evt.which : evt.keyCode
   
    if(ASCIICode == 43 || ASCIICode == 45 || ASCIICode == 32 )
    {
        return true;
    }
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}

   

        

</script>


 <script>
         var fetch = angular.module('searhmyapp', []);

        fetch.controller('fetchCtrl', ['$scope', '$http', function ($scope, $http) {

            // Fetch data
            $scope.fetchUsers = function(){
                
                var searchText_len = $scope.searchText.trim().length;

                // Check search text length
                if(searchText_len > 0){
                    $http({
                    method: 'post',
                    url: '<?php echo SITE_URL;?>/b2b/ajax/b2b_getdatasearch.php',
                    data: {searchText:$scope.searchText}
                    }).then(function successCallback(response) {
                        
                        if(response.data != "")
                        {
                            $scope.searchResult = response.data;
                        }
                        

                    });
                } else {
                    $scope.searchResult = {};
                }
                
            }

            // Set value to search box
            $scope.setValue = function(index,$event){
				$scope.searchinsert = {};
                $scope.searchText = $scope.searchResult[index].name;
                // $scope.searchinsert.slug = $scope.searchResult[index].slug;
                slug = $scope.searchResult[index].slug;
						 $http({
                            method:"POST",
                            url:"<?php echo SITE_URL;?>/b2b/ajax/b2bsearchinputdata.php",
                            data:{slug:slug},
                            }).success(function(data){
                                $scope.searchText = "";
                                $scope.searchinsert.slug = "";
                                window.location.href = data;      
                            });
                $scope.searchResult = {};
                $event.stopPropagation();
            }

            $scope.searchboxClicked = function($event){
                $event.stopPropagation();
            }

            $scope.containerClicked = function(){
                $scope.searchResult = {};
            }
           
                $scope.searchData = function(){
                $scope.searchinsert = {};
                $scope.searchResult = {};
                $scope.searchText = "";
                // $http({
                // method:"POST",
                // url: "<?php echo SITE_URL ?>/b2b/ajax/b2bsearchinputdata.php",
                // data:{slug:$scope.searchinsert.slug},
                // }).success(function(data){
                //     $scope.searchText = "";
                //     $scope.searchinsert.slug = "";
                //     window.location.href = data;      
                // });
  
 }
 
        }]);

        </script>




    <!--====================  scroll top ====================-->
    <a href="#" class="scroll-top" id="scroll-top">
        <i class="arrow-top icon-arrow-up"></i>
        <i class="arrow-bottom icon-arrow-up"></i>
    </a>
    <!--====================  End of scroll top  ====================-->
 

 <!-- akbar script -->
 <script>

var create_workspace=document.querySelector(".create-workspace");
var next_click=document.querySelectorAll(".next-click");
var back_click=document.querySelectorAll(".back-click");
// var finish_click=document.querySelector(".finish-click");
var main_form=document.querySelectorAll(".main");
var list=document.querySelectorAll(".progress-bar2 li")
let formnumber=0;


create_workspace.addEventListener('click',function(){
   
    // if(!validateform()){
    //     return false;
    // }
        var is_ok = 1;
        $('.b2b_tr').each(function() {
                    var $this = $(this),
                    // qty = parseInt($this.find( ".b2b_qty" ).val()),
                    price = parseFloat($this.find( ".b2b_item_price" ).val());
                    var total_price = 0;
               
                    if(price == 0 || isNaN(price) )
                    { 
                        $this.find( ".b2b_item_price" ).addClass("red_alert");
                        is_ok = 0;
                        
                    } else {

                        $this.find( ".b2b_item_price" ).removeClass("red_alert");
                        is_ok = 1;
                    }
                     //   alert(is_ok);
                    if(is_ok == 0)
                    {
                        return false;
                    }


                
        });
    
        if(is_ok == 0)
                    {
                        return false;
                    }

    $(".b2b_search").hide();
    $(".b2b_search_btn").hide();
    $(".b2b_search_icon").hide();

    // check if cart is not empty
   formnumber++;
   updateform();
   progress_forward();
});

next_click.forEach(function(next_page){
    next_page.addEventListener('click',function(){
       
         if(!validateform()){
            return false;
        }

        var tag = $(this).data('tag');
        if (tag == 'save_me')
        {
           // var file_data = $("#upload_file").prop("files")[0]; 
            var customer_name =  $(".b2b_customer_name").val();
            var customer_email =  $(".b2b_customer_email").val();
            var customer_phone =  $(".b2b_customer_phone").val();
            var customer_business_name =  $(".b2b_customer_business_name").val();
            var customer_address =  $(".b2b_customer_address").val();
            var customer_comment =  $(".b2b_customer_comment").val();
            
            
            var formData = new FormData();  
                        
           // formData.append('file', file_data);

            formData.append('customer_name', customer_name);
            formData.append('customer_email', customer_email);
            formData.append('customer_phone', customer_phone);
            formData.append('customer_business_name', customer_business_name);
            formData.append('customer_address', customer_address);
            formData.append('customer_comment', customer_comment);

            $.ajax({
            url: '<?php echo SITE_URL;?>/b2b/ajax/save_b2b_customer_detail.php',
            type: 'POST',
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $(".save_click_btn").attr("disabled" , "disabled");
                $(".loader").show();
            },
            complete: function(){
                $(".save_click_btn").removeAttr("disabled");
                $(".loader").hide();
            },


            success: function(data) {
                
                // alert(data);
                if(data.status == 1)
                {
                   // alert();
                   $(".save_click_btn").removeAttr("disabled");
                   $(".b2b_thankyou_area").html(data.msg);
                   formnumber++;
                   updateform();
                   progress_forward();

                    var remove_progress=document.querySelector(".progress-bar2");
                    remove_progress.classList.add('d-none'); 

                } else if (data.status == 0) {

                    $(".error_msg").html(data.msg);

                }
                
            }
            });

        }

    });
});


back_click.forEach(function(back_page){
    
    
    
    back_page.addEventListener('click',function(){
        
        var tag = $(this).data('tag');
        if(tag == 'save_area')
        {
            $(".b2b_search").show();
            $(".b2b_search_btn").show();
            $(".b2b_search_icon").show();
        }
        
        
        formnumber--;
         updateform();  
         
         
    });
});

// finish_click.addEventListener('click',function(){
// //         return false;
// //         }
//          formnumber++;
        
//          updateform();
// });
function progress_forward(){
    list[formnumber].classList.add('active');
}



function updateform(){
    main_form.forEach(function(main_number) { 
       main_number.classList.remove('active'); 
    });
    main_form[formnumber].classList.add('active');
  
   
} 

function validateform() {
    validate=true;
    var validate_form=document.querySelectorAll(".main.active input");
    validate_form.forEach(function(val){
        val.classList.remove('warning');
        if(val.hasAttribute('require')){
            if(val.value.length==0){
                validate=false;
                val.classList.add('warning');
            }
        }
    });

    // var validate_form=document.querySelectorAll(".main.active textarea");
    // validate_form.forEach(function(val){
    //     val.classList.remove('warning');
    //     if(val.hasAttribute('require')){
    //         if(val.value.length==0){
    //             validate=false;
    //             val.classList.add('warning');
    //         }
    //     }
    // });
    return validate;
}

</script>
 <!-- End Akbar script -->
 
</body>

</html>