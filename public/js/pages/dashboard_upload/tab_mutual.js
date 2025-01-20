function generateMutual() {
    showLoadingSpinner();

    $.ajax({
        type: "GET",
        url: `${BASE_URL}/api/v1/upload_data/generate_mutual`,
        headers: {
            token: $.cookie("jwt_token"),
        },
        success: function (data) {
            hideLoadingSpinner();
            showToast("generate mutual <b>successfully</b>", "success");

            updateEnableBtnNext('tab_mutual', true, 'tab_mutual_detail')
            updateTab('tab_mutual_detail', 'enable')
            $("#tab_mutual_value").val('finish')
            setActiveTab('tab_mutual_detail')
            updateTab('tab_mutual', 'finish')
            checkLinkTab('linktab_mutual');
            fetchMutualList();
        },
        error: function (data) {
            hideLoadingSpinner();
            showToast("kesalahan pada <b>server</b>", "danger");
        },
    });
}

if ($("#tab_mutual_value").val() === "finish") {
    fetchMutualList();
} else {
    showEmptyTable();
}

function fetchMutualList() {
    $("#table-mutual")
        .DataTable({
            bDestroy: true,
            serverSide: true,
            processing: true,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            order: [[0, "asc"]],
            ajax: {
                type: "POST",
                url: `${BASE_URL}/api/v1/datasnap/list_mutual`,
                data: function (d) {
                    return d;
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("token", $.cookie("jwt_token"));
                },
            },
            columns: [
                { data: "no", width: "5%" },
                { data: "username", width: "95%" },
            ],
            columnDefs: [
                {
                    targets: [0, 1],
                    className: "text-center align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#table-mutual-wrapper .col-md-6:eq(0)");
}

function showEmptyTable() {
    $("#table-mutual")
        .DataTable({
            bDestroy: true,
            serverSide: false,
            processing: false,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            data: [], // Data kosong
            columns: [
                { data: "no", width: "5%" },
                { data: "username", width: "95%" },
            ],
            columnDefs: [
                {
                    targets: [0, 1],
                    className: "text-center align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#table-mutual-wrapper .col-md-6:eq(0)");
}
