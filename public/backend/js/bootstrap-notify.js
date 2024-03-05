!(function (t) {
    "function" == typeof define && define.amd ? define(["jquery"], t) : t("object" == typeof exports ? require("jquery") : jQuery);
})(function (t) {
    function s(s) {
        var e = !1;
        return (
            t('[data-notify="container"]').each(function (i, n) {
                var a = t(n),
                    o = a.find('[data-notify="title"]').text().trim(),
                    r = a.find('[data-notify="message"]').html().trim(),
                    l =
                        o ===
                        t("<div>" + s.settings.content.title + "</div>")
                            .html()
                            .trim(),
                    d =
                        r ===
                        t("<div>" + s.settings.content.message + "</div>")
                            .html()
                            .trim(),
                    g = a.hasClass("alert-" + s.settings.type);
                return l && d && g && (e = !0), !e;
            }),
            e
        );
    }
    function e(e, n, a) {
        var o = { content: { message: "object" == typeof n ? n.message : n, title: n.title ? n.title : "", icon: n.icon ? n.icon : "", url: n.url ? n.url : "#", target: n.target ? n.target : "-" } };
        (a = t.extend(!0, {}, o, a)),
            (this.settings = t.extend(!0, {}, i, a)),
            (this._defaults = i),
            "-" === this.settings.content.target && (this.settings.content.target = this.settings.url_target),
            (this.animations = { start: "webkitAnimationStart oanimationstart MSAnimationStart animationstart", end: "webkitAnimationEnd oanimationend MSAnimationEnd animationend" }),
            "number" == typeof this.settings.offset && (this.settings.offset = { x: this.settings.offset, y: this.settings.offset }),
            (this.settings.allow_duplicates || (!this.settings.allow_duplicates && !s(this))) && this.init();
    }
    var i = {
        element: "body",
        position: null,
        type: "info",
        allow_dismiss: !0,
        allow_duplicates: !0,
        newest_on_top: !1,
        showProgressbar: !1,
        placement: { from: "top", align: "right" },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 5e3,
        timer: 1e3,
        url_target: "_blank",
        mouse_over: null,
        animate: { enter: "animated fadeInDown", exit: "animated fadeOutUp" },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: "class",
        template:
            '<div data-notify="container" class="col-xl-4 col-md-4 col-sm-6 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>',
    };
    (String.format = function () {
        for (var t = arguments[0], s = 1; s < arguments.length; s++) t = t.replace(RegExp("\\{" + (s - 1) + "\\}", "gm"), arguments[s]);
        return t;
    }),
        t.extend(e.prototype, {
            init: function () {
                var t = this;
                this.buildNotify(),
                    this.settings.content.icon && this.setIcon(),
                    "#" != this.settings.content.url && this.styleURL(),
                    this.styleDismiss(),
                    this.placement(),
                    this.bind(),
                    (this.notify = {
                        $ele: this.$ele,
                        update: function (s, e) {
                            var i = {};
                            "string" == typeof s ? (i[s] = e) : (i = s);
                            for (var n in i)
                                switch (n) {
                                    case "type":
                                        this.$ele.removeClass("alert-" + t.settings.type),
                                            this.$ele.find('[data-notify="progressbar"] > .progress-bar').removeClass("progress-bar-" + t.settings.type),
                                            (t.settings.type = i[n]),
                                            this.$ele
                                                .addClass("alert-" + i[n])
                                                .find('[data-notify="progressbar"] > .progress-bar')
                                                .addClass("progress-bar-" + i[n]);
                                        break;
                                    case "icon":
                                        var a = this.$ele.find('[data-notify="icon"]');
                                        "class" === t.settings.icon_type.toLowerCase() ? a.removeClass(t.settings.content.icon).addClass(i[n]) : (a.is("img") || a.find("img"), a.attr("src", i[n]));
                                        break;
                                    case "progress":
                                        var o = t.settings.delay - t.settings.delay * (i[n] / 100);
                                        this.$ele.data("notify-delay", o),
                                            this.$ele
                                                .find('[data-notify="progressbar"] > div')
                                                .attr("aria-valuenow", i[n])
                                                .css("width", i[n] + "%");
                                        break;
                                    case "url":
                                        this.$ele.find('[data-notify="url"]').attr("href", i[n]);
                                        break;
                                    case "target":
                                        this.$ele.find('[data-notify="url"]').attr("target", i[n]);
                                        break;
                                    default:
                                        this.$ele.find('[data-notify="' + n + '"]').html(i[n]);
                                }
                            var r = this.$ele.outerHeight() + parseInt(t.settings.spacing) + parseInt(t.settings.offset.y);
                            t.reposition(r);
                        },
                        close: function () {
                            t.close();
                        },
                    });
            },
            buildNotify: function () {
                var s = this.settings.content;
                (this.$ele = t(String.format(this.settings.template, this.settings.type, s.title, s.message, s.url, s.target))),
                    this.$ele.attr("data-notify-position", this.settings.placement.from + "-" + this.settings.placement.align),
                    this.settings.allow_dismiss || this.$ele.find('[data-notify="dismiss"]').css("display", "none"),
                    ((this.settings.delay > 0 || this.settings.showProgressbar) && this.settings.showProgressbar) || this.$ele.find('[data-notify="progressbar"]').remove();
            },
            setIcon: function () {
                "class" === this.settings.icon_type.toLowerCase()
                    ? this.$ele.find('[data-notify="icon"]').addClass(this.settings.content.icon)
                    : this.$ele.find('[data-notify="icon"]').is("img")
                    ? this.$ele.find('[data-notify="icon"]').attr("src", this.settings.content.icon)
                    : this.$ele.find('[data-notify="icon"]').append('<img src="' + this.settings.content.icon + '" alt="Notify Icon" />');
            },
            styleDismiss: function () {
                this.$ele.find('[data-notify="dismiss"]').css({ position: "absolute", right: "10px", top: "5px", zIndex: this.settings.z_index + 2 });
            },
            styleURL: function () {
                this.$ele
                    .find('[data-notify="url"]')
                    .css({ backgroundImage: "url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)", height: "100%", left: 0, position: "absolute", top: 0, width: "100%", zIndex: this.settings.z_index + 1 });
            },
            placement: function () {
                var s = this,
                    e = this.settings.offset.y,
                    i = {
                        display: "inline-block",
                        margin: "0px auto",
                        position: this.settings.position ? this.settings.position : "body" === this.settings.element ? "fixed" : "absolute",
                        transition: "all .5s ease-in-out",
                        zIndex: this.settings.z_index,
                    },
                    n = !1,
                    a = this.settings;
                switch (
                    (t('[data-notify-position="' + this.settings.placement.from + "-" + this.settings.placement.align + '"]:not([data-closing="true"])').each(function () {
                        e = Math.max(e, parseInt(t(this).css(a.placement.from)) + parseInt(t(this).outerHeight()) + parseInt(a.spacing));
                    }),
                    this.settings.newest_on_top === !0 && (e = this.settings.offset.y),
                    (i[this.settings.placement.from] = e + "px"),
                    this.settings.placement.align)
                ) {
                    case "left":
                    case "right":
                        i[this.settings.placement.align] = this.settings.offset.x + "px";
                        break;
                    case "center":
                        (i.left = 0), (i.right = 0);
                }
                this.$ele.css(i).addClass(this.settings.animate.enter),
                    t.each(["webkit-", "moz-", "o-", "ms-", ""], function (t, e) {
                        s.$ele[0].style[e + "AnimationIterationCount"] = 1;
                    }),
                    t(this.settings.element).append(this.$ele),
                    this.settings.newest_on_top === !0 && ((e = parseInt(e) + parseInt(this.settings.spacing) + this.$ele.outerHeight()), this.reposition(e)),
                    t.isFunction(s.settings.onShow) && s.settings.onShow.call(this.$ele),
                    this.$ele
                        .one(this.animations.start, function () {
                            n = !0;
                        })
                        .one(this.animations.end, function () {
                            t.isFunction(s.settings.onShown) && s.settings.onShown.call(this);
                        }),
                    setTimeout(function () {
                        n || (t.isFunction(s.settings.onShown) && s.settings.onShown.call(this));
                    }, 600);
            },
            bind: function () {
                var s = this;
                if (
                    (this.$ele.find('[data-notify="dismiss"]').on("click", function () {
                        s.close();
                    }),
                    this.$ele
                        .mouseover(function () {
                            t(this).data("data-hover", "true");
                        })
                        .mouseout(function () {
                            t(this).data("data-hover", "false");
                        }),
                    this.$ele.data("data-hover", "false"),
                    this.settings.delay > 0)
                ) {
                    s.$ele.data("notify-delay", s.settings.delay);
                    var e = setInterval(function () {
                        var t = parseInt(s.$ele.data("notify-delay")) - s.settings.timer;
                        if (("false" === s.$ele.data("data-hover") && "pause" === s.settings.mouse_over) || "pause" != s.settings.mouse_over) {
                            var i = ((s.settings.delay - t) / s.settings.delay) * 100;
                            s.$ele.data("notify-delay", t),
                                s.$ele
                                    .find('[data-notify="progressbar"] > div')
                                    .attr("aria-valuenow", i)
                                    .css("width", i + "%");
                        }
                        t > -s.settings.timer || (clearInterval(e), s.close());
                    }, s.settings.timer);
                }
            },
            close: function () {
                var s = this,
                    e = parseInt(this.$ele.css(this.settings.placement.from)),
                    i = !1;
                this.$ele.data("closing", "true").addClass(this.settings.animate.exit),
                    s.reposition(e),
                    t.isFunction(s.settings.onClose) && s.settings.onClose.call(this.$ele),
                    this.$ele
                        .one(this.animations.start, function () {
                            i = !0;
                        })
                        .one(this.animations.end, function () {
                            t(this).remove(), t.isFunction(s.settings.onClosed) && s.settings.onClosed.call(this);
                        }),
                    setTimeout(function () {
                        i || (s.$ele.remove(), s.settings.onClosed && s.settings.onClosed(s.$ele));
                    }, 600);
            },
            reposition: function (s) {
                var e = this,
                    i = '[data-notify-position="' + this.settings.placement.from + "-" + this.settings.placement.align + '"]:not([data-closing="true"])',
                    n = this.$ele.nextAll(i);
                this.settings.newest_on_top === !0 && (n = this.$ele.prevAll(i)),
                    n.each(function () {
                        t(this).css(e.settings.placement.from, s), (s = parseInt(s) + parseInt(e.settings.spacing) + t(this).outerHeight());
                    });
            },
        }),
        (t.notify = function (t, s) {
            var i = new e(this, t, s);
            return i.notify;
        }),
        (t.notifyDefaults = function (s) {
            return (i = t.extend(!0, {}, i, s));
        }),
        (t.notifyClose = function (s) {
            void 0 === s || "all" === s
                ? t("[data-notify]").find('[data-notify="dismiss"]').trigger("click")
                : t('[data-notify-position="' + s + '"]')
                      .find('[data-notify="dismiss"]')
                      .trigger("click");
        });
});
