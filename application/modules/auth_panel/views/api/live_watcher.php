<style>
    pre {
        background-color: unset;
        border:unset;
    }
    .string {
        color: darkviolet;
    }
    .number {
        color: darkorange;
    }
    .boolean {
        color: darksalmon;
    }
    .null {
        color: magenta;
    }
    .key {
        color: #1f1da3;
    }
    .actions{
        display: none;
    }
    .card{
        border: none;
        box-shadow: none;
        margin-bottom: 20px;
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    .card-body {
        flex: 1 1 auto;
        padding: 1.25rem;
    }
    .card-header {
        border-color: #eff2f7;
        background: #fff;
        font-size: 16px;
        font-weight: 300;
    }
    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
</style>
<script src="<?= AUTH_ASSETS ?>new/socket.js"></script>
<div class="col-lg-12 no-padding">
    <nav aria-label="breadcrumb">
        <input hidden="" id="s_no" value="0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span id="show_socket_state"><i class="fa fa-spin fa-refresh"></i> Connection Please Wait...</span>
            </li>
            <li class="breadcrumb-item pull-right">
                <label>
                    <div class="box-tools actions">
                        Syncing
                        <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                            <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button>
                            <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
                        </div>
                    </div>
                </label>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <label>Filter User Id</label>
                <input placeholder="Enter User Id" class="input-xs number" id="filter">
            </li>
            <li class="pull-right">
                <button class="btn btn-xs btn-danger" onclick="$('.content').html('');$('#s_no').val(0)">Clear Screen</button>
            </li>
        </ol>
    </nav>
    <div class="content">
    </div>
</div>
<script type="text/javascript" language="javascript" >
    $("#realtime button").click(function () {
        if (!$(this).hasClass("active"))
            $("#realtime button").toggleClass("active");
    });

    $(document).on("click", '.delete_response', function () {
        $(this).parent().parent().remove();
    });

    var socket = io.connect('<?= WEB_SOCKET_IP ?>', {
        reconnectionDelay: 5000,
        reconnectionAttempts: 3
    });
    


    socket.on('live_watcher', function (response) {
        console.log(response);
        var data = JSON.parse(response);
        if ($("#realtime button.active").text() == "Off") {
            console.log("API Missing");
            return false;
        }
        if ($("#filter").val() == "" || $("#filter").val() == data.header.Userid) {
            var s_no = parseInt($("#s_no").val()) + 1;
            $("#s_no").val(s_no);
            var html = '<section class="card"><header class="card-header">' + s_no + ') <i class="fa fa-internet-explorer"></i><label class="box-title"> URL- ' + data.url + '</label><button class="btn btn-xs btn-danger pull-right delete_response" style="margin-left :2%;">Delete</button><span class="pull-right">TIME- ' + data.time + '</span></header><div class="card-body"><div class="alert alert-danger">HEADER-<pre>' + syntaxHighlight(JSON.stringify(data.header, undefined, 4)) + '</pre></div><div class="alert alert-success">INPUT- <pre>' + syntaxHighlight(JSON.stringify(data.input, undefined, 4)) + '</pre></div><div class="alert alert-info">RESPONSE- <pre>' + syntaxHighlight(JSON.stringify(data.response, undefined, 4)) + '</pre></div></div></div></section>';
            $(".content").prepend(html);
        } else {
//            show_toast("warning", "Warning!", "Missed Response With User Id-" + data.header.user_id);
        }
    });
    socket.on('connect', function (user) {
        $(".actions").show();
        $('#show_socket_state').html('<i class="fa fa-check" aria-hidden="true"></i> You are connected to server.');
    });

    socket.on('disconnect', function (user) {
        $(".actions").hide();
        $('#show_socket_state').html('<i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i> Please Wait while we connecting you to server. ');
    });

    function syntaxHighlight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
    }

    $("#filter").keyup(function(){
        sessionStorage.setItem("user_id",$(this).val());
    });
    
    let user_id = sessionStorage.getItem("user_id");
    if(user_id!=undefined && user_id !=null){
        $("#filter").val(user_id);
    }
</script>