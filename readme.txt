=== PayPal API Subscriptions ===
Contributors: zackdesign
Donate link: http://www.zackdesign.biz/paypal-api-subscriptions
Tags: subscription, paypal, api, user, blog, register, wordpress, shortcode, plugin, billing, recurring, sandbox, payment
Requires at least: 2.5
Tested up to: 2.5.1
Stable tag: 1.0

Provides an all-in-one registration point for subscribers to your blog. Uses Paypal API and recurring billing.

== Description ==

Paypal API Subscriptions is designed to allow your users to register on your blog and subscribe all at once.
The Paypal API keeps them on your blog and means that you simply have to skin the Wordpress Admin to suit your
site theme. Because it is fully integrated with Wordpress you can keep track of your users easily and effectively.

This plugin defaults to the Paypal Sandbox for test results so you can test it for yourself.

You really need SSL before you can begin to think of using it for real. Download Admin SSL here: [Admin SSL](http://www.kerrins.co.uk/blog/admin-ssl/ "Admin SSL").

It's important to realise that you won't be able to test Express Checkout until you use your own developer API credentials.

Features:

 * Subscription button shortcode tags for your posts
 * Use your Paypal API credentials
 * Integrates with Wordpress registration seamlessly
 * Use a Wordpress Admin Theme to make the user transition completely transparent!
 * Adheres to all Paypal Guidelines and Requirements
 * Uses Wordpress-supplied error checking in the registration form for both transactions and fields! This means no registered users by accident.
 * Wordpress-MU compatibility!
 * Automatically tests using the Sandbox SDK credentials 

List of possible future features:

 * Ability to cancel subscriptions directly inside Wordpress
 * Further Paypal Profile changes
 * Additional subscription stuff like initial payment and trials 
 

Need help? Contact me at [Zack Design](http://www.zackdesign.biz "Zack Design").  
 
Please be aware that I'll only be updating this if I need to or if I'm paid to. Feel free to come on board and contribute!

== Installation ==

1. Upload the 'subscriptions' folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add tags to your post text: 

   In any post where you want a subscription button add this tag: 
   
   `[ppsa]NAME|PRICE|FREQUENCY|PERIOD[/ppsa]`
   
   This will create the subscription button. E.g. [ppsa]news|20|5|day[/ppsa] - this means 'bill me 20 dollars every 5 days for news'
   
   Acceptable period values: day, week, month, year

4. Test your registration form by clicking through from the subscription button

One thing to be aware of: I have included the main API credentials for testing direct payment. 
Add your own when testing Express Checkout.

Test data for the main API credentials:

Visa
4595258908900506
01/2010
Verification 962

All the other details can be what you like.

5. Enable SSL http://www.kerrins.co.uk/blog/admin-ssl/ (It's important to make sure wp-login.php and your Wordpress URL in General Settings are both set to SSL)
6. Place your Paypal API credentials in Wordpress Settings -> Paypal API Subscriptions
7. Optionally change the thank you message
8. It is recommended you test once again using SSL and real money (say 10, 20 cents)
9. Put up a Wordpress Admin Theme to reflect your site so that the transition is seamless! http://codex.wordpress.org/Creating_Admin_Themes
10. Create as many subscription buttons as you want!

--------------------------------------------

== Latest Changes ==

1.0

- First release!

== Frequently Asked Questions ==

= I Need HELP!!! =

That's what I'm here for. I do Wordpress sites for many people in a professional capacity and
can do the same for you. Check out www.zackdesign.biz

= CURL Error =

Your server needs something like php5-curl (if on Debian) to run this plugin and talk to Paypal. If you don't have shell access ask your host to fix it up for you. Else just ask me!

= Transaction Failure =

Read the error. Try checking your API credentials, try running without them, and try checking your currency code.
Also ensure that you're not doing something impossible like setting 30 months in a year.
'Did you try turning it on and off again?' - obligatory IT Crowd ref.