<script>
    $(document).ready(function() {

        let dt_resignation_list = $("table#dt-resignation-list").DataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "order": [],

            "ajax": {
                url: "<?= site_url('resignation_list') ?>", // json datasource
                type: "post", // method  , by default get
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("table#dt-resignation-list").append('<tbody><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                    $("#dt-resignation-list_processing").css("display", "none");

                }
            }
        });

        $('table#dt-resignation-list').on('click', 'button.action', function() {

            let [action, emp_id, termination_no] = this.id.split("_");
            console.log(action, emp_id, termination_no);

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_resignation_list.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            if (action == 'upload') {

                $("div#upload-resignation-letter").modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                $("div.upload-resignation-letter").html("");
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('upload_resignation_letter') ?>",
                    data: {
                        emp_id,
                        termination_no
                    },
                    success: function(data) {

                        $("div.upload-resignation-letter").html(data);
                    }
                });

            } else {

                $("div#view-resignation-letter").modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                $("div.view-resignation-letter").html("");
                $.ajax({
                    type: "GET",
                    url: "<?= site_url('show_resignation_letter') ?>",
                    data: {
                        termination_no
                    },
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == 'success') {

                            $("div.view-resignation-letter").html("<center><img class='img-responsive' src='" + response.image + "' alt='Photo'></center>");
                        } else {
                            console.log(data);
                        }
                    }
                });
            }
        });

        $("form#upload-resignation-letter").submit(function(e) {

            e.preventDefault();
            let formData = new FormData(this);
            let resignation = $("input[name = 'resignation']").val();
            if (resignation == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (resignation == "") {
                                $("input[name = 'resignation']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });

            } else {

                $.ajax({
                    url: "<?= site_url('submit_resignation_letter') ?>",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Resignation Letter Successfully Uploaded!",
                                buttons: {
                                    OK: 'Yes'
                                },
                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("div#upload-resignation-letter").modal("hide");
                                        location.reload();
                                    }

                                }
                            });
                        } else {

                            console.log(data);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });
    });

    function readURL(input, upload) {

        $('#clear' + upload).show();
        var res = validateForm(upload);

        if (res != 1) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo' + upload).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            $('#clear' + upload).hide();
            $('#photo' + upload).removeAttr('src');
        }
    }

    function validateForm(imgid) {
        var img = $("#" + imgid).val();
        var res = '';
        var i = img.length - 1;
        while (img[i] != ".") {
            res = img[i] + res;
            i--;
        }

        //checks the file format
        if (res != "PNG" && res != "jpg" && res != "JPG" && res != "png") {
            $("#" + imgid).val("");
            errDup('Invalid File Format. Take note on the allowed file!');
            return 1;
        }

        //checks the filesize- should not be greater than 2MB
        var uploadedFile = document.getElementById(imgid);
        var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;
        if (fileSize == false) {
            $("#" + imgid).val("");
            errDup('The size of the file exceeds 2MB!')
            return 1;
        }
    }

    function clears(file, preview, clrbtn) {

        $("#" + file).val("");
        $('#' + preview).removeAttr('src');
        $('#' + clrbtn).hide();
    }
</script>