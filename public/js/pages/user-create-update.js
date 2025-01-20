let autoComplete = false;

// autocomplete select2
$(".select2bs4").select2({
    theme: "bootstrap4",
    width: "100%",
});
$(".select2bs4").on("select2:select", function (e) {
    $(this).removeClass("is-invalid");
    autoComplete = true;
});

//  clear error when keydown
$("#formCreateUpdateUser input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateUser #${$(this).attr('name')}-error`).html('');
})

// form validation
$('#formCreateUpdateUser').validate({
    rules: {
        name: {
            required: true,
        },
        email: {
            required: true,
        },
        level_id: {
            required: true,
        },
        password: {
            required: true,
        },
    },
    messages: {
        name: {
            required: "nama harus diisi",
        },
        email: {
            required: "email harus diisi",
        },
        level_id: {
            required: "hak akses harus diisi",
        },
        password: {
            required: "password harus diisi",
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

// form enter
$(document).on('keydown', function(event) {
    if (event.keyCode === 13) {
        if (autoComplete == false) {
            saveData()
        } else {
            autoComplete = false;
        }
    }
});

function saveData() {
    if ($("form#formCreateUpdateUser").valid()) {
        showLoadingSpinner();

        let form   = new FormData(document.querySelector('#formCreateUpdateUser'));
        let userId = form.get('id');

        $.ajax({
            type: userId ? "PUT": "POST",
            url: `${BASE_URL}/api/v1/user/${userId ? 'update' : 'create'}`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $.cookie("jwt_token"),
            },
            success:function(data) {
                hideLoadingSpinner();

                showToast(`user berhasil <b>${userId ? 'diedit' : 'ditambah'}..!</b>`,'success');

                if (userId == null) {
                    $("#formCreateUpdateUser input").val('');
                    $("#formCreateUpdateUser select").val('');
                }
                else {
                    $("#formCreateUpdateUser input[type=password]").val('');
                }
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formCreateUpdateUser #${key}`).addClass('is-invalid');
                        $(`#formCreateUpdateUser #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
