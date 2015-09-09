$(document).ready(function () {

    var userId = document.getElementsByName('userId')[0];

    if (userId) {
        userId = userId.value;
        var bAllUploaded = false;
        var bAllAdded = false;
        var galleryUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-gallery"),
            template: 'qq-template-gallery',
            autoUpload: false,
            request: {
                endpoint: './php/fine-uploader/endpoint.php',
                params: {
                    userId: userId
                }
            },
            thumbnails: {
                placeholders: {
                    waitingPath: './placeholders/waiting-generic.png',
                    notAvailablePath: './placeholders/not_available-generic.png'
                }
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            callbacks: {
                onComplete: function (id, name, json) {
                    console.log(json);
                    $.ajax({
                        type: 'get',
                        data: 'type=addPhoto&photoUrl=' + json.uploadName + '&ajax=true',
                        success: function (json) {
                            console.log(json);
                            if (bAllUploaded) {
                                window.location.reload();
                            }
                        }
                    });
                },
                onAllComplete: function () {
                    bAllUploaded = true;
                }
            }
        });
        qq(document.getElementById("uploadButton")).attach('click', function () {
            galleryUploader.uploadStoredFiles();
        });
    }

});
