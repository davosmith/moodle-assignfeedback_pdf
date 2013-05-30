/*global M,YUI*/
YUI.add('moodle-assignfeedback_pdf-menubutton', function (Y) {
    "use strict";

    var MENUBUTTONNAME = 'pdf_menubutton';
    var MENUBUTTON = function() {
        MENUBUTTON.superclass.constructor.apply(this, arguments);
    };

    Y.extend(MENUBUTTON, Y.Base, {
        button: null,
        menu: null,
        isimage: false,

        initializer: function (params) {
            var parent;

            this.button = Y.Plugin.Button.createNode(params.button);
            this.menu = Y.one(params.menu);
            this.isimage = params.isimage;

            this.button.addClass('menubutton');
            parent = this.menu.ancestor();
            parent.removeChild(this.menu);
            Y.one('body').appendChild(this.menu);

            this.hide_menu();
            this.button.after('click', function (e) {
                if (this.menu.getStyle('display') === 'none') {
                    this.show_menu();
                }
            }, this);
            this.menu.all('li.yuimenuitem').on('click', this.select_item, this);
        },

        hide_menu: function () {
            this.menu.setStyle('display', 'none');
        },

        show_menu: function () {
            var self;
            this.position_menu();
            this.menu.setStyle('display', 'block');
            self = this;
            window.setTimeout(function () {
                Y.one('body').once('click', function (e) {
                    self.hide_menu();
                }, self);
            }, 200);
        },

        position_menu: function () {
            var x, y;

            x = this.button.getX();
            y = this.button.getY() + parseInt(this.button.getComputedStyle('height'), 10);
            y += parseInt(this.button.getComputedStyle('paddingTop'), 10);
            y += parseInt(this.button.getComputedStyle('paddingBottom'), 10);

            this.menu.setStyle('left', x + 'px');
            this.menu.setStyle('top', y + 'px');
        },

        select_item: function (e) {
            var value, src, content;
            value = e.currentTarget.getAttribute('value');
            this.button.set('value', value);
            if (this.isimage) {
                src = e.currentTarget.one('img').get('src');
                if (src) {
                    this.button.set('src', src);
                }
            } else {
                content = e.currentTarget.getContent();
                this.button.setContent(content);
            }
            this.fire('selectionChanged', {target: e.currentTarget, value: value});
        },

        set: function (attr, value) {
            return this.button.set(attr, value);
        },

        get: function (attr) {
            return this.button.get(attr);
        }
    });

    M.assignfeedback_pdf = M.assignfeedback_pdf || {};
    M.assignfeedback_pdf.menubutton = MENUBUTTON;

}, '@VERSION@', {
    requires: ['base', 'node', 'button-plugin']
});
