$('.form-control-file').on('change', function (event) {
    const file = this.files[0]; // Mendapatkan file yang dipilih
    
    // Validasi tipe file
    if (file && file.type !== 'application/json') {
        showToast("only json file <b>allowed</b>", "warning");
        $(this).val(''); // Reset input file
    }
});

// Validate Form Upload Data
$('#form-upload-data').validate({
    rules: {
        file_followers: {
            required: true,
        },
        file_following: {
            required: true,
        },
    },
    messages: {
        file_followers: {
            required: "file ini wajib diupload",
        },
        file_following: {
            required: "file ini wajib diupload",
        },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});

// Submit Form Upload Data
function submitUploadData() {    
    if ($("form#form-upload-data").valid()) {
        showLoadingSpinner();
        let form = new FormData(document.querySelector("form#form-upload-data"));

        $.ajax({
            type: "POST",
            url: `${BASE_URL}/api/v1/upload_data/import_followers_following`,
            data: form,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                token: $.cookie("jwt_token"),
            },
            success: function (data) {
                hideLoadingSpinner();
                $("form#form-upload-data input").val("");

                updateEnableBtnNext('tab_upload_data', true, 'tab_mutual')
                showToast("upload file <b>successfully</b>", "success");
                updateTab('tab_upload_data', 'finish')
                checkLinkTab('linktab_upload_data');
                updateTab('tab_mutual', 'enable')
                updateProgressStatus('ongoing')
                setActiveTab('tab_mutual')
                // moveTab('tab_mutual')
            },
            error: function (data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`form#form-upload-data #${key}`).addClass("is-invalid");
                        $(`form#form-upload-data #${key}-error`).html(errors[key][0]);
                    }
                } else if (data.status >= 500) {
                    showToast("kesalahan pada <b>server</b>", "danger");
                }
            },
        });
    }
}
