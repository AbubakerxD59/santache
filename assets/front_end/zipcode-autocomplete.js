/**
 * ZIP + City autocomplete for address / checkout forms.
 * Expects:
 *   window.ADDRESS_ZIPCODES = [{ zipcode, city_id, city_name }, ...]
 *   window.ADDRESS_CITIES = [{ id, name }, ...]
 */
(function (window) {
  "use strict";

  function boot($) {
    function normalize(value) {
      return String(value || "").trim();
    }

    function findZipMatch(zipcodes, value) {
      var q = normalize(value);
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
      var q = normalize(value);
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

    function findCityMatch(cities, value) {
      var q = normalize(value).toLowerCase();
      if (!q) {
        return null;
      }
      for (var i = 0; i < cities.length; i++) {
        if (String(cities[i].name || "").trim().toLowerCase() === q) {
          return cities[i];
        }
      }
      return null;
    }

    function findCityById(cities, cityId) {
      if (!cityId) {
        return null;
      }
      for (var i = 0; i < cities.length; i++) {
        if (String(cities[i].id) === String(cityId)) {
          return cities[i];
        }
      }
      return null;
    }

    function filterCities(cities, value, limit) {
      var q = normalize(value).toLowerCase();
      if (!q) {
        return [];
      }
      limit = limit || 12;
      var matches = [];
      for (var i = 0; i < cities.length; i++) {
        var name = String(cities[i].name || "");
        if (name.toLowerCase().indexOf(q) === 0) {
          matches.push(cities[i]);
          if (matches.length >= limit) {
            break;
          }
        }
      }
      return matches;
    }

    function bindSuggestions($input, $list, getMatches, onSelect, itemClass, getLabel, getDataAttrs) {
      function hide() {
        $list.hide().empty();
      }

      function show(matches) {
        $list.empty();
        if (!matches.length) {
          hide();
          return;
        }
        matches.forEach(function (item) {
          var attrs = {
            type: "button",
            class: "list-group-item list-group-item-action " + itemClass,
            text: getLabel(item),
          };
          var data = getDataAttrs(item);
          Object.keys(data).forEach(function (key) {
            attrs["data-" + key] = data[key];
          });
          $list.append($("<button/>", attrs));
        });
        $list.show();
      }

      $input.on("input", function () {
        show(getMatches($input.val()));
      });

      $input.on("focus", function () {
        var value = normalize($input.val());
        if (value) {
          show(getMatches(value));
        }
      });

      $list.on("mousedown", "." + itemClass, function (e) {
        e.preventDefault();
        onSelect($(this));
        hide();
      });

      $input.on("blur", function () {
        setTimeout(hide, 150);
      });

      return { hide: hide, show: show };
    }

    function bindPair(options) {
      var zipcodes = options.zipcodes || window.ADDRESS_ZIPCODES || [];
      var cities = options.cities || window.ADDRESS_CITIES || [];
      var $zip = $(options.zipInput);
      var $city = $(options.cityInput);
      var $cityId = $(options.cityIdInput);
      var $zipList = $(options.zipSuggestionsList);
      var $cityList = $(options.citySuggestionsList);

      if (!$zip.length || !$city.length || !$zipList.length || !$cityList.length) {
        return;
      }

      function setCity(cityId, cityName) {
        if (cityName) {
          $city.val(cityName);
        } else if (cityId) {
          var city = findCityById(cities, cityId);
          if (city) {
            $city.val(city.name);
            cityId = city.id;
          }
        }
        $cityId.val(cityId ? String(cityId) : "");
      }

      function syncCityIdFromName() {
        var match = findCityMatch(cities, $city.val());
        $cityId.val(match ? String(match.id) : "");
      }

      bindSuggestions(
        $zip,
        $zipList,
        function (value) {
          return filterZipcodes(zipcodes, value);
        },
        function ($btn) {
          $zip.val($btn.data("zipcode"));
          setCity($btn.data("city-id"), $btn.data("city-name"));
          $zip.trigger("blur");
        },
        "zipcode-suggestion-item",
        function (item) {
          return item.zipcode;
        },
        function (item) {
          return {
            zipcode: item.zipcode,
            "city-id": item.city_id || "",
            "city-name": item.city_name || "",
          };
        }
      );

      bindSuggestions(
        $city,
        $cityList,
        function (value) {
          return filterCities(cities, value);
        },
        function ($btn) {
          setCity($btn.data("city-id"), $btn.data("city-name"));
          $city.trigger("blur");
        },
        "city-suggestion-item",
        function (item) {
          return item.name;
        },
        function (item) {
          return {
            "city-id": item.id || "",
            "city-name": item.name || "",
          };
        }
      );

      $zip.on("input", function () {
        var match = findZipMatch(zipcodes, $zip.val());
        if (match && normalize($zip.val()) === String(match.zipcode)) {
          setCity(match.city_id, match.city_name);
        }
      });

      $zip.on("blur", function () {
        var match = findZipMatch(zipcodes, $zip.val());
        if (match) {
          setCity(match.city_id, match.city_name);
        }
      });

      $city.on("input", function () {
        syncCityIdFromName();
      });

      $city.on("blur", function () {
        syncCityIdFromName();
      });

      return {
        setCityByIdOrName: function (cityId, cityName) {
          if (cityId && findCityById(cities, cityId)) {
            setCity(cityId);
            return;
          }
          if (cityName) {
            var match = findCityMatch(cities, cityName);
            if (match) {
              setCity(match.id, match.name);
            } else {
              setCity("", cityName);
            }
          }
        },
        setZipcode: function (zip) {
          $zip.val(zip || "");
          var match = findZipMatch(zipcodes, zip);
          if (match) {
            setCity(match.city_id, match.city_name);
          }
        },
      };
    }

    window.AddressZipcodeAutocomplete = {
      bind: bindPair,
      findZipMatch: findZipMatch,
      findCityMatch: findCityMatch,
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
