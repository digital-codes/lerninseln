<template>

<!--
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="6YZLGJFX3M27W">

<input  v-bind:value=url type="hidden" name="notify_url">
<input v-bind:value="invoice" type="hidden" name="invoice" >

<input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
</form>

</template>

<script>

// see https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/formbasics/
// Note: normally invoice number must be unique. can be deactivated in profile
// else duplicate id lead to "system error" after payment process

// ecnryption see:
//    https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/encryptedwebpayments/
//    https://developer.paypal.com/docs/paypal-payments-standard/integration-guide/encryptedwebpayments/

// button monagement: https://developer.paypal.com/docs/archive/button-manager/integration-guide/#inventory-management-for-digital-downloads
// IPN: https://developer.paypal.com/docs/api-basics/notifications/ipn/IPNIntro/
// https://github.com/paypal/ipn-code-samples/tree/master/php

/*
You should not write HTML button code for saved buttons. Always use the code that PayPal generates. 
However, you can enhance the generated code for saved buttons by adding hidden HTML
 variables that do not affect the transaction amount. 
 For example, you can enhance saved buttons with automatic fill-out variables.

Recordkeeping with passthrough variables

Some variables are exclusively for your own use, such as order management. PayPal returns the values that you send through Instant Payment Notification exactly as you sent them. For this reason, they are called passthrough variables. Their values are not recorded or used by PayPal.

The following are passthrough variables:

    custom
    item_number oritem_number_x
    invoice

<INPUT TYPE="hidden" name="charset" value="utf-8">

Instant Payment Notification — notify_url

    Someone pays you.
    PayPal POSTs FORM variables to a URL you specify that runs a program to process the variables.

    You validate the notification.

    Figure 3. How IPN Works — General Steps

Detailed description of each step:

    A payment or a refund triggers IPN. This payment can be via PayPal Payments Standard FORMs 
    or via the PayPal Web Services APIs for Express Checkout, MassPay, or RefundTransaction.
     If the payment has a "Pending" status, you receive another IPN when the payment clears, fails, or is denied.
    PayPal posts HTML FORM variables to a program at a URL you specify. 
    You can specify this URL either in your account profile or with the notify_url variable on each transaction. 
    This post is the heart of IPN. Included in the notification is the payment information, 
    such as the payer's name and the amount paid. All possible variables in IPN posts are 
    detailed in the Instant Payment Notification Guide. When your server receives a notification, 
    it must process the incoming data.
    Your server must then validate the notification to ensure that it is legitimate. 
    For details, see the Instant Payment Notification Guide.



*/

import { defineComponent } from 'vue'; 

export default defineComponent({
  props: ["url","xyz","invoice"]
});

</script>

<style scoped>

.paypal {
  text-align: left;
  white-space: normal;
  margin-top: 0;
  margin-bottom: 0;
}
</style>

