var swfuploadhandler = {
    init: function (b, a) {
        b = $.extend(true, {}, swfuploadefaults, b);
        swfuploadhandler["SWFUPLOAD_" + b.custom_settings.form.attr("id") + "_" + a] = new SWFUpload(b)
    },
    swfUploadLoaded: function () {},
    uploadStart: function () {},
    uploadDone: function () {
        this.customSettings.showmsg("已成功上传文件！", 2)
    },
    fileDialogStart: function () {
        this.customSettings.form.find("input[plugin='swfupload']").val("");
        this.cancelUpload()
    },
    fileQueueError: function (a, d, b) {
        try {
            switch (d) {
            case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                this.customSettings.showmsg("You have attempted to queue too many files.\n" + (b === 0 ?
                    "You have reached the upload limit." : "You may select " + (b > 1 ? "up to " + b + " files." :
                    "one file.")), 3);
                return;
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                this.customSettings.showmsg("The file you selected is too big.", 3);
                this.debug("Error Code: File too big, File name: " + a.name + ", File size: " + a.size + ", Message: " +
                    b);
                return;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                this.customSettings.showmsg("The file you selected is empty.  Please select another file.", 3);
                this.debug("Error Code: Zero byte file, File name: " + a.name + ", File size: " + a.size +
                    ", Message: " + b);
                return;
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                this.customSettings.showmsg("The file you choose is not an allowed file type.", 3);
                this.debug("Error Code: Invalid File Type, File name: " + a.name + ", File size: " + a.size +
                    ", Message: " + b);
                return;
            default:
                swfu.customSettings.showmsg("An error occurred in the upload. Try again later.", 3);
                this.debug("Error Code: " + d + ", File name: " + a.name + ", File size: " + a.size + ", Message: " + b);
                return
            }
        } catch (c) {}
    },
    fileQueued: function (a) {
        try {
            this.customSettings.form.find("input[plugin='swfupload']").val(a.name)
        } catch (b) {}
    },
    fileDialogComplete: function (a, b) {
        this.startUpload()
    },
    uploadProgress: function (a, d, c) {
        try {
            var b = Math.ceil((d / c) * 100);
            this.customSettings.showmsg("已上传：" + b + "%", 1)
        } catch (f) {}
    },
    uploadSuccess: function (b, a) {
        try {
            if (a === " ") {
                this.customSettings.upload_successful = false
            } else {
                this.customSettings.upload_successful = true;
                this.customSettings.form.find("input[pluginhidden='swfupload']").val(a)
            }
        } catch (c) {}
    },
    uploadComplete: function (a) {
        try {
            if (this.customSettings.upload_successful) {
                swfuploadhandler.uploadDone.call(this)
            } else {
                this.customSettings.form.find("input[plugin='swfupload']").val("");
                this.customSettings.showmsg("There was a problem with the upload.\nThe server did not accept it.", 3)
            }
        } catch (b) {}
    },
    uploadError: function (b, d, c) {
        try {
            if (d === SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
                return
            }
            this.customSettings.form.find("input[plugin='swfupload']").val("");
            switch (d) {
            case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
                this.customSettings.showmsg(
                    "There was a configuration error.  You will not be able to upload a resume at this time.", 3);
                this.debug("Error Code: No backend file, File name: " + b.name + ", Message: " + c);
                return;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                this.customSettings.showmsg("You may only upload 1 file.", 3);
                this.debug("Error Code: Upload Limit Exceeded, File name: " + b.name + ", File size: " + b.size +
                    ", Message: " + c);
                return;
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                break;
            default:
                this.customSettings.showmsg("An error occurred in the upload. Try again later.", 3);
                this.debug("Error Code: " + d + ", File name: " + b.name + ", File size: " + b.size + ", Message: " + c);
                return
            }
        } catch (a) {}
    }
};
var swfuploadefaults = {
    file_size_limit: "10 MB",
    file_types: "*.*",
    file_types_description: "All Files",
    file_upload_limit: "0",
    file_queue_limit: "10",
    button_placeholder_id: "spanButtonPlaceholder",
    file_post_name: "resume_file",
    upload_url: "plugin/swfupload/upload.php",
    button_image_url: "plugin/swfupload/XPButtonUploadText_61x22.png",
    button_width: 61,
    button_height: 22,
    flash_url: "plugin/swfupload/swfupload.swf",
    swfupload_loaded_handler: swfuploadhandler.swfUploadLoaded,
    file_dialog_start_handler: swfuploadhandler.fileDialogStart,
    file_queued_handler: swfuploadhandler.fileQueued,
    file_queue_error_handler: swfuploadhandler.fileQueueError,
    file_dialog_complete_handler: swfuploadhandler.fileDialogComplete,
    upload_start_handler: swfuploadhandler.uploadStart,
    upload_progress_handler: swfuploadhandler.uploadProgress,
    upload_error_handler: swfuploadhandler.uploadError,
    upload_success_handler: swfuploadhandler.uploadSuccess,
    upload_complete_handler: swfuploadhandler.uploadComplete,
    custom_settings: {},
    debug: false
};