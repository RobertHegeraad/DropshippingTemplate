(function() {


// Contact Name:
// Country/Region:
// Street Address:
// Apartment, suite, unit etc. (optional)
// City:
// State/Province/Region:
// Zip/Postal Code:
// Tel:  
// Mobile

$(document).on('ready', function(e) {
	var html = '<div id="ali-header">';
	html += '<button id="ali-paste-button" class="ali-button">Paste address</button>';
	html += '<button id="ali-copy-button" class="ali-button">Copy address</button>';
	html += '<div id="ali-message"></div>';
	html += '</div>';

	$("body").append(html);
});

$(document).on('mousedown', "#ali-copy-button", function(e) {
	SetShippingAddress();
});

$(document).on('mousedown', "#ali-paste-button", function(e) {
	chrome.storage.local.get('shipping_address', GetShippingAddress);
});

// $(document).on('keyup', function(e) {
// 	if(e.keyCode == 67) {
// 		SetShippingAddress();
// 	}

// 	if(e.keyCode == 86) {
// 		chrome.storage.local.get('shipping_address', GetShippingAddress);
// 	}
// });

function GetShippingAddress(result) {
	if(typeof result.shipping_address !== 'undefined') {
		var data = JSON.parse(result.shipping_address);
		
		$("#ali-message").html("Filling in address...");

		$("input[name='contactPerson']").val(data.name);
		$("input[name='address']").val(data.address);
		$("input[name='address2']").val(data.number);
		$("input[name='city']").val(data.city);
		$("input[name='province']").val(data.state);
		$("input[name='zip']").val(data.postcode);
		$("input[name='phoneCountry']").val(data.phoneCountry);
		$("input[name='phoneArea']").val(data.phoneArea);
		$("input[name='phoneNumber']").val(data.phoneNumber);
		$("input[name='mobileNo']").val(data.mobileNo);
		$("textarea.message-text").val(data.excerpt);

  		chrome.storage.local.clear(ClearShippingAddress);

  		$("#ali-message").html("Done");
	} else {
  		$("#ali-message").html("Address not found, did you copy it from your website?");
	}
}

function SetShippingAddress() {
	if(!$("#order_data").length) {
		return;
	}

	$("#ali-message").html("Copying address...");

	var contactFirstName = $("#_shipping_first_name").val(),
		contactLastName = $("#_shipping_last_name").val(),
		address = $("#_shipping_address_1").val(),
		number = $("#_shipping_address_2").val(),
		postcode = $("#_shipping_postcode").val(),
		city = $("#_shipping_city").val(),
		state = $("#meta-4256-value").val(),
		excerpt = $("#excerpt").val(),
		country = $("#select2-chosen-5").html(),
		phoneCountry = $("#meta-4257-value").val(),
		phoneCity = $("#meta-4258-value").val(),
		phoneNumber = $("#meta-4259-value").val();
		mobile = $("#meta-4260-value").val();

	var data = {
		"name": contactFirstName + " " + contactLastName,
		"address": address,
		"number": number,
		"postcode": postcode,
		"city": city,
		"state": state,
		"excerpt": excerpt,
		"country": country,
		"phoneCountry": phoneCountry,
		"phoneArea": phoneCity,
		"phoneNumber": phoneNumber,
		"mobileNo": mobile
	};

	console.log(data);

	var json = JSON.stringify(data);

	chrome.storage.local.set({'shipping_address': json}, SetShippingAddressSuccess);
}

function SetShippingAddressSuccess() {
  $("#ali-message").html("Done");
  console.log(data);
}

function ClearShippingAddress() {
	console.log("Cleared shipping address");
}

})();