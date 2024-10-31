<?php 

/* 

   This class is lifted directly from the Paypal Developer forum. 
   http://www.paypaldeveloper.com/pdn/board/message?board.id=nvp&thread.id=1952
   Thanks to the incredible user 'Boanerges' who released this class
   I have been able to modify it slightly to suit myself!

*/

class paypal
{
    var $postdata=array();
    var $response=array();
    var $username;
    var $password;
    var $signature=NULL;
    var $certfile=NULL;
    var $proxy=NULL;
    var $currency;
    var $sandbox;
    var $error;
    var $version="52.0";
    var $sandbox_url="https://api-3t.sandbox.paypal.com/nvp";
    var $live_url="https://api-aa-3t.paypal.com/nvp";
    var $express_sandbox_url="https://www.sandbox.paypal.com/webscr";
    var $express_url="https://www.paypal.com/cgi-bin/webscr";
	  
    //initialize 
	  function __construct($proxy=NULL)
 	  {
	      // SandBox info set here automatically for testing purposes
        $this->username = get_option('ppsa_username');
        $this->password = get_option('ppsa_password');
        $this->signature = get_option('ppsa_signature');
        $this->sandbox = get_option('ppsa_sandbox');
            
        if (empty($this->username))
        {
            $this->username = 'sdk-three_api1.sdk.com';
            $this->password = 'QFZCWN5HZM8VBG7Q';
            $this->signature = 'A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU';
            $this->sandbox = true;
        }
            
        // Currency by default is USD.
        $currency = get_option('ppsa_cc');
        if (empty($currency))
            $this->currency = 'USD';

	      if(is_file($cert)) 
            $this->certfile=$cert;
	      
	      if($proxy) $this->proxy=$proxy;
	}
	//add values to the array
	function addvalue($key, $val, $limit=NULL)
	{
	    $v=$val;
	    if(is_numeric($limit)) $v=substr($v,0,$limit);
	    $this->postdata[$key]=urlencode($v);
	}
	//clear the array for a new call
	function resetdata()
	{
	    $this->postdata=array();
	}
	
	function call_paypal($showurl=false)
	{
	    $this->postdata['USER']=urlencode($this->username);
	    $this->postdata['PWD']=urlencode($this->password);
	    if($this->signature) $this->postdata['SIGNATURE']=urlencode($this->signature);
	    if(!isset($this->postdata['VERSION'])) $this->postdata['VERSION']=$this->version;
  
	    $url=($this->sandbox) ? $this->sandbox_url : $this->live_url;
	    $nvp=NULL;
	    foreach($this->postdata as $k => $v):
	        $nvp.="$k=$v&";
	    endforeach;
	    if(!$nvp) return false;
	    //strip out the last character, which is a &
	    $nvp=substr($nvp, 0, -1);
	    if($showurl) echo $nvp;
	    //curl request
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    if($this->certfile) curl_setopt($ch, CURLOPT_SSLCERT, $this->certfile);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    if($this->proxy)
		  {
		    curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		    curl_setopt ($ch, CURLOPT_PROXY,$this->proxy);
		  }
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $nvp);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $mydata = curl_exec ($ch);
	    if(curl_error($ch)) 
		  {
		      $this->error = (curl_error($ch));
		      echo $this->error;
		      return false;
		  }
	    curl_close ($ch);
	
	    return $this->process_response($mydata);
	}
	
	function process_response($str)
	{
	    $data=array();
	    $x=explode("&", $str);
	    foreach($x as $val):
		      $y=explode("=", $val);
		      $data[$y[0]]=urldecode($y[1]);
	    endforeach;
	    return $data;
	}
	
	function show_error()
	{
	    return $error;
	}
}

?>