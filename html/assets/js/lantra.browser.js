function formatBytes(bytes,decimals) {
    if(bytes == 0) return '0 Bytes';
    let k = 1024,
        dm = decimals || 2,
        sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

(function($) {
    $.lantraBrowser = function(element, options) {

        let plugin = this,
            uploadId = Date.now(),
            fieldName = null,
            userId = null,
            packageId = null,
            $target = null,
            $element = $(element),
            $ulEvidence = $element.find('ul.assets-evidence'),
            $ulPackages = $element.find('ul.packages-evidence'),
            $inputUpload = $element.find('span.upload').find('input'),
            $progress = $element.find('div.progress'),
            defaults = {
                urlBrowser: '/sp/assets/browse-evidence',
                urlUpload: '/sp/assets/upload-evidence',
                urlPackages: '/sp/packages/browse-packages',
            };

        plugin.settings = {}

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

            // cancel
            $element.on('click', 'a.cancel', function(e){
                e.preventDefault();
                plugin.closeModal();
            });

            // add select click
            $element.on('click', '#browser-modal li.lantra-asset', function(e){
                e.preventDefault();
                plugin.selectAsset($(this).clone());
            });

            // add select click
            $element.on('click', 'a.upload-cancel', function(e){
                plugin.cancelUpload();
            });

            // upload file
            $inputUpload.fileupload({
                url: this.settings.urlUpload,
                dataType: 'json',
                autoUpload: true,
                multiple: false,
                formData: {},
                maxChunkSize: 2000000,
                start: function (e) {
                    $element.addClass('loading');
                },
                stop: function (e) {
                    $element.removeClass('loading');
                },
                done: function (e, data) {
                    $element.removeClass('loading');
                    if (!data.result.success || !data.result.asset) {
                        return alert(data.result.message);
                    }
                    plugin.selectAsset(data.result.html);
                },
                error: function (e, data) {
                    $element.removeClass('loading');
                }
            }).on('fileuploadadd', function (e, data) {
                $element.data('jqXHR', data.submit());
                $element.data('fileName', data.files[0].name);
            }).on('fileuploadsubmit', function (e, data) {
                // add a random upload ID to avoid conflicts
                data.formData = {
                    userId: userId,
                    uploadId: uploadId,
                    fieldName: fieldName
                };
            }).on('fileuploadprogressall', function (e, data) {
                let progress = parseInt(data.loaded / data.total * 100, 10);
                $progress.text( progress + '%');
            }).on('fileuploadsend', function (e, data) {
                $progress.text( '0%');
            });

            $element.on('click', '#browser-modal li.lantra-asset', function(e){
                e.preventDefault();
                plugin.selectAsset($(this).clone());
            });
        }

        plugin.cancelUpload = function() {
            let jqXHR = $element.data('jqXHR'),
                data = {
                uploadId: uploadId,
                fileName: $element.data('fileName')
            };
            if (jqXHR) jqXHR.abort();
            $element.removeClass('loading');
            data[window.csrfTokenName] = window.csrfTokenValue;
            $.post(this.settings.urlUpload, data, function(response) {}, "json");
        }

        plugin.setTarget = function(t) {
            $target = t;
            fieldName = $target.data('field-name');
            userId = $target.data('user-id');
            packageId = $target.data('package-id');
            return plugin;
        }

        plugin.closeModal = function(){
            $element.parents('.modal-wrapper').removeClass('modal-open');
            return plugin;
        }

        plugin.openModal = function(){
            $element.parents('.modal-wrapper').addClass('modal-open');
            return plugin;
        }

        plugin.refreshAssets = function() {
            plugin.loadAssets($ulEvidence);
            return plugin;
        }

        plugin.refreshPackages = function($packageId) {
            plugin.loadPackages($ulPackages, $packageId);
            return plugin;
        }

        plugin.loadAssets = function($ul) {
            let data = {
                volume: $ul.data('volume'),
                userId: userId,
                packageId: packageId,
                fieldName: fieldName
            };
            data[window.csrfTokenName] = window.csrfTokenValue;
            $('body').addClass('loading');
            $.post(this.settings.urlBrowser, data, function(response) {
                $('body').removeClass('loading');
                if (!response.success) {
                    alert(response.message ? response.message : 'Server error, check the console.');
                    return;
                }
                $ul.empty();
                $.each(response.assets, function(key, asset) {
                    $ul.prepend(asset.html);
                });

            }).fail(function(error) {
                $('body').removeClass('loading');
                alert('Server error, check the console.');
            });
        }

        plugin.loadPackages = function($ul, $packageId) {
            let data = {
                userId: userId,
                packageId: $packageId
            };
            data[window.csrfTokenName] = window.csrfTokenValue;
            $('body').addClass('loading');
            $.post(this.settings.urlPackages, data, function(response) {
                $('body').removeClass('loading');
                if (!response.success) {
                    alert(response.message ? response.message : 'Server error, check the console.');
                    return;
                }
                $ul.empty();
                $ul.prepend(response.html);

            }).fail(function(error) {
                $('body').removeClass('loading');
                alert('Server error, check the console.');
            });
        }        

        plugin.selectAsset = function($asset) {
            $target.prepend($asset);
            plugin.closeModal();
            if ($target.data('auto-save') === 1) {
                let $form = $target.parents('form').eq(0);
                $.post('/', $form.serialize(), function(response) {}, "json");
            }
        }

        plugin.init();
    }

    $.fn.lantraBrowser = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('lantraBrowser')) {
                let plugin = new $.lantraBrowser(this, options);
                $(this).data('lantraBrowser', plugin);
            }
        });
    }
})(jQuery);

$(document).ready(function(){
    $('.lantra-browser').lantraBrowser();
    $('.open-lantra-browser').click(function(e){
        e.preventDefault();
        let t = $(this).data('target'),
            $element = $('#browser-modal').find('.lantra-browser').eq(0);
        $element.data('lantraBrowser').setTarget($('#' + t)).refreshAssets().openModal();
    });
    $('ul.assets').on( 'click', 'i.delete', function(){
        let $li = $(this).parents('li'),
            $form = $(this).parents('form').eq(0);
        $li.fadeOut('fast', function(){
            $li.remove();
            $.post('/', $form.serialize(), function(response) {}, "json");
        });
    });


    $(document).on("click", "a.open-lantra-evidence-upload", function(e) {
        e.preventDefault();
        let t = $(this).data('target'),
            $packageId = $(this).data('package-id'),
            $element = $('#evidence-upload-modal').find('.lantra-browser').eq(0);
        $element.data('lantraBrowser').setTarget($('#' + t)).refreshAssets().refreshPackages($packageId).openModal();
        $("section.lantra-browser ul.assets").css('height', 'auto');
    });
    // $('ul.assets').on( 'click', 'i.delete', function(){
    //     let $li = $(this).parents('li'),
    //         $form = $(this).parents('form').eq(0);
    //     $li.fadeOut('fast', function(){
    //         $li.remove();
    //         $.post('/', $form.serialize(), function(response) {}, "json");
    //     });
    // });

    /*
    $(document).on("click", "a.open-lantra-upload", function(e) {
        e.preventDefault();
        $element = $('#upload-evidence-modal').find('.lantra-browser').eq(0);
        $element.parents('.modal-wrapper').addClass('modal-open');
    })

    $(document).on("click", "#upload-evidence-modal a.close", function(e) {
        e.preventDefault();
        $element = $('#upload-evidence-modal').find('.lantra-browser').eq(0);
        $element.parents('.modal-wrapper').removeClass('modal-open');
    })
    */
});