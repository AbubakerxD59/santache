"use strict";

/* ---------------------------------------------------------------------------------------------------------------------------------------------------

Common-Functions or events
 1.login-Module
 2.Product-Module
 3.Category-Module
 4.Order-Module
 5.Featured_Section-Module
 6.Notifation-Module
 7.Faq-Module
 8.Slider-Module
 9.Offer-Module
 10.Promo_code-Module
 11.Delivery_boys-Module 
 12.Settings-Module 
 13.City-Module
 14.Transaction_Module
 15.Customer-Wallet-Module   
 16.Fund-Transfer-Module
 17.Return-Request-Module
 18.Tax-Module
 19.Image Upload 
 20.Client Api Key Module  
 21.System Users
 22.Whatsapp status
--------------------------------------------------------------------------------------------------------------------------------------------------- */

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

$(document).ready(function () {
  $("#loading").hide();
  $(".no_of_users").removeClass("d-none");

  const previousBtn = document.querySelector(".page-item:first-child");
  const nextBtn = document.querySelector(".page-item:last-child");

  // Set the initial state
  let activePage = 1;
  updatePagination();

  // Add click event listeners to the pagination buttons
  previousBtn.addEventListener("click", goToPreviousPage);
  nextBtn.addEventListener("click", goToNextPage);

  function goToPreviousPage() {
    activePage--;
    updatePagination();
  }

  function goToNextPage() {
    activePage++;
    updatePagination();
  }

  function updatePagination() {
    // Enable/disable the previous button based on the active page
    if (activePage === 1) {
      previousBtn.classList.add("disabled");
    } else {
      previousBtn.classList.remove("disabled");
    }

    // Update the UI to reflect the active page
    const pageButtons = document.querySelectorAll(
      ".page-item:not(:first-child):not(:last-child) a"
    );
    pageButtons.forEach((button) => {
      if (parseInt(button.innerText) === activePage) {
        button.classList.add("active");
      } else {
        button.classList.remove("active");
      }
    });
  }
});
$(document).ready(function () {
  $("#zipcode_remove").click(function () {
    var ids = $.map(
      $("#zipcode-table").bootstrapTable("getSelections"),
      function (row) {
        return row.id;
      }
    );

    if (ids.length > 0) {
      Swal.fire({
        title: "Are You Sure!",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        showLoaderOnConfirm: true,
        preConfirm: function () {
          return new Promise((resolve, reject) => {
            $.ajax({
              method: "POST",
              url: base_url + "admin/Area/delete_zipcode_multi",
              data: { ids: ids },
              dataType: "json",
              success: function (response) {
                if (response.success) {
                  $("#zipcode-table").bootstrapTable("remove", {
                    field: "id",
                    values: ids,
                  });
                  $("#zipcode-table").bootstrapTable("refresh");

                  Swal.fire("Success", "Files Deleted!", "success");
                } else {
                  Swal.fire("Oops...", result["message"], "error");
                }
                resolve();
              },
              error: function (xhr, status, error) {
                Swal.fire("Oops...", "Something went wrong!", "error");
                reject(error);
              },
            });
          });
        },
        allowOutsideClick: false,
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire("Cancelled!", "Your data is safe.", "error");
        }
      });
    } else {
      alert("Please select at least one item to delete.");
    }
  });
});
$(document).ready(function () {
  function toggleDiscountFields() {
    var offerType = $("#offer_type").val();
    var minDiscountField = $("#min_discount");
    var maxDiscountField = $("#max_discount");

    if (
      offerType === "default" ||
      offerType === "products" ||
      offerType === "offer_url"
    ) {
      minDiscountField.prop("disabled", true);
      maxDiscountField.prop("disabled", true);
    } else {
      minDiscountField.prop("disabled", false);
      maxDiscountField.prop("disabled", false);
    }
  }

  $("#offer_type").on("ready change", toggleDiscountFields);

  toggleDiscountFields();
});
$(document).on("click", ".page-link", function () {
  const previousBtn = document.querySelector(".page-item:first-child");
  const nextBtn = document.querySelector(".page-item:last-child");

  // Set the initial state
  let activePage = 1;
  updatePagination();

  // Add click event listeners to the pagination buttons
  previousBtn.addEventListener("click", goToPreviousPage);
  nextBtn.addEventListener("click", goToNextPage);

  function goToPreviousPage() {
    activePage--;
    updatePagination();
  }

  function goToNextPage() {
    activePage++;
    updatePagination();
  }

  function updatePagination() {
    // Enable/disable the previous button based on the active page
    if (activePage === 1) {
      previousBtn.classList.add("disabled");
    } else {
      previousBtn.classList.remove("disabled");
    }

    // Update the UI to reflect the active page
    const pageButtons = document.querySelectorAll(
      ".page-item:not(:first-child):not(:last-child) a"
    );
    pageButtons.forEach((button) => {
      if (parseInt(button.innerText) === activePage) {
        button.classList.add("active");
      } else {
        button.classList.remove("active");
      }
    });
  }
});
$("#attribute_modal_close").on("click", function () {
  if ($(".modal-backdrop").hasClass("show")) {
    $(".modal-backdrop").removeClass("show");
    $(".modal-backdrop").removeClass("modal-backdrop");
  }
  $("#edit_attribute_modal").modal("hide");
});

$.event.special.touchstart = {
  setup: function (_, ns, handle) {
    this.addEventListener("touchstart", handle, {
      passive: !ns.includes("noPreventDefault"),
    });
  },
};
$(document).ready(function () {
  $(".kv-fa").rating({
    theme: "krajee-fa",
    filledStar: '<i class="fas fa-star"></i>',
    emptyStar: '<i class="far fa-star"></i>',
    showClear: false,
    size: "md",
  });

  $("#media_remove").click(function () {
    var ids = $.map(
      $("#media-table").bootstrapTable("getSelections"),
      function (row) {
        return row.id;
      }
    );
    if (ids.length > 0) {
      Swal.fire({
        title: "Are You Sure!",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        showLoaderOnConfirm: true,
        preConfirm: function () {
          return new Promise((resolve, reject) => {
            $.ajax({
              method: "POST",
              url: base_url + "admin/media/media_delete",
              data: {
                ids: ids,
                [csrfName]: csrfHash,
              },
              dataType: "json",
              success: function (response) {
                if (response.error == false) {
                  $("#media-table").bootstrapTable("refresh");
                  Swal.fire("Success", "Files Deleted!", "success");
                  csrfName = response["csrfName"];
                  csrfHash = response["csrfHash"];
                } else {
                  Swal.fire("Oops...", response["message"], "error");
                  csrfName = response["csrfName"];
                  csrfHash = response["csrfHash"];
                }
                resolve();
              },
              error: function (xhr, status, error) {
                Swal.fire("Oops...", "Something went wrong!", "error");
                reject(error);
              },
            });
          });
        },
        allowOutsideClick: false,
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire("Cancelled!", "Your data is safe.", "error");
        }
      });
    } else {
      alert("Please select at least one item to delete.");
    }
  });
});

$(document).on(
  "load-success.bs.table",
  "#product-rating-table",
  function (event) {
    $(".kv-fa").rating({
      theme: "krajee-fa",
      filledStar: '<i class="fas fa-star"></i>',
      emptyStar: '<i class="far fa-star"></i>',
      showClear: false,
      size: "md",
    });
  }
);

$(document).on("load-success.bs.table", "#products_table", function (event) {
  $(".kv-fa").rating({
    theme: "krajee-fa",
    filledStar: '<i class="fas fa-star"></i>',
    emptyStar: '<i class="far fa-star"></i>',
    showClear: false,
    size: "md",
  });
  var $pagination = $(this).closest(".bootstrap-table").find(".pagination");
  var $pageLinks = $pagination.find(
    "li:not(.page-first):not(.page-pre):not(.page-next):not(.page-last)"
  );
  var currentPage = $pagination.find("li.active").index();
  var totalPages = $pageLinks.length;

  if (totalPages > 5) {
    $pageLinks.hide();
    var start = Math.max(0, Math.min(currentPage - 2, totalPages - 5));
    var end = Math.min(start + 5, totalPages);
    $pageLinks.slice(start, end).show();
  }
});

$(document).on("column-switch.bs.table", "#products_table", function (event) {
  $(".kv-fa").rating({
    theme: "krajee-fa",
    filledStar: '<i class="fas fa-star"></i>',
    emptyStar: '<i class="far fa-star"></i>',
    showClear: false,
    size: "md",
  });
  var $pagination = $(this).closest(".bootstrap-table").find(".pagination");
  var $pageLinks = $pagination.find(
    "li:not(.page-first):not(.page-pre):not(.page-next):not(.page-last)"
  );
  var currentPage = $pagination.find("li.active").index();
  var totalPages = $pageLinks.length;

  if (totalPages > 5) {
    $pageLinks.hide();
    var start = Math.max(0, Math.min(currentPage - 2, totalPages - 5));
    var end = Math.min(start + 5, totalPages);
    $pageLinks.slice(start, end).show();
  }
});
$(document).on("page-change.bs.table", "#products_table", function (event) {
  setTimeout(function () {
    var $pagination = $("#products_table")
      .closest(".bootstrap-table")
      .find(".pagination");
    var $pageLinks = $pagination.find(
      "li:not(.page-first):not(.page-pre):not(.page-next):not(.page-last)"
    );
    var currentPage = $pagination.find("li.active").index();
    var totalPages = $pageLinks.length;

    if (totalPages > 5) {
      $pageLinks.hide();
      var start = Math.max(0, Math.min(currentPage - 2, totalPages - 5));
      var end = Math.min(start + 5, totalPages);
      $pageLinks.slice(start, end).show();
    }
  }, 100);
});

$(document).on("click", "#fund-transfer-rest-btn", function () {
  $("#transfer_amt").val("");
  $("#message").val("");
});

// Read More / Read Less functionality for product rating comments
$(document).on("click", ".read-more-btn", function (e) {
  e.preventDefault();
  var $this = $(this);
  var $container = $this.closest(".comment-container");
  var $commentText = $container.find(".comment-text");
  var $fullText = $container.find(".comment-full-text");
  var action = $this.data("action");

  if (action === "more") {
    $fullText.show();
    $commentText
      .removeClass("comment-text-truncated")
      .addClass("comment-text-full");
    $this.text("Read Less").data("action", "less");
  } else {
    $fullText.hide();
    $commentText
      .removeClass("comment-text-full")
      .addClass("comment-text-truncated");
    $this.text("Read More").data("action", "more");
  }
});

$(document).on("click", ".delete-product-rating", function () {
  var cat_id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/product/delete_rating",
          data: {
            id: cat_id,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            } else {
              Swal.fire("Oops...", response.message, "warning");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on("click", ".delete-product-faq", function () {
  var faq_id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/product_faqs/delete_product_faq",
          data: {
            id: faq_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            } else {
              Swal.fire("Oops...", response.message, "warning");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
          });
      });
    },
    allowOutsideClick: false,
  });
});

iziToast.settings({
  position: "topRight",
});

$("[data-toggle=tooltip").tooltip();

var attributes_values_selected = [];
var variant_values_selected = [];
var value_check_array = [];
var attributes_selected_variations = [];
var attributes_values = [];
var pre_selected_attr_values = [];
var current_attributes_selected = [];
var current_variants_selected = [];
var attribute_flag = 0;
var pre_selected_attributes_name = [];
var current_selected_image;
var attributes_values = [];
var all_attributes_values = [];
var counter = 0;
var variant_counter = 0;

//-------------
//- CATEGORY EISE PRODUCT SALE CHART -
//-------------
// Get context with jQuery - using jQuery's .get() method.

if (document.getElementById("piechart_3d")) {
  $.ajax({
    url: base_url + "admin/home/category_wise_product_count",
    type: "GET",
    dataType: "json",
    success: function (result) {
      // Assuming result is in the format [['Category', 'Count'], ['Cat1', 10], ['Cat2', 20], ...]
      // Extract labels and series data from the result
      let labels = [];
      let series = [];

      // Skip the header row if present
      for (let i = 1; i < result.length; i++) {
        labels.push(result[i][0]);
        series.push(result[i][1]);
      }

      // ApexCharts configuration
      var options = {
        chart: {
          type: "donut",
          height: 320,
        },
        series: series,
        labels: labels,
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
        dataLabels: {
          enabled: true,
        },
        legend: {
          show: true,
          position: "right",
          offsetY: 0,
          height: 230,
        },
        plotOptions: {
          pie: {
            donut: {
              size: "65%",
            },
          },
        },
        colors: ["#b778ebff", "#7C6EBB", "#BFBFEC"],
        states: {
          hover: {
            filter: {
              type: "darken",
              value: 0.1,
            },
          },
        },
      };

      // Render the chart
      var chart = new ApexCharts(
        document.getElementById("piechart_3d"),
        options
      );
      chart.render();
    },
  });

  $.ajax({
    url: base_url + "admin/home/fetch_sales",
    type: "GET",
    dataType: "json",
    success: function (result) {
      var charts = {
        day: null,
        week: null,
        month: null,
      };

      // Data preparation with debug logging
      var dayData = {
        labels: result[2].day,
        series: result[2].total_sale,
      };
      var weekData = {
        labels: result[1].week,
        series: result[1].total_sale,
      };
      var monthData = {
        labels: result[0].month_name,
        series: result[0].total_sale,
      };

      // Function to create ApexCharts line chart
      function createLineChart(elementId, labels, data, chartKey) {
        var element = document.querySelector("#" + elementId);

        // Check if element exists
        if (!element) {
          return;
        }

        // Handle empty data case - show flat line at zero
        var chartLabels = labels;
        var chartData = data;

        if (!labels || !data || labels.length === 0 || data.length === 0) {
          switch (chartKey) {
            case "week":
              // Show 7 days (current week)
              chartLabels = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
              chartData = [0, 0, 0, 0, 0, 0, 0];
              break;
            case "day":
              // Show last 7 days
              chartLabels = [
                "Day 1",
                "Day 2",
                "Day 3",
                "Day 4",
                "Day 5",
                "Day 6",
                "Day 7",
              ];
              chartData = [0, 0, 0, 0, 0, 0, 0];
              break;
            case "month":
              // Show 12 months
              chartLabels = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
              ];
              chartData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
              break;
          }
        } else {
          // Handle cases with insufficient data points (1-2 points) - pad with zeros for better visualization
          if (chartKey === "week" && labels.length < 7) {
            var weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            var paddedLabels = [];
            var paddedData = [];

            // Create full week with existing data placed appropriately and zeros for missing days
            for (var i = 0; i < 7; i++) {
              paddedLabels.push(weekDays[i]);
              // Check if we have data for this day, otherwise use 0
              var foundIndex = -1;
              for (var j = 0; j < labels.length; j++) {
                // Simple check - you might want to make this more sophisticated based on your date format
                if (labels[j]) {
                  foundIndex = j;
                  break;
                }
              }
              if (foundIndex >= 0 && i === Math.floor(7 / 2)) {
                // Place the data point in the middle
                paddedData.push(data[foundIndex]);
              } else {
                paddedData.push(0);
              }
            }
            chartLabels = paddedLabels;
            chartData = paddedData;
          } else if (chartKey === "day" && labels.length < 7) {
            var dayLabels = [
              "Day 1",
              "Day 2",
              "Day 3",
              "Day 4",
              "Day 5",
              "Day 6",
              "Day 7",
            ];
            var paddedLabels = [];
            var paddedData = [];

            for (var i = 0; i < 7; i++) {
              paddedLabels.push(dayLabels[i]);
              if (i < labels.length) {
                paddedData.push(data[i]);
              } else {
                paddedData.push(0);
              }
            }
            chartLabels = paddedLabels;
            chartData = paddedData;
          } else if (chartKey === "month" && labels.length < 12) {
            var monthLabels = [
              "Jan",
              "Feb",
              "Mar",
              "Apr",
              "May",
              "Jun",
              "Jul",
              "Aug",
              "Sep",
              "Oct",
              "Nov",
              "Dec",
            ];
            var paddedLabels = [];
            var paddedData = [];

            for (var i = 0; i < 12; i++) {
              paddedLabels.push(monthLabels[i]);
              if (i < labels.length) {
                paddedData.push(data[i]);
              } else {
                paddedData.push(0);
              }
            }
            chartLabels = paddedLabels;
            chartData = paddedData;
          }
        }

        // Define colors for different chart types
        var chartColor;
        switch (chartKey) {
          case "month":
            chartColor = "#BFBFEC";
            break;
          case "week":
            chartColor = "#b778eb98";
            break;
          case "day":
            chartColor = "#9e6dffa1";
            break;
          default:
            chartColor = "#6133bd8c";
        }

        var options = {
          chart: {
            type: "line",
            height: 300,
            animations: {
              enabled: true,
              easing: "easeinout",
              speed: 1000,
              animateGradually: {
                enabled: true,
                delay: 150,
              },
              dynamicAnimation: {
                enabled: true,
                speed: 350,
              },
            },
            toolbar: {
              show: false,
            },
            dropShadow: {
              enabled: true,
              color: "#000",
              top: 18,
              left: 7,
              blur: 10,
              opacity: 0.2,
            },
          },
          series: [
            {
              name: "Sales",
              data: chartData,
            },
          ],
          xaxis: {
            categories: chartLabels,
            labels: {
              style: {
                colors: "#9aa0ac",
              },
            },
          },
          yaxis: {
            labels: {
              formatter: function (value) {
                return value / 1000 + "K";
              },
              style: {
                colors: "#9aa0ac",
              },
            },
          },
          stroke: {
            curve: "smooth",
            width: 3,
            colors: [chartColor],
          },
          grid: {
            show: false,
          },
          colors: [chartColor],
          markers: {
            size: 4,
            colors: [chartColor],
            strokeColors: "#fff",
            strokeWidth: 2,
            hover: {
              size: 6,
            },
          },
          tooltip: {
            enabled: true,
            y: {
              formatter: function (value) {
                return value / 1000 + "K";
              },
            },
          },
        };

        // Destroy existing chart if it exists
        if (charts[chartKey]) {
          charts[chartKey].destroy();
          charts[chartKey] = null;
        }

        // Create new chart and store reference
        charts[chartKey] = new ApexCharts(element, options);
        charts[chartKey].render();
      }

      // Function to get active tab or set default
      function getActiveTab() {
        var activeTab = $(".chart-action li a.active");
        if (activeTab.length > 0) {
          return activeTab.attr("href");
        }
        // Default to month if none active
        $('.chart-action li a[href="#scoreLineToMonth"]').addClass("active");
        return "#scoreLineToMonth";
      }

      // Function to render chart for active tab
      function renderActiveChart() {
        var activeHref = getActiveTab();

        switch (activeHref) {
          case "#scoreLineToDay":
            createLineChart(
              "scoreLineToDay",
              dayData.labels,
              dayData.series,
              "day"
            );
            break;
          case "#scoreLineToWeek":
            createLineChart(
              "scoreLineToWeek",
              weekData.labels,
              weekData.series,
              "week"
            );
            break;
          case "#scoreLineToMonth":
            createLineChart(
              "scoreLineToMonth",
              monthData.labels,
              monthData.series,
              "month"
            );
            break;
        }
      }

      // Initialize chart on page load
      setTimeout(function () {
        renderActiveChart();
      }, 500);

      // Handle tab clicks
      $(document)
        .off("click.chartTabs", ".chart-action li a")
        .on("click.chartTabs", ".chart-action li a", function (e) {
          e.preventDefault();

          // Update active state
          $(".chart-action li a").removeClass("active");
          $(this).addClass("active");

          // Render chart for the clicked tab
          var href = $(this).attr("href");
          switch (href) {
            case "#scoreLineToDay":
              setTimeout(function () {
                createLineChart(
                  "scoreLineToDay",
                  dayData.labels,
                  dayData.series,
                  "day"
                );
              }, 100);
              break;
            case "#scoreLineToWeek":
              setTimeout(function () {
                createLineChart(
                  "scoreLineToWeek",
                  weekData.labels,
                  weekData.series,
                  "week"
                );
              }, 100);
              break;
            case "#scoreLineToMonth":
              setTimeout(function () {
                createLineChart(
                  "scoreLineToMonth",
                  monthData.labels,
                  monthData.series,
                  "month"
                );
              }, 100);
              break;
          }
        });
    },
  });
}

$(document).on("click", '[data-toggle="lightbox"]', function (event) {
  event.preventDefault();
  $(this).ekkoLightbox();
});

var url = window.location.origin + window.location.pathname;
var $selector = $('.sidebar a[href="' + url + '"]');
$($selector).addClass("active");
$($selector).closest("ul").closest("li").addClass("menu-open");
$($selector).closest("ul").removeAttr("style");
$($selector).closest("ul").closest("li").find('a[href*="#"').addClass("active");

var tmp = [];
var permute_counter = 0;

//User defined functions

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

function containsAll(needles, haystack) {
  for (var i = 0; i < needles.length; i++) {
    if ($.inArray(needles[i], haystack) == -1) return false;
  }
  return true;
}

function getPermutation(args) {
  var r = [],
    max = args.length - 1;

  function helper(arr, i) {
    for (var j = 0, l = args[i].length; j < l; j++) {
      var a = arr.slice(0); // clone arr
      a.push(args[i][j]);
      if (i == max) r.push(a);
      else helper(a, i + 1);
    }
  }
  helper([], 0);
  return r;
}

function clear_form_elements(class_name) {
  jQuery("." + class_name)
    .find(":input")
    .each(function () {
      switch (this.type) {
        case "password":
        case "text":
        case "textarea":
        case "file":
        case "select-one":
        case "select-multiple":
        case "date":
        case "number":
        case "tel":
        case "email":
          jQuery(this).val("");
          break;
        case "checkbox":
        case "radio":
          this.checked = false;
          break;
      }
    });
}

function add_product_variant_html(type) {
  if (type == "packet") {
    var html =
      "<div class='row offset-md-1 border-bottom ml-5 mr-5 mb-3'><div class='col-md-12 mt-2 remove_pro_btn'><div class='card-tools float-right'> <label>Remove</label> <button type='button' class='btn btn-tool' id='remove_product_btn'> <i class='text-danger far fa-times-circle fa-2x '></i> </button></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Measurement</label><div class='col-sm-10'> <span><input type='number' name='packet_measurement[]' ></span></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-6 col-form-label'>Unit</label><div class='col-sm-6'> <select class='form-control valid' name='packet_measurement_unit_id[]' aria-invalid='false'><option value='1'>kg</option><option value='2'>gm</option><option value='3'>ltr</option><option value='4'>ml</option><option value='5'>pack</option> </select></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Price</label><div class='col-sm-10'> <span><input type='number' class='price' name='packet_price[]'></span></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Discounted Price</label><div class='col-sm-10'> <span><input type='number' class='discount' name='packet_discnt[]'></span></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Stock</label><div class='col-sm-10'> <input type='number' name='packet_stock[]'></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Unit</label><div class='col-sm-6'> <select class='form-control valid' name='packet_stock_unit_id[]' aria-invalid='false'><option value='1'>kg</option><option value='2'>gm</option><option value='3'>ltr</option><option value='4'>ml</option><option value='5'>pack</option> </select></div></div><div class='form-group col-md-4'> <label for='inputPassword' class='col-sm-4 col-form-label'>Status</label><div class='col-sm-6'> <select name='packet_serve_for[]' class='form-control' required='' aria-invalid='false'><option value='Available'>Available</option><option value='Sold Out'>Sold Out</option> </select></div></div></div>";
    return html;
  } else {
    var html =
      '<div class="row offset-md-1 border-bottom ml-5 mr-5 mb-3"><div class="col-md-12 mt-2 remove_pro_btn"><div class="card-tools float-right"> <label>Remove</label> <button type="button" class="btn btn-tool" id="remove_product_btn"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div><div class="form-group col-md-3 col-12"> <label for="inputPassword" class="col-sm-12 col-form-label">Measurement</label><div class="col-12"> <span><input type="number" name="loose_measurement[]" class="col-12" ></span></div></div><div class="form-group col-md-3"> <label for="inputPassword" class="col-sm-6 col-form-label">Unit</label><div class="col-sm-12"> <select class="form-control valid" name="loose_measurement_unit_id[] col-12" aria-invalid="false"><option value="1">kg</option><option value="2">gm</option><option value="3">ltr</option><option value="4">ml</option><option value="5">pack</option> </select></div></div><div class="form-group col-md-3"> <label for="inputPassword" class="col-sm-4 col-form-label">Price</label><div class="col-sm-10"> <span><input type="number" name="loose_price[]" class="col-12 price"></span></div></div><div class="form-group col-md-3"> <label for="inputPassword" class="col-sm-12 col-form-label">Discounted Price</label><div class="col-sm-10"> <span><input type="number" name="loose_discnt[]" class="col-12 discount"></span></div></div></div>';
    return html;
  }
}

function save_attributes() {
  attributes_values = [];
  all_attributes_values = [];
  var tmp = $(".product-attr-selectbox");
  $.each(tmp, function (index) {
    var data = $(tmp[index])
      .closest(".row")
      .find(".multiple_values")
      .select2("data");
    var tmp_values = [];
    for (var i = 0; i < data.length; i++) {
      if (!$.isEmptyObject(data[i])) {
        tmp_values[i] = data[i].id;
      }
    }
    if (!$.isEmptyObject(data)) {
      all_attributes_values.push(tmp_values);
    }
    if ($(tmp[index]).find(".is_attribute_checked").is(":checked")) {
      if (!$.isEmptyObject(data)) {
        attributes_values.push(tmp_values);
      }
    }
  });
}

function create_variants(preproccessed_permutation_result = false) {
  var html = "";
  var is_appendable = false;
  var permutated_attribute_value = [];
  if (preproccessed_permutation_result != false) {
    var response = preproccessed_permutation_result;
    is_appendable = true;
  } else {
    var response = getPermutation(attributes_values);
  }
  var selected_variant_ids = JSON.stringify(response);
  var selected_attributes_values = JSON.stringify(attributes_values);

  $(".no-variants-added").hide();
  $.ajax({
    type: "GET",
    url: base_url + "admin/product/get_variants_by_id",
    data: {
      variant_ids: selected_variant_ids,
      attributes_values: selected_attributes_values,
    },
    dataType: "json",
    success: function (data) {
      var result = data["result"];
      $.each(result, function (a, b) {
        variant_counter++;
        var attr_name = "pro_attr_" + variant_counter;
        html +=
          '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-variant-selectbox"><div class="col-1 text-center my-auto"><i class="fas fa-sort"></i></div>';
        var tmp_variant_value_id = " ";
        $.each(b, function (key, value) {
          tmp_variant_value_id = tmp_variant_value_id + " " + value.id;
          html +=
            '<div class="col-2"> <input type="text" class="col form-control" value="' +
            value.value +
            '" readonly></div>';
        });
        html +=
          '<input type="hidden" name="variants_ids[]" value="' +
          tmp_variant_value_id +
          '"><div class="col my-auto row justify-content-center"> <a data-toggle="collapse" class="btn btn-tool text-primary" data-target="#' +
          attr_name +
          '" aria-expanded="true"><i class="fas fa-angle-down fa-2x"></i> </a> <button type="button" class="btn btn-tool remove_variants"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div><div class="col-12" id="variant_stock_management_html"><div id=' +
          attr_name +
          ' class="collapse">';
        if (
          $(".variant_stock_status").is(":checked") &&
          $(".variant-stock-level-type").val() == "variable_level"
        ) {
          html +=
            '<div class="form-group row mt-4"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field variant-base-price" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price variant-special-price" min="0" step="0.01"></div><div class="col col-xs-12"> <label class="control-label">Sku :</label> <input type="text" name="variant_sku[]" class="col form-control varaint-must-fill-field"></div><div class="col col-xs-12"> <label class="control-label">Total Stock :</label> <input type="number" min="1" name="variant_total_stock[]" class="col form-control varaint-must-fill-field"></div><div class="col col-xs-12"> <label class="control-label">Stock Status :</label> <select type="text" name="variant_level_stock_status[]" class="col form-control varaint-must-fill-field"><option value="1">In Stock</option><option value="0">Out Of Stock</option> </select></div></div>' +
            '<div class="form-group row mt-4">' +
            '<div class="col col-xs-12">' +
            '<label for="weight" class="control-label col-md-12">Weight <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="weight[]" placeholder="Weight" id="weight" value="" step="0.01">' +
            "</div>" +
            '<div class="col col-xs-12">' +
            '<label for="height" class="control-label col-md-12">Height <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="height[]" placeholder="Height" id="height" value="">' +
            "</div>" +
            '<div class="col col-xs-12">' +
            '<label for="breadth" class="control-label col-md-12">Breadth <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="breadth[]" placeholder="Breadth" id="breadth" value="">' +
            "</div>" +
            '<div class="col col-xs-12">' +
            '<label for="length" class="control-label col-md-12">Length <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="length[]" placeholder="Length" id="length" value="">' +
            "</div></div>";
        } else {
          html +=
            '<div class="form-group row mt-4"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field variant-base-price" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price variant-special-price" min="0" step="0.01"></div></div>' +
            '<div class="form-group row mt-4">' +
            '<div class="col col-xs-12">' +
            '<label for="weight" class="control-label col-md-12">Weight <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="weight[]" placeholder="Weight" id="weight" value="" step="0.01">' +
            "</div>" +
            '<div class="col col-xs-12">' +
            '<label for="height" class="control-label col-md-12">Height <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="height[]" placeholder="Height" id="height" value="">' +
            "</div>" +
            '<div class="col col-xs-12">' +
            '<label for="breadth" class="control-label col-md-12">Breadth <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="breadth[]" placeholder="Breadth" id="breadth" value="">' +
            "</div>" +
            '<div class="col col-xs-12">' +
            '<label for="length" class="control-label col-md-12">Length <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
            '<input type="number" min="0" class="form-control" name="length[]" placeholder="Length" id="length" value="">' +
            "</div>" +
            '<div class="col col-xs-12"></div>' +
            "</div>";
        }
        html +=
          '<div class="col-12 pt-3"><label class="control-label">Images :</label><div class="col-md-3"><a class="uploadFile img btn btn-primary text-white btn-sm"  data-input="variant_images[' +
          a +
          '][]" data-isremovable="1" data-is-multiple-uploads-allowed="1" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i> Upload</a> </div><div class="container-fluid row image-upload-section"></div></div>';
        html += "</div></div></div></div></div>";
      });

      if (is_appendable == false) {
        $("#variants_process").html(html);
      } else {
        $("#variants_process").append(html);
      }
      $("#variants_process").unblock();
    },
  });
}

function create_attributes(value, selected_attr) {
  counter++;
  var $attribute = $("#attributes_values_json_data").find(".select_single");
  var $options = $($attribute).clone().html();
  var $selected_attrs = [];
  if (selected_attr) {
    $.each(selected_attr.split(","), function () {
      $selected_attrs.push($.trim(this));
    });
  }

  var attr_name = "pro_attr_" + counter;

  // product-attr-selectbox
  if ($("#product-type").val() == "simple_product") {
    var html =
      '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' +
      attr_name +
      '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' +
      $options +
      '</select></div><div class="col-md-4 col-sm-12"> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
  } else {
    $("#note").removeClass("d-none");
    var html =
      '<div class="form-group row move my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' +
      attr_name +
      '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' +
      $options +
      '</select></div><div class="col-md-4 col-sm-12"> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"><input type="checkbox" name="variations[]" class="is_attribute_checked custom-checkbox mt-2"></div><div class="col-md-1 col-sm-6 text-center py-1 align-self-center"> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
  }
  $("#attributes_process").append(html);
  if (selected_attr) {
    if ($.inArray(value.name, $selected_attrs) > -1) {
      $("#attributes_process")
        .find(".product-attr-selectbox")
        .last()
        .find(".is_attribute_checked")
        .prop("checked", true)
        .addClass("custom-checkbox mt-2");
      $("#attributes_process")
        .find(".product-attr-selectbox")
        .last()
        .find(".remove_attributes")
        .addClass("remove_edit_attribute")
        .removeClass("remove_attributes");
    }
  }
  $("#attributes_process")
    .find(".product-attr-selectbox")
    .last()
    .find(".attributes")
    .select2({
      theme: "bootstrap4",
      width: $(this).data("width")
        ? $(this).data("width")
        : $(this).hasClass("w-100")
          ? "100%"
          : "style",
      placeholder: $(this).data("placeholder"),
      allowClear: Boolean($(this).data("allow-clear")),
    })
    .val(value.name);

  $("#attributes_process")
    .find(".product-attr-selectbox")
    .last()
    .find(".attributes")
    .trigger("change");
  $("#attributes_process")
    .find(".product-attr-selectbox")
    .last()
    .find(".select_single")
    .trigger("select2:select");

  var multiple_values = [];
  $.each(value.ids.split(","), function () {
    multiple_values.push($.trim(this));
  });

  $("#attributes_process")
    .find(".product-attr-selectbox")
    .last()
    .find(".multiple_values")
    .select2({
      theme: "bootstrap4",
      width: $(this).data("width")
        ? $(this).data("width")
        : $(this).hasClass("w-100")
          ? "100%"
          : "style",
      placeholder: $(this).data("placeholder"),
      allowClear: Boolean($(this).data("allow-clear")),
    })
    .val(multiple_values);
  $("#attributes_process")
    .find(".product-attr-selectbox")
    .last()
    .find(".multiple_values")
    .trigger("change");
}

function create_fetched_attributes_html() {
  var edit_id = $('input[name="edit_product_id"]').val();
  $.ajax({
    type: "GET",
    url: base_url + "admin/product/fetch_attributes_by_id",
    data: {
      edit_id: edit_id,
      [csrfName]: csrfHash,
    },
    dataType: "json",
    success: function (data) {
      csrfName = data["csrfName"];
      csrfHash = data["csrfHash"];
      var result = data["result"];

      if (!$.isEmptyObject(result.attr_values)) {
        $.each(result.attr_values, function (key, value) {
          create_attributes(value, result.pre_selected_variants_names);
        });

        $.each(result["pre_selected_variants_ids"], function (key, val) {
          // pre_selected_attr_values[key] = $.trim();
          var tempArray = [];
          if (val.variant_ids) {
            $.each(val.variant_ids.split(","), function (k, v) {
              tempArray.push($.trim(v));
            });
            pre_selected_attr_values[key] = tempArray;
          }
        });

        if (result.pre_selected_variants_names) {
          $.each(
            result.pre_selected_variants_names.split(","),
            function (key, value) {
              pre_selected_attributes_name.push($.trim(value));
            }
          );
        }
      } else {
        $(".no-attributes-added").show();
        $("#save_attributes").addClass("d-none");
      }
    },
  });
  return $.Deferred().resolve();
}

function search_category_wise_products() {
  var category_id = $("#category_parent").val();

  if (category_id != "") {
    $.ajax({
      data: {
        cat_id: category_id,
      },
      type: "GET",
      url: base_url + "admin/product/search_category_wise_products",
      dataType: "json",
      beforeSend: function () {
        $("#sortable").html(
          '<div class="text-center py-5"><i class="fas fa-spinner fa-spin"></i> Loading...</div>'
        );
      },
      success: function (result) {
        var html = "";

        if (!$.isEmptyObject(result)) {
          $.each(result, function (index, value) {

            html += '<div class="card mb-3 border" id="product_id-' + value["id"] + '">';
            html += '  <div class="card-body py-3">';

            /* ===== Desktop Layout ===== */
            html += '    <div class="row align-items-center d-none d-md-flex">';
            html += '      <div class="col-2 text-center">';
            html += '        <span class="badge bg-primary fs-6">' + value["row_order"] + '</span>';
            html += '      </div>';
            html += '      <div class="col-4">';
            html += '        <h6 class="mb-0">' + value["name"] + '</h6>';
            html += '      </div>';
            html += '      <div class="col-3 text-center">';
            html += '        <img src="' + base_url + value["image"] + '" class="img-fluid rounded object-fit-cover" width="80" height="80">';
            html += '      </div>';
            html += '      <div class="col-3 text-center">';
            html += value["status"] == 1
              ? '<span class="badge bg-success">Active</span>'
              : '<span class="badge bg-danger">Inactive</span>';
            html += '      </div>';
            html += '    </div>';

            /* ===== Mobile Layout ===== */
            html += '    <div class="d-block d-md-none">';
            html += '      <div class="row g-3">';
            html += '        <div class="col-12">';
            html += '          <div class="d-flex justify-content-between align-items-start">';
            html += '            <h6 class="mb-1">' + value["name"] + '</h6>';
            html += '            <span class="badge bg-primary">Order: ' + value["row_order"] + '</span>';
            html += '          </div>';
            html += '        </div>';
            html += '        <div class="col-6">';
            html += '          <img src="' + base_url + value["image"] + '" class="img-fluid rounded w-100" style="max-height:120px;object-fit:cover;">';
            html += '        </div>';
            html += '        <div class="col-6 d-flex align-items-center justify-content-center">';
            html += '          <div class="text-center">';
            html += '            <small class="text-muted d-block mb-1">Status</small>';
            html += value["status"] == 1
              ? '<span class="badge bg-success">Active</span>'
              : '<span class="badge bg-danger">Inactive</span>';
            html += '          </div>';
            html += '        </div>';
            html += '      </div>';
            html += '    </div>';

            html += '  </div>';
            html += '</div>';
          });

          $("#sortable").html(html);

        } else {
          html += '<div class="text-center py-5">';
          html += '<div class="mb-3"><i class="fas fa-box-open fa-3x text-muted"></i></div>';
          html += '<h5 class="text-muted">No Products Available</h5>';
          html += '<p class="text-muted">No products available for this category.</p>';
          html += '</div>';

          $("#sortable").html(html);
        }
      },
      error: function () {
        iziToast.error({
          message: "Something went wrong while fetching products",
        });
      }
    });
  } else {
    iziToast.error({
      message: "Category Field Should Be Selected",
    });
  }
}


// $(document).ready(function () {
//     $('.update_setting, .update_firebase_setting, .time_slot, .update_email_setting, .update_shipping, .category_update, .update_faqs, .update_attri, .slider_update, .update_off_slider, .update_promo, .payment_update, .pickup_update')
//         .click(function () {
//             setTimeout(function () {
//                 location.reload();
//             }, 1500);
//         });
// });

function save_product(form) {
  $('input[name="product_type"]').val($("#product-type").val());
  if ($(".simple_stock_management_status").is(":checked")) {
    $('input[name="simple_product_stock_status"]').val(
      $("#simple_product_stock_status").val()
    );
  } else {
    $('input[name="simple_product_stock_status"]').val("");
  }
  $("#product-type").prop("disabled", true);
  $(".product-attributes").removeClass("disabled");
  $(".product-variants").removeClass("disabled");
  $(".simple_stock_management_status").prop("disabled", true);

  var catid = $("#product_category_tree_view_html").jstree("get_selected");
  var formData = new FormData(form);
  var submit_btn = $("#submit_btn");
  var btn_html = $("#submit_btn").html();
  var btn_val = $("#submit_btn").val();
  var button_text =
    btn_html != "" || btn_html != "undefined" ? btn_html : btn_val;
  save_attributes();
  formData.append(csrfName, csrfHash);

  formData.append("category_id", catid);
  formData.append("attribute_values", all_attributes_values);

  $.ajax({
    type: "POST",
    url: $(form).attr("action"),
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
        submit_btn.html(button_text);
        submit_btn.attr("disabled", false);
        iziToast.error({
          message: result["message"],
        });
      } else {
        submit_btn.html(button_text);
        submit_btn.attr("disabled", false);
        iziToast.success({
          message: result["message"],
        });
        window.location.href = base_url + "admin/product";
      }
    },
  });
}

function get_variants(edit_id) {
  return $.ajax({
    type: "GET",
    url: base_url + "admin/product/fetch_variants_values_by_pid",
    data: {
      edit_id: edit_id,
    },
    dataType: "json",
  }).done(function (data) {
    return data.responseCode != 200 ? $.Deferred().reject(data) : data;
  });
}

function create_fetched_variants_html(add_newly_created_variants = false) {
  var newArr1 = [];
  for (var i = 0; i < pre_selected_attr_values.length; i++) {
    var temp = newArr1.concat(pre_selected_attr_values[i]);
    newArr1 = [...new Set(temp)];
  }
  var newArr2 = [];
  for (var i = 0; i < attributes_values.length; i++) {
    newArr2 = newArr2.concat(attributes_values[i]);
  }

  current_attributes_selected = $.grep(newArr2, function (x) {
    return $.inArray(x, newArr1) < 0;
  });

  if (containsAll(newArr1, newArr2)) {
    var temp = [];
    if (!$.isEmptyObject(current_attributes_selected)) {
      $.ajax({
        type: "GET",
        url: base_url + "admin/product/fetch_attribute_values_by_id",
        data: {
          id: current_attributes_selected,
        },
        dataType: "json",
        success: function (result) {
          temp = result;
          $.each(result, function (key, value) {
            if (pre_selected_attributes_name.indexOf($.trim(value.name)) > -1) {
              delete temp[key];
            }
          });
          var resetArr = temp.filter(function () {
            return true;
          });
          setTimeout(function () {
            var edit_id = $('input[name="edit_product_id"]').val();
            get_variants(edit_id).done(function (data) {
              create_editable_variants(
                data.result,
                resetArr,
                add_newly_created_variants
              );
            });
          }, 1000);
        },
      });
    } else {
      if (attribute_flag == 0) {
        var edit_id = $('input[name="edit_product_id"]').val();
        get_variants(edit_id).done(function (data) {
          create_editable_variants(
            data.result,
            false,
            add_newly_created_variants
          );
        });
      }
    }
  } else {
    var edit_id = $('input[name="edit_product_id"]').val();
    get_variants(edit_id).done(function (data) {
      create_editable_variants(data.result, false, add_newly_created_variants);
    });
  }
}

function create_editable_variants(
  data,
  newly_selected_attr = false,
  add_newly_created_variants = false
) {
  if (data[0].variant_ids) {
    $("#reset_variants").show();
    var html = "";

    if (
      !$.isEmptyObject(attributes_values) &&
      add_newly_created_variants == true
    ) {
      var permuted_value_result = getPermutation(attributes_values);
    }

    $.each(data, function (a, b) {
      if (
        !$.isEmptyObject(permuted_value_result) &&
        add_newly_created_variants == true
      ) {
        var permuted_value_result_temp = permuted_value_result;
        var varinat_ids = b.variant_ids.split(",");
        $.each(permuted_value_result_temp, function (index, value) {
          if (containsAll(varinat_ids, value)) {
            permuted_value_result.splice(index, 1);
          }
        });
      }

      variant_counter++;
      var attr_name = "pro_attr_" + variant_counter;
      html +=
        '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-variant-selectbox"><div class="col-1 text-center my-auto"><i class="fas fa-sort"></i></div>';
      html +=
        '<input type="hidden" name="edit_variant_id[]" value=' + b.id + ">";
      var tmp_variant_value_id = "";
      var varaint_array = [];
      var varaint_ids_temp_array = [];
      var flag = 0;
      var variant_images = "";
      var image_html = "";
      if (b.images != "") {
        variant_images = JSON.parse(b.images);
      }

      $.each(b.variant_ids.split(","), function (key) {
        varaint_ids_temp_array[key] = $.trim(this);
      });

      $.each(b.variant_values.split(","), function (key) {
        varaint_array[key] = $.trim(this);
      });

      $.each(variant_images, function (img_key, img_value) {
        image_html +=
          '<div class="col-md-3 col-sm-12 shadow bg-white rounded m-3 p-3 text-center grow"><div class="image-upload-div"><img src=' +
          base_url +
          img_value +
          ' alt="Image Not Found"></div> <a href="javascript:void(0)" class="delete-img m-3" data-id="' +
          b.id +
          '" data-field="images" data-img=' +
          img_value +
          ' data-table="product_variants" data-path="uploads/media/" data-isjson="true"> <span class="btn btn-block bg-gradient-danger btn-xs"><i class="far fa-trash-alt "></i> Delete</span></a> <input type="hidden" name="variant_images[' +
          a +
          '][]"  value=' +
          img_value +
          "></div>";
      });

      for (var i = 0; i < varaint_array.length; i++) {
        // html += '<div class="col-2 variant_col"> <a href="javascript:void(0)" class="remove_individual_variants" ><i class="far fa-times-circle icon-link-remove fa-md"></i></a><input type="hidden"  value="' + varaint_ids_temp_array[i] + '"><input type="text" class="col form-control" value="' + varaint_array[i] + '" readonly></div>';
        html +=
          '<div class="col-2 variant_col"> <input type="hidden"  value="' +
          varaint_ids_temp_array[i] +
          '"><input type="text" class="col form-control" value="' +
          varaint_array[i] +
          '" readonly></div>';
      }
      if (newly_selected_attr != false && newly_selected_attr.length > 0) {
        for (var i = 0; i < newly_selected_attr.length; i++) {
          var tempVariantsIds = [];
          var tempVariantsValues = [];
          $.each(
            newly_selected_attr[i].attribute_values_id.split(","),
            function () {
              tempVariantsIds.push($.trim(this));
            }
          );
          html +=
            '<div class="col-2"><select class="col new-added-variant form-control" ><option value="">Select Attribute</option>';
          $.each(
            newly_selected_attr[i].attribute_values.split(","),
            function (key) {
              tempVariantsValues.push($.trim(this));
              html +=
                '<option value="' +
                tempVariantsIds[key] +
                '">' +
                tempVariantsValues[key] +
                "</option>";
            }
          );
          html += "</select></div>";
        }
      }
      html +=
        '<input type="hidden" name="variants_ids[]" value="' +
        b.attribute_value_ids +
        '"><div class="col my-auto row justify-content-center"> <a data-toggle="collapse" class="btn btn-tool text-primary" data-target="#' +
        attr_name +
        '" aria-expanded="true"><i class="fas fa-angle-down fa-2x"></i> </a> <button type="button" class="btn btn-tool remove_variants"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div><div class="col-12" id="variant_stock_management_html"><div id=' +
        attr_name +
        ' class="collapse">';
      if (
        $(".variant_stock_status").is(":checked") &&
        $(".variant-stock-level-type").val() == "variable_level"
      ) {
        var selected = b.availability == "0" ? "selected" : " ";
        html +=
          '<div class="form-group row"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field variant-base-price" value="' +
          b.price +
          '" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price variant-special-price" min="0" value="' +
          b.special_price +
          '" step="0.01"></div><div class="col col-xs-12"> <label class="control-label">Sku :</label> <input type="text" name="variant_sku[]" class="col form-control varaint-must-fill-field"  value="' +
          b.sku +
          '" ></div><div class="col col-xs-12"> <label class="control-label">Total Stock :</label> <input type="number" min="1" name="variant_total_stock[]" class="col form-control varaint-must-fill-field" value="' +
          b.stock +
          '"></div><div class="col col-xs-12"> <label class="control-label">Stock Status :</label> <select type="text" name="variant_level_stock_status[]" class="col form-control varaint-must-fill-field"><option value="1">In Stock</option><option value="0"  ' +
          selected +
          "  >Out Of Stock</option> </select></div></div>" +
          '<div class="form-group row mt-4">' +
          '<div class="col col-xs-12">' +
          '<label for="weight" class="control-label col-md-12">Weight <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="weight[]" placeholder="Weight" id="weight" value="' +
          b.weight +
          '" step="0.01">' +
          "</div>" +
          '<div class="col col-xs-12">' +
          '<label for="height" class="control-label col-md-12">Height <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="height[]" placeholder="Height" id="height" value="' +
          b.height +
          '">' +
          "</div>" +
          '<div class="col col-xs-12">' +
          '<label for="breadth" class="control-label col-md-12">Breadth <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="breadth[]" placeholder="Breadth" id="breadth" value="' +
          b.breadth +
          '">' +
          "</div>" +
          '<div class="col col-xs-12">' +
          '<label for="length" class="control-label col-md-12">Length <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="length[]" placeholder="Length" id="length" value="' +
          b.length +
          '">' +
          "</div></div>";
      } else {
        html +=
          '<div class="form-group row"><div class="col col-xs-12"><label class="control-label">Price :</label><input type="number" name="variant_price[]" class="col form-control price varaint-must-fill-field variant-base-price" value="' +
          b.price +
          '" min="1" step="0.01"></div><div class="col col-xs-12"><label class="control-label">Special Price :</label><input type="number" name="variant_special_price[]" class="col form-control discounted_price variant-special-price"  min="0" value="' +
          b.special_price +
          '" step="0.01"></div></div>' +
          '<div class="form-group row mt-4">' +
          '<div class="col col-xs-12">' +
          '<label for="weight" class="control-label col-md-12">Weight <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="weight[]" placeholder="Weight" id="weight" value="' +
          b.weight +
          '" step="0.01">' +
          "</div>" +
          '<div class="col col-xs-12">' +
          '<label for="height" class="control-label col-md-12">Height <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="height[]" placeholder="Height" id="height" value="' +
          b.height +
          '">' +
          "</div>" +
          '<div class="col col-xs-12">' +
          '<label for="breadth" class="control-label col-md-12">Breadth <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="breadth[]" placeholder="Breadth" id="breadth" value="' +
          b.breadth +
          '">' +
          "</div>" +
          '<div class="col col-xs-12">' +
          '<label for="length" class="control-label col-md-12">Length <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
          '<input type="number" min="0" class="form-control" name="length[]" placeholder="Length" id="length" value="' +
          b.length +
          '">' +
          "</div></div>";
      }
      html +=
        '<div class="col-12 pt-3"><label class="control-label">Images :</label><div class="col-md-3"><a class="uploadFile img btn btn-primary text-white btn-sm"  data-input="variant_images[' +
        a +
        '][]" data-isremovable="1" data-is-multiple-uploads-allowed="1" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i> Upload</a> </div><div class="container-fluid row image-upload-section"> ' +
        image_html +
        " </div></div>";
      html += "</div></div></div>";

      $("#variants_process").html(html);
    });

    if (
      !$.isEmptyObject(attributes_values) &&
      add_newly_created_variants == true
    ) {
      create_variants(permuted_value_result);
    }
  }
}

function status_date_wise_search() {
  $(".table-striped").bootstrapTable("refresh");
}

function resetfilters() {
  $("#datepicker").val("");
  $("#media-type").val("");
  $("#start_date").val("");
  $("#end_date").val("");
  $(".table-striped").bootstrapTable("refresh");
}

function formatRepo(repo) {
  if (repo.loading) return repo.text;
  var markup =
    "<div class='select2-result-repository clearfix'>" +
    "<div class='select2-result-repository__meta'>" +
    "<div class='select2-result-repository__title'>" +
    repo.name +
    "</div>";

  if (repo.description) {
    markup +=
      "<div class='select2-result-repository__description'> In " +
      repo.category_name +
      "</div>";
  }

  return markup;
}

function formatRepo1(repo) {
  if (repo.loading) return repo.text;
  var markup =
    "<div class='select2-result-repository clearfix'>" +
    "<div class='select2-result-repository__meta'>" +
    "<div class='select2-result-repository__title'>" +
    repo.zipcode +
    "</div>";
  return markup;
}


function formatRepoSelection(repo) {
  return repo.name || repo.text;
}

function formatRepoSelection1(repo) {
  return repo.zipcode || repo.text;
}

function mediaParams(p) {
  return {
    type: $("#media_type").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function stock_query_params(p) {
  return {
    status: $("#status_filter").val(),
    limit: p.limit,
    offset: p.offset,
    sort: p.sort,
    order: p.order,
    search: p.search,
    seller_id: $("#seller_filter").val(),
    category_id: $("#category_parent").val(),
  };
}

function mediaUploadParams(p) {
  return {
    type: $("#media-type").val(),
    start_date: $("#start_date").val(),
    end_date: $("#end_date").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function home_query_params(p) {
  return {
    start_date: $("#start_date").val(),
    end_date: $("#end_date").val(),
    order_status: $("#order_status").val(),
    payment_method: $("#payment_method").val(),
    delivery_boy: $("#delivery_boy").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function queryParams(p) {
  return {
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function deliveryBoyQueryParams(p) {
  return {
    bonus_type: $("#delivery_boy_type_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function ticket_queryParams(p) {
  return {
    status: $("#ticket_status_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function ReturnRequestQueryParams(p) {
  return {
    status: $("#return_request_status_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function SendNotificationQueryParams(p) {
  return {
    type: $("#send_notification_type_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function flashSaleQueryParams(p) {
  return {
    status_filter: $("#status_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function sliderTypeQueryParams(p) {
  return {
    type_filter: $("#type_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function offerQueryParams(p) {
  return {
    offer_type: $("#offer_type_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function popupOfferQueryParams(p) {
  return {
    offer_type: $("#popup_offer_type_filter").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function offerSliderQueryParams(p) {
  return {
    type: $("#offer_slider_type").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function product_query_params(p) {
  return {
    category_id: $("#category_parent").val(),
    type: $("#product_type_filter").val(),
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
    status: $("#status_filter").val(),
    type: $("#type_filter").val(),
  };
}

function blog_category_query_params(p) {
  return {
    category_id: $("#category_parent").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function category_query_params(p) {
  return {
    category_id: $("#category_id").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function brand_query_params(p) {
  return {
    brand_id: $("#brand_id").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function noti_query_params(p) {
  return {
    message_type: $("#message_type").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function ratingParams(p) {
  return {
    category_id: $("#category_parent").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function sales_invoice_query_params(p) {
  return {
    start_date: $("#start_date").val(),
    end_date: $("#end_date").val(),
    order_status: $("#order_status").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function address_query_params(p) {
  return {
    user_id: $("#address_user_id").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

function orders_query_params(p) {
  0;
  return {
    start_date: $("#start_date").val(),
    end_date: $("#end_date").val(),
    order_status: $("#order_status").val(),
    user_id: $("#order_user_id").val(),
    payment_method: $("#payment_method").val(),
    delivery_boy: $("#delivery_boy").val(),
    order_type: $("#order_type").val(),
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

function cash_collection_query_params(p) {
  return {
    filter_date: $("#filter_date").val(),
    filter_status: $("#filter_status").val(),
    filter_d_boy: $("#filter_d_boy").val(),
    start_date: $("#start_date").val(),
    end_date: $("#end_date").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

$(document).on("change", "#delivery_boy_type_filter", function () {
  $(".table-striped").bootstrapTable("refresh");
});

$(document).on("change", "#ticket_status_filter", function () {
  $(".table-striped").bootstrapTable("refresh");
});

$(document).on("change", "#product_type_filter", function () {
  $(".table-striped").bootstrapTable("refresh");
});

$(document).on("change", "#return_request_status_filter", function () {
  $("#return_request_table").bootstrapTable("refresh");
});

$(document).on("change", "#send_notification_type_filter", function () {
  $(".table-striped").bootstrapTable("refresh");
});

$(document).on("change", ".type_event_trigger", function (e, data) {
  e.preventDefault();
  var type_val = $(this).val();
  if (type_val != "default" && type_val != " ") {
    if (type_val == "categories") {
      $(".slider-categories").removeClass("d-none");
      $(".notification-categories").removeClass("d-none");
      $(".slider-products").addClass("d-none");
      $(".notification-products").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-all-products").addClass("d-none");
      $(".notification-all-products").addClass("d-none");
      $(".slider-brand").addClass("d-none");
      $(".offer-url").addClass("d-none");
      $(".notification-url").addClass("d-none");
    } else if (type_val == "all_products") {
      $(".slider-all-products").removeClass("d-none");
      $(".notification-all-products").removeClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-products").addClass("d-none");
      $(".notification-products").addClass("d-none");
      $(".slider-brand").addClass("d-none");
      $(".offer-url").addClass("d-none");
    } else if (type_val == "products") {
      $(".slider-all-products").removeClass("d-none");
      $(".notification-all-products").removeClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-products").removeClass("d-none");
      $(".notification-products").removeClass("d-none");
      $(".slider-brand").addClass("d-none");
      $(".offer-url").addClass("d-none");
      $(".slider-url").addClass("d-none");
      $(".notification-url").addClass("d-none");
    } else if (type_val == "flash_sale") {
      $(".slider-all-products").addClass("d-none");
      $(".notification-flash-sale").removeClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".slider-products").addClass("d-none");
      $(".notification-products").addClass("d-none");
      $(".slider-brand").addClass("d-none");
      $(".offer-url").addClass("d-none");
      $(".slider-url").addClass("d-none");
      $(".notification-url").addClass("d-none");
    } else if (type_val == "slider_url") {
      $(".slider-url").removeClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-products").addClass("d-none");
      $(".offer-url").removeClass("d-none");
      $(".notification-products").addClass("d-none");
      $(".notification-url").addClass("d-none");
    } else if (type_val == "notification_url") {
      $(".slider-url").addClass("d-none");
      $(".notification-url").removeClass("d-none");
      $(".offer-url").addClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-products").addClass("d-none");
      $(".notification-products").addClass("d-none");
    } else if (type_val == "offer_url") {
      $(".slider-brand").addClass("d-none");
      $(".offer-url").removeClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-products").addClass("d-none");
      $(".notification-products").addClass("d-none");
      $(".notification-url").addClass("d-none");
    } else if (type_val == "brand") {
      $(".slider-all-products").removeClass("d-none");
      $(".notification-all-products").removeClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-brand").removeClass("d-none");
      $(".notification-products").removeClass("d-none");
      $(".slider-products").addClass("d-none");
      $(".offer-url").addClass("d-none");
    } else {
      $(".slider-products").addClass("d-none");
      $(".notification-products").removeClass("d-none");
      $(".slider-brand").addClass("d-none");
      $(".slider-categories").addClass("d-none");
      $(".notification-categories").addClass("d-none");
      $(".notification-flash-sale").addClass("d-none");
      $(".slider-all-products").addClass("d-none");
      $(".offer-url").addClass("d-none");
      $(".notification-all-products").addClass("d-none");
    }
  } else {
    $(".slider-categories").addClass("d-none");
    $(".slider-brand").addClass("d-none");
    $(".slider-products").addClass("d-none");
    $(".slider-all-products").addClass("d-none");
    $(".notification-all-products").addClass("d-none");
    $(".notification-categories").addClass("d-none");
    $(".notification-flash-sale").addClass("d-none");
    $(".notification-products").addClass("d-none");
    $(".offer-url").addClass("d-none");
    $(".notification-url").addClass("d-none");
  }
});

if ($("input[data-bootstrap-switch]").length) {
  $("input[data-bootstrap-switch]").each(function () {
    $("input[data-bootstrap-switch]").bootstrapSwitch();
  });
}

$(document).on("click", ".edit_btn", function () {
  // alert("here");
  var id = $(this).data("id");
  var close_attribute_modal = $(this).data("close-attribute-modal");

  if (close_attribute_modal == "true" || close_attribute_modal == true) {
    $("#attributeSet_list").modal("hide");
  }

  var url = $(this).data("url");

  $(".edit-modal-lg")
    .modal("show")
    .find(".modal-body")
    .load(
      base_url + url + "?edit_id=" + id + " .form-submit-event",
      function () {
        if ($("input[data-bootstrap-switch]").length) {
          $("input[data-bootstrap-switch]").each(function () {
            $("input[data-bootstrap-switch]").bootstrapSwitch();
          });
        }
        $("#category_parent").select2({
          theme: "bootstrap4",
          width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
              ? "100%"
              : "style",
          placeholder: $(this).data("placeholder"),
          allowClear: Boolean($(this).data("allow-clear")),
          templateResult: function (data) {
            if (!data.element) {
              return data.text;
            }

            var $element = $(data.element);

            var $wrapper = $("<span></span>");
            $wrapper.addClass($element[0].className);

            $wrapper.text(data.text);

            return $wrapper;
          },
        });
        $(".select_multiple").each(function () {
          $(this).select2({
            theme: "bootstrap4",
            width: $(this).data("width")
              ? $(this).data("width")
              : $(this).hasClass("w-100")
                ? "100%"
                : "style",
            placeholder: $(this).data("placeholder"),
            allowClear: Boolean($(this).data("allow-clear")),
          });
        });

        $(".search_product").each(function () {
          $(this).select2({
            ajax: {
              url: base_url + "admin/product/get_product_data",
              dataType: "json",
              delay: 250,
              data: function (data) {
                return {
                  search: data.term, // search term
                  limit: 10,
                  status: 1,
                };
              },
              processResults: function (response) {
                return {
                  results: response.rows,
                };
              },
              cache: true,
            },
            escapeMarkup: function (markup) {
              return markup;
            },
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection,
            placeholder: "Search for products",
            theme: "bootstrap4",
            width: $(this).data("width")
              ? $(this).data("width")
              : $(this).hasClass("w-100")
                ? "100%"
                : "style",
            placeholder: $(this).data("placeholder"),
            allowClear: Boolean($(this).data("allow-clear")),
          });
        });

        $(".search_admin_digital_product").select2({
          ajax: {
            url: base_url + "admin/product/get_digital_product_data",
            dataType: "json",
            delay: 250,
            data: function (params) {
              return {
                search: params.term, // search term
                page: params.page,
              };
            },
            processResults: function (response, params) {
              params.page = params.page || 1;

              return {
                results: response.rows,
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
          minimumInputLength: 1,
          templateResult: formatRepo,
          templateSelection: formatRepoSelection,
          theme: "bootstrap4",
          placeholder: "Search for products",
        });

        $(".select_promocode_user").each(function () {
          $(this).select2({
            ajax: {
              url: base_url + "admin/promo_code/get_users",
              type: "GET",
              dataType: "json",
              delay: 250,
              data: function (params) {
                return {
                  search: params.term, // search term
                };
              },
              processResults: function (response) {
                return {
                  results: response,
                };
              },
              cache: true,
            },
            minimumInputLength: 1,
            theme: "bootstrap4",
            placeholder: "Search for users",
          });
        });

        //custom notification
        // $('.hashtag').click(function () {
        //     var data = $('textarea#text-box').text()
        //     var tab = $.trim($(this).text())
        //     var message = data + tab
        //     $('textarea#text-box').val(message)
        // })
        // $('.hashtag_input').click(function () {
        //     var data = $('#udt_title').val()
        //     var tab = $.trim($(this).text())
        //     var message = data + tab
        //     $('input#update_title').val(message)
        // })
        custommessageAutoFill();

        //    seacrh offer in offer sliders
        $(".search_offer").select2({
          ajax: {
            url: base_url + "admin/offer_slider/offer_slider_data",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function (params) {
              return {
                search: params.term, // search term
              };
            },
            processResults: function (response) {
              return {
                results: response.data,
              };
            },
            // cache: true
          },
          escapeMarkup: function (markup) {
            return markup;
          },
          // minimumInputLength: 1,
          templateResult: formatOffers,
          templateSelection: formatOffersSelection,
          // width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
          theme: "bootstrap4",
          placeholder: "Search for offers",
        });
      }
    );
});

function custommessageAutoFill() {
  const inputs = document.querySelectorAll(".text-box");
  const titleInput = document.querySelectorAll(".update_title");

  if (inputs.length == 2) {
    initializeInputFiller(".hashtag", inputs[1]);
    initializeInputFiller(".hashtag_input", titleInput[1]);
  } else {
    initializeInputFiller(".hashtag", inputs[0]);
    initializeInputFiller(".hashtag_input", titleInput[0]);
  }
}

$(document).on("click", ".view_address", function () {
  var id = $(this).data("id");
  var url = $(this).data("url");
});

$(document).on("click", ".view_btn", function () {
  var id = $(this).data("id");
  var url = $(this).data("url");
  $(".modal-body").load(
    base_url + url + "?edit_id=" + id + " .form-submit-event"
  );
  $(".modal-title").html("Manage Promo code");
  $(".modal-body").addClass("view");
  $(".edit-modal-lg").modal();
});

$(document).on("hidden.bs.modal", ".edit-modal-lg", function () {
  $(".edit-modal-lg .modal-body").removeClass("view");
  $(".edit-modal-lg .modal-body").html("");
});

//form-submit-event
$(document).on(
  "submit",
  ".container-fluid .form-submit-event,.form-submit-event",
  function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    var update_id = $("#update_id").val();
    if (update_id == "1") {
      var error_box = $(".edit-modal-lg #error_box");
      var submit_btn = $(".edit-modal-lg #submit_btn");
      var btn_html = $(".edit-modal-lg #submit_btn").html();
      var btn_val = $(".edit-modal-lg #submit_btn").val();
      var button_text =
        (btn_html != "" && btn_html != undefined) ? btn_html : btn_val;
    } else {
      var error_box = $("#error_box", this);
      var submit_btn = $(this).find("#submit_btn");
      var btn_html = $(this).find("#submit_btn").html();
      var btn_val = $(this).find("#submit_btn").val();
      var button_text =
        (btn_html != "" && btn_html != undefined) ? btn_html : btn_val;
    }

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
          error_box
            .addClass("msg_error rounded p-3")
            .removeClass("d-none msg_success");
          error_box.show().delay(1000).fadeOut();
          error_box.html(result["message"]);
          submit_btn.html(button_text);
          submit_btn.attr("disabled", false);
          iziToast.error({
            message: result["message"],
          });
        } else {
          error_box
            .addClass("msg_success rounded p-3")
            .removeClass("d-none msg_error");
          error_box.show().delay(1000).fadeOut();
          error_box.html(result["message"]);
          submit_btn.html(button_text);
          submit_btn.attr("disabled", false);

          iziToast.success({
            title: "Success",
            message: result["message"],
            position: "topRight",
            timeout: 3000,
          });
          setTimeout(function () {
            $(".modal").modal("hide");
          }, 1000);
          $(".table-striped").bootstrapTable("refresh");
          $(".search_user").html("");
          $(".form-submit-event")[0].reset();
          // if ((window.location.href.indexOf('login') > -1) || (window.location.href.indexOf('offer') > -1)) {
          if (window.location.pathname.includes("create_category")) {
            const currentUrl = window.location.href;
            if (currentUrl.includes("?edit_id")) {
              const cleanUrl = currentUrl.split("?")[0];

              window.location.href = cleanUrl;
            }
          }
          setTimeout(function () {
            location.reload();
          }, 1500);
          // }
        }
      },
    });
  }
);

// 1.login

$(document).ready(function () {
  custommessageAutoFill();
});
//forgot_page
$(document).ready(function () {
  $("#forgot_password_page").on("submit", function (e) {
    e.preventDefault();
    var form = $(this);
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: formData,
      beforeSend: function () {
        form.find("#submit_btn").html("Please Wait..");
        form.find("#submit_btn").attr("disabled", true);
      },
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];
        $("#result").html(result["message"]);
        $("#result").show().delay(6000).fadeOut();
        form.find("#submit_btn").html("Send Email");
        form.find("#submit_btn").attr("disabled", false);
      },
    });
  });
});

//2.Product-Module
var edit_product_id = $("input[name=edit_product_id]").val();

if (edit_product_id) {
  create_fetched_attributes_html().done(function () {
    $(".no-attributes-added").hide();
    $("#save_attributes").removeClass("d-none");
    $(".no-variants-added").hide();
    save_attributes();
    create_fetched_variants_html(false);
  });
}

// $(document).on(
//   'change',
//   '#is_cancelable',
//   function (event) {
//     event.preventDefault()
//     var state = $(this).bootstrapSwitch('state')
//     if (state) {
//       $('#cancelable_till').show()
//     } else {
//       $('#cancelable_till').hide()
//     }
//   }
// )

$(document).on("change", "#is_cancelable", function (e, data) {
  if ($(this).prop("checked") == true) {
    $("#cancelable_till").show();
  } else {
    $("#cancelable_till").hide();
  }
});

$(document).on(
  "switchChange.bootstrapSwitch",
  "#download_allowed",
  function (event) {
    event.preventDefault();
    var state = $(this).bootstrapSwitch("state");
    if (state) {
      $("#download_type").show();
    } else {
      $("#download_type").hide();
      $("#digital_link_container").addClass("d-none");
      $("#digital_media_container").addClass("d-none");
    }
  }
);

$(document).on("change", "#category_parent", function () {
  $("#products_table").bootstrapTable("refresh");
});
$(document).ready(function () {
  // Event listener for the product type dropdown
  $("#product_type").change(function () {
    // Get the selected product type
    var selectedType = $(this).val();

    // Refresh the table with the selected product type as a query parameter
    $("#products_table").bootstrapTable("refresh", {
      query: {
        type: selectedType,
      },
    });
  });
});

$(document).on("change", "#category_parent", function () {
  $("#blog_table").bootstrapTable("refresh");
});

$(document).on("change", "#message_type", function () {
  $("#system_notofication_table").bootstrapTable("refresh");
});
//Summer-note
$(document).ready(function () {
  var sub_id = $("#subcategory_id_js").val();
  if (typeof sub_id !== "undefined") {
    $("#category_id").trigger("change", [
      {
        subcategory_id: sub_id,
      },
    ]);
  }
});

$(document).on("click", "#variation_product_btn", function (e) {
  e.preventDefault();
  var radio = $("input[name='pro_input_type']:checked").val();
  var edit_product_id = $("input[name=edit_product_id]").val();
  var html = "";
  html = add_product_variant_html(radio);
  $("#product_variance_html").append(html);
  if (typeof edit_product_id != "undefined") {
    $("#product_variance_html")
      .children("div")
      .last()
      .append("<input type='hidden' name='edit_product_variant[]'>");
  }
});
$(document).on("click", "#remove_product_btn", function (e) {
  e.preventDefault();
  $(this).closest(".row").remove();
});
$(document).on("click", ".delete-img", function () {
  var isJson = false;
  var id = $(this).data("id");
  var path = $(this).data("path");
  var field = $(this).data("field");
  var img_name = $(this).data("img");
  var table_name = $(this).data("table");
  var t = this;
  var isjson = $(this).data("isjson");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: base_url + "admin/home/delete_image",
          data: {
            id: id,
            path: path,
            field: field,
            img_name: img_name,
            table_name: table_name,
            isjson: isjson,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["is_deleted"] == true) {
              $(t).closest("div").remove();
              Swal.fire("Success", "Media Deleted !", "success");
            } else {
              Swal.fire("Oops...", "Something went wrong with ajax !", "error");
            }
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});

$(document).on("click", ".delete-media", function () {
  var id = $(this).data("id");
  var t = this;
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/media/delete/" + id,
          dataType: "json",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["error"] == false) {
              $("table").bootstrapTable("refresh");
              Swal.fire("Success", "File Deleted !", "success");
            } else {
              Swal.fire("Oops...", result["message"], "error");
            }
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});

$(document).on("focusout", ".discounted_price", function () {
  var discount_amt = parseInt($(this).val());
  var price = parseInt(
    $(this).closest(".form-group").siblings().find(".price").val()
  );
  if (typeof price != "undefined" && price != "") {
    if (discount_amt > price) {
      iziToast.error({
        message: "Special price can" + "'" + "t exceed price",
      });
      $(this).val("");
    }
  }
});
$(document).on("focusout", ".price", function () {
  var price = parseInt($(this).val());
  var discount_amt = parseInt(
    $(this).closest(".form-group").siblings().find(".discounted_price").val()
  );
  if (typeof discount_amt != "undefined" && discount_amt != "") {
    if (discount_amt > price) {
      iziToast.error({
        message: "Special price can" + "'" + "t exceed price",
      });
      $(this).val("");
    }
  }
});
$(document).on("click", ".clear-product-variance", function () {
  var edit_product_id = $("input[name=edit_product_id]").val();
  var radio_val = $("input[name='pro_input_type']:checked").val();
  var t = this;
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/product/delete_product",
          type: "GET",
          data: {
            id: edit_product_id,
            // [csrfName]: csrfHash
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            Swal.fire("Deleted!", response.message);
            if (radio_val == "packet") {
              html = add_product_variant_html(radio_val);
              $("#product_variance_html").html(html);
              $("#product_loose_html").hide();
              $(".pro_loose").hide();
              $(".remove_pro_btn").hide();
              $(t).hide();
            } else {
              $("#product_variance_html").show();
              html = add_product_variant_html(radio_val);
              $("#product_loose_html").show();
              $("#product_variance_html").html(html);
              $(".remove_pro_btn").hide();
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});
$("#sortable").sortable({
  axis: "y",
  opacity: 0.6,
  cursor: "grab",
});

$("#sortable").sortableJS({
  axis: "y",
  opacity: 0.6,
  cursor: "grab",
});

$(document).on("click", "#save_product_order", function () {
  var data = $("#sortable").sortable("serialize");
  $.ajax({
    data: data,
    type: "GET",
    url: base_url + "admin/product/update_product_order",
    dataType: "json",
    success: function (response) {
      if (response.error == false) {
        iziToast.success({
          message: response.message,
        });
      } else {
        iziToast.error({
          message: response.message,
        });
      }
    },
  });
});

$(document).on("click", "#save_product_order", function () {
  var data = $("#sortable").sortableJS("serialize");
  $.ajax({
    data: data,
    type: "GET",
    url: base_url + "admin/product/update_product_order",
    dataType: "json",
    success: function (response) {
      if (response.error == false) {
        iziToast.success({
          message: response.message,
        });
      } else {
        iziToast.error({
          message: response.message,
        });
      }
    },
  });
});

$(document).on("click", "#save_section_order", function () {
  var data = $("#sortable").sortable("serialize");
  $.ajax({
    data: data,
    type: "GET",
    url: base_url + "admin/featured_sections/update_section_order",
    dataType: "json",
    success: function (response) {
      if (response.error == false) {
        iziToast.success({
          message: response.message,
        });
      } else {
        iziToast.error({
          message: response.message,
        });
      }
    },
  });
});

$(document).on("click", "#save_section_order", function () {
  var data = $("#sortable").sortableJS("serialize");
  $.ajax({
    data: data,
    type: "GET",
    url: base_url + "admin/featured_sections/update_section_order",
    dataType: "json",
    success: function (response) {
      if (response.error == false) {
        iziToast.success({
          message: response.message,
        });
      } else {
        iziToast.error({
          message: response.message,
        });
      }
    },
  });
});

//form-submit-event
$(document).on("submit", "#save-product", function (e) {
  e.preventDefault();
  var product_type = $("#product-type").val();
  var counter = 0;
  if (product_type != "undefined" && product_type != " ") {
    if ($.trim(product_type) == "simple_product") {
      if ($(".simple_stock_management_status").is(":checked")) {
        var len = 0;
      } else {
        var len = 1;
      }
      if (
        $(".stock-simple-mustfill-field").filter(function () {
          return this.value === "";
        }).length === len
      ) {
        $('input[name="product_type"]').val($("#product-type").val());
        if ($(".simple_stock_management_status").is(":checked")) {
          $('input[name="simple_product_stock_status"]').val(
            $("#simple_product_stock_status").val()
          );
        } else {
          $('input[name="simple_product_stock_status"]').val("");
        }
        $("#product-type").prop("disabled", true);
        $(".product-attributes").removeClass("disabled");
        $(".product-variants").removeClass("disabled");
        $(".simple_stock_management_status").prop("disabled", true);

        save_product(this);
      } else {
        iziToast.error({
          message: "Please Fill All The Fields",
        });
      }
    }

    if ($.trim(product_type) == "variable_product") {
      if ($(".variant_stock_status").is(":checked")) {
        var variant_stock_level_type = $(".variant-stock-level-type").val();
        if (variant_stock_level_type == "product_level") {
          if (
            $(".variant-stock-level-type").filter(function () {
              return this.value === "";
            }).length === 0 &&
            $.trim($(".variant-stock-level-type").val()) != ""
          ) {
            if (
              $(".variant-stock-level-type").val() == "product_level" &&
              $(".variant-stock-mustfill-field").filter(function () {
                return this.value === "";
              }).length !== 0
            ) {
              iziToast.error({
                message: "Please Fill All The Fields",
              });
            } else {
              var varinat_price = $('input[name="variant_price[]"]').val();

              if ($('input[name="variant_price[]"]').length >= 1) {
                if (
                  $(".varaint-must-fill-field").filter(function () {
                    return this.value === "";
                  }).length == 0
                ) {
                  $('input[name="product_type"]').val($("#product-type").val());
                  $('input[name="variant_stock_level_type"]').val(
                    $("#stock_level_type").val()
                  );
                  $('input[name="varaint_stock_status"]').val("0");
                  $("#product-type").prop("disabled", true);
                  $("#stock_level_type").prop("disabled", true);
                  $(this).removeClass("save-variant-general-settings");
                  $(".product-attributes").removeClass("disabled");
                  $(".product-variants").removeClass("disabled");
                  $(".variant-stock-level-type").prop("readonly", true);
                  $("#stock_status_variant_type").attr("readonly", true);
                  $(".variant-product-level-stock-management")
                    .find("input,select")
                    .prop("readonly", true);
                  $("#tab-for-variations").removeClass("d-none");
                  $(".variant_stock_status").prop("disabled", true);
                  $('#product-tab a[href="#product-attributes"]').tab("show");
                  save_product(this);
                } else {
                  $(".varaint-must-fill-field").each(function () {
                    $(this).css("border", "");
                    if ($(this).val() == "") {
                      $(this).css("border", "2px solid red");
                      $(this)
                        .closest("#variant_stock_management_html")
                        .find("div:first")
                        .addClass("show");
                      $('#product-tab a[href="#product-variants"]').tab("show");
                      counter++;
                    }
                  });
                }
              } else {
                Swal.fire(
                  "Variation Needed !",
                  "Atleast Add One Variation To Add The Product.",
                  "warning"
                );
              }
            }
          } else {
            iziToast.error({
              message: "Please Fill All The Fields",
            });
          }
        } else {
          if ($('input[name="variant_price[]"]').length >= 1) {
            if (
              $(".varaint-must-fill-field").filter(function () {
                return this.value === "";
              }).length == 0
            ) {
              $('input[name="product_type"]').val($("#product-type").val());
              $(".variant_stock_status").prop("disabled", true);
              $("#product-type").prop("disabled", true);
              $(".product-attributes").removeClass("disabled");
              $(".product-variants").removeClass("disabled");
              $("#tab-for-variations").removeClass("d-none");
              save_product(this);
            } else {
              $(".varaint-must-fill-field").each(function () {
                $(this).css("border", "");
                if ($(this).val() == "") {
                  $(this).css("border", "2px solid red");
                  $(this)
                    .closest("#variant_stock_management_html")
                    .find("div:first")
                    .addClass("show");
                  $('#product-tab a[href="#product-variants"]').tab("show");
                  counter++;
                }
              });
            }
          } else {
            Swal.fire(
              "Variation Needed !",
              "Atleast Add One Variation To Add The Product.",
              "warning"
            );
          }
        }
      } else {
        if ($('input[name="variant_price[]"]').length == 0) {
          Swal.fire(
            "Variation Needed !",
            "Atleast Add One Variation To Add The Product.",
            "warning"
          );
        } else {
          if (
            $(".varaint-must-fill-field").filter(function () {
              return this.value === "";
            }).length == 0
          ) {
            save_product(this);
          } else {
            $(".varaint-must-fill-field").each(function () {
              $(this).css("border", "");
              if ($(this).val() == "") {
                $(this).css("border", "2px solid red");
                $(this)
                  .closest("#variant_stock_management_html")
                  .find("div:first")
                  .addClass("show");
                $('#product-tab a[href="#product-variants"]').tab("show");
                counter++;
              }
            });
          }
        }
      }
    }

    if ($.trim(product_type) == "digital_product") {
      save_product(this);
    }
  } else {
    iziToast.error({
      message: "Please Select Product Type !",
    });
  }

  if (counter > 0) {
    iziToast.error({
      message: "Please fill all the required fields in the variation tab !",
    });
  }
});

$(document).on("click", "#delete-product", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/product/delete_product",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
            } else {
              Swal.fire("Oops...", response.message, "error");
            }
            $("table").bootstrapTable("refresh");
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
          });
      });
    },
    allowOutsideClick: false,
  });
});

// multiple_values
$(".select_single , .multiple_values , #product-type").each(function () {
  $(this).select2({
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
        ? "100%"
        : "style",
    placeholder: $(this).data("placeholder"),
    allowClear: Boolean($(this).data("allow-clear")),
  });
});

$(document).on("select2:selecting", ".select_single", function (e) {
  if ($.inArray($(this).val(), attributes_values_selected) > -1) {
    //Remove value if further selected
    attributes_values_selected.splice(
      attributes_values_selected.indexOf(
        $(this).select2().find(":selected").val()
      ),
      1
    );
  }
});

$(document).on(
  "select2:selecting",
  ".select_single .variant_attributes",
  function (e) {
    if ($.inArray($(this).val(), variant_values_selected) > -1) {
      //Remove value if further selected
      variant_values_selected.splice(
        variant_values_selected.indexOf(
          $(this).select2().find(":selected").val()
        ),
        1
      );
    }
  }
);

$(document).on("select2:select", ".select_single", function (e) {
  var text = this.className;
  var type;
  $(this).closest(".row").find(".multiple_values").text(null).trigger("change");
  var data = $(this).select2().find(":selected").data("values");
  if (text.search("attributes") != -1) {
    value_check_array = attributes_values_selected.slice();
    type = "attributes";
  }

  if (text.search("variant_attributes") != -1) {
    value_check_array = variant_values_selected.slice();
    type = "variant_attributes";
  }

  if (
    $.inArray($(this).select2().find(":selected").val(), value_check_array) > -1
  ) {
    iziToast.error({
      message: "Attribute Already Selected",
    });
    $(this).val("").trigger("change");
  } else {
    value_check_array.push($(this).select2().find(":selected").val());
  }
  if (text.search("attributes") != -1) {
    attributes_values_selected = value_check_array.slice();
  }

  if (text.search("variant_attributes") != -1) {
    variant_values_selected = value_check_array.slice();
  }
  $(this)
    .closest(".row")
    .find("." + type)
    .select2({
      theme: "bootstrap4",
      width: $(this).data("width")
        ? $(this).data("width")
        : $(this).hasClass("w-100")
          ? "100%"
          : "style",
      placeholder: $(this).data("placeholder"),
      allowClear: Boolean($(this).data("allow-clear")),
    });
  $(this)
    .closest(".row")
    .find(".multiple_values")
    .select2({
      theme: "bootstrap4",
      width: $(this).data("width")
        ? $(this).data("width")
        : $(this).hasClass("w-100")
          ? "100%"
          : "style",
      placeholder: $(this).data("placeholder"),
      allowClear: Boolean($(this).data("allow-clear")),
      data: data,
    });
});

$(document).on("click", " #add_attributes , #tab-for-variations", function (e) {
  if (e.target.id == "add_attributes") {
    $(".no-attributes-added").hide();
    $("#save_attributes").removeClass("d-none");
    counter++;
    var $attribute = $("#attributes_values_json_data").find(".select_single");
    var $options = $($attribute).clone().html();
    var attr_name = "pro_attr_" + counter;
    // product-attr-selectbox
    if ($("#product-type").val() == "simple_product") {
      var html =
        '<div class="form-group move row my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' +
        attr_name +
        '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' +
        $options +
        '</select></div><div class="col-md-4 col-sm-12 "> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
    } else {
      $("#note").removeClass("d-none");
      var html =
        '<div class="form-group row move my-auto p-2 border rounded bg-gray-light product-attr-selectbox" id=' +
        attr_name +
        '><div class="col-md-1 col-sm-12 text-center my-auto"><i class="fas fa-sort"></i></div><div class="col-md-4 col-sm-12"> <select name="attribute_id[]" class="attributes select_single" data-placeholder=" Type to search and select attributes"><option value=""></option>' +
        $options +
        '</select></div><div class="col-md-4 col-sm-12 "> <select name="attribute_value_ids[]" class="multiple_values" multiple="" data-placeholder=" Type to search and select attributes values"><option value=""></option> </select></div><div class="col-md-2 col-sm-6 text-center py-1 align-self-center"><input type="checkbox" name="variations[]" class="is_attribute_checked custom-checkbox "></div><div class="col-md-1 col-sm-6 text-center py-1 align-self-center "> <button type="button" class="btn btn-tool remove_attributes"> <i class="text-danger far fa-times-circle fa-2x "></i> </button></div></div>';
    }
    $("#attributes_process").append(html);

    $("#attributes_process")
      .last()
      .find(".attributes")
      .select2({
        theme: "bootstrap4",
        width: $(this).data("width")
          ? $(this).data("width")
          : $(this).hasClass("w-100")
            ? "100%"
            : "style",
        placeholder: $(this).data("placeholder"),
        allowClear: Boolean($(this).data("allow-clear")),
      });

    // $("#attributes_process").last().find(".attributes").trigger('change');

    $("#attributes_process")
      .last()
      .find(".multiple_values")
      .select2({
        theme: "bootstrap4",
        width: $(this).data("width")
          ? $(this).data("width")
          : $(this).hasClass("w-100")
            ? "100%"
            : "style",
        placeholder: $(this).data("placeholder"),
        allowClear: Boolean($(this).data("allow-clear")),
      });
  }

  if (e.target.id == "tab-for-variations") {
    $(".additional-info").block({
      message: "<h6>Loading Variations</h6>",
      css: {
        border: "3px solid #E7F3FE",
      },
    });
    if (attributes_values.length > 0) {
      $(".no-variants-added").hide();
      create_variants();
    }
    setTimeout(function () {
      $(".additional-info").unblock();
    }, 3000);
  }
});

$(document).on("click", "#reset_variants", function () {
  Swal.fire({
    title: "Are You Sure To Reset!",
    text: "You won't be able to revert this after update!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Reset it!",
    showLoaderOnConfirm: true,
    allowOutsideClick: false,
  }).then((result) => {
    if (result.value) {
      $(".additional-info").block({
        message: "<h6>Reseting Variations</h6>",
        css: {
          border: "3px solid #E7F3FE",
        },
      });
      if (attributes_values.length > 0) {
        $(".no-variants-added").hide();
        create_variants();
      }
      setTimeout(function () {
        $(".additional-info").unblock();
      }, 2000);
    }
  });
});

$(document).on("click", ".remove_edit_attribute", function (e) {
  $(this).closest(".row").remove();
});

$(document).on("click", ".remove_attributes , .remove_variants", function (e) {
  Swal.fire({
    title: "Are you sure want to delete!",
    text: "You won't be able to revert this after update!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Delete it!",
    showLoaderOnConfirm: true,
    allowOutsideClick: false,
  }).then((result) => {
    if (result.value) {
      var text = this.className;
      if (text.search("remove_attributes") != -1) {
        var edit_id = $("#edit_product_id").val();

        attributes_values_selected.splice(
          attributes_values_selected.indexOf(
            $(this).select2().find(":selected").val()
          ),
          1
        );
        $(this).closest(".row").remove();
        counter -= 1;
        var numItems = $(".product-attr-selectbox").length;
        if (numItems == 0) {
          $(".no-attributes-added").show();
          $("#save_attributes").addClass("d-none");
          $("#note").addClass("d-none");
        }
      }
      if (text.search("remove_variants") != -1) {
        variant_values_selected.splice(
          variant_values_selected.indexOf(
            $(this).select2().find(":selected").val()
          ),
          1
        );
        $(this).closest(".form-group").remove();
        variant_counter -= 1;
        var numItems = $(".product-variant-selectbox").length;
        if (numItems == 0) {
          $(".no-variants-added").show();
        }
      }
    }
  });
});

// $(document).on('change', '#main_offer_type', function () {
//     var value = $(this).val()
//     if ($.trim(value) != '') {
//         if (value == 'offer_slider') {
//             $('#active_popup_offer').hide(200)
//             $('#offer_url').hide(200)
//             // $('#active_popup_offer').hide(200)
//         }
//         else{
//             $('#active_popup_offer').show(200)
//             $('#offer_url').show(200)
//         }
//     }

// })

$(document).on("change", "#main_offer_type", function () {
  var value = $(this).val();
  if ($.trim(value) != "") {
    if (value == "offer_slider") {
      $("#active_popup_offer").hide(200);
      // $('#offer_url').show(200)
      // $('#active_popup_offer').hide(200)
    }
    if (value == "popup_offer") {
      $("#active_popup_offer").show(200);
      // $('#offer_url').hide(200)
    }
  }
});

$(document).on("select2:select", "#product-type", function () {
  var value = $(this).val();
  if ($.trim(value) != "") {
    if (value == "simple_product") {
      $("#variant_stock_level").hide(200);
      $("#general_price_section").show(200);
      $(".simple-product-save").show(700);
      $("#product-dimensions").show(200);
      $(".product-attributes").addClass("disabled");
      $(".product-variants").addClass("disabled");
      $("#digital_product_setting").hide(200);
      $(".simple-product-level-stock-management").removeClass("d-none");
    }
    if (value == "variable_product") {
      $("#general_price_section").hide(200);
      $(".simple-product-level-stock-management").hide(200);
      $(".simple-product-save").hide(200);
      $(".product-attributes").addClass("disabled");
      $(".product-variants").addClass("disabled");
      $("#variant_stock_level").show();
      $("#digital_product_setting").hide(200);
    }
    if (value == "digital_product") {
      $("#variant_stock_level").hide(200);
      $("#general_price_section").show(200);
      $("#product-dimensions").hide(200);
      $("#stock-management").hide(200);
      $(".simple-product-save").hide(200);
      $(".simple-product-level-stock-management").addClass("d-none");
      $(".simple_stock_management").addClass("d-none");
      $(".cod_allowed").addClass("d-none");
      $(".is_returnable").addClass("d-none");
      $(".is_cancelable").addClass("d-none");
      $(".product-attributes").addClass("disabled");
      $(".product-variants").addClass("disabled");
      $("#digital_product_setting").show();
    }
  } else {
    $(".product-attributes").addClass("disabled");
    $(".product-variants").addClass("disabled");
    $("#general_price_section").hide(200);
    $(".simple-product-level-stock-management").hide(200);
    $(".simple-product-save").hide(200);
    $("#variant_stock_level").hide(200);
  }
});

$(document).on("change", ".variant_stock_status", function () {
  if ($(this).prop("checked") == true) {
    $(this).attr("checked", true);
    $("#stock_level").show(200);
  } else {
    $(this).attr("checked", false);
    $("#stock_level").hide(200);
  }
});

$(document).on("change", ".variant-stock-level-type", function () {
  if ($(".variant-stock-level-type").val() == "product_level") {
    $(".variant-product-level-stock-management").show();
  }
  if ($.trim($(".variant-stock-level-type").val()) != "product_level") {
    $(".variant-product-level-stock-management").hide();
  }
});

$(document).on("change", ".simple_stock_management_status", function () {
  if ($(this).prop("checked") == true) {
    $(this).attr("checked", true);
    $(".simple-product-level-stock-management").show(200);
  } else {
    $(this).attr("checked", false);
    $(".simple-product-level-stock-management").hide(200);
    $(".simple-product-level-stock-management").find("input").val("");
  }
});

$(document).on("click", "#save_attributes", function () {
  Swal.fire({
    title: "Are you sure want to save changes!",
    text: "Do not save attributes if you made no changes! It will reset the variants if there are no changes in attributes or its values !",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, save it!",
    showLoaderOnConfirm: true,
    allowOutsideClick: false,
  }).then((result) => {
    if (result.value) {
      attribute_flag = 1;
      save_attributes();
      create_fetched_variants_html(true);
      iziToast.success({
        message: "Attributes Saved Successfully",
      });
    }
  });
});

$("#attributes_process").sortable({
  axis: "y",
  opacity: 0.6,
  cursor: "grab",
});

$("#variants_process").sortable({
  axis: "y",
  opacity: 0.6,
  cursor: "grab",
});

$(document).on("click", ".reset-settings", function (e) {
  Swal.fire({
    title: "Are You Sure To Reset!",
    text: "This will reset all attributes && variants too if added.",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Reset it!",
    showLoaderOnConfirm: true,
    allowOutsideClick: false,
  }).then((result) => {
    if (result.value) {
      attributes_values_selected = [];
      value_check_array = [];
      pre_selected_attr_values = [];
      var html =
        '<input type="hidden" name="reset_settings" value="1"><div class="row mt-4 col-md-12 "> <nav class="w-100"><div class="nav nav-tabs" id="product-tab" role="tablist"> <a class="nav-item nav-link active" id="tab-for-general-price" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general-price" aria-selected="true">General</a> <a class="nav-item nav-link disabled product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Attributes</a> <a class="nav-item nav-link disabled product-variants d-none" id="tab-for-variations" data-toggle="tab" href="#product-variants" role="tab" aria-controls="product-variants" aria-selected="false">Variations</a></div> </nav><div class="tab-content p-3 col-md-12" id="nav-tabContent"><div class="tab-pane fade active show" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab"><div class="form-group"> <label for="type" class="col-md-2">Type Of Product :</label><div class="col-md-12"> <input type="hidden" name="product_type"> <input type="hidden" name="simple_product_stock_status"> <input type="hidden" name="variant_stock_level_type"> <input type="hidden" name="variant_stock_status"> <select name="type" id="product-type" class="form-control product-type" data-placeholder=" Type to search and select type"><option value=" ">Select Type</option><option value="simple_product">Simple Product</option><option value="digital_product">Digital Product</option><option value="variable_product">Variable Product</option> </select></div></div><div id="product-general-settings"><div id="general_price_section" class="collapse"><div class="form-group"> <label for="type" class="col-md-2">Price:</label><div class="col-md-12"> <input type="number" name="simple_price" class="form-control stock-simple-mustfill-field price" min="0"></div></div><div class="form-group"> <label for="type" class="col-md-2">Special Price:</label><div class="col-md-12"> <input type="number" name="simple_special_price" class="form-control discounted_price" min="0"></div></div>' +
        '<div class="form-group row mt-4">' +
        '<div class="col col-xs-12">' +
        '<label for="weight" class="control-label col-md-12">Weight <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
        '<input type="number" min="0" class="form-control" name="weight[]" placeholder="Weight" id="weight" value="" step="0.01">' +
        "</div>" +
        '<div class="col col-xs-12">' +
        '<label for="height" class="control-label col-md-12">Height <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
        '<input type="number" min="0" class="form-control" name="height[]" placeholder="Height" id="height" value="">' +
        "</div>" +
        '<div class="col col-xs-12">' +
        '<label for="breadth" class="control-label col-md-12">Breadth <small>(cms)</small> <span class="text-danger text-xs">*</span></label>' +
        '<input type="number" min="0" class="form-control" name="breadth[]" placeholder="Breadth" id="breadth" value="">' +
        "</div>" +
        '<div class="col col-xs-12">' +
        '<label for="length" class="control-label col-md-12">Length <small>(kg)</small> <span class="text-danger text-xs">*</span></label>' +
        '<input type="number" min="0" class="form-control" name="length[]" placeholder="Length" id="length" value="">' +
        "</div></div>" +
        '<div class="form-group">' +
        '<div class="col">' +
        ' <input type="checkbox" name="simple_stock_management_status" class="align-middle simple_stock_management_status">' +
        ' <span class="align-middle">Enable Stock Management</span></div></div></div>' +
        '<div class="form-group simple-product-level-stock-management collapse">' +
        '<div class="col col-xs-12">' +
        ' <label class="control-label">SKU :</label> ' +
        '<input type="text" name="product_sku" class="col form-control simple-pro-sku"></div><div class="col col-xs-12">' +
        ' <label class="control-label">Total Stock :</label>' +
        ' <input type="text" name="product_total_stock" class="col form-control stock-simple-mustfill-field"></div>' +
        '<div class="col col-xs-12"> <label class="control-label">Stock Status :</label>' +
        ' <select type="text" class="col form-control stock-simple-mustfill-field" id="simple_product_stock_status">' +
        '<option value="1">In Stock</option><option value="0">Out Of Stock</option> </select></div></div>' +
        '<div class="form-group collapse simple-product-save"><div class="col">' +
        ' <a href="javascript:void(0);" class="btn btn-primary save-settings">Save Settings</a></div></div></div>' +
        '<div id="variant_stock_level" class="collapse"><div class="form-group"><div class="col"> <input type="checkbox" name="variant_stock_management_status" class="align-middle variant_stock_status">' +
        ' <span class="align-middle"> Enable Stock Management</span></div></div><div class="form-group collapse" id="stock_level"> <label for="type" class="col-md-2">Choose Stock Management Type:</label><div class="col-md-12"> <select id="stock_level_type" class="form-control variant-stock-level-type" data-placeholder=" Type to search and select type"><option value=" ">Select Stock Type</option><option value="product_level">Product Level ( Stock Will Be Managed Generally )</option><option value="variable_level">Variable Level ( Stock Will Be Managed Variant Wise )</option> </select><div class="form-group row variant-product-level-stock-management collapse"><div class="col col-xs-12"> <label class="control-label">SKU :</label> <input type="text" name="sku_variant_type" class="col form-control"></div><div class="col col-xs-12"> <label class="control-label">Total Stock :</label> <input type="text" name="total_stock_variant_type" class="col form-control variant-stock-mustfill-field"></div><div class="col col-xs-12"> <label class="control-label">Stock Status :</label> <select type="text" id="stock_status_variant_type" name="variant_status" class="col form-control variant-stock-mustfill-field"><option value="1">In Stock</option>' +
        '<option value="0">Out Of Stock</option> </select></div></div></div></div><div class="form-group"><div class="col"> <a href="javascript:void(0);" class="btn btn-primary save-variant-general-settings">Save Settings</a></div></div></div></div><div class="tab-pane fade" id="product-attributes" role="tabpanel" aria-labelledby="product-attributes-tab"><div class="info col-12 p-3 d-none" id="note"><div class=" col-12 d-flex align-center"> <strong>Note : </strong> <input type="checkbox" checked="checked" class="ml-3 my-auto custom-checkbox" disabled> <span class="ml-3">check if the attribute is to be used for variation </span></div></div><div class="col-md-12"> <a href="javascript:void(0);" id="add_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 ">Add Attributes</a> <a href="javascript:void(0);" id="save_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2  d-none">Save Attributes</a></div><div class="clearfix"></div><div id="attributes_process"><div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-attributes-added"><div class="col-md-12 text-center">No Product Attribures Are Added !</div></div></div></div><div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="product-variants-tab"><div class="clearfix"></div><div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-variants-added"><div class="col-md-12 text-center">No Product Variations Are Added !</div></div><div id="variants_process" class="ui-sortable"></div></div></div></div>';

      $(".additional-info").html(html);
      $(".no-attributes-added").show();
      $("#product-type").each(function () {
        $(this).select2({
          theme: "bootstrap4",
          width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
              ? "100%"
              : "style",
          placeholder: $(this).data("placeholder"),
          allowClear: Boolean($(this).data("allow-clear")),
        });
      });
    }
  });
});
$(document).on("click", ".save-settings", function (e) {
  e.preventDefault();

  if ($(".simple_stock_management_status").is(":checked")) {
    var len = 0;
  } else {
    var len = 1;
  }

  if (
    $(".stock-simple-mustfill-field").filter(function () {
      return this.value === "";
    }).length === len
  ) {
    $(".additional-info").block({
      message: "<h6>Saving Settings</h6>",
      css: {
        border: "3px solid #E7F3FE",
      },
    });

    $('input[name="product_type"]').val($("#product-type").val());
    if ($(".simple_stock_management_status").is(":checked")) {
      $('input[name="simple_product_stock_status"]').val(
        $("#simple_product_stock_status").val()
      );
    } else {
      $('input[name="simple_product_stock_status"]').val("");
    }
    $("#product-type").prop("disabled", true);
    $(".product-attributes").removeClass("disabled");
    $(".product-variants").removeClass("disabled");
    $(".simple_stock_management_status").prop("disabled", true);
    setTimeout(function () {
      $(".additional-info").unblock();
    }, 2000);
  } else {
    iziToast.error({
      message: "Please Fill All The Fields",
    });
  }
});

$(document).on("click", ".save-variant-general-settings", function (e) {
  e.preventDefault();
  if ($(".variant_stock_status").is(":checked")) {
    if (
      $(".variant-stock-level-type").filter(function () {
        return this.value === "";
      }).length === 0 &&
      $.trim($(".variant-stock-level-type").val()) != ""
    ) {
      if (
        $(".variant-stock-level-type").val() == "product_level" &&
        $(".variant-stock-mustfill-field").filter(function () {
          return this.value === "";
        }).length !== 0
      ) {
        iziToast.error({
          message: "Please Fill All The Fields",
        });
      } else {
        $('input[name="product_type"]').val($("#product-type").val());
        $('input[name="variant_stock_level_type"]').val(
          $("#stock_level_type").val()
        );
        $('input[name="variant_stock_status"]').val("0");
        $("#product-type").prop("disabled", true);
        $("#stock_level_type").prop("disabled", true);
        $(this).removeClass("save-variant-general-settings");
        $(".product-attributes").removeClass("disabled");
        $(".product-variants").removeClass("disabled");
        $(".variant-stock-level-type").prop("readonly", true);
        $("#stock_status_variant_type").attr("readonly", true);
        $(".variant-product-level-stock-management")
          .find("input,select")
          .prop("readonly", true);
        $("#tab-for-variations").removeClass("d-none");
        $(".variant_stock_status").prop("disabled", true);
        $('#product-tab a[href="#product-attributes"]').tab("show");
        Swal.fire(
          "Settings Saved !",
          "Attributes & Variations Can Be Added Now",
          "success"
        );
      }
    } else {
      iziToast.error({
        message: "Please Fill All The Fields",
      });
    }
  } else {
    $('input[name="product_type"]').val($("#product-type").val());
    $('input[name="variant_stock_status"]').val("");
    $('input[name="variant_stock_level_type"]').val("");
    $('#product-tab a[href="#product-attributes"]').tab("show");
    $(".variant_stock_status").prop("disabled", true);
    $("#product-type").prop("disabled", true);
    $(".product-attributes").removeClass("disabled");
    $(".product-variants").removeClass("disabled");
    $("#tab-for-variations").removeClass("d-none");
    Swal.fire(
      "Settings Saved !",
      "Attributes & Variations Can Be Added Now",
      "success"
    );
  }
});

$(document).on("change", ".new-added-variant", function () {
  var myOpts = $(this)
    .children()
    .map(function () {
      return $(this).val();
    })
    .get();
  var variant_id = $(this).val();
  var curr_vals = [];
  var $variant_ids = $(this)
    .closest(".product-variant-selectbox")
    .find('input[name="variants_ids[]"]')
    .val();
  $.each($variant_ids.split(","), function (key, val) {
    if (val != "") {
      curr_vals[key] = $.trim(val);
    }
  });
  var newvalues = curr_vals.filter((el) => !myOpts.includes(el));
  var len = newvalues.length;
  if (variant_id != "") {
    newvalues[len] = $.trim(variant_id);
  }
  $(this)
    .closest(".product-variant-selectbox")
    .find('input[name="variants_ids[]"]')
    .val(newvalues.toString());
});

if (window.location.href.indexOf("admin/product") > -1) {
  var edit_id = $('input[name="category_id"]').val();
  var ignore_status = $.isNumeric(edit_id) && edit_id > 0 ? 1 : 0;
  // var ignore_status = 0
  $.ajax({
    type: "GET",
    url: base_url + "admin/category/get_categories",
    data: {
      ignore_status: ignore_status,
    },
    dataType: "json",
    success: function (result) {
      var edit_id = $('input[name="category_id"]').val();

      $("#product_category_tree_view_html").jstree({
        plugins: ["checkbox", "themes"],
        core: {
          data: result["data"],
          multiple: false,
        },
        checkbox: {
          three_state: false,
          cascade: "none",
        },
      });

      $("#product_category_tree_view_html").bind(
        "ready.jstree",
        function (e, data) {
          $(this).jstree(true).select_node(edit_id);
        }
      );
    },
  });
}

// 3.Category-Module
$(document).on("click", "#list_view", function () {
  $("#list_view_html").show();
  $("#tree_view_html").hide();
});

$(document).on("click", "#tree_view", function () {
  $("#tree_view_html").show();
  $("#list_view_html").hide();

  $.ajax({
    type: "GET",
    url: base_url + "admin/category/get_categories",
    dataType: "json",
    success: function (result) {
      $("#tree_view_html").jstree({
        core: {
          data: result["data"],
        },
      });
    },
  });
});

$(document).on("click", ".update_active_status", function () {
  var update_id = $(this).data("id");
  var status = $(this).data("status");
  var table = $(this).data("table");

  $.ajax({
    type: "GET",
    url: base_url + "admin/home/update_status",
    data: {
      id: update_id,
      status: status,
      table: table,
    },
    dataType: "json",
    success: function (result) {
      if (result.error === false) {
        iziToast.success({
          message:
            '<span class="text-capitalize">' + result.message + "</span>",
        });

        $(".table").bootstrapTable("refresh");

        setTimeout(function () {
          location.reload();
        }, 1000);
      } else {
        iziToast.error({
          message:
            '<span class="text-capitalize">' + result.message + "</span>",
        });

        $(".table").bootstrapTable("refresh");
      }
    },
  });
});

$(document).on("click", ".update_default_theme", function () {
  var theme_id = $(this).data("id");
  $.ajax({
    type: "POST",
    url: base_url + "admin/setting/set-default-theme",
    data: {
      [csrfName]: csrfHash,
      theme_id: theme_id,
    },
    dataType: "json",
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result["error"] == false) {
        iziToast.success({
          message: result.message,
        });
        $(".table").bootstrapTable("refresh");
      } else {
        iziToast.error({
          message: result.message,
        });
      }
    },
  });
});

$(document).on("click", ".delete-categoty", function () {
  var cat_id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/category/delete_category",
          data: {
            id: cat_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            } else {
              Swal.fire("Oops...", response.message, "warning");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
          });
      });
    },
    allowOutsideClick: false,
  });
});

// $(document).on('click', '.delete-brand', function () {
//     var brand_id = $(this).data('id')
//     Swal.fire({
//         title: 'Are You Sure!',
//         text: "You won't be able to revert this!",
//         type: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, delete it!',
//         showLoaderOnConfirm: true,
//         preConfirm: function () {
//             return new Promise((resolve, reject) => {
//                 $.ajax({
//                     type: 'GET',
//                     url: base_url + 'admin/brand/delete_brand',
//                     data: {
//                         id: brand_id
//                     },
//                     dataType: 'json'
//                 })
//                     .done(function (response, textStatus) {
//                         if (response.error == true) {
//                             Swal.fire('Deleted!', 'Brand deleted successfully', 'success')
//                             $('table').bootstrapTable('refresh')
//                             csrfName = response['csrfName']
//                             csrfHash = response['csrfHash']
//                         } else {
//                             Swal.fire('Oops...', 'Something went wrong with ajax !', 'error')
//                             $('table').bootstrapTable('refresh')
//                             csrfName = response['csrfName']
//                             csrfHash = response['csrfHash']
//                         }
//                     })
//                     .fail(function (jqXHR, textStatus, errorThrown) {
//                         Swal.fire('Oops...', 'Something went wrong with ajax !', 'error')
//                         csrfName = response['csrfName']
//                         csrfHash = response['csrfHash']
//                     })
//             })
//         },
//         allowOutsideClick: false
//     })
// })
$(document).on("click", ".delete-brand", function () {
  var brand_id = $(this).data("id");

  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    icon: "warning", // Changed from 'type' to 'icon'
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/brand/delete_brand",
          data: {
            id: brand_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response) {
            // Check if the response indicates success or failure
            if (response.error) {
              Swal.fire("Oops...", response.message, "error");
            } else {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
            }
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
            resolve();
          })
          .fail(function () {
            Swal.fire("Oops...", "Something went wrong with ajax!", "error");
            resolve(); // Resolve promise even on fail
          });
      });
    },
    allowOutsideClick: false,
  });
});

$("#category_parent").each(function () {
  $(this).select2({
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
        ? "100%"
        : "style",
    placeholder: $(this).data("placeholder"),
    allowClear: Boolean($(this).data("allow-clear")),
    dropdownCssClass: "test",
    templateResult: function (data) {
      // We only really care if there is an element to pull classes from
      if (!data.element) {
        return data.text;
      }

      var $element = $(data.element);

      var $wrapper = $("<span></span>");
      $wrapper.addClass($element[0].className);

      $wrapper.text(data.text);

      return $wrapper;
    },
  });
});

$(".get_blog_category").select2({
  ajax: {
    url: base_url + "admin/blogs/get_blog_category",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
      };
    },
    processResults: function (response) {
      return {
        results: response,
      };
    },
    cache: true,
  },
  minimumInputLength: 1,
  theme: "bootstrap4",
  placeholder: "Search for categories",
});

$(document).on("click", "#save_category_order", function () {
  var data = $("#sortable").sortable("serialize");
  $.ajax({
    data: data,
    type: "GET",
    url: base_url + "admin/category/update_category_order",
    dataType: "json",
    success: function (response) {
      if (response.error == false) {
        iziToast.success({
          message: response.message,
        });
        setTimeout(function () {
          location.reload();
        }, 1000);
      } else {
        iziToast.error({
          message: response.message,
        });
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
    },
  });
});

//4.Order-Module
$("#datepicker").attr({
  placeholder: "Select Date Range To Filter",
  autocomplete: "off",
});
$("#datepicker").on("cancel.daterangepicker", function (ev, picker) {
  $(this).val("");
  $("#start_date").val("");
  $("#end_date").val("");
});
$("#datepicker").on("apply.daterangepicker", function (ev, picker) {
  var drp = $("#datepicker").data("daterangepicker");
  $("#start_date").val(drp.startDate.format("YYYY-MM-DD"));
  $("#end_date").val(drp.endDate.format("YYYY-MM-DD"));
  $(this).val(
    picker.startDate.format("MM/DD/YYYY") +
    " - " +
    picker.endDate.format("MM/DD/YYYY")
  );
});

$("#datepicker").daterangepicker({
  showDropdowns: true,
  alwaysShowCalendars: true,
  autoUpdateInput: false,
  ranges: {
    Today: [moment(), moment()],
    Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
    "Last 7 Days": [moment().subtract(6, "days"), moment()],
    "Last 30 Days": [moment().subtract(29, "days"), moment()],
    "This Month": [moment().startOf("month"), moment().endOf("month")],
    "Last Month": [
      moment().subtract(1, "month").startOf("month"),
      moment().subtract(1, "month").endOf("month"),
    ],
  },
  startDate: moment().subtract(29, "days"),
  endDate: moment(),
  locale: {
    format: "DD/MM/YYYY",
    separator: " - ",
    cancelLabel: "Clear",
    label: "Select range of dates to filter",
  },
});

$(document).on("click", ".update_discount", function () {
  var field = "discount";
  var orderid = $(this).data("orderid");
  var json = $(this).data("isjson");
  if (typeof json == "undefined") {
    json = false;
  }
  val = $(this).parent().find("input[type=number]").val();
  var amount = $("#amount").html();
  var delivery_charge = $("#delivery_charge").html();
  var total = parseInt(amount) + parseInt(delivery_charge);
  $.ajax({
    type: "POST",
    url: base_url + "admin/orders/update_orders",
    data: {
      orderid: orderid,
      field: field,
      val: val,
      json: json,
      [csrfName]: csrfHash,
    },
    dataType: "json",
    success: function (result) {
      csrfName = result["csrfName"];
      csrfHash = result["csrfHash"];
      if (result["error"] == false) {
        iziToast.success({
          message: "Discount Updated Successfully",
        });
        $("#final_total").val(parseInt(result["total_amount"]));
      } else {
        iziToast.error({
          title: "Error",
          message: "Illegal operation",
        });
      }
    },
  });
});

$(document).on("click", ".update_order", function () {
  var field = "status";
  var deliver_by = $("#deliver_by").val();
  var val = $("#status").val();
  var seller_notes = $("#seller_notes").val();
  var pickup_time = $("#pickup_time").val();

  var orderid = $("#status").data("orderid");
  var json = $("#status").data("isjson");
  if (typeof json == "undefined") {
    json = false;
  }

  if ((val == "delivered" || val == "shipped") && deliver_by == "") {
    iziToast.error({
      message: "Please Select Delivery Boy",
    });
  } else {
    Swal.fire({
      title: "Are You Sure!",
      text: "You won't be able to revert this!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, update it!",
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return new Promise((resolve, reject) => {
          $.ajax({
            type: "POST",
            url: base_url + "admin/orders/update_orders",
            data: {
              orderid: orderid,
              seller_notes: seller_notes,
              pickup_time: pickup_time,
              field: field,
              val: val,
              deliver_by: deliver_by,
              json: json,
              [csrfName]: csrfHash,
            },
            dataType: "json",
            success: function (result) {
              csrfName = result["csrfName"];
              csrfHash = result["csrfHash"];
              if (result["error"] == false) {
                iziToast.success({
                  message: result["message"],
                });
                setTimeout(function () {
                  location.reload();
                }, 500);
              } else {
                iziToast.error({
                  message: result["message"],
                });
              }
              swal.close();
            },
          });
        });
      },
      allowOutsideClick: false,
    });
  }
});

$(document).on("click", ".update_order_delivery_boy", function () {
  var field = "status";
  var deliver_by = $("#deliver_by").val();
  var val = $("#status").val();

  var orderid = $("#status").data("orderid");
  var json = $("#status").data("isjson");
  if (typeof json == "undefined") {
    json = false;
  }
  var otp_system = $(this).data("otp-system");
  var post_otp = 0;
  if (otp_system == 1 && val == "delivered") {
    post_otp = prompt("Enter Order OTP");
  }
  if (val == "delivered" && deliver_by == "") {
    iziToast.error({
      message: "Please Select Delivery Boy",
    });
  } else {
    Swal.fire({
      title: "Are You Sure!",
      text: "You won't be able to revert this!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, update it!",
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return new Promise((resolve, reject) => {
          $.ajax({
            type: "POST",
            url: base_url + "delivery_boy/orders/update_orders",
            data: {
              orderid: orderid,
              field: field,
              val: val,
              deliver_by: deliver_by,
              json: json,
              otp: post_otp,
              [csrfName]: csrfHash,
            },
            dataType: "json",
            success: function (result) {
              csrfName = result["csrfName"];
              csrfHash = result["csrfHash"];
              if (result["error"] == false) {
                iziToast.success({
                  message: result["message"],
                });
              } else {
                iziToast.error({
                  message: result["message"],
                });
              }
              swal.close();
            },
          });
        });
      },
      allowOutsideClick: false,
    });
  }
});

$(document).on("click", ".update_status_admin", function (e) {
  var order_id = $(this).data("id");
  var status = $(this).closest(".row").find("select").val();

  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, update it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/orders/update_order_status",
          data: {
            id: order_id,
            status: status,
          },
          dataType: "json",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["error"] == false) {
              iziToast.success({
                message: result["message"],
              });
            } else {
              iziToast.error({
                message: result["message"],
              });
            }
            swal.close();
          },
        });
      });
    },
    allowOutsideClick: false,
  });
});
$(document).on("click", ".update_mail_status_admin", function (e) {
  var order_id = $(this).data("id");

  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, update it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/orders/update_mail_status",
          data: {
            id: order_id,
          },
          dataType: "json",
          success: function (result) {
            // $('.order_status').removeClass('d-none')
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];

            if (result["error"] == false) {
              iziToast.success({
                message: result["message"],
              });
            } else {
              iziToast.error({
                message: result["message"],
              });
            }
            swal.close();
          },
        });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on("click", ".update_status_delivery_boy", function (e) {
  var order_item_id = $(this).data("id");
  var otp_system = $(this).data("otp-system");
  order_item_id = order_item_id.replace(" ", "");
  var status = $(this).closest(".row").find("select").val();
  var post_otp = 0;
  if (otp_system == 1 && status == "delivered") {
    post_otp = prompt("Enter Order OTP");
  }

  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, update it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "delivery_boy/orders/update_order_status",
          data: {
            id: order_item_id,
            status: status,
            otp: post_otp,
          },
          dataType: "json",
          success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["error"] == false) {
              iziToast.success({
                message: result["message"],
              });
            } else {
              iziToast.error({
                message: result["message"],
              });
            }
            swal.close();
          },
        });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on("click", ".delete-orders", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/orders/delete_orders",
          data: {
            id: id,
          },
          dataType: "json",
          success: function (result) {
            if (result["error"] == false) {
              Swal.fire("Deleted!", "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", result["message"], "error");
            }
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});

//5.Featured_Section-Module
$(".select_multiple").each(function () {
  $(this).select2({
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
        ? "100%"
        : "style",
    placeholder: $(this).data("placeholder"),
    allowClear: Boolean($(this).data("allow-clear")),
  });
});

$(".search_product").each(function () {
  $(this).select2({
    ajax: {
      url: base_url + "admin/product/get_product_data",
      dataType: "json",
      delay: 250,
      data: function (data) {
        return {
          search: data.term, // search term
          limit: 10,
          status: 1,
        };
      },
      processResults: function (response) {
        return {
          results: response.rows,
        };
      },
      cache: true,
    },
    escapeMarkup: function (markup) {
      return markup;
    },
    minimumInputLength: 1,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection,
    placeholder: "Search for products",
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
        ? "100%"
        : "style",
    placeholder: $(this).data("placeholder"),
    allowClear: Boolean($(this).data("allow-clear")),
  });
});

$(".search_admin_digital_product").each(function () {
  $(this).select2({
    ajax: {
      url: base_url + "admin/product/get_digital_product_data",
      dataType: "json",
      delay: 250,
      data: function (data) {
        return {
          search: data.term, // search term
          limit: 10,
          status: 1,
        };
      },
      processResults: function (response) {
        return {
          results: response.rows,
        };
      },
      cache: true,
    },
    escapeMarkup: function (markup) {
      return markup;
    },
    minimumInputLength: 1,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection,
    placeholder: "Search for products",
    theme: "bootstrap4",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
        ? "100%"
        : "style",
    placeholder: $(this).data("placeholder"),
    allowClear: Boolean($(this).data("allow-clear")),
  });
});

$(document).on("click", "#delete-featured-section", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/featured_sections/delete_featured_section",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          success: function (result) {
            if (result["error"] == false) {
              Swal.fire("Deleted!", result["message"], "success");
              setInterval(() => {
                location.reload();
              }, 1000);
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});

//6.Notifation-Module
$("#image_checkbox").on("click", function () {
  if (this.checked) {
    $(this).prop("checked", true);
    $(".include_image").removeClass("d-none");
  } else {
    $(this).prop("checked", false);
    $(".include_image").addClass("d-none");
  }
});

$(document).on("click", ".delete_notifications", function () {
  var value = $(this).data("id");
  var url = base_url + "admin/Notification_settings/delete_notification";
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: url,
          type: "GET",
          data: {
            id: value,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Deleted!", result["message"], "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on("click", ".save-digital-product-settings", function (e) {
  e.preventDefault();
  $(".product-attributes").removeClass("disabled");
});

$(document).on("click", ".delete_system_noti", function () {
  var value = $(this).data("id");
  var url = base_url + "admin/Notification_settings/delete_system_notification";
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: url,
          type: "GET",
          data: {
            id: value,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Deleted!", result["message"], "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//7.Faq-Module
$(document).on("click", ".edit_faq", function () {
  var faq = $(this).parent().closest(".card");
  if ($(this).hasClass("cancel")) {
    $(this).removeClass("cancel");
    $(this).html('<i class="fa fa-pen"></i>');
    $(this).closest("button").addClass("btn-success").removeClass("btn-danger");
    $(faq).find("input").addClass("d-none");
    $(faq).find("textarea").addClass("d-none");
    $(faq).find(".faq_question").show();
    $(faq).find(".faq_answer").show();
    $(faq).find(".save").addClass("d-none");
    $(faq).find(".collapse").collapse("hide");
  } else {
    $(this).addClass("cancel");
    $(this).html('<i class="fa fa-times"></i>');
    $(this).closest("button").addClass("btn-danger").removeClass("btn-success");
    var question = $(faq).find(".faq_question").html();
    var answer = $(faq).find(".faq_answer").html();
    $(faq).find(".faq_question").hide();
    $(faq).find(".faq_answer").hide();
    $(faq).find(".collapse").collapse("show");
    $(faq).find("input").removeClass("d-none").val($.trim(question));
    $(faq).find(".save").removeClass("d-none");
    $(faq).find("textarea").removeClass("d-none").val($.trim(answer));
  }
});

$(document).on("click", ".delete_faq", function () {
  var id = $(this).data("id");
  var t = this;
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/faq/delete_faq",
          type: "GET",
          data: {
            id: id,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              $(t).closest(".card").remove();
              Swal.fire("Deleted!", result["message"], "success");
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
            location.reload();
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//8.Slider-Module
$(document).on("click", "#delete-slider", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Slider/delete_slider",
          type: "GET",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on("change", ".product_type", function (e, data) {
  e.preventDefault();
  var sort_type_val = $(this).val();
  if (sort_type_val == "custom_products" && sort_type_val != " ") {
    $(".custom_products").removeClass("d-none");
  } else {
    $(".custom_products").addClass("d-none");
  }
  if (sort_type_val == "digital_product" && sort_type_val != " ") {
    $(".digital_products").removeClass("d-none");
  } else {
    $(".digital_products").addClass("d-none");
  }
});

//9.Offer-Module
$(document).on("click", "#delete-offer", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Offer/delete_offer",
          type: "GET",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//10.Promo_code-Module
$(document).on("change", "#repeat_usage", function () {
  var repeat_usage = $(this).val();
  if (typeof repeat_usage != "undefined" && repeat_usage == "1") {
    $("#repeat_usage_html").removeClass("d-none");
  } else {
    $("#repeat_usage_html").addClass("d-none");
  }
});

$(document).on("change", "#discount_type", function () {
  var discount_type = $(this).val();
  if (typeof discount_type != "undefined" && discount_type == "percentage") {
    $("#max_discount_amount_html").removeClass("d-none");
  } else {
    $("#max_discount_amount_html").addClass("d-none");
  }
});

$(document).on("click", "#delete-promo-code", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Promo_code/delete_promo_code",
          type: "GET",
          data: {
            id: id,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $(".table-striped").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//11.Delivery_boys-Module
$(document).on("click", "#delete-delivery-boys", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Delivery_boys/delete_delivery_boys",
          type: "GET",
          data: {
            id: id,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//12.Settings-Module
$(document).on("click", "#delete-time-slot", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Time_slots/delete_time_slots",
          type: "GET",
          data: {
            id: id,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            Swal.fire("Deleted!", response.message);
            $(".table-striped").bootstrapTable("refresh");
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//13.City-Module
$(document).on("click", "#delete-location", function () {
  var id = $(this).data("id");
  var table = $(this).data("table");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Area/delete_city",
          type: "GET",
          data: {
            id: id,
            table: table,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const cityWiseDeliverability = document.getElementById(
    "city_wise_deliverability"
  );
  const cityDeliverySettings = document.querySelectorAll(
    ".city-delivery-settings"
  );

  if (cityWiseDeliverability) {
    cityWiseDeliverability.addEventListener("change", function () {
      if (this.checked) {
        cityDeliverySettings.forEach((el) => (el.style.display = "block"));
      } else {
        cityDeliverySettings.forEach((el) => (el.style.display = "none"));
      }
    });
  }
});

//14.Transaction_Module
$(document).on("change", "#transaction_type", function () {
  $(".table-striped").bootstrapTable("refresh");
});

//15.Customer-Wallet-Module
$("#customers").on("check.bs.table", function (e, row) {
  $("#customer_dtls").val(row.name + " | " + row.email);
  $("#user_id").val(row.id);
});

//16.Fund-Transder-Module
$("#fund_transfer").on(
  "click-cell.bs.table",
  function (field, value, row, $el) {
    var balance = $el.balance
      ? $el.balance.toString().replace(/,/g, "")
      : $el.balance;

    $("#name").val($el.name);
    $("#mobile").val($el.mobile);
    $("#balance").val(balance);
    $("#delivery_boy_id").val($el.id);
  }
);

//17.Return-Request-Module
$("#return_request_table").on(
  "click-cell.bs.table",
  function (field, value, row, $el) {
    // Set form values based on the clicked row
    $('input[name="return_request_id"]').val($el.id);
    $("#user_id").val($el.user_id);
    $("#order_item_id").val($el.order_item_id);
    $("#update_remarks").html($el.remarks);

    // Handle radio button selection and visibility of delivery selection based on status
    if ($el.status_digit == 0) {
      // Pending
      $(".pending").prop("checked", true);
      $("#return_request_delivery_by").addClass("d-none");
      $("input[type=radio][name=status]").prop("disabled", false); // Enable radio buttons
    } else if ($el.status_digit == 1) {
      // Approved
      $(".approved").prop("checked", true);
      $("#return_request_delivery_by").removeClass("d-none");
      $("input[type=radio][name=status]").prop("disabled", true); // Disable radio buttons
    } else if ($el.status_digit == 2) {
      // Rejected
      $(".rejected").prop("checked", true);
      $("#return_request_delivery_by").addClass("d-none");
      $("input[type=radio][name=status]").prop("disabled", true); // Disable radio buttons
    }
  }
);

$("input[type=radio][name=status]").change(function () {
  var status = $('input[type=radio][name="status"]:checked').val();
  if (status == 0) {
    $("#return_request_delivery_by").addClass("d-none");
  } else if (status == 1) {
    $("#return_request_delivery_by").removeClass("d-none");
  } else if (status == 2) {
    $("#return_request_delivery_by").addClass("d-none");
  }
});

//18.Tax-Module
$(document).on("click", "#delete-tax", function () {
  var tax_id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/taxes/delete_tax",
          data: {
            id: tax_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success").then(() => {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                location.reload();
              });
            } else {
              Swal.fire("Opps", response.message, "warning");
            }
            $("table").bootstrapTable("refresh");
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//19.Payment-Request-Module
$(document).on("click", ".edit_request", function (e) {
  e.preventDefault();
  var $row = $(this).closest("tr");
  var table = $("#payment_request_table").bootstrapTable("getData");
  var rowIndex = $row.index();
  var $el = table[rowIndex];

  $('input[name="payment_request_id"]').val($el.id);
  $("#update_remarks").html($el.remarks);

  $('input[name="status"]').prop("disabled", false);
  $(".pending-label, .rejected-label, .approved-label")
    .removeClass("disabled")
    .css("opacity", "1")
    .css("cursor", "pointer");

  if ($el.status_digit == 0) {
    $(".pending").prop("checked", true);
  } else if ($el.status_digit == 1) {
    $(".approved").prop("checked", true);
    $('input[name="status"][value="0"], input[name="status"][value="2"]').prop(
      "disabled",
      true
    );
    $(".pending-label, .rejected-label")
      .addClass("disabled")
      .css("opacity", "0.5")
      .css("cursor", "not-allowed");
  } else if ($el.status_digit == 2) {
    $(".rejected").prop("checked", true);
  }
});

$("#payment_request_table").on(
  "click-cell.bs.table",
  function (field, value, row, $el) {
    $('input[name="payment_request_id"]').val($el.id);
    $("#update_remarks").html($el.remarks);

    $('input[name="status"]').prop("disabled", false);
    $(".pending-label, .rejected-label, .approved-label")
      .removeClass("disabled")
      .css("opacity", "1")
      .css("cursor", "pointer");

    if ($el.status_digit == 0) {
      $(".pending").prop("checked", true);
    } else if ($el.status_digit == 1) {
      $(".approved").prop("checked", true);
      $(
        'input[name="status"][value="0"], input[name="status"][value="2"]'
      ).prop("disabled", true);
      $(".pending-label, .rejected-label")
        .addClass("disabled")
        .css("opacity", "0.5")
        .css("cursor", "not-allowed");
    } else if ($el.status_digit == 2) {
      $(".rejected").prop("checked", true);
    }
  }
);

$("#upload-media").on("click", function () {
  var $result = $("#media-upload-table").bootstrapTable("getSelections");

  var path = base_url + $result[0].sub_directory + $result[0].name;
  var sub_directory = $result[0].sub_directory + $result[0].name;
  var media_type = $("#media-upload-modal")
    .find('input[name="media_type"]')
    .val();
  var input = $("#media-upload-modal")
    .find('input[name="current_input"]')
    .val();
  var is_removable = $("#media-upload-modal")
    .find('input[name="remove_state"]')
    .val();
  var ismultipleAllowed = $("#media-upload-modal")
    .find('input[name="multiple_images_allowed_state"]')
    .val();
  var removable_btn =
    is_removable == "1"
      ? '<button class="remove-image btn btn-danger btn-xs mt-3">Remove</button>'
      : "";

  $(current_selected_image)
    .closest(".form-group")
    .find(".image")
    .removeClass("d-none");
  if (ismultipleAllowed == "1") {
    for (let index = 0; index < $result.length; index++) {
      $(current_selected_image)
        .closest(".form-group")
        .find(".image-upload-section")
        .append(
          '<div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image"><div class="image-upload-div"><img class="img-fluid" alt="' +
          $result[index].name +
          '" title="' +
          $result[index].name +
          '" src=' +
          base_url +
          $result[index].sub_directory +
          $result[index].name +
          ' ><input type="hidden" name=' +
          input +
          " value=" +
          $result[index].sub_directory +
          $result[index].name +
          "></div>" +
          removable_btn +
          "</div>"
        );
    }
  } else {
    path =
      media_type != "image"
        ? base_url + "assets/admin/images/" + media_type + "-file.png"
        : path;
    $(current_selected_image)
      .closest(".form-group")
      .find(".image-upload-section")
      .html(
        '<div class=" col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image"><div class="image-upload-div"><img class="img-fluid" alt="' +
        $result[0].name +
        '" title="' +
        $result[0].name +
        '" src=' +
        path +
        ' ><input type="hidden" name=' +
        input +
        " value=" +
        sub_directory +
        "></div>" +
        removable_btn +
        "</div>"
      );
  }

  current_selected_image = "";
  $("#media-upload-modal").modal("hide");
});

$(document).on("show.bs.modal", "#media-upload-modal", function (event) {
  var triggerElement = $(event.relatedTarget);
  current_selected_image = triggerElement;
  var input = $(current_selected_image).data("input");
  var isremovable = $(current_selected_image).data("isremovable");
  var ismultipleAllowed = $(current_selected_image).data(
    "is-multiple-uploads-allowed"
  );
  var media_type = $(current_selected_image).is("[data-media_type]")
    ? $(current_selected_image).data("media_type")
    : "image";
  $("#media_type").val(media_type);
  if (ismultipleAllowed == 1) {
    $("#media-upload-table").bootstrapTable("refreshOptions", {
      singleSelect: false,
    });
  } else {
    $("#media-upload-table").bootstrapTable("refreshOptions", {
      singleSelect: true,
    });
  }

  $(this).find('input[name="current_input"]').val(input);
  $(this).find('input[name="remove_state"]').val(isremovable);
  $(this)
    .find('input[name="multiple_images_allowed_state"]')
    .val(ismultipleAllowed);
});

$(document).on("change", "#video_type", function () {
  var video_type = $(this).val();
  if (video_type == "youtube" || video_type == "vimeo") {
    $("#video_link_container").removeClass("d-none");
    $("#video_media_container").addClass("d-none");
  } else if (video_type == "self_hosted") {
    $("#video_link_container").addClass("d-none");
    $("#video_media_container").removeClass("d-none");
  } else {
    $("#video_link_container").addClass("d-none");
    $("#video_media_container").addClass("d-none");
  }
});
$(document).on("change", "#download_link_type", function () {
  var download_link_type = $(this).val();
  if (download_link_type == "add_link") {
    $("#digital_link_container").removeClass("d-none");
    $("#digital_media_container").addClass("d-none");
  } else if (download_link_type == "self_hosted") {
    $("#digital_link_container").addClass("d-none");
    $("#digital_media_container").removeClass("d-none");
  } else {
    $("#digital_media_container").addClass("d-none");
    $("#digital_link_container").addClass("d-none");
  }
});
if ($("#tags").length) {
  var tags_element = document.querySelector("input[name=tags]");
  new Tagify(tags_element);
}

$(document).on("show.bs.modal", "#product-faqs-modal", function (event) {
  var triggerElement = $(event.relatedTarget);
  current_selected_image = triggerElement;
  var id = $(current_selected_image).data("id");
  var existing_url = $(this).find("#product-faqs-table").data("url");

  if (existing_url.indexOf("?") > -1) {
    var temp = $(existing_url).text().split("?");
    var new_url = temp[0] + "?product_id=" + id;
  } else {
    var new_url = existing_url + "?product_id=" + id;
  }
  $("#product-faqs-table").bootstrapTable("refreshOptions", {
    url: new_url,
  });
});

$(document).on("show.bs.modal", "#customer-address-modal", function (event) {
  var triggerElement = $(event.relatedTarget);
  current_selected_image = triggerElement;
  var id = $(current_selected_image).data("id");
  var existing_url = $(this).find("#customer-address-table").data("url");

  if (existing_url.indexOf("?") > -1) {
    var temp = $(existing_url).text().split("?");
    var new_url = temp[0] + "?user_id=" + id;
  } else {
    var new_url = existing_url + "?user_id=" + id;
  }
  $("#customer-address-table").bootstrapTable("refreshOptions", {
    url: new_url,
  });
});
$(document).on("show.bs.modal", "#product-rating-modal", function (event) {
  var triggerElement = $(event.relatedTarget);
  current_selected_image = triggerElement;
  var id = $(current_selected_image).data("id");

  var existing_url = $(this).find("#product-rating-table").data("url");
  if (existing_url.indexOf("?") > -1) {
    var temp = $(existing_url).text().split("?");
    var new_url = temp[0] + "?product_id=" + id;
  } else {
    var new_url = existing_url + "?product_id=" + id;
  }
  $("#product-rating-table").bootstrapTable("refreshOptions", {
    url: new_url,
  });
});

$(document).on("click", ".remove-image", function (e) {
  e.preventDefault();
  $(this).closest(".image").remove();
});

$(document).on("change", "#media-type", function () {
  $("table").bootstrapTable("refresh");
});

Dropzone.autoDiscover = false;

if (document.getElementById("dropzone")) {
  var myDropzone = new Dropzone("#dropzone", {
    url: base_url + "admin/media/upload",
    paramName: "documents",
    autoProcessQueue: false,
    parallelUploads: 12,
    maxFiles: 12,
    autoDiscover: false,
    addRemoveLinks: true,
    timeout: 180000,
    dictRemoveFile: "x",
    dictMaxFilesExceeded: "Only 12 files can be uploaded at a time ",
    dictResponseError: "Error",
    uploadMultiple: true,
    dictDefaultMessage:
      '<p><input type="submit" value="Select Files" class="btn btn-success" /><br> or <br> Drag & Drop Media Files Here</p>',
  });

  myDropzone.on("addedfile", function (file) {
    var i = 0;
    if (this.files.length) {
      var _i, _len;
      for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
        if (
          this.files[_i].name === file.name &&
          this.files[_i].size === file.size &&
          this.files[_i].lastModifiedDate.toString() ===
          file.lastModifiedDate.toString()
        ) {
          this.removeFile(file);
          i++;
        }
      }
    }
  });

  myDropzone.on("error", function (file, errorMessage, xhr) {
    if (errorMessage == "Upload canceled.") {
      return;
    }
    if (typeof errorMessage === "string") {
      iziToast.error({
        title: "Error",
        message: errorMessage,
      });
    } else if (typeof errorMessage === "object" && errorMessage.message) {
      iziToast.error({
        title: "Error",
        message: errorMessage.message,
      });
    } else {
      iziToast.error({
        title: "Error",
        message: "Invalid file format or upload error",
      });
    }
    this.removeFile(file);
  });

  myDropzone.on("sending", function (file, xhr, formData) {
    formData.append(csrfName, csrfHash);
  });

  myDropzone.on("successmultiple", function (files, response) {
    if (typeof response === "string") {
      response = JSON.parse(response);
    }
    csrfName = response.csrfName;
    csrfHash = response.csrfHash;
    if (response["error"] == false) {
      Dropzone.forElement("#dropzone").removeAllFiles(true);
      $("#media-table").bootstrapTable("refresh");
      iziToast.success({
        message: response["message"],
      });
    } else {
      iziToast.error({
        title: "Error",
        message: response["message"],
      });
    }
    files.forEach((file) => {
      $(file.previewElement).find(".dz-error-message").text(response.message);
    });
  });
}
if (document.getElementById("system-update-dropzone")) {
  var systemDropzone = new Dropzone("#system-update-dropzone", {
    url: base_url + "admin/updater/upload_update_file",
    paramName: "update_file",
    autoProcessQueue: false,
    parallelUploads: 1,
    maxFiles: 1,
    timeout: 360000,
    autoDiscover: false,
    addRemoveLinks: true,
    dictRemoveFile: "x",
    dictMaxFilesExceeded: "Only 1 file can be uploaded at a time ",
    dictResponseError: "Error",
    uploadMultiple: true,
    dictDefaultMessage:
      '<p><input type="button" value="Select Files" class="btn btn-success" /><br> or <br> Drag & Drop System Update / Installable / Plugin\'s .zip file Here</p>',
  });

  systemDropzone.on("addedfile", function (file) {
    var i = 0;
    if (this.files.length) {
      var _i, _len;
      for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
        if (
          this.files[_i].name === file.name &&
          this.files[_i].size === file.size &&
          this.files[_i].lastModifiedDate.toString() ===
          file.lastModifiedDate.toString()
        ) {
          this.removeFile(file);
          i++;
        }
      }
    }
  });

  systemDropzone.on("error", function (file, response) { });

  systemDropzone.on("sending", function (file, xhr, formData) {
    formData.append(csrfName, csrfHash);
  });

  systemDropzone.on("successmultiple", function (files, response) {
    if (typeof response === "string") {
      response = JSON.parse(response);
    }
    csrfName = response.csrfName;
    csrfHash = response.csrfHash;
    if (response["error"] == false) {
      iziToast.success({
        message: response["message"],
      });
    } else {
      iziToast.error({
        title: "Error",
        message: response["message"],
      });
    }
    files.forEach((file) => {
      $(file.previewElement).find(".dz-error-message").text(response.message);
    });
  });
  $("#system_update_btn").on("click", function (e) {
    e.preventDefault();
    if (systemDropzone.files.length === 0) {
      iziToast.error({
        message: "Please select a file to upload",
      });
      return false;
    }
    systemDropzone.processQueue();
  });
}

$("#upload-files-btn").on("click", function (e) {
  e.preventDefault();
  if (myDropzone.files.length === 0) {
    iziToast.error({
      message: "Please select files to upload",
    });
    return false;
  }
  myDropzone.processQueue();
});

$(document).on("click", ".copy-to-clipboard", function () {
  var $element = $(this).closest("tr").find(".path");
  copyToClipboard($element);
  iziToast.success({
    message: "Image path copied to clipboard",
  });
});
$(document).on("click", ".copy-relative-path", function () {
  var $element = $(this).closest("tr").find(".relative-path");
  copyToClipboard($element);
  iziToast.success({
    message: "Image path copied to clipboard",
  });
});

$(document).on("click", 'button[type="reset"]', function () {
  $(".image-upload-div").remove();
  $(".image-upload-section").find(".image").addClass("d-none");
});

//20.Client Api Key Module
$(document).on("click", "#delete-client", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/client_api_keys/delete_client",
          data: {
            id: id,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
            } else {
              Swal.fire("Opps", response.message, "warning");
            }
            $("table").bootstrapTable("refresh");
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

//21.System Users
$(document).on("click", "#delete-system-users", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/system_users/delete_system_user",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success").then(() => {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
              });
            } else {
              Swal.fire("Opps", response.message, "warning");
            }
            $("table").bootstrapTable("refresh");
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on("change", ".system-user-role", function () {
  var role = $(this).val();
  if (role > 0) {
    $(".permission-table").removeClass("d-none");
    $(".permission-table-controls").removeClass("d-none");
  } else {
    $(".permission-table").addClass("d-none");
    $(".permission-table-controls").addClass("d-none");
  }
});

$(document).on("click", "#selectAllPermissions", function () {
  $(".system-users-switch").each(function () {
    if (!$(this).is(":checked")) {
      $(this).bootstrapSwitch("state", true, true);
    }
  });
});

$(document).on("click", "#deselectAllPermissions", function () {
  $(".system-users-switch").each(function () {
    if ($(this).is(":checked")) {
      $(this).bootstrapSwitch("state", false, true);
    }
  });
});

$(document).on("click", ".toggle-column", function () {
  var column = $(this).data("column");
  var columnSwitches = $('.system-users-switch[data-column="' + column + '"]');
  var allChecked = true;
  columnSwitches.each(function () {
    if (!$(this).is(":checked")) {
      allChecked = false;
      return false;
    }
  });

  var newState = !allChecked;
  columnSwitches.each(function () {
    $(this).bootstrapSwitch("state", newState, true);
  });
});

$(document).on("click", ".remove_individual_variants", function () {
  var variant_id = $(this)
    .closest(".variant_col")
    .find('input[type="hidden"]')
    .val();
  var all_variant_ids = $(this)
    .closest(".row")
    .find('input[name="variants_ids[]"]')
    .val()
    .split(",");
  all_variant_ids.splice(all_variant_ids.indexOf(variant_id), 1);
  if ($.isEmptyObject(all_variant_ids)) {
    $(this).closest(".row").remove();
  } else {
    $(this)
      .closest(".row")
      .find('input[name="variants_ids[]"]')
      .val(all_variant_ids.toString());
    $(this).closest(".variant_col").remove();
  }
});

$(document).on("change", "#system_timezone", function () {
  var gmt = $(this).find(":selected").data("gmt");
  $("#system_timezone_gmt").val(gmt);
});
$(".city_list").select2({
  ajax: {
    url: base_url + "admin/area/get_cities",
    type: "GET",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
      };
    },
    processResults: function (response) {
      return {
        results: response,
      };
    },
    cache: true,
  },

  minimumInputLength: 1,
  theme: "bootstrap4",
  placeholder: "Search for cities",
});
$("#city").on("change", function (e) {
  e.preventDefault();
  $.ajax({
    type: "POST",
    data: {
      city_id: $(this).val(),
      [csrfName]: csrfHash,
    },
    url: base_url + "my-account/get-areas",
    dataType: "json",
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result.error == false) {
        var html = "";
        $.each(result.data, function (i, e) {
          html += "<option value=" + e.id + ">" + e.name + "</option>";
        });
        $("#area").html(html);
      } else {
        Toast.fire({
          icon: "error",
          title: result.message,
        });
        $("#area").html("");
      }
    },
  });
});
$("#add-address-form").on("submit", function (e) {
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
      $("#save-address-submit-btn")
        .val("Please Wait...")
        .attr("disabled", true);
    },
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result.error == false) {
        $("#save-address-result")
          .html("<div class='alert alert-success'>" + result.message + "</div>")
          .delay(1500)
          .fadeOut();
        $("#add-address-form")[0].reset();
        $("#address_list_table").bootstrapTable("refresh");
      } else {
        $("#save-address-result")
          .html("<div class='alert alert-danger'>" + result.message + "</div>")
          .delay(1500)
          .fadeOut();
      }
      $("#save-address-submit-btn").val("Save").attr("disabled", false);
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
$("#edit_city").on("change", function (e, data) {
  e.preventDefault();
  $.ajax({
    type: "POST",
    data: {
      city_id: $(this).val(),
      [csrfName]: csrfHash,
    },
    url: base_url + "my-account/get-areas",
    dataType: "json",
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result.error == false) {
        var html = "";
        $.each(result.data, function (i, e) {
          html += "<option value=" + e.id + ">" + e.name + "</option>";
        });
        $("#edit_area").html(html);
      } else {
        Toast.fire({
          icon: "error",
          title: result.message,
        });
        $("#edit_area").html("");
      }
    },
  });
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
      $("#edit-address-submit-btn")
        .val("Please Wait...")
        .attr("disabled", true);
    },
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result.error == false) {
        $("#edit-address-result")
          .html("<div class='alert alert-success'>" + result.message + "</div>")
          .delay(1500)
          .fadeOut();
        $("#edit-address-form")[0].reset();
        $("#address_list_table").bootstrapTable("refresh");
        setTimeout(function () {
          $("#address-modal").modal("hide");
        }, 2000);
      } else {
        $("#edit-address-result")
          .html("<div class='alert alert-danger'>" + result.message + "</div>")
          .delay(1500)
          .fadeOut();
      }
      $("#edit-address-submit-btn").val("Save").attr("disabled", false);
    },
  });
});

// $(document).ready(function() {
//     $('.select2').select2({
//         theme: 'bootstrap4',          // change to 'bootstrap-5' if using Bootstrap 5
//         placeholder: "Select category",
//         allowClear: true,
//         width: '100%'
//     });
// });

$("#add-new-language-form").on("submit", function (e) {
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
      $("#submit_btn").val("Please Wait...").attr("disabled", true);
    },

    success: function (result) {
      // Update CSRF
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;

      if (result.error === false) {
        iziToast.success({
          title: "Success",
          message: result.message || "Language added successfully",
          position: "topRight",
          timeout: 3000,
        });

        $("#add-new-language-form")[0].reset();

        setTimeout(function () {
          $("#language-modal").modal("hide");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        }, 2000);
      } else {
        iziToast.error({
          title: "Error",
          message: result.message || "Something went wrong",
          position: "topRight",
          timeout: 3000,
        });
      }

      $("#submit_btn").val("Save").attr("disabled", false);
    },

    error: function () {
      iziToast.error({
        title: "Error",
        message: "Server error. Please try again later.",
        position: "topRight",
        timeout: 3000,
      });

      $("#submit_btn").val("Save").attr("disabled", false);
    },
  });
});

$("#selected_language").on("change", function () {
  var id = $(this).val();
  window.location.href = base_url + "admin/language?id=" + id;
});
$("#update-language-form").on("submit", function (e) {
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
      $("#update_btn").val("Please Wait...").attr("disabled", true);
    },
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result.error == false) {
        $("#update-result")
          .show()
          .removeClass("msg_error")
          .addClass("msg_success")
          .html(result.message)
          .delay(100)
          .fadeOut(function () {
            // Reload the page after the message fades out
            window.location.reload();
          });
      } else {
        $("#update-result")
          .show()
          .removeClass("msg_success")
          .addClass("msg_error")
          .html(result.message)
          .delay(1000)
          .fadeOut();
      }
      $("#update_btn").val("Save").attr("disabled", false);
    },
  });
});

function product_rating_query_params(p) {
  return {
    product_id: $("#product_id").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}
$(document).on("click", ".sync-zipcode-with-area", function () {
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Sync table !",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/area/table_sync",
          type: "GET",
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Done!", response.message, "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});
$(document).on("change", "#area_wise_delivery_charge", function () {
  var checked = $(this).is(":checked");
  console.log(checked);

  if (!checked) {
    if ($(".delivery_charge").hasClass("d-none")) {
      $(".delivery_charge").removeClass("d-none");
    }
    if ($(".min_amount").hasClass("d-none")) {
      $(".min_amount").removeClass("d-none");
    }
    if ($(".area_wise_delivery_charge").hasClass("col-md-6")) {
      $(".area_wise_delivery_charge").removeClass("col-md-6");
      $(".area_wise_delivery_charge").addClass("col-md-4");
    }
  } else {
    if (!$(".delivery_charge").hasClass("d-none")) {
      $(".delivery_charge").addClass("d-none");
    }
    if (!$(".min_amount").hasClass("d-none")) {
      $(".min_amount").addClass("d-none");
    }
    if ($(".area_wise_delivery_charge").hasClass("col-md-4")) {
      $(".area_wise_delivery_charge").removeClass("col-md-4");
      $(".area_wise_delivery_charge").addClass("col-md-6");
    }
  }
});

$("#bulk_upload_form").on("submit", function (e) {
  e.preventDefault();
  var type = $("#type").val();
  if (type != "") {
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
        $("#submit_btn").html("Please Wait...").attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          $("#upload_result")
            .show()
            .removeClass("msg_error")
            .addClass("msg_success")
            .html(result.message)
            .delay(3000)
            .fadeOut();
        } else {
          $("#upload_result")
            .show()
            .removeClass("msg_success")
            .addClass("msg_error")
            .html(result.message)
            .delay(3000)
            .fadeOut();
        }
        $("#submit_btn").html("Submit").attr("disabled", false);
      },
    });
  } else {
    iziToast.error({
      message: "Please select type",
    });
  }
});
$("#location_bulk_upload_form").on("submit", function (e) {
  e.preventDefault();
  var type = $("#type").val();
  var location_type = $("#location_type").val();
  if (
    type != "" &&
    location_type != "" &&
    type != "undefined" &&
    location_type != "undefined"
  ) {
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
        $("#submit_btn").html("Please Wait...").attr("disabled", true);
      },
      success: function (result) {
        csrfName = result.csrfName;
        csrfHash = result.csrfHash;
        if (result.error == false) {
          $("#upload_result")
            .show()
            .removeClass("msg_error")
            .addClass("msg_success")
            .html(result.message)
            .delay(3000)
            .fadeOut();
        } else {
          $("#upload_result")
            .show()
            .removeClass("msg_success")
            .addClass("msg_error")
            .html(result.message)
            .delay(3000)
            .fadeOut();
        }
        $("#submit_btn").html("Submit").attr("disabled", false);
      },
    });
  } else {
    iziToast.error({
      message: "Please select Type and Location Type",
    });
  }
});

// swatche js

// if ($('.swatche_type').length) {
$("#swatche_color").hide();
$("#swatche_image").hide();
$(document.body).on("change", ".swatche_type", function (e) {
  e.preventDefault();
  var swatche_type = $(this).val();
  if (swatche_type == "1") {
    $("#swatche_image").hide();
    $("#swatche_color").show();
    $("#swatche_image").val("");
  } else if (swatche_type == "2") {
    $("#swatche_color").hide();
    $("#swatche_image").show();
    $("#swatche_color").val("");
  } else {
    $("#swatche_color").hide();
    $("#swatche_image").hide();
    $("#swatche_color").val("");
    $("#swatche_image").val("");
  }
});
// }
if ($("#google_pay_currency_code").length) {
  $("#google_pay_currency_code").on("change", function (e) {
    e.preventDefault();
    var country_code = $(this).find(":selected").data("countrycode");
    $("#google_pay_country_code").val(country_code);
  });
}

var ticket_id = "";
var scrolled = 0;
$(document).on("click", ".view_ticket", function (e, row) {
  e.preventDefault();
  scrolled = 0;
  $(".ticket_msg").data("max-loaded", false);
  ticket_id = $(this).data("id");
  var username = $(this).data("username");
  var date_created = $(this).data("date_created");
  var subject = $(this).data("subject");
  var status = $(this).data("status");
  var ticket_type = $(this).data("ticket_type");
  $('input[name="ticket_id"]').val(ticket_id);
  $("#user_name").html(username);
  $("#date_created").html(date_created);
  $("#subject").html(subject);
  $(".change_ticket_status").data("ticket_id", ticket_id);
  if (status == 1) {
    $("#status").html('<label class="badge bg-secondary ml-2">PENDING</label>');
  } else if (status == 2) {
    $("#status").html('<label class="badge bg-info ml-2">OPENED</label>');
  } else if (status == 3) {
    $("#status").html('<label class="badge bg-success ml-2">RESOLVED</label>');
  } else if (status == 4) {
    $("#status").html('<label class="badge bg-danger ml-2">CLOSED</label>');
  } else if (status == 5) {
    $("#status").html('<label class="badge bg-warning ml-2">REOPENED</label>');
  }
  $("#ticket_type").html(ticket_type);
  $(".ticket_msg").html("");
  $(".ticket_msg").data("limit", 5);
  $(".ticket_msg").data("offset", 0);
  load_messages($(".ticket_msg"), ticket_id);
});

$(document).ready(function () {
  if ($("#element").length) {
    $("#element").scrollTop($("#element")[0].scrollHeight);
    $("#element").scroll(function () {
      // var ticket_id = $(this).data('ticket_id');
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

$("#ticket_send_msg_form").on("submit", function (e) {
  e.preventDefault();
  var formdata = new FormData(this);
  formdata.append(csrfName, csrfHash);

  $.ajax({
    type: "POST",
    url: $(this).attr("action"),
    data: formdata,
    beforeSend: function () {
      $("#submit_btn").html("Sending..").attr("disabled", true);
    },
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (result) {
      csrfHash = result.csrfHash;
      $("#submit_btn").html("Send").attr("disabled", false);
      if (result.error == false) {
        if (result.data.id > 0) {
          var message = result.data;
          var is_left = message.user_type == "user" ? "left" : "right";
          var message_html = "";
          var atch_html = "";
          if (message.attachments.length > 0) {
            message.attachments.forEach((atch) => {
              if (atch.media && atch.media.trim() !== "") {
                atch_html = "";
                atch_html +=
                  "<div class='container-fluid image-upload-section'>" +
                  "<a class='btn btn-danger btn-xs mr-1 mb-1' href='" +
                  atch.media +
                  "'  target='_blank' alt='Attachment Not Found'>Attachment</a>" +
                  "<div class='col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none'></div>" +
                  "</div>";
              }
            });
          }

          message_html +=
            "<div class='direct-chat-msg " +
            is_left +
            "'>" +
            "<div class='direct-chat-infos clearfix'>" +
            "<span class='direct-chat-name float-" +
            is_left +
            "' id='name'>" +
            message.name +
            "</span>" +
            "<span class='direct-chat-timestamp float-" +
            is_left +
            "' id='last_updated'>" +
            message.last_updated +
            "</span>" +
            "</div>" +
            "<div class='direct-chat-text' id='message'>" +
            message.message +
            "</br>" +
            atch_html +
            "</div>" +
            "</div>";

          $(".ticket_msg").append(message_html);
          // $('.image-upload-section').remove()
          // Reset image upload sections but don't remove them
          $(".image-upload-section .grow").hide();
          $(".image-upload-section .image").attr("src", "");
          $('.image-upload-section input[type="hidden"]').val("");

          // Re-initialize image upload functionality if needed
          $(".upload_media").show();

          $("#message_input").val("");
          // $('input[name="attachments[]"]').val('')
          $('input[name="attachments[]"]').replaceWith(
            $('input[name="attachments[]"]').clone(true)
          );
          $("#element").scrollTop($("#element")[0].scrollHeight);
        }
      } else {
        $("#element").data("max-loaded", true);
        iziToast.error({
          message:
            '<span class="text-capitalize">' + result.message + "</span> ",
        });
        return false;
      }
      iziToast.success({
        message: '<span class="text-capitalize">' + result.message + "</span> ",
      });
    },
  });
});

$(".add_product_form").on("click", function (e) {
  e.preventDefault();

  var name = $("#db_name").val();
  var mobile = $("#db_mobile").val();
  var email = $("#email").val();
  var password = $("#password").val();
  var confirm_password = $("#confirm_password").val();
  var address = $("#address").val();
  var bonus_type = $("#bonus_type").val();
  var bonus_amount = $("#bonus_amount").val();
  var bonus_percentage = $("#bonus_percentage").val();
  var driving_license = $("#driving_license").val();
  var status = $('input[name="status"]:checked').val(); //  Correct way to get radio button value

  var data = {
    name: name,
    status: status,
    mobile: mobile,
    email: email,
    password: password,
    confirm_password: confirm_password,
    address: address,
    bonus_type: bonus_type,
    bonus_amount: bonus_amount,
    bonus_percentage: bonus_percentage,
    driving_license: driving_license,
    [csrfName]: csrfHash,
  };

  // var jsonString = JSON.stringify(data);

  $.ajax({
    type: "POST",
    url: base_url + "admin/delivery_boys/add_delivery_boy",
    data: data,
    dataType: "json",
    // contentType: 'application/json', // Set content type to JSON
    beforeSend: function () {
      $("#submit_btn").html("Sending..").attr("disabled", true);
    },
    success: function (result) {
      csrfHash = result.csrfHash;

      if (result.error == false) {
        iziToast.success({
          message: result.message,
        });
        setTimeout(function () {
          location.reload();
        }, 600);
      } else {
        iziToast.error({
          message: result.message,
        });
      }
    },
  });
});

// if ($('#delete-ticket').length) {
$(document).on("click", "#delete-ticket", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/tickets/delete_ticket",
          type: "GET",
          data: {
            id: id,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});
// }

$(document).on("change", ".change_ticket_status", function () {
  var status = $(this).val();
  if (status != "") {
    if (
      confirm(
        "Are you sure you want to mark the ticket as " +
        $(".change_ticket_status option:selected").text() +
        "? "
      )
    ) {
      var id = $(this).data("ticket_id");
      var dataString = {
        ticket_id: id,
        status: status,
        [csrfName]: csrfHash,
      };
      $.ajax({
        type: "post",
        url: base_url + "admin/tickets/edit-ticket-status",
        data: dataString,
        dataType: "json",
        success: function (result) {
          csrfHash = result.csrfHash;
          if (result.error == false) {
            $("#ticket_table").bootstrapTable("refresh");
            if (status == 1) {
              $("#status").html(
                '<label class="badge bg-secondary ml-2">PENDING</label>'
              );
            } else if (status == 2) {
              $("#status").html(
                '<label class="badge bg-info ml-2">OPENED</label>'
              );
            } else if (status == 3) {
              $("#status").html(
                '<label class="badge bg-success ml-2">RESOLVED</label>'
              );
            } else if (status == 4) {
              $("#status").html(
                '<label class="badge bg-danger ml-2">CLOSED</label>'
              );
            } else if (status == 5) {
              $("#status").html(
                '<label class="badge bg-warning ml-2">REOPENED</label>'
              );
            }

            iziToast.success({
              message:
                '<span class="text-capitalize">' + result.message + "</span> ",
            });
          } else {
            iziToast.error({
              message: "<span>" + result.message + "</span> ",
            });
          }
        },
      });
    }
  }
});

$(document).on("click", ".delete-ticket-type", function () {
  var cat_id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/tickets/delete_ticket_type",
          data: {
            id: cat_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            } else {
              Swal.fire("Oops...", response.message, "warning");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
          });
      });
    },
    allowOutsideClick: false,
  });
});

function load_messages(element, ticket_id) {
  var limit = element.data("limit");
  var offset = element.data("offset");

  element.data("offset", limit + offset);
  var max_loaded = element.data("max-loaded");
  if (max_loaded == false) {
    var loader =
      '<div class="loader text-center"><img src="' +
      base_url +
      'assets/pre-loader.gif" alt="Loading. please wait.. ." title="Loading. please wait.. ."></div>';
    $.ajax({
      type: "get",
      data: "ticket_id=" + ticket_id + "&limit=" + limit + "&offset=" + offset,
      url: base_url + "admin/tickets/get_ticket_messages",
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
              var msg_i = 1;
              is_left = messages.user_type == "user" ? "left" : "right";
              is_right = messages.user_type == "user" ? "right" : "left";
              if (messages.attachments.length > 0) {
                messages.attachments.forEach((atch) => {
                  if (atch.media && atch.media.trim() !== "") {
                    atch_html = "";
                    atch_html +=
                      "<div class='container-fluid image-upload-section'>" +
                      "<a class='btn btn-danger btn-xs mr-1 mb-1' href='" +
                      atch.media +
                      "'  target='_blank' alt='Attachment Not Found'>Attachment " +
                      msg_i +
                      "</a>" +
                      "<div class='col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none'></div>" +
                      "</div>";
                    msg_i++;
                  }
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
            '<div class="text-center"> <p>You have reached the top most message!</p></div>'
          );
        }
        $("#element").scrollTop(20); // Scroll alittle way down, to allow user to scroll more
        $(element).animate({
          scrollTop: $(element).offset().top,
        });
        return false;
      },
    });
  }
}

$(document).on("click", ".edit_transaction", function (e, row) {
  e.preventDefault();
  var id = $(this).data("id");
  var txn_id = $(this).data("txn_id");
  var status = $(this).data("status");
  var message = $(this).data("message");

  $("#id").val(id);
  $("#txn_id").val(txn_id);
  $("#t_status").val(status);
  $("#message").val(message);
});

$("#edit_transaction_form").on("submit", function (e) {
  e.preventDefault();
  var formdata = new FormData(this);
  formdata.append(csrfName, csrfHash);

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
      $("#submit_btn").html("Update Transaction").attr("disabled", false);
      if (result.error == false) {
        $("table").bootstrapTable("refresh");
        iziToast.success({
          message:
            '<span class="text-capitalize">' + result.message + "</span> ",
        });
        setTimeout(function () {
          location.reload();
        }, 1000);
      } else {
        iziToast.error({
          message: "<span>" + result.message + "</span> ",
        });
      }
    },
  });
});

$(document).on("click", ".delete-receipt", function () {
  var cat_id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/orders/delete_receipt",
          data: {
            id: cat_id,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
              setTimeout(function () {
                window.location.reload();
              }, 2000);
            } else {
              Swal.fire("Oops...", response.message, "warning");
              $("table").bootstrapTable("refresh");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
            csrfName = response["csrfName"];
            csrfHash = response["csrfHash"];
          });
      });
    },
    allowOutsideClick: false,
  });
});

//13.zipcode-Module
$(document).on("click", "#delete-zipcode", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Area/delete_zipcode",
          type: "GET",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

var search_products = $(".search_zipcode").select2({
  ajax: {
    url: base_url + "admin/area/get_zipcodes",
    dataType: "json",
    delay: 250,
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
  // minimumInputLength: 1,
  templateResult: formatRepo1,
  templateSelection: formatRepoSelection1,
  theme: "bootstrap4",
  placeholder: "Search for zipcodes",
  allowClear: Boolean($(this).data("allow-clear")),
});

search_products.on("select2:select", function (e) {
  var data = e.params.data;
  if (data.link != undefined && data.link != null) {
    window.location.href = data.link;
  }
});
$(document).ready(function () {
  function updateDeliverabilityUI() {
    const isPincodeEnabled = $("#pincode_wise_deliverability").is(":checked");
    const isCityEnabled = $("#city_wise_deliverability").is(":checked");

    // ❌ Both cannot be ON
    if (isPincodeEnabled && isCityEnabled) {
      $("#city_wise_deliverability").prop("checked", false);
    }

    // ✅ Show city fields ONLY when:
    // City = ON AND Pincode = OFF
    if (isCityEnabled && !isPincodeEnabled) {
      $(".city-delivery-settings").removeClass("d-none").addClass("d-block");
    } else {
      $(".city-delivery-settings").removeClass("d-block").addClass("d-none");
    }
  }

  // 🔹 Pincode toggle
  $(document).on("change", "#pincode_wise_deliverability", function () {
    if ($(this).is(":checked")) {
      $("#city_wise_deliverability").prop("checked", false);
    }
    updateDeliverabilityUI();
  });

  // 🔹 City toggle
  $(document).on("change", "#city_wise_deliverability", function () {
    if ($(this).is(":checked")) {
      $("#pincode_wise_deliverability").prop("checked", false);
    }
    updateDeliverabilityUI();
  });

  // 🔹 Initial page load (MOST IMPORTANT)
  updateDeliverabilityUI();
});

$(document).on("change", "#deliverable_type", function () {
  var type = $(this).val();
  if (type == "1" || type == "0") {
    $("#deliverable_zipcodes").prop("disabled", "disabled");
  } else {
    $("#deliverable_zipcodes").prop("disabled", false);
  }
});

$(document).on("change", "#deliverable_city_type", function () {
  var type = $(this).val();
  if (type == "1" || type == "0") {
    $("#deliverable_cities").prop("disabled", "disabled");
  } else {
    $("#deliverable_cities").prop("disabled", false);
  }
});

$(document).on("change", "#pincode_wise_deliverability", function () {
  var isPincodeEnabled = $(this).is(":checked");
  if (isPincodeEnabled) {
    $("#city_wise_deliverability").prop("checked", false).trigger("change");
    $(".city-delivery-settings").addClass("d-none");
  } else {
    var isCityEnabled = $("#city_wise_deliverability").is(":checked");
    if (isCityEnabled) {
      $(".city-delivery-settings").removeClass("d-none");
    }
  }
});

$(document).on("change", "#city_wise_deliverability", function () {
  var isCityEnabled = $(this).is(":checked");
  var isPincodeEnabled = $("#pincode_wise_deliverability").is(":checked");

  if (isCityEnabled && isPincodeEnabled) {
    $("#pincode_wise_deliverability").prop("checked", false).trigger("change");
  }

  if (isCityEnabled && !isPincodeEnabled) {
    $(".city-delivery-settings").removeClass("d-none");
  } else {
    $(".city-delivery-settings").addClass("d-none");
  }
});

$(document).ready(function () {
  var isPincodeEnabled = $("#pincode_wise_deliverability").is(":checked");
  var isCityEnabled = $("#city_wise_deliverability").is(":checked");

  if (isPincodeEnabled) {
    $(".city-delivery-settings").addClass("d-none");
  } else if (isCityEnabled) {
    $(".city-delivery-settings").removeClass("d-none");
  } else {
    $(".city-delivery-settings").addClass("d-none");
  }
});

$("#update_receipt_status").on("change", function (e) {
  e.preventDefault();
  var order_id = $(this).data("id");
  var user_id = $(this).data("user_id");
  var status = $(this).val();
  if (status != "") {
    Swal.fire({
      title: "Are You Sure!",
      text: "You won't be able to revert this!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Change it!",
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return new Promise((resolve, reject) => {
          $.ajax({
            type: "POST",
            url: base_url + "admin/orders/update_receipt_status",
            data: {
              order_id: order_id,
              status: status,
              user_id: user_id,
              [csrfName]: csrfHash,
            },
            dataType: "json",
          })
            .done(function (response, textStatus) {
              if (response.error == false) {
                Swal.fire("Status Changed!", response.message, "success");
                $("table").bootstrapTable("refresh");
                csrfName = response["csrfName"];
                csrfHash = response["csrfHash"];
              } else {
                Swal.fire("Oops...", response.message, "warning");
                $("table").bootstrapTable("refresh");
                csrfName = response["csrfName"];
                csrfHash = response["csrfHash"];
              }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
              Swal.fire("Oops...", "Something went wrong with ajax !", "error");
              csrfName = response["csrfName"];
              csrfHash = response["csrfHash"];
            });
        });
      },
      allowOutsideClick: false,
    });
  }
});

$(document).on("click", ".edit_order_tracking", function (e, rows) {
  e.preventDefault();
  var order_id = $(this).data("order_id");
  var courier_agency = $(this).data("courier_agency");
  var tracking_id = $(this).data("tracking_id");
  var url = $(this).data("url");
  $('input[name="order_id"]').val(order_id);
  $("#order_id").val(order_id);
  $("#courier_agency").val(courier_agency);
  $("#tracking_id").val(tracking_id);
  $("#url").val(url);
});

$("#order_tracking_form").on("submit", function (e) {
  e.preventDefault();
  var formdata = new FormData(this);
  formdata.append(csrfName, csrfHash);

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
      $("#submit_btn").html("save").attr("disabled", false);
      if (result.error == false) {
        $("table").bootstrapTable("refresh");
        iziToast.success({
          message:
            '<span class="text-capitalize">' + result.message + "</span> ",
        });
      } else {
        iziToast.error({
          message: "<span>" + result.message + "</span> ",
        });
      }
    },
  });
});

var d_boy_cash = 0;
$("#delivery_boys_details").on("check.bs.table", function (e, row) {
  d_boy_cash = row.cash_received;
  $("#details").val(
    "Id: " +
    row.id +
    " | Name:" +
    row.name +
    " | Mobile: " +
    row.mobile +
    " | Cash: " +
    row.cash_received
  );
  $("#delivery_boy_id").val(row.id);
});

function validate_amount() {
  var cash = d_boy_cash;
  var amount = $("#amount").val();
  var details_val = $("#details").val();
  if (details_val == "") {
    iziToast.error({
      message: "<span>you have to select delivery boy to collect cash.</span> ",
    });
    $("#amount").val("");
  } else {
    if (parseInt(cash) > 0) {
      if (parseInt(amount) > parseInt(cash)) {
        iziToast.error({
          message: "<span>You Can not enter amount greater than cash</span> ",
        });
        $("#amount").val("");
      }
      if (parseInt(amount) <= 0) {
        iziToast.error({
          message: "<span>Amount must be greater than zero</span> ",
        });
        $("#amount").val("");
      }
    } else {
      iziToast.error({
        message: "<span>Cash must be greater than zero</span> ",
      });
      $("#amount").val("");
    }
  }
}

function idFormatter() {
  return "Total";
}

function priceFormatter(data) {
  var field = this.field;
  var store_currency = $('input[name="store_currency"]').val();

  return (
    '<span class="price-format">' +
    store_currency +
    data
      .map(function (row) {
        return +row[field];
      })
      .reduce(function (sum, i) {
        return sum + i;
      }, 0)
  );
}

// Feature Section Hide or Show Category Field

$(document).on("change", ".product_type", function () {
  var product_type = $(".product_type").val();
  var exclude_product_type = ["custom_products"];
  if (exclude_product_type.includes(product_type)) {
    $(".select-categories").hide();
  } else {
    $(".select-categories").show();
  }
});

$(document).on("click", ".add_promo_code_discount", function () {
  Swal.fire({
    title: "Are You Sure !",
    text: "This will permanently settle the promo code discount!",
    type: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, settle Discounted!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/cron_job/settle_cashback_discount",
          type: "GET",
          data: {
            is_date: true,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Done!", response.message, "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

// $(".notification-toggle").dropdown();
// $(".notification-toggle").parent().on('shown.bs.dropdown', function() {
//     $(".dropdown-list-icons").niceScroll({
//         cursoropacitymin: .3,
//         cursoropacitymax: .8,
//         cursorwidth: 7
//     });
// });

$(document).on("click", ".mark-all-notifications-as-read", function () {
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, mark all notifications as read!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: base_url + "admin/Notification_settings/mark_all_as_read",
          type: "GET",
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Done!", response.message, "success");
              location.reload();
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(".country_list").select2({
  ajax: {
    url: base_url + "admin/product/get_countries_data",
    type: "GET",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
      };
    },
    processResults: function (response) {
      return {
        results: response,
      };
    },
    cache: true,
  },
  minimumInputLength: 1,
  theme: "bootstrap4",
  placeholder: "Search for countries",
});

// select 2 js select brands
$(".brand_list").select2({
  ajax: {
    url: base_url + "admin/product/get_brands_data",
    type: "GET",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
      };
    },
    processResults: function (response) {
      return {
        results: response,
      };
    },
    cache: true,
  },
  minimumInputLength: 1,
  theme: "bootstrap4",
  placeholder: "Search for brands",
});

$(".offer_brand_list").select2({
  ajax: {
    url: base_url + "admin/product/get_offer__brands_data",
    type: "GET",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
      };
    },
    processResults: function (response) {
      return {
        results: response,
      };
    },
    cache: true,
  },
  minimumInputLength: 1,
  theme: "bootstrap4",
  placeholder: "Search for brands",
});

$(document).on("click", ".edit_order_refund", function () {
  var order_item_id = $(this).data("order_item_id");
  var payment_method = $(this).data("payment_method");
  var txn_id = $(this).data("txn_id");
  var txn_amount = $(this).data("txn_amount");
  $("#transaction_id").val(txn_id);
  $("#txn_amount").val(txn_amount);
  $("#item_id").val(order_item_id);
  $("#refund_payment_method").val(payment_method);
});

$("#refund_form").on("click", function (e) {
  e.preventDefault();
  var txn_id = $("#transaction_id").val();
  var txn_amount = $("#txn_amount").val();
  var item_id = $("#item_id").val();
  var refund_payment_method = $("#refund_payment_method").val();
  $.ajax({
    type: "POST",
    data: {
      txn_id: txn_id,
      txn_amount: txn_amount,
      item_id: item_id,
      refund_payment_method: refund_payment_method,
      [csrfName]: csrfHash,
    },
    url: base_url + "admin/orders/refund_payment",
    dataType: "json",
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result["error"] == false) {
        iziToast.success({
          message: result["message"],
        });
      } else {
        iziToast.error({
          message: result["message"],
        });
      }
    },
  });
});

//bonus_type
$(document).on("change", ".bonus_type", function (e, data) {
  e.preventDefault();
  var sort_type_val = $(this).val();
  if (sort_type_val == "fixed_amount_per_order" && sort_type_val != " ") {
    $(".fixed_amount_per_order").removeClass("d-none");
  } else {
    $(".fixed_amount_per_order").addClass("d-none");
  }
  if (sort_type_val == "percentage_per_order" && sort_type_val != " ") {
    $(".percentage_per_order").removeClass("d-none");
  } else {
    $(".percentage_per_order").addClass("d-none");
  }
});

function customer_query_params(p) {
  return {
    order_status: $("#order_status").val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

// custom notification
// $(document).ready(function () {
//     $('.hashtag').click(function () {
//         var txt = $.trim($(this).text())
//         var box = $('#text-box')
//         box.val(box.val() + txt)
//     })
//     $('.hashtag_input').click(function () {
//         var txt = $.trim($(this).text())
//         var box = $('#update_title')
//         box.val(box.val() + txt)
//     })
// })

$(document).on("click", ".delete_custom_notification", function () {
  var id = $(this).data("id");
  var t = this;
  Swal.fire({
    title: "Are You Sure !",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          url:
            base_url + "admin/custom_notification/delete_custom_notification",
          type: "GET",
          data: {
            id: id,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Deleted!", result["message"], "success");
              $("table").bootstrapTable("refresh");
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on("change", ".type", function (e, data) {
  e.preventDefault();
  var sort_type_val = $(this).val();
  if (sort_type_val == "place_order" && sort_type_val != " ") {
    $(".place_order").removeClass("d-none");
  } else {
    $(".place_order").addClass("d-none");
  }
  if (sort_type_val == "settle_cashback_discount" && sort_type_val != " ") {
    $(".settle_cashback_discount").removeClass("d-none");
  } else {
    $(".settle_cashback_discount").addClass("d-none");
  }
  if (sort_type_val == "settle_seller_commission" && sort_type_val != " ") {
    $(".settle_seller_commission").removeClass("d-none");
  } else {
    $(".settle_seller_commission").addClass("d-none");
  }
  if (sort_type_val == "customer_order_received" && sort_type_val != " ") {
    $(".customer_order_received").removeClass("d-none");
  } else {
    $(".customer_order_received").addClass("d-none");
  }
  if (sort_type_val == "customer_order_processed" && sort_type_val != " ") {
    $(".customer_order_processed").removeClass("d-none");
  } else {
    $(".customer_order_processed").addClass("d-none");
  }
  if (sort_type_val == "customer_order_shipped" && sort_type_val != " ") {
    $(".customer_order_shipped").removeClass("d-none");
  } else {
    $(".customer_order_shipped").addClass("d-none");
  }
  if (sort_type_val == "customer_order_delivered" && sort_type_val != " ") {
    $(".customer_order_delivered").removeClass("d-none");
  } else {
    $(".customer_order_delivered").addClass("d-none");
  }
  if (sort_type_val == "customer_order_cancelled" && sort_type_val != " ") {
    $(".customer_order_cancelled").removeClass("d-none");
  } else {
    $(".customer_order_cancelled").addClass("d-none");
  }
  if (sort_type_val == "customer_order_returned" && sort_type_val != " ") {
    $(".customer_order_returned").removeClass("d-none");
  } else {
    $(".customer_order_returned").addClass("d-none");
  }
  if (
    sort_type_val == "customer_order_returned_request_approved" &&
    sort_type_val != " "
  ) {
    $(".customer_order_returned_request_approved").removeClass("d-none");
  } else {
    $(".customer_order_returned_request_approved").addClass("d-none");
  }
  if (
    sort_type_val == "customer_order_returned_request_decline" &&
    sort_type_val != " "
  ) {
    $(".customer_order_returned_request_decline").removeClass("d-none");
  } else {
    $(".customer_order_returned_request_decline").addClass("d-none");
  }
  if (sort_type_val == "delivery_boy_order_deliver" && sort_type_val != " ") {
    $(".delivery_boy_order_deliver").removeClass("d-none");
  } else {
    $(".delivery_boy_order_deliver").addClass("d-none");
  }
  if (sort_type_val == "wallet_transaction" && sort_type_val != " ") {
    $(".wallet_transaction").removeClass("d-none");
  } else {
    $(".wallet_transaction").addClass("d-none");
  }
  if (sort_type_val == "ticket_status" && sort_type_val != " ") {
    $(".ticket_status").removeClass("d-none");
  } else {
    $(".ticket_status").addClass("d-none");
  }
  if (sort_type_val == "ticket_message" && sort_type_val != " ") {
    $(".ticket_message").removeClass("d-none");
  } else {
    $(".ticket_message").addClass("d-none");
  }
  if (sort_type_val == "bank_transfer_receipt_status" && sort_type_val != " ") {
    $(".bank_transfer_receipt_status").removeClass("d-none");
  } else {
    $(".bank_transfer_receipt_status").addClass("d-none");
  }
  if (sort_type_val == "bank_transfer_proof" && sort_type_val != " ") {
    $(".bank_transfer_proof").removeClass("d-none");
  } else {
    $(".bank_transfer_proof").addClass("d-none");
  }
});

// send notification to specific user
$(document).on("change", "#send_to", function (e) {
  e.preventDefault();
  var type_val = $(this).val();
  if (type_val == "specific_user") {
    $(".notification-users").removeClass("d-none");
  } else {
    $(".notification-users").addClass("d-none");
  }
});

var noti_user_id = 0;
$("#select_user_id").on("change", function () {
  noti_user_id = $("#select_user_id").val();
});

$(".search_user").each(function () {
  $(this).select2({
    ajax: {
      url: base_url + "admin/customer/search_user",
      type: "GET",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          search: params.term, // search term
        };
      },
      processResults: function (response) {
        return {
          results: response,
        };
      },
      cache: true,
    },
    minimumInputLength: 1,
    theme: "bootstrap4",
    placeholder: "Search for countries",
  });
});

// google translate

// $(document).ready(function googleTranslateElementInit() {
//     new google.translate.TranslateElement({
//         pageLanguage: 'en'
//     },
//         'google_translate_element'
//     )
// })

// send admin notification
$(document).ready(function () {
  setInterval(function () {
    $.ajax({
      type: "GET",
      url: base_url + "admin/home/get_notification",
      dataType: "json",
      success: function (result) {
        $(".order_notification").text(result.count_notifications);
      },
    });
  }, 30000);
});
$(document).on("click", "#notification_count", function (e) {
  e.preventDefault();

  const $list = $("#list");

  // Loading state
  $list.addClass("show").html(`
            <div class="text-center py-4 text-muted">
                <div class="spinner-border spinner-border-sm me-2"></div>
                Please wait...
            </div>
        `);

  $.ajax({
    type: "GET",
    url: base_url + "admin/home/new_notification_list",
    dataType: "json",
    success: function (result) {
      let html = "";
      let url = base_url + "admin/flash_sale";

      if (result.notifications && result.notifications.length > 0) {
        $.each(result.notifications, function (i, a) {
          const unreadBadge =
            a.read_by == 0
              ? `<span class="badge bg-warning text-dark ms-2">New</span>`
              : "";

          if (a.type === "place_order") {
            url =
              base_url +
              "admin/orders/edit_orders?edit_id=" +
              a.type_id +
              "&noti_id=" +
              a.id;
          }

          html += `
                        <a href="${url}" 
                           class="dropdown-item py-3 text-wrap w-100">
                            
                            <div class="d-flex w-100 align-items-start">

                                <div class="flex-grow-1 overflow-hidden">

                                    <div class="fw-semibold text-dark text-break">
                                        ${a.title.replace(/\\'/g, "'")}
                                        ${unreadBadge}
                                    </div>

                                    <div class="small text-muted mt-1 text-break">
                                        ${a.message.replace(/\\'/g, "'")}
                                    </div>

                                    <div class="small text-muted mt-2">
                                        <i class="far fa-clock me-1"></i>${a.date_sent
            }
                                    </div>

                                </div>

                            </div>
                        </a>
                        <div class="dropdown-divider m-0"></div>
                    `;
        });

        html += `
                    <a href="javascript:void(0);"
                       class="dropdown-item text-center fw-semibold text-primary py-2 mark-all-notifications-as-read">
                        Mark all as read
                    </a>
                    <div class="dropdown-divider m-0"></div>
                `;
      } else {
        html += `
                    <div class="text-center py-4 text-muted">
                        <i class="far fa-bell-slash fs-3 mb-2"></i>
                        <div class="small">No new notifications</div>
                    </div>
                    <div class="dropdown-divider m-0"></div>
                `;
      }

      html += `
                <a href="${base_url}admin/Notification_settings/manage_ststem_notifications"
                   class="dropdown-item text-center fw-semibold py-2">
                    See all notifications
                </a>
            `;

      $list.html(html);
    },
  });
});

$(document).ready(function () {
  $(".edit-modal-lg").on("shown.bs.modal", function (e) {
    if ($(".textarea").length > 0) {
      tinymce.init({
        selector: ".textarea",
        plugins: [
          "a11ychecker",
          "advlist",
          "advcode",
          "advtable",
          "autolink",
          "checklist",
          "export",
          "forecolor backcolor",
          "lists",
          "link",
          "image",
          "charmap",
          "preview",
          "code",
          "anchor",
          "searchreplace",
          "visualblocks",
          "powerpaste",
          "fullscreen",
          "formatpainter",
          "insertdatetime",
          "media",
          "image",
          "spellchecker",
          "directionality",
          "fullscreen",
          "table",
          "help",
          "wordcount",
        ],
        toolbar:
          "undo redo | image media | code fullscreen| formatpainter casechange blocks fontsize | bold italic forecolor backcolor | " +
          "alignleft aligncenter alignright alignjustify | " +
          "bullist numlist checklist outdent indent | removeformat | ltr rtl |a11ycheck table help",

        font_size_formats: "8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt",
        image_uploadtab: false,
        images_upload_url: base_url + "admin/media/upload",
        relative_urls: false,
        remove_script_host: false,
        file_picker_types: "image media",
        media_poster: false,
        media_alt_source: false,

        file_picker_callback: function (callback, value, meta) {
          if (meta.filetype == "media" || meta.filetype == "image") {
            const input = document.createElement("input");
            input.setAttribute("type", "file");
            input.setAttribute("accept", "image/* audio/* video/*");

            input.addEventListener("change", (e) => {
              const file = e.target.files[0];

              var reader = new FileReader();
              var fd = new FormData();
              var files = file;
              fd.append("documents[]", files);
              fd.append("filetype", meta.filetype);
              fd.append(csrfName, csrfHash);

              var filename = "";
              var year = date("Y");
              // AJAX
              jQuery.ajax({
                url: base_url + "admin/media/upload",
                type: "post",
                data: fd,
                contentType: false,
                processData: false,
                async: false,
                success: function (response) {
                  var response = jQuery.parseJSON(response);
                  filename = response.file_name;
                },
              });

              reader.onload = function (e) {
                callback(base_url + "uploads/media/" + year + "/" + filename);
              };
              reader.readAsDataURL(file);
            });
            input.click();
          }
        },
        setup: function (editor) {
          editor.on("change keyup", function (e) {
            //tinyMCE.triggerSave(); // updates all instances
            editor.save(); // updates this instance's textarea
            $(editor.getElement()).trigger("change"); // for garlic to detect change
          });
        },
      });
    }
  });
  tinymce.init({
    selector: ".addr_editor",
    menubar: true,
    plugins: [
      "a11ychecker",
      "advlist",
      "advcode",
      "advtable",
      "autolink",
      "checklist",
      "export",
      "lists",
      "link",
      "image",
      "charmap",
      "preview",
      "code",
      "anchor",
      "searchreplace",
      "visualblocks",
      "powerpaste",
      "fullscreen",
      "formatpainter",
      "insertdatetime",
      "media",
      "image",
      "directionality",
      "fullscreen",
      "table",
      "help",
      "wordcount",
    ],
    toolbar:
      "undo redo | image media | code fullscreen| formatpainter casechange blocks fontsize | bold italic forecolor backcolor | " +
      "alignleft aligncenter alignright alignjustify | " +
      "bullist numlist checklist outdent indent | removeformat | ltr rtl |a11ycheck table help",

    font_size_formats: "8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt",
    image_uploadtab: false,
    images_upload_url: base_url + "admin/media/upload",
    relative_urls: false,
    remove_script_host: false,
    file_picker_types: "image media",
    media_poster: false,
    media_alt_source: false,

    file_picker_callback: function (callback, value, meta) {
      if (meta.filetype == "media" || meta.filetype == "image") {
        const input = document.createElement("input");
        input.setAttribute("type", "file");
        input.setAttribute("accept", "image/* audio/* video/*");

        input.addEventListener("change", (e) => {
          const file = e.target.files[0];

          var reader = new FileReader();
          var fd = new FormData();
          var files = file;
          fd.append("documents[]", files);
          fd.append("filetype", meta.filetype);
          fd.append(csrfName, csrfHash);

          const date = new Date();
          var filename = "";
          var year = date.getFullYear();
          // AJAX
          jQuery.ajax({
            url: base_url + "admin/media/upload",
            type: "post",
            data: fd,
            contentType: false,
            processData: false,
            async: false,
            success: function (response) {
              var response = jQuery.parseJSON(response);
              filename = response.file_name;
            },
          });

          reader.onload = function (e) {
            callback(base_url + "uploads/media/" + year + "/" + filename);
          };
          reader.readAsDataURL(file);
        });
        input.click();
      }
    },
    setup: function (editor) {
      editor.on("change keyup", function (e) {
        //tinyMCE.triggerSave(); // updates all instances
        editor.save(); // updates this instance's textarea
        $(editor.getElement()).trigger("change"); // for garlic to detect change
      });
    },
  });
});

// inventory report
function inventory_query_params(p) {
  return {
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

// Time Slots Validation
$(document).ready(function () {
  $(".form-submit-event").on("submit", function (e) {
    if ($(this).find('input[name="time_slot_config"]').length > 0) {
      return true;
    }

    var fromTime = $("#from_time").val();
    var toTime = $("#to_time").val();
    var lastOrderTime = $("#last_order_time").val();
    var isValid = true;

    $("#from_time_error").addClass("d-none");
    $("#to_time_error").addClass("d-none");
    $("#last_order_time_error").addClass("d-none");

    if (fromTime && toTime) {
      if (toTime <= fromTime) {
        $("#to_time_error").removeClass("d-none");
        isValid = false;
      }
    }

    if (fromTime && toTime && lastOrderTime) {
      if (lastOrderTime < fromTime || lastOrderTime > toTime) {
        $("#last_order_time_error").removeClass("d-none");
        isValid = false;
      }
    }

    if (!isValid) {
      e.preventDefault();
      return false;
    }
  });

  $("#to_time").on("change", function () {
    var fromTime = $("#from_time").val();
    var toTime = $(this).val();

    if (fromTime && toTime && toTime <= fromTime) {
      $("#to_time_error").removeClass("d-none");
    } else {
      $("#to_time_error").addClass("d-none");
    }
  });

  $("#last_order_time").on("change", function () {
    var fromTime = $("#from_time").val();
    var toTime = $("#to_time").val();
    var lastOrderTime = $(this).val();

    if (fromTime && toTime && lastOrderTime) {
      if (lastOrderTime < fromTime || lastOrderTime > toTime) {
        $("#last_order_time_error").removeClass("d-none");
      } else {
        $("#last_order_time_error").addClass("d-none");
      }
    }
  });

  $("#from_time").on("change", function () {
    var fromTime = $(this).val();
    var toTime = $("#to_time").val();
    var lastOrderTime = $("#last_order_time").val();

    if (fromTime && toTime && toTime <= fromTime) {
      $("#to_time_error").removeClass("d-none");
    } else {
      $("#to_time_error").addClass("d-none");
    }

    if (fromTime && toTime && lastOrderTime) {
      if (lastOrderTime < fromTime || lastOrderTime > toTime) {
        $("#last_order_time_error").removeClass("d-none");
      } else {
        $("#last_order_time_error").addClass("d-none");
      }
    }
  });
});

// shiprocket

$(".shipping_type").on("change", function (e) {
  e.preventDefault();
  if ($(this).val() == "0") {
    $(".shiprocket_type").hide();
    $(".shiprocket_type_label").hide();
  }
  if ($(this).val() == "1") {
    $(".shiprocket_type").hide();
    $(".shiprocket_type_label").hide();
  }
  if ($(this).val() == "2") {
    $(".shiprocket_type_label").removeClass("d-none");
    $(".shiprocket_type").show();
  }
});
$(".edit_shipping_type").on("change", function (e) {
  e.preventDefault();
  if ($(this).val() == "0") {
    $(".edit_shiprocket_type").hide();
    $(".edit_shiprocket_type_label").addClass("d-none");
  }
  if ($(this).val() == "1") {
    $(".edit_shiprocket_type").hide();
    $(".edit_shiprocket_type_label").addClass("d-none");
  }
  if ($(this).val() == "2") {
    $(".edit_shiprocket_type_label").removeClass("d-none");
    $(".edit_shiprocket_type").show();
  }
});

$(".check_create_order").on("change", function (e) {
  e.preventDefault();
  if ($(this).is(":checked")) {
    $(".create_shiprocket_order").attr("disabled", false);
    var pickup_location = $(this).attr("id");
    $("#pickup_location").attr("value", pickup_location);
  } else {
    $(".create_shiprocket_order").attr("disabled", true);
  }
});

$(".generate_awb").on("click", function (e) {
  e.preventDefault();
  var shipment_id = $(this).attr("id");
  Swal.fire({
    title: "Are You Sure !",
    text: "you want to generate AWb!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, generate AWB!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: base_url + "admin/orders/generate_awb",
          data: {
            shipment_id: shipment_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("AWB Generated!", result["message"], "success");
              location.reload();
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(".send_pickup_request").on("click", function (e) {
  e.preventDefault();
  var shipment_id = $(this).attr("name");
  Swal.fire({
    title: "Are You Sure !",
    text: "you want to send pickup request!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, send request!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: base_url + "admin/orders/send_pickup_request",
          data: {
            shipment_id: shipment_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Request send!", result["message"], "success");
              location.reload();
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

function digital_order_mails_query_params(p) {
  return {
    order_item_id: $('input[name="order_item_id"]').val(),
    order_id: $('input[name="order_id"]').val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search,
  };
}

$(document).on("click", ".edit_digital_order_mails", function (e, rows) {
  e.preventDefault();
  var order_item_id = $(this).data("order_item_id");
  var order_id = $(this).data("order_id");
  $('input[name="order_id"]').val(order_id);
  $('input[name="order_item_id"]').val(order_item_id);
  $("#digital_order_mail_table").bootstrapTable("refresh");
});

$(".cancel_shiprocket_order").on("click", function (e) {
  e.preventDefault();
  var shiprocket_order_id = $(this).attr("name");

  Swal.fire({
    title: "Are You Sure !",
    text: "you want to cancel order!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, cancel it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: base_url + "admin/orders/cancel_shiprocket_order",
          data: {
            shiprocket_order_id: shiprocket_order_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Order cancelled !", result["message"], "success");
              location.reload();
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(".generate_menifest").on("click", function (e) {
  e.preventDefault();
  var shipment_id = $(this).attr("name");

  Swal.fire({
    title: "Are You Sure !",
    text: "you want to generate manifest!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, generate manifest!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: base_url + "admin/orders/generate_menifest",
          data: {
            shipment_id: shipment_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Manifest generated!", result["message"], "success");
              location.reload();
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(".generate_label").on("click", function (e) {
  e.preventDefault();
  var shipment_id = $(this).attr("name");

  Swal.fire({
    title: "Are You Sure !",
    text: "you want to generate label!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, generate label!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: base_url + "admin/orders/generate_label",
          data: {
            shipment_id: shipment_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Label generated!", result["message"], "success");
              location.reload();
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(".generate_invoice").on("click", function (e) {
  e.preventDefault();
  var order_id = $(this).attr("name");

  Swal.fire({
    title: "Are You Sure !",
    text: "you want to generate invoice!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, generate invoice!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "POST",
          url: base_url + "admin/orders/generate_invoice",
          data: {
            order_id: order_id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (result, textStatus) {
            if (result["error"] == false) {
              Swal.fire("Invoice generated!", result["message"], "success");
              location.reload();
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$("#shiprocket_order_parcel_form").on("submit", function (e) {
  e.preventDefault();
  var formData = new FormData(this);
  formData.append(csrfName, csrfHash);
  $.ajax({
    type: "POST",
    url: $(this).attr("action"),
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (result) {
      csrfName = result.csrfName;
      csrfHash = result.csrfHash;
      if (result.error == false) {
        iziToast.success({
          message: result["message"],
        });
        location.reload();
      } else {
        iziToast.error({
          message: result["message"],
        });
      }
    },
  });
});

// shiprocket end

$(document).on("click", "#delete-flash-sale", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085D6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/Flash_sale/delete_flash_sale",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          success: function (result) {
            if (result["error"] == false) {
              Swal.fire("Deleted!", result["message"], "success");
              $("table").bootstrapTable("refresh");
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});

$("#start_date, #end_date").on("click", function (e) {
  // e.preventDefault();
  today = new Date().toISOString().slice(0, 16);

  document.getElementsByName("start_date")[0].min = today;

  var today = new Date().toISOString().slice(0, 16);

  document.getElementsByName("end_date")[0].min = today;
});

$("#tryrun").on("shown.bs.modal", function (e) {
  $(".search_flash_sale_product").select2({
    ajax: {
      url: base_url + "admin/product/get_sale_product_data",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          search: params.term, // search term
          limit: 10,
          status: 1,
        };
      },
      processResults: function (response) {
        return {
          results: response,
        };
      },
      cache: true,
    },
    minimumInputLength: 1,
    theme: "bootstrap4",
    placeholder: "Search for products",
  });
});

$(".search_flash_sale_product").select2({
  ajax: {
    url: base_url + "admin/product/get_sale_product_data",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
        limit: 10,
        status: 1,
      };
    },
    processResults: function (response) {
      return {
        results: response,
      };
    },
    cache: true,
  },
  minimumInputLength: 1,
  theme: "bootstrap4",
  placeholder: "Search for products",
});

$(document).on("click", "#add_attribute_value", function (e) {
  e.preventDefault();
  load_attribute_section();
});

$(document).on("change", ".swatche_type", function () {
  var currentRow = $(this).closest(".row");
  var colorPicker = $(this).siblings(".color_picker");
  var uploadMedia = $(this).siblings(".upload_media");
  var growDiv = $(this).siblings(".grow");
  var imageUploadSection = currentRow.find(".image-upload-section");

  if ($(this).val() == "1") {
    // Color type selected
    colorPicker.show();
    colorPicker.attr("name", "swatche_value[]");
    uploadMedia.hide();
    growDiv.hide();
    // Clear any existing image upload data
    imageUploadSection.find('input[type="hidden"]').val("");
    imageUploadSection.find('input[type="hidden"]').removeAttr("name");
  } else if ($(this).val() == "2") {
    // Image type selected
    colorPicker.hide();
    colorPicker.removeAttr("name");
    uploadMedia.show();
    growDiv.show();
    // Set the hidden input name for image uploads
    imageUploadSection
      .find('input[type="hidden"]')
      .attr("name", "swatche_value[]");
  } else if ($(this).val() == "0") {
    // Default type selected
    colorPicker.hide();
    colorPicker.removeAttr("name");
    uploadMedia.hide();
    growDiv.hide();
    // Remove name attributes to prevent validation issues
    imageUploadSection.find('input[type="hidden"]').removeAttr("name");
    // Clear any existing data
    imageUploadSection.find('input[type="hidden"]').val("");
  }
});

function load_attribute_section() {
  var html =
    ' <div class="d-flex flex-wrap form-group gap-3 row">' +
    '<div class="col-sm-4">' +
    '<input type="text" step="any"  class="form-control"  placeholder="Enter Attribute Value" name="attribute_value[]" >' +
    "</div>" +
    '<div class="col-sm-4">' +
    '<select class="form-control swatche_type"  name="swatche_type[]">' +
    '<option value="0"> Default </option>' +
    '<option value="1"> Color </option >' +
    '<option value="2"> Image </option >' +
    "</select >" +
    '<input type="color" class="form-control color_picker my-3" id="swatche_value" style="display: none;">' +
    '<a style="display: none;" class="uploadFile img btn btn-primary text-white btn-sm upload_media my-3" data-input="swatche_value[]" name="attribute_img[]" data-isremovable="0" data-is-multiple-uploads-allowed="0" data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class="fa fa-upload"></i> Upload</a></div>' +
    '<div class="col-sm-2"> ' +
    '<button type="button" class="btn btn-tool remove_attribute_section" > <i class="text-danger far fa-times-circle fa-2x "></i> </button>' +
    "</div>" +
    '<div class="container-fluid row image-upload-section">' +
    '<div style="display: none;" class="shadow p-3 mb-5 bg-white rounded m-4 text-center grow">' +
    '<div class="image-upload-div"><img class="img-fluid mb-2 image" src="" alt="Image Not Found"></div>' +
    '<input type="hidden" name="swatche_value[]" value="">' +
    "</div>" +
    "</div>" +
    "</div>";

  $("#attribute_section").append(html);

  $(".swatche_type").each(function () {
    $(".swatche_type").select2({
      theme: "bootstrap4",
      width: $(".swatche_type").data("width")
        ? $(".swatche_type").data("width")
        : $(".swatche_type").hasClass("w-100")
          ? "100%"
          : "style",
      placeholder: $(".swatche_type").data("placeholder"),
      allowClear: Boolean($(".swatche_type").data("allow-clear")),
    });
  });
}

$(document).on("click", ".remove_attribute_section", function () {
  $(this).closest(".row").remove();
});

$(".search_offer_product").select2({
  ajax: {
    url: base_url + "admin/product/get_sale_product_data",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
        limit: 10,
        status: 1,
      };
    },
    processResults: function (response) {
      return {
        results: response,
      };
    },
    cache: true,
  },
  minimumInputLength: 1,
  theme: "bootstrap4",
  placeholder: "Search for products",
});

// send notification to specific user
$(document).on("change", "#products_type", function (e) {
  e.preventDefault();
  var type_val = $(this).val();
  if (type_val == "specific_products") {
    $(".offer-slider-products").removeClass("d-none");
  } else {
    $(".offer-slider-products").addClass("d-none");
  }
});

//bonus_type
$(document).on("change", ".offer_discount", function (e, data) {
  e.preventDefault();
  var sort_type_val = $(this).val();
  if (sort_type_val == "categories" && sort_type_val != " ") {
    $(".categories").removeClass("d-none");
  } else {
    $(".categories").addClass("d-none");
  }
  if (sort_type_val == "all_products" && sort_type_val != " ") {
    $(".all_products").removeClass("d-none");
  } else {
    $(".all_product").addClass("d-none");
  }
  if (sort_type_val == "brand" && sort_type_val != " ") {
    $(".brand").removeClass("d-none");
  } else {
    $(".brand").addClass("d-none");
  }
});

$("#offer_type").change(function (e) {
  e.preventDefault();

  if (
    $("#offer_type").val() == "categories" ||
    $("#offer_type").val() == "all_products" ||
    $("#offer_type").val() == "brand"
  ) {
    $("#min_max_section").removeClass("d-none");
  } else {
    $("#min_max_section").addClass("d-none");
  }
});

$(document).ready(function () {
  if (
    $("#offer_type").val() == "categories" ||
    $("#offer_type").val() == "all_products" ||
    $("#offer_type").val() == "brand"
  ) {
    $("#min_max_section").removeClass("d-none");
  }
});

$(".search_offer").select2({
  ajax: {
    url: base_url + "admin/offer_slider/offer_slider_data",
    type: "GET",
    dataType: "json",
    delay: 250,
    data: function (params) {
      return {
        search: params.term, // search term
      };
    },
    processResults: function (response) {
      return {
        results: response.data,
      };
    },
    // cache: true
  },
  escapeMarkup: function (markup) {
    return markup;
  },
  // minimumInputLength: 1,
  templateResult: formatOffers,
  templateSelection: formatOffersSelection,
  theme: "bootstrap4",
  placeholder: "Search for offers",
});
$(document).on("change", "#category_parent", function (e) {
  if ($("#stock_products_table").length) {
    e.preventDefault();
    $("#stock_products_table").bootstrapTable("refresh", {
      url: base_url + "admin/manage_stock/get_stock_list",
    });
  }
});
function formatOffers(offer) {
  if (offer.loading) return offer.text;

  var discountDisplay = "";
  if (!["default", "products", "offer_url"].includes(offer.type)) {
    discountDisplay = `Min - Max Discount : ${offer.min_discount}% - ${offer.max_discount}% `;
  }

  var markup = `<div class="row">
    <div class="col-md-1 align-self-center">
        <div class="">
            <img class="img-fluid" src="${offer.image}"></div>
        </div>
        <div class="align-self-center col-md-10">
            <div class="">${discountDisplay}
        </div>
        <small class="">ID - ${offer.id} </small> |
        <small class="">Type - ${offer.type} </small> 
    </div>`;
  return markup;
}

function formatOffersSelection(offer) {
  if (offer.element.dataset.select2Text == undefined) {
    var discountDisplay = "";
    if (!["default", "products", "offer_url"].includes(offer.type)) {
      discountDisplay = `Min - Max Discount : ${offer.min_discount}% - ${offer.max_discount}% `;
    }

    var markup = `<div class="row">
        <div class="col-md-1 align-self-center">
            <div class="">
                <img class="img-fluid" src="${offer.image}"></div>
            </div>
            <div class="align-self-center col-md-11">
                <div class="">${discountDisplay}
            </div>
            <small class="">ID - ${offer.id} </small> |
            <small class="">Type - ${offer.type} </small> 
            </div>
        </div>`;
  } else {
    markup = offer.element.dataset.select2Text;
  }
  return markup;
  // return offer.type || offer.id;
}

$(document).on("click", "#delete-offer-slider", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/offer_slider/delete_offer_slider",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
          success: function (result) {
            if (result["error"] == false) {
              Swal.fire("Deleted!", result["message"], "success");
              $("table").bootstrapTable("refresh");
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", result["message"], "warning");
            }
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});

$(document).on("click", "#settle_flash_sale", function () {
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, settle flash sale!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/cron_job/fetch_active_flash_sale",

          success: function (result) {
            Swal.fire("Flash Sale Settle!", result["message"], "success");
            location.reload();
          },
        });
      });
    },
    allowOutsideClick: false,
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Cancelled!", "Your data is  safe.", "error");
    }
  });
});

$(document).on("click", "#save_offer_section_order", function () {
  var data = $("#sortable").sortable("serialize");
  $.ajax({
    data: data,
    type: "GET",
    url: base_url + "admin/offer_slider/update_section_order",
    dataType: "json",
    success: function (response) {
      if (response.error == false) {
        iziToast.success({
          message: response.message,
        });
      } else {
        iziToast.error({
          message: response.message,
        });
      }
    },
  });
});
$(document).on("change", "#product_type_menu", function () {
  var value = $(this).val();
  //   alert(value)
  if (value == "digital_product") {
    var html = '<option value="digital_product">Digital Product</option>';

    $("#product-type").html(html);
    $("#variant_stock_level").hide(200);
    $("#general_price_section").show(200);
    $(".simple-product-save").hide(200);
    $(".simple-product-level-stock-management").addClass("d-none");
    $(".simple_stock_management").addClass("d-none");
    $(".product-attributes").addClass("disabled");
    $(".product-variants").addClass("disabled");
    $("#digital_product_setting").show();
    $(".cod_allowed").addClass("d-none");
    $(".is_returnable").addClass("d-none");
    $(".is_cancelable").addClass("d-none");
    $(".indicator").addClass("d-none");
    $(".product_qty").addClass("d-none");
    $(".delivery_settings").addClass("d-none");
    $(".total_allowed_quantity").addClass("d-none");
    $(".minimum_order_quantity").addClass("d-none");
    $(".guarantee_period").addClass("d-none");
    $(".warranty_period").addClass("d-none");
    $(".quantity_step_size").addClass("d-none");
    $(".deliverable_type").addClass("d-none");
    $(".pickup_locations").addClass("d-none");
    $(".local_shipping").addClass("d-none");
    $(".is_attachment_required").addClass("d-none");
    $("#product-dimensions").addClass("d-none");
    $("#stock-management").addClass("d-none");
  } else {
    var html =
      ' <option value=" ">Select Type</option>' +
      '<option value="simple_product">Simple Product</option>' +
      '<option value="variable_product">Variable Product</option>';
    $("#product-type").html(html);
    $(".cod_allowed").removeClass("d-none");
    $(".is_returnable").removeClass("d-none");
    $(".is_cancelable").removeClass("d-none");
    $(".indicator").removeClass("d-none");
    $(".product_qty").removeClass("d-none");
    $(".delivery_settings").removeClass("d-none");
    $(".total_allowed_quantity").removeClass("d-none");
    $(".minimum_order_quantity").removeClass("d-none");
    $(".guarantee_period").removeClass("d-none");
    $(".warranty_period").removeClass("d-none");
    $(".quantity_step_size").removeClass("d-none");
    $(".deliverable_type").removeClass("d-none");
    $(".pickup_locations").removeClass("d-none");
    $(".local_shipping").removeClass("d-none");
    $(".is_attachment_required").removeClass("d-none");
    $("#product-dimensions").removeClass("d-none");
    $("#stock-management").removeClass("d-none");
  }
});
$(document).on("click", ".update_offer_active_status", function () {
  var update_id = $(this).data("id");
  var status = $(this).data("status");
  var table = $(this).data("table");
  $.ajax({
    type: "GET",
    url: base_url + "admin/popup_offer/update_status",
    data: {
      id: update_id,
      status: status,
      table: table,
    },
    dataType: "json",
    success: function (result) {
      if (result["error"] == true) {
        iziToast.success({
          message:
            '<span class="text-capitalize">' +
            result.message +
            "</span> Status Updated",
        });
        $(".table").bootstrapTable("refresh");
      } else {
        iziToast.error({
          message: '<span class="text-capitalize">' + result.message,
        });
      }
    },
  });
});
$(document).on("click", "#delete-popup-offer", function () {
  var id = $(this).data("id");
  Swal.fire({
    title: "Are You Sure!",
    text: "You won't be able to revert this!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    showLoaderOnConfirm: true,
    preConfirm: function () {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: base_url + "admin/popup_offer/delete_offer",
          data: {
            id: id,
            [csrfName]: csrfHash,
          },
          dataType: "json",
        })
          .done(function (response, textStatus) {
            if (response.error == false) {
              Swal.fire("Deleted!", response.message, "success");
              $("table").bootstrapTable("refresh");
              csrfName = result.csrfName;
              csrfHash = result.csrfHash;
            } else {
              Swal.fire("Oops...", response.message, "warning");
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Swal.fire("Oops...", "Something went wrong with ajax !", "error");
          });
      });
    },
    allowOutsideClick: false,
  });
});

$(document).on(
  "switchChange.bootstrapSwitch",
  "#is_specific_user",
  function (event) {
    event.preventDefault();
    var state = $(this).bootstrapSwitch("state");
    if (state) {
      $("#promocode_users").show();
      $(".no_of_users").hide();
    } else {
      $("#promocode_users").hide();
      $(".no_of_users").show();
      $(".no_of_users").removeClass("d-none");
    }
  }
);

$(".select_promocode_user").each(function () {
  $(this).select2({
    ajax: {
      url: base_url + "admin/promo_code/get_users",
      type: "GET",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          search: params.term, // search term
        };
      },
      processResults: function (response) {
        return {
          results: response,
        };
      },
      cache: true,
    },
    minimumInputLength: 1,
    theme: "bootstrap4",
    placeholder: "Search for users",
  });
});
//select 2 for the city

$(document).on("click", "#add_faq", function () {
  // setTimeout(function () {
  //     location.reload();
  // }, 600)
});

// $(document).ready(function() {
//     $('.select2').select2({
//         theme: 'bootstrap4',          // use 'bootstrap-5' if you're using Bootstrap 5
//         placeholder: "Select City",
//         allowClear: true,
//         width: '100%'
//     });
// });

// 22.Whatsapp status
$(document).on("change", "#whatsapp_status", function (e, data) {
  if ($(this).prop("checked") == true) {
    $("#whatapp_number_input").show(200);
  } else {
    $("#whatapp_number_input").hide(200);
  }
});

function validateNumberInput(input) {
  // Remove any non-numeric characters from the input value
  input.value = input.value.replace(/\D/g, "");
}

// authentication setting
$("input[type=radio][name=authentication_method]").change(function () {
  var firebaseRadio = $('input[type=radio][id="firebaseRadio"]:checked').val();
  var smsRadio = $('input[type=radio][id="smsRadio"]:checked').val();
  if (firebaseRadio == "firebase") {
    $(".firebase_config").removeClass("d-none");
    $(".sms_gateway").addClass("d-none");
  } else if (smsRadio == "sms") {
    $(".sms_gateway").removeClass("d-none");
    $(".firebase_config").addClass("d-none");
  }
});

var cat_html = "";
var sms_data = $("#sms_gateway_data").val() ? $("#sms_gateway_data").val() : [];
if (sms_data.length != 0) {
  var sms_data = JSON.parse(sms_data);
}

// custom_notification_message
function initializeInputFiller(spanSelector) {
  const fillerSpans = document.querySelectorAll(spanSelector);

  fillerSpans.forEach(function (span) {
    if (span.getAttribute("data-filler-bound")) return;
    span.setAttribute("data-filler-bound", "true");
    span.addEventListener("mousedown", function (e) {
      e.preventDefault(); // stop focus loss

      const inputField = document.activeElement;

      if (
        !inputField ||
        (inputField.tagName !== "TEXTAREA" && inputField.tagName !== "INPUT")
      ) {
        return;
      }

      const text = this.textContent.trim();
      const startPos = inputField.selectionStart;
      const endPos = inputField.selectionEnd;
      const currentValue = inputField.value;

      const newValue =
        currentValue.substring(0, startPos) +
        text +
        currentValue.substring(endPos);

      inputField.value = newValue;
      inputField.focus();
      inputField.setSelectionRange(
        startPos + text.length,
        startPos + text.length
      );
    });
  });
}


$(document).ready(function () {
  $("#sms-gateway-modal").on("hidden.bs.modal", function () {
    $(".smsgateway_setting_form").removeClass("d-none");
    $(".update_notification_module").removeClass("d-none");
  });
});

$(document).on("click", ".edit_sms_modal", function () {
  $("#sms-gateway-modal").modal("show");
  var id = $(this).data("id");
  var url = $(this).data("url");
  // var formData = new FormData(this);
  $.ajax({
    type: "GET",
    url: base_url + url + "?edit_id=" + id,
    // data: formData,
    // dataType: "dataType",
    success: function (response) {
      var formSubmitEventHtml = $(response).find(".form-submit-event");
      $(".sms-modal").find(".modal-body").html(formSubmitEventHtml);

      // if ($("#sms-gateway-modal").hasClass('show')) {
      // $(this).closest('.smsgateway_setting_form').find('class:card-body').addClass('d-none');
      $(".smsgateway_setting_form").addClass("d-none");
      $(".update_notification_module").addClass("d-none");
      // }

      initializeInputFiller(".hashtag");
      initializeInputFiller(".hashtag_input");

      setTimeout(function () {
        $(".sms-modal").unblock();
      }, 2000);
    },
  });
});

// body data
$(document).on("click", "#add_sms_body", function (e) {
  e.preventDefault();
  load_sms_body_section(cat_html, false);
});

function load_sms_body_section(
  cat_html,
  is_edit = false,
  body_keys = [],
  body_values = []
) {
  var html = "";
  if (is_edit == true) {
    if (Array.isArray(body_keys)) {
      for (var i = 0; i < body_keys.length; i++) {
        html += `
          <div class="form-group row key-value-pair align-items-end">
            <div class="col-sm-5">
              <label class="form-label"> Key </label>
              <input type="text" class="form-control" placeholder="Enter Key" name="body_key[]" value="${body_keys[i]}">
            </div>
            <div class="col-sm-5">
              <label class="form-label"> Value </label>
              <input type="text" class="form-control" placeholder="Enter Value" name="body_value[]" value="${body_values[i]}">
            </div>
            <div class="col-sm-2 pb-1">
              <button type="button" class="btn btn-tool remove_keyvalue_section">
                <i class="text-danger far fa-times-circle fa-2x"></i>
              </button>
            </div>
          </div>`;
      }
    }
  } else {
    html = `
      <div class="form-group row key-value-pair align-items-end">
        <div class="col-sm-5">
          <label class="form-label"> Key </label>
          <input type="text" class="form-control" placeholder="Enter Key" name="body_key[]" value="">
        </div>
        <div class="col-sm-5">
          <label class="form-label"> Value </label>
          <input type="text" class="form-control" placeholder="Enter Value" name="body_value[]" value="">
        </div>
        <div class="col-sm-2 pb-1">
          <button type="button" class="btn btn-tool remove_keyvalue_section">
            <i class="text-danger far fa-times-circle fa-2x"></i>
          </button>
        </div>
      </div>`;
  }
  $("#formdata_section").append(html);
}

$(document).on("click", ".remove_keyvalue_section", function () {
  $(this).closest(".row").remove();
});

// header data
$(document).on("click", "#add_sms_header", function (e) {
  e.preventDefault();
  load_sms_header_section(cat_html, false);
});

function load_sms_header_section(
  cat_html,
  is_edit = false,
  key_headers = [],
  value_headers = []
) {
  var html = "";
  if (is_edit == true) {
    if (Array.isArray(key_headers)) {
      for (var i = 0; i < key_headers.length; i++) {
        html += `
          <div class="form-group row align-items-end">
            <div class="col-sm-5">
              <label class="form-label"> Key </label>
              <input type="text" class="form-control" placeholder="Enter Key" name="header_key[]" value="${key_headers[i]}">
            </div>
            <div class="col-sm-5">
              <label class="form-label"> Value </label>
              <input type="text" class="form-control" placeholder="Enter Value" name="header_value[]" value="${value_headers[i]}">
            </div>
            <div class="col-sm-2 pb-1">
              <button type="button" class="btn btn-tool remove_keyvalue_header_section">
                <i class="text-danger far fa-times-circle fa-2x"></i>
              </button>
            </div>
          </div>`;
      }
    }
  } else {
    html = `
      <div class="form-group row align-items-end">
        <div class="col-sm-5">
          <label class="form-label"> Key </label>
          <input type="text" class="form-control" placeholder="Enter Key" name="header_key[]" value="">
        </div>
        <div class="col-sm-5">
          <label class="form-label"> Value </label>
          <input type="text" class="form-control" placeholder="Enter Value" name="header_value[]" value="">
        </div>
        <div class="col-sm-2 pb-1">
          <button type="button" class="btn btn-tool remove_keyvalue_header_section">
            <i class="text-danger far fa-times-circle fa-2x"></i>
          </button>
        </div>
      </div>`;
  }
  $("#formdata_header_section").append(html);
}

$(document).on("click", ".remove_keyvalue_header_section", function () {
  $(this).closest(".row").remove();
});

// paramas data
$(document).on("click", "#add_sms_params", function (e) {
  e.preventDefault();
  load_sms_params_section(cat_html, false);
});

function load_sms_params_section(
  cat_html,
  is_edit = false,
  key_params = [],
  value_params = []
) {
  var html = "";
  if (is_edit == true) {
    if (Array.isArray(key_params)) {
      for (var i = 0; i < key_params.length; i++) {
        html += `
          <div class="form-group row align-items-end">
            <div class="col-sm-5">
              <label class="form-label"> Key </label>
              <input type="text" class="form-control" placeholder="Enter Key" name="params_key[]" value="${key_params[i]}">
            </div>
            <div class="col-sm-5">
              <label class="form-label"> Value </label>
              <input type="text" class="form-control" placeholder="Enter Value" name="params_value[]" value="${value_params[i]}">
            </div>
            <div class="col-sm-2 pb-1">
              <button type="button" class="btn btn-tool remove_keyvalue_paramas_section">
                <i class="text-danger far fa-times-circle fa-2x"></i>
              </button>
            </div>
          </div>`;
      }
    }
  } else {
    html = `
      <div class="form-group row align-items-end">
        <div class="col-sm-5">
          <label class="form-label"> Key </label>
          <input type="text" class="form-control" placeholder="Enter Key" name="params_key[]" value="">
        </div>
        <div class="col-sm-5">
          <label class="form-label"> Value </label>
          <input type="text" class="form-control" placeholder="Enter Value" name="params_value[]" value="">
        </div>
        <div class="col-sm-2 pb-1">
          <button type="button" class="btn btn-tool remove_keyvalue_paramas_section">
            <i class="text-danger far fa-times-circle fa-2x"></i>
          </button>
        </div>
      </div>`;
  }
  $("#formdata_params_section").append(html);
}

$(document).on("click", ".remove_keyvalue_paramas_section", function () {
  $(this).closest(".row").remove();
});

$(document).ready(function () {
  load_sms_header_section(
    cat_html,
    true,
    sms_data.header_key,
    sms_data.header_value
  );
  load_sms_body_section(cat_html, true, sms_data.body_key, sms_data.body_value);
  load_sms_params_section(
    cat_html,
    true,
    sms_data.params_key,
    sms_data.params_value
  );
});

$(document).ready(function () {
  $("#sms_gateway_submit").click(function (event) {
    event.preventDefault();

    var form = document.getElementById("smsgateway_setting_form"); // Get the form DOM element
    var formData = new FormData(form); // Initialize FormData object with form DOM element

    formData.append(csrfName, csrfHash);
    // return
    $.ajax({
      type: $(form).attr("method"),
      url: base_url + "admin/Sms_gateway_settings/add_sms_data",
      data: formData,
      contentType: false, // Important: false prevents jQuery from setting Content-Type header
      processData: false,
      success: function (response) {
        var response = jQuery.parseJSON(response);
        csrfName = response.csrfName;
        csrfHash = response.csrfHash;

        if (response.error == false) {
          iziToast.success({
            message: response.message,
          });
          setTimeout(function () {
            location.reload();
          }, 3000);
        } else {
          iziToast.error({
            message: response.message,
          });
        }
      },
    });
    return;
  });
});

// var sms_data = $("#auth_setting").val();
// var sms_data = JSON.parse(sms_data);

$("input[type=radio][name=authentication_method]").change(function () {
  var firebaseRadio = $('input[type=radio][id="firebaseRadio"]:checked').val();
  var smsRadio = $('input[type=radio][id="smsRadio"]:checked').val();
  if (firebaseRadio == "firebase") {
    $(".firebase_config").removeClass("d-none");
    $(".sms_gateway").addClass("d-none");
  } else if (smsRadio == "sms") {
    $(".sms_gateway").removeClass("d-none");
    $(".firebase_config").addClass("d-none");
  }
});

$(document).ready(function () {
  $("#product-body-tab").on("click", function (event) {
    event.preventDefault();
    $("#product-text").addClass("show");
    $("#product-text").addClass("active");
    $("#product-formdata").addClass("show");
  });

  $("#product-header-tab").click(function (event) {
    event.preventDefault();
    if ($("#product-formdata").hasClass("show")) {
      $("#product-formdata").removeClass("active");
      $("#product-formdata").removeClass("show");
    }
    if ($("#product-text").hasClass("show")) {
      $("#product-text").removeClass("active");
      $("#product-text").removeClass("show");
    }
  });
  $("#product-params-tab").click(function (event) {
    event.preventDefault();
    if ($("#product-formdata").hasClass("show")) {
      $("#product-formdata").removeClass("active");
      $("#product-formdata").removeClass("show");
    }
    if ($("#product-text").hasClass("show")) {
      $("#product-text").removeClass("active");
      $("#product-text").removeClass("show");
    }
  });
});

function createHeader() {
  const username = document.getElementById("converterInputAccountSID").value;
  const password = document.getElementById("converterInputAuthToken").value;

  if (username && password) {
    const stringToEncode = `${username}:${password}`;
    document.getElementById(
      "basicToken"
    ).innerText = `Authorization: Basic ${btoa(stringToEncode)}`;
  } else {
    alert("Please provide both account SID and Auth Token.");
  }
}

$(document).ready(function () {
  $("#multiple_tax").select2({
    placeholder: "Select Taxes",
    allowClear: true,
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const quantityStepSize = document.getElementById("quantity_step_size");
  const minimumOrderQuantity = document.getElementById(
    "minimum_order_quantity"
  );
  const totalAllowedQuantity = document.getElementById(
    "total_allowed_quantity"
  );

  function validateStepSize() {
    const minOrderValue =
      minimumOrderQuantity && minimumOrderQuantity.value
        ? parseInt(minimumOrderQuantity.value, 10)
        : NaN;
    const stepSizeValue =
      quantityStepSize && quantityStepSize.value
        ? parseInt(quantityStepSize.value, 10)
        : NaN;
    const totalAllowedValue =
      totalAllowedQuantity && totalAllowedQuantity.value
        ? parseInt(totalAllowedQuantity.value, 10)
        : NaN;

    if (!isNaN(stepSizeValue)) {
      if (!isNaN(minOrderValue) && stepSizeValue < minOrderValue) {
        iziToast.error({
          title: "Error",
          message:
            "Quantity Step Size cannot be less than Minimum Order Quantity!",
          position: "topRight",
        });
      }

      if (!isNaN(totalAllowedValue)) {
        if (stepSizeValue > totalAllowedValue) {
          iziToast.error({
            title: "Error",
            message:
              "Quantity Step Size cannot be greater than Total Allowed Quantity!",
            position: "topRight",
          });
        }

        if (totalAllowedValue % stepSizeValue !== 0) {
          iziToast.error({
            title: "Error",
            message:
              "Quantity Step Size must be a multiple of Total Allowed Quantity!",
            position: "topRight",
          });
        }
      }
    }
  }

  if (minimumOrderQuantity) {
    minimumOrderQuantity.addEventListener("input", validateStepSize);
  }
  if (quantityStepSize) {
    quantityStepSize.addEventListener("input", validateStepSize);
  }
  if (totalAllowedQuantity) {
    totalAllowedQuantity.addEventListener("input", validateStepSize);
  }
});

// $(document).ready(function () {
//   $(".system_setting_form").on("click", function (event) {
//     event.preventDefault();

//     // ---------------------------------------------------------
//     // Custom Validation: Check for Duplicate Custom Charges
//     // (ONLY name + amount, ignore settings)
//     // ---------------------------------------------------------
//     let chargeSignatures = [];
//     let duplicateFound = false;

//     $('#custom_charges_wrapper .custom-charge-row').each(function () {
//       let row = $(this);

//       let nameInput = row.find('input[name*="[name]"]');
//       let amountInput = row.find('input[name*="[amount]"]');

//       // Safety check
//       if (nameInput.length === 0 || amountInput.length === 0) return;

//       let name = nameInput.val().trim().toLowerCase();
//       let amount = amountInput.val().trim();

//       // Skip empty rows
//       if (name === '' || amount === '') return;

//       // Signature ONLY based on name + amount
//       let signature = `${name}|${amount}`;

//       if (chargeSignatures.includes(signature)) {
//         duplicateFound = true;

//         iziToast.error({
//           message: `Duplicate custom charge found: "${nameInput.val()}" with amount ${amount}. Please remove duplicates.`,
//           position: 'topRight'
//         });

//         return false; // break .each()
//       }

//       chargeSignatures.push(signature);
//     });

//     if (duplicateFound) {
//       return false; // stop form submit
//     }
//     // ---------------------------------------------------------

//     var form = $("#system_setting_form");
//     var formData = new FormData(form[0]);
//     formData.append(csrfName, csrfHash);

//     $.ajax({
//       url: form.attr("action"),
//       type: "POST",
//       data: formData,
//       processData: false,
//       contentType: false,
//       dataType: "json",
//       success: function (response) {
//         csrfName = response["csrfName"];
//         csrfHash = response["csrfHash"];

//         if (response.error == false) {
//           iziToast.success({
//             message: response.message,
//           });
//           setTimeout(function () {
//             location.reload();
//           }, 1500);
//         } else {
//           iziToast.error({
//             message: response.message,
//           });
//         }
//       }
//     });
//   });
// });


// De-Register Web Purchase Code
$("#de_register_web_purchase_code").click(function () {
  $("#purchaseCodeModal").modal("show");
});
$("#submitPurchaseCode").click(function () {
  let current_code = $.trim($("#de_register_web_purchase_code").val());
  let purchase_code = $.trim($("#modalPurchaseCode").val());

  if (purchase_code == current_code) {
    $.ajax({
      type: "POST",
      url: base_url + "admin/purchase_code/de_register_web",
      data: { purchase_code: current_code },
      dataType: "json",
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];

        if (result.error == false) {
          var form = new FormData();
          form.append("code_bravo", result["data"]["code_bravo"]);
          form.append("dr_firestone", result["data"]["dr_firestone"]);
          form.append("time_check", result["data"]["time_check"]);
          form.append("domain_url", result["data"]["domain_url"]);

          $.ajax({
            type: "POST",
            url: "https://wrteam.in/validator/home/deregister",
            data: form,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
              if (result.error == false) {
                var de_register = result.time_check;

                $.ajax({
                  type: "POST",
                  url: base_url + "admin/purchase_code/delete_web_purchasecode",
                  data: { de_register_code: de_register },
                  dataType: "json",
                  success: function (result) {
                    if (result.error == false) {
                      iziToast.success({
                        message: result.message,
                      });
                      setTimeout(function () {
                        location.reload();
                      }, 300);
                    } else {
                      iziToast.error({
                        message: result.message,
                      });
                    }
                  },
                });
              } else {
                iziToast.error({
                  message: result.message,
                });
              }
            },
          });
        } else {
          iziToast.error({
            message: "Enter Purchase Code and try again.",
          });
        }
      },
    });
  } else {
    iziToast.error({
      message:
        "Incorrect Purchase Code! Please confirm your purchase code and try again.",
    });
  }
});

// De-Register App Purchase Code
$("#de_register_app_purchase_code").click(function () {
  $("#AppPurchaseCodeModal").modal("show");
});
$("#submitAppPurchaseCode").click(function () {
  let purchase_code = $.trim($("#modalAppPurchaseCode").val());
  let current_code = $.trim($("#de_register_app_purchase_code").val());

  if (purchase_code === current_code) {
    $.ajax({
      type: "POST",
      url: base_url + "admin/purchase_code/de_register_app",
      data: { purchase_code: current_code },
      dataType: "json",
      success: function (result) {
        csrfName = result["csrfName"];
        csrfHash = result["csrfHash"];

        if (result.error == false) {
          var form = new FormData();
          form.append("code_bravo", result["data"]["code_bravo"]);
          form.append("dr_firestone", result["data"]["dr_firestone"]);
          form.append("time_check", result["data"]["time_check"]);
          form.append("domain_url", result["data"]["domain_url"]);

          $.ajax({
            type: "POST",
            url: "https://wrteam.in/validator/home/deregister",
            data: form,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
              if (result.error == false) {
                var de_register = result.time_check;

                $.ajax({
                  type: "POST",
                  url:
                    base_url + "admin/purchase_code/delete_app_purchase_code",
                  data: { de_register_code: de_register },
                  dataType: "json",
                  success: function (result) {
                    if (result.error == false) {
                      iziToast.success({
                        message: result.message,
                      });
                      setTimeout(function () {
                        location.reload();
                      }, 300);
                    } else {
                      iziToast.error({
                        message: result.message,
                      });
                    }
                  },
                });
              } else {
                iziToast.error({
                  message: result.message,
                });
              }
            },
          });
        } else {
          iziToast.error({
            message: "Something went wrong!",
          });
        }
      },
    });
  } else {
    iziToast.error({
      message:
        "Incorrect Purchase Code! Please confirm your purchase code and try again.",
    });
  }
});

((window, document, Math) => {
  const ctx = document.createElement("canvas").getContext("2d");
  const currentColor = {
    r: 0,
    g: 0,
    b: 0,
    h: 0,
    s: 0,
    v: 0,
    a: 1,
  };
  let picker,
    colorArea,
    colorAreaDims,
    colorMarker,
    colorPreview,
    colorValue,
    clearButton,
    hueSlider,
    hueMarker,
    alphaSlider,
    alphaMarker,
    currentEl,
    currentFormat,
    oldColor;

  // Default settings
  const settings = {
    el: "[data-coloris]",
    parent: null,
    theme: "light",
    wrap: true,
    margin: 2,
    format: "hex",
    formatToggle: false,
    swatches: [],
    alpha: true,
    clearButton: {
      show: false,
      label: "Clear",
    },
    a11y: {
      open: "Open color picker",
      close: "Close color picker",
      marker: "Saturation: {s}. Brightness: {v}.",
      hueSlider: "Hue slider",
      alphaSlider: "Opacity slider",
      input: "Color value field",
      format: "Color format",
      swatch: "Color swatch",
      instruction:
        "Saturation and brightness selector. Use up, down, left and right arrow keys to select.",
    },
  };

  /**
   * Configure the color picker.
   * @param {object} options Configuration options.
   */
  function configure(options) {
    if (typeof options !== "object") {
      return;
    }

    for (const key in options) {
      switch (key) {
        case "el":
          bindFields(options.el);
          if (options.wrap !== false) {
            wrapFields(options.el);
          }
          break;
        case "parent":
          settings.parent = document.querySelector(options.parent);
          if (settings.parent) {
            settings.parent.appendChild(picker);
          }
          break;
        case "theme":
          picker.className = `clr-picker clr-${options.theme
            .split("-")
            .join(" clr-")}`;
          break;
        case "margin":
          options.margin *= 1;
          settings.margin = !isNaN(options.margin)
            ? options.margin
            : settings.margin;
          break;
        case "wrap":
          if (options.el && options.wrap) {
            wrapFields(options.el);
          }
          break;
        case "format":
          settings.format = options.format;
          break;
        case "formatToggle":
          getEl("clr-format").style.display = options.formatToggle
            ? "block"
            : "none";
          if (options.formatToggle) {
            settings.format = "auto";
          }
          break;
        case "swatches":
          if (Array.isArray(options.swatches)) {
            const swatches = [];

            options.swatches.forEach((swatch, i) => {
              swatches.push(
                `<button id="clr-swatch-${i}" aria-labelledby="clr-swatch-label clr-swatch-${i}" style="color: ${swatch};">${swatch}</button>`
              );
            });

            if (swatches.length) {
              getEl("clr-swatches").innerHTML = `<div>${swatches.join(
                ""
              )}</div>`;
            }
          }
          break;
        case "alpha":
          settings.alpha = !!options.alpha;
          picker.setAttribute("data-alpha", settings.alpha);
          break;
        case "clearButton":
          let display = "none";

          if (options.clearButton.show) {
            display = "block";
          }

          if (options.clearButton.label) {
            clearButton.innerHTML = options.clearButton.label;
          }

          clearButton.style.display = display;
          break;
        case "a11y":
          const labels = options.a11y;
          let update = false;

          if (typeof labels === "object") {
            for (const label in labels) {
              if (labels[label] && settings.a11y[label]) {
                settings.a11y[label] = labels[label];
                update = true;
              }
            }
          }

          if (update) {
            const openLabel = getEl("clr-open-label");
            const swatchLabel = getEl("clr-swatch-label");

            openLabel.innerHTML = settings.a11y.open;
            swatchLabel.innerHTML = settings.a11y.swatch;
            colorPreview.setAttribute("aria-label", settings.a11y.close);
            hueSlider.setAttribute("aria-label", settings.a11y.hueSlider);
            alphaSlider.setAttribute("aria-label", settings.a11y.alphaSlider);
            colorValue.setAttribute("aria-label", settings.a11y.input);
            colorArea.setAttribute("aria-label", settings.a11y.instruction);
          }
      }
    }
  }

  /**
   * Bind the color picker to input fields that match the selector.
   * @param {string} selector One or more selectors pointing to input fields.
   */
  function bindFields(selector) {
    // Show the color picker on click on the input fields that match the selector
    addListener(document, "click", selector, (event) => {
      const parent = settings.parent;
      const coords = event.target.getBoundingClientRect();
      const scrollY = window.scrollY;
      let reposition = {
        left: false,
        top: false,
      };
      let offset = {
        x: 0,
        y: 0,
      };
      let left = coords.x;
      let top = scrollY + coords.y + coords.height + settings.margin;

      currentEl = event.target;
      oldColor = currentEl.value;
      currentFormat = getColorFormatFromStr(oldColor);
      picker.classList.add("clr-open");

      const pickerWidth = picker.offsetWidth;
      const pickerHeight = picker.offsetHeight;

      // If the color picker is inside a custom container
      // set the position relative to it
      if (parent) {
        const style = window.getComputedStyle(parent);
        const marginTop = parseFloat(style.marginTop);
        const borderTop = parseFloat(style.borderTopWidth);

        offset = parent.getBoundingClientRect();
        offset.y += borderTop + scrollY;
        left -= offset.x;
        top -= offset.y;

        if (left + pickerWidth > parent.clientWidth) {
          left += coords.width - pickerWidth;
          reposition.left = true;
        }

        if (top + pickerHeight > parent.clientHeight - marginTop) {
          top -= coords.height + pickerHeight + settings.margin * 2;
          reposition.top = true;
        }

        top += parent.scrollTop;

        // Otherwise set the position relative to the whole document
      } else {
        if (left + pickerWidth > document.documentElement.clientWidth) {
          left += coords.width - pickerWidth;
          reposition.left = true;
        }

        if (
          top + pickerHeight - scrollY >
          document.documentElement.clientHeight
        ) {
          top = scrollY + coords.y - pickerHeight - settings.margin;
          reposition.top = true;
        }
      }

      picker.classList.toggle("clr-left", reposition.left);
      picker.classList.toggle("clr-top", reposition.top);
      picker.style.left = `${left}px`;
      picker.style.top = `${top}px`;
      colorAreaDims = {
        width: colorArea.offsetWidth,
        height: colorArea.offsetHeight,
        x: picker.offsetLeft + colorArea.offsetLeft + offset.x,
        y: picker.offsetTop + colorArea.offsetTop + offset.y,
      };

      setColorFromStr(oldColor);
      colorValue.focus({
        preventScroll: true,
      });
    });

    // Update the color preview of the input fields that match the selector
    addListener(document, "input", selector, (event) => {
      const parent = event.target.parentNode;

      // Only update the preview if the field has been previously wrapped
      if (parent.classList.contains("clr-field")) {
        parent.style.color = event.target.value;
      }
    });
  }

  /**
   * Wrap the linked input fields in a div that adds a color preview.
   * @param {string} selector One or more selectors pointing to input fields.
   */
  function wrapFields(selector) {
    document.querySelectorAll(selector).forEach((field) => {
      const parentNode = field.parentNode;

      if (!parentNode.classList.contains("clr-field")) {
        const wrapper = document.createElement("div");

        wrapper.innerHTML = `<button aria-labelledby="clr-open-label"></button>`;
        parentNode.insertBefore(wrapper, field);
        wrapper.setAttribute("class", "clr-field");
        wrapper.style.color = field.value;
        wrapper.appendChild(field);
      }
    });
  }

  /**
   * Close the color picker.
   * @param {boolean} [revert] If true, revert the color to the original value.
   */
  function closePicker(revert) {
    if (currentEl) {
      // Revert the color to the original value if needed
      if (revert && oldColor !== currentEl.value) {
        currentEl.value = oldColor;

        // Trigger an "input" event to force update the thumbnail next to the input field
        currentEl.dispatchEvent(
          new Event("input", {
            bubbles: true,
          })
        );
      }

      if (oldColor !== currentEl.value) {
        currentEl.dispatchEvent(
          new Event("change", {
            bubbles: true,
          })
        );
      }

      picker.classList.remove("clr-open");
      currentEl.focus({
        preventScroll: true,
      });
      currentEl = null;
    }
  }

  /**
   * Set the active color from a string.
   * @param {string} str String representing a color.
   */
  function setColorFromStr(str) {
    const rgba = strToRGBA(str);
    const hsva = RGBAtoHSVA(rgba);

    updateMarkerA11yLabel(hsva.s, hsva.v);
    updateColor(rgba, hsva);

    // Update the UI
    hueSlider.value = hsva.h;
    picker.style.color = `hsl(${hsva.h}, 100%, 50%)`;
    hueMarker.style.left = `${(hsva.h / 360) * 100}%`;

    colorMarker.style.left = `${(colorAreaDims.width * hsva.s) / 100}px`;
    colorMarker.style.top = `${colorAreaDims.height - (colorAreaDims.height * hsva.v) / 100
      }px`;

    alphaSlider.value = hsva.a * 100;
    alphaMarker.style.left = `${hsva.a * 100}%`;
  }

  /**
   * Guess the color format from a string.
   * @param {string} str String representing a color.
   * @return {string} The color format.
   */
  function getColorFormatFromStr(str) {
    const format = str.substring(0, 3).toLowerCase();

    if (format === "rgb" || format === "hsl") {
      return format;
    }

    return "hex";
  }

  /**
   * Copy the active color to the linked input field.
   * @param {number} [color] Color value to override the active color.
   */
  function pickColor(color) {
    if (currentEl) {
      currentEl.value = color !== undefined ? color : colorValue.value;
      currentEl.dispatchEvent(
        new Event("input", {
          bubbles: true,
        })
      );
    }
  }

  /**
   * Set the active color based on a specific point in the color gradient.
   * @param {number} x Left position.
   * @param {number} y Top position.
   */
  function setColorAtPosition(x, y) {
    const hsva = {
      h: hueSlider.value * 1,
      s: (x / colorAreaDims.width) * 100,
      v: 100 - (y / colorAreaDims.height) * 100,
      a: alphaSlider.value / 100,
    };
    const rgba = HSVAtoRGBA(hsva);

    updateMarkerA11yLabel(hsva.s, hsva.v);
    updateColor(rgba, hsva);
    pickColor();
  }

  /**
   * Update the color marker's accessibility label.
   * @param {number} saturation
   * @param {number} value
   */
  function updateMarkerA11yLabel(saturation, value) {
    let label = settings.a11y.marker;

    saturation = saturation.toFixed(1) * 1;
    value = value.toFixed(1) * 1;
    label = label.replace("{s}", saturation);
    label = label.replace("{v}", value);
    colorMarker.setAttribute("aria-label", label);
  }

  //
  /**
   * Get the pageX and pageY positions of the pointer.
   * @param {object} event The MouseEvent or TouchEvent object.
   * @return {object} The pageX and pageY positions.
   */
  function getPointerPosition(event) {
    return {
      pageX: event.changedTouches ? event.changedTouches[0].pageX : event.pageX,
      pageY: event.changedTouches ? event.changedTouches[0].pageY : event.pageY,
    };
  }

  /**
   * Move the color marker when dragged.
   * @param {object} event The MouseEvent object.
   */
  function moveMarker(event) {
    const pointer = getPointerPosition(event);
    let x = pointer.pageX - colorAreaDims.x;
    let y = pointer.pageY - colorAreaDims.y;

    if (settings.parent) {
      y += settings.parent.scrollTop;
    }

    x = x < 0 ? 0 : x > colorAreaDims.width ? colorAreaDims.width : x;
    y = y < 0 ? 0 : y > colorAreaDims.height ? colorAreaDims.height : y;

    colorMarker.style.left = `${x}px`;
    colorMarker.style.top = `${y}px`;

    setColorAtPosition(x, y);

    // Prevent scrolling while dragging the marker
    event.preventDefault();
    event.stopPropagation();
  }

  /**
   * Move the color marker when the arrow keys are pressed.
   * @param {number} offsetX The horizontal amount to move.
   * * @param {number} offsetY The vertical amount to move.
   */
  function moveMarkerOnKeydown(offsetX, offsetY) {
    const x = colorMarker.style.left.replace("px", "") * 1 + offsetX;
    const y = colorMarker.style.top.replace("px", "") * 1 + offsetY;

    colorMarker.style.left = `${x}px`;
    colorMarker.style.top = `${y}px`;

    setColorAtPosition(x, y);
  }

  /**
   * Update the color picker's input field and preview thumb.
   * @param {Object} rgba Red, green, blue and alpha values.
   * @param {Object} [hsva] Hue, saturation, value and alpha values.
   */
  function updateColor(rgba = {}, hsva = {}) {
    let format = settings.format;

    for (const key in rgba) {
      currentColor[key] = rgba[key];
    }

    for (const key in hsva) {
      currentColor[key] = hsva[key];
    }

    const hex = RGBAToHex(currentColor);
    const opaqueHex = hex.substring(0, 7);

    colorMarker.style.color = opaqueHex;
    alphaMarker.parentNode.style.color = opaqueHex;
    alphaMarker.style.color = hex;
    colorPreview.style.color = hex;

    // Force repaint the color and alpha gradients as a workaround for a Google Chrome bug
    colorArea.style.display = "none";
    colorArea.offsetHeight;
    colorArea.style.display = "";
    alphaMarker.nextElementSibling.style.display = "none";
    alphaMarker.nextElementSibling.offsetHeight;
    alphaMarker.nextElementSibling.style.display = "";

    if (format === "mixed") {
      format = currentColor.a === 1 ? "hex" : "rgb";
    } else if (format === "auto") {
      format = currentFormat;
    }

    switch (format) {
      case "hex":
        colorValue.value = hex;
        break;
      case "rgb":
        colorValue.value = RGBAToStr(currentColor);
        break;
      case "hsl":
        colorValue.value = HSLAToStr(HSVAtoHSLA(currentColor));
        break;
    }

    // Select the current format in the format switcher
    document.querySelector(`.clr-format [value="${format}"]`).checked = true;
  }

  /**
   * Set the hue when its slider is moved.
   */
  function setHue() {
    const hue = hueSlider.value * 1;
    const x = colorMarker.style.left.replace("px", "") * 1;
    const y = colorMarker.style.top.replace("px", "") * 1;

    picker.style.color = `hsl(${hue}, 100%, 50%)`;
    hueMarker.style.left = `${(hue / 360) * 100}%`;

    setColorAtPosition(x, y);
  }

  /**
   * Set the alpha when its slider is moved.
   */
  function setAlpha() {
    const alpha = alphaSlider.value / 100;

    alphaMarker.style.left = `${alpha * 100}%`;
    updateColor({
      a: alpha,
    });
    pickColor();
  }

  /**
   * Convert HSVA to RGBA.
   * @param {object} hsva Hue, saturation, value and alpha values.
   * @return {object} Red, green, blue and alpha values.
   */
  function HSVAtoRGBA(hsva) {
    const saturation = hsva.s / 100;
    const value = hsva.v / 100;
    let chroma = saturation * value;
    let hueBy60 = hsva.h / 60;
    let x = chroma * (1 - Math.abs((hueBy60 % 2) - 1));
    let m = value - chroma;

    chroma = chroma + m;
    x = x + m;
    m = m;

    const index = Math.floor(hueBy60) % 6;
    const red = [chroma, x, m, m, x, chroma][index];
    const green = [x, chroma, chroma, x, m, m][index];
    const blue = [m, m, x, chroma, chroma, x][index];

    return {
      r: Math.round(red * 255),
      g: Math.round(green * 255),
      b: Math.round(blue * 255),
      a: hsva.a,
    };
  }

  /**
   * Convert HSVA to HSLA.
   * @param {object} hsva Hue, saturation, value and alpha values.
   * @return {object} Hue, saturation, lightness and alpha values.
   */
  function HSVAtoHSLA(hsva) {
    const value = hsva.v / 100;
    const lightness = value * (1 - hsva.s / 100 / 2);
    let saturation;

    if (lightness > 0 && lightness < 1) {
      saturation = Math.round(
        ((value - lightness) / Math.min(lightness, 1 - lightness)) * 100
      );
    }

    return {
      h: hsva.h,
      s: saturation || 0,
      l: Math.round(lightness * 100),
      a: hsva.a,
    };
  }

  /**
   * Convert RGBA to HSVA.
   * @param {object} rgba Red, green, blue and alpha values.
   * @return {object} Hue, saturation, value and alpha values.
   */
  function RGBAtoHSVA(rgba) {
    const red = rgba.r / 255;
    const green = rgba.g / 255;
    const blue = rgba.b / 255;
    const xmax = Math.max(red, green, blue);
    const xmin = Math.min(red, green, blue);
    const chroma = xmax - xmin;
    const value = xmax;
    let hue = 0;
    let saturation = 0;

    if (chroma) {
      if (xmax === red) {
        hue = (green - blue) / chroma;
      }
      if (xmax === green) {
        hue = 2 + (blue - red) / chroma;
      }
      if (xmax === blue) {
        hue = 4 + (red - green) / chroma;
      }
      if (xmax) {
        saturation = chroma / xmax;
      }
    }

    hue = Math.floor(hue * 60);

    return {
      h: hue < 0 ? hue + 360 : hue,
      s: Math.round(saturation * 100),
      v: Math.round(value * 100),
      a: rgba.a,
    };
  }

  /**
   * Parse a string to RGBA.
   * @param {string} str String representing a color.
   * @return {object} Red, green, blue and alpha values.
   */
  function strToRGBA(str) {
    const regex =
      /^((rgba)|rgb)[\D]+([\d.]+)[\D]+([\d.]+)[\D]+([\d.]+)[\D]*?([\d.]+|$)/i;
    let match, rgba;

    // Default to black for invalid color strings
    ctx.fillStyle = "#000";

    // Use canvas to convert the string to a valid color string
    ctx.fillStyle = str;
    match = regex.exec(ctx.fillStyle);

    if (match) {
      rgba = {
        r: match[3] * 1,
        g: match[4] * 1,
        b: match[5] * 1,
        a: match[6] * 1,
      };
    } else {
      match = ctx.fillStyle
        .replace("#", "")
        .match(/.{2}/g)
        .map((h) => parseInt(h, 16));
      rgba = {
        r: match[0],
        g: match[1],
        b: match[2],
        a: 1,
      };
    }

    return rgba;
  }

  /**
   * Convert RGBA to Hex.
   * @param {object} rgba Red, green, blue and alpha values.
   * @return {string} Hex color string.
   */
  function RGBAToHex(rgba) {
    let R = rgba.r.toString(16);
    let G = rgba.g.toString(16);
    let B = rgba.b.toString(16);
    let A = "";

    if (rgba.r < 16) {
      R = "0" + R;
    }

    if (rgba.g < 16) {
      G = "0" + G;
    }

    if (rgba.b < 16) {
      B = "0" + B;
    }

    if (settings.alpha && rgba.a < 1) {
      const alpha = (rgba.a * 255) | 0;
      A = alpha.toString(16);

      if (alpha < 16) {
        A = "0" + A;
      }
    }

    return "#" + R + G + B + A;
  }

  /**
   * Convert RGBA values to a CSS rgb/rgba string.
   * @param {object} rgba Red, green, blue and alpha values.
   * @return {string} CSS color string.
   */
  function RGBAToStr(rgba) {
    if (!settings.alpha || rgba.a === 1) {
      return `rgb(${rgba.r}, ${rgba.g}, ${rgba.b})`;
    } else {
      return `rgba(${rgba.r}, ${rgba.g}, ${rgba.b}, ${rgba.a})`;
    }
  }

  /**
   * Convert HSLA values to a CSS hsl/hsla string.
   * @param {object} hsla Hue, saturation, lightness and alpha values.
   * @return {string} CSS color string.
   */
  function HSLAToStr(hsla) {
    if (!settings.alpha || hsla.a === 1) {
      return `hsl(${hsla.h}, ${hsla.s}%, ${hsla.l}%)`;
    } else {
      return `hsla(${hsla.h}, ${hsla.s}%, ${hsla.l}%, ${hsla.a})`;
    }
  }

  /**
   * Init the color picker.
   */
  //Custom header colour
  function init() {
    // Render the UI
    picker = document.createElement("div");
    picker.setAttribute("id", "clr-picker");
    picker.className = "clr-picker";
    picker.innerHTML =
      `<input id="clr-color-value" class="clr-color" type="text" value="" aria-label="${settings.a11y.input}">` +
      `<div id="clr-color-area" class="clr-gradient" role="application" aria-label="${settings.a11y.instruction}">` +
      '<div id="clr-color-marker" class="clr-marker" tabindex="0"></div>' +
      "</div>" +
      '<div class="clr-hue">' +
      `<input id="clr-hue-slider" type="range" min="0" max="360" step="1" aria-label="${settings.a11y.hueSlider}">` +
      '<div id="clr-hue-marker"></div>' +
      "</div>" +
      '<div class="clr-alpha">' +
      `<input id="clr-alpha-slider" type="range" min="0" max="100" step="1" aria-label="${settings.a11y.alphaSlider}">` +
      '<div id="clr-alpha-marker"></div>' +
      "<span></span>" +
      "</div>" +
      '<div id="clr-format" class="clr-format">' +
      '<fieldset class="clr-segmented">' +
      `<legend>${settings.a11y.format}</legend>` +
      '<input id="clr-f1" type="radio" name="clr-format" value="hex">' +
      '<label for="clr-f1">Hex</label>' +
      '<input id="clr-f2" type="radio" name="clr-format" value="rgb">' +
      '<label for="clr-f2">RGB</label>' +
      '<input id="clr-f3" type="radio" name="clr-format" value="hsl">' +
      '<label for="clr-f3">HSL</label>' +
      "<span></span>" +
      "</fieldset>" +
      "</div>" +
      '<div id="clr-swatches" class="clr-swatches"></div>' +
      `<button id="clr-clear" class="clr-clear">${settings.clearButton.label}</button>` +
      `<button id="clr-color-preview" class="clr-preview" aria-label="${settings.a11y.close}"></button>` +
      `<span id="clr-open-label" hidden>${settings.a11y.open}</span>` +
      `<span id="clr-swatch-label" hidden>${settings.a11y.swatch}</span>`;

    // Append the color picker to the DOM
    document.body.appendChild(picker);

    // Reference the UI elements
    colorArea = getEl("clr-color-area");
    colorMarker = getEl("clr-color-marker");
    clearButton = getEl("clr-clear");
    colorPreview = getEl("clr-color-preview");
    colorValue = getEl("clr-color-value");
    hueSlider = getEl("clr-hue-slider");
    hueMarker = getEl("clr-hue-marker");
    alphaSlider = getEl("clr-alpha-slider");
    alphaMarker = getEl("clr-alpha-marker");

    // Bind the picker to the default selector
    bindFields(settings.el);
    wrapFields(settings.el);

    addListener(picker, "mousedown", (event) => {
      picker.classList.remove("clr-keyboard-nav");
      event.stopPropagation();
    });

    addListener(colorArea, "mousedown", (event) => {
      addListener(document, "mousemove", moveMarker);
    });

    addListener(colorArea, "touchstart", (event) => {
      document.addEventListener("touchmove", moveMarker, {
        passive: false,
      });
    });

    addListener(colorMarker, "mousedown", (event) => {
      addListener(document, "mousemove", moveMarker);
    });

    addListener(colorMarker, "touchstart", (event) => {
      document.addEventListener("touchmove", moveMarker, {
        passive: false,
      });
    });

    addListener(colorValue, "change", (event) => {
      setColorFromStr(colorValue.value);
      pickColor();
    });

    addListener(clearButton, "click", (event) => {
      pickColor("");
      closePicker();
    });

    addListener(colorPreview, "click", (event) => {
      pickColor();
      closePicker();
    });

    addListener(document, "click", ".clr-format input", (event) => {
      currentFormat = event.target.value;
      updateColor();
      pickColor();
    });

    addListener(picker, "click", ".clr-swatches button", (event) => {
      setColorFromStr(event.target.textContent);
      pickColor();
    });

    addListener(document, "mouseup", (event) => {
      document.removeEventListener("mousemove", moveMarker);
    });

    addListener(document, "touchend", (event) => {
      document.removeEventListener("touchmove", moveMarker);
    });

    addListener(document, "mousedown", (event) => {
      picker.classList.remove("clr-keyboard-nav");
      closePicker();
    });

    addListener(document, "keydown", (event) => {
      if (event.key === "Escape") {
        closePicker(true);
      } else if (event.key === "Tab") {
        picker.classList.add("clr-keyboard-nav");
      }
    });

    addListener(document, "click", ".clr-field button", (event) => {
      event.target.nextElementSibling.dispatchEvent(
        new Event("click", {
          bubbles: true,
        })
      );
    });

    addListener(colorMarker, "keydown", (event) => {
      const movements = {
        ArrowUp: [0, -1],
        ArrowDown: [0, 1],
        ArrowLeft: [-1, 0],
        ArrowRight: [1, 0],
      };

      if (Object.keys(movements).indexOf(event.key) !== -1) {
        moveMarkerOnKeydown(...movements[event.key]);
        event.preventDefault();
      }
    });

    addListener(colorArea, "click", moveMarker);
    addListener(hueSlider, "input", setHue);
    addListener(alphaSlider, "input", setAlpha);
  }

  /**
   * Shortcut for getElementById to optimize the minified JS.
   * @param {string} id The element id.
   * @return {object} The DOM element with the provided id.
   */
  function getEl(id) {
    return document.getElementById(id);
  }

  /**
   * Shortcut for addEventListener to optimize the minified JS.
   * @param {object} context The context to which the listener is attached.
   * @param {string} type Event type.
   * @param {(string|function)} selector Event target if delegation is used, event handler if not.
   * @param {function} [fn] Event handler if delegation is used.
   */
  function addListener(context, type, selector, fn) {
    const matches =
      Element.prototype.matches || Element.prototype.msMatchesSelector;

    // Delegate event to the target of the selector
    if (typeof selector === "string") {
      context.addEventListener(type, (event) => {
        if (matches.call(event.target, selector)) {
          fn.call(event.target, event);
        }
      });

      // If the selector is not a string then it's a function
      // in which case we need regular event listener
    } else {
      fn = selector;
      context.addEventListener(type, fn);
    }
  }

  /**
   * Call a function only when the DOM is ready.
   * @param {function} fn The function to call.
   * @param {array} args Arguments to pass to the function.
   */
  function DOMReady(fn, args) {
    args = args !== undefined ? args : [];

    if (document.readyState !== "loading") {
      fn(...args);
    } else {
      document.addEventListener("DOMContentLoaded", () => {
        fn(...args);
      });
    }
  }

  // Polyfill for Nodelist.forEach
  if (
    NodeList !== undefined &&
    NodeList.prototype &&
    !NodeList.prototype.forEach
  ) {
    NodeList.prototype.forEach = Array.prototype.forEach;
  }

  // Expose the color picker to the global scope
  window.Coloris = (() => {
    const methods = {
      set: configure,
      wrap: wrapFields,
      close: closePicker,
    };

    function Coloris(options) {
      DOMReady(() => {
        if (options) {
          if (typeof options === "string") {
            bindFields(options);
          } else {
            configure(options);
          }
        }
      });
    }

    for (const key in methods) {
      Coloris[key] = (...args) => {
        DOMReady(methods[key], args);
      };
    }

    return Coloris;
  })();

  // Init the color picker when the DOM is ready
  DOMReady(init);
})(window, document, Math);
Coloris({
  el: ".coloris",
});
$(document).on("submit", ".add_return_reason_form", function (e) {
  e.preventDefault();
  var formData = new FormData(this);
  formData.append(csrfName, csrfHash);

  $.ajax({
    type: "POST",
    url: base_url + "admin/return_reasons/add_return_reasons",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (response) {
      (csrfName = response.csrfName), (csrfHash = response.csrfHash);
      if (response.error == false) {
        iziToast.success({
          message: response.message,
        });
        setTimeout(function () {
          location.reload();
        }, 1000);
      } else {
        iziToast.error({
          message: response.message,
        });
        $("#submit_btn").attr("disabled", false).html("Add Return Reason");
      }
    },
  });
});

$(document).on("click", ".edit_return_reason", function (e) {
  e.preventDefault();

  $(".edit-modal-lg .modal-body").removeClass("view");
  var offer_id = $(this).data("id");

  var offer_url = $(this).attr("href");

  var urlParams = new URLSearchParams(offer_url.split("?")[1]);
  var edit_id = urlParams.get("promocode_edit_id");

  $.ajax({
    type: "POST",
    url: offer_url,
    data: {
      edit_id: edit_id,
      [csrfName]: csrfHash,
    },
    dataType: "json",
    success: function (response) {
      (csrfName = response.csrfName), (csrfHash = response.csrfHash);
      response = response.fetched_data;

      $(".modal-title").text("Edit Return Reason");
      $(".save_return_reason").text("Update Return Reason");
      $(".image-upload-section").removeClass("d-none");
      $("#add_promocode").val("");
      $("#edit_return_reason_id").val(response[0].id);
      $("#return_reason").val(response[0].return_reason);
      $("#message").val(response[0].message);

      $("#uploaded_image_here_val").val(response[0].image);

      $("#uploaded_image_here").attr("src", base_url + "/" + response[0].image);
    },
  });
});

$(document).on("click", "#delete-return-reason", function () {
  let id = $(this).data("id");

  Swal.fire({
    title: "Are you sure?",
    text: "This action cannot be undone.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#d33",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: base_url + "admin/return_reasons/delete_return_reason",
        type: "GET",
        data: { id: id },
        dataType: "json",

        success: function (response) {
          Swal.fire({
            toast: true,
            position: "top-end",
            icon: response.error ? "error" : "success",
            title: response.message || "Return reason deleted successfully",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
          });

          if (!response.error) {
            $(".table-striped").bootstrapTable("refresh");
          }
        },

        error: function () {
          Swal.fire({
            toast: true,
            position: "top-end",
            icon: "error",
            title: "Something went wrong. Please try again.",
            showConfirmButton: false,
            timer: 3000,
          });
        },
      });
    }
  });
});

$(document).on("click", ".edit_cash_collection_btn", function (e) {
  let delivery_boy_id = $(this).data("delivery_boy_id");
  let order_id = $(this).data("orderid");
  let amount = $(this).data("amount");
  let name = $(this).data("name");
  let mobile = $(this).data("mobile");
  $("#delivery_boy_id").val(delivery_boy_id);

  $("#details").val(
    "Id: " +
    order_id +
    " | Name:" +
    name +
    " | Mobile: " +
    mobile +
    " | Cash: " +
    amount
  );

  $("#amount").val(amount);

  window.scrollTo({ top: 0, behavior: "smooth" });
});

$(function () {
  $(".menuSearch").on("input", function () {
    let searchValue = $(this).val().toLowerCase();
    $(".menu-inner li").each(function () {
      let $currentItem = $(this);
      let text = $currentItem.text().toLowerCase();
      if (
        text.includes(searchValue) ||
        $currentItem.find("*").filter(function () {
          return $(this).text().toLowerCase().includes(searchValue);
        }).length > 0
      ) {
        $currentItem.show();
        $currentItem.parents(".sidebar-list").show();
      } else {
        $currentItem.hide();
      }
    });
  });
});

// document.querySelectorAll('.togglePassword').forEach(function (toggle) {
//     toggle.addEventListener('click', function () {
//         const input = this.previousElementSibling; // Find the input just before the button
//         const icon = this.querySelector('i'); // Get the eye icon
//         if (input.type === 'password') {
//             input.type = 'text';
//             icon.classList.remove('fa-eye');
//             icon.classList.add('fa-eye-slash');
//         } else {
//             input.type = 'password';
//             icon.classList.remove('fa-eye-slash');
//             icon.classList.add('fa-eye');
//         }
//     });
// });

document.querySelectorAll(".password-toggle").forEach((icon) => {
  icon.addEventListener("click", () => {
    const targetId = icon.getAttribute("data-target");
    const input = document.getElementById(targetId);
    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";
    input.setSelectionRange(input.value.length, input.value.length);
    icon.setAttribute("name", isPassword ? "eye-off-outline" : "eye-outline");
  });
});

window.isValidPhoneChar = function (event) {
  const charCode = event.charCode || event.keyCode;
  if (
    (charCode >= 48 && charCode <= 57) || // digits
    charCode === 43 || // +
    charCode === 8 || // backspace
    charCode === 0 // for arrow keys or tab
  ) {
    return true;
  }
  return false;
};
$(document).ready(function () {
  // Delegate the event to the body or a static parent element
  $("body").on("click", ".togglePassword", function () {
    const passwordField = $(this).prev("input");
    const eyeIcon = $(this).find("i");

    if (passwordField.attr("type") === "password") {
      passwordField.attr("type", "text"); // Show the password
      eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash"); // Change to eye-slash icon
    } else {
      passwordField.attr("type", "password"); // Hide the password
      eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye"); // Change back to eye icon
    }
  });
});

// Inventory Report functionality - Global scope
var inventoryChart = null;
var inventoryUnitsChart = null;

// Function to handle query parameters with date filter - Global scope
function inventory_query_params(params) {
  var start_date = $("#start_date").val();
  var end_date = $("#end_date").val();

  if (start_date) {
    params.start_date = start_date;
  }
  if (end_date) {
    params.end_date = end_date;
  }

  return params;
}

// Function to load chart data
function loadInventoryChart() {
  var start_date = $("#start_date").val();
  var end_date = $("#end_date").val();

  var url = base_url + "admin/Invoice/get_inventory_chart_data";
  var data = {};

  if (start_date) data.start_date = start_date;
  if (end_date) data.end_date = end_date;

  $.ajax({
    url: url,
    type: "GET",
    data: data,
    dataType: "json",
    success: function (response) {
      if (response.error === false && response.data) {
        renderPieChart(response.data);
        renderUnitsChart(response.data);
      } else {
        // Show empty chart message
        $("#inventory_pie_chart").html(
          '<div class="text-center p-4"><p>No data available for the selected period</p></div>'
        );
        $("#inventory_units_chart").html(
          '<div class="text-center p-4"><p>No data available for the selected period</p></div>'
        );
      }
    },
    error: function (xhr, status, error) {
      $("#inventory_pie_chart").html(
        '<div class="text-center p-4"><p>Error loading chart data</p></div>'
      );
      $("#inventory_units_chart").html(
        '<div class="text-center p-4"><p>Error loading chart data</p></div>'
      );
    },
  });
}

// Helper function to truncate long text
function truncateText(text, maxLength) {
  if (text.length <= maxLength) {
    return text;
  }
  return text.substring(0, maxLength - 3) + "...";
}

// Function to render Apex pie chart
function renderPieChart(data) {
  // Clear previous chart
  if (inventoryChart) {
    inventoryChart.destroy();
    inventoryChart = null;
  }

  if (!data || data.length === 0) {
    $("#inventory_pie_chart").html(
      '<div class="text-center p-4"><p>No data available for chart</p></div>'
    );
    return;
  }

  var labels = data.map(function (item) {
    return item.product_name;
  });
  var series = data.map(function (item) {
    return parseFloat(item.final_total);
  });

  // Calculate total sales
  var totalSales = series.reduce(function (sum, value) {
    return sum + value;
  }, 0);

  var options = {
    series: series,
    chart: {
      width: "100%",
      height: 400,
      type: "donut",
      events: {
        dataPointMouseEnter: function (event, chartContext, config) {
          var dataPointIndex = config.dataPointIndex;
          var value = series[dataPointIndex];
          var label = labels[dataPointIndex];
          var truncatedLabel = truncateText(label, 25);

          // Find and update custom center content
          var centerDiv = document.querySelector(
            "#inventory_pie_chart .custom-center-content"
          );
          if (centerDiv) {
            centerDiv.innerHTML =
              '<div class="center-label" style="font-size: 16px; font-weight: 600; color: #373d3f; margin-bottom: 5px;">' +
              truncatedLabel +
              '</div><div class="center-value" style="font-size: 22px; font-weight: 400; color: #373d3f;">$' +
              value.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              }) +
              "</div>";
          }
        },
        dataPointMouseLeave: function (event, chartContext, config) {
          // Reset to total when mouse leaves
          var centerDiv = document.querySelector(
            "#inventory_pie_chart .custom-center-content"
          );
          if (centerDiv) {
            centerDiv.innerHTML =
              '<div class="center-label" style="font-size: 16px; font-weight: 600; color: #373d3f; margin-bottom: 5px;">Total Sales</div><div class="center-value" style="font-size: 22px; font-weight: 400; color: #373d3f;">$' +
              totalSales.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              }) +
              "</div>";
          }
        },
      },
    },
    labels: labels,
    plotOptions: {
      pie: {
        donut: {
          size: "65%",
        },
      },
    },
    responsive: [
      {
        breakpoint: 480,
        options: {
          chart: {
            width: 200,
          },
          legend: {
            position: "bottom",
          },
        },
      },
    ],
    colors: [
      "#8e44adee",
      "#27ae60ee",
      "#e67e22ee",
      "#3498dbee",
      "#e74c3cee",
      "#f1c40fee",
      "#1abc9cee",
      "#ff69b4ee",
      "#7f8c8dee",
      "#9b59b6ee",
    ],
    legend: {
      position: "bottom",
      horizontalAlign: "center",
    },
    states: {
      hover: {
        filter: {
          type: "lighten",
          value: 0.1,
        },
      },
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "$" + val.toFixed(2);
        },
      },
    },
  };

  inventoryChart = new ApexCharts(
    document.querySelector("#inventory_pie_chart"),
    options
  );
  inventoryChart
    .render()
    .then(function () {
      // Add custom center content overlay
      var chartContainer = document.querySelector("#inventory_pie_chart");
      var existingCenter = chartContainer.querySelector(
        ".custom-center-content"
      );
      if (!existingCenter) {
        var centerDiv = document.createElement("div");
        centerDiv.className = "custom-center-content";
        centerDiv.style.cssText = `
                position: absolute;
                top: 45%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                pointer-events: none;
                z-index: 10;
            `;
        centerDiv.innerHTML =
          '<div class="center-label" style="font-size: 16px; font-weight: 600; color: #373d3f; margin-bottom: 5px;">Total Sales</div><div class="center-value" style="font-size: 22px; font-weight: 400; color: #373d3f;">$' +
          totalSales.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }) +
          "</div>";
        chartContainer.style.position = "relative";
        chartContainer.appendChild(centerDiv);
      }
    })
    .catch(function (error) {
      $("#inventory_pie_chart").html(
        '<div class="text-center p-4"><p>Error rendering chart</p></div>'
      );
    });
}

// Function to render Units Sold donut chart
function renderUnitsChart(data) {
  // Clear previous chart
  if (inventoryUnitsChart) {
    inventoryUnitsChart.destroy();
    inventoryUnitsChart = null;
  }

  if (!data || data.length === 0) {
    $("#inventory_units_chart").html(
      '<div class="text-center p-4"><p>No data available for chart</p></div>'
    );
    return;
  }

  var labels = data.map(function (item) {
    return item.product_name;
  });
  var series = data.map(function (item) {
    return parseInt(item.quantity);
  });

  // Calculate total units
  var totalUnits = series.reduce(function (sum, value) {
    return sum + value;
  }, 0);

  var options = {
    series: series,
    chart: {
      width: "100%",
      height: 400,
      type: "donut",
      events: {
        dataPointMouseEnter: function (event, chartContext, config) {
          var dataPointIndex = config.dataPointIndex;
          var value = series[dataPointIndex];
          var label = labels[dataPointIndex];
          var truncatedLabel = truncateText(label, 25);

          // Find and update custom center content
          var centerDiv = document.querySelector(
            "#inventory_units_chart .custom-center-content"
          );
          if (centerDiv) {
            centerDiv.innerHTML =
              '<div class="center-label" style="font-size: 16px; font-weight: 600; color: #373d3f; margin-bottom: 5px;">' +
              truncatedLabel +
              '</div><div class="center-value" style="font-size: 22px; font-weight: 400; color: #373d3f;">' +
              value.toLocaleString() +
              " units</div>";
          }
        },
        dataPointMouseLeave: function (event, chartContext, config) {
          // Reset to total when mouse leaves
          var centerDiv = document.querySelector(
            "#inventory_units_chart .custom-center-content"
          );
          if (centerDiv) {
            centerDiv.innerHTML =
              '<div class="center-label" style="font-size: 16px; font-weight: 600; color: #373d3f; margin-bottom: 5px;">Total Units</div><div class="center-value" style="font-size: 22px; font-weight: 400; color: #373d3f;">' +
              totalUnits.toLocaleString() +
              " units</div>";
          }
        },
      },
    },
    labels: labels,
    plotOptions: {
      pie: {
        donut: {
          size: "65%",
        },
      },
    },
    responsive: [
      {
        breakpoint: 480,
        options: {
          chart: {
            width: 200,
          },
          legend: {
            position: "bottom",
          },
        },
      },
    ],
    colors: [
      "#8e44adee",
      "#27ae60ee",
      "#e67e22ee",
      "#3498dbee",
      "#e74c3cee",
      "#f1c40fee",
      "#1abc9cee",
      "#ff69b4ee",
      "#7f8c8dee",
      "#9b59b6ee",
    ],
    legend: {
      position: "bottom",
      horizontalAlign: "center",
    },
    states: {
      hover: {
        filter: {
          type: "lighten",
          value: 0.1,
        },
      },
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return val + " units";
        },
      },
    },
  };

  inventoryUnitsChart = new ApexCharts(
    document.querySelector("#inventory_units_chart"),
    options
  );
  inventoryUnitsChart
    .render()
    .then(function () {
      // Add custom center content overlay
      var chartContainer = document.querySelector("#inventory_units_chart");
      var existingCenter = chartContainer.querySelector(
        ".custom-center-content"
      );
      if (!existingCenter) {
        var centerDiv = document.createElement("div");
        centerDiv.className = "custom-center-content";
        centerDiv.style.cssText = `
                position: absolute;
                top: 45%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                pointer-events: none;
                z-index: 10;
            `;
        centerDiv.innerHTML =
          '<div class="center-label" style="font-size: 16px; font-weight: 600; color: #373d3f; margin-bottom: 5px;">Total Units</div><div class="center-value" style="font-size: 22px; font-weight: 400; color: #373d3f;">' +
          totalUnits.toLocaleString() +
          " units</div>";
        chartContainer.style.position = "relative";
        chartContainer.appendChild(centerDiv);
      }
    })
    .catch(function (error) {
      $("#inventory_units_chart").html(
        '<div class="text-center p-4"><p>Error rendering chart</p></div>'
      );
    });
}

$(document).ready(function () {
  // Check if we're on the inventory report page
  if ($("#inventory_datepicker").length) {
    // Initialize the daterangepicker for inventory report
    $("#inventory_datepicker").attr({
      placeholder: "Select Date Range To Filter",
      autocomplete: "off",
    });

    $("#inventory_datepicker").on(
      "cancel.daterangepicker",
      function (ev, picker) {
        $(this).val("");
        $("#start_date").val("");
        $("#end_date").val("");
        // Auto-refresh table and chart when cleared
        if ($("#inventory_table").length) {
          $("#inventory_table").bootstrapTable("refresh");
        }
        loadInventoryChart();
      }
    );

    $("#inventory_datepicker").on(
      "apply.daterangepicker",
      function (ev, picker) {
        var drp = $("#inventory_datepicker").data("daterangepicker");
        $("#start_date").val(drp.startDate.format("YYYY-MM-DD"));
        $("#end_date").val(drp.endDate.format("YYYY-MM-DD"));
        $(this).val(
          picker.startDate.format("MM/DD/YYYY") +
          " - " +
          picker.endDate.format("MM/DD/YYYY")
        );

        // Auto-refresh table and chart when date range is applied
        if ($("#inventory_table").length) {
          $("#inventory_table").bootstrapTable("refresh");
        }
        loadInventoryChart();
      }
    );

    $("#inventory_datepicker").daterangepicker({
      showDropdowns: true,
      alwaysShowCalendars: true,
      autoUpdateInput: false,
      ranges: {
        Today: [moment(), moment()],
        Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
        "This Year": [moment().startOf("year"), moment().endOf("year")],
        "Last Year": [
          moment().subtract(1, "year").startOf("year"),
          moment().subtract(1, "year").endOf("year"),
        ],
      },
      locale: {
        cancelLabel: "Clear",
        applyLabel: "Apply",
      },
    });

    // Load initial chart
    setTimeout(function () {
      loadInventoryChart();
    }, 1000);

    // Clear filter button
    $("#clear_filter").on("click", function () {
      if ($("#start_date").val() == "" && $("#end_date").val() == "") {
        return;
      }
      $("#inventory_datepicker").val("");
      $("#start_date").val("");
      $("#end_date").val("");
      // Refresh table
      if ($("#inventory_table").length) {
        $("#inventory_table").bootstrapTable("refresh");
      }
      // Reload chart
      loadInventoryChart();
    });
  }
});

$(document).ready(function () {
  $("#base_price").on("input", function () {
    var value = $(this).val();
    if (
      value &&
      !isNaN(value) &&
      value.indexOf("e") === -1 &&
      value.indexOf("E") === -1
    ) {
      $("#special_price").val(value);
    }
  });

  $(document).on("input", ".variant-base-price", function () {
    var basePrice = $(this).val();

    // Only proceed if it's a valid number (not containing 'e' or other invalid chars)
    if (
      !basePrice ||
      isNaN(basePrice) ||
      basePrice.indexOf("e") !== -1 ||
      basePrice.indexOf("E") !== -1
    ) {
      return;
    }

    // Find the special price field in the same parent row
    var $parentRow = $(this).closest(".form-group.row, .row");
    var $specialPriceField = $parentRow.find(".variant-special-price");

    if ($specialPriceField.length > 0) {
      $specialPriceField.val(basePrice);
    } else {
    }
  });

  $(document).on(
    "keypress",
    '.variant-base-price, .variant-special-price, #base_price, #special_price, input[name="variant_price[]"], input[name="variant_special_price[]"]',
    function (e) {
      var charCode = e.which || e.keyCode;
      var char = String.fromCharCode(charCode);

      if (char.toLowerCase() === "e") {
        e.preventDefault();
        return false;
      }
      return true;
    }
  );
  $(document).on(
    "paste",
    '.variant-base-price, .variant-special-price, #base_price, #special_price, input[name="variant_price[]"], input[name="variant_special_price[]"]',
    function (e) {
      var $this = $(this);
      setTimeout(function () {
        var value = $this.val();
        if (value.toLowerCase().indexOf("e") !== -1) {
          value = value.replace(/[eE]/g, "");
          $this.val(value);
        }
        if (isNaN(value) || value === "") {
          $this.val("");
        }
      }, 10);
    }
  );
});

document.addEventListener("DOMContentLoaded", function () {
  // count existing rows
  let chargeIndex = document.querySelectorAll(
    "#custom_charges_wrapper .custom-charge-row"
  ).length;

  // Add new charge row
  const addCustomChargeBtn = document.getElementById("add_custom_charge");
  if (addCustomChargeBtn) {
    addCustomChargeBtn.addEventListener("click", function () {
      chargeIndex++;

      let row = `
            <div class="custom-charge-row border rounded p-3 mb-3 bg-light" id="charge_row_${chargeIndex}">
                <div class="row align-items-center g-3">

                    <!-- Name -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">Title</label>
                        <input type="text" name="custom_charges[${chargeIndex}][name]" 
                               class="form-control" placeholder="Charge Name" required>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1">Amount</label>
                        <input type="number" name="custom_charges[${chargeIndex}][amount]" 
                               class="form-control" step="0.01" min="0" 
                               placeholder="Amount" required>
                    </div>

                    <!-- Switches -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1 d-block">Applicable On</label>
                        <div class="d-flex flex-wrap gap-3 align-items-center bg-white rounded p-2 border">
                            
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" 
                                       name="custom_charges[${chargeIndex}][apply_pos]" checked>
                                <label class="form-check-label small">POS</label>
                            </div>

                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" 
                                       name="custom_charges[${chargeIndex}][apply_doorstep]" checked>
                                <label class="form-check-label small">Door</label>
                            </div>

                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" 
                                       name="custom_charges[${chargeIndex}][apply_pickup]" checked>
                                <label class="form-check-label small">Pickup</label>
                            </div>

                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" 
                                       name="custom_charges[${chargeIndex}][apply_digital]" checked>
                                <label class="form-check-label small">Digital product</label>
                            </div>

                            <div class="vr"></div>

                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" 
                                       name="custom_charges[${chargeIndex}][is_refundable]" checked>
                                <label class="form-check-label small">Refundable</label>
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="col-md-1 text-center">
                        <label class="form-label small fw-bold mb-1 d-block">Action</label>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-charge" data-id="${chargeIndex}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>

                </div>
            </div>`;



      document
        .getElementById("custom_charges_wrapper")
        .insertAdjacentHTML("beforeend", row);
    });
  }

  // Remove charge row
  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".remove-charge");
    if (btn) {
      const id = btn.getAttribute("data-id");
      const row = document.getElementById("charge_row_" + id);
      if (row) row.remove();
    }
  });
});

$(document).on("click", "#bulk_delete_products", function () {
  var rows = $("#products_table").bootstrapTable("getSelections");

  console.log(rows);

  if (rows.length === 0) {
    Swal.fire("Warning", "Please select at least one product", "warning");
    return;
  }

  var ids = rows.map(function (row) {
    return row.id;
  });

  Swal.fire({
    title: "Are you sure?",
    text: "Selected products will be permanently deleted!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete",
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: base_url + "admin/product/bulk_delete_products",
        data: { ids: ids },
        dataType: "json",
        success: function (response) {
          if (!response.error) {
            Swal.fire("Success", response.message, "success");
            setTimeout(function () {
              location.reload();
            }, 6000);
          } else {
            Swal.fire("Error", response.message, "error");
            setTimeout(function () {
              location.reload();
            }, 6000);
          }
        },
      });
    }
  });
});

//reset btn logic
function reset_filters() {
  // Reset datepicker text input + hidden values
  $("#datepicker").val("");
  $("#start_date").val("");
  $("#end_date").val("");

  // Reset all selects
  $("#order_status").val("");
  $("#delivery_boy").val("");
  $("#payment_method").val("");
  $("#order_type").val("");

  // If using a plugin like Select2, refresh it:
  // $('#order_status').trigger('change');
  // $('#delivery_boy').trigger('change');
  // $('#payment_method').trigger('change');
  // $('#order_type').trigger('change');

  // Reload default data (optional)
  status_date_wise_search();
}

// translate js
// translate
function googleTranslateElementInit() {
  new google.translate.TranslateElement(
    {
      pageLanguage: "en",
    },
    "google_translate_element"
  );
}

$(document).ready(function () {
  googleTranslateElementInit();
});

// select2 for categories

// $(document).ready(function() {
//     $('.select2').select2({
//         theme: 'bootstrap4',          // change to 'bootstrap-5' if you're using Bootstrap 5
//         placeholder: "Select category",
//         allowClear: true,
//         width: '100%'
//     });
// });
// $(document).ready(function() {
//     $('.select2').select2({
//         theme: 'bootstrap4',          // or 'bootstrap-5' if using Bootstrap 5
//         placeholder: function() {
//             return $(this).data('placeholder');
//         },
//         allowClear: true,
//         width: '100%'
//     });
// });

// quiry params for payment requests
function paymentRequestQueryParams(params) {
  return {
    offset: params.offset,
    limit: params.limit,
    search: params.search,
    sort: params.sort,
    order: params.order,
    status: $("#status_filter").val(),
    type: $("#type_filter").val(),
  };
}

/* Refresh table when filters change */
$("#status_filter, #type_filter").on("change", function () {
  $("#payment_request_table").bootstrapTable("refresh");
});

/* Reset filters */
$("#reset_filters").on("click", function () {
  $("#status_filter").val("");
  $("#type_filter").val("");
  $("#payment_request_table").bootstrapTable("refresh");
});
function promoCodeQueryParams(params) {
  return {
    offset: params.offset,
    limit: params.limit,
    search: params.search,
    sort: params.sort,
    order: params.order,
    status: $("#status_filter").val(),
    discount_type: $("#discount_type_filter").val(),
  };
}

$("#status_filter, #discount_type_filter").on("change", function () {
  $("#promo_code_table").bootstrapTable("refresh");
});

$("#reset_filters").on("click", function () {
  $("#status_filter").val("");
  $("#discount_type_filter").val("");
  $("#promo_code_table").bootstrapTable("refresh");
});
function sectionQueryParams(params) {
  return {
    offset: params.offset,
    limit: params.limit,
    search: params.search,
    sort: params.sort,
    order: params.order,
    product_type: $("#product_type_filter").val(),
  };
}

$("#product_type_filter").on("change", function () {
  $("#section_table").bootstrapTable("refresh");
});

$("#reset_filters").on("click", function () {
  $("#product_type_filter").val("");
  $("#section_table").bootstrapTable("refresh");
});
function transactionQueryParams(params) {
  return {
    offset: params.offset,
    limit: params.limit,
    search: params.search,
    sort: params.sort,
    order: params.order,
    status: $("#status_filter").val(),
    transaction_type: "transaction",
  };
}

$("#status_filter").on("change", function () {
  $("#transaction_table").bootstrapTable("refresh");
});

$("#status_filter, #type_filter").on("change", function () {
  $("#transaction_table").bootstrapTable("refresh");
});
// AI Genersted Descriptions Settings Save
"use strict";

/* ---------------------------------------------------------------------------------------------------------------------------------------------------
   Common Functions & Events
   --------------------------------------------------------------------------------------------------------------------------------------------------- */

$.ajaxSetup({
  beforeSend: function (jqXHR, settings) {
    if (settings.type && settings.type.toUpperCase() === "POST") {
      if (settings.data instanceof FormData) {
        settings.data.append(csrfName, csrfHash);
      } else if (typeof settings.data === "string") {
        settings.data += (settings.data ? "&" : "") +
          encodeURIComponent(csrfName) + "=" + encodeURIComponent(csrfHash);
      } else {
        settings.data = settings.data || {};
        settings.data[csrfName] = csrfHash;
      }
    }
  }
});

$(document).ready(function () {
  $("#loading").hide();
  $(".no_of_users").removeClass("d-none");

  // ... your existing pagination code, zipcode remove, offer discount toggle, etc. ...

  // AI Generated Descriptions - Improved Version
  // =============================================

  // Toggle Custom Prompt Box
  $(document).on('click', '.toggle-custom-prompt-btn', function () {
    var targetId = $(this).data('target');
    $('#' + targetId).toggleClass('d-none');
  });

  // Load Suggested Prompts
  $(document).on('click', '.suggest-prompts-btn', function () {
    var btn = $(this);
    var field = $(this).data('field');
    var title = $('#pro_input_text').val() || $('#pro_input_name').val();
    var suggestionsSelect = $('#suggestions-' + field);

    if (!title || !title.trim()) {
      iziToast.warning({
        title: 'Warning',
        message: 'Please enter Product Name first',
        position: 'topRight'
      });
      return;
    }

    var originalContent = btn.html();
    btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

    var formData = new FormData();
    formData.append('title', title);

    $.ajax({
      url: base_url + 'admin/product/suggest_product_prompts',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (data) {
        btn.html(originalContent).prop('disabled', false);

        if (data.error) {
          iziToast.error({
            title: 'Error',
            message: data.message || 'Failed to get prompt suggestions',
            position: 'topRight'
          });
        }
        else if (data.prompts && data.prompts.length > 0) {
          let options = '<option value="">Select a suggested prompt...</option>';
          data.prompts.forEach(prompt => {
            options += `<option value="${prompt}">${prompt.substring(0, 100)}${prompt.length > 100 ? '...' : ''}</option>`;
          });
          suggestionsSelect.html(options).removeClass('d-none');
        }
      },
      error: function () {
        btn.html(originalContent).prop('disabled', false);
        iziToast.error({
          title: 'Error',
          message: 'Failed to load prompt suggestions',
          position: 'topRight'
        });
      }
    });
  });

  // Use selected suggested prompt
  $(document).on('change', '.prompt-suggestions', function () {
    var targetId = $(this).data('target');
    var selected = $(this).val();
    if (selected) {
      $('#' + targetId).val(selected);
    }
  });

  // Generate AI Content (Main button handler)
  $(document).on('click', '.generate-ai-btn', function () {
    var btn = $(this);
    var field = $(this).data('field');
    var title = $('#pro_input_text').val() || $('#pro_input_name').val();

    var customPromptInput = $('#prompt-input-' + field);
    var useCustomPrompt = false;
    var customPrompt = '';

    // Check if custom prompt is visible and filled
    if (customPromptInput.length &&
      !customPromptInput.closest('div').hasClass('d-none') &&
      customPromptInput.val() && customPromptInput.val().trim() !== '') {
      useCustomPrompt = true;
      customPrompt = customPromptInput.val().trim();
    }

    if (!title || !title.trim()) {
      iziToast.warning({
        title: 'Warning',
        message: 'Please enter Product Name first',
        position: 'topRight'
      });
      return;
    }

    var originalContent = btn.html();
    btn.html('<span class="spinner-border spinner-border-sm"></span> Generating...')
      .prop('disabled', true);

    var formData = new FormData();
    formData.append('title', title);
    formData.append('field_type', field);
    formData.append('use_custom_prompt', useCustomPrompt ? 1 : 0);
    formData.append('custom_prompt', customPrompt);

    $.ajax({
      url: base_url + 'admin/product/generate_product_description',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',

      success: function (data) {
        btn.html(originalContent).prop('disabled', false);

        if (data.error) {
          iziToast.error({
            title: 'Error',
            message: data.message || 'Failed to generate description',
            position: 'topRight'
          });
          console.error('AI Generation Error:', data.message);
        }
        else {
          var generatedText = data.data.generated_text || '';
          console.log(data);
          if (field === 'short_description') {
            $('#short_description').val(generatedText);
            iziToast.success({
              title: 'Success',
              message: 'Short description generated!',
              position: 'topRight'
            });
            console.log('Short description updated successfully');
          }
          else {
            // For rich text editors (main description)
            console.log('Updating rich editor field:', field);

            // Check if TinyMCE editor exists
            if (tinymce.get(field)) {
              tinymce.get(field).setContent(generatedText);
              iziToast.success({
                title: 'Success',
                message: 'Description generated!',
                position: 'topRight'
              });
            }
            else {
              // Fall back to hugeRTE if TinyMCE is not available
              var editor = hugeRTE.get(field);
              if (editor) {
                editor.setContent(generatedText);
                editor.save();
                iziToast.success({
                  title: 'Success',
                  message: 'Description generated!',
                  position: 'topRight'
                });
              }
            }
          }
        }
      },

      error: function (xhr, status, error) {
        btn.html(originalContent).prop('disabled', false);
        iziToast.error({
          title: 'Error',
          message: 'Failed to connect to AI service',
          position: 'topRight'
        });
        console.error('AI AJAX Error:', error);
      }
    });
  });


});

$(document).ready(function () {
  $(".system_setting_form").on("click", function (event) {
    event.preventDefault();

    // ---------------------------------------------------------
    // Custom Validation: Check for Duplicate Custom Charges
    // (ONLY name + amount, ignore settings)
    // ---------------------------------------------------------
    let chargeSignatures = [];
    let duplicateFound = false;

    $('#custom_charges_wrapper .custom-charge-row').each(function () {
      let row = $(this);

      let nameInput = row.find('input[name*="[name]"]');
      let amountInput = row.find('input[name*="[amount]"]');

      // Safety check
      if (nameInput.length === 0 || amountInput.length === 0) return;

      let name = nameInput.val().trim().toLowerCase();
      let amount = amountInput.val().trim();

      // Skip empty rows
      if (name === '' || amount === '') return;

      // Signature ONLY based on name + amount
      let signature = `${name}|${amount}`;

      if (chargeSignatures.includes(signature)) {
        duplicateFound = true;

        iziToast.error({
          message: `Duplicate custom charge found: "${nameInput.val()}" with amount ${amount}. Please remove duplicates.`,
          position: 'topRight'
        });

        return false; // break .each()
      }

      chargeSignatures.push(signature);
    });

    if (duplicateFound) {
      return false; // stop form submit
    }
    // ---------------------------------------------------------

    var form = $("#system_setting_form");
    var formData = new FormData(form[0]);
    formData.append(csrfName, csrfHash);

    $.ajax({
      url: form.attr("action"),
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        csrfName = response["csrfName"];
        csrfHash = response["csrfHash"];

        if (response.error == false) {
          iziToast.success({
            message: response.message,
          });
          setTimeout(function () {
            location.reload();
          }, 1500);
        } else {
          iziToast.error({
            message: response.message,
          });
        }
      }
    });
  });
});