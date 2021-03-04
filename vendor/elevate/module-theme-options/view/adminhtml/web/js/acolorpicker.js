/*!
 * a-color-picker (https://github.com/narsenico/a-color-picker)
 *
 * Copyright (c) 2017-2018, Gianfranco Caldi.
 * Released under the MIT License.
 */
!function (e, t) {
    "object" == typeof exports && "object" == typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define("AColorPicker", [], t) : "object" == typeof exports ? exports.AColorPicker = t() : e.AColorPicker = t()
}("undefined" != typeof self ? self : this, function () {
    return function (e) {
        var t = {};

        function r(i) {
            if (t[i]) return t[i].exports;
            var o = t[i] = {i: i, l: !1, exports: {}};
            return e[i].call(o.exports, o, o.exports, r), o.l = !0, o.exports
        }

        return r.m = e, r.c = t, r.d = function (e, t, i) {
            r.o(e, t) || Object.defineProperty(e, t, {enumerable: !0, get: i})
        }, r.r = function (e) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(e, "__esModule", {value: !0})
        }, r.t = function (e, t) {
            if (1 & t && (e = r(e)), 8 & t) return e;
            if (4 & t && "object" == typeof e && e && e.__esModule) return e;
            var i = Object.create(null);
            if (r.r(i), Object.defineProperty(i, "default", {
                enumerable: !0,
                value: e
            }), 2 & t && "string" != typeof e) for (var o in e) r.d(i, o, function (t) {
                return e[t]
            }.bind(null, o));
            return i
        }, r.n = function (e) {
            var t = e && e.__esModule ? function () {
                return e.default
            } : function () {
                return e
            };
            return r.d(t, "a", t), t
        }, r.o = function (e, t) {
            return Object.prototype.hasOwnProperty.call(e, t)
        }, r.p = "", r(r.s = 1)
    }([function (e, t, r) {
        "use strict";
        /*!
     * is-plain-object <https://github.com/jonschlinkert/is-plain-object>
     *
     * Copyright (c) 2014-2017, Jon Schlinkert.
     * Released under the MIT License.
     */
        var i = r(3);

        function o(e) {
            return !0 === i(e) && "[object Object]" === Object.prototype.toString.call(e)
        }

        e.exports = function (e) {
            var t, r;
            return !1 !== o(e) && "function" == typeof (t = e.constructor) && !1 !== o(r = t.prototype) && !1 !== r.hasOwnProperty("isPrototypeOf")
        }
    }, function (e, t, r) {
        "use strict";
        Object.defineProperty(t, "__esModule", {value: !0}), t.VERSION = t.PALETTE_MATERIAL_CHROME = t.PALETTE_MATERIAL_500 = t.COLOR_NAMES = t.getLuminance = t.intToRgb = t.rgbToInt = t.rgbToHsv = t.rgbToHsl = t.hslToRgb = t.rgbToHex = t.parseColor = t.parseColorToHsla = t.parseColorToHsl = t.parseColorToRgba = t.parseColorToRgb = t.from = t.createPicker = void 0;
        var i = function () {
            function e(e, t) {
                for (var r = 0; r < t.length; r++) {
                    var i = t[r];
                    i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
                }
            }

            return function (t, r, i) {
                return r && e(t.prototype, r), i && e(t, i), t
            }
        }(), o = function (e, t) {
            if (Array.isArray(e)) return e;
            if (Symbol.iterator in Object(e)) return function (e, t) {
                var r = [], i = !0, o = !1, s = void 0;
                try {
                    for (var n, a = e[Symbol.iterator](); !(i = (n = a.next()).done) && (r.push(n.value), !t || r.length !== t); i = !0) ;
                } catch (e) {
                    o = !0, s = e
                } finally {
                    try {
                        !i && a.return && a.return()
                    } finally {
                        if (o) throw s
                    }
                }
                return r
            }(e, t);
            throw new TypeError("Invalid attempt to destructure non-iterable instance")
        }, s = r(2), n = l(r(0)), a = l(r(4));

        function l(e) {
            return e && e.__esModule ? e : {default: e}
        }

        function c(e, t) {
            if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
        }

        function h(e) {
            if (Array.isArray(e)) {
                for (var t = 0, r = Array(e.length); t < e.length; t++) r[t] = e[t];
                return r
            }
            return Array.from(e)
        }

        /*!
     * a-color-picker
     * https://github.com/narsenico/a-color-picker
     *
     * Copyright (c) 2017-2019, Gianfranco Caldi.
     * Released under the MIT License.
     */
        var u = "undefined" != typeof window && window.navigator.userAgent.indexOf("Edge") > -1,
            p = "undefined" != typeof window && window.navigator.userAgent.indexOf("rv:") > -1, d = {
                id: null,
                attachTo: "body",
                showHSL: !0,
                showRGB: !0,
                showHEX: !0,
                showAlpha: !1,
                color: "#ff0000",
                palette: null,
                paletteEditable: !1,
                useAlphaInPalette: "auto",
                slBarSize: [232, 150],
                hueBarSize: [150, 11],
                alphaBarSize: [150, 11]
            }, f = "COLOR", g = "RGBA_USER", b = "HSLA_USER";

        function v(e, t, r) {
            return e ? e instanceof HTMLElement ? e : e instanceof NodeList ? e[0] : "string" == typeof e ? document.querySelector(e) : e.jquery ? e.get(0) : r ? t : null : t
        }

        function m(e) {
            var t = e.getContext("2d"), r = +e.width, i = +e.height, n = t.createLinearGradient(1, 1, 1, i - 1);
            return n.addColorStop(0, "white"), n.addColorStop(1, "black"), {
                setHue: function (e) {
                    var o = t.createLinearGradient(1, 0, r - 1, 0);
                    o.addColorStop(0, "hsla(" + e + ", 100%, 50%, 0)"), o.addColorStop(1, "hsla(" + e + ", 100%, 50%, 1)"), t.fillStyle = n, t.fillRect(0, 0, r, i), t.fillStyle = o, t.globalCompositeOperation = "multiply", t.fillRect(0, 0, r, i), t.globalCompositeOperation = "source-over"
                }, grabColor: function (e, r) {
                    return t.getImageData(e, r, 1, 1).data
                }, findColor: function (e, t, n) {
                    var a = (0, s.rgbToHsv)(e, t, n), l = o(a, 3), c = l[1], h = l[2];
                    return [c * r, i - h * i]
                }
            }
        }

        function A(e, t, r) {
            return null === e ? t : /^\s*$/.test(e) ? r : !!/true|yes|1/i.test(e) || !/false|no|0/i.test(e) && t
        }

        function y(e, t, r) {
            if (null === e) return t;
            if (/^\s*$/.test(e)) return r;
            var i = e.split(",").map(Number);
            return 2 === i.length && i[0] && i[1] ? i : t
        }

        var k = function () {
            function e(t, r) {
                if (c(this, e), r ? (t = v(t), this.options = Object.assign({}, d, r)) : t && (0, n.default)(t) ? (this.options = Object.assign({}, d, t), t = v(this.options.attachTo)) : (this.options = Object.assign({}, d), t = v((0, s.nvl)(t, this.options.attachTo))), !t) throw new Error("Container not found: " + this.options.attachTo);
                !function (e, t) {
                    var r = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : "acp-";
                    if (t.hasAttribute(r + "show-hsl") && (e.showHSL = A(t.getAttribute(r + "show-hsl"), d.showHSL, !0)), t.hasAttribute(r + "show-rgb") && (e.showRGB = A(t.getAttribute(r + "show-rgb"), d.showRGB, !0)), t.hasAttribute(r + "show-hex") && (e.showHEX = A(t.getAttribute(r + "show-hex"), d.showHEX, !0)), t.hasAttribute(r + "show-alpha") && (e.showAlpha = A(t.getAttribute(r + "show-alpha"), d.showAlpha, !0)), t.hasAttribute(r + "palette-editable") && (e.paletteEditable = A(t.getAttribute(r + "palette-editable"), d.paletteEditable, !0)), t.hasAttribute(r + "sl-bar-size") && (e.slBarSize = y(t.getAttribute(r + "sl-bar-size"), d.slBarSize, [232, 150])), t.hasAttribute(r + "hue-bar-size") && (e.hueBarSize = y(t.getAttribute(r + "hue-bar-size"), d.hueBarSize, [150, 11]), e.alphaBarSize = e.hueBarSize), t.hasAttribute(r + "palette")) {
                        var i = t.getAttribute(r + "palette");
                        switch (i) {
                            case"PALETTE_MATERIAL_500":
                                e.palette = s.PALETTE_MATERIAL_500;
                                break;
                            case"PALETTE_MATERIAL_CHROME":
                            case"":
                                e.palette = s.PALETTE_MATERIAL_CHROME;
                                break;
                            default:
                                e.palette = i.split(/[;|]/)
                        }
                    }
                    t.hasAttribute(r + "color") && (e.color = t.getAttribute(r + "color"))
                }(this.options, t), this.H = 0, this.S = 0, this.L = 0, this.R = 0, this.G = 0, this.B = 0, this.A = 1, this.palette = {}, this.element = document.createElement("div"), this.options.id && (this.element.id = this.options.id), this.element.className = "a-color-picker", this.element.innerHTML = a.default, t.appendChild(this.element);
                var i = this.element.querySelector(".a-color-picker-h");
                this.setupHueCanvas(i), this.hueBarHelper = m(i), this.huePointer = this.element.querySelector(".a-color-picker-h+.a-color-picker-dot");
                var o = this.element.querySelector(".a-color-picker-sl");
                this.setupSlCanvas(o), this.slBarHelper = m(o), this.slPointer = this.element.querySelector(".a-color-picker-sl+.a-color-picker-dot"), this.preview = this.element.querySelector(".a-color-picker-preview"), this.setupClipboard(this.preview.querySelector(".a-color-picker-clipbaord")), this.options.showHSL ? (this.setupInput(this.inputH = this.element.querySelector(".a-color-picker-hsl>input[nameref=H]")), this.setupInput(this.inputS = this.element.querySelector(".a-color-picker-hsl>input[nameref=S]")), this.setupInput(this.inputL = this.element.querySelector(".a-color-picker-hsl>input[nameref=L]"))) : this.element.querySelector(".a-color-picker-hsl").remove(), this.options.showRGB ? (this.setupInput(this.inputR = this.element.querySelector(".a-color-picker-rgb>input[nameref=R]")), this.setupInput(this.inputG = this.element.querySelector(".a-color-picker-rgb>input[nameref=G]")), this.setupInput(this.inputB = this.element.querySelector(".a-color-picker-rgb>input[nameref=B]"))) : this.element.querySelector(".a-color-picker-rgb").remove(), this.options.showHEX ? this.setupInput(this.inputRGBHEX = this.element.querySelector("input[nameref=RGBHEX]")) : this.element.querySelector(".a-color-picker-rgbhex").remove(), this.options.paletteEditable || this.options.palette && this.options.palette.length > 0 ? this.setPalette(this.paletteRow = this.element.querySelector(".a-color-picker-palette")) : (this.paletteRow = this.element.querySelector(".a-color-picker-palette"), this.paletteRow.remove()), this.options.showAlpha ? (this.setupAlphaCanvas(this.element.querySelector(".a-color-picker-a")), this.alphaPointer = this.element.querySelector(".a-color-picker-a+.a-color-picker-dot")) : this.element.querySelector(".a-color-picker-alpha").remove(), this.element.style.width = this.options.slBarSize[0] + "px", this.onValueChanged(f, this.options.color)
            }

            return i(e, [{
                key: "setupHueCanvas", value: function (e) {
                    var t = this;
                    e.width = this.options.hueBarSize[0], e.height = this.options.hueBarSize[1];
                    for (var r = e.getContext("2d"), i = r.createLinearGradient(0, 0, this.options.hueBarSize[0], 0), o = 0; o <= 1; o += 1 / 360) i.addColorStop(o, "hsl(" + 360 * o + ", 100%, 50%)");
                    r.fillStyle = i, r.fillRect(0, 0, this.options.hueBarSize[0], this.options.hueBarSize[1]);
                    var n = function (r) {
                        var i = (0, s.limit)(r.clientX - e.getBoundingClientRect().left, 0, t.options.hueBarSize[0]),
                            o = Math.round(360 * i / t.options.hueBarSize[0]);
                        t.huePointer.style.left = i - 7 + "px", t.onValueChanged("H", o)
                    }, a = function e() {
                        document.removeEventListener("mousemove", n), document.removeEventListener("mouseup", e)
                    };
                    e.addEventListener("mousedown", function (e) {
                        n(e), document.addEventListener("mousemove", n), document.addEventListener("mouseup", a)
                    })
                }
            }, {
                key: "setupSlCanvas", value: function (e) {
                    var t = this;
                    e.width = this.options.slBarSize[0], e.height = this.options.slBarSize[1];
                    var r = function (r) {
                        var i = (0, s.limit)(r.clientX - e.getBoundingClientRect().left, 0, t.options.slBarSize[0] - 1),
                            o = (0, s.limit)(r.clientY - e.getBoundingClientRect().top, 0, t.options.slBarSize[1] - 1),
                            n = t.slBarHelper.grabColor(i, o);
                        t.slPointer.style.left = i - 7 + "px", t.slPointer.style.top = o - 7 + "px", t.onValueChanged("RGB", n)
                    }, i = function e() {
                        document.removeEventListener("mousemove", r), document.removeEventListener("mouseup", e)
                    };
                    e.addEventListener("mousedown", function (e) {
                        r(e), document.addEventListener("mousemove", r), document.addEventListener("mouseup", i)
                    })
                }
            }, {
                key: "setupAlphaCanvas", value: function (e) {
                    var t = this;
                    e.width = this.options.alphaBarSize[0], e.height = this.options.alphaBarSize[1];
                    var r = e.getContext("2d"), i = r.createLinearGradient(0, 0, e.width - 1, 0);
                    i.addColorStop(0, "hsla(0, 0%, 50%, 0)"), i.addColorStop(1, "hsla(0, 0%, 50%, 1)"), r.fillStyle = i, r.fillRect(0, 0, this.options.alphaBarSize[0], this.options.alphaBarSize[1]);
                    var o = function (r) {
                        var i = (0, s.limit)(r.clientX - e.getBoundingClientRect().left, 0, t.options.alphaBarSize[0]),
                            o = +(i / t.options.alphaBarSize[0]).toFixed(2);
                        t.alphaPointer.style.left = i - 7 + "px", t.onValueChanged("ALPHA", o)
                    }, n = function e() {
                        document.removeEventListener("mousemove", o), document.removeEventListener("mouseup", e)
                    };
                    e.addEventListener("mousedown", function (e) {
                        o(e), document.addEventListener("mousemove", o), document.addEventListener("mouseup", n)
                    })
                }
            }, {
                key: "setupInput", value: function (e) {
                    var t = this, r = +e.min, i = +e.max, o = e.getAttribute("nameref");
                    e.hasAttribute("select-on-focus") && e.addEventListener("focus", function () {
                        e.select()
                    }), "text" === e.type ? e.addEventListener("change", function () {
                        t.onValueChanged(o, e.value)
                    }) : ((u || p) && e.addEventListener("keydown", function (n) {
                        "Up" === n.key ? (e.value = (0, s.limit)(+e.value + 1, r, i), t.onValueChanged(o, e.value), n.returnValue = !1) : "Down" === n.key && (e.value = (0, s.limit)(+e.value - 1, r, i), t.onValueChanged(o, e.value), n.returnValue = !1)
                    }), e.addEventListener("change", function () {
                        var n = +e.value;
                        t.onValueChanged(o, (0, s.limit)(n, r, i))
                    }))
                }
            }, {
                key: "setupClipboard", value: function (e) {
                    var t = this;
                    e.title = "click to copy", e.addEventListener("click", function () {
                        e.value = (0, s.parseColor)([t.R, t.G, t.B, t.A], "hexcss4"), e.select(), document.execCommand("copy")
                    })
                }
            }, {
                key: "setPalette", value: function (e) {
                    var t = this, r = "auto" === this.options.useAlphaInPalette ? this.options.showAlpha : this.options.useAlphaInPalette,
                        i = null;
                    switch (this.options.palette) {
                        case"PALETTE_MATERIAL_500":
                            i = s.PALETTE_MATERIAL_500;
                            break;
                        case"PALETTE_MATERIAL_CHROME":
                            i = s.PALETTE_MATERIAL_CHROME;
                            break;
                        default:
                            i = (0, s.ensureArray)(this.options.palette)
                    }
                    if (this.options.paletteEditable || i.length > 0) {
                        var o = function (r, i, o) {
                            var s = e.querySelector('.a-color-picker-palette-color[data-color="' + r + '"]') || document.createElement("div");
                            s.className = "a-color-picker-palette-color", s.style.backgroundColor = r, s.setAttribute("data-color", r), s.title = r, e.insertBefore(s, i), t.palette[r] = !0, o && t.onPaletteColorAdd(r)
                        }, n = function (r, i) {
                            r ? (e.removeChild(r), t.palette[r.getAttribute("data-color")] = !1, i && t.onPaletteColorRemove(r.getAttribute("data-color"))) : (e.querySelectorAll(".a-color-picker-palette-color[data-color]").forEach(function (t) {
                                e.removeChild(t)
                            }), Object.keys(t.palette).forEach(function (e) {
                                t.palette[e] = !1
                            }), i && t.onPaletteColorRemove())
                        };
                        if (i.map(function (e) {
                            return (0, s.parseColor)(e, r ? "rgbcss4" : "hex")
                        }).filter(function (e) {
                            return !!e
                        }).forEach(function (e) {
                            return o(e)
                        }), this.options.paletteEditable) {
                            var a = document.createElement("div");
                            a.className = "a-color-picker-palette-color a-color-picker-palette-add", a.innerHTML = "+", e.appendChild(a), e.addEventListener("click", function (e) {
                                /a-color-picker-palette-add/.test(e.target.className) ? e.shiftKey ? n(null, !0) : o(r ? (0, s.parseColor)([t.R, t.G, t.B, t.A], "rgbcss4") : (0, s.rgbToHex)(t.R, t.G, t.B), e.target, !0) : /a-color-picker-palette-color/.test(e.target.className) && (e.shiftKey ? n(e.target, !0) : t.onValueChanged(f, e.target.getAttribute("data-color")))
                            })
                        } else e.addEventListener("click", function (e) {
                            /a-color-picker-palette-color/.test(e.target.className) && t.onValueChanged(f, e.target.getAttribute("data-color"))
                        })
                    } else e.style.display = "none"
                }
            }, {
                key: "updatePalette", value: function (e) {
                    this.paletteRow.innerHTML = "", this.palette = {}, this.paletteRow.parentElement || this.element.appendChild(this.paletteRow), this.options.palette = e, this.setPalette(this.paletteRow)
                }
            }, {
                key: "onValueChanged", value: function (e, t) {
                    var r = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {silent: !1};
                    switch (e) {
                        case"H":
                            this.H = t;
                            var i = (0, s.hslToRgb)(this.H, this.S, this.L), n = o(i, 3);
                            this.R = n[0], this.G = n[1], this.B = n[2], this.slBarHelper.setHue(t), this.updatePointerH(this.H), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updateInputRGBHEX(this.R, this.G, this.B, this.A);
                            break;
                        case"S":
                            this.S = t;
                            var a = (0, s.hslToRgb)(this.H, this.S, this.L), l = o(a, 3);
                            this.R = l[0], this.G = l[1], this.B = l[2], this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updateInputRGBHEX(this.R, this.G, this.B, this.A);
                            break;
                        case"L":
                            this.L = t;
                            var c = (0, s.hslToRgb)(this.H, this.S, this.L), h = o(c, 3);
                            this.R = h[0], this.G = h[1], this.B = h[2], this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updateInputRGBHEX(this.R, this.G, this.B, this.A);
                            break;
                        case"R":
                            this.R = t;
                            var u = (0, s.rgbToHsl)(this.R, this.G, this.B), p = o(u, 3);
                            this.H = p[0], this.S = p[1], this.L = p[2], this.slBarHelper.setHue(this.H), this.updatePointerH(this.H), this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGBHEX(this.R, this.G, this.B, this.A);
                            break;
                        case"G":
                            this.G = t;
                            var d = (0, s.rgbToHsl)(this.R, this.G, this.B), v = o(d, 3);
                            this.H = v[0], this.S = v[1], this.L = v[2], this.slBarHelper.setHue(this.H), this.updatePointerH(this.H), this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGBHEX(this.R, this.G, this.B, this.A);
                            break;
                        case"B":
                            this.B = t;
                            var m = (0, s.rgbToHsl)(this.R, this.G, this.B), A = o(m, 3);
                            this.H = A[0], this.S = A[1], this.L = A[2], this.slBarHelper.setHue(this.H), this.updatePointerH(this.H), this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGBHEX(this.R, this.G, this.B, this.A);
                            break;
                        case"RGB":
                            var y = o(t, 3);
                            this.R = y[0], this.G = y[1], this.B = y[2];
                            var k = (0, s.rgbToHsl)(this.R, this.G, this.B), F = o(k, 3);
                            this.H = F[0], this.S = F[1], this.L = F[2], this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updateInputRGBHEX(this.R, this.G, this.B, this.A);
                            break;
                        case g:
                            var E = o(t, 4);
                            this.R = E[0], this.G = E[1], this.B = E[2], this.A = E[3];
                            var B = (0, s.rgbToHsl)(this.R, this.G, this.B), H = o(B, 3);
                            this.H = H[0], this.S = H[1], this.L = H[2], this.slBarHelper.setHue(this.H), this.updatePointerH(this.H), this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updateInputRGBHEX(this.R, this.G, this.B, this.A), this.updatePointerA(this.A);
                            break;
                        case b:
                            var R = o(t, 4);
                            this.H = R[0], this.S = R[1], this.L = R[2], this.A = R[3];
                            var C = (0, s.hslToRgb)(this.H, this.S, this.L), S = o(C, 3);
                            this.R = S[0], this.G = S[1], this.B = S[2], this.slBarHelper.setHue(this.H), this.updatePointerH(this.H), this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updateInputRGBHEX(this.R, this.G, this.B, this.A), this.updatePointerA(this.A);
                            break;
                        case"RGBHEX":
                            var L = (0, s.cssColorToRgba)(t) || [this.R, this.G, this.B, this.A], w = o(L, 4);
                            this.R = w[0], this.G = w[1], this.B = w[2], this.A = w[3];
                            var T = (0, s.rgbToHsl)(this.R, this.G, this.B), x = o(T, 3);
                            this.H = x[0], this.S = x[1], this.L = x[2], this.slBarHelper.setHue(this.H), this.updatePointerH(this.H), this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updatePointerA(this.A);
                            break;
                        case f:
                            var G = (0, s.parseColor)(t, "rgba") || [0, 0, 0, 1], I = o(G, 4);
                            this.R = I[0], this.G = I[1], this.B = I[2], this.A = I[3];
                            var P = (0, s.rgbToHsl)(this.R, this.G, this.B), D = o(P, 3);
                            this.H = D[0], this.S = D[1], this.L = D[2], this.slBarHelper.setHue(this.H), this.updatePointerH(this.H), this.updatePointerSL(this.H, this.S, this.L), this.updateInputHSL(this.H, this.S, this.L), this.updateInputRGB(this.R, this.G, this.B), this.updateInputRGBHEX(this.R, this.G, this.B, this.A), this.updatePointerA(this.A);
                            break;
                        case"ALPHA":
                            this.A = t, this.updateInputRGBHEX(this.R, this.G, this.B, this.A)
                    }
                    1 === this.A ? this.preview.style.backgroundColor = "rgb(" + this.R + "," + this.G + "," + this.B + ")" : this.preview.style.backgroundColor = "rgba(" + this.R + "," + this.G + "," + this.B + "," + this.A + ")", r && r.silent || this.onchange && this.onchange(this.preview.style.backgroundColor)
                }
            }, {
                key: "onPaletteColorAdd", value: function (e) {
                    this.oncoloradd && this.oncoloradd(e)
                }
            }, {
                key: "onPaletteColorRemove", value: function (e) {
                    this.oncolorremove && this.oncolorremove(e)
                }
            }, {
                key: "updateInputHSL", value: function (e, t, r) {
                    this.options.showHSL && (this.inputH.value = e, this.inputS.value = t, this.inputL.value = r)
                }
            }, {
                key: "updateInputRGB", value: function (e, t, r) {
                    this.options.showRGB && (this.inputR.value = e, this.inputG.value = t, this.inputB.value = r)
                }
            }, {
                key: "updateInputRGBHEX", value: function (e, t, r, i) {
                    this.options.showHEX && (this.options.showAlpha ? this.inputRGBHEX.value = (0, s.parseColor)([e, t, r, i], "hexcss4") : this.inputRGBHEX.value = (0, s.rgbToHex)(e, t, r))
                }
            }, {
                key: "updatePointerH", value: function (e) {
                    var t = this.options.hueBarSize[0] * e / 360;
                    this.huePointer.style.left = t - 7 + "px"
                }
            }, {
                key: "updatePointerSL", value: function (e, t, r) {
                    var i = (0, s.hslToRgb)(e, t, r), n = o(i, 3), a = n[0], l = n[1], c = n[2], h = this.slBarHelper.findColor(a, l, c),
                        u = o(h, 2), p = u[0], d = u[1];
                    p >= 0 && (this.slPointer.style.left = p - 7 + "px", this.slPointer.style.top = d - 7 + "px")
                }
            }, {
                key: "updatePointerA", value: function (e) {
                    if (this.options.showAlpha) {
                        var t = this.options.alphaBarSize[0] * e;
                        this.alphaPointer.style.left = t - 7 + "px"
                    }
                }
            }]), e
        }(), F = function () {
            function e(t) {
                c(this, e), this.name = t, this.listeners = []
            }

            return i(e, [{
                key: "on", value: function (e) {
                    e && this.listeners.push(e)
                }
            }, {
                key: "off", value: function (e) {
                    this.listeners = e ? this.listeners.filter(function (t) {
                        return t !== e
                    }) : []
                }
            }, {
                key: "emit", value: function (e, t) {
                    for (var r = this.listeners.slice(0), i = 0; i < r.length; i++) r[i].apply(t, e)
                }
            }]), e
        }();

        function E(e, t) {
            var r = new k(e, t), i = {change: new F("change"), coloradd: new F("coloradd"), colorremove: new F("colorremove")}, n = !0,
                a = {}, l = {
                    get element() {
                        return r.element
                    }, get rgb() {
                        return [r.R, r.G, r.B]
                    }, set rgb(e) {
                        var t = o(e, 3), i = t[0], n = t[1], a = t[2],
                            l = [(0, s.limit)(i, 0, 255), (0, s.limit)(n, 0, 255), (0, s.limit)(a, 0, 255)];
                        i = l[0], n = l[1], a = l[2], r.onValueChanged(g, [i, n, a, 1])
                    }, get hsl() {
                        return [r.H, r.S, r.L]
                    }, set hsl(e) {
                        var t = o(e, 3), i = t[0], n = t[1], a = t[2],
                            l = [(0, s.limit)(i, 0, 360), (0, s.limit)(n, 0, 100), (0, s.limit)(a, 0, 100)];
                        i = l[0], n = l[1], a = l[2], r.onValueChanged(b, [i, n, a, 1])
                    }, get rgbhex() {
                        return this.all.hex
                    }, get rgba() {
                        return [r.R, r.G, r.B, r.A]
                    }, set rgba(e) {
                        var t = o(e, 4), i = t[0], n = t[1], a = t[2], l = t[3],
                            c = [(0, s.limit)(i, 0, 255), (0, s.limit)(n, 0, 255), (0, s.limit)(a, 0, 255), (0, s.limit)(l, 0, 1)];
                        i = c[0], n = c[1], a = c[2], l = c[3], r.onValueChanged(g, [i, n, a, l])
                    }, get hsla() {
                        return [r.H, r.S, r.L, r.A]
                    }, set hsla(e) {
                        var t = o(e, 4), i = t[0], n = t[1], a = t[2], l = t[3],
                            c = [(0, s.limit)(i, 0, 360), (0, s.limit)(n, 0, 100), (0, s.limit)(a, 0, 100), (0, s.limit)(l, 0, 1)];
                        i = c[0], n = c[1], a = c[2], l = c[3], r.onValueChanged(b, [i, n, a, l])
                    }, get color() {
                        return this.all.toString()
                    }, set color(e) {
                        r.onValueChanged(f, e)
                    }, setColor: function (e) {
                        var t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
                        r.onValueChanged(f, e, {silent: t})
                    }, get all() {
                        if (n) {
                            var e = [r.R, r.G, r.B, r.A],
                                t = r.A < 1 ? "rgba(" + r.R + "," + r.G + "," + r.B + "," + r.A + ")" : s.rgbToHex.apply(void 0, e);
                            (a = (0, s.parseColor)(e, a)).toString = function () {
                                return t
                            }, n = !1
                        }
                        return Object.assign({}, a)
                    }, get onchange() {
                        return i.change && i.change.listeners[0]
                    }, set onchange(e) {
                        this.off("change").on("change", e)
                    }, get oncoloradd() {
                        return i.coloradd && i.coloradd.listeners[0]
                    }, set oncoloradd(e) {
                        this.off("coloradd").on("coloradd", e)
                    }, get oncolorremove() {
                        return i.colorremove && i.colorremove.listeners[0]
                    }, set oncolorremove(e) {
                        this.off("colorremove").on("colorremove", e)
                    }, get palette() {
                        return Object.keys(r.palette).filter(function (e) {
                            return r.palette[e]
                        })
                    }, set palette(e) {
                        r.updatePalette(e)
                    }, show: function () {
                        r.element.classList.remove("hidden")
                    }, hide: function () {
                        r.element.classList.add("hidden")
                    }, toggle: function () {
                        r.element.classList.toggle("hidden")
                    }, on: function (e, t) {
                        return e && i[e] && i[e].on(t), this
                    }, off: function (e, t) {
                        return e && i[e] && i[e].off(t), this
                    }, destroy: function () {
                        i.change.off(), i.coloradd.off(), i.colorremove.off(), r.element.remove(), i = null, r = null
                    }
                };
            return r.onchange = function () {
                for (var e = arguments.length, t = Array(e), r = 0; r < e; r++) t[r] = arguments[r];
                n = !0, i.change.emit([l].concat(t), l)
            }, r.oncoloradd = function () {
                for (var e = arguments.length, t = Array(e), r = 0; r < e; r++) t[r] = arguments[r];
                i.coloradd.emit([l].concat(t), l)
            }, r.oncolorremove = function () {
                for (var e = arguments.length, t = Array(e), r = 0; r < e; r++) t[r] = arguments[r];
                i.colorremove.emit([l].concat(t), l)
            }, r.element.ctrl = l, l
        }

        if ("undefined" != typeof window && !document.querySelector('head>style[data-source="a-color-picker"]')) {
            var B = r(5).toString(), H = document.createElement("style");
            H.setAttribute("type", "text/css"), H.setAttribute("data-source", "a-color-picker"), H.innerHTML = B, document.querySelector("head").appendChild(H)
        }
        t.createPicker = E, t.from = function (e, t) {
            var r = function (e) {
                return e ? Array.isArray(e) ? e : e instanceof HTMLElement ? [e] : e instanceof NodeList ? [].concat(h(e)) : "string" == typeof e ? [].concat(h(document.querySelectorAll(e))) : e.jquery ? e.get() : [] : []
            }(e).map(function (e, r) {
                var i = E(e, t);
                return i.index = r, i
            });
            return r.on = function (e, t) {
                return r.forEach(function (r) {
                    return r.on(e, t)
                }), this
            }, r.off = function (e) {
                return r.forEach(function (t) {
                    return t.off(e)
                }), this
            }, r
        }, t.parseColorToRgb = s.parseColorToRgb, t.parseColorToRgba = s.parseColorToRgba, t.parseColorToHsl = s.parseColorToHsl, t.parseColorToHsla = s.parseColorToHsla, t.parseColor = s.parseColor, t.rgbToHex = s.rgbToHex, t.hslToRgb = s.hslToRgb, t.rgbToHsl = s.rgbToHsl, t.rgbToHsv = s.rgbToHsv, t.rgbToInt = s.rgbToInt, t.intToRgb = s.intToRgb, t.getLuminance = s.getLuminance, t.COLOR_NAMES = s.COLOR_NAMES, t.PALETTE_MATERIAL_500 = s.PALETTE_MATERIAL_500, t.PALETTE_MATERIAL_CHROME = s.PALETTE_MATERIAL_CHROME, t.VERSION = "1.2.2"
    }, function (e, t, r) {
        "use strict";
        Object.defineProperty(t, "__esModule", {value: !0}), t.nvl = t.ensureArray = t.limit = t.getLuminance = t.parseColor = t.parseColorToHsla = t.parseColorToHsl = t.cssHslaToHsla = t.cssHslToHsl = t.parseColorToRgba = t.parseColorToRgb = t.cssRgbaToRgba = t.cssRgbToRgb = t.cssColorToRgba = t.cssColorToRgb = t.intToRgb = t.rgbToInt = t.rgbToHsv = t.rgbToHsl = t.hslToRgb = t.rgbToHex = t.PALETTE_MATERIAL_CHROME = t.PALETTE_MATERIAL_500 = t.COLOR_NAMES = void 0;
        var i = function (e, t) {
            if (Array.isArray(e)) return e;
            if (Symbol.iterator in Object(e)) return function (e, t) {
                var r = [], i = !0, o = !1, s = void 0;
                try {
                    for (var n, a = e[Symbol.iterator](); !(i = (n = a.next()).done) && (r.push(n.value), !t || r.length !== t); i = !0) ;
                } catch (e) {
                    o = !0, s = e
                } finally {
                    try {
                        !i && a.return && a.return()
                    } finally {
                        if (o) throw s
                    }
                }
                return r
            }(e, t);
            throw new TypeError("Invalid attempt to destructure non-iterable instance")
        }, o = function (e) {
            return e && e.__esModule ? e : {default: e}
        }(r(0));

        function s(e) {
            if (Array.isArray(e)) {
                for (var t = 0, r = Array(e.length); t < e.length; t++) r[t] = e[t];
                return r
            }
            return Array.from(e)
        }

        var n = {
            aliceblue: "#f0f8ff",
            antiquewhite: "#faebd7",
            aqua: "#00ffff",
            aquamarine: "#7fffd4",
            azure: "#f0ffff",
            beige: "#f5f5dc",
            bisque: "#ffe4c4",
            black: "#000000",
            blanchedalmond: "#ffebcd",
            blue: "#0000ff",
            blueviolet: "#8a2be2",
            brown: "#a52a2a",
            burlywood: "#deb887",
            cadetblue: "#5f9ea0",
            chartreuse: "#7fff00",
            chocolate: "#d2691e",
            coral: "#ff7f50",
            cornflowerblue: "#6495ed",
            cornsilk: "#fff8dc",
            crimson: "#dc143c",
            cyan: "#00ffff",
            darkblue: "#00008b",
            darkcyan: "#008b8b",
            darkgoldenrod: "#b8860b",
            darkgray: "#a9a9a9",
            darkgrey: "#a9a9a9",
            darkgreen: "#006400",
            darkkhaki: "#bdb76b",
            darkmagenta: "#8b008b",
            darkolivegreen: "#556b2f",
            darkorange: "#ff8c00",
            darkorchid: "#9932cc",
            darkred: "#8b0000",
            darksalmon: "#e9967a",
            darkseagreen: "#8fbc8f",
            darkslateblue: "#483d8b",
            darkslategray: "#2f4f4f",
            darkslategrey: "#2f4f4f",
            darkturquoise: "#00ced1",
            darkviolet: "#9400d3",
            deeppink: "#ff1493",
            deepskyblue: "#00bfff",
            dimgray: "#696969",
            dimgrey: "#696969",
            dodgerblue: "#1e90ff",
            firebrick: "#b22222",
            floralwhite: "#fffaf0",
            forestgreen: "#228b22",
            fuchsia: "#ff00ff",
            gainsboro: "#dcdcdc",
            ghostwhite: "#f8f8ff",
            gold: "#ffd700",
            goldenrod: "#daa520",
            gray: "#808080",
            grey: "#808080",
            green: "#008000",
            greenyellow: "#adff2f",
            honeydew: "#f0fff0",
            hotpink: "#ff69b4",
            "indianred ": "#cd5c5c",
            "indigo ": "#4b0082",
            ivory: "#fffff0",
            khaki: "#f0e68c",
            lavender: "#e6e6fa",
            lavenderblush: "#fff0f5",
            lawngreen: "#7cfc00",
            lemonchiffon: "#fffacd",
            lightblue: "#add8e6",
            lightcoral: "#f08080",
            lightcyan: "#e0ffff",
            lightgoldenrodyellow: "#fafad2",
            lightgray: "#d3d3d3",
            lightgrey: "#d3d3d3",
            lightgreen: "#90ee90",
            lightpink: "#ffb6c1",
            lightsalmon: "#ffa07a",
            lightseagreen: "#20b2aa",
            lightskyblue: "#87cefa",
            lightslategray: "#778899",
            lightslategrey: "#778899",
            lightsteelblue: "#b0c4de",
            lightyellow: "#ffffe0",
            lime: "#00ff00",
            limegreen: "#32cd32",
            linen: "#faf0e6",
            magenta: "#ff00ff",
            maroon: "#800000",
            mediumaquamarine: "#66cdaa",
            mediumblue: "#0000cd",
            mediumorchid: "#ba55d3",
            mediumpurple: "#9370db",
            mediumseagreen: "#3cb371",
            mediumslateblue: "#7b68ee",
            mediumspringgreen: "#00fa9a",
            mediumturquoise: "#48d1cc",
            mediumvioletred: "#c71585",
            midnightblue: "#191970",
            mintcream: "#f5fffa",
            mistyrose: "#ffe4e1",
            moccasin: "#ffe4b5",
            navajowhite: "#ffdead",
            navy: "#000080",
            oldlace: "#fdf5e6",
            olive: "#808000",
            olivedrab: "#6b8e23",
            orange: "#ffa500",
            orangered: "#ff4500",
            orchid: "#da70d6",
            palegoldenrod: "#eee8aa",
            palegreen: "#98fb98",
            paleturquoise: "#afeeee",
            palevioletred: "#db7093",
            papayawhip: "#ffefd5",
            peachpuff: "#ffdab9",
            peru: "#cd853f",
            pink: "#ffc0cb",
            plum: "#dda0dd",
            powderblue: "#b0e0e6",
            purple: "#800080",
            rebeccapurple: "#663399",
            red: "#ff0000",
            rosybrown: "#bc8f8f",
            royalblue: "#4169e1",
            saddlebrown: "#8b4513",
            salmon: "#fa8072",
            sandybrown: "#f4a460",
            seagreen: "#2e8b57",
            seashell: "#fff5ee",
            sienna: "#a0522d",
            silver: "#c0c0c0",
            skyblue: "#87ceeb",
            slateblue: "#6a5acd",
            slategray: "#708090",
            slategrey: "#708090",
            snow: "#fffafa",
            springgreen: "#00ff7f",
            steelblue: "#4682b4",
            tan: "#d2b48c",
            teal: "#008080",
            thistle: "#d8bfd8",
            tomato: "#ff6347",
            turquoise: "#40e0d0",
            violet: "#ee82ee",
            wheat: "#f5deb3",
            white: "#ffffff",
            whitesmoke: "#f5f5f5",
            yellow: "#ffff00",
            yellowgreen: "#9acd32"
        };

        function a(e, t, r) {
            return e = +e, isNaN(e) ? t : e < t ? t : e > r ? r : e
        }

        function l(e, t) {
            return null == e ? t : e
        }

        function c(e, t, r) {
            var i = [a(e, 0, 255), a(t, 0, 255), a(r, 0, 255)];
            return "#" + ("000000" + ((e = i[0]) << 16 | (t = i[1]) << 8 | (r = i[2])).toString(16)).slice(-6)
        }

        function h(e, t, r) {
            var i = void 0, o = void 0, s = void 0, n = [a(e, 0, 360) / 360, a(t, 0, 100) / 100, a(r, 0, 100) / 100];
            if (e = n[0], r = n[2], 0 == (t = n[1])) i = o = s = r; else {
                var l = function (e, t, r) {
                    return r < 0 && (r += 1), r > 1 && (r -= 1), r < 1 / 6 ? e + 6 * (t - e) * r : r < .5 ? t : r < 2 / 3 ? e + (t - e) * (2 / 3 - r) * 6 : e
                }, c = r < .5 ? r * (1 + t) : r + t - r * t, h = 2 * r - c;
                i = l(h, c, e + 1 / 3), o = l(h, c, e), s = l(h, c, e - 1 / 3)
            }
            return [255 * i, 255 * o, 255 * s].map(Math.round)
        }

        function u(e, t, r) {
            var i = [a(e, 0, 255) / 255, a(t, 0, 255) / 255, a(r, 0, 255) / 255];
            e = i[0], t = i[1], r = i[2];
            var o = Math.max(e, t, r), s = Math.min(e, t, r), n = void 0, l = void 0, c = (o + s) / 2;
            if (o == s) n = l = 0; else {
                var h = o - s;
                switch (l = c > .5 ? h / (2 - o - s) : h / (o + s), o) {
                    case e:
                        n = (t - r) / h + (t < r ? 6 : 0);
                        break;
                    case t:
                        n = (r - e) / h + 2;
                        break;
                    case r:
                        n = (e - t) / h + 4
                }
                n /= 6
            }
            return [360 * n, 100 * l, 100 * c].map(Math.round)
        }

        function p(e, t, r) {
            return e << 16 | t << 8 | r
        }

        function d(e) {
            if (e) {
                var t = n[e.toString().toLowerCase()],
                    r = /^\s*#?((([0-9A-F])([0-9A-F])([0-9A-F]))|(([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})))\s*$/i.exec(t || e) || [],
                    o = i(r, 10), s = o[3], a = o[4], l = o[5], c = o[7], h = o[8], u = o[9];
                if (void 0 !== s) return [parseInt(s + s, 16), parseInt(a + a, 16), parseInt(l + l, 16)];
                if (void 0 !== c) return [parseInt(c, 16), parseInt(h, 16), parseInt(u, 16)]
            }
        }

        function f(e) {
            if (e) {
                var t = n[e.toString().toLowerCase()],
                    r = /^\s*#?((([0-9A-F])([0-9A-F])([0-9A-F])([0-9A-F])?)|(([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})?))\s*$/i.exec(t || e) || [],
                    o = i(r, 12), s = o[3], a = o[4], l = o[5], c = o[6], h = o[8], u = o[9], p = o[10], d = o[11];
                if (void 0 !== s) return [parseInt(s + s, 16), parseInt(a + a, 16), parseInt(l + l, 16), c ? +(parseInt(c + c, 16) / 255).toFixed(2) : 1];
                if (void 0 !== h) return [parseInt(h, 16), parseInt(u, 16), parseInt(p, 16), d ? +(parseInt(d, 16) / 255).toFixed(2) : 1]
            }
        }

        function g(e) {
            if (e) {
                var t = /^rgb\((\d+)[\s,](\d+)[\s,](\d+)\)/i.exec(e) || [], r = i(t, 4), o = r[0], s = r[1], n = r[2], l = r[3];
                return o ? [a(s, 0, 255), a(n, 0, 255), a(l, 0, 255)] : void 0
            }
        }

        function b(e) {
            if (e) {
                var t = /^rgba?\((\d+)\s*[\s,]\s*(\d+)\s*[\s,]\s*(\d+)(\s*[\s,]\s*(\d*(.\d+)?))?\)/i.exec(e) || [], r = i(t, 6), o = r[0],
                    s = r[1], n = r[2], c = r[3], h = r[5];
                return o ? [a(s, 0, 255), a(n, 0, 255), a(c, 0, 255), a(l(h, 1), 0, 1)] : void 0
            }
        }

        function v(e) {
            if (Array.isArray(e)) return [a(e[0], 0, 255), a(e[1], 0, 255), a(e[2], 0, 255), a(l(e[3], 1), 0, 1)];
            var t = f(e) || b(e);
            return t && 3 === t.length && t.push(1), t
        }

        function m(e) {
            if (e) {
                var t = /^hsl\((\d+)[\s,](\d+)[\s,](\d+)\)/i.exec(e) || [], r = i(t, 4), o = r[0], s = r[1], n = r[2], l = r[3];
                return o ? [a(s, 0, 360), a(n, 0, 100), a(l, 0, 100)] : void 0
            }
        }

        function A(e) {
            if (e) {
                var t = /^hsla?\((\d+)\s*[\s,]\s*(\d+)\s*[\s,]\s*(\d+)(\s*[\s,]\s*(\d*(.\d+)?))?\)/i.exec(e) || [], r = i(t, 6), o = r[0],
                    s = r[1], n = r[2], c = r[3], h = r[5];
                return o ? [a(s, 0, 255), a(n, 0, 255), a(c, 0, 255), a(l(h, 1), 0, 1)] : void 0
            }
        }

        function y(e) {
            if (Array.isArray(e)) return [a(e[0], 0, 360), a(e[1], 0, 100), a(e[2], 0, 100), a(l(e[3], 1), 0, 1)];
            var t = A(e);
            return t && 3 === t.length && t.push(1), t
        }

        function k(e, t) {
            switch (t) {
                case"rgb":
                default:
                    return e.slice(0, 3);
                case"rgbcss":
                    return "rgb(" + e[0] + ", " + e[1] + ", " + e[2] + ")";
                case"rgbcss4":
                    return "rgb(" + e[0] + ", " + e[1] + ", " + e[2] + ", " + e[3] + ")";
                case"rgba":
                    return e;
                case"rgbacss":
                    return "rgba(" + e[0] + ", " + e[1] + ", " + e[2] + ", " + e[3] + ")";
                case"hsl":
                    return u.apply(void 0, s(e));
                case"hslcss":
                    return "hsl(" + (e = u.apply(void 0, s(e)))[0] + ", " + e[1] + ", " + e[2] + ")";
                case"hslcss4":
                    var r = u.apply(void 0, s(e));
                    return "hsl(" + r[0] + ", " + r[1] + ", " + r[2] + ", " + e[3] + ")";
                case"hsla":
                    return [].concat(s(u.apply(void 0, s(e))), [e[3]]);
                case"hslacss":
                    var i = u.apply(void 0, s(e));
                    return "hsla(" + i[0] + ", " + i[1] + ", " + i[2] + ", " + e[3] + ")";
                case"hex":
                    return c.apply(void 0, s(e));
                case"hexcss4":
                    return c.apply(void 0, s(e)) + ("00" + parseInt(255 * e[3]).toString(16)).slice(-2);
                case"int":
                    return p.apply(void 0, s(e))
            }
        }

        t.COLOR_NAMES = n, t.PALETTE_MATERIAL_500 = ["#f44336", "#e91e63", "#e91e63", "#9c27b0", "#9c27b0", "#673ab7", "#673ab7", "#3f51b5", "#3f51b5", "#2196f3", "#2196f3", "#03a9f4", "#03a9f4", "#00bcd4", "#00bcd4", "#009688", "#009688", "#4caf50", "#4caf50", "#8bc34a", "#8bc34a", "#cddc39", "#cddc39", "#ffeb3b", "#ffeb3b", "#ffc107", "#ffc107", "#ff9800", "#ff9800", "#ff5722", "#ff5722", "#795548", "#795548", "#9e9e9e", "#9e9e9e", "#607d8b", "#607d8b"], t.PALETTE_MATERIAL_CHROME = ["#f44336", "#e91e63", "#9c27b0", "#673ab7", "#3f51b5", "#2196f3", "#03a9f4", "#00bcd4", "#009688", "#4caf50", "#8bc34a", "#cddc39", "#ffeb3b", "#ffc107", "#ff9800", "#ff5722", "#795548", "#9e9e9e", "#607d8b"], t.rgbToHex = c, t.hslToRgb = h, t.rgbToHsl = u, t.rgbToHsv = function (e, t, r) {
            var i = [a(e, 0, 255) / 255, a(t, 0, 255) / 255, a(r, 0, 255) / 255];
            e = i[0], t = i[1], r = i[2];
            var o, s = Math.max(e, t, r), n = Math.min(e, t, r), l = void 0, c = s, h = s - n;
            if (o = 0 === s ? 0 : h / s, s == n) l = 0; else {
                switch (s) {
                    case e:
                        l = (t - r) / h + (t < r ? 6 : 0);
                        break;
                    case t:
                        l = (r - e) / h + 2;
                        break;
                    case r:
                        l = (e - t) / h + 4
                }
                l /= 6
            }
            return [l, o, c]
        }, t.rgbToInt = p, t.intToRgb = function (e) {
            return [e >> 16 & 255, e >> 8 & 255, 255 & e]
        }, t.cssColorToRgb = d, t.cssColorToRgba = f, t.cssRgbToRgb = g, t.cssRgbaToRgba = b, t.parseColorToRgb = function (e) {
            return Array.isArray(e) ? e = [a(e[0], 0, 255), a(e[1], 0, 255), a(e[2], 0, 255)] : d(e) || g(e)
        }, t.parseColorToRgba = v, t.cssHslToHsl = m, t.cssHslaToHsla = A, t.parseColorToHsl = function (e) {
            return Array.isArray(e) ? e = [a(e[0], 0, 360), a(e[1], 0, 100), a(e[2], 0, 100)] : m(e)
        }, t.parseColorToHsla = y, t.parseColor = function (e, t) {
            if (t = t || "rgb", null != e) {
                var r = void 0;
                if ((r = v(e)) || (r = y(e)) && (r = [].concat(s(h.apply(void 0, s(r))), [r[3]]))) return (0, o.default)(t) ? ["rgb", "rgbcss", "rgbcss4", "rgba", "rgbacss", "hsl", "hslcss", "hslcss4", "hsla", "hslacss", "hex", "hexcss4", "int"].reduce(function (e, t) {
                    return e[t] = k(r, t), e
                }, t || {}) : k(r, t.toString().toLowerCase())
            }
        }, t.getLuminance = function (e, t, r) {
            return .2126 * (e = (e /= 255) < .03928 ? e / 12.92 : Math.pow((e + .055) / 1.055, 2.4)) + .7152 * (t = (t /= 255) < .03928 ? t / 12.92 : Math.pow((t + .055) / 1.055, 2.4)) + .0722 * ((r /= 255) < .03928 ? r / 12.92 : Math.pow((r + .055) / 1.055, 2.4))
        }, t.limit = a, t.ensureArray = function (e) {
            return e ? Array.from(e) : []
        }, t.nvl = l
    }, function (e, t, r) {
        "use strict";
        /*!
     * isobject <https://github.com/jonschlinkert/isobject>
     *
     * Copyright (c) 2014-2017, Jon Schlinkert.
     * Released under the MIT License.
     */
        e.exports = function (e) {
            return null != e && "object" == typeof e && !1 === Array.isArray(e)
        }
    }, function (e, t) {
        e.exports = '<div class="a-color-picker-row a-color-picker-stack a-color-picker-row-top"> <canvas class="a-color-picker-sl a-color-picker-transparent"></canvas> <div class=a-color-picker-dot></div> </div> <div class=a-color-picker-row> <div class="a-color-picker-stack a-color-picker-transparent a-color-picker-circle"> <div class=a-color-picker-preview> <input class=a-color-picker-clipbaord type=text> </div> </div> <div class=a-color-picker-column> <div class="a-color-picker-cell a-color-picker-stack"> <canvas class=a-color-picker-h></canvas> <div class=a-color-picker-dot></div> </div> <div class="a-color-picker-cell a-color-picker-alpha a-color-picker-stack" show-on-alpha> <canvas class="a-color-picker-a a-color-picker-transparent"></canvas> <div class=a-color-picker-dot></div> </div> </div> </div> <div class="a-color-picker-row a-color-picker-hsl" show-on-hsl> <label>H</label> <input nameref=H type=number maxlength=3 min=0 max=360 value=0> <label>S</label> <input nameref=S type=number maxlength=3 min=0 max=100 value=0> <label>L</label> <input nameref=L type=number maxlength=3 min=0 max=100 value=0> </div> <div class="a-color-picker-row a-color-picker-rgb" show-on-rgb> <label>R</label> <input nameref=R type=number maxlength=3 min=0 max=255 value=0> <label>G</label> <input nameref=G type=number maxlength=3 min=0 max=255 value=0> <label>B</label> <input nameref=B type=number maxlength=3 min=0 max=255 value=0> </div> <div class="a-color-picker-row a-color-picker-rgbhex a-color-picker-single-input" show-on-single-input> <label>HEX</label> <input nameref=RGBHEX type=text select-on-focus> </div> <div class="a-color-picker-row a-color-picker-palette"></div>'
    }, function (e, t, r) {
        var i = r(6);
        e.exports = "string" == typeof i ? i : i.toString()
    }, function (e, t, r) {
        (e.exports = r(7)(!1)).push([e.i, "/*!\n * a-color-picker\n * https://github.com/narsenico/a-color-picker\n *\n * Copyright (c) 2017-2018, Gianfranco Caldi.\n * Released under the MIT License.\n */.a-color-picker{background-color:#fff;padding:0;display:inline-flex;flex-direction:column;user-select:none;width:232px;font:400 10px Helvetica,Arial,sans-serif;border-radius:3px;box-shadow:0 0 0 1px rgba(0,0,0,.05),0 2px 4px rgba(0,0,0,.25)}.a-color-picker,.a-color-picker-row,.a-color-picker input{box-sizing:border-box}.a-color-picker-row{padding:15px;display:flex;flex-direction:row;align-items:center;justify-content:space-between;user-select:none}.a-color-picker-row-top{padding:0}.a-color-picker-row:not(:first-child){border-top:1px solid #f5f5f5}.a-color-picker-column{display:flex;flex-direction:column}.a-color-picker-cell{flex:1 1 auto;margin-bottom:4px}.a-color-picker-cell:last-child{margin-bottom:0}.a-color-picker-stack{position:relative}.a-color-picker-dot{position:absolute;width:14px;height:14px;top:0;left:0;background:#fff;pointer-events:none;border-radius:50px;z-index:1000;box-shadow:0 1px 2px rgba(0,0,0,.75)}.a-color-picker-a,.a-color-picker-h,.a-color-picker-sl{cursor:cell}.a-color-picker-a+.a-color-picker-dot,.a-color-picker-h+.a-color-picker-dot{top:-2px}.a-color-picker-a,.a-color-picker-h{border-radius:2px}.a-color-picker-preview{box-sizing:border-box;width:30px;height:30px;user-select:none;border-radius:15px}.a-color-picker-circle{border-radius:50px;border:1px solid #eee}.a-color-picker-hsl,.a-color-picker-rgb,.a-color-picker-single-input{justify-content:space-evenly}.a-color-picker-hsl>label,.a-color-picker-rgb>label,.a-color-picker-single-input>label{padding:0 8px;flex:0 0 auto;color:#969696}.a-color-picker-hsl>input,.a-color-picker-rgb>input,.a-color-picker-single-input>input{text-align:center;padding:2px 0;width:0;flex:1 1 auto;border:1px solid #e0e0e0;line-height:20px}.a-color-picker-hsl>input::-webkit-inner-spin-button,.a-color-picker-rgb>input::-webkit-inner-spin-button,.a-color-picker-single-input>input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}.a-color-picker-hsl>input:focus,.a-color-picker-rgb>input:focus,.a-color-picker-single-input>input:focus{border-color:#04a9f4;outline:none}.a-color-picker-transparent{background-image:linear-gradient(-45deg,#cdcdcd 25%,transparent 0),linear-gradient(45deg,#cdcdcd 25%,transparent 0),linear-gradient(-45deg,transparent 75%,#cdcdcd 0),linear-gradient(45deg,transparent 75%,#cdcdcd 0);background-size:11px 11px;background-position:0 0,0 -5.5px,-5.5px 5.5px,5.5px 0}.a-color-picker-sl{border-radius:3px 3px 0 0}.a-color-picker.hide-alpha [show-on-alpha],.a-color-picker.hide-hsl [show-on-hsl],.a-color-picker.hide-rgb [show-on-rgb],.a-color-picker.hide-single-input [show-on-single-input]{display:none}.a-color-picker-clipbaord{width:100%;height:100%;opacity:0;cursor:pointer}.a-color-picker-palette{flex-flow:wrap;flex-direction:row;justify-content:flex-start;padding:10px}.a-color-picker-palette-color{width:15px;height:15px;flex:0 1 15px;margiån:3px;box-sizing:border-box;cursor:pointer;border-radius:3px;box-shadow:inset 0 0 0 1px rgba(0,0,0,.1)}.a-color-picker-palette-add{text-align:center;line-height:13px;color:#607d8b}.a-color-picker.hidden{display:none}", ""])
    }, function (e, t) {
        e.exports = function (e) {
            var t = [];
            return t.toString = function () {
                return this.map(function (t) {
                    var r = function (e, t) {
                        var r = e[1] || "", i = e[3];
                        if (!i) return r;
                        if (t && "function" == typeof btoa) {
                            var o = function (e) {
                                return "/*# sourceMappingURL=data:application/json;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(e)))) + " */"
                            }(i), s = i.sources.map(function (e) {
                                return "/*# sourceURL=" + i.sourceRoot + e + " */"
                            });
                            return [r].concat(s).concat([o]).join("\n")
                        }
                        return [r].join("\n")
                    }(t, e);
                    return t[2] ? "@media " + t[2] + "{" + r + "}" : r
                }).join("")
            }, t.i = function (e, r) {
                "string" == typeof e && (e = [[null, e, ""]]);
                for (var i = {}, o = 0; o < this.length; o++) {
                    var s = this[o][0];
                    "number" == typeof s && (i[s] = !0)
                }
                for (o = 0; o < e.length; o++) {
                    var n = e[o];
                    "number" == typeof n[0] && i[n[0]] || (r && !n[2] ? n[2] = r : r && (n[2] = "(" + n[2] + ") and (" + r + ")"), t.push(n))
                }
            }, t
        }
    }])
});