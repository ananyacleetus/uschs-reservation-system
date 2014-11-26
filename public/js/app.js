/*
 | ----------------------------------------------------------------------
 | Application Bootstrap
 | ----------------------------------------------------------------------
 */

requirejs.config({
    baseUrl: "/js/vendor",
    paths: {
        "jquery": "jquery/dist/jquery.min",
        "underscore": "underscore-amd/underscore-min",
        "backbone": "backbone-amd/backbone-min",
        "handlebars": "handlebars/handlebars.min",
        "bootstrap": "bootstrap.min"
    },
    shim: {
        "bootsrap": {
            "deps": ["jquery"]
        },
        "backbone": {
            "deps": ["jquery", "underscore"],
            "exports": "Backbone"
        },
        "handlebars": {
            "exports": "Handlebars"
        },
        "underscore": {
            "exports": "_"
        }
    }
});

/*
 | ----------------------------------------------------------------------
 | Define App Module
 | ----------------------------------------------------------------------
 */

define(["jquery", "underscore", "backbone", "handlebars", "bootstrap"], function($, _, Backbone, Handlebars) {

    /*
     | ----------------------------------------------------------------------
     | Handlebars Helper Functions
     | ----------------------------------------------------------------------
     */

    Handlebars.registerHelper('condEqual', function (v1, v2, options) {
        if (v1 == v2)
        {
            return options.fn(this);
        }

        return options.inverse(this);
    });

    Handlebars.registerHelper('condNotEqual', function (v1, v2, options) {
        if (v1 != v2)
        {
            return options.fn(this);
        }

        return options.inverse(this);
    });

    Handlebars.registerHelper('greaterThan', function(v1, v2, options) {
        if (v1 > v2)
        {
            return options.fn(this);
        }

        return options.inverse(this);
    });

    /*
     | ----------------------------------------------------------------------
     | Base 64 Encoding
     | ----------------------------------------------------------------------
     */
    var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

    var encodeBase64 = function(input) {
        input = escape(input);
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;

        do {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
                keyStr.charAt(enc1) +
                keyStr.charAt(enc2) +
                keyStr.charAt(enc3) +
                keyStr.charAt(enc4);
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
        } while (i < input.length);

        return output;
    }

    /*
     | ----------------------------------------------------------------------
     | Retrieve cookie
     | ----------------------------------------------------------------------
     */
    var getCookie = function(name)
    {
        var i,x,y,cookies;
        cookies = document.cookie.split(";");

        for (i = 0; i < cookies.length; i++)
        {
            x = cookies[i].substr(0, cookies[i].indexOf("="));
            y = cookies[i].substr(cookies[i].indexOf("=")+1);
            x = x.replace(/^\s+|\s+$/g,"");
            if (x == name)
            {
                return unescape(y);
            }
        }
    };

    /*
     | ----------------------------------------------------------------------
     | Helpers
     | ----------------------------------------------------------------------
     */

    String.prototype.startsWith = function (str) {
        return this.slice(0, str.length) == str;
    };

    Array.prototype.contains = function ( needle ) {
        for (i in this) {
            if (this[i] === needle) return true;
        }
        return false;
    }

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


    /*
     | -------------------------------------------------
     | Models
     | -------------------------------------------------
     */

    var Reservation = Backbone.Model.extend({ });


    /*
     | -------------------------------------------------
     | Collections
     | -------------------------------------------------
     */

    var Reservations = Backbone.Collection.extend({
        model: Reservation
    });
    
    /*
     | -------------------------------------------------
     | Views
     | -------------------------------------------------
     */
 
    var ReservationView = Backbone.View.extend({
        template: Handlebars.compile($('[data-template-name="reservationTemplate"]').html()),
        tagName: "tr",

        initialize: function()
        {
            this.render();
        },

        render: function()
        {
            var data = this.model.toJSON();

            this.$el.html(this.template(data));
            this.$el.attr("data-reservation-id", data.id);

            return this;
        }

    });

    var reservationsHTML = "";

    var ReservationsView = Backbone.View.extend({
        initialize: function()
        {
            this.render();
        },

        render: function()
        {
            var that = this;
            _.each(this.collection.models, function(item)
            {
                that.renderReservation(item);
            }, this);

            this.$el.html(reservationsHTML);
            reservationsHTML = "";
        },

        renderReservation: function(item)
        {
            var view = new ReservationView({ model: item });
            reservationsHTML += view.el.outerHTML;
        }
    });
    
    
    /*
     | ----------------------------------------------------------------------
     | Application namespace
     | ----------------------------------------------------------------------
     */

    App = {};
    
    App.reservations = {};
    
    App.reservations.filterByDate = function(container, date) {
        var deferred = $.Deferred();
        
        $.ajax({
            url: "/reservations/filter_by_date?date=" + date,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === "ok") {
                    deferred.resolve();
                    
                    if (response.reservations.length > 0) {
                        new ReservationsView({collection: new Reservations(response.reservations), el: container});
                    } else {
                        container.html('<tr><td colspan="5">No carts are reserved on this date.</td></tr>');
                    }
                } else {
                    deferred.reject();
                }
            }
        });
        
        return deferred.promise();
    };
    
    App.reservations.create = function(formData) {
        
        var d = $.Deferred();
        
        $.ajax({
            url: "/reservations",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === "ok") {
                    d.resolve();
                    
                } else {
                    d.reject();
                }
            }
        });
        
        return d.promise();
    }

    return App;
});