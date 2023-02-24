<script>
    $(document).ready(function() {

        $("button#add-department").click(function() {

            $("div#add-department").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("div.add-department").html("");
            $.ajax({
                url: "<?= site_url('add_department_form') ?>",
                success: function(data) {

                    $("div.add-department").html(data);
                }
            });
        });

        $("form#add-department").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();

            $(`span.error-message`).text('');
            $("button#submit-btn").html('<img src="<?= base_url('assets/images/PleaseWait.gif'); ?>" alt="" width="16" height="16" border="0" /> <span>Please Wait....</span>');
            $("button#submit-btn").prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "<?= site_url('create_department'); ?>",
                data: formData,
                dataType: 'json',
                success: function(data) {

                    if (data.status === 406) {

                        let errors = data.errors
                        let i = 0;
                        for (const key in errors) {

                            if (++i === 1) {
                                $(`[name ='${key}']`).focus();
                            }

                            $(`span.${key}-error`).text(`${errors[key]}`);
                        }
                        $("button#submit-btn").html('Submit');
                        $("button#submit-btn").prop('disabled', false);

                    } else if (data.status === 200) {

                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: data.message,
                            buttons: {
                                OK: 'Ok'
                            },
                            callback: function(button) {
                                if (button == 'OK') {

                                    $("button#submit-btn").html('Submit');
                                    $("button#submit-btn").prop('disabled', false);
                                    location.reload();
                                }
                            }
                        });

                    } else {
                        console.log(data);
                    }
                }
            });
        });

        var dt_company = $("table#dt-company").DataTable({
            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "<?= site_url('company_list') ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-company').on('click', 'a.action', function() {

            let [action, id] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            let messej = `Are you sure you want to ${action} this company`;

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: messej,
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (action == 'delete') {

                            $.post("<?= site_url('delete_company') ?>", {
                                id
                            }, function(data, status) {

                                let response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: "Company has been deleted.",
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (action == 'activate' || action == 'deactivate') {

                            $.post("<?= site_url('update_company_status') ?>", {
                                action,
                                id
                            }, function(data, status) {

                                var response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Company has been ${action}.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else {

                            $("div#show-company").modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true
                            });

                            $("div.show-company").html("");
                            $.ajax({
                                type: "GET",
                                url: "<?= site_url('show_company') ?>",
                                data: {
                                    id
                                },
                                success: function(data) {

                                    $("div.show-company").html(data);
                                }
                            });
                        }
                    }
                }
            });
        });

        $("form#update-company").submit(function(e) {

            e.preventDefault();

            let formData = $(this).serialize();
            let company = $("input#edit-company").val();
            if (company.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Company Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (company.trim() == "") {

                                $("input[name = 'company']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('update_company') ?>",
                    data: formData,
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Company has been updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response.status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Company is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button#add-company").click(function() {

            $("div#add-company").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("input[name = 'company']").val('');
        });

        $("form#add-company").submit(function(e) {

            e.preventDefault();
            let company = $("input[name = 'company']").val();
            if (company.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Company Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (company.trim() == "") {

                                $("input[name = 'company']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_company') ?>",
                    data: {
                        company
                    },
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Company has been added!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response.status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Company is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        let dt_agency = $("table#dt_agency").DataTable({

            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "<?= site_url('agency_list') ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt_agency').on('click', 'a.action', function() {

            let [action, agency_code] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_agency.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            let messej = `Are you sure you want to ${action} this agency`;

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: messej,
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (action == 'delete') {

                            $.post("<?= site_url('delete_agency') ?>", {
                                agency_code: agency_code
                            }, function(data, status) {

                                var response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: "Agency has been deleted.",
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (action == 'activate' || action == 'deactivate') {

                            $.post("<?= site_url('agency_status') ?>", {
                                action,
                                agency_code
                            }, function(data, status) {

                                var response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Agency has been ${action}.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else {

                            $("div#show-agency").modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true
                            });

                            $("div.show-agency").html("");
                            $.ajax({
                                url: "<?= site_url('show_agency') ?>",
                                data: {
                                    agency_code
                                },
                                success: function(data) {

                                    $("div.show-agency").html(data);
                                }
                            });
                        }
                    }
                }
            });
        });

        $("form#update-agency").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();
            let agency = $("input#edit-agency").val();

            if (agency.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Agency Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (agency.trim() == "") {

                                $("input[name = 'agency']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('update_agency') ?>",
                    data: formData,
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Agency has been updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response.status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Agency is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button#add-agency").click(function() {

            $("div#add-agency").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("input[name = 'agency']").val('');
        });

        $("form#add-agency").submit(function(e) {

            e.preventDefault();
            let agency = $("input[name = 'agency']").val();

            if (agency.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Agency Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (agency.trim() == "") {

                                $("input[name = 'agency']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_agency') ?>",
                    data: {
                        agency: agency
                    },
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Agency has been added!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response.status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Agency is already exist"
                            });

                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        var dt_company_agency = $("table#dt-company-agency").DataTable({

            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "<?= site_url('companies_for_agency') ?>",
                type: "POST"
            },
            "order": [
                [0, "asc"],
                [1, "asc"]
            ],
            "columnDefs": [{
                "targets": [2],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-company-agency').on('click', 'i.action', function() {

            let [action, company_code] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company_agency.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Are you sure you want to untag this company?",
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $.post("<?= site_url('untag_company_agency') ?>", {
                            company_code: company_code
                        }, function(data, status) {

                            var response = JSON.parse(data);
                            if (response.status == "success") {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "Company has been untag in this agency.",
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            location.reload();
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            });
        });

        $("button#setup_under_agency").click(function() {

            $("div#setup_company_under_agency").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("div.companies").html("");
            $.ajax({
                type: "POST",
                url: "<?= site_url('choose_agency') ?>",
                success: function(data) {

                    $("div.agencies").html(data);
                }
            });
        });

        $("form#company-under-agency").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();

            $.post("<?= site_url('store_promo_locate_company') ?>", formData, function(data, status) {

                var response = JSON.parse(data);
                if (response.status == "success") {

                    $.alert.open({
                        type: 'warning',
                        title: 'Info',
                        icon: 'confirm',
                        cancel: false,
                        content: "Company has been setup under agency !",
                        buttons: {
                            OK: 'Yes'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                $("div#setup_company_under_agency").modal("hide");
                                setTimeout(() => {

                                    location.reload();
                                }, 1000);
                            }
                        }
                    });
                } else {

                    console.log(data);
                }
            });
        });

        let dt_products = $("table#dt-products").DataTable({

            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "<?= site_url('product_list') ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-products').on('click', 'a.action', function() {

            let [action, id] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_products.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            let messej = `Are you sure you want to ${action} this product`;

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: messej,
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (action == 'delete') {

                            $.post("<?= site_url('delete_product') ?>", {
                                id
                            }, function(data, status) {

                                var response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: "Product has been deleted.",
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (action == 'activate' || action == 'deactivate') {

                            $.post("<?= site_url('update_product_status') ?>", {
                                action,
                                id
                            }, function(data, status) {

                                var response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Product has been ${action}.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },

                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            });
                        } else {

                            if (!$(this).parents('tr').hasClass('selected')) {
                                dt_products.$('tr.selected').removeClass('selected');
                                $(this).parents('tr').addClass('selected');
                            }

                            $("div#update-product").modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true
                            });

                            $("div.update-product").html("");
                            $.ajax({
                                type: "GET",
                                url: "<?= site_url('show_product') ?>",
                                data: {
                                    id
                                },
                                success: function(data) {

                                    $("div.update-product").html(data);
                                }
                            });

                        }
                    }
                }
            });
        });

        $("form#update-product").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();
            let product = $("input#edit-product").val();

            if (product.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Product Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (product.trim() == "") {

                                $("input[name = 'product']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('update_product') ?>",
                    data: formData,
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Product has been updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response, status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Product is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button#add-product").click(function() {

            $("div#add-product").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("input[name ='product']").val("");
        });

        $("form#add-product").submit(function(e) {

            e.preventDefault();
            let product = $("input[name ='product']").val();
            if (product.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up product Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (product.trim() == "") {

                                $("input[name = 'product']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_product') ?>",
                    data: {
                        product
                    },
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Product has been added!",
                                buttons: {
                                    OK: 'Yes'
                                },
                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response.status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Product is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        let dt_company_product = $("table#dt-company-product").DataTable({

            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "<?= site_url('products_under_company') ?>",
                type: "POST"
            },
            "order": [
                [0, "asc"],
                [1, "asc"]
            ],
            "columnDefs": [{
                "targets": [2],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-company-product').on('click', 'i.action', function() {

            let [action, id] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company_product.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: `Are you sure you want to ${action} this product?`,
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $.post("<?= site_url('untag_product_company') ?>", {
                            id
                        }, function(data, status) {

                            var response = JSON.parse(data);
                            if (response.status == "success") {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "Company has been deleted.",
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            location.reload();
                                        }
                                    }
                                });
                            } else {
                                console.log(data);
                            }
                        });
                    }
                }
            });
        });

        $("button#setup-product-company").click(function() {

            $("div#setup_product_under_company").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("div.products").html("");
            $.ajax({
                type: "POST",
                url: "<?= site_url('choose_company') ?>",
                success: function(data) {

                    $("div.companies").html(data);
                }
            });
        });

        $("form#product-under-company").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();

            $.post("<?= site_url('store_promo_company_products') ?>", formData, function(data, status) {

                var response = JSON.parse(data);
                if (response.status == "success") {

                    $.alert.open({
                        type: 'warning',
                        title: 'Info',
                        icon: 'confirm',
                        cancel: false,
                        content: "Product has been setup under company!",
                        buttons: {
                            OK: 'Yes'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                $("div#setup_product_under_company").modal("hide");
                                setTimeout(() => {

                                    location.reload();
                                }, 1000);
                            }
                        }
                    });
                } else {

                    console.log(data);
                }
            });
        });

        $("input[name = 'supervisor']").keyup(function(e) {

            let str = $(this).val().trim();
            $(".search-results").hide();

            if (str == '') {

                $(".search-results").hide();
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('find_active_supervisor'); ?>",
                    data: {
                        str
                    },
                    success: function(data) {

                        if (data.trim() != "No Result Found") {

                            $(".search-results").show().html(data);
                        } else {

                            $(".search-results").hide();
                        }
                    }
                });
            }
        });

        $("select[name = 'store']").change(function() {

            let [id, field] = $(this).val().split('/');
            $.ajax({
                type: "POST",
                url: "<?= site_url('fetch_assigned_department') ?>/" + id,
                success: function(data) {

                    $("select[name = 'department']").prop('disabled', false);
                    $("select[name = 'department']").html(data);
                }
            });
        });

        $("select[name = 'department']").change(function() {

            let [id, field] = $("select[name = 'store']").val().split('/');
            let department = $(this).val();
            let rater = $("input[name = 'rater']").val();

            $("div#load-gif").show();
            $.ajax({
                type: "POST",
                url: "<?= site_url('employee_list') ?>",
                data: {
                    field,
                    department,
                    rater
                },
                success: function(data) {

                    $("div#load-gif").hide();
                    $("div.employee-list").html(data);
                }
            });
        });

        $("form#add-subordinates").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();
            let rater = $("input[name = 'rater']").val();
            let employees = [];

            $("input[name = 'employees[]']:checked").each(function() {
                employees.push($(this).val());
            });

            if (employees.length === 0) {

                errDup("You need to check a subordinate to be added!");
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_subordinates') ?>",
                    data: formData,
                    success: function(data) {

                        response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Subordinate(s) Successfully Added",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("div#add-subordinates").modal("hide");
                                        list_of_subordinates(rater);
                                    }

                                }
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        let dt_business_unit = $("table#dt-business-unit").DataTable({

            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "<?= site_url('business_unit_list') ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                "targets": [7],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-business-unit').on('change', 'select.status', function() {

            let id = this.id;
            let [column, bunit_id] = this.id.split("-");
            let status = $(this).val();

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_business_unit.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            let messej = `Are you sure you want to update ${column} to ${status}?`;

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: messej,
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {


                        $.ajax({
                            type: "POST",
                            url: "<?= site_url('update_business_unit_status') ?>",
                            data: {
                                column,
                                bunit_id,
                                status
                            },
                            success: function(data) {

                                let response = JSON.parse(data);
                                if (response.status == 'success') {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Business Unit ${column} has been updated.`,
                                        buttons: {
                                            OK: 'Yes'
                                        },
                                        callback: function(button) {
                                            if (button == 'OK') {

                                                location.reload();
                                            }

                                        }
                                    });
                                } else {
                                    console.log(data);
                                }
                            }
                        });
                    } else {

                        if (status == 'active') {
                            $(`select#${id}`).val('inactive');
                        } else {
                            $(`select#${id}`).val('active');
                        }
                    }
                }
            });
        });

        $('table#dt-business-unit').on('click', 'a.action', function() {

            let [action, bunit_id] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_business_unit.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            let messej = `Are you sure you want to ${action} this business unit?`;

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: messej,
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $("div#show-business-unit").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });

                        $("div.show-business-unit").html("");
                        $.ajax({
                            url: "<?= site_url('show_business_unit') ?>/" + bunit_id,
                            success: function(data) {

                                $("div.show-business-unit").html(data);
                            }
                        });
                    }
                }
            });
        });

        $("form#update-business-unit").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();
            let bunit_name = $("input[name = 'bunit_name']").val();
            let bunit_field = $("input[name = 'bunit_field']").val();
            let bunit_acronym = $("input[name = 'bunit_acronym']").val();
            let hrd_location = $("select[name = 'hrd_location']").val();
            if (bunit_name.trim() == '' || bunit_field.trim() == '' || bunit_acronym.trim() == '' || hrd_location.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (bunit_name.trim() == '') {

                                $("input[name = 'bunit_name']").css("border-color", "#dd4b39");
                            }

                            if (bunit_field.trim() == '') {

                                $("input[name = 'bunit_field']").css("border-color", "#dd4b39");
                            }

                            if (bunit_acronym.trim() == '') {

                                $("input[name = 'bunit_acronym']").css("border-color", "#dd4b39");
                            }

                            if (hrd_location.trim() == '') {

                                $("select[name = 'hrd_location']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $("button#update-btn").prop('disabled', true);
                $("button#update-btn").html('Please Wait...');

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('update_promo_business_unit') ?>",
                    data: formData,
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $("button#update-btn").prop('disabled', false);
                            $("button#update-btn").html('Update');

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Business Unit has been successfully updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });

                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button#add-business-unit").click(function() {

            $("div#add-business-unit").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $.ajax({
                url: "<?= site_url('add_businessUnit_form') ?>",
                success: function(data) {

                    $("div.add-business-unit").html(data);
                }
            });
        });

        $("form#add-business-unit").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();
            let bunit_name = $("input[name = 'bunit_name']").val();
            let bunit_field = $("input[name = 'bunit_field']").val();
            let bunit_acronym = $("input[name = 'bunit_acronym']").val();
            let hrd_location = $("select[name = 'hrd_location']").val();
            if (bunit_name.trim() == '' || bunit_field.trim() == '' || bunit_acronym.trim() == '' || hrd_location.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (bunit_name.trim() == '') {

                                $("input[name = 'bunit_name']").css("border-color", "#dd4b39");
                            }

                            if (bunit_field.trim() == '') {

                                $("input[name = 'bunit_field']").css("border-color", "#dd4b39");
                            }

                            if (bunit_acronym.trim() == '') {

                                $("input[name = 'bunit_acronym']").css("border-color", "#dd4b39");
                            }

                            if (hrd_location.trim() == '') {

                                $("select[name = 'hrd_location']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $("button#add-btn").prop('disabled', true);
                $("button#add-btn").html('Please Wait...');

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_promo_business_unit') ?>",
                    data: formData,
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $("button#add-btn").prop('disabled', false);
                            $("button#add-btn").html('Submit');

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Business Unit has been successfully added!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });

                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

    });

    function company_list(agency_code) {

        if (agency_code) {

            $("div.companies").html('<img src="<?= base_url('assets/images/gif/loading.gif') ?>"> <span>Please Wait...</span>');
            $.ajax({
                type: "GET",
                url: "<?= site_url('tag_company_agency') ?>",
                data: {
                    agency_code
                },
                success: function(result) {

                    $("div.companies").html(result);
                }
            });
        } else {

            $("div.companies").html('');
        }
    }

    function product_list(company) {

        if (company) {

            $("div.products").html('<img src="<?= base_url('assets/images/gif/loading.gif') ?>"> <span>Please Wait...</span>');
            $.ajax({
                type: "GET",
                url: "<?= site_url('tag_product_company') ?>",
                data: {
                    company
                },
                success: function(result) {

                    $("div.products").html(result);
                }
            });
        } else {

            $("div.products").html('');
        }
    }

    function getEmpId(supervisor) {

        let [emp_id, name] = supervisor.split('*');

        $("input[name='supervisor']").val(supervisor);
        $("input[name = 'rater']").val(emp_id.trim());
        $(".search-results").hide();

        $.ajax({
            url: "<?php echo site_url('supervisor_details'); ?>",
            data: {
                emp_id: emp_id.trim()
            },
            success: function(data) {

                $("div.supervisor-details").html(data);
                list_of_subordinates(emp_id.trim());
            }
        });
    }

    function list_of_subordinates(emp_id) {

        $("div#loading-gif").show();
        $.ajax({
            type: "GET",
            url: "<?= site_url('list_of_subordinates') ?>",
            data: {
                emp_id
            },
            success: function(data) {

                $("div#loading-gif").hide();
                $("div.subordinates").html(data);
            }
        });
    }

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#d2d6de");
    }

    function remove_sub() {

        let subordinates = [];
        let [emp_id, name] = $("input[name = 'supervisor']").val().split('*');
        $("input[name= 'subordinates[]']:checked").each(function() {

            subordinates.push($(this).val());
        })

        if (subordinates.length === 0) {

            errDup("You need to check atleast one subordinate to remove!");
        } else {

            $.ajax({
                type: "POST",
                url: "<?= site_url('remove_subordinates') ?>",
                data: {
                    subordinates
                },
                success: function(data) {

                    let response = JSON.parse(data);
                    if (response.status == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Subordinate(s) Successfully Removed",
                            buttons: {
                                OK: 'Yes'
                            },
                            callback: function(button) {
                                if (button == 'OK') {

                                    list_of_subordinates(emp_id.trim());
                                }

                            }
                        });
                    } else {

                        console.log(data);
                    }
                }
            });
        }
    }

    function addSubordinates() {

        $("div#add-subordinates").modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }
</script>