<script>
    $(function() {

        $("button.extend-contract").click(function() {

            $("div#extend").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("button.proceed-extend").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: "<?= site_url('extend_contract') ?>",
                success: function(data) {

                    $("div.extend").html(data);
                }
            });
        })

        $('button.proceed-extend').click(function() {

            let empId = $("[name = 'employee']").val().split("*");
            if (empId[1].trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (empId[1].trim() == "") {

                                $("[name = 'employee']").css("border-color", "#dd4b39");
                            }
                        }

                    }
                });
            } else {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Process to renewal process now?",
                    buttons: {
                        OK: 'Yes',
                        NO: 'Not now'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            window.location = "<?= base_url('placement/page/menu/contract/process-renewal/') ?>" + empId[0].trim();
                        }

                    }
                });
            }
        });

        $("form#dataProcessRenewal").submit(function(e) {

            e.preventDefault();

            let formData = new FormData(this);
            let edited = $("input[name = 'edited']").val();
            let companyDuration = $("input[name = 'companyDuration']").val();
            let startdate = $("input[name = 'startdate']").val();
            let eocdate = $("input[name = 'eocdate']").val();
            let duration = $("input[name = 'duration']").val();

            let company = "";
            let promo_type = "";
            let department = "";
            let vendor = "";
            let position = "";
            let position_level = "";
            let emp_type = "";
            let contract_type = "";

            let intros = $("input[name='bunit_intro[]']").map(function() {
                return this.value;
            }).get();

            let intro_msg = '';
            intros.forEach(i => {

                const intro = $(`input[name = '${i}']`).val();
                if (!intro) {
                    intro_msg = 'true';
                }
            });

            let stores = $("input[name='stores[]']:checked").map(function() {
                return this.value;
            }).get();

            if (edited == 'true') {

                company = $("select[name = 'company_select']").val();
                promo_type = $("select[name = 'promoType_select']").val();
                department = $("select[name = 'department_select']").val();
                vendor = $("[name = 'vendor_select']").val();
                position = $("select[name = 'position_select']").val();
                position_level = $("input[name = 'positionlevel_select']").val();
                emp_type = $("select[name = 'empType_select']").val();
                contract_type = $("select[name = 'contractType_select']").val();
            } else {

                department = $("input[name = 'department']").val();
                contract_type = $("input[name = 'contractType']").val();
            }

            if ((edited == 'true' && (company == '' || promo_type == '' || department == '' || vendor == '' || position == '')) || intro_msg == 'true' || stores.length == 0 || (companyDuration == "" && (contract_type == "Seasonal" || department == "HOME AND FASHION" || department == "FIXRITE" || department == "EASY FIX")) || startdate == '' || eocdate == '' || duration == '') {

                if (stores.length == 0) {

                    errDup("Please select Business Unit!");
                } else if (edited == "true" && promo_type == 'ROVING' && stores.length < 2) {

                    errDup("Please add another Business Unit for setup!");
                } else {

                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons: {
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                if (edited == "true" && company == "") {

                                    $("select[name = 'company_select']").css("border-color", "#dd4b39");
                                }

                                if (edited == "true" && promo_type == "") {

                                    $("select[name = 'promoType_select']").css("border-color", "#dd4b39");
                                }

                                if (edited == "true" && vendor == "") {

                                    $("span.select2-selection--single").css("border-color", "#dd4b39");
                                }

                                if (edited == "true" && position == "") {

                                    $("select[name = 'position_select']").css("border-color", "#dd4b39");
                                }

                                if ((companyDuration == "" && (contract_type == "Seasonal" || department == "HOME AND FASHION" || department == "FIXRITE" || department == "EASY FIX"))) {

                                    $("input[name = 'companyDuration']").css("border-color", "#dd4b39");
                                }

                                if (startdate == "") {

                                    $("input[name = 'startdate']").css("border-color", "#dd4b39");
                                }

                                if (eocdate == "") {

                                    $("input[name = 'eocdate']").css("border-color", "#dd4b39");
                                }

                                if (duration == "") {

                                    $("input[name = 'duration']").css("border-color", "#dd4b39");
                                }

                                if (intro_msg == "true") {

                                    intros.forEach(i => {

                                        const intro = $(`input[name = '${i}']`).val();
                                        if (!intro) {

                                            $("input[name = '" + intro + "']").css("border-color", "#dd4b39");
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            } else {

                $.ajax({
                    url: "<?= site_url('process_renewal') ?>",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == "success") {

                            $("div#printContractAndPermit").modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true
                            });

                            let empId = $("input[name = 'empId']").val();
                            $.ajax({
                                url: "<?= site_url('print_contract_permit/') ?>" + empId,
                                success: function(data) {

                                    $("div.printContractAndPermit").html(data);
                                }
                            });
                        } else {

                            console.log(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });

        $("button.close-event").click(function() {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Are you sure you want to exit?",
                buttons: {
                    OK: 'Ok',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        window.location = "<?= base_url('placement/page/menu/contract/renewal') ?>";
                    }

                }
            });
        });

        $("form#generate_permit").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();

            let empId = $("input[name = 'empId']").val();
            let recordNo = $("input[name = 'record_no']").val();
            let store = $("select[name = 'storeName']").val();
            let dutyDays = $("input[name = 'dutyDays']").val();
            let dutySched = $("[name = 'dutySched']").val();
            let specialSched = $("select[name = 'specialSched']").val();
            let specialDays = $("input[name = 'specialDays']").val();
            let dayOff = $("select[name = 'dayOff']").val();
            let cutOff = $("input[name = 'cutOff']").val();

            let table1 = "employee3";
            let table2 = "promo_record";

            if (store == "" || dutyDays == "" || dutySched == "" || dayOff == "" || (specialSched != "" && specialDays == "")) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (store == "") {

                                $("select[name = 'storeName']").css("border-color", "#dd4b39");
                            }

                            if (dutyDays == "") {

                                $("input[name = 'dutyDays']").css("border-color", "#dd4b39");
                            }

                            if (dutySched == "") {

                                $("[name = 'dutySched']").css("border-color", "#dd4b39");
                            }

                            if (specialSched != "" && specialDays == "") {

                                $("input[name = 'specialDays']").css("border-color", "#dd4b39");
                            }

                            if (dayOff == "") {

                                $("select[name = 'dayOff']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                dutyDays = dutyDays.replace(/ &/g, ",");
                specialDays = specialDays.replace(/ &/g, ",");

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_duty_details') ?>",
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == "success") {

                            window.open("http://172.16.43.134:81/hrms/report/promo_permit_towork.php?recordNo=" + recordNo + "&empId=" + empId + "&store=" + store + "&dutySched=" + dutySched + "&specialSched=" + specialSched + "&dutyDays=" + dutyDays + "&specialDays=" + specialDays + "&dayoff=" + dayOff + "&table1=" + table1 + "&table2=" + table2);
                        } else {
                            console.log(data);
                        }
                    }
                });

            }
        });

        $("form#print-current-permit").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();

            let empId = $("input[name = 'empId']").val();
            let recordNo = $("input[name = 'record_no']").val();
            let store = $("select[name = 'storeName']").val();
            let dutyDays = $("input[name = 'dutyDays']").val();
            let dutySched = $("[name = 'dutySched']").val();
            let specialSched = $("select[name = 'specialSched']").val();
            let specialDays = $("input[name = 'specialDays']").val();
            let dayOff = $("select[name = 'dayOff']").val();
            let cutOff = $("input[name = 'cutOff']").val();

            let table1 = "employee3";
            let table2 = "promo_record";

            if (store == "" || dutyDays == "" || dutySched == "" || dayOff == "" || (specialSched != "" && specialDays == "")) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (store == "") {

                                $("select[name = 'storeName']").css("border-color", "#dd4b39");
                            }

                            if (dutyDays == "") {

                                $("input[name = 'dutyDays']").css("border-color", "#dd4b39");
                            }

                            if (dutySched == "") {

                                $("[name = 'dutySched']").css("border-color", "#dd4b39");
                            }

                            if (specialSched != "" && specialDays == "") {

                                $("input[name = 'specialDays']").css("border-color", "#dd4b39");
                            }

                            if (dayOff == "") {

                                $("select[name = 'dayOff']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                dutyDays = dutyDays.replace(/ &/g, ",");
                specialDays = specialDays.replace(/ &/g, ",");

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_duty_details') ?>",
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == "success") {

                            window.open("http://172.16.43.134:81/hrms/report/promo_permit_towork.php?recordNo=" + recordNo + "&empId=" + empId + "&store=" + store + "&dutySched=" + dutySched + "&specialSched=" + specialSched + "&dutyDays=" + dutyDays + "&specialDays=" + specialDays + "&dayoff=" + dayOff + "&table1=" + table1 + "&table2=" + table2);
                        } else {
                            console.log(data);
                        }
                    }
                });

            }
        });

        $("form#generate_contract").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();

            let empId = $("input[name = 'empId']").val();
            let recordNo = $("input[name = 'contract_recordNo']").val();
            let witness1 = $("input[name = 'witness1Renewal']").val();
            let witness2 = $("input[name = 'witness2Renewal']").val();
            let contractHeader = $("select[name = 'contractHeader']").val();
            let contractDate = $("input[name = 'contractDate']").val();
            let clear = $("input[name = 'clear']").val();

            let clear1 = "";
            let clear2 = "";
            let table1 = "employee3";
            let table2 = "promo_record";

            if ($("input#clear1").is(':checked')) {

                clear1 = "true";
            }

            if ($("input#clear2").is(':checked')) {

                clear2 = "true";
            }

            let issuedOn = "";
            let sss = "";
            let cedula = "";
            let issuedAt = "";
            if (clear1 == "true") {

                cedula = $("input[name = 'cedula']").val();
                issuedOn = $("input[name = 'issuedOn']").val();
                issuedAt = $("input[name = 'issuedAt']").val();
            } else {

                sss = $("input[name = 'sss']").val();
                issuedAt = $("input[name = 'issuedAt']").val();
            }

            if (witness1 == "" || witness2 == "" || (clear1 == "" && clear2 == "") || contractHeader == "" || contractDate == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (witness1 == "") {

                                $("input[name = 'witness1Renewal']").css("border-color", "#dd4b39");
                            }

                            if (witness2 == "") {

                                $("input[name = 'witness2Renewal']").css("border-color", "#dd4b39");
                            }

                            if (contractHeader == "") {

                                $("select[name = 'contractHeader']").css("border-color", "#dd4b39");
                            }

                            if (contractDate == "") {

                                $("input[name = 'contractDate']").css("border-color", "#dd4b39");
                            }
                        }

                    }
                });
            } else {

                var chk = "false";
                if (clear1 == "true") {


                    if (cedula == "" || issuedOn == "" || issuedAt == "") {

                        chk = "true";
                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: "Please Fill-up Required Fields!",
                            buttons: {
                                OK: 'Ok'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    if (cedula == "") {

                                        $("input[name = 'cedula']").css("border-color", "#dd4b39");
                                    }

                                    if (issuedOn == "") {

                                        $("input[name = 'issuedOn']").css("border-color", "#dd4b39");
                                    }

                                    if (issuedAt == "") {

                                        $("input[name = 'issuedAt']").css("border-color", "#dd4b39");
                                    }
                                }

                            }
                        });
                    }
                }

                if (clear2 == "true") {

                    if (sss == "" || issuedAt == "") {

                        chk = "true";
                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: "Please Fill-up Required Fields!",
                            buttons: {
                                OK: 'Ok'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    if (sss == "") {

                                        $("input[name = 'sss']").css("border-color", "#dd4b39");
                                    }

                                    if (issuedAt == "") {

                                        $("input[name = 'issuedAt']").css("border-color", "#dd4b39");
                                    }
                                }

                            }
                        });
                    }
                }

                if (chk == "false") {

                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('store_witness_otherdetails') ?>",
                        data: formData,
                        success: function(data) {

                            response = JSON.parse(data);
                            if (response.status == 'success') {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: response.message,
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            window.open("http://172.16.43.134:81/hrms/report/promo_contract.php?empId=" + empId + "&&recordNo=" + recordNo + "&&table1=" + table1 + "&&table2=" + table2);
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
        });

        $("button.current-permit").click(function() {

            $("div#current-permit").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("input[name = 'permit']").val("current");
            $.ajax({
                type: "POST",
                url: "<?= site_url('print_current_permit') ?>",
                success: function(data) {

                    $("div.current-permit").html(data);
                }
            });
        });

        $("button.previous-permit").click(function() {

            $("div#current-permit").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("input[name = 'permit']").val("current");
            $.ajax({
                type: "POST",
                url: "<?= site_url('print_current_permit') ?>",
                success: function(data) {

                    $("div.current-permit").html(data);
                }
            });
        });
    });

    function edit_renew_control() {
        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure do you want to edit this records?",
            buttons: {
                OK: 'Ok',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $("a#cancel_new").show();
                    $("a#edit_new").hide();
                    $(".inputLabel").hide();
                    $(".inputSelect").show();
                    $(".checkedEnable").prop("disabled", false);
                    $("[name = 'edited']").val("true");

                    let empId = $("[name = 'empId']").val();
                    let recordNo = $("[name = 'recordNo']").val();
                    let agency = $("[name = 'agency']").val();
                    let company = $("[name = 'company']").val();
                    let promo_company = $("[name = 'company_name']").val();
                    let promoType = $("[name = 'promoType']").val();
                    let department = $("[name = 'department']").val();
                    let vendor = $("[name = 'vendor']").val();
                    let product = $("[name = 'product']").val();
                    let position = $("[name = 'position']").val();
                    let empType = $("[name = 'empType']").val();
                    let contractType = $("[name = 'contractType']").val();
                    let statCut = $("[name = 'cutoff']").val();

                    // for agency 
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_agency') ?>",
                        data: {
                            agency: agency
                        },
                        success: function(data) {

                            $("select[name='agency_select']").html(data);
                        }
                    });

                    // for company 
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_company') ?>",
                        data: {
                            promo_company: promo_company,
                            agency_code: agency
                        },
                        success: function(data) {

                            $("select[name='company_select']").html(data);
                        }
                    });

                    // for promo type 
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_promo_type') ?>",
                        data: {
                            promoType
                        },
                        success: function(data) {

                            $("select[name='promoType_select']").html(data);
                        }
                    });

                    let stores = $("input[name='stores[]']:checked").map(function() {
                        return this.value;
                    }).get();

                    // for department
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_department') ?>",
                        data: {
                            stores,
                            department
                        },
                        success: function(data) {

                            $("select[name='department_select']").html(data);
                        }
                    });

                    // for vendor
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_vendor') ?>",
                        data: {
                            department,
                            vendor
                        },
                        success: function(data) {

                            $("[name='vendor_select']").html(data);
                            $("select[name = 'vendor_select']").addClass('select2');
                        }
                    });

                    // for product
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('load_products') ?>",
                        data: {
                            company,
                            product
                        },
                        success: function(data) {

                            $("div.product_select").html(data);
                        }
                    });

                    // for cutoff
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_cutoff') ?>",
                        data: {
                            statCut
                        },
                        success: function(data) {

                            $("select[name = 'cutoff_select']").html(data);
                        }
                    });

                    // for position 
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_position') ?>",
                        data: {
                            position
                        },
                        success: function(data) {

                            $("select[name='position_select']").html(data);
                        }
                    });

                    // for position level
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('select_position_level') ?>",
                        data: {
                            position
                        },
                        success: function(data) {

                            data = JSON.parse(data);
                            $("input[name='positionlevel_select']").val(data.level_no);
                            $("input[name='level']").val(data.level_no);
                        }
                    });
                }

            }
        });
    }

    function cancel_renew_control() {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure do you want to cancel the edit transaction?",
            buttons: {
                OK: 'Ok',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $("#cancel_new").hide();
                    $("#edit_new").show();
                    $(".inputLabel").show();
                    $(".inputSelect").hide();
                    $(".checkedEnable").prop("disabled", true);
                    $("[name = 'edited']").val("false");

                    var promoType = $("input[name = 'promoType']").val();
                    var empId = $("input[name = 'empId']").val();

                    // for business unit
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('load_promo_business_unit') ?>",
                        data: {
                            empId: empId,
                            promoType: promoType
                        },
                        success: function(data) {

                            $(".store").html(data);
                        }
                    });

                    // for intro
                    $.ajax({
                        type: "GET",
                        url: "<?= site_url('load_promo_intro') ?>",
                        data: {
                            empId
                        },
                        success: function(data) {

                            $("#promoIntro").html(data);
                        }
                    });
                }
            }
        });
    }

    function search_name(key) {

        let str = key.trim();
        if (str) {
            $.ajax({
                type: "POST",
                url: "<?= site_url('find_iextend_promo') ?>",
                data: {
                    str: str,
                    promo_type: 'all'
                },
                success: function(data) {

                    if (data) {
                        $("div.search-results").show().html(data);
                    }
                }
            });
        } else {

            $("div.search-results").hide();
        }
    }

    function getExtendEmpId(empId) {

        $("input[name='employee']").val(empId);
        $("div.search-results").hide();
        $("button.proceed-extend").prop('disabled', false);
    }

    function select_product(company) {

        if (company != '') {

            $.ajax({
                url: "<?= site_url('select_promo_products') ?>",
                data: {
                    company
                },
                success: function(data) {

                    $("div.product_select").html(data);
                }
            });
        } else {

            $("div.product_select").html('');
        }
    }

    function input_company_duration(contract_type) {

        if (contract_type == 'Seasonal') {
            $(".companyDuration").show();
        } else {
            $(".companyDuration").hide();
        }
    }

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#ccc");
    }

    function inputStartdate() {

        $("[name = 'startdate']").css("border-color", "#ccc");
        $("[name = 'eocdate']").val("");
    }

    function durationContract(eocdate) {

        var dF = $("input[name = 'startdate']").val();
        var dT = eocdate;
        $("input[name = 'eocdate']").css("border-color", "#ccc");

        if (dF == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Startdate First!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $("input[name = 'eocdate']").val("");
                    }
                }
            });
        } else {

            $.ajax({
                type: "GET",
                url: "<?= site_url('contract_duration') ?>",
                data: {
                    dF,
                    dT
                },
                success: function(data) {

                    let response = JSON.parse(data);
                    if (response.message == 'success') {

                        $("input.duration").val(response.duration);
                    } else {

                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: response.message,
                            buttons: {
                                OK: 'Ok'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("input[name = 'eocdate']").val("");
                                }
                            }
                        });
                    }
                }
            });
        }
    }

    function position_level(position) {

        $.ajax({
            type: "GET",
            url: "<?= site_url('select_position_level') ?>",
            data: {
                position
            },
            success: function(data) {

                data = JSON.parse(data);
                $("input[name='positionlevel_select']").val(data.level_no);
                $("input[name='level']").val(data.level_no);
            }
        });
    }

    function vendor_list(department) {

        if (department == "HOME AND FASHION" || department == "FIXRITE" || department == "EASY FIX") {

            $(".companyDuration").show();
        } else {

            var type = $("select[name = 'contractType_select']").val();
            if (type == "Seasonal") {

                $(".companyDuration").show();
            } else {

                $(".companyDuration").hide();
            }
        }

        $.ajax({
            type: "GET",
            url: "<?= site_url('select_vendor') ?>",
            data: {
                department
            },
            success: function(data) {

                $("[name = 'vendor']").html(data);
            }
        });
    }

    function select_business_unit(promo_type) {

        $("select[name = 'promoType_select']").css("border-color", "#ccc");
        $.ajax({
            type: "GET",
            url: "<?= site_url('load_business_unit') ?>",
            data: {
                promo_type
            },
            success: function(data) {

                $("div.store").html(data);
            }
        });
    }

    function load_department() {

        let stores = [];
        $("input[name = 'stores[]']:checked").each(function() {

            stores.push($(this).val())
        })

        $.ajax({
            type: "GET",
            url: "<?= site_url('load_department') ?>",
            data: {
                stores
            },
            success: function(data) {

                $("select[name='department_select']").html(data);
            }
        });

        $.ajax({
            type: "GET",
            url: "<?= site_url('show_intro') ?>",
            data: {
                stores
            },
            success: function(data) {
                $("#promoIntro").html('').append(data);
            }
        });
    }

    function search_witness(witness, key) {

        let str = key.trim();
        let renewal = $("input.renewContract").val();

        if (str) {
            $.ajax({
                type: "POST",
                url: "<?= site_url('find_witness') ?>",

                data: {
                    str,
                    witness
                },
                success: function(data) {

                    if (data.trim() == 'No Result Found') {
                        $(`div.${witness}${renewal}`).hide();
                    } else {
                        $(`div.${witness}${renewal}`).show().html(data);
                    }
                }
            });
        } else {

            $(`div.${witness}${renewal}`).hide();
        }
    }

    function getWitness(name, witness) {

        let renewal = $("input.renewContract").val();

        $(`input[name = '${witness}${renewal}']`).val(name);
        $(`div.${witness}${renewal}`).hide();
        $(`input[name = '${witness}${renewal}']`).css("border-color", "#ccc");
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

    function printPermit(empId) {

        $("div#printPermit").modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

        $.ajax({
            url: "<?= site_url('print_permit_renewal/') ?>" + empId,
            success: function(data) {

                $("div.printPermit").html(data);
            }
        });
    }

    function printContract(empId) {

        $("div#printContract").modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

        $("input.renewContract").val('Renewal');
        $.ajax({
            url: "<?= site_url('print_contract_renewal/') ?>" + empId,
            success: function(data) {

                $("div.printContract").html(data);
            }
        });
    }

    function inputSpecialDays(specialSched) {

        if (specialSched == "") {

            $("input[name = 'specialDays']").val("");
        } else {

            $("input[name = 'specialDays']").prop("disabled", false);
            $("input[name = 'specialDays']").prop("required", true);
        }
    }

    function inputDutySched() {

        $("select.dutySched").css("border-color", "#ccc");
    }

    function sssctc(type) {

        if (type == "ctc") {

            $("[name = 'cedula']").show();
            $(".issuedOn").show();
            $(".issuedAt").show();

            $("[name = 'sss']").hide();
        } else {

            $("[name = 'cedula']").hide();
            $(".issuedOn").hide();

            $(".issuedAt").show();
            $("[name = 'sss']").show();
        }
    }

    function nameSearch(key) {

        var str = key.trim();
        if (str == '') {

            $(".search-results").hide();
        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('find_active_promo'); ?>",
                data: {
                    str: str,
                    promo_type: 'all'
                },
                success: function(data) {

                    $(".search-results").show().html(data);
                }
            });
        }
    }

    function getEmpId(response) {

        let res = response.split('*');

        $(".search-results").hide();
        $("input[name='employee']").val(response);
        $("input[name = 'employee']").css("border-color", "#ccc");

        $.ajax({
            url: "<?php echo site_url('promo_details'); ?>",
            data: {
                empId: res[0].trim()
            },
            success: function(data) {

                $("div.promo-details").html(data);
                $("div.otherdetails").show();
                $("div#loading-gif").show();

                otherDetailsForm(res[0].trim());
            }
        });

    }

    function otherDetailsForm(emp_id) {

        $.ajax({
            url: "<?= site_url('print_contract_renewal/') ?>" + emp_id,
            success: function(data) {

                $("div#loading-gif").hide();
                $("div.otherdetails-form").html(data);
            }
        });
    }

    function searchDiserPermit(key) {

        let str = key.trim();
        if (str == '') {

            $(".search-results").hide();
        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('find_iprintpermit_promo'); ?>",
                data: {
                    str: str,
                    promo_type: 'all'
                },
                success: function(data) {

                    $(".search-results").show().html(data);
                }
            });
        }
    }

    function getIprintPermit(response) {

        let permit = $("input[name = 'permit']").val();
        let res = response.split('*');

        $(".search-results").hide();
        $("input[name='employee']").val(response);
        $("input[name = 'employee']").css("border-color", "#ccc");

        if (permit == 'current') {

            displayBUCutoff(res[0].trim());
        } else {

            printPreviousPermit(res[0].trim());
        }
    }

    function displayBUCutoff(empId) {

        $("select[name = 'storeName']").prop('disabled', false);
        $(".selects2").prop('disabled', false);
        $("select[name = 'cutOff']").prop('disabled', false);
        $("select[name = 'dayOff']").prop('disabled', false);
        $("input[name = 'dutyDays']").prop('disabled', false);

        $.ajax({
            url: "<?php echo site_url('current_permit_form/'); ?>" + empId,
            success: function(data) {

                $("div.current-permit-form").html(data);
            }
        });
    }
</script>