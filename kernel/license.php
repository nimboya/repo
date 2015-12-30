<?php
/*
@ Author: Ewere Diagboya
@ Date: 21-11-2015
@ Time: 1:04pm
@ Location: Ajah, Lagos
@ Project: Superstore API
*/
class License {
// License Validator
  /*
  @params
	- $useragent
	- $token
	- $licensekey
  */
 public static function ValidateLicense($licenseparams) {
      if(empty($licenseparams['key'])) {
          $response[] = array('error_code'=>'2','status'=>'failed','description'=>'No License Key Entered');
      }
      if(empty($licenseparams['deviceid'])) {
          $response[] = array('error_code'=>'2','status'=>'failed','description'=>'Device ID is empty');
      }
      
      $response = array();
      $db = Utility::mysqlRes();
      $license = $db->license()->where("licensekey",$licenseparams['key'])->where("used",0);
       
      if($license->count()) {      
		  if(License::ApplyLicense($licenseparams)){
              $response[] = array('error_code'=>'0','status'=>'success','description'=>'Product Activated'); 
          }
      } else {
          $response[] = array('error_code'=>'1','status'=>'failed','description'=>'Invalid or Used License Key');
      }
      return $response;
  }
  
  public static function ValidateRequest ($token) {
      $status = false;
      
      if(strlen($token) == 9) {
            $status = true;
	}  else {
            $status = false;
        }
      return $status;
  }
  
  public static function ApplyLicense($applyparams){
      $db = Utility::mysqlRes();
      $key = $applyparams['key'];
      $deviceid = $applyparams['deviceid'];
	  
      $data = array("licensekey"=>$key,"used"=>1,"deviceid"=>$deviceid);
      $license = $db->license()->where('licensekey',$key);
      if($license->fetch()) {
          try{
              $license->update($data);
			  return true;
          } catch (Exception $ex) {
              return false;
          }
      }
  }
  
  /*
  @params
  
  name - Store Name
  address - Address
  phone - Phone Number
  email - Email
  state - State in the Country
  city - City (Benin, Auchi)
  lga - Local Government Area
  country - Country
  deviceid - DeviceID
  
  */
  
  public static function StoreInfo($storeparams) {
	  
	  $db = Utility::mysqlRes();
	  $response = array();
	  $errors = array();
	  
	  $name = isset($storeparams['name']) ? $storeparams['name'] : null;
	  $email = isset($storeparams['email']) ? $storeparams['email'] : null;
	  $address = isset($storeparams['address']) ? $storeparams['address'] : null;
	  $phone = isset($storeparams['phone']) ? $storeparams['phone'] : null;
	  $state = isset($storeparams['state']) ? $storeparams['state'] : null;
	  $city = isset($storeparams['city']) ? $storeparams['city'] : null;
	  $lga = isset($storeparams['lga']) ? $storeparams['lga'] : null;
	  $country = isset($storeparams['country']) ? $storeparams['country'] : null;
	  $deviceid = isset($storeparams['deviceid']) ? $storeparams['deviceid'] : null;
	  // Input Validation
	  if(strlen(trim($name)) === 0){
        $errors[] = "Please enter your store name!";
	  }
	  
	  if(strlen(trim($address)) === 0){
        $errors[] = "Please enter your store address!";
	  }
	  
	  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid!";
	  }
	  
	  if(strlen(trim($state)) === 0){
        $errors[] = "Please enter your state!";
	  }
	  
	  if(strlen(trim($city)) === 0){
        $errors[] = "Please enter your city!";
	  }
	  
	  if(strlen(trim($lga)) === 0){
        $errors[] = "Please enter your local government!";
	  }
	  
	  if(strlen(trim($country)) === 0){
        $errors[] = "Please enter your country!";
	  }
	  
	  if(strlen(trim($deviceid)) === 0){
        $errors[] = "Please enter your DeviceID!";
	  }
	  // DeviceID Parameter
	  
	  if(empty($errors)){
        //Process Registration.
		$proc = $db->store->insert($storeparams);
		$response[] = array('error_code'=>'0','status'=>'ok','description'=>'Success'); 
      } else {
		$response[] = array('error_code'=>'1','status'=>'failed','description'=>$errors);
	  }
	  return $response; 
  }
  
  
}