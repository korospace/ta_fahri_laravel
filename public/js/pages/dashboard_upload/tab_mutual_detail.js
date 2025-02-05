function scrapeMutualDetail() {
    fetchMutualDetailList();
    updateTab('tab_mutual_detail', 'ongoing')
}

if ($("#tab_mutual_detail_value").val() === "ongoing" || $("#tab_mutual_detail_value").val() === "finish") {
    if ($("#tab_active").val() == "tab_mutual_detail") {
        fetchMutualDetailList();
    }
} else {
    showEmptyMutualDetailTable();
}

function fetchMutualDetailList() {
    $("#table-mutual-detail")
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
                    // bulkScrapeMutualDetail(json.data)
                    return json.data;
                },
            },
            drawCallback: function (settings) {
                const api = this.api();
                const jsonData = api.rows({ order: "current", search: "applied" }).data().toArray();

                bulkScrapeMutualDetail(jsonData);
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
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7],
                    className: "text-center align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#table-mutual-detail-wrapper .col-md-6:eq(0)");
}

function showEmptyMutualDetailTable() {
    $("#table-mutual-detail")
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
                { data: "no", width: "5%" },
                { data: "username", width: "10%" },
                { data: "is_scraped_html", width: "10%" },
                { data: "is_verified_html", width: "10%" },
                { data: "posts_html", width: "10%" },
                { data: "followers_html", width: "10%" },
                { data: "following_html", width: "10%" },
                { data: "bio_html" },
            ],
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7],
                    className: "text-center align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#table-mutual-detail-wrapper .col-md-6:eq(0)");
}

async function bulkScrapeMutualDetail(listMutual) {
    let dataNotScrapedExist = false
    let successAll          = true

    for (let index = 0; index < listMutual.length; index++) {
        const mutualData = listMutual[index];

        if (mutualData.is_scraped == 0) {
            dataNotScrapedExist = true

            $(`#table-mutual-detail span.${mutualData.id}.is_scraped`).removeClass(`text-success text-danger`)
            $(`#table-mutual-detail span.${mutualData.id}.is_scraped`).html(`<i class="fas fa-spinner fa-spin"></i>`)

            await delay(2000);

            try {
                const response = await fetchScrapeMutualDetail(mutualData.username);
                $(`#table-mutual-detail span.${mutualData.id}.is_scraped`).html(`<i class='fas fa-check-circle'></i>`)
                
                $(`#table-mutual-detail span.${mutualData.id}.posts`).html(response.data.result.posts)
                $(`#table-mutual-detail span.${mutualData.id}.followers`).html(response.data.result.followers)
                $(`#table-mutual-detail span.${mutualData.id}.following`).html(response.data.result.following)
                $(`#table-mutual-detail span.${mutualData.id}.bio`).html(response.data.result.bio)
                $(`#table-mutual-detail span.${mutualData.id}.is_verified`).html(response.data.result.is_verified == 1 ? `<i class='fas fa-check-circle text-info'></i>` : '-')

                console.log(`Data mutual ${mutualData.id} berhasil di-scrape`, response);
            } catch (error) {
                $(`#table-mutual-detail span.${mutualData.id}.is_scraped`).addClass(`text-danger`)
                $(`#table-mutual-detail span.${mutualData.id}.is_scraped`).removeClass(`text-secondary`)
                $(`#table-mutual-detail span.${mutualData.id}.is_scraped`).html(`<i class='fas fa-times-circle'></i>`)
                successAll = false
                
                console.error(`Gagal scrape data mutual ${mutualData.id}`, error);
            }
        }
    }

    if (dataNotScrapedExist) {
        showToast("scraping mutual detail <b>is finished</b>", "success");
        updateEnableBtnNext('tab_mutual_detail', true, 'tab_mutual_followers')
        updateTab('tab_mutual_followers', 'enable')
        $("#tab_mutual_detail_value").val('finish')
        updateTab('tab_mutual_detail', 'finish')
        checkLinkTab('linktab_mutual_detail');
        if (successAll) {
            setActiveTab('tab_mutual_followers')
        } else {
            setActiveTab('tab_mutual_detail')
        }
    }
}

function fetchScrapeMutualDetail(username) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `${BASE_URL}/api/v1/upload_data/scrape_mutual_detail/${username}`,
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
