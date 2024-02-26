//let dashUri = "https://made-easy-prime.s3.ap-south-1.amazonaws.com/file_library/videos/vod_drm/6050/170054387146585340_video_6050720b_1609173457.mpd?";
////            dashUri = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/mpds/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.mpd";
//let widevineToken = "eyJkcm1fdHlwZSI6IldpZGV2aW5lIiwic2l0ZV9pZCI6IlpIREEiLCJ1c2VyX2lkIjoiMjMiLCJjaWQiOiI2MDUwXzE3MDA1NDM4NzE0NjU4NTM0MF92aWRlb182MDUwNzIwYl8xNjA5MTczNDU3IiwicG9saWN5IjoiVzk0YW02YWZwXC85Q01MK2JKY0dtNnJGYjB0bHE0ZXd1RXBxWHo5cm53aGk0VXBuRVVKTmtmTWxna3NPbG8zYWlHWEhpSWNGRTJWaEpzOVZxM09qZlN3PT0iLCJ0aW1lc3RhbXAiOiIyMDIxLTAzLTE3VDEwOjM0OjI1WiIsInJlc3BvbnNlX2Zvcm1hdCI6Im9yaWdpbmFsIiwiaGFzaCI6ImFyeFJzaktrT2FZWFpSazROcnljY2tlbTFJcHZrRmNGUlcyTTBVY0ZtSFU9In0=";
// let licenseUri = "https://license.pallycon.com/ri/licenseManager.do";
let licenseUri = 'https://www.videocrypt.in/index.php/rest_api/courses/course/on_request_create_video_license';
function init_shaka_player(element_id, url, type, widevineToken) {
    if (type == "mpd") {
        var player = videojs(element_id, {
            techOrder: ['shaka'],
            playbackRates: [0.5, 1, 1.5, 2, 4],
            shaka: {
                debug: false,
                sideload: true,
                configuration: {
                    drm: {
                        servers: {
                            // 'com.widevine.alpha': licenseUri
                             'com.widevine.alpha': licenseUri+"?pallyconCustomdataV2="+widevineToken
                        },
                        advanced: {
                            'com.widevine.alpha': {
                                'videoRobustness': 'SW_SECURE_CRYPTO',
                                'audioRobustness': 'SW_SECURE_CRYPTO'
                            }
                        }
                    },
                    // shaka player configuration - https://shaka-player-demo.appspot.com/docs/api/tutorial-config.html
                },
                licenseServerAuth: function (type, request) {
                    if (type == shaka.net.NetworkingEngine.RequestType.LICENSE) {
                        // This is the specific header name and value the server wants:
                        // request.headers['pallycon-customdata-v2'] = widevineToken;

                    }
                }
            }

        });
        player.qualityPickerPlugin();
        player.src([
            {
                type: 'application/dash+xml',
                src: url
            }
        ]);

//        player.seekButtons({
//            forward: 30,
//            back: 10
//        });

        player.on('error', function () {
            console.log(JSON.parse(JSON.stringify(player.error())));
        });

        player.play().then(function () {
            console.log("autoplay was successful!");
        }).catch(function (error) {
            console.log(error);
        });
    } else if (type == "m3u8") {
        var player = videojs(element_id, {
//            techOrder: ['shaka'],
//            playbackRates: [0.5, 1, 1.5, 2, 4],
//            shaka: {
//                debug: false,
//                sideload: true
//            }

        });
        player.qualityPickerPlugin();
        player.src([
            {
                type: 'application/x-mpegURL',
                src: url
            }
        ]);

//        player.seekButtons({
//            forward: 30,
//            back: 10
//        });

        player.on('error', function () {
            console.log(JSON.parse(JSON.stringify(player.error())));
        });

        player.play().then(function () {
            console.log("autoplay was successful!");
        }).catch(function (error) {
            console.log(error);
        });
    }
}

function pause_shaka_player(element_id) {
    var player = videojs(element_id);
    player.pause();
}