function fetchFollowersList() {
    $("#table-followers")
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
                url: `${BASE_URL}/api/v1/datasnap/list_followers`,
                data: function (d) {
                    return d;
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("token", $.cookie("jwt_token"));
                },
            },
            columns: [
                { data: "no", width: "5%" },
                { data: "username", width: "30%" },
                { data: "timestamp_formatted", width: "30%" },
                { data: "href_html" },
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3],
                    className: "text-center align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#table-followers-wrapper .col-md-6:eq(0)");
}

fetchFollowersList();
