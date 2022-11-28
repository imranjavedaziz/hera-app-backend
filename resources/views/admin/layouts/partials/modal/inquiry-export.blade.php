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
                            <div class="custome-select-year">
                                <div class="select">
                                    <div class="selectBtn active" id="month_select" data-type="{{idate('m')}}">{{date('F')}}</div>
                                    <div class="selectDropdown">
                                        <div class="option option1" data-type="1">January</div>
                                        <div class="option option2" data-type="2">February</div>
                                        <div class="option option3" data-type="3">March</div>
                                        <div class="option option4" data-type="4">April</div>
                                        <div class="option option5" data-type="5">May</div>
                                        <div class="option option6" data-type="6">June</div>
                                        <div class="option option7" data-type="7">July</div>
                                        <div class="option option8" data-type="8">August</div>
                                        <div class="option option9" data-type="9">September</div>
                                        <div class="option option10" data-type="10">October</div>
                                        <div class="option option11" data-type="11">November</div>
                                        <div class="option option12" data-type="12">December</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="select-label">Year</div>
                            <div class="custome-select-year">
                                <div class="select">
                                    <div class="selectBtn active" id="year_select" data-type="{{date('Y')}}">{{date("Y")}}</div>
                                    <div class="selectDropdown">
                                        <div class="option" data-type="{{date('Y')}}">{{date("Y")}}</div>
                                        <div class="option" data-type="{{date('Y',strtotime('-1 year'))}}">{{date("Y",strtotime("-1 year"))}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="export-footer">
                        <button type="button" class="btn-primary btn-logout" id="generate_csv">GENERATE CSV</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  End Export CSV modal  -->