<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class B2bFront extends CI_Controller {

	
	public function __construct()
    {
        parent::__construct();
		$this->load->model("FrontModel");
		$this->prd_category=$this->FrontModel->getData("prd_category","ASC","id");
		$this->load->library('email');
	}
	
	public function errorfound()
	{
		$this->load->view('front/404error');
	}
	
	
	public function index()
	{
		//$this->session->unset_userdata("shopping_cart");
		$data["latest"]=$this->FrontModel->getallData("product","DESC","id","5");
		$data["featured"]=$this->FrontModel->getdatawithlimit("product","featured","1","id","5");
		$data["bestseller"]=$this->FrontModel->getdatawithlimit("product","best_saller","1","id","5");
		$data['homebanner']=$this->FrontModel->getdatabysingleid("homebanner","id","1","id");
		$data['homepopup']=$this->FrontModel->getdatabysingleid("home_popup","id","1","id");
		$this->load->view('front/index',$data);
	}

	public function b2bcustomer()
	{
		
		$this->load->view('front/b2bcustomer',$data);
	}

	

	
	
	
	

	// by ayaz ,15 dec 2023
	public function b2b_getdatasearch(){
		$_POST = json_decode(file_get_contents('php://input'), true);
		$fld= $_POST['searchText'];
		$latest=$this->FrontModel->getsearchdata("product",$fld);
		if($latest==""){ $data[] = array(); }
		else{
		foreach($latest as $latest) {
			$data[] = array("name"=>$latest->p_name,"id"=>$latest->id,"p_code"=>$latest->p_code,"thumbnail"=>$latest->thumbnail,"slug"=>$latest->p_slug,"price"=>'£'.$latest->final_price);
		}	
		}
		echo json_encode($data);
	}


	// by ayaz 14 dec 2023 ,  total price of items
	public function b2b_count_total_amount()
	{
		$total_amount = 0;
		$b2b_cart_data = $this->session->userdata('b2b_cart_data');
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

	// by ayaz 14 dec 2023,  count total item in cart
	public function b2b_count_total_item()
	{
		$total_item = 0;
		$b2b_cart_data = $this->session->userdata('b2b_cart_data');
		if(isset($b2b_cart_data) and !empty($b2b_cart_data))
		{
				$total_item = 0;
				// check if item allready exist in cart
				foreach($b2b_cart_data as $mykey=>$myval)
				{
					$total_item = $total_item+1;
					
				}
				
		}
		
		return $total_item;
	}

	// by ayaz 14 dec 2023, save customer detail
	public function save_b2b_customer_detail()
	{
		// print_r($_POST);exit;
		extract($_POST);

		if($customer_name == "")
		{
			$error .= "Please enter the name."."<br>";
		}
		if($customer_email == "")
		{
			$error .= "Please enter the email address."."<br>";
		}

		if($customer_email != "")
		{
			if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
				$error .= "Invalid email format."."<br>";
			  }

		}
		if($customer_phone == "")
		{
			$error .= "Please enter the phone number."."<br>";
		}
		if($customer_phone != "")
		{
			
			
			$char = substr($customer_phone, 0, 1);
			if($char == 0)
			{
				$error .= "Phone number can not be start with zero."."<br>";
			}

			else if(!preg_match("^[1-9][0-9]*|0^", $customer_phone)) { 
				$error .= "phone number not valid"."<br>";
			}
			else if(strlen($customer_phone) < 10)
			{
				$error .= "Phone number must be 10 digit in length."."<br>";
			}
			else if(strlen($customer_phone) > 10)
			{
				$error .= "Phone number must be 10 digit in length."."<br>";
			}

		}

		if($customer_address == "")
		{
			$error .= "Please enter the address."."<br>";
		}

		if(!$error)
		{
			$total_order=$this->FrontModel->countAllData("tbl_b2b_order");
			
			$i = $total_order+1;
			$num = str_pad($i, 4, '0', STR_PAD_LEFT);

			$orderid = 'B2B'.$num;

			// count total price 
			$total_amount = $this->b2b_count_total_amount();
			$total_product = $this->b2b_count_total_item();
			
			

			$data=array(
				
				"order_no"=>$orderid,
				"total_price"=>$total_amount,
				"total_product"=> $total_product,
				"customer_name" =>$customer_name,
				"customer_email" =>$customer_email,
				"customer_phone" =>$customer_phone,
				"customer_business_name" =>$customer_business_name,
				"customer_address" =>$customer_address,
				"order_status"=>"1",
				"cancel_reason"=>"NA",
				"create_date"=>date('Y-m-d')
			 );

				$data = $this->security->xss_clean($data);
				$order_id = $this->FrontModel->addData("tbl_b2b_order",$data);
				

				if($order_id)
				{
					$b2b_cart_data = $this->session->userdata('b2b_cart_data');
					if(isset($b2b_cart_data) and !empty($b2b_cart_data)) {
					
						$cartarray = $b2b_cart_data;
						foreach($cartarray as $product=>$val) {
						
							$p_slug = ($val['item_slug']) ? $val['item_slug'] : '';
						
							$odata=array(
								"orderno"=>$orderid,
								"pid"=>$val['item_id'],
								"p_name"=>$val['product_name'],
								"pcode"=>$val['p_code'],
								"final_price"=>number_format($val['item_price'], 2),
								"p_slug"=>$p_slug,
								"thumnbail"=>$val['thumbnail'],
								"quantity"=>$val['item_qty'],
								"createon"=>date('Y-m-d')
							 );
	
							$odata=$this->security->xss_clean($odata);
							$order_detail=$this->FrontModel->addData("tbl_b2b_order_detail",$odata);

						}
					}
							if($order_detail)
							{


								$baseurl = base_url();
				
								$htmlContent = "<html>
								<head>
								<meta charset='utf-8'>
								<title>Order Confirmation</title>
								</head>
								<body style='margin: 0;'>
								<table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
								<tbody>
								<tr>
								<td valign='top'>
								<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tbody>
								<tr>
								<td>
								<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tbody>
								<tr>
								<td  align='center' style=' width: 100%;'>
								<a href='".$baseurl."'><img src='".$baseurl."/assets/front/images/logo/logo.png' style='display: block; border: 0; width:100px;'></a></td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td valign='top'>
								<table width='100%' border='0' cellspacing='10' cellpadding='0' style='border-top: 2px solid;'>
								<tbody>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td>
				
								<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tbody>
								<tr>
								<td width='70%' align='left' style='  font-size:24px;font-family: arial;color: #000;line-height: 24px;'>
								Dear ".$customer_name." , 
								</td>
								<td width='30%' align='right' style='  font-size: 14px;font-family: arial;color: #848484;line-height: 24px;'>
								ORDER #".$orderid."
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td valign='top'>
								<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tbody>
								<tr>
								<td width='70%'  style='font-size:24px; font-family: arial; color:#848484; line-height: 25px;'>Thank you for your Order
								</td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td width='70%'  style='font-size: 14px; font-family: arial; color:#848484; line-height: 25px;'>If you have any query. please send us an email ASAP.
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td>
								<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tbody>
								
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td valign='top'>
								<table width='100%' border='0' cellspacing='10' cellpadding='0' style='border-top:1px solid #b1b1b1;'>
								<tbody>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td>
								<table width='100%' border='0' cellspacing='0' cellpadding='0'>
								<tbody>
								<tr>
								<td style='font-size:17px;
								font-family: arial;
								color: #848484; white-space: nowrap;
								'>Order summary</td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>";
								$b2b_cart_data = $this->session->userdata('b2b_cart_data');
								foreach($b2b_cart_data as $product=>$val){

									$tot_amount = $val['item_qty']*$val['item_price'];
									$tot_amount = number_format((float)$tot_amount, 2, '.', '');
								$htmlContent .= "<tr>
								<td style='font-family: arial; width: 10%;'>
								<img src='".$baseurl."assets/upload/product/".$val['p_code']."/".$val['thumbnail']."' align='left' width='100' height='100' style='margin-right:15px;border-radius:8px;border:1px solid #e5e5e5;'>
								</td>
								<td style='font-family: arial; width:100%;'>
								<span style='font-size:13px;font-weight:600;line-height:1.4;color:#555'>".$val['product_name']."</span> 
								</td>
								<td style='font-family: arial; width:100%;'>
								<span style='font-size:13px;font-weight:600;line-height:1.4;color:#555'>".$val['item_qty']."</span> 
								</td>
								<td style='font-family: arial;white-space:nowrap;'>
								<p style='color:#555;line-height:150%;font-size:13px;font-weight:600;margin:0 0 0 15px' align='right'>&#163; ".$tot_amount."</p>
								</td>
								</tr>
				
								<tr>
								<td>&nbsp;</td>
								</tr>";
								}
								
								$htmlContent .= "
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td valign='top'>
								<table width='100%' border='0' cellspacing='10' cellpadding='0' style='border-top:1px solid #b1b1b1;'>
								<tbody>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								
								<tr>
								<td>&nbsp;</td>
								<td width='33%' style='font-size: 14px;font-family: arial;color: #848484;line-height: 24px; text-align: right;'> Total Amount
								</td>
								<td width='33%' style='font-size: 14px;font-family: arial;color: #848484;line-height: 24px; text-align: right;'>&#163;".$total_amount."
								</td>
								</tr>
								
								
				
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td valign='top'>
								<table width='100%' border='0' cellspacing='10' cellpadding='0' style='border-top:1px solid #b1b1b1;'>
								<tbody>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td width='100%' style='  font-size:17px;font-family: arial;color: #000;line-height: 24px;'>
								Customer information
								</td>
								<td>&nbsp;</td>
								</tr>
								<tr>
								<td width='100%' style='  font-size:14px;font-family: arial;color:#848484;;line-height: 24px;'>
								".$customer_name."<br>
								".$customer_phone."<br>
								".$customer_email."<br>
								".$customer_business_name." <br>
								".$customer_address." <br>
								
								</td>
								<td>&nbsp;</td>
								</tr>
								
								
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td valign='top'>
								<table width='100%' align='center' border='0' cellspacing='0' cellpadding='0' style='border-top:1px solid #b1b1b1; padding-top: 15px;'>
								<tbody>
								<tr>
								<td width='50%' align='center' style='font-family: arial; font-size: 16px; color:#848484;'>Follow us
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td valign='top'>
								<table width='100%' align='center' border='0' cellspacing='10' cellpadding='0'>
								<tbody>
								<tr>
								<td width='43%'>&nbsp;</td>
								<td width='7%' align='right' style='background: #000;border-radius: 50px;'>
								<a href='#'><img src='".$baseurl."/assets/front/images/emailer/facebook-icon.png' style='display: block; border: 0;padding: 10px;'></a>
								</td>
								<td width='7%' style='background: #000;border-radius: 50px;'>
								<a href='#'><img src='".$baseurl."/assets/front/images/emailer/twitter.png' style='display: block; border: 0; padding: 10px;'></a>
								</td>
								<td width='43%'>&nbsp;</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>
								</body>
								</html>";
								
									$config['mailtype'] = 'html';
									$config['protocol'] = 'sendmail';
									$config['mailpath'] = '/usr/sbin/sendmail';
									$config['charset'] = 'iso-8859-1';
									$config['wordwrap'] = TRUE;
									$this->email->initialize($config);
									//$this->email->to('farman@quinterocorp.com');
									$this->email->to($customer_email);
									// $this->email->cc('info@harrowdecor.co.uk');
									$this->email->from('info@harrowdecor.co.uk','HerrowDecore');
									$this->email->subject('B2B Order Confirmation | Harrow Decor');
									$this->email->message($htmlContent);
									//$this->email->attach($file);
									 $this->email->send();
									
								}
								
									// $this->session->unset_userdata("shopping_cart");

									// manage 4th screen content


									$str = '';

									$str .= ' <div class="col-sm-6 custo-detail">
												<h2>Custome Details</h2>
												<ul>
													<li><span>Name:</span>'.$customer_name.'</li>
													<li><span>Contact No.:</span> +91-'.$customer_phone.'</li>
													<li><span>Email:</span>'.$customer_email.' </li>';
													if($customer_business_name != ""){
													$str .= '<li><span>Company:</span>'.$customer_business_name.'</li>';
													}
													$str .= '<li><span>Address:</span>'.$customer_address.'</li>';
												$str .= '</ul></div>';

											$str .= '<div class="col-sm-6 ord-detail">
											<h2>Order Details</h2>
											<table>';

											$str .= '<tr>
													<th>#</th>
													<th>Product Name</th>
													<th>Qty</th>
													<th>Amount(£)</th>';
											$k=1;	
											foreach($b2b_cart_data as $key=>$myval){
												
												$val = (object)$myval;
												$tot_amount = $val->item_qty*$val->item_price;
												$tot_amount = number_format((float)$tot_amount, 2, '.', '');
												
												$str .= '<tr>
													<th>'.$k.'</th>
													<th class="txt-al">'.$val->product_name.'</th>
													<th>'.$val->item_qty.'</th>
													<th>'.$tot_amount.'</th>
												</tr>';
												$k++;

											}
												
												$str .= '<tr>
													<td colspan="2" rowspan="1" class="tottxt-al"><b>Amount</b></td>
													<td colspan="2" class="totamt-al">£'.$total_amount.'</td>';
													
												$str .= '</tr></table></div>';

												$this->session->unset_userdata('b2b_cart_data');

												echo json_encode(array("status" => 1,"msg" => $str ,  "response" => "success" , "order_id" => $orderid ));
												exit;



				}

						
			} else {

						echo json_encode(array("status" => 0,"msg" => $error ,  "response" => "fail" ));
						exit;

			}


	}

	// by ayaz 14 dec 2023 update cart on change price
	public function update_b2bcart_price()
	{
		extract($_POST);
		if(empty($pid) || empty($price))
		{
			echo json_encode(array("status" => 2,"msg" => "update price or item id looks empty" , "response" => "fail" ));
			exit;
		}
		$b2b_cart_data = $this->session->userdata('b2b_cart_data');
		
		$b2b_cart_data[$pid]['item_price'] = $price;

		$this->session->set_userdata('b2b_cart_data', $b2b_cart_data);

		$total_amount = $this->b2b_count_total_amount();

		echo json_encode(array("status" => 1,"msg" => 'done' , "b2b_total_amount" => $total_amount ,  "response" => "success" ));
		exit;


		
	}
	// by ayaz 14 dec 2023 update cart on increament or decreament
	public function updateb2bCart()
	{
		extract($_POST);

		if(empty($pid) || empty($qty) )
		{
			echo json_encode(array("status" => 2,"msg" => "qty or item id looks empty" , "response" => "fail" ));
			exit;
		}
		$b2b_cart_data = $this->session->userdata('b2b_cart_data');
		// 

		
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
					$this->session->set_userdata('b2b_cart_data', $b2b_cart_data);
				}

				$total_amount = $this->b2b_count_total_amount();


				echo json_encode(array("status" => 1,"msg" => "done" , "b2b_total_amount" => $total_amount ,  "response" => "success" ));
				exit;
		}


	}

	// remove b2b item from cart
	public function removeb2bItem()
	{
		
		// print_r($_POST);exit;
	
		if(isset($_POST['mykey']) and $_POST['mykey'] > 0 )
		{
			
			$my_key = $_POST['mykey'];
			$b2b_cart_data = $this->session->userdata('b2b_cart_data');
			
			// remove item from session and reindex session array again
			
			if(isset($b2b_cart_data) and !empty($b2b_cart_data))
			{

				
				unset($b2b_cart_data[$my_key]);
				$this->session->set_userdata('b2b_cart_data', $b2b_cart_data);

				$b2b_cart_data = $this->session->userdata('b2b_cart_data');
				$item_count = count($b2b_cart_data);
				
				$total_amount = $this->b2b_count_total_amount();
				
				echo json_encode(array("status" => 1,"msg" => "done" , "b2b_item_count" => $item_count , "b2b_total_amount" => $total_amount ,  "response" => "success" ));
				exit;

				

			} else {

				echo json_encode(array("status" => 0,"msg" => "Item Id looks empty." , "response" => "fail" ));
				exit;
			}

		}
	}

	// by ayaz , 14 dec 2023 , add item to cart
	public function setb2bCookiesData()
	{
		
		// print_r($_POST);
		

		if(isset($_POST['pid']) and !empty($_POST['pid']))
		{
			$pid = $_POST['pid'];

			//  unset($_SESSION['b2b_cart_items']);
			//  print_r($_SESSION['b2b_cart_items']);
			//  exit;
			
			$row = $this->FrontModel->editData("product",$pid);
			$b2b_cart_data = $this->session->userdata('b2b_cart_data');
			
			
			
			if(!isset($b2b_cart_data) and empty($b2b_cart_data))
			{
				$item_attr = [];
				$item_attr['item_id'] = $pid;
				$item_attr['item_qty'] = 1;
				
				$item_attr['item_price'] = $row->final_price;
				$item_attr['product_name'] = $row->p_name;
				$item_attr['thumbnail'] = $row->thumbnail;
				$item_attr['p_code'] = $row->p_code;
				$item_attr['item_slug'] = $row->p_slug;
				$item_attr['item_qty'] = 1;

				// $_SESSION['b2b_cart_items'][$pid] = $item_attr;
				$b2b_cart_items = [];
				$b2b_cart_items[$pid] = $item_attr;
				
				$this->session->set_userdata('b2b_cart_data', $b2b_cart_items);
				
				
				
			} else {
				
				$item_attr = [];
				$item_attr['item_id'] = $pid;
				$item_attr['item_qty'] = 1;
				$item_attr['item_price'] = $row->final_price;
				$item_attr['product_name'] = $row->p_name;
				$item_attr['thumbnail'] = $row->thumbnail;
				$item_attr['p_code'] = $row->p_code;
				$item_attr['item_qty'] = 1;
				
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
					$this->session->set_userdata('b2b_cart_data', $b2b_cart_data);
				} else {

					$b2b_cart_data[$pid] = $item_attr;
				
					$this->session->set_userdata('b2b_cart_data', $b2b_cart_data);
					
					
				}



			}

				// echo $index.'ayaz';
 			 //  print_r($_SESSION['b2b_cart_items']);exit;

			$b2b_cart_data = $this->session->userdata('b2b_cart_data');
			if(isset($b2b_cart_data) and count($b2b_cart_data)>0 )
			{
				// print_r($row);exit;
				
				$str = '';
				$total_amount = 0;

				foreach($b2b_cart_data as $mykey=>$myval)
				{
					$val = (object) $myval;
					$item_price = $val->item_price*$val->item_qty;
						
						$str .= ' <tr class="b2b_tr">
									<td class="tdw-img">
									<center> <a href="#"><img src="'. base_url().'/assets/upload/product/'.$val->p_code.'/'.$val->thumbnail.'" class="pr-img img-responsive"></a></center>
									</td>
									<td><span>'.$val->product_name.'</span></a></td>
									<td>
										<input type="text" maxlength="10"  class="qty-text b2b_item_price" value = "'.$val->item_price.'" placeholder="00" require>
									</td>
									<td>
											<div class="qty-sec prom">
											<ul>
												<li class="qtyli b2bdec qtybutton" data-id="'.$mykey.'">-</li>
			<li><input type="text" maxlength="3" onkeypress="return onlyNumberKey(event);" id="pquantity'.$mykey.'" class="qty-text b2b_qty" value="'.$val->item_qty.'"  require></li>
												<li data-id="'.$mykey.'" class="qtyli b2binc qtybutton">+</li>
											</ul>
										</div>                             
									</td>                        
									<td>
									£<span class="b2b_total_price">'.$item_price.'</span>
									</td>
									<td><center><a href="javascript:void(0);"><img data-key="'.$mykey.'" src="'.base_url().'/assets/b2bimages/del.png" class="del-img img-responsive remove_item"></a></center></td>
								</tr>';
								
								$total_amount = $total_amount+$item_price;
								


							}	

							if($total_amount > 0)
							{
								  
								$total_amount = number_format((float)$total_amount, 2, '.', '');
								$str .= 	'<tr>
											
											
								<td  colspan="3" rowspan="1" class="tottxt-al total-head">Total Price :</td>
								<td  colspan="3" rowspan="1" class="totamt-al tamt-b">
											£<span class="b2b_grand_total">'.$total_amount.'</span>
											</td>
											
										</tr>';
							 }else{

								$str .= 	'<tr>
											
													<td colspan="6">
														<b>Empty cart, please add item.</b>
													</td>
																	
											</tr>';
							 }			
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
		
		
				


	}
	
	public function b2bsearchinputdata(){
		$baseurl = base_url();
		$_POST = json_decode(file_get_contents('php://input'), true);
		$slug= $_POST['slug'];
		echo $baseurl."product/".$slug;
		//echo json_encode($data);
	}
	
}
