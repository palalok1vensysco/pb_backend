
let credentials = {
    IdentityPoolId: 'ap-south-1:66adf0fe-6c11-45d6-8d51-77fa00db3e49'
};
var bucketName = 'videocrypt-app'; // Enter your bucket name
// Initialize the Amazon Cognito credentials provider
AWS.config.region = 'ap-south-1'; // Region
AWS.config.credentials = new AWS.CognitoIdentityCredentials(credentials);

AWS.config.credentials.get(function (err) {
    if (err) {
        //console.log(AWS.config.credentials);
    }
});

var bucket = new AWS.S3({
    params: {
        Bucket: bucketName
    }
});

function s_s3_file_upload(folder_path, fileChooser) {
    return new Promise(function (resolve) {
        $("#overlay").show();
        overlay("Please Wait.. Connecting To Server");
        //delelte old file
        var file = fileChooser.files[0];
        if (file) {
            var filename = file.name;
            var ext = filename.split('.').pop();
            var random = Math.floor(Math.random() * 900000000000000000);

            filename = random +'.' + ext;

            var objKey = folder_path + filename;
            var params = {
                Key: objKey,
                ContentType: file.type,
                Body: file,
                ACL: 'bucket-owner-full-control'
            };

            bucket.putObject(params, function (err, data) {
                if (err) {
                    console.log('ERROR: ' + err);
                    overlay("");
                    show_toast("error", 'Connection Error', "Connection Failed")
                } else {
                    console.log('Upload Complete');
                }
            });
            bucket.upload(params).on('httpUploadProgress', function (evt) {
                console.log("Uploaded :: " + parseInt((evt.loaded * 100) / evt.total) + '%');
                overlay("Uploading Progress: " + parseInt((evt.loaded * 100) / evt.total) + '% ');
            }).send(function (err, data) {
                overlay("");
                console.log(data.Location);
                resolve(data);
            });
        } else {
            console.log('Nothing to upload.');
        }
    });
}

function upload_file_size(fileChooser) {
    var nBytes = fileChooser.files[0];
    if (nBytes != undefined)
        nBytes = nBytes.size;
    var sOutput = (nBytes == undefined ? 0 : (nBytes / 1024)) + " K";
    // optional code for multiples approximation
//    for (var aMultiples = ["K", "M", "G", "T", "P", "E", "Z", "Y"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
//        sOutput =  nApprox.toFixed(3) +" "+ aMultiples[nMultiple];
//    }

    return sOutput;
}