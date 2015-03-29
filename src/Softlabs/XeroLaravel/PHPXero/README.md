PHP-Xero PHP Wrapper
====================

No longer maintained
------------
Please note the PHP-Xero wrapper library is no longer under active development.
All development effort is going into the [XeroOAuth-PHP library](https://github.com/XeroAPI/XeroOAuth-PHP).

Introduction
------------
A class for interacting with the xero (xero.com) private application API.  It could also be used for the public application API too, but it hasn't been tested with that.  More documentation for Xero can be found at http://blog.xero.com/developer/api-overview/  It is suggested you become familiar with the API before using this class, otherwise it may not make much sense to you - http://blog.xero.com/developer/api/

Thanks for the Oauth* classes provided by Andy Smith, find more about them at http://oauth.googlecode.com/.  The
OAuthSignatureMethod_Xero class was written by me, as required by the Oauth classes.  The ArrayToXML classes were sourced from wwwzealdcom's work as shown on the comment dated August 30, 2009 on this page: http://snipplr.com/view/3491/convert-php-array-to-xml-or-simple-xml-object-if-you-wish/  I made a few minor changes to that code to overcome some bugs.

Requires
--------
PHP5+

Authors
--------
Ronan Quirke, Xero (just very minor bugfixes, vast majority of work done by David Pitman)


License
-------
License (applies to Xero and Oauth* classes):
The MIT License

Copyright (c) 2007 Andy Smith (Oauth* classes)
Copyright (c) 2010 David Pitman (Xero class)
Copyright (c) 2012 Ronan Quirke, Xero (Xero class)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

Usage
-----

Basically instantiate the Xero class with your credentials and desired output format.  Then call any of the methods as outlined in the API.  Calling an API method name as a property is the same as calling that API method with no options. Calling the API method as a method with an array as the only input param with like calling the corresponding POST or PUT API method.  You can make more complex GET requests using up to four params on the method.  If you have read the xero api documentation, it should be clear.

### GET Request usage

Retrieving a result set from Xero involves identifying the endpoint you want to access, and optionally, setting some parameters to further filter the result set.
There are 5 possible parameters:

1. Record filter: The first parameter could be a boolean "false" or a unique resource identifier: document ID or unique number eg: $xero->Invoices('INV-2011', false, false, false, false);
2. Modified since: second parameter could be a date/time filter to only return data modified since a certain date/time eg: $xero->Invoices(false, "2012-05-11T00:00:00");
3. Custom filters: an array of filters, with array keys being filter fields (left of operand), and array values being the right of operand values.  The array value can be a string or an array(operand, value), or a boolean eg: $xero->Invoices(false, false, $filterArray);
4. Order by: set the ordering of the result set eg: $xero->Invoices('', '', '', 'Date', '');
5. Accept type: this only needs to be set if you want to retrieve a PDF version of a document, eg: $xero->Invoices($invoice_id, '', '', '', 'pdf');

Further details on filtering GET requests here: http://blog.xero.com/developer/api-overview/http-get/

### Example Usage:
```php
<?php

// Include the class file
include_once "xero.php";

// Define your application key and secret (find these at https://api.xero.com/Application)
define('XERO_KEY','[APPLICATION KEY]');
define('XERO_SECRET','[APPLICATION SECRET]');

// Instantiate the Xero class with your key, secret and paths to your RSA cert and key
// The last argument is optional and may be either "xml" or "json" (default)
// "xml" will give you the result as a SimpleXMLElement object, while 'json' will give you a plain array object
$xero = new Xero(XERO_KEY, XERO_SECRET, '[path to public certificate]', '[path to private key]', 'xml' );

if($_REQUEST['sample']=="") {

	// The input format for creating a new contact see http://blog.xero.com/developer/api/contacts/ to understand more
	$new_contact = array(
		array(
			"Name" => "API TEST Contact",
			"FirstName" => "TEST",
			"LastName" => "Contact",
			"Addresses" => array(
				"Address" => array(
					array(
						"AddressType" => "POBOX",
						"AddressLine1" => "PO Box 100",
						"City" => "Someville",
						"PostalCode" => "3890"
					),
					array(
						"AddressType" => "STREET",
						"AddressLine1" => "1 Some Street",
						"City" => "Someville",
						"PostalCode" => "3890"
					)
				)
			)
		)
	);

	// Create the contact
	$contact_result = $xero->Contacts( $new_contact );

	// The input format for creating a new invoice (or credit note) see http://blog.xero.com/developer/api/invoices/
	$new_invoice = array(
		array(
			"Type"=>"ACCREC",
			"Contact" => array(
				"Name" => "API TEST Contact"
			),
			"Date" => "2010-04-08",
			"DueDate" => "2010-04-30",
			"Status" => "AUTHORISED",
			"LineAmountTypes" => "Exclusive",
			"LineItems"=> array(
				"LineItem" => array(
					array(
						"Description" => "Just another test invoice",
						"Quantity" => "2.0000",
						"UnitAmount" => "250.00",
						"AccountCode" => "200"
					)
				)
			)
		)
	);

	// The input format for creating a new payment see http://blog.xero.com/developer/api/payments/ to understand more
	$new_payment = array(
		array(
			"Invoice" => array(
				"InvoiceNumber" => "INV-0002"
			),
			"Account" => array(
				"Code" => "[account code]"
			),
			"Date" => "2010-04-09",
			"Amount"=>"100.00",
		)
	);


	// Raise an invoice
	$invoice_result = $xero->Invoices( $new_invoice );

	$payment_result = $xero->Payments( $new_payment );

	// Get details of an account, with the name "Test Account"
	$result = $xero->Accounts(false, false, array("Name"=>"Test Account") );
	// The params above correspond to the "Optional params for GET Accounts" on http://blog.xero.com/developer/api/accounts/

	// To do a POST request, the first and only param must be a multidimensional array as shown above in $new_contact etc.

	// Get details of all accounts
	$all_accounts = $xero->Accounts;

	// Echo the results back
	if ( is_object($result) ) {
		// Use this to see the source code if the $format option is "xml"
		echo htmlentities($result->asXML()) . "<hr />";
	} else {
		// Use this to see the source code if the $format option is "json" or not specified
		echo json_encode($result) . "<hr />";
	}

}

if($_REQUEST['sample']=="pdf"){

	// first get an invoice number to use

	$org_invoices = $xero->Invoices;

	$invoice_count = sizeof($org_invoices->Invoices->Invoice);

	$invoice_index = rand(0,$invoice_count);

	$invoice_id = (string) $org_invoices->Invoices->Invoice[$invoice_index]->InvoiceID;

	if(!$invoice_id) {
		echo "You will need some invoices for this...";
	}

	// Now retrieve that and display the pdf
	$pdf_invoice = $xero->Invoices($invoice_id, '', '', '', 'pdf');

	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="the.pdf"');

	echo ($pdf_invoice);
}
```
