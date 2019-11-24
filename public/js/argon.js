/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/argon/assets/js/argon.js":
/*!***********************************************!*\
  !*** ./resources/js/argon/assets/js/argon.js ***!
  \***********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vendor_headroom_headroom_min__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../vendor/headroom/headroom.min */ "./resources/js/argon/assets/vendor/headroom/headroom.min.js");
/* harmony import */ var _vendor_headroom_headroom_min__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_vendor_headroom_headroom_min__WEBPACK_IMPORTED_MODULE_0__);

"use strict";

$(document).ready(function () {
  // Collapse navigation
  $('.navbar-main .collapse').on('hide.bs.collapse', function () {
    var $this = $(this);
    $this.addClass('collapsing-out');
  });
  $('.navbar-main .collapse').on('hidden.bs.collapse', function () {
    var $this = $(this);
    $this.removeClass('collapsing-out');
  });
  $('.navbar-main .dropdown').on('hide.bs.dropdown', function () {
    var $this = $(this).find('.dropdown-menu');
    $this.addClass('close');
    setTimeout(function () {
      $this.removeClass('close');
    }, 200);
  }); // Headroom - show/hide navbar on scroll

  if ($('.headroom')[0]) {
    var headroom = new _vendor_headroom_headroom_min__WEBPACK_IMPORTED_MODULE_0___default.a(document.querySelector("#navbar-main"), {
      offset: 300,
      tolerance: {
        up: 30,
        down: 30
      }
    });
    headroom.init();
  } // Datepicker


  $('.datepicker')[0] && $('.datepicker').each(function () {
    $('.datepicker').datepicker({
      disableTouchKeyboard: true,
      autoclose: false
    });
  }); // Tooltip

  $('[data-toggle="tooltip"]').tooltip(); // Popover

  $('[data-toggle="popover"]').each(function () {
    var popoverClass = '';

    if ($(this).data('color')) {
      popoverClass = 'popover-' + $(this).data('color');
    }

    $(this).popover({
      trigger: 'focus',
      template: '<div class="popover ' + popoverClass + '" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    });
  }); // Additional .focus class on form-groups

  $('.form-control').on('focus blur', function (e) {
    $(this).parents('.form-group').toggleClass('focused', e.type === 'focus' || this.value.length > 0);
  }).trigger('blur'); // NoUI Slider

  if ($(".input-slider-container")[0]) {
    $('.input-slider-container').each(function () {
      var slider = $(this).find('.input-slider');
      var sliderId = slider.attr('id');
      var minValue = slider.data('range-value-min');
      var maxValue = slider.data('range-value-max');
      var sliderValue = $(this).find('.range-slider-value');
      var sliderValueId = sliderValue.attr('id');
      var startValue = sliderValue.data('range-value-low');
      var c = document.getElementById(sliderId),
          d = document.getElementById(sliderValueId);
      noUiSlider.create(c, {
        start: [parseInt(startValue)],
        connect: [true, false],
        //step: 1000,
        range: {
          'min': [parseInt(minValue)],
          'max': [parseInt(maxValue)]
        }
      });
      c.noUiSlider.on('update', function (a, b) {
        d.textContent = a[b];
      });
    });
  }

  if ($("#input-slider-range")[0]) {
    var c = document.getElementById("input-slider-range"),
        d = document.getElementById("input-slider-range-value-low"),
        e = document.getElementById("input-slider-range-value-high"),
        f = [d, e];
    noUiSlider.create(c, {
      start: [parseInt(d.getAttribute('data-range-value-low')), parseInt(e.getAttribute('data-range-value-high'))],
      connect: !0,
      range: {
        min: parseInt(c.getAttribute('data-range-value-min')),
        max: parseInt(c.getAttribute('data-range-value-max'))
      }
    }), c.noUiSlider.on("update", function (a, b) {
      f[b].textContent = a[b];
    });
  } // When in viewport


  $('[data-toggle="on-screen"]')[0] && $('[data-toggle="on-screen"]').onScreen({
    container: window,
    direction: 'vertical',
    doIn: function doIn() {//alert();
    },
    doOut: function doOut() {// Do something to the matched elements as they get off scren
    },
    tolerance: 200,
    throttle: 50,
    toggleClass: 'on-screen',
    debug: false
  }); // Scroll to anchor with scroll animation

  $('[data-toggle="scroll"]').on('click', function (event) {
    var hash = $(this).attr('href');
    var offset = $(this).data('offset') ? $(this).data('offset') : 0; // Animate scroll to the selected section

    $('html, body').stop(true, true).animate({
      scrollTop: $(hash).offset().top - offset
    }, 600);
    event.preventDefault();
  });
});

/***/ }),

/***/ "./resources/js/argon/assets/vendor/headroom/headroom.min.js":
/*!*******************************************************************!*\
  !*** ./resources/js/argon/assets/vendor/headroom/headroom.min.js ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/*!
 * headroom.js v0.9.4 - Give your page some headroom. Hide your header until you need it
 * Copyright (c) 2017 Nick Williams - http://wicky.nillia.ms/headroom.js
 * License: MIT
 */
!function (a, b) {
  "use strict";

   true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (b),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
}(this, function () {
  "use strict";

  function a(a) {
    this.callback = a, this.ticking = !1;
  }

  function b(a) {
    return a && "undefined" != typeof window && (a === window || a.nodeType);
  }

  function c(a) {
    if (arguments.length <= 0) throw new Error("Missing arguments in extend function");
    var d,
        e,
        f = a || {};

    for (e = 1; e < arguments.length; e++) {
      var g = arguments[e] || {};

      for (d in g) {
        "object" != _typeof(f[d]) || b(f[d]) ? f[d] = f[d] || g[d] : f[d] = c(f[d], g[d]);
      }
    }

    return f;
  }

  function d(a) {
    return a === Object(a) ? a : {
      down: a,
      up: a
    };
  }

  function e(a, b) {
    b = c(b, e.options), this.lastKnownScrollY = 0, this.elem = a, this.tolerance = d(b.tolerance), this.classes = b.classes, this.offset = b.offset, this.scroller = b.scroller, this.initialised = !1, this.onPin = b.onPin, this.onUnpin = b.onUnpin, this.onTop = b.onTop, this.onNotTop = b.onNotTop, this.onBottom = b.onBottom, this.onNotBottom = b.onNotBottom;
  }

  var f = {
    bind: !!function () {}.bind,
    classList: "classList" in document.documentElement,
    rAF: !!(window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame)
  };
  return window.requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame, a.prototype = {
    constructor: a,
    update: function update() {
      this.callback && this.callback(), this.ticking = !1;
    },
    requestTick: function requestTick() {
      this.ticking || (requestAnimationFrame(this.rafCallback || (this.rafCallback = this.update.bind(this))), this.ticking = !0);
    },
    handleEvent: function handleEvent() {
      this.requestTick();
    }
  }, e.prototype = {
    constructor: e,
    init: function init() {
      if (e.cutsTheMustard) return this.debouncer = new a(this.update.bind(this)), this.elem.classList.add(this.classes.initial), setTimeout(this.attachEvent.bind(this), 100), this;
    },
    destroy: function destroy() {
      var a = this.classes;
      this.initialised = !1;

      for (var b in a) {
        a.hasOwnProperty(b) && this.elem.classList.remove(a[b]);
      }

      this.scroller.removeEventListener("scroll", this.debouncer, !1);
    },
    attachEvent: function attachEvent() {
      this.initialised || (this.lastKnownScrollY = this.getScrollY(), this.initialised = !0, this.scroller.addEventListener("scroll", this.debouncer, !1), this.debouncer.handleEvent());
    },
    unpin: function unpin() {
      var a = this.elem.classList,
          b = this.classes;
      !a.contains(b.pinned) && a.contains(b.unpinned) || (a.add(b.unpinned), a.remove(b.pinned), this.onUnpin && this.onUnpin.call(this));
    },
    pin: function pin() {
      var a = this.elem.classList,
          b = this.classes;
      a.contains(b.unpinned) && (a.remove(b.unpinned), a.add(b.pinned), this.onPin && this.onPin.call(this));
    },
    top: function top() {
      var a = this.elem.classList,
          b = this.classes;
      a.contains(b.top) || (a.add(b.top), a.remove(b.notTop), this.onTop && this.onTop.call(this));
    },
    notTop: function notTop() {
      var a = this.elem.classList,
          b = this.classes;
      a.contains(b.notTop) || (a.add(b.notTop), a.remove(b.top), this.onNotTop && this.onNotTop.call(this));
    },
    bottom: function bottom() {
      var a = this.elem.classList,
          b = this.classes;
      a.contains(b.bottom) || (a.add(b.bottom), a.remove(b.notBottom), this.onBottom && this.onBottom.call(this));
    },
    notBottom: function notBottom() {
      var a = this.elem.classList,
          b = this.classes;
      a.contains(b.notBottom) || (a.add(b.notBottom), a.remove(b.bottom), this.onNotBottom && this.onNotBottom.call(this));
    },
    getScrollY: function getScrollY() {
      return void 0 !== this.scroller.pageYOffset ? this.scroller.pageYOffset : void 0 !== this.scroller.scrollTop ? this.scroller.scrollTop : (document.documentElement || document.body.parentNode || document.body).scrollTop;
    },
    getViewportHeight: function getViewportHeight() {
      return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    },
    getElementPhysicalHeight: function getElementPhysicalHeight(a) {
      return Math.max(a.offsetHeight, a.clientHeight);
    },
    getScrollerPhysicalHeight: function getScrollerPhysicalHeight() {
      return this.scroller === window || this.scroller === document.body ? this.getViewportHeight() : this.getElementPhysicalHeight(this.scroller);
    },
    getDocumentHeight: function getDocumentHeight() {
      var a = document.body,
          b = document.documentElement;
      return Math.max(a.scrollHeight, b.scrollHeight, a.offsetHeight, b.offsetHeight, a.clientHeight, b.clientHeight);
    },
    getElementHeight: function getElementHeight(a) {
      return Math.max(a.scrollHeight, a.offsetHeight, a.clientHeight);
    },
    getScrollerHeight: function getScrollerHeight() {
      return this.scroller === window || this.scroller === document.body ? this.getDocumentHeight() : this.getElementHeight(this.scroller);
    },
    isOutOfBounds: function isOutOfBounds(a) {
      var b = a < 0,
          c = a + this.getScrollerPhysicalHeight() > this.getScrollerHeight();
      return b || c;
    },
    toleranceExceeded: function toleranceExceeded(a, b) {
      return Math.abs(a - this.lastKnownScrollY) >= this.tolerance[b];
    },
    shouldUnpin: function shouldUnpin(a, b) {
      var c = a > this.lastKnownScrollY,
          d = a >= this.offset;
      return c && d && b;
    },
    shouldPin: function shouldPin(a, b) {
      var c = a < this.lastKnownScrollY,
          d = a <= this.offset;
      return c && b || d;
    },
    update: function update() {
      var a = this.getScrollY(),
          b = a > this.lastKnownScrollY ? "down" : "up",
          c = this.toleranceExceeded(a, b);
      this.isOutOfBounds(a) || (a <= this.offset ? this.top() : this.notTop(), a + this.getViewportHeight() >= this.getScrollerHeight() ? this.bottom() : this.notBottom(), this.shouldUnpin(a, c) ? this.unpin() : this.shouldPin(a, c) && this.pin(), this.lastKnownScrollY = a);
    }
  }, e.options = {
    tolerance: {
      up: 0,
      down: 0
    },
    offset: 0,
    scroller: window,
    classes: {
      pinned: "headroom--pinned",
      unpinned: "headroom--unpinned",
      top: "headroom--top",
      notTop: "headroom--not-top",
      bottom: "headroom--bottom",
      notBottom: "headroom--not-bottom",
      initial: "headroom"
    }
  }, e.cutsTheMustard = "undefined" != typeof f && f.rAF && f.bind && f.classList, e;
});

/***/ }),

/***/ 1:
/*!*****************************************************!*\
  !*** multi ./resources/js/argon/assets/js/argon.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Applications/MAMP/htdocs/skeleton2/resources/js/argon/assets/js/argon.js */"./resources/js/argon/assets/js/argon.js");


/***/ })

/******/ });