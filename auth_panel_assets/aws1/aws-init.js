// Initialize the Amazon Cognito credentials provider
AWS.config.region = 'ap-south-1'; // Region
AWS.config.credentials = new AWS.CognitoIdentityCredentials({
    IdentityPoolId: 'ap-south-1:e6b9eaae-5c57-4f4d-9017-0e7bb97e32df',
});

AWS.config.credentials.get(function (err) {
    if (err) {
        //console.log(AWS.config.credentials);
    }
});

var bucketName = 'mahua-tv'; // Enter your bucket name
var bucket = new AWS.S3({
    params: {
        Bucket: bucketName
    }
});
var META_ID = "_video_VOD";
function s_s3_file_upload(folder_path, fileChooser,out_dest) {
    return new Promise(function (resolve) {
        $("#overlay").show();
        overlay("Please Wait.. Connecting To Server");
        //delelte old file
        var file = fileChooser.files[0];
        if (file) {
            var filename = file.name;
            var ext = filename.split('.').pop();
            var random = Math.floor(Math.random() * 900000000000000000);

            filename = random + META_ID + '.' + ext;

            var objKey = folder_path + filename;
            var params = {
                Key: objKey,
                ContentType: file.type,
                Body: file,
                ACL: 'public-read'
            };
            bucket.upload(params).on('httpUploadProgress', function (evt) {
                overlay("Uploading Progress: " + parseInt((evt.loaded * 100) / evt.total) + '% <i class="fa fa-circle-o-notch fa-spin"></i>');
            }).send(function (err, data) {
                overlay("");
                $("input[name="+out_dest+"]").val(data.Location);
                console.log(data.Location);
                resolve(data);
            });
        } else {
            show_toast('error','Error!!','Nothing To Upload');
        }
    });
}

function upload_file_size(elementId) {
    var nBytes = 0,
            oFiles = document.getElementById(elementId).files,
            nFiles = oFiles.length;

    for (var nFileId = 0; nFileId < nFiles; nFileId++) {
        nBytes += oFiles[nFileId].size;
    }
    var sOutput = (nBytes / 1024) + " K";

    return sOutput;
}