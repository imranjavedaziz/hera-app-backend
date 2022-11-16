<!--  start Export CSV modal  -->

<div class="modal fade" id="modalExportCsv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-export">
        <div class="modal-content">
            <div class="modal-body">
                <div class="close-btn">
                    <img src="/assets/images/svg/cross-big.svg" alt="Close Icon" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <div class="export-wrapper">
                    <h3>Export Data</h3>
                    <div class="row select-wrapper">
                        <div class="col-6">
                            <div class="select-label">Month</div>
                            <div class="custom-select">
                                <!-- default bootstrap select use plz use plugin -->
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>October</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                  </select>



                            </div>
                        </div>
                        <div class="col-6">
                            <div class="select-label">Year</div>
                            <div class="custom-select">
                                <!-- default bootstrap select use plz use plugin -->
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>2023</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                  </select>
                            </div>
                        </div>
                    </div>
                    <div class="export-footer">
                        <button type="button" class="btn-primary btn-logout">GENERATE CSV</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  End Export CSV modal  -->