<?php
session_start();
    include_once("../../admin/includes/config.php");
    include_once("../../admin/includes/connection.php");
    include_once("../../admin/classes/admin.cls.php");
    $admin= new Admin();
    // echo BASE_URL;

      

         //   unset($_SESSION['b2b_cart_data']);
            // print_r($_SESSION['b2b_cart_data']);exit;
       
        if(isset($_POST['pid']) and !empty($_POST['pid']))
		{
			$pid = $_POST['pid'];

			
			
			// $row = $this->FrontModel->editData("product",$pid);
            $run=$admin->editData("tbl_products",$pid);
            $row = mysqli_fetch_object($run);
           
            $b2b_cart_data = $_SESSION['b2b_cart_data'];
			if(!isset($b2b_cart_data) and empty($b2b_cart_data))
			{  
				// $thumbnail = (!empty($row->prod_img2))? $row->prod_img2 : $row->prod_img1;
				$thumbnail = $row->prod_img1;
				
				$item_attr = [];
				$item_attr['item_id'] = $pid;
				$item_attr['item_qty'] = 1;
				
				$item_attr['item_price'] = $row->prod_price;;
				$item_attr['product_name'] = $row->prod_name;
				$item_attr['thumbnail'] = $thumbnail;
				$item_attr['p_code'] = $row->prod_code;
				$item_attr['item_slug'] = $row->prod_slug;
				$item_attr['item_db_qty'] = $row->prod_qty;

				$b2b_cart_items = [];
				$b2b_cart_items[$pid] = $item_attr;
				
				// $this->session->set_userdata('b2b_cart_data', $b2b_cart_items);
				 $_SESSION['b2b_cart_data'] = $b2b_cart_items;
				
				
			} else {
				
				// $thumbnail = (!empty($row->prod_img2))? $row->prod_img2 : $row->prod_img1;
				$thumbnail = $row->prod_img1;

				$item_attr = [];
				$item_attr['item_id'] = $pid;
				$item_attr['item_qty'] = 1;
				$item_attr['item_price'] = $row->prod_price;
				$item_attr['product_name'] = $row->prod_name;
				$item_attr['thumbnail'] = $thumbnail;
				$item_attr['p_code'] = $row->prod_code;
				$item_attr['item_slug'] = $row->prod_slug;
				$item_attr['item_db_qty'] = $row->prod_qty;
				
				$index = '';
				// check if item allready exist in cart
				foreach($b2b_cart_data as $mykey=>$myval)
				{
					$val = (object) $myval;

					if($val->item_id == $pid)
					{
						$index = $mykey;
						break;
					}

				}

				if(!empty($index))
				{
					$b2b_cart_data[$index]['item_qty'] = $b2b_cart_data[$index]['item_qty']+1;
					// $this->session->set_userdata('b2b_cart_data', $b2b_cart_data);
                    $_SESSION['b2b_cart_data'] = $b2b_cart_data;
				} else {

					$b2b_cart_data[$pid] = $item_attr;
				
					// $this->session->set_userdata('b2b_cart_data', $b2b_cart_data);
                    $_SESSION['b2b_cart_data'] = $b2b_cart_data;
					
					
				}



			}

            	
 			 //  print_r($_SESSION['b2b_cart_data']);exit;

			// $b2b_cart_data = $this->session->userdata('b2b_cart_data');
            $b2b_cart_data = $_SESSION['b2b_cart_data'];
			if(isset($b2b_cart_data) and count($b2b_cart_data) > 0 )
			{
				// print_r($row);exit;
				
				$str = '';
				$total_amount = 0;

				foreach($b2b_cart_data as $mykey=>$myval)
				{
					$val = (object) $myval;
					$item_price = $val->item_price*$val->item_qty;
					$preorder_text = (empty($val->item_db_qty)) ? " <span style='color:red;font-size:12px;'>(Pre order)</span>" : '';
						
						$str .= ' <tr class="b2b_tr">
									<td class="tdw-img">
									<center> <a href="#"><img src="'. SITE_URL.'/admin/uploads/'.$val->thumbnail.'" class="pr-img img-responsive"></a></center>
									</td>
									<td style="text-align:left;padding-left:5px;"><span>'.$val->p_code.' - '.$val->product_name.''.$preorder_text.'</span></td>
									<td>
										<input type="text" maxlength="10" data-price="'.$val->item_price.'" onkeypress="return onlyNumberKey1(event);"  class="qty-text b2b_item_price" value = "'.$val->item_price.'" placeholder="00" require>
									</td>
									<td>
											<div class="qty-sec prom">
											<ul>
												<li class="qtyli b2bdec qtybutton" data-id="'.$mykey.'">-</li>
			<li><input type="text" maxlength="3" data-qty="'.$val->item_qty.'" onkeypress="return onlyNumberKey(event);" id="pquantity'.$mykey.'" class="qty-text b2b_qty" value="'.$val->item_qty.'"  require></li>
												<li data-id="'.$mykey.'" class="qtyli b2binc qtybutton">+</li>
											</ul>
										</div>                             
									</td>                        
									<td>
									£<span class="b2b_total_price">'.$item_price.'</span>
									</td>
									<td><center><a href="javascript:void(0);"><img data-key="'.$mykey.'" src="'.SITE_URL.'/b2b/img/del.png" class="del-img img-responsive remove_item"></a></center></td>
								</tr>';
								
								$total_amount = $total_amount+$item_price;
								


							}	

								$total_amount = number_format((float)$total_amount, 2, '.', '');
								$vat_percent = 20;
								$vat_amount = ($total_amount*$vat_percent)/100;
		
								$total_amount = $total_amount+$vat_amount;
								$carriage_amount = '';
								if(isset($_SESSION['carriage_amount']) and $_SESSION['carriage_amount'] > 0 )
								{
									$carriage_amount = $_SESSION['carriage_amount'];
									$total_amount =  $total_amount+$carriage_amount;
								}
				
											$str .=  '<tr class="b2b_vat_amount_area">
															<td  colspan="3" rowspan="1" class="tottxt-al total-head">Vat (20%) :</td>
															<td  colspan="3" rowspan="1" class="ambg totamt-al tamt-b">
																	£<span class="b2b_vat_amount">'.number_format((float)$vat_amount, 2, '.', '').'</span>
															</td>
															
														</tr>';
				
											$str .= '<tr class="b2b_carriage_amount_area">
														 <td  colspan="3" rowspan="1" class="tottxt-al total-head">Carriage Amount :</td>
														 <td  colspan="3" rowspan="1" class="totamt-al tamt-b">
															   <input type = "text" placeholder="0" name="b2b_carriage_amount" onkeypress="return onlyNumberKey1(event);" class="carragetxtf b2b_carriage_amount" value="'.$carriage_amount.'" >
														</td>
														
													</tr>';


											$str .= '<tr class="b2b_total_amount_area">
													<td  colspan="3" rowspan="1" class="tottxt-al total-head">Total Price :</td>
													<td  colspan="3" rowspan="1" class="ambg totamt-al tamt-b">
																£<span class="b2b_grand_total">'.$total_amount.'</span>
																</td>
															</tr>';		


							    $b2b_item_count = count($b2b_cart_data);
								echo json_encode(array("status" => 1,"msg" => $str , "response" => "success" , "total_b2b_count" => $b2b_item_count ));
								exit;

								


				


			} else {

				echo json_encode(array("status" => 0,"msg" => "Item does not exist in our system." , "response" => "fail" ));
				exit;
			}
			
		} else {

			echo json_encode(array("status" => 0,"msg" => "Item Id looks empty." , "response" => "fail" ));
			exit;
		}
		









        


?>