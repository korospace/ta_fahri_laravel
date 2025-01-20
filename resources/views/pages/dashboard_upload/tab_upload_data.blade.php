<div class="tab-pane fade {{$dataTab->tab_active == 'tab_upload_data' ? 'show active' : ''}}" id="tab_upload_data" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
    <div class="row">
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-secondary btn-sm disabled">
                <i class="fas fa-arrow-left"></i> prev
            </button>
            <button type="button" class="btn btn-primary btn-sm" {{$dataTab->tab_mutual == 'disabled' ? 'disabled' : "onclick=moveTab(`tab_mutual`)"}}>
                next <i class="fas fa-arrow-right"></i>
            </button>
        </div>
        <div class="col-12">
            <div class="card card-secondary card-outline card-tabs">
                <div class="card-body">
                    <form id="form-upload-data" class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="file_followers">File Followers</label>
                                <input class="form-control-file" type="file" id="file_followers" name="file_followers">
                            </div>
                            <div class="form-group">
                                <label for="file_following">File Following</label>
                                <input class="form-control-file" type="file" id="file_following" name="file_following">
                            </div>

                            <button class="btn btn-block btn-success" type="button" onclick="submitUploadData()">
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
