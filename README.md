<img src="./assets/logo.svg" alt="drawing" width="50"/>

# CHIP for Tour Master

This module adds CHIP payment method option to your [Tour Master](https://codecanyon.net/item/tour-master-tour-booking-travel-wordpress-plugin/20539780) plugin.

## Installation

* [Download plugin zip file.](https://github.com/CHIPAsia/chip-for-tour-master/archive/refs/heads/main.zip)
* Log in to your Wordpress admin panel and go: **Plugins** -> **Add New**
* Select **Upload Plugin**, choose zip file you downloaded in step 1 and press **Install Now**
* Activate plugin

## Configuration

Set the **Brand ID** and **Secret Key** in the plugins settings.

For currency settings, set the value to **MYR**.

## Setting payment method whitelist

To configure for payment method whitelist, you may utilize this filters:

* **tourmaster_chip_payment_send_params_tour**
* **tourmaster_chip_payment_send_params_room**

Example as follows:

```php
  add_filter( 'tourmaster_chip_payment_send_params_tour', 'chip_payment_method_whitelist', 10, 2 );
  add_filter( 'tourmaster_chip_payment_send_params_room', 'chip_payment_method_whitelist', 10, 2 );

  function chip_payment_method_whitelist( $send_params, $tid ) {
    // available option: ['fpx', 'fpx_b2b1', 'duitnow_qr', 'visa', 'mastercard', 'razer_tng', 'razer_maybank_qr', 'razer_shopeepay', 'razer_grabpay']
    $send_params['payment_method_whitelist'] = ['fpx'];
    return $send_params;
  }
```

## Screenshot

![Set API Key](./assets/api_key.png "Set Secret Key & Brand ID Screenshot")

## Other

Facebook: [Merchants & DEV Community](https://www.facebook.com/groups/3210496372558088)