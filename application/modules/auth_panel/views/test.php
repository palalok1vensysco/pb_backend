<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <title>videojs-contrib-hls embed</title>
        <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
        <script src="https://unpkg.com/video.js/dist/video.js"></script>
        <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>

    </head>
    <body>
        <video id="my_video_1" class="video-js vjs-default-skin" controls preload="auto" width="640" height="268" 
               data-setup='{}'>
            <source src="" type="application/x-mpegURL">
        </video>

        <script>
            var player = videojs('my_video_1');

            player.src({
                src: 'https://d1645ymx2ftw70.cloudfront.net/out/v1/ef67245fa23d4ea5ae05162663156109/index.m3u8',
                type: 'application/x-mpegURL',
//                withCredentials: true,
//                selectPlaylist:true
            });
        </script>

    </body>
</html>