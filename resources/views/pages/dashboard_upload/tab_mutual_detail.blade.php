<div class="tab-pane fade {{$dataTab->tab_active == 'tab_mutual_detail' ? 'show active' : ''}}" id="tab_mutual_detail" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
    <div class="row">
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-secondary btn-sm" onclick="moveTab('tab_mutual')">
                <i class="fas fa-arrow-left"></i> prev
            </button>
            <button type="button" class="btn btn-primary btn-sm" {{$dataTab->tab_mutual_followers == 'disabled' ? 'disabled' : "onclick=moveTab(`tab_mutual_followers`)"}}>
                next <i class="fas fa-arrow-right"></i>
            </button>
        </div>
        <div class="col-12">
            <input type="hidden" id="tab_mutual_detail_value" value="{{ $dataTab->tab_mutual_detail }}">
            <div class="card card-secondary card-outline card-tabs">
                <div class="card-body">
                    <button class="btn btn-success mb-4" type="button" onclick="scrapeMutualDetail()">
                        <i class="fas fa-sync mr-2"></i> Scrape Mutual Detail
                    </button>

                    <table id="table-mutual-detail" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle">
                                    No
                                </th>
                                <th class="align-middle">
                                    Username
                                </th>
                                <th class="align-middle">
                                    Scraping Status
                                </th>
                                <th class="align-middle">
                                    Verified
                                </th>
                                <th class="align-middle">
                                    Posts
                                </th>
                                <th class="align-middle">
                                    Followers
                                </th>
                                <th class="align-middle">
                                    Following
                                </th>
                                <th class="align-middle">
                                    Bio
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
