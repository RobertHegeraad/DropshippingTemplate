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

$(document).on('keyup', function(e) {
	if(e.keyCode == 67) {
		SetShippingAddress();		
	}

	if(e.keyCode == 86) {
		chrome.storage.local.get('shipping_address', GetShippingAddress);
	}
});

function GetShippingAddress(result) {
	alert("Getting address...");
	if(typeof result.shipping_address !== 'undefined') {
		var data = JSON.parse(result.shipping_address);
		alert(result.shipping_address);
		console.log(data);

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

  		chrome.storage.local.clear(ClearShippingAddress);
	} else {
		alert("Not set");
	}
}

function SetShippingAddress() {
	if(!$("#order_data").length) {
		return;
	}

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

	var json = JSON.stringify(data);

	alert(json);

	chrome.storage.local.set({'shipping_address': json}, SetShippingAddressSuccess);
}

function SetShippingAddressSuccess() {
  alert("Set value: " + json);
  console.log(data);
}

function ClearShippingAddress() {
	console.log("Cleared shipping address");
}

})();