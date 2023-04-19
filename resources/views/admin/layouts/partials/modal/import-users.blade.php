<!--  start Export CSV modal  -->

<div class="modal fade" id="modalExportCsv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-export">
        <div class="modal-content">
            <div class="modal-body">
                <div class="close-btn">
                    <img src="/assets/images/svg/cross-big.svg" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="export-wrapper">
                    <h3>Import Users</h3>

                    <form action="{{url('admin/import-users')}}" autocomplete="off" id="form1" method="POST" enctype="multipart/form-data">
                        @csrf
                       <div class="row select-wrapper">
                        <div class="col-12">
                            <div class="select-label">Upload File</div>
                            <div class="custome-select-year">
                              <input class="form-control" type="file" name="file">  
                            </div>
                        </div>
                        </div>
                        <button class="btn btn-sm btn-secondary" type="submit">Submit</button>
                    </form>
                    <div class="export-footer">
                        <div class="custome-select-year">
                            <a href="/assets/csv/MBC_SAMPLE.xlsx" download="">Download Sample File</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  End Export CSV modal  -->