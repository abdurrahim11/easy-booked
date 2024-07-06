/**
 * @return {undefined}
 */
function abs_add_to_calendar() {
    /**
     * @param {string} id
     * @return {?}
     */
    function $(id) {
        return document.getElementById(id);
    }
    var self = function() {
        var e;
        var t;
        var midInPackage;
        var o;
        var conflict;
        var hoverElement;
        var tref;
        var going;
        /** @type {boolean} */
        var r = false;
        /** @type {number} */
        var uuid = 1;
        /** @type {boolean} */
        var files_list = false;
        /** @type {boolean} */
        var p = true;
        /** @type {boolean} */
        var u = false;
        /** @type {boolean} */
        var v = false;
        /** @type {boolean} */
        var m = false;
        /** @type {number} */
        var zIndex = 1;
        /** @type {string} */
        var fragment = "";
        /** @type {boolean} */
        var xmlDoc = true;
        /** @type {boolean} */
        var show = true;
        /** @type {boolean} */
        var w = true;
        /** @type {boolean} */
        var disabled = true;
        /** @type {boolean} */
        var k = true;
        /** @type {boolean} */
        var y = true;
        /** @type {string} */
        var dataText = "Apple Calendar";
        /** @type {string} */
        var error = "Google <em>(online)</em>";
        /** @type {string} */
        var text = "Outlook";
        /** @type {string} */
        var result = "Outlook.com <em>(online)</em>";
        /** @type {string} */
        var txt = "Yahoo <em>(online)</em>";
        /** @type {string} */
        var textValue = "Facebook Event";
        /** @type {null} */
        var callback = null;
        /** @type {null} */
        var msg = null;
        /** @type {null} */
        var fn = null;
        /** @type {null} */
        var _fn = null;
        /** @type {null} */
        var requestFrame = null;
        /** @type {null} */
        var realTrigger = null;
        /** @type {boolean} */
        var M = false;
        return{
            /**
             * @return {undefined}
             */
            initialize : function() {
                if (!r) {
                    /** @type {boolean} */
                    r = true;
                    try {
                        addeventasync();
                    } catch (e) {
                    }
                    /** @type {string} */
                    t = (e = "https:") + "//" + (midInPackage = "addevent.com");
                    /** @type {string} */
                    o = "undefined" != typeof SVGRect ? "https://www.addevent.com/gfx/icon-calendar-t1.svg" : "https://www.addevent.com/gfx/icon-calendar-t5.png";
                    self.trycss();
                    self.generate();
                }
            },
            /**
             * @return {undefined}
             */
            generate : function() {
                /** @type {NodeList} */
                var elements = document.getElementsByTagName("*");
                /** @type {number} */
                var j = 0;
                for (;j < elements.length;j += 1) {
                    if (self.hasclass(elements[j], "addeventatc")) {
                        (function() {
                            var domId = "addeventatc" + uuid;
                            elements[j].id = domId;
                            /** @type {string} */
                            elements[j].title = "";
                            /** @type {string} */
                            elements[j].style.visibility = "visible";
                            elements[j].setAttribute("aria-haspopup", "true");
                            elements[j].setAttribute("aria-expanded", "false");
                            elements[j].setAttribute("tabindex", "0");
                            if (files_list) {
                                /**
                                 * @return {?}
                                 */
                                elements[j].onclick = function() {
                                    return false;
                                };
                                /**
                                 * @return {undefined}
                                 */
                                elements[j].onmouseover = function() {
                                    clearTimeout(tref);
                                    self.toogle(this, {
                                        type : "mouseover",
                                        id : domId
                                    });
                                };
                                /**
                                 * @return {undefined}
                                 */
                                elements[j].onmouseout = function() {
                                    /** @type {number} */
                                    tref = setTimeout(function() {
                                        self.mouseout(this, {
                                            type : "mouseout",
                                            id : domId
                                        });
                                    }, 100);
                                };
                            } else {
                                /**
                                 * @return {?}
                                 */
                                elements[j].onclick = function() {
                                    return self.toogle(this, {
                                        type : "click",
                                        id : domId
                                    }), false;
                                };
                                /**
                                 * @return {undefined}
                                 */
                                elements[j].onmouseover = function() {
                                };
                                /**
                                 * @return {undefined}
                                 */
                                elements[j].onmouseout = function() {
                                };
                            }
                            /**
                             * @param {Event} e
                             * @return {undefined}
                             */
                            elements[j].onkeydown = function(e) {
                                var first = e.which || e.keyCode;
                                if (!("13" != first && ("32" != first && ("27" != first && ("38" != first && "40" != first))))) {
                                    e.preventDefault();
                                }
                                if (!("13" != first && "32" != first)) {
                                    self.keyboardclick(this, {
                                        type : "click",
                                        id : domId
                                    });
                                    self.toogle(this, {
                                        type : "click",
                                        id : domId,
                                        keynav : true
                                    });
                                }
                                if ("27" == first) {
                                    self.hideandreset();
                                }
                                if ("38" == first) {
                                    self.keyboard(this, {
                                        type : "keyboard",
                                        id : domId,
                                        key : "up"
                                    });
                                }
                                if ("40" == first) {
                                    self.keyboard(this, {
                                        type : "keyboard",
                                        id : domId,
                                        key : "down"
                                    });
                                }
                                /** @type {boolean} */
                                M = true;
                            };
                            /**
                             * @return {undefined}
                             */
                            elements[j].onblur = function() {
                                if (M) {
                                    setTimeout(function() {
                                        self.hideandreset();
                                    }, 300);
                                }
                            };
                            var el = elements[j];
                            if ("none" != elements[j].getAttribute("data-styling") && "inline-buttons" != elements[j].getAttribute("data-render") || (p = false), p) {
                                /** @type {Element} */
                                var elContent = document.createElement("span");
                                /** @type {string} */
                                elContent.className = "addeventatc_icon";
                                el.appendChild(elContent);
                            }
                            uuid++;
                            /** @type {boolean} */
                            u = true;
                            var codeSegments = elements[j].getElementsByTagName("*");
                            /** @type {number} */
                            var i = 0;
                            for (;i < codeSegments.length;i += 1) {
                                if (!self.hasclass(codeSegments[i], "atc_node")) {
                                    if ("" != codeSegments[i].className) {
                                        codeSegments[i].className += " atc_node";
                                    } else {
                                        codeSegments[i].className += "atc_node";
                                    }
                                }
                            }
                            if ("inline-buttons" == elements[j].getAttribute("data-render")) {
                                /**
                                 * @return {undefined}
                                 */
                                elements[j].onclick = function() {
                                };
                                self.toogle(elements[j], {
                                    type : "render",
                                    id : domId
                                });
                                elements[j].setAttribute("aria-expanded", "true");
                                elements[j].removeAttribute("tabindex");
                                /**
                                 * @return {undefined}
                                 */
                                elements[j].onkeydown = function() {
                                };
                                /**
                                 * @return {undefined}
                                 */
                                elements[j].blur = function() {
                                };
                                /** @type {(HTMLElement|null)} */
                                var element = document.getElementById(domId + "-drop");
                                if (element) {
                                    element.setAttribute("aria-hidden", "false");
                                    /** @type {NodeList} */
                                    codeSegments = element.getElementsByTagName("SPAN");
                                    /** @type {number} */
                                    i = 0;
                                    for (;i < codeSegments.length;i += 1) {
                                        codeSegments[i];
                                        /** @type {string} */
                                        codeSegments[i].tabIndex = "0";
                                        /**
                                         * @param {Event} e
                                         * @return {undefined}
                                         */
                                        codeSegments[i].onkeydown = function(e) {
                                            var first = e.which || e.keyCode;
                                            if (!("13" != first && "32" != first)) {
                                                e.target.click();
                                            }
                                        };
                                    }
                                }
                            }
                        })();
                    }
                }
                zIndex = self.topzindex();
                if (p) {
                    self.applycss();
                } else {
                    self.removeelement($("ate_css"));
                    self.removeelement($("ate_tmp_css"));
                    self.helpercss();
                }
                if (u) {
                    if (!v) {
                        /** @type {boolean} */
                        v = true;
                        self.track({
                            typ : "exposure",
                            cal : ""
                        });
                    }
                }
            },
            /**
             * @param {Element} map
             * @param {Object} e
             * @return {?}
             */
            toogle : function(map, e) {
                var id;
                var el;
                var name;
                /** @type {boolean} */
                var i = false;
                /** @type {string} */
                var html = "";
                if (id = map.id, el = $(id)) {
                    name = el.getAttribute("data-direct");
                    var data_intel = el.getAttribute("data-intel");
                    var data_intel_apple = el.getAttribute("data-intel-apple");
                    if ("ios" == self.agent() && ("click" == e.type && ("true" !== data_intel_apple && ("false" !== data_intel && (name = "appleical", el.setAttribute("data-intel-apple", "true"))))), "outlook" == name || ("google" == name || ("yahoo" == name || ("hotmail" == name || ("outlookcom" == name || ("appleical" == name || ("apple" == name || "facebook" == name))))))) {
                        if ("click" == e.type) {
                            self.click({
                                button : id,
                                service : name,
                                id : e.id
                            });
                            if (null != callback) {
                                self.trigger("button_click", {
                                    id : id
                                });
                            }
                        }
                    } else {
                        if ("mouseover" == e.type && (hoverElement != el && (m = false)), "click" == e.type || ("render" == e.type || "mouseover" == e.type && !m)) {
                            if ("mouseover" == e.type) {
                                /** @type {boolean} */
                                m = true;
                                if (null != msg) {
                                    self.trigger("button_mouseover", {
                                        id : id
                                    });
                                }
                            }
                            i = self.getburl({
                                id : id,
                                facebook : true
                            });

                            if ("" == fragment) {
                                /** @type {string} */
                                fragment = "appleical,google,outlook,outlookcom,yahoo,facebook";
                            }
                            /** @type {Array.<string>} */
                            var branchDataJSON = (fragment = (fragment += ",").replace(/ /gi, "")).split(",");
                            /** @type {number} */
                            var conditionIndex = 0;
                            for (;conditionIndex < branchDataJSON.length;conditionIndex += 1) {
                                if (xmlDoc && "ical" == branchDataJSON[conditionIndex] || xmlDoc && "appleical" == branchDataJSON[conditionIndex]) {
                                    html += '<span class="ateappleical" id="' + id + '-appleical" role="menuitem">' + dataText + "</span>";
                                }
                                if (show) {
                                    if ("google" == branchDataJSON[conditionIndex]) {
                                        html += '<span class="ategoogle" id="' + id + '-google" role="menuitem">' + error + "</span>";
                                    }
                                }
                                if (w) {
                                    if ("outlook" == branchDataJSON[conditionIndex]) {
                                        html += '<span class="ateoutlook" id="' + id + '-outlook" role="menuitem">' + text + "</span>";
                                    }
                                }
                                if (disabled && "hotmail" == branchDataJSON[conditionIndex] || disabled && "outlookcom" == branchDataJSON[conditionIndex]) {
                                    html += '<span class="ateoutlookcom" id="' + id + '-outlookcom" role="menuitem">' + result + "</span>";
                                }
                                if (k) {
                                    if ("yahoo" == branchDataJSON[conditionIndex]) {
                                        html += '<span class="ateyahoo" id="' + id + '-yahoo" role="menuitem">' + txt + "</span>";
                                    }
                                }
                                if (i) {
                                    if ("facebook" == branchDataJSON[conditionIndex]) {
                                        if (y) {
                                            if ("facebook" == branchDataJSON[conditionIndex]) {
                                                html += '<span class="atefacebook" id="' + id + '-facebook" role="menuitem">' + textValue + "</span>";
                                            }
                                        }
                                    }
                                }
                            }
                            if (self.getlicense(conflict) || (html += '<em class="copyx"><em class="brx"></em><em class="frs"><a href="https://www.addevent.com" title="" tabindex="-1" id="' + id + '-home">AddEvent.com</a></em></em>'), !$(id + "-drop")) {
                                /** @type {Element} */
                                var node = document.createElement("span");
                                /** @type {string} */
                                node.id = id + "-drop";
                                /** @type {string} */
                                node.className = "addeventatc_dropdown";
                                node.setAttribute("aria-hidden", "true");
                                node.setAttribute("aria-labelledby", id);
                                /** @type {string} */
                                node.innerHTML = html;
                                el.appendChild(node);
                            }
                            if ($(id + "-appleical")) {
                                /**
                                 * @return {undefined}
                                 */
                                $(id + "-appleical").onclick = function() {
                                    self.click({
                                        button : id,
                                        service : "appleical",
                                        id : e.id
                                    });
                                };
                            }
                            if ($(id + "-google")) {
                                /**
                                 * @return {undefined}
                                 */
                                $(id + "-google").onclick = function() {
                                    self.click({
                                        button : id,
                                        service : "google",
                                        id : e.id
                                    });
                                };
                            }
                            if ($(id + "-outlook")) {
                                /**
                                 * @return {undefined}
                                 */
                                $(id + "-outlook").onclick = function() {
                                    self.click({
                                        button : id,
                                        service : "outlook",
                                        id : e.id
                                    });
                                };
                            }
                            if ($(id + "-outlookcom")) {
                                /**
                                 * @return {undefined}
                                 */
                                $(id + "-outlookcom").onclick = function() {
                                    self.click({
                                        button : id,
                                        service : "outlookcom",
                                        id : e.id
                                    });
                                };
                            }
                            if ($(id + "-yahoo")) {
                                /**
                                 * @return {undefined}
                                 */
                                $(id + "-yahoo").onclick = function() {
                                    self.click({
                                        button : id,
                                        service : "yahoo",
                                        id : e.id
                                    });
                                };
                            }
                            if ($(id + "-facebook")) {
                                /**
                                 * @return {undefined}
                                 */
                                $(id + "-facebook").onclick = function() {
                                    self.click({
                                        button : id,
                                        service : "facebook",
                                        id : e.id
                                    });
                                };
                            }
                            if ($(id + "-home")) {
                                /**
                                 * @return {undefined}
                                 */
                                $(id + "-home").onclick = function() {
                                    self.click({
                                        button : id,
                                        service : "home",
                                        id : e.id
                                    });
                                };
                            }
                            self.show(id, e);
                        }
                    }
                    return hoverElement = el, false;
                }
            },
            /**
             * @param {?} opt_attributes
             * @return {undefined}
             */
            click : function(opt_attributes) {
                var elem;
                var a;
                var str;
                /** @type {string} */
                var last = location.origin;
                /** @type {boolean} */
                var newWindow = true;
                if (void 0 === location.origin && (last = location.protocol + "//" + location.host), elem = $(opt_attributes.button)) {
                    if ("home" == opt_attributes.service) {
                        /** @type {string} */
                        str = "https://www.addevent.com";
                    } else {
                        a = self.getburl({
                            id : opt_attributes.button,
                            facebook : false
                        });
                        /** @type {string} */
                        str = "https://www.addevent.com/create/?service=" + opt_attributes.service + a;
                        if (!("outlook" != opt_attributes.service && "appleical" != opt_attributes.service)) {
                            /** @type {boolean} */
                            newWindow = false;
                            if (self.usewebcal()) {
                                /** @type {string} */
                                str = "webcal://www.addevent.com/create/?uwc=on&service=" + opt_attributes.service + a;
                            }
                        }
                        var id = elem.getAttribute("data-id");
                        if (null !== id) {
                            /** @type {string} */
                            str = "https://www.addevent.com/event/?" + id + "+" + opt_attributes.service;
                        }
                    }
                    if (!$("atecllink")) {
                        /** @type {Element} */
                        var node = document.createElement("a");
                        /** @type {string} */
                        node.id = "atecllink";
                        /** @type {string} */
                        node.rel = "external";
                        node.setAttribute("data-role", "none");
                        /** @type {string} */
                        node.innerHTML = "{addeventatc-ghost-link}";
                        /** @type {string} */
                        node.style.display = "none";
                        document.body.appendChild(node);
                    }
                    var el = $("atecllink");
                    if (el.target = newWindow ? "_blank" : "_self", el.href = str, self.eclick("atecllink"), self.track({
                        typ : "click",
                        cal : opt_attributes.service
                    }), null != realTrigger) {
                        self.trigger("button_dropdown_click", {
                            id : opt_attributes.button,
                            service : opt_attributes.service
                        });
                        try {
                            (event || window.event).stopPropagation();
                        } catch (e) {
                        }
                    }
                }
            },
            /**
             * @param {?} dataAndEvents
             * @param {Element} data
             * @return {undefined}
             */
            mouseout : function(dataAndEvents, data) {
                /** @type {boolean} */
                m = false;
                self.hideandreset();
                if (null != fn) {
                    self.trigger("button_mouseout", {
                        id : data.id
                    });
                }
            },
            /**
             * @param {string} id
             * @param {Object} e
             * @return {undefined}
             */
            show : function(id, e) {
                var element = $(id);
                var elm = $(id + "-drop");
                if (element && elm) {
                    if ("block" == self.getstyle(elm, "display")) {
                        self.hideandreset();
                    } else {
                        self.hideandreset(true);
                        /** @type {string} */
                        elm.style.display = "block";
                        /** @type {string} */
                        element.style.outline = "0";
                        element.style.zIndex = zIndex + 1;
                        /** @type {string} */
                        element.className = element.className + " addeventatc-selected";
                        /** @type {string} */
                        element.className = element.className.replace(/\s+/g, " ");
                        element.setAttribute("aria-expanded", "true");
                        elm.setAttribute("aria-hidden", "false");
                        if (e.keynav) {
                            self.keyboard(this, {
                                type : "keyboard",
                                id : id,
                                key : "down"
                            });
                        }
                        var middle = element.getAttribute("data-dropdown-x");
                        var dir = element.getAttribute("data-dropdown-y");
                        /** @type {string} */
                        var direction = "auto";
                        /** @type {string} */
                        var right = "auto";
                        if (null != middle) {
                            right = middle;
                        }
                        if (null != dir) {
                            direction = dir;
                        }
                        /** @type {string} */
                        elm.style.left = "0px";
                        /** @type {string} */
                        elm.style.top = "0px";
                        /** @type {string} */
                        elm.style.display = "block";
                        /** @type {number} */
                        var currentPosition = parseInt(element.offsetHeight);
                        /** @type {number} */
                        var j = parseInt(element.offsetWidth);
                        /** @type {number} */
                        var width = parseInt(elm.offsetHeight);
                        /** @type {number} */
                        var length = parseInt(elm.offsetWidth);
                        var coords = self.viewport();
                        /** @type {number} */
                        var low = parseInt(coords.w);
                        /** @type {number} */
                        var a = parseInt(coords.h);
                        /** @type {number} */
                        var high = parseInt(coords.x);
                        /** @type {number} */
                        var b = parseInt(coords.y);
                        var cs = self.elementposition(elm);
                        /** @type {number} */
                        var line = parseInt(cs.x);
                        /** @type {number} */
                        var px = parseInt(cs.y) + width;
                        /** @type {number} */
                        var exponent = a + b;
                        /** @type {number} */
                        var l = line + length;
                        /** @type {number} */
                        var i = low + high;
                        /** @type {number} */
                        var w = 0;
                        /** @type {number} */
                        var position = 0;
                        if ("down" == direction && "left" == right) {
                            /** @type {string} */
                            w = "0px";
                            /** @type {string} */
                            position = currentPosition + "px";
                        } else {
                            if ("up" == direction && "left" == right) {
                                /** @type {string} */
                                w = "0px";
                                /** @type {string} */
                                position = -width + "px";
                            } else {
                                if ("down" == direction && "right" == right) {
                                    /** @type {string} */
                                    w = -(length - j) + "px";
                                    /** @type {string} */
                                    position = currentPosition + "px";
                                } else {
                                    if ("up" == direction && "right" == right) {
                                        /** @type {string} */
                                        w = -(length - j) + "px";
                                        /** @type {string} */
                                        position = -width + "px";
                                    } else {
                                        if ("auto" == direction && "left" == right) {
                                            /** @type {string} */
                                            w = "0px";
                                            /** @type {string} */
                                            position = px > exponent ? -width + "px" : currentPosition + "px";
                                        } else {
                                            if ("auto" == direction && "right" == right) {
                                                /** @type {string} */
                                                w = -(length - j) + "px";
                                                /** @type {string} */
                                                position = px > exponent ? -width + "px" : currentPosition + "px";
                                            } else {
                                                /** @type {string} */
                                                position = px > exponent ? -width + "px" : currentPosition + "px";
                                                /** @type {string} */
                                                w = l > i ? -(length - j) + "px" : "0px";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        /** @type {number} */
                        elm.style.left = w;
                        /** @type {number} */
                        elm.style.top = position;
                        if ("click" == e.type) {
                            if (null != callback) {
                                self.trigger("button_click", {
                                    id : id
                                });
                            }
                        }
                        if (null != _fn) {
                            self.trigger("button_dropdown_show", {
                                id : id
                            });
                        }
                    }
                }
            },
            /**
             * @param {Object} val
             * @return {undefined}
             */
            hide : function(val) {
                /** @type {boolean} */
                var t = false;
                if ("string" == typeof val && "" !== val || val instanceof String && "" !== val) {
                    if (val.indexOf("addeventatc") > -1 || val.indexOf("atc_node") > -1) {
                        /** @type {boolean} */
                        t = true;
                    }
                }
                if (!t) {
                    self.hideandreset();
                }
            },
            /**
             * @param {boolean} dataAndEvents
             * @return {undefined}
             */
            hideandreset : function(dataAndEvents) {
                /** @type {string} */
                var it = "";
                /** @type {NodeList} */
                var els = document.getElementsByTagName("*");
                /** @type {number} */
                var j = 0;
                for (;j < els.length;j += 1) {
                    if (self.hasclass(els[j], "addeventatc")) {
                        els[j].className = els[j].className.replace(/addeventatc-selected/gi, "");
                        els[j].className = els[j].className.replace(/\s+$/, "");
                        /** @type {string} */
                        els[j].style.zIndex = "auto";
                        /** @type {string} */
                        els[j].style.outline = "";
                        var ul = $(els[j].id + "-drop");
                        if (ul) {
                            var result = self.getstyle(ul, "display");
                            if ("block" == result) {
                                it = els[j].id;
                            }
                            /** @type {string} */
                            ul.style.display = "none";
                            if ("block" !== (result = self.getstyle(ul, "display"))) {
                                els[j].setAttribute("aria-expanded", "false");
                                ul.setAttribute("aria-hidden", "true");
                            }
                            var codeSegments = ul.getElementsByTagName("SPAN");
                            /** @type {number} */
                            var i = 0;
                            for (;i < codeSegments.length;i += 1) {
                                /** @type {RegExp} */
                                var rclass = new RegExp("(\\s|^)drop_markup(\\s|$)");
                                codeSegments[i].className = codeSegments[i].className.replace(rclass, " ");
                                codeSegments[i].className = codeSegments[i].className.replace(/\s+$/, "");
                            }
                        }
                    }
                }
                if (!dataAndEvents) {
                    if (null != requestFrame) {
                        self.trigger("button_dropdown_hide", {
                            id : it
                        });
                    }
                }
            },
            /**
             * @param {Object} $scope
             * @return {?}
             */
            getburl : function($scope) {
                var tb = $($scope.id);
                /** @type {string} */
                var options = "";
                /** @type {boolean} */
                var o = false;
                if (tb) {
                    var codeSegments = tb.getElementsByTagName("*");
                    /** @type {number} */
                    var i = 0;
                    for (;i < codeSegments.length;i += 1) {
                        if (self.hasclass(codeSegments[i], "_start") || self.hasclass(codeSegments[i], "start")) {
                            options += "&dstart=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_end") || self.hasclass(codeSegments[i], "end")) {
                            options += "&dend=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_zonecode") || self.hasclass(codeSegments[i], "zonecode")) {
                            options += "&dzone=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_timezone") || self.hasclass(codeSegments[i], "timezone")) {
                            options += "&dtime=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_summary") || (self.hasclass(codeSegments[i], "summary") || self.hasclass(codeSegments[i], "title"))) {
                            options += "&dsum=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_description") || self.hasclass(codeSegments[i], "description")) {
                            options += "&ddesc=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_location") || self.hasclass(codeSegments[i], "location")) {
                            options += "&dloca=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_organizer") || self.hasclass(codeSegments[i], "organizer")) {
                            options += "&dorga=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_organizer_email") || self.hasclass(codeSegments[i], "organizer_email")) {
                            options += "&dorgaem=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_attendees") || self.hasclass(codeSegments[i], "attendees")) {
                            options += "&datte=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_all_day_event") || self.hasclass(codeSegments[i], "all_day_event")) {
                            options += "&dallday=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_date_format") || self.hasclass(codeSegments[i], "date_format")) {
                            options += "&dateformat=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_alarm_reminder") || self.hasclass(codeSegments[i], "alarm_reminder")) {
                            options += "&alarm=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_recurring") || self.hasclass(codeSegments[i], "recurring")) {
                            options += "&drule=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_facebook_event") || self.hasclass(codeSegments[i], "facebook_event")) {
                            options += "&fbevent=" + encodeURIComponent(codeSegments[i].innerHTML);
                            /** @type {boolean} */
                            o = true;
                        }
                        if (self.hasclass(codeSegments[i], "_client") || self.hasclass(codeSegments[i], "client")) {
                            options += "&client=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_calname") || self.hasclass(codeSegments[i], "calname")) {
                            options += "&calname=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_uid") || self.hasclass(codeSegments[i], "uid")) {
                            options += "&uid=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_status") || self.hasclass(codeSegments[i], "status")) {
                            options += "&status=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                        if (self.hasclass(codeSegments[i], "_method") || self.hasclass(codeSegments[i], "method")) {
                            options += "&method=" + encodeURIComponent(codeSegments[i].innerHTML);
                        }
                    }
                }
                return $scope.facebook && (options = o), options;
            },
            /**
             * @return {undefined}
             */
            trycss : function() {
                if (!$("ate_tmp_css")) {
                    try {
                        /** @type {string} */
                        var css = "";
                        /** @type {string} */
                        css = ".addeventatc {visibility:hidden;}";
                        css += ".addeventatc .data {display:none!important;}";
                        css += ".addeventatc .start, .addeventatc .end, .addeventatc .timezone, .addeventatc .title, .addeventatc .description, .addeventatc .location, .addeventatc .organizer, .addeventatc .organizer_email, .addeventatc .facebook_event, .addeventatc .all_day_event, .addeventatc .date_format, .addeventatc .alarm_reminder, .addeventatc .recurring, .addeventatc .attendees, .addeventatc .client, .addeventatc .calname, .addeventatc .uid, .addeventatc .status, .addeventatc .method {display:none!important;}";
                        if (p) {
                            css += ".addeventatc {background-image:url(https://www.addevent.com/gfx/icon-calendar-t5.png), url(https://www.addevent.com/gfx/icon-calendar-t1.svg), url(https://www.addevent.com/gfx/icon-apple-t1.svg), url(https://www.addevent.com/gfx/icon-facebook-t1.svg), url(https://www.addevent.com/gfx/icon-google-t1.svg), url(https://www.addevent.com/gfx/icon-outlook-t1.svg), url(https://www.addevent.com/gfx/icon-yahoo-t1.svg);background-position:-9999px -9999px;background-repeat:no-repeat;}"
                            ;
                        }
                        /** @type {Element} */
                        var stylesheet = document.createElement("style");
                        /** @type {string} */
                        stylesheet.type = "text/css";
                        /** @type {string} */
                        stylesheet.id = "ate_tmp_css";
                        if (stylesheet.styleSheet) {
                            /** @type {string} */
                            stylesheet.styleSheet.cssText = css;
                        } else {
                            stylesheet.appendChild(document.createTextNode(css));
                        }
                        document.getElementsByTagName("head")[0].appendChild(stylesheet);
                    } catch (e) {
                    }
                    self.track({
                        typ : "jsinit",
                        cal : ""
                    });
                }
            },
            /**
             * @return {undefined}
             */
            applycss : function() {
                if (!$("ate_css")) {
                    /** @type {string} */
                    var css = "";
                    css += '.addeventatc {display:inline-block;*display:inline;zoom:1;position:relative;z-index:1;font-family:Roboto,"Helvetica Neue",Helvetica,Optima,Segoe,"Segoe UI",Candara,Calibri,Arial,sans-serif;color:#000!important;font-weight:300;line-height:100%!important;background-color:#fff;border:1px solid;border-color:#e5e6e9 #dfe0e4 #d0d1d5;font-size:15px;text-decoration:none;padding:13px 12px 12px 43px;-webkit-border-radius:3px;border-radius:3px;cursor:pointer;-webkit-font-smoothing:antialiased!important;text-shadow:1px 1px 1px rgba(0,0,0,0.004);-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-tap-highlight-color:rgba(0,0,0,0);background-image:url(https://www.addevent.com/gfx/icon-calendar-t5.png), url(https://www.addevent.com/gfx/icon-calendar-t1.svg), url(https://www.addevent.com/gfx/icon-apple-t1.svg), url(https://www.addevent.com/gfx/icon-facebook-t1.svg), url(https://www.addevent.com/gfx/icon-google-t1.svg), url(https://www.addevent.com/gfx/icon-outlook-t1.svg), url(https://www.addevent.com/gfx/icon-yahoo-t1.svg);background-position:-9999px -9999px;background-repeat:no-repeat;}';
                    css += ".addeventatc:hover {border:1px solid #aab9d4;color:#000;font-size:15px;text-decoration:none;}";
                    css += ".addeventatc:focus {outline:none;border:1px solid #aab9d4;}";
                    css += ".addeventatc:active {top:1px;}";
                    css += ".addeventatc-selected {background-color:#f9f9f9;}";
                    css += ".addeventatc .addeventatc_icon {width:18px;height:18px;position:absolute;z-index:1;left:12px;top:10px;background:url(" + o + ") no-repeat;background-size:18px 18px;}";
                    css += ".addeventatc .start, .addeventatc .end, .addeventatc .timezone, .addeventatc .title, .addeventatc .description, .addeventatc .location, .addeventatc .organizer, .addeventatc .organizer_email, .addeventatc .facebook_event, .addeventatc .all_day_event, .addeventatc .date_format, .addeventatc .alarm_reminder, .addeventatc .recurring, .addeventatc .attendees, .addeventatc .client, .addeventatc .calname, .addeventatc .uid, .addeventatc .status, .addeventatc .method {display:none!important;}";
                    css += ".addeventatc .data {display:none!important;}";
                    if (self.getlicense(conflict)) {
                        css += ".addeventatc_dropdown {width:200px;position:absolute;z-index:99999;padding:6px 0px 6px 0px;background:#fff;text-align:left;display:none;margin-top:-2px;margin-left:-1px;border-top:1px solid #c8c8c8;border-right:1px solid #bebebe;border-bottom:1px solid #a8a8a8;border-left:1px solid #bebebe;-moz-border-radius:2px;-webkit-border-radius:2px;-webkit-box-shadow:1px 3px 6px rgba(0,0,0,0.15);-moz-box-shadow:1px 3px 6px rgba(0,0,0,0.15);box-shadow:1px 3px 6px rgba(0,0,0,0.15);}";
                    } else {
                        css += ".addeventatc_dropdown {width:200px;position:absolute;z-index:99999;padding:6px 0px 0px 0px;background:#fff;text-align:left;display:none;margin-top:-2px;margin-left:-1px;border-top:1px solid #c8c8c8;border-right:1px solid #bebebe;border-bottom:1px solid #a8a8a8;border-left:1px solid #bebebe;-moz-border-radius:2px;-webkit-border-radius:2px;-webkit-box-shadow:1px 3px 6px rgba(0,0,0,0.15);-moz-box-shadow:1px 3px 6px rgba(0,0,0,0.15);box-shadow:1px 3px 6px rgba(0,0,0,0.15);}";
                    }
                    css += ".addeventatc_dropdown span {display:block;line-height:100%;background:#fff;text-decoration:none;font-size:14px;color:#333;padding:9px 10px 9px 40px;}";
                    css += ".addeventatc_dropdown span:hover {background-color:#f4f4f4;color:#000;text-decoration:none;font-size:14px;}";
                    css += ".addeventatc_dropdown .drop_markup {background-color:#f4f4f4;color:#000;text-decoration:none;font-size:14px;}";
                    css += ".addeventatc_dropdown .copyx {height:21px;display:block;position:relative;cursor:default;}";
                    css += ".addeventatc_dropdown .brx {height:1px;overflow:hidden;background:#e0e0e0;position:absolute;z-index:100;left:10px;right:10px;top:9px;}";
                    css += ".addeventatc_dropdown .frs {position:absolute;top:5px;cursor:pointer;right:10px;font-style:normal!important;font-weight:normal!important;text-align:right;z-index:101;line-height:9px!important;background:#fff;text-decoration:none;font-size:9px!important;color:#cacaca!important;}";
                    css += ".addeventatc_dropdown .frs a {margin:0!important;padding:0!important;font-style:normal!important;font-weight:normal!important;line-height:9px!important;background-color:#fff!important;text-decoration:none;font-size:9px!important;color:#cacaca!important;padding-left:10px!important;display:inline-block;}";
                    css += ".addeventatc_dropdown .frs a:hover {color:#999!important;}";
                    css += ".addeventatc_dropdown .ateappleical {background-image:url(https://www.addevent.com/gfx/icon-apple-t1.svg);background-repeat:no-repeat;background-position:13px 50%;background-size:14px auto;}";
                    css += ".addeventatc_dropdown .ateoutlook {background-image:url(https://www.addevent.com/gfx/icon-outlook-t1.svg);background-repeat:no-repeat;background-position:12px 50%;background-size:16px auto;}";
                    css += ".addeventatc_dropdown .ateoutlookcom {background-image:url(https://www.addevent.com/gfx/icon-outlook-t1.svg);background-repeat:no-repeat;background-position:12px 50%;background-size:16px auto;}";
                    css += ".addeventatc_dropdown .ategoogle {background-image:url(https://www.addevent.com/gfx/icon-google-t1.svg);background-repeat:no-repeat;background-position:12px 50%;background-size:16px auto;}";
                    css += ".addeventatc_dropdown .ateyahoo {background-image:url(https://www.addevent.com/gfx/icon-yahoo-t1.svg);background-repeat:no-repeat;background-position:12px 50%;background-size:16px auto;}";
                    css += ".addeventatc_dropdown .atefacebook {background-image:url(https://www.addevent.com/gfx/icon-facebook-t1.svg);background-repeat:no-repeat;background-position:12px 50%;background-size:16px auto;}";
                    css += ".addeventatc_dropdown em {font-size:12px!important;color:#999!important;}";
                    /** @type {Element} */
                    var stylesheet = document.createElement("style");
                    /** @type {string} */
                    stylesheet.type = "text/css";
                    /** @type {string} */
                    stylesheet.id = "ate_css";
                    if (stylesheet.styleSheet) {
                        /** @type {string} */
                        stylesheet.styleSheet.cssText = css;
                    } else {
                        stylesheet.appendChild(document.createTextNode(css));
                    }
                    document.getElementsByTagName("head")[0].appendChild(stylesheet);
                    self.removeelement($("ate_tmp_css"));
                }
            },
            /**
             * @return {undefined}
             */
            helpercss : function() {
                if (!$("ate_helper_css")) {
                    /** @type {string} */
                    var css = "";
                    css += ".addeventatc_dropdown .drop_markup {background-color:#f4f4f4;}";
                    css += ".addeventatc_dropdown .frs a {margin:0!important;padding:0!important;font-style:normal!important;font-weight:normal!important;line-height:110%!important;background-color:#fff!important;text-decoration:none;font-size:9px!important;color:#cacaca!important;display:inline-block;}";
                    css += ".addeventatc_dropdown .frs a:hover {color:#999!important;}";
                    css += ".addeventatc .start, .addeventatc .end, .addeventatc .timezone, .addeventatc .title, .addeventatc .description, .addeventatc .location, .addeventatc .organizer, .addeventatc .organizer_email, .addeventatc .facebook_event, .addeventatc .all_day_event, .addeventatc .date_format, .addeventatc .alarm_reminder, .addeventatc .recurring, .addeventatc .attendees, .addeventatc .client, .addeventatc .calname, .addeventatc .uid, .addeventatc .status, .addeventatc .method {display:none!important;}";
                    /** @type {Element} */
                    var stylesheet = document.createElement("style");
                    /** @type {string} */
                    stylesheet.type = "text/css";
                    /** @type {string} */
                    stylesheet.id = "ate_helper_css";
                    if (stylesheet.styleSheet) {
                        /** @type {string} */
                        stylesheet.styleSheet.cssText = css;
                    } else {
                        stylesheet.appendChild(document.createTextNode(css));
                    }
                    document.getElementsByTagName("head")[0].appendChild(stylesheet);
                }
            },
            /**
             * @param {?} dataAndEvents
             * @return {?}
             */
            removeelement : function(dataAndEvents) {
                try {
                    return!!(hdx = dataAndEvents) && hdx.parentNode.removeChild(hdx);
                } catch (e) {
                }
            },
            /**
             * @return {?}
             */
            topzindex : function() {
                /** @type {number} */
                var mode = 1;
                /** @type {NodeList} */
                var codeSegments = document.getElementsByTagName("*");
                /** @type {number} */
                var i = 0;
                for (;i < codeSegments.length;i += 1) {
                    if (self.hasclass(codeSegments[i], "addeventatc") || self.hasclass(codeSegments[i], "addeventstc")) {
                        var value = self.getstyle(codeSegments[i], "z-index");
                        if (!isNaN(parseFloat(value))) {
                            if (isFinite(value)) {
                                if ((value = parseInt(value)) > mode) {
                                    /** @type {number} */
                                    mode = value;
                                }
                            }
                        }
                    }
                }
                return mode;
            },
            /**
             * @return {?}
             */
            viewport : function() {
                /** @type {number} */
                var myWidth = 0;
                /** @type {number} */
                var myHeight = 0;
                /** @type {number} */
                var posY = 0;
                /** @type {number} */
                var scrollX = 0;
                return "number" == typeof window.innerWidth ? (myWidth = window.innerWidth, myHeight = window.innerHeight) : document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight) ? (myWidth = document.documentElement.clientWidth, myHeight = document.documentElement.clientHeight) : document.body && ((document.body.clientWidth || document.body.clientHeight) && (myWidth = document.body.clientWidth, myHeight = document.body.clientHeight)), document.all ?
                    (scrollX = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft, posY = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) : (scrollX = window.pageXOffset, posY = window.pageYOffset), {
                    w : myWidth,
                    h : myHeight,
                    x : scrollX,
                    y : posY
                };
            },
            /**
             * @param {Element} el
             * @return {?}
             */
            elementposition : function(el) {
                /** @type {number} */
                var xPos = 0;
                /** @type {number} */
                var yPos = 0;
                if (el.offsetParent) {
                    xPos = el.offsetLeft;
                    yPos = el.offsetTop;
                    for (;el = el.offsetParent;) {
                        xPos += el.offsetLeft;
                        yPos += el.offsetTop;
                    }
                }
                return{
                    x : xPos,
                    y : yPos
                };
            },
            /**
             * @param {Object} element
             * @param {string} style
             * @return {?}
             */
            getstyle : function(element, style) {
                var match;
                /** @type {Object} */
                var el = element;
                return el.currentStyle ? match = el.currentStyle[style] : window.getComputedStyle && (match = document.defaultView.getComputedStyle(el, null).getPropertyValue(style)), match;
            },
            /**
             * @param {string} eventType
             * @return {?}
             */
            getlicense : function(eventType) {
                /** @type {string} */
                var origin = location.origin;
                /** @type {boolean} */
                var a = false;
                if (void 0 === location.origin && (origin = location.protocol + "//" + location.host), eventType) {
                    var b = eventType.substring(0, 1);
                    var _ = eventType.substring(9, 10);
                    var name = eventType.substring(17, 18);
                    if ("a" == b) {
                        if ("z" == _) {
                            if ("m" == name) {
                                /** @type {boolean} */
                                a = true;
                            }
                        }
                    }
                }
                return(-1 == origin.indexOf("addevent.com") && "aao8iuet5zp9iqw5sm9z" == eventType || (-1 == origin.indexOf("addevent.to") && "aao8iuet5zp9iqw5sm9z" == eventType || -1 == origin.indexOf("addevent.com") && "aao8iuet5zp9iqw5sm9z" == eventType)) && (a = true), a;
            },
            /**
             * @return {undefined}
             */
            refresh : function() {
                /** @type {NodeList} */
                var items = document.getElementsByTagName("*");
                /** @type {Array} */
                var c = [];
                /** @type {number} */
                var i = 0;
                for (;i < items.length;i += 1) {
                    if (self.hasclass(items[i], "addeventatc")) {
                        items[i].className = items[i].className.replace(/addeventatc-selected/gi, "");
                        /** @type {string} */
                        items[i].id = "";
                        var r = items[i].getElementsByTagName("*");
                        /** @type {number} */
                        var o = 0;
                        for (;o < r.length;o += 1) {
                            if (self.hasclass(r[o], "addeventatc_icon") || self.hasclass(r[o], "addeventatc_dropdown")) {
                                c.push(r[o]);
                            }
                        }
                    }
                }
                /** @type {number} */
                var n = 0;
                for (;n < c.length;n += 1) {
                    self.removeelement(c[n]);
                }
                self.removeelement($("ate_css"));
                /** @type {number} */
                uuid = 1;
                /** @type {boolean} */
                v = false;
                self.generate();
            },
            /**
             * @param {Element} elem
             * @param {string} value
             * @return {?}
             */
            hasclass : function(elem, value) {
                return(new RegExp("(\\s|^)" + value + "(\\s|$)")).test(elem.className);
            },
            /**
             * @param {string} id
             * @return {undefined}
             */
            eclick : function(id) {
                /** @type {(HTMLElement|null)} */
                var button = document.getElementById(id);
                if (button.click) {
                    button.click();
                } else {
                    if (document.createEvent) {
                        /** @type {(Event|null)} */
                        var click = document.createEvent("MouseEvents");
                        click.initEvent("click", true, true);
                        button.dispatchEvent(click);
                    }
                }
            },
            /**
             * @param {?} opt_attributes
             * @return {undefined}
             */
            track : function(opt_attributes) {
                new Image;
                (new Date).getTime();
                encodeURIComponent(window.location.origin);
            },
            /**
             * @return {?}
             */
            getguid : function() {
                /** @type {string} */
                var nameEQ = "addevent_track_cookie=";
                /** @type {string} */
                var fragment = "";
                /** @type {Array.<string>} */
                var ca = document.cookie.split(";");
                /** @type {number} */
                var i = 0;
                for (;i < ca.length;i++) {
                    /** @type {string} */
                    var c = ca[i];
                    for (;" " == c.charAt(0);) {
                        /** @type {string} */
                        c = c.substring(1, c.length);
                    }
                    if (0 == c.indexOf(nameEQ)) {
                        /** @type {string} */
                        fragment = c.substring(nameEQ.length, c.length);
                    }
                }
                if ("" == fragment) {
                    var first = (self.s4() + self.s4() + "-" + self.s4() + "-4" + self.s4().substr(0, 3) + "-" + self.s4() + "-" + self.s4() + self.s4() + self.s4()).toLowerCase();
                    /** @type {Date} */
                    var expires = new Date;
                    expires.setTime(expires.getTime() + 31536E6);
                    /** @type {string} */
                    var c_value = "expires=" + expires.toUTCString();
                    /** @type {string} */
                    document.cookie = "addevent_track_cookie=" + first + "; " + c_value;
                    fragment = first;
                }
                return fragment;
            },
            /**
             * @return {?}
             */
            s4 : function() {
                return(65536 * (1 + Math.random()) | 0).toString(16).substring(1);
            },
            /**
             * @param {Object} e
             * @return {undefined}
             */
            documentclick : function(e) {
                e = e || window.event;
                e = e.target || e.srcElement;
                if (ate_touch_capable) {
                    clearTimeout(going);
                    /** @type {number} */
                    going = setTimeout(function() {
                        self.hide(e.className);
                    }, 500);
                } else {
                    self.hide(e.className);
                }
            },
            /**
             * @param {string} extra
             * @param {?} opt_attributes
             * @return {undefined}
             */
            trigger : function(extra, opt_attributes) {
                if ("button_click" == extra) {
                    try {
                        callback(opt_attributes);
                    } catch (e) {
                    }
                }
                if ("button_mouseover" == extra) {
                    try {
                        msg(opt_attributes);
                    } catch (e) {
                    }
                }
                if ("button_mouseout" == extra) {
                    try {
                        fn(opt_attributes);
                    } catch (e) {
                    }
                }
                if ("button_dropdown_show" == extra) {
                    try {
                        _fn(opt_attributes);
                    } catch (e) {
                    }
                }
                if ("button_dropdown_hide" == extra) {
                    try {
                        requestFrame(opt_attributes);
                    } catch (e) {
                    }
                }
                if ("button_dropdown_click" == extra) {
                    try {
                        realTrigger(opt_attributes);
                    } catch (e) {
                    }
                }
            },
            /**
             * @param {string} tmpl
             * @param {Object} type
             * @return {undefined}
             */
            register : function(tmpl, type) {
                if ("button-click" == tmpl) {
                    /** @type {Object} */
                    callback = type;
                }
                if ("button-mouseover" == tmpl) {
                    /** @type {Object} */
                    msg = type;
                }
                if ("button-mouseout" == tmpl) {
                    /** @type {Object} */
                    fn = type;
                }
                if ("button-dropdown-show" == tmpl) {
                    /** @type {Object} */
                    _fn = type;
                }
                if ("button-dropdown-hide" == tmpl) {
                    /** @type {Object} */
                    requestFrame = type;
                }
                if ("button-dropdown-click" == tmpl) {
                    /** @type {Object} */
                    realTrigger = type;
                }
            },
            /**
             * @param {Object} $scope
             * @return {undefined}
             */
            settings : function($scope) {
                if (void 0 != $scope.license) {
                    conflict = $scope.license;
                }
                if (void 0 != $scope.css) {
                    if ($scope.css) {
                        /** @type {boolean} */
                        p = true;
                    } else {
                        /** @type {boolean} */
                        p = false;
                        self.removeelement($("ate_css"));
                    }
                }
                if (void 0 != $scope.mouse) {
                    files_list = $scope.mouse;
                }
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    /** @type {boolean} */
                    files_list = false;
                }
                if (void 0 != $scope.outlook) {
                    if (void 0 != $scope.outlook.show) {
                        w = $scope.outlook.show;
                    }
                }
                if (void 0 != $scope.google) {
                    if (void 0 != $scope.google.show) {
                        show = $scope.google.show;
                    }
                }
                if (void 0 != $scope.yahoo) {
                    if (void 0 != $scope.yahoo.show) {
                        k = $scope.yahoo.show;
                    }
                }
                if (void 0 != $scope.hotmail) {
                    if (void 0 != $scope.hotmail.show) {
                        disabled = $scope.hotmail.show;
                    }
                }
                if (void 0 != $scope.outlookcom) {
                    if (void 0 != $scope.outlookcom.show) {
                        disabled = $scope.outlookcom.show;
                    }
                }
                if (void 0 != $scope.ical) {
                    if (void 0 != $scope.ical.show) {
                        xmlDoc = $scope.ical.show;
                    }
                }
                if (void 0 != $scope.appleical) {
                    if (void 0 != $scope.appleical.show) {
                        xmlDoc = $scope.appleical.show;
                    }
                }
                if (void 0 != $scope.facebook) {
                    if (void 0 != $scope.facebook.show) {
                        y = $scope.facebook.show;
                    }
                }
                if (void 0 != $scope.outlook) {
                    if (void 0 != $scope.outlook.text) {
                        text = $scope.outlook.text;
                    }
                }
                if (void 0 != $scope.google) {
                    if (void 0 != $scope.google.text) {
                        error = $scope.google.text;
                    }
                }
                if (void 0 != $scope.yahoo) {
                    if (void 0 != $scope.yahoo.text) {
                        txt = $scope.yahoo.text;
                    }
                }
                if (void 0 != $scope.hotmail) {
                    if (void 0 != $scope.hotmail.text) {
                        result = $scope.hotmail.text;
                    }
                }
                if (void 0 != $scope.outlookcom) {
                    if (void 0 != $scope.outlookcom.text) {
                        result = $scope.outlookcom.text;
                    }
                }
                if (void 0 != $scope.ical) {
                    if (void 0 != $scope.ical.text) {
                        dataText = $scope.ical.text;
                    }
                }
                if (void 0 != $scope.appleical) {
                    if (void 0 != $scope.appleical.text) {
                        dataText = $scope.appleical.text;
                    }
                }
                if (void 0 != $scope.facebook) {
                    if (void 0 != $scope.facebook.text) {
                        textValue = $scope.facebook.text;
                    }
                }
                if (void 0 != $scope.dropdown) {
                    if (void 0 != $scope.dropdown.order) {
                        fragment = $scope.dropdown.order;
                    }
                }
            },
            /**
             * @param {?} dataAndEvents
             * @param {Object} opt_attributes
             * @return {undefined}
             */
            keyboard : function(dataAndEvents, opt_attributes) {
                /** @type {(HTMLElement|null)} */
                var wrapper = document.getElementById(opt_attributes.id + "-drop");
                if (wrapper && "block" == self.getstyle(wrapper, "display")) {
                    /** @type {NodeList} */
                    var filters = wrapper.getElementsByTagName("SPAN");
                    /** @type {null} */
                    var filter = null;
                    /** @type {number} */
                    var key = 0;
                    /** @type {number} */
                    var id = 0;
                    /** @type {number} */
                    var i = 0;
                    for (;i < filters.length;i += 1) {
                        key++;
                        if (self.hasclass(filters[i], "drop_markup")) {
                            filter = filters[i];
                            /** @type {number} */
                            id = key;
                        }
                    }
                    if (null === filter) {
                        /** @type {number} */
                        id = 1;
                    } else {
                        if ("down" == opt_attributes.key) {
                            if (id >= key) {
                                /** @type {number} */
                                id = 1;
                            } else {
                                id++;
                            }
                        } else {
                            if (1 == id) {
                                /** @type {number} */
                                id = key;
                            } else {
                                id--;
                            }
                        }
                    }
                    /** @type {number} */
                    key = 0;
                    /** @type {number} */
                    i = 0;
                    for (;i < filters.length;i += 1) {
                        if (++key == id) {
                            filters[i].className += " drop_markup";
                        } else {
                            /** @type {RegExp} */
                            var rclass = new RegExp("(\\s|^)drop_markup(\\s|$)");
                            filters[i].className = filters[i].className.replace(rclass, " ");
                            filters[i].className = filters[i].className.replace(/\s+$/, "");
                        }
                    }
                }
            },
            /**
             * @param {?} dataAndEvents
             * @param {Element} ignores
             * @return {undefined}
             */
            keyboardclick : function(dataAndEvents, ignores) {
                /** @type {(HTMLElement|null)} */
                var wrapper = document.getElementById(ignores.id + "-drop");
                if (wrapper) {
                    /** @type {NodeList} */
                    var filters = wrapper.getElementsByTagName("SPAN");
                    /** @type {null} */
                    var f = null;
                    /** @type {number} */
                    var i = 0;
                    for (;i < filters.length;i += 1) {
                        if (self.hasclass(filters[i], "drop_markup")) {
                            f = filters[i];
                        }
                    }
                    if (null !== f) {
                        f.click();
                        /** @type {number} */
                        i = 0;
                        for (;i < filters.length;i += 1) {
                            /** @type {RegExp} */
                            var rclass = new RegExp("(\\s|^)drop_markup(\\s|$)");
                            filters[i].className = filters[i].className.replace(rclass, " ");
                            filters[i].className = filters[i].className.replace(/\s+$/, "");
                        }
                    }
                }
            },
            /**
             * @return {?}
             */
            usewebcal : function() {
                /** @type {boolean} */
                var e = false;
                /** @type {string} */
                var userAgent = window.navigator.userAgent.toLowerCase();
                return/iphone|ipod|ipad/.test(userAgent) && (e = !!userAgent.match("crios") || !userAgent.match("safari")), e;
            },
            /**
             * @return {?}
             */
            agent : function() {
                var nType = navigator.userAgent || (navigator.vendor || window.opera);
                return/windows phone/i.test(nType) ? "windows_phone" : /android/i.test(nType) ? "android" : /iPad|iPhone|iPod/.test(nType) && !window.MSStream ? "ios" : "unknown";
            }
        };
    }();
    !function(timeoutKey, win) {
        /**
         * @return {undefined}
         */
        function loaded() {
            if (!d) {
                /** @type {boolean} */
                d = true;
                /** @type {number} */
                var i = 0;
                for (;i < fns.length;i++) {
                    fns[i].fn.call(window, fns[i].ctx);
                }
                /** @type {Array} */
                fns = [];
            }
        }
        /**
         * @return {undefined}
         */
        function handler() {
            if ("complete" === document.readyState) {
                loaded();
            }
        }
        /** @type {string} */
        timeoutKey = timeoutKey || "docReady";
        /** @type {Array} */
        var fns = [];
        /** @type {boolean} */
        var d = false;
        /** @type {boolean} */
        var c = false;
        /**
         * @param {Function} fn
         * @param {Object} context
         * @return {undefined}
         */
        (win = win || window)[timeoutKey] = function(fn, context) {
            if ("function" != typeof fn) {
                throw new TypeError("callback for docReady(fn) must be a function");
            }
            if (d) {
                setTimeout(function() {
                    fn(context);
                }, 1);
            } else {
                fns.push({
                    /** @type {Function} */
                    fn : fn,
                    ctx : context
                });
                if ("complete" === document.readyState || !document.attachEvent && "interactive" === document.readyState) {
                    setTimeout(loaded, 1);
                } else {
                    if (!c) {
                        if (document.addEventListener) {
                            document.addEventListener("DOMContentLoaded", loaded, false);
                            window.addEventListener("load", loaded, false);
                        } else {
                            document.attachEvent("onreadystatechange", handler);
                            window.attachEvent("onload", loaded);
                        }
                        /** @type {boolean} */
                        c = true;
                    }
                }
            }
        };
    }("addeventReady", window);
    var ate_touch_capable = "ontouchstart" in window || (window.DocumentTouch && document instanceof window.DocumentTouch || (navigator.maxTouchPoints > 0 || window.navigator.msMaxTouchPoints > 0));
    if (window.addEventListener) {
        document.addEventListener("click", self.documentclick, false);
        if (ate_touch_capable) {
            document.addEventListener("touchend", self.documentclick, false);
        }
    } else {
        if (window.attachEvent) {
            document.attachEvent("onclick", self.documentclick);
            if (ate_touch_capable) {
                document.attachEvent("ontouchend", self.documentclick);
            }
        } else {
            /**
             * @return {undefined}
             */
            document.onclick = function() {
                self.documentclick(event);
            };
        }
    }
    addeventReady(function() {
        self.initialize();
        setTimeout("addeventatc.initialize();", 200);
    });
}
abs_add_to_calendar();
