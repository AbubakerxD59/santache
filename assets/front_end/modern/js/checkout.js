"use strict";
var stripe1;
var fatoorah_url = "";
var currency = $("#currency").val();
var supported_locals = $("#supported_locals").val();

// Helper function to check if custom charges should apply based on delivery type
function shouldApplyCustomCharges() {
  var local_pickup = $("input[name='local_pickup']:checked").val() || "0";
  var custom_charges_doorstep = $("#custom_charges_doorstep").val() || "1";
  var custom_charges_pickup = $("#custom_charges_pickup").val() || "0";

  if (local_pickup == "1") {
    // Pickup from store
    return custom_charges_pickup == "1";
  } else {
    // Doorstep delivery
    return custom_charges_doorstep == "1";
  }
}

function getApplicableCustomChargesTotal() {
  let total = 0;

  if ($("#custom_charges_json").length === 0) return 0;

  let product_type = $("#product_type").val();
  let local_pick_up = $("input[name='local_pickup']:checked").val() || "0";
  let type = local_pick_up == "1" ? "pickup" : "doorstep";

  try {
    let charges = JSON.parse($("#custom_charges_json").val()) || [];

    charges.forEach((c) => {
      let allow = false;

      if (product_type === "digital_product") {
        allow = c.apply_digital == 1;
      } else {
        allow =
          (type === "doorstep" && c.apply_doorstep == 1) ||
          (type === "pickup" && c.apply_pickup == 1);
      }

      if (allow) {
        total += parseFloat(c.amount) || 0;
      }
    });
  } catch (e) {
    console.error("Invalid custom_charges_json", e);
  }

  return total;
}

function getApplicableCustomCharges() {
  let total = 0;
  let list = [];

  if ($("#custom_charges_json").length === 0) {
    return { total, list };
  }

  let product_type = $("#product_type").val();
  let local_pick_up = $("input[name='local_pickup']:checked").val() || "0";

  let charges = [];
  try {
    charges = JSON.parse($("#custom_charges_json").val()) || [];
  } catch (e) {
    console.error("Invalid custom_charges_json");
    return { total, list };
  }

  charges.forEach((c) => {
    let apply = false;

    // DIGITAL PRODUCT
    if (product_type === "digital_product") {
      apply = c.apply_digital == 1;
    }
    // PHYSICAL PRODUCT
    else {
      let type = local_pick_up == "1" ? "pickup" : "doorstep";
      apply =
        (type === "doorstep" && c.apply_doorstep == 1) ||
        (type === "pickup" && c.apply_pickup == 1);
    }

    if (apply) {
      let amt = parseFloat(c.amount) || 0;
      total += amt;
      list.push(c);
    }
  });

  return { total, list };
}

// Helper function to update custom charges visibility and recalculate total
function updateCustomChargesVisibility() {
  let result = getApplicableCustomCharges();
  let list = $("#custom_charges_list");
  
  list.html("");

  if (result.list.length === 0) {
    $("#custom_charges_section").addClass("d-none");
    return;
  }

  result.list.forEach((c) => {
    
    list.append(`
      <div class="d-flex justify-content-between py-1 custom-charge-item">
        <span class="text-muted">${c.name.replaceAll("_", " ")}</span>
        <span class="text-muted">${currency} ${parseFloat(c.amount).toFixed(
      2,
    )}</span>
      </div>
    `);
  });

  $("#custom_charges_section").removeClass("d-none");
}

$(document).ready(function () {
  // Ensure Door Step Delivery is selected by default
  if (!$('input[name="local_pickup"]:checked').length) {
    $("#door_step").prop("checked", true);
  }

  function initCheckoutZipAutocomplete() {
    if (window.AddressZipcodeAutocomplete) {
      window.AddressZipcodeAutocomplete.bind({
        zipcodes: window.ADDRESS_ZIPCODES || [],
        cities: window.ADDRESS_CITIES || [],
        zipInput: "#checkout_pincode",
        cityInput: "#checkout_city",
        cityIdInput: "#checkout_city_id",
        zipSuggestionsList: "#checkout-zipcode-suggestions",
        citySuggestionsList: "#checkout-city-suggestions",
      });
    } else {
      setTimeout(initCheckoutZipAutocomplete, 50);
    }
  }
  initCheckoutZipAutocomplete();

  // Ensure the correct button styling is applied
  $('input[name="local_pickup"]').each(function () {
    if ($(this).is(":checked")) {
      $(this).next("label").addClass("active");
    } else {
      $(this).next("label").removeClass("active");
    }
  });

  updateCustomChargesVisibility();
  // Trigger initial calculation
  setTimeout(function () {
    if (typeof computeFinalTotal === "function") {
      computeFinalTotal();
    }
  }, 100);
});
function getDeliveryType() {
  return $("input[name='local_pickup']:checked").val() == "1"
    ? "pickup"
    : "doorstep";
}
// =============================
// CUSTOM CHARGES HELPERS
// =============================
function getFilteredCustomCharges(type) {
  let total = 0;

  if ($("#custom_charges_json").length === 0) return 0;

  try {
    let charges = JSON.parse($("#custom_charges_json").val());

    charges.forEach((c) => {
      if (
        (type === "doorstep" && c.apply_doorstep == 1) ||
        (type === "pickup" && c.apply_pickup == 1)
      ) {
        total += parseFloat(c.amount) || 0;
      }
    });
  } catch (e) { }

  return total;
}

// const Toast = Swal.mixin({
//     toast: true,
//     position: 'top-end',
//     showConfirmButton: false,
//     timer: 1500,
//     timerProgressBar: true
// })
$(document).ready(function () {
  var mindate = "",
    maxdate = "";
  if ($("#delivery_starts_from").val() != "") {
    mindate = moment().add($("#delivery_starts_from").val() - 1, "days");
  } else {
    mindate = null;
  }

  if ($("#delivery_ends_in").val() != "") {
    maxdate = moment(mindate).add($("#delivery_ends_in").val() - 1, "days");
  } else {
    maxdate = null;
  }
  $("#datepicker").daterangepicker({
    showDropdowns: false,
    alwaysShowCalendars: true,
    autoUpdateInput: false,
    singleDatePicker: true,
    autoApply: true,
    minDate: mindate,
    maxDate: maxdate,
    locale: {
      format: "DD/MM/YYYY",
      separator: " - ",
      cancelLabel: "Clear",
      label: "Preferred Delivery Date",
    },
  });

  $("#datepicker").attr({
    placeholder: "Preferred Delivery Date",
    autocomplete: "off",
  });
  $("#datepicker").on("cancel.daterangepicker", function (ev, picker) {
    $(this).val("");
    $("#start_date").val("");
  });
  $("#datepicker").on("apply.daterangepicker", function (ev, picker) {
    var drp = $("#datepicker").data("daterangepicker");
    var current_time = moment().format("HH:mm");
    if (moment(drp.startDate).isSame(moment(), "d")) {
      $(".time-slot-inputs").each(function (i, e) {
        if ($(this).data("last_order_time") < current_time) {
          $(this).prop("checked", false).attr("required", false);
          $(this).parent().hide();
        } else {
          $(this).attr("required", true);
          $(this).parent().show();
        }
      });
    } else {
      $(".time-slot-inputs").each(function (i, e) {
        $(this).attr("required", true);
        $(this).parent().show();
      });
    }
    $("#start_date").val(drp.startDate.format("YYYY-MM-DD"));
    $("#delivery_date").val(drp.startDate.format("YYYY-MM-DD"));
    $(this).val(picker.startDate.format("MM/DD/YYYY"));
  });
});
$(document).ready(function () {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
  });

  var csrfName = "";
  var csrfHash = "";
  var addresses = [];
  let newlyCreatedAddressId = null;

  if ($("#csrf_token").length) {
    csrfName = $("#csrf_token").attr("name") || csrfName;
    csrfHash = $("#csrf_token").val() || csrfHash;
  }

  function formatAddressLine(parts) {
    return parts
      .map((p) => (p == null ? "" : String(p)).trim())
      .filter((p) => p.length > 0);
  }

  function getAddressLineOne(a) {
    if (!a) return "";
    return formatAddressLine([a.address]).join("");
  }

  function getAddressLineTwo(a) {
    if (!a) return "";
    return formatAddressLine([a.city, a.state, a.pincode, a.country]).join(", ");
  }

  $("#documents").on("change", function () {
    var allowedExtensions = ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx"];
    var selectedFiles = this.files;
    for (var i = 0; i < selectedFiles.length; i++) {
      var file = selectedFiles[i];
      var extension = file.name.split(".").pop().toLowerCase();
      if (allowedExtensions.indexOf(extension) === -1) {
        Toast.fire({
          icon: "error",
          title: "Invalid file format. " + file.name + " !",
        });
        $("#documents").val("");
        return false;
      }
    }
  });

  function midtrans_setup(midtrans_transaction_token) {
    // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
    window.snap.pay(midtrans_transaction_token, {
      onSuccess: function (result) {
        /* You may add your own implementation here */
       
        place_order().done(function (result) {
          if (result.error == false) {
            setTimeout(function () {
              location.href = base_url + "payment/success";
            }, 3000);
          }
        });
      },
      onPending: function (result) {
        /* You may add your own implementation here */
        alert("wating your payment!");
        
      },
      onError: function (result) {
        /* You may add your own implementation here */
        alert("payment failed!");
        $("#place_order_btn").attr("disabled", false).html("Place Order");
        
      },
      onClose: function () {
        /* You may add your own implementation here */
        $("#place_order_btn").attr("disabled", false).html("Place Order");
        alert("you closed the popup without finishing the payment");
      },
    });
  }

  function razorpay_setup(
    key,
    amount,
    app_name,
    logo,
    razorpay_order_id,
    username,
    user_email,
    user_contact,
  ) {
    var options = {
      key: key, // Enter the Key ID generated from the Dashboard
      amount: amount * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
      currency: supported_locals,
      name: app_name,
      description: "Product Purchase",
      image: logo,
      order_id: razorpay_order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
      handler: function (response) {
        $("#razorpay_payment_id").val(response.razorpay_payment_id);
        $("#razorpay_signature").val(response.razorpay_signature);
        place_order().done(function (result) {
          if (result.data.error == false) {
            setTimeout(function () {
              location.href = base_url + "payment/success";
            }, 3000);
          }
        });
      },
      prefill: {
        name: username,
        email: user_email,
        contact: user_contact,
      },
      notes: {
        address: app_name + " Purchase",
      },
      theme: {
        color: "#3399cc",
      },
      escape: false,
      modal: {
        ondismiss: function () {
          $("#place_order_btn").attr("disabled", false).html("Place Order");
        },
      },
    };
    var rzp = new Razorpay(options);
    return rzp;
  }

  function paystack_setup(key, user_email, order_amount) {
    var handler = PaystackPop.setup({
      key: key,
      email: user_email,
      amount: parseInt(order_amount * 100),
      currency: supported_locals,
      callback: function (response) {
        $("#paystack_reference").val(response.reference);
        if (response.status == "success") {
          place_order().done(function (result) {
            if (result.error == false) {
              setTimeout(function () {
                location.href = base_url + "payment/success";
              }, 3000);
            }
          });
        } else {
          location.href = base_url + "payment/cancel";
        }
      },
      onClose: function () {
        $("#place_order_btn").attr("disabled", false).html("Place Order");
      },
    });
    return handler;
  }

  function stripe_setup(key) {
    // A reference to Stripe.js initialized with a fake API key.
    // Sign in to see examples pre-filled with your key.
    var stripe = Stripe(key);
    // Disable the button until we have Stripe set up on the page
    var elements = stripe.elements();
    var style = {
      base: {
        color: "#32325d",
        fontFamily: "Arial, sans-serif",
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#32325d",
        },
      },
      invalid: {
        fontFamily: "Arial, sans-serif",
        color: "#fa755a",
        iconColor: "#fa755a",
      },
    };

    var card = elements.create("card", {
      style: style,
    });
    card.mount("#stripe-card-element");

    card.on("change", function (event) {
      // Disable the Pay button if there are no card details in the Element
      document.querySelector("button").disabled = event.empty;
      document.querySelector("#card-error").textContent = event.error
        ? event.error.message
        : "";
    });
    return {
      stripe: stripe,
      card: card,
    };
  }

  function stripe_payment(stripe, card, clientSecret) {
    
    // Calls stripe.confirmCardPayment
    // If the card requires authentication Stripe shows a pop-up modal to
    // prompt the user to enter authentication details without leaving your page.
    stripe
      .confirmCardPayment(clientSecret, {
        payment_method: {
          card: card,
        },
      })
      .then(function (result) {
        if (result.error) {
          // Show error to your customer
          var errorMsg = document.querySelector("#card-error");
          errorMsg.textContent = result.error.message;
          setTimeout(function () {
            errorMsg.textContent = "";
          }, 4000);
          Toast.fire({
            icon: "error",
            title: result.error.message,
          });
          $("#place_order_btn").attr("disabled", false).html("Place Order");
        } else {
          // The payment succeeded!
          place_order().done(function (result) {
            if (result.error == false) {
              setTimeout(function () {
                location.href = base_url + "payment/success";
              }, 1000);
            }
          });
        }
      });
  }

  $(document).ready(function () {
    $('input[name="local_pickup"]:checked').trigger("change");
  });

  function flutterwave_payment() {
    var documents = $("#documents").val();
    var address_id = $("#address_id").val();
    if ($("#wallet_balance").is(":checked")) {
      var wallet_used = 1;
    } else {
      var wallet_used = 0;
    }
    var final_total = $("#final_total").text();
    final_total = final_total.replace(",", "");
    var promo_set = $("#promo_set").val();
    var promo_code = "";
    if (promo_set == 1) {
      promo_code = $("#promocode_input").val();
    }
    var logo = $("#logo").val();
    var public_key = $("#flutterwave_public_key").val();
    var currency_code = $("#flutterwave_currency").val();
    switch (currency_code) {
      case "KES":
        var country = "KE";
        break;
      case "GHS":
        var country = "GH";
        break;
      case "ZAR":
        var country = "ZA";
        break;
      case "TZS":
        var country = "TZ";
        break;

      default:
        var country = "NG";
        break;
    }
    $.post(
      base_url + "cart/pre-payment-setup",
      {
        [csrfName]: csrfHash,
        payment_method: "Flutterwave",
        wallet_used: wallet_used,
        address_id: address_id,
        promo_code: promo_code,
        documents: documents,
        // final_total: $("#amount").val(),
        final_total_with_charges: final_total,
      },
      function (data) {
        csrfName = data.csrfName;
        csrfHash = data.csrfHash;
        if (data.error == false) {
          var amount = data.final_amount;
          var phone_number = $("#user_contact").val();
          var email = $("#user_email").val();
          var name = $("#username").val();
          var title = $("#app_name").val();
          var d = new Date();
          var ms = d.getMilliseconds();
          var number = Math.floor(1000 + Math.random() * 9000);
          var tx_ref = title + "-" + ms + "-" + number;
          FlutterwaveCheckout({
            public_key: public_key,
            tx_ref: tx_ref,
            amount: amount,
            currency: currency_code,
            country: country,
            payment_options: "card,mobilemoney,ussd",
            customer: {
              email: email,
              phone_number: phone_number,
              name: name,
            },
            callback: function (data) {
              // specified callback function
              if (data.status == "successful") {
                $("#flutterwave_transaction_id").val(data.transaction_id);
                $("#flutterwave_transaction_ref").val(data.tx_ref);
                place_order().done(function (result) {
                  if (result.error == false) {
                    setTimeout(function () {
                      location.href = base_url + "payment/success";
                    }, 3000);
                  }
                });
              } else {
                location.href = base_url + "payment/cancel";
              }
            },
            customizations: {
              title: title,
              description: "Payment for product purchase",
              logo: logo,
            },
          });
        } else {
          Toast.fire({
            icon: "error",
            title: "Something went wrong!",
          });
        }
      },
      "json",
    );
  }

  function paytm_setup(
    txnToken,
    orderId,
    amount,
    app_name,
    logo,
    username,
    user_email,
    user_contact,
  ) {
    var config = {
      root: "",
      flow: "DEFAULT",
      merchant: {
        name: app_name,
        logo: logo,
        redirect: false,
      },
      style: {
        headerBackgroundColor: "#8dd8ff",
        headerColor: "#3f3f40",
      },
      data: {
        orderId: orderId,
        token: txnToken,
        tokenType: "TXN_TOKEN",
        amount: amount,
        userDetail: {
          mobileNumber: user_contact,
          name: username,
        },
      },
      handler: {
        notifyMerchant: function (eventName, data) {
          if (eventName == "SESSION_EXPIRED") {
            alert("Your session has expired!!");
            location.reload();
          }
          if (eventName == "APP_CLOSED") {
            $("#place_order_btn")
              .data("placing", false)
              .prop("disabled", false)
              .attr("disabled", false)
              .html("Place Order");
          }
        },
        transactionStatus: function (data) {
          window.Paytm.CheckoutJS.close();
          if (data.STATUS == "TXN_SUCCESS" || data.STATUS == "PENDING") {
            let myForm = document.getElementById("checkout_form");
            var formdata = new FormData(myForm);
            formdata.append(csrfName, csrfHash);
            formdata.append("promo_code", $("#promocode_input").val());
            var latitude =
              sessionStorage.getItem("latitude") === null
                ? ""
                : sessionStorage.getItem("latitude");
            var longitude =
              sessionStorage.getItem("longitude") === null
                ? ""
                : sessionStorage.getItem("longitude");
            formdata.append("latitude", latitude);
            formdata.append("longitude", longitude);
            $.ajax({
              type: "POST",
              data: formdata,
              url: base_url + "cart/place-order",
              dataType: "json",
              cache: false,
              processData: false,
              contentType: false,
              beforeSend: function () {
                $("#place_order_btn")
                  .data("placing", true)
                  .prop("disabled", true)
                  .attr("disabled", true)
                  .html("Please Wait...");
              },
              success: function (data) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                if (data.error == false) {
                  $("#place_order_btn")
                    .data("placing", true)
                    .prop("disabled", true)
                    .attr("disabled", true)
                    .html("Please Wait...");
                  Toast.fire({
                    icon: "success",
                    title: data.message,
                  });
                  setTimeout(function () {
                    location.href = base_url + "payment/success";
                  }, 3000);
                } else {
                  $("#place_order_btn")
                    .data("placing", false)
                    .prop("disabled", false)
                    .attr("disabled", false)
                    .html("Place Order");
                  Toast.fire({
                    icon: "error",
                    title: data.message,
                  });
                }
              },
            });
          } else {
            Toast.fire({
              icon: "error",
              title: "Something went wrong please try again!",
            });
          }
        },
      },
    };

    if (window.Paytm && window.Paytm.CheckoutJS) {
      // initialze configuration using init method
      window.Paytm.CheckoutJS.init(config)
        .then(function onSuccess() {
          // after successfully update configuration invoke checkoutjs
          window.Paytm.CheckoutJS.invoke();
        })
        .catch(function onError(error) {
          console.log("Error => ", error);
        });
    }
  }
  $("input[name='payment_method']").on("change", function (e) {
    e.preventDefault();

    try {
      function toNumber(v) {
        return parseFloat(String(v).replace(/,/g, "")) || 0;
      }

      // read payment method (guard if nothing selected)
      let payment_method =
        $('input[name="payment_method"]:checked').val() || "";
      let pmUpper = payment_method.toString().toUpperCase();

      let sub_total = toNumber($("#sub_total").val());
      let promocode_amount = $("#promocode_amount").text().trim();

      // remove everything except digits, minus, dot
      promocode_amount = promocode_amount.replace(/[^\d.-]/g, "");

      if (promocode_amount === "" || isNaN(promocode_amount)) {
        promocode_amount = 0;
      } else {
        promocode_amount = Math.abs(parseFloat(promocode_amount));
      }

      let wallet_used = toNumber($(".wallet_used").first().text());
      let bulk_discount = toNumber($("#bulk_discount").val());

      let delivery_charge_with_cod = toNumber(
        $(".delivery_charge_with_cod").val(),
      );
      let delivery_charge_without_cod = toNumber(
        $(".delivery_charge_without_cod").val(),
      );

      let product_type = $("#product_type").val() || "";
      let local_pick_up = $("input[name='local_pickup']:checked").val() || "";

      // Primary selectors for rows (use ID if present, otherwise fallback to closest tr)
      let rowCOD = $("#row_delivery_with_cod");
      if (rowCOD.length === 0) {
        rowCOD = $(".delivery_charge_with_cod").closest(".charges-section");
      }

      let rowNONCOD = $("#row_delivery_without_cod");
      if (rowNONCOD.length === 0) {
        rowNONCOD = $(".delivery_charge_without_cod").closest(
          ".charges-section",
        );
      }

      // safety: if still not found, create empty jQuery object to avoid errors
      if (rowCOD.length === 0) rowCOD = $();
      if (rowNONCOD.length === 0) rowNONCOD = $();

      // toggle rows based on payment method
      if (pmUpper === "COD") {
        rowCOD.show();
        rowNONCOD.hide();
      } else {
        rowCOD.hide();
        rowNONCOD.show();
      }

      // determine delivery charge
      let delivery_charge = 0;
      if (product_type !== "digital_product" && local_pick_up != "1") {
        delivery_charge =
          pmUpper === "COD"
            ? delivery_charge_with_cod
            : delivery_charge_without_cod;
      }

      // compute custom charges total (based on product type + delivery type)
      let custom_charges_total = getApplicableCustomChargesTotal();
      updateCustomChargesVisibility();

      // final total
      let final_total =
        sub_total +
        delivery_charge +
        custom_charges_total -
        bulk_discount -
        wallet_used -
        parseFloat(promocode_amount);

      // update UI
      $("#final_total").html(
        final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }),
      );
      $("#amount").val(final_total);
    
      if (payment_method == "Stripe") {
        stripe1 = stripe_setup($("#stripe_key_id").val());
        $("#stripe_div").slideDown();
      } else {
        $("#stripe_div").slideUp();
      }

      if (payment_method == "my_fatoorah") {
        $("#my_fatoorah_div").slideDown();
      } else {
        $("#my_fatoorah_div").slideUp();
      }

      if (payment_method == "Direct Bank Transfer") {
        $("#bank_transfer_slide").slideDown();
        $("#account_data").removeClass("d-none");
      } else {
        $("#bank_transfer_slide").slideUp();
        $("#account_data").addClass("d-none");
      }
      
    } catch (err) {
      console.error("Error in payment_method change handler:", err);
    }
  });

  let custom_total = 0;
  if (shouldApplyCustomCharges()) {
    let type =
      $("input[name='local_pickup']:checked").val() == "1"
        ? "pickup"
        : "doorstep";

    let charges = JSON.parse($("#custom_charges_json").val()) || [];

    charges.forEach((c) => {
      if (
        (type === "doorstep" && c.apply_doorstep == 1) ||
        (type === "pickup" && c.apply_pickup == 1)
      ) {
        custom_total += parseFloat(c.amount) || 0;
      }
    });
  }

  // Function to compute final total based on delivery type
  function computeFinalTotal() {
    try {
      function toNumber(v) {
        return parseFloat(String(v).replace(/,/g, "")) || 0;
      }

      let sub_total = toNumber($("#sub_total").val());
      let promocode_amount = $("#promocode_amount").text().trim();

      // remove everything except digits, minus, dot
      promocode_amount = promocode_amount.replace(/[^\d.-]/g, "");

      if (promocode_amount === "" || isNaN(promocode_amount)) {
        promocode_amount = 0;
      } else {
        promocode_amount = Math.abs(parseFloat(promocode_amount));
      }
      let wallet_used = toNumber($(".wallet_used").first().text());
      let bulk_discount = toNumber($("#bulk_discount").val());

      let delivery_charge_with_cod = toNumber(
        $(".delivery_charge_with_cod").val(),
      );
      let delivery_charge_without_cod = toNumber(
        $(".delivery_charge_without_cod").val(),
      );

      let product_type = $("#product_type").val() || "";
      let local_pick_up = $("input[name='local_pickup']:checked").val() || "0";
      let payment_method =
        $("input[name='payment_method']:checked").val() || "";
      let pmUpper = payment_method.toString().toUpperCase();

      // Determine delivery charge
      let delivery_charge = 0;
      if (product_type !== "digital_product" && local_pick_up != "1") {
        delivery_charge =
          pmUpper === "COD"
            ? delivery_charge_with_cod
            : delivery_charge_without_cod;
      }

      // Compute custom charges total
      let custom_charges_total = getApplicableCustomChargesTotal();
      updateCustomChargesVisibility();

      // Final total
      let final_total =
        sub_total +
        delivery_charge +
        custom_charges_total -
        wallet_used -
        promocode_amount -
        bulk_discount;

      // Update UI
      $("#final_total").html(
        final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }),
      );
      $("#amount").val(final_total);

    } catch (err) {
      console.error("Error in computeFinalTotal:", err);
    }
  }

  

  $(document).on("change", 'input[name="local_pickup"]', function () {
    // 0 = door, 1 = pickup
    let deliveryType = $(this).val();
    
    updateCustomChargesVisibility();
    computeFinalTotal();
  });
  // Function to update the address modal with data
  function updateAddressModal(preselectedAddressId = null) {
    return $.ajax({
      type: "POST",
      data: {
        [csrfName]: csrfHash,
      },
      url: base_url + "my-account/get-address/",
      dataType: "json",
      success: function (data) {
        csrfName = data.csrfName;
        csrfHash = data.csrfHash;
        addresses = [];
        var html = "";
        if (data.error == false && data.data.length > 0) {
          var address_id = $("#address_id").val();
          var found = 0;
          $.each(data.data, function (i, e) {
            var checked = "";
            if (
              preselectedAddressId !== null &&
              String(e.id) === String(preselectedAddressId)
            ) {
              found = 1;
              checked = "checked";
            } else if (e.id == address_id) {
              found = 1;
              checked = "checked";
            } else if (e.is_default == 1 && found == 0) {
              checked = "checked";
            }
            addresses.push(e);
            const addressLineOne = getAddressLineOne(e);
            const addressLineTwo = getAddressLineTwo(e);
            html +=
              '<div class="d-flex gap-3 align-items-center form-check-box border-bottom ">' +
              '<input class="form-check-input" ' +
              checked +
              ' type="radio" name="select-address" data-index=' +
              i +
              ' id="select-address-' +
              e.id +
              '"' +
              '><label class="form-check-label" for="select-address-' +
              e.id +
              '"> <div class="addresses">' +
              '<div class="d-flex gap-1 fw-semibold"><ion-icon name="location-outline"></ion-icon><p>' +
              e.name +
              " - " +
              e.type +
              "</p></div>" +
              (addressLineOne ? "<p>" + addressLineOne + "</p>" : "") +
              (addressLineTwo ? "<p>" + addressLineTwo + "</p>" : "") +
              "<p>" +
              e.mobile +
              "</p></div></label></div>";
          });

          $("#address-list").html(html);
        } else {
          $("#address-list").html(`
    <div class="col-12 text-center py-4 text-muted">
        <i class="fa-solid fa-location-dot fa-2x mb-2"></i>
        <h5 class="mt-2">No address found</h5>
        <p class="mb-0">You haven’t added any shipping address yet.</p>
    </div>
`);
        }
      },
    });
  }

  $(".address-modal").on("show.bs.modal", function (event) {
    updateAddressModal(newlyCreatedAddressId);
  });

  $(document).on("click", "#open-create-address-modal", function () {
    $("#checkout-create-address-form")[0].reset();
    $("#checkout_country").val("United States");
    $("#checkout_city_id").val("");
    $("#checkout_type_home").prop("checked", true);
    const userName = ($("#username").val() || "").trim();
    const selectedMobile = ($("#address-mobile").text() || "").trim();
    const userMobile = ($("#user_contact").val() || "").trim();
    const isValidProfileMobile =
      userMobile !== "" &&
      userMobile.toLowerCase() !== "null" &&
      userMobile !== "0";
    $("#checkout_address_name").val(userName);
    $("#checkout_mobile_number").val(
      isValidProfileMobile ? userMobile : selectedMobile,
    );
    $("#checkout-create-address-modal").modal("show");
  });
  $(".estimate_date_section").hide();

  function selectAddressByIndex(index) {
    if (index === undefined || index === null || addresses[index] === undefined) {
      return;
    }
    var address = addresses[index];
    var total = $("#temp_total").val();
    var sub_total = $("#sub_total").val();
    sub_total = sub_total.replace(",", "");
    var promocode_amount = $("#promocode_amount").text();
    if (promocode_amount == "") {
      promocode_amount = 0;
    } else {
      promocode_amount = promocode_amount.replace(",", "");
    }
    $("#address-name-type").html(address.name + " - " + address.type);
    $("#address-full").html(getAddressLineOne(address));
    $("#address-country").html(getAddressLineTwo(address));
    $("#address-mobile").html(address.mobile);
    $("#address_id").val(address.id);
    $("#mobile").val(address.mobile);
    var address_id = $("#address_id").val();

    $.ajax({
      type: "POST",
      data: {
        [csrfName]: csrfHash,
        address_id: address_id,
        total: total,
      },
      url: base_url + "cart/get-delivery-charge",
      dataType: "json",
      beforeSend: function () {
        $("#checkout_form > .row").show({
          message:
            "<h2>Please wait... Checking serviceability in your area</h2>",
          css: {
            border: "none",
            padding: "16px",
          },
        });
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        var is_time_slots_enabled = 0;
        var className = result.error == true ? "danger" : "success";

        $("#checkout_form > .row").show();
        $("#deliverable_status").html(
          "<p class='text-" + className + "'>" + result.message + "</p>",
        );
        result.availability_data.forEach((product) => {
          if (product.is_deliverable == false) {
            $("#p_" + product.product_id).html(
              "<b class='text-danger'> " +
              (product.message ?? "Not deliverable") +
              "</b>",
            );
          } else {
            $("#p_" + product.product_id).html("");
          }
          if (product.delivery_by == "standard_shipping") {
            is_time_slots_enabled = 0;
            $("#is_time_slots_enabled").val(is_time_slots_enabled);
          }
        });

        $(".shipping_method").html(result.shipping_method);
        $(".delivery-charge").html(result.delivery_charge_with_cod);
        $(".delivery_charge_with_cod").html(result.delivery_charge_with_cod);
        $(".delivery_charge_with_cod").val(result.delivery_charge_with_cod);
        $(".delivery_charge_without_cod").html(
          result.delivery_charge_without_cod,
        );
        $(".delivery_charge_without_cod").val(
          result.delivery_charge_without_cod,
        );
        if (result.estimate_date != "" && result.estimate_date != null) {
          $(".estimate_date").html(result.estimate_date);
          $(".estimate_date_section").show();
        }
        let shipping_method = result.shipping_method;
        let delivery_charge = 0;
        result.availability_data.forEach((product) => {
          if (product.delivery_by == "standard_shipping") {
            $(".date-time-label").addClass("d-none");
            $(".date-time-picker").addClass("d-none");
            $(".time-slot").addClass("d-none");
          } else {
            $(".date-time-label").removeClass("d-none");
            $(".date-time-picker").removeClass("d-none");
            $(".time-slot").removeClass("d-none");
          }
        });
        let payment_method = $("input[name='payment_method']:checked").val();
        var local_pick_up = $("input[name='local_pickup']:checked").val();

        if (payment_method == "COD") {
          delivery_charge = result.delivery_charge_with_cod.replace(",", "");
        } else {
          delivery_charge = result.delivery_charge_without_cod.replace(",", "");
        }
        if (local_pick_up == "1") {
          delivery_charge = 0;
        }
        // let platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;
        let custom_charges_total = 0;

        if ($("#custom_charges_json").length > 0) {
          let custom_charges = JSON.parse($("#custom_charges_json").val());

          let type =
            $("input[name='local_pickup']:checked").val() == "1"
              ? "pickup"
              : "doorstep";

          custom_charges.forEach((c) => {
            if (
              (type === "doorstep" && c.apply_doorstep == 1) ||
              (type === "pickup" && c.apply_pickup == 1)
            ) {
              custom_charges_total += parseFloat(c.amount) || 0;
            }
          });
        }
       
        let bulk_discount = $("#bulk_discount").val() || "0";
        
        let final_total =
          parseFloat(sub_total) +
          parseFloat(delivery_charge) +
          parseFloat(custom_charges_total);
        // parseFloat(platform_fees);
      
        let wallet_used = $(".wallet_used").text();
        if (wallet_used == "") {
          wallet_used = 0;
        } else {
          wallet_used = wallet_used.replace(",", "");
        }
        final_total =
          parseFloat(sub_total) +
          parseFloat(delivery_charge) +
          // parseFloat(platform_fees) +
          parseFloat(custom_charges_total) -
          parseFloat(wallet_used) -
          parseFloat(promocode_amount) -
          parseFloat(bulk_discount);
        final_total = final_total.toLocaleString(undefined, {
          maximumFractionDigits: 2,
        });

        $("#final_total").html(final_total);
        final_total = final_total.replace(",", "");
        $("#amount").val(final_total);
        if (final_total != 0) {
          $("#cod").prop("required", true);
          $("#paypal").prop("required", true);
          $("#razorpay").prop("required", true);
          $("#paystack").prop("required", true);
          $("#midtrans").prop("required", false);
          $("#payumoney").prop("required", true);
          $("#flutterwave").prop("required", true);
          $("#stripe").prop("required", true);
          $("#paytm").prop("required", true);
          $("#bank_transfer").prop("required", true);
          $(".payment-methods").show();
        }
      },
    });
  }

  $(".address-modal").on("click", ".submit", function (event) {
    event.preventDefault();
    var index = $('input[name="select-address"]:checked').data("index");
    selectAddressByIndex(index);
  });

  $("#checkout-create-address-form").on("submit", function (event) {
    event.preventDefault();

    if (!$("#checkout_city").val() || !$("#checkout_city").val().trim()) {
      Toast.fire({
        icon: "error",
        title: "Please enter a city.",
      });
      $("#checkout_city").focus();
      return false;
    }

    var zipcode = ($("#checkout_pincode").val() || "").trim();
    if (!/^\d{5}(-\d{4})?$/.test(zipcode)) {
      Toast.fire({
        icon: "error",
        title: "Enter a valid US ZIP Code (e.g. 10001 or 10001-1234).",
      });
      $("#checkout_pincode").focus();
      return false;
    }

    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
      type: "POST",
      url: base_url + "my-account/add-address",
      data: formdata,
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      beforeSend: function () {
        $("#checkout_save_address_btn").attr("disabled", true).text("Please Wait...");
      },
      success: function (result) {
        $("#checkout_save_address_btn").attr("disabled", false).text("Save");
        csrfName = result.csrfName || csrfName;
        csrfHash = result.csrfHash || csrfHash;
        $("#csrf_token").attr("name", csrfName).val(csrfHash);

        if (result.error == false) {
          let createdAddressId = null;
          if (result.data && !Array.isArray(result.data) && result.data.id) {
            createdAddressId = result.data.id;
          } else if (
            Array.isArray(result.data) &&
            result.data.length > 0 &&
            result.data[0].id
          ) {
            createdAddressId = result.data[0].id;
          }

          newlyCreatedAddressId = createdAddressId;
          $("#checkout-create-address-modal").modal("hide");
          updateAddressModal(createdAddressId).done(function () {
            var selectedIndex = null;
            if (createdAddressId !== null) {
              selectedIndex = addresses.findIndex(
                (addr) => String(addr.id) === String(createdAddressId),
              );
            }
            if (selectedIndex === null || selectedIndex < 0) {
              selectedIndex = addresses.length > 0 ? addresses.length - 1 : null;
            }
            selectAddressByIndex(selectedIndex);
            $("#address-modal").modal("hide");
          });

          Toast.fire({
            icon: "success",
            title: result.message || "Address Added Successfully",
          });
        } else {
          Toast.fire({
            icon: "error",
            title: result.message || "Unable to add address.",
          });
        }
      },
      error: function () {
        $("#checkout_save_address_btn").attr("disabled", false).text("Save");
        Toast.fire({
          icon: "error",
          title: "Unable to add address.",
        });
      },
    });
  });

  function promocodes() {
    $.ajax({
      type: "GET",
      url: base_url + "my-account/get_promo_codes/",
      dataType: "json",
      success: function (data) {
        csrfName = data.csrfName;
        csrfHash = data.csrfHash;
        var html = "";
        if (data.promo_codes.length != 0) {
          $.each(data.promo_codes, function (i, e) {
            html +=
              '<a class="btn col-12" id="redeem_promocode" data-value="' +
              e.promo_code +
              '" href="#" checked>' +
              '<div class="row promocode-section">' +
              '<div class="col-3">' +
              '<div class="promocode-image-box"><img src="' +
              e.image +
              '" alt="" srcset="">' +
              '</div></div><div class="col-9">' +
              '<p class="promo-title m-0">' +
              e.promo_code +
              '</p><p class="promo-disc m-0">' +
              e.message +
              "</p></div></div></a>";
          });
        } else {
          html +=
            '<div class="col-12 text-dark d-flex justify-content-center">Opps...No Offers Avilable</div>';
        }
        $("#promocode-list").html(html);
      },
    });
  }
  $("#promo_code_modal").on("show.bs.modal", function (event) {
    promocodes();
  });

  $(document).ready(function () {
    $.ajax({
      type: "GET",
      url: base_url + "home/get_csrf_token",
      success: function (response) {
        csrfHash = response;
        var address_id = $("#address_id").val();
        var sub_total = parseFloat($("#sub_total").val());
        var total = parseFloat($("#temp_total").val());

        $.ajax({
          type: "POST",
          data: {
            address_id: address_id,
            total: total,
            eshop_security_token: csrfHash,
          },
          url: base_url + "cart/get-delivery-charge",
          dataType: "json",
          success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;

            var className = result.error ? "danger" : "success";
            $("#deliverable_status").html(
              "<b class='text-" + className + "'>" + result.message + "</b>",
            );

            var is_time_slots_enabled = 0;

            if (result.availability_data) {
              result.availability_data.forEach((product) => {
                if (!product.is_deliverable) {
                  $("#p_" + product.product_id).html(
                    "<b class='text-danger'>" +
                    (product.message ?? "Not deliverable") +
                    "</b>",
                  );
                } else {
                  $("#p_" + product.product_id).html("");
                }

                if (product.delivery_by === "standard_shipping") {
                  is_time_slots_enabled = 0;
                  $("#is_time_slots_enabled").val(is_time_slots_enabled);
                }
              });
            }

            $(".shipping_method").html(result.shipping_method);
            $(".delivery-charge").html(result.delivery_charge);
            $(".delivery_charge_with_cod").html(
              result.delivery_charge_with_cod,
            );
            $(".delivery_charge_with_cod").val(result.delivery_charge_with_cod);
            $(".delivery_charge_without_cod").html(
              result.delivery_charge_without_cod,
            );
            $(".delivery_charge_without_cod").val(
              result.delivery_charge_without_cod,
            );
            $("input[name='payment_method']:checked").trigger("change");

            // Estimate date display
            if (result.estimate_date) {
              $(".estimate_date").html(result.estimate_date);
              $(".estimate_date_section").show();
            }

            // Show/Hide time slots
            if (result.availability_data) {
              result.availability_data.forEach((product) => {
                if (product.delivery_by === "standard_shipping") {
                  $(".date-time-label, .date-time-picker, .time-slot").addClass(
                    "d-none",
                  );
                } else {
                  $(
                    ".date-time-label, .date-time-picker, .time-slot",
                  ).removeClass("d-none");
                }
              });
            }
            // -------------------------------
            //   CUSTOM CHARGES TOTAL ONLY
            // -------------------------------
            // let custom_charges_total = 0;

            // if (
            //   shouldApplyCustomCharges() &&
            //   $("#custom_charges_json").length > 0
            // ) {
            //   let custom_charges = JSON.parse($("#custom_charges_json").val());
            //   custom_charges.forEach((c) => {
            //     custom_charges_total += parseFloat(c.amount);
            //   });
            // }
            // updateCustomChargesVisibility();

            let bulk_discount = parseFloat($("#bulk_discount").val()) || 0;

            let delivery_charge = parseFloat(result.delivery_charge_with_cod);
            let custom_charges_total = getApplicableCustomChargesTotal();
            updateCustomChargesVisibility();
            let final_total =
              sub_total +
              delivery_charge +
              custom_charges_total -
              // wallet_balance -
              bulk_discount;
          
            // Update hidden amount
            $("#amount").val(final_total);

            // Display formatted total
            $("#final_total").html(
              final_total.toLocaleString(undefined, {
                maximumFractionDigits: 2,
              }),
            );
          },
        });
      },
    });

    // -------------------------------
    // DIGITAL PRODUCT PAGE LOGIC
    // (Removed platform fees completely)
    // -------------------------------

    var product_type = $("#product_type").val();

    if (product_type === "digital_product") {
      var sub_total = parseFloat($("#sub_total").val());
      var bulk_discount = parseFloat($("#bulk_discount").val()) || 0;

      // Custom charges only
      let custom_charges_total = 0;
      if (shouldApplyCustomCharges() && $("#custom_charges_json").length > 0) {
        let custom_charges = JSON.parse($("#custom_charges_json").val());
        custom_charges.forEach((c) => {
          custom_charges_total += parseFloat(c.amount);
        });
      }
      updateCustomChargesVisibility();

      var final_total = sub_total + custom_charges_total - bulk_discount;

      $("#amount").val(final_total);

      $("#final_total").html(
        final_total.toLocaleString(undefined, { maximumFractionDigits: 2 }),
      );
    }
  });

  var global_final_total = 0;
  var global_delivery_charge = 0;
  $(document).on("change", 'input[name="local_pickup"]', function () {
 
    // Update button styling
    $('input[name="local_pickup"]').each(function () {
      if ($(this).is(":checked")) {
        $(this).next("label").addClass("active");
      } else {
        $(this).next("label").removeClass("active");
      }
    });

    updateCustomChargesVisibility();
    computeFinalTotal();
  });

  // $('#door_step').on('change', function (e) {
  //     e.preventDefault()

  //     $('.address').show()
  //     $('.charges-section').show()
  //     $('.delivery_charge_with_cod').show()
  //     $('.delivery_charge_without_cod').show()
  //     $('.estimate_date').show()
  //     $('.deliverycharge_currency').show()
  //     $('.delivery-charge').text(global_delivery_charge)
  //     $('.deliverable_status').show()

  //     // Update custom charges visibility and recalculate total
  //     updateCustomChargesVisibility();
  //     computeFinalTotal();
  // })
  $("#checkout_form").on("submit", function (event) {
    event.preventDefault();
    var $placeOrderBtn = $("#place_order_btn");
    if ($placeOrderBtn.data("placing")) {
      return false;
    }
    var btn_html = $placeOrderBtn.html();
    $placeOrderBtn
      .data("placing", true)
      .prop("disabled", true)
      .attr("disabled", true)
      .html("Please Wait...");

    setTimeout(function () {
    var type = $("#product_type").val();
    var fatoorah_order_id = "";
    var local_pick_up = $("input[name='local_pickup']:checked").val();
    var address_id = $("#address_id").val();
    var documents = $("#documents").val();
    if ($("#wallet_balance").is(":checked")) {
      var wallet_used = 1;
    } else {
      var wallet_used = 0;
    }

    var promo_set = $("#promo_set").val();
    var promo_code = "";
    if (promo_set == 1) {
      promo_code = $("#promocode_input").val();
    }
    var final_total = $("#final_total").text();
    final_total = final_total.replace(",", "");
    var address_id = $("#address_id").val();
    if (local_pick_up == 0 && type != "digital_product") {
      if (address_id == null || address_id == undefined || address_id == "") {
        Toast.fire({
          icon: "error",
          title: "Please add/choose address.",
        });
        $placeOrderBtn
          .data("placing", false)
          .prop("disabled", false)
          .attr("disabled", false)
          .html(btn_html);
        return false;
      }
    }
    if (documents === "") {
      $placeOrderBtn
        .data("placing", false)
        .prop("disabled", false)
        .attr("disabled", false)
        .html(btn_html);
      return Toast.fire({
        icon: "error",
        title: "Please select an Document.",
      });
    }
    var payment_methods = $("input[name='payment_method']:checked").val();
    var delivery_type = local_pick_up == 1 ? "pickup_from_store" : "door_step";
    if (payment_methods == "Stripe") {
      $.post(
        base_url + "cart/pre-payment-setup",
        {
          [csrfName]: csrfHash,
          payment_method: "Stripe",
          wallet_used: wallet_used,
          address_id: address_id,
          promo_code: promo_code,
          documents: documents,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function processPayment(data) {
          $("#stripe_client_secret").val(data.client_secret);
          $("#stripe_payment_id").val(data.id);

          var stripe_client_secret = data.client_secret;
          stripe_payment(stripe1.stripe, stripe1.card, stripe_client_secret);
        },
        "json",
      );
    } else if (payment_methods == "Paystack") {
      var key = $("#paystack_key_id").val();
      var user_email = $("#user_email").val();
      $.post(
        base_url + "cart/pre-payment-setup",
        {
          [csrfName]: csrfHash,
          payment_method: "Paystack",
          wallet_used: wallet_used,
          address_id: address_id,
          promo_code: promo_code,
          documents: documents,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function (data) {
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
          if (data.error == false) {
            var handler = paystack_setup(key, user_email, data.final_amount);
            handler.openIframe();
          } else {
            Toast.fire({
              icon: "error",
              title: "Something went wrong!",
            });
          }
        },
        "json",
      );
    } else if (payment_methods == "Razorpay") {
      $.post(
        base_url + "cart/pre-payment-setup",
        {
          [csrfName]: csrfHash,
          payment_method: "Razorpay",
          wallet_used: wallet_used,
          address_id: address_id,
          promo_code: promo_code,
          documents: documents,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function (data) {
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
          if (data.error == false) {
            $("#razorpay_order_id").val(data.order_id);
            var key = $("#razorpay_key_id").val();
            var app_name = $("#app_name").val();
            var logo = $("#logo").val();
            var razorpay_order_id = $("#razorpay_order_id").val();
            var username = $("#username").val();
            var user_email = $("#user_email").val();
            var user_contact = $("#user_contact").val();
            var rzp1 = razorpay_setup(
              key,
              final_total,
              app_name,
              logo,
              razorpay_order_id,
              username,
              user_email,
              user_contact,
            );
            rzp1.open();
            rzp1.on("payment.failed", function (response) {
              location.href = base_url + "payment/cancel";
            });
          } else {
            Toast.fire({
              icon: "error",
              title: data.message,
            });
          }
        },
        "json",
      );
    } else if (payment_methods == "Midtrans") {
      $.post(
        base_url + "cart/pre-payment-setup",
        {
          [csrfName]: csrfHash,
          payment_method: "Midtrans",
          wallet_used: wallet_used,
          address_id: address_id,
          promo_code: promo_code,
          documents: documents,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function (data) {
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
          if (data.error == false) {
            $("#midtrans_transaction_token").val(data.token);
            $("#midtrans_order_id").val(data.order_id);
            var key = $("#razorpay_key_id").val();
            var app_name = $("#app_name").val();
            var logo = $("#logo").val();
            var midtrans_transaction_token = data.token;
            var username = $("#username").val();
            var user_email = $("#user_email").val();
            var user_contact = $("#user_contact").val();
            var midtrans_payment = midtrans_setup(midtrans_transaction_token);
          } else {
            Toast.fire({
              icon: "error",
              title: data.message,
            });
          }
        },
        "json",
      );
    } else if (payment_methods == "instamojo") {
      $.post(
        base_url + "cart/pre-payment-setup",
        {
          [csrfName]: csrfHash,
          payment_method: "instamojo",
          wallet_used: wallet_used,
          address_id: address_id,
          promo_code: promo_code,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function (data) {
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
          if (data.error == false) {
            $("#instamojo_order_id").val(data.order_id);
            var instamojo_payment = instamojo_setup(data.redirect_url);
            $("#place_order_btn")
              .data("placing", false)
              .prop("disabled", false)
              .attr("disabled", false)
              .html("Place Order");
          } else {
            Toast.fire({
              icon: "error",
              title: data.message,
            });
            $("#place_order_btn")
              .data("placing", false)
              .prop("disabled", false)
              .attr("disabled", false)
              .html("Place Order");
          }
        },
        "json",
      );
    } else if (payment_methods == "my_fatoorah") {
      place_order().done(function (result) {
        $("#my_fatoorah_order_id").val(result.data.order_id);
        fatoorah_order_id = $("#my_fatoorah_order_id").val();
        $("#csrf_token").val(csrfHash);
        $.post(
          base_url + "cart/pre-payment-setup",
          {
            [csrfName]: csrfHash,
            payment_method: "my_fatoorah",
            wallet_used: wallet_used,
            address_id: address_id,
            my_fatoorah_order_id: fatoorah_order_id,
            promo_code: promo_code,
            documents: documents,
            delivery_type: delivery_type,
            final_total_with_charges: final_total,
          },

          function (data) {
            csrfName = data.csrfName;
            csrfHash = data.csrfHash;
            if (data.error == false) {
              $("#my_fatoorah_order_id").val(data.order_id);
              fatoorah_url = data.PaymentURL;
              var my_fatoorah_payment = my_fatoorah_setup();
            } else {
              Toast.fire({
                icon: "error",
                title: data.message,
              });
            }
          },
          "json",
        );
      });
    } else if (payment_methods == "Paypal") {
      place_order().done(function (result) {
        $("#paypal_order_id").val(result.data.order_id);
        $("#csrf_token").val(csrfHash);
        $("#paypal_form").submit();
      });
    } else if (payment_methods == "Paytm") {
      var amount = $("#amount").val();
      var user_id = $("#user_id").val();
      var address_id = $("#address_id").val();
      if ($("#wallet_balance").is(":checked")) {
        var wallet_used = 1;
      } else {
        var wallet_used = 0;
      }

      var promo_set = $("#promo_set").val();
      var promo_code = "";
      if (promo_set == 1) {
        promo_code = $("#promocode_input").val();
      }
      $.post(
        base_url + "payment/initiate-paytm-transaction",
        {
          [csrfName]: csrfHash,
          amount: amount,
          user_id: user_id,
          address_id: address_id,
          wallet_used: wallet_used,
          promo_code: promo_code,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function (data) {
          if (data.error) {
            Toast.fire({
              icon: "error",
              title:
                data.message || "Something went wrong please try again later.",
            });
            return false;
          }

          if (
            typeof data.data.body.txnToken != "undefined" &&
            data.data.body.txnToken !== null
          ) {
            $("#paytm_transaction_token").val(data.data.body.txnToken);
            $("#paytm_order_id").val(data.data.order_id);
            var txn_token = $("#paytm_transaction_token").val();
            var order_id = $("#paytm_order_id").val();
            var app_name = $("#app_name").val();
            var logo = $("#logo").val();
            var username = $("#username").val();
            var user_email = $("#user_email").val();
            var user_contact = $("#user_contact").val();
            paytm_setup(
              txn_token,
              order_id,
              data.final_amount,
              app_name,
              logo,
              username,
              user_email,
              user_contact,
            );
          } else {
            Toast.fire({
              icon: "error",
              title: "Something went wrong please try again later.",
            });
          }
        },
        "json",
      );
    } else if (payment_methods == "phonepe") {
      var amount = $("#amount").val();
      var user_id = $("#user_id").val();
      var address_id = $("#address_id").val();
      if ($("#wallet_balance").is(":checked")) {
        var wallet_used = 1;
      } else {
        var wallet_used = 0;
      }

      var promo_set = $("#promo_set").val();
      var promo_code = "";
      if (promo_set == 1) {
        promo_code = $("#promocode_input").val();
      }
      $.post(
        base_url + "payment/phonepe",
        {
          [csrfName]: csrfHash,
          amount: amount,
          user_id: user_id,
          address_id: address_id,
          wallet_used: wallet_used,
          promo_code: promo_code,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function (data) {
          var url = data["url"] != "" ? data["url"] : "";
          var message = data["data"]["message"] ? data["data"]["message"] : "";
          $("#phonepe_transaction_id").val(
            data["transaction_id"] ? data["transaction_id"] : "",
          );

          if (url != "") {
            place_order().done(function (result) {
              if (result.data.error == false) {
                window.location.replace(url);
              } else {
                Toast.fire({
                  icon: "error",
                  title: message,
                });
              }
            });
          } else {
            Toast.fire({
              icon: "error",
              title: data.message,
            });
          }
        },
        "json",
      );
    } else if (payment_methods == "Flutterwave") {
      flutterwave_payment();
    } else if (
      payment_methods == "COD" ||
      payment_methods == "Direct Bank Transfer"
    ) {
      place_order().done(function (result) {
        if (result.data.error == false) {
          location.href = base_url + "payment/success";
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
      });
    } else if (
      (wallet_used == 1 && final_total == "0") ||
      final_total == "0.00"
    ) {
      place_order().done(function (result) {
        if (result.data.error == false) {
          setTimeout(function () {
            location.href = base_url + "payment/success";
          }, 3000);
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
      });
    }
    }, 0);
  });

  function my_fatoorah_setup() {
    window.location.replace(fatoorah_url);
  }
  var instamojo_payment_method = $("#instamojo_payment_method").val();

  function instamojo_setup(instamojo_redirect_url) {
    
    Instamojo.open(instamojo_redirect_url);
  }

  if (instamojo_payment_method == "1" || instamojo_payment_method == 1) {
    Instamojo.configure({
      handlers: {
        onSuccess: onPaymentSuccessHandler,
        onFailure: onPaymentFailureHandler,
      },
    });
  }

  function onPaymentSuccessHandler(response) {

    $("#instamojo_payment_id").val(response.paymentId);
    if (response.status == "success") {
      place_order().done(function (result) {
        if (result.error == false) {
          setTimeout(function () {
            location.href = base_url + "payment/success";
          }, 3000);
        }
      });
    } else {
      location.href = base_url + "payment/cancel";
    }
  }

  function onPaymentFailureHandler(response) {
    alert("Payment Failure");
    if (response.status == "failure") {
      location.href = base_url + "payment/cancel";
    }
  }

  function place_order() {
    let myForm = document.getElementById("checkout_form");
    var final_total_with_charges = $("#amount").val() ?? "";
    var custom_charges_total = getApplicableCustomChargesTotal();
    var custom_charges_list = getApplicableCustomCharges();
   
    var formdata = new FormData(myForm);
    formdata.append("custom_charges_total", custom_charges_total);
    formdata.append(
      "custom_charges_list",
      JSON.stringify(custom_charges_list.list),
    );
    formdata.append(csrfName, csrfHash);
    formdata.append("promo_code", $("#promocode_input").val());
    formdata.append("final_total_with_charges", final_total_with_charges);
    var latitude =
      sessionStorage.getItem("latitude") === null
        ? ""
        : sessionStorage.getItem("latitude");
    var longitude =
      sessionStorage.getItem("longitude") === null
        ? ""
        : sessionStorage.getItem("longitude");
    formdata.append("latitude", latitude);
    formdata.append("longitude", longitude);
    return $.ajax({
      type: "POST",
      data: formdata,
      url: base_url + "cart/place-order",
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      beforeSend: function () {
        $("#place_order_btn")
          .data("placing", true)
          .prop("disabled", true)
          .attr("disabled", true)
          .html("Please Wait...");
      },
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        if (result.data.error == false) {
          $("#place_order_btn")
            .data("placing", true)
            .prop("disabled", true)
            .attr("disabled", true)
            .html("Please Wait...");
          Toast.fire({
            icon: "success",
            title: result.message,
          });
        } else {
          $("#place_order_btn")
            .data("placing", false)
            .prop("disabled", false)
            .attr("disabled", false)
            .html("Place Order");
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
      },
      error: function () {
        $("#place_order_btn")
          .data("placing", false)
          .prop("disabled", false)
          .attr("disabled", false)
          .html("Place Order");
      },
    });
  }

  $("#redeem_btn").on("click", function (event) {
    event.preventDefault();

    var formdata = new FormData();
    formdata.append(csrfName, csrfHash);
    formdata.append("promo_code", $("#promocode_input").val());
    formdata.append("address_id", $("#address_id").val());

    $.ajax({
      type: "POST",
      data: formdata,
      url: base_url + "cart/validate-promo-code",
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        csrfName = data.csrfName;
        csrfHash = data.csrfHash;

        if (data.error === false) {
          let final_discount = parseFloat(data.data[0].final_discount) || 0;
          let is_cashback = data.data[0].is_cashback;

          let current_total =
            parseFloat($("#final_total").text().replace(/,/g, "")) || 0;

          $("#checkout_base_total").val(current_total);

          $("#promocode_div").removeClass("d-none");
          $("#promocode").text("(" + data.data[0].promo_code + ")");

          if (is_cashback == 1) {
            $("#promocode_amount").text(
              final_discount.toFixed(2) + " (Cashback)",
            );

            $("#final_total").text(current_total.toFixed(2));
            $("#amount").val(current_total);
          } else {
            $("#promocode_amount").text("- " + final_discount.toFixed(2));

            let new_total = current_total - final_discount;
            if (new_total < 0) new_total = 0;

            $("#final_total").text(new_total.toFixed(2));
            $("#amount").val(new_total);
          }

          $("#promo_is_cashback").val(is_cashback);
          $("#promo_set").val(1);
          $("#clear_promo_btn").removeClass("d-none");
          $("#redeem_btn").hide();

          Toast.fire({
            icon: "success",
            title: data.cashback_message || data.message,
          });
        } else {
          Toast.fire({
            icon: "error",
            title: data.message,
          });
          $("#promo_set").val(0);
          $("#promocode_input").val("");
        }
      },
    });
  });
  $("#clear_promo_btn").on("click", function (event) {
    event.preventDefault();

    let base_total = parseFloat($("#checkout_base_total").val());

    if (isNaN(base_total) || base_total <= 0) {
      base_total = parseFloat($("#final_total").text().replace(/,/g, "")) || 0;
    }

    $("#final_total").text(base_total.toFixed(2));
    $("#amount").val(base_total);

    $("#promocode_div").addClass("d-none");
    $("#promocode_amount").text("");
    $("#promocode_input").val("");
    $("#promo_set").val(0);

    $("#clear_promo_btn").addClass("d-none");
    $("#redeem_btn").show();

    // Optional: clear stored base
    $("#checkout_base_total").val("");
    location.reload();
  });

  $(document).on("click", "#redeem_promocode", function (e) {
    e.preventDefault();
    var promo_code = $(this).data("value");
    $("#promocode_input").val(promo_code);
    $(this)
      .addClass("bg-body-tertiary")
      .siblings()
      .removeClass("bg-body-tertiary");
  });

  $(document).on("click", "#wallet_balance", function () {
    var current_wallet_balance = $("#current_wallet_balance").val();
    var wallet_balance = current_wallet_balance.replace(",", "");
    var final_total = $("#final_total").text();
    final_total = final_total.replace(",", "");

    var sub_total = $("#sub_total").val();
    let payment_method = $("input[name=payment_method]:checked").val();
    if (payment_method == "COD") {
      var delivery_charge = $(".delivery_charge_with_cod")
        .val()
        .replace(",", "");
    } else {
      var delivery_charge = $(".delivery_charge_without_cod")
        .val()
        .replace(",", "");
    }
    // var delivery_charge_with_cod = $(".delivery_charge_with_cod").val().replace(',', '');
    // var delivery_charge_without_cod = $(".delivery_charge_without_cod").val().replace(',', '');
    var local_pick_up = $("input[name='local_pickup']:checked").val();

    if (delivery_charge != undefined) {
      if (delivery_charge == "") {
        delivery_charge = 0;
      } else {
        delivery_charge = delivery_charge.replace(",", "");
      }
    } else {
      delivery_charge = 0;
    }
    var promo_set = $("#promo_set").val();
    var promocode_amount = "";
    if (promo_set == 1) {
      promocode_amount = $("#promocode_amount").text();
      promocode_amount = promocode_amount.replace(",", "");
    } else {
      promocode_amount = 0;
    }
    var bulk_discount = $("#bulk_discount").val() || "0";
    // var platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;

    // Wallet Used
    var wallet_used = $(".wallet_used").text();
    wallet_used = wallet_used === "" ? 0 : wallet_used.replace(",", "");

    // --- CUSTOM CHARGES TOTAL ---
    let custom_charges_total = 0;
    if ($("#custom_charges_json").length > 0) {
      let custom_charges = JSON.parse($("#custom_charges_json").val());
      custom_charges.forEach((c) => {
        custom_charges_total += parseFloat(c.amount);
      });
    }
    if ($(this).is(":checked")) {
      $("#wallet_used").val(1);
      wallet_balance = parseFloat(wallet_balance.replace(",", ""));

      if (final_total - wallet_balance <= 0) {
        var available_balance = wallet_balance - final_total;
        available_balance = parseFloat(available_balance);
        $(".wallet_used").html(
          final_total.toLocaleString(undefined, {
            maximumFractionDigits: 2,
          }),
        );
        $("#available_balance").html(
          available_balance.toLocaleString(undefined, {
            maximumFractionDigits: 2,
          }),
        );
        $("#final_total").html("0.00");
        $("#cod").prop("required", false);
        $("#paypal").prop("required", false);
        $("#razorpay").prop("required", false);
        $("#midtrans").prop("required", false);
        $("#my_fatoorah").prop("required", false);
        $("#paystack").prop("required", false);
        $("#payumoney").prop("required", false);
        $("#flutterwave").prop("required", false);
        $("#paytm").prop("required", false);
        $("#bank_transfer").prop("required", false);
        $("#stripe").prop("required", false);
        $("#paytm").prop("required", false);
        $("#bank_transfer").prop("required", false);
        $(".wallet-section").removeClass("d-none");
        $(".wallet-section").removeAttr("style");
        $(".payment-methods").hide();
      } else {
        $(".wallet_used").html(current_wallet_balance);
        $("#available_balance").html("0.00");
        final_total =
          parseFloat(sub_total) -
          parseFloat(wallet_balance) -
          parseFloat(promocode_amount) +
          parseFloat(delivery_charge) +
          parseFloat(custom_charges_total) -
          parseFloat(bulk_discount);

        $("#final_total").html(
          final_total.toLocaleString(undefined, {
            maximumFractionDigits: 2,
          }),
        );
        $("#amount").val(final_total);
        $(".wallet-section").removeClass("d-none");
        $(".wallet-section").removeAttr("style");

        $("#cod").prop("required", true);
        $("#paypal").prop("required", true);
        $("#razorpay").prop("required", true);
        $("#paystack").prop("required", true);
        $("#payumoney").prop("required", true);
        $("#flutterwave").prop("required", true);
        $("#paytm").prop("required", true);
        $("#bank_transfer").prop("required", true);
        $("#stripe").prop("required", true);
        $("#paytm").prop("required", true);
        $("#bank_transfer").prop("required", true);
        $(".payment-methods").show();
      }
    } else {
      $("#wallet_used").val(0);
      if (local_pick_up == 1) {
        delivery_charge = 0;
      }
      var final_total =
        parseFloat(sub_total) +
        parseFloat(delivery_charge) +
        parseFloat(custom_charges_total) -
        parseFloat(promocode_amount) -
        parseFloat(bulk_discount);

      $(".wallet_used").html("0.00");
      $("#final_total").html(
        final_total.toLocaleString(undefined, {
          maximumFractionDigits: 2,
        }),
      );
      $("#amount").val(final_total);
      $("#available_balance").html(current_wallet_balance);
      $(".wallet-section").addClass("d-none");
      $(".payment-methods").show();
      $("#cod").prop("required", true);
      $("#paypal").prop("required", true);
      $("#razorpay").prop("required", true);
      $("#paystack").prop("required", true);
      $("#payumoney").prop("required", true);
      $("#flutterwave").prop("required", true);
      $("#paytm").prop("required", true);
      $("#bank_transfer").prop("required", true);
      $("#stripe").prop("required", true);
      $("#paytm").prop("required", true);
      $("#bank_transfer").prop("required", true);
    }
  });
});
