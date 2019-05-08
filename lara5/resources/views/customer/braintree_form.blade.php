

<div class="form-table-wrapper" style="background:#fff;">
<form action="/user/check-credit-card/" method="POST" id="braintree-payment-form">
<table class="form-table">
<tr><td colspan="2"><h2>Customer Information</h2></td></tr>
<tr class="first-td">
<td class="form-table-left">First Name</td>
<td><input type="text" name="first_name" id="first_name" placeholder=" First Name *" value="<? if(isset($data["user_data"])){echo $data["user_data"][0]->username;} ?>"/></td>
</tr>


<tr>
<td class="form-table-left">Last Name</td>
<td><input type="text" name="last_name" id="last_name" placeholder=" Last Name *" /></td>
</tr>

<tr class="first-td">
<td class="form-table-left">Postal Code</td>
<td><input type="text" name="postal_code" id="postal_code" placeholder=" Postal Code *" value="<? if(isset($data["user_data"])){echo $data["user_data"][0]->zipcode;} ?>" /></td>
</tr>

<tr><td colspan="2"><h2>Credit Card</h2></td></tr>

<tr class="first-td">
<td class="form-table-left">Card Number</td>
<td><input type="text" name="number" size="20" placeholder=" Card Number *" autocomplete="off" data-encrypted-name="number" value="<? if(isset($data["user_data"])){echo $data["user_data"][0]->credit_card_number;} ?>" />
</tr>
<tr>
<td class="form-table-left">CVV</td>
<td><input type="text" name="cvv" size="4" placeholder=" CVV *" autocomplete="off" data-encrypted-name="cvv" />
</tr>
<tr class="first-td">
<td class="form-table-left">Expiration (MM/YYYY)</td>
<td><input type="text" name="month" size="2" placeholder=" MM *" data-encrypted-name="month" style="width:50px;" /> / <input type="text" name="year" size="4" data-encrypted-name="year" placeholder=" YYYY *" style="width:50px;" />
</tr>
<tr>
<td></td>
<td>
<input class="submit-button" type="submit" value="Save Card" />
</td>
</tr>
</table>
</form>
</div>
<script type="text/javascript" src="https://js.braintreegateway.com/v1/braintree.js"></script>
<script type="text/javascript">
var braintree = Braintree.create("wny236k7npnksjzs");
braintree.onSubmitEncryptForm("braintree-payment-form");
</script>