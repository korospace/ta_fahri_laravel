function updateEnableBtnNext(tabName, enableStatus = true, tabTarget) {
    $(`#${tabName} .btn-primary`).prop('disabled', !enableStatus);
    $(`#${tabName} .btn-primary`).attr('onclick', `moveTab('${tabTarget}')`);
}

function checkLinkTab(linkTabId) {
    $(`#${linkTabId} i`).removeClass('fa-circle');
    $(`#${linkTabId} i`).addClass('fa-check-circle');
}

function getActiveTabId() {
    let activeTab = document.querySelector(".nav-link.active"); // Cari elemen dengan class 'active'
    
    return activeTab[0] ? activeTab[0].id : null; // Kembalikan ID jika ditemukan, jika tidak, null
}

function moveTab(tabName) {
    // Hapus class 'active' dari semua elemen dengan class 'nav-link'
    $('#dashboard-upload-page .nav-link').removeClass('active');

    // Tambahkan class 'active' pada elemen dengan id sesuai 'tabName'
    $(`#link${tabName}`).addClass('active');

    // Optional: Ganti konten tab yang aktif (gunakan atribut 'data-toggle' atau sejenisnya jika diperlukan)
    $('.tab-pane').removeClass('active show');
    $(`#${tabName}`).addClass('active show');
}

function setActiveTab(tabName) {
    $.ajax({
        type: "PUT",
        url: `${BASE_URL}/api/v1/upload_data/set_active_tab`,
        data: JSON.stringify({
            "tab_name"   : tabName,
        }),
        contentType: "application/json",
        headers: {
            token: $.cookie("jwt_token"),
        },
        success: function (data) {
        },
        error: function (data) {
            showToast("terjadi kesalahan ketika update active tab", "danger");
        },
    });
}

function updateTab(tabName, status) {
    $.ajax({
        type: "PUT",
        url: `${BASE_URL}/api/v1/upload_data/update_tab`,
        data: JSON.stringify({
            "tab_name"   : tabName,
            "tab_status" : status
        }),
        contentType: "application/json",
        headers: {
            token: $.cookie("jwt_token"),
        },
        success: function (data) {
        },
        error: function (data) {
            showToast("terjadi kesalahan ketika update tab", "danger");
        },
    });
}

function updateProgressStatus(status) {
    $.ajax({
        type: "PUT",
        url: `${BASE_URL}/api/v1/upload_data/update_progress_status`,
        data: JSON.stringify({
            "progress_status" : status
        }),
        contentType: "application/json",
        headers: {
            token: $.cookie("jwt_token"),
        },
        success: function (data) {
            if (status == "completed") {
                window.location.href = `${BASE_URL}/dashboard/overview`;
            }
        },
        error: function (data) {
            showToast("terjadi kesalahan ketika update progress status", "danger");
        },
    });
}
    