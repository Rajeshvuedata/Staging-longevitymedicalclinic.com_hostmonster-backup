/* CART SUMMARY */
MagegiantOnestepcheckoutReviewCart = Class.create();
MagegiantOnestepcheckoutReviewCart.prototype = {
    initialize: function (config) {
        this.config = config;
        this.container = $$(config.containerSelector).first();
        this.useForShippingCheckboxContainer = $$(config.useForShippingCheckboxContainerSelector).first();
        this.ajaxCartItemUrl = config.ajaxCartItemUrl;
        this.overlayConfig = config.overlayConfig;
        this.removeItemMessage = config.removeItemMessage;
        this.initObservers();
        var me = this;
        Event.observe(window, 'dom:loaded', function (e) {
            me.initRelatedBlockElements();
        });
    },

    initObservers: function () {
        var me = this;
        $$(this.config.removeLinkSelector).each(function (el) {
            el.observe('click', me.onClickOnRemoveLink.bind(me));
        });
        $$(this.config.plusLinkSelector).each(function (el) {
            el.observe('click', me.onClickOnPlusLink.bind(me));
        });
        $$(this.config.minusLinkSelector).each(function (el) {
            el.observe('click', me.onClickOnMinusLink.bind(me));
        });
    },

    initRelatedBlockElements: function () {
        this.relatedBlockContainer = $$(this.config.relatedBlockContainerSelector).first();
    },

    onClickOnRemoveLink: function (event) {
        if (!confirm(this.removeItemMessage))
            return false;
        var item = event.target;
        this._processCartItem('remove', item.id);
        Event.stop(event);
    },
    onClickOnPlusLink: function (event) {
        var item = event.target;
        this._processCartItem('plus', item.id);
        Event.stop(event);
    },
    onClickOnMinusLink: function (event) {
        var item = event.target;
        this._processCartItem('minus', item.id);
        Event.stop(event);
    },
    _processCartItem: function (action, id) {
        var me = this;
        /* ---------------------------------------- */
        var requestOptions = {
            method: 'post',
            parameters: {
                action: action,
                id: id
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(me.ajaxCartItemUrl, requestOptions);
        /*if (action == 'remove')
         this.addLoaderToRelated();*/
    },
    _onAjaxCompleteFn: function (transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch (e) {
            this.removeLoaderFromRelated();
            return;
        }
        if (json.success) {
            if ("blocks" in json) {
                this._updateBlocksFromJSONResponse(json.blocks);
                var action = MagegiantOnestepcheckoutCore.updater._getActionFromUrl(transport.request.url);
                MagegiantOnestepcheckoutCore.updater.removeActionBlocksFromQueue(action, json);
                if ("can_shop" in json && json.can_shop) {
                    this.useForShippingCheckboxContainer.removeClassName('no-display')
                }
                this.initObservers();
                this.initRelatedBlockElements();
            }
        }
        this.removeLoaderFromRelated();
    },

    _updateBlocksFromJSONResponse: function (json) {
        if (json.related && this.relatedBlockContainer) {
            var storage = new Element('div');
            storage.innerHTML = json.related;
            var newBlock = storage.select('#' + this.relatedBlockContainer.getAttribute('id')).first();
            this.relatedBlockContainer.update(newBlock.innerHTML);
        }
    },

    addLoaderToRelated: function () {
        if (!this.relatedBlockContainer) {
            return;
        }
        MagegiantOnestepcheckoutCore.addLoaderOnBlock(this.relatedBlockContainer, this.overlayConfig);
    },

    removeLoaderFromRelated: function () {
        if (!this.relatedBlockContainer) {
            return;
        }
        MagegiantOnestepcheckoutCore.removeLoaderFromBlock(this.relatedBlockContainer, this.overlayConfig);
    }
};

/* COUPON CODE */
MagegiantOnestepcheckoutReviewCoupon = Class.create();
MagegiantOnestepcheckoutReviewCoupon.prototype = {
    initialize: function (config) {
        // init dom elements
        this.msgContainer = $$(config.msgContainerSelector).first();
        this.couponCodeInput = $(config.couponCodeInput);
        // init urls
        this.applyCouponUrl = config.applyCouponUrl;
        // init messages
        this.successMessageBoxCssClass = config.successMessageBoxCssClass;
        this.errorMessageBoxCssClass = config.errorMessageBoxCssClass;
        this.jsErrorMsg = config.jsErrorMsg;
        this.jsSuccessMsg = config.jsSuccessMsg;
        // init config
        this.isCouponApplied = config.isCouponApplied;
        // init "Apply Coupon Button"
        this.isApplyCouponButton = config.isApplyCouponButton;
        this.applyCouponButton = $$(config.applyCouponButtonSelector).first();
        this.cancelCouponButton = $$(config.cancelCouponButtonSelector).first();
        // init behaviour
        this.ajaxRequestId = 0;
        this.init();
    },
    init: function () {
        if (this.isApplyCouponButton) {
            if (this.applyCouponButton) {
                this.applyCouponButton.observe('click', this.applyCoupon.bind(this));
                this.cancelCouponButton.observe('click', this.applyCoupon.bind(this))
            }
        } else {
            if (this.couponCodeInput) {
                this.couponCodeInput.observe('change', this.applyCoupon.bind(this))
            }
        }
    },
    applyCoupon: function (e) {
        this.removeMsg();
        if (this.isApplyCouponButton) {
            if (!this.isCouponApplied) {
                this.couponCodeInput.addClassName('required-entry');
                var validationResult = Validation.validate(this.couponCodeInput)
                this.couponCodeInput.removeClassName('required-entry');
                if (!validationResult) {
                    return;
                }
            } else {
                this.couponCodeInput.setValue('');
            }
        } else {
            if (!this.couponCodeInput.getValue() && !this.isCouponApplied) {
                return;
            }
        }
        var me = this;
        this.ajaxRequestId++;
        var currentAjaxRequestId = this.ajaxRequestId;
        var requestOptions = {
            method: 'post',
            parameters: {
                coupon_code: this.couponCodeInput.getValue()
            },
            onComplete: function (transport) {
                if (currentAjaxRequestId !== me.ajaxRequestId) {
                    return;
                }
                me._onAjaxCouponActionCompleteFn(transport);
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.applyCouponUrl, requestOptions);
    },
    _onAjaxCouponActionCompleteFn: function (transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch (e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        this.isCouponApplied = json.coupon_applied;
        if (json.success) {
            var successMsg = this.jsSuccessMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                successMsg = json.messages;
            }
            this.showSuccess(successMsg);
            if (this.isCouponApplied) {
                this.applyCouponButton.hide();
                this.cancelCouponButton.show();
            } else {
                this.applyCouponButton.show();
                this.cancelCouponButton.hide();
            }
        } else {
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg);
        }
    },
    showError: function (msg, afterShowFn) {
        MagegiantOnestepcheckoutCore.showMsg(msg, this.errorMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function (e) {
                afterShowFn();
            }
        });
    },
    showSuccess: function (msg, afterShowFn) {
        MagegiantOnestepcheckoutCore.showMsg(msg, this.successMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function (e) {
                afterShowFn();
            }
        });
    },
    removeMsg: function () {
        if (this.msgContainer.down()) {
            var me = this;
            new Effect.Morph(this.msgContainer, {
                style: {
                    height: 0 + 'px'
                },
                duration: 0.3,
                afterFinish: function (e) {
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.errorMessageBoxCssClass);
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.successMessageBoxCssClass);
                }
            });
        }
    }
};

/* COMMENTS*/
MagegiantOnestepcheckoutReviewComments = Class.create();
MagegiantOnestepcheckoutReviewComments.prototype = {
    initialize: function (config) {
        this.container = $$(config.containerSelector).first();
        this.newRowCount = config.newRowCount || 5;
        this.saveValuesUrl = config.saveValuesUrl;

        var me = this;
        this.container.select('textarea').each(function (textarea) {
            textarea.setStyle({
                'overflow-y': 'hidden'
            });
            me.initShowEffectObserver(textarea);
        });
        Form.getElements(this.container).each(function (element) {
            element.observe('change', me.requestToValuesSave.bind(me));
        });
    },

    requestToValuesSave: function (e) {
        new Ajax.Request(this.saveValuesUrl, {
            method: 'post',
            parameters: Form.serialize(this.container, true)
        });
    },

    initShowEffectObserver: function (textarea) {
        var originalScrollHeight = textarea.scrollHeight;
        var originalRowCount = parseInt(textarea.getAttribute('rows'));
        var originalHeight = parseInt(textarea.getStyle('height'));

        var me = this;
        textarea.observe('focus', function (e) {
            var currentRowCount = originalRowCount +
                (((textarea.scrollHeight - originalScrollHeight) * originalRowCount) / originalHeight);
            if (currentRowCount < me.newRowCount) {
                currentRowCount = me.newRowCount;
            } else {
                currentRowCount++; //add on empty line
            }
            var currentHeight = (originalHeight / originalRowCount) * currentRowCount;
            me.doChangeRowsAttributeEffect(textarea, currentRowCount, currentHeight, function () {
                textarea.setStyle({
                    'overflow-y': 'auto'
                });
            });
        });
        textarea.observe('blur', function (e) {
            var lengthOfValue = textarea.getValue().strip().length;
            if (lengthOfValue === 0) {
                me.doChangeScrollOfTextareaEffect(textarea, function () {
                    textarea.setStyle({
                        'overflow-y': 'hidden'
                    });
                    me.doChangeRowsAttributeEffect(textarea, originalRowCount, originalHeight);
                });
            } else {
                var newHeight = (originalHeight / originalRowCount) * me.newRowCount;
                me.doChangeScrollOfTextareaEffect(textarea, function () {
                    textarea.setStyle({
                        'overflow-y': 'hidden'
                    });
                    me.doChangeRowsAttributeEffect(textarea, me.newRowCount, newHeight);
                });
            }
        });
    },

    doChangeRowsAttributeEffect: function (textarea, newRows, newHeight, afterFinish) {
        if (textarea.effect) {
            textarea.effect.cancel();
        }
        var afterFinish = afterFinish || new Function();
        textarea.effect = new Effect.Morph(textarea, {
            style: {
                height: newHeight + "px"
            },
            duration: 0.5,
            afterFinish: function () {
                textarea.setAttribute('rows', newRows);
                delete textarea.effect;
                afterFinish();
            }
        });
    },

    doChangeScrollOfTextareaEffect: function (textarea, afterFinish) {
        if (textarea.effect) {
            textarea.effect.cancel();
        }
        var afterFinish = afterFinish || new Function();
        if (textarea.scrollTop === 0) {
            afterFinish();
            return;
        }
        new Effect.Tween(textarea, textarea.scrollTop, 0, {
            duration: 0.5,
            afterFinish: function () {
                afterFinish();
            }
        }, 'scrollTop');
    }
};

/* NEWSLETTER*/
MagegiantOnestepcheckoutReviewNewsletter = Class.create();
MagegiantOnestepcheckoutReviewNewsletter.prototype = {
    initialize: function (config) {
        this.container = $$(config.containerSelector).first();
        this.generalInput = $$(config.generalInputSelector).first();
        this.segmentsContainer = $$(config.segmentsContainerSelector).first();
        this.saveValuesUrl = config.saveValuesUrl;

        if (this.generalInput) {
            this.generalInput.observe('click', this.onSubscriptionChecked.bind(this));
        }
        var me = this;
        Form.getElements(this.container).each(function (element) {
            element.observe('click', me.requestToSaveValues.bind(me));
        });
    },

    requestToSaveValues: function (e) {
        new Ajax.Request(this.saveValuesUrl, {
            method: 'post',
            parameters: Form.serialize(this.container, true)
        })
    },

    onSubscriptionChecked: function (e) {
        var me = this;
        if (this.segmentsContainer) {
            if (this.generalInput.getValue()) {
                this.showSegments();
            } else {
                this.hideSegments();
            }
        }
    },

    showSegments: function () {
        this._changeHeightToWithEffect(this._collectRealSegmentsHeight());
    },

    hideSegments: function () {
        this._changeHeightToWithEffect(0);
    },

    _changeHeightToWithEffect: function (height) {
        var me = this;
        if (this.effect) {
            this.effect.cancel();
        }
        this.effect = new Effect.Morph(this.segmentsContainer, {
            style: {'height': height + "px"},
            duration: 0.5,
            afterEffect: function () {
                delete me.effect;
            }
        });
    },

    _collectRealSegmentsHeight: function () {
        var originalHeightStyle = this.segmentsContainer.getStyle('height');
        this.segmentsContainer.setStyle({'height': 'auto'});
        var realHeight = this.segmentsContainer.getHeight();
        this.segmentsContainer.setStyle({'height': originalHeightStyle});
        return realHeight;
    }
};

/* TERMS & CONDITIONS */
MagegiantOnestepcheckoutReviewTerms = Class.create();
MagegiantOnestepcheckoutReviewTerms.prototype = {
    initialize: function (config) {
        this.container = $$(config.containerSelector).first();
        this.items = $$(config.itemsSelector);
        this.linkFromItemSelector = config.linkFromItemSelector;
        this.checkboxFromItemSelector = config.checkboxFromItemSelector;
        this.descriptionContainerFromItemSelector = config.descriptionContainerFromItemSelector;
        this.popup = new MagegiantOnestepcheckoutUIPopup(config.popup);
        this.initObservers();
    },

    initObservers: function () {
        var me = this;
        this.items.each(function (item) {
            var link = item.select(me.linkFromItemSelector).first();
            var description = item.select(me.descriptionContainerFromItemSelector).first();
            if (!link || !description) {
                return;
            }
            link.observe('click', function (e) {
                me.currentItem = item;
                me.popup.showPopupWithDescription(description.innerHTML);
            });
        });
        this.popup.buttons.accept.onClickFn = function (e) {
            if (me.currentItem) {
                var checkbox = me.currentItem.select(me.checkboxFromItemSelector).first();
                if (checkbox) {
                    checkbox.checked = true;
                }
            }
        }
    }
};

/* Gift wrap */
MagegiantOnestepcheckoutReviewGiftwrap = Class.create();
MagegiantOnestepcheckoutReviewGiftwrap.prototype = {
    initialize: function (config) {
        // init dom elements
        this.useGiftwrapCheckbox = $(config.useGiftwrapCheckbox);
        // init urls
        this.addGiftwrapUrl = config.addGiftwrapUrl;
        this.initObserve();
    },
    initObserve: function () {
        if (this.useGiftwrapCheckbox) {
            this.useGiftwrapCheckbox.observe('change', this.applyGiftwrap.bind(this))
        }
    },
    applyGiftwrap: function () {
        var me = this;
        var requestOptions = {
            method: 'post',
            parameters: {
                is_used_giftwrap: this.useGiftwrapCheckbox.getValue()
            },
            onComplete: function (transport) {
                if (currentAjaxRequestId !== me.ajaxRequestId) {
                    return;
                }
                me._onAjaxCompleteFn(transport);
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.addGiftwrapUrl, requestOptions);
    },
    _onAjaxCompleteFn: function (transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch (e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        this.isPointsApplied = json.points_applied;
        if (json.success) {
            var successMsg = this.jsSuccessMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                successMsg = json.messages;
            }
            this.showSuccess(successMsg);
        } else {
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg);
        }
    },
};
/*Gift message*/
MagegiantOnestepcheckoutReviewGiftmessage = Class.create();
MagegiantOnestepcheckoutReviewGiftmessage.prototype = {
    initialize: function (config) {
        // init dom elements
        this.useGiftmessageCheckbox = $(config.useGiftmessageCheckbox);
        this.containerSelector = $(config.containerSelector);
        this.config = config;
        // init observer
        this.initObserve();
    },
    initObserve: function () {
        this.useGiftmessageCheckbox.observe('change', this.handleGiftMessage.bind(this))
    },
    handleGiftMessage: function () {
        if (this.useGiftmessageCheckbox.checked)
            this.showGiftMessage();
        else this.hideGiftMessage();
    },
    showGiftMessage: function () {
        var me = this;
        var newHeight = MagegiantOnestepcheckoutCore.getElementHeight(this.containerSelector);
        this.containerSelector.setStyle({'display': ''});
        this._applyEffect(this.containerSelector, newHeight, 0.3, function () {
            me.containerSelector.setStyle({'height': ''});
        });

    },
    hideGiftMessage: function () {
        var me = this;
        this._applyEffect(me.containerSelector, 0, 0.5, function () {
            me.containerSelector.setStyle({'display': 'none'});
        });
        this.clearInput();
    },
    clearInput: function () {
        $(this.config.sender).setValue('');
        $(this.config.receiver).setValue('');
        $(this.config.message).setValue('');
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
    }
};
/*Delivery time*/
MagegiantOnestepcheckoutReviewDelivery = Class.create();
MagegiantOnestepcheckoutReviewDelivery.prototype = {
    initialize: function (config) {
        // init dom elements
        this.useDeliveryCheckbox = $(config.useDeliveryCheckbox);
        this.containerSelector = $(config.containerSelector);
        this.config = config;
        // init observer
        this.initObserve();
    },
    initObserve: function () {
        this.useDeliveryCheckbox.observe('change', this.handleDelivery.bind(this))
    },
    handleDelivery: function () {
        if (this.useDeliveryCheckbox.getValue())
            this.showDelivery();
        else this.hideDelivery();
    },
    showDelivery: function () {
        var me = this;
        var newHeight = MagegiantOnestepcheckoutCore.getElementHeight(this.containerSelector);
        this.containerSelector.setStyle({'display': ''});
        this._applyEffect(this.containerSelector, newHeight, 0.3, function () {
            me.containerSelector.setStyle({'height': ''});
        });

    },
    hideDelivery: function () {
        var me = this;
        this._applyEffect(me.containerSelector, 0, 0.5, function () {
            me.containerSelector.setStyle({'display': 'none'});
        });
        this.clearInput();
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
    }
};
/*Intergrate Enterprise*/

/* Store Credit from Enterprise */
MagegiantOnestepcheckoutReviewEnterpriseStorecredit = Class.create();
MagegiantOnestepcheckoutReviewEnterpriseStorecredit.prototype = {
    initialize: function(config) {
        // init dom elements
        this.container = $$(config.containerSelector).first();
        this.msgContainer = $$(config.msgContainerSelector).first();
        this.useStorecreditCheckbox = $(config.useStorecreditCheckbox);

        // init urls
        this.applyStorecreditUrl = config.applyStorecreditUrl;

        // init messages
        this.successMessageBoxCssClass = config.successMessageBoxCssClass;
        this.errorMessageBoxCssClass = config.errorMessageBoxCssClass;
        this.jsErrorMsg = config.jsErrorMsg;
        this.jsSuccessMsg = config.jsSuccessMsg;

        // init behaviour
        this.ajaxRequestId = 0;
        this.init();
    },
    init: function() {
        if (this.useStorecreditCheckbox) {
            this.useStorecreditCheckbox.observe('change', this.applyStorecredit.bind(this))
        }
    },
    applyStorecredit: function() {
        this.removeMsg();
        var me = this;
        this.ajaxRequestId++;
        var currentAjaxRequestId = this.ajaxRequestId;
        var requestOptions = {
            method: 'post',
            parameters: {
                use_customer_balance: this.useStorecreditCheckbox.getValue()
            },
            onComplete: function(transport) {
                if (currentAjaxRequestId !== me.ajaxRequestId) {
                    return;
                }
                me._onAjaxCompleteFn(transport);
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.applyStorecreditUrl, requestOptions);
    },
    _onAjaxCompleteFn: function(transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch(e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        this.isPointsApplied = json.points_applied;
        if (json.success) {
            var successMsg = this.jsSuccessMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                successMsg = json.messages;
            }
            this.showSuccess(successMsg);
        } else {
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg);
        }
    },
    showError: function(msg, afterShowFn){
        MagegiantOnestepcheckoutCore.showMsg(msg, this.errorMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    showSuccess: function(msg, afterShowFn){
        MagegiantOnestepcheckoutCore.showMsg(msg, this.successMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    removeMsg: function() {
        if (this.msgContainer.down()) {
            var me = this;
            new Effect.Morph(this.msgContainer, {
                style: {
                    height: 0 + 'px'
                },
                duration: 0.3,
                afterFinish: function(e) {
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.errorMessageBoxCssClass);
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.successMessageBoxCssClass);
                }
            });
        }
    }
};

/* POINTS & REWARDS from Enterprise */
MagegiantOnestepcheckoutReviewEnterprisePoints = Class.create();
MagegiantOnestepcheckoutReviewEnterprisePoints.prototype = {
    initialize: function(config) {
        // init dom elements
        this.container = $$(config.containerSelector).first();
        this.msgContainer = $$(config.msgContainerSelector).first();
        this.usePointsCheckbox = $(config.usePointsCheckbox);

        // init urls
        this.applyPointsUrl = config.applyPointsUrl;

        // init messages
        this.successMessageBoxCssClass = config.successMessageBoxCssClass;
        this.errorMessageBoxCssClass = config.errorMessageBoxCssClass;
        this.jsErrorMsg = config.jsErrorMsg;
        this.jsSuccessMsg = config.jsSuccessMsg;

        // init behaviour
        this.ajaxRequestId = 0;
        this.init();
    },
    init: function() {
        if (this.usePointsCheckbox) {
            this.usePointsCheckbox.observe('change', this.applyPoints.bind(this))
        }
    },
    applyPoints: function() {
        this.removeMsg();
        var me = this;
        this.ajaxRequestId++;
        var currentAjaxRequestId = this.ajaxRequestId;
        var requestOptions = {
            method: 'post',
            parameters: {
                use_reward_points: this.usePointsCheckbox.getValue()
            },
            onComplete: function(transport) {
                if (currentAjaxRequestId !== me.ajaxRequestId) {
                    return;
                }
                me._onAjaxCompleteFn(transport);
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.applyPointsUrl, requestOptions);
    },
    _onAjaxCompleteFn: function(transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch(e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        this.isPointsApplied = json.points_applied;
        if (json.success) {
            var successMsg = this.jsSuccessMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                successMsg = json.messages;
            }
            this.showSuccess(successMsg);
        } else {
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg);
        }
    },
    showError: function(msg, afterShowFn){
        MagegiantOnestepcheckoutCore.showMsg(msg, this.errorMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    showSuccess: function(msg, afterShowFn){
        MagegiantOnestepcheckoutCore.showMsg(msg, this.successMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    removeMsg: function() {
        if (this.msgContainer.down()) {
            var me = this;
            new Effect.Morph(this.msgContainer, {
                style: {
                    height: 0 + 'px'
                },
                duration: 0.3,
                afterFinish: function(e) {
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.errorMessageBoxCssClass);
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.successMessageBoxCssClass);
                }
            });
        }
    }
};

/*GIFT CARD CODE*/
MagegiantOnestepcheckoutEnterpriseGiftcard = Class.create();
MagegiantOnestepcheckoutEnterpriseGiftcard.prototype = {
    initialize: function(config) {
        // init dom elements
        this.msgContainer = $$(config.msgContainerSelector).first();
        this.giftcardCodeInput = $(config.giftcardCodeInput);
        // init urls
        this.applyGiftcardUrl = config.applyGiftcardUrl;
        this.removeGiftcardUrl = config.removeGiftcardUrl;
        // init messages
        this.successMessageBoxCssClass = config.successMessageBoxCssClass;
        this.errorMessageBoxCssClass = config.errorMessageBoxCssClass;
        this.jsErrorMsg = config.jsErrorMsg;
        this.jsSuccessMsg = config.jsSuccessMsg;
        // init "Apply Coupon Button"
        this.applyGiftcardButton = $$(config.applyGiftcardButtonSelector).first();

        this.cancelGiftcardElSelector = config.cancelGiftcardElSelector;
        // init behaviour
        this.ajaxRequestId = 0;
        this.init();
    },
    init: function() {
        if (this.applyGiftcardButton) {
            this.applyGiftcardButton.observe('click', this.applyGiftcard.bind(this));
        }
        this.initRemoveHandle();
    },

    initRemoveHandle: function() {
        var me = this;
        $$(this.cancelGiftcardElSelector).each(function(el){
            if (el.getAttribute('href') && el.getAttribute('href').indexOf('giftcard/cart/remove/code/') !== -1) {
                el.observe('click', function(e){
                    me.removeGiftcard(e, el);
                });
            }
        });
    },

    applyGiftcard: function(e) {
        this.removeMsg();
        this.giftcardCodeInput.addClassName('required-entry');
        var validationResult = Validation.validate(this.giftcardCodeInput);
        this.giftcardCodeInput.removeClassName('required-entry');
        if (!validationResult) {
            return;
        }
        var me = this;
        this.ajaxRequestId++;
        var currentAjaxRequestId = this.ajaxRequestId;
        var requestOptions = {
            method: 'post',
            parameters: {
                enterprise_giftcard_code: this.giftcardCodeInput.getValue()
            },
            onComplete: function(transport){
                if (currentAjaxRequestId !== me.ajaxRequestId) {
                    return;
                }
                me._onAjaxGiftcardActionCompleteFn(transport);
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.applyGiftcardUrl, requestOptions);
    },

    removeGiftcard: function(e, el) {
        e.stop();
        this.removeMsg();
        var code = el.getAttribute('href').match(/giftcard\/cart\/remove\/code\/([^\/]+)\//)[1];
        var requestOptions = {
            method: 'post',
            parameters: {
                enterprise_giftcard_code: code
            }
        };
        MagegiantOnestepcheckoutCore.updater.startRequest(this.removeGiftcardUrl, requestOptions);
    },

    _onAjaxGiftcardActionCompleteFn: function(transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch(e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        if (json.success) {
            var successMsg = this.jsSuccessMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                successMsg = json.messages;
            }
            this.showSuccess(successMsg);
            this.giftcardCodeInput.setValue('');
            this.initRemoveHandle();
        } else {
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg);
        }
    },
    showError: function(msg, afterShowFn){
        MagegiantOnestepcheckoutCore.showMsg(msg, this.errorMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    showSuccess: function(msg, afterShowFn){
        MagegiantOnestepcheckoutCore.showMsg(msg, this.successMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    removeMsg: function() {
        if (this.msgContainer.down()) {
            var me = this;
            new Effect.Morph(this.msgContainer, {
                style: {
                    height: 0 + 'px'
                },
                duration: 0.3,
                afterFinish: function(e) {
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.errorMessageBoxCssClass);
                    MagegiantOnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.successMessageBoxCssClass);
                }
            });
        }
    }
};
/*\Intergrate Enterprise*/