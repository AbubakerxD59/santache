"use strict";
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
import {
  getMessaging,
  getToken,
  onMessage,
} from "https://cdnjs.cloudflare.com/ajax/libs/firebase/10.0.0/firebase-messaging.min.js";

$.ajaxSetup({
  beforeSend: function (jqXHR, settings) {
    if (settings.type && settings.type.toUpperCase() === "POST") {
      if (settings.data instanceof FormData) {
        settings.data.append(csrfName, csrfHash);
      } else if (typeof settings.data === "string") {
        settings.data +=
          (settings.data ? "&" : "") +
          encodeURIComponent(csrfName) +
          "=" +
          encodeURIComponent(csrfHash);
      } else {
        settings.data = settings.data || {};
        settings.data[csrfName] = csrfHash;
      }
    }
  },
});

// For deeplink
var android_app_store_link = $("#android_app_store_link").val();
var ios_app_store_link = $("#ios_app_store_link").val();
var host = $("#host").val();
var scheme = $("#scheme").val();

$(document).ready(function () {
  // Function to check if the device is mobile or tablet
  function isMobileOrTablet() {
    return true;
    return window.matchMedia("(max-width: 1024px)").matches;
  }
});

function requestPermission() {
  Notification.requestPermission().then((permission) => {
    if (permission === "granted") {
    }
  });
}
$(document).ready(function () {
  $.ajax({
    url: base_url + "my-account/get_system_settings_for_web",
    async: false,
    type: "GET",
    dataType: "json",
    success: function (result) {
      var res = JSON.parse(result.firebase_settings);

      const app = initializeApp(res);

      requestPermission();

      const messaging = getMessaging();
      getToken(messaging, { vapidKey: result["vapidKey"] })
        .then((currentToken) => {
          if (currentToken) {
            updateWebFCM(currentToken);
          } else {
          }
        })
        .catch((err) => { });
    },
  });
});

// $('.edit-address').on('click', function (e, row) {
function populateEditAddressForm(row) {
  $("#address_id").val(row.id);
  $("#edit_name").val(row.name);
  $("#edit_mobile").val(row.mobile);
  $("#edit_alternate_mobile").val(row.alternate_mobile);
  $("#edit_address").val(row.address);
  $("#edit_state").val(row.state);
  $("#edit_country").val(row.country);
  $("#edit_zipcode").val(row.pincode);
  if ($("#edit_city").length) {
    var matched = false;
    if (row.city_id) {
      $("#edit_city").val(String(row.city_id));
      matched = !!$("#edit_city").val();
    }
    if (!matched && row.city) {
      $("#edit_city option").each(function () {
        if ($(this).text().trim().toLowerCase() === String(row.city).trim().toLowerCase()) {
          $("#edit_city").val($(this).val());
          matched = true;
          return false;
        }
      });
    }
    if (!matched) {
      $("#edit_city").val("");
    }
    var selectedName = $("#edit_city option:selected").val()
      ? $("#edit_city option:selected").text().trim()
      : (row.city || "");
    $("#edit_city_name").val(selectedName);
    if (window.AddressZipcodeAutocomplete && window.ADDRESS_ZIPCODES) {
      var match = window.AddressZipcodeAutocomplete.findZipMatch(window.ADDRESS_ZIPCODES, row.pincode);
      if (match && match.city_id) {
        $("#edit_city").val(String(match.city_id));
        $("#edit_city_name").val(match.city_name || $("#edit_city option:selected").text().trim());
      }
    }
  } else {
    $("#edit_city_name").val(row.city);
  }
  $('#edit-address-form input[name="type"]').prop('checked', false);
  $('#edit-address-form input[name="type"][value="' + (row.type || 'home').toLowerCase() + '"]').prop('checked', true);
}

$("#address_list_table").off("click-cell.bs.table").on(
  "click-cell.bs.table",
  function (field, value, row) {
    if (field !== "action") {
      return;
    }
    populateEditAddressForm(row);
  },
);

window.editAddress = {
  "click .edit-address": function (e, value, row) {
    populateEditAddressForm(row);
  },
};

var currency = $("#currency").val();
var quickViewgalleryTop;
var is_rtl = $("#body").data("is-rtl");
var mode = is_rtl == 1 ? "right" : "left";
const is_loggedin = $("#is_loggedin").val();

var custom_url = location.href;
var target_height = "";
var new_msg_count = 0;
var auth_settings = $("#auth_settings").val();

function queryParams(p) {
  return {
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function transaction_query_params(p) {
  return {
    transaction_type: "transaction",
    user_id: $("#transaction_user_id").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function customer_wallet_query_params(p) {
  return {
    transaction_type: "wallet",
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}
function customer_withdrawal_query_params(p) {
  return {
    payment_type: "customer",
    limit: p.limit,
    offset: p.offset,
    sort: p.sort,
    order: p.order,
    search: p.search,
  };
}

$(document).on("click", ".add-to-fav-btn", function (e) {
  e.preventDefault();
  var formdata = new FormData();
  var product_id = $(this).data("product-id");
  var fav_btn = $(this);
  formdata.append(csrfName, csrfHash);
  formdata.append("product_id", product_id);
  $.ajax({
    type: "POST",
    url: base_url + "my-account/manage-favorites",
    data: formdata,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result.error == true) {
        Toast.fire({
          icon: "error",
          title: result.message,
        });
      } else {
        let favorite_count = result.data.favorite_count ?? null;
        $(".favorite_count").html(parseFloat(favorite_count));
        if (fav_btn.find("ion-icon").attr("name") == "heart") {
          fav_btn
            .find("ion-icon")
            .attr("name", "heart-outline")
            .removeClass("heart text-danger")
            .addClass("heart-outline text-dark");
        } else {
          fav_btn
            .find("ion-icon")
            .attr("name", "heart")
            .removeClass("heart-outline text-dark")
            .addClass("heart text-danger");
        }
        $(".wishlist-product-remove").on("click", function () {
          var productId = $(this).closest(".fav-product").data("product-id");
        });
      }
    },
  });
});
$(document)
  .off("click", ".add-to-fav-btn")
  .on("click", ".add-to-fav-btn", function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var product_id = $(this).data("product-id");
    var fav_btn = $(this);
    formdata.append(csrfName, csrfHash);
    formdata.append("product_id", product_id);
    $.ajax({
      type: "POST",
      url: base_url + "my-account/manage-favorites",
      data: formdata,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == true) {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        } else {
          let favorite_count = result.data.favorite_count ?? null;
          $(".favorite_count").html(parseFloat(favorite_count));
          if (fav_btn.find("ion-icon").attr("name") == "heart") {
            fav_btn
              .find("ion-icon")
              .attr("name", "heart-outline")
              .removeClass("heart text-danger")
              .addClass("heart-outline text-dark");
          } else {
            fav_btn
              .find("ion-icon")
              .attr("name", "heart")
              .removeClass("heart-outline text-dark")
              .addClass("heart text-danger");
          }
          $(".wishlist-product-remove").on("click", function () {
            var productId = $(this).closest(".fav-product").data("product-id");
          });
          Toast.fire({
            icon: "success",
            title: result.message,
          });
        }
      },
    });
  });
$(document).ready(() => {
  $(".wishlist-product-remove").on("click", function (e) {
    e.preventDefault();

    var formdata = new FormData();
    var product_id = $(this).closest(".fav-product").data("product-id");
    var fav_btn = $(this);
    formdata.append(csrfName, csrfHash);
    formdata.append("product_id", product_id);
    $.ajax({
      type: "POST",
      url: base_url + "my-account/manage-favorites",
      data: formdata,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      beforeSend: function () {
        fav_btn
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == true) {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        } else {
          let favorite_count = result.data.favorite_count ?? null;
          $(".favorite_count").html(parseFloat(favorite_count));
          if (result) {
            fav_btn.closest(".fav-product").fadeOut(300);
          } else {
          }
        }
      },
    });
  });

  (function () {
    function logElementEvent(eventName, element) { }

    var callback_enter = function (element) { };
    var callback_exit = function (element) { };
    var callback_loading = function (element) { };
    var callback_loaded = function (element) { };
    var callback_error = function (element) {
      "https://via.placeholder.com/440x560/?text=Error+Placeholder";
    };
    var callback_finish = function () { };
    var callback_cancel = function (element) { };

    var ll = new LazyLoad({
      threshold: 0,
      // Assign the callbacks defined above
      callback_enter: callback_enter,
      callback_exit: callback_exit,
      callback_cancel: callback_cancel,
      callback_loading: callback_loading,
      callback_loaded: callback_loaded,
      callback_error: callback_error,
      callback_finish: callback_finish,
    });
  })();

  $(".wishlist-bulk-remove").on("click", function (e) {
    e.preventDefault();
    var formdata = new FormData();
    var product_id = $(this).closest(".fav-product").data("product-id");
    var fav_btn = $(this);
    formdata.append(csrfName, csrfHash);
    formdata.append("product_id", product_id);
    $.ajax({
      type: "POST",
      url: base_url + "my-account/manage-favorites",
      data: formdata,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",

      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == true) {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        } else {
          let favorite_count = result.data.favorite_count ?? null;
          $(".favorite_count").html(parseFloat(favorite_count));
          if (result) {
            fav_btn
              .$(".checkbox-clicked:checked")
              .closest(".fav-product")
              .data("product-id")
              .hide();
          } else {
          }
        }
      },
    });
  });

  $("#validate-zipcode-form").on("submit", function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
      type: "POST",
      url: base_url + "products/check_zipcode",
      data: formdata,
      beforeSend: function () {
        $("#validate_zipcode")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        $("#validate_zipcode")
          .html("Check Availability")
          .attr("disabled", false);
        if (result.error == false) {
          $("#add_cart").removeClass("disabled");
          $("#buy_it_now").removeClass("disabled");
          $("#error_box").html(result.message);
        } else {
          $("#add_cart").addClass("disabled", "true");
          $("#buy_it_now").addClass("disabled", "true");
          $("#error_box").html(result.message);
        }
      },
    });
  });

  $(".save_for_later").on("click", function (e) {
    e.preventDefault();
    let product_variant_id = $(this).data("id");
    let is_saved_for_later = $(this).data("save-for-later");
    $.ajax({
      type: "POST",
      url: base_url + "cart/manage",
      data: {
        product_variant_id,
        is_saved_for_later,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      success: function (response) {
        csrfName = response.csrfName;
        csrfHash = response.csrfHash;
        if (response.error == false) {
          if (is_saved_for_later == 1) {
            location.reload();
            Toast.fire({
              icon: "success",
              title: "Added to Save For Later",
            });
            return;
          }
          location.reload();
          Toast.fire({
            icon: "success",
            title: "Remove From Save For Later",
          });
          return;
        } else {
          Toast.fire({
            icon: "error",
            title: response.message,
          });
        }
      },
    });
  });

  $("#validate-city-form").on("submit", function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);

    $.ajax({
      type: "POST",
      url: base_url + "products/check_city",
      data: formdata,
      beforeSend: function () {
        $("#validate_city")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        $("#validate_city").html("Check Availability").attr("disabled", false);
        if (result.error == false) {
          $("#add_cart").removeAttr("disabled");
          $("#error_box").html(result.message);
        } else {
          $("#add_cart").attr("disabled", "true");
          $("#error_box").html(result.message);
        }
      },
    });
  });

  $("#load-user-ratings").on("click", function (e) {
    e.preventDefault();
    var limit = $(this).attr("data-limit");
    var offset = $(this).attr("data-offset");
    var product_id = $(this).attr("data-product");
    var btn_html = $(this).html();
    var btn = $(this);
    var html = "";
    $.ajax({
      type: "GET",
      data: {
        limit: limit,
        offset: offset,
        product_id: product_id,
      },
      url: base_url + "products/get-rating",
      dataType: "json",
      beforeSend: function () {
        $(this)
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        $(this).html(btn_html).attr("disabled", false);
        if (result.error == false) {
          $.each(result.data.product_rating, function (i, e) {
            html +=
              '<div class="col-md-6">' +
              '<div class="comment-text">' +
              '<div class="d-flex justify-content-between mb-1">' +
              '<p class="comment-title">' +
              e.user_name +
              ' </p><p class="comment-time">' +
              e.data_added +
              "</p></div>" +
              '<div class="d-flex justify-content-between mb-1">' +
              '<input type="text" name="input-3-ltr-star-md" class="kv-ltr-theme-svg-star kv-fa rating-loading" value="' +
              e.rating +
              '" data-size="xs" dir="ltr" readonly>' +
              "</div>" +
              '<div class="discription"><p>' +
              e.comment +
              '</p></div><div class="comment-image"><div class="row">' +
              $.each(e.images, function (j, image) {
                html +=
                  '<div class="col-3">' +
                  '<a href="' +
                  image +
                  '" data-lightbox="review-images">' +
                  '<img src="' +
                  image +
                  '" width="120px" height="120px" alt="' +
                  image +
                  '"></a></div>';
              });

            html += "</div>" + "</div>" + "</div>" + "</div>";
          });
          offset += limit;
          $("#review-list").append(html);
          $(".kv-fa").rating("create", {
            filledStar: '<i class="fas fa-star"></i>',
            emptyStar: '<i class="far fa-star"></i>',
            size: "xs",
            showCaption: false,
            showClear: false,
          });
          btn.attr("data-offset", offset);
          $(".load-more-container").hide();
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
      },
    });
  });

  $("#product-rating-form").on("submit", function (e) {
    e.preventDefault();
    var submit_btn_html = $("#rating-submit-btn").html();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
      type: "POST",
      data: formdata,
      url: $(this).attr("action"),
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $("#rating-submit-btn")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });

          setTimeout(function () {
            location.reload();
          }, 300);
          $("#product-rating-form")[0].reset();
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }

        $("#rating-submit-btn").html(submit_btn_html).attr("disabled", false);
      },
    });
  });

  $("#product-edit-rating-form").on("submit", function (e) {
    e.preventDefault();
    var submit_btn_html = $("#edit-rating-submit-btn").html();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
      type: "POST",
      data: formdata,
      url: $(this).attr("action"),
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $("#edit-rating-submit-btn")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });
          $("#product-rating-form")[0].reset();
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
        $("#edit-rating-submit-btn")
          .html(submit_btn_html)
          .attr("disabled", false);
      },
    });
  });

  $("#delete_rating").on("click", function (e) {
    e.preventDefault();
    if (confirm("Are you sure want to Delete Rating ?")) {
      var rating_id = $(this).data("rating-id");
      $.ajax({
        type: "POST",
        data: {
          [csrfName]: csrfHash,
          rating_id: rating_id,
        },
        url: $(this).attr("href"),
        dataType: "json",
        success: function (result) {
          csrfName = result["csrfName"];
          csrfHash = result["csrfHash"];
          if (result.error == false) {
            Toast.fire({
              icon: "success",
              title: result.message,
            });
            setTimeout(function () {
              location.reload();
            }, 300);
            $("#delete_rating").parent().parent().parent().remove();
            $("#no_ratings").text(result.data.rating[0].no_of_rating);
          } else {
            Toast.fire({
              icon: "error",
              title: result.message,
            });
          }
        },
      });
    }
  });

  $(document).ready(function () {
    if ($(location).attr("href") == base_url + "compare") {
      if (localStorage.getItem("compare")) {
        var compare = localStorage.getItem("compare").length;
        compare = compare !== null ? JSON.parse(compare) : null;
        if (compare) {
          display_compare();
        }
      }
    }
  });

  $(document).on("click", ".compare", function (e) {
    e.preventDefault();
    var product_id = $(this).attr("data-product-id");
    var product_variant_id = $(this).attr("data-product-variant-id");
    var compare_item = {
      product_id: product_id.trim(),
      product_variant_id: product_variant_id.trim(),
    };
    var compare = localStorage.getItem("compare");
    Toast.fire({
      icon: "success",
      title: "products added to compare list",
    });
    compare = compare !== null ? JSON.parse(compare) : null;
    if (compare !== null && compare !== undefined) {
      if (compare.find((item) => item.product_id === product_id)) {
        Toast.fire({
          icon: "error",
          title: "This item is already in your comparison list !",
        });
        return;
      }
      compare.push(compare_item);
    } else {
      compare = [compare_item];
    }
    localStorage.setItem("compare", JSON.stringify(compare));
    var compare_count = compare.length ? compare.length : "";
    $("#compare_count").text(compare_count);
    if (compare !== null && compare_count <= 1) {
      Toast.fire({
        icon: "error",
        title: "Please select 1 more item compare",
      });
      return false;
    }
  });

  function shortDescriptionWordLimit(string, length = 32, dots = "...") {
    return string.length > length
      ? string.substring(0, length - dots.length) + dots
      : string;
  }

  function display_compare() {
    var compare = localStorage.getItem("compare");
    compare = localStorage.getItem("compare") !== null ? compare : null;
    $.ajax({
      type: "POST",
      url: base_url + "compare/add_to_compare",
      data: {
        product_id: compare,
        product_variant_id: compare,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        var compare_count = JSON.parse(compare).length
          ? JSON.parse(compare).length
          : 0;
        $("#compare_count").text(result.data.total);
        var comp = "";
        if (result.error == false) {
          if (compare !== null && compare_count > 0) {
            comp +=
              ' <table class="compare-table"><tbody><tr>' +
              '<th class="compare-field"></th>';
            $.each(result.data.product, function (i, e) {
              var variant_price =
                e.variants[0]["special_price"] > 0 &&
                  e.variants[0]["special_price"] != ""
                  ? e.variants[0]["special_price"]
                  : e.variants[0]["price"];
              var data_min = e.minimum_order_quantity
                ? e.minimum_order_quantity
                : 1;
              var data_step =
                e.minimum_order_quantity && e.quantity_step_size
                  ? e.quantity_step_size
                  : 1;
              var data_max = e.total_allowed_quantity
                ? e.total_allowed_quantity
                : 1;
              comp +=
                '<td class="compare-value">' +
                '<div class="wishlist-product-actions d-flex mb-1">' +
                '<div class="wishlist-product-remove">' +
                '<a class="align-middle gray-700 gray-500-hover remove-compare-item pointer" data-product-id="' +
                e.id +
                '"><ion-icon name="close-outline" class="vertical-align-middle"></ion-icon>remove' +
                "</a></div></div>" +
                '<div class="card product-card p-2 bg-light shadow-none text-start">' +
                '<div class="compare-product-img">' +
                '<img class="pic-1" src="' +
                e.image +
                '"></div><div class="card-body">' +
                '<h4 class="card-title">' +
                shortDescriptionWordLimit(e.name) +
                ' </h4><div class="d-flex flex-column"><div itemscope itemtype="https://schema.org/Product">';
              if (e.rating != "") {
                comp +=
                  '<div class="col-md-12 mb-1 product-rating-small" dir="ltr" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"><meta itemprop="ratingValue" content="' +
                  e.rating +
                  '" /><input type="text" class="kv-fa rating-loading" value="' +
                  e.rating +
                  '" data-size="sm" title="" readonly></div>';
              } else {
                comp +=
                  '<div class="col-md-12 mb-1 product-rating-small" dir="ltr"><input type="text" class="kv-fa rating-loading" value="' +
                  e.rating +
                  '" data-size="sm" title="" readonly></div>';
              }

              comp += "</div></div>";
              comp +=
                '<h4 class="card-price">' +
                currency +
                " " +
                (e.type == "simple_product"
                  ? e.variants[0]["price"]
                  : "Range: " +
                  e.min_max_price.max_special_price +
                  " - " +
                  e.min_max_price.max_price) +
                "</h4></div>";
              if (e.type == "simple_product") {
                var variant_id = e.variants[0]["id"];
                var modal = "";
              } else {
                var variant_id = "";
                var modal = "#quick-view";
              }
              comp +=
                '<a href="products/details/' +
                e.slug +
                '" class="btn btn-primary" data-product-id="' +
                '><span class="add-in-cart">View</span></a>' +
                "</div></div></a></td>";
            });
            comp += "</tr>";
            comp += "<tr>" + '<th class="compare-field">variants </th>';
            $.each(result.data.product, function (i, e) {
              var attribute_name = e.variants[0]["attr_name"].split(",");
              var attribute_values = e.variants[0]["variant_values"].split(",");
              if (e.type == "variable_product") {
                comp += '<td class="compare-value" data-title="variants">';
                for (var i = 0; i < attribute_name.length; i++) {
                  if (attribute_name[i] !== attribute_values[i]) {
                    comp +=
                      attribute_name[i] + " : " + attribute_values[i] + "<br>";
                  }
                }
                comp += "</td>";
              } else {
                comp +=
                  '<td class="compare-value" data-title="variants">-</td>';
              }
            });
            comp += "</tr>";
            comp += "<tr>" + '<th class="compare-field">Availability </th>';
            $.each(result.data.product, function (i, e) {
              comp +=
                '<td class="compare-value" data-title="Availability">' +
                (e.availability == "0"
                  ? (e.availability = "Out of Stock")
                  : (e.availability = "In Stock")) +
                "</td>";
            });
            comp += "</tr>";
            comp += "<tr>" + '<th class="compare-field">Made In </th>';
            $.each(result.data.product, function (i, e) {
              comp +=
                '<td class="compare-value" data-title="made in">' +
                (e.made_in ? e.made_in : "-") +
                "</td>";
            });
            comp += "</tr>";
            comp += "<tr>" + '<th class="compare-field">Warranty</th>';
            $.each(result.data.product, function (i, e) {
              comp +=
                '<td class="compare-value" data-title="warranty period">' +
                (e.warranty_period ? e.warranty_period : "-") +
                "</td>";
            });
            comp += "</tr>";
            comp += "<tr>" + '<th class="compare-field">Guarantee</th>';
            $.each(result.data.product, function (i, e) {
              comp +=
                '<td class="compare-value" data-title="warranty period">' +
                (e.guarantee_period ? e.guarantee_period : "-") +
                "</td>";
            });
            comp += "</tr>";
            comp += "<tr>" + '<th class="compare-field">Returnable</th>';
            $.each(result.data.product, function (i, e) {
              comp +=
                '<td class="compare-value" data-title="Returnable">' +
                (e.is_returnable == "1"
                  ? (e.is_returnable = "Yes")
                  : (e.is_returnable = "No")) +
                "</td>";
            });
            comp += "</tr>";
            comp += "<tr>" + '<th class="compare-field">Cancelable</th>';
            $.each(result.data.product, function (i, e) {
              comp +=
                '<td class="compare-value" data-title="cancelable">' +
                (e.is_cancelable == "1"
                  ? (e.is_cancelable = "Yes")
                  : (e.is_cancelable = "No")) +
                "</td>";
            });
            comp += "</tr>";
            comp += "</tbody>" + "</table>";
          } else {
            comp +=
              '<div class="text-center">' +
              '<h2 class="fw-semibold">Compare list is empty.</h2>' +
              '<p class="mb-2 text-secondary">No products added in the compare list. You must add some products to compare them.You will find a lot of interesting products on our "Shop" page.</p>' +
              '<div class="text-center mt-2"><a class="btn btn-primary" href="' +
              base_url +
              'products">return to shop</a></div></div>';
          }
          $("#compare-items").html(comp);
          $(".kv-fa").rating({
            theme: "krajee-fa",
            filledStar: '<i class="fas fa-star"></i>',
            emptyStar: '<i class="far fa-star"></i>',
            showClear: false,
            showCaption: false,
            size: "md",
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
  $(document).on("click", ".favremove", function (e) {
    e.preventDefault();

    location.reload();
  });
  //Remove from compare item.
  $(document).on("click", ".remove-compare-item", function (e) {
    e.preventDefault();
    var product_id = $(this).attr("data-product-id");
    if (confirm("Are you sure want to remove this?")) {
      var compare_count = $("#compare_count").text();
      compare_count--;

      if (compare_count < 1) {
        // Check if the comparison is empty before showing the message
        var compare = localStorage.getItem("compare");
        var html = "";
        compare = compare !== null ? JSON.parse(compare) : null;

        if (!compare || compare.length === 1) {
          html +=
            '<div class="text-center ">' +
            '<h2 class="fw-semibold">Compare list is empty.</h2>' +
            '<p class="mb-2 text-secondary">No products added in the compare list. You must add some products to compare them.You will find a lot of interesting products on our "Shop" page.</p>' +
            '<div class="text-center mt-2"><a class="btn btn-primary" href="' +
            base_url +
            "/products" +
            '">return to shop</a></div>' +
            "</div>";
          $("#comparison_empty_msg").html(html);
        }
      }

      $("#compare_count").text(compare_count);
      $(this).parent().parent().parent().parent().parent().remove();

      var compare = localStorage.getItem("compare");
      compare = compare !== null ? JSON.parse(compare) : null;
      if (compare) {
        var new_compare = compare.filter(function (item) {
          return item.product_id != product_id;
        });
        localStorage.setItem("compare", JSON.stringify(new_compare));
        display_compare();
      }
    }
  });

  // password show and hide
  $(".password-hide").hide();
  const passwordSections = document.querySelectorAll(".password-insert");

  // Loop through each password section
  passwordSections.forEach((section) => {
    const passwordInput = section.querySelector('input[type="password"]');
    const passwordShowIcon = section.querySelector(".password-show");
    const passwordHideIcon = section.querySelector(".password-hide");

    // Add click event listener to show password icon
    passwordShowIcon.addEventListener("click", () => {
      passwordInput.type = "text";
      passwordShowIcon.style.display = "none";
      passwordHideIcon.style.display = "inline-block";
    });

    // Add click event listener to hide password icon
    passwordHideIcon.addEventListener("click", () => {
      passwordInput.type = "password";
      passwordHideIcon.style.display = "none";
      passwordShowIcon.style.display = "inline-block";
    });
  });

  // login register section toggle
  $(".login-section").hide();
  $(".register-text").hide();
  $(".login-btn").on("click", () => {
    toggleSections(".registration-section", ".login-section");
    toogleText(".login-text", ".register-text");
  });

  $(".register-btn").on("click", () => {
    toggleSections(".login-section", ".registration-section");
    toogleText(".register-text", ".login-text");
  });

  function toggleSections(hideSection, showSection) {
    $(hideSection).hide();
    $(showSection).fadeIn(400);
  }

  function toogleText(hideText, showText) {
    $(hideText).hide();
    $(showText).show();
  }

  $(".checkbox-clicked").on("click", () => {
    if ($(".checkbox-clicked").is(":checked")) {
      $(".wishlist-bulk-action").addClass("wishlist-visible");
      $(".wishlist-btn").addClass("wishlist-btn-visible");
    } else {
      $(".wishlist-bulk-action").removeClass("wishlist-visible");
      $(".wishlist-btn").removeClass("wishlist-btn-visible");
    }
  });

  $(".wishlist-deselectall-action").hide();
  $(".wishlist-selectall-action").on("click", () => {
    $(".checkbox-clicked").prop("checked", true);
    $(".wishlist-deselectall-action").show();
    $(".wishlist-selectall-action").hide();
  });
  $(".wishlist-deselectall-action").on("click", () => {
    $(".checkbox-clicked").prop("checked", false);
    $(".wishlist-bulk-action").removeClass("wishlist-visible");
    $(".wishlist-btn").removeClass("wishlist-btn-visible");
    $(".wishlist-deselectall-action").hide();
    $(".wishlist-selectall-action").show();
  });

  // add address code
  $("#add-address-form").on("submit", function (e) {
    e.preventDefault();
    var t = new FormData(this);
    var currentUrl = window.location.href;
    t.append(csrfName, csrfHash);
    $.ajax({
      type: "POST",
      data: t,
      url: $(this).attr("action"),
      dataType: "json",
      cache: !1,
      contentType: !1,
      processData: !1,
      success: function (result) {
        (csrfName = result.csrfName), (csrfHash = result.csrfHash);
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          }),
            $("#add-address-form")[0].reset(),
            $("#address_list_table").bootstrapTable("refresh");
          $("#add-address-modal").modal("hide");
          if (currentUrl.includes("/checkout")) {
            $("#address-modal").modal("show");
          }
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
          $("#save-address-submit-btn").val("Save").attr("disabled", !1);
        }
      },
    });
  }),
    $("#send_forgot_password_otp_form").validate({
      rules: {
        mobile_number: {
          required: true,
          number: true,
          maxlength: 16,
        },
      },
    });
  $("#edit-address-form").validate({
    rules: {
      mobile: {
        required: true,
        number: true,
        maxlength: 16,
      },
    },
  });

  $("#login_form").validate({
    rules: {
      identity: {
        required: true,
        number: true,
        maxlength: 16,
      },
      email: {
        required: false,
        email: true,
      },
    },
  });

  $("#verify-otp-form").validate({
    rules: {
      otp: {
        required: true,
        number: true,
        maxlength: 16,
      },
    },
  });


  $("#edit_city").on("change", function (e, pincode) {
    e.preventDefault();
    var city_id = $(this).val();
    var value = $(this).val();
    if (value == 0 || value == "") {
      $(".edit_area").addClass("d-none");
      $("#edit_area").val("");
      $(".edit_pincode").addClass("d-none");
      $(".other_city").removeClass("d-none");
      $(".other_areas").removeClass("d-none");
      $(".other_pincode").removeClass("d-none");
    } else {
      $(".edit_area").removeClass("d-none");
      $(".edit_pincode").removeClass("d-none");
      $(".edit_city").removeClass("d-none");
      $(".other_city").addClass("d-none");
      $(".other_areas").addClass("d-none");
      $(".other_pincode").addClass("d-none");

      $.ajax({
        type: "POST",
        data: {
          city_id: $(this).val(),
          [csrfName]: csrfHash,
        },
        url: base_url + "my-account/get-zipcode",
        dataType: "json",
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          if (result.error == false) {
            var html = "";
            $.each(result.data, function (i, e) {
              var is_selected = e.zipcode == pincode ? "selected" : "";
              html += '<option value="0">Other</option>';
              html +=
                "<option value=" +
                e.zipcode +
                " " +
                is_selected +
                ">" +
                e.zipcode +
                "</option>";
            });
            $("#edit_pincode").html(html);
          } else {
            Toast.fire({
              icon: "error",
              title: result.message,
            });
            $("#edit_pincode").html("");
          }
        },
      });
    }
  });


  $("#city").on("change", function (e) {
    e.preventDefault();
    var value = $(this).val();
    if (value == 0 || value == -1) {
      $(".city_name").removeClass("d-none");
      $(".area_name").removeClass("d-none");
      $(".pincode_name").removeClass("d-none");
      $(".area").addClass("d-none");
      $(".pincode").addClass("d-none");
    } else {
      $("#edit_pincode").empty();
      $(".city").trigger("change");
      $(".city").removeClass("d-none");
      $(".area").removeClass("d-none");
      $(".pincode").removeClass("d-none");
      $(".city_name").addClass("d-none");
      $(".area_name").addClass("d-none");
      $(".pincode_name").addClass("d-none");
      $.ajax({
        type: "POST",
        data: {
          city_id: $(this).val(),
          [csrfName]: csrfHash,
        },
        url: base_url + "my-account/get-zipcode",
        dataType: "json",
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          if (result.error == false) {
            var html = "";
            html += '<option value="">--Select Zipcode--</option>';
            html += '<option value="0">Other</option>';
            $.each(result.data, function (i, e) {
              html +=
                "<option value=" + e.zipcode + ">" + e.zipcode + "</option>";
            });

            $("#pincode").html(html);
          } else {
            var html = "";
            html += '<option value="">--Select Zipcode--</option>';
            html += '<option value="0">Other</option>';

            $("#pincode").html(html);
          }
        },
      });
    }
  });

  $("#pincode").on("change", function (e) {
    e.preventDefault();
    var value = $(this).val();
    if (value == 0 || value == -1) {
      $(".pincode_name").removeClass("d-none");
    } else {
      $(".pincode_name").addClass("d-none");
    }
  });
  $("#edit_pincode").on("change", function (e) {
    e.preventDefault();
    var value = $(this).val();
    if (value == 0 || value == -1) {
      $(".other_pincode").removeClass("d-none");
    } else {
      $(".other_pincode").addClass("d-none");
    }
  });

  $("#edit-address-form").on("submit", function (e) {
    e.preventDefault();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
      type: "POST",
      data: formdata,
      url: $(this).attr("action"),
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $("#edit-address-submit-btn").val("Please Wait...");
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });
          $("#edit-address-form")[0].reset();
          $("#address_list_table").bootstrapTable("refresh");
          setTimeout(function () {
            $("#address-modal").modal("hide");
          }, 2000);
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
        $("#edit-address-submit-btn").val("Save");
      },
    });
  });
  $(document).on("click", ".delete-address", function (e) {
    e.preventDefault();
    if (confirm("Are you sure ? You want to delete this address?")) {
      $.ajax({
        type: "POST",
        data: {
          id: $(this).data("id"),
          [csrfName]: csrfHash,
        },
        url: base_url + "my-account/delete-address",
        dataType: "json",
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          if (result.error == false) {
            $("#address_list_table").bootstrapTable("refresh");
          } else {
            Toast.fire({
              icon: "error",
              title: result.message,
            });
          }
        },
      });
    }
  });

  $(document).on("click", ".default-address", function (e) {
    e.preventDefault();
    if (confirm("Are you sure ? You want to set this address as default?")) {
      $.ajax({
        type: "POST",
        data: {
          id: $(this).data("id"),
          [csrfName]: csrfHash,
        },
        url: base_url + "my-account/set-default-address",
        dataType: "json",
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          if (result.error == false) {
            $("#address_list_table").bootstrapTable("refresh");
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
  });

  // swipers
  var swiper = new Swiper(".mySwiper-thumb", {
    spaceBetween: 10,
    direction: "vertical",
    mousewheel: true,
    slidesPerView: 4,
    watchSlidesProgress: true,
  });
  var swiper2 = new Swiper(".mySwiper-preview", {
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    thumbs: {
      swiper: swiper,
    },
  });
  var swiper = new Swiper(".mySwiper", {
    spaceBetween: 0,
    centeredSlides: true,
    loop: true,
    autoHeight: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    pagination: {
      el: ".swiper-pagination",
      dynamicBullets: true,
    },
  });
  var swiper2 = new Swiper(".swiperForCategories", {
    slidesPerView: 1,
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      300: {
        slidesPerView: 2,
        spaceBetweenSlides: 10,
      },
      350: {
        slidesPerView: 2,
        spaceBetweenSlides: 10,
      },
      400: {
        slidesPerView: 3,
        spaceBetweenSlides: 10,
      },
      499: {
        slidesPerView: 4,
        spaceBetweenSlides: 10,
      },
      550: {
        slidesPerView: 5,
        spaceBetweenSlides: 10,
      },
      600: {
        slidesPerView: 5,
        spaceBetweenSlides: 10,
      },
      700: {
        slidesPerView: 5,
        spaceBetweenSlides: 10,
      },
      800: {
        slidesPerView: 6,
        spaceBetweenSlides: 10,
      },
      999: {
        slidesPerView: 8,
        spaceBetweenSlides: 10,
      },
      991: {
        slidesPerView: 8,
        spaceBetween: 10,
      },
      1024: {
        slidesPerView: 8,
        spaceBetween: 10,
      },
      1440: {
        slidesPerView: 8,
        spaceBetween: 10,
      },
    },
  });
  var swiper2 = new Swiper(".swiperForBrands", {
    slidesPerView: 1,
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      300: {
        slidesPerView: 2,
        spaceBetweenSlides: 10,
      },
      350: {
        slidesPerView: 2,
        spaceBetweenSlides: 10,
      },
      400: {
        slidesPerView: 3,
        spaceBetweenSlides: 10,
      },
      499: {
        slidesPerView: 4,
        spaceBetweenSlides: 10,
      },
      550: {
        slidesPerView: 5,
        spaceBetweenSlides: 10,
      },
      600: {
        slidesPerView: 5,
        spaceBetweenSlides: 10,
      },
      700: {
        slidesPerView: 5,
        spaceBetweenSlides: 10,
      },
      800: {
        slidesPerView: 6,
        spaceBetweenSlides: 10,
      },
      999: {
        slidesPerView: 8,
        spaceBetweenSlides: 10,
      },
      991: {
        slidesPerView: 8,
        spaceBetween: 10,
      },
      1024: {
        slidesPerView: 8,
        spaceBetween: 10,
      },
      1440: {
        slidesPerView: 8,
        spaceBetween: 10,
      },
    },
  });
  var swiper3 = new Swiper(".mySwiper3", {
    slidesPerView: 2,
    spaceBetween: 10,
    lazyLoading: true,
    lazyLoadingInPrevNextAmount: 0,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 10,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 10,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 10,
      },
      1440: {
        slidesPerView: 5,
        spaceBetween: 10,
      },
    },
  });
  var swiper4 = new Swiper(".mySwiper4", {
    slidesPerView: 1,
    spaceBetween: 10,
    lazyLoading: true,
    lazyLoadingInPrevNextAmount: 0,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1440: {
        slidesPerView: 4,
        spaceBetween: 20,
      },
    },
  });
  var swiper5 = new Swiper(".mySwiper5", {
    slidesPerView: 1,
    spaceBetween: 10,
    lazyLoading: true,
    lazyLoadingInPrevNextAmount: 0,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      640: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1440: {
        slidesPerView: 2,
        spaceBetween: 10,
      },
    },
  });

  var swiper6 = new Swiper(".mySwiper6", {
    slidesPerView: 1,
    spaceBetween: 10,
    lazyLoading: true,
    lazyLoadingInPrevNextAmount: 0,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 10,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 10,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 10,
      },
      1440: {
        slidesPerView: 4,
        spaceBetween: 10,
      },
    },
  });

  var swiper_quickview = new Swiper(".mySwiper-quickview", {
    slidesPerView: 1,
    spaceBetween: 0,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  var swiperFilterSection = new Swiper(".mySwiper-filtersection", {
    spaceBetween: 10,
    freeMode: true,
    breakpoints: {
      320: {
        slidesPerView: 3,
      },
      640: {
        slidesPerView: 3,
      },
      1024: {
        slidesPerView: 4,
      },
      1440: {
        slidesPerView: 4,
      },
    },
  });

  var swiper = new Swiper(".mySwiper-productview", {
    direction: "vertical",
    slidesPerView: 5,
    spaceBetween: 0,
    mousewheel: true,
  });

  //Hide and show
  $(".expand").hide();
  $(".show-less").hide();
  $(".show-btn").on("click", function () {
    $(".expand").show();
    $(".show-more").hide();
    $(".show-less").show();
  });
  $(".hide-btn").on("click", function () {
    $(".expand").hide();
    $(".show-less").hide();
    $(".show-more").show();
  });

  $(".get_e_time").each((val, element) => {
    get_flash_sale_timer(element);
  });

  function get_flash_sale_timer(element) {
    const end_date = $(element).text();
    const elementId = $(element).data("value");
    // Update the count down every 1 second
    var x = setInterval(function () {
      var timer = " ";
      var countDownDate = new Date(end_date).getTime();
      var now = new Date().getTime();

      // Find the distance between now and the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
      );
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      timer +=
        '<ul class="p-0  default-cursor">' +
        '<li><span id="days">' +
        days +
        "</span>days</li>" +
        '<li><span id="hours">' +
        hours +
        "</span>Hr</li>" +
        '<li><span id="minutes">' +
        minutes +
        "</span>Min</li>" +
        ' <li><span id="seconds">' +
        seconds +
        "</span>Sc</li>" +
        "</ul>";

      $(`#timer-${elementId}`).html(timer);

      $(`#timer-${elementId}`).addClass("badge-success");

      // If the count down is over, write some text
      if (distance < 0) {
        clearInterval(x);
        $(`#timer-${elementId}`).text("Sale is Expired");
        $(`#timer-${elementId}`).addClass("badge-warning");
      }
    }, 1000);
  }

  // star rating
  $(".kv-ltr-theme-svg-star").rating({
    hoverOnClear: false,
    theme: "krajee-svg",
  });
  $(document).on("click", ".clear-rating", (e) => {
    e.preventDefault();
    $(".star-rating").rating("reset");
  });

  $(document).on("click", ".swatch", function () {
    $(this).addClass("border-active").siblings().removeClass("border-active");
  });

  $("#product-filters-mobile").on("click", ".nav-link", function (e) {
    e.preventDefault();
    $(".nav-link").removeClass("active");
    $(this).addClass("active");
  });

  $(document).on("submit", "#add-faqs", function (e) {
    e.preventDefault();

    // ⭐ REQUIRE QUESTION FIELD
    let question = $.trim($("#question").val());
    if (question === "") {
      Toast.fire({
        icon: "error",
        title: "Please enter a question.",
      });
      return false; // Stop form submit
    }

    var t = new FormData(this);
    t.append(csrfName, csrfHash);

    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      dataType: "json",
      data: t,
      processData: false,
      contentType: false,
      success: function (e) {
        csrfName = e.csrfName;
        csrfHash = e.csrfHash;

        if (e.error == 0) {
          Toast.fire({
            icon: "success",
            title: e.message,
          });
          $("#add-faqs")[0].reset();
        } else {
          Toast.fire({
            icon: "error",
            title: e.message,
          });
        }

        setTimeout(function () {
          location.reload();
        }, 1000);
      },
    });
  });

  /* reduce the height of sticky nav when sticks to top */
  var searchNav = $(".search-nav");
  var mainContent = $("main");

  $(window).scroll(function () {
    var scrollTop = $(window).scrollTop();
    var topNav = $(".top-nav").height();

    if (scrollTop >= topNav && scrollTop >= mainContent.offset().top) {
      $(".nav-outer").addClass("nav-sticky");
    } else {
      $(".nav-outer").removeClass("nav-sticky");
    }
  });

  $(window).bind("mousewheel", function (event) {
    var isScrollingWithinMain = $(event.target).closest(mainContent).length > 0;

    if (isScrollingWithinMain) {
      if (event.originalEvent.wheelDelta >= 0) {
        $(searchNav).addClass("nav-scroll-up").slideDown(250);
      } else {
        $(searchNav).removeClass("nav-scroll-up").slideUp(250);
      }
    }
  });

  var lastYPosition = window.pageYOffset;
  $(document).on("touchmove", function (e) {
    var currentYPosition = window.pageYOffset;
    if (currentYPosition > lastYPosition) {
      // Scrolled down
      $(searchNav).removeClass("nav-scroll-up");
    } else {
      // Scrolled up
      $(searchNav).addClass("nav-scroll-up");
    }
    lastYPosition = currentYPosition;
  });

  $("#modal-add-to-cart-button").on("click", function (e) {
    e.preventDefault();
    var title = $(".modal-product-title").text();
    var qty = $("#modal-product-quantity").val();
    var description = $("#modal-product-short-description").text();
    var image = $(".swiper-slide-quickview img").attr("src");
    var price = $("#modal-product-price").text();
    var price_without_currency = price.match(/\d+/)[0];
    var price_with_qty = qty * price_without_currency;
    $("#quick-view").data("data-product-id", $(this).data("productId"));
    var product_variant_id = $(this).attr("data-product-variant-id");
    var product_type = $("#modal-product-type").text();
    var min = $(this).attr("data-min");
    var max = $(this).attr("data-max");
    var product_type = $(this).attr("data-type");
    var step = $(this).attr("data-step");
    var btn = $(this);
    var btn_html = $(this).html();
    var is_buy_now = 0;
    if ($(this).hasClass("buy_now")) {
      is_buy_now = 1;
    }
    if (product_type != "digital_product") {
      if (!product_variant_id) {
        Toast.fire({
          icon: "error",
          title: "Please select variant",
        });
        return;
      }
    }
    $.ajax({
      type: "POST",
      url: base_url + "cart/manage",
      data: {
        product_variant_id: product_variant_id,
        qty: $("#modal-product-quantity").val(),
        price: price_with_qty,
        is_buy_now,
        is_saved_for_later: false,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      beforeSend: function () {
        btn
          .html("Please Wait")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        btn.html(btn_html).attr("disabled", false);
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });
          $(".cart-count").text(result.data.cart_count);
          var html = "";

          display_cart(result.data.items);
        } else {
          if (is_loggedin == 0) {
            Toast.fire({
              icon: "success",
              title: "Item added to cart",
            });
            var cart_item = {
              product_variant_id: product_variant_id.trim(),
              title: title,
              description: description,
              qty: qty,
              image: image,
              price: price_with_qty,
              min: min,
              max: max,
              step: step,
            };
            var cart = localStorage.getItem("cart");
            cart =
              localStorage.getItem("cart") !== null ? JSON.parse(cart) : null;
            if (cart !== null && cart !== undefined) {
              let existingItemIndex = cart.findIndex(
                (item) =>
                  item.product_variant_id === cart_item.product_variant_id,
              );
              if (existingItemIndex !== -1) {
                Toast.fire({
                  icon: "error",
                  title: "Item Already Added in Cart",
                });
              } else {
                cart.push(cart_item);
              }
            } else {
              cart = [cart_item];
            }
            display_cart(cart);
            localStorage.setItem("cart", JSON.stringify(cart));
            $(".cart-count").text(cart.length);
            return;
          }
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
      },
    });
  });
  $(document).ready(function () {
    // Function to update cart visibility
    function updateCartVisibility() {
      const cartItems = $("#cart-item-sidebar .cart-modal-card").length;
      if (cartItems > 0) {
        $(".cart-modal-checkout-section").show();
      } else {
        $(".cart-modal-checkout-section").hide();
      }
    }

    // Function to initialize cart count from localStorage
    function initializeCartCount() {
      if (typeof is_loggedin !== "undefined" && is_loggedin == 0) {
        var cart = localStorage.getItem("cart");
        if (cart !== null) {
          cart = JSON.parse(cart);
          $(".cart-count").text(cart.length);
          display_cart(cart); // Display the cart items
        } else {
          $(".cart-count").text(0);
          display_cart([]); // Display empty cart
        }
      }
    }

    // Initial checks when the document is ready
    updateCartVisibility();
    initializeCartCount();

    // Handle Add to Cart button click
    $(".add-to-cart-button").click(function (e) {
      e.preventDefault(); // Prevent default form submission

      // Assuming data is extracted from your form or button attributes
      const productId = $(this).data("product-id");
      const quantity = 1; // Example, adjust as needed

      // Ajax request to add the item to cart
      $.ajax({
        url: base_url + "cart/manage",
        method: "POST",
        data: {
          product_variant_id: productId,
          qty: quantity,
          [csrfName]: csrfHash,
        },
        success: function (response) {
          csrfName = response["csrfName"];
          csrfHash = response["csrfHash"];

          // Handle response data and update the UI
          if (response.error == false) {
            // User is logged in - use server response
            Toast.fire({
              icon: "success",
              title: response.message,
            });
            $(".cart-count").text(response.data.cart_count);

            // Update cart sidebar with new item
            const newItemHtml = `
                            <div class="cart-modal-card card max-width-540px">
                                <!-- Your card HTML structure for new item -->
                            </div>`;
            $("#cart-item-sidebar").append(newItemHtml);

            // Update cart visibility
            updateCartVisibility();
          } else {
            // User is not logged in - handle with localStorage
            if (response.data && response.data.is_logged_in == 0) {
              Toast.fire({
                icon: "success",
                title: "Item added to cart",
              });

              // Create cart item for localStorage
              var cart_item = {
                product_variant_id: productId.toString().trim(),
                qty: quantity,
                title:
                  $(this)
                    .closest(".product-card")
                    .find(".product-title, .card-title")
                    .text()
                    .trim() || "Product",
                description:
                  $(this)
                    .closest(".product-card")
                    .find(".product-description, .card-text")
                    .text()
                    .trim() || "",
                image:
                  $(this).closest(".product-card").find("img").attr("src") ||
                  "",
                price: 0, // You may need to extract this from the product card
                special_price: 0, // You may need to extract this from the product card
                min: 1,
                max: 999,
                step: 1,
              };

              var cart = localStorage.getItem("cart");
              cart =
                localStorage.getItem("cart") !== null ? JSON.parse(cart) : null;

              if (cart !== null && cart !== undefined) {
                let existingItemIndex = cart.findIndex(
                  (item) =>
                    item.product_variant_id === cart_item.product_variant_id,
                );
                if (existingItemIndex !== -1) {
                  Toast.fire({
                    icon: "error",
                    title: "Item Already Added in Cart",
                  });
                  return;
                } else {
                  cart.push(cart_item);
                }
              } else {
                cart = [cart_item];
              }

              localStorage.setItem("cart", JSON.stringify(cart));
              $(".cart-count").text(cart.length);

              // Update cart visibility
              updateCartVisibility();
            } else {
              Toast.fire({
                icon: "error",
                title: response.message,
              });
            }
          }
        },
        error: function (xhr, status, error) {
          console.error("Error adding to cart:", error);
          Toast.fire({
            icon: "error",
            title: "Error adding item to cart",
          });
        },
      });
    });

    // Handle removal of cart items
    $(document).on("click", ".remove-product", function (e) {
      e.preventDefault();

      const productId = $(this).data("id");

      if (typeof is_loggedin !== "undefined" && is_loggedin == 0) {
        // Handle localStorage cart removal
        var cart = localStorage.getItem("cart");
        if (cart !== null) {
          cart = JSON.parse(cart);

          cart = cart.filter((item) => {
            const variantId = item.product_variant_id ?? item.variant_id;
            return variantId !== productId.toString();
          });

          localStorage.setItem("cart", JSON.stringify(cart));
          $(".cart-count").text(cart.length);
          display_cart(cart);
          updateCartVisibility();
        }
      } else {
        // Handle server-side cart removal
        $.ajax({
          url: "<?= base_url('cart/remove_item') ?>",
          method: "POST",
          data: {
            product_variant_id: productId,
            [csrfName]: csrfHash,
          },
          success: function (response) {
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
            $(`.remove-product[data-id="${productId}"]`)
              .closest(".cart-modal-card")
              .remove();
            updateCartVisibility();
          },
          error: function (xhr, status, error) {
            console.error("Error removing item:", error);
          },
        });
      }
    });

    // Handle quantity changes in cart modal
    $(document).on("change", ".input-field-cart-modal", function () {
      const productId = $(this).data("id");
      const newQty = parseInt($(this).val());

      if (typeof is_loggedin !== "undefined" && is_loggedin == 0) {
        // Handle localStorage cart quantity update
        var cart = localStorage.getItem("cart");
        if (cart !== null) {
          cart = JSON.parse(cart);
          const itemIndex = cart.findIndex(
            (item) => item.product_variant_id === productId.toString().trim(),
          );
          if (itemIndex !== -1) {
            cart[itemIndex].qty = newQty;
            localStorage.setItem("cart", JSON.stringify(cart));
            updateSubtotal();
          }
        }
      }
    });
  });

  $(document).on("change", ".brand", function (e) {
    e.preventDefault();
    var t = $(this).data("value");
    custom_url = setUrlParameter(custom_url, "brand", t);

    const brand_name = getUrlParameter("brand");
    var brands = $('[data-value="' + brand_name + '"]');
    $('[data-value="' + brand_name + '"]').attr("checked", true);
    var gp = $(brands).siblings();
    $(gp).removeClass("selected-brand");
  }),
    $(".quickview-trigger").on("click", function () {
      var productId = $(this).closest(".product-card").data("product-id");
      var url = base_url + "products/get-details/" + productId;
      let brand = "";

      $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (data) {
          $(".modal-product-title").text(data.name);
          $("#modal-product-short-description").text(data.short_description);
          $("#modal-product-rating").rating("update", data.rating);
          $("#modal-product-no-of-ratings").text(data.no_of_ratings);
          $("#modal-product-price").text(
            currency + " " + data.variants[0].special_price,
          );
          $("#modal-category_name").text(data.category_name);
          $("#modal-product-type").text(data.type);
          $("#modal-product-quantity").attr("data-id", data.id);
          $("#modal-product-quantity").attr(
            "data-price",
            data.min_max_price.special_price,
          );
          brand +=
            '<a href="' +
            base_url +
            "products?brand=" +
            data.brand_slug +
            '">' +
            data.brand +
            "</a>";
          $("#modal-product-brand").html(brand);
          let whatsappNumber = $("#whatsappNumber").val();
          $("#whatsappButton").on("click", function () {
            var whatsappLink =
              "https://api.whatsapp.com/send?phone=" +
              whatsappNumber +
              "&text=Hello, I Seen " +
              data.name +
              " In Your Website And I Want to Buy This";
            window.open(whatsappLink, "_blank");
          });

          let variant_id = $("#modal-variants-id").text(data.variants[0].id);

          var product_detail_URL = base_url + "products/details/";
          var detailURL = product_detail_URL + data.slug;
          $("#product-link").attr("href", detailURL);

          var product_category_URL = "products/category/";
          var categoryURL = product_category_URL + data.category_name;
          $("#category-link").attr("href", categoryURL);

          if (data.type != "variable_product") {
            $("#modal-add-to-cart-button").attr(
              "data-product-variant-id",
              data.variants[0].id,
            );
            $("#buy_now_btn_model").attr(
              "data-product-variant-id",
              data.variants[0].id,
            );
          }

          var variant_attributes = "";
          var variants = "";
          var total_images = 1;
          var variant_attributes = "";
          var is_image = 0;
          var is_color = 0;
          $.each(data.variant_attributes, function (i, e) {
            var attribute_ids = e.ids.split(",");
            var attribute_values = e.values.split(",");
            var swatche_types = e.swatche_type.split(",");
            var swatche_values = e.swatche_value.split(",");
            var style =
              "<style> .product-page-details .btn-group>.active { border: 1px solid black;}</style>";
            variant_attributes +=
              '<ul class="d-flex gap-2 flex-wrap align-items-center list-unstyled quickview-variant-sec"><li class="fw-semibold">' +
              e.attr_name +
              " : </li>";
            $.each(attribute_ids, function (j, id) {
              var color_code = "";
              if (swatche_types[j] == "1") {
                is_color = 1;
                color_code =
                  'style="background-color:' + swatche_values[j] + '";';
                variant_attributes +=
                  "<style> .product-page-details 1 .btn-group>.active { border: 1px solid black;}</style>" +
                  '<li class="color-swatch swatch"><label class="product-color m-0"' +
                  color_code +
                  ">" +
                  '<input type="radio" name="' +
                  e.attr_name +
                  '" value="' +
                  id +
                  '" class="attributes filter-input modal-product-attributes" autocomplete="off">' +
                  "</label></li>";
              } else if (swatche_types[j] == "2") {
                is_image = 1;
                variant_attributes +=
                  "<style> .product-page-details 2 .btn-group>.active { color: #000000; border: 1px solid black;}</style>" +
                  '<li class="image-swatch swatch"><label class="color-swatch-img m-0">' +
                  '<img class="swatche-image" src="' +
                  swatche_values[j] +
                  '">' +
                  '<input type="radio" name="' +
                  e.attr_name +
                  '" value="' +
                  id +
                  '" class="attributes filter-input modal-product-attributes" autocomplete="off">' +
                  "</label></li>";
              } else {
                variant_attributes +=
                  '<li class="default-swatch swatch"><label class="m-0 position-relative">' +
                  '<input type="radio" name="' +
                  e.attr_name +
                  '" value="' +
                  id +
                  '" class="attributes filter-input modal-product-attributes" autocomplete="off">' +
                  attribute_values[j] +
                  "</label>" +
                  "</li>";
              }
            });
            variant_attributes += "</li></ul>";
          });
          var variants = "";
          total_images = 1;
          $.each(data.variants, function (i, e) {
            variants +=
              '<input type="hidden" class="modal-product-variants" data-image-index="' +
              total_images +
              '" name="variants_ids" data-name="' +
              data.name +
              '" value="' +
              e.variant_ids +
              '" data-id="' +
              e.id +
              '" data-price="' +
              e.price +
              '" data-special_price="' +
              e.special_price +
              '">';
            total_images += e.images.length;
          });
          $("#modal-product-variant-attributes").html(variant_attributes);
          $("#modal-product-variants-div").html(variants);

          if (
            data.minimum_order_quantity != 1 &&
            data.minimum_order_quantity != "" &&
            data.minimum_order_quantity != "undefined"
          ) {
            $(".plus-minus-input").attr({
              "data-min": data.minimum_order_quantity, // values (or variables) here
            });
            $(".minus-btn").attr({
              "data-min": data.minimum_order_quantity, // values (or variables) here
            });
            $("#modal-add-to-cart-button").attr({
              "data-min": data.minimum_order_quantity, // values (or variables) here
            });
            $("#buy_now_btn_model").attr({
              "data-min": data.minimum_order_quantity, // values (or variables) here
            });
          } else {
            $(".plus-minus-input").attr({
              "data-min": 1, // values (or variables) here
            });
            $(".minus-btn").attr({
              "data-min": 1, // values (or variables) here
            });
            $("#modal-add-to-cart-button").attr({
              "data-min": 1, // values (or variables) here
            });
            $("#buy_now_btn_model").attr({
              "data-min": 1, // values (or variables) here
            });
          }
          if (
            data.quantity_step_size != 1 &&
            data.quantity_step_size != "" &&
            data.quantity_step_size != "undefined"
          ) {
            $(".plus-minus-input").attr({
              "data-step": data.quantity_step_size, // values (or variables) here
            });
            $(".minus-btn").attr({
              "data-step": data.quantity_step_size, // values (or variables) here
            });
            $(".plus-btn").attr({
              "data-step": data.quantity_step_size, // values (or variables) here
            });
            $("#modal-add-to-cart-button").attr({
              "data-step": data.quantity_step_size, // values (or variables) here
            });
            $("#buy_now_btn_model").attr({
              "data-step": data.quantity_step_size, // values (or variables) here
            });
          } else {
            $(".plus-minus-input").attr({
              "data-step": 1, // values (or variables) here
            });
            $(".minus-btn").attr({
              "data-step": 1, // values (or variables) here
            });
            $(".plus-btn").attr({
              "data-step": 1, // values (or variables) here
            });
            $("#modal-add-to-cart-button").attr({
              "data-step": 1, // values (or variables) here
            });
            $("#buy_now_btn_model").attr({
              "data-step": 1, // values (or variables) here
            });
          }
          if (
            data.total_allowed_quantity != "" &&
            data.total_allowed_quantity != "undefined" &&
            data.total_allowed_quantity != null
          ) {
            $(".plus-minus-input").attr({
              "data-max": data.total_allowed_quantity, // values (or variables) here
            });
            $(".plus-btn").attr({
              "data-max": data.total_allowed_quantity, // values (or variables) here
            });
            $("#modal-add-to-cart-button").attr({
              "data-max": data.total_allowed_quantity, // values (or variables) here
            });
            $("#buy_now_btn_model").attr({
              "data-max": data.total_allowed_quantity, // values (or variables) here
            });
          } else {
            $(".plus-minus-input").attr({
              "data-max": 1, // values (or variables) here
            });
            $(".plus-btn").attr({
              "data-max": 1, // values (or variables) here
            });
            $("#modal-add-to-cart-button").attr({
              "data-max": 1, // values (or variables) here
            });
            $("#buy_now_btn_model").attr({
              "data-max": 1, // values (or variables) here
            });
          }

          $("#modal-product-quantity").val(data.minimum_order_quantity);

          // Show the quickview modal
          $("#quickview").modal("show");
          if (!quickViewgalleryTop) {
            quickViewgalleryTop = new Swiper(".mySwiper-quickview", {
              spaceBetween: 0,
              navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
              },
            });
          } else {
            // Clear existing slides
            quickViewgalleryTop.removeAllSlides();

            // Add new slides
            var main_images = $(
              '<div class="swiper-slide swiper-slide-quickview text-center">' +
              '<img src="' +
              data.image_md +
              '">' +
              "</div>",
            );
            $(".swiper-wrapper-main").append(main_images);

            var variant_images_md = data.variants.map(function (value, index) {
              return value.images_md;
            });

            $.each(variant_images_md, function (i, images) {
              if (images != null && images != "") {
                $.each(images, function (i, url) {
                  var main_images = $(
                    '<div class="swiper-slide swiper-slide-quickview text-center">' +
                    '<img src="' +
                    url +
                    '">' +
                    "</div>",
                  );
                  $(".swiper-wrapper-main").append(main_images);
                });
              }
            });

            $.each(data.other_images_md, function (i, url) {
              if (url != null && url != "") {
                // Check if the URL is not blank
                total_images++;
                var main_images = $(
                  '<div class="swiper-slide swiper-slide-quickview text-center">' +
                  '<img src="' +
                  url +
                  '">' +
                  "</div>",
                );
                $(".swiper-wrapper-main").append(main_images);
              }
            });

            // Update the Swiper instance
            quickViewgalleryTop.update();
          }

          quickViewgalleryTop = new Swiper(".mySwiper-quickview", {
            spaceBetween: 0,
            navigation: {
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev",
            },
          });

          quickViewgalleryTop.removeAllSlides();

          var main_images = $(
            '<div class="swiper-slide swiper-slide-quickview text-center">' +
            '<img src="' +
            data.image_md +
            '">' +
            "</div>",
          );
          $(".swiper-wrapper-main").append(main_images);

          var variant_images_md = data.variants.map(function (value, index) {
            return value.images_md;
          });

          $.each(variant_images_md, function (i, images) {
            if (images != null && images != "") {
              $.each(images, function (i, url) {
                var main_images = $(
                  '<div class="swiper-slide swiper-slide-quickview text-center">' +
                  '<img src="' +
                  url +
                  '">' +
                  "</div>",
                );
                $(".swiper-wrapper-main").append(main_images);
              });
            }
          });

          $.each(data.other_images_md, function (i, url) {
            if (url != null && url != "") {
              // Check if the URL is not blank
              total_images++;
              var main_images = $(
                '<div class="swiper-slide swiper-slide-quickview text-center">' +
                '<img src="' +
                url +
                '">' +
                "</div>",
              );
              $(".swiper-wrapper-main").append(main_images);
            }
          });
          if (main_images.length > 1) {
            quickViewgalleryTop.addSlide(1, main_images);
          }
        },
      });
    });

  $("#reload").on("click", function (e) {
    window.location = window.location.href.split("?")[0];
  });

  // Product Details Page.
  $(".attributes").on("change", function (e) {
    e.preventDefault();
    var selected_attributes = [];
    var attributes_length = "";
    var price = "";
    var is_variant_available = false;
    var variant = [];
    var prices = [];
    var variant_prices = [];
    var variants = [];
    var variant_ids = [];
    var image_indexes = [];
    var selected_image_index;
    $(".variants").each(function () {
      prices = {
        price: $(this).data("price"),
        special_price: $(this).data("special_price"),
      };
      variant_ids.push($(this).data("id"));
      variant_prices.push(prices);
      variant = $(this).val().split(",");
      variants.push(variant);
      image_indexes.push($(this).data("image-index"));
    });

    attributes_length = variant.length;

    $(".attributes").each(function (i, e) {
      if ($(this).prop("checked")) {
        selected_attributes.push($(this).val());
        if (selected_attributes.length == attributes_length) {
          /* compare the arrays */
          prices = [];
          var selected_variant_id = "";
          $.each(variants, function (i, e) {
            if (arrays_equal(selected_attributes, e)) {
              is_variant_available = true;
              prices.push(variant_prices[i]);
              selected_variant_id = variant_ids[i];
              selected_image_index = image_indexes[i];
            }
          });
          if (is_variant_available) {
            $("#add_cart").attr("data-product-variant-id", selected_variant_id);
            $("#buy_it_now").attr(
              "data-product-variant-id",
              selected_variant_id,
            );
            galleryTop.slideTo(selected_image_index, 500, false);
            if (
              prices[0].special_price < prices[0].price &&
              prices[0].special_price != 0
            ) {
              price = prices[0].special_price;
              $("#add_cart").attr("data-product-price", price);
              $("#buy_it_now").attr("data-product-price", price);
              $("#price").html(currency + " " + price.toLocaleString());
              $("#striped-price").html(currency + " " + prices[0].price);
              $("#striped-price-div").show();
              $("#add_cart").removeAttr("disabled");
            } else {
              price = prices[0].price;
              $("#add_cart").attr("data-product-price", price);
              $("#buy_it_now").attr("data-product-price", price);
              $("#price").html(currency + " " + price.toLocaleString());
              $("#striped-price-div").hide();
              $("#add_cart").removeAttr("disabled");
            }
          } else {
            price =
              '<small class="text-danger h5">No Variant available!</small>';
            $("#price").html(price.toLocaleString());
            $("#striped-price-div").hide();
            $("#striped-price").html("");
            $("#add_cart").attr("disabled", "true");
          }
        }
      }
    });
    variants = "";
  });

  var galleryTop = new Swiper(".gallery-top-1", {
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  //Modal Product Variant Selection Event
  $(document).on("change", ".modal-product-attributes", function (e) {
    e.preventDefault();
    var selected_attributes = [];
    var attributes_length = "";
    var price = "";
    var is_variant_available = false;
    var variant = [];
    var prices = [];
    var variant_prices = [];
    var variants = [];
    var variant_ids = [];
    var image_indexes = [];
    var selected_image_index;
    $(".modal-product-variants").each(function () {
      prices = {
        price: $(this).data("price"),
        special_price: $(this).data("special_price"),
      };
      variant_ids.push($(this).data("id"));
      variant_prices.push(prices);
      variant = $(this).val().split(",");
      variants.push(variant);
      image_indexes.push($(this).data("image-index"));
    });
    attributes_length = variant.length;
    $(".modal-product-attributes").each(function () {
      if ($(this).prop("checked")) {
        selected_attributes.push($(this).val());

        if (selected_attributes.length == attributes_length) {
          /* compare the arrays */
          prices = [];
          var selected_variant_id = "";
          $.each(variants, function (i, e) {
            if (arrays_equal(selected_attributes, e)) {
              is_variant_available = true;
              prices.push(variant_prices[i]);
              selected_variant_id = variant_ids[i];
              selected_image_index = image_indexes[i];
            }
          });
          if (is_variant_available) {
            quickViewgalleryTop.slideTo(selected_image_index, 500, false);
            if (
              prices[0].special_price < prices[0].price &&
              prices[0].special_price != 0
            ) {
              price = prices[0].special_price;
              $("#modal-product-price").text(currency + " " + price);

              $("#modal-product-special-price").text(
                currency + " " + prices[0].price,
              );

              $("#modal-add-to-cart-button").attr(
                "data-product-variant-id",
                selected_variant_id,
              );
              $("#buy_now_btn_model").attr(
                "data-product-variant-id",
                selected_variant_id,
              );
              $("#modal-product-special-price-div").show();
            } else {
              price = prices[0].price;
              $("#modal-product-price").html(currency + " " + price);
              $("#modal-product-special-price-div").hide();
              $("#modal-add-to-cart-button").attr(
                "data-product-variant-id",
                selected_variant_id,
              );
              $("#buy_now_btn_model").attr(
                "data-product-variant-id",
                selected_variant_id,
              );
            }
          } else {
            $("#modal-product-special-price-div").hide();
          }
        }
      }
    });
  });

  function arrays_equal(_arr1, _arr2) {
    if (
      !Array.isArray(_arr1) ||
      !Array.isArray(_arr2) ||
      _arr1.length !== _arr2.length
    ) {
      return false;
    }

    const arr1 = _arr1.concat().sort();
    const arr2 = _arr2.concat().sort();

    for (let i = 0; i < arr1.length; i++) {
      if (arr1[i] !== arr2[i]) {
        return false;
      }
    }

    return true;
  }

  function formatRepoSelection(repo) {
    return repo.name || repo.text;
  }
  var search_products = $(".search_product").select2({
    ajax: {
      url: base_url + "home/get_products",
      dataType: "json",
      delay: 1000,
      data: function (params) {
        return {
          search: params.term, // search term
          page: params.page,
        };
      },
      processResults: function (response, params) {
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be used
        params.page = params.page || 1;

        return {
          results: response.data,
          pagination: {
            more: params.page * 30 < response.total,
          },
        };
      },
      cache: true,
    },
    escapeMarkup: function (markup) {
      return markup;
    },
    minimumInputLength: 3,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection,
    theme: "adwitt",
    placeholder: "Search for products",
    containerCssClass: "search-container",
    dropdownCssClass: "search-dropdown",
  });

  search_products.on("select2:select", function (e) {
    var data = e.params.data;
    if (data.link != undefined && data.link != null) {
      window.location.href = data.link;
    }
  });

  function formatRepo(repo) {
    if (repo.loading) return repo.text;
    var markup =
      "<div class='select2-result-repository clearfix pointer'>" +
      "<div class='select2-result-repository__avatar'><img src='" +
      repo.image_sm +
      "' /></div>" +
      "<div class='select2-result-repository__meta'>" +
      "<div class='select2-result-repository__title'>" +
      repo.name +
      "</div>";

    if (repo.category_name) {
      markup +=
        "<div class='select2-result-repository__description'> In " +
        repo.category_name +
        "</div>";
    }

    return markup;
  }

  $(document).on("mousedown focusin", ".in-num", function (e) {
    if (is_loggedin == 0) {
      e.preventDefault();
      $(this).blur();
      $("#login-canvas").offcanvas("show");
      return false;
    }
  });

  //Qty Counter
  $(document).on("change", "input.in-num", function (e) {
    e.preventDefault();
    var input = $(this);

    if (input.val() == null || typeof input.val() == "string") {
      if (!$.isNumeric(input.val())) {
        input.val(1);
      } else {
        if (input.val() == "0") {
          input.val(1);
        }
      }
    }
  });
  $(document).on("focusout", ".in-num", function (e) {
    if (is_loggedin == 0) {
      $("#login-canvas").offcanvas("show");
      return false;
    }
    e.preventDefault();
    var value = $(this).val();
    var min = $(this).data("min");
    var step = $(this).data("step");
    var max = $(this).data("max");

    if (value < min) {
      $(this).val(min);
      Toast.fire({
        icon: "error",
        title: "Minimum allowed quantity is " + min,
      });
    } else if (value > max) {
      $(this).val(max);
      Toast.fire({
        icon: "error",
        title: "Maximum allowed quantity is " + max,
      });
    }
  });

  $(document).on("click", ".num-block .num-in span, .num-block .plus, .num-block .minus, .num-block .plus-btn, .num-block .minus-btn", function (e) {
    if (is_loggedin == 0) {
      $("#login-canvas").offcanvas("show");
      return false;
    }
    e.preventDefault();
    var input = $(this).parents(".num-block").find("input.in-num");
    if (input.val() == null) {
      input.val(1);
    }
    if ($(this).hasClass("minus")) {
      var step = $(this).data("step");
      var count = parseFloat(input.val()) - step;
      var min = $(this).data("min");
      if (count >= min) {
        input.val(count);
      } else {
        input.val(min);
        Toast.fire({
          icon: "error",
          title: "Minimum allowed quantity is " + min,
        });
      }
    } else {
      var step = $(this).data("step");
      var max = $(this).data("max");
      var count = parseFloat(input.val()) + step;

      if (max != 0) {
        if (count <= max) {
          input.val(count);
          if (count > 1) {
            $(this).parents(".num-block").find(".minus").removeClass("dis");
          }
        } else {
          input.val(max);
          Toast.fire({
            icon: "error",
            title: "Maximum allowed quantity is " + max,
          });
        }
      } else {
        input.val(count);
      }
    }
    input.change();
    return false;
  });
  $(document).on("click", ".add_to_cart", function (e) {
    e.preventDefault();
    var qty = $(this)
      .parent()
      .parent()
      .parent()
      .find(".input-group-field")
      .val();

    if (qty == undefined || qty == "") {
      qty = 1;
    }

    $("#quick-view").data("data-product-id", $(this).data("productId"));
    var QuickViewModal = $(this).attr("data-bs-target");
    var product_variant_id = $(this).attr("data-product-variant-id");
    var title = $(this).attr("data-product-title");
    var image = $(this).attr("data-product-image");
    var price = $(this).attr("data-product-price");
    var description = $(this).attr("data-product-description");
    var min = $(this).attr("data-min");
    var max = $(this).attr("data-max");
    var type = $(this).attr("data-type");

    if (
      QuickViewModal &&
      QuickViewModal !== "" &&
      $("#modal-product-price").length > 0
    ) {
      var modalPriceText = $("#modal-product-price").text();
      if (modalPriceText && modalPriceText.trim() !== "") {
        // Extract numeric value from price text (remove currency symbols and spaces)
        var extractedPrice = modalPriceText
          .replace(/[^\d.,]/g, "")
          .replace(",", "");
        if (extractedPrice && !isNaN(parseFloat(extractedPrice))) {
          price = extractedPrice;
        }
      }
    }

    if (
      (!price || price === "" || price === "0") &&
      $(".modal-product-price").length > 0
    ) {
      var hiddenModalPrice = $(".modal-product-price").val();
      if (
        hiddenModalPrice &&
        hiddenModalPrice.trim() !== "" &&
        !isNaN(parseFloat(hiddenModalPrice))
      ) {
        price = hiddenModalPrice;
      }
    }

    if ((!price || price === "" || price === "0") && $("#price").length > 0) {
      var displayedPriceText = $("#price").text();
      if (displayedPriceText && displayedPriceText.trim() !== "") {
        var pageExtractedPrice = displayedPriceText
          .replace(/[^\d.,]/g, "")
          .replace(",", "");
        if (pageExtractedPrice && !isNaN(parseFloat(pageExtractedPrice))) {
          price = pageExtractedPrice;
        }
      }
    }

    if (max == undefined || max == "") {
      max = Infinity;
    }
    if (min == undefined || min == "") {
      min = 1;
    }
    var prod_qty = $("#prod_qty").val();
    var step = $(this).attr("data-step");
    var btn = $(this);
    var btn_html = $(this).html();
    var is_buy_now = 0;
    if ($(this).hasClass("buy_now")) {
      is_buy_now = 1;
    }
    if (!product_variant_id) {
      Toast.fire({
        icon: "error",
        title: "Please select variant",
      });
      return;
    }
    if (QuickViewModal == "" || QuickViewModal == undefined) {
      $.ajax({
        type: "POST",
        url: base_url + "cart/manage",
        data: {
          product_variant_id: product_variant_id,
          qty: prod_qty,
          is_saved_for_later: false,
          is_buy_now,
          [csrfName]: csrfHash,
        },
        dataType: "json",
        beforeSend: function () {
          btn
            .html(
              '<div class="spinner-border" role="status">' +
              '<span class="visually-hidden">Loading...</span>' +
              "</div>",
            )
            .attr("disabled", true);
        },
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          btn.html(btn_html).attr("disabled", false);
          if (result.error == false) {
            $(".cart-count").text(result.data.cart_count);
            var html = "";

            display_cart(result.data.items);
            if (is_buy_now == 1) {
              window.location.href = base_url + "cart";
            }

            Toast.fire({
              icon: "success",
              title: result.message,
            });
            return false;
          } else if (is_loggedin == 0) {
            var parsedPrice = 0;
            if (price !== undefined && price !== null && price !== "") {
              if (typeof price === "string") {
                parsedPrice = parseFloat(price.trim()) || 0;
              } else if (typeof price === "number" && !isNaN(price)) {
                parsedPrice = price;
              }
            }

            var cart_item = {
              product_variant_id: product_variant_id.trim(),
              title: title,
              description: description,
              qty: min,
              image: image,
              price: parsedPrice,
              special_price: parsedPrice,
              min: min,
              max: max,
              step: step,
              type: type,
            };

            var cart = localStorage.getItem("cart");
            cart =
              localStorage.getItem("cart") !== null ? JSON.parse(cart) : null;
            if (cart !== null && cart !== undefined) {
              let existingItemIndex = cart.findIndex(
                (item) =>
                  item.product_variant_id === cart_item.product_variant_id,
              );
              if (existingItemIndex !== -1) {
                Toast.fire({
                  icon: "error",
                  title: "Item Already Added in Cart",
                });
              } else {
                cart.push(cart_item);
              }
            } else {
              cart = [cart_item];
            }
            localStorage.setItem("cart", JSON.stringify(cart));
            // $('.cart-count').text(result.data.cart_count)
            $(".cart-count").text(cart.length);
            display_cart(cart);
            Toast.fire({
              icon: "success",
              title: "Item added to cart",
            });
            return false;
          }
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        },
      });
    }
  });
  if (is_loggedin == 0) {
    $(document).ready(function () {
      var cart = localStorage.getItem("cart");
      cart = localStorage.getItem("cart") !== null ? JSON.parse(cart) : null;
      if (cart) {
        var cart_count = cart.length ? cart.length : "";
        $(".cart-count").text(cart_count);
        var html = "";
        var product_variant_id = '';
        if (cart !== null && cart.length > 0) {
          cart.forEach((item) => {

            product_variant_id = item.product_variant_id != undefined ? item.product_variant_id : (item.variant_id != undefined ? item.variant_id : '');


            let qty = item.qty != undefined ? item.qty : (item.quantity != undefined ? item.quantity : '');
            html +=
              '<div class="cart-modal-card card" class="max-width-540px"">' +
              '<div class="row">' +
              '<div class="col-4">' +
              '<div class="cart-img-box">' +
              '<img class="img-fluid rounded-start pic-1 lazy" src="' +
              item.image +
              '"></div></div>' +
              '<div class="col-8">' +
              '<div class="card-body">' +
              '<h5 class="card-title">' +
              item.title +
              "</h5>" +
              '<p class="card-text">' +
              item.description +
              "</p>" +
              '<div class="input-group plus-minus-input mb-3 num-block">' +
              '<div class="input-group-button">' +
              '<button type="button" class="button hollow circle minus-btn minus" data-quantity="minus"' +
              'data-field="quantity" data-min="' +
              item.min +
              '" data-step="' +
              item.step +
              '"><i class="fa-solid fa-minus"></i></button></div>' +
              '<div class="product-quantity product-sm-quantity">' +
              '<input type="number" name="qty" class="input-group-field input-field-cart-modal form-input in-num" value="' +
              qty +
              '" data-id="' +
              item.product_variant_id +
              ' " data-price="' +
              item.price +
              '" min="' +
              item.min +
              '" max="' +
              item.max +
              '" step="' +
              item.step +
              '"></div><div class="input-group-button"><button type="button" class="button hollow circle plus-btn ttt plus" data-quantity="plus" data-field="quantity" data-max="' +
              item.max +
              ' " data-step="' +
              item.step +
              '"><i class="fa-solid fa-plus"></i></button></div></div>' +
              '<p class="product-line-price cart-modal-pricing">' +
              currency +
              " " +
              (qty &&
                item.price !== undefined &&
                item.price !== null &&
                !isNaN(item.price)
                ? (qty * item.price).toLocaleString()
                : "0") +
              "</p>" +
              "</div>" +
              "</div>" +
              "</div>" +
              '<div class="product-sm-removal align-self-center">' +
              '<button class="remove-product btn" data-id="' +
              product_variant_id +
              '"><ion-icon name="close-outline"></ion-icon>' +
              "</button>" +
              "</div>" +
              "</div>";
          });
        } else {
          html +=
            '<div class="offcanvas-body p-0">' +
            '<div class="text-center py-5">' +
            '<ion-icon name="bag-add-outline" class="fa-6x text-body-tertiary opacity-50"></ion-icon>' +
            '<h5 class="">Your Cart Is Empty</h5>' +
            ' <div class="text-center mt-2"><a class="btn btn-primary" href="' +
            base_url +
            "products" +
            ' ">return to shop</a></div>' +
            "</div></div>";
        }
        $("#cart-item-sidebar").html(html);
        updateSubtotal();
      }
    });
  }

  function display_cart(cart) {

    if (is_loggedin == 0) {
      var cart_count = cart.length ? cart.length : "";
      var html = "";
      let variant_value = "";

      if (cart && cart.length > 0) {
        $(".cart-modal-checkout-section").show();

        cart.forEach((item) => {

          // safe variant check
          if (item.product_variants?.[0]?.variant_values) {
            variant_value = `<p class="card-text text-capitalize">${item.product_variants[0].variant_values}</p>`;
          } else {
            variant_value = "";
          }

          // safe price fallback
          let price =
            parseFloat(item.special_price) || parseFloat(item.price) || 0;


          let qty = item.qty != undefined ? item.qty : (item.quantity != undefined ? item.quantity : '');

          let total = (qty || 1) * price;

          html += `
                <div class="cart-modal-card card">
                    <div class="row">
                        <div class="col-4">
                            <div class="cart-img-box">
                                <img class="img-fluid rounded-start pic-1 lazy" src="${item.image}">
                            </div>
                        </div>

                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title">${item.title}</h5>
                                <p class="card-text">${item.description}</p>
                                ${variant_value}

                                <div class="input-group plus-minus-input mb-3 num-block">
                                    <div class="input-group-button">
                                        <button type="button" class="button hollow circle minus-btn minus"
                                            data-quantity="minus" data-field="quantity"
                                            data-min="${item.min}" data-step="${item.step}">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </div>

                                    <div class="product-quantity product-sm-quantity">
                                        <input type="number" name="qty"
                                            class="input-group-field input-field-cart-modal form-input in-num"
                                            value="${qty}"
                                            data-id="${item.product_variant_id}"
                                            data-price="${price}"
                                            min="${item.min}" max="${item.max}"
                                            step="${item.step}">
                                    </div>

                                    <div class="input-group-button">
                                        <button type="button" class="button hollow circle plus-btn plus"
                                            data-quantity="plus" data-field="quantity"
                                            data-max="${item.max}" data-step="${item.step}">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <p class="product-line-price cart-modal-pricing">
                                    ${currency} ${total}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="product-sm-removal align-self-center">
                        <button class="remove-product btn" data-id="${item.product_variant_id}">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </div>
                </div>`;
        });
      } else {
        html += emptyCartHTML();
      }

      $("#cart-item-sidebar").html(html);
      updateSubtotal();
    }

    // ---------------- LOGGED IN CART ----------------
    else {
      var cart_count = cart.length ? cart.length : "";
      $(".cart-count").text(cart_count);

      var html = "";
      let variant_value = "";

      if (cart && cart.length > 0) {
        $(".cart-modal-checkout-section").show();

        cart.forEach((item) => {

          if (item.product_variants?.[0]?.variant_values) {
            variant_value = `<p class="card-text text-capitalize">${item.product_variants[0].variant_values}</p>`;
          } else {
            variant_value = "";
          }

          // safe price fallback
          let price =
            parseFloat(item.special_price) || parseFloat(item.price) || 0;
          let total = (item.qty || 1) * price;

          html += `
                <div class="cart-modal-card card">
                    <div class="row">
                        <div class="col-4">
                            <div class="cart-img-box">
                                <img class="img-fluid rounded-start pic-1 lazy" src="${base_url}${item.image}">
                            </div>
                        </div>

                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title">${item.name}</h5>
                                <p class="card-text">${item.short_description}</p>
                                ${variant_value}

                                <div class="input-group plus-minus-input mb-3 num-block">
                                    <div class="input-group-button">
                                        <button type="button" class="button hollow circle minus-btn minus"
                                            data-quantity="minus"
                                            data-field="quantity"
                                            data-min="${item.minimum_order_quantity}"
                                            data-step="${item.quantity_step_size}">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                    </div>

                                    <div class="product-quantity product-sm-quantity">
                                        <input type="number" name="qty"
                                            class="input-group-field input-field-cart-modal form-input in-num"
                                            value="${item.qty}"
                                            data-id="${item.product_variant_id}"
                                            data-price="${price}"
                                            min="${item.minimum_order_quantity}"
                                            max="${item.total_allowed_quantity}"
                                            step="${item.quantity_step_size}">
                                    </div>

                                    <div class="input-group-button">
                                        <button type="button" class="button hollow circle plus-btn plus"
                                            data-quantity="plus"
                                            data-field="quantity"
                                            data-max="${item.total_allowed_quantity}"
                                            data-step="${item.quantity_step_size}">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <p class="product-line-price cart-modal-pricing">
                                    ${currency} ${total}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="product-sm-removal align-self-center">
                        <button class="remove-product btn" data-id="${item.product_variant_id}">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </div>
                </div>`;
        });
      } else {
        html += emptyCartHTML();
      }

      $("#cart-item-sidebar").html(html);
      updateSubtotal();
    }
  }

  function emptyCartHTML() {
    return `
        <div class="offcanvas-body p-0">
            <div class="text-center py-5">
                <ion-icon name="bag-add-outline" class="fa-6x text-body-tertiary opacity-50"></ion-icon>
                <h5>Your Cart Is Empty</h5>
                <div class="text-center mt-2">
                    <a class="btn btn-primary" href="${base_url}products">return to shop</a>
                </div>
            </div>
        </div>`;
  }

  function mycartTotal() {
    var cartTotal = 0;
    $(
      "#cart_item_table > tbody > tr > .total-price  > .product-line-price",
    ).each(function (i) {
      cartTotal =
        parseFloat(cartTotal) +
        parseFloat(
          $(this)
            .text()
            .replace(/[^\d\.]/g, ""),
        );
    });
    $(".final_total").text(cartTotal.toFixed(2));
  }

  function recalculateCart() {
    var subtotal = 0;

    /* Set rates + misc */
    var taxRate = 0.05;
    var shippingRate = 15.0;
    var fadeTime = 300;
    /* Sum up row totals */
    $(".product").each(function () {
      subtotal += parseFloat($(this).children(".product-line-price").text());
    });

    /* Calculate totals */
    var tax = subtotal * taxRate;
    var shipping = subtotal > 0 ? shippingRate : 0;
    var total = subtotal + tax + shipping;

    /* Update totals display */
    $(".totals-value").fadeOut(fadeTime, function () {
      $("#cart-subtotal").html(subtotal.toFixed(2));
      $("#cart-tax").html(tax.toFixed(2));
      $("#cart-shipping").html(shipping.toFixed(2));
      $("#cart-total").html(total.toFixed(2));
      if (total == 0) {
        $(".checkout").fadeOut(fadeTime);
      } else {
        $(".checkout").fadeIn(fadeTime);
      }
      $(".totals-value").fadeIn(fadeTime);
    });
  }
  /* Update quantity */
  function updateQuantity(quantityInput, price) {
    /* Calculate line price */
    if (quantityInput.data("page") == "cart") {
      var productRow = $(quantityInput)
        .parent()
        .parent()
        .parent()
        .siblings(".total-price");
    } else {
      var productRow = $(quantityInput).parent().parent();
    }
    var quantity = $(quantityInput).val();
    var linePrice = price * quantity;

    /* Update line price display and recalc cart totals */
    productRow.find(".product-line-price").fadeOut(200, function () {
      $(this).fadeOut(200, function () {
        $(this).text(currency + linePrice.toFixed(2));
        recalculateCart();
        mycartTotal();
        $(this).fadeIn(200);
      });
    });
  }

  $(document).on(
    "click",
    ".num-block .input-group-button button",
    function (e) {
      e.preventDefault();
      var input = $(this).parents(".num-block").find("input");

      if (input.data("page") === "cart" && is_loggedin == 1) {
        return;
      }

      if (input.val() == null) {
        input.val(1);
        $(input).attr("value", 1);
      }
      if ($(this).hasClass("minus-btn")) {
        var step = $(this).data("step");
        var count = parseFloat(input.val()) - step;
        var min = $(this).data("min");
        if (count >= min) {
          input.val(count);
          $(input).attr("value", count);
        } else {
          input.val(min);
          $(input).attr("value", min);

          Toast.fire({
            icon: "error",
            title: "Minimum allowed quantity is " + min,
          });
        }
        updateSubtotal();
        update_price();
      } else if ($(this).hasClass("plus-btn")) {
        var step = $(this).data("step");
        var max = $(this).data("max");
        var count = parseFloat(input.val()) + step;
        if (max != 0) {
          if (count <= max) {
            input.val(count);
            $(input).attr("value", count);

            if (count > 1) {
              $(this)
                .parents(".num-block")
                .find(".minus-btn")
                .removeClass("dis");
            }
          } else {
            max = parseInt(max);
            input.val(max);
            $(input).attr("value", max);

            Toast.fire({
              icon: "error",
              title: "Maximum allowed quantity is " + max,
            });
          }
        } else {
          input.val(count);
          $(input).attr("value", count);
        }
        updateSubtotal();
        update_price();
      }
      input.change();
      return false;
    },
  );

  setTimeout(function () {

  }, 2000);
  /* Assign actions */

  //Remove from Cart.
  $(document).on(
    "click",
    ".product-removal button,.product-removal ion-icon,.product-sm-removal button",
    function (e) {
      e.preventDefault();
      var id = $(this).data("id");
      var is_save_for_later =
        typeof $(this).data("is-save-for-later") != "undefined" &&
          $(this).data("is-save-for-later") == 1
          ? "1"
          : "0";
      var product = $(this).parent().parent();
      if (is_loggedin == 1) {
        // remove from server
        $.ajax({
          type: "POST",
          url: base_url + "cart/remove",
          data: {
            product_variant_id: id,
            is_save_for_later: is_save_for_later,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.error == false) {
              var cart_count = $(".cart-count");
              cart_count.each(function () {
                var cart_count = parseInt($(this).text()); // Get the current count as an integer
                cart_count--;

                // Update the text content with the new count
                $(this).text(cart_count.toString());
              });
              removeItem(product);
              Toast.fire({
                icon: "success",
                title: "Item Remove From Cart",
              });
            } else {
              Toast.fire({
                icon: "error",
                title: result.message,
              });
            }
          },
        });
      } else {
        // remove from local storage
        removeItem(product);
        var cart = localStorage.getItem("cart");
        cart = localStorage.getItem("cart") !== null ? JSON.parse(cart) : null;
        if (cart) {
          var new_cart = cart.filter(function (item) {
            return item.product_variant_id != id;
          });
          localStorage.setItem("cart", JSON.stringify(new_cart));
          if (cart) display_cart(new_cart);
          updateSubtotal();
        }
      }
    },
  );

  /* Remove item from cart */
  function removeItem(removeProduct) {
    var productRow = $(removeProduct);

    productRow.slideUp(200, function () {
      productRow.remove();
      updateSubtotal();
      mycartTotal();
    });
  }

  function updateSubtotal() {
    var subtotal = 0;

    $(".cart-modal-card").each(function () {
      let price = parseFloat(
        $(this).find(".input-field-cart-modal").data("price"),
      );
      let quantity = parseInt($(this).find(".product-quantity input").val());

      if (!isNaN(price) && !isNaN(quantity) && quantity > 0) {
        let lineTotal = price * quantity;

        $(this)
          .find(".product-line-price")
          .text(currency + " " + lineTotal.toFixed(2));

        subtotal += lineTotal;
      }
    });

    $(document).on(
      "input click",
      ".product-quantity input, .plus-btn, .minus-btn",
      function () {
        updateSubtotal();
        mycartTotal(); // final total update
      },
    );
    $("#subtotal-amount").fadeOut(200, function () {
      $(this)
        .text(currency + " " + subtotal.toFixed(2))
        .fadeIn(200);
    });
  }

  updateSubtotal();
  $(".product-quantity input").on("input", function () {
    updateSubtotal();
  });

  function update_price() {
    $(".cart-modal-card").each(function () {
      var card = $(this);
      var price = card
        .find(".product-quantity .input-field-cart-modal")
        .data("price");
      var quantity = card.find(".product-quantity input").val();
      var linePrice = card.find(".product-line-price");

      if (!isNaN(price) && !isNaN(quantity)) {
        var product_price = price * quantity;

        linePrice.fadeOut(200, function () {
          $(this).text(currency + " " + product_price.toFixed(2));
          $(this).fadeIn(200);
        });
      }
    });
  }

  function setUrlParameter(url, paramName, paramValue) {
    paramName = paramName.replace(/\s+/g, "-");
    if (paramValue == null || paramValue == "") {
      return url
        .replace(new RegExp("[?&]" + paramName + "=[^&#]*(#.*)?$"), "$1")
        .replace(new RegExp("([?&])" + paramName + "=[^&]*&"), "$1");
    }
    var pattern = new RegExp("\\b(" + paramName + "=).*?(&|#|$)");
    if (url.search(pattern) >= 0) {
      return url.replace(pattern, "$1" + paramValue + "$2");
    }
    url = url.replace(/[?#]$/, "");
    return (
      url + (url.indexOf("?") > 0 ? "&" : "?") + paramName + "=" + paramValue
    );
  }

  function buildUrlParameterValue(
    paramName,
    paramValue,
    action,
    custom_url = "",
  ) {
    if (custom_url != "") {
      var param = getUrlParameter(paramName, custom_url);
    } else {
      var param = getUrlParameter(paramName);
    }
    if (action == "add") {
      if (param == undefined) {
        param = paramValue;
      } else {
        param += "|" + paramValue;
      }
      return param;
    } else if (action == "remove") {
      if (param != undefined) {
        param = param.split("|");
        param.splice($.inArray(paramValue, param), 1);
        return param.join("|");
      } else {
        return "";
      }
    }
  }

  $(document).on("change", ".product_attributes", function (e) {
    e.preventDefault();
    var attribute_name = $(this).data("attribute");
    attribute_name = "filter-" + attribute_name;
    var get_param = getUrlParameter(attribute_name);
    var current_param_value = $(this).val();
    if (get_param == undefined) {
      get_param = "";
    }
    if (this.checked) {
      var param = buildUrlParameterValue(
        attribute_name,
        current_param_value,
        "add",
        custom_url,
      );
    } else {
      var param = buildUrlParameterValue(
        attribute_name,
        current_param_value,
        "remove",
        custom_url,
      );
    }
    custom_url = setUrlParameter(custom_url, attribute_name, param);
  });

  function getUrlParameter(sParam, custom_url = "") {
    sParam = sParam.replace(/\s+/g, "-");
    if (custom_url != "") {
      if (custom_url.indexOf("?") > -1) {
        var sPageURL = custom_url.substring(custom_url.indexOf("?") + 1);
      } else {
        return undefined;
      }
    } else {
      var sPageURL = window.location.search.substring(1);
    }

    var sURLVariables = sPageURL.split("&"),
      sParameterName,
      i;

    for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split("=");

      if (sParameterName[0] === sParam) {
        return sParameterName[1] === undefined
          ? true
          : decodeURIComponent(sParameterName[1]);
      }
    }
  }

  if ($("#product-filters").length) {
    if (!checkUrlHasParam()) {
      sessionStorage.setItem(
        $("#product-filters").data("key"),
        $("#product-filters").val(),
      );
      var filters = sessionStorage.getItem($("#product-filters").data("key"));
      filters = filters.replace(/\\/g, "");

      print_filters_desktop(filters, "Desktop", "#product-filters-desktop");
      print_filters_mobile(filters, "Mobile", "#product-filters-mobile");
      print_filters_mobile_value(
        filters,
        "Mobile",
        "#product-filters-mobile-value",
      );
    } else {
      if (
        sessionStorage.getItem($("#product-filters").data("key")) == undefined
      ) {
        sessionStorage.setItem(
          $("#product-filters").data("key"),
          $("#product-filters").val(),
        );
      }
      var filters = sessionStorage.getItem($("#product-filters").data("key"));
      filters = filters.replace(/\\/g, "");
      print_filters_desktop(filters, "Desktop", "#product-filters-desktop");
      print_filters_mobile(filters, "Mobile", "#product-filters-mobile");
      print_filters_mobile_value(
        filters,
        "Mobile",
        "#product-filters-mobile-value",
      );
    }
  }

  function print_filters_desktop(filters, prefix = "", target) {
    var html = "";
    var attribute_values_id;
    var attribute_values;
    var new_attr_val;
    var attr_name;
    var collapse_status;
    var selected_attributes;
    var attr_checked_status;
    var e_name;

    function decodeUnicode(inputStr) {
      // Use a regular expression to find all occurrences of the pattern uXXXX
      return inputStr.replace(/u([0-9A-Fa-f]{4})/g, (match, group) => {
        // Convert the hex value to a character using String.fromCharCode
        return String.fromCharCode(parseInt(group, 16));
      });
    }

    if (filters != "") {
      $.each(JSON.parse(filters), function (i, e) {
        const brand_name = getUrlParameter("brand");
        var brands = $('[data-value="' + brand_name + '"]');
        $('[data-value="' + brand_name + '"]').attr("checked");
        var gp = $(brands).siblings();
        $(gp).addClass("selected-brand");

        e_name = decodeUnicode(e.name.replace(" ", "-").toLowerCase());
        attr_name = getUrlParameter("filter-" + e_name);
        collapse_status = attr_name == undefined ? " " : "show";
        selected_attributes =
          attr_name != undefined ? attr_name.split("|") : "";
        html +=
          '<div class="accordion accordion-flush border-top" id="accordionFlushExample">' +
          '<div class="accordion-item">' +
          '<h2 class="accordion-header" id="flush-headingOne">' +
          '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' +
          prefix +
          i +
          '" aria-expanded="false" aria-controls="flush-collapseOne">' +
          decodeUnicode(e.name.replace(" ", "-").toLowerCase()) +
          "</button></h2>" +
          '<div id="' +
          prefix +
          i +
          '" class="accordion-collapse collapse ' +
          collapse_status +
          '" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">' +
          '<div class="accordion-body">';
        attribute_values_id = e.attribute_values_id.split(",");
        attribute_values = e.attribute_values.split(",");

        $.each(attribute_values, function (j, v) {
          let decodedValue = decodeUnicode(v);
          attr_checked_status =
            $.inArray(decodedValue, selected_attributes) !== -1
              ? "checked"
              : "";
          new_attr_val = e_name + " " + decodedValue;
          html +=
            '<div class="form-check d-flex ps-0">' +
            '<input type="checkbox" name="' +
            decodedValue +
            '" value="' +
            decodedValue +
            '" class="toggle-input product_attributes width15px" id="' +
            prefix +
            new_attr_val +
            '" data-attribute="' +
            e_name +
            '" ' +
            attr_checked_status +
            '> <label class="form-check-label ms-2" for="' +
            prefix +
            new_attr_val +
            '">' +
            decodedValue +
            "</label></div>";
        });
        html += "</div></div></div></div>";
      });
    }
    $(target).html(html);
  }

  function print_filters_mobile(filters, prefix = "", target) {
    var html = "";
    var attribute_values_id;
    var attribute_values;
    var new_attr_val;
    var attr_name;
    var collapse_status;
    var selected_attributes;
    var attr_checked_status;
    var e_name;
    function decodeUnicode(inputStr) {
      // Use a regular expression to find all occurrences of the pattern uXXXX
      return inputStr.replace(/u([0-9A-Fa-f]{4})/g, (match, group) => {
        // Convert the hex value to a character using String.fromCharCode
        return String.fromCharCode(parseInt(group, 16));
      });
    }

    if (filters != "") {
      $.each(JSON.parse(filters), function (i, e) {
        const brand_name = getUrlParameter("brand");
        var brands = $('[data-value="' + brand_name + '"]');
        $('[data-value="' + brand_name + '"]').attr("checked");
        var gp = $(brands).siblings();
        $(gp).addClass("selected-brand");

        e_name = e.name.replace(" ", "-").toLowerCase();
        e_name = decodeUnicode(e_name);
        attr_name = getUrlParameter("filter-" + e_name);
        collapse_status = attr_name == undefined ? " " : "show";
        selected_attributes =
          attr_name != undefined ? attr_name.split("|") : "";
        html +=
          '<div class="nav flex-column nav-pills me-2 ms-2 text-break text-wrap" id="' +
          prefix +
          i +
          '-tab1" role="tablist" aria-orientation="vertical">' +
          '<button class="nav-link filter-titles me-2" id="' +
          prefix +
          i +
          '-tab" data-bs-toggle="tab" data-bs-target="#' +
          prefix +
          i +
          '" type="button" role="tab" aria-controls="' +
          prefix +
          i +
          '" aria-selected="true">' +
          e.name +
          "</button></div>";
      });
    }
    $(target).html(html);
  }

  function print_filters_mobile_value(filters, prefix = "", target) {
    var html = "";
    var attribute_values_id;
    var attribute_values;
    var new_attr_val;
    var attr_name;
    var collapse_status;
    var selected_attributes;
    var attr_checked_status;
    var e_name;

    function decodeUnicode(inputStr) {
      // Use a regular expression to find all occurrences of the pattern uXXXX
      return inputStr.replace(/u([0-9A-Fa-f]{4})/g, (match, group) => {
        // Convert the hex value to a character using String.fromCharCode
        return String.fromCharCode(parseInt(group, 16));
      });
    }

    if (filters != "") {
      $.each(JSON.parse(filters), function (i, e) {
        e_name = e.name.replace(" ", "-").toLowerCase();
        e_name = decodeUnicode(e_name);
        attr_name = getUrlParameter("filter-" + e_name);
        collapse_status = attr_name == undefined ? " " : "show";
        selected_attributes =
          attr_name != undefined ? attr_name.split("|") : "";
        html +=
          '<div class="tab-pane fade' +
          collapse_status +
          '" id="' +
          prefix +
          i +
          '" role="tabpanel" aria-labelledby="' +
          prefix +
          i +
          '-tab" tabindex="0">' +
          '<div class="form-check">';

        attribute_values_id = e.attribute_values_id.split(",");
        attribute_values = e.attribute_values.split(",");

        $.each(attribute_values, function (j, v) {
          attr_checked_status =
            $.inArray(v, selected_attributes) !== -1 ? "checked" : "";
          new_attr_val = e_name + " " + v;
          html +=
            '<label class="form-check-label py-2 text-break text-wrap" for="' +
            prefix +
            new_attr_val +
            '">' +
            '<input type="checkbox" name="' +
            v +
            '" value="' +
            v +
            '" class="toggle-input product_attributes width15px form-check-input" id="' +
            prefix +
            new_attr_val +
            '" data-attribute="' +
            e_name +
            '" ' +
            attr_checked_status +
            ">" +
            v +
            "</label>";
        });
        html += "</div>" + "</div>";
      });
    }
    $(target).html(html);
  }

  $(".nav-link").on("click", function () {
    // Hide all tab-panes
    $(".tab-pane").removeClass("show active");

    // Get the ID of the corresponding tab-pane
    var targetTab = $(this).attr("aria-controls");

    // Show the clicked tab-pane
    $("#" + targetTab).addClass("show active");
  });

  function checkUrlHasParam(custom_url = "") {
    if (custom_url == "") {
      custom_url = window.location.href;
    }

    if (custom_url.indexOf("?") > -1) {
      return true;
    } else {
      return undefined;
    }
  }

  //Set URL in Product Listing Page Style buttons
  var type_url = "";
  type_url = setUrlParameter(custom_url, "type", null);
  $("#product_grid_view_btn").attr("href", type_url);
  type_url = setUrlParameter(custom_url, "type", "list");
  $("#product_list_view_btn").attr("href", type_url);
  if (getUrlParameter("type") == "list") {
    $("#product_list_view_btn").addClass("active");
  } else {
    $("#product_grid_view_btn").addClass("active");
  }

  $(".product_filter_btn").on("click", function (e) {
    e.preventDefault();
    location.href = custom_url;
  });

  $("#product_sort_by").on("change", function (e) {
    e.preventDefault();
    var sort = $(this).val();
    location.href = setUrlParameter(location.href, "sort", sort);
  });

  $("input[type=radio][name=flexRadioDefault]").on("change", function (e) {
    e.preventDefault();
    var sort = $(this).val();
    location.href = setUrlParameter(location.href, "sort", sort);
  });

  $("#contact-us-form").on("submit", function (e) {
    e.preventDefault();
    var submit_btn_html = $("#contact-us-submit-btn").html();
    var formdata = new FormData(this);
    formdata.append(csrfName, csrfHash);
    $.ajax({
      type: "POST",
      data: formdata,
      url: $(this).attr("action"),
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $("#contact-us-submit-btn")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });
          $("#contact-us-form")[0].reset();
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
        $("#contact-us-submit-btn")
          .html(submit_btn_html)
          .attr("disabled", false);
      },
    });
  });

  $(document).on("click", ".delete_user_account", function () {
    var mobile = $("#Mobile_number").val();

    var password = $("#password").val();
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Delete My Account!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          data: {
            mobile: mobile,
            password: password,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          url: base_url + "My_account/delete_account",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result.error == false) {
              setTimeout(function () {
                window.location.href = base_url;
              }, 2000);
              Toast.fire({
                icon: "info",
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
    });
  });

  // Handle account deletion for email users (no mobile number)
  $(document).on("click", ".delete_email_user_account", function () {
    var session_user_id = document.getElementById("session_user_id").value;
    var email = $("#Email_address").val();
    var password = $("#password").val();

    if (!email || !password) {
      Toast.fire({
        icon: "error",
        title: "Please enter both email and password",
      });
      return;
    }

    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this! This will permanently delete your account.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Delete My Account!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          data: {
            session_user_id: session_user_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          url: base_url + "My_account/delete_social_account",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result.error == false) {
              setTimeout(function () {
                window.location.href = base_url;
              }, 2000);
              Toast.fire({
                icon: "info",
                title: result.message,
              });
            } else {
              Toast.fire({
                icon: "error",
                title: result.message,
              });
            }
          },
          error: function (xhr, status, error) {
            Toast.fire({
              icon: "error",
              title: "Server error: Please try again later.",
            });
          },
        });
      }
    });
  });

  $(document).on("click", ".delete_social_account", function () {
    var uid = "";
    var session_user_id = document.getElementById("session_user_id").value;
    firebase.auth().onAuthStateChanged((user) => {
      if (user) {
        uid = user.uid;
        user.delete().then(function () {
          // User deleted.
          $.ajax({
            type: "POST",
            data: {
              session_user_id: session_user_id,
              [csrfName]: csrfHash,
            },
            dataType: "json",
            url: base_url + "My_account/delete_social_account",
            success: function (result) {
              csrfName = result["csrfName"];
              csrfHash = result["csrfHash"];
              if (result.error == false) {
                Toast.fire({
                  icon: "success",
                  title: result.message,
                });
                location.replace(base_url);
              } else {
                Toast.fire({
                  icon: "error",
                  title: result.message,
                });
              }
            },
          });

          var ref = firebase.database().ref("users/".concat(user.uid, "/"));
          ref.remove();
        });
      } else {
      }
    });
  });

  $(".update-order-item").on("click", function (e) {
    e.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, update it!",
    }).then((result) => {
      var formdata = new FormData();
      var order_item_id = $(this).data("item-id");
      var status = $(this).data("status");
      var t = $(this);
      var btn_text = t.text();

      formdata.append(csrfName, csrfHash);
      formdata.append("order_item_id", order_item_id);
      formdata.append("status", status);

      $.ajax({
        type: "POST",
        url: base_url + "my-account/update-order-item-status",
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function () {
          t.html(
            '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>',
          ).attr("disabled", true);
        },
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          if (result.error == false) {
            Toast.fire({
              icon: "success",
              title: result.message,
            });
            setTimeout(function () {
              location.reload();
            }, 1000);
          } else {
            Toast.fire({
              icon: "error",
              title: result.message,
            });
          }
          t.html(btn_text).attr("disabled", false);
        },
        error: function (xhr, status, error) {
          Toast.fire({
            icon: "error",
            title:
              "Server error: " +
              (xhr.responseJSON?.message || "Please try again later."),
          });
          t.html(btn_text).attr("disabled", false);
        },
      });
    });
  });

  $(".update-order").on("click", function (e) {

    e.preventDefault();
    var formdata = new FormData();
    var order_id = $(this).data("order-id");
    var status = $(this).data("status");
    var temp = "";
    if (status == "cancelled") {
      temp = "Cancel";
    } else {
      temp = "Return";
    }
    if (confirm("Are you sure you want to " + temp + " this order ?")) {
      var t = $(this);
      var btn_text = t.text();
      formdata.append(csrfName, csrfHash);
      formdata.append("order_id", order_id);
      formdata.append("status", status);
      $.ajax({
        type: "POST",
        url: base_url + "my-account/update-order",
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function () {
          t.html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          ).attr("disabled", true);
        },
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          if (result.error == false) {
            Toast.fire({
              icon: "success",
              title: result.message,
            });
            setTimeout(function () {
              window.location.reload();
            }, 3000);
          } else {
            Toast.fire({
              icon: "error",
              title: result.message,
            });
          }
          t.html(btn_text).attr("disabled", false);
        },
      });
    }
  });

  // form - submit - event
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
  });

  $(document).on("submit", "#send_forgot_password_otp_form", function (e) {
    e.preventDefault();
    var send_otp_btn = $("#forgot_password_send_otp_btn").html();
    var phoneNumber =
      $("#send_forgot_password_otp_form").find(".selected-dial-code").text() +
      $("#forgot_password_number").val();
    var forget_password_val = $("#forget_password_val").val();

    var captchaResponse = grecaptcha.getResponse();
    if (!captchaResponse) {
      $("#forgot_pass_error_box")
        .html("Please complete the CAPTCHA verification.")
        .show();
      Toast.fire({
        icon: "error",
        title: "Please complete the CAPTCHA verification.",
      });
      return;
    }
    var response = is_user_exist($("#forgot_password_number").val());
    if (response.error == false) {
      $("#forgot_pass_error_box").html(
        "You have not registered using this number.",
      );
      $("#forgot_password_send_otp_btn")
        .html(send_otp_btn)
        .attr("disabled", false);
    } else {
      var appVerifier = window.recaptchaVerifier;
      firebase
        .auth()
        .signInWithPhoneNumber(phoneNumber, appVerifier)
        .then(function (confirmationResult) {
          resetRecaptcha();
          $("#verify_forgot_password_otp_form").removeClass("d-none");
          $("#send_forgot_password_otp_form").hide();
          $("#forgot_pass_error_box").html(response.message);
          $("#forgot_password_send_otp_btn")
            .html(send_otp_btn)
            .attr("disabled", false);
          $(document).on(
            "submit",
            "#verify_forgot_password_otp_form",
            function (e) {
              e.preventDefault();
              var reset_pass_btn_html = $("#reset_password_submit_btn").html();
              var code = $("#forgot_password_otp").val();
              var formdata = new FormData(this);
              var url = base_url + "home/reset-password";
              $("#reset_password_submit_btn")
                .html("Please Wait...")
                .attr("disabled", true);
              confirmationResult
                .confirm(code)
                .then(function (result) {
                  formdata.append(csrfName, csrfHash);
                  formdata.append("mobile", $("#forgot_password_number").val());
                  formdata.append(
                    "forget_password_val",
                    $("#forget_password_val").val(),
                  );
                  $.ajax({
                    type: "POST",
                    url: url,
                    data: formdata,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "json",
                    beforeSend: function () {
                      $("#reset_password_submit_btn")
                        .html(
                          '<div class="spinner-border" role="status">' +
                          '<span class="visually-hidden">Loading...</span>' +
                          "</div>",
                        )
                        .attr("disabled", true);
                    },
                    success: function (result) {
                      csrfName = result.csrfName;
                      csrfHash = result.csrfHash;
                      $("#reset_password_submit_btn")
                        .html(reset_pass_btn_html)
                        .attr("disabled", false);
                      $("#set_password_error_box").html(result.message).show();
                      if (result.error == false) {
                        setTimeout(function () {
                          $(".forget-password-section").hide();
                          $(".register-login-section").show();
                          $("register_div").hide();
                          $(".login-section").show();
                        }, 2000);
                      }
                    },
                  });
                })
                .catch(function (error) {
                  $("#reset_password_submit_btn")
                    .html(reset_pass_btn_html)
                    .attr("disabled", false);
                  $("#set_password_error_box")
                    .html("Invalid OTP. Please Enter Valid OTP")
                    .show();
                });
            },
          );
        })
        .catch(function (error) {
          $("#forgot_pass_error_box").html(error.message).show();
          $("#forgot_password_send_otp_btn")
            .html(send_otp_btn)
            .attr("disabled", false);
          resetRecaptcha();
        });
    }
  });

  $(document).on("submit", ".form-submit-event", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var form_id = $(this).attr("id");
    var error_box = $("#error_box", this);
    var submit_btn = $(this).find(".submit_btn");
    var btn_html = $(this).find(".submit_btn").html();
    var btn_val = $(this).find(".submit_btn").val();
    var button_text =
      btn_html != "" || btn_html != "undefined" ? btn_html : btn_val;
    formData.append(csrfName, csrfHash);

    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: formData,
      beforeSend: function () {
        submit_btn.html("Please Wait..");
        submit_btn.attr("disabled", true);
      },
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (result) {

        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        if (result["error"] == true) {
          Toast.fire({
            icon: "error",
            title: result["message"],
          });

          submit_btn.html(button_text);
          submit_btn.attr("disabled", false);
        } else {
          Toast.fire({
            icon: "success",
            title: result["message"],
          });

          submit_btn.html(button_text);
          submit_btn.attr("disabled", false);
          $(".form-submit-event")[0].reset();
          if (form_id == "login_form") {
            cart_sync();
          }
          setTimeout(function () {
            location.reload();
          }, 600);
        }
      },
    });
  });

  // Handle email login button click in offcanvas modal
  $(document).on("click", "#login-canvas-emailLogin-btn", function (e) {
    e.preventDefault();

    // Hide mobile form and show email form in login modal
    $("#login-mobile-form").addClass("d-none");
    $("#login-email-form").removeClass("d-none");

    // Set the form to email login mode
    $("#login_form").attr("data-login-mode", "email");

    // Toggle button visibility - only within offcanvas
    $("#login-canvas .emailLogin").addClass("d-none");
    $("#login-canvas .phoneLogin").removeClass("d-none");

    // Update validation rules for email login
    $("#login_form").validate().destroy();
    $("#login_form").validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
      },
    });
  });

  // Handle email login button click in register-login page
  $(document).on("click", "#login-emailLogin-btn", function (e) {
    e.preventDefault();

    // Hide mobile form and show email form in login section
    $("#mobile-form").addClass("d-none");
    $("#email-form").removeClass("d-none");

    // Set the form to email login mode
    $("#login_form").attr("data-login-mode", "email");

    // Toggle button visibility - only in register-login page (not in offcanvas)
    $(".login-section .emailLogin").addClass("d-none");
    $(".login-section .phoneLogin").removeClass("d-none");

    // Update validation rules for email
    $("#login_form").validate().destroy();
    $("#login_form").validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
      },
    });
  });

  // Handle phone login button click in register-login page
  $(document).on("click", "#login-phoneLogin-btn", function (e) {
    e.preventDefault();

    // Show mobile form and hide email form in login section
    $("#mobile-form").removeClass("d-none");
    $("#email-form").addClass("d-none");

    // Remove email login mode
    $("#login_form").removeAttr("data-login-mode");

    // Toggle button visibility - only in register-login page (not in offcanvas)
    $(".login-section .phoneLogin").addClass("d-none");
    $(".login-section .emailLogin").removeClass("d-none");

    // Update validation rules for mobile number
    $("#login_form").validate().destroy();
    $("#login_form").validate({
      rules: {
        identity: {
          required: true,
          number: true,
          maxlength: 16,
        },
      },
    });
  });

  // Handle text link email login in login section
  $(document).on("click", "#login-emailLogin", function (e) {
    e.preventDefault();

    // Hide mobile form and show email form in login section
    $("#mobile-form").addClass("d-none");
    $("#email-form").removeClass("d-none");

    // Toggle text link visibility
    $(".login-email-login").addClass("d-none");
    $(".login-phone-login").removeClass("d-none");

    // Set the form to email login mode
    $("#login_form").attr("data-login-mode", "email");

    // Update validation rules for email
    $("#login_form").validate().destroy();
    $("#login_form").validate({
      rules: {
        identity: {
          required: true,
          email: true,
        },
      },
    });
  });

  // Handle text link phone login in login section
  $(document).on("click", "#login-phoneLogin", function (e) {
    e.preventDefault();

    // Show mobile form and hide email form in login section
    $("#mobile-form").removeClass("d-none");
    $("#email-form").addClass("d-none");

    // Toggle text link visibility
    $(".login-phone-login").addClass("d-none");
    $(".login-email-login").removeClass("d-none");

    // Remove email login mode
    $("#login_form").removeAttr("data-login-mode");

    // Update validation rules for mobile number
    $("#login_form").validate().destroy();
    $("#login_form").validate({
      rules: {
        identity: {
          required: true,
          number: true,
          maxlength: 16,
        },
      },
    });
  });

  // Handle phone login button click in offcanvas modal
  $(document).on("click", "#login-canvas-phoneLogin-btn", function (e) {
    e.preventDefault();

    // Show mobile form and hide email form in login modal
    $("#login-mobile-form").removeClass("d-none");
    $("#login-email-form").addClass("d-none");

    // Remove email login mode
    $("#login_form").removeAttr("data-login-mode");

    // Toggle button visibility - only within offcanvas
    $("#login-canvas .phoneLogin").addClass("d-none");
    $("#login-canvas .emailLogin").removeClass("d-none");

    $("#login_form").validate().destroy();
    $("#login_form").validate({
      rules: {
        identity: {
          required: true,
          number: true,
          maxlength: 16,
        },
      },
    });
  });

  $(document).on("click", ".registration-section #emailLogin", function (e) {
    e.preventDefault();
    // Hide the email login link and show phone login link
    $(".email-login").addClass("d-none");
    $(".phone-login").removeClass("d-none");

    // Hide phone OTP forms and show email signup form
    $("#send-otp-form").hide();
    $("#verify-otp-form").hide();
    $("#sign-up-form").show().css("display", "block");

    // Set the hidden type field to email for the signup form
    $("#sign-up-form").find('input[name="type"]').remove();
    $("#sign-up-form").append(
      '<input type="hidden" name="type" value="email">',
    );
  });

  $(document).on("click", ".registration-section #phoneLogin", function (e) {
    e.preventDefault();
    // Hide the phone login link and show email login link
    $(".phone-login").addClass("d-none");
    $(".email-login").removeClass("d-none");

    // Show phone OTP send form and hide email signup form
    // Hide verify-otp-form initially since user needs to send OTP first
    $("#send-otp-form").show();
    $("#verify-otp-form").hide();
    $("#sign-up-form").hide();

    // Remove email type field if it exists
    $("#sign-up-form").find('input[name="type"][value="email"]').remove();
  });

  // Handle sign-up form submission
  $(document).on("submit", "#sign-up-form", function (e) {
    e.preventDefault();

    var email = $(this).find('input[name="email"]').val();
    var password = $(this).find('input[name="password"]').val();
    var confirmPassword = $('input[name="confirm_password"]').val();
    var username = $(this).find('input[name="username"]').val();
    var type = "email";
    var web_fcm = $("#web_fcm").val();
    var passwordRegex =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (!passwordRegex.test(password)) {
      Toast.fire({
        icon: "error",
        title:
          "Password must be at least 8 characters, with one uppercase letter, one lowercase letter, one number, and one special character.",
      });
      return;
    }

    if (password !== confirmPassword) {
      Toast.fire({
        icon: "error",
        title: "Password and Confirm Password do not match.",
      });
      return;
    }
    $.ajax({
      type: "POST",
      url: base_url + "home/verifyUser",
      data: {
        email: email,
        type: type,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;

        if (result.error == true) {
          // Create the user in Firebase
          firebase
            .auth()
            .createUserWithEmailAndPassword(email, password)
            .then(function (userCredential) {
              userCredential.user
                .sendEmailVerification()
                .then(() => {
                  Swal.fire({
                    title: "Verification Email Sent",
                    text: "Please check your email to verify your account before logging in.",
                    icon: "info",
                  });
                })
                .catch((error) => {
                  Toast.fire({
                    icon: "error",
                    title: "ERROR: " + error.message,
                  });
                });
              setTimeout(function () {
                userCredential.user.updateProfile({
                  displayName: username,
                });
              }, 2000);
            })
            .then(function () {
              var formData = {
                name: username,
                email: email,
                password: password,
                type: type,
                web_fcm: web_fcm,
                [csrfName]: csrfHash,
              };

              $.ajax({
                type: "POST",
                url: base_url + "auth/register_user",
                data: formData,
                dataType: "json",
                success: function (result) {
                  csrfName = result["csrfName"];
                  csrfHash = result["csrfHash"];
                  Toast.fire({
                    icon: "success",
                    title:
                      "Registration successful! Please verify your email before logging in.",
                  });

                  if (result.error == false) {
                    Swal.fire({
                      title: "Registration Complete",
                      text: "Your account has been created successfully. Please check your email and verify your account before logging in.",
                      icon: "success",
                      confirmButtonText: "OK",
                    });
                    setTimeout(function () {
                      location.reload();
                    }, 2000);
                  } else {
                    $("#sign-up-error").html(
                      '<div class="alert alert-danger">' +
                      result.message +
                      "</div>",
                    );
                  }
                },
              });
            })
            .catch(function (error) {
              // Handle Firebase errors
              var errorMessage = error.message;
              $("#sign-up-error").html(
                '<div class="alert alert-danger">' + errorMessage + "</div>",
              );
            });
        } else {
          $("#sign-up-error").html(
            '<div class="alert alert-danger">' +
            "Email is already registered" +
            "</div>",
          );
        }
      },
    });
  });

  function show_message(title, message, type) { }

  function cart_sync() {
    var cart = localStorage.getItem("cart");
    if (cart == null || !cart) {
      var message = "Please add items to cart";
      show_message("Oops!", message, "error");
      return;
    }
    $.ajax({
      type: "POST",
      url: base_url + "cart/cart_sync",
      data: {
        data: cart,
        is_saved_for_later: false,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });
          localStorage.removeItem("cart");
          return true;
        }
        Toast.fire({
          icon: "error",
          title: result.message,
        });
        localStorage.removeItem("cart");
        return true;
      },
    });
  }
  if (is_loggedin == 1) {
    cart_sync();
  }

  $(document).on("click", "#googleLogin", function (e) {
    var webFcmToken = $("#web_fcm").val();
    e.preventDefault();
    googleSignIn();
  });
  $(document).on("click", "#facebookLogin", function (e) {
    e.preventDefault();
    facebookSignIn();
  });

  function completeSocialLogin(loginResult) {
    if (typeof loginResult.csrfHash !== "undefined") {
      csrfHash = loginResult.csrfHash;
    }
    if (typeof loginResult.csrfName !== "undefined") {
      csrfName = loginResult.csrfName;
    }
    if (loginResult.error == false) {
      cart_sync();
      location.reload();
    } else {
      Toast.fire({
        icon: "error",
        title: loginResult.message || "Login failed. Please try again.",
      });
    }
  }

  function googleSignIn() {
    var provider = new firebase.auth.GoogleAuthProvider();
    provider.addScope("email");
    firebase
      .auth()
      .signInWithPopup(provider)
      .then(function (result) {
        var type = "google";
        var name = result.user.displayName;
        if (result.user.email != null && result.user.email != "") {
          var email = result.user.email;
        } else if (
          result.user.providerData[0].email != null &&
          result.user.providerData[0].email != ""
        ) {
          var email = result.user.providerData[0].email;
        } else {
          var email = result.additionalUserInfo.profile.email;
        }
        var password = result.user.uid;
        var webFcmToken = $("#web_fcm").val(); // Get the FCM token from the hidden fiel

        $.ajax({
          type: "POST",
          url: base_url + "home/verifyUser",
          data: {
            email: email,
            type: type,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];

            if (result.error == true) {
              $.ajax({
                type: "POST",
                url: base_url + "auth/register_user",
                data: {
                  type: type,
                  name: name,
                  email: email,
                  password: password,
                  web_fcm: $("#web_fcm").val(),
                  [csrfName]: csrfHash,
                },
                dataType: "json",
                success: function (result) {
                  csrfName = result["csrfName"];
                  csrfHash = result["csrfHash"];
                  if (result.error == false) {
                    $.ajax({
                      type: "POST",
                      url: base_url + "home/login",
                      data: {
                        identity: email,
                        type: type,
                        password: password,
                        web_fcm: $("#web_fcm").val(),
                        [csrfName]: csrfHash,
                      },
                      dataType: "json",
                      success: function (result) {
                        completeSocialLogin(result);
                      },
                    });
                  }
                },
              });
            } else {
              let vap_id_Key = $("#vap_id_Key").val();

              let web_fcm = "";

              const messaging = getMessaging();

              getToken(messaging, { vapidKey: vap_id_Key })
                .then((currentToken) => {
                  if (currentToken) {
                    web_fcm = updateWebFCM(currentToken, result.data.id);
                  } else {
                    console.log(
                      "No registration token available. Request permission to generate one.",
                    );
                  }
                })
                .catch((err) => {
                  console.log(
                    "An error occurred while retrieving token. ",
                    err,
                  );
                });
              $.ajax({
                type: "POST",
                url: base_url + "home/login",
                data: {
                  identity: email,
                  type: type,
                  password: password,
                  web_fcm,
                  [csrfName]: csrfHash,
                },
                dataType: "json",
                success: function (result) {
                  completeSocialLogin(result);
                },
              });
            }
          },
        });
      })
      .catch(function (error) { });
  }
  function facebookSignIn() {
    var provider = new firebase.auth.FacebookAuthProvider();
    firebase
      .auth()
      .signInWithPopup(provider)
      .then(function (result) {
        var type = "facebook";
        var name = result.user.displayName;
        var email = result.additionalUserInfo.profile.email;
        var password = result.user.uid;
        $.ajax({
          type: "POST",
          url: base_url + "home/verifyUser",
          data: {
            email: email,
            type: type,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result.error == true) {
              $.ajax({
                type: "POST",
                url: base_url + "auth/register_user",
                data: {
                  type: type,
                  name: name,
                  email: email,
                  password: password,
                  [csrfName]: csrfHash,
                },
                dataType: "json",
                success: function (result) {
                  csrfName = result["csrfName"];
                  csrfHash = result["csrfHash"];
                  if (result.error == false) {
                    $.ajax({
                      type: "POST",
                      url: base_url + "home/login",
                      data: {
                        identity: email,
                        type: type,
                        password: password,
                        [csrfName]: csrfHash,
                      },
                      dataType: "json",
                      success: function (result) {
                        completeSocialLogin(result);
                      },
                    });
                  }
                },
              });
            } else {
              $.ajax({
                type: "POST",
                url: base_url + "home/login",
                data: {
                  identity: email,
                  type: type,
                  password: password,
                  [csrfName]: csrfHash,
                },
                dataType: "json",
                success: function (result) {
                  completeSocialLogin(result);
                },
              });
            }
          },
        });
      })
      .catch(function (error) { });
  }

  function send_bank_receipt() {
    var order_id = $(this).data("order-id");
    $.ajax({
      type: "POST",
      url: base_url + "cart/send_bank_receipt",
      data: {
        [csrfName]: csrfHash,
        data: order_id,
      },
      dataType: "json",
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });
        }
      },
    });
  }

  $(document).on("click", ".ticket_button", function (e) {
    $(".display_fields").removeClass("d-none");
  });
  $(document).on("submit", "#create_ticket_form", function (event) {
    event.preventDefault(); // Prevent default form submission

    var type = $("#ticket_type").val();
    var email = $("#email").val();
    var subject = $("#subject").val();
    var description = $("#description").val();
    var id = $("#user_id").val();

    if (type === "") {
      Toast.fire({
        icon: "error",
        title: "Please select a ticket type",
      });
      return false;
    }

    $.ajax({
      type: "POST",
      data: {
        ticket_type_id: type,
        email: email,
        subject: subject,
        description: description,
        user_id: id,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      url: base_url + "Tickets/add_ticket",
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        if (result.error == false) {
          Toast.fire({
            icon: "success",
            title: result.message,
          });
          setTimeout(function () {
            location.reload();
          }, 600);
        } else {
          Toast.fire({
            icon: "error",
            title: result.message,
          });
        }
      },
      error: function (xhr, status, error) {
        Toast.fire({
          icon: "error",
          title: "An error occurred. Please try again.",
        });
      },
    });
  });

  $(".ticket-chat-link").click(function () {
    window.location = $(this).data("href");
  });

  $("#upload").on("change", function () {
    var imagePreviewContainer = $(".image-preview-container");
    imagePreviewContainer.empty(); // Clear existing previews

    var files = this.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var reader = new FileReader();

      reader.onload = function (e) {
        var imagePreview = $("<img>")
          .addClass("chat-image-preview")
          .attr("src", e.target.result);
        var imagePreviewtext = `<p class="m-0 text-black-50 text-center">Preview</p><ion-icon name="close-circle" class="preview-close fs-5 text-danger"></ion-icon>`;
        var previewWrapper = $("<div>")
          .addClass("preview-wrapper")
          .append(imagePreview, imagePreviewtext);
        imagePreviewContainer.append(previewWrapper);
      };

      reader.readAsDataURL(file);
    }
  });

  $(".image-preview-container").on("click", ".preview-close", function () {
    $(this).closest(".preview-wrapper").remove();
  });

  $("#ticket_send_msg_form").on("submit", function (e) {
    var user_type = $("#user_type").val();
    var user_id = $("#user_id").val();
    var ticket_id = $("#ticket_id").val();
    var message = $("#message_input").val();
    var attachments = $("#upload").val();

    e.preventDefault();
    $(".image-preview-container").empty().hide();
    $(".image-preview-container").show();
    if (message == "" && attachments == "") {
      Toast.fire({
        icon: "error",
        title: "You need to add atleast one image or message",
      });
    } else {
      function load_messages(element, ticket_id) {
        var limit = element.data("limit");
        var offset = element.data("offset");

        element.data("offset", limit + offset);
        var max_loaded = element.data("max-loaded");
        var loader =
          '<div class="loader text-center"><img src="' +
          base_url +
          'assets/pre-loader.gif" alt="Loading. please wait.. ." title="Loading. please wait.. ."></div>';
        $.ajax({
          type: "get",
          data:
            "ticket_id=" + ticket_id + "&limit=" + limit + "&offset=" + offset,
          url: base_url + "tickets/get_ticket_messages",
          beforeSend: function () {
            $(".ticket_msg").prepend(loader);
          },
          dataType: "json",
          cache: false,
          contentType: false,
          processData: false,
          success: function (result) {
            if (result.error == false) {
              if (result.error == false && result.data.length > 0) {
                var messages_html = "";
                var is_left = "";
                var is_right = "";
                var i = 1;
                result.data.reverse().forEach((messages) => {
                  var atch_html = "";
                  is_left = messages.user_type == "user" ? "left" : "right";
                  is_right = messages.user_type == "user" ? "right" : "left";
                  if (messages.attachments.length > 0) {
                    messages.attachments.forEach((atch) => {
                      atch_html +=
                        "<div class='container-fluid image-upload-section'>" +
                        "<a class='btn btn-danger btn-xs mr-1 mb-1' href='" +
                        atch.media +
                        "'  target='_blank' alt='Attachment Not Found'>Attachment " +
                        i +
                        "</a>" +
                        "<div class='col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none'></div>" +
                        "</div>";
                      i++;
                    });
                  }
                  messages_html +=
                    "<div class='direct-chat-msg " +
                    is_left +
                    "'>" +
                    "<div class='direct-chat-infos clearfix'>" +
                    "<span class='direct-chat-name float-" +
                    is_left +
                    "' id='name'>" +
                    messages.name +
                    "</span>" +
                    "<span class='direct-chat-timestamp float-" +
                    is_left +
                    "' id='last_updated'>" +
                    messages.last_updated +
                    "</span>" +
                    "</div>" +
                    "<div class='direct-chat-text' id='message'>" +
                    messages.message +
                    "</br>" +
                    atch_html +
                    "</div>" +
                    "</div>";
                });

                $(".ticket_msg").prepend(messages_html);
                $(".ticket_msg").find(".loader").remove();
                $(element).animate({
                  scrollTop: $(element).offset().top,
                });
              }
            } else {
              element.data("offset", offset);
              element.data("max-loaded", true);
              $(".ticket_msg").find(".loader").remove();
              $(".ticket_msg").prepend(
                '<div class="text-center"> <p>You have reached the top most message!</p></div>',
              );
            }
            $("#element").scrollTop(20); // Scroll alittle way down, to allow user to scroll more
            $(element).text();
            $(element).animate({
              scrollTop: $(element).offset().top,
            });
            return false;
          },
        });
      }

      var formdata = new FormData(this);
      formdata.append(csrfName, csrfHash);
      $.ajax({
        type: "POST",
        data: formdata,
        url: base_url + "Tickets/send_message",
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,

        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;
          if (result.error == false) {
            var time = result["data"].last_updated;
            if (result["data"].attachments[0] !== undefined) {
              var image = result["data"].attachments[0].media;
            }

            var div = $(".msg-body");
            var ticket_id = $("#ticket_id").val();
            load_messages(div, ticket_id);
            var message = $("#message_input").val();
            var last_added = $("#last_added").val();
            var div1 = document.createElement("div");
            var span = document.createElement("span");
            var messagediv = document.getElementById("message-ul");

            div1.setAttribute("class", "text-end py-2");
            if (image) {
              div1.innerHTML = `<div class="reply-box"><div class="reply"><img class="chat-img" src=${image}></div></div><p class="chat-send-time">${time}</p>`;
            } else {
              div1.innerHTML = `<div class="reply-box"><div class="reply">${message}</div></div><p class="chat-send-time">${time}</p>`;
            }

            messagediv.append(div1);
            document.getElementById("message_input").value = "";
            document.getElementById("upload").value = "";
          } else {
            $("#save-address-result")
              .html(
                "<div class='alert alert-danger'>" + result.message + "</div>",
              )
              .delay(1500)
              .fadeOut();
          }
          $("#save-address-submit-btn").val("Save");
        },
      });
    }
  });

  if ($("#element").length) {
    $("#element").scrollTop($("#element")[0].scrollHeight);
    $("#element").scroll(function () {
      if ($("#element").scrollTop() == 0) {
        load_messages($(".ticket_msg"), ticket_id);
      }
    });

    $("#element").bind("mousewheel", function (e) {
      if (e.originalEvent.wheelDelta / 120 > 0) {
        if ($(".ticket_msg")[0].scrollHeight < 370 && scrolled == 0) {
          load_messages($(".ticket_msg"), ticket_id);
          scrolled = 1;
        }
      }
    });
  }
});

// refer and earn code
function copyText() {
  const text = $("#text-to-copy").text().trim();

  const tempInput = $("<input>");
  tempInput.attr("type", "text");
  tempInput.val(text);
  $("body").append(tempInput);

  tempInput.select();
  document.execCommand("copy");

  tempInput.remove();

  const copyButton = $(".copy-button");
  copyButton.text("Copied !");
  setTimeout(function () {
    copyButton.text("Tap to copy");
  }, 1000);
}

// Attach the copyText function to the button's click event
$(document).ready(function () {
  $(".copy-button").click(copyText);
});

$(document).on("submit", ".sign-up-form", function (e) {
  e.preventDefault();

  // Check if this is phone registration (has phone-number field) or email registration
  var isPhoneRegistration =
    $("#phone-number").length > 0 && $("#phone-number").is(":visible");
  var registrationType = $(this).find('input[name="type"]').val();

  // If this is email registration, let the ID-based handler (#sign-up-form) handle it
  if (registrationType === "email" || !isPhoneRegistration) {
    return;
  }

  var countrycode = $(".selected-dial-code").html();
  var $phonenumber = $("#phone-number").val();
  var $username = $('input[name="username"]').val();
  var $email = $('input[name="email"]').val();
  var $passwd = $('input[name="password"]').val();

  // Only proceed if we have a valid phone number
  if (!$phonenumber) {
    return;
  }

  $.ajax({
    type: "POST",
    url: base_url + "auth/register_user",
    data: {
      country_code: countrycode,
      mobile: $phonenumber,
      name: $username,
      email: $email,
      password: $passwd,
      [csrfName]: csrfHash,
    },
    dataType: "json",
    success: function (result) {
      csrfName = result["csrfName"];
      csrfHash = result["csrfHash"];
      if (result.error == true) {
        $("#sign-up-error").html(
          '<span class="text-danger" >' + response.message + "</span>",
        );
      }
    },
  });
});

// js social
if ($(".quick_view_share").length > 0)
  $(".quick_view_share").jsSocials({
    showLabel: false,
    showCount: false,
    shares: [
      {
        share: "facebook",
        logo:
          base_url +
          "assets/front_end/modern/ionicons/dist/ionicons/svg/logo-facebook.svg",
      },
      {
        share: "whatsapp",
        logo:
          base_url +
          "assets/front_end/modern/ionicons/dist/svg/logo-whatsapp.svg",
      },
      {
        share: "twitter",
        logo:
          base_url +
          "assets/front_end/modern/ionicons/dist/svg/logo-twitter.svg",
      },
      {
        share: "linkedin",
        logo:
          base_url +
          "assets/front_end/modern/ionicons/dist/svg/logo-linkedin.svg",
      },
    ],
  });

// color switcher
jQuery(document).ready(function ($) {
  $("ul.color-style .blue").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/blue.css",
    );
    return false;
  });

  $("ul.color-style .cyan-dark").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/cyan-dark.css",
    );
    return false;
  });

  $("ul.color-style .dark-blue").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/dark-blue.css",
    );
    return false;
  });

  $("ul.color-style .dark-purple").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/dark-purple.css",
    );
    return false;
  });

  $("ul.color-style .default").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/default.css",
    );
    return false;
  });

  $("ul.color-style .green").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/green.css",
    );
    return false;
  });

  $("ul.color-style .indigo").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/indigo.css",
    );
    return false;
  });

  $("ul.color-style .orange").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/orange.css",
    );
    return false;
  });

  $("ul.color-style .peach").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/peach.css",
    );
    return false;
  });

  $("ul.color-style .pink").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/pink.css",
    );
    return false;
  });
  $("ul.color-style .purple").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/purple.css",
    );
    return false;
  });
  $("ul.color-style .red").click(function () {
    $("#color-switcher").attr(
      "href",
      base_url + "assets/front_end/modern/css/colors/red.css",
    );
    return false;
  });

  $("ul.color-style li a").click(function (e) {
    e.preventDefault();
    $(this).parent().parent().find("a").removeClass("active");
    $(this).addClass("active");
  });

  $("#colors-switcher .color-bottom a.settings").click(function (e) {
    e.preventDefault();
    var div = $("#colors-switcher");
    if (div.css(mode) === "-189px") {
      $("#colors-switcher").animate({
        [mode]: "0px",
      });
    } else {
      $("#colors-switcher").animate({
        [mode]: "-189px",
      });
    }
  });
  $("#colors-switcher").animate({
    [mode]: "-189px",
  });
});

// initiate the plugin and pass the id of the div containing gallery images
$(".zoom_03").elevateZoom({
  gallery: "gallery_01",
  cursor: "pointer",
  easing: true,
  // scrollZoom:true,
  galleryActiveClass: "active",
  imageCrossfade: true,
  borderSize: 2,
  loadingIcon: "https://www.elevateweb.co.uk/spinner.gif",
});

$(this).removeClass("d-none");
$(".send-otp-form").show();
$("#verify-otp-form").addClass("d-none");
$(".sign-up-form").hide();
$("#is-user-exist-error").html("");
$("#sign-up-error").html("");

if (auth_settings == "firebase") {
  if ($("#recaptcha-container").length > 0) {
    $("#recaptcha-container").html("");
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
      "recaptcha-container",
    );
    window.recaptchaVerifier.render().then(function (widgetId) {
      grecaptcha.reset(widgetId);
    });
  }
}

var telInput = $("#phone-number"),
  errorMsg = $("#error-msg"),
  validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({
  allowExtensions: true,
  formatOnDisplay: true,
  autoFormat: true,
  autoHideDialCode: true,
  autoPlaceholder: true,
  defaultCountry: "in",
  ipinfoToken: "yolo",

  nationalMode: false,
  numberType: "MOBILE",
  preferredCountries: ["in", "ae", "qa", "om", "bh", "kw", "ma"],
  preventInvalidNumbers: true,
  separateDialCode: true,
  initialCountry: "auto",
  geoIpLookup: function (callback) {
    $.get("https://ipinfo.io", function () { }, "jsonp").always(function (resp) {
      var countryCode = resp && resp.country ? resp.country : "";
      callback(countryCode);
    });
  },
  utilsScript:
    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
});

var reset = function () {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function () {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
    }
  }
});
// on keyup / change flag: reset
telInput.on("keyup change", reset);
$(".cancel-btn-forget-password").on("click", function () {
  $(".register-login-section").show();
  $(".forget-password-section").hide();
});

$(".forget_password_sec").on("click", function () {
  var fragment = "register#forget-password-section";
  var currentURL = window.location.href;
  if (currentURL.includes("#forget-password-section")) {
    window.location.reload();
  } else {
    var newURL = base_url + fragment;
    // Reload the page with the updated URL
    window.location.href = newURL;
    window.location.reload();
  }
});

if (window.location.hash === "#forget-password-section") {
  var currentURL = window.location.href;
  $(".forget-password-section").fadeIn(300);
  $(".register-login-section").hide();
  $("#forgot_password_div")
    .removeClass("hide")
    .siblings("section")
    .addClass("hide");
  if (auth_settings == "firebase") {
    if ($("#recaptcha-container-2").length > 0) {
      $("#recaptcha-container-2").html("");
      window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
        "recaptcha-container-2",
      );
    }
    window.recaptchaVerifier.render().then(function (widgetId) {
      grecaptcha.reset(widgetId);
    });
  }
  var telInput = $("#forgot_password_number");
  // initialise plugin
  telInput.intlTelInput({
    allowExtensions: true,
    formatOnDisplay: true,
    autoFormat: true,
    autoHideDialCode: true,
    autoPlaceholder: true,
    defaultCountry: "in",
    ipinfoToken: "yolo",

    nationalMode: false,
    numberType: "MOBILE",
    preferredCountries: ["in", "ae", "qa", "om", "bh", "kw", "ma"],
    preventInvalidNumbers: true,
    separateDialCode: true,
    initialCountry: "auto",
    geoIpLookup: function (callback) {
      $.get("https://ipinfo.io", function () { }, "jsonp").always(function (
        resp,
      ) {
        var countryCode = resp && resp.country ? resp.country : "";
        callback(countryCode);
      });
    },
    utilsScript:
      "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.js",
  });
}

$(".cancel_reload").on("click", function (e) {
  window.location = window.location.href.split("#")[0];
});

/**
 * Function called when clicking the Login/Logout button.
 */
$("#send-otp-form").validate({
  rules: {
    mobileNumber: {
      required: true,
      number: true,
      minlength: 10,
      maxlength: 10,
    },
  },
});

if (auth_settings == "firebase") {
  function onSignInSubmit(e) {
    e.preventDefault();

    var recaptchaResponse = grecaptcha.getResponse();
    if (!recaptchaResponse) {
      $("#recaptcha-error").html("Please complete the reCAPTCHA.");
      return;
    }

    if (isPhoneNumberValid()) {
      $("#send-otp-button").html("Please Wait...");
      var response = is_user_exist();
      updateSignInButtonUI();

      if (response.error == true) {
        $("#is-user-exist-error").html(response.message);
        $("#send-otp-button").html("Send OTP");
      } else {
        window.signingIn = true;

        var phoneNumber = getPhoneNumberFromUserInput();
        var appVerifier = window.recaptchaVerifier;

        firebase
          .auth()
          .signInWithPhoneNumber(phoneNumber, appVerifier)
          .then(function (confirmationResult) {
            $("#send-otp-button").html("Send OTP");
            window.signingIn = false;
            updateSignInButtonUI();
            resetRecaptcha();
            $("#send-otp-form").hide();
            $("#otp_div").show();
            $("#verify-otp-form").removeClass("d-none");

            $(document).on("submit", "#verify-otp-form", function (e) {
              e.preventDefault();
              $("#registration-error").html("");
              var code = $("#otp").val();
              var formdata = new FormData(this);
              var url = $(this).attr("action");
              $("#register_submit_btn").html("Please Wait...");
              confirmationResult
                .confirm(code)
                .then(function (result) {
                  formdata.append(csrfName, csrfHash);
                  formdata.append("mobile", $("#phone-number").val());
                  formdata.append(
                    "country_code",
                    $(".selected-dial-code").text(),
                  );
                  $.ajax({
                    type: "POST",
                    url: url,
                    data: formdata,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "json",
                    beforeSend: function () {
                      $("#register_submit_btn")
                        .html(
                          '<div class="spinner-border" role="status">' +
                          '<span class="visually-hidden">Loading...</span>' +
                          "</div>",
                        )
                        .attr("disabled", true);
                    },
                    success: function (result) {
                      csrfName = result.csrfName;
                      csrfHash = result.csrfHash;
                      $("#register_submit_btn").html("Submit");
                      $("#registration-error").html(result.message).show();
                      if (result.error == false) {
                        $(".registration-section").hide();
                        $(".login-section").show();
                      }
                    },
                  });
                })
                .catch(function (error) {
                  $("#register_submit_btn").html("Please Wait...");
                  $("#registration-error")
                    .html("Invalid OTP. Please Enter Valid OTP")
                    .show();
                  $("#register_submit_btn").html("Submit");
                });
            });
          })
          .catch(function (error) {
            window.signingIn = false;
            $("#is-user-exist-error").html(error.message).show();
            $("#send-otp-button").html("Send OTP");
            updateSignInButtonUI();
            resetRecaptcha();
          });
      }
    }
  }
  /**
   * Set up UI event listeners and registering Firebase auth listeners.
   */
  window.onload = function () {
    // Event bindings.
    $("#send-otp-form").on("submit", onSignInSubmit);
    $("#phone-number").on("keyup change", updateSignInButtonUI);

    var offerPopupValue = $("#offer_popup_value").val();
    if (
      offerPopupValue === "refresh" ||
      offerPopupValue === "session_storage"
    ) {
      $("#offer_popup").modal("show");
    }
  };
  /**
   * * Reads the phone number from the user input.
   */
  function getPhoneNumberFromUserInput() {
    var countrycode = $(".selected-dial-code").html();
    var phonenumber = $("#phone-number").val();
    return countrycode + phonenumber;
  }

  /**
   * Returns true if the phone number is valid.
   */
  function isPhoneNumberValid() {
    var pattern = /^\+[0-9\s\-\(\)]+$/;
    var phoneNumber = getPhoneNumberFromUserInput();
    return phoneNumber.search(pattern) !== -1;
  }
}

if (auth_settings == "sms") {
  $(document).on("click", "#send-otp-button", function (e) {
    e.preventDefault();
    var t = $("#phone-number").val();
    $.ajax({
      type: "POST",
      async: !1,
      url: base_url + "auth/verify_user",
      data: {
        mobile: t,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      success: function (e) {
        (csrfName = e.csrfName), (csrfHash = e.csrfHash);
        if (e.error == true) {
          $("#registration-user-error").html(e.message).show();
          Toast.fire({
            icon: "error",
            title: e.message,
          });
        } else {
          $("#send-otp-form").hide(),
            $("#verify-otp-form").removeClass("d-none");
          Toast.fire({
            icon: "success",
            title: e.message,
          });
        }
      },
    });
  });

  $(document).on("submit", "#verify-otp-form", function (e) {
    e.preventDefault();
    $("#registration-error").html("");
    var code = $("#otp").val();
    var formdata = new FormData(this);
    var url = $(this).attr("action");
    $("#register_submit_btn").html("Please Wait...");
    formdata.append(csrfName, csrfHash);
    formdata.append("mobile", $("#phone-number").val());
    formdata.append("country_code", $(".selected-dial-code").text());
    $.ajax({
      type: "POST",
      url: url,
      data: formdata,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",
      beforeSend: function () {
        $("#register_submit_btn")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        $("#register_submit_btn").html("Submit");
        $("#registration-error").html(result.message).show();
        if (result.error == false) {
          $(".registration-section").hide();
          $(".login-section").show();
        }
      },
    });
  });

  $(document).on("click", "#forgot_password_send_otp_btn", function (e) {
    e.preventDefault();
    var t = $("#forgot_password_number").val();
    var forget_password_val = $("#forget_password_val").val();

    $.ajax({
      type: "POST",
      async: !1,
      url: base_url + "auth/verify_user",
      data: {
        mobile: t,
        forget_password_val: forget_password_val,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      success: function (e) {
        (csrfName = e.csrfName),
          (csrfHash = e.csrfHash),
          $("#verify_forgot_password_otp_form").removeClass("d-none");
        $("#send_forgot_password_otp_form").hide();
        $("#verify-otp-form").removeClass("d-none");
      },
    });
  });

  $(document).on("submit", "#verify_forgot_password_otp_form", function (e) {
    e.preventDefault();
    var reset_pass_btn_html = $("#reset_password_submit_btn").html();
    var code = $("#forgot_password_otp").val();
    var formdata = new FormData(this);
    var url = base_url + "home/reset-password";
    $("#reset_password_submit_btn")
      .html("Please Wait...")
      .attr("disabled", true);
    formdata.append(csrfName, csrfHash);
    formdata.append("mobile", $("#forgot_password_number").val());
    formdata.append("forget_password_val", $("#forget_password_val").val());
    $.ajax({
      type: "POST",
      url: url,
      data: formdata,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",
      beforeSend: function () {
        $("#reset_password_submit_btn")
          .html(
            '<div class="spinner-border" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            "</div>",
          )
          .attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        $("#reset_password_submit_btn")
          .html(reset_pass_btn_html)
          .attr("disabled", false);
        $("#set_password_error_box").html(result.message).show();
        if (result.error == false) {
          window.location = window.location.href.split("#")[0];
          setTimeout(function () {
            $(".forget-password-section").hide();
            $(".register-login-section").show();
            $("register_div").hide();
            $(".login-section").show();
          }, 2000);
        }
      },
    });
  });
}

$(document).on("click", "#resend-otp", function (e) {
  e.preventDefault();
});

/**
 * This resets the recaptcha widget.
 */
function resetRecaptcha() {
  return window.recaptchaVerifier.render().then(function (widgetId) {
    grecaptcha.reset(widgetId);
  });
}

window.addEventListener("DOMContentLoaded", function () {
  if (window.location.hash === "#forget-password-section") {
    document.getElementById("forget-password-section").classList.add("active");
  }
});

var galleryTop = new Swiper(".gallery-top-1", {
  spaceBetween: 10,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});

//preview-image-swiper
var swiperF = new Swiper(".preview-image-swiper", {
  pagination: {
    el: ".preview-image-swiper-pagination",
  },
});
/**
 * Updates the Sign-in button state depending on ReCaptcha and form values state.
 */
function updateSignInButtonUI() { }

function is_user_exist(phone_number = "") {
  if (phone_number == "") {
    var phoneNumber = $("#phone-number").val();
  } else {
    var phoneNumber = phone_number;
  }
  var response;
  $.ajax({
    type: "POST",
    async: false,
    url: base_url + "auth/verify_user",
    data: {
      mobile: phoneNumber,
      [csrfName]: csrfHash,
    },
    dataType: "json",
    success: function (result) {
      csrfName = result["csrfName"];
      csrfHash = result["csrfHash"];
      response = result;
    },
  });
  return response;
}

$(document).ready(function () {
  // Submit chat message to backend on form submit
  $(".reorder-btn").on("click", (event) => {
    const variants = $(event.target).data("variants") + "";
    const qty = $(event.target).data("quantity") + "";
    let html = $(event.target).html();
    $.ajax({
      type: "POST",
      url: base_url + "cart/manage",
      data: {
        product_variant_id: variants,
        qty: qty,
        is_saved_for_later: false,
        [csrfName]: csrfHash,
      },
      dataType: "json",
      beforeSend: function () {
        $(event.target).text("Please Wait").attr("disabled", true);
      },
      success: function (res) {
        if (!res.error) {
          csrfName = res.csrfName;
          csrfHash = res.csrfHash;
          $(event.target).text(html).attr("disabled", false);
          window.location.href = base_url + "cart/checkout";
        } else {
          csrfName = res.csrfName;
          csrfHash = res.csrfHash;
          $(event.target).text(html).attr("disabled", false);
          Toast.fire({
            icon: "error",
            title: res.message,
          });
        }
      },
    });
  });
});

function updateWebFCM(token) {
  var fcmtoken = token;
  $.ajax({
    url: base_url + "my-account/update_web_fcm",
    type: "POST",
    data: csrfName + "=" + csrfHash + "&web_fcm=" + fcmtoken,
    dataTpe: "json",
    success: function (result) {
      var data = JSON.parse(result);
      (csrfName = data.csrfName), (csrfHash = data.csrfHash);
    },
  });
}

$(document).ready(function () {
  // Show/hide chat iframe on chat button click
  $("#chat-button").on("click", function (e) {
    e.preventDefault();
    $("#chat-iframe").toggle();
    $(this).toggleClass("opened");
    $("#chat-iframe").toggleClass("opened");
  });
  $("#chat-with-button").on("click", function (e) {
    e.preventDefault();
    $("#chat-iframe").attr(
      "src",
      base_url +
      "my-account/floating_chat_modern?user_id=" +
      $(this).data("id"),
    );
    $("#chat-iframe").toggle();
    // $(this).toggleClass("opened");
    $("#chat-iframe").toggleClass("opened");
  });
});

//Scroll back to top
var progressPath = document.querySelector(".progress-wrap path");
var pathLength = progressPath.getTotalLength();
progressPath.style.transition = progressPath.style.WebkitTransition = "none";
progressPath.style.strokeDasharray = pathLength + " " + pathLength;
progressPath.style.strokeDashoffset = pathLength;
progressPath.getBoundingClientRect();
progressPath.style.transition = progressPath.style.WebkitTransition =
  "stroke-dashoffset 10ms linear";
var updateProgress = function () {
  var scroll = $(window).scrollTop();
  var height = $(document).height() - $(window).height();
  var progress = pathLength - (scroll * pathLength) / height;
  progressPath.style.strokeDashoffset = progress;
};
updateProgress();
$(window).scroll(updateProgress);
var offset = 50;
var duration = 550;
jQuery(window).on("scroll", function () {
  if (jQuery(this).scrollTop() > offset) {
    jQuery(".progress-wrap").addClass("d-block active-progress");
    jQuery(".progress-wrap").removeClass("d-none");
  } else {
    jQuery(".progress-wrap").removeClass("active-progress");
    jQuery(".progress-wrap").addClass("d-none");
  }
});
jQuery(".progress-wrap").on("click", function (event) {
  event.preventDefault();
  jQuery("html, body").animate(
    {
      scrollTop: 0,
    },
    duration,
  );
  return false;
});
var theme = {
  /**
   * Theme's components/functions list
   * Comment out or delete the unnecessary component.
   * Some components have dependencies (plugins).
   * Do not forget to remove dependency from src/js/vendor/ and recompile.
   */
  init: function () {
    theme.pageProgress();
  },
  pageProgress: () => {
    var progressWrap = document.querySelector(".progress-wrap");
    if (progressWrap != null) {
      var progressPath = document.querySelector(".progress-wrap path");
      var pathLength = progressPath.getTotalLength();
      var offset = 50;
      progressPath.style.transition = progressPath.style.WebkitTransition =
        "none";
      progressPath.style.strokeDasharray = pathLength + " " + pathLength;
      progressPath.style.strokeDashoffset = pathLength;
      progressPath.getBoundingClientRect();
      progressPath.style.transition = progressPath.style.WebkitTransition =
        "stroke-dashoffset 10ms linear";
      window.addEventListener("scroll", function (event) {
        var scroll =
          document.body.scrollTop || document.documentElement.scrollTop;
        var height =
          document.documentElement.scrollHeight -
          document.documentElement.clientHeight;
        var progress = pathLength - (scroll * pathLength) / height;
        progressPath.style.strokeDashoffset = progress;
        var scrollElementPos =
          document.body.scrollTop || document.documentElement.scrollTop;
        if (scrollElementPos >= offset) {
          progressWrap.classList.add("active-progress");
        } else {
          progressWrap.classList.remove("active-progress");
        }
      });
      progressWrap.addEventListener("click", function (e) {
        e.preventDefault();
        window.scroll({
          top: 0,
          left: 0,
          behavior: "smooth",
        });
      });
    }
  },
};
theme.init();

// Toggle "Other" reason field visibility for each modal
// Toggle "Other" reason field visibility
$(document).on("change", '[name^="return_reason_"]', function () {
  var itemId = $(this).attr("name").split("_").pop();
  $(`#otherReasonField_${itemId}`).toggle(this.value === "other");
});

// Handle Confirm Return button click
$(document).on("click", ".confirmReturn", function (e) {
  e.preventDefault();

  let itemId = $(this).data("item-id");
  let $button = $(this);
  let status = $(`#status_${itemId}`).val(); // Should be 'return_request_pending'
  let selectedReason = $(`input[name="return_reason_${itemId}"]:checked`).val();
  let otherReason = $(`#otherReasonField_${itemId}`).val();
  let returnImages = $(`#return_item_image_${itemId}`)[0].files;

  if (!selectedReason) {
    Toast.fire({
      icon: "error",
      title: "Please select a return reason.",
    });
    return;
  }

  let formData = new FormData();
  formData.append("order_item_id", itemId);
  formData.append("status", status);
  formData.append("return_reason", selectedReason);
  if (selectedReason === "other") {
    formData.append("other_reason", otherReason);
  }
  for (let i = 0; i < returnImages.length; i++) {
    formData.append("return_item_images[]", returnImages[i]);
  }
  formData.append(csrfName, csrfHash);

  $.ajax({
    type: "POST",
    url: base_url + "my-account/update-order-item-status",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    beforeSend: function () {
      $button.prop("disabled", true).text("Processing...");
    },
    success: function (response) {
      csrfName = response.csrfName;
      csrfHash = response.csrfHash;
      Toast.fire({
        icon: response.error ? "error" : "success",
        title: response.message,
      });
      if (!response.error) {
        $(`#returnModal_${itemId}`).modal("hide");
        setTimeout(() => window.location.reload(), 3000);
      }
      $button.prop("disabled", false).text("Confirm Return");
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", xhr.responseText, status, error);
      Toast.fire({
        icon: "error",
        title:
          "Server error: " + (xhr.responseJSON?.message || "Please try again."),
      });
      $button.prop("disabled", false).text("Confirm Return");
    },
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const otherReasonRadio = document.getElementById("otherReasonRadio");
  const otherReasonField = document.getElementById("otherReasonField");
  const reasonRadios = document.querySelectorAll(".reason-radio");

  reasonRadios.forEach((radio) => {
    radio.addEventListener("change", function () {
      if (this.value === "other") {
        otherReasonField.style.display = "block";
        otherReasonField.focus(); // Auto-focus the input field
      } else {
        otherReasonField.style.display = "none";
      }
    });
  });
});

document.querySelectorAll(".togglePassword").forEach(function (toggle) {
  toggle.addEventListener("click", function () {
    const input = this.previousElementSibling; // Find the input just before the button
    const icon = this.querySelector("i"); // Get the eye icon
    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  });
});
$(document).ready(function () {
  $(".logout-link").click(function (e) {
    e.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, log out!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: base_url + "login/logout",
          type: "POST",
          success: function () {
            Toast.fire({
              icon: "success",
              title: "Logged out successfully!",
            });
            location.reload();
          },
        });
      }
    });
  });
});

$("#send_bank_receipt_form").on("submit", function (e) {
  e.preventDefault();

  // Check if at least one file is selected
  var files = $("#receipt").prop("files");
  if (files.length === 0) {
    Toast.fire({
      icon: "error",
      title: "Please attach at least one file before submitting.",
    });
    return false;
  }

  var formdata = new FormData(this);
  formdata.append(csrfName, csrfHash);
  Swal.fire({
    title: "Are you sure?",
    text: "You are about to upload a bank payment receipt. Do you want to proceed?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, upload it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: $(this).attr("action"),
        data: formdata,
        beforeSend: function () {
          $("#submit_btn").html("Please Wait..").attr("disabled", true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
          csrfHash = result.csrfHash;
          $("#submit_btn").html("Send").attr("disabled", false);

          if (result.error === false) {
            Toast.fire({
              icon: "success",
              title:
                result.message || "Bank payment receipt uploaded successfully.",
            });

            // ✅ Reload SAME page
            setTimeout(function () {
              window.location.reload(true);
            }, 1200);
          } else {
            Toast.fire({
              icon: "error",
              title: result.message || "Failed to upload receipt.",
            });
          }
        },
        error: function () {
          $("#submit_btn").html("Send").attr("disabled", false);
          Toast.fire({
            icon: "error",
            title: "An error occurred while uploading the receipt.",
          });
        },
      });
    }
  });
});

window.isValidPhoneChar = function (event) {
  const charCode = event.charCode || event.keyCode;
  if (
    (charCode >= 48 && charCode <= 57) ||
    charCode === 43 ||
    charCode === 8 ||
    charCode === 0
  ) {
    return true;
  }
  return false;
};

$(document).ready(() => {
  $("#forgot-password-email-link").on("click", function (e) {
    e.preventDefault();
    // Hide all phone-related forms
    $("#send_forgot_password_otp_form").hide();
    $("#verify_forgot_password_otp_form").addClass("d-none").hide();
    // Show email form and switch link
    $("#forgot-password-email-form").removeClass("d-none").show();
    $("#forgot-password-phone-div").removeClass("d-none").show();
    $("#forgot-password-email-link").addClass("d-none");
    // Clear any error messages and reset forms
    $("#forgot_pass_error_box").html("");
    $("#set_password_error_box").html("");
    $("#forgot_pass_email_error_box").html("");
    $("#forgot_password_number").val("");
    $("#forgot_password_otp").val("");
  });

  $("#forgot-password-phone").on("click", function (e) {
    e.preventDefault();
    // Hide email form and switch link
    $("#forgot-password-email-form").addClass("d-none").hide();
    $("#forgot-password-phone-div").addClass("d-none").hide();
    // Show phone forms and reset to initial state
    $("#send_forgot_password_otp_form").show();
    $("#verify_forgot_password_otp_form").addClass("d-none").hide();
    $("#forgot-password-email-link").removeClass("d-none");
    // Clear any error messages and reset forms
    $("#forgot_pass_email_error_box").html("");
    $("#forgot_pass_error_box").html("");
    $("#set_password_error_box").html("");
    $("#forgot_password_email").val("");
    $("#forgot_password_number").val("");
    $("#forgot_password_otp").val("");
    window.location.href = base_url + "/register#forget-password-section";
  });

  // Handle Firebase email reset
  $("#forgot-password-email-form").on("submit", function (e) {
    e.preventDefault();
    var email = $("#forgot_password_email").val();
    if (!email) {
      $("#forgot_pass_email_error_box").html(
        '<span class="text-danger">Please enter your email.</span>',
      );
      return;
    }
    $("#send_firebase_reset_email_btn")
      .attr("disabled", true)
      .text("Sending...");
    firebase
      .auth()
      .sendPasswordResetEmail(email)
      .then(function () {
        $("#forgot_pass_email_error_box").html(
          '<span class="text-success">Password reset email sent! Please check your inbox.</span>',
        );
        $("#send_firebase_reset_email_btn")
          .attr("disabled", false)
          .text("Send Reset Email");
      })
      .catch(function (error) {
        $("#forgot_pass_email_error_box").html(
          '<span class="text-danger">' + error.message + "</span>",
        );
        $("#send_firebase_reset_email_btn")
          .attr("disabled", false)
          .text("Send Reset Email");
      });
  });

  let paramQueries = new URLSearchParams(window.location.search);
  if (window.location.pathname.includes("register")) {
    let mode = paramQueries.get("mode");
    let oobCode = paramQueries.get("oobCode");

    let registerForm = document.getElementById("register_div");
    let loginText = document.getElementById("login-text");
    let resetForm = document.getElementById("resetForm");

    if (mode === "verifyEmail" && oobCode) {
      // Handle email verification
      if (registerForm) registerForm.style.display = "none";
      if (loginText) loginText.style.display = "none";
      if (resetForm) resetForm.style.display = "none";

      // Apply the email verification code
      firebase
        .auth()
        .applyActionCode(oobCode)
        .then(() => {

          Toast.fire({
            title: "Email Verified!",
            icon: "success",
          }).then(() => {
            location.href = base_url;
          });
        })
        .catch((error) => {

          let errorMessage = "Invalid or expired verification link";

          // Handle specific error codes
          switch (error.code) {
            case "auth/expired-action-code":
              errorMessage =
                "This verification link has expired. Please request a new one.";
              break;
            case "auth/invalid-action-code":
              errorMessage =
                "This verification link is invalid or has already been used.";
              break;
            case "auth/user-disabled":
              errorMessage = "This user account has been disabled.";
              break;
            case "auth/user-not-found":
              errorMessage = "No user found for this verification link.";
              break;
          }

          Swal.fire({
            title: "Verification Failed",
            text: errorMessage,
            icon: "error",
            confirmButtonText: "Go to Homepage",
            allowOutsideClick: false,
            allowEscapeKey: false,
          }).then(() => {
            location.href = base_url;
          });
        });
    } else if (mode === "resetPassword" && oobCode) {
      // Hide registration form
      if (registerForm) registerForm.style.display = "none";
      if (loginText) loginText.style.display = "none";
      if (resetForm) resetForm.style.display = "block";

      // Verify reset code
      firebase
        .auth()
        .verifyPasswordResetCode(oobCode)
        .then((email) => {

          Toast.fire({
            icon: "success",
            title: "Enter your new Password",
          });
          window.resetEmail = email;
        })
        .catch((error) => {

          Toast.fire({
            icon: "error",
            title: "Invalid or expired link",
          });
          resetForm.style.display = "none";
          delete window.resetEmail;
          location.href = base_url;
        });

      // Handle reset form submit
      resetForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const newPassword = document.getElementById("newPassword").value;
        const email = window.resetEmail;

        firebase
          .auth()
          .confirmPasswordReset(oobCode, newPassword)
          .then(() => {

            // Update password in Database
            $.ajax({
              url: "home/reset-password",
              type: "POST",
              data: {
                email: email,
                new_password: newPassword,
              },
              beforeSend: function () {
                console.log(
                  "Sending request to reset MySQL password..." +
                  email +
                  " " +
                  newPassword,
                );
              },
              success: function (response) {
                const res = JSON.parse(response);
                if (res.error) {

                  Toast.fire({
                    icon: "error",
                    title: res.message,
                  });
                } else {

                  Toast.fire({
                    icon: "success",
                    title:
                      "Password has been reset successfully in both systems!",
                  });
                  resetForm.style.display = "none";
                  location.href = base_url;
                }
              },
              error: function (xhr, status, error) {

                Toast.fire({
                  icon: "error",
                  title: "Failed to update password in database",
                });
                delete window.resetEmail;
              },
            });
          })
          .catch((error) => {

            Toast.fire({
              icon: "error",
              title: "Invalid or expired link",
            });
            delete window.resetEmail;
            location.href = base_url;
          });
      });
    } else {
      if (registerForm) registerForm.style.display = "block";
      if (resetForm) resetForm.style.display = "none";
    }
  }

  $(".quickview-variant-sec").each(function () {
    var $swatchGroup = $(this);
    var $swatches = $swatchGroup.find(".swatch");

    if ($swatches.length === 1) {
      var $radioInput = $swatches.find('input[type="radio"].attributes');
      if ($radioInput.length > 0 && !$radioInput.prop("checked")) {
        $radioInput.prop("checked", true);
        $swatches
          .addClass("border-active")
          .siblings()
          .removeClass("border-active");
        $radioInput.trigger("change");
      }
    }
  });

  $(document).on("shown.bs.modal", "#quickview", function () {
    $(".quickview-variant-sec").each(function () {
      var $swatchGroup = $(this);
      var $swatches = $swatchGroup.find(".swatch");

      // If this attribute group has only one swatch, auto-select it
      if ($swatches.length === 1) {
        var $radioInput = $swatches.find(
          'input[type="radio"].modal-product-attributes',
        );
        if ($radioInput.length > 0 && !$radioInput.prop("checked")) {
          $radioInput.prop("checked", true);
          // Add visual selection for swatch
          $swatches
            .addClass("border-active")
            .siblings()
            .removeClass("border-active");
          // Trigger the change event to update pricing and other logic
          $radioInput.trigger("change");
        }
      }
    });
  });

  // Cart page specific plus/minus button functionality with high priority
  $(document).on(
    "click",
    ".num-block .plus-btn, .num-block .minus-btn",
    function (e) {
      if (is_loggedin == 0) {
        $("#login-canvas").offcanvas("show");
        return;
      }
      var input = $(this).parents(".num-block").find("input");

      if ($(this).hasClass("quickview-btn")) {
        return;
      }

      // Only handle if we're on the cart page and user is logged in
      if (input.data("page") === "cart" && is_loggedin == 1) {
        // Stop all event propagation to prevent the general handler from running
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var product_variant_id = input.data("id");
        var current_qty = parseInt(input.val());
        var step = parseInt($(this).data("step")) || 1;
        var min = parseInt($(this).data("min")) || 1;
        var max = parseInt($(this).data("max")) || 999999;
        var new_qty = current_qty;

        if ($(this).hasClass("plus-btn")) {
          new_qty = current_qty + step;
          if (max > 0 && new_qty > max) {
            new_qty = max;
            Toast.fire({
              icon: "error",
              title: "Maximum allowed quantity is " + max,
            });
          }
        } else if ($(this).hasClass("minus-btn")) {
          new_qty = current_qty - step;
          if (new_qty < min) {
            new_qty = min;
            Toast.fire({
              icon: "error",
              title: "Minimum allowed quantity is " + min,
            });
          }
        }

        if (new_qty !== current_qty) {
          // Disable button temporarily to prevent multiple clicks
          var currentButton = $(this);
          currentButton.prop("disabled", true);

          // Prepare AJAX data
          var ajaxData = {
            product_variant_id: product_variant_id,
            update_cart: 1,
            qty: new_qty,
          };

          // Add CSRF token if available
          if (
            typeof csrfName !== "undefined" &&
            typeof csrfHash !== "undefined" &&
            csrfName &&
            csrfHash
          ) {
            ajaxData[csrfName] = csrfHash;
          }

          // Update cart via AJAX
          $.ajax({
            url: base_url + "cart/manage",
            type: "POST",
            data: ajaxData,
            dataType: "json",
            success: function (result) {
              if (result.csrfName) csrfName = result.csrfName;
              if (result.csrfHash) csrfHash = result.csrfHash;

              if (result.error == false) {
                // Update the input value
                input.val(new_qty);
                input.attr("value", new_qty);

                // Update the line price
                var price = parseFloat(input.data("price"));
                var linePrice = price * new_qty;
                var productRow = input
                  .closest("tr")
                  .find(".product-line-price");

                productRow.fadeOut(200, function () {
                  $(this).text(currency + " " + linePrice.toFixed(2));
                  $(this).fadeIn(200);
                  if (typeof mycartTotal === "function") {
                    mycartTotal();
                  }
                });
              } else {
                Toast.fire({
                  icon: "error",
                  title: result.message || "Failed to update cart",
                });
                // Reset input value on error
                input.val(current_qty);
              }
            },
            error: function (xhr, status, error) {
              Toast.fire({
                icon: "error",
                title: "Error updating cart. Please try again.",
              });
              // Reset input value on error
              input.val(current_qty);
            },
            complete: function () {
              // Re-enable button
              currentButton.prop("disabled", false);
            },
          });
        }

        return false;
      }
    },
  );

  // Cart page specific input change functionality
  $(document).on("change", ".input-field-cart-modal", function () {
    var input = $(this);

    if ($(this).hasClass("quick-view-field")) {
      return;
    }

    // if (input.data('page') === 'cart' && is_loggedin == 1) {

    var product_variant_id = input.data("id");
    var new_qty = parseInt(input.val());
    var min = parseInt(input.attr("min")) || 1;
    var max = parseInt(input.attr("max")) || 999999;

    // Validate quantity
    if (new_qty < min) {
      new_qty = min;
      input.val(new_qty);
      Toast.fire({
        icon: "error",
        title: "Minimum allowed quantity is " + min,
      });
    } else if (max > 0 && new_qty > max) {
      new_qty = max;
      input.val(new_qty);
      Toast.fire({
        icon: "error",
        title: "Maximum allowed quantity is " + max,
      });
    }
    if (is_loggedin == 1) {
      // Update cart via AJAX
      $.ajax({
        url: base_url + "cart/manage",
        type: "POST",
        data: {
          product_variant_id: product_variant_id,
          update_cart: 1,
          qty: new_qty,
          [csrfName]: csrfHash,
        },
        dataType: "json",
        success: function (result) {
          csrfName = result.csrfName;
          csrfHash = result.csrfHash;

          if (result.error == false) {
            // Update the line price
            var price = parseFloat(input.data("price"));
            var linePrice = price * new_qty;
            var productRow = input.closest("tr").find(".product-line-price");

            productRow.fadeOut(200, function () {
              $(this).text(currency + " " + linePrice.toFixed(2));
              $(this).fadeIn(200);
            });

            // Update cart totals (if functions exist)
            if (typeof recalculateCart === "function") {
              recalculateCart();
            }
            if (typeof mycartTotal === "function") {
              mycartTotal();
            }
          } else {
            Toast.fire({
              icon: "error",
              title: result.message || "Failed to update cart",
            });
          }
        },
        error: function (xhr, status, error) {
          Toast.fire({
            icon: "error",
            title: "Error updating cart. Please try again.",
          });
        },
      });
    }
  });

  // Profile page password change functionality - use event delegation with more specific targeting
  $(document).on("submit", 'form[action*="login/update_user"]', function (e) {
    var form = $(this);
    var oldPassword = $("#old").val();
    var newPassword = $("#new").val();
    var confirmPassword = $("#new_confirm").val();
    var userType = $("#user_type").val();
    var userEmail = $("#user_email").val();

    // Only handle if password fields are filled (indicates password change request)
    if (oldPassword && newPassword && confirmPassword) {
      e.preventDefault();
      e.stopPropagation();

      // Validate password match
      if (newPassword !== confirmPassword) {
        Toast.fire({
          icon: "error",
          title: "New passwords do not match",
        });
        return false;
      }

      // Validate password length
      if (newPassword.length < 6) {
        Toast.fire({
          icon: "error",
          title: "Password must be at least 6 characters long",
        });
        return false;
      }

      // For email users, update Firebase first, then MySQL
      if (
        userType === "email" &&
        typeof firebase !== "undefined" &&
        firebase.auth
      ) {
        var user = firebase.auth().currentUser;

        if (user && user.email === userEmail) {
          // Re-authenticate user with old password first
          var credential = firebase.auth.EmailAuthProvider.credential(
            userEmail,
            oldPassword,
          );

          user
            .reauthenticateWithCredential(credential)
            .then(function () {
              // Update password in Firebase
              return user.updatePassword(newPassword);
            })
            .then(function () {

              // Now update password in MySQL database
              return $.ajax({
                url: base_url + "home/reset-password",
                type: "POST",
                data: {
                  email: userEmail,
                  new_password: newPassword,
                  [csrfName]: csrfHash,
                },
                dataType: "json",
              });
            })
            .then(function (response) {
              if (response.error === false) {
                Toast.fire({
                  icon: "success",
                  title: "Password updated successfully!",
                });
                // Clear password fields
                $("#old, #new, #new_confirm").val("");

                // Submit the rest of the form data (profile image, username) if changed
                var formData = new FormData(form[0]);
                formData.delete("old");
                formData.delete("new");
                formData.delete("new_confirm");
                formData.append(csrfName, csrfHash);

                $.ajax({
                  url: form.attr("action"),
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  dataType: "json",
                  success: function (result) {
                    if (result.error === false) {
                      Toast.fire({
                        icon: "success",
                        title: "Profile updated successfully!",
                      });
                      setTimeout(function () {
                        location.reload();
                      }, 1500);
                    } else {
                      Toast.fire({
                        icon: "warning",
                        title:
                          "Password updated but profile update failed: " +
                          (result.message || "Unknown error"),
                      });
                    }
                  },
                  error: function () {
                    Toast.fire({
                      icon: "warning",
                      title: "Password updated but profile update failed",
                    });
                  },
                });
              } else {
                Toast.fire({
                  icon: "error",
                  title:
                    response.message || "Failed to update password in database",
                });
              }
            })
            .catch(function (error) {
              console.error("Password update error:", error);
              var errorMessage = "Failed to update password";

              if (error.code === "auth/wrong-password") {
                errorMessage = "Current password is incorrect";
              } else if (error.code === "auth/weak-password") {
                errorMessage = "Password is too weak";
              } else if (error.code === "auth/requires-recent-login") {
                errorMessage =
                  "Please log out and log in again before changing password";
              } else if (error.message) {
                errorMessage = error.message;
              }

              Toast.fire({
                icon: "error",
                title: errorMessage,
              });
            });
        } else {
          Toast.fire({
            icon: "error",
            title: "User not properly authenticated with Firebase",
          });
        }
      } else {
        // For phone users or when Firebase is not available, let the existing handler take over
        // Remove the password fields and submit normally to prevent conflicts
        $("#old, #new, #new_confirm").remove();
        form.removeClass("form-submit-event");
        form.submit();
      }

      return false;
    }
    // If no password change, let the form submit normally
  });
});

window.downloadInvoicePDF = function (orderId) {
  const invoiceUrl = base_url + "my-account/order-invoice/" + orderId;

  const xhr = new XMLHttpRequest();
  xhr.open("GET", invoiceUrl, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = xhr.responseText;

      const invoiceContent = tempDiv.querySelector("#section-to-print");

      if (invoiceContent) {
        const element = invoiceContent.cloneNode(true);

        // Scale down the logo
        const logo = element.querySelector(".invoice_logo");
        if (logo) {
          logo.style.maxWidth = "80px";
          logo.style.height = "auto";
          logo.style.margin = "0 auto";
        }

        // Remove the print button
        const printButton = element.querySelector("#section-not-to-print");
        if (printButton) {
          printButton.remove();
        }

        // Apply PDF-friendly styling
        element.style.fontSize = "12px";
        element.style.lineHeight = "1.4";

        // Scale tables and content
        const tables = element.querySelectorAll("table");
        tables.forEach((table) => {
          table.style.fontSize = "11px";
        });

        const headings = element.querySelectorAll("h1, h2, h3, h4, h5, h6");
        headings.forEach((h) => {
          h.style.marginTop = "8px";
          h.style.marginBottom = "8px";
        });

        const paragraphs = element.querySelectorAll("p");
        paragraphs.forEach((p) => {
          p.style.margin = "4px 0";
        });

        const opt = {
          margin: [5, 5, 5, 5],
          filename: "Invoice_" + orderId + ".pdf",
          image: { type: "jpeg", quality: 0.95 },
          html2canvas: {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            logging: false,
            windowHeight: 1400,
          },
          jsPDF: {
            orientation: "portrait",
            unit: "mm",
            format: "a4",
            hotfixes: ["px_scaling"],
          },
        };

        html2pdf()
          .set(opt)
          .from(element)
          .save()
          .catch(function (error) {
            console.error("PDF generation error:", error);
            alert("Error generating PDF. Please try again.");
          });
      } else {
        alert("Invoice content not found");
      }
    } else {
      alert("Error loading invoice. Please try again.");
    }
  };
  xhr.onerror = function () {
    alert("Error loading invoice page. Please try again.");
  };
  xhr.send();
}
