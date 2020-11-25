MagegiantOnestepcheckoutShipment = Class.create();
MagegiantOnestepcheckoutShipment.prototype = {
    initialize: function (config) {
        window.shippingMethod = {};
        window.shippingMethod.validator = null;
        this.container = $$(config.containerSelector).first();
        this.switchMethodInputs = $$(config.switchMethodInputsSelector);
        this.saveShipmentUrl = config.saveShipmentUrl;

        this.init();
        this.initObservers();
    },

    init: function () {
        var me = this;
        this.switchMethodInputs.each(function (element) {
            var methodCode = element.value;
            if (element.checked) {
                me.switchToMethod(element.value);
                me.currentMethod = methodCode;
            }
        });
    },

    initObservers: function () {
        var me = this;
        this.switchMethodInputs.each(function (element) {
            element.observe('click', function (e) {
                me.switchToMethod(element.value);
            });
        })
    },

    switchToMethod: function (methodCode) {
        if (this.currentMethod !== methodCode) {
            MagegiantOnestepcheckoutCore.updater.startRequest(this.saveShipmentUrl, {
                method: 'post',
                parameters: Form.serialize(this.container, true)
            });
            this.currentMethod = methodCode;
        }
    }
};

/* Giftwrap from Enterprise */
MagegiantOnestepcheckoutShipmentEnterpriseGiftwrap = Class.create();
MagegiantOnestepcheckoutShipmentEnterpriseGiftwrap.prototype = {
    initialize: function (config) {
        // init dom elements
        this.addPrintedCardCheckbox = $(config.addPrintedCardCheckbox);
        this.addGiftOptionsCheckbox = $(config.addGiftOptionsCheckbox);

        // init urls
        this.addPrintedCardUrl = config.addPrintedCardUrl;

        // init behaviour
        this.init();
    },
    init: function () {
        if (this.addPrintedCardCheckbox) {
            this.addPrintedCardCheckbox.observe('change', this.addPrintedCard.bind(this))
        }
        if (this.addGiftOptionsCheckbox) {
            this.addGiftOptionsCheckbox.observe('change', this.addGiftOptions.bind(this))
        }
    },
    addPrintedCard: function () {
        var me = this;
        var requestOptions = {
            method: 'post',
            parameters: {add_printed_card: this.addPrintedCardCheckbox.getValue()},
            onComplete: function (transport) {
                me._onAjaxCompleteFn(transport);
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.addPrintedCardUrl, requestOptions);
    },
    addGiftOptions: function () {
        if (this.addPrintedCardCheckbox.getValue() || this.isPrintedCardApplied) {
            var requestOptions = {
                method: 'post',
                parameters: {add_printed_card: 0},
                onComplete: function (transport) {
                    me._onAjaxCompleteFn(transport);
                }
            };
            MagegiantOnestepcheckoutCore.updater.startRequest(this.addPrintedCardUrl, requestOptions);
        }
    },
    _onAjaxCompleteFn: function (transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch (e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        this.isPrintedCardApplied = json.printed_card_applied;
    }
};
/*Magegiant Store Pickup*/
MagegiantOnestepcheckoutShipmentStorePickup = Class.create();
MagegiantOnestepcheckoutShipmentStorePickup.prototype = {
    initialize: function (config) {
        // init dom elements
        this.containerSelector = $$(config.containerSelector).first();
        this.shipmentCheckbox = $$(config.shipmentCheckbox);
        this.initObserver();
        this.init();
    },
    initObserver: function () {
        this.shipmentCheckbox.each(function (el) {
            el.observe('click', function (event) {
                this.changeShippingMethod(event.target);
            }.bind(this))
        }.bind(this));
    },
    init: function () {
        this.shipmentCheckbox.each(function (el) {
            if (el.id == 's_method_storepickup_storepickup' && el.checked) {
                this.showStorePickup();
            }
        }.bind(this));
    },
    showStorePickup: function () {
        var me = this;
        var newHeight = MagegiantOnestepcheckoutCore.getElementHeight(this.containerSelector);
        this.containerSelector.setStyle({'display': ''});
        this._applyEffect(this.containerSelector, newHeight, 0.5, function () {
            me.containerSelector.setStyle({'height': ''});
        });
    },
    hideStorePickup: function () {
        var me = this;
        this._applyEffect(me.containerSelector, 0, 0.5, function () {
            me.containerSelector.setStyle({'display': 'none'});
        });
    },
    _applyEffect: function (element, newHeight, duration, afterFinish) {
        if (element.effect) {
            element.effect.cancel();
        }
        var afterFinishFn = afterFinish || Prototype.emptyFunction;
        element.effect = new Effect.Morph(element, {
            style: {
                'height': newHeight + 'px'
            },
            duration: duration,
            afterFinish: function () {
                delete element.effect;
                afterFinishFn();
            }
        });
    },
    changeShippingMethod: function (el) {
        if (el.id == 's_method_storepickup_storepickup' && el.checked) {
            this.showStorePickup();
        }
        else this.hideStorePickup();
    }
};

/*Magegiant Store Pickup*/
MagegiantOnestepcheckoutStorePickupPopup = Class.create();
MagegiantOnestepcheckoutStorePickupPopup.prototype = {
    initialize: function (config) {
        var me = this;
        this.selectStoreLink = $$(config.selectStoreLink).first();
        this.container = $$(config.containerSelector).first();
        this.popup = new MagegiantOnestepcheckoutUIPopup(config.popup);
        this.isLoadedMap = false;
        this.map = config.map;
        this.initObservers();
    },
    initObservers: function () {
        var me = this;
        this.selectStoreLink.observe('click', function (e) {
            if (!this.isLoadedMap) {
                getStoreGoogleLocation();
                this.isLoadedMap = true;
            }
            me.popup.showPopup();
        }.bind(this));
    },

};

/* Giftwrap from Enterprise */
MagegiantOnestepcheckoutShipmentEnterpriseGiftwrap = Class.create();
MagegiantOnestepcheckoutShipmentEnterpriseGiftwrap.prototype = {
    initialize: function(config) {
        // init dom elements
        this.addPrintedCardCheckbox = $(config.addPrintedCardCheckbox);
        this.addGiftOptionsCheckbox = $(config.addGiftOptionsCheckbox);

        // init urls
        this.addPrintedCardUrl = config.addPrintedCardUrl;

        // init behaviour
        this.init();
    },
    init: function() {
        if (this.addPrintedCardCheckbox) {
            this.addPrintedCardCheckbox.observe('change', this.addPrintedCard.bind(this))
        }
        if (this.addGiftOptionsCheckbox) {
            this.addGiftOptionsCheckbox.observe('change', this.addGiftOptions.bind(this))
        }
    },
    addPrintedCard: function() {
        var me = this;
        var requestOptions = {
            method: 'post',
            parameters: {add_printed_card: this.addPrintedCardCheckbox.getValue()},
            onComplete: function(transport) {
                me._onAjaxCompleteFn(transport);
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.addPrintedCardUrl, requestOptions);
    },
    addGiftOptions: function() {
        if (this.addPrintedCardCheckbox.getValue() || this.isPrintedCardApplied) {
            var requestOptions = {
                method: 'post',
                parameters: {add_printed_card: 0},
                onComplete: function(transport) {
                    me._onAjaxCompleteFn(transport);
                }
            };
            MagegiantOnestepcheckoutCore.updater.startRequest(this.addPrintedCardUrl, requestOptions);
        }
    },
    _onAjaxCompleteFn: function(transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch(e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        this.isPrintedCardApplied = json.printed_card_applied;
    }
};