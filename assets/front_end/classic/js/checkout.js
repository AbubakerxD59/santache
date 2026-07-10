"use strict";
var stripe1;
var fatoorah_url = "";
var currency = $("#currency").val();
var supported_locals = $("#supported_locals").val();
var Toast;

$(document).ready(function () {
  Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
  });

  function initCheckoutZipAutocomplete() {
    if (window.AddressZipcodeAutocomplete) {
      window.AddressZipcodeAutocomplete.bind({
        zipcodes: window.ADDRESS_ZIPCODES || [],
        zipInput: "#checkout_pincode",
        citySelect: "#checkout_city",
        suggestionsList: "#checkout-zipcode-suggestions",
      });
    } else {
      setTimeout(initCheckoutZipAutocomplete, 50);
    }
  }
  initCheckoutZipAutocomplete();

  var addresses = [];
  let newlyCreatedAddressId = null;

  if ($("#csrf_token").length) {
    csrfName = $("#csrf_token").attr("name") || csrfName;
    csrfHash = $("#csrf_token").val() || csrfHash;
  }

  function formatAddressLine(parts) {
    return parts
      .map(function (p) {
        return p == null ? "" : String(p).trim();
      })
      .filter(function (p) {
        return p.length > 0;
      });
  }

  function getAddressLineOne(a) {
    if (!a) return "";
    return formatAddressLine([a.address]).join("");
  }

  function getAddressLineTwo(a) {
    if (!a) return "";
    return formatAddressLine([a.city, a.state, a.pincode, a.country]).join(", ");
  }

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
    user_contact
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

  function flutterwave_payment(delivery_type) {
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
        delivery_type: delivery_type,
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
      "json"
    );
  }
  $("#checkout_form").on("submit", function (event) {
    event.preventDefault();
    var type = $("#product_type").val();
    var fatoorah_order_id = "";
    var local_pick_up = $("input[name='local_pickup']:checked").val();
    var address_id = $("#address_id").val();
    var delivery_type = local_pick_up == "1" ? "pickup_from_store" : "doorstep";
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
    var btn_html = $("#place_order_btn").html();
    $("#place_order_btn").attr("disabled", true).html("Please Wait...");
    if (
      $("#is_time_slots_enabled").val() == 1 &&
      $('input[name="delivery_time"]').length > 0 &&
      $('input[name="delivery_time"]').is(":checked") == false &&
      $("#product_type").val() != "digital_product"
    ) {
      Toast.fire({
        icon: "error",
        title: "Please select Delivery Date & Time.",
      });
      $("#place_order_btn").attr("disabled", false).html(btn_html);
      return false;
    }
    var address_id = $("#address_id").val();
    if (local_pick_up == 0 && type != "digital_product") {
      if (address_id == null || address_id == undefined || address_id == "") {
        Toast.fire({
          icon: "error",
          title: "Please add/choose address.",
        });
        $("#place_order_btn").attr("disabled", false).html(btn_html);
        return false;
      }
    }
    var payment_methods = $("input[name='payment_method']:checked").val();
    if (payment_methods == "Stripe") {
      $.post(
        base_url + "cart/pre-payment-setup",
        {
          [csrfName]: csrfHash,
          payment_method: "Stripe",
          wallet_used: wallet_used,
          address_id: address_id,
          promo_code: promo_code,
          delivery_type: delivery_type,
          final_total_with_charges: final_total,
        },
        function (data) {
          $("#stripe_client_secret").val(data.client_secret);
          $("#stripe_payment_id").val(data.id);
          var stripe_client_secret = data.client_secret;
          stripe_payment(stripe1.stripe, stripe1.card, stripe_client_secret);
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
        },
        "json"
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
        "json"
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
            var amount = $("#amount").val();
            var rzp1 = razorpay_setup(
              key,
              amount,
              app_name,
              logo,
              razorpay_order_id,
              username,
              user_email,
              user_contact
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
        "json"
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
        "json"
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
        },
        function (data) {
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
          if (data.error == false) {
            $("#instamojo_order_id").val(data.order_id);
            var instamojo_payment = instamojo_setup(data.redirect_url);
            $("#place_order_btn").attr("disabled", false).html("Place Order");
          } else {
            Toast.fire({
              icon: "error",
              title: data.message,
            });
          }
        },
        "json"
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
          "json"
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
          final_total_with_charges: final_total,
        },
        function (data) {
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
              user_contact
            );
          } else {
            Toast.fire({
              icon: "error",
              title: "Something went wrong please try again later.",
            });
          }
        },
        "json"
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
          final_total_with_charges: final_total,
        },
        function (data) {
          var url = data["url"] ? data["url"] : "";
          var message = data["data"]["message"] ? data["data"]["message"] : "";
          $("#phonepe_transaction_id").val(
            data["transaction_id"] ? data["transaction_id"] : ""
          );

          if (url != "") {
            place_order().done(function (result) {
              if (result.error == false) {
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
              title: message,
            });
          }
        },
        "json"
      );
    } else if (payment_methods == "Flutterwave") {
      flutterwave_payment(delivery_type);
    } else if (
      payment_methods == "COD" ||
      payment_methods == "Direct Bank Transfer"
    ) {
      place_order().done(function (result) {
        if (result.error == false) {
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
        if (result.error == false) {
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
  });

  var instamojo_payment_method = $("#instamojo_payment_method").val();

  function my_fatoorah_setup() {
    window.location.replace(fatoorah_url);
  }

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
  function place_order() {
    let myForm = document.getElementById("checkout_form");
    var final_total_with_charges = $("#amount").val() ?? "";
    var custom_charges_total = getApplicableCustomChargesTotal();
    var formdata = new FormData(myForm);
    formdata.append("custom_charges_total", custom_charges_total);
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
        $("#place_order_btn").attr("disabled", true).html("Please Wait...");
      },
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        $("#place_order_btn").attr("disabled", false).html("Place Order");
        if (result.data.error == false) {
          $("#place_order_btn").attr("disabled", true).html("Place Order");
          Toast.fire({
            icon: "success",
            title: result.message,
          });
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
      },
    });
  }

  $("input[name='payment_method']").on("change", function (e) {
    e.preventDefault();
    var payment_method = $("input[name=payment_method]:checked").val();

    // Update delivery charge based on payment method
    var delivery_charge_with_cod = $(".delivery_charge_with_cod").val();
    var delivery_charge_without_cod = $(".delivery_charge_without_cod").val();
    var sub_total = $("#sub_total").val();
    var delivery_charge = 0;

    if (delivery_charge_with_cod) {
      delivery_charge_with_cod = delivery_charge_with_cod.replace(",", "");
    } else {
      delivery_charge_with_cod = "0";
    }

    if (delivery_charge_without_cod) {
      delivery_charge_without_cod = delivery_charge_without_cod.replace(
        ",",
        ""
      );
    } else {
      delivery_charge_without_cod = "0";
    }

    if (sub_total) {
      sub_total = sub_total.replace(",", "");
    }

    var promocode_amount = $("#promocode_amount").text();
    if (promocode_amount == "") {
      promocode_amount = 0;
    } else {
      promocode_amount = promocode_amount.replace(",", "");
    }

    var wallet_used = $(".wallet_used").text();
    if (wallet_used == "") {
      wallet_used = 0;
    } else {
      wallet_used = wallet_used.replace(",", "");
    }

    // var platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;
    var bulk_discount = $("#bulk_discount").val()
      ? parseFloat($("#bulk_discount").val())
      : 0;
    var product_type = $("#product_type").val();
    var local_pick_up = $("input[name='local_pickup']:checked").val();

    // Calculate custom charges based on delivery type and product type
    var custom_charges_total = 0;
    if ($("#custom_charges_json").length > 0) {
      try {
        var custom_charges = JSON.parse($("#custom_charges_json").val());
        custom_charges.forEach(function (c) {
          // Check if charge applies to current delivery type and product type
          var shouldApply = false;

          if (product_type === "digital_product") {
            shouldApply = c.apply_digital == 1;
          } else if (local_pick_up == "1") {
            shouldApply = c.apply_pickup == 1;
          } else {
            // Doorstep delivery
            shouldApply = c.apply_doorstep == 1;
          }

          if (shouldApply) {
            custom_charges_total += parseFloat(c.amount);
          }
        });
      } catch (e) { }
    } else if ($("#custom_charges_total").length > 0) {
      custom_charges_total = parseFloat($("#custom_charges_total").val()) || 0;
    }

    // Determine delivery charge based on payment method
    if (product_type === "digital_product" || local_pick_up == "1") {
      delivery_charge = 0;
    } else {
      if (payment_method == "COD") {
        delivery_charge = isNaN(parseFloat(delivery_charge_with_cod))
          ? 0
          : parseFloat(delivery_charge_with_cod);
      } else {
        delivery_charge = isNaN(parseFloat(delivery_charge_without_cod))
          ? 0
          : parseFloat(delivery_charge_without_cod);
      }
    }

    // Update displayed delivery charge
    $(".delivery-charge").html(
      delivery_charge.toLocaleString(undefined, {
        maximumFractionDigits: 2,
      })
    );

    // Calculate final total
    var final_total =
      (isNaN(parseFloat(sub_total)) ? 0 : parseFloat(sub_total)) +
      (isNaN(parseFloat(delivery_charge)) ? 0 : parseFloat(delivery_charge)) +
      (isNaN(parseFloat(custom_charges_total))
        ? 0
        : parseFloat(custom_charges_total)) +
      // (isNaN(parseFloat(platform_fees)) ? 0 : parseFloat(platform_fees)) -
      (isNaN(parseFloat(wallet_used)) ? 0 : parseFloat(wallet_used)) -
      (isNaN(parseFloat(promocode_amount)) ? 0 : parseFloat(promocode_amount)) -
      (isNaN(parseFloat(bulk_discount)) ? 0 : parseFloat(bulk_discount));

    $("#amount").val(final_total);
    $("#final_total").val(final_total);
    // $("#final_total").html(
    //   final_total.toLocaleString(undefined, {
    //     maximumFractionDigits: 2,
    //   })
    // );

    if (payment_method == "Stripe") {
      stripe1 = stripe_setup($("#stripe_key_id").val());
      $("#stripe_div").slideDown();
    } else {
      $("#stripe_div").slideUp();
    }
  });

  // $('#redeem_btn').on('click', function (event) {
  //     event.preventDefault()
  //     var formdata = new FormData()
  //     formdata.append(csrfName, csrfHash)
  //     formdata.append('promo_code', $('#promocode_input').val())
  //     var address_id = $('#address_id').val()
  //     formdata.append('address_id', address_id)
  //     var wallet_used = $('.wallet_used').text()
  //     if (wallet_used == '') {
  //         wallet_used = 0
  //     } else {
  //         wallet_used = wallet_used.replace(',', '')
  //     }

  //     $.ajax({
  //         type: 'POST',
  //         data: formdata,
  //         url: base_url + 'cart/validate-promo-code',
  //         dataType: 'json',
  //         cache: false,
  //         processData: false,
  //         contentType: false,
  //         success: function (data) {
  //             csrfName = data.csrfName
  //             csrfHash = data.csrfHash
  //             if (data.error == false) {
  //                 Toast.fire({
  //                     icon: 'success',
  //                     title: data.message
  //                 })
  //                 var delivery_charge = $('.delivery-charge').text()
  //                 if (delivery_charge == '') {
  //                     delivery_charge = 0
  //                 } else {
  //                     delivery_charge = delivery_charge.replace(',', '')
  //                 }
  //                 var final_total = data.data[0].final_total
  //                 final_total =
  //                     parseFloat(final_total) -
  //                     parseFloat(wallet_used) +
  //                     parseFloat(delivery_charge)
  //                 var final_discount = parseFloat(data.data[0].final_discount)
  //                 $('#promocode_div').removeClass('d-none')
  //                 $('#promocode').text('(' + data.data[0].promo_code + ')')
  //                 $('#promocode_amount').text(
  //                     final_discount.toLocaleString(undefined, {
  //                         maximumFractionDigits: 2
  //                     })
  //                 )
  //                 $('#final_total').text(
  //                     final_total.toLocaleString(undefined, {
  //                         maximumFractionDigits: 2
  //                     })
  //                 )
  //                 $('#amount').val(final_total)
  //                 $('#clear_promo_btn').removeClass('d-none')
  //                 $('#redeem_btn').hide()
  //                 $('#promo_set').val(1)
  //             } else {
  //                 Toast.fire({
  //                     icon: 'error',
  //                     title: data.message
  //                 })
  //                 $('#promo_set').val(0)
  //                 $('#promocode_input').val('')
  //             }
  //         }
  //     })
  // })
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
              final_discount.toFixed(2) + " (Cashback)"
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
  // $('#clear_promo_btn').on('click', function (event) {
  //     event.preventDefault()
  //     $('#promocode_div').addClass('d-none')
  //     var wallet_used = $('.wallet_used').text()
  //     if (wallet_used == '') {
  //         wallet_used = 0
  //     } else {
  //         wallet_used = wallet_used.replace(',', '')
  //     }

  //     var promocode_amount = $('#promocode_amount').text()
  //     if (promocode_amount == '') {
  //         promocode_amount = 0
  //     } else {
  //         promocode_amount = promocode_amount.replace(',', '')
  //     }
  //     var sub_total = $('.sub_total').text()
  //     if (sub_total == '') {
  //         sub_total = 0
  //     } else {
  //         sub_total = sub_total.replace(',', '')
  //     }
  //     var delivery_charge = $('.delivery-charge').text()
  //     if (delivery_charge == '') {
  //         delivery_charge = 0
  //     } else {
  //         delivery_charge = delivery_charge.replace(',', '')
  //     }
  //     // var platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;
  //     var bulk_discount = $('#bulk_discount').val() ? parseFloat($('#bulk_discount').val()) : 0;

  //     var new_final_total =
  //         parseFloat(sub_total) -
  //         parseFloat(wallet_used) +
  //         parseFloat(delivery_charge) -
  //         // parseFloat(platform_fees) -
  //         parseFloat(bulk_discount);
  //     $('#final_total').text(
  //         new_final_total.toLocaleString(undefined, {
  //             maximumFractionDigits: 2
  //         })
  //     )
  //     $('#amount').val(new_final_total)
  //     $('#clear_promo_btn').addClass('d-none')
  //     $('#redeem_btn').show()
  //     $('#promocode_input').val('')
  //     $('#promo_set').val(0)
  // })
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
  /* Instantiating iziModal */
  function refreshAddressList(preselectedAddressId = null, modal = null) {
    if (modal) modal.startLoading();
    addresses = [];
    return $.ajax({
      type: "POST",
      data: {
        [csrfName]: csrfHash,
      },
      url: base_url + "my-account/get-address/",
      dataType: "json",
      success: function (data) {
        csrfName = data.csrfName || csrfName;
        csrfHash = data.csrfHash || csrfHash;
        $("#csrf_token").attr("name", csrfName).val(csrfHash);
        var html = "";
        if (data.error == false) {
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
            var addressLineOne = getAddressLineOne(e);
            var addressLineTwo = getAddressLineTwo(e);
            html +=
              '<label for="select-address-' +
              e.id +
              '"><li class="list-group-item d-flex justify-content-between lh-condensed mt-3">' +
              '<div class="col-md-1 h-100 my-auto">' +
              '<input type="radio" class="select-address" ' +
              checked +
              ' name="select-address" data-index=' +
              i +
              ' id="select-address-' +
              e.id +
              '" class="m-0"/>' +
              "</div>" +
              '<div class="col-11 row p-0">' +
              '<div class="col-6 text-dark"><i class="fa fa-map-marker-alt"></i> ' +
              e.name +
              " - " +
              e.type +
              "</div>" +
              (addressLineOne
                ? '<small class="col-12 text-muted">' + addressLineOne + "</small>"
                : "") +
              (addressLineTwo
                ? '<small class="col-12 text-muted">' + addressLineTwo + "</small>"
                : "") +
              '<small class="col-12 text-muted">' +
              e.mobile +
              "</small>" +
              "</div>" +
              "</li></label>";
          });
          $("#address-list").html(html);
        }
        if (modal) modal.stopLoading();
      },
      error: function () {
        if (modal) modal.stopLoading();
      },
    });
  }

  $(".address-modal").iziModal({
    overlayClose: false,
    overlayColor: "rgba(0, 0, 0, 0.6)",
    onOpening: function (modal) {
      refreshAddressList(newlyCreatedAddressId, modal);
    },
  });

  $(".checkout-create-address-modal").iziModal({
    overlayClose: false,
    overlayColor: "rgba(0, 0, 0, 0.6)",
  });

  $(document).on("click", "#open-create-address-modal", function (event) {
    event.preventDefault();
    $("#checkout-create-address-form")[0].reset();
    $("#checkout_country").val("United States");
    $("#checkout_type_home").prop("checked", true);
    var userName = ($("#username").val() || "").trim();
    var selectedMobile = ($("#address-mobile").text() || "").trim();
    var userMobile = ($("#user_contact").val() || "").trim();
    var isValidProfileMobile =
      userMobile !== "" &&
      userMobile.toLowerCase() !== "null" &&
      userMobile !== "0";
    $("#checkout_address_name").val(userName);
    $("#checkout_mobile_number").val(
      isValidProfileMobile ? userMobile : selectedMobile
    );
    $(".checkout-create-address-modal").iziModal("open");
  });

  $(".promo_code_modal").iziModal({
    overlayClose: false,
    overlayColor: "rgba(0, 0, 0, 0.6)",
    onOpening: function (modal) {
      modal.startLoading();
      $.ajax({
        type: "POST",
        data: {
          [csrfName]: csrfHash,
        },
        url: base_url + "my-account/get_promo_codes/",
        dataType: "json",
        success: function (data) {
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
          var html = "";
          if (data.promo_codes.length != 0) {
            $.each(data.promo_codes, function (i, e) {
              html +=
                '<label for="promo-code-' +
                e.id +
                '"><li class="list-group-item d-flex justify-content-between lh-condensed mt-3">' +
                '<a class="btn" id="redeem_promocode" data-value="' +
                e.promo_code +
                '" href="#"><img src="' +
                e.image +
                '" class="redeem_promocode" style="max-width: 100px; max-height: 60px; object-fit: contain;"/></a>' +
                '<div class="col-11 row pl-2">' +
                '<div class="col-6 text-dark">' +
                e.promo_code +
                "</div>" +
                '<small class="col-12 text-muted">' +
                e.message +
                "</small>" +
                "</div>" +
                "</li></label>";
            });
          } else {
            html +=
              '<div class="col-12 text-dark d-flex justify-content-center">Opps...No Offers Avilable</small>';
          }
          $("#promocode-list").html(html);
        },
      });
      modal.stopLoading();
    },
  });

  $(document).on("click", "#redeem_promocode", function () {
    event.preventDefault();
    var promo_code = $(this).data("value");
    $("#promocode_input").val(promo_code);
    $(".promo_code_modal").iziModal("close");
  
  });

  function selectAddressByIndex(index) {
    if (index === undefined || index === null || addresses[index] === undefined) {
      return;
    }
    var address = addresses[index];
    var sub_total = $("#sub_total").val();
    sub_total = sub_total.replace(",", "");
    var total = $("#temp_total").val();
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
    $(".address-modal").iziModal("close");
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
        $("#checkout_form > .row").block({
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
        var is_time_slots_enabled = 1;
        var className = result.error == true ? "danger" : "success";

        $("#checkout_form > .row").unblock();
        $("#deliverable_status").html(
          "<b class='text-" + className + "'>" + result.message + "</b>"
        );
        if (result.availability_data != undefined) {
          result.availability_data.forEach((product) => {
            if (product.is_deliverable == false) {
              $("#p_" + product.product_id).html(
                "<b class='text-danger'> " +
                (product.message ?? "Not deliverable") +
                "</b>"
              );
            } else {
              $("#p_" + product.product_id).html("");
            }
            if (product.delivery_by == "standard_shipping") {
              is_time_slots_enabled = 0;
            }
          });
        }

        $("#is_time_slots_enabled").val(is_time_slots_enabled);
        $(".shipping_method").html(result.shipping_method);
        $(".delivery_charge_with_cod").html(result.delivery_charge_with_cod);
        $(".delivery_charge_with_cod").val(result.delivery_charge_with_cod);
        $(".delivery_charge_without_cod").html(
          result.delivery_charge_without_cod
        );
        $(".delivery_charge_without_cod").val(
          result.delivery_charge_without_cod
        );
        $(".estimate_date").html(result.estimate_date);
        var shipping_method = result.shipping_method;
        var delivery_charge_with_cod = result.delivery_charge_with_cod;
        var delivery_charge_without_cod = result.delivery_charge_without_cod;

        if (result.availability_data != undefined) {
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
        }

        // Check which payment method is currently selected
        var selectedPaymentMethod = $(
          "input[type=radio][name=payment_method]:checked"
        ).val();
        var delivery_charge = 0;
        var product_type = $("#product_type").val();
        var local_pick_up = $("input[name='local_pickup']:checked").val();

        // Determine which delivery charge to use based on payment method
        if (product_type === "digital_product" || local_pick_up == "1") {
          delivery_charge = 0;
        } else {
          if (selectedPaymentMethod === "COD") {
            var delivery_charge_with_cod_val = result.delivery_charge_with_cod;
            delivery_charge_with_cod_val =
              delivery_charge_with_cod_val != null
                ? String(delivery_charge_with_cod_val)
                : "";
            delivery_charge_with_cod_val = delivery_charge_with_cod_val.replace(
              /,/g,
              ""
            );
            delivery_charge = parseFloat(delivery_charge_with_cod_val) || 0;
          } else {
            var delivery_charge_without_cod_val =
              result.delivery_charge_without_cod;
            delivery_charge_without_cod_val =
              delivery_charge_without_cod_val != null
                ? String(delivery_charge_without_cod_val)
                : "";
            delivery_charge_without_cod_val =
              delivery_charge_without_cod_val.replace(/,/g, "");
            delivery_charge = parseFloat(delivery_charge_without_cod_val) || 0;
          }
        }

        // Update displayed delivery charge
        $(".delivery-charge").html(
          delivery_charge.toLocaleString(undefined, {
            maximumFractionDigits: 2,
          })
        );

        // var platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;
        var bulk_discount = $("#bulk_discount").val()
          ? parseFloat($("#bulk_discount").val())
          : 0;
        var wallet_used = $(".wallet_used").text();
        if (wallet_used == "") {
          wallet_used = 0;
        } else {
          wallet_used = wallet_used.replace(",", "");
        }
        var custom_charges_total = 0;
        if ($("#custom_charges_json").length > 0) {
          try {
            var custom_charges = JSON.parse($("#custom_charges_json").val());
            custom_charges.forEach(function (c) {
              var shouldApply = false;

              if (product_type === "digital_product") {
                shouldApply = c.apply_digital == 1;
              } else if (local_pick_up == "1") {
                shouldApply = c.apply_pickup == 1;
              } else {
                shouldApply = c.apply_doorstep == 1;
              }

              if (shouldApply) {
                custom_charges_total += parseFloat(c.amount);
              }
            });
          } catch (e) { }
        } else if ($("#custom_charges_total").length > 0) {
          custom_charges_total =
            parseFloat($("#custom_charges_total").val()) || 0;
        }
        var final_total =
          parseFloat(sub_total) +
          parseFloat(delivery_charge) +
          parseFloat(custom_charges_total) -
          // parseFloat(platform_fees) -
          parseFloat(wallet_used) -
          parseFloat(promocode_amount) -
          parseFloat(bulk_discount);
        final_total = final_total.toLocaleString(undefined, {
          maximumFractionDigits: 2,
        });

        $("#final_total").html(final_total);
        var final_total = final_total.replace(",", "");
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
    var index = $('input[class="select-address"]:checked').data("index");
    selectAddressByIndex(index);
  });

  $("#checkout-create-address-form").on("submit", function (event) {
    event.preventDefault();

    if (!$("#checkout_city").val()) {
      Toast.fire({
        icon: "error",
        title: "Please select a city.",
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
          $(".checkout-create-address-modal").iziModal("close");
          refreshAddressList(createdAddressId, null).done(function () {
            var selectedIndex = null;
            if (createdAddressId !== null) {
              selectedIndex = addresses.findIndex(function (addr) {
                return String(addr.id) === String(createdAddressId);
              });
            }
            if (selectedIndex === null || selectedIndex < 0) {
              selectedIndex = addresses.length > 0 ? addresses.length - 1 : null;
            }
            selectAddressByIndex(selectedIndex);
            $(".address-modal").iziModal("close");
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
  minDate: mindate,
  maxDate: maxdate,
  locale: {
    format: "DD/MM/YYYY",
    separator: " - ",
    cancelLabel: "Clear",
    label: "Preferred Delivery Date",
  },
});
$(document).ready(function () {
  // Calculate initial total with bulk discount on page load for consistency
  var initial_sub_total = $("#sub_total").val();
  var initial_bulk_discount = $("#bulk_discount").val()
    ? parseFloat($("#bulk_discount").val())
    : 0;
  // var initial_platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;

  if (initial_bulk_discount > 0 && initial_sub_total) {
    var initial_total = parseFloat(initial_sub_total) - initial_bulk_discount;
    $("#final_total").html(
      initial_total.toLocaleString(undefined, {
        maximumFractionDigits: 2,
      })
    );
  }

  var address_id = $("#address_id").val();
  var sub_total = $("#sub_total").val();
  var total = $("#temp_total").val();
  $.ajax({
    type: "POST",
    data: {
      [csrfName]: csrfHash,
      address_id: address_id,
      total: total,
    },
    url: base_url + "cart/get-delivery-charge",
    dataType: "json",
    success: function (result) {
     
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      var className = result.error == true ? "danger" : "success";
      var is_time_slots_enabled = 1;
      $("#deliverable_status").html(
        "<b class='text-" + className + "'>" + result.message + "</b>"
      );

      if (result.availability_data) {
        if (result.availability_data != undefined) {
          result.availability_data.forEach((product) => {
            if (product.is_deliverable == false) {
              $("#p_" + product.product_id).html(
                "<b class='text-danger'> " +
                (product.message ?? "Not deliverable") +
                "</b>"
              );
            } else {
              $("#p_" + product.product_id).html("");
            }
            if (product.delivery_by == "standard_shipping") {
              is_time_slots_enabled = 0;
            }
          });
        }
      }
      $("#is_time_slots_enabled").val(is_time_slots_enabled);
      $(".shipping_method").html(result.shipping_method);
      $(".delivery-charge").html(result.delivery_charge_without_cod);
      $(".delivery_charge_with_cod").html(result.delivery_charge_with_cod);
      $(".delivery_charge_with_cod").val(result.delivery_charge_with_cod);
      $(".delivery_charge_without_cod").html(
        result.delivery_charge_without_cod
      );
      $(".delivery_charge_without_cod").val(result.delivery_charge_without_cod);
      $(".estimate_date").html(result.estimate_date);
      var shipping_method = result.shipping_method;
      var delivery_charge = result.delivery_charge_with_cod;
      var delivery_charge_with_cod = result.delivery_charge_with_cod;
      var delivery_charge_without_cod = result.delivery_charge_without_cod;
      if (result.availability_data != undefined) {
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
      }

      // Check which payment method is currently selected
      var selectedPaymentMethod = $(
        "input[type=radio][name=payment_method]:checked"
      ).val();
      var delivery_charge = 0;
      var product_type = $("#product_type").val();
      var local_pick_up = $("input[name='local_pickup']:checked").val();

      // Determine which delivery charge to use based on payment method
      if (product_type === "digital_product" || local_pick_up == "1") {
        delivery_charge = 0;
      } else {
        if (selectedPaymentMethod === "COD") {
          var delivery_charge_with_cod_val = result.delivery_charge_with_cod;
          delivery_charge_with_cod_val =
            delivery_charge_with_cod_val != null
              ? String(delivery_charge_with_cod_val)
              : "";
          delivery_charge_with_cod_val = delivery_charge_with_cod_val.replace(
            /,/g,
            ""
          );
          delivery_charge = parseFloat(delivery_charge_with_cod_val) || 0;
        } else {
          var delivery_charge_without_cod_val =
            result.delivery_charge_without_cod;
          delivery_charge_without_cod_val =
            delivery_charge_without_cod_val != null
              ? String(delivery_charge_without_cod_val)
              : "";
          delivery_charge_without_cod_val =
            delivery_charge_without_cod_val.replace(/,/g, "");
          delivery_charge = parseFloat(delivery_charge_without_cod_val) || 0;
        }
      }

      // Update displayed delivery charge
      $(".delivery-charge").html(
        delivery_charge.toLocaleString(undefined, {
          maximumFractionDigits: 2,
        })
      );

      // Calculate final total with all components
      // var platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;
      var bulk_discount = $("#bulk_discount").val()
        ? parseFloat($("#bulk_discount").val())
        : 0;
      var sub_total_val = sub_total
        ? parseFloat(sub_total.replace(/,/g, ""))
        : 0;

      var custom_charges_total = 0;
      if ($("#custom_charges_json").length > 0) {
        try {
          var custom_charges = JSON.parse($("#custom_charges_json").val());
          custom_charges.forEach(function (c) {
            // Filter based on product type and delivery type
            var shouldApply = false;

            if (product_type === "digital_product") {
              shouldApply = c.apply_digital == 1;
            } else if (local_pick_up == "1") {
              shouldApply = c.apply_pickup == 1;
            } else {
              // Doorstep delivery
              shouldApply = c.apply_doorstep == 1;
            }

            if (shouldApply) {
              custom_charges_total += parseFloat(c.amount);
            }
          });
        } catch (e) { }
      } else if ($("#custom_charges_total").length > 0) {
        custom_charges_total =
          parseFloat($("#custom_charges_total").val()) || 0;
      }
      var final_total =
        sub_total_val + delivery_charge + custom_charges_total - bulk_discount;
     
      $("#amount").val(final_total);
      final_total = final_total.toLocaleString(undefined, {
        maximumFractionDigits: 2,
      });
      $("#final_total").html(final_total);

      // Update custom charges visibility on page load
      if (typeof updateCustomCharges === "function") {
        setTimeout(updateCustomCharges, 100);
      }
    },
  });
});
$(document).on("click", "#wallet_balance", function () {
  var current_wallet_balance = $("#current_wallet_balance").val();
  var wallet_balance = current_wallet_balance.replace(",", "");
  var final_total = $("#final_total").text();

  var is_cashback = $("#promo_is_cashback").val();
  final_total = final_total.replace(",", "");

  var sub_total = $("#sub_total").val();
  var delivery_charge = $(".delivery_charge_with_cod").val();

  if (delivery_charge != undefined) {
    if (delivery_charge == "") {
      delivery_charge = 0;
    } else {
      delivery_charge = delivery_charge.replace(",", "");
    }
  } else {
    delivery_charge = 0;
  }
  var promocode_amount = $("#promocode_amount").text();
  if (promocode_amount == "") {
    promocode_amount = 0;
  } else {
    promocode_amount = promocode_amount.replace(",", "");
  }
  var wallet_used = $(".wallet_used").text();
  if (wallet_used == "") {
    wallet_used = 0;
  } else {
    wallet_used = wallet_used.replace(",", "");
  }
  // var platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;
  var bulk_discount = $("#bulk_discount").val()
    ? parseFloat($("#bulk_discount").val())
    : 0;

  if ($(this).is(":checked")) {
    $("#wallet_used").val(1);
    wallet_balance = parseFloat(wallet_balance.replace(",", ""));

    if (final_total - wallet_balance <= 0) {
      var available_balance = wallet_balance - final_total;
      available_balance = parseFloat(available_balance);
      $(".wallet_used").html(
        final_total.toLocaleString(undefined, { maximumFractionDigits: 2 })
      );
      $("#available_balance").html(
        available_balance.toLocaleString(undefined, {
          maximumFractionDigits: 2,
        })
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
      $(".payment-methods").hide();
    } else {
      $(".wallet_used").html(current_wallet_balance);
      $("#available_balance").html("0.00");
      var custom_charges_total = 0;
      var product_type = $("#product_type").val();
      var local_pick_up = $("input[name='local_pickup']:checked").val();
      if ($("#custom_charges_json").length > 0) {
        try {
          var custom_charges = JSON.parse($("#custom_charges_json").val());
          custom_charges.forEach(function (c) {
            var shouldApply = false;

            if (product_type === "digital_product") {
              shouldApply = c.apply_digital == 1;
            } else if (local_pick_up == "1") {
              shouldApply = c.apply_pickup == 1;
            } else {
              shouldApply = c.apply_doorstep == 1;
            }

            if (shouldApply) {
              custom_charges_total += parseFloat(c.amount);
            }
          });
        } catch (e) { }
      } else if ($("#custom_charges_total").length > 0) {
        custom_charges_total =
          parseFloat($("#custom_charges_total").val()) || 0;
      }
      final_total =
        parseFloat(sub_total) -
        parseFloat(wallet_balance) -
        parseFloat(promocode_amount) +
        parseFloat(delivery_charge) +
        parseFloat(custom_charges_total) -
        parseFloat(bulk_discount);

      $("#final_total").html(
        final_total.toLocaleString(undefined, { maximumFractionDigits: 2 })
      );
      $("#amount").val(final_total);
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
    $("#wallet_used").val(1);

    var custom_charges_total = 0;
    var product_type = $("#product_type").val();
    var local_pick_up = $("input[name='local_pickup']:checked").val();
    if ($("#custom_charges_json").length > 0) {
      try {
        var custom_charges = JSON.parse($("#custom_charges_json").val());
        custom_charges.forEach(function (c) {
          var shouldApply = false;

          if (product_type === "digital_product") {
            shouldApply = c.apply_digital == 1;
          } else if (local_pick_up == "1") {
            shouldApply = c.apply_pickup == 1;
          } else {
            shouldApply = c.apply_doorstep == 1;
          }

          if (shouldApply) {
            custom_charges_total += parseFloat(c.amount);
          }
        });
      } catch (e) { }
    } else if ($("#custom_charges_total").length > 0) {
      custom_charges_total = parseFloat($("#custom_charges_total").val()) || 0;
    }
    if (is_cashback == 1) {
      final_total =
        parseFloat(sub_total) +
        parseFloat(delivery_charge) +
        parseFloat(custom_charges_total) -
        parseFloat(bulk_discount);
    } else {
      final_total =
        parseFloat(sub_total) +
        parseFloat(delivery_charge) -
        parseFloat(promocode_amount) +
        parseFloat(custom_charges_total) -
        parseFloat(bulk_discount);
    }

    $(".wallet_used").html("0.00");
    $("#final_total").html(
      final_total.toLocaleString(undefined, { maximumFractionDigits: 2 })
    );
    $("#amount").val(final_total);
    $("#available_balance").html(current_wallet_balance);
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

function paytm_setup(
  txnToken,
  orderId,
  amount,
  app_name,
  logo,
  username,
  user_email,
  user_contact
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
          $("#place_order_btn").attr("disabled", false).html("Place Order");
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
                .attr("disabled", true)
                .html("Please Wait...");
            },
            success: function (data) {
              csrfName = data.csrfName;
              csrfHash = data.csrfHash;
              $("#place_order_btn").attr("disabled", false).html("Place Order");
              if (data.error == false) {
                Toast.fire({
                  icon: "success",
                  title: data.message,
                });
                setTimeout(function () {
                  location.href = base_url + "payment/success";
                }, 3000);
              } else {
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
  var payment_method = $(this).val();

  // Update delivery charge based on payment method
  var delivery_charge_with_cod = $(".delivery_charge_with_cod").val();
  var delivery_charge_without_cod = $(".delivery_charge_without_cod").val();
  var sub_total = $("#sub_total").val();
  var delivery_charge = 0;

  if (delivery_charge_with_cod) {
    delivery_charge_with_cod = delivery_charge_with_cod.replace(",", "");
  } else {
    delivery_charge_with_cod = "0";
  }

  if (delivery_charge_without_cod) {
    delivery_charge_without_cod = delivery_charge_without_cod.replace(",", "");
  } else {
    delivery_charge_without_cod = "0";
  }

  if (sub_total) {
    sub_total = sub_total.replace(",", "");
  }

  let promocode_amount = $("#promocode_amount").text().trim();

  // remove everything except digits, minus, dot
  promocode_amount = promocode_amount.replace(/[^\d.-]/g, "");

  if (promocode_amount === "" || isNaN(promocode_amount)) {
    promocode_amount = 0;
  } else {
    promocode_amount = Math.abs(parseFloat(promocode_amount));
  }
  
  var wallet_used = $(".wallet_used").text();
  if (wallet_used == "") {
    wallet_used = 0;
  } else {
    wallet_used = wallet_used.replace(",", "");
  }

  // var platform_fees = $('#platform_fees').val() ? parseFloat($('#platform_fees').val()) : 0;
  var bulk_discount = $("#bulk_discount").val()
    ? parseFloat($("#bulk_discount").val())
    : 0;
  var product_type = $("#product_type").val();
  var local_pick_up = $("input[name='local_pickup']:checked").val();

  // Calculate custom charges based on delivery type and product type
  var custom_charges_total = 0;
  if ($("#custom_charges_json").length > 0) {
    try {
      var custom_charges = JSON.parse($("#custom_charges_json").val());
      custom_charges.forEach(function (c) {
        var shouldApply = false;

        if (product_type === "digital_product") {
          shouldApply = c.apply_digital == 1;
        } else if (local_pick_up == "1") {
          shouldApply = c.apply_pickup == 1;
        } else {
          shouldApply = c.apply_doorstep == 1;
        }

        if (shouldApply) {
          custom_charges_total += parseFloat(c.amount);
        }
      });
    } catch (e) { }
  } else if ($("#custom_charges_total").length > 0) {
    custom_charges_total = parseFloat($("#custom_charges_total").val()) || 0;
  }

  // Determine delivery charge based on payment method
  if (product_type === "digital_product" || local_pick_up == "1") {
    delivery_charge = 0;
  } else {
    if (payment_method == "COD") {
      delivery_charge = isNaN(parseFloat(delivery_charge_with_cod))
        ? 0
        : parseFloat(delivery_charge_with_cod);
    } else {
      delivery_charge = isNaN(parseFloat(delivery_charge_without_cod))
        ? 0
        : parseFloat(delivery_charge_without_cod);
    }
  }

  // Update displayed delivery charge
  $(".delivery-charge").html(
    delivery_charge.toLocaleString(undefined, {
      maximumFractionDigits: 2,
    })
  );
  
  // Calculate final total
  var final_total =
    (isNaN(parseFloat(sub_total)) ? 0 : parseFloat(sub_total)) +
    (isNaN(parseFloat(delivery_charge)) ? 0 : parseFloat(delivery_charge)) +
    (isNaN(parseFloat(custom_charges_total))
      ? 0
      : parseFloat(custom_charges_total)) -
    // (isNaN(parseFloat(platform_fees)) ? 0 : parseFloat(platform_fees)) -
    (isNaN(parseFloat(wallet_used)) ? 0 : parseFloat(wallet_used)) -
    promocode_amount -
    (isNaN(parseFloat(bulk_discount)) ? 0 : parseFloat(bulk_discount));
  
  $("#amount").val(final_total);
  $("#final_total").html(
    final_total.toLocaleString(undefined, {
      maximumFractionDigits: 2,
    })
  );

  if (payment_method == "Direct Bank Transfer") {
    $("#account_data").show();
    $("#bank_transfer_slide").slideDown();
  } else {
    $("#account_data").hide();
    $("#bank_transfer_slide").slideUp();
  }
});

var global_final_total = 0;
var global_delivery_charge = 0;

// Helper function to update custom charges visibility and recalculate total
function updateCustomCharges() {
  var product_type = $("#product_type").val();
  var local_pick_up = $("input[name='local_pickup']:checked").val();
  var sub_total = parseFloat($(".sub_total").text().replace(/,/g, "")) || 0;
  var delivery_charge =
    parseFloat($(".delivery-charge").text().replace(/,/g, "")) || 0;
  var promocode_amount =
    parseFloat($("#promocode_amount").text().replace(/,/g, "")) || 0;
  var wallet_used = parseFloat($(".wallet_used").text().replace(/,/g, "")) || 0;
  var bulk_discount = parseFloat($("#bulk_discount").val()) || 0;
  var custom_charges_total = 0;

  // Show/hide custom charge rows based on delivery type
  $(".custom-charge-row").each(function () {
    var $row = $(this);
    var shouldShow = false;

    if (product_type === "digital_product") {
      shouldShow = $row.data("apply-digital") == 1;
    } else if (local_pick_up == "1") {
      shouldShow = $row.data("apply-pickup") == 1;
    } else {
      shouldShow = $row.data("apply-doorstep") == 1;
    }

    if (shouldShow) {
      $row.show();
      custom_charges_total += parseFloat($row.data("amount")) || 0;
    } else {
      $row.hide();
    }
  });

  // Recalculate final total
  var final_total =
    sub_total +
    delivery_charge +
    custom_charges_total -
    wallet_used -
    promocode_amount -
    bulk_discount;
  $("#amount").val(final_total);
  $("#final_total").html(
    final_total.toLocaleString(undefined, {
      maximumFractionDigits: 2,
    })
  );
}

$("#pickup_from_store").on("change", function (e) {
  e.preventDefault();
  var final_time = $("#final_total").text();
  var delivery_charge = parseInt($(".delivery-charge").text());
  var sub_total = $(".sub_total").text();
  global_final_total = final_time;
  global_delivery_charge = delivery_charge;

  $(".address").hide();
  $(".delivery_charge").hide();
  $(".delivery_charge_with_cod").hide();
  $(".delivery_charge_without_cod").hide();
  $(".estimate_date").hide();
  $(".deliverycharge_currency").hide();
  $(".deliverable_status").hide();
  $(".delivery-charge").text("0.00");

  // Update custom charges for pickup
  updateCustomCharges();
});
$("#door_step").on("change", function (e) {
  e.preventDefault();
  $(".address").show();
  $(".delivery_charge").show();
  $(".delivery_charge_with_cod").show();
  $(".delivery_charge_without_cod").show();
  $(".estimate_date").show();
  $(".deliverycharge_currency").show();
  $(".delivery-charge").text(global_delivery_charge);
  $(".deliverable_status").show();

  // Update custom charges for doorstep delivery
  updateCustomCharges();
});
