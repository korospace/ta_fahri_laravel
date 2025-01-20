function scrapeMutualFollowers() {
    fetchMutualListToScraping();
    updateTab('tab_mutual_followers', 'ongoing')
}

if ($("#tab_mutual_followers_value").val() === "ongoing" || $("#tab_mutual_followers_value").val() === "finish") {
    fetchMutualListToScraping();
} else {
    showEmptyTable();
}

function fetchMutualListToScraping() {
    $("#table-mutual-followers")
        .DataTable({
            bDestroy: true,
            serverSide: true,
            processing: true,
            responsive: true,
            autoWidth: false,
            paging: false, // Nonaktifkan pagination
            info: false,
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
                    return json.data;
                },
            },
            drawCallback: function (settings) {
                const api = this.api();
                const jsonData = api.rows({ order: "current", search: "applied" }).data().toArray();

                bulkScrapeFollowersMutual(jsonData);
            },
            columns: [
                { data: "no", width: "10%" },
                { data: "username" },
                { data: "is_followers_scraped_html", width: "20%" },
                { data: "action", width: "20%" },
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
        .appendTo("#table-mutual-followers-wrapper .col-md-6:eq(0)");
}

function showEmptyTable() {
    $("#table-mutual-followers")
        .DataTable({
            bDestroy: true,
            serverSide: false,
            processing: false,
            responsive: true,
            autoWidth: false,
            paging: false, // Nonaktifkan pagination
            info: false,
            data: [], // Data kosong
            columns: [
                { data: "no", width: "10%" },
                { data: "username" },
                { data: "is_followers_scraped_html", width: "20%" },
                { data: "action", width: "20%" },
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
        .appendTo("#table-mutual-followers-wrapper .col-md-6:eq(0)");
}

async function bulkScrapeFollowersMutual(listMutual) {
    let dataNotScrapedExist = false

    for (let index = 0; index < listMutual.length; index++) {
        const mutualData = listMutual[index];

        if (mutualData.is_followers_scraped == 0) {
            dataNotScrapedExist = true

            $(`#table-mutual-followers span.${mutualData.id}.is_followers_scraped`).removeClass(`text-success text-danger`)
            $(`#table-mutual-followers span.${mutualData.id}.is_followers_scraped`).html(`<i class="fas fa-spinner fa-spin"></i>`)

            await delay(2000);

            try {
                const response = await fetchScrapeMutualFollowers(mutualData.username);
                if (response.message == "skip scraping") {
                    $(`#table-mutual-followers span.${mutualData.id}.is_followers_scraped`).html(`<i class='fas fa-check-circle'> skip</i>`)
                } else {
                    $(`#table-mutual-followers span.${mutualData.id}.is_followers_scraped`).html(`<i class='fas fa-check-circle'></i>`)
                    $(`#table-mutual-followers button.${mutualData.id}.show-followers`).prop('disabled', false);
                }

                console.log(`Data mutual ${mutualData.id} berhasil di-scrape`, response);
            } catch (error) {
                $(`#table-mutual-followers span.${mutualData.id}.is_followers_scraped`).addClass(`text-danger`)
                $(`#table-mutual-followers span.${mutualData.id}.is_followers_scraped`).removeClass(`text-secondary`)
                $(`#table-mutual-followers span.${mutualData.id}.is_followers_scraped`).html(`<i class='fas fa-times-circle'></i>`)
                
                console.error(`Gagal scrape data mutual ${mutualData.id}`, error);
            }
        }
    }

    if (dataNotScrapedExist) {
        showToast("scraping mutual followers <b>is finished</b>", "success");
        updateEnableBtnNext('tab_mutual_followers', true, 'tab_node_graph')
        updateTab('tab_node_graph', 'enable')
        $("#tab_mutual_followers_value").val('finish')
        setActiveTab('tab_node_graph')
        updateTab('tab_mutual_followers', 'finish')
        checkLinkTab('linktab_mutual_followers');
    }
}

function fetchScrapeMutualFollowers(username) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `${BASE_URL}/api/v1/upload_data/scrape_mutual_followers/${username}`,
            type: "GET",
            headers: {
                token: $.cookie("jwt_token"),
            },
            success: function (response) {
                resolve(response);
            },
            error: function (xhr, status, error) {
                reject(error);
            },
        });
    });
}

function delay(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}
