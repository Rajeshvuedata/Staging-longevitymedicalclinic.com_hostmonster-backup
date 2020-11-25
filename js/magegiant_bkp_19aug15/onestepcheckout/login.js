MagegiantOnestepcheckoutLogin = Class.create();
MagegiantOnestepcheckoutLogin.prototype = {
    initialize: function (config) {
        var me = this;
        this.loginLink = $$(config.loginLink).first();
        this.container = $$(config.containerSelector).first();
        this.contentLogin = $$(config.contentLogin).first();
        this.contentForgotPassword = $$(config.contentForgotPassword).first();
        this.forgotPasswordLinkArray = $$(config.forgotPasswordLinkSelector);
        this.backToLoginLinkArray = $$(config.backToLoginLinkSelector);
        this.loginForm = $$(config.loginFormSelector).first();
        this.forgotPasswordForm = $$(config.forgotPasswordFormSelector).first();
        this.forgotPasswordSuccessBlock = $$(config.forgotPasswordSuccessBlockSelector).first();
        this.errorMessageBoxCssClass = config.errorMessageBoxCssClass;
        this.overlayConfig = config.overlayConfig;
        this.jsErrorMsg = config.jsErrorMsg;

        this.forgotPasswordLinkArray.each(function (link) {
            link.observe('click', me.onClickOnForgotPasswordLink.bind(me));
        });
        this.backToLoginLinkArray.each(function (link) {
            link.observe('click', me.onClickOnBackToLoginLink.bind(me));
        });

        this.loginForm = new VarienForm(this.loginForm);
        this.forgotPasswordForm = new VarienForm(this.forgotPasswordForm);

        this.loginForm.form.select('button[type=submit]').each(function (btn) {
            btn.observe('click', me.onLoginFormSubmit.bind(me));
        });
        this.forgotPasswordForm.form.select('button[type=submit]').each(function (btn) {
            btn.observe('click', me.onForgotPasswordFormSubmit.bind(me));
        });
        this.currentVisibleBlock = this.loginForm.form;
//        this.initContainerHeight();

        this.container.select('input, button, a').each(function (el) {
            el.setAttribute('tabindex', '-1');
        });

        this.currentVisibleBlock.select('input, button, a').each(function (el) {
            el.removeAttribute('tabindex');
        });
        this.popup = new MagegiantOnestepcheckoutUIPopup(config.popup);
        this.initObservers();
    },
    initObservers: function () {
        var me = this;
        if (this.loginLink) {
            this.loginLink.observe('click', function (e) {
                me.popup.showPopup();
            });
        }
    },
    initContainerHeight: function () {
        var height = this.currentVisibleBlock.getHeight();
        this.container.setStyle({'height': height + 'px'});
    },

    moveToBlock: function (block, forward) {
        var me = this;
        var block = $(block);
        this.initContainerHeight();
        if (forward) {
            this.container.insertBefore(block, me.currentVisibleBlock.next());
        } else {
            this.container.insertBefore(block, me.currentVisibleBlock);
            me.container.setStyle({top: '-' + block.getHeight() + 'px'});
        }
        var newHeight = block.getHeight();
        var newTop = 0;
        if (forward) {
            newTop = this.currentVisibleBlock.getHeight() * (-1);
        }
        new Effect.Morph(this.container, {
            style: {
                height: newHeight + 'px',
                top: newTop + 'px'
            },
            duration: 0.6,
            afterFinish: function (e) {
                me.container.insertBefore(block, me.container.down());
                me.container.setStyle({top: '0px'});
                me.currentVisibleBlock = block;

                me.container.select('input, button, a').each(function (el) {
                    el.setAttribute('tabindex', '-1');
                });
                block.select('input, button, a').each(function (el) {
                    el.removeAttribute('tabindex');
                });
            }
        });

    },

    addLoader: function () {
        MagegiantOnestepcheckoutCore.addLoaderOnBlock(this.container, this.overlayConfig);
    },

    removeLoader: function () {
        MagegiantOnestepcheckoutCore.removeLoaderFromBlock(this.container, this.overlayConfig);
    },

    showError: function (msg, afterShowFn) {
        MagegiantOnestepcheckoutCore.showMsg(msg, this.errorMessageBoxCssClass, this.currentVisibleBlock);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        afterShowFn();
    },

    removeErrorOnBlock: function (block) {
        MagegiantOnestepcheckoutCore.removeMsgFromBlock(block, this.errorMessageBoxCssClass);
    },

    onClickOnForgotPasswordLink: function (e) {
        this.contentLogin.hide();
        this.contentForgotPassword.show();
    },

    onClickOnBackToLoginLink: function (e) {
        this.contentLogin.show();
        this.contentForgotPassword.hide();
    },

    onLoginFormSubmit: function (e) {
        //remove old advices
        this.loginForm.form.select('.validation-advice').each(function (adviceEl) {
            adviceEl.remove();
        });
        if (this.loginForm.validator.validate()) {
            this.addLoader();
            this.loginForm.form.setStyle({
                paddingBottom: '0px'
            });
            this.removeErrorOnBlock(this.loginForm.form);
            new Ajax.Request(this.loginForm.form.getAttribute('action'), {
                method: 'post',
                parameters: giantOSCLoginBlock.loginForm.form.serialize(true),
                onComplete: this._onAjaxLoginCompleteFn.bind(this)
            });
        } else {
            //change container height after show validation-advice
            var maxValidationAdviceHeight = 0;
            this.loginForm.form.select('.validation-advice').each(function (adviceEl) {
                if (adviceEl.getHeight() > maxValidationAdviceHeight) {
                    maxValidationAdviceHeight = adviceEl.getHeight();
                }
            });

            var newContainerHeight = 0;
            var currentFormPaddingBottom = parseInt(this.loginForm.form.getStyle('paddingBottom'));
            if (currentFormPaddingBottom < maxValidationAdviceHeight) {
                this.loginForm.form.setStyle({
                    paddingBottom: maxValidationAdviceHeight + 'px'
                });
                newContainerHeight = this.loginForm.form.getHeight()
            } else {
                newContainerHeight = this.loginForm.form.getHeight() - currentFormPaddingBottom + maxValidationAdviceHeight;
            }

            var me = this;
            new Effect.Morph(this.container, {
                style: {
                    height: newContainerHeight + 'px'
                },
                duration: 0.6,
                afterFinish: function () {
                    me.loginForm.form.setStyle({
                        paddingBottom: maxValidationAdviceHeight + 'px'
                    });
                }
            });
        }
        e.stop();
    },

    onForgotPasswordFormSubmit: function (e) {
        //remove old advices
        this.forgotPasswordForm.form.select('.validation-advice').each(function (adviceEl) {
            adviceEl.remove();
        });
        if (this.forgotPasswordForm.validator.validate()) {
            this.addLoader();
            this.forgotPasswordForm.form.setStyle({
                paddingBottom: '0px'
            });
            this.removeErrorOnBlock(this.forgotPasswordForm.form);
            new Ajax.Request(this.forgotPasswordForm.form.getAttribute('action'), {
                method: 'post',
                parameters: giantOSCLoginBlock.forgotPasswordForm.form.serialize(true),
                onComplete: this._onAjaxForgotPasswordCompleteFn.bind(this)
            });
        } else {
            //change container height after show validation-advice
            var maxValidationAdviceHeight = 0;
            this.forgotPasswordForm.form.select('.validation-advice').each(function (adviceEl) {
                if (adviceEl.getHeight() > maxValidationAdviceHeight) {
                    maxValidationAdviceHeight = adviceEl.getHeight();
                }
            });

            var newContainerHeight = 0;
            var currentFormPaddingBottom = parseInt(this.forgotPasswordForm.form.getStyle('paddingBottom'));
            if (currentFormPaddingBottom < maxValidationAdviceHeight) {
                this.forgotPasswordForm.form.setStyle({
                    paddingBottom: maxValidationAdviceHeight + 'px'
                });
                newContainerHeight = this.forgotPasswordForm.form.getHeight()
            } else {
                newContainerHeight = this.forgotPasswordForm.form.getHeight() - currentFormPaddingBottom + maxValidationAdviceHeight;
            }

            var me = this;
            new Effect.Morph(this.container, {
                style: {
                    height: newContainerHeight + 'px'
                },
                duration: 0.6,
                afterFinish: function () {
                    me.forgotPasswordForm.form.setStyle({
                        paddingBottom: maxValidationAdviceHeight + 'px'
                    });
                }
            });
        }
        e.stop();
    },

    _onAjaxLoginCompleteFn: function (transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch (e) {
            this.showError(this.jsErrorMsg, this.removeLoader.bind(this));
            return;
        }
        if (json.success) {
            document.location.reload();
        } else {
            if (json.redirect_to) {
                document.location.href = json.redirect_to;
                return;
            }
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg, this.removeLoader.bind(this));
        }
    },

    _onAjaxForgotPasswordCompleteFn: function (transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch (e) {
            this.showError(this.jsErrorMsg, this.removeLoader.bind(this));
            return;
        }
        if (json.success) {
            this.removeLoader();
            this.moveToBlock(this.forgotPasswordSuccessBlock, true);
        } else {
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg, this.removeLoader.bind(this));
        }
    }
};