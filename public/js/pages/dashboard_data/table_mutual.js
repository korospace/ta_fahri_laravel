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
                dataSrc: function (json) {
                    // bulkScrapeMutualDetail(json.data)
                    return json.data;
                },
            },
            drawCallback: function (settings) {
                // const api = this.api();
                // const jsonData = api.rows({ order: "current", search: "applied" }).data().toArray();

                // bulkScrapeMutualDetail(jsonData);
            },
            columns: [
                { data: "no", width: "5%" },
                { data: "username", width: "10%" },
                { data: "is_scraped_html", width: "10%" },
                { data: "is_verified_html", width: "10%" },
                { data: "posts_html", width: "10%" },
                { data: "followers_html", width: "10%" },
                { data: "following_html", width: "10%" },
                { data: "bio_html" },
                { data: "action" },
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                    className: "text-center align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#table-mutual-wrapper .col-md-6:eq(0)");
}

fetchMutualList();
