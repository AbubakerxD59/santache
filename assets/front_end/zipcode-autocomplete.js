/**
 * ZIP Code autocomplete + city auto-select for address / checkout forms.
 * Expects window.ADDRESS_ZIPCODES = [{ zipcode, city_id, city_name }, ...]
 */
(function (window) {
  "use strict";

  function boot($) {
    function normalizeZip(value) {
      return String(value || "").trim();
    }

    function findZipMatch(zipcodes, value) {
      var q = normalizeZip(value);
      if (!q) {
        return null;
      }
      var exact = null;
      var base = q.split("-")[0];
      for (var i = 0; i < zipcodes.length; i++) {
        var z = String(zipcodes[i].zipcode || "");
        if (z === q) {
          return zipcodes[i];
        }
        if (!exact && z === base) {
          exact = zipcodes[i];
        }
      }
      return exact;
    }

    function filterZipcodes(zipcodes, value, limit) {
      var q = normalizeZip(value);
      if (!q) {
        return [];
      }
      limit = limit || 12;
      var matches = [];
      for (var i = 0; i < zipcodes.length; i++) {
        var z = String(zipcodes[i].zipcode || "");
        if (z.indexOf(q) === 0) {
          matches.push(zipcodes[i]);
          if (matches.length >= limit) {
            break;
          }
        }
      }
      return matches;
    }

    function syncCityName($citySelect) {
      var $opt = $citySelect.find("option:selected");
      var name = $opt.length && $opt.val() ? $opt.text().trim() : "";
      var $nameField = $citySelect
        .closest("form")
        .find('input[name="city_name"]');
      if ($nameField.length) {
        $nameField.val(name);
      }
    }

    function selectCity($citySelect, cityId) {
      if (!cityId) {
        return;
      }
      $citySelect.val(String(cityId));
      syncCityName($citySelect);
      $citySelect.trigger("change");
    }

    function bindPair(options) {
      var zipcodes = options.zipcodes || window.ADDRESS_ZIPCODES || [];
      var $zip = $(options.zipInput);
      var $city = $(options.citySelect);
      var $list = $(options.suggestionsList);

      if (!$zip.length || !$city.length || !$list.length) {
        return;
      }

      function hideSuggestions() {
        $list.hide().empty();
      }

      function showSuggestions(matches) {
        $list.empty();
        if (!matches.length) {
          hideSuggestions();
          return;
        }
        matches.forEach(function (item) {
          var $item = $("<button/>", {
            type: "button",
            class:
              "list-group-item list-group-item-action zipcode-suggestion-item",
            text: item.zipcode,
            "data-zipcode": item.zipcode,
            "data-city-id": item.city_id || "",
          });
          $list.append($item);
        });
        $list.show();
      }

      function applyZipSelection(zipcode, cityId) {
        $zip.val(zipcode);
        if (cityId) {
          selectCity($city, cityId);
        }
        hideSuggestions();
        $zip.trigger("blur");
      }

      $city.on("change", function () {
        syncCityName($city);
      });
      syncCityName($city);

      $zip.on("input", function () {
        var value = normalizeZip($zip.val());
        showSuggestions(filterZipcodes(zipcodes, value));
        var match = findZipMatch(zipcodes, value);
        if (match && match.city_id && value === String(match.zipcode)) {
          selectCity($city, match.city_id);
        }
      });

      $zip.on("focus", function () {
        var value = normalizeZip($zip.val());
        if (value) {
          showSuggestions(filterZipcodes(zipcodes, value));
        }
      });

      $list.on("mousedown", ".zipcode-suggestion-item", function (e) {
        e.preventDefault();
        applyZipSelection($(this).data("zipcode"), $(this).data("city-id"));
      });

      $zip.on("blur", function () {
        setTimeout(hideSuggestions, 150);
        var match = findZipMatch(zipcodes, $zip.val());
        if (match && match.city_id) {
          selectCity($city, match.city_id);
        }
      });

      return {
        setCityByIdOrName: function (cityId, cityName) {
          if (cityId && $city.find('option[value="' + cityId + '"]').length) {
            selectCity($city, cityId);
            return;
          }
          if (cityName) {
            var matched = false;
            $city.find("option").each(function () {
              if (
                $(this).text().trim().toLowerCase() ===
                String(cityName).trim().toLowerCase()
              ) {
                selectCity($city, $(this).val());
                matched = true;
                return false;
              }
            });
            if (!matched) {
              $city.val("");
              syncCityName($city);
            }
          }
        },
        setZipcode: function (zip) {
          $zip.val(zip || "");
          var match = findZipMatch(zipcodes, zip);
          if (match && match.city_id) {
            selectCity($city, match.city_id);
          }
        },
      };
    }

    window.AddressZipcodeAutocomplete = {
      bind: bindPair,
      findZipMatch: findZipMatch,
    };
  }

  function waitForJQuery() {
    if (window.jQuery) {
      boot(window.jQuery);
    } else {
      setTimeout(waitForJQuery, 50);
    }
  }

  waitForJQuery();
})(window);
