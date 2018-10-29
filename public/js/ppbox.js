class PPbox {
    constructor() {
    }

    static redirect(url) {
        window.location.href = url;
    }

    static close(id) {
        this._closeDialog(id);
    }

    static dialog(type, id, title, content, options) {
        if (this.dialogs === undefined) {
            this.dialogs = {};
        }
        
        if (title === undefined) {
            if (type === 'confirm') {
                title = Translator.trans('generic.diag_confirm.title');
            } else if (type === 'alert') {
                title = Translator.trans('generic.diag_alert.title');
            }
        }

        if (content === undefined) {
            content = Translator.trans('generic.loading.message');
        }

        if (this.dialogs[id] === undefined) {
            this._createDialog(type, id, title, content, options);
        } else {
            this._openDialog(type, id, title, content, options);
        }
    }

    static form(id, title, url, params, options) {
        PPbox.dialog('form', id, title);
        $.get(url, params).done(function (content) {
            for (let i in options.buttons) {
                if (!options.buttons.hasOwnProperty(i)) {
                    continue;
                }
                if (options.buttons[i].success === undefined) {
                    continue;
                }

                let callback_submit = options.buttons[i].callback_submit;

                options.buttons[i].click = function () {
                    let form = $('form'),
                        form_serialize = form.serializeArray(),
                        form_name = form.attr('name'),
                        regex = /([_A-Za-z]+)(\[)([_A-Za-z]+)(\])/i,
                        field_name, correspondance, form_data = {};

                    form_data[form_name] = {};
                    for (let i in form_serialize) {
                        if (!form_serialize.hasOwnProperty(i)) {
                            continue;
                        }
                        correspondance = regex.exec(form_serialize[i]['name']);
                        if (correspondance) {
                            field_name = correspondance[3];
                            form_data[form_name][field_name] = form_serialize[i]['value'];
                        }
                    }

                    $.post(url, form_data).done(function (data) {
                        if (data.success) {
                            callback_submit(data);
                            return;
                        } else {
                            PPbox.refreshContent(id, data);
                        }
                    });
                };

                delete options.buttons[i].callback;
                delete options.buttons[i].callback_submit;
            }

            PPbox.dialog('form', id, title, content, options);
            bind();
        });
    }

    static refreshContent(id, content) {
        $('#ppbox' + id).html(content);
    }

    static confirm(id, title, content, options) {
        this.dialog('confirm', id, title, content, options);
    }

    static alert(id, title, content, options) {
        this.dialog('alert', id, title, content, options);
    }

    static _openDialog(type, id, title, content, options) {
        let d = $('#ppbox' + id);
        d.data('title', title);
        d.html(content);
        options = this._parseOptions(type, id, options);
        d.dialog(options);
    }

    static _closeDialog(id) {
        $('#ppbox' + id).dialog('close');
    }

    static _createDialog(type, id, title, content, options) {
        if (this.dialogs === undefined) {
            this.dialogs = {};
        }
        this.dialogs[id] = true;

        options = this._parseOptions(type, id, options);

        $(document.body).append('<div id="ppbox' + id + '" title="' + title + '">' + content + '</div>');
        $('#ppbox' + id).dialog(options);
    }

    static _parseOptions(type, id, options) {
        if (options === undefined) {
            options = {};
        }
        if (options.closeOnEscape === undefined) {
            options.closeOnEscape = true;
        }
        if (options.buttons === undefined) {
            options.buttons = {};
        }
        if (options.classes === undefined) {
            options.classes = {};
        }
        options.buttons = this._parseOptionsButtons(type, id, options.buttons);
        options.classes = this._parseOptionsClasses(type, options.classes, options.theme, options.size);

        return options;
    }

    static _parseOptionsClasses(type, classes, theme, size) {
        if (theme === undefined) {
            if (type === 'confirm') {
                theme = 'danger';
            } else if (type === 'alert') {
                theme = 'warning';
            } else {
                theme = 'default';
            }
        }

        if (size === undefined) {
            size = 'sm';
        }

        classes = {
            "ui-dialog": ("ui-corner-all ui-dialog-" + theme + " ui-dialog-" + size),
            "ui-dialog-titlebar": ("ui-corner-all ui-dialog-titlebar-" + theme + " ui-dialog-titlebar-" + size),
        };

        return classes;
    }

    static _parseOptionsButtons(type, id, buttons) {
        for (let i in buttons) {
            if (!buttons.hasOwnProperty(i)) {
                continue;
            }

            if (buttons[i].route !== undefined) {
                if (buttons[i].route_params === undefined) {
                    buttons[i].route_params = {};
                }
                let url = Routing.generate(buttons[i].route, buttons[i].route_params);

                buttons[i].click = function () {
                    PPbox.redirect(url);
                };
            } else if (buttons[i].url !== undefined) {
                buttons[i].click = function () {
                    PPbox.redirect(buttons[i].url);
                };
            } else if (buttons[i].callback !== undefined) {
            } else if (buttons[i].success !== undefined) {
            } else {
                buttons[i].click = function () {
                    PPbox._closeDialog(id);
                }
            }

            if (buttons[i].class === undefined) {
                if (parseInt(i) === 0) {
                    buttons[i].class = 'btn btn-dark';
                } else {
                    buttons[i].class = 'btn btn-outline-dark';
                }
            }
        }

        return buttons;
    }

    static processInline(type, config, params) {
        let id = config.data('ppbox-id'),
            title = config.data('ppbox-title'),
            content = config.data('ppbox-content'),
            options = {};

        options.theme = config.data('ppbox-theme');
        options.size = config.data('ppbox-size');
        options.buttons = config.data('ppbox-buttons');

        if (params !== undefined) {
            for (let i in options.buttons) {
                if (!options.buttons.hasOwnProperty(i)) {
                    continue;
                }
                if (options.buttons[i].route === undefined) {
                    continue;
                }

                for (let key in params) {
                    if (!params.hasOwnProperty(key)) {
                        continue;
                    }

                    options.buttons[i].route_params[key] = params[key];
                }
            }
        }

        if (type === 'confirm') {
            this.confirm(id, title, content, options);
        } else if (type === 'alert') {
            this.alert(id, title, content, options);
        }
    }

    static init() {
        let ppboxconfirm = $('.ppboxconfirm'),
            ppboxalert = $('.ppboxalert');

        ppboxconfirm.on('click', function () {
            PPbox.processInline('confirm', $(this));
        });

        ppboxalert.on('click', function () {
            PPbox.processInline('alert', $(this));
        });
    }

}
