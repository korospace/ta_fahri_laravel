@if($dataTab ? $dataTab->progress_status == "ongoing" : false)
<div class="bg-warning">
    <div class="alert alert-warning mb-0">
        <div class="container">
            <div class="content">
                <div class="row px-3">
                    <div class="col">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Warning!</h5>
                        There is an upload process that has not been completed.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
