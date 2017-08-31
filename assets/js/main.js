$(document).on('change', '.btn-file :file', function() { // for file input
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(function(){

    var cntr = 1;
    var btnHtml = '<div class="cssload-container">' +
        '<div class="cssload-speeding-wheel"></div>' +
        '</div>';

    $('[data-toggle="tooltip"]').tooltip();

    $('.new').click(function(){
        var flag = $(this).closest('section#courses').length > 0; // is new course or new file
        if(!flag){
            var course_id = $(this).closest('section').attr('id').substring(6);
        }

        $(this).addClass('disabled');

        if($(this).closest('div.row').next('div.row').length == 0){
            var html = '<div class="row top15 NewCourse" style="display: none">' +
                '<form class="newForm" id="form' + cntr++ + '" role="form" action="admin/' + (flag ? 'addCourse' : 'addFile') + '" method="post" ' + (flag ? '' : 'enctype="multipart/form-data"') + '>' +
                '<input type="hidden" name="' + $('input#_csrf_token').attr('name') + '" value="' + $('input#_csrf_token').val() + '">' +
                (flag ? '' : '<input type="hidden" name="course_id" value="' + course_id + '">') +
                '<fieldset class="scheduler-border">' +
                '<legend class="scheduler-border">' + (flag ? 'New Course' : 'New File') + '</legend>' +
                '<div class="form-group has-feedback">' +
                '<label for="' + (flag ? 'name' : 'description') + '">' + (flag ? 'Name' : 'Description') + ':</label>' +
                (flag ?
                    '<input type="text" class="form-control r2l" name="name" id="name">'
                    : '<textarea class="form-control r2l" rows="3" name="description" id="description"></textarea>') +
                '</div>' +
                (flag ? '' : '<div class="form-group has-feedback">' +
                '<div class="input-group">' +
                '<span class="input-group-btn">' +
                '<span class="btn btn-info btn-file">' +
                '<span class="glyphicon glyphicon-search"></span> <input type="file" name="files[]" multiple>' +
                '</span>' +
                '</span>' +
                '<input type="text" class="form-control" readonly>' +
                '</div>' +
                '</div>') +
                '<div class="form-group">' +
                '<button type="submit" class="btn btn-success saveNew">Save</button>' +
                '<button type="button" class="btn btn-warning closeNew">Close</button>' +
                '</div>' +
                (flag ? '' : '<div class="form-group" style="display: none">' +
                '<p>Uploading...</p>' +
                '<div class="progress" style="height: 20px">' +
                '<div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 20px; font-size: 20px; padding-top: 5px">' +
                '0%' +
                '</div>' +
                '</div>' +
                '</div>') +
                '</fieldset>' +
                '</form>' +
                '</div>';
            var form = $(this).parent('div.row').after(html).next('div.row');
            form.slideDown('slow');
        }else{
            $(this).closest('div.row').next('div.row').slideDown('slow');
        }

        $('.btn-file :file').on('fileselect', function(event, numFiles, label) { // for file input

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });

        $('.closeNew').click(function(){
            $(this).closest('div.row').prev('div.row').find('button').removeClass('disabled');
            $(this).closest('div.row').slideUp('slow');
        });

        $(".newForm").each(function(i, obj){

            $(this).validate({

                errorPlacement: function(error,element) {
                    return true;
                },

                rules :{
                    name:{
                        required: true,
                        maxlength: 255
                    },
                    'files[]':{
                        required: true
                    }
                },

                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                    var flag = $(element).closest('section#courses').length > 0; // new course or new file
                    if(flag){
                        if($(element).siblings('span.glyphicon').length == 0)
                            $(element).after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
                        else{
                            $(element).siblings('span.glyphicon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
                        }
                    }
                    else{
                        if($(element).closest('span.input-group-btn').siblings('span.glyphicon').length == 0)
                            $(element).closest('span.input-group-btn').siblings('input').after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
                        else{
                            $(element).closest('span.input-group-btn').siblings('span.glyphicon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
                        }
                    }
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                    var flag = $(element).closest('section#courses').length > 0; // new course or new file
                    if(flag){
                        $(element).siblings('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
                    }
                    else{
                        $(element).closest('span.input-group-btn').siblings('span.glyphicon').removeClass('glyphicon-remove').addClass('glyphicon-ok');
                    }
                },

                submitHandler: function(form){

                    var options = {
                            beforeSubmit:   showRequest,
                            uploadProgress: showUpload,
                            success:        showResponse,
                            error:          showError,
                            dataType:       'json',
                            encode:         true
                        },
                        form_id = "#"+form.id,
                        btn = $(form_id + " " + "button[type='submit']");

                    var progress = btn.closest('div.form-group').next('div.form-group');
                    progress.slideDown('fast');

                    var progress = progress.find('.progress');
                    var percent = progress.find('.progress-bar');
                    var status = progress.prev('p');

                    $(form).ajaxSubmit(options);

                    function showRequest(formData, jqForm, options) {

                        status.text('Uploading...');
                        var percentVal = '0%';
                        percent.width(percentVal)
                        percent.html(percentVal);

                        btn.addClass('disabled').html(btnHtml);

                        return true;
                    }

                    function showUpload(event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        percent.width(percentVal)
                        percent.html(percentVal);
                    }

                    function showResponse(responseText, statusText, xhr, $form)  {

                        var percentVal = '100%';
                        percent.width(percentVal)
                        percent.html(percentVal);

                        btn.removeClass('disabled').html('Save');
                        status.text('Completed');
                        Example.show('Please wait...');
                        location.reload();
                        return true;
                    }

                    function showError()
                    {
                        btn.removeClass('disabled').html('Save');
                        alert('error');
                    }
                }
            });
        });
    });

    $('.changeActive').click(function(){

        var btn = $(this);
        btn.addClass('disabled');

        var flag = $(this).closest('section#courses').length > 0; // is course or file
        if(flag){
            var course_id = $(this).closest('tr').attr('id').substring(10);
        }else{
            var file_id = $(this).closest('tr').attr('id').substring(4);
        }

        $.post("admin/changeActive",{
            type: (flag ? 'course' : 'file'),
            course_id: course_id,
            file_id: file_id,
            GandomToken: $('#_csrf_token').val()
        }, function(data, status){

            btn.removeClass('disabled');

            if(data.success && status == "success")
            {
                if(btn.hasClass('btn-success')){
                    btn.removeClass('btn-success').addClass('btn-danger');
                    btn.find('span').removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
                    btn.tooltip('hide').attr('data-original-title', 'Show').tooltip('fixTitle').tooltip('show');
                }else{
                    btn.removeClass('btn-danger').addClass('btn-success');
                    btn.find('span').removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
                    btn.tooltip('hide').attr('data-original-title', 'Hide').tooltip('fixTitle').tooltip('show');
                }
            }
            else
            {

            }
        }, "json");
    });

    $(document).on('click', '.edit', function () {

        var flag = $(this).closest('section#courses').length > 0; // is course or file
        if(flag){
            var course_id = $(this).closest('tr').attr('id').substring(10);
            var name = $(this).closest('tr').children('td').eq(1).text();
        }else{
            var course_id = $(this).closest('section').attr('id').substring(6);
            var file_id = $(this).closest('tr').attr('id').substring(4);
            var name = $(this).closest('tr').children('td').eq(1).find('a').text();
            var download = $(this).closest('tr').children('td').eq(1).find('span.badge').text();
            var description = $(this).closest('tr').children('td').eq(4).text();
        }

        var html = '<form class="editForm" id="form' + cntr++ + '" role="form" action="admin/' + (flag ? 'editCourse' : 'editFile') + '" method="post">' +
            '<input type="hidden" name="' + $('input#_csrf_token').attr('name') + '" value="' + $('input#_csrf_token').val() + '">' +
            '<input type="hidden" name="course_id" value="' + course_id + '">' +
            (flag ? '' : '<input type="hidden" name="file_id" value="' + file_id + '">') +
            '<div class="form-group has-feedback">' +
            '<label for="name">New name:</label>' +
            '<input type="text" class="form-control ' + (flag ? 'r2l' : '') + '" name="name" id="name" value="' + name + '">' +
            '</div>' +
            (flag ? '' : '<div class="form-group has-feedback">' +
            '<label for="download">Set download counter:</label>' +
            '<input type="number" class="form-control" name="download" id="download"  min="0" value="' + download + '">' +
            '</div>' +
            '<div class="form-group">' +
            '<label for="description">Description:</label>' +
            '<textarea class="form-control r2l" rows="3" name="description" id="description">' + description + '</textarea>' +
            '</div>')
            '</form>';

        var dialog = bootbox.dialog({
            size: 'large',
            title: name,
            message: html,
            onEscape: function() {},
            buttons: {
                save: {
                    label: "Save",
                    className: "btn-success",
                    callback: function() {
                        $(".editForm").submit();
                        return false;
                    }
                },
                close: {
                    label: "Close",
                    className: "btn-warning",
                    callback: function() {}
                }
            },
            show: false
        });

        dialog.on("shown.bs.modal", function() {

            $(".editForm").validate({

                errorElement: 'span',
                errorClass: 'help-block',

                rules :{
                    name:{
                        required: true,
                        maxlength: 255
                    },
                    download:{
                        required: true,
                        number: true,
                        min: 0
                    }
                },

                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                    if($(element).siblings('span.glyphicon').length == 0)
                        $(element).after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
                    else{
                        $(element).siblings('span.glyphicon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
                    }
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                    $(element).siblings('span').removeClass('glyphicon-remove').addClass('glyphicon-ok');
                },

                submitHandler: function(form){

                    dialog.modal('hide');
                    Example.show('Please wait...');

                    var options = {
                            beforeSubmit:   showRequest,
                            success:        showResponse,
                            error:          showError,
                            dataType:       'json',
                            encode:         true
                        };

                    $(form).ajaxSubmit(options);

                    function showRequest(formData, jqForm, options) {
                        return true;
                    }

                    function showResponse(responseText, statusText, xhr, $form)  {

                        location.reload();
                        return true;
                    }

                    function showError()
                    {
                        Example.show('Error :(');
                    }
                }
            });
        });

        dialog.modal('show');
    });

    $(document).on("click", ".delete", function(e) {

        var btn = $(this);
        var flag = btn.closest('section#courses').length > 0; // is course or file
        if(flag){
            var course_id = btn.closest('tr').attr('id').substring(10);
        }else{
            var file_id = btn.closest('tr').attr('id').substring(4);
        }

        bootbox.confirm({
            title: btn.closest('tr').children('td').eq(1).find('a').text(),
            message: (flag ? 'This course and it\'s files'  : 'This file') + ' will be deleted. Are you sure?',
            callback: function(result) {

                if(result){

                    btn.addClass('disabled').html(btnHtml);

                    $.post("admin/delete",{
                        type: (flag ? 'course' : 'file'),
                        course_id: course_id,
                        file_id: file_id,
                        GandomToken: $('#_csrf_token').val()
                    }, function(data, status){

                        btn.removeClass('disabled').html('<span class="glyphicon glyphicon-trash"></span>');

                        if(data.success && status == "success")
                        {
                            btn.closest('tr').fadeOut('slow', function(){
                                Example.show('Done :)');
                                $(this).remove();
                            });
                            if(flag){
                                $('section#course'+course_id).fadeOut('slow');
                                location.reload();
                            }
                        }
                        else
                        {
                            Example.show('error!');
                        }
                    }, "json");
                }
            }
        });
    });
});