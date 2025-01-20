<div class="tab-pane fade {{$dataTab->tab_active == 'tab_mutual_followers' ? 'show active' : ''}}" id="tab_mutual_followers" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
    <div class="row">
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-secondary btn-sm" onclick="moveTab('tab_mutual_detail')">
                <i class="fas fa-arrow-left"></i> prev
            </button>
            <button type="button" class="btn btn-primary btn-sm" {{$dataTab->tab_node_graph == 'disabled' ? 'disabled' : "onclick=moveTab(`tab_node_graph`)"}}>
                next <i class="fas fa-arrow-right"></i>
            </button>
        </div>
        <div class="col-12">
            <input type="hidden" id="tab_mutual_followers_value" value="{{ $dataTab->tab_mutual_followers }}">
            <div class="card card-secondary card-outline card-tabs">
                <div class="card-body">
                    <button class="btn btn-success mb-4" type="button" onclick="scrapeMutualFollowers()">
                        <i class="fas fa-sync mr-2"></i> Scrape Mutual Followers
                    </button>

                    <table id="table-mutual-followers" class="table table-bordered table-hover">
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
                                    Action
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
