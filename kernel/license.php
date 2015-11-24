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
      
      $data = array("deviceid"=>$deviceid,"used"=>1);
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
  
    
  public static function ValidateLicense_()  {
	// Display Keys
	$response = "";
	$licenses = array();
	$db = Utility::mysqlRes();	
	try {
                foreach ($db->license() as $licensedata) {
                $licenses[]  = array(
                        'status'=>"success",
                        'id' => $licensedata['id'],
                        'license' => $licensedata['licensekey']);
                }
		$response = $licenses;
	} 
	catch (Exception $ex) {
		$response = array ('status'=>"failed",'description'=>$ex->getMessage());
	}
    return $response;
  }
  
  public static function StoreInfo($content) {
	  $dbres = $this->dbconect();
  }
}