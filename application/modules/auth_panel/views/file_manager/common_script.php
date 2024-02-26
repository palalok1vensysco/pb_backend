<script>   
    //Searching Course to attach file
    if ($('#selected_course').length > 0) {
        $('#selected_course').select2({
            placeholder: 'Select an Course',
            theme: "classic",
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }
    
    $(document).on("click", ".remove_file_from_topic", function () {
        var selector = $(this);
        let file_id = selector.attr("file_id");
        let courseId = selector.attr("course_id");
        $.ajax({
            url: '<?= AUTH_PANEL_URL ?>course_product/course/ajax_remove_file_from_topic/' + file_id + '?course_id=' + courseId,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                if (data.data == 1) {
                    selector = selector.parent().remove();
                    if (data.topic_type == "video") {
                        show_toast("success", "Video Removed Successfully", "Video Remove");
                    } else {
                        show_toast("success", "Topic Removed Successfully", "Topic Remove");
                    }
                    $('.refresher').show();
                } else {
                    show_toast("warning", data.title, data.message);
                }
            }
        });
    });
    
    $(document).on('click', '.virtual-name-changer', function () {
        var m = $(this).parent().parent('.set_virtual_name');

        var info = {
            id: m.children("input[name=for_name_id]").val(),
            v_name: m.find("input[name=v_name]").val(),
            v_name_2: m.find("input[name=v_name_2]").val()
        }
        $.ajax({
            url: "<?= AUTH_PANEL_URL ?>course_product/course/add_virtual_name",
            type: "POST",
            dataType: 'json',
            data: info,
            success: function (data) {
                if (data.status == true) {
                    show_toast('success', 'Virtual name set successfully !!', 'Name Updated ');
                }
            },
            error: function (data) {
                show_toast('error', 'Please try after some time ', 'Name not Updated ');
            }
        });
    });
</script>