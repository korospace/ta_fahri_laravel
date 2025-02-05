<div class="tab-pane fade {{$dataTab->tab_active == 'tab_node_graph' ? 'show active' : ''}}" id="tab_node_graph" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
    <div class="row">
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-secondary btn-sm" onclick="moveTab('tab_mutual_followers')">
                <i class="fas fa-arrow-left"></i> prev
            </button>
            <!-- <button type="button" class="btn btn-primary btn-sm" {{$dataTab->tab_node_graph == 'finish' ? "onclick=finishProgress()" : 'disabled'}}> -->
            <button type="button" class="btn btn-primary btn-sm" {{true ? "onclick=finishProgress()" : 'disabled'}}>
                finish
            </button>
        </div>
        <div class="col-12">
            <input type="hidden" id="tab_node_graph_value" value="{{ $dataTab->tab_node_graph }}">
            <div class="card card-secondary card-outline card-tabs">
                <div class="card-body">
                    <button class="btn btn-success mb-4" type="button" onclick="generateNodesEdges()">
                        <i class="fas fa-sync mr-2"></i> Generate Nodes & Edges
                    </button>

                    <div class="row">
                        <div class="col-12" style="max-width: 100%;overflow: auto;">
                            <div id="graph-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
