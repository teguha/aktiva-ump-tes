/*
 * BASE FORM
 */
const BaseForm = function () {

    return {
        submit: function (form, options = {}) {
            var defaultOptions = {
                    btnSubmit: null,
                    btnBack: null,
                    loaderModal: true,
                    hideModal: true,
                    drawDataTable: true,
                    refreshSidebarBadge: true,
                    scrollTop: false,
                    swalSuccess: false,
                    swalError: false,
                    swalSuccessTimer: '3000',
                    swalSuccessButton: {},
                    swalErrorTimer: '',
                    swalErrorButton: 'OK',
                    redirectTo: false,
                    fullCallbackSuccess: false,
                    fullCallbackError: false,
                    callbackSuccess: function(resp, form, options) {},
                    callbackError: function(resp, form, options) {}
                },
                options = $.extend(defaultOptions, options);

            var formLoading = setTimeout(function(){
                if (options.loaderModal && form.closest('.modal').length) {
                    BaseModal.loader('#'+form.closest('.modal').attr('id'), true);
                }
                else {
                    BaseContent.loader(true);
                }
            }, 700);

            if (options.btnSubmit !== null && options.btnSubmit.length) {
                options.btnSubmit.prop('disabled', true)
                    .prepend('<i class="fas fa-spinner fa-spin btn-loader m-r-3"></i> ');
            }

            form.ajaxSubmit({
                success: function (resp) {
                    clearTimeout(formLoading);
                    if (options.fullCallbackSuccess === true) {
                        if ($.isFunction(options.callbackSuccess)) {
                            options.callbackSuccess(resp, form, options);
                        }
                    }
                    else {
                        BaseForm.validationMessages(resp, form);
                        $.gritter.add({
                            title: resp.title != undefined ? resp.title : 'Success!',
                            text: resp.message != undefined ? resp.message : 'Data saved successfull!',
                            image: BaseUtil.getUrl('assets/media/ui/check.png'),
                            sticky: false,
                            time: '3000'
                        });
                        BaseForm.defaultCallbackSuccess(resp, form, options);

                    }
                },
                error: function (resp) {
                    clearTimeout(formLoading);
                    if (options.fullCallbackError === true) {
                        if ($.isFunction(options.callbackError)) {
                            options.callbackError(resp, form, options);
                        }
                    }
                    else {
                        resp = resp.responseJSON;
                        BaseForm.validationMessages(resp, form);
                        BaseForm.defaultCallbackError(resp, form, options);
                        if (resp.alert !== undefined) {
                            form.find('.base-alert').remove();
                            form.prepend(`
                                <div class="alert alert-`+resp.alert+` fade show base-alert">
                                    <span class="close" data-dismiss="alert">×</span>
                                    `+resp.message+`
                                </div>
                            `).hide().fadeIn(700);
                        }
                        if (options.swalError) {
                            Swal.fire({
                                title: resp.title != undefined ? resp.title : 'Failed!',
                                text: resp.message != undefined ? resp.message : 'Data failed to save!',
                                icon: 'error',
                                timer: options.swalErrorTimer,
                                confirmButtonText: options.swalErrorButton,
                            });
                        }
                        else {
                            $.gritter.add({
                                title: resp.title != undefined ? resp.title : 'Failed!',
                                text: resp.message != undefined ? resp.message : 'Data failed to save!',
                                image: BaseUtil.getUrl('assets/media/ui/cross.png'),
                                sticky: false,
                                time: 3000
                            });
                        }
                    }
                }
            });
        },
        validationMessages: function (resp, form) {
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.is-invalid-message').remove();
            form.find('.is-invalid-alert').remove();
            if (resp.message == 'The given data was invalid.') {
                if (BaseUtil.getLang() == 'id') {
                    resp.message = 'Data yang Anda masukkan tidak valid.';
                }
                $.each(resp.errors, function (name, messages) {
                    var names = name.split('.'),
                        name  = names.reduce((all, item) => {
                                    all += (name == 0 ? item : '[' + item + ']');
                                    return all;
                                }),
                        field = form.find('[name^="'+ names[0] +'[]"], [name="'+ name +'"], [name="'+ name +'[]"]'),
                        parentGroup = field.closest('.parent-group').length ? field.closest('.parent-group') : field.closest('.form-group');

                    field.addClass('is-invalid');
                    field.closest('.bootstrap-select').addClass('is-invalid');
                    $.each(messages, function (i, message) {
                        parentGroup.append('<p class="is-invalid-message text-danger my-1">'+message+'</p>');
                    });
                });
                $('.is-invalid-message').hide().fadeIn(500);
            }
            else if (BaseUtil.isDev()) {
                if(resp.exception !== undefined && resp.file !== undefined && resp.line !== undefined && resp.message !== undefined) {
                    BaseModal.render('body', {
                        modal_id: '#alert-modal',
                        modal_size: 'modal-lg',
                        modal_bg: 'bg-light-danger',
                        callback: function (options, modalLoadingTimer) {
                            clearTimeout(modalLoadingTimer);
                            $(options.modal_id+' .modal-content').html(`
                                <div class="modal-header">
                                    <h4 class="modal-title">Failed!</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body py-5">
                                    <div class="alert alert-danger">
                                        Terjadi kesalahan!
                                        <p mt-5><small>*Pesan ini hanya akan ditampilkan pada masa development</small></p>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td class="width-80">File : </td>
                                                    <td>`+resp.file+`</td>
                                                </tr>
                                                <tr>
                                                    <td class="width-80">Line : </td>
                                                    <td>`+resp.line+`</td>
                                                </tr>
                                                <tr>
                                                    <td class="width-80">Message : </td>
                                                    <td>`+resp.message+`</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
                                </div>
                            `);

                            if (!$(options.modal_id).hasClass('show')) {
                                $(options.modal_id).modal('show');
                                BaseModal.loader(options.modal_id, false);
                            }
                        }
                    });
                }
                else if($.type( resp.errors ) === "string") {
                    var alerParent = form.find('.modal-body').length ? form.find('.modal-body').first() : form;
                    alerParent.prepend(`
                        <div class="alert alert-danger fade show is-invalid-alert">
                            <span class="close alert-dismiss" data-dismiss="alert">×</span>
                            `+resp.errors+`
                            <br/>
                            <p><small>*Pesan ini hanya akan ditampilkan pada masa development</small></p>
                        </div>
                    `).hide().fadeIn(700);
                }
            }
        },
        defaultCallbackSuccess: function (resp, form, options = {}) {
            if (resp.redirectToModal) {
                BaseModal.render(options.btnSubmit, {
                    modal_id: '#modal-submit',
                    modal_size: 'modal-md',
                    modal_position: 'default',
                    modal_url: resp.redirectToModal,
                });
                BaseContent.loader(false);
                options.btnSubmit.prop('disabled', false).find('.btn-loader').remove();
                return false;
            }
            if (resp.redirect) {
                BaseContent.replaceByUrl(resp.redirect);
                return false;
            }
            if (resp.redirectTo) {
                // BaseUtil.redirect(resp.redirectTo);
                BaseContent.replaceByUrl(resp.redirectTo.replace(location.origin, ''));
                return false;
            }
            if (options.btnBack !== null && $(options.btnBack).length) {
                BaseContent.replace(options.btnBack);
                return false;
            }
            if (options.btnSubmit !== null && options.btnSubmit.length) {
                options.btnSubmit.prop('disabled', false)
                        .find('.btn-loader').remove();
            }
            if (options.loaderModal && form.closest('.modal').length) {
                BaseModal.loader('#'+form.closest('.modal').attr('id'), false);
            } else {
                BaseContent.loader(false);
            }
            if (options.hideModal && form.closest('.modal').length) {
                form.closest('.modal').modal('hide');
            }
            if (options.drawDataTable) {
                BaseList.draw();
            }
            if ($.isFunction(options.callbackSuccess)) {
                options.callbackSuccess(resp, form, options);
            }
            if (options.scrollTop) {
                $('body,html').animate({scrollTop: '5px'}, 500).animate({scrollTop: 0}, 800);
            }
        },
        defaultCallbackError: function (resp, form, options = {}) {
            if (options.btnSubmit !== null && options.btnSubmit.length) {
                options.btnSubmit.prop('disabled', false)
                        .find('.btn-loader').remove();
            }
            if (options.loaderModal && form.closest('.modal').length) {
                BaseModal.loader('#'+form.closest('.modal').attr('id'), false);
            } else {
                BaseContent.loader(false);
            }
            if ($.isFunction(options.callbackError)) {
                options.callbackError(resp, form);
            }
            var firstError = form.find('.is-invalid').first();
            if (firstError.length && form.closest('.modal').length == 0) {
                $('body,html').animate({scrollTop: firstError.position().top}, 500);
            }
        },
        delete: function (url, options = {}) {
            var defaultOptions = {
                    swalSuccess: false,
                    swalError: false,
                },
                options = $.extend(defaultOptions, options);

            var contentLoading = setTimeout(function () {
                BaseContent.loader(true);
            }, 700);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: BaseUtil.getToken(),
                    _method: 'DELETE'
                },
                success: function (resp) {
                    clearTimeout(contentLoading);
                    BaseContent.loader(false);
                    if (options.swalSuccess) {
                        Swal.fire({
                            title: resp.title != undefined ? resp.title : 'Success!',
                            text: resp.message != undefined ? resp.message : 'Data deleted successfully!',
                            icon: 'success',
                            timer: 3000,
                        });
                    }
                    else {
                        $.gritter.add({
                            title: resp.title != undefined ? resp.title : 'Success!',
                            text: resp.message != undefined ? resp.message : 'Data deleted successfully!',
                            image: BaseUtil.getUrl('assets/media/ui/check.png'),
                            sticky: false,
                            time: '3000'
                        });
                    }
                    BaseList.draw();
                },
                error: function (resp) {
                    clearTimeout(contentLoading);
                    BaseContent.loader(false);
                    if (options.swalError) {
                        Swal.fire({
                            title: resp.title != undefined ? resp.title : 'Failed!',
                            text: resp.message != undefined ? resp.message : 'Data failed to delete!',
                            icon: 'error',
                            // timer: 3000,
                        });
                    }
                    else {
                        $.gritter.add({
                            title: resp.title != undefined ? resp.title : 'Failed!',
                            text: resp.message != undefined ? resp.message : 'Data failed to delete!',
                            image: BaseUtil.getUrl('assets/media/ui/cross.png'),
                            sticky: false,
                            time: '3000'
                        });
                    }
                }
            });
        },
        activate: function (url, status, options = {}) {
            var defaultOptions = {
                    swalSuccess: false,
                    swalError: false,
                },
                options = $.extend(defaultOptions, options);

            var contentLoading = setTimeout(function () {
                BaseContent.loader(true);
            }, 700);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: BaseUtil.getToken(),
                    _method: 'POST',
                    status: status == 1 ? 0 : 1,
                },
                success: function (resp) {
                    clearTimeout(contentLoading);
                    BaseContent.loader(false);
                    if (options.swalSuccess) {
                        Swal.fire({
                            title: resp.title != undefined ? resp.title : 'Success!',
                            text: resp.message != undefined ? resp.message : 'Data has been activated successfully!',
                            icon: 'success',
                            timer: 3000,
                        });
                    }
                    else {
                        $.gritter.add({
                            title: resp.title != undefined ? resp.title : 'Success!',
                            text: resp.message != undefined ? resp.message : 'Data has been activated successfully!',
                            image: BaseUtil.getUrl('assets/media/ui/check.png'),
                            sticky: false,
                            time: '3000'
                        });
                    }
                    BaseList.draw();
                    BaseUtil.sidebarBadge();

                },
                error: function (resp) {
                    clearTimeout(contentLoading);
                    BaseContent.loader(false);
                    if (options.swalError) {
                        Swal.fire({
                            title: resp.title != undefined ? resp.title : 'Failed!',
                            text: resp.message != undefined ? resp.message : 'Data failed to activation!',
                            icon: 'error',
                        });
                    }
                    else {
                        $.gritter.add({
                            title: resp.title != undefined ? resp.title : 'Failed!',
                            text: resp.message != undefined ? resp.message : 'Data failed to activation!',
                            image: BaseUtil.getUrl('assets/media/ui/cross.png'),
                            sticky: false,
                            time: '3000'
                        });
                    }
                }
            });
        },
        saveTempFiles: function (el, event, options=[]) {
            var el = $(el),
                form = el.closest('form'),
                files = event.target.files;

            var defaultOptions = {
                    parentClass: el.data('container') ?? 'form-group',
                    maxFile: el.data('max-file') ?? 1, //1:Singgle, 2,...:Multiple
                    maxSize: el.data('max-size') ?? 5024, //5mb
                    type: el.data('container') ?? null,
                    callbackSuccess: false,
                    callbackError: false,
                },
                options = $.extend(defaultOptions, options);

            var parent = el.closest('.'+options.parentClass);
            if (!parent.length) {
                parent = el.closest('.custom-file').parent();
            }
            if ((parent.find('.progress-container:not(.error-uploaded)').length >= options.maxFile) || (files.length > options.maxFile)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Opps',
                    text: 'Maximal File ' + options.maxFile,
                    showConfirmButton: false,
                    // timer: 1500
                });
                el.val("");
                el.parent().find('.custom-file-label').text('Choose file');
                return false;
            }

            if (files.length) {
                var filesTooBig = [];
                $.each(files, function (index, file) {
                    if (file && file.size && (Math.round((file.size / 1024)) >= options.maxSize)) {
                        filesTooBig.push(file);
                    }
                });
                if (filesTooBig.length) {
                    var showSize = function (bytes) {
                        if (bytes === 0) {
                            return '0 Bytes';
                        }
                        else {
                            var k = 1024;
                            var dm = 2;
                            var sizes = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                            var i = Math.floor(Math.log(bytes) / (Math.log(k)));
                            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                        }
                    }

                    var fileNames = '<ul class="pl-20px text-left">';
                    $.each(filesTooBig, function (index, file) {
                        fileNames = fileNames + '<li>' + file.name + ' (' + (showSize(file.size / 1024)) + ')' + ' </li>';
                    })
                    fileNames = fileNames + '</ul>';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Opps',
                        html: 'Maximum File Size is ' + showSize(options.maxSize) + '<br>Please check file fize:' + fileNames,
                        confirmButtonText: 'OKE',
                    });
                    if (parent.find('.success-uploaded').length == 0) {
                        el.val("");
                        parent.find('.custom-file-label').text('Choose file');
                    }
                }
                else {
                    $.each(files, function (index, file) {
                        if (file && file.size) {
                            var fmData = new FormData();
                            var uniqueid = Math.floor(Math.random() * 26) + Date.now();
                            fmData.append('_token', BaseUtil.getToken());
                            fmData.append('file', file);
                            fmData.append('type', options.type ? options.type : null);
                            fmData.append('uniqueid', uniqueid);

                            parent.find('.custom-file-label').text(files.length + ' Files Attached');

                            $.ajax({
                                url: BaseUtil.getUrl('ajax/saveTempFiles')+'?accept='+el.attr('accept'),
                                type: 'POST',
                                data: fmData,
                                contentType: false,
                                processData: false,
                                query: {
                                    accept: el.attr('accept')
                                },
                                // async: false,
                                beforeSend: function (e) {
                                    parent.append(`
                                        <div class="progress-container w-100" data-uid="` + uniqueid + `">
                                            <div class="progress uploading mt-2">
                                                <div class="progress-bar bar-` + uniqueid +` progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div>
                                            </div>
                                        </div>
                                    `);
                                },
                                xhr: function (resp) {
                                    var xhr = new window.XMLHttpRequest();
                                    xhr.upload.addEventListener("progress", function (evt) {
                                        if (evt.lengthComputable) {
                                            var percentComplete = parseInt((evt.loaded / evt.total) * 100);
                                            form.find('button[type="submit"]').attr('disabled', 'disabled');

                                            form.find('.progress-bar.bar-' + uniqueid)
                                                .attr('aria-valuenow', percentComplete)
                                                .css('width', percentComplete + '%')
                                                .text(percentComplete + '%');

                                        }
                                    }, false);

                                    return xhr;
                                },
                                success: function (resp) {
                                    if ($.isFunction(options.callbackSuccess)){
                                        options.callback(resp, el, uniqueid);
                                    }
                                    var icon = 'far fa-file-alt';
                                    if (resp.file.file_type == 'pdf') {
                                        icon = 'text-danger far fa-file-pdf';
                                    }
                                    else if(resp.file.file_type == 'xlsx') {
                                        icon = 'text-success far fa-file-excel';
                                    }
                                    else if(resp.file.file_type == 'jpg' || resp.file.file_type == 'png') {
                                        icon = 'text-warning far fa-file-image';
                                    }
                                    else if(resp.file.file_type == 'ppt') {
                                        icon = 'text-danger far fa-file-powerpoint';
                                    }
                                    else if(resp.file.file_type == 'docx') {
                                        icon = 'text-primary far fa-file-word';
                                    }
                                    parent.find('.uploaded').val(1);
                                    parent.find('.progress-container[data-uid="'+uniqueid+'"]')
                                        .prepend(`
                                            <div class="alert alert-custom alert-light fade show py-2 px-4 mb-0 mt-2 success-uploaded" role="alert">
                                                <div class="alert-icon">
                                                    <i class="`+icon+`"></i>
                                                </div>
                                                <div class="alert-text text-left">
                                                    <input type="hidden" name="`+el.data('name')+`[temp_files_ids][]" value="`+ resp.file.id +`">
                                                    <div>Upload File:</div>
                                                    <a href="`+ resp.file.file_url +`" target="_blank" class="text-primary">`+ resp.file.file_name +`</a>
                                                </div>
                                                <div class="alert-close">
                                                    <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip" title="Remove">
                                                        <span aria-hidden="true">
                                                            <i class="ki ki-close"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        `);
                                    parent.find('.progress-container[data-uid="'+uniqueid+'"] .progress')
                                        .removeClass('mt-2');
                                    form.find('button[type="submit"]').removeAttr('disabled');
                                    form.find('.progress-bar.bar-' + uniqueid)
                                        .removeClass('progress-bar-striped')
                                        .text('Done');

                                    // var myEvent = window.attachEvent || window.addEventListener;
                                    // var chkevent = window.attachEvent ? 'onbeforeunload' : 'beforeunload';
                                    // myEvent(chkevent, function (e) { // For >=IE7, Chrome, Firefox
                                    //     var confirmationMessage = 'Are you sure to leave the page?'; // a space
                                    //     (e || window.event).returnValue = confirmationMessage;
                                    //     return confirmationMessage;
                                    // });
                                    parent.find('.custom-file-label').text('Add file');
                                    BasePlugin.initTooltipPopover();

                                    if ((parent.find('.progress-container').length >= options.maxFile) || (files.length > options.maxFile)) {
                                        el.prop('disabled', true);
                                        parent.find('.custom-file-label').text('Uploaded');
                                    }
                                },
                                error: function (resp) {
                                    parent.find('.progress-container[data-uid="'+uniqueid+'"]').remove();
                                    parent.append(`
                                            <div class="alert alert-custom alert-light-danger fade show py-2 px-4 my-2 error-uploaded" role="alert">
                                                <div class="alert-icon">
                                                    <i class="flaticon-warning"></i>
                                                </div>
                                                <div class="alert-text text-left">Error Upload File: `+ file.name +`</div>
                                                <div class="alert-text text-left">
                                                    <div>Upload File:</div>
                                                    <a href="javascript:;" class="text-primary">`+ file.name +`</a>
                                                </div>
                                                <div class="alert-close">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">
                                                                <i class="ki ki-close"></i>
                                                            </span>
                                                        </button>
                                                </div>
                                            </div>
                                        `);
                                    form.find('button[type="submit"]').removeAttr('disabled');
                                    form.find('.progress-bar.bar-' + uniqueid)
                                        .removeClass('progress-bar-striped')
                                        .text('Error');
                                    if (parent.find('.success-uploaded').length == 0) {
                                        parent.find('.uploaded').val('');
                                    }
                                    parent.find('.custom-file-label').text('Choose file');
                                },
                            });
                        }
                    });

                }
            }
        },
        removeTempFiles: function (el) {
            var me = $(el),
                container = me.closest('.progress-container'),
                parent = container.parent();

            me.tooltip('hide');
            container.remove();
            parent.find('input[type="file"]').val('').prop('disabled', false);
            parent.find('.custom-file-label').text('Choose file');
            if (parent.find('.success-uploaded').length == 0) {
                parent.find('.uploaded').val('');
            }
            BasePlugin.initTooltipPopover();
        },
        approveByUrl: function (el) {
            var me = $(el),
                url = me.attr('href') ?? me.data('url');

            if (url) {
                Swal.fire({
                    title: me.data('swal-title') ?? BaseApp.lang('confirm.approve.title'),
                    text: me.data('swal-text') ?? BaseApp.lang('confirm.save.text'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: BaseApp.lang('confirm.approve.ok'),
                    cancelButtonText: BaseApp.lang('confirm.approve.cancel'),
                })
                .then(function (result) {
                    if (result.isConfirmed) {
                        var contentLoading = setTimeout(function () {
                            BaseContent.loader(true);
                        }, 500);

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                _token: BaseUtil.getToken(),
                            },
                            success: function (resp) {
                                clearTimeout(contentLoading);
                                BaseContent.loader(false);
                                $.gritter.add({
                                    title: 'Success!',
                                    text: BaseApp.lang('success.approved'),
                                    image: BaseUtil.getUrl('assets/media/ui/check.png'),
                                    sticky: false,
                                    time: '3000'
                                });
                                BaseContent.replaceByUrl(resp.redirect);
                            },
                            error: function (resp) {
                                resp = resp.responseJSON;
                                clearTimeout(contentLoading);
                                BaseContent.loader(false);
                                $.gritter.add({
                                    title: 'Failed!',
                                    text: BaseApp.lang('error.approved'),
                                    image: BaseUtil.getUrl('assets/media/ui/cross.png'),
                                    sticky: false,
                                    time: '3000'
                                });
                            }
                        });
                    }
                });
            }
        },
        authorizeByUrl: function (el) {
            var me = $(el),
                url = me.attr('href') ?? me.data('url');

            if (url) {
                Swal.fire({
                    title: me.data('swal-title') ?? BaseApp.lang('confirm.authorize.title'),
                    text: me.data('swal-text') ?? BaseApp.lang('confirm.save.text'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: BaseApp.lang('confirm.authorize.ok'),
                    cancelButtonText: BaseApp.lang('confirm.authorize.cancel'),
                })
                .then(function (result) {
                    if (result.isConfirmed) {
                        var contentLoading = setTimeout(function () {
                            BaseContent.loader(true);
                        }, 500);

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                _token: BaseUtil.getToken(),
                            },
                            success: function (resp) {
                                clearTimeout(contentLoading);
                                BaseContent.loader(false);
                                $.gritter.add({
                                    title: 'Success!',
                                    text: BaseApp.lang('success.authorized'),
                                    image: BaseUtil.getUrl('assets/media/ui/check.png'),
                                    sticky: false,
                                    time: '3000'
                                });
                                BaseContent.replaceByUrl(resp.redirect);
                            },
                            error: function (resp) {
                                resp = resp.responseJSON;
                                clearTimeout(contentLoading);
                                BaseContent.loader(false);
                                $.gritter.add({
                                    title: 'Failed!',
                                    text: BaseApp.lang('error.authorized'),
                                    image: BaseUtil.getUrl('assets/media/ui/cross.png'),
                                    sticky: false,
                                    time: '3000'
                                });
                            }
                        });
                    }
                });
            }
        },
        postByUrl: function (el, options = {}) {
            var me = $(el);
            var defaultOptions = {
                    url: me.attr('href') ?? me.data('url'),
                    method: me.data('method') ?? 'POST',
                    swalTitle: me.data('swal-title') ?? BaseApp.lang('confirm.save.title'),
                    swalText: me.data('swal-text') ?? BaseApp.lang('confirm.save.text'),
                    swalOk: me.data('swal-ok') ?? BaseApp.lang('confirm.save.ok'),
                    swalCancel: me.data('swal-cancel') ?? BaseApp.lang('confirm.save.cancel'),
                    data: {},
                },
                options = $.extend(defaultOptions, options);

            if (options.url) {
                Swal.fire({
                    title: options.swalTitle,
                    text: options.swalText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: options.swalOk,
                    cancelButtonText: options.swalCancel,
                })
                .then(function (result) {
                    if (result.isConfirmed) {
                        var contentLoading = setTimeout(function () {
                            BaseContent.loader(true);
                        }, 500);

                        $.ajax({
                            type: 'POST',
                            url: options.url,
                            data: $.extend(
                                {
                                    _token: BaseUtil.getToken(),
                                    _method: options.method,
                                },
                                options.data
                            ),
                            success: function (resp) {
                                clearTimeout(contentLoading);
                                BaseContent.loader(false);
                                $.gritter.add({
                                    title: 'Success!',
                                    text: resp.message ?? BaseApp.lang('success.saved'),
                                    image: BaseUtil.getUrl('assets/media/ui/check.png'),
                                    sticky: false,
                                    time: '3000'
                                });
                                BaseContent.replaceByUrl(resp.redirect);
                            },
                            error: function (resp) {
                                resp = resp.responseJSON;
                                clearTimeout(contentLoading);
                                BaseContent.loader(false);
                                $.gritter.add({
                                    title: 'Failed!',
                                    text:  resp.message ?? BaseApp.lang('error.saved'),
                                    image: BaseUtil.getUrl('assets/media/ui/cross.png'),
                                    sticky: false,
                                    time: '3000'
                                });
                            }
                        });
                    }
                });
            }
        },
        documentEvent: function () {
            $(document).on('change', 'input.base-form--save-temp-files', function (e) {
                BaseForm.saveTempFiles(this, e);
            });

            $(document).on('click', '.base-form--remove-temp-files', function (e) {
                // e.preventDefault();
                BaseForm.removeTempFiles(this);
            });

            $(document).on('click', '.base-form--approveByUrl', function (e) {
                e.preventDefault();
                BaseForm.approveByUrl(this);
            });
            $(document).on('click', '.base-form--authorizeByUrl', function (e) {
                e.preventDefault();
                BaseForm.authorizeByUrl(this);
            });
            $(document).on('click', '.base-form--postByUrl', function (e) {
                e.preventDefault();
                BaseForm.postByUrl(this);
            });

            $(document).on('click', '.base-form--submit-login', function (e) {
                e.preventDefault();

                var me = $(this),
                    form = me.closest('form');

                BaseForm.submit(form, {
                    btnSubmit: me,
                    swalError: false,
                    fullCallbackSuccess: true,
                    callbackSuccess: function (resp, form, options) {
                        BaseUtil.redirect('/');
                    },
                });
            });

            $(document).on('click', '.base-form--submit-modal', function (e) {
                e.preventDefault();

                var me = $(this),
                    form = me.closest('form'),
                    swalConfirm = me.data('swal-confirm'),
                    swalTitle = me.data('swal-title') ?? BaseApp.lang('confirm.save.title'),
                    swalText = me.data('swal-text') ?? BaseApp.lang('confirm.save.text'),
                    swalOk = me.data('swal-ok') ?? BaseApp.lang('confirm.save.ok'),
                    swalCancel = me.data('swal-cancel') ?? BaseApp.lang('confirm.save.cancel');

                if (form.find('[name="is_submit"]').length == 0) {
                    form.append('<input type="hidden" name="is_submit" value="1">');
                }
                form.find('[name="is_submit"]').val(me.data('submit') ?? 1);

                if (swalConfirm) {
                    Swal.fire({
                        title: swalTitle,
                        text: swalText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: swalOk,
                        cancelButtonText: swalCancel,
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            BaseForm.submit(form, {
                                btnSubmit: me,
                            });
                        }
                    });
                }
                else {
                    BaseForm.submit(form, {
                        btnSubmit: me,
                    });
                }
            });

            $(document).on('click', '.base-form--submit-page', function (e) {
                e.preventDefault();

                var me = $(this),
                    form = me.closest('form'),
                    swalConfirm = me.data('swal-confirm'),
                    swalTitle = me.data('swal-title') ?? BaseApp.lang('confirm.save.title'),
                    swalText = me.data('swal-text') ?? BaseApp.lang('confirm.save.text'),
                    swalOk = me.data('swal-ok') ?? BaseApp.lang('confirm.save.ok'),
                    swalCancel = me.data('swal-cancel') ?? BaseApp.lang('confirm.save.cancel');

                if (form.find('[name="is_submit"]').length == 0) {
                    form.append('<input type="hidden" name="is_submit" value="1">');
                }
                form.find('[name="is_submit"]').val(me.data('submit') ?? 1);

                if (swalConfirm !== false) {
                    Swal.fire({
                        title: swalTitle,
                        text: swalText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: swalOk,
                        cancelButtonText: swalCancel,
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            BaseForm.submit(form, {
                                btnSubmit: me,
                                btnBack: form.find('.btn-back'),
                                loaderModal: false,
                            });
                        }
                    });
                }
                else {
                    BaseForm.submit(form, {
                        btnSubmit: me,
                        btnBack: form.find('.btn-back'),
                        loaderModal: false,
                    });
                }
            });

            $(document).on('click', '.base-modal--confirm', function (e) {
                e.preventDefault();
                BaseModal.confirm(this);
            });

            $(document).on('click', '.base-form--delete', function (e) {
                e.preventDefault();

                var me = $(this),
                    url = me.attr('href') ? me.attr('href') : (me.data('url') ? me.data('url') : '' );

                me.closest('.modal').modal('hide');
                if (url) {
                    BaseForm.delete(url);
                }
            });

            $(document).on('click', '.base-form--activate', function (e) {
                e.preventDefault();

                var me = $(this),
                    status = $(this).data('status') == 1 ? 0 : 1,
                    text = status == 1 ? 'Are you sure you want to activate this data?'
                                        : 'Are you sure you want to inactivate this data?';

                BaseModal.confirm(me, {
                    method: 'POST',
                    confirm_text: `<input type="hidden" name="status" value="`+status+`">`+text,
                });
            });

            $(document).on('focus', 'form input, form textarea', function () {
                var me = $(this),
                    fg = me.closest('.parent-group').length ? me.closest('.parent-group') : me.closest('.form-group');
                if (fg.length) {
                    fg.find('.is-invalid').removeClass('is-invalid');
                    fg.find('.is-invalid-message').remove();
                    fg.find('.is-invalid-alert').remove();
                }
            });

            $(document).on('change', 'select', function () {
                var me = $(this),
                    fg = me.closest('.parent-group').length ? me.closest('.parent-group') : me.closest('.form-group');
                if (fg.length) {
                    fg.find('.is-invalid').removeClass('is-invalid');
                    fg.find('.is-invalid-message').remove();
                    fg.find('.is-invalid-alert').remove();
                }
            });

            $(document).on('change', '.custom-file input[type="file"]:not(.base-form--save-temp-files)', function (e) {
                if (e.target.files.length) {
                    $(this).next('.custom-file-label').html(e.target.files[0].name);
                }
            });
        }
    }
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseForm;
}
