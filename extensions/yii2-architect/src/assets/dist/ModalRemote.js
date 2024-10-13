(function ($) {
    $.fn.hasAttr = function (name) {
        return this.attr(name) !== undefined;
    };
}(jQuery));


function ModalRemote(modalId) {

    this.defaults = {
        okLabel: "OK",
        executeLabel: "Выполнить",
        cancelLabel: "Отмена",
        loadingTitle: "Загрузка"
    };

    this.modal = $(modalId);

    this.dialog = $(modalId).find('.modal-dialog');

    this.header = $(modalId).find('.modal-header');

    this.content = $(modalId).find('.modal-body');

    this.footer = $(modalId).find('.modal-footer');

    this.loadingContent = '<div class="progress progress-striped active" style="margin-bottom:0;">' +
        '<div class="progress-bar" style="width: 100%"></div>' +
        '</div>';


    /**
     * Show the modal
     */
    this.show = function () {
        this.clear();
        $(this.modal).modal('show');
    };

    /**
     * Hide the modal
     */
    this.hide = function () {
        $(this.modal).modal('hide');
    };

    /**
     * Toogle show/hide modal
     */
    this.toggle = function () {
        $(this.modal).modal('toggle');
    };

    /**
     * Clear modal
     */
    this.clear = function () {
        $(this.modal).find('.modal-title').remove();
        $(this.content).html("");
        $(this.footer).html("");
    };

    /**
     * Set size of modal
     * @param {string} size large/normal/small
     */
    this.setSize = function (size) {
        $(this.dialog).removeClass('modal-lg');
        $(this.dialog).removeClass('modal-sm');
        if (size === 'large')
            $(this.dialog).addClass('modal-lg');
        else if (size === 'small')
            $(this.dialog).addClass('modal-sm');
        else if (size !== 'normal')
            console.warn("Undefined size " + size);
    };

    /**
     * Set modal header
     * @param {string} content The content of modal header
     */
    this.setHeader = function (content) {
        console.log('content');
        $(this.header).html(content);
    };

    /**
     * @param content
     */
    this.setContent = function (content) {
        $(this.content).html(content);
    };

    /**
     * Set modal footer
     * @param {string} content The content of modal footer
     */
    this.setFooter = function (content) {
        $(this.footer).html(content);
    };

    this.setTitle = function (title) {
        $(this.header).find('h5.modal-title').remove();
        $(this.header).append('<h5 class="modal-title">' + title + '</h5>');
    };

    this.hidenCloseButton = function () {
        $(this.header).find('button.close').hide();
    };

    this.showCloseButton = function () {
        $(this.header).find('button.close').show();
    };

    this.displayLoading = function () {
        this.setContent(this.loadingContent);
        this.setTitle(this.defaults.loadingTitle);
    };

    /**
     * @param label
     * @param type
     * @param classes
     * @param callback
     */
    this.addFooterButton = function (label, type, classes, callback) {
        let buttonElm = document.createElement('button');
        buttonElm.setAttribute('type', type === null ? 'button' : type);
        buttonElm.setAttribute('class', classes === null ? 'btn btn-primary' : classes);
        buttonElm.innerHTML = label;
        let instance = this;
        $(this.footer).append(buttonElm);
        if (callback !== null) {
            $(buttonElm).click(function (event) {
                callback.call(instance, this, event);
            });
        }
    };

    /**
     * Send ajax request and wrapper response to modal
     * @param {string} url The url of request
     * @param {string} method The method of request
     * @param {object}data of request
     */
    this.doRemote = function (url, method, data) {
        let instance = this;
        $.ajax({
            url: url,
            method: method,
            data: data,
            async: false,
            beforeSend: function () {
                beforeRemoteRequest.call(instance);
            },
            error: function (response) {
                errorRemoteResponse.call(instance, response);
            },
            success: function (response) {
                successRemoteResponse.call(instance, response);
            },
            contentType: false,
            cache: false,
            processData: false
        });
    };

    /**
     * Before send request process
     * - Ensure clear and show modal
     * - Show loading state in modal
     */
    function beforeRemoteRequest() {
        this.show();
        this.displayLoading();
    }


    /**
     * When remote sends error response
     * @param {string} response
     */
    function errorRemoteResponse(response) {
        this.setTitle(response.status + response.statusText);
        this.setContent(response.responseText);
        this.addFooterButton(
            'Закрыть',
            'button',
            'btn btn-secondary',
            function (button, event) {
                this.hide();
            }
        );
    }

    /**
     * @param response
     */
    function successRemoteResponse(response) {
        if (response.forceReload !== undefined && response.forceReload) {
            if (response.forceReload === 'true') {
                $.pjax.reload({
                    container: '#js-container-reload'
                });
            } else {
                $.pjax.reload({
                    container: response.forceReload
                });
            }
        }

        // Close modal if response contains forceClose field
        if (response.forceClose !== undefined && response.forceClose) {
            this.hide();
            return;
        }

        if (response.size !== undefined)
            this.setSize(response.size);

        if (response.title !== undefined)
            this.setTitle(response.title);

        if (response.content !== undefined)
            this.setContent(response.content);

        if (response.footer !== undefined)
            this.setFooter(response.footer);

        if ($(this.content).find("form")[0] !== undefined) {
            this.setupFormSubmit(
                $(this.content).find("form")[0],
                $(this.footer).find('[type="submit"]')[0]
            );
        }
    }

    /**
     * Prepare submit button when modal has form
     * @param {string} modalForm
     * @param {object} modalFormSubmitBtn
     */
    this.setupFormSubmit = function (modalForm, modalFormSubmitBtn) {

        if (modalFormSubmitBtn === undefined) {
            console.warn('Modal has form but does not have a submit button');
        } else {
            let instance = this;

            $(modalFormSubmitBtn).click(function (e) {
                let data;

                if (window.FormData) {
                    data = new FormData($(modalForm)[0]);
                } else {
                    data = $(modalForm).serializeArray();
                }

                instance.doRemote(
                    $(modalForm).attr('action'),
                    $(modalForm).hasAttr('method') ? $(modalForm).attr('method') : 'GET',
                    data
                );
            });
        }
    };

    /**
     * Show the confirm dialog
     * @param {string} title The title of modal
     * @param {string} message The message for ask user
     * @param {string} okLabel The label of ok button
     * @param {string} cancelLabel The class of cancel button
     * @param {string} size The size of the modal
     * @param {string} dataUrl Where to post
     * @param {string} dataRequestMethod POST or GET
     * @param {number[]} selectedIds
     */
    this.confirmModal = function (title, message, okLabel, cancelLabel, size, dataUrl, dataRequestMethod, selectedIds) {
        this.show();
        this.setSize(size);

        if (title !== undefined) {
            this.setTitle(title);
        }

        this.setContent('<form id="ModalRemoteConfirmForm">' + message);

        let instance = this;
        this.addFooterButton(
            okLabel === undefined ? this.defaults.okLabel : okLabel,
            'submit',
            'btn btn-primary',
            function (e) {
                let data;
                if (window.FormData) {
                    data = new FormData($('#ModalRemoteConfirmForm')[0]);
                    if (typeof selectedIds !== 'undefined' && selectedIds)
                        data.append('ids', selectedIds.join());
                } else {
                    data = $('#ModalRemoteConfirmForm');
                    if (typeof selectedIds !== 'undefined' && selectedIds)
                        data.pks = selectedIds;
                    data = data.serializeArray();
                }

                instance.doRemote(
                    dataUrl,
                    dataRequestMethod,
                    data
                );
            }
        );

        this.addFooterButton(
            cancelLabel === undefined ? this.defaults.cancelLabel : cancelLabel,
            'button',
            'btn btn-secondary pull-left',
            function (e) {
                this.hide();
            }
        );

    }

    /**
     * Open the modal
     * HTML data attributes for use in local confirm
     *   - href/data-url         (If href not set will get data-url)
     *   - data-request-method   (string GET/POST)
     *   - data-confirm-ok       (string OK button text)
     *   - data-confirm-cancel   (string cancel button text)
     *   - data-confirm-title    (string title of modal box)
     *   - data-confirm-message  (string message in modal box)
     *   - data-modal-size       (string small/normal/large)
     * Attributes for remote response (json)
     *   - forceReload           (string reloads a pjax ID)
     *   - forceClose            (boolean remote close modal)
     *   - size                  (string small/normal/large)
     *   - title                 (string/html title of modal box)
     *   - content               (string/html content in modal box)
     *   - footer                (string/html footer of modal box)
     * @params {elm}
     */
    this.open = function (elm, bulkData) {
        if ($(elm).hasAttr('data-confirm-title') || $(elm).hasAttr('data-confirm-message')) {
            this.confirmModal(
                $(elm).attr('data-confirm-title'),
                $(elm).attr('data-confirm-message'),
                $(elm).attr('data-confirm-ok'),
                $(elm).attr('data-confirm-cancel'),
                $(elm).hasAttr('data-modal-size') ? $(elm).attr('data-modal-size') : 'normal',
                $(elm).hasAttr('href') ? $(elm).attr('href') : $(elm).attr('data-url'),
                $(elm).hasAttr('data-request-method') ? $(elm).attr('data-request-method') : 'GET',
                bulkData
            );
        } else {
            this.doRemote(
                $(elm).hasAttr('href') ? $(elm).attr('href') : $(elm).attr('data-url'),
                $(elm).hasAttr('data-request-method') ? $(elm).attr('data-request-method') : 'GET',
                bulkData
            );
        }
    }
}
