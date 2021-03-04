/*


   Magic Scroll v2.0.26 
   Copyright 2016 Magic Toolbox
   Buy a license: https://www.magictoolbox.com/magicscroll/
   License agreement: https://www.magictoolbox.com/license/


*/

window.MagicScroll = (function() {
  var s, u;
  s = u = (function() {
    var Q = {
      version: "v3.3-b5",
      UUID: 0,
      storage: {},
      $uuid: function(U) {
        return (U.$J_UUID || (U.$J_UUID = ++K.UUID))
      },
      getStorage: function(U) {
        return (K.storage[U] || (K.storage[U] = {}))
      },
      $F: function() {},
      $false: function() {
        return false
      },
      $true: function() {
        return true
      },
      stylesId: "mjs-" + Math.floor(Math.random() * new Date().getTime()),
      defined: function(U) {
        return (undefined != U)
      },
      ifndef: function(V, U) {
        return (undefined != V) ? V : U
      },
      exists: function(U) {
        return !!(U)
      },
      jTypeOf: function(U) {
        if (!K.defined(U)) {
          return false
        }
        if (U.$J_TYPE) {
          return U.$J_TYPE
        }
        if (!!U.nodeType) {
          if (1 == U.nodeType) {
            return "element"
          }
          if (3 == U.nodeType) {
            return "textnode"
          }
        }
        if (U.length && U.item) {
          return "collection"
        }
        if (U.length && U.callee) {
          return "arguments"
        }
        if ((U instanceof window.Object || U instanceof window.Function) && U.constructor === K.Class) {
          return "class"
        }
        if (U instanceof window.Array) {
          return "array"
        }
        if (U instanceof window.Function) {
          return "function"
        }
        if (U instanceof window.String) {
          return "string"
        }
        if (K.browser.trident) {
          if (K.defined(U.cancelBubble)) {
            return "event"
          }
        } else {
          if (U === window.event || U.constructor == window.Event || U.constructor == window.MouseEvent || U.constructor == window.UIEvent || U.constructor == window.KeyboardEvent || U.constructor == window.KeyEvent) {
            return "event"
          }
        }
        if (U instanceof window.Date) {
          return "date"
        }
        if (U instanceof window.RegExp) {
          return "regexp"
        }
        if (U === window) {
          return "window"
        }
        if (U === document) {
          return "document"
        }
        return typeof(U)
      },
      extend: function(Z, Y) {
        if (!(Z instanceof window.Array)) {
          Z = [Z]
        }
        if (!Y) {
          return Z[0]
        }
        for (var X = 0, V = Z.length; X < V; X++) {
          if (!K.defined(Z)) {
            continue
          }
          for (var W in Y) {
            if (!Object.prototype.hasOwnProperty.call(Y, W)) {
              continue
            }
            try {
              Z[X][W] = Y[W]
            } catch (U) {}
          }
        }
        return Z[0]
      },
      implement: function(Y, X) {
        if (!(Y instanceof window.Array)) {
          Y = [Y]
        }
        for (var W = 0, U = Y.length; W < U; W++) {
          if (!K.defined(Y[W])) {
            continue
          }
          if (!Y[W].prototype) {
            continue
          }
          for (var V in (X || {})) {
            if (!Y[W].prototype[V]) {
              Y[W].prototype[V] = X[V]
            }
          }
        }
        return Y[0]
      },
      nativize: function(W, V) {
        if (!K.defined(W)) {
          return W
        }
        for (var U in (V || {})) {
          if (!W[U]) {
            W[U] = V[U]
          }
        }
        return W
      },
      $try: function() {
        for (var V = 0, U = arguments.length; V < U; V++) {
          try {
            return arguments[V]()
          } catch (W) {}
        }
        return null
      },
      $A: function(W) {
        if (!K.defined(W)) {
          return K.$([])
        }
        if (W.toArray) {
          return K.$(W.toArray())
        }
        if (W.item) {
          var V = W.length || 0,
            U = new Array(V);
          while (V--) {
            U[V] = W[V]
          }
          return K.$(U)
        }
        return K.$(Array.prototype.slice.call(W))
      },
      now: function() {
        return new Date().getTime()
      },
      detach: function(Y) {
        var W;
        switch (K.jTypeOf(Y)) {
          case "object":
            W = {};
            for (var X in Y) {
              W[X] = K.detach(Y[X])
            }
            break;
          case "array":
            W = [];
            for (var V = 0, U = Y.length; V < U; V++) {
              W[V] = K.detach(Y[V])
            }
            break;
          default:
            return Y
        }
        return K.$(W)
      },
      $: function(W) {
        var U = true;
        if (!K.defined(W)) {
          return null
        }
        if (W.$J_EXT) {
          return W
        }
        switch (K.jTypeOf(W)) {
          case "array":
            W = K.nativize(W, K.extend(K.Array, {
              $J_EXT: K.$F
            }));
            W.jEach = W.forEach;
            return W;
            break;
          case "string":
            var V = document.getElementById(W);
            if (K.defined(V)) {
              return K.$(V)
            }
            return null;
            break;
          case "window":
          case "document":
            K.$uuid(W);
            W = K.extend(W, K.Doc);
            break;
          case "element":
            K.$uuid(W);
            W = K.extend(W, K.Element);
            break;
          case "event":
            W = K.extend(W, K.Event);
            break;
          case "textnode":
          case "function":
          case "array":
          case "date":
          default:
            U = false;
            break
        }
        if (U) {
          return K.extend(W, {
            $J_EXT: K.$F
          })
        } else {
          return W
        }
      },
      $new: function(U, W, V) {
        return K.$(K.doc.createElement(U)).setProps(W || {}).jSetCss(V || {})
      },
      addCSS: function(V, X, ab) {
        var Y, W, Z, aa = [],
          U = -1;
        ab || (ab = K.stylesId);
        Y = K.$(ab) || K.$new("style", {
          id: ab,
          type: "text/css"
        }).jAppendTo((document.head || document.body), "top");
        W = Y.sheet || Y.styleSheet;
        if ("string" != K.jTypeOf(X)) {
          for (var Z in X) {
            aa.push(Z + ":" + X[Z])
          }
          X = aa.join(";")
        }
        if (W.insertRule) {
          U = W.insertRule(V + " {" + X + "}", W.cssRules.length)
        } else {
          U = W.addRule(V, X)
        }
        return U
      },
      removeCSS: function(X, U) {
        var W, V;
        W = K.$(X);
        if ("element" !== K.jTypeOf(W)) {
          return
        }
        V = W.sheet || W.styleSheet;
        if (V.deleteRule) {
          V.deleteRule(U)
        } else {
          if (V.removeRule) {
            V.removeRule(U)
          }
        }
      },
      generateUUID: function() {
        return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function(W) {
          var V = Math.random() * 16 | 0,
            U = W == "x" ? V : (V & 3 | 8);
          return U.toString(16)
        }).toUpperCase()
      },
      getAbsoluteURL: (function() {
        var U;
        return function(V) {
          if (!U) {
            U = document.createElement("a")
          }
          U.setAttribute("href", V);
          return ("!!" + U.href).replace("!!", "")
        }
      })(),
      getHashCode: function(W) {
        var X = 0,
          U = W.length;
        for (var V = 0; V < U; ++V) {
          X = 31 * X + W.charCodeAt(V);
          X %= 4294967296
        }
        return X
      }
    };
    var K = Q;
    var L = Q.$;
    if (!window.magicJS) {
      window.magicJS = Q;
      window.$mjs = Q.$
    }
    K.Array = {
      $J_TYPE: "array",
      indexOf: function(X, Y) {
        var U = this.length;
        for (var V = this.length, W = (Y < 0) ? Math.max(0, V + Y) : Y || 0; W < V; W++) {
          if (this[W] === X) {
            return W
          }
        }
        return -1
      },
      contains: function(U, V) {
        return this.indexOf(U, V) != -1
      },
      forEach: function(U, X) {
        for (var W = 0, V = this.length; W < V; W++) {
          if (W in this) {
            U.call(X, this[W], W, this)
          }
        }
      },
      filter: function(U, Z) {
        var Y = [];
        for (var X = 0, V = this.length; X < V; X++) {
          if (X in this) {
            var W = this[X];
            if (U.call(Z, this[X], X, this)) {
              Y.push(W)
            }
          }
        }
        return Y
      },
      map: function(U, Y) {
        var X = [];
        for (var W = 0, V = this.length; W < V; W++) {
          if (W in this) {
            X[W] = U.call(Y, this[W], W, this)
          }
        }
        return X
      }
    };
    K.implement(String, {
      $J_TYPE: "string",
      jTrim: function() {
        return this.replace(/^\s+|\s+$/g, "")
      },
      eq: function(U, V) {
        return (V || false) ? (this.toString() === U.toString()) : (this.toLowerCase().toString() === U.toLowerCase().toString())
      },
      jCamelize: function() {
        return this.replace(/-\D/g, function(U) {
          return U.charAt(1).toUpperCase()
        })
      },
      dashize: function() {
        return this.replace(/[A-Z]/g, function(U) {
          return ("-" + U.charAt(0).toLowerCase())
        })
      },
      jToInt: function(U) {
        return parseInt(this, U || 10)
      },
      toFloat: function() {
        return parseFloat(this)
      },
      jToBool: function() {
        return !this.replace(/true/i, "").jTrim()
      },
      has: function(V, U) {
        U = U || "";
        return (U + this + U).indexOf(U + V + U) > -1
      }
    });
    Q.implement(Function, {
      $J_TYPE: "function",
      jBind: function() {
        var V = K.$A(arguments),
          U = this,
          W = V.shift();
        return function() {
          return U.apply(W || null, V.concat(K.$A(arguments)))
        }
      },
      jBindAsEvent: function() {
        var V = K.$A(arguments),
          U = this,
          W = V.shift();
        return function(X) {
          return U.apply(W || null, K.$([X || (K.browser.ieMode ? window.event : null)]).concat(V))
        }
      },
      jDelay: function() {
        var V = K.$A(arguments),
          U = this,
          W = V.shift();
        return window.setTimeout(function() {
          return U.apply(U, V)
        }, W || 0)
      },
      jDefer: function() {
        var V = K.$A(arguments),
          U = this;
        return function() {
          return U.jDelay.apply(U, V)
        }
      },
      interval: function() {
        var V = K.$A(arguments),
          U = this,
          W = V.shift();
        return window.setInterval(function() {
          return U.apply(U, V)
        }, W || 0)
      }
    });
    var R = {},
      J = navigator.userAgent.toLowerCase(),
      I = J.match(/(webkit|gecko|trident|presto)\/(\d+\.?\d*)/i),
      N = J.match(/(edge|opr)\/(\d+\.?\d*)/i) || J.match(/(crios|chrome|safari|firefox|opera|opr)\/(\d+\.?\d*)/i),
      P = J.match(/version\/(\d+\.?\d*)/i),
      E = document.documentElement.style;

    function F(V) {
      var U = V.charAt(0).toUpperCase() + V.slice(1);
      return V in E || ("Webkit" + U) in E || ("Moz" + U) in E || ("ms" + U) in E || ("O" + U) in E
    }
    K.browser = {
      features: {
        xpath: !!(document.evaluate),
        air: !!(window.runtime),
        query: !!(document.querySelector),
        fullScreen: !!(document.fullscreenEnabled || document.msFullscreenEnabled || document.exitFullscreen || document.cancelFullScreen || document.webkitexitFullscreen || document.webkitCancelFullScreen || document.mozCancelFullScreen || document.oCancelFullScreen || document.msCancelFullScreen),
        xhr2: !!(window.ProgressEvent) && !!(window.FormData) && (window.XMLHttpRequest && "withCredentials" in new XMLHttpRequest),
        transition: F("transition"),
        transform: F("transform"),
        perspective: F("perspective"),
        animation: F("animation"),
        requestAnimationFrame: false,
        multibackground: false,
        cssFilters: false,
        canvas: false,
        svg: (function() {
          return document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image", "1.1")
        })()
      },
      touchScreen: function() {
        return "ontouchstart" in window || (window.DocumentTouch && document instanceof DocumentTouch) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0)
      }(),
      mobile: J.match(/(android|bb\d+|meego).+|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/) ? true : false,
      engine: (I && I[1]) ? I[1].toLowerCase() : (window.opera) ? "presto" : !!(window.ActiveXObject) ? "trident" : (undefined !== document.getBoxObjectFor || null != window.mozInnerScreenY) ? "gecko" : (null !== window.WebKitPoint || !navigator.taintEnabled) ? "webkit" : "unknown",
      version: (I && I[2]) ? parseFloat(I[2]) : 0,
      uaName: (N && N[1]) ? N[1].toLowerCase() : "",
      uaVersion: (N && N[2]) ? parseFloat(N[2]) : 0,
      cssPrefix: "",
      cssDomPrefix: "",
      domPrefix: "",
      ieMode: 0,
      platform: J.match(/ip(?:ad|od|hone)/) ? "ios" : (J.match(/(?:webos|android)/) || navigator.platform.match(/mac|win|linux/i) || ["other"])[0].toLowerCase(),
      backCompat: document.compatMode && "backcompat" == document.compatMode.toLowerCase(),
      scrollbarsWidth: 0,
      getDoc: function() {
        return (document.compatMode && "backcompat" == document.compatMode.toLowerCase()) ? document.body : document.documentElement
      },
      requestAnimationFrame: window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || undefined,
      cancelAnimationFrame: window.cancelAnimationFrame || window.mozCancelAnimationFrame || window.mozCancelAnimationFrame || window.oCancelAnimationFrame || window.msCancelAnimationFrame || window.webkitCancelRequestAnimationFrame || undefined,
      ready: false,
      onready: function() {
        if (K.browser.ready) {
          return
        }
        var X, W;
        K.browser.ready = true;
        K.body = K.$(document.body);
        K.win = K.$(window);
        try {
          var V = K.$new("div").jSetCss({
            width: 100,
            height: 100,
            overflow: "scroll",
            position: "absolute",
            top: -9999
          }).jAppendTo(document.body);
          K.browser.scrollbarsWidth = V.offsetWidth - V.clientWidth;
          V.jRemove()
        } catch (U) {}
        try {
          X = K.$new("div");
          W = X.style;
          W.cssText = "background:url(https://),url(https://),red url(https://)";
          K.browser.features.multibackground = (/(url\s*\(.*?){3}/).test(W.background);
          W = null;
          X = null
        } catch (U) {}
        if (!K.browser.cssTransformProp) {
          K.browser.cssTransformProp = K.normalizeCSS("transform").dashize()
        }
        try {
          X = K.$new("div");
          X.style.cssText = K.normalizeCSS("filter").dashize() + ":blur(2px);";
          K.browser.features.cssFilters = !!X.style.length && (!K.browser.ieMode || K.browser.ieMode > 9);
          X = null
        } catch (U) {}
        if (!K.browser.features.cssFilters) {
          K.$(document.documentElement).jAddClass("no-cssfilters-magic")
        }
        try {
          K.browser.features.canvas = (function() {
            var Y = K.$new("canvas");
            return !!(Y.getContext && Y.getContext("2d"))
          })()
        } catch (U) {}
        if (undefined === window.TransitionEvent && undefined !== window.WebKitTransitionEvent) {
          R.transitionend = "webkitTransitionEnd"
        }
        K.Doc.jCallEvent.call(K.$(document), "domready")
      }
    };
    (function() {
      var Z = [],
        Y, X, V;

      function U() {
        return !!(arguments.callee.caller)
      }
      switch (K.browser.engine) {
        case "trident":
          if (!K.browser.version) {
            K.browser.version = !!(window.XMLHttpRequest) ? 3 : 2
          }
          break;
        case "gecko":
          K.browser.version = (N && N[2]) ? parseFloat(N[2]) : 0;
          break
      }
      K.browser[K.browser.engine] = true;
      if (N && "crios" === N[1]) {
        K.browser.uaName = "chrome"
      }
      if (!!window.chrome) {
        K.browser.chrome = true
      }
      if (N && "opr" === N[1]) {
        K.browser.uaName = "opera";
        K.browser.opera = true
      }
      if ("safari" === K.browser.uaName && (P && P[1])) {
        K.browser.uaVersion = parseFloat(P[1])
      }
      if ("android" == K.browser.platform && K.browser.webkit && (P && P[1])) {
        K.browser.androidBrowser = true
      }
      Y = ({
        gecko: ["-moz-", "Moz", "moz"],
        webkit: ["-webkit-", "Webkit", "webkit"],
        trident: ["-ms-", "ms", "ms"],
        presto: ["-o-", "O", "o"]
      })[K.browser.engine] || ["", "", ""];
      K.browser.cssPrefix = Y[0];
      K.browser.cssDomPrefix = Y[1];
      K.browser.domPrefix = Y[2];
      K.browser.ieMode = (!K.browser.trident) ? undefined : (document.documentMode) ? document.documentMode : function() {
        var aa = 0;
        if (K.browser.backCompat) {
          return 5
        }
        switch (K.browser.version) {
          case 2:
            aa = 6;
            break;
          case 3:
            aa = 7;
            break
        }
        return aa
      }();
      Z.push(K.browser.platform + "-magic");
      if (K.browser.mobile) {
        Z.push("mobile-magic")
      }
      if (K.browser.androidBrowser) {
        Z.push("android-browser-magic")
      }
      if (K.browser.ieMode) {
        K.browser.uaName = "ie";
        K.browser.uaVersion = K.browser.ieMode;
        Z.push("ie" + K.browser.ieMode + "-magic");
        for (X = 11; X > K.browser.ieMode; X--) {
          Z.push("lt-ie" + X + "-magic")
        }
      }
      if (K.browser.webkit && K.browser.version < 536) {
        K.browser.features.fullScreen = false
      }
      if (K.browser.requestAnimationFrame) {
        K.browser.requestAnimationFrame.call(window, function() {
          K.browser.features.requestAnimationFrame = true
        })
      }
      if (K.browser.features.svg) {
        Z.push("svg-magic")
      } else {
        Z.push("no-svg-magic")
      }
      V = (document.documentElement.className || "").match(/\S+/g) || [];
      document.documentElement.className = K.$(V).concat(Z).join(" ");
      try {
        document.documentElement.setAttribute("data-magic-ua", K.browser.uaName);
        document.documentElement.setAttribute("data-magic-ua-ver", K.browser.uaVersion)
      } catch (W) {}
      if (K.browser.ieMode && K.browser.ieMode < 9) {
        document.createElement("figure");
        document.createElement("figcaption")
      }
    })();
    (function() {
      K.browser.fullScreen = {
        capable: K.browser.features.fullScreen,
        enabled: function() {
          return !!(document.fullscreenElement || document[K.browser.domPrefix + "FullscreenElement"] || document.fullScreen || document.webkitIsFullScreen || document[K.browser.domPrefix + "FullScreen"])
        },
        request: function(U, V) {
          V || (V = {});
          if (this.capable) {
            K.$(document).jAddEvent(this.changeEventName, this.onchange = function(W) {
              if (this.enabled()) {
                V.onEnter && V.onEnter()
              } else {
                K.$(document).jRemoveEvent(this.changeEventName, this.onchange);
                V.onExit && V.onExit()
              }
            }.jBindAsEvent(this));
            K.$(document).jAddEvent(this.errorEventName, this.onerror = function(W) {
              V.fallback && V.fallback();
              K.$(document).jRemoveEvent(this.errorEventName, this.onerror)
            }.jBindAsEvent(this));
            (U[K.browser.domPrefix + "RequestFullscreen"] || U[K.browser.domPrefix + "RequestFullScreen"] || U.requestFullscreen || function() {}).call(U)
          } else {
            if (V.fallback) {
              V.fallback()
            }
          }
        },
        cancel: (document.exitFullscreen || document.cancelFullScreen || document[K.browser.domPrefix + "ExitFullscreen"] || document[K.browser.domPrefix + "CancelFullScreen"] || function() {}).jBind(document),
        changeEventName: document.msExitFullscreen ? "MSFullscreenChange" : (document.exitFullscreen ? "" : K.browser.domPrefix) + "fullscreenchange",
        errorEventName: document.msExitFullscreen ? "MSFullscreenError" : (document.exitFullscreen ? "" : K.browser.domPrefix) + "fullscreenerror",
        prefix: K.browser.domPrefix,
        activeElement: null
      }
    })();
    var T = /\S+/g,
      H = /^(border(Top|Bottom|Left|Right)Width)|((padding|margin)(Top|Bottom|Left|Right))$/,
      M = {
        "float": ("undefined" === typeof(E.styleFloat)) ? "cssFloat" : "styleFloat"
      },
      O = {
        fontWeight: true,
        lineHeight: true,
        opacity: true,
        zIndex: true,
        zoom: true
      },
      G = (window.getComputedStyle) ? function(W, U) {
        var V = window.getComputedStyle(W, null);
        return V ? V.getPropertyValue(U) || V[U] : null
      } : function(X, V) {
        var W = X.currentStyle,
          U = null;
        U = W ? W[V] : null;
        if (null == U && X.style && X.style[V]) {
          U = X.style[V]
        }
        return U
      };

    function S(W) {
      var U, V;
      V = (K.browser.webkit && "filter" == W) ? false : (W in E);
      if (!V) {
        U = K.browser.cssDomPrefix + W.charAt(0).toUpperCase() + W.slice(1);
        if (U in E) {
          return U
        }
      }
      return W
    }
    K.normalizeCSS = S;
    K.Element = {
      jHasClass: function(U) {
        return !(U || "").has(" ") && (this.className || "").has(U, " ")
      },
      jAddClass: function(Y) {
        var V = (this.className || "").match(T) || [],
          X = (Y || "").match(T) || [],
          U = X.length,
          W = 0;
        for (; W < U; W++) {
          if (!K.$(V).contains(X[W])) {
            V.push(X[W])
          }
        }
        this.className = V.join(" ");
        return this
      },
      jRemoveClass: function(Z) {
        var V = (this.className || "").match(T) || [],
          Y = (Z || "").match(T) || [],
          U = Y.length,
          X = 0,
          W;
        for (; X < U; X++) {
          if ((W = K.$(V).indexOf(Y[X])) > -1) {
            V.splice(W, 1)
          }
        }
        this.className = Z ? V.join(" ") : "";
        return this
      },
      jToggleClass: function(U) {
        return this.jHasClass(U) ? this.jRemoveClass(U) : this.jAddClass(U)
      },
      jGetCss: function(V) {
        var W = V.jCamelize(),
          U = null;
        V = M[W] || (M[W] = S(W));
        U = G(this, V);
        if ("auto" === U) {
          U = null
        }
        if (null !== U) {
          if ("opacity" == V) {
            return K.defined(U) ? parseFloat(U) : 1
          }
          if (H.test(V)) {
            U = parseInt(U, 10) ? U : "0px"
          }
        }
        return U
      },
      jSetCssProp: function(V, U) {
        var X = V.jCamelize();
        try {
          if ("opacity" == V) {
            this.jSetOpacity(U);
            return this
          }
          V = M[X] || (M[X] = S(X));
          this.style[V] = U + (("number" == K.jTypeOf(U) && !O[X]) ? "px" : "")
        } catch (W) {}
        return this
      },
      jSetCss: function(V) {
        for (var U in V) {
          this.jSetCssProp(U, V[U])
        }
        return this
      },
      jGetStyles: function() {
        var U = {};
        K.$A(arguments).jEach(function(V) {
          U[V] = this.jGetCss(V)
        }, this);
        return U
      },
      jSetOpacity: function(W, U) {
        var V;
        U = U || false;
        this.style.opacity = W;
        W = parseInt(parseFloat(W) * 100);
        if (U) {
          if (0 === W) {
            if ("hidden" != this.style.visibility) {
              this.style.visibility = "hidden"
            }
          } else {
            if ("visible" != this.style.visibility) {
              this.style.visibility = "visible"
            }
          }
        }
        if (K.browser.ieMode && K.browser.ieMode < 9) {
          if (!isNaN(W)) {
            if (!~this.style.filter.indexOf("Alpha")) {
              this.style.filter += " progid:DXImageTransform.Microsoft.Alpha(Opacity=" + W + ")"
            } else {
              this.style.filter = this.style.filter.replace(/Opacity=\d*/i, "Opacity=" + W)
            }
          } else {
            this.style.filter = this.style.filter.replace(/progid:DXImageTransform.Microsoft.Alpha\(Opacity=\d*\)/i, "").jTrim();
            if ("" === this.style.filter) {
              this.style.removeAttribute("filter")
            }
          }
        }
        return this
      },
      setProps: function(U) {
        for (var V in U) {
          if ("class" === V) {
            this.jAddClass("" + U[V])
          } else {
            this.setAttribute(V, "" + U[V])
          }
        }
        return this
      },
      jGetTransitionDuration: function() {
        var V = 0,
          U = 0;
        V = this.jGetCss("transition-duration");
        U = this.jGetCss("transition-delay");
        V = V.indexOf("ms") > -1 ? parseFloat(V) : V.indexOf("s") > -1 ? parseFloat(V) * 1000 : 0;
        U = U.indexOf("ms") > -1 ? parseFloat(U) : U.indexOf("s") > -1 ? parseFloat(U) * 1000 : 0;
        return V + U
      },
      hide: function() {
        return this.jSetCss({
          display: "none",
          visibility: "hidden"
        })
      },
      show: function() {
        return this.jSetCss({
          display: "",
          visibility: "visible"
        })
      },
      jGetSize: function() {
        return {
          width: this.offsetWidth,
          height: this.offsetHeight
        }
      },
      getInnerSize: function(V) {
        var U = this.jGetSize();
        U.width -= (parseFloat(this.jGetCss("border-left-width") || 0) + parseFloat(this.jGetCss("border-right-width") || 0));
        U.height -= (parseFloat(this.jGetCss("border-top-width") || 0) + parseFloat(this.jGetCss("border-bottom-width") || 0));
        if (!V) {
          U.width -= (parseFloat(this.jGetCss("padding-left") || 0) + parseFloat(this.jGetCss("padding-right") || 0));
          U.height -= (parseFloat(this.jGetCss("padding-top") || 0) + parseFloat(this.jGetCss("padding-bottom") || 0))
        }
        return U
      },
      jGetScroll: function() {
        return {
          top: this.scrollTop,
          left: this.scrollLeft
        }
      },
      jGetFullScroll: function() {
        var U = this,
          V = {
            top: 0,
            left: 0
          };
        do {
          V.left += U.scrollLeft || 0;
          V.top += U.scrollTop || 0;
          U = U.parentNode
        } while (U);
        return V
      },
      jGetPosition: function() {
        var Y = this,
          V = 0,
          X = 0;
        if (K.defined(document.documentElement.getBoundingClientRect)) {
          var U = this.getBoundingClientRect(),
            W = K.$(document).jGetScroll(),
            Z = K.browser.getDoc();
          return {
            top: U.top + W.y - Z.clientTop,
            left: U.left + W.x - Z.clientLeft
          }
        }
        do {
          V += Y.offsetLeft || 0;
          X += Y.offsetTop || 0;
          Y = Y.offsetParent
        } while (Y && !(/^(?:body|html)$/i).test(Y.tagName));
        return {
          top: X,
          left: V
        }
      },
      jGetRect: function() {
        var V = this.jGetPosition();
        var U = this.jGetSize();
        return {
          top: V.top,
          bottom: V.top + U.height,
          left: V.left,
          right: V.left + U.width
        }
      },
      changeContent: function(V) {
        try {
          this.innerHTML = V
        } catch (U) {
          this.innerText = V
        }
        return this
      },
      jRemove: function() {
        return (this.parentNode) ? this.parentNode.removeChild(this) : this
      },
      kill: function() {
        K.$A(this.childNodes).jEach(function(U) {
          if (3 == U.nodeType || 8 == U.nodeType) {
            return
          }
          K.$(U).kill()
        });
        this.jRemove();
        this.jClearEvents();
        if (this.$J_UUID) {
          K.storage[this.$J_UUID] = null;
          delete K.storage[this.$J_UUID]
        }
        return null
      },
      append: function(W, V) {
        V = V || "bottom";
        var U = this.firstChild;
        ("top" == V && U) ? this.insertBefore(W, U): this.appendChild(W);
        return this
      },
      jAppendTo: function(W, V) {
        var U = K.$(W).append(this, V);
        return this
      },
      enclose: function(U) {
        this.append(U.parentNode.replaceChild(this, U));
        return this
      },
      hasChild: function(U) {
        if ("element" !== K.jTypeOf("string" == K.jTypeOf(U) ? U = document.getElementById(U) : U)) {
          return false
        }
        return (this == U) ? false : (this.contains && !(K.browser.webkit419)) ? (this.contains(U)) : (this.compareDocumentPosition) ? !!(this.compareDocumentPosition(U) & 16) : K.$A(this.byTag(U.tagName)).contains(U)
      }
    };
    K.Element.jGetStyle = K.Element.jGetCss;
    K.Element.jSetStyle = K.Element.jSetCss;
    if (!window.Element) {
      window.Element = K.$F;
      if (K.browser.engine.webkit) {
        window.document.createElement("iframe")
      }
      window.Element.prototype = (K.browser.engine.webkit) ? window["[[DOMElement.prototype]]"] : {}
    }
    K.implement(window.Element, {
      $J_TYPE: "element"
    });
    K.Doc = {
      jGetSize: function() {
        if (K.browser.touchScreen || K.browser.presto925 || K.browser.webkit419) {
          return {
            width: window.innerWidth,
            height: window.innerHeight
          }
        }
        return {
          width: K.browser.getDoc().clientWidth,
          height: K.browser.getDoc().clientHeight
        }
      },
      jGetScroll: function() {
        return {
          x: window.pageXOffset || K.browser.getDoc().scrollLeft,
          y: window.pageYOffset || K.browser.getDoc().scrollTop
        }
      },
      jGetFullSize: function() {
        var U = this.jGetSize();
        return {
          width: Math.max(K.browser.getDoc().scrollWidth, U.width),
          height: Math.max(K.browser.getDoc().scrollHeight, U.height)
        }
      }
    };
    K.extend(document, {
      $J_TYPE: "document"
    });
    K.extend(window, {
      $J_TYPE: "window"
    });
    K.extend([K.Element, K.Doc], {
      jFetch: function(X, V) {
        var U = K.getStorage(this.$J_UUID),
          W = U[X];
        if (undefined !== V && undefined === W) {
          W = U[X] = V
        }
        return (K.defined(W) ? W : null)
      },
      jStore: function(W, V) {
        var U = K.getStorage(this.$J_UUID);
        U[W] = V;
        return this
      },
      jDel: function(V) {
        var U = K.getStorage(this.$J_UUID);
        delete U[V];
        return this
      }
    });
    if (!(window.HTMLElement && window.HTMLElement.prototype && window.HTMLElement.prototype.getElementsByClassName)) {
      K.extend([K.Element, K.Doc], {
        getElementsByClassName: function(U) {
          return K.$A(this.getElementsByTagName("*")).filter(function(W) {
            try {
              return (1 == W.nodeType && W.className.has(U, " "))
            } catch (V) {}
          })
        }
      })
    }
    K.extend([K.Element, K.Doc], {
      byClass: function() {
        return this.getElementsByClassName(arguments[0])
      },
      byTag: function() {
        return this.getElementsByTagName(arguments[0])
      }
    });
    if (K.browser.fullScreen.capable && !document.requestFullScreen) {
      K.Element.requestFullScreen = function() {
        K.browser.fullScreen.request(this)
      }
    }
    K.Event = {
      $J_TYPE: "event",
      isQueueStopped: K.$false,
      stop: function() {
        return this.stopDistribution().stopDefaults()
      },
      stopDistribution: function() {
        if (this.stopPropagation) {
          this.stopPropagation()
        } else {
          this.cancelBubble = true
        }
        return this
      },
      stopDefaults: function() {
        if (this.preventDefault) {
          this.preventDefault()
        } else {
          this.returnValue = false
        }
        return this
      },
      stopQueue: function() {
        this.isQueueStopped = K.$true;
        return this
      },
      getClientXY: function() {
        var V, U;
        V = ((/touch/i).test(this.type)) ? this.changedTouches[0] : this;
        return (!K.defined(V)) ? {
          x: 0,
          y: 0
        } : {
          x: V.clientX,
          y: V.clientY
        }
      },
      jGetPageXY: function() {
        var V, U;
        V = ((/touch/i).test(this.type)) ? this.changedTouches[0] : this;
        return (!K.defined(V)) ? {
          x: 0,
          y: 0
        } : {
          x: V.pageX || V.clientX + K.browser.getDoc().scrollLeft,
          y: V.pageY || V.clientY + K.browser.getDoc().scrollTop
        }
      },
      getTarget: function() {
        var U = this.target || this.srcElement;
        while (U && 3 == U.nodeType) {
          U = U.parentNode
        }
        return U
      },
      getRelated: function() {
        var V = null;
        switch (this.type) {
          case "mouseover":
          case "pointerover":
          case "MSPointerOver":
            V = this.relatedTarget || this.fromElement;
            break;
          case "mouseout":
          case "pointerout":
          case "MSPointerOut":
            V = this.relatedTarget || this.toElement;
            break;
          default:
            return V
        }
        try {
          while (V && 3 == V.nodeType) {
            V = V.parentNode
          }
        } catch (U) {
          V = null
        }
        return V
      },
      getButton: function() {
        if (!this.which && this.button !== undefined) {
          return (this.button & 1 ? 1 : (this.button & 2 ? 3 : (this.button & 4 ? 2 : 0)))
        }
        return this.which
      },
      isTouchEvent: function() {
        return (this.pointerType && ("touch" === this.pointerType || this.pointerType === this.MSPOINTER_TYPE_TOUCH)) || (/touch/i).test(this.type)
      },
      isPrimaryTouch: function() {
        return this.pointerType ? (("touch" === this.pointerType || this.MSPOINTER_TYPE_TOUCH === this.pointerType) && this.isPrimary) : 1 === this.changedTouches.length && (this.targetTouches.length ? this.targetTouches[0].identifier == this.changedTouches[0].identifier : true)
      }
    };
    K._event_add_ = "addEventListener";
    K._event_del_ = "removeEventListener";
    K._event_prefix_ = "";
    if (!document.addEventListener) {
      K._event_add_ = "attachEvent";
      K._event_del_ = "detachEvent";
      K._event_prefix_ = "on"
    }
    K.Event.Custom = {
      type: "",
      x: null,
      y: null,
      timeStamp: null,
      button: null,
      target: null,
      relatedTarget: null,
      $J_TYPE: "event.custom",
      isQueueStopped: K.$false,
      events: K.$([]),
      pushToEvents: function(U) {
        var V = U;
        this.events.push(V)
      },
      stop: function() {
        return this.stopDistribution().stopDefaults()
      },
      stopDistribution: function() {
        this.events.jEach(function(V) {
          try {
            V.stopDistribution()
          } catch (U) {}
        });
        return this
      },
      stopDefaults: function() {
        this.events.jEach(function(V) {
          try {
            V.stopDefaults()
          } catch (U) {}
        });
        return this
      },
      stopQueue: function() {
        this.isQueueStopped = K.$true;
        return this
      },
      getClientXY: function() {
        return {
          x: this.clientX,
          y: this.clientY
        }
      },
      jGetPageXY: function() {
        return {
          x: this.x,
          y: this.y
        }
      },
      getTarget: function() {
        return this.target
      },
      getRelated: function() {
        return this.relatedTarget
      },
      getButton: function() {
        return this.button
      },
      getOriginalTarget: function() {
        return this.events.length > 0 ? this.events[0].getTarget() : undefined
      }
    };
    K.extend([K.Element, K.Doc], {
      jAddEvent: function(W, Y, Z, ac) {
        var ab, U, X, aa, V;
        if ("string" == K.jTypeOf(W)) {
          V = W.split(" ");
          if (V.length > 1) {
            W = V
          }
        }
        if (K.jTypeOf(W) == "array") {
          K.$(W).jEach(this.jAddEvent.jBindAsEvent(this, Y, Z, ac));
          return this
        }
        if (!W || !Y || K.jTypeOf(W) != "string" || K.jTypeOf(Y) != "function") {
          return this
        }
        if (W == "domready" && K.browser.ready) {
          Y.call(this);
          return this
        }
        W = R[W] || W;
        Z = parseInt(Z || 50);
        if (!Y.$J_EUID) {
          Y.$J_EUID = Math.floor(Math.random() * K.now())
        }
        ab = K.Doc.jFetch.call(this, "_EVENTS_", {});
        U = ab[W];
        if (!U) {
          ab[W] = U = K.$([]);
          X = this;
          if (K.Event.Custom[W]) {
            K.Event.Custom[W].handler.add.call(this, ac)
          } else {
            U.handle = function(ad) {
              ad = K.extend(ad || window.e, {
                $J_TYPE: "event"
              });
              K.Doc.jCallEvent.call(X, W, K.$(ad))
            };
            this[K._event_add_](K._event_prefix_ + W, U.handle, false)
          }
        }
        aa = {
          type: W,
          fn: Y,
          priority: Z,
          euid: Y.$J_EUID
        };
        U.push(aa);
        U.sort(function(ae, ad) {
          return ae.priority - ad.priority
        });
        return this
      },
      jRemoveEvent: function(aa) {
        var Y = K.Doc.jFetch.call(this, "_EVENTS_", {}),
          W, U, V, ab, Z, X;
        Z = arguments.length > 1 ? arguments[1] : -100;
        if ("string" == K.jTypeOf(aa)) {
          X = aa.split(" ");
          if (X.length > 1) {
            aa = X
          }
        }
        if (K.jTypeOf(aa) == "array") {
          K.$(aa).jEach(this.jRemoveEvent.jBindAsEvent(this, Z));
          return this
        }
        aa = R[aa] || aa;
        if (!aa || K.jTypeOf(aa) != "string" || !Y || !Y[aa]) {
          return this
        }
        W = Y[aa] || [];
        for (V = 0; V < W.length; V++) {
          U = W[V];
          if (-100 == Z || !!Z && Z.$J_EUID === U.euid) {
            ab = W.splice(V--, 1)
          }
        }
        if (0 === W.length) {
          if (K.Event.Custom[aa]) {
            K.Event.Custom[aa].handler.jRemove.call(this)
          } else {
            this[K._event_del_](K._event_prefix_ + aa, W.handle, false)
          }
          delete Y[aa]
        }
        return this
      },
      jCallEvent: function(Y, aa) {
        var X = K.Doc.jFetch.call(this, "_EVENTS_", {}),
          W, U, V;
        Y = R[Y] || Y;
        if (!Y || K.jTypeOf(Y) != "string" || !X || !X[Y]) {
          return this
        }
        try {
          aa = K.extend(aa || {}, {
            type: Y
          })
        } catch (Z) {}
        if (undefined === aa.timeStamp) {
          aa.timeStamp = K.now()
        }
        W = X[Y] || [];
        for (V = 0; V < W.length && !(aa.isQueueStopped && aa.isQueueStopped()); V++) {
          W[V].fn.call(this, aa)
        }
      },
      jRaiseEvent: function(V, U) {
        var Y = ("domready" == V) ? false : true,
          X = this,
          W;
        V = R[V] || V;
        if (!Y) {
          K.Doc.jCallEvent.call(this, V);
          return this
        }
        if (X === document && document.createEvent && !X.dispatchEvent) {
          X = document.documentElement
        }
        if (document.createEvent) {
          W = document.createEvent(V);
          W.initEvent(U, true, true)
        } else {
          W = document.createEventObject();
          W.eventType = V
        }
        if (document.createEvent) {
          X.dispatchEvent(W)
        } else {
          X.fireEvent("on" + U, W)
        }
        return W
      },
      jClearEvents: function() {
        var V = K.Doc.jFetch.call(this, "_EVENTS_");
        if (!V) {
          return this
        }
        for (var U in V) {
          K.Doc.jRemoveEvent.call(this, U)
        }
        K.Doc.jDel.call(this, "_EVENTS_");
        return this
      }
    });
    (function(U) {
      if ("complete" === document.readyState) {
        return U.browser.onready.jDelay(1)
      }
      if (U.browser.webkit && U.browser.version < 420) {
        (function() {
          (U.$(["loaded", "complete"]).contains(document.readyState)) ? U.browser.onready(): arguments.callee.jDelay(50)
        })()
      } else {
        if (U.browser.trident && U.browser.ieMode < 9 && window == top) {
          (function() {
            (U.$try(function() {
              U.browser.getDoc().doScroll("left");
              return true
            })) ? U.browser.onready(): arguments.callee.jDelay(50)
          })()
        } else {
          U.Doc.jAddEvent.call(U.$(document), "DOMContentLoaded", U.browser.onready);
          U.Doc.jAddEvent.call(U.$(window), "load", U.browser.onready)
        }
      }
    })(Q);
    K.Class = function() {
      var Y = null,
        V = K.$A(arguments);
      if ("class" == K.jTypeOf(V[0])) {
        Y = V.shift()
      }
      var U = function() {
        for (var ab in this) {
          this[ab] = K.detach(this[ab])
        }
        if (this.constructor.$parent) {
          this.$parent = {};
          var ad = this.constructor.$parent;
          for (var ac in ad) {
            var aa = ad[ac];
            switch (K.jTypeOf(aa)) {
              case "function":
                this.$parent[ac] = K.Class.wrap(this, aa);
                break;
              case "object":
                this.$parent[ac] = K.detach(aa);
                break;
              case "array":
                this.$parent[ac] = K.detach(aa);
                break
            }
          }
        }
        var Z = (this.init) ? this.init.apply(this, arguments) : this;
        delete this.caller;
        return Z
      };
      if (!U.prototype.init) {
        U.prototype.init = K.$F
      }
      if (Y) {
        var X = function() {};
        X.prototype = Y.prototype;
        U.prototype = new X;
        U.$parent = {};
        for (var W in Y.prototype) {
          U.$parent[W] = Y.prototype[W]
        }
      } else {
        U.$parent = null
      }
      U.constructor = K.Class;
      U.prototype.constructor = U;
      K.extend(U.prototype, V[0]);
      K.extend(U, {
        $J_TYPE: "class"
      });
      return U
    };
    Q.Class.wrap = function(U, V) {
      return function() {
        var X = this.caller;
        var W = V.apply(U, arguments);
        return W
      }
    };
    (function(X) {
      var W = X.$;
      var U = 5,
        V = 300;
      X.Event.Custom.btnclick = new X.Class(X.extend(X.Event.Custom, {
        type: "btnclick",
        init: function(aa, Z) {
          var Y = Z.jGetPageXY();
          this.x = Y.x;
          this.y = Y.y;
          this.clientX = Z.clientX;
          this.clientY = Z.clientY;
          this.timeStamp = Z.timeStamp;
          this.button = Z.getButton();
          this.target = aa;
          this.pushToEvents(Z)
        }
      }));
      X.Event.Custom.btnclick.handler = {
        options: {
          threshold: V,
          button: 1
        },
        add: function(Y) {
          this.jStore("event:btnclick:options", X.extend(X.detach(X.Event.Custom.btnclick.handler.options), Y || {}));
          this.jAddEvent("mousedown", X.Event.Custom.btnclick.handler.handle, 1);
          this.jAddEvent("mouseup", X.Event.Custom.btnclick.handler.handle, 1);
          this.jAddEvent("click", X.Event.Custom.btnclick.handler.onclick, 1);
          if (X.browser.trident && X.browser.ieMode < 9) {
            this.jAddEvent("dblclick", X.Event.Custom.btnclick.handler.handle, 1)
          }
        },
        jRemove: function() {
          this.jRemoveEvent("mousedown", X.Event.Custom.btnclick.handler.handle);
          this.jRemoveEvent("mouseup", X.Event.Custom.btnclick.handler.handle);
          this.jRemoveEvent("click", X.Event.Custom.btnclick.handler.onclick);
          if (X.browser.trident && X.browser.ieMode < 9) {
            this.jRemoveEvent("dblclick", X.Event.Custom.btnclick.handler.handle)
          }
        },
        onclick: function(Y) {
          Y.stopDefaults()
        },
        handle: function(ab) {
          var aa, Y, Z;
          Y = this.jFetch("event:btnclick:options");
          if (ab.type != "dblclick" && ab.getButton() != Y.button) {
            return
          }
          if (this.jFetch("event:btnclick:ignore")) {
            this.jDel("event:btnclick:ignore");
            return
          }
          if ("mousedown" == ab.type) {
            aa = new X.Event.Custom.btnclick(this, ab);
            this.jStore("event:btnclick:btnclickEvent", aa)
          } else {
            if ("mouseup" == ab.type) {
              aa = this.jFetch("event:btnclick:btnclickEvent");
              if (!aa) {
                return
              }
              Z = ab.jGetPageXY();
              this.jDel("event:btnclick:btnclickEvent");
              aa.pushToEvents(ab);
              if (ab.timeStamp - aa.timeStamp <= Y.threshold && Math.sqrt(Math.pow(Z.x - aa.x, 2) + Math.pow(Z.y - aa.y, 2)) <= U) {
                this.jCallEvent("btnclick", aa)
              }
              document.jCallEvent("mouseup", ab)
            } else {
              if (ab.type == "dblclick") {
                aa = new X.Event.Custom.btnclick(this, ab);
                this.jCallEvent("btnclick", aa)
              }
            }
          }
        }
      }
    })(Q);
    (function(V) {
      var U = V.$;
      V.Event.Custom.mousedrag = new V.Class(V.extend(V.Event.Custom, {
        type: "mousedrag",
        state: "dragstart",
        dragged: false,
        init: function(Z, Y, X) {
          var W = Y.jGetPageXY();
          this.x = W.x;
          this.y = W.y;
          this.clientX = Y.clientX;
          this.clientY = Y.clientY;
          this.timeStamp = Y.timeStamp;
          this.button = Y.getButton();
          this.target = Z;
          this.pushToEvents(Y);
          this.state = X
        }
      }));
      V.Event.Custom.mousedrag.handler = {
        add: function() {
          var X = V.Event.Custom.mousedrag.handler.handleMouseMove.jBindAsEvent(this),
            W = V.Event.Custom.mousedrag.handler.handleMouseUp.jBindAsEvent(this);
          this.jAddEvent("mousedown", V.Event.Custom.mousedrag.handler.handleMouseDown, 1);
          this.jAddEvent("mouseup", V.Event.Custom.mousedrag.handler.handleMouseUp, 1);
          document.jAddEvent("mousemove", X, 1);
          document.jAddEvent("mouseup", W, 1);
          this.jStore("event:mousedrag:listeners:document:move", X);
          this.jStore("event:mousedrag:listeners:document:end", W)
        },
        jRemove: function() {
          this.jRemoveEvent("mousedown", V.Event.Custom.mousedrag.handler.handleMouseDown);
          this.jRemoveEvent("mouseup", V.Event.Custom.mousedrag.handler.handleMouseUp);
          U(document).jRemoveEvent("mousemove", this.jFetch("event:mousedrag:listeners:document:move") || V.$F);
          U(document).jRemoveEvent("mouseup", this.jFetch("event:mousedrag:listeners:document:end") || V.$F);
          this.jDel("event:mousedrag:listeners:document:move");
          this.jDel("event:mousedrag:listeners:document:end")
        },
        handleMouseDown: function(X) {
          var W;
          if (1 != X.getButton()) {
            return
          }
          W = new V.Event.Custom.mousedrag(this, X, "dragstart");
          this.jStore("event:mousedrag:dragstart", W)
        },
        handleMouseUp: function(X) {
          var W;
          W = this.jFetch("event:mousedrag:dragstart");
          if (!W) {
            return
          }
          X.stopDefaults();
          W = new V.Event.Custom.mousedrag(this, X, "dragend");
          this.jDel("event:mousedrag:dragstart");
          this.jCallEvent("mousedrag", W)
        },
        handleMouseMove: function(X) {
          var W;
          W = this.jFetch("event:mousedrag:dragstart");
          if (!W) {
            return
          }
          X.stopDefaults();
          if (!W.dragged) {
            W.dragged = true;
            this.jCallEvent("mousedrag", W)
          }
          W = new V.Event.Custom.mousedrag(this, X, "dragmove");
          this.jCallEvent("mousedrag", W)
        }
      }
    })(Q);
    (function(V) {
      var U = V.$;
      V.Event.Custom.dblbtnclick = new V.Class(V.extend(V.Event.Custom, {
        type: "dblbtnclick",
        timedout: false,
        tm: null,
        init: function(Y, X) {
          var W = X.jGetPageXY();
          this.x = W.x;
          this.y = W.y;
          this.clientX = X.clientX;
          this.clientY = X.clientY;
          this.timeStamp = X.timeStamp;
          this.button = X.getButton();
          this.target = Y;
          this.pushToEvents(X)
        }
      }));
      V.Event.Custom.dblbtnclick.handler = {
        options: {
          threshold: 200
        },
        add: function(W) {
          this.jStore("event:dblbtnclick:options", V.extend(V.detach(V.Event.Custom.dblbtnclick.handler.options), W || {}));
          this.jAddEvent("btnclick", V.Event.Custom.dblbtnclick.handler.handle, 1)
        },
        jRemove: function() {
          this.jRemoveEvent("btnclick", V.Event.Custom.dblbtnclick.handler.handle)
        },
        handle: function(Y) {
          var X, W;
          X = this.jFetch("event:dblbtnclick:event");
          W = this.jFetch("event:dblbtnclick:options");
          if (!X) {
            X = new V.Event.Custom.dblbtnclick(this, Y);
            X.tm = setTimeout(function() {
              X.timedout = true;
              Y.isQueueStopped = V.$false;
              this.jCallEvent("btnclick", Y);
              this.jDel("event:dblbtnclick:event")
            }.jBind(this), W.threshold + 10);
            this.jStore("event:dblbtnclick:event", X);
            Y.stopQueue()
          } else {
            clearTimeout(X.tm);
            this.jDel("event:dblbtnclick:event");
            if (!X.timedout) {
              X.pushToEvents(Y);
              Y.stopQueue().stop();
              this.jCallEvent("dblbtnclick", X)
            } else {}
          }
        }
      }
    })(Q);
    (function(aa) {
      var Z = aa.$;

      function U(ab) {
        return ab.pointerType ? (("touch" === ab.pointerType || ab.MSPOINTER_TYPE_TOUCH === ab.pointerType) && ab.isPrimary) : 1 === ab.changedTouches.length && (ab.targetTouches.length ? ab.targetTouches[0].identifier == ab.changedTouches[0].identifier : true)
      }

      function W(ab) {
        if (ab.pointerType) {
          return ("touch" === ab.pointerType || ab.MSPOINTER_TYPE_TOUCH === ab.pointerType) ? ab.pointerId : null
        } else {
          return ab.changedTouches[0].identifier
        }
      }

      function X(ab) {
        if (ab.pointerType) {
          return ("touch" === ab.pointerType || ab.MSPOINTER_TYPE_TOUCH === ab.pointerType) ? ab : null
        } else {
          return ab.changedTouches[0]
        }
      }
      aa.Event.Custom.tap = new aa.Class(aa.extend(aa.Event.Custom, {
        type: "tap",
        id: null,
        init: function(ac, ab) {
          var ad = X(ab);
          this.id = ad.pointerId || ad.identifier;
          this.x = ad.pageX;
          this.y = ad.pageY;
          this.pageX = ad.pageX;
          this.pageY = ad.pageY;
          this.clientX = ad.clientX;
          this.clientY = ad.clientY;
          this.timeStamp = ab.timeStamp;
          this.button = 0;
          this.target = ac;
          this.pushToEvents(ab)
        }
      }));
      var V = 10,
        Y = 200;
      aa.Event.Custom.tap.handler = {
        add: function(ab) {
          this.jAddEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], aa.Event.Custom.tap.handler.onTouchStart, 1);
          this.jAddEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], aa.Event.Custom.tap.handler.onTouchEnd, 1);
          this.jAddEvent("click", aa.Event.Custom.tap.handler.onClick, 1)
        },
        jRemove: function() {
          this.jRemoveEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], aa.Event.Custom.tap.handler.onTouchStart);
          this.jRemoveEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], aa.Event.Custom.tap.handler.onTouchEnd);
          this.jRemoveEvent("click", aa.Event.Custom.tap.handler.onClick)
        },
        onClick: function(ab) {
          ab.stopDefaults()
        },
        onTouchStart: function(ab) {
          if (!U(ab)) {
            this.jDel("event:tap:event");
            return
          }
          this.jStore("event:tap:event", new aa.Event.Custom.tap(this, ab));
          this.jStore("event:btnclick:ignore", true)
        },
        onTouchEnd: function(ae) {
          var ac = aa.now(),
            ad = this.jFetch("event:tap:event"),
            ab = this.jFetch("event:tap:options");
          if (!ad || !U(ae)) {
            return
          }
          this.jDel("event:tap:event");
          if (ad.id == W(ae) && ae.timeStamp - ad.timeStamp <= Y && Math.sqrt(Math.pow(X(ae).pageX - ad.x, 2) + Math.pow(X(ae).pageY - ad.y, 2)) <= V) {
            this.jDel("event:btnclick:btnclickEvent");
            ae.stop();
            ad.pushToEvents(ae);
            this.jCallEvent("tap", ad)
          }
        }
      }
    })(Q);
    K.Event.Custom.dbltap = new K.Class(K.extend(K.Event.Custom, {
      type: "dbltap",
      timedout: false,
      tm: null,
      init: function(V, U) {
        this.x = U.x;
        this.y = U.y;
        this.clientX = U.clientX;
        this.clientY = U.clientY;
        this.timeStamp = U.timeStamp;
        this.button = 0;
        this.target = V;
        this.pushToEvents(U)
      }
    }));
    K.Event.Custom.dbltap.handler = {
      options: {
        threshold: 300
      },
      add: function(U) {
        this.jStore("event:dbltap:options", K.extend(K.detach(K.Event.Custom.dbltap.handler.options), U || {}));
        this.jAddEvent("tap", K.Event.Custom.dbltap.handler.handle, 1)
      },
      jRemove: function() {
        this.jRemoveEvent("tap", K.Event.Custom.dbltap.handler.handle)
      },
      handle: function(W) {
        var V, U;
        V = this.jFetch("event:dbltap:event");
        U = this.jFetch("event:dbltap:options");
        if (!V) {
          V = new K.Event.Custom.dbltap(this, W);
          V.tm = setTimeout(function() {
            V.timedout = true;
            W.isQueueStopped = K.$false;
            this.jCallEvent("tap", W)
          }.jBind(this), U.threshold + 10);
          this.jStore("event:dbltap:event", V);
          W.stopQueue()
        } else {
          clearTimeout(V.tm);
          this.jDel("event:dbltap:event");
          if (!V.timedout) {
            V.pushToEvents(W);
            W.stopQueue().stop();
            this.jCallEvent("dbltap", V)
          } else {}
        }
      }
    };
    (function(Z) {
      var Y = Z.$;

      function U(aa) {
        return aa.pointerType ? (("touch" === aa.pointerType || aa.MSPOINTER_TYPE_TOUCH === aa.pointerType) && aa.isPrimary) : 1 === aa.changedTouches.length && (aa.targetTouches.length ? aa.targetTouches[0].identifier == aa.changedTouches[0].identifier : true)
      }

      function W(aa) {
        if (aa.pointerType) {
          return ("touch" === aa.pointerType || aa.MSPOINTER_TYPE_TOUCH === aa.pointerType) ? aa.pointerId : null
        } else {
          return aa.changedTouches[0].identifier
        }
      }

      function X(aa) {
        if (aa.pointerType) {
          return ("touch" === aa.pointerType || aa.MSPOINTER_TYPE_TOUCH === aa.pointerType) ? aa : null
        } else {
          return aa.changedTouches[0]
        }
      }
      var V = 10;
      Z.Event.Custom.touchdrag = new Z.Class(Z.extend(Z.Event.Custom, {
        type: "touchdrag",
        state: "dragstart",
        id: null,
        dragged: false,
        init: function(ac, ab, aa) {
          var ad = X(ab);
          this.id = ad.pointerId || ad.identifier;
          this.clientX = ad.clientX;
          this.clientY = ad.clientY;
          this.pageX = ad.pageX;
          this.pageY = ad.pageY;
          this.x = ad.pageX;
          this.y = ad.pageY;
          this.timeStamp = ab.timeStamp;
          this.button = 0;
          this.target = ac;
          this.pushToEvents(ab);
          this.state = aa
        }
      }));
      Z.Event.Custom.touchdrag.handler = {
        add: function() {
          var ab = Z.Event.Custom.touchdrag.handler.onTouchMove.jBind(this),
            aa = Z.Event.Custom.touchdrag.handler.onTouchEnd.jBind(this);
          this.jAddEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], Z.Event.Custom.touchdrag.handler.onTouchStart, 1);
          this.jAddEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], Z.Event.Custom.touchdrag.handler.onTouchEnd, 1);
          this.jAddEvent(["touchmove", window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove"], Z.Event.Custom.touchdrag.handler.onTouchMove, 1);
          this.jStore("event:touchdrag:listeners:document:move", ab);
          this.jStore("event:touchdrag:listeners:document:end", aa);
          Y(document).jAddEvent(window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove", ab, 1);
          Y(document).jAddEvent(window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp", aa, 1)
        },
        jRemove: function() {
          this.jRemoveEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], Z.Event.Custom.touchdrag.handler.onTouchStart);
          this.jRemoveEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], Z.Event.Custom.touchdrag.handler.onTouchEnd);
          this.jRemoveEvent(["touchmove", window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove"], Z.Event.Custom.touchdrag.handler.onTouchMove);
          Y(document).jRemoveEvent(window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove", this.jFetch("event:touchdrag:listeners:document:move") || Z.$F, 1);
          Y(document).jRemoveEvent(window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp", this.jFetch("event:touchdrag:listeners:document:end") || Z.$F, 1);
          this.jDel("event:touchdrag:listeners:document:move");
          this.jDel("event:touchdrag:listeners:document:end")
        },
        onTouchStart: function(ab) {
          var aa;
          if (!U(ab)) {
            return
          }
          aa = new Z.Event.Custom.touchdrag(this, ab, "dragstart");
          this.jStore("event:touchdrag:dragstart", aa)
        },
        onTouchEnd: function(ab) {
          var aa;
          aa = this.jFetch("event:touchdrag:dragstart");
          if (!aa || !aa.dragged || aa.id != W(ab)) {
            return
          }
          aa = new Z.Event.Custom.touchdrag(this, ab, "dragend");
          this.jDel("event:touchdrag:dragstart");
          this.jCallEvent("touchdrag", aa)
        },
        onTouchMove: function(ab) {
          var aa;
          aa = this.jFetch("event:touchdrag:dragstart");
          if (!aa || !U(ab)) {
            return
          }
          if (aa.id != W(ab)) {
            this.jDel("event:touchdrag:dragstart");
            return
          }
          if (!aa.dragged && Math.sqrt(Math.pow(X(ab).pageX - aa.x, 2) + Math.pow(X(ab).pageY - aa.y, 2)) > V) {
            aa.dragged = true;
            this.jCallEvent("touchdrag", aa)
          }
          if (!aa.dragged) {
            return
          }
          aa = new Z.Event.Custom.touchdrag(this, ab, "dragmove");
          this.jCallEvent("touchdrag", aa)
        }
      }
    })(Q);
    K.Event.Custom.touchpinch = new K.Class(K.extend(K.Event.Custom, {
      type: "touchpinch",
      scale: 1,
      previousScale: 1,
      curScale: 1,
      state: "pinchstart",
      init: function(V, U) {
        this.timeStamp = U.timeStamp;
        this.button = 0;
        this.target = V;
        this.x = U.touches[0].clientX + (U.touches[1].clientX - U.touches[0].clientX) / 2;
        this.y = U.touches[0].clientY + (U.touches[1].clientY - U.touches[0].clientY) / 2;
        this._initialDistance = Math.sqrt(Math.pow(U.touches[0].clientX - U.touches[1].clientX, 2) + Math.pow(U.touches[0].clientY - U.touches[1].clientY, 2));
        this.pushToEvents(U)
      },
      update: function(U) {
        var V;
        this.state = "pinchupdate";
        if (U.changedTouches[0].identifier != this.events[0].touches[0].identifier || U.changedTouches[1].identifier != this.events[0].touches[1].identifier) {
          return
        }
        V = Math.sqrt(Math.pow(U.changedTouches[0].clientX - U.changedTouches[1].clientX, 2) + Math.pow(U.changedTouches[0].clientY - U.changedTouches[1].clientY, 2));
        this.previousScale = this.scale;
        this.scale = V / this._initialDistance;
        this.curScale = this.scale / this.previousScale;
        this.x = U.changedTouches[0].clientX + (U.changedTouches[1].clientX - U.changedTouches[0].clientX) / 2;
        this.y = U.changedTouches[0].clientY + (U.changedTouches[1].clientY - U.changedTouches[0].clientY) / 2;
        this.pushToEvents(U)
      }
    }));
    K.Event.Custom.touchpinch.handler = {
      add: function() {
        this.jAddEvent("touchstart", K.Event.Custom.touchpinch.handler.handleTouchStart, 1);
        this.jAddEvent("touchend", K.Event.Custom.touchpinch.handler.handleTouchEnd, 1);
        this.jAddEvent("touchmove", K.Event.Custom.touchpinch.handler.handleTouchMove, 1)
      },
      jRemove: function() {
        this.jRemoveEvent("touchstart", K.Event.Custom.touchpinch.handler.handleTouchStart);
        this.jRemoveEvent("touchend", K.Event.Custom.touchpinch.handler.handleTouchEnd);
        this.jRemoveEvent("touchmove", K.Event.Custom.touchpinch.handler.handleTouchMove)
      },
      handleTouchStart: function(V) {
        var U;
        if (V.touches.length != 2) {
          return
        }
        V.stopDefaults();
        U = new K.Event.Custom.touchpinch(this, V);
        this.jStore("event:touchpinch:event", U)
      },
      handleTouchEnd: function(V) {
        var U;
        U = this.jFetch("event:touchpinch:event");
        if (!U) {
          return
        }
        V.stopDefaults();
        this.jDel("event:touchpinch:event")
      },
      handleTouchMove: function(V) {
        var U;
        U = this.jFetch("event:touchpinch:event");
        if (!U) {
          return
        }
        V.stopDefaults();
        U.update(V);
        this.jCallEvent("touchpinch", U)
      }
    };
    (function(Z) {
      var X = Z.$;
      Z.Event.Custom.mousescroll = new Z.Class(Z.extend(Z.Event.Custom, {
        type: "mousescroll",
        init: function(af, ae, ah, ab, aa, ag, ac) {
          var ad = ae.jGetPageXY();
          this.x = ad.x;
          this.y = ad.y;
          this.timeStamp = ae.timeStamp;
          this.target = af;
          this.delta = ah || 0;
          this.deltaX = ab || 0;
          this.deltaY = aa || 0;
          this.deltaZ = ag || 0;
          this.deltaFactor = ac || 0;
          this.deltaMode = ae.deltaMode || 0;
          this.isMouse = false;
          this.pushToEvents(ae)
        }
      }));
      var Y, V;

      function U() {
        Y = null
      }

      function W(aa, ab) {
        return (aa > 50) || (1 === ab && !("win" == Z.browser.platform && aa < 1)) || (0 === aa % 12) || (0 == aa % 4.000244140625)
      }
      Z.Event.Custom.mousescroll.handler = {
        eventType: "onwheel" in document || Z.browser.ieMode > 8 ? "wheel" : "mousewheel",
        add: function() {
          this.jAddEvent(Z.Event.Custom.mousescroll.handler.eventType, Z.Event.Custom.mousescroll.handler.handle, 1)
        },
        jRemove: function() {
          this.jRemoveEvent(Z.Event.Custom.mousescroll.handler.eventType, Z.Event.Custom.mousescroll.handler.handle, 1)
        },
        handle: function(af) {
          var ag = 0,
            ad = 0,
            ab = 0,
            aa = 0,
            ae, ac;
          if (af.detail) {
            ab = af.detail * -1
          }
          if (af.wheelDelta !== undefined) {
            ab = af.wheelDelta
          }
          if (af.wheelDeltaY !== undefined) {
            ab = af.wheelDeltaY
          }
          if (af.wheelDeltaX !== undefined) {
            ad = af.wheelDeltaX * -1
          }
          if (af.deltaY) {
            ab = -1 * af.deltaY
          }
          if (af.deltaX) {
            ad = af.deltaX
          }
          if (0 === ab && 0 === ad) {
            return
          }
          ag = 0 === ab ? ad : ab;
          aa = Math.max(Math.abs(ab), Math.abs(ad));
          if (!Y || aa < Y) {
            Y = aa
          }
          ae = ag > 0 ? "floor" : "ceil";
          ag = Math[ae](ag / Y);
          ad = Math[ae](ad / Y);
          ab = Math[ae](ab / Y);
          if (V) {
            clearTimeout(V)
          }
          V = setTimeout(U, 200);
          ac = new Z.Event.Custom.mousescroll(this, af, ag, ad, ab, 0, Y);
          ac.isMouse = W(Y, af.deltaMode || 0);
          this.jCallEvent("mousescroll", ac)
        }
      }
    })(Q);
    K.win = K.$(window);
    K.doc = K.$(document);
    return Q
  })();
  (function(G) {
    if (!G) {
      throw "MagicJS not found"
    }
    var F = G.$;
    var E = window.URL || window.webkitURL || null;
    s.ImageLoader = new G.Class({
      img: null,
      ready: false,
      options: {
        onprogress: G.$F,
        onload: G.$F,
        onabort: G.$F,
        onerror: G.$F,
        oncomplete: G.$F,
        onxhrerror: G.$F,
        xhr: false,
        progressiveLoad: true
      },
      size: null,
      _timer: null,
      loadedBytes: 0,
      _handlers: {
        onprogress: function(H) {
          if (H.target && (200 === H.target.status || 304 === H.target.status) && H.lengthComputable) {
            this.options.onprogress.jBind(null, (H.loaded - (this.options.progressiveLoad ? this.loadedBytes : 0)) / H.total).jDelay(1);
            this.loadedBytes = H.loaded
          }
        },
        onload: function(H) {
          if (H) {
            F(H).stop()
          }
          this._unbind();
          if (this.ready) {
            return
          }
          this.ready = true;
          this._cleanup();
          !this.options.xhr && this.options.onprogress.jBind(null, 1).jDelay(1);
          this.options.onload.jBind(null, this).jDelay(1);
          this.options.oncomplete.jBind(null, this).jDelay(1)
        },
        onabort: function(H) {
          if (H) {
            F(H).stop()
          }
          this._unbind();
          this.ready = false;
          this._cleanup();
          this.options.onabort.jBind(null, this).jDelay(1);
          this.options.oncomplete.jBind(null, this).jDelay(1)
        },
        onerror: function(H) {
          if (H) {
            F(H).stop()
          }
          this._unbind();
          this.ready = false;
          this._cleanup();
          this.options.onerror.jBind(null, this).jDelay(1);
          this.options.oncomplete.jBind(null, this).jDelay(1)
        }
      },
      _bind: function() {
        F(["load", "abort", "error"]).jEach(function(H) {
          this.img.jAddEvent(H, this._handlers["on" + H].jBindAsEvent(this).jDefer(1))
        }, this)
      },
      _unbind: function() {
        if (this._timer) {
          try {
            clearTimeout(this._timer)
          } catch (H) {}
          this._timer = null
        }
        F(["load", "abort", "error"]).jEach(function(I) {
          this.img.jRemoveEvent(I)
        }, this)
      },
      _cleanup: function() {
        this.jGetSize();
        if (this.img.jFetch("new")) {
          var H = this.img.parentNode;
          this.img.jRemove().jDel("new").jSetCss({
            position: "static",
            top: "auto"
          });
          H.kill()
        }
      },
      loadBlob: function(I) {
        var J = new XMLHttpRequest(),
          H;
        F(["abort", "progress"]).jEach(function(K) {
          J["on" + K] = F(function(L) {
            this._handlers["on" + K].call(this, L)
          }).jBind(this)
        }, this);
        J.onerror = F(function() {
          this.options.onxhrerror.jBind(null, this).jDelay(1);
          this.options.xhr = false;
          this._bind();
          this.img.src = I
        }).jBind(this);
        J.onload = F(function() {
          if (200 !== J.status && 304 !== J.status) {
            this._handlers.onerror.call(this);
            return
          }
          H = J.response;
          this._bind();
          if (E && !G.browser.trident && !("ios" === G.browser.platform && G.browser.version < 537)) {
            this.img.setAttribute("src", E.createObjectURL(H))
          } else {
            this.img.src = I
          }
        }).jBind(this);
        J.open("GET", I);
        J.responseType = "blob";
        J.send()
      },
      init: function(I, H) {
        this.options = G.extend(this.options, H);
        this.img = F(I) || G.$new("img", {}, {
          "max-width": "none",
          "max-height": "none"
        }).jAppendTo(G.$new("div").jAddClass("magic-temporary-img").jSetCss({
          position: "absolute",
          top: -10000,
          width: 10,
          height: 10,
          overflow: "hidden"
        }).jAppendTo(document.body)).jStore("new", true);
        if (G.browser.features.xhr2 && this.options.xhr && "string" == G.jTypeOf(I)) {
          this.loadBlob(I);
          return
        }
        var J = function() {
          if (this.isReady()) {
            this._handlers.onload.call(this)
          } else {
            this._handlers.onerror.call(this)
          }
          J = null
        }.jBind(this);
        this._bind();
        if ("string" == G.jTypeOf(I)) {
          this.img.src = I
        } else {
          if (G.browser.trident && 5 == G.browser.version && G.browser.ieMode < 9) {
            this.img.onreadystatechange = function() {
              if (/loaded|complete/.test(this.img.readyState)) {
                this.img.onreadystatechange = null;
                J && J()
              }
            }.jBind(this)
          }
          this.img.src = I.getAttribute("src")
        }
        this.img && this.img.complete && J && (this._timer = J.jDelay(100))
      },
      destroy: function() {
        this._unbind();
        this._cleanup();
        this.ready = false;
        return this
      },
      isReady: function() {
        var H = this.img;
        return (H.naturalWidth) ? (H.naturalWidth > 0) : (H.readyState) ? ("complete" == H.readyState) : H.width > 0
      },
      jGetSize: function() {
        return this.size || (this.size = {
          width: this.img.naturalWidth || this.img.width,
          height: this.img.naturalHeight || this.img.height
        })
      }
    })
  })(s);
  (function(F) {
    if (!F) {
      throw "MagicJS not found"
    }
    if (F.FX) {
      return
    }
    var E = F.$;
    F.FX = new F.Class({
      init: function(H, G) {
        var I;
        this.el = F.$(H);
        this.options = F.extend(this.options, G);
        this.timer = false;
        this.easeFn = this.cubicBezierAtTime;
        I = F.FX.Transition[this.options.transition] || this.options.transition;
        if ("function" === F.jTypeOf(I)) {
          this.easeFn = I
        } else {
          this.cubicBezier = this.parseCubicBezier(I) || this.parseCubicBezier("ease")
        }
        if ("string" == F.jTypeOf(this.options.cycles)) {
          this.options.cycles = "infinite" === this.options.cycles ? Infinity : parseInt(this.options.cycles) || 1
        }
      },
      options: {
        fps: 60,
        duration: 600,
        transition: "ease",
        cycles: 1,
        direction: "normal",
        onStart: F.$F,
        onComplete: F.$F,
        onBeforeRender: F.$F,
        onAfterRender: F.$F,
        forceAnimation: false,
        roundCss: false
      },
      styles: null,
      cubicBezier: null,
      easeFn: null,
      setTransition: function(G) {
        this.options.transition = G;
        G = F.FX.Transition[this.options.transition] || this.options.transition;
        if ("function" === F.jTypeOf(G)) {
          this.easeFn = G
        } else {
          this.easeFn = this.cubicBezierAtTime;
          this.cubicBezier = this.parseCubicBezier(G) || this.parseCubicBezier("ease")
        }
      },
      start: function(I) {
        var G = /\%$/,
          H;
        this.styles = I || {};
        this.cycle = 0;
        this.state = 0;
        this.curFrame = 0;
        this.pStyles = {};
        this.alternate = "alternate" === this.options.direction || "alternate-reverse" === this.options.direction;
        this.continuous = "continuous" === this.options.direction || "continuous-reverse" === this.options.direction;
        for (H in this.styles) {
          G.test(this.styles[H][0]) && (this.pStyles[H] = true);
          if ("reverse" === this.options.direction || "alternate-reverse" === this.options.direction || "continuous-reverse" === this.options.direction) {
            this.styles[H].reverse()
          }
        }
        this.startTime = F.now();
        this.finishTime = this.startTime + this.options.duration;
        this.options.onStart.call();
        if (0 === this.options.duration) {
          this.render(1);
          this.options.onComplete.call()
        } else {
          this.loopBind = this.loop.jBind(this);
          if (!this.options.forceAnimation && F.browser.features.requestAnimationFrame) {
            this.timer = F.browser.requestAnimationFrame.call(window, this.loopBind)
          } else {
            this.timer = this.loopBind.interval(Math.round(1000 / this.options.fps))
          }
        }
        return this
      },
      stopAnimation: function() {
        if (this.timer) {
          if (!this.options.forceAnimation && F.browser.features.requestAnimationFrame && F.browser.cancelAnimationFrame) {
            F.browser.cancelAnimationFrame.call(window, this.timer)
          } else {
            clearInterval(this.timer)
          }
          this.timer = false
        }
      },
      stop: function(G) {
        G = F.defined(G) ? G : false;
        this.stopAnimation();
        if (G) {
          this.render(1);
          this.options.onComplete.jDelay(10)
        }
        return this
      },
      calc: function(I, H, G) {
        I = parseFloat(I);
        H = parseFloat(H);
        return (H - I) * G + I
      },
      loop: function() {
        var H = F.now(),
          G = (H - this.startTime) / this.options.duration,
          I = Math.floor(G);
        if (H >= this.finishTime && I >= this.options.cycles) {
          this.stopAnimation();
          this.render(1);
          this.options.onComplete.jDelay(10);
          return this
        }
        if (this.alternate && this.cycle < I) {
          for (var J in this.styles) {
            this.styles[J].reverse()
          }
        }
        this.cycle = I;
        if (!this.options.forceAnimation && F.browser.features.requestAnimationFrame) {
          this.timer = F.browser.requestAnimationFrame.call(window, this.loopBind)
        }
        this.render((this.continuous ? I : 0) + this.easeFn(G % 1))
      },
      render: function(G) {
        var H = {},
          J = G;
        for (var I in this.styles) {
          if ("opacity" === I) {
            H[I] = Math.round(this.calc(this.styles[I][0], this.styles[I][1], G) * 100) / 100
          } else {
            H[I] = this.calc(this.styles[I][0], this.styles[I][1], G);
            this.pStyles[I] && (H[I] += "%")
          }
        }
        this.options.onBeforeRender(H, this.el);
        this.set(H);
        this.options.onAfterRender(H, this.el)
      },
      set: function(G) {
        return this.el.jSetCss(G)
      },
      parseCubicBezier: function(G) {
        var H, I = null;
        if ("string" !== F.jTypeOf(G)) {
          return null
        }
        switch (G) {
          case "linear":
            I = E([0, 0, 1, 1]);
            break;
          case "ease":
            I = E([0.25, 0.1, 0.25, 1]);
            break;
          case "ease-in":
            I = E([0.42, 0, 1, 1]);
            break;
          case "ease-out":
            I = E([0, 0, 0.58, 1]);
            break;
          case "ease-in-out":
            I = E([0.42, 0, 0.58, 1]);
            break;
          case "easeInSine":
            I = E([0.47, 0, 0.745, 0.715]);
            break;
          case "easeOutSine":
            I = E([0.39, 0.575, 0.565, 1]);
            break;
          case "easeInOutSine":
            I = E([0.445, 0.05, 0.55, 0.95]);
            break;
          case "easeInQuad":
            I = E([0.55, 0.085, 0.68, 0.53]);
            break;
          case "easeOutQuad":
            I = E([0.25, 0.46, 0.45, 0.94]);
            break;
          case "easeInOutQuad":
            I = E([0.455, 0.03, 0.515, 0.955]);
            break;
          case "easeInCubic":
            I = E([0.55, 0.055, 0.675, 0.19]);
            break;
          case "easeOutCubic":
            I = E([0.215, 0.61, 0.355, 1]);
            break;
          case "easeInOutCubic":
            I = E([0.645, 0.045, 0.355, 1]);
            break;
          case "easeInQuart":
            I = E([0.895, 0.03, 0.685, 0.22]);
            break;
          case "easeOutQuart":
            I = E([0.165, 0.84, 0.44, 1]);
            break;
          case "easeInOutQuart":
            I = E([0.77, 0, 0.175, 1]);
            break;
          case "easeInQuint":
            I = E([0.755, 0.05, 0.855, 0.06]);
            break;
          case "easeOutQuint":
            I = E([0.23, 1, 0.32, 1]);
            break;
          case "easeInOutQuint":
            I = E([0.86, 0, 0.07, 1]);
            break;
          case "easeInExpo":
            I = E([0.95, 0.05, 0.795, 0.035]);
            break;
          case "easeOutExpo":
            I = E([0.19, 1, 0.22, 1]);
            break;
          case "easeInOutExpo":
            I = E([1, 0, 0, 1]);
            break;
          case "easeInCirc":
            I = E([0.6, 0.04, 0.98, 0.335]);
            break;
          case "easeOutCirc":
            I = E([0.075, 0.82, 0.165, 1]);
            break;
          case "easeInOutCirc":
            I = E([0.785, 0.135, 0.15, 0.86]);
            break;
          case "easeInBack":
            I = E([0.6, -0.28, 0.735, 0.045]);
            break;
          case "easeOutBack":
            I = E([0.175, 0.885, 0.32, 1.275]);
            break;
          case "easeInOutBack":
            I = E([0.68, -0.55, 0.265, 1.55]);
            break;
          default:
            G = G.replace(/\s/g, "");
            if (G.match(/^cubic-bezier\((?:-?[0-9\.]{0,}[0-9]{1,},){3}(?:-?[0-9\.]{0,}[0-9]{1,})\)$/)) {
              I = G.replace(/^cubic-bezier\s*\(|\)$/g, "").split(",");
              for (H = I.length - 1; H >= 0; H--) {
                I[H] = parseFloat(I[H])
              }
            }
        }
        return E(I)
      },
      cubicBezierAtTime: function(S) {
        var G = 0,
          R = 0,
          O = 0,
          T = 0,
          Q = 0,
          M = 0,
          N = this.options.duration;

        function L(U) {
          return ((G * U + R) * U + O) * U
        }

        function K(U) {
          return ((T * U + Q) * U + M) * U
        }

        function I(U) {
          return (3 * G * U + 2 * R) * U + O
        }

        function P(U) {
          return 1 / (200 * U)
        }

        function H(U, V) {
          return K(J(U, V))
        }

        function J(ab, ac) {
          var aa, Z, Y, V, U, X;

          function W(ad) {
            if (ad >= 0) {
              return ad
            } else {
              return 0 - ad
            }
          }
          for (Y = ab, X = 0; X < 8; X++) {
            V = L(Y) - ab;
            if (W(V) < ac) {
              return Y
            }
            U = I(Y);
            if (W(U) < 0.000001) {
              break
            }
            Y = Y - V / U
          }
          aa = 0;
          Z = 1;
          Y = ab;
          if (Y < aa) {
            return aa
          }
          if (Y > Z) {
            return Z
          }
          while (aa < Z) {
            V = L(Y);
            if (W(V - ab) < ac) {
              return Y
            }
            if (ab > V) {
              aa = Y
            } else {
              Z = Y
            }
            Y = (Z - aa) * 0.5 + aa
          }
          return Y
        }
        O = 3 * this.cubicBezier[0];
        R = 3 * (this.cubicBezier[2] - this.cubicBezier[0]) - O;
        G = 1 - O - R;
        M = 3 * this.cubicBezier[1];
        Q = 3 * (this.cubicBezier[3] - this.cubicBezier[1]) - M;
        T = 1 - M - Q;
        return H(S, P(N))
      }
    });
    F.FX.Transition = {
      linear: "linear",
      sineIn: "easeInSine",
      sineOut: "easeOutSine",
      expoIn: "easeInExpo",
      expoOut: "easeOutExpo",
      quadIn: "easeInQuad",
      quadOut: "easeOutQuad",
      cubicIn: "easeInCubic",
      cubicOut: "easeOutCubic",
      backIn: "easeInBack",
      backOut: "easeOutBack",
      elasticIn: function(H, G) {
        G = G || [];
        return Math.pow(2, 10 * --H) * Math.cos(20 * H * Math.PI * (G[0] || 1) / 3)
      },
      elasticOut: function(H, G) {
        return 1 - F.FX.Transition.elasticIn(1 - H, G)
      },
      bounceIn: function(I) {
        for (var H = 0, G = 1; 1; H += G, G /= 2) {
          if (I >= (7 - 4 * H) / 11) {
            return G * G - Math.pow((11 - 6 * H - 11 * I) / 4, 2)
          }
        }
      },
      bounceOut: function(G) {
        return 1 - F.FX.Transition.bounceIn(1 - G)
      },
      none: function(G) {
        return 0
      }
    }
  })(s);
  (function(F) {
    if (!F) {
      throw "MagicJS not found"
    }
    if (F.PFX) {
      return
    }
    var E = F.$;
    F.PFX = new F.Class(F.FX, {
      init: function(G, H) {
        this.el_arr = G;
        this.options = F.extend(this.options, H);
        this.timer = false;
        this.$parent.init()
      },
      start: function(K) {
        var G = /\%$/,
          J, I, H = K.length;
        this.styles_arr = K;
        this.pStyles_arr = new Array(H);
        for (I = 0; I < H; I++) {
          this.pStyles_arr[I] = {};
          for (J in K[I]) {
            G.test(K[I][J][0]) && (this.pStyles_arr[I][J] = true);
            if ("reverse" === this.options.direction || "alternate-reverse" === this.options.direction || "continuous-reverse" === this.options.direction) {
              this.styles_arr[I][J].reverse()
            }
          }
        }
        this.$parent.start({});
        return this
      },
      render: function(G) {
        for (var H = 0; H < this.el_arr.length; H++) {
          this.el = F.$(this.el_arr[H]);
          this.styles = this.styles_arr[H];
          this.pStyles = this.pStyles_arr[H];
          this.$parent.render(G)
        }
      }
    })
  })(s);
  (function(F) {
    if (!F) {
      throw "MagicJS not found";
      return
    }
    if (F.Tooltip) {
      return
    }
    var E = F.$;
    F.Tooltip = function(H, I) {
      var G = this.tooltip = F.$new("div", null, {
        position: "absolute",
        "z-index": 999
      }).jAddClass("MagicToolboxTooltip");
      F.$(H).jAddEvent("mouseover", function() {
        G.jAppendTo(document.body)
      });
      F.$(H).jAddEvent("mouseout", function() {
        G.jRemove()
      });
      F.$(H).jAddEvent("mousemove", function(N) {
        var P = 20,
          M = F.$(N).jGetPageXY(),
          L = G.jGetSize(),
          K = F.$(window).jGetSize(),
          O = F.$(window).jGetScroll();

        function J(S, Q, R) {
          return (R < (S - Q) / 2) ? R : ((R > (S + Q) / 2) ? (R - Q) : (S - Q) / 2)
        }
        G.jSetCss({
          left: O.x + J(K.width, L.width + 2 * P, M.x - O.x) + P,
          top: O.y + J(K.height, L.height + 2 * P, M.y - O.y) + P
        })
      });
      this.text(I)
    };
    F.Tooltip.prototype.text = function(G) {
      this.tooltip.firstChild && this.tooltip.removeChild(this.tooltip.firstChild);
      this.tooltip.append(document.createTextNode(G))
    }
  })(s);
  (function(F) {
    if (!F) {
      throw "MagicJS not found";
      return
    }
    if (F.MessageBox) {
      return
    }
    var E = F.$;
    F.Message = function(J, I, H, G) {
      this.hideTimer = null;
      this.messageBox = F.$new("span", null, {
        position: "absolute",
        "z-index": 999,
        visibility: "hidden",
        opacity: 0.8
      }).jAddClass(G || "").jAppendTo(H || document.body);
      this.setMessage(J);
      this.show(I)
    };
    F.Message.prototype.show = function(G) {
      this.messageBox.show();
      this.hideTimer = this.hide.jBind(this).jDelay(F.ifndef(G, 5000))
    };
    F.Message.prototype.hide = function(G) {
      clearTimeout(this.hideTimer);
      this.hideTimer = null;
      if (this.messageBox && !this.hideFX) {
        this.hideFX = new s.FX(this.messageBox, {
          duration: F.ifndef(G, 500),
          onComplete: function() {
            this.messageBox.kill();
            delete this.messageBox;
            this.hideFX = null
          }.jBind(this)
        }).start({
          opacity: [this.messageBox.jGetCss("opacity"), 0]
        })
      }
    };
    F.Message.prototype.setMessage = function(G) {
      this.messageBox.firstChild && this.tooltip.removeChild(this.messageBox.firstChild);
      this.messageBox.append(document.createTextNode(G))
    }
  })(s);
  (function(F) {
    if (!F) {
      throw "MagicJS not found"
    }
    if (F.Options) {
      return
    }
    var I = F.$,
      E = null,
      M = {
        "boolean": 1,
        array: 2,
        number: 3,
        "function": 4,
        string: 100
      },
      G = {
        "boolean": function(P, O, N) {
          if ("boolean" != F.jTypeOf(O)) {
            if (N || "string" != F.jTypeOf(O)) {
              return false
            } else {
              if (!/^(true|false)$/.test(O)) {
                return false
              } else {
                O = O.jToBool()
              }
            }
          }
          if (P.hasOwnProperty("enum") && !I(P["enum"]).contains(O)) {
            return false
          }
          E = O;
          return true
        },
        string: function(P, O, N) {
          if ("string" !== F.jTypeOf(O)) {
            return false
          } else {
            if (P.hasOwnProperty("enum") && !I(P["enum"]).contains(O)) {
              return false
            } else {
              E = "" + O;
              return true
            }
          }
        },
        number: function(Q, P, O) {
          var N = false,
            S = /%$/,
            R = (F.jTypeOf(P) == "string" && S.test(P));
          if (O && !"number" == typeof P) {
            return false
          }
          P = parseFloat(P);
          if (isNaN(P)) {
            return false
          }
          if (isNaN(Q.minimum)) {
            Q.minimum = Number.NEGATIVE_INFINITY
          }
          if (isNaN(Q.maximum)) {
            Q.maximum = Number.POSITIVE_INFINITY
          }
          if (Q.hasOwnProperty("enum") && !I(Q["enum"]).contains(P)) {
            return false
          }
          if (Q.minimum > P || P > Q.maximum) {
            return false
          }
          E = R ? (P + "%") : P;
          return true
        },
        array: function(Q, O, N) {
          if ("string" === F.jTypeOf(O)) {
            try {
              O = window.JSON.parse(O)
            } catch (P) {
              return false
            }
          }
          if (F.jTypeOf(O) === "array") {
            E = O;
            return true
          } else {
            return false
          }
        },
        "function": function(P, O, N) {
          if (F.jTypeOf(O) === "function") {
            E = O;
            return true
          } else {
            return false
          }
        }
      },
      H = function(S, R, O) {
        var Q;
        Q = S.hasOwnProperty("oneOf") ? S.oneOf : [S];
        if ("array" != F.jTypeOf(Q)) {
          return false
        }
        for (var P = 0, N = Q.length - 1; P <= N; P++) {
          if (G[Q[P].type](Q[P], R, O)) {
            return true
          }
        }
        return false
      },
      K = function(S) {
        var Q, P, R, N, O;
        if (S.hasOwnProperty("oneOf")) {
          N = S.oneOf.length;
          for (Q = 0; Q < N; Q++) {
            for (P = Q + 1; P < N; P++) {
              if (M[S.oneOf[Q]["type"]] > M[S.oneOf[P].type]) {
                O = S.oneOf[Q];
                S.oneOf[Q] = S.oneOf[P];
                S.oneOf[P] = O
              }
            }
          }
        }
        return S
      },
      L = function(Q) {
        var P;
        P = Q.hasOwnProperty("oneOf") ? Q.oneOf : [Q];
        if ("array" != F.jTypeOf(P)) {
          return false
        }
        for (var O = P.length - 1; O >= 0; O--) {
          if (!P[O].type || !M.hasOwnProperty(P[O].type)) {
            return false
          }
          if (F.defined(P[O]["enum"])) {
            if ("array" !== F.jTypeOf(P[O]["enum"])) {
              return false
            }
            for (var N = P[O]["enum"].length - 1; N >= 0; N--) {
              if (!G[P[O].type]({
                type: P[O].type
              }, P[O]["enum"][N], true)) {
                return false
              }
            }
          }
        }
        if (Q.hasOwnProperty("default") && !H(Q, Q["default"], true)) {
          return false
        }
        return true
      },
      J = function(N) {
        this.schema = {};
        this.options = {};
        this.parseSchema(N)
      };
    F.extend(J.prototype, {
      parseSchema: function(P) {
        var O, N, Q;
        for (O in P) {
          if (!P.hasOwnProperty(O)) {
            continue
          }
          N = (O + "").jTrim().jCamelize();
          if (!this.schema.hasOwnProperty(N)) {
            this.schema[N] = K(P[O]);
            if (!L(this.schema[N])) {
              throw "Incorrect definition of the '" + O + "' parameter in " + P
            }
            this.options[N] = undefined
          }
        }
      },
      set: function(O, N) {
        O = (O + "").jTrim().jCamelize();
        if (F.jTypeOf(N) == "string") {
          N = N.jTrim()
        }
        if (this.schema.hasOwnProperty(O)) {
          E = N;
          if (H(this.schema[O], N)) {
            this.options[O] = E
          }
          E = null
        }
      },
      get: function(N) {
        N = (N + "").jTrim().jCamelize();
        if (this.schema.hasOwnProperty(N)) {
          return F.defined(this.options[N]) ? this.options[N] : this.schema[N]["default"]
        }
      },
      fromJSON: function(O) {
        for (var N in O) {
          this.set(N, O[N])
        }
      },
      getJSON: function() {
        var O = F.extend({}, this.options);
        for (var N in O) {
          if (undefined === O[N] && undefined !== this.schema[N]["default"]) {
            O[N] = this.schema[N]["default"]
          }
        }
        return O
      },
      fromString: function(N) {
        I(N.split(";")).jEach(I(function(O) {
          O = O.split(":");
          this.set(O.shift().jTrim(), O.join(":"))
        }).jBind(this))
      },
      exists: function(N) {
        N = (N + "").jTrim().jCamelize();
        return this.schema.hasOwnProperty(N)
      },
      isset: function(N) {
        N = (N + "").jTrim().jCamelize();
        return this.exists(N) && F.defined(this.options[N])
      },
      jRemove: function(N) {
        N = (N + "").jTrim().jCamelize();
        if (this.exists(N)) {
          delete this.options[N];
          delete this.schema[N]
        }
      }
    });
    F.Options = J
  }(s));
  u.$AA = function(E) {
    var G = [],
      F;
    for (F in E) {
      if (!E.hasOwnProperty(F) || (F + "").substring(0, 2) == "$J") {
        continue
      }
      G.push(E[F])
    }
    return u.$A(G)
  };
  u.nativeEvents = {
    click: 2,
    dblclick: 2,
    mouseup: 2,
    mousedown: 2,
    contextmenu: 2,
    mousewheel: 2,
    DOMMouseScroll: 2,
    mouseover: 2,
    mouseout: 2,
    mousemove: 2,
    selectstart: 2,
    selectend: 2,
    keydown: 2,
    keypress: 2,
    keyup: 2,
    focus: 2,
    blur: 2,
    change: 2,
    reset: 2,
    select: 2,
    submit: 2,
    load: 1,
    unload: 1,
    beforeunload: 2,
    resize: 1,
    move: 1,
    DOMContentLoaded: 1,
    readystatechange: 1,
    error: 1,
    abort: 1
  };
  u.customEventsAllowed = {
    document: true,
    element: true,
    "class": true,
    object: true
  };
  u.customEvents = {
    bindEvent: function(I, H, F) {
      if (u.jTypeOf(I) == "array") {
        k(I).jEach(this.bindEvent.jBindAsEvent(this, H, F));
        return this
      }
      if (!I || !H || u.jTypeOf(I) != "string" || u.jTypeOf(H) != "function") {
        return this
      }
      if (I == "domready" && u.browser.ready) {
        H.call(this);
        return this
      }
      F = parseInt(F || 10);
      if (!H.$J_EUID) {
        H.$J_EUID = Math.floor(Math.random() * u.now())
      }
      var G = this.jFetch("_events", {});
      G[I] || (G[I] = {});
      G[I][F] || (G[I][F] = {});
      G[I]["orders"] || (G[I]["orders"] = {});
      if (G[I][F][H.$J_EUID]) {
        return this
      }
      if (G[I]["orders"][H.$J_EUID]) {
        this.unbindEvent(I, H)
      }
      var E = this,
        J = function(K) {
          return H.call(E, k(K))
        };
      if (u.nativeEvents[I] && !G[I]["function"]) {
        if (u.nativeEvents[I] == 2) {
          J = function(K) {
            K = u.extend(K || window.e, {
              $J_TYPE: "event"
            });
            return H.call(E, k(K))
          }
        }
        G[I]["function"] = function(K) {
          E.jCallEvent(I, K)
        };
        this[u._event_add_](u._event_prefix_ + I, G[I]["function"], false)
      }
      G[I][F][H.$J_EUID] = J;
      G[I]["orders"][H.$J_EUID] = F;
      return this
    },
    jCallEvent: function(F, H) {
      try {
        H = u.extend(H || {}, {
          type: F
        })
      } catch (G) {}
      if (!F || u.jTypeOf(F) != "string") {
        return this
      }
      var E = this.jFetch("_events", {});
      E[F] || (E[F] = {});
      E[F]["orders"] || (E[F]["orders"] = {});
      u.$AA(E[F]).jEach(function(I) {
        if (I != E[F]["orders"] && I != E[F]["function"]) {
          u.$AA(I).jEach(function(J) {
            J(this)
          }, this)
        }
      }, H);
      return this
    },
    unbindEvent: function(H, G) {
      if (!H || !G || u.jTypeOf(H) != "string" || u.jTypeOf(G) != "function") {
        return this
      }
      if (!G.$J_EUID) {
        G.$J_EUID = Math.floor(Math.random() * u.now())
      }
      var F = this.jFetch("_events", {});
      F[H] || (F[H] = {});
      F[H]["orders"] || (F[H]["orders"] = {});
      order = F[H]["orders"][G.$J_EUID];
      F[H][order] || (F[H][order] = {});
      if (order >= 0 && F[H][order][G.$J_EUID]) {
        delete F[H][order][G.$J_EUID];
        delete F[H]["orders"][G.$J_EUID];
        if (u.$AA(F[H][order]).length == 0) {
          delete F[H][order];
          if (u.nativeEvents[H] && u.$AA(F[H]).length == 0) {
            var E = this;
            this[u._event_del_](u._event_prefix_ + H, F[H]["function"], false)
          }
        }
      }
      return this
    },
    destroyEvent: function(G) {
      if (!G || u.jTypeOf(G) != "string") {
        return this
      }
      var F = this.jFetch("_events", {});
      if (u.nativeEvents[G]) {
        var E = this;
        this[u._event_del_](u._event_prefix_ + G, F[G]["function"], false)
      }
      F[G] = {};
      return this
    },
    cloneEvents: function(G, F) {
      var E = this.jFetch("_events", {});
      for (t in E) {
        if (F && t != F) {
          continue
        }
        for (order in E[t]) {
          if (order == "orders" || order == "function") {
            continue
          }
          for (f in E[t][order]) {
            k(G).bindEvent(t, E[t][order][f], order)
          }
        }
      }
      return this
    },
    jCopyEvents: function(H, G) {
      if (1 !== H.nodeType) {
        return this
      }
      var F = this.jFetch("events");
      if (!F) {
        return this
      }
      for (var E in F) {
        if (G && E != G) {
          continue
        }
        for (var I in F[E]) {
          k(H).bindEvent(E, F[E][I])
        }
      }
      return this
    },
    jFetch: u.Element.jFetch,
    jStore: u.Element.jStore
  };
  (function(E) {
    if (!E) {
      throw "MagicJS not found";
      return
    }
    E.extend = function(M, L) {
      if (!(M instanceof window.Array)) {
        M = [M]
      }
      if (!(L instanceof window.Array)) {
        L = [L]
      }
      for (var J = 0, G = M.length; J < G; J++) {
        if (!E.defined(M[J])) {
          continue
        }
        for (var I = 0, K = L.length; I < K; I++) {
          if (!E.defined(L[I])) {
            continue
          }
          for (var H in (L[I] || {})) {
            try {
              M[J][H] = L[I][H]
            } catch (F) {}
          }
        }
      }
      return M[0]
    };
    E.inherit = function(H, G) {
      function F() {}
      F.prototype = G.prototype;
      H.$parent = G.prototype;
      H.prototype = new F();
      H.prototype.constructor = H
    };
    E.extend([E.Element, window.magicJS.Element], {
      jGetSize_: E.Element.jGetSize,
      jGetSize: function(F, H) {
        var G, I = {
          width: 0,
          height: 0
        };
        if (H) {
          I = this.jGetSize_()
        } else {
          G = this.getBoundingClientRect();
          I.width = G.width;
          I.height = G.height
        }
        if (F) {
          I.width += (parseInt(this.jGetCss("margin-left") || 0) + parseInt(this.jGetCss("margin-right") || 0));
          I.height += (parseInt(this.jGetCss("margin-top") || 0) + ((this.jGetCss("display") != "block") ? parseInt(this.jGetCss("margin-bottom") || 0) : 0))
        }
        return I
      }
    })
  })(s);
  u.Modules || (u.Modules = {});
  u.Modules.ArrowsPair = (function() {
    var E = ["next", "prev"],
      H;

    function I(K, J) {
      return u.$new("button", {
        type: "button"
      }, {
        display: "inline-block"
      }).jAddClass(H["class"]).jAddClass(H.orientation).jAddClass(H["class"] + "-arrow").jAddClass(H["class"] + "-arrow-" + K).jAppendTo(J)
    }

    function F(J, K) {
      K.stopDistribution();
      this.jCallEvent(J)
    }
    var G = function(K, J) {
      u.$uuid(this);
      this.options = {
        "class": "",
        classHidden: "",
        classDisabled: "",
        position: "inside",
        orientation: "ms-horizontal",
        form: "button"
      };
      H = this.o = this.options;
      u.extend(this.o, K);
      this.prev = I("prev", J);
      this.prev.setAttribute('aria-label','Previous Image');
      this.next = I("next", J);
      this.next.setAttribute('aria-label','Next Image');
      this.next.jAddEvent("click", function(L) {
        L.stop()
      }).jAddEvent("btnclick tap", F.jBind(this, "forward"));
      this.prev.jAddEvent("click", function(L) {
        L.stop()
      }).jAddEvent("btnclick tap", F.jBind(this, "backward"))
    };
    G.prototype = {
      disable: function(J) {
        j(J && [J] || E).jEach(function(K) {
          this[K].jAddClass(H.classDisabled)
        }, this)
      },
      enable: function(J) {
        j(J && [J] || E).jEach(function(K) {
          this[K].jRemoveClass(H.classDisabled)
        }, this)
      },
      hide: function(J) {
        j(J && [J] || E).jEach(function(K) {
          this[K].jAddClass(H.classHidden)
        }, this)
      },
      show: function(J) {
        j(J && [J] || E).jEach(function(K) {
          this[K].jRemoveClass(H.classHidden)
        }, this)
      },
      jRemove: function(J) {
        j(J && [J] || E).jEach(function(K) {
          this[K].kill()
        }, this)
      },
      setOrientation: function(J) {
        j(E).jEach(function(K) {
          this[K].jRemoveClass("mcs-" + H.orientation);
          this[K].jAddClass("mcs-" + J)
        }, this);
        this.o.orientation = "mcs-" + J
      }
    };
    u.extend(G.prototype, u.customEvents);
    return G
  })();
  u.Modules || (u.Modules = {});
  u.Modules.Bullets = (function() {
    var F = "active",
      E = function(I, H, G) {
        u.$uuid(this);
        this._options = {};
        this.o = this._options;
        u.extend(this.o, I);
        this.bullets = u.$([]);
        this.callback = G;
        this.activeBullet = {};
        this.ban = false;
        this.container = u.$new("div", {
          "class": "mcs-bullets"
        });
        this.container.jAppendTo(H)
      };
    E.prototype = {
      push: function(G) {
        var H = j(function(J) {
          var I = this.bullets.length;
          this.bullets.push({
            index: I,
            enable: false,
            jump: J,
            node: u.$new("div", {
              "class": "mcs-bullet mcs-bullet-" + I
            })
          });
          if (!I) {
            this.activeBullet = this.bullets[I];
            this.activate(this.bullets[I]);
            this.bullets[I].enable = true
          }
          this.bullets[I].node.jAddEvent("click", j(function(K) {
            K.stop();
            if (this.bullets[I].index == this.activeBullet.index) {
              return
            }
            this.ban = this.callback();
            !this.ban && this.jCallEvent("bullets-click", {
              direction: this.getDirection(this.bullets[I]),
              jumpIndex: this.bullets[I].jump
            })
          }).jBind(this));
          this.bullets[I].node.jAppendTo(this.container)
        }).jBind(this);
        this.reset();
        G.jEach(j(function(I) {
          H(I)
        }).jBind(this))
      },
      setActiveBullet: function(G, H) {
        if (this.activeBullet.index == G[0]) {
          return
        }
        this.activate(this.getBulletIndex(G, H))
      },
      show: function() {
        this.container.jAddClass("show")
      },
      update: function() {
        if (this.activeBullet.node) {
          this.deactivate();
          this.activate(this.bullets[0])
        }
      },
      jRemove: function() {
        this.bullets.jEach(function(G) {
          G.node.kill()
        });
        this.container.kill()
      },
      deactivate: function() {
        this.activeBullet.enable = false;
        this.activeBullet.node.jRemoveClass(F)
      },
      activate: function(G) {
        this.deactivate();
        this.activeBullet = G;
        G.enable = true;
        G.node.jAddClass(F)
      },
      getDirection: function(G) {
        var H = this.activeBullet.index > G.index ? "backward" : "forward";
        this.activate(G);
        return H
      },
      getBulletIndex: function(G, J) {
        var K, I = this.bullets.length - 1,
          H = this.activeBullet;
        for (var K = I; K >= 0; K--) {
          if (this.bullets[K].jump <= G[0]) {
            H = this.bullets[K];
            break
          }
        }
        if (J) {
          if (this.o.items - 1 == G[G.length - 1]) {
            H = this.bullets[I]
          }
        }
        return H
      },
      reset: function() {
        this.ban = false;
        this.activeBullet = {};
        this.bullets.jEach(function(G) {
          G.node.kill()
        });
        this.bullets.length = 0
      }
    };
    u.extend(E.prototype, u.customEvents);
    return E
  })();
  u.Modules || (u.Modules = {});
  u.Modules.Progress = (function() {
    var F = 300,
      E = function(G, H) {
        this.flag = "none";
        this.node = u.$new("div", {
          "class": "mcs-loader"
        });
        if (u.browser.ieMode && u.browser.ieMode < 10) {
          this.node.append(u.$new("div", {
            "class": "mcs-loader-text"
          }).append(u.doc.createTextNode("Loading...")))
        } else {
          if (H) {
            this.node.append(u.$new("div", {
              "class": "mcs-loader-circles"
            }).append(u.$new("div", {
              "class": "mcs-item-loader"
            }, {
              "z-index": 100000
            })))
          } else {
            this.node.append(u.$new("div", {
              "class": "mcs-loader-circles"
            }).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_01"
            })).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_02"
            })).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_03"
            })).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_04"
            })).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_05"
            })).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_06"
            })).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_07"
            })).append(u.$new("div", {
              "class": "mcs-loader-circle mcs-loader-circle_08"
            })))
          }
        }
        this.node.jAppendTo(G);
        this.node.hide()
      };
    E.prototype = {
      show: function() {
        if (this.flag === "show") {
          return
        }
        if (this.node) {
          this.flag = "show";
          this.node.jSetOpacity(1);
          this.node.show()
        }
      },
      hide: function(G) {
        if (this.flag === "hide") {
          return
        }
        if (this.node) {
          this.flag = "hide";
          this.node.jSetOpacity(0);
          this.node.hide()
        }
      },
      jRemove: function() {
        this.node && this.node.kill()
      }
    };
    return E
  })();
  u.Modules || (u.Modules = {});
  u.Modules.ShowItems = (function() {
    var E = function() {
      var L = [],
        G = 300,
        I = 0,
        J = 0,
        M = false,
        K = this;
      u.$uuid(this);

      function H() {
        var P;
        if (L.length == 0) {
          K.jCallEvent("complete");
          return
        }
        if (!M && L.length > 0) {
          M = true;
          P = L.shift();
          var O = j([]);
          O.push(P.item);
          if (P.item.clone && P.item.clone.length > 0) {
            j(P.item.clone).jEach(j(function(Q) {
              O.push(Q)
            }).jBind(this))
          }
          O.jEach(function(R, Q) {
            J += 1;
            if (P.visible) {
              if (Q) {
                P.visible = false
              }
            }
            N(R, !!Q, P.visible, P.callback, function() {
              M = false;
              H()
            }, P.showReflection)
          })
        }
      }

      function F(P, R, O, Q) {
        if (P.progress) {
          P.progress.hide(true)
        }
        I++;
        if (I == J) {
          J = I = 0;
          O();
          Q()
        }
      }

      function N(U, T, Q, R, P, O) {
        var V, W, S = j(U.content);
        if (U.load == "loaded") {
          F(U, T, R, P);
          return
        }
        if (Q) {
          if (u.browser.ieMode && u.browser.ieMode < 10) {
            W = j(S).jGetSize();
            V = {
              opacity: [0, 1],
              top: [W.height / 2, 0],
              left: [W.width / 2, 0],
              width: [0, W.width],
              height: [0, W.height]
            };
            this.itemFX = new u.FX(S, {
              duration: G,
              onComplete: j(function(Y, X) {
                S.jSetCss({
                  overflow: "",
                  position: "",
                  top: "",
                  left: "",
                  width: "",
                  height: ""
                });
                T && (U.load = "loaded");
                F(U, T, Y, X)
              }).jBind(this, R, P),
              onStart: j(function() {
                S.jSetCss({
                  position: "relative",
                  overflow: "hidden"
                })
              }).jBind(this)
            });
            this.itemFX.start(V)
          } else {
            S.jSetCssProp(g, "scale(0.2, 0.2)");
            S.jSetCssProp("transition", "none");
            S.jSetOpacity(0);
            S.offsetHeight;
            S.parentNode.offsetHeight;
            S.jAddEvent("transitionend", j(function(X) {
              if (X.target == S) {
                this.jRemoveEvent(X.type);
                this.jSetCssProp(g, "");
                this.jSetCssProp("transition", "")
              }
            }).jBind(S));
            if (!T && O) {
              O(U)
            }
            S.jSetCssProp("transition", g + " " + G + "ms cubic-bezier(.5,.5,.69,1.9), opacity " + G + "ms linear");
            S.offsetHeight;
            S.parentNode.offsetHeight;
            S.jSetCssProp(g, "scale(1.0, 1.0)");
            S.jSetOpacity(1);
            T && (U.load = "loaded");
            F(U, T, R, P)
          }
        } else {
          S.jSetOpacity(1);
          if (T) {
            U.load = "loaded"
          } else {
            O(U)
          }
          F(U, T, R, P)
        }
      }
      this.push = function(Q, P, O, R) {
        L.push({
          item: Q,
          visible: P,
          callback: O,
          showReflection: R
        });
        H()
      }
    };
    u.extend(E.prototype, u.customEvents);
    return E
  })();
  (function(E) {
    E.QImageLoader = function(L, G) {
      var F = 0,
        K = this,
        J, H;

      function O(P) {
        return function(Q) {
          (G[P] || E.$F).call(K, Q, Q.origItem);
          F--;
          N()
        }
      }

      function N() {
        var P;
        if (!L.length) {} else {
          if (F < (G.queue || 3)) {
            J = L.shift();
            P = I(J.node);
            if (P) {
              H = new E.ImageLoader(P, {
                onload: O("onload"),
                onerror: O("onerror"),
                onabort: O("onabort"),
                oncomplete: O("oncomplete")
              });
              H.origItem = J
            } else {
              (G.onload || E.$F).call(K, {
                size: j(J.node).jGetSize(),
                img: P
              }, J);
              F--;
              N()
            }
            F++
          }
        }
      }

      function M(P) {
        var Q, R;
        Q = (P && P instanceof HTMLImageElement);
        if (Q) {
          R = P.getAttribute("data-src") || null;
          if (R) {
            P.setAttribute("src", R)
          }
        }
        return (Q && P.getAttribute("src")) ? P : null
      }

      function I(P) {
        return E.jTypeOf(J) == "string" ? P : (E.jTypeOf(P) == "object" ? M(P.img) : ((P.tagName == "A" || P.tagName.toLowerCase() == "figure") ? M(j(P).byTag("IMG")[0] || P.firstChild) : (P.tagName == "IMG" ? M(P) : null)))
      }
      this.push = function(P, Q) {
        L[Q ? "unshift" : "push"](P);
        G.delay || N();
        return this
      };
      this.abort = function() {
        H.destroy();
        count--
      };
      this.load = N;
      G.delay || L.length && N()
    }
  })(s);
  var m, j = u.$,
    C = j,
    k = j;
  var o;
  var p = function() {
    return "mgctlbxN$MSC mgctlbxV$" + "v2.0.26".replace("v", "") + " mgctlbxL$" + "c".toUpperCase() + ((window.mgctlbx$Pltm && "string" == u.jTypeOf(window.mgctlbx$Pltm)) ? " mgctlbxP$" + window.mgctlbx$Pltm.toLowerCase() : "")
  };

  function c() {
    u.addCSS(".msc-tmp-hdn-holder", {
      display: "block !important",
      "min-height": "0 !important",
      "min-width": "0 !important",
      "max-height": "none !important",
      "max-width": "none !important",
      width: "10px !important",
      height: "10px !important",
      position: "absolute !important",
      top: "-10000px !important",
      left: "0 !important",
      overflow: "hidden !important",
      "-webkit-transform": "none !important",
      transform: "none !important",
      "-webkit-transition": "none !important",
      transition: "none !important"
    }, "magicsroll-reset-css")
  }
  u.Scroll = {};
  m = {
    width: {
      oneOf: [{
        type: "number",
        minimum: 1
      }, {
        type: "string",
        "enum": ["auto"]
      }],
      "default": "auto"
    },
    height: {
      oneOf: [{
        type: "number",
        minimum: 1
      }, {
        type: "string",
        "enum": ["auto"]
      }],
      "default": "auto"
    },
    items: {
      oneOf: [{
        type: "number",
        minimum: 1
      }, {
        type: "array"
      }, {
        type: "string",
        "enum": ["auto", "fit"]
      }],
      "default": "auto"
    },
    scrollOnWheel: {
      oneOf: [{
        type: "boolean"
      }, {
        type: "string",
        "enum": ["auto"]
      }],
      "default": "auto"
    },
    arrows: {
      oneOf: [{
        type: "boolean"
      }, {
        type: "string",
        "enum": ["inside", "outside", "off"]
      }],
      "default": "outside"
    },
    autoplay: {
      type: "number",
      "default": 0
    },
    speed: {
      type: "number",
      "default": 600
    },
    loop: {
      oneOf: [{
        type: "string",
        "enum": ["infinite", "rewind", "off"]
      }, {
        type: "boolean",
        "enum": [false]
      }],
      "default": "infinite"
    },
    lazyLoad: {
      type: "boolean",
      "default": false
    },
    orientation: {
      type: "string",
      "enum": ["horizontal", "vertical"],
      "default": "horizontal"
    },
    step: {
      oneOf: [{
        type: "number",
        minimum: 0
      }, {
        type: "string",
        "enum": ["auto"]
      }],
      "default": "auto"
    },
    draggable: {
      type: "boolean",
      "default": true
    },
    mode: {
      type: "string",
      "enum": ["scroll", "animation", "carousel", "cover-flow"],
      "default": "scroll"
    },
    pagination: {
      type: "boolean",
      "default": false
    },
    easing: {
      type: "string",
      "default": "cubic-bezier(.8, 0, .5, 1)"
    },
    keyboard: {
      type: "boolean",
      "default": false
    },
    autostart: {
      type: "boolean",
      "default": true
    },
    onItemHover: {
      type: "function",
      "default": u.$F
    },
    onItemOut: {
      type: "function",
      "default": u.$F
    },
    onReady: {
      type: "function",
      "default": u.$F
    },
    onStop: {
      type: "function",
      "default": u.$F
    },
    onMoveStart: {
      type: "function",
      "default": u.$F
    },
    onMoveEnd: {
      type: "function",
      "default": u.$F
    }
  };
  document.createElement("figure");
  document.createElement("figcaption");
  var n = function(E) {
      return {
        width: ((parseInt(E.jGetCss("margin-left")) || 0) + (parseInt(E.jGetCss("margin-right")) || 0)),
        height: ((parseInt(E.jGetCss("margin-top")) || 0) + (parseInt(E.jGetCss("margin-bottom")) || 0))
      }
    },
    i = function(E) {
      return {
        width: ((parseInt(E.jGetCss("padding-left")) || 0) + (parseInt(E.jGetCss("padding-right")) || 0)),
        height: ((parseInt(E.jGetCss("padding-top")) || 0) + (parseInt(E.jGetCss("padding-bottom")) || 0))
      }
    },
    r = function(E) {
      return {
        width: ((parseInt(E.jGetCss("border-left-width")) || 0) + (parseInt(E.jGetCss("border-right-width")) || 0)),
        height: ((parseInt(E.jGetCss("border-top-width")) || 0) + (parseInt(E.jGetCss("border-bottom-width")) || 0))
      }
    },
    D = function(E) {
      return {
        width: j(E).jGetCss("width"),
        height: j(E).jGetCss("height")
      }
    },
    v = u.browser.domPrefix,
    g = u.normalizeCSS("transform").dashize(),
    b = function(F, G) {
      var E = false,
        H = 0;
      u.$uuid(this);
      this._options = {
        stopDownload: true,
        timingFunction: "cubic-bezier(.8, 0, .5, 1)",
        effect: "scroll",
        continuous: false,
        progress: false,
        debug: false,
        orientation: "horizontal",
        duration: 500,
        loop: true,
        lazyLoad: true,
        step: "auto",
        draggable: true,
        keyboard: false
      };
      this.o = this._options;
      u.extend(this.o, G);
      this.container = j(F).jSetCssProp("white-space", "nowrap");
      this.loop = {
        firstItem: false,
        lastItem: false
      };
      this._setProperties();
      this.keyboardCallback = j(function(K) {
        var J = {},
          I = true;
        if (37 === K.keyCode || 39 === K.keyCode) {
          J.direction = K.keyCode == 39 ? "forward" : "backward";
          if (!this.o.loop) {
            if ("forward" === J.direction) {
              if (this.loop.lastItem) {
                I = false
              }
            } else {
              if (this.loop.firstItem) {
                I = false
              }
            }
          }
          I && this.jCallEvent("key_down", J)
        }
      }).jBind(this);
      this.name = "scroll";
      this.items = j([]);
      this.itemsFirstClones = j([]);
      this.itemsLastClones = j([]);
      this.exitItems = j([]);
      this.enterItems = j([]);
      this.last = 0;
      this.globalIndex = 0;
      this.itemStep = this.o.step;
      this.containerPosition = 0;
      this.l = null;
      this.globalLength = null;
      this.distance = null;
      this.allSize = 0;
      this.correctPosition = 0;
      this.containerWidth = 0;
      this.direction = "forward";
      this.callback = u.$F;
      this.fullViewedItems = 0;
      this.stopScroll = false;
      this.moveTimer = null;
      this.wheelDiff = 0;
      this.tempArray = null;
      this.prevIndex = this.last;
      this.wheel_ = false;
      this.preloadAllFlag = false;
      this.disableReflection = false;
      this.loadAll = false;
      this.allNodes = null;
      this.doneFlag = {};
      this.wrapperPosition = 0;
      this.moveSettings = {
        direction: "forward",
        disableEffect: false
      };
      this.onDrag = null;
      this.queue = new u.QImageLoader([], {
        queue: 1,
        onerror: j(function(J, K) {
          var I = this.items[K.index];
          I.load = "error";
          if (I.progress) {
            I.progress.jRemove();
            I.progress = null
          }
          I.node.jAddClass("mcs-noimg");
          this.performedOnClones(j(function(M, L) {
            if (M.index == I.index) {
              M.append = true;
              if (M.progress) {
                M.progress.jRemove();
                M.progress = null
              }
              M.node.load = "error";
              M.node.jAddClass("mcs-noimg")
            }
          }).jBind(this));
          H++;
          if (this.o.lazyLoad) {
            if (this.checkLoadingVisibleItems()) {
              if (this.o.stopDownload || !this.doneFlag.two) {
                this.jCallEvent("hideProgress");
                this.jCallEvent("groupLoad")
              }
              if (!this.move_) {
                this.changeClones()
              }!this.doneFlag.two && this.jCallEvent("complete")
            }
          } else {
            if (H == this.l && !this.o.lazyLoad) {
              this.loadAll = true;
              !this.doneFlag.two && this.jCallEvent("complete")
            }
          }
          this.checkLoadedItems()
        }).jBind(this),
        onload: (function(L, M) {
          var K = [],
            J = this.items[M.index],
            I;
          if (!J) {
            return
          }
          J.node.append(J.content);
          try {
            this.setReflection(J)
          } catch (L) {}
          if (!this.disableReflection) {
            try {
              this.setCanvasPosition(J)
            } catch (L) {
              this.disableReflection = true
            }
          }
          this.addCloneContent(J, j(function() {
            var N = true;
            if (j(["scroll", "animation"]).contains(this.name)) {
              if (!this.doneFlag.two && !this.o.lazyLoad) {
                N = M.index < this.fullViewedItems
              }
            }
            this.showItem(J, N, this.showReflection);
            J.load = "loaded";
            H++;
            if (this.o.lazyLoad) {
              this.onLazyLoad(H)
            } else {
              if (H == this.l) {
                this.loadAll = true;
                !this.doneFlag.two && this.jCallEvent("complete")
              }
            }
            this.checkLoadedItems()
          }).jBind(this))
        }).jBind(this)
      })
    };
  b.prototype = {
    constructor: b,
    showReflection: u.$F,
    setCanvasPosition: u.$F,
    setReflection: u.$F,
    onLazyLoad: function(E) {
      if (this.checkLoadingVisibleItems()) {
        if (this.o.stopDownload || !this.doneFlag.two) {
          this.jCallEvent("hideProgress");
          this.jCallEvent("groupLoad")
        }
        if (!this.doneFlag.two) {
          this.jCallEvent("complete")
        }
      }
    },
    showItem: function(I, L, K) {
      var E, H, G, J = 500,
        F = I.content;
      if (L) {
        if (u.browser.ieMode && u.browser.ieMode < 10) {
          E = j(F).jGetSize();
          H = {
            opacity: [0, 1],
            top: [E.height / 2, 0],
            left: [E.width / 2, 0],
            width: [0, E.width],
            height: [0, E.height]
          };
          G = new u.FX(F, {
            duration: J,
            onComplete: j(function(N, M) {
              F.jSetCss({
                overflow: "",
                position: "",
                top: "",
                left: "",
                width: "",
                height: ""
              });
              if (I.progress) {
                I.progress.jRemove();
                I.progress = null
              }
            }).jBind(this),
            onStart: j(function() {
              F.jSetCss({
                position: "relative",
                overflow: "hidden"
              })
            }).jBind(this)
          });
          G.start(H)
        } else {
          F.jSetCssProp("transition", "none");
          F.jSetOpacity(0);
          F.offsetHeight;
          F.parentNode.offsetHeight;
          F.jAddEvent("transitionend", j(function(M) {
            if (M.target == F) {
              this.jRemoveEvent(M.type);
              this.jSetCssProp(g, "");
              this.jSetCssProp("transition", "");
              if (I.progress) {
                I.progress.jRemove();
                I.progress = null
              }
            }
          }).jBind(F));
          F.jSetCssProp("transition", g + " " + J + "ms cubic-bezier(.5,.5,.69,1.9), opacity " + J + "ms linear");
          F.offsetHeight;
          F.parentNode.offsetHeight;
          F.jSetOpacity(1);
          K && K(I)
        }
      } else {
        F.jSetOpacity(1);
        if (I.progress) {
          I.progress.jRemove();
          I.progress = null
        }
      }
      I.clone.length > 0 && j(I.clone).jEach(j(function(M) {
        if (M) {
          j(M.content).jSetOpacity(1);
          M.load = "loaded";
          if (M.progress) {
            M.progress.jRemove();
            M.progress = null
          }
        }
      }).jBind(this))
    },
    checkLoadedItems: function() {
      var E = 0;
      this.items.jEach(j(function(F) {
        if (F.load == "loaded" || F.load == "error") {
          E++
        }
        if (this.l == E) {
          this.loadAll = true;
          this.jCallEvent("hideProgress")
        }
      }).jBind(this))
    },
    checkLoadingVisibleItems: function() {
      var E = 0,
        F = 0;
      if (this.loadAll) {
        return true
      }
      for (; E < this.fullViewedItems; E++) {
        if (this.items[this._getItemIndex(this.last + E)].load == "loaded" || this.items[this._getItemIndex(this.last + E)].load == "error") {
          F += 1
        }
      }
      return F == this.fullViewedItems
    },
    _sWidth: function() {
      return this.container.parentNode.jGetSize()[this.p_.size]
    },
    _setProperties: function() {
      var E = {
        horizontal: {
          size: "width",
          pos: "left",
          otherSize: "height"
        },
        vertical: {
          size: "height",
          pos: "top",
          otherSize: "width"
        }
      };
      this.p_ = E[this.o.orientation];
      if (this.o.step == 0) {
        this.o.step = "auto"
      }
      if (!this.o.loop || "rewind" === this.o.loop) {
        this.loop.firstItem = true
      }
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        this.container.jSetCssProp(this.p_.pos, 0)
      } else {
        this.container.jSetCssProp(g, "translate3d(0, 0, 0)")
      }
    },
    _render: function() {
      this.container.offsetHeight
    },
    preloadAll: function() {
      if (this.loadAll || this.preloadAllFlag) {
        return
      }
      this.preloadAllFlag = true;
      this.jCallEvent("showProgress");
      this.items.jEach(j(function(E) {
        if (E.load == "notLoaded") {
          if (E.progress) {
            E.progress.jRemove();
            E.progress = null
          }
          E.clone.length > 0 && j(E.clone).jEach(function(F) {
            if (F.progress) {
              F.progress.jRemove();
              F.progress = null
            }
          });
          this.queue.push({
            node: E.content,
            index: E.index
          })
        }
      }).jBind(this));
      this.loadAll = true
    },
    preloadItem: function(F) {
      var G, I = this.last,
        E = j([]),
        H, J;
      if (this.loadAll) {
        return
      }
      if (this.o.lazyLoad) {
        F && (I = (F == "forward") ? this._getItemIndex(I + this.fullViewedItems) : this._getItemIndex(I - this.fullViewedItems));
        J = j(function(K) {
          if (K.load == "notLoaded") {
            if (this.o.stopDownload) {
              !F && this.jCallEvent("showProgress")
            } else {
              K.progress && K.progress.show()
            }
            K.load = "load";
            this.queue.push({
              node: K.content,
              index: K.index
            })
          }
        }).jBind(this);
        for (G = 0; G < this.fullViewedItems; G++) {
          H = this.items[this._getItemIndex(I + G)];
          J(H);
          if (!F) {
            J(this.items[this._getItemIndex(H.index + this.fullViewedItems)]);
            J(this.items[this._getItemIndex(H.index - this.fullViewedItems)])
          }
        }
      }
    },
    freeTouchPreload: function(J) {
      var K, F, H, G, E = 0,
        I = this.allNodes.length;
      if (J == "backward") {
        E = I - 1;
        I = -1
      }
      if (!this.loadAll) {
        while (E != I) {
          G = this.allNodes[E];
          K = G.jGetPosition();
          F = G.getAttribute("data-item");
          if (K[this.p_.pos] + this.items[0].size[this.p_.size] > this.wrapperPosition[this.p_.pos] && K[this.p_.pos] < this.wrapperPosition[this.p_.pos] + this.containerWidth) {
            H = this.items[F];
            if (H.load == "notLoaded") {
              H.load = "load";
              H.progress && H.progress.show();
              j(H.clone).jEach(j(function(L) {
                L.progress && L.progress.show()
              }).jBind(this));
              this.queue.push({
                node: H.content,
                index: H.index
              })
            }
          }
          J == "forward" ? E++ : E--
        }
      }
    },
    done: function(I) {
      var F, E, H, G;
      if (this.doneFlag.one) {
        return
      }
      this.doneFlag.one = true;
      E = this.l = this.items.length;
      this.containerWidth = this._sWidth();
      H = j(this.container.parentNode).jGetPosition();
      for (F = 0; F < this.l; F++) {
        G = this.items[F];
        G.size = G.node.jGetSize(true);
        this.allSize += G.size[this.p_.size]
      }
      this.onResize()
    },
    done2: function(F) {
      this.doneFlag.two = true;
      this.setItemStep();
      if (!u.browser.ieMode || u.browser.ieMode && u.browser.ieMode > 9) {
        if (this.o.draggable) {
          this._initDragOnScroll()
        }
      }
      this.itemEvent();
      if ((!u.browser.ieMode || u.browser.ieMode && u.browser.ieMode > 9) && "scroll" === this.o.effect && this.o.scrollOnWheel) {
        this._initOnWheel()
      }
      if (j(["scroll", "animation"]).contains(this.name)) {
        for (var E = 0; E < this.items.length; E++) {
          if (E >= this.fullViewedItems) {
            this.items[E].progress && this.items[E].progress.show()
          }
        }
      }
      this.last = 0;
      this.globalIndex = this.itemsFirstClones.length;
      j(window).jAddEvent("resize", this.onResize.jBind(this));
      if (this.o.keyboard) {
        j(document).jAddEvent("keydown", this.keyboardCallback)
      }
      this.onResize();
      F && F()
    },
    itemEvent: function() {
      this.items.jEach(j(function(E) {
        E.content.showThis = j(function() {
          this.jCallEvent("show-this", {
            index: E.index
          })
        }).jBind(this);
        E.content.jAddEvent("click", j(function(F) {
          if (this.move_) {
            F.stop()
          }
        }).jBind(this))
      }).jBind(this))
    },
    setItemStep: function(G) {
      var E, F = 0;
      if (this.stopScroll) {
        return
      }
      if (this.o.continuous) {
        this.itemStep = this.fullViewedItems;
        return
      }
      for (E = 0; E < this.l; E++) {
        F += this.items[E].size[this.p_.size];
        if (F >= this.containerWidth) {
          if (this.itemStep == "auto" || this.itemStep >= E) {
            if (this.o.effect == "animation" && F - this.items[E].size[this.p_.size] + 5 < this.containerWidth || F == this.containerWidth) {
              E += 1
            }
            this.itemStep = E;
            if (this.o.step != "auto" && this.o.step < this.itemStep) {
              this.itemStep = this.o.step
            }
          }
          break
        }
      }!this.itemStep && (this.itemStep = 1)
    },
    cloneFigure: function(F) {
      var E = F.cloneNode();
      figure = document.createElement("figure"), figcaption = document.createElement("figcaption");
      u.$A(F.firstChild.childNodes).jEach(j(function(G) {
        if (G.tagName.toLowerCase() == "figcaption") {
          u.$A(G.childNodes).jEach(j(function(H) {
            j(figcaption).append(H.cloneNode(true))
          }).jBind(this));
          u.$A(G.attributes).jEach(j(function(H) {
            figure.setAttribute(H, H.nodeValue)
          }).jBind(this));
          figure.append(figcaption)
        } else {
          j(figure).append(G.cloneNode(true))
        }
      }).jBind(this));
      u.$A(F.firstChild.attributes).jEach(j(function(G) {
        figure.setAttribute(G, G.nodeValue)
      }).jBind(this));
      E.append(figure);
      return E
    },
    performedOnClones: function(E) {
      if (this.itemsFirstClones.length > 0) {
        j([this.itemsFirstClones, this.itemsLastClones]).jEach(j(function(F) {
          F.jEach(j(function(H, G) {
            E(H, G)
          }).jBind(this))
        }).jBind(this))
      }
    },
    addCloneContent: function(F, G) {
      if (this.itemsFirstClones.length > 0) {
        var E = j(function() {
          var H;
          if (u.browser.ieMode && u.browser.ieMode < 9 && F.node.firstChild.tagName.toLowerCase() == "figure") {
            H = this.cloneFigure(F.content.cloneNode(true))
          } else {
            H = F.content.cloneNode(true)
          }
          H.childNodes && u.$A(H.childNodes).jEach(j(function(I) {
            if (j(I).jHasClass && j(I).jHasClass("MagicScroll-progress-bar")) {
              I.kill()
            }
          }).jBind(this));
          return H
        }).jBind(this);
        this.performedOnClones(j(function(I, H) {
          if (I.index == F.index && !I.append) {
            I.content = E();
            this.items[F.index].clone.push(I);
            I.append = true;
            I.node.append(I.content)
          }
        }).jBind(this))
      }
      G && G()
    },
    _prepareClones: function() {
      var E, F = 0,
        I = 0,
        K = 0,
        H = {
          left: 0,
          top: 0
        },
        J, G;
      if (this.stopScroll) {
        return
      }
      for (E = 0; E < this.l; E++) {
        F += this.items[E].size[this.p_.size];
        K++;
        if (this.containerWidth <= F) {
          break
        }
      }
      if (this.l > 1 && (K > this.fullViewedItems || this.itemsFirstClones.length == 0)) {
        I = this.itemsFirstClones.length;
        for (E = I; E < K; E++) {
          J = {
            node: this.items[this.l - 1 - E].node.cloneNode(),
            load: "notLoaded",
            append: false
          };
          j(J.node).setAttribute("data-item", this.l - 1 - E);
          J.index = this.items[this.l - 1 - E].index;
          if (this.o.lazyLoad && this.o.progress) {
            J.progress = new u.Modules.Progress(J.node);
            J.progress.show()
          }
          this.itemsFirstClones.push(J);
          G = {
            node: this.items[E].node.cloneNode(),
            load: "notLoaded",
            append: false
          };
          j(G.node).setAttribute("data-item", E);
          G.index = this.items[E].index;
          if (this.o.lazyLoad && this.o.progress) {
            G.progress = new u.Modules.Progress(G.node);
            G.progress.show()
          }
          this.itemsLastClones.push(G);
          j([G.node, J.node]).jEach(j(function(L) {
            L.jAddEvent("click", j(function(M) {
              if (this.move_) {
                M.stop()
              }
            }).jBind(this))
          }).jBind(this));
          this.container.append(G.node);
          this.container.append(J.node, "top");
          j([this.items[this.l - 1 - E], this.items[E]]).jEach(j(function(L) {
            if (L.load == "loaded") {
              this.addCloneContent(L, j(function() {
                var M = true;
                if (j(["scroll", "animation"]).contains(this.name)) {
                  if (!this.doneFlag.two && !this.o.lazyLoad) {
                    M = L.index < this.fullViewedItems
                  }
                }
                this.showItem(L, M);
                L.clone.length > 0 && j(L.clone).jEach(function(N) {
                  if (N.progress) {
                    N.progress.jRemove();
                    N.progress = null
                  }
                })
              }).jBind(this))
            }
          }).jBind(this))
        }
        if (I) {
          this.fullViewedItems += K - I
        } else {
          this.fullViewedItems = K
        }
      } else {
        this.fullViewedItems = K
      }
      this.correctPosition = this.containerPosition = 0;
      F = 0;
      for (E = 0; E < this.itemsFirstClones.length; E++) {
        F += this.items[this.l - 1 - E].size[this.p_.size]
      }
      this.correctPosition += F;
      this.containerPosition -= F;
      H[this.p_.pos] = this.containerPosition;
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        this.container.jSetCssProp(this.p_.pos, H[this.p_.pos])
      } else {
        this.correctContainerPosition()
      }
    },
    push: function(E) {
      this.l = this.items.length;
      E.index = this.l;
      E.load = "notLoaded";
      E.clone = [];
      if ("gecko" === u.browser.engine) {
        E.content.jAddEvent("dragstart", function(F) {
          F.preventDefault()
        })
      }
      if (this.o.progress && this.o.lazyLoad) {
        E.progress = new u.Modules.Progress(E.node, true);
        if (!this.o.stopDownload) {
          E.progress.show()
        }
      }
      E.node.setAttribute("data-item", E.index);
      E.node.jAddEvent("mouseover mouseout", j(function(G) {
        var F = G.getRelated();
        while (F && F !== E.node) {
          F = F.parentNode
        }
        if (F == E.node) {
          return
        }
        if ("mouseover" === G.type) {
          this.jCallEvent("on-item-hover", {
            itemIndex: E.index
          })
        } else {
          this.jCallEvent("on-item-out", {
            itemIndex: E.index
          })
        }
      }).jBind(this));
      this.items.push(E)
    },
    _getItemIndex: function(E) {
      E %= this.l;
      E < 0 && (E = E + this.l);
      return E
    },
    jump: function(F, G) {
      var E;
      if (F == "forward" || F == "backward") {
        this.direction = F
      }
      if (this.move_ || this.wheel_) {
        return
      }
      this.move_ = true;
      if (u.jTypeOf(F) == "object") {
        this.direction = F.direction;
        F.disableEffect = false;
        F.defaultMove = false
      } else {
        if (/forward|backward|^\+|^\-/.test(F)) {
          if (/^\+|^\-/.test(F)) {
            E = /^\+/.test(F) ? "forward" : "backward";
            F = {
              goTo: Math.abs(parseInt(F)),
              direction: E
            };
            F.goTo > this.l && (F.goTo = this.l);
            F.target = this._getItemIndex(F.direction == "forward" ? (this.last + F.goTo) : (this.last - F.goTo))
          } else {
            F = {
              direction: F
            };
            F.target = this._getItemIndex(F.direction == "forward" ? (this.last + this.itemStep) : (this.last - this.itemStep))
          }
          F.disableEffect = false;
          F.defaultMove = true
        } else {
          if (u.jTypeOf(parseInt(F)) == "number") {
            F = {
              target: this._getItemIndex(F),
              disableEffect: true,
              defaultMove: false
            }
          }
        }
      }
      F.callback = G;
      if (!this.o.loop) {
        if (this.loop.firstItem || this.loop.lastItem) {
          if (this.loop.firstItem) {
            if ("backward" === F.direction) {
              this.move_ = false;
              G(null, true);
              return
            }
          } else {
            if ("forward" === F.direction) {
              this.move_ = false;
              G(null, true);
              return
            }
          }
        }
      }
      this["_" + this.name](F)
    },
    _shiftContainer: function(H, F) {
      var G = {
          left: 0,
          top: 0
        },
        I = false,
        E = F || this.containerPosition;
      if (H == "forward") {
        if (E + this.correctPosition - this.distance + this.allSize < 0) {
          this.containerPosition = E + this.allSize;
          G[this.p_.pos] = this.containerPosition;
          I = true
        }
      } else {
        if (E + this.distance > 0) {
          this.containerPosition = E - this.allSize;
          G[this.p_.pos] = this.containerPosition;
          I = true
        }
      }
      if (I) {
        if (u.browser.ieMode && u.browser.ieMode < 10) {
          this.container.jSetCssProp(this.p_.pos, G[this.p_.pos] + "px")
        } else {
          this.container.jSetCssProp(g, "translate3d(" + G.left + "px, " + G.top + "px, 0)");
          this.container.jSetCssProp("transition", g + " 0ms " + this.o.timingFunction);
          this._render();
          if (this.o.effect == "animation") {
            this.previous = this.globalIndex = this._getGlobalIndex();
            if (H == "forward") {
              this.globalIndex += this.itemStep
            } else {
              this.globalIndex -= this.itemStep
            }
          }
        }
      }
      return I
    },
    _calcDistance: function(H, G) {
      var F, E = true;
      if (!G) {
        if (this.o.step == "auto") {
          this.itemStep = "auto";
          this.setItemStep(H == "backward")
        }
        E = false;
        G = this.itemStep
      } else {
        this.o.stopDownload = false
      }
      for (F = G; F > 0; F--) {
        this.last = this._getItemIndex((H == "forward") ? (this.last + 1) : (this.last - 1));
        this.globalIndex = (H == "forward") ? (this.globalIndex + 1) : (this.globalIndex - 1);
        this.distance += this.items[(H == "forward") ? this._getItemIndex(this.last - 1) : this.last].size[this.p_.size]
      }
      if ("infinite" === this.o.loop) {
        if (!this.o.continuous) {
          this.jCallEvent("on-start-effect", {
            arr: this.getVisibleIndexes()
          })
        }
      } else {
        if ("scroll" === this.o.effect && this.loop.lastItem && H == "backward") {
          if (E) {
            this.last -= (this.itemsVisible - 1)
          } else {
            this.last -= (G - 1)
          }
          if (this.last < 0) {
            this.last = 0
          }
        }
        this.jCallEvent("enable");
        if (this.loop.lastItem && H == "forward") {
          this.loop.lastItem = false;
          this.loop.firstItem = true;
          this.containerPosition = 0;
          this.distance = 0;
          this.last = 0;
          this.globalIndex = 0;
          this.jCallEvent("first-frame");
          this.jCallEvent("on-start-effect", {
            arr: this.getVisibleIndexes()
          })
        } else {
          if (this.loop.firstItem && H == "backward") {
            this.loop.firstItem = false;
            this.loop.lastItem = true;
            this.distance = 0;
            this.last = this.l - 1;
            if (this.o.effect == "scroll") {
              this.globalIndex = this.l - this.itemsVisible;
              this.containerPosition = (this.allSize - this.containerWidth) * (-1)
            } else {
              this.globalIndex = this.l - this.l % this.itemsVisible;
              this.containerPosition = (Math.ceil(this.l / this.itemStep) - 1) * this.containerWidth * (-1)
            }
            this.jCallEvent("last-frame");
            this.jCallEvent("on-start-effect", {
              arr: this.getVisibleIndexes(true)
            })
          } else {
            this.loop.lastItem = false;
            this.loop.firstItem = false;
            if (H == "forward") {
              if (this.containerPosition - this.distance <= this.containerWidth - this.allSize || this.containerPosition - this.distance + 1 <= this.containerWidth - this.allSize) {
                this.jCallEvent("last-frame");
                if (this.o.effect == "scroll" || this.o.effect == "animation" && "infinite" === this.o.loop) {
                  this.distance = this.containerPosition - (this.containerWidth - this.allSize)
                } else {
                  this.distance = this.containerWidth
                }
                this.loop.lastItem = true;
                this.last = this.l - 1;
                this.jCallEvent("on-start-effect", {
                  arr: this.getVisibleIndexes(true)
                })
              } else {
                this.jCallEvent("on-start-effect", {
                  arr: this.getVisibleIndexes()
                })
              }
            } else {
              if (this.containerPosition + this.distance >= 0 || this.containerPosition + this.distance === -1) {
                this.jCallEvent("first-frame");
                this.distance = Math.abs(this.containerPosition);
                this.loop.firstItem = true;
                this.globalIndex = 0;
                this.last = 0;
                this.jCallEvent("on-start-effect", {
                  arr: this.getVisibleIndexes()
                })
              } else {
                this.jCallEvent("on-start-effect", {
                  arr: this.getVisibleIndexes()
                })
              }
            }
          }
        }
      }
    },
    jumpToNumber: function(I) {
      var E, G, F = 0,
        H;
      if (!I.direction) {
        F = Math.floor(this.fullViewedItems / 2);
        if (this.fullViewedItems % 2 == 0) {
          F -= 1
        }
        F < 0 && (F = 0)
      }
      if ("infinite" === this.o.loop) {
        I.target = this._getItemIndex(I.target - F)
      }
      if (this.last != I.target) {
        this.o.stopDownload = false;
        H = j(function(M) {
          var K = this.last,
            L = 0,
            J;
          do {
            L++;
            !M ? K++ : K--;
            J = this._getItemIndex(K)
          } while (J != I.target);
          return L
        }).jBind(this);
        if (!I.direction) {
          if ("infinite" === this.o.loop) {
            I.direction = H() <= H(true) ? "forward" : "backward"
          } else {
            I.direction = I.target > this.last ? "forward" : "backward"
          }
        }
        this.jCallEvent("enable");
        if ("infinite" === this.o.loop) {
          while (this.last != I.target) {
            this.last = this._getItemIndex(I.direction == "forward" ? ++this.last : --this.last);
            this.globalIndex = I.direction == "forward" ? ++this.globalIndex : --this.globalIndex;
            this.distance += this.items[this.last].size[this.p_.size]
          }
        } else {
          this.loop.lastItem = false;
          this.loop.firstItem = false;
          this.last = I.target;
          G = 0;
          for (E = 0; E < I.target - F; E++) {
            G += this.items[E].size[this.p_.size]
          }
          this.globalIndex = I.target;
          this.containerPosition = 0 - this.correctPosition - G;
          if (this.o.effect == "scroll" && this.containerPosition <= 0 - (this.allSize - this.containerWidth) || this.containerPosition <= 0 - ((this.allSize + (this.l % this.itemStep) * this.items[0].size[this.p_.size]) - this.containerWidth)) {
            if (this.o.effect == "scroll") {
              this.containerPosition = 0 - (this.allSize - this.containerWidth)
            }
            this.loop.lastItem = true;
            this.jCallEvent("last-frame");
            this.last = this.l - 1
          }
          if (this.containerPosition >= 0) {
            this.containerPosition = 0;
            this.jCallEvent("first-frame");
            this.loop.firstItem = true;
            this.last = 0
          }
        }
      } else {
        this.move_ = false;
        this.wheel_ = false;
        this.jCallEvent("disableHold")
      }
    },
    _scroll: function(H) {
      var E = this.containerPosition,
        F = false,
        G;
      this.previous = this.globalIndex;
      this.distance = 0;
      if ((!this.o.loop || "rewind" === this.o.loop) && this.o.effect == "animation") {
        if (this.loop.lastItem && H.direction == "forward" || this.loop.firstItem && H.direction == "backward") {
          F = true
        }
      }
      if (H.defaultMove) {
        this._calcDistance(H.direction, H.goTo)
      } else {
        this.jumpToNumber(H)
      }
      if (F) {
        H.direction = H.direction == "forward" ? "backward" : "forward"
      }
      if (0 !== this.wheelDiff) {
        G = this.items[this.prevIndex].size[this.p_.size] - this.wheelDiff;
        if (H.direction == "forward") {
          this.distance -= G
        } else {
          this.distance += G
        }
        this.wheelDiff = 0
      }
      "infinite" === this.o.loop && this._shiftContainer(H.direction);
      if (H.direction == "forward") {
        this.containerPosition -= this.distance
      } else {
        this.containerPosition += this.distance
      }
      this.moveSettings.direction = H.direction;
      this.moveSettings.disableEffect = H.disableEffect;
      if (E != this.containerPosition) {
        this.callback = H.callback;
        if (this.o.stopDownload && !this.loadAll && !this.checkLoadingVisibleItems()) {
          this.jCallEvent("showProgress");
          this.preloadItem();
          this.bindEvent("groupLoad", j(function(I) {
            this.move_ && this._move(null, I.direction, I.disableEffect)
          }).jBind(this, this.moveSettings))
        } else {
          if (!this.loadAll) {
            this.preloadItem()
          }
          this._move(null, H.direction, H.disableEffect)
        }
      } else {
        this.jCallEvent("hold")
      }
    },
    _move: function(F, E, H) {
      var G = {
        left: 0,
        top: 0
      };
      this.move_ = true;
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        G = {};
        G[this.p_.pos] = [parseInt(this.container.jGetCss(this.p_.pos)), this.containerPosition];
        this.fx = new u.FX(this.container, {
          transition: this.o.timingFunction,
          duration: F || this.o.duration,
          onComplete: this._onComplete.jBind(this),
          onStart: j(function() {
            this.stop_ = false
          }).jBind(this)
        }).start(G)
      } else {
        G[this.p_.pos] = this.containerPosition;
        if (this.o.effect == "animation" && !H) {
          this._moveEffect(E, G)
        } else {
          this.container.jRemoveEvent("transitionend");
          this.container.jAddEvent("transitionend", j(function(I) {
            if (I.target == this.container) {
              this.container.jRemoveEvent(I.type);
              if (H) {
                this.globalIndex = this._getGlobalIndex();
                this._cleansingStyles()
              }
              this._onComplete()
            }
          }).jBind(this));
          this.container.jSetCssProp(g, "translate3d(" + G.left + "px, " + G.top + "px, 0)");
          this.container.jSetCssProp("transition", g + " " + (F || this.o.duration) + "ms " + this.o.timingFunction)
        }
      }
    },
    _moveEffect: function(K, J) {
      var I, F, H, G = this.container.childNodes,
        E = G.length,
        L = j(function(M) {
          M %= this.globalLength;
          M < 0 && (M = M + this.globalLength);
          return M
        }).jBind(this);
      this.exitItems.length = 0;
      this.enterItems.length = 0;
      for (I = 0; I < this.itemStep; I++) {
        if ("infinite" === this.o.loop) {
          F = L(this.previous + I)
        } else {
          F = this.previous + I < E ? this.previous + I : null
        }
        F != null && this.exitItems.push(G[F]);
        if ("infinite" === this.o.loop) {
          H = L(this.globalIndex + I)
        } else {
          H = this.globalIndex + I < E ? this.globalIndex + I : null
        }
        H != null && this.enterItems.push(G[H])
      }
      if (K == "backward") {
        this.exitItems.reverse();
        this.enterItems.reverse()
      }
      this.container.setAttribute("data-" + K, "");
      this.exitItems.jEach(j(function(N, M) {
        N.jAddEvent(v + "AnimationEnd animationend", j(function(O, P, Q) {
          if (O == this.exitItems[P]) {
            O.jRemoveEvent(v + "AnimationEnd animationend").setAttribute("data-exited", "");
            if (P == this.exitItems.length - 1) {
              this.exitItems.jEach(j(function(S, R) {
                S.removeAttribute("data-animation-nth");
                S.removeAttribute("data-action")
              }).jBind(this));
              this.enterItems.jEach(j(function(S, R) {
                if (R == this.enterItems.length - 1) {
                  S.jAddEvent(v + "AnimationEnd animationend", j(function(T) {
                    if (T.target == S) {
                      S.jRemoveEvent(v + "AnimationEnd animationend");
                      this.enterItems.jEach(j(function(U, V) {
                        U.removeAttribute("data-animation-nth");
                        U.removeAttribute("data-action")
                      }).jBind(this));
                      this.exitItems.jEach(j(function(U, V) {
                        U.removeAttribute("data-exited")
                      }).jBind(this));
                      this.container.removeAttribute("data-" + K);
                      this._render();
                      this._onComplete()
                    }
                  }).jBind(this))
                }
                S.setAttribute("data-entering", "");
                S.jAddEvent(v + "AnimationStart animationstart", j(function(T) {
                  if (T.target == this) {
                    this.jRemoveEvent(v + "AnimationStart animationstart");
                    S.removeAttribute("data-entering")
                  }
                }).jBind(S));
                S.setAttribute("data-action", "enter");
                S.setAttribute("data-animation-nth", (R + 1))
              }).jBind(this));
              this.container.jSetCssProp(g, "translate3d(" + J.left + "px, " + J.top + "px, 0)")
            }
          }
        }).jBind(this, N, M))
      }).jBind(this));
      this.exitItems.jEach(j(function(N, M) {
        N.setAttribute("data-exiting", "");
        N.jAddEvent(v + "AnimationStart animationstart", j(function(O) {
          if (O.target == this) {
            N.jRemoveEvent(v + "AnimationStart animationstart");
            this.removeAttribute("data-exiting")
          }
        }).jBind(N));
        N.setAttribute("data-action", "exit");
        N.setAttribute("data-animation-nth", (M + 1))
      }).jBind(this))
    },
    getVisibleIndexes: function(H) {
      var I = 0,
        G = this.itemStep,
        E = [],
        F;
      if (H) {
        if (this.o.effect == "scroll") {
          I = this.l - this.itemStep
        } else {
          I = this.l % this.itemStep ? this.l - this.l % this.itemStep : this.l - this.itemStep
        }
        G = this.l
      }
      for (; I < G; I++) {
        if (!H) {
          F = this.last + I
        } else {
          F = I
        }
        E.push(this._getItemIndex(F))
      }
      return E
    },
    _onComplete: function() {
      this.move_ = false;
      this.continuousPause = false;
      this.callback && this.callback(this.getVisibleIndexes(this.loop.lastItem))
    },
    _cleansingStyles: function() {
      this.container.jSetCssProp("transition", g + " 0ms")
    },
    getMatrixPosition: function(J) {
      var I = {
          x: 0,
          y: 0
        },
        G = J.jGetCss(g) || "",
        H = /3d/.test(G) ? (/matrix3d\(([^\)]+)\)/) : (/matrix\(([^\)]+)\)/),
        F = /3d/.test(G) ? 12 : 4,
        E = /3d/.test(G) ? 13 : 5;
      (J.jGetCss(g) || "").replace(H, function(M, L) {
        var K = L.split(",");
        I.x += parseInt(K[F], 10);
        I.y += parseInt(K[E])
      });
      return I
    },
    _getGlobalIndex: function() {
      var H;
      var G;
      var E;
      var F = Number.MAX_VALUE;
      var I = this.container.parentNode.jGetPosition()[this.p_.pos];
      for (H = 0; H < this.globalLength; H++) {
        G = this.container.childNodes[H].jGetPosition()[this.p_.pos];
        if (F > Math.abs(I - G)) {
          F = Math.abs(I - G);
          E = H
        } else {
          break
        }
      }
      return E
    },
    changeClones: function() {
      if (this.itemsFirstClones.length == 0) {
        return
      }
      var F, E, G = j(function(I, J) {
        var K, H;
        if (this.items[J].node != I && this.items[J].load == "loaded") {
          for (H = 0; H < this.globalLength; H++) {
            if (this.items[J].node == this.container.childNodes[H]) {
              K = H;
              break
            }
          }
          if (K < E) {
            this.container.insertBefore(I, this.container.childNodes[K]);
            if (E + 1 <= this.globalLength - 1) {
              this.container.insertBefore(this.items[J].node, this.container.childNodes[E + 1])
            } else {
              this.container.appendChild(this.items[J].node)
            }
          } else {
            this.container.insertBefore(this.items[J].node, I);
            if (K + 1 <= this.globalLength - 1) {
              this.container.insertBefore(I, this.container.childNodes[K + 1])
            } else {
              this.container.appendChild(I)
            }
          }
        }
      }).jBind(this);
      E = this._getGlobalIndex();
      for (F = 0; F < this.fullViewedItems; F++) {
        G(this.container.childNodes[E], this._getItemIndex(this.last + F));
        E++
      }
    },
    correctItemPosition: function(M) {
      var K, I, J, P = 0,
        F = 0,
        O, L = this.container.parentNode.jGetPosition()[this.p_.pos] + 1,
        H = this.container.jGetPosition()[this.p_.pos] - L,
        N = Math.abs(Math.abs(H) - Math.abs(this.containerPosition)),
        G, E = j(function(Q) {
          return parseInt(this.container.childNodes[Q].getAttribute("data-item"))
        }).jBind(this);
      (N > 0 && N < 1) && (N = 0);
      if (M == "forward") {
        L += N
      } else {
        L -= N
      }
      for (K = 0; K < this.globalLength; K++) {
        J = this.container.childNodes[K].jGetPosition()[this.p_.pos];
        if (J == L) {
          this.last = E(K);
          return 0
        }
        O = parseInt(this.container.childNodes[K].jGetSize()[this.p_.size]);
        if (J < L && J + O > L) {
          G = K;
          if (M == "forward") {
            G = K + 1 > this.globalLength - 1 ? this.globalLength - 1 : K + 1;
            K++
          }
          for (I = 0; I < K; I++) {
            F += this.items[E(I)].size[this.p_.size]
          }
          P = Math.abs(Math.abs(this.containerPosition) - F);
          this.last = E(G);
          break
        }
      }
      return P
    },
    _initDragOnScroll: function() {
      var ae, K, ac, U, ad, J, F = (this.p_.pos == "left") ? "x" : "y",
        L = {
          x: 0,
          y: 0
        },
        S = this.o.effect == "scroll",
        V, X = true,
        O = {
          x: 0,
          y: 0
        },
        H = false,
        W = false,
        M = null,
        Q = 0,
        Y = null,
        R = false,
        G = j(function(ah) {
          var ag, af = 0;
          if (ah > this.containerWidth) {
            ah = this.containerWidth
          }
          for (ag = 1.5; ag <= 90; ag += 1.5) {
            af += (ah * Math.cos(ag / Math.PI / 2))
          }
          return this.containerWidth > af ? af : this.containerWidth
        }).jBind(this),
        I = j(function(ah) {
          var ai, af = 0,
            ag, aj;
          while (af > this.containerPosition) {
            af -= this.containerWidth
          }
          if (Math.abs(af - this.containerPosition) > this.containerWidth / 2) {
            af += this.containerWidth
          }
          aj = af;
          for (ai = 0; ai < this.globalLength; ai++) {
            ag = parseInt(this.container.childNodes[ai].getAttribute("data-item"));
            if (aj == 0) {
              this.last = ag;
              break
            }
            aj += this.items[ag].size[this.p_.size]
          }
          return af
        }).jBind(this),
        aa = j(function(af) {
          W = true;
          j(document.body).jAddClass("mcs-dragging");
          this.o.stopDownload = false;
          X = true;
          clearTimeout(this.moveTimer);
          if (this.o.effect == "animation") {
            this.stopEffect()
          }
          this.stopWhell && this.stopWhell();
          L = {
            x: 0,
            y: 0
          };
          F = (this.p_.pos == "left") ? "x" : "y";
          this.jCallEvent("drag-start");
          this.container.jRemoveEvent("transitionend");
          this.containerPosition = this.getMatrixPosition(this.container)[F];
          L[F] = this.containerPosition;
          this.container.jSetCssProp(g, "translate3d(" + L.x + "px, " + L.y + "px, 0)");
          this.container.jSetCssProp("transition", "none");
          this._render();
          this.o.effect == "scroll" && (S = true);
          this.move_ = true
        }).jBind(this),
        E = j(function() {
          if (this.o.effect == "animation") {
            this.container.jSetCssProp("transition", "none");
            this.globalIndex = this._getGlobalIndex()
          }
          if (this.o.effect == "animation") {
            this.last = parseInt(this.container.childNodes[this._getGlobalIndex()].getAttribute("data-item"))
          }
          if ("infinite" === this.o.loop) {
            this.changeClones()
          }
          this.move_ = false;
          this.wheel_ = false;
          S = false;
          X = true;
          this.preloadItem();
          this.jCallEvent("drag-end", {
            arr: this.getVisibleIndexes(this.loop.lastItem)
          })
        }).jBind(this),
        T = j(function(ag) {
          j(document.body).jRemoveClass("mcs-dragging");
          if (W) {
            W = false;
            var af = this.containerPosition;
            if (!X) {
              ag.returnValue = false;
              P();
              K = ag.timeStamp - ae;
              if (this.o.effect == "scroll") {
                if (K > 200) {
                  J = ad;
                  S = false
                } else {
                  J = G(Math.abs(O[F] - ag[F]))
                }
                ad = J;
                if ("infinite" === this.o.loop) {
                  this.distance = Math.abs(ad);
                  this._shiftContainer(ac)
                }
                if ("infinite" === this.o.loop || this.containerPosition <= 0) {
                  if (Math.abs(this.containerPosition) < ad) {
                    ad = Math.abs(this.containerPosition)
                  }
                  this.containerPosition -= ad
                }
                ac == "forward" ? this.containerPosition -= this.correctItemPosition(ac) : this.containerPosition += this.correctItemPosition(ac);
                if (!this.o.loop || "rewind" === this.o.loop) {
                  this.jCallEvent("enable");
                  this.loop.firstItem = false;
                  this.loop.lastItem = false;
                  if (this.containerPosition > 0) {
                    this.containerPosition = 0;
                    this.last = 0;
                    S = true;
                    this.jCallEvent("first-frame");
                    this.loop.firstItem = true
                  }
                  if (this.containerPosition < this.containerWidth - this.allSize) {
                    this.containerPosition = this.containerWidth - this.allSize;
                    this.last = this.l - 1;
                    S = true;
                    this.jCallEvent("last-frame");
                    this.loop.lastItem = true
                  }
                }
                V = S ? 600 : 300
              } else {
                S = true;
                this.distance = 0;
                this.containerPosition = I();
                "infinite" === this.o.loop && this._shiftContainer(ac);
                if (K < 200) {
                  this.distance = this.containerWidth;
                  "infinite" === this.o.loop && this._shiftContainer(ac);
                  if (ac == "forward") {
                    this.containerPosition -= this.containerWidth
                  } else {
                    this.containerPosition += this.containerWidth
                  }
                }
                if (!this.o.loop || "rewind" === this.o.loop) {
                  this.jCallEvent("enable");
                  this.loop.firstItem = false;
                  this.loop.lastItem = false;
                  if (this.containerPosition >= 0) {
                    this.containerPosition = 0;
                    this.last = 0;
                    this.loop.firstItem = true;
                    this.jCallEvent("first-frame")
                  }
                  if (this.containerPosition <= (Math.ceil(this.l / this.itemStep) - 1) * this.containerWidth * (-1)) {
                    this.containerPosition = (Math.ceil(this.l / this.itemStep) - 1) * this.containerWidth * (-1);
                    this.last = this.l - 1;
                    this.loop.lastItem = true;
                    this.jCallEvent("last-frame")
                  }
                }
                V = 500
              }
              L[F] = this.containerPosition;
              this.container.jAddEvent("transitionend", j(function(ah) {
                if (ah.target == this.container) {
                  E()
                }
              }).jBind(this));
              if (af == this.containerPosition) {
                this.move_ = false;
                S = false;
                X = true
              }
              this.container.jSetCssProp("transition", g + " " + V + "ms cubic-bezier(.22,.63,.49,.8)");
              this.container.jSetCssProp(g, "translate3d(" + L.x + "px, " + L.y + "px, 0)")
            } else {
              if (!u.browser.mobile) {
                E()
              } else {
                this.move_ = false
              }
            }
          }
        }).jBind(this),
        N = 0,
        P = j(function() {
          clearTimeout(Y);
          Y = null;
          R = false;
          N = 0
        }).jBind(this),
        ab = j(function() {
          var af = N * 0.2;
          if (Math.abs(af) < 0.0001) {
            P();
            return
          }
          N -= af;
          this.containerPosition -= af;
          L[F] = this.containerPosition;
          this.container.jSetCssProp(g, "translate3d(" + L.x + "px, " + L.y + "px, 0)");
          Y = setTimeout(ab, 16)
        }).jBind(this),
        Z = j(function(ag) {
          if (W) {
            var af = ag[F] - Q > 0 ? "backward" : "forward";
            X = false;
            if ("infinite" === this.o.loop) {
              this.distance = Math.abs(ad);
              this._shiftContainer(af)
            }
            if (u.browser.ieMode) {
              N += ad;
              if (!R) {
                R = true;
                ab()
              }
            } else {
              this.container.jSetCssProp("transition", g + " 0ms");
              if (this.o.effect == "animation") {}
              this.containerPosition -= ad;
              L[F] = this.containerPosition;
              this.container.jSetCssProp(g, "translate3d(" + L.x + "px, " + L.y + "px, 0)")
            }
            this.freeTouchPreload(af)
          }
        }).jBind(this);
      this.onDrag = j(function(af) {
        if (this.stopScroll || this.o.effect == "animation" && S) {
          return
        }
        if ("dragstart" == af.state) {
          ae = af.timeStamp;
          O.x = af.x;
          O.y = af.y;
          Q = af[F]
        } else {
          ac = (ad > 0) ? "forward" : "backward";
          ad = Q - af[F];
          this.moveSettings.direction = ac;
          if ("dragend" == af.state) {
            if (H) {
              H = false;
              T(af)
            }
          } else {
            if (this.o.orientation == "vertical" || Math.abs(af.x - O.x) > Math.abs(af.y - O.y)) {
              af.stopDefaults();
              if (!H) {
                if (this.o.effect == "animation" && this.move_) {
                  return
                }
                H = true;
                aa(af)
              } else {
                Z(af)
              }
            }
          }
        }
        Q = af[F]
      }).jBind(this);
      if (!u.browser.ieMode || u.browser.ieMode && u.browser.ieMode > 9) {
        this.container.parentNode.jAddEvent("mousedrag touchdrag", this.onDrag)
      }
    },
    _initOnWheel: function() {
      var I, J, F = 0,
        H = {
          x: 0,
          y: 0
        },
        G = (this.p_.pos == "left") ? "x" : "y",
        E = j(function(L) {
          var K = F * (L || 0.2);
          I = K > 0 ? "forward" : "backward";
          F -= K;
          if (Math.abs(K) < 0.00001) {
            clearTimeout(this.moveTimer);
            this.last = parseInt(this.container.childNodes[this._getGlobalIndex()].getAttribute("data-item"));
            this.changeClones();
            this.wheelDiff = this._getWheelDiff();
            this.prevIndex = this.last;
            F = 0;
            this.distance = 0;
            this.moveTimer = null;
            this.wheel_ = false;
            this.move_ = false;
            this.jCallEvent("drag-end", {
              arr: this.getVisibleIndexes(this.loop.lastItem)
            });
            return
          }
          this.distance = Math.abs(K);
          "infinite" === this.o.loop && this._shiftContainer(I);
          this.containerPosition -= K;
          this.distance = 0;
          this.freeTouchPreload(I);
          if (!this.o.loop || "rewind" === this.o.loop) {
            if (this.containerPosition > 0) {
              this.containerPosition = 0;
              F = 0.00001;
              this.jCallEvent("first-frame")
            } else {
              if (this.containerPosition < this.containerWidth - this.allSize) {
                this.containerPosition = this.containerWidth - this.allSize;
                F = 0.00001;
                this.jCallEvent("last-frame")
              } else {
                this.jCallEvent("enable")
              }
            }
          }
          H[G] = this.containerPosition;
          this.container.jSetCssProp(g, "translate3d(" + H.x + "px, " + H.y + "px, 0)");
          this.moveTimer = setTimeout(E.jBind(this, L), 30)
        }).jBind(this);
      if (u.browser.ieMode && u.browser.ieMode < 10 || this.stopScroll) {
        return
      }
      this.stopWhell = j(function() {
        if (this.wheel_) {
          clearTimeout(this.moveTimer);
          F = 0;
          this.distance = 0;
          this.moveTimer = null;
          this.wheel_ = false;
          this.move_ = false
        }
      }).jBind(this);
      this.container.jAddEvent("mousescroll", j(function(K) {
        var L = (Math.abs(K.deltaY) < Math.abs(K.deltaX) ? K.deltaX : K.deltaY * (!K.isMouse ? -1 : -30));
        if (this.move_) {
          return
        }
        if ((true === this.o.scrollOnWheel && K.isMouse) || "vertical" === this.o.orientation && Math.abs(K.deltaY) > Math.abs(K.deltaX) || "horizontal" === this.o.orientation && Math.abs(K.deltaY) < Math.abs(K.deltaX)) {
          K.stop();
          this.wheel_ = true;
          if (0 === F) {
            this.container.jSetCssProp("transition", g + " 0ms");
            H = {
              x: 0,
              y: 0
            };
            G = (this.p_.pos == "left") ? "x" : "y"
          }
          this.jCallEvent("drag-start");
          F += L;
          if (!this.moveTimer) {
            E(0.4)
          }
        }
      }).jBind(this))
    },
    _getWheelDiff: function() {
      var F, E, G = this.containerPosition,
        H = j(["tempArray", "items", "itemsLastClones"]);
      this.tempArray = [];
      this.itemsFirstClones.jEach(j(function(I) {
        this.tempArray.push(I)
      }).jBind(this));
      this.tempArray.reverse();
      for (F = 0; F < H.length; F++) {
        for (E = 0; E < this[H[F]].length; E++) {
          G += this.items[this[H[F]][E].index].size[this.p_.size];
          if (G > 0) {
            this.last = this[H[F]][E].index;
            this.tempArray = null;
            return G
          }
        }
      }
    },
    pause: function() {
      var E, F;
      if (!this.o.continuous || this.continuousPause || !this.move_ || this.o.effect == "animation") {
        return
      }
      this.continuousPause = true;
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        this.fx && (this.fx.options.onComplete = u.$F);
        this.fx && this.fx.stop();
        this.fx = null;
        this.containerPosition = Math.round(parseInt(this.container.jGetCss(this.p_.pos)))
      } else {
        this.containerPosition = this.getMatrixPosition(this.container)[(this.p_.pos == "left") ? "x" : "y"]
      }
      E = this.correctItemPosition(this.direction);
      F = this.o.duration / this.distance * E;
      if (this.direction == "forward") {
        this.containerPosition -= E
      } else {
        this.containerPosition += E
      }
      this._move(F)
    },
    stop: function() {
      this.stop_ = true;
      this.move_ = false;
      this.stopWhell && this.stopWhell();
      if (this.o.effect == "animation") {
        this.stopEffect()
      }
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        this.fx && this.fx.stop(true);
        this.fx = null
      } else {
        this._cleansingStyles()
      }
    },
    stopEffect: function() {
      var E = {
        x: 0,
        y: 0
      };
      if (!u.browser.ieMode || u.browser.ieMode && u.browser.ieMode > 10) {
        E[this.p_.pos] = this.containerPosition;
        this.container.removeAttribute("data-forward");
        this.container.removeAttribute("data-backward");
        j([this.exitItems, this.enterItems]).jEach(j(function(F, G) {
          if (F.length > 0) {
            F.jEach(j(function(I, H) {
              I.jRemoveEvent(v + "AnimationStart animationstart " + v + "AnimationEnd animationend");
              I.removeAttribute("data-animation-nth");
              I.removeAttribute("data-action");
              if (!G) {
                I.removeAttribute("data-exiting");
                I.removeAttribute("data-exited")
              } else {
                I.removeAttribute("data-entering")
              }
            }).jBind(this))
          }
        }).jBind(this));
        this.container.jSetCssProp(g, "translate3d(" + E.left + "px, " + E.top + "px, 0)");
        this.move_ = false;
        this._render()
      }
    },
    onResize: function() {
      var F, G, E, H;
      this.stop();
      this.continuousPause = false;
      this.wrapperPosition = j(this.container.parentNode).jGetPosition();
      this.containerWidth = this._sWidth();
      this.itemsVisible = 0;
      this.allSize = 0;
      for (F = 0; F < this.l; F++) {
        this.items[F].size = this.items[F].node.jGetSize(true);
        this.allSize += this.items[F].size[this.p_.size];
        if (this.allSize <= this.containerWidth) {
          this.itemsVisible += 1
        }
      }
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        this.last = 0
      } else {
        this.correctContainerPosition()
      }
      this.distance = 0;
      this.itemStep = this.o.step;
      if (this.allSize <= this.containerWidth) {
        this.stopScroll = true;
        this.jCallEvent("hideArrows");
        this.jCallEvent("disable");
        this.correctPosition = 0;
        this.containerPosition = 0;
        if (u.browser.ieMode && u.browser.ieMode < 10) {
          this.container.jSetCssProp(this.p_.pos, 0)
        } else {
          this.container.jSetCssProp(g, "translate3d(0px, 0px, 0)")
        }
        this._removeClones()
      } else {
        this.stopScroll = false;
        this.jCallEvent("showArrows");
        this.jCallEvent("enable");
        if (!this.o.loop || "rewind" === this.o.loop) {
          if (this.loop.firstItem) {
            this.jCallEvent("first-frame")
          }
          if (this.loop.lastItem) {
            this.jCallEvent("last-frame")
          }
        }
      }
      if ((this.allSize > this.containerWidth) && ("infinite" === this.o.loop || this.o.continuous)) {
        this._prepareClones()
      } else {
        this.fullViewedItems = G = 0;
        for (F = 0; F < this.l; F++) {
          G += this.items[F].size[this.p_.size];
          this.fullViewedItems++;
          if (this.containerWidth <= G) {
            break
          }
        }
      }
      this._shiftContainer("forward");
      this.container.jRemoveEvent("transitionend");
      this.globalIndex = this._getGlobalIndex();
      this.globalLength = this.container.childNodes.length;
      this.setItemStep();
      this.changeClones();
      this.allNodes = u.$A(this.container.childNodes);
      this.o.lazyLoad ? this.preloadItem() : this.preloadAll()
    },
    correctContainerPosition: function() {
      var G, I, H = {
          left: 0,
          top: 0
        },
        F = this.items[this.last].node.jGetPosition()[this.p_.pos],
        E = this.container.parentNode.jGetPosition()[this.p_.pos];
      if (u.browser.ieMode && u.browser.ieMode < 10) {} else {
        if (!this.o.loop && this.loop.lastItem) {
          if ("scroll" === this.o.effect) {
            H[this.p_.pos] = this.containerWidth - this.allSize
          } else {
            I = this.itemsVisible - this.l % this.itemsVisible;
            H[this.p_.pos] = this.containerWidth - (this.allSize + this.items[0].size[this.p_.size] * I)
          }
        } else {
          G = this.getMatrixPosition(this.container)["left" === this.p_.pos ? "x" : "y"];
          H[this.p_.pos] = G - (F - E)
        }
        this.containerPosition = H[this.p_.pos];
        this.container.jSetCssProp(g, "translate3d(" + H.left + "px, " + H.top + "px, 0)")
      }
    },
    rightQueue: function(F) {
      var L = 0,
        K = true,
        G = this.l - 1,
        H = j(["itemsLastClones", "items", "itemsFirstClones"]),
        J = j(function(P, N) {
          var M, O = null;
          for (M = 0; M < P.length; M++) {
            if (P[M].index == N) {
              O = P[M].node;
              break
            }
          }
          return O
        }).jBind(this),
        I = j(function(M) {
          return (L == 0) ? M - 1 : (L - 1)
        }).jBind(this),
        E = j(function(P, N) {
          var O, M = P.length;
          if (M > 0) {
            for (O = 0; O < M; O++) {
              if (K) {
                K = false;
                L = M - 1;
                this.container.appendChild(P[L].node)
              } else {
                this.container.insertBefore(J(P, !L ? G : I(M)), J(!L ? this[H[N - 1]] : P, L));
                L = !L ? G : L - 1
              }
            }
          }
        }).jBind(this);
      H.jEach(j(function(M, N) {
        E(this[M], N);
        L = 0
      }).jBind(this));
      if (!F) {
        this.last = 0
      }
    },
    _removeClones: function() {
      this.itemsFirstClones.jEach(function(E) {
        E.node.kill()
      });
      this.itemsFirstClones = j([]);
      this.itemsLastClones.jEach(function(E) {
        E.node.kill()
      });
      this.itemsLastClones = j([])
    },
    update: function(F) {
      var E = {
        left: 0,
        top: 0
      };
      this.stop();
      if (F) {
        this.containerPosition = this.last = 0
      }
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        this.container.jSetCss(E)
      } else {
        if (F) {
          this.container.jSetCssProp(g, "translate3d(" + E.left + "px, " + E.top + "px, 0)")
        } else {
          this.correctContainerPosition()
        }
      }
      this.itemStep = this.o.step;
      if ((!this.o.continuous && (!this.o.loop || "rewind" === this.o.loop)) && this.itemsFirstClones.length > 0) {
        this.correctPosition = 0;
        this._removeClones()
      }
      this.onResize();
      this.rightQueue(!F);
      if (F) {
        this.container.parentNode.jRemoveEvent("mousedrag touchdrag", this.onDrag);
        if (this.o.draggable) {
          this.container.parentNode.jAddEvent("mousedrag touchdrag", this.onDrag)
        }
      }
      this.move_ = false
    },
    setNewOptions: function(E) {
      for (var F in E) {
        this.o[F] = E[F]
      }
      this._setProperties()
    },
    dispose: function() {
      this.stop();
      this._removeClones();
      j(window).jRemoveEvent("resize");
      j(document).jRemoveEvent("keydown");
      this.container.jRemoveEvent("touchdrag mousedrag");
      this.items.jEach(j(function(E) {
        E.node.jRemoveEvent("mouseover mouseout");
        delete E.content.showThis
      }).jBind(this))
    }
  };
  u.extend(b.prototype, u.customEvents);
  u.Scroll.Effect = b;
  var x = function(E, F) {
    u.Scroll.Effect.apply(this, arguments);
    this._options = {
      radius: "auto",
      gradientBezier: j([0.44, 0.59, 0.35, 0.89]),
      startAlpha: 255,
      timingFunction: "cubic-bezier(.8, 0, .5, 1)"
    };
    this.name = "carousel";
    this.o = this._options;
    u.extend(this.o, F);
    this.distance = 70;
    this.lastAngle = 0;
    this.nextAngle = 0;
    this.moveTimer = null;
    this.fxk = Math.pow(10, 8);
    this.circle = 2 * Math.PI;
    this.last = 0;
    this.getVisibleItems = j([]);
    this.originSize = null;
    this.angle = null;
    this.endItem = null;
    this.radius = 0;
    this.l = 0;
    this.originFontSize = null
  };
  u.inherit(x, u.Scroll.Effect);
  u.extend(x.prototype, {
    constructor: x,
    _prepareClones: u.$F,
    changeClones: u.$F,
    getVisibleIndexes: u.$F,
    _scroll: u.$F,
    pause: u.$F,
    resetZIndex: u.$F,
    performedOnClones: u.$F,
    cloneFigure: u.$F,
    preloadItem: u.$F,
    _getWheelDiff: u.$F,
    gradientBezier: u.extend({}, u.FX.prototype),
    _shiftContainer: function() {
      this.lastAngle %= this.circle;
      this.nextAngle = this.lastAngle
    },
    done: function(G) {
      var F, E;
      if (this.doneFlag.one) {
        return
      }
      this.doneFlag.one = true;
      E = this.l = this.items.length;
      this.containerWidth = this._sWidth();
      if (u.browser.ieMode && u.browser.ieMode < 10 && this.items[0].content.length && this.items[0].content.lastChild.tagName.toLowerCase() == "figcaption") {
        this.originFontSize = parseInt(this.items[0].content.lastChild.jGetCss("font-size"))
      }
      this.gradientBezier.cubicBezier = this.o.gradientBezier;
      for (F = 0; F < this.l; F++) {
        this.items[F].size = this.items[F].node.jGetSize(true, true);
        this.allSize += this.items[F].size[this.p_.size];
        this.items[F].node.jSetCssProp("position", "absolute");
        this.items[F].img = this.getImg(this.items[F])
      }
      if ("infinite" === this.o.loop) {
        this.jCallEvent("enable")
      }
      this.items.jEach(j(function(H) {
        if (H.figcaption && !H.captionA) {
          if (H.content.tagName.toLowerCase() != "figure") {
            H.captionA = true
          }
        }
      }).jBind(this));
      this.onResize();
      this.preloadAll()
    },
    done2: function(E) {
      this.doneFlag.two = true;
      this.itemEvent();
      this.angle = this.circle / this.l;
      this.endItem = (this.circle - this.angle) * (-1);
      this.itemStep = 1;
      this._initDragOnScroll();
      this.o.scrollOnWheel && this._initOnWheel();
      j(window).jAddEvent("resize", this.onResize.jBind(this));
      if (this.o.keyboard) {
        j(document).jAddEvent("keydown", this.keyboardCallback)
      }
      E && E();
      this.onResize()
    },
    itemEvent: function() {
      x.$parent.itemEvent.apply(this);
      this.items.jEach(j(function(E) {
        E.node.jAddEvent("click", j(function(F) {
          this.jCallEvent("item-click", {
            index: E.index
          })
        }).jBind(this))
      }).jBind(this))
    },
    showReflection: function(F) {
      var E = 1000;
      if (u.browser.ieMode && u.browser.ieMode < 10 || !F.canvas) {
        return
      }
      F.canvas.jSetOpacity(1);
      F.canvas.jSetCssProp("transition", "opacity " + E + "ms")
    },
    setCanvasPosition: function(G) {
      var E, F, H = j(function(I) {
        if (I.canvas || I.captionA) {
          E = I.img.jGetSize(false, true);
          F = I.img.offsetTop + E.height;
          if (I.canvas) {
            I.canvas.jSetCss({
              top: F,
              left: I.img.offsetLeft,
              width: E.width
            })
          }
          if (I.captionA && I.figcaption) {
            I.figcaption.jSetCss({
              top: F
            })
          }
        }
      }).jBind(this);
      G ? H(G) : this.items.jEach(j(function(I) {
        H(I)
      }).jBind(this))
    },
    getImg: function(G) {
      var E, F = G.content;
      if (F.tagName == "IMG") {
        E = F
      } else {
        if (F.firstChild.tagName == "IMG") {
          E = F.firstChild
        } else {
          if (F.firstChild.tagName == "FIGURE" && F.firstChild.firstChild.tagName == "IMG") {
            E = F.firstChild.firstChild
          } else {
            E = null
          }
        }
      }
      if (E) {
        j(E).jSetCssProp("z-index", 100)
      }
      return E
    },
    setReflection: function(R) {
      if (this.o.orientation == "vertical") {
        return
      }
      var G = u.$new("canvas", {}, {
          opacity: 0
        }),
        S = u.$new("canvas"),
        F, E, J, P, O, Q, T = 1,
        M, N, K, H, L, I;
      if (u.browser.ieMode && u.browser.ieMode < 10) {
        return
      }
      if (G.getContext) {
        F = G.getContext("2d");
        E = S.getContext("2d");
        if (!R.img) {
          return
        }
        O = j(R.img).jGetSize(false, true);
        Q = O.height / 100 * 30;
        S.width = O.width;
        S.height = O.height;
        E.save();
        E.scale(1, -1);
        E.drawImage(R.img, 0, O.height * (-1), O.width, O.height);
        J = E.getImageData(0, 0, O.width, Q);
        E.restore();
        G.width = O.width;
        G.height = Q;
        F.save();
        N = J.data;
        I = N.length;
        H = I / 4 / O.width;
        K = this.o.startAlpha;
        M = I / H;
        for (L = 3; L < I; L += 4) {
          if (L > M) {
            M += (I / H);
            T++;
            K = Math.round(this.o.startAlpha - this.o.startAlpha * this.gradientBezier.cubicBezierAtTime(1 / (H / T)))
          }
          N[L] = K
        }
        F.putImageData(J, 0, 0);
        F.restore();
        R.canvas = G;
        if ((!R.content.childNodes || R.content.childNodes.length < 2) && R.content.tagName.toLowerCase() !== "a") {
          R.node.appendChild(G)
        } else {
          R.content.insertBefore(G, R.content.childNodes[1])
        }
        G.jAddClass("mcs-reflection")
      }
    },
    showCaption: function(F) {
      var G = 0,
        E = this.distance / (this.l / 2),
        H = 100 - E;
      if (F > H) {
        G = (F - H) / E
      }
      return G
    },
    renderCarousel: function(L) {
      var I = {
          left: 0,
          top: 0
        },
        G = {
          left: 0,
          top: 0
        },
        S = {
          left: 0,
          top: 0
        },
        M, R, Q = this.l,
        N = this.distance,
        E = this.circle / Q,
        P, H, F, K, J, O;
      I[this.p_.pos] = this.radius;
      u.defined(L) || (L = 0);
      this.lastAngle = L;
      for (J = 0; J < Q; J++) {
        H = F = J * E + L;
        F %= this.circle;
        H %= this.circle;
        if (F != 0 && F != Math.PI) {
          if (Math.ceil(Math.abs(F) / Math.PI) % 2 == 0) {
            if (Math.abs(F) % Math.PI != 0) {
              H = Math.PI - (Math.abs(F) % Math.PI)
            }
          } else {
            H = Math.abs(F)
          }
        }
        H = Math.abs(H * 100 / Math.PI);
        if (this.items[J].figcaption) {
          this.items[J].figcaption.jSetOpacity(this.showCaption(100 - (H * N / 100)))
        }
        H = 100 - Math.round(H * N / 100);
        !this.originSize && (this.originSize = this.items[J].size);
        M = Math.abs(F);
        if (M > Math.PI / 2 && M < Math.PI + Math.PI / 2) {
          if (M > Math.PI) {
            M = Math.PI / 2 - Math.abs(M - Math.PI)
          } else {
            M = M - Math.PI / 2
          }
          M = (1 - Math.sin(M)) * 0.7
        } else {
          M = 1
        }
        if (u.browser.ieMode && u.browser.ieMode < 10) {
          K = {
            width: this.setItemSide("width", H),
            height: this.setItemSide("height", H)
          };
          this.items[J].node.jSetCss(K);
          this.items[J].node.jSetCss({
            top: Math.sin(F) * I.top + parseInt(this.containerSize.height) / 2 - parseInt(K.height) / 2,
            left: Math.sin(F) * I.left + parseInt(this.containerSize.width) / 2 - parseInt(K.width) / 2
          });
          if (this.items[J].content.length && this.items[J].content.lastChild.tagName.toLowerCase() == "figcaption") {
            this.items[J].content.lastChild.style.fontSize = this.setFontSize(H / 100 * H)
          }
          if (this.items[J].captionA) {
            P = this.items[J].img.jGetSize(false, true);
            this.items[J].figcaption.jSetCss({
              top: this.items[J].img.offsetTop + P.height
            })
          }
        } else {
          S[this.p_.pos] = 360 / this.circle * F;
          this.o.orientation == "vertical" && (S[this.p_.pos] *= (-1));
          O = Math.abs(F);
          R = Math.sqrt(1 - Math.sin(O) * Math.sin(O));
          if (O > Math.PI / 2 && O < Math.PI + Math.PI / 2) {
            O = this.radius * (R) + this.radius
          } else {
            O = this.radius * (1 - R)
          }
          O > 0 && (O *= (-1));
          G[this.p_.pos] = (Math.sin(F) * I[this.p_.pos] + parseInt(this.containerSize[this.p_.size]) / 2 - this.items[J].size[this.p_.size] / 2);
          this.items[J].node.jSetCssProp(g, "translateX(" + G.left + "px)translateY(" + G.top + "px)translateZ(" + O + "px)rotateX(" + S.top + "deg)rotateY(" + S.left + "deg)")
        }
        this.items[J].node.jSetCssProp("z-index", 0 + H);
        this.items[J].node.jSetOpacity(M)
      }
    },
    round: function(E, F) {
      var G = Math.pow(10, F || 15);
      return Math.round(E * G) / G
    },
    _calcDistance: function(H) {
      var F, G, E = 360 / this.l;
      if (H.defaultMove) {
        if (H.goTo) {
          if (H.direction == "forward" && this.last > H.target) {
            F = this.l - this.last;
            F += H.target
          } else {
            if (H.direction == "backward" && this.last < H.target) {
              F = this.l - H.target;
              F += this.last
            }
          }!F && (F = Math.abs(this.last - H.target));
          this.last = H.target
        } else {
          F = this.itemStep;
          this.last = this._getItemIndex(H.direction == "forward" ? this.last + F : this.last - F)
        }
      } else {
        G = (360 - this.last * E + H.target * E) % 360;
        if (G >= 0 && G <= 180) {
          !H.direction && (H.direction = "forward")
        } else {
          if (G >= 180 && G <= 360) {
            !H.direction && (H.direction = "backward")
          }
        }
        if (H.direction == "forward") {
          F = Math.round(G / E)
        } else {
          F = Math.round((360 - G) / E)
        }
        this.last = H.target
      }
      return u.extend(H, {
        angle: F * this.angle
      })
    },
    _carousel: function(F) {
      var E;
      F = this._calcDistance(F);
      E = F.angle;
      if (!this.o.loop) {
        this.jCallEvent("enable")
      }
      if (F.direction == "forward") {
        this.nextAngle -= E;
        if (!this.o.loop) {
          if (this.nextAngle == this.endItem) {
            this.jCallEvent("last-frame")
          } else {
            if (this.nextAngle < this.endItem) {
              this.last = 0;
              this.nextAngle = 0;
              this.jCallEvent("first-frame")
            }
          }
        }
      } else {
        this.nextAngle += E;
        if (!this.o.loop) {
          if (this.nextAngle == 0) {
            this.jCallEvent("first-frame")
          } else {
            if (this.nextAngle > 0) {
              this.last = this.l - 1;
              this.nextAngle = this.endItem;
              this.jCallEvent("last-frame")
            }
          }
        }
      }
      this.jCallEvent("on-start-effect", {
        arr: [this.last]
      });
      this.callback = F.callback;
      this._move(this.nextAngle)
    },
    setItemSide: function(E, F) {
      return this.originSize[E] / 100 * F
    },
    setFontSize: function(E) {
      return Math.round(this.originFontSize / 100 * E) + "px"
    },
    _move: function(E) {
      this.fx = new u.FX(this.container, {
        duration: this.o.duration,
        transition: this.o.timingFunction,
        onBeforeRender: (function(F) {
          this.renderCarousel(F.angle / this.fxk)
        }).jBind(this),
        onComplete: j(function() {
          this._onComplete()
        }).jBind(this)
      }).start({
        angle: [this.fxk * this.lastAngle, this.fxk * E]
      })
    },
    _onComplete: function() {
      this._shiftContainer();
      x.$parent._onComplete.apply(this)
    },
    _move2: function(F) {
      var E = Math.abs(this.nextAngle - this.lastAngle) * (F || 0.2);
      if (Math.abs(E) < 0.00001) {
        clearTimeout(this.moveTimer);
        this.moveTimer = null;
        this.move_ = false;
        this.jCallEvent("drag-end", {
          arr: [this.last]
        });
        return
      }
      if (this.nextAngle < this.lastAngle) {
        E *= (-1)
      }
      this.renderCarousel(this.lastAngle + E);
      this.moveTimer = setTimeout(this._move2.jBind(this, F), 30)
    },
    searchIndex: function() {
      var G, H = this.nextAngle % this.circle,
        F = parseInt(Math.abs(this.nextAngle / this.circle)),
        J, I, E = j(function(K) {
          while (F != 0) {
            F--;
            if (H <= 0) {
              K -= this.circle
            } else {
              K += this.circle
            }
          }
          return K
        }).jBind(this);
      for (G = 0; G < this.l; G++) {
        J = (G * this.circle) / this.l;
        I = ((G + 1) * this.circle) / this.l;
        if (H <= 0) {
          J *= (-1);
          I *= (-1)
        } else {
          J = this.circle - J;
          I = this.circle - I
        }
        if (J != H) {
          if (J > H && H > I) {
            if (Math.abs(H - J) <= Math.abs(I - H)) {
              this.nextAngle = E(J);
              this.last = G
            } else {
              this.nextAngle = E(I);
              this.last = this._getItemIndex(G + 1)
            }
          }
        } else {
          this.last = G
        }
      }
    },
    _initOnWheel: function() {
      var G, F, E = this.circle / 360 * 15;
      this.container.jAddEvent("mousescroll", j(function(H) {
        if (true === this.o.scrollOnWheel || H.isMouse || "vertical" === this.o.orientation && Math.abs(H.deltaY) > Math.abs(H.deltaX) || "horizontal" === this.o.orientation && Math.abs(H.deltaY) < Math.abs(H.deltaX)) {
          this.jCallEvent("drag-start");
          this.fx && this.fx.stop(true);
          this.fx = null;
          H.stop();
          if (u.browser.ieMode && u.browser.ieMode < 10) {
            H.isMouse = true
          }
          F = Math.abs(H.deltaY) < Math.abs(H.deltaX) ? H.deltaX : -1 * H.deltaY;
          F = H.isMouse ? (F * E) : (F * (8 / 864));
          !H.isMouse && (F = F > 0 ? Math.min(this.angle / 4, F) : Math.max(this.angle / 4 * (-1), F));
          this.nextAngle -= F;
          clearTimeout(G);
          G = setTimeout(j(function() {
            this.searchIndex()
          }).jBind(this), 100);
          if (!this.o.loop) {
            if (this.nextAngle >= 0) {
              this.jCallEvent("first-frame");
              this.nextAngle = 0;
              this.last = 0
            } else {
              if (this.nextAngle <= this.endItem) {
                this.jCallEvent("last-frame");
                this.nextAngle = this.endItem;
                this.last = this.l - 1
              }
            }
          }
          if (!this.moveTimer) {
            this._move2(0.08)
          }
        }
      }).jBind(this))
    },
    _initDragOnScroll: function() {
      var J = (this.p_.pos == "left") ? "x" : "y",
        L = {
          x: 0,
          y: 0
        },
        K = {
          x: 0,
          y: 0
        },
        I, F = false,
        H = "forward",
        E = false,
        G = j(function(M) {
          if ("dragstart" == M.state) {
            j(document.body).jAddClass("mcs-dragging");
            E = true;
            L.x = K.x = M.x;
            L.y = K.y = M.y
          } else {
            if (E) {
              L.x = M.x;
              L.y = M.y;
              if ("dragend" == M.state) {
                j(document.body).jRemoveClass("mcs-dragging");
                E = false;
                if (F) {
                  F = false;
                  this.searchIndex()
                }
              } else {
                if (this.o.orientation == "vertical" || Math.abs(M.x - K.x) > Math.abs(M.y - K.y)) {
                  M.stopDefaults();
                  if (!F) {
                    F = true;
                    this.move_ = true;
                    this.fx && this.fx.stop();
                    this.jCallEvent("drag-start");
                    clearTimeout(this.moveTimer);
                    this.moveTimer = null
                  }
                  H = K[J] < L[J] ? "backward" : "forward";
                  I = Math.abs(K[J] - L[J]) / this.radius;
                  if (H == "forward") {
                    this.nextAngle -= I;
                    if (!this.o.loop) {
                      if (this.nextAngle <= this.endItem) {
                        this.jCallEvent("last-frame");
                        this.nextAngle = this.endItem;
                        this.last = this.l - 1
                      }
                    }
                  } else {
                    this.nextAngle += I;
                    if (!this.o.loop) {
                      if (this.nextAngle >= 0) {
                        this.jCallEvent("first-frame");
                        this.nextAngle = 0;
                        this.last = 0
                      }
                    }
                  }!this.moveTimer && this._move2()
                }
                K.x = L.x;
                K.y = L.y
              }
            }
          }
        }).jBind(this);
      this.container.jAddEvent("touchdrag mousedrag", G)
    },
    stop: function() {
      this.fx && this.fx.stop(true);
      this.fx = null;
      clearTimeout(this.moveTimer);
      this.moveTimer = null;
      this.nextAngle && this.renderCarousel(this.nextAngle)
    },
    onResize: function() {
      var F, G, E, H;
      this.stop();
      this.containerWidth = this._sWidth();
      this.containerSize = this.container.parentNode.jGetSize(false, true);
      this.allSize = 0;
      for (F = 0; F < this.l; F++) {
        this.items[F].size = this.items[F].node.jGetSize(true, true);
        this.allSize += this.items[F].size[this.p_.size]
      }
      this.angle = 1 * this.circle / this.l;
      this.endItem = (this.circle - this.angle) * (-1);
      G = this.allSize / this.circle;
      this.radius = this.containerSize[this.p_.size] / 2;
      (this.radius < G) && (this.radius = G);
      (u.browser.ieMode && u.browser.ieMode < 10) && (this.radius -= (this.items[0].size[this.p_.size] / 2));
      this.lastAngle = this.nextAngle = 0;
      this.renderCarousel();
      this.setCanvasPosition();
      E = this.last;
      this.last = 0;
      H = this._calcDistance({
        target: E
      });
      if ("forward" === H.direction) {
        this.nextAngle -= H.angle
      } else {
        this.nextAngle += H.angle
      }
      this.renderCarousel(this.nextAngle)
    },
    update: function(E) {
      this.stop();
      this.last = 0;
      if (this.o.orientation == "vertical") {
        this.removeCanvas()
      } else {
        this.items.jEach(j(function(F) {
          if (!F.canvas) {
            this.setReflection(F)
          }
        }).jBind(this))
      }
      this.container.jRemoveEvent("touchdrag mousedrag mousescroll");
      this._initDragOnScroll();
      this.o.scrollOnWheel && this._initOnWheel();
      this.resetZIndex();
      this._setProperties();
      this.onResize();
      if (this.o.orientation == "horizontal") {
        this.items.jEach(j(function(F) {
          this.showReflection(F)
        }).jBind(this))
      }
      this.move_ = false
    },
    removeCanvas: function() {
      this.items.jEach(j(function(E) {
        if (E.canvas) {
          E.canvas.jRemove();
          delete E.canvas
        }
      }).jBind(this))
    },
    dispose: function() {
      x.$parent.dispose.apply(this);
      this.container.jRemoveEvent("mousescroll");
      this.removeCanvas();
      this.items.jEach(j(function(E) {
        E.node.jRemoveEvent("click")
      }).jBind(this))
    }
  });
  u.extend(x.prototype, u.customEvents);
  u.Scroll.Carousel = x;
  var d = function(E, F) {
    u.Scroll.Carousel.apply(this, arguments);
    this.name = "coverFlow";
    this.center = null;
    this.distance = null;
    this.moiety = null;
    this.lastPosition = null;
    this.nextPosition = null;
    this.depth = 350;
    this.itemStep = 1;
    this.moveTimer = null;
    this.firstSide = null;
    this.lastSide = null;
    this.stepDistance = null;
    this.lastItemLoad = 0
  };
  u.inherit(d, u.Scroll.Carousel);
  u.extend(d.prototype, {
    constructor: d,
    _shiftContainer: u.$F,
    _carousel: u.$F,
    showCaption: u.$F,
    setItemsPosition: function() {
      var E, G, F;
      this.stepDistance = this.moiety;
      if (this.o.orientation == "vertical") {
        F = this.moiety + this.moiety * 0.8;
        this.stepDistance /= 2
      } else {
        F = this.moiety * 2
      }
      for (E = 0; E < this.l; E++) {
        G = (E == 1) ? F : this.stepDistance;
        this.items[E].position = !E ? (this.center - this.moiety) : (this.items[E - 1].position + G)
      }
    },
    zIndex: function(E) {
      if (this.o.orientation == "horizontal") {
        return Math.round(this.allSize - Math.abs(this.center - (E.position + this.moiety)))
      }
    },
    done: function(G) {
      var F, E;
      if (this.one) {
        return
      }
      this.one = true;
      E = this.l = this.items.length;
      this.containerWidth = this._sWidth();
      this.gradientBezier.cubicBezier = this.o.gradientBezier;
      for (F = 0; F < this.l; F++) {
        this.items[F].size = this.items[F].node.jGetSize(true, true);
        this.allSize += this.items[F].size[this.p_.size];
        this.items[F].node.jSetCssProp("position", "absolute");
        this.items[F].img = this.getImg(this.items[F]);
        this.items[F].figcaption && j(this.items[F].figcaption).jSetOpacity(0)
      }
      this.o.loop = false;
      this.items.jEach(j(function(H) {
        if (H.figcaption && !H.captionA) {
          if (H.content.tagName.toLowerCase() != "figure") {
            H.captionA = true
          }
        }
      }).jBind(this));
      this.onResize();
      !this.o.lazyLoad && this.preloadAll()
    },
    done2: function(E) {
      this.doneFlag.two = true;
      this.itemEvent();
      this.itemStep = 1;
      this._initDragOnScroll();
      this.o.scrollOnWheel && this._initOnWheel();
      j(window).jAddEvent("resize", this.onResize.jBind(this));
      if (this.o.keyboard) {
        j(document).jAddEvent("keydown", this.keyboardCallback)
      }
      E && E();
      this.onResize()
    },
    zoom: function(M) {
      var K, F, L, J, G = 1,
        E, H = M.position + this.moiety,
        I = M.position + this.moiety <= this.center;
      J = I ? (this.center - H) : (H - this.center);
      J /= ((I ? (this.center - this.firstSide) : (this.lastSide - this.center)) / 100);
      F = (90 / 100 * J) * (Math.PI / 180);
      K = 60 * Math.sin(F);
      E = 1 - 1 * Math.sin(F);
      if (this.o.orientation == "horizontal") {
        !I && (K *= (-1))
      } else {
        K *= (-1);
        I && (G = 1 - 0.7 * Math.sin(F))
      }
      L = this.depth * Math.sin(F) * (-1);
      return {
        rotate: K,
        translateZ: L,
        opacity: G,
        captionOpasity: E
      }
    },
    calcItemPosition: function(I, K) {
      var G, F = false,
        J = false,
        E = I.position + this.moiety,
        L, H = {
          rotate: 60,
          translateZ: this.depth * (-1),
          opacity: 1
        };
      L = E - K;
      if (E >= this.lastSide) {
        if (E - K < this.lastSide) {
          G = E - this.lastSide;
          J = true;
          K -= G;
          if (K <= this.moiety) {
            K = (this.lastSide - this.center) / this.stepDistance * K
          } else {
            if (K <= this.moiety * 2) {
              K = (this.lastSide - this.firstSide) / (this.stepDistance * 2) * K
            } else {
              K += (this.moiety * 2);
              J = false
            }
          }
          I.position -= G
        }
        F = true;
        I.position -= K
      } else {
        if (E <= this.firstSide) {
          if (this.o.orientation == "vertical") {
            K = (this.lastSide - this.center) / this.stepDistance * K
          } else {
            if (E - K > this.firstSide) {
              J = true;
              G = this.firstSide - E;
              K += G;
              if (K >= this.moiety * (-1)) {
                K = (this.lastSide - this.center) / this.stepDistance * K
              } else {
                if (K >= this.moiety * 2 * (-1)) {
                  K = (this.lastSide - this.firstSide) / (this.stepDistance * 2) * K
                } else {
                  K -= (this.moiety * 2)
                }
              }
              I.position += G
            }
          }
          F = true;
          I.position -= K
        } else {
          if (E > this.firstSide && E < this.lastSide) {
            K = (this.lastSide - this.center) / this.stepDistance * K;
            if (E - K >= this.lastSide) {
              G = this.lastSide - E;
              K += G;
              K = this.stepDistance / ((this.lastSide - this.center) / K);
              I.position += G
            } else {
              if (E - K <= this.firstSide) {
                if (this.o.orientation == "horizontal") {
                  G = E - this.firstSide;
                  K -= G;
                  K = this.stepDistance / ((this.lastSide - this.center) / K);
                  I.position -= G
                }
              } else {
                J = true
              }
            }
            I.position -= K
          }
        }
      }
      if (this.o.orientation == "horizontal") {
        I.position > this.center && (H.rotate *= (-1))
      } else {
        H.rotate = 60 * (-1);
        I.position < this.center && (H.opacity = 0.3)
      }
      J && (H = this.zoom(I));
      F && (H.captionOpasity = 0);
      if (this.o.lazyLoad) {
        if (this.containerWidth > L - this.moiety && "notLoaded" === I.load) {
          this.lastItemLoad = I.index;
          I.load = "load";
          if (this.o.stopDownload) {
            this.jCallEvent("showProgress")
          } else {
            I.progress && I.progress.show()
          }
          this.queue.push({
            node: I.content,
            index: I.index
          })
        }
      }
      return H
    },
    onLazyLoad: function(E) {
      if (this.lastItemLoad === E - 1) {
        if (this.o.stopDownload || !this.doneFlag.two) {
          this.jCallEvent("hideProgress")
        }
        if (!this.doneFlag.two) {
          this.jCallEvent("complete")
        }
      }
    },
    renderCarousel: function(H) {
      var F, G, J, E, I = this.lastPosition - H;
      H || (H = 0);
      this.lastPosition = H;
      for (F = 0; F < this.l; F++) {
        J = {
          left: 0,
          top: 0
        };
        E = {
          left: 0,
          top: 0
        };
        G = this.calcItemPosition(this.items[F], I);
        J[this.p_.pos] = this.items[F].position;
        E[this.p_.pos] = G.rotate;
        this.items[F].node.jSetCssProp(g, "translate3d(" + J.left + "px, " + J.top + "px, " + G.translateZ + "px)rotateX(" + E.top + "deg)rotateY(" + E.left + "deg)");
        this.items[F].figcaption && this.items[F].figcaption.jSetOpacity(G.captionOpasity);
        if (this.o.orientation == "horizontal") {
          this.items[F].node.jSetCssProp("z-index", this.zIndex(this.items[F]))
        } else {
          this.items[F].node.jSetOpacity(G.opacity)
        }
      }
    },
    _calcDistance: function(F) {
      var E = this.itemStep;
      if (F.defaultMove) {
        F.goTo && (E = F.goTo);
        if (F.direction == "forward") {
          this.loop.firstItem = false;
          if (this.last + E > this.l - 1) {
            if (this.last != this.l - 1) {
              E = this.l - 1 - this.last;
              this.last += E;
              this.loop.lastItem = true
            } else {
              this.last = 0;
              E = this.l - 1;
              this.loop.firstItem = true;
              this.loop.lastItem = false;
              F.direction = "backward"
            }
          } else {
            this.last += E;
            if (this.last === this.l - 1) {
              this.loop.lastItem = true
            }
          }
        } else {
          this.loop.lastItem = false;
          if (this.last - E < 0) {
            if (this.last != 0) {
              E = this.last;
              this.last -= E;
              this.loop.firstItem = true
            } else {
              this.last = this.l - 1;
              E = this.l - 1;
              this.loop.firstItem = false;
              this.loop.lastItem = true;
              F.direction = "forward"
            }
          } else {
            this.last -= E;
            if (this.last === 0) {
              this.loop.firstItem = true
            }
          }
        }
      } else {
        !F.direction && (F.direction = F.target >= this.last ? "forward" : "backward");
        E = Math.abs(this.last - F.target);
        this.last = F.target
      }
      this.distance = this.stepDistance * E;
      return F.direction
    },
    _coverFlow: function(E) {
      E.direction = this._calcDistance(E);
      this.callback = E.callback;
      this.jCallEvent("on-start-effect", {
        arr: [this.last]
      });
      this._move(E.direction == "forward" ? this.lastPosition - this.distance : this.lastPosition + this.distance)
    },
    _move: function(E) {
      this.nextPosition = E;
      this.fx = new u.FX(this.container, {
        duration: 500,
        transition: this.o.timingFunction,
        onBeforeRender: (function(F) {
          this.renderCarousel(F.pos)
        }).jBind(this),
        onComplete: j(function() {
          this._onComplete()
        }).jBind(this)
      }).start({
        pos: [this.lastPosition, E]
      })
    },
    _move2: function(F) {
      var E = Math.abs(this.nextPosition - this.lastPosition) * (F || 0.2);
      if (Math.abs(E) < 0.01) {
        clearTimeout(this.moveTimer);
        this.moveTimer = null;
        this.move_ = false;
        this.jCallEvent("drag-end", {
          arr: [this.last]
        });
        return
      }
      if (this.nextPosition < this.lastPosition) {
        E *= (-1)
      }
      this.renderCarousel(this.lastPosition + E);
      this.moveTimer = setTimeout(this._move2.jBind(this, F), 30)
    },
    checkPosition: function(I, J) {
      var G, F = I.position + this.moiety,
        E = I.position,
        H = j(function(K) {
          if (F > this.firstSide && F < this.lastSide || K) {
            J = (this.lastSide - this.center) / this.stepDistance * J;
            if (F - J >= this.lastSide) {
              G = this.lastSide - F;
              J += G;
              J = this.stepDistance / ((this.lastSide - this.center) / J);
              E += G
            } else {
              if (F - J <= this.firstSide) {
                if (this.o.orientation == "horizontal") {
                  G = F - this.firstSide;
                  J -= G;
                  J = this.stepDistance / ((this.lastSide - this.center) / J);
                  E -= G
                }
              }
            }
            E -= J
          }
        }).jBind(this);
      if (F >= this.lastSide) {
        if (F - J < this.lastSide) {
          G = F - this.lastSide;
          J -= G;
          E -= G;
          H(true)
        } else {
          E -= J
        }
      } else {
        if (F <= this.firstSide) {
          if (this.o.orientation == "vertical") {
            J = (this.lastSide - this.center) / this.stepDistance * J
          }
          if (F - J > this.firstSide) {
            G = this.firstSide - F;
            J += G;
            E += G;
            H(true)
          } else {
            E -= J
          }
        } else {
          H()
        }
      }
      return E
    },
    searchIndex: function() {
      var G, F, E, H = this.lastPosition - this.nextPosition;
      if (this.o.orientation == "vertical") {
        H *= 2
      }
      for (G = 0; G < this.l; G++) {
        F = !F ? this.checkPosition(this.items[G], H) : E;
        E = (G + 1 < this.l) ? this.checkPosition(this.items[G + 1], H) : null;
        if (F + this.moiety > this.firstSide || G == this.l - 1) {
          if (E && E + this.moiety >= this.lastSide || !E) {
            E = 100000000
          }
          if (this.center - (F + this.moiety) < (E + this.moiety) - this.center) {
            this.last = G
          } else {
            this.last = G + 1
          }
          if (this.last === 0) {
            this.loop.firstItem = true
          } else {
            if (this.last === this.l - 1) {
              this.loop.lastItem = true
            }
          }
          this.nextPosition = this.center - this.last * this.stepDistance;
          break
        }
      }
    },
    _initOnWheel: function() {
      var F, E;
      this.container.jAddEvent("mousescroll", j(function(G) {
        if (true === this.o.scrollOnWheel || G.isMouse || "vertical" === this.o.orientation && Math.abs(G.deltaY) > Math.abs(G.deltaX) || "horizontal" === this.o.orientation && Math.abs(G.deltaY) < Math.abs(G.deltaX)) {
          this.jCallEvent("drag-start");
          this.fx && this.fx.stop();
          this.fx = null;
          G.stop();
          E = Math.abs(G.deltaY) < Math.abs(G.deltaX) ? G.deltaX : -1 * G.deltaY;
          E = G.isMouse ? (E * this.stepDistance) : (E * (8 / 13));
          !G.isMouse && (E = E > 0 ? Math.min(this.stepDistance / 4, E) : Math.max(this.stepDistance / 4 * (-1), E));
          this.nextPosition -= E;
          clearTimeout(F);
          F = setTimeout(j(function() {
            this.searchIndex()
          }).jBind(this), 100);
          if (this.nextPosition >= this.center) {
            this.nextPosition = this.center;
            this.last = 0
          } else {
            if (this.nextPosition <= this.center - ((this.l - 1) * this.stepDistance)) {
              this.nextPosition = this.center - ((this.l - 1) * this.stepDistance);
              this.last = this.l - 1
            }
          }
          if (!this.moveTimer) {
            this._move2(0.08)
          }
        }
      }).jBind(this))
    },
    _initDragOnScroll: function() {
      var H = (this.p_.pos == "left") ? "x" : "y",
        J = {
          x: 0,
          y: 0
        },
        I = {
          x: 0,
          y: 0
        },
        F = false,
        E = false,
        G = j(function(K) {
          if ("dragstart" == K.state) {
            j(document.body).jAddClass("mcs-dragging");
            E = true;
            J.x = I.x = K.x;
            J.y = I.y = K.y;
            this.loop.firstItem = false;
            this.loop.lastItem = false
          } else {
            if (E) {
              J.x = K.x;
              J.y = K.y;
              if ("dragend" == K.state) {
                j(document.body).jRemoveClass("mcs-dragging");
                E = false;
                if (F) {
                  this.searchIndex();
                  F = false
                }
              } else {
                if (this.o.orientation == "vertical" || Math.abs(K.x - I.x) > Math.abs(K.y - I.y)) {
                  K.stopDefaults();
                  if (!F) {
                    this.fx && this.fx.stop();
                    this.jCallEvent("drag-start");
                    clearTimeout(this.moveTimer);
                    this.move_ = true;
                    this.moveTimer = null;
                    F = true
                  }
                  this.nextPosition -= (I[H] - J[H]);
                  !this.moveTimer && this._move2()
                } else {
                  this.move_ = false
                }
                I.x = J.x;
                I.y = J.y
              }
            }
          }
        }).jBind(this);
      this.container.jAddEvent("touchdrag mousedrag", G)
    },
    stop: function() {
      this.fx && this.fx.stop(true);
      this.fx = null;
      clearTimeout(this.moveTimer);
      this.moveTimer = null;
      this.nextPosition && this.renderCarousel(this.nextPosition)
    },
    onResize: function() {
      var F, E, H, G;
      this.stop();
      this.distance = 0;
      this.containerWidth = this._sWidth();
      this.allSize = 0;
      for (F = 0; F < this.l; F++) {
        this.items[F].size = this.items[F].node.jGetSize(true, true);
        this.allSize += this.items[F].size[this.p_.size]
      }
      this.moiety = this.items[0].size[this.p_.size] / 2;
      if (this.o.orientation == "horizontal") {
        this.center = this.containerWidth / 2
      } else {
        this.center = this.moiety + (this.moiety / 50 * 15)
      }
      this.lastPosition = this.nextPosition = this.center;
      if (this.o.orientation == "horizontal") {
        this.firstSide = this.center - (this.moiety * 2);
        this.lastSide = this.center + (this.moiety * 2)
      } else {
        this.firstSide = 0;
        this.lastSide = this.center + this.moiety + this.moiety * 0.8
      }
      this.setItemsPosition();
      this.renderCarousel(this.lastPosition);
      this.setCanvasPosition();
      E = this.last;
      this.last = 0;
      H = this._calcDistance({
        target: E
      });
      G = H == "forward" ? this.lastPosition - this.distance : this.lastPosition + this.distance;
      this.nextPosition = G;
      this.renderCarousel(G)
    },
    resetZIndex: function() {
      this.items.jEach(j(function(E) {
        if (this.o.orientation == "horizontal") {
          E.node.style.opacity = ""
        } else {
          E.node.jSetCssProp("z-index", "")
        }
      }).jBind(this))
    }
  });
  u.extend(d.prototype, u.customEvents);
  u.Scroll.CoverFlow = d;
  var A = function(H, R) {
    var L, J, F, N, Q, I, M, O, K = 0,
      E, G, P = "Cannot calculate scroll size.";
    this.options = new u.Options(m);
    this.o = this.options.get.jBind(this.options);
    this.set = this.options.set.jBind(this.options);
    this.options.fromJSON(window.MagicScrollOptions || {});
    this.options.fromJSON((window.MagicScrollExtraOptions || {})[H.getAttribute("id") || ""] || {});
    this.options.fromString(H.getAttribute("data-options") || "");
    if (u.browser.mobile) {
      this.options.fromJSON(window.MagicScrollMobileOptions || {});
      this.options.fromJSON((window.MagicScrollMobileExtraOptions || {})[H.getAttribute("id") || ""] || {});
      this.options.fromString(H.getAttribute("data-mobile-options") || "")
    }
    if ("string" == u.jTypeOf(R)) {
      this.options.fromString(R || "")
    } else {
      this.options.fromJSON(R || {})
    }
    if (!this.o("autostart")) {
      return false
    }
    this.original = j(H).jStore("scroll", this);
    u.$uuid(this);
    this.scrollReady = false;
    if (u.browser.ieMode) {
      u.$A(H.getElementsByTagName("a")).jEach(function(S) {
        S.href = S.href
      });
      u.$A(H.getElementsByTagName("img")).jEach(function(S) {
        S.src = S.src
      })
    }
    this.originalClasses = j(H).getAttribute("class") || j(H).getAttribute("className");
    this.originalNodes = [];
    this._insideOptions = {
      autoplay: this.o("autoplay"),
      pause: true,
      debug: false,
      progress: true,
      continuous: false,
      maxSize: "scroll",
      stopDownload: true,
      timingFunctionDefault: "cubic-bezier(.8, 0, .5, 1)",
      itemSettings: "auto"
    };
    this.id = H.getAttribute("id") || "MagicScroll-" + Math.floor(Math.random() * u.now());
    this.container = H.jStore("scroll", this);
    this.wrapper = u.$new("div", {
      "class": "mcs-wrapper"
    }, {
      display: "inline-block"
    });
    this.itemsContainer = u.$new("div", {
      "class": "mcs-items-container"
    });
    this.scrollReady = false;
    for (L = this.container.childNodes.length - 1; L >= 0; L--) {
      F = this.container.childNodes[L];
      if (F.nodeType === 3 || F.nodeType === 8) {
        this.container.removeChild(F)
      } else {
        this.originalNodes.push(F)
      }
    }
    if (this.originalNodes.length === 0) {
      return
    }
    I = function(T) {
      var S = function(W) {
        var V = T.childNodes[W],
          U = V.tagName.toLowerCase();
        if ("br" === U || "hr" === U) {
          return S(++W)
        } else {
          return V
        }
      };
      return S(0)
    };
    O = I(this.container);
    if (O.tagName == "FIGURE") {
      O = j(O).byTag("IMG")[0] || O.firstChild
    }
    if (O.tagName == "A") {
      O = j(O).byTag("IMG")[0] || O.firstChild
    }
    this.tagImg = false;
    if (O.tagName == "IMG") {
      this.tagImg = O;
      M = O.getAttribute("data-src");
      if (M) {
        M = (M + "").jTrim();
        if ("" != M) {
          O.setAttribute("src", M)
        }
      }
    }
    this.coreTimeout = null;
    E = j(function(S) {
      this.coreTimeout = setTimeout(j(function() {
        this.firstItemSize = j(I(this.container)).jGetSize();
        if (this.firstItemSize.height == 0) {
          if (K < 100) {
            K++;
            E(S)
          }
        } else {
          clearTimeout(this.coreTimeout);
          S()
        }
      }).jBind(this), 100)
    }).jBind(this);
    E(j(function() {
      this.cachedCSS = j([]);
      N = u.$A(this.container.childNodes);
      this.firstItem = N[0];
      j(N[0]).jSetCssProp("display", "none");
      this.itemCss = {
        size: D(N[0]),
        border: r(N[0]),
        padding: i(N[0]),
        margin: n(N[0])
      };
      N[0].jSetCssProp("display", "inline-block");
      this.container.jSetCssProp("display", "none");
      this.containerCssSize = D(this.container);
      this.container.jSetCssProp("display", "inline-block");
      this.sizeFirstImg = null;
      this.setupOptions();
      if (this._insideOptions.progress) {
        this.progress = new u.Modules.Progress(this.container)
      }
      this.initBullets();
      this.initEffect_();
      G = j(function() {
        var T, V = true,
          S = {};
        this.hashBox = u.$new("div", null, {
          position: "absolute",
          left: "-10000px",
          top: "-10000px"
        }).jAppendTo(document.body);
        this.show();
        for (L = 0, J = N.length; L < J; L++) {
          T = N[L].tagName.toLowerCase();
          if (V) {
            if ("br" === T || "hr" === T) {
              continue
            }
          } else {
            if ("br" === T || "hr" === T) {
              continue
            }
          }
          try {
            if (p) {
              o.append(u.$new("div", {}, {
                display: "none",
                visibility: "hidden"
              }).append(document.createTextNode(p)));
              p = undefined
            }
          } catch (U) {}
          V = false;
          j(N[L]).jSetOpacity(0).jSetCssProp("display", "inline-block");
          this.push(N[L], S);
          S = {};
          if (L == J - 1) {
            this.done()
          }
        }
      }).jBind(this);
      new u.QImageLoader([{
        node: N[0]
      }], {
        queue: 1,
        onerror: function(S) {
          throw "Error: MagicScroll: Error loading image - " + S.img.src + ". " + P
        },
        onload: (function(S, T) {
          this.sizeFirstImg = (S.img) ? S.img.jGetSize() : S.size;
          if (T.node.tagName.toLowerCase() == "figure") {
            u.$A(T.node.childNodes).jEach(j(function(V) {
              if (V.tagName && V.tagName.toLowerCase() == "figcaption") {
                var U = n(j(V));
                this.sizefigcaption = V.jGetSize();
                this.sizefigcaption.width += U.width;
                this.sizefigcaption.height += U.height;
                this.sizeFirstImg.height += this.sizefigcaption.height
              }
            }).jBind(this))
          }
          G()
        }).jBind(this)
      })
    }).jBind(this))
  };
  u.extend(A.prototype, {
    hovered: false,
    setupOptions: function() {
      if ("animation" == this.o("mode") && (u.browser.ieMode || !u.browser.features.animation)) {
        this.set("mode", "scroll")
      }
      if (u.browser.ieMode && u.browser.ieMode <= 9 && this.o("mode") == "cover-flow") {
        this.set("mode", "scroll")
      }
      this._insideOptions.debug = document.location.hash.indexOf("#magic-debug-mode") != -1;
      if (u.jTypeOf(this.o("items")) === "array") {
        this._insideOptions.itemSettings = this.o("items");
        j(function() {
          var G, I, F, H = this._insideOptions.itemSettings,
            E = H.length;
          for (G = 0; G < E; G++) {
            for (I = G + 1; I < E; I++) {
              if (H[G][0] < H[I][0]) {
                F = H[G];
                H[G] = H[I];
                H[I] = F
              }
            }
          }
          this._insideOptions.itemSettings = H
        }).jBind(this)();
        this.set("items", "auto")
      }
      if (this.o("speed") === 0) {
        this.set("speed", 10)
      }
      if (this.o("autoplay") < 0 || this.o("step") == 0) {
        this._insideOptions.continuous = true
      }
      if (j(["cover-flow", "animation"]).contains(this.o("mode"))) {
        this._insideOptions.continuous = false
      }
      if ("off" === this.o("loop") || "false" === this.o("loop")) {
        this.set("loop", false)
      }
      if (this.o("mode") == "carousel" || this._insideOptions.continuous) {
        this.set("loop", "infinite")
      }
      if (this.o("mode") == "cover-flow") {
        this.set("loop", false)
      }
      if ("rewind" === this.o("loop") && "animation" === this.o("mode")) {
        this.set("loop", false)
      }
      if (j(["cover-flow", "carousel"]).contains(this.o("mode")) || this._insideOptions.continuous) {
        this.set("pagination", false)
      }
      if (j(["cover-flow", "carousel"]).contains(this.o("mode")) && !this._insideOptions.continuous) {
        this.set("step", 1)
      }
      if (j(["cover-flow", "carousel"]).contains(this.o("mode")) && !j(["auto", "fit"]).contains(this.o("items"))) {
        this.set("items", "auto")
      }
      if (this.o("mode") == "animation" && this.o("items") == "auto") {
        this.set("items", "fit")
      }
      if (this.o("mode") == "animation") {
        this.set("step", "auto")
      }
      if (this._insideOptions.continuous) {
        this.set("easing", "cubic-bezier(0, 0, 1, 1)")
      } else {
        if (this.o("easing") == "cubic-bezier(0, 0, 1, 1)") {
          this.set("easing", this._insideOptions.timingFunctionDefault)
        }
      }
      if ("carousel" === this.o("mode")) {
        this.set("lazyLoad", false)
      }
      if (j(["cover-flow", "carousel"]).contains(this.o("mode"))) {
        this._insideOptions.itemSettings = "auto"
      }
      this.originwidth = this.o("width");
      this.originheight = this.o("height");
      if (this._insideOptions.continuous) {
        this.set("autoplay", 0)
      }
      if (j(["cover-flow", "carousel"]).contains(this.o("mode")) || this._insideOptions.continuous) {
        this.set("arrows", false)
      }
      if ("false" === this.o("arrows") || "off" === this.o("arrows")) {
        this.set("arrows", false)
      }
      if (this.o("arrows")) {
        this.container.jAddClass("MagicScroll-arrows-" + this.o("arrows"))
      }
      this.container.jAddClass("MagicScroll-" + this.o("orientation"));
      this.container.setAttribute("data-mode", this.o("mode"))
    },
    initBullets: function() {
      if (!this.o("pagination")) {
        if (this.bullets) {
          this.bullets.jRemove();
          this.bullets = null
        }
        return
      }
      if (!this.bullets) {
        this.bullets = new u.Modules.Bullets({}, this.container, j(function() {
          return this.hold_
        }).jBind(this));
        this.container.jAddClass("MagicScroll-bullets");
        this.bullets.bindEvent("bullets-click", j(function(E) {
          this.jump({
            direction: E.direction,
            target: E.jumpIndex
          })
        }).jBind(this))
      }
    },
    setBullets: function() {
      var F, E = j([]);
      if (!this.effect) {
        return
      }
      for (F = 0; F < this.effect.l; F++) {
        if (j(["scroll", "animation"]).contains(this.o("mode"))) {
          if (F % this.effect.itemStep == 0) {
            E.push(this.effect.items[F].index)
          }
        } else {
          E.push(this.effect.items[F].index)
        }
      }
      this.bullets.push(E)
    },
    setupArrows: function() {
      var E = i(this.container);
      if (this.arrows) {
        this.arrows.jRemove();
        this.arrows = null
      }
      this.wrapper.jSetCss({
        top: "",
        left: "",
        right: "",
        bottom: ""
      });
      if (this.o("arrows")) {
        if (!this.arrows) {
          this.arrows = new u.Modules.ArrowsPair({
            orientation: "mcs-" + this.o("orientation"),
            "class": "mcs-button",
            classHidden: "mcs-hidden",
            classDisabled: "mcs-disabled"
          }, this.container);
          this.effect.bindEvent("disable", this.arrows.disable.jBind(this.arrows, undefined));
          this.effect.bindEvent("enable", this.arrows.enable.jBind(this.arrows, undefined));
          this.effect.bindEvent("hideArrows", this.arrows.hide.jBind(this.arrows, undefined));
          this.effect.bindEvent("showArrows", this.arrows.show.jBind(this.arrows, undefined));
          if (!this.o("loop")) {
            this.effect.bindEvent("scroll", this.arrows.enable.jBind(this.arrows, undefined));
            this.effect.bindEvent("last-frame", this.arrows.disable.jBind(this.arrows, "next"));
            this.effect.bindEvent("first-frame", this.arrows.disable.jBind(this.arrows, "prev"))
          }
          this.arrows.bindEvent("forward", (function(I) {
            this.jump("forward")
          }).jBind(this));
          this.arrows.bindEvent("backward", (function(I) {
            this.jump("backward")
          }).jBind(this))
        } else {
          this.arrows.setOrientation(this.o("orientation"))
        }
        if (this.o("arrows") == "outside") {
          var H = this.o("orientation") == "horizontal" ? j(["left", "right"]) : j(["top", "bottom"]),
            F = this.o("orientation") == "horizontal" ? "width" : "height",
            G = parseInt(this.arrows.next.jGetSize()[F]);
          H.jEach(j(function(I) {
            this.wrapper.jSetCssProp(I, G + (E[F] / 2))
          }).jBind(this))
        }
      }
    },
    setContainerSize: function() {
      if (this.o("width") != "auto") {
        this.container.jSetCssProp("width", this.o("width"))
      }
      if (this.o("height") != "auto") {
          this.container.jSetCssProp("height", this.o("height"));
      }
      return
    },
    initEffect_: function() {
      var E = j(["scroll", "animation"]).contains(this.o("mode")) ? "effect" : this.o("mode");
      this.effect = new u.Scroll[("-" + E).jCamelize()](this.itemsContainer, {
        orientation: this.o("orientation"),
        duration: this.o("speed"),
        continuous: this._insideOptions.continuous,
        timingFunction: this.o("easing"),
        loop: this.o("loop"),
        step: this.o("step"),
        effect: this.o("mode"),
        lazyLoad: this.o("lazyLoad"),
        progress: this._insideOptions.progress,
        stopDownload: this._insideOptions.stopDownload,
        debug: this._insideOptions.debug,
        scrollOnWheel: this.o("scrollOnWheel"),
        draggable: this.o("draggable"),
        keyboard: this.o("keyboard")
      });
      if (this.o("items") != "auto" && this.o("step") == "auto") {
        this.set("step", this.o("items"))
      }
      this.effect.bindEvent("hold", j(function() {
        this.hold_ = false;
        this.auto()
      }).jBind(this))
    },
    jump: function(E, F) {
      if (this.o("mode") == "animation" && /^\+|^\-/.test(E)) {
        E = /^\+/.test(E) ? "forward" : "backward"
      }
      if (!this.hold_ && !this.effect.stopScroll) {
        this.hold_ = true;
        clearTimeout(this.auto_);
        this.effect.jump(E, j(function(G, H) {
          this.hold_ = false;
          if (H) {
            return
          }
          this.jCallEvent("after-scroll");
          if (!this._insideOptions.continuous || this.hovered || this.pause_) {
            if (this.hashBox.childNodes.length == 0) {
              this.hashBox.jRemove()
            }
            if (this.o("loop")) {
              this.effect.changeClones()
            }
            this.o("onMoveEnd")({
              id: this.id,
              items: G
            });
            this.effect.continuousMove = false;
            F && F()
          } else {
            this.jump("forward", F)
          }
        }).jBind(this))
      }
    },
    parseTag: function(J) {
      var F, I, G, E, H;
      if (J.tagName.toUpperCase() == "A") {
        if ((E = j(J).byTag("IMG")[0])) {
          if ((H = j(J).byTag("span")[0]) && "" !== H.innerHTML.jTrim()) {
            I = j(H.cloneNode(true)).jAddClass("mcs-caption");
            I.setAttribute("magic-user", "yes")
          } else {
            if (((F = E.nextSibling) && 3 == F.nodeType && "" !== F.nodeValue.jTrim()) || (H && (F = H.nextSibling) && 3 == F.nodeType && "" !== F.nodeValue.jTrim())) {
              I = u.$new("span", {
                "class": "mcs-caption"
              }).append(F.cloneNode(true))
            }
          }
          for (G = J.childNodes.length - 1; G >= 0; G--) {
            if (E !== J.childNodes[G]) {
              J.removeChild(J.childNodes[G])
            }
          }
          if (I) {
            J.append(I)
          }
        }
      } else {
        if (J.tagName.toLowerCase() == "figure") {
          u.$A(J.childNodes).jEach(j(function(K) {
            if (K.tagName && K.tagName.toLowerCase() == "figcaption") {
              F = K.getAttribute("id") || "figcaption-" + Math.floor(Math.random() * u.now());
              K.setAttribute("id", F);
              j(K).jAddClass("mcs-caption");
              I = K;
              this.cssId = u.addCSS("#" + F + ":before", {
                "padding-top": (this.sizefigcaption.height + r(j(K)) / 2) / parseInt(this.firstItemSize.width) * 100 + "%"
              })
            }
          }).jBind(this))
        }
      }
      return {
        node: J,
        figcaption: I
      }
    },
    setPercent: function(E) {
      if (this.o("items") != "auto") {
        E.node.jSetCssProp(this.o("orientation") == "horizontal" ? "width" : "height", 100 / this.o("items") + "%")
      }
    },
    checkWholeItems: function(F) {
      var G, E;
      if (this.o("items") == "fit") {
        this.set("items", Math.floor(this.wrapper.jGetSize()[this.effect.p_.size] / this.sizeFirstImg[this.effect.p_.size]))
      } else {
        if (this.o("items") == "auto") {
          if (!this.itemCss.size[this.effect.p_.size]) {
            G = this.sizeFirstImg[this.effect.p_.size] || this.firstItemSize[this.effect.p_.size];
            E = this.itemsContainer.jGetSize();
            if ("vertical" === this.o("orientation")) {
              G = Math.min(G, E[this.effect.p_.size])
            }
            E = (G + n(F.content)[this.effect.p_.size] + r(F.content)[this.effect.p_.size] + i(F.content)[this.effect.p_.size] + i(F.node)[this.effect.p_.size]) / this.itemsContainer.jGetSize()[this.effect.p_.size] * 100;
            if (E > 100) {
              E = 100
            }
            F.node.jSetCssProp(this.effect.p_.size, E + "%")
          }
        }
      }
    },
    push: function(F, E) {
      F.show();
      F = {
        content: F
      };
      if (E.top) {
        E.top.jEach(function(H) {
          H.jRemove()
        })
      }
      if (E.bottom) {
        E.bottom.jEach(function(H) {
          H.jRemove()
        })
      }
      F.additionalTags = E;
      var G = this.parseTag(F.content);
      F.content = G.node;
      F.figcaption = G.figcaption;
      F.node = u.$new("div", {
        "class": "mcs-item"
      });
      F.node.jAppendTo(this.itemsContainer);
      this.checkWholeItems(F);
      this.setPercent(F);
      F.content.jAppendTo(this.hashBox);
      this.effect.push(F)
    },
    show: function() {
      if (this.indoc) {
        return
      }
      this.indoc = true;
      this.container.append(this.wrapper.append(this.itemsContainer)).show().setAttribute("id", this.id);
      this.container.jSetCssProp("display", "inline-block");
      if (this.o("arrows")) {
        this.setupArrows();
        this.o("loop") && this.arrows.disable("prev");
        this.arrows.hide()
      }
      this.checkSizes_();
      this.setContainerSize();
      if (this.tagImg) {
        if ("horizontal" === this.o("orientation") && this.container.jGetSize().width < this.sizeFirstImg.width) {
          this.checkSizes_(true);
          this.setContainerSize()
        }
      }
      this.countTheNumberOfItems();
      j(window).jAddEvent("resize", this.onResize.jBind(this))
    },
    done: function(E) {
      this.effect.bindEvent("key_down", j(function(F) {
        this.jump(F.direction)
      }).jBind(this));
      this.effect.bindEvent("show-this", j(function(F) {
        this.jump(F.index)
      }).jBind(this));
      this.effect.bindEvent("showProgress", j(function() {
        this.progress && this.progress.show()
      }).jBind(this));
      this.effect.bindEvent("hideProgress", j(function() {
        this.progress && this.progress.hide()
      }).jBind(this));
      this.effect.bindEvent("complete", j(function() {
        this.effect.done2(j(function() {
          this.effect.bindEvent("disableHold", j(function() {
            this.hold_ = false
          }).jBind(this));
          this.effect.bindEvent("item-click", j(function(H) {
            var G = true,
              F, I;
            if (this.o("mode") == "carousel") {
              F = 360 / this.effect.l;
              I = (360 - this.effect.last * F + H.index * F) % 360;
              if (I > 90 && I < 270) {
                G = false
              }
            }
            G && this.jump(H.index)
          }).jBind(this));
          if (this.bullets) {
            this.bullets.o.items = this.effect.items.length;
            this.setBullets();
            this.bullets.show()
          }
          this.effect.bindEvent("on-item-hover", j(function(F) {
            this.o("onItemHover")({
              id: this.id,
              item: F.itemIndex
            })
          }).jBind(this));
          this.effect.bindEvent("on-item-out", j(function(F) {
            this.o("onItemOut")({
              id: this.id,
              item: F.itemIndex
            })
          }).jBind(this));
          this.effect.bindEvent("on-start-effect", j(function(F) {
            this.bullets && this.bullets.setActiveBullet(F.arr, !this.o("loop"));
            this.o("onMoveStart")({
              id: this.id,
              items: F.arr
            })
          }).jBind(this));
          this.effect.bindEvent("drag-start", j(function() {
            this.hold_ = true;
            this.auto()
          }).jBind(this));
          this.effect.bindEvent("drag-end", j(function(F) {
            this.bullets && this.bullets.setActiveBullet(F.arr, !this.o("loop"));
            this.hold_ = false;
            this.o("onMoveEnd")({
              id: this.id,
              items: F.arr
            });
            if (this.hashBox.childNodes.length == 0) {
              this.hashBox.jRemove()
            }
            this.auto()
          }).jBind(this));
          this.container.jSetCssProp("overflow", "visible");
          this.scrollReady = true;
          this.o("onReady").call(this, this.id);
          j(window).jAddEvent("resize", j(function() {
            this.hold_ = false;
            if (this._insideOptions.continuous) {
              this.jump.jBind(this, "forward").jDelay(200)
            } else {
              this.auto()
            }
          }).jBind(this));
          this.setEvent();
          if ("vertical" === this.o("orientation") && /%$/.test(this.o("height"))) {
            this.set("height", this.container.jGetSize().height);
            this.setContainerSize()
          }
          if (this.o("autoplay") != 0) {
            this.auto()
          } else {
            this.pause_ = true
          }
          if (this._insideOptions.continuous) {
            this.pause_ = false;
            this.jump.jBind(this, "forward").jDelay(200)
          }
          this.scrollReady = true
        }).jBind(this))
      }).jBind(this));
      this.effect.done()
    },
    setEvent: function() {
      this.bindEvent("after-scroll", j(function() {
        if (this._insideOptions.autoplay != 0) {
          !this._insideOptions.continuous && this.auto()
        }
      }).jBind(this));
      if (!u.browser.touchScreen && (this._insideOptions.pause || this._insideOptions.continuous)) {
        this.wrapper.jAddEvent("mouseover mouseout", j(function(F) {
          F.stop();
          var E = F.getRelated();
          while (E && E !== this.wrapper) {
            E = E.parentNode
          }
          if (E == this.wrapper) {
            return
          }
          if (this._insideOptions.pause && !this.pause_) {
            this.pauseHover_ = "mouseover" == F.type;
            this.hovered = "mouseover" == F.type;
            if (this._insideOptions.continuous) {
              if (F.type == "mouseover") {
                this.pauseContinuous()
              } else {
                this.jump("forward")
              }
            } else {
              this.auto()
            }
          }
        }).jBind(this))
      }
      if (!this._insideOptions.continuous && "animation" === this.o("mode") && this.o("scrollOnWheel")) {
        this.wrapper.jAddEvent("mousescroll", j(function(E) {
          var F = -1 * (Math.abs(E.deltaY) < Math.abs(E.deltaX) ? E.deltaX : -1 * E.deltaY);
          F = E.isMouse ? (F) : (F * (8 / 54));
          if ((true === this.o("scrollOnWheel") && E.isMouse) || "vertical" === this.o("orientation") && Math.abs(E.deltaY) > Math.abs(E.deltaX) || "horizontal" === this.o("orientation") && Math.abs(E.deltaY) < Math.abs(E.deltaX)) {
            E.stop();
            if (Math.abs(F) < 0.6) {
              return
            }
            this.jump(F > 0 ? "backward" : "forward")
          }
        }).jBind(this))
      }
    },
    checkSizes_: function(M) {
      var L = "width",
        N = "height",
        I = this.o("orientation") == "vertical",
        E = this.container.jGetSize(),
        H = {
          width: 0,
          height: 0
        },
        J = i(this.container),
        Q = r(this.wrapper),
        U = n(this.wrapper),
        O = i(this.wrapper),
        P = n(this.firstItem),
        K = u.$new("div", {
          "class": "mcs-item"
        }).jAppendTo(this.wrapper.firstChild),
        R, S, G, T, F = i(K);
      K.jRemove();
      if (this.container.jGetCss("box-sizing") == "border-box") {
        H = r(this.container)
      }
      if (I) {
        L = N;
        N = "width"
      }
      if (this.o(L) == "auto" && !parseInt(this.containerCssSize[L])) {
        if (I) {
          if (!isNaN(this.o("items"))) {
            this.set(L, E[L] * this.o("items"))
          } else {
            this.set(L, E[L])
          }
        } else {
          this.set(L, "100%")
        }
      }
      if (this.o(N) == "auto" && !parseInt(this.containerCssSize[N]) || M) {
        G = H[N] + J[N] + Q[N] + P[N] + F[N];
        if (I) {
          R = Math.min(this.sizeFirstImg[N], E[N])
        } else {
          R = this.sizeFirstImg[N];
          if (this.tagImg) {
            S = this.sizeFirstImg[N] / this.sizeFirstImg[L];
            if (this.sizeFirstImg[L] > E[L]) {
              R = E[L] * S
            }
          }
        }
        T = (R + n(j(this.originalNodes[0]))[N] + i(this.originalNodes[0])[N] + r(this.originalNodes[0])[N]) || this.firstItemSize[N] || E[N];
        T += G;
        T += "";
        this.set(N, T)
      }
    },
    countTheNumberOfItems: function() {
      var H, G, F, J, I = true,
        E = this.o("items");
      if (this._insideOptions.itemSettings != "auto" && j(["scroll", "animation"]).contains(this.o("mode"))) {
        J = this._insideOptions.itemSettings;
        F = J.length;
        G = this._insideOptions.maxSize == "scroll" ? this.container.jGetSize()[this.o("orientation") == "vertical" ? "height" : "width"] : j(window).jGetSize()[this.o("orientation") == "vertical" ? "height" : "width"];
        for (H = F - 1; H >= 0; H--) {
          if (G <= J[H][0] && !isNaN(J[H][1])) {
            this.set("items", J[H][1]);
            I = false;
            break
          } else {
            if (0 === H) {
              if (j(["carousel", "cover-flow"]).contains(this.o("mode"))) {
                this.set("items", 1)
              } else {
                if ("animation" === this.o("mode")) {
                  this.set("items", "fit")
                } else {
                  this.set("items", "fit")
                }
              }
            }
          }
        }
        if (E === this.o("items")) {
          return
        }
        u.$A(this.itemsContainer.childNodes).jEach(j(function(L, K) {
          this.checkWholeItems({
            node: L,
            content: L.firstChild
          });
          this.setPercent({
            node: L
          })
        }).jBind(this));
        if (this.effect.items.length > 0) {
          this.effect.update()
        }
      }
    },
    onResize: function() {
      this.countTheNumberOfItems()
    },
    resize: function() {
      if (this.scrollReady) {
        this.onResize();
        this.effect.onResize()
      }
    },
    pauseContinuous: function() {
      this.effect.pause()
    },
    stop: function() {
      this.container.jStore("swap-items-opacity", false);
      this.effect && this.effect.stop();
      this.hold_ = false;
      clearTimeout(this.auto_);
      this.auto_ = false
    },
    checkEffect: function(E) {
      return E == this.o("mode")
    },
    registerCallback: function(F, E) {
      if (!j(["onItemHover", "onItemOut", "onReady", "onMoveStart", "onMoveEnd"]).contains(F)) {
        return
      }
      this.set(F, E)
    },
    dispose: function() {
      var E, F, G;
      this.stop();
      clearTimeout(this.coreTimeout);
      this.wrapper.jRemoveEvent("mouseover mouseout");
      this.wrapper.jRemoveEvent("mousewheel");
      this.effect && this.effect.dispose();
      if (this.cachedCSS) {
        for (E = 0; E < this.cachedCSS.length; E++) {
          u.removeCSS("magicscroll-css", this.cachedCSS[E])
        }
      }
      this.container.jRemoveClass("MagicScroll-bullets");
      j(this.originalNodes).jEach(j(function(H) {
        if (H.parentNode) {
          j(H).jRemove()
        }
        G = H;
        if (G.tagName == "FIGURE") {
          G = G.firstChild
        }
        if (G.tagName == "A") {
          G = G.firstChild
        }
        if (G.tagName == "IMG") {
          F = G.getAttribute("data-src");
          if (F) {
            F = (F + "").jTrim();
            if ("" != F) {
              G.removeAttribute("src")
            }
          }
        }
        if (H.childNodes.length > 0 && H.tagName.toLowerCase() == "a") {
          u.$A(H.childNodes).jEach(j(function(I) {
            if (I.tagName && I.tagName.toLowerCase() == "span") {
              if ("yes" === I.getAttribute("magic-user")) {
                I.removeAttribute("magic-user");
                H.append(I)
              } else {
                H.append(I.childNodes[0]);
                I.jRemove()
              }
            }
          }).jBind(this))
        }
        H.jSetCss({
          visibility: "",
          opacity: "1"
        })
      }).jBind(this));
      this.hashBox && this.hashBox.jRemove();
      u.$A(this.container.childNodes).jEach(function(H) {
        j(H).kill()
      });
      j(this.container).removeAttribute("data-mode");
      j(this.container).jClearEvents().jRemoveClass().jAddClass(this.originalClasses);
      this.container.jSetCss({
        width: "",
        height: "",
        visibility: "",
        display: "",
        overflow: ""
      });
      this.container.jDel("scroll");
      for (E = this.originalNodes.length - 1; E >= 0; E--) {
        j(this.originalNodes[E]).jSetCss({
          opacity: ""
        }).jAppendTo(this.container)
      }
      this.o("onStop").call(this, this.id);
      return null
    },
    play: function(E) {
      if (null === E || undefined === E) {
        E = this.o("autoplay")
      } else {
        E || (E = 1000);
        E = parseInt(E);
        if (isNaN(E)) {
          E = this.o("autoplay")
        }
      }
      if (!this.pause_) {
        return
      }
      if (!this.auto_) {
        this.pause_ = false;
        this.effect.continuousPause = false;
        this._insideOptions.autoplay = E;
        this.jump("forward")
      }
    },
    pause: function() {
      if (this.pause_) {
        return
      }
      this.pause_ = true;
      if (this._insideOptions.continuous) {
        this.pauseContinuous()
      } else {
        this.stop()
      }
      this.auto()
    },
    updateOptions: function(E) {
      var H, G = {
          height: "",
          width: ""
        },
        F = this.o("mode");
      this.stop();
      this.container.jRemoveClass("MagicScroll-arrows-" + this.o("arrows"));
      this.container.jRemoveClass("MagicScroll-" + this.o("orientation"));
      this.wrapper.jRemoveEvent("mouseover mouseout mousewheel");
      this.destroyEvent("after-scroll");
      this.progress = null;
      this.container.jRemoveClass("MagicScroll-bullets");
      if ("string" == u.jTypeOf(E)) {
        this.options.fromString(E || "")
      } else {
        this.options.fromJSON(E || {})
      }
      if (F != this.o("mode")) {
        return false
      }
      this._insideOptions.autoplay = this.o("autoplay");
      this.setupOptions();
      this.effect.items.jEach(j(function(I) {
        I.node.jSetCss(G)
      }).jBind(this));
      this.effect.itemsFirstClones.jEach(j(function(I) {
        j(I).node.jSetCss(G)
      }).jBind(this));
      this.effect.itemsLastClones.jEach(j(function(I) {
        j(I).node.jSetCss(G)
      }).jBind(this));
      this.setupArrows();
      for (H = 0; H < this.cachedCSS.length; H++) {
        this.cachedCSS[H] && u.removeCSS("magicscroll-css", this.cachedCSS[H])
      }
      this.effect.setNewOptions({
        orientation: this.o("orientation"),
        duration: this.o("speed"),
        continuous: this._insideOptions.continuous,
        timingFunction: this.o("easing"),
        loop: this.o("loop"),
        step: this.o("step"),
        effect: this.o("mode"),
        lazyLoad: this.o("lazyLoad"),
        progress: this._insideOptions.progress,
        stopDownload: this._insideOptions.stopDownload,
        debug: this._insideOptions.debug,
        scrollOnWheel: this.o("scrollOnWheel"),
        draggable: this.o("draggable"),
        keyboard: this.o("keyboard")
      });
      this.checkSizes_();
      this.setContainerSize();
      this.countTheNumberOfItems();
      u.$A(this.itemsContainer.childNodes).jEach(j(function(J, I) {
        this.checkWholeItems({
          node: J,
          content: J.firstChild
        });
        this.setPercent({
          node: J
        })
      }).jBind(this));
      this.effect.update(true);
      this.initBullets();
      if (this.bullets) {
        this.setBullets();
        this.bullets.show()
      }
      if (this.o("autoplay") == 0) {
        this.pause()
      } else {
        this.pause_ = false
      }
      this.o("arrows") && this.arrows.show();
      this.setEvent();
      if (this._insideOptions.continuous) {
        this.jump.jBind(this, "forward").jDelay(200);
        this.pause_ = false
      } else {
        this.auto()
      }
      return true
    },
    auto: function() {
      var E = "forward";
      clearTimeout(this.auto_);
      this.auto_ = false;
      if (this.hold_ || this.pause_ || this.pauseHover_) {
        return
      }
      if (this._insideOptions.autoplay != 0) {
        this.auto_ = setTimeout(j(function() {
          this.jump(E)
        }).jBind(this), Math.abs(this._insideOptions.autoplay))
      }
    }
  });
  u.extend(A.prototype, u.customEvents);
  u.Scroll.Full = A;
  var B = function(F) {
      var E = h(F);
      if (!E) {
        return
      }
      return {
        registerCallback: E.registerCallback.jBind(E),
        pause: E.pause.jBind(E),
        play: j(function(G) {
          this.play(G)
        }).jBind(E),
        forward: j(function(G) {
          G = !G ? "forward" : a(G, "+");
          this.jump(G)
        }).jBind(E),
        backward: j(function(G) {
          G = !G ? "backward" : a(G, "-");
          this.jump(G)
        }).jBind(E),
        jump: j(function(G) {
          if (!G || isNaN(Math.abs(parseInt(G)))) {
            G = "forward"
          }
          this.jump(G)
        }).jBind(E),
        updateOptions: j(function(G) {
          if (!G || u.jTypeOf(G) != "object") {
            G = {}
          }
          this.updateOptions(G)
        }).jBind(E)
      }
    },
    h = function(F) {
      var E = null;
      if (u.jTypeOf(F) == "string" && j(F) || u.jTypeOf(F) == "element") {
        E = j(F).jFetch("scroll")
      } else {
        if (u.jTypeOf(F) == "function" && (F instanceof u.Scroll.Full) || F && F.indoc) {
          E = F
        }
      }
      return E
    },
    e = function(G, H, F) {
      var E = h(G);
      if (E) {
        return E[F](H)
      } else {
        H = G;
        G = y
      }
      j(G).jEach(function(I) {
        I[F](H)
      })
    },
    a = function(F, E) {
      if (u.jTypeOf(F) === "string") {
        F = parseInt(F);
        if (isNaN(F)) {
          F = F
        }
      }
      if (u.jTypeOf(F) === "number") {
        F = E + F
      }
      return F
    },
    w = function(F) {
      var E = u.$A((F || document).byClass("MagicScroll")).map(function(G) {
        return q.start(G)
      });
      l = true;
      return E
    },
    l = false,
    z = function(E) {
      return y = j(y).filter(function(F) {
        return F.dispose()
      })
    },
    y = [],
    q = {
      version: "v2.0.26",
      start: function(F) {
        var E = null;
        if (arguments.length) {
          F = j(F);
          if (F && j(F).jHasClass("MagicScroll")) {
            if (E = j(F).jFetch("scroll")) {
              return E
            } else {
              E = new u.Scroll.Full(F, l ? {
                autostart: true
              } : {});
              if (!E.o("autostart")) {
                E = null;
                return false
              } else {
                y.push(E);
                return E
              }
            }
          } else {
            return false
          }
        } else {
          return w()
        }
      },
      stop: function(E) {
        if (arguments.length) {
          E = (E instanceof u.Scroll.Full) ? E : (j(E) && j(E).jFetch("scroll") || null);
          if (!E) {
            return
          }
          y.splice(j(y).indexOf(E), 1);
          E.dispose()
        } else {
          z();
          return
        }
      },
      refresh: function(E) {
        if (E) {
          q.stop(E);
          return q.start(E.id || E)
        } else {
          z();
          return w()
        }
      },
      running: function(G) {
        var F, E = false;
        if (G) {
          F = h(G);
          if (F) {
            E = F.scrollReady
          }
        }
        return E
      },
      getInstance: function(E) {
        return B(E)
      },
      updateOptions: function(E, F) {
        return e(E, F, "updateOptions")
      },
      resize: function(E) {
        if (E) {
          e(E, null, "resize")
        } else {
          j(y).jEach(function(F) {
            q.resize(F)
          })
        }
      },
      jump: function(E, F) {
        if (undefined != E && null != E) {
          e(E, F, "jump")
        }
      },
      pause: function(E) {
        e(E, null, "pause")
      },
      play: function(E, F) {
        e(E, F, "play")
      },
      forward: function(E, F) {
        var G;
        F = !F ? "forward" : a(F, "+");
        if (!E) {
          E = F
        } else {
          if (!h(E)) {
            E = a(E, "+")
          }
        }
        e(E, F, "jump")
      },
      backward: function(E, F) {
        var G;
        F = !F ? "backward" : a(F, "-");
        if (!E) {
          E = F
        } else {
          if (!h(E)) {
            E = a(E, "-")
          }
        }
        e(E, F, "jump")
      }
    };
  j(document).jAddEvent("domready", function() {
    p = p();
    o = u.$new("div", {
      "class": "msc-tmp-hdn-holder"
    }).jAppendTo(document.body);
    u.defined(window.MagicScrollOptions) || (window.MagicScrollOptions = {});
    u.defined(window.MagicScrollMobileOptions) || (window.MagicScrollMobileOptions = {});
    u.defined(window.MagicScrollExtraOptions) || (window.MagicScrollExtraOptions = {});
    u.defined(window.MagicScrollMobileExtraOptions) || (window.MagicScrollMobileExtraOptions = {});
    var E = window.MagicScrollMobileExtraOptions.beforeInit || window.MagicScrollExtraOptions.beforeInit || window.MagicScrollMobileOptions.beforeInit || window.MagicScrollOptions.beforeInit || u.$F;
    E();
    q.start.jDelay(10)
  });
  return q
})();