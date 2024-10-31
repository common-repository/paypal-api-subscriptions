<?php
/*
Plugin Name: Paypal API Subscriptions
Plugin URI: http://zackdesign.biz
Description: This plugin creates the ability for users to register on Wordpress and subscribe using the Paypal API at the same time
Author: Isaac Rowntree
Version: 1.0
Author URI: http://zackdesign.biz

	Copyright (c) 2005, 2006 Isaac Rowntree (http://zackdesign.biz)
	QuickShop is released under the GNU General Public
	License (GPL) http://www.gnu.org/licenses/gpl.txt

	This is a WordPress plugin (http://wordpress.org).

*/
session_start();

// define URL
define('PPSAFOLDER', dirname(plugin_basename(__FILE__)));
// MU Compatibility
if (function_exists('get_current_site'))
    define('PPSA_URLPATH', get_option('siteurl').'/wp-content/mu-plugins/' . PPSAFOLDER.'/');
else
    define('PPSA_URLPATH', get_option('siteurl').'/wp-content/plugins/' . PPSAFOLDER.'/');

// Show the Form
function paypal_api_subscription_form() 
{    
    // Output the hidden fields and the form
    if ($_SESSION['subscribe'] == 1)
    {
        // All very self-explanatory: this form has all the HTML needed
        require_once('form.php');
        
    }
}

// Error checking and Transactions
//
// @errors = the WP_Error object
//
function paypal_api_subscription_post_and_error($errors)
{
    // Form field validation
    if ($_SESSION['subscribe'] == 1)
    {        
        if ( empty( $_POST['first_name'] ) )
    	    	$errors->add('first_name',"<strong>ERROR: </strong> Please enter your first name.");
    	    	
        if ( empty( $_POST['last_name'] ) )
    	    	$errors->add('l_name',"<strong>ERROR: </strong> Please enter your last name.");
    	    	
        if ( empty( $_POST['credit_card_type'] ) || ($_POST['credit_card_type'] == ' ') )
     	    	$errors->add('cctype',"<strong>ERROR: </strong> You need to provide your credit card type.");
     	    	
        if ( empty( $_POST['cc_number']) && empty($_POST['cc_number1']) )
     	    	$errors->add('ccnum',"<strong>ERROR: </strong> You need to provide your credit card number.");
     	    	
        if ( empty( $_POST['cvv2_number'] ))
     	    	$errors->add('cvv2',"<strong>ERROR: </strong> You need to provide your credit card verification code.");
        
        if ( empty( $_POST['address1'] )) 
     	    	$errors->add('cvv2',"<strong>ERROR: </strong> You need to provide a street address.");
        
        if ( empty( $_POST['city'] )) 
     	    	$errors->add('cvv2',"<strong>ERROR: </strong> You need to provide your city.");
        
        if ( empty( $_POST['state'] )) 
     	    	$errors->add('cvv2',"<strong>ERROR: </strong> You need to provide your state.");
        
        if ( empty( $_POST['zip'] )) 
     	    	$errors->add('cvv2',"<strong>ERROR: </strong> You need to provide your zip/postal code.");
        
        if ( empty( $_POST['country_code'] )) 
     	    	$errors->add('cvv2',"<strong>ERROR: </strong> You need to provide your country.");
        

        // Only run the transaction if there are no form field errors
        $count = 0;
        foreach ($errors as $error)
        {
            foreach ($error as $e)
                $count++;
        }
        
        if ( !$count && ($_SESSION['subscribe'] == 1) )
        {
            // Paypal API class - it just works! :D
            require_once 'paypal_api.php';
            
            $p=new paypal();
            
            // Generate current date for profile start date.
            $start_time = strtotime(date('m/d/Y'));
            $start_date = date('Y-m-d\T00:00:00\Z',$start_time);

            // Create ALL the values to be sent via CURL
            $p->addvalue('METHOD', 'CreateRecurringPaymentsProfile');
            $p->addvalue('CREDITCARDTYPE', $_POST['credit_card_type']);
            if (empty($_POST['cc_number']) && !empty($_POST['cc_number1']))
                $p->addvalue('ACCT', $_POST['cc_number1']);
            else
                $p->addvalue('ACCT', $_POST['cc_number']);
            $p->addvalue('EXPDATE',str_pad( $_POST['expdate_month'], 2, '0', STR_PAD_LEFT).$_POST['expdate_year']);
            $p->addvalue('CVV2', $_POST['cvv2_number']);
            $p->addvalue('FIRSTNAME', $_POST['first_name']);
            $p->addvalue('LASTNAME', $_POST['last_name']);
            $p->addvalue('PROFILESTARTDATE', $start_date);
            $p->addvalue('BILLINGFREQUENCY', $_SESSION['frequency']);
            $p->addvalue('BILLINGPERIOD', $_SESSION['period']);
            $p->addvalue('SUBSCRIBERNAME', $_POST['user_login']);
            $p->addvalue('AMT', $_SESSION['price']);
            $p->addvalue('DESC', $_SESSION['name']);
            $p->addvalue('CURRENCYCODE', $p->currency);
            $p->addvalue('STREET', $_POST['address1']);
            if (!empty($_POST['address2']))
                $p->addvalue('STREET2', $_POST['address2']);
            $p->addvalue('CITY', $_POST['city']);
            $p->addvalue('STATE', $_POST['state']);
            $p->addvalue('ZIP', $_POST['zip']);
            $p->addvalue('COUNTRYCODE', $_POST['country_code']);
            
            // Run it!
            // Use 'true' to display the string sent to Paypal if you're unsure what's going on.
            // $data = $p->call_paypal(true);
            $data = $p->call_paypal();
            
		        // If successful we can go to step 2!
            if (strcasecmp($data['ACK'],'SUCCESS') == 0)
                $_SESSION['subscribe'] = 2;
            else // Bounce back to the register form with error info. Probably a result of poorly-entered credit card info...
                $errors->add('failed',"<strong>ERROR: </strong> The transaction failed. ".$data['L_LONGMESSAGE0'].' '.$p->show_error());
        }
    }
    else if ( $_POST['subscribe'] == 3 )
    {
        // We're using the error checking of Wordpress to help with the first step
        SetExpressCheckout($errors);
    }
    return $errors;
}

// Step 1 messages
//
// @msg = the string containing all the messages
//
function paypal_api_subscription_message_step1($msg)
{
    // Do some house-keeping.
    // I've set it so that the session is over-written if a new subscription button is pressed on the site
    
    if ($_GET['subscribe'] == 'canceled')
        session_destroy();
    
    if (!empty($_GET['subscribe']) || !isset($_SESSION['subscribe']))
    {
        $_SESSION['subscribe'] = $_GET['subscribe'];
        if (!empty($_GET['frequency']))
            $_SESSION['frequency'] = $_GET['frequency'];
        if (!empty($_GET['period']))
            $_SESSION['period'] = $_GET['period'];
        if (!empty($_GET['name']))
            $_SESSION['name'] = $_GET['name'];
        if (!empty($_GET['price']))
            $_SESSION['price'] = $_GET['price'];
    }
    if ($_SESSION['subscribe'] == 1)
    {
        // Add an 's' to the end of day, month, year, semi-month...
        if ($_SESSION['frequency'] > 1)
            $plural = 's';
        else
            $plural = '';

        // Currency by default is USD.
        $currency = get_option('ppsa_cc');
        if (empty($currency))
            $currency = 'USD';

        $sub = $_SESSION['frequency'] . ' ' . $_SESSION['period'].$plural . ' of ' . $_SESSION['name'] . ' for $' . $_SESSION['price'] . ' ' . $currency;
        
        // Completely clear any previous messages with this one. You're not missing anything!
        $msg = '<div class="message register">
                <h2>'.get_bloginfo('name').' Subscription</h2><br />

        <p>You are subscribing to the following:</p><br />
        <p style="text-decoration: underline; font-weight: bold;">'.$sub.'</p><br />
        <p>Please note that this is a recurring billing cycle. You are also registering on '.get_bloginfo('name').'.</p><br />
        <p><strong><a href="?action=register&subscribe=canceled">Cancel this subscription process.</a></strong></p>
        </div>';
    }
    else if ($_SESSION['subscribe'] == 5) // second-last step in Express Checkout
    {
        // Completely clear any previous messages with this one. You're not missing anything!
         $msg = '<p class="message">You have successfully paid your subscription and will be billed on a recurring basis. Please continue by registering.</p>';   
    }
    
    return $msg;
}

// Step 2 messages
//
// @msg = the string containing all the messages
//
function paypal_api_subscription_message_step2($msg) 
{
    if ($_SESSION['subscribe'] == 2)
    {
        // Append a new message to the 'successful registration' one. We're now ready to direct the user
        $step2 = get_option('ppsa_step2');
        if (!empty($step2))
            $msg .= '<p class="message">'.$step2.'</p>';
        else
            $msg .= '<p class="message">You have successfully paid your subscription and will be billed on a recurring basis.</p>';   
        
        // Time to say bye-bye to the session
        session_destroy();
    }
    else if ($_SESSION['subscribe'] == 5) // Express checkout version!
    {
        // Append a new message to the 'successful registration' one. We're now ready to direct the user
        $step4 = get_option('ppsa_step4');
        if (!empty($step4))
            $msg .= '<p class="message">'.$step4.'</p>';
        
        // Time to say bye-bye to the session
        session_destroy();
    }
    
    
    return $msg;
}

// Add options page
function paypal_api_subscriptions_options_page() 
{
     add_options_page('Paypal API Subscriptions', 'Paypal API Subscriptions', 8, __FILE__, 'paypal_api_subscriptions_options');
}

// Options page form
function paypal_api_subscriptions_options()
{
    $sb = get_option('ppsa_sandbox');
    $user = get_option('ppsa_username');
    
    if (!empty($sb) || empty($user))
        $sb = 'checked="checked"';
    
    echo '
    <div class="wrap"><h2>Subscription Options</h2>
    
    <form method="post" action="options.php">
    
    '.wp_nonce_field('update-options').'
    
    <h3>Paypal API</h3>
    <p><a href="http://www.paypal.com/">Login to PayPal</a> (to set up API or manage users)</p>
    <p><a href="https://developer.paypal.com/">Paypal Developer Central</a></p>
    <p><strong>HIGHLY IMPORTANT:</strong> You require SSL when using your API credentials and 
    when you take your user\'s credit card details. Please use the following plugin for Wordpress SSL:
    <a href="http://www.kerrins.co.uk/blog/admin-ssl/">Admin SSL</a></p>
    <p>Please do not use your API credentials until SSL is working.</p>
    <table class="form-table">
     
        <tr valign="top">
            <th scope="row">Sandbox Mode?</th>
            <td><input type="checkbox" name="ppsa_sandbox" '.$sb.' /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Username</th>
            <td><input type="text" name="ppsa_username" style="width: 40%" value="'.$user.'"  /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Password</th>
            <td><input type="text" name="ppsa_password" value="'.get_option('ppsa_password').'"  /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Signature</th>
            <td><input type="text" name="ppsa_signature" style="width: 60%" value="'.get_option('ppsa_signature').'"  /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Currency Code</th>
            <td><input type="text" name="ppsa_cc" value="'.get_option('ppsa_cc').'"  />  - defaults to USD</td>
        </tr>
    
    </table>
    
    <h3>Thank you Message (Direct Payment)</h3>
    <p>May include HTML. Needs to include verification that the transaction process has completed. Can also send your users elsewhere via HTML link. One is already provided by default but you can put your own down here.</p>
    
    <textarea name="ppsa_step2" id="ppsa_step2" style="width: 30%;" rows="10" cols="50">'.get_option('ppsa_step2').'</textarea>
    
    <h3>Thank you Message (Express Checkout)</h3>
    <p>May include HTML. Needs to include verification that the transaction process has completed. Can also send your users elsewhere via HTML link. One is already provided by default but you can put your own down here.</p>
    
    <textarea name="ppsa_step4" id="ppsa_step4" style="width: 30%;" rows="10" cols="50">'.get_option('ppsa_step4').'</textarea>
    
    <p class="submit">
<input type="submit" name="Submit" value="Update Options &raquo;" />
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="ppsa_username,ppsa_password,ppsa_signature,ppsa_step2,ppsa_step4,ppsa_cc,ppsa_sandbox" />
</p>
    </div>
    ';
}

// This function is called to display the subscribe button on the page
//
// @content = the content to replace
//
function paypal_api_subscriptions_button($content)
{
        $pattern = '/\[ppsa\].+\[\/ppsa\]/';
        preg_match_all ($pattern, $content, $matches);
        
        foreach ($matches[0] as $match)
        {            
            // Get rid of the surrounding shortcodes
            $pattern = '/\[ppsa\]/';
            $m = preg_replace ($pattern, '', $match);
            $content = preg_replace ($pattern, '', $content);
            
            $pattern = '/\[\/ppsa\]/';
            $m = preg_replace ($pattern, '', $m);
            $content = preg_replace ($pattern, '', $content);

            // Only show subscription button if user is logged out. No point otherwise
            if (!is_user_logged_in())
            {
                // If the pieces aren't in the right order this won't work
                // Error checking is done by the transaction so will probably
                // leave this until a later version unless it's a huge problem...
                $pieces = explode('|',$m);
                
                $options = '<input type="hidden" name="frequency" value="'.$pieces[2].'">
                            <input type="hidden" name="period" value="'.$pieces[3].'">
                            <input type="hidden" name="name" value="'.$pieces[0].'">
                            <input type="hidden" name="price" value="'.$pieces[1].'">';

                $replacement = '<div class="ppsa_subscription_form"><form class="subscription_form_1" action="'.get_bloginfo('wpurl').'/wp-login.php" method="get">
                                <input type="image" src="'.PPSA_URLPATH.'images/subscribe.gif" border="0" name="submit" alt="'.get_bloginfo('name').'">
                                '.$options.'<input type="hidden" name="subscribe" value="1"><input type="hidden" name="action" value="register"></form>
                                <span class="ppsa_or">OR</span>
                                <form class="subscription_form_2" style="margin: 0; border: 0; padding: 0; background: none;" action="'.get_bloginfo('wpurl').'/wp-login.php" method="post"> 
                                <input alt="Make payments with PayPal - it\'s fast, free and secure!" name="submit" src="'. PPSA_URLPATH.'images/xpress.gif" type="image" /> 
                                <input type="hidden" name="subscribe" value="3"><input type="hidden" name="action" value="register">'.$options.'</form></div>';
            }
            else
                $replacement = '';
            
            // Replace using the original match as the template. 
            $m = preg_replace ('/\|/', '\|', $m);
            $content = preg_replace('/'.$m.'/', $replacement, $content);    
        }
        return $content;
}

// While processing the API call tell the user to wait and clear the register button
function paypal_api_subscriptions_processing() 
{
    // At the moment this is the best I can do as wp_enqueue_script doesn't seem to want to work here
    ?>
    
    <script type='text/javascript' src='<?php echo PPSA_URLPATH; ?>js/jquery-1.2.6.min.js'></script>
    
    <script type="text/javascript">
		$(document).ready(function(){

        <?php
        
            // Further Javascript to remove all doubt of where they're registering
            if (!empty($_SESSION['subscribe']))
            {
        ?>

        $(".submit input").click(function () {
            $(this).attr({disabled : "disabled"});
            $(this).val("Please wait... processing transaction.");
            $(this).css("color","red");
        });
        
        $("#registerform p label:first").prepend('Your New <?php bloginfo("name"); ?> ');
                
        <?php
        
            }
        
        ?>

    });
	  </script>
    
    <?php
}

// ExpressCheckout function step 1
//
// @errors = the errors we will clear and substitute with our own if this transaction fails
//
function SetExpressCheckout($errors)
{    
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['period'] = $_POST['period'];
    $_SESSION['frequency'] = $_POST['frequency'];
    
    // Paypal API class - it just works! :D
    require_once 'paypal_api.php';
            
    $p=new paypal();

    // Create ALL the values to be sent via CURL
    $p->addvalue('METHOD', 'SetExpressCheckout');
    $p->addvalue('L_BILLINGTYPE0', 'RecurringPayments');
    $p->addvalue('L_BILLINGAGREEMENTDESCRIPTION0', $_SESSION['name']);
    $p->addvalue('AMT', $_SESSION['price']);
    $p->addvalue('RETURNURL',get_bloginfo('url'));
    $p->addvalue('CANCELURL',get_bloginfo('url'));
            
    // Run it!
    // Use 'true' to display the string sent to Paypal if you're unsure what's going on.
    // $data = $p->call_paypal(true);
    $data = $p->call_paypal();
            
		// If successful we can go to step 2!
    if (strcasecmp($data['ACK'],'SUCCESS') == 0)
    {        
        $_SESSION['subscribe'] = 4;
        
        if ($p->sandbox)
            $url = $p->express_sandbox_url;
        else
            $url = $p->express_url;
            
        $redirect_to = $url.'?cmd=_express-checkout&token='.$data['TOKEN'];
	      wp_redirect($redirect_to);
	      
	      exit();
    }
    else // Bounce back to the register form with error info. Probably a result of poorly-entered credit card info...
    {
        unset($errors->errors);
                
        $errors->add('failed',"<strong>ERROR: </strong> The transaction failed. ".$data['L_LONGMESSAGE0'].' '.$p->show_error().
        '<br /><br /><strong><a href="'.get_bloginfo('url').'" >&laquo; Back to '. get_bloginfo('title', 'display' ).'</a></strong>');
                
    }
    return $errors;
}

// Load the subscription stuff before Wordpress pulls up the headers.
function ppsa_init()
{
    if ($_SESSION['subscribe'] == 4)
        CreateRecurringPaymentsProfile(); // ExpressCheckout function step 2
}

// ExpressCheckout function step 2
function CreateRecurringPaymentsProfile()
{
    $token = $_GET['token'];
    $ID = $_GET['PayerID'];
    
    // Paypal API class - it just works! :D
    require_once 'paypal_api.php';
            
    $p=new paypal();
    
    // Generate current date for profile start date.
    $start_time = strtotime(date('m/d/Y'));
    $start_date = date('Y-m-d\T00:00:00\Z',$start_time);

    // Create ALL the values to be sent via CURL
    $p->addvalue('METHOD', 'CreateRecurringPaymentsProfile');
    $p->addvalue('PROFILESTARTDATE', $start_date);
    $p->addvalue('BILLINGFREQUENCY', $_SESSION['frequency']);
    $p->addvalue('BILLINGPERIOD', $_SESSION['period']);
    $p->addvalue('AMT', $_SESSION['price']);
    $p->addvalue('DESC',$_SESSION['name']);
    $p->addvalue('CURRENCYCODE', $p->currency);
    $p->addvalue('TOKEN', $token);
    $p->addvalue('PayerID', $ID);
            
    // Run it!
    // Use 'true' to display the string sent to Paypal if you're unsure what's going on.
    // $data = $p->call_paypal(true);
    $data = $p->call_paypal();
    
		// If successful we can go to step 3!
    if (strcasecmp($data['ACK'],'SUCCESS') == 0)
    {            
        $_SESSION['subscribe'] = 5;
        $redirect_to = get_bloginfo('wpurl').'/wp-login.php?action=register';
	      wp_redirect($redirect_to);
	      
	      exit();
    }
    else // Bounce back to the register form with error info. Probably a result of timed-out-edness
    {
        echo "<strong>ERROR: </strong> The transaction failed. ".$data['L_LONGMESSAGE0'].' '.$p->show_error().
        '<br /><br /><strong><a href="'.get_bloginfo('url').'" >&laquo; Back to '. get_bloginfo('title', 'display' ).'</a></strong>';
        session_destroy();
        die();
    }
}

// Displays the form inside the register form
add_action('register_form', 'paypal_api_subscription_form');

// Runs (very minimal as of right now) error checking and transaction posting via CURL
add_filter('registration_errors','paypal_api_subscription_post_and_error');

// Shows information about what the user is registering for in the first message
add_filter('login_message','paypal_api_subscription_message_step1');

// Shows the user a success message and invites them to proceed further if necessary
add_filter('login_messages','paypal_api_subscription_message_step2');

// An options page for the API information and other details
add_action('admin_menu','paypal_api_subscriptions_options_page');

// Convert shortcode into subscription buttons 
add_filter('the_content', 'paypal_api_subscriptions_button');

// Ensure that the 'Register' button can't be clicked twice during API call
add_action('login_head', 'paypal_api_subscriptions_processing');

// Make sure the second step for Express Checkout works!
add_action('init', 'ppsa_init');

?>