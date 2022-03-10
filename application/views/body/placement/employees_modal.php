<!-- ./Modal -->
<div id="profilePic" class="modal fade">
    <div class="modal-dialog" style="width: 35%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload Photo</h4>
            </div>
            <form action="" id="dataProfilePic" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="profilePic"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="update_birthCert" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Birth Certificate</h4>
            </div>
            <form action="" id="data_birthCert" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="update_birthCert"></div>
                </div>
                <div class="modal-footer">
                    <span class="show-hide-submit">
                        <button class="btn btn-primary">Submit</button>
                    </span> &nbsp;
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="view_birthCert" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Birth Certificate</h4>
            </div>
            <div class="modal-body">
                <div class="view_birthCert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="seminar_form" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Eligibility / Seminars / Trainings Information</h4>
            </div>
            <form action="" id="dataSeminar" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="seminar_form"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button class="btn btn-primary" id="submit_seminar_btn">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="viewSeminar" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Eligibility / Seminars / Trainings Information</h4>
            </div>
            <div class="modal-body">
                <div class="viewSeminar"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="addCharRef" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Character References</h4>
            </div>
            <div class="modal-body">
                <div class="addCharRef"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button class="btn btn-primary" id="submitCharRef" onclick="submit_character_ref()">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="appraisal_form" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Performance Appraisal For Promodiser/Merchandiser</h4>
            </div>
            <div class="modal-body">
                <div class="appraisal_form"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="examDetails" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Examination Details</h4>
            </div>
            <div class="modal-body">
                <div class="examDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="appHistDetails" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Application History Details</h4>
            </div>
            <div class="modal-body">
                <div class="appHistDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="interviewDetails" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Interview History Details</h4>
            </div>
            <div class="modal-body">
                <div class="interviewDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="addContractDetails" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Contract</h4>
            </div>
            <div class="modal-body">
                <div class="addContractDetails"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="addContract()">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="contractDetails" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Contract History Details</h4>
            </div>
            <div class="modal-body">
                <div class="loading-gif"></div>
                <div class="contractDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="viewFile" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">View Uploaded Scanned File</h4>
            </div>
            <div class="modal-body">
                <div class="viewFile"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="updateContractDetails" class="modal fade">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Contract</h4>
            </div>
            <div class="modal-body">
                <div class="updateContractDetails"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="updateContractDetails()">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="updatePromoContractDetails" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Contract</h4>
            </div>
            <form action="" id="dataPromoContractDetails" method="post">
                <div class="modal-body">
                    <div class="updatePromoContractDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="uploadScannedFile" class="modal fade">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload Scanned File</h4>
            </div>
            <form action="" id="dataUploadScannedFile" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="uploadScannedFile"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>