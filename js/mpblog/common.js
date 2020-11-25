/**
 * MagPleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * MagPleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   MagPleasure
 * @package    Magpleasure_Blog
 * @version    1.2.3
 * @copyright  Copyright (c) 2012-2013 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

/** Drop Down Dialog */
var MpBlogCommonSelectDialog = Class.create();
MpBlogCommonSelectDialog.prototype = {
    initialize: function (params) {
        for (key in params) {
            this[key] = params[key];
        }

        this.expanded = false;
        this.timer = null;

        $(this.button_id).observe('click', (function(e){
            var id = this.button_id;
            if (!this.expanded){
                this.showDialog();
            } else {
                this.hideDialog();
            }
        }).bind(this));

        $$(this.element_selector).each((function(el){
            $(el).observe('click', (function(e){
                $(this.button_id).removeClassName(this.expanded_class);
                this.hideDialog();
            }).bind(this));
        }).bind(this));

        $(this.dropdown_id).observe('mouseover', (function(e){
            this.removeTimer();
        }).bind(this));

        $(this.dropdown_id).observe('mouseout', (function(e){
            if (this.expanded){
                this.setTimer();
            }
        }).bind(this));

        $(this.button_id).observe('mouseover', (function(e){
            this.removeTimer();
        }).bind(this));

        $(this.button_id).observe('mouseout', (function(e){
            if (this.expanded){
                this.setTimer();
            }
        }).bind(this));

    },
    showDialog: function(){
        Effect.Appear(this.dropdown_id, {duration: this.appear_time, afterFinish: function(){
            if ((this.onShow) !== undefined){
                this.onShow();
            }
        }});
        this.expanded = true;
        $(this.button_id).addClassName(this.expanded_class);
    },
    hideDialog: function(){
        this.expanded = false;
        $(this.button_id).removeClassName(this.expanded_class);

        Effect.Fade(this.dropdown_id, {duration: this.hide_time, afterFinish: function(){
            if ((this.onHide) !== undefined){
                this.onHide();
            }
        }});
    },
    setTimer: function(){
        this.timer = setTimeout((function(e){
            this.hideDialog();
        }).bind(this), this.timeout);

    },
    removeTimer: function(){
        if (this.timer){
            clearTimeout(this.timer);
            this.timer = null;
        }
    }
};