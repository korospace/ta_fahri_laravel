<style>
    #table-followers-modal {
        width: 100% !important; /* Pastikan tabel selalu 100% dari induk */
        table-layout: fixed; /* Paksa tabel agar kolom menyesuaikan lebar */
    }

    #table-followers-modal th,
    #table-followers-modal td {
        white-space: nowrap; /* Hindari teks terpotong */
        overflow: hidden; /* Sembunyikan teks panjang */
        text-overflow: ellipsis; /* Tambahkan tiga titik untuk teks yang terpotong */
    }
</style>

<script>
    function showModalFollowers(mutualID, mutualUsername) {
        $("#modal-followers .modal-title").html(`List Followers - ${mutualUsername}`)

        $("#modal-followers").modal("show");

        $("#table-followers-modal")
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
                    url: `${BASE_URL}/api/v1/datasnap/list_mutual_followers`,
                    data: function (d) {
                        d.mutual_id = mutualID;
                        return d;
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("token", $.cookie("jwt_token"));
                    },
                },
                columns: [
                    { data: "no", width: "5%" },
                    { data: "username" },
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
            .appendTo("#table-followers-modal-wrapper .col-md-6:eq(0)");
    }
</script>

<div class="modal fade" id="modal-followers">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">List Followers</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <table id="table-followers-modal" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle">
                                    No
                                </th>
                                <th class="align-middle">
                                    Username
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div>
