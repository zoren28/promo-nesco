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
            <form action="" id="dataPromoContractDetails" method="post" autocomplete="off">
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

<!-- ./Modal -->
<div id="uploadPromoScannedFile" class="modal fade">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload Scanned File</h4>
            </div>
            <form action="" id="dataUploadPromoScannedFile" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="uploadPromoScannedFile"></div>
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
<div id="addEmploymentHist" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Employment History</h4>
            </div>
            <form action="" id="dataEmploymentHistory" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="addEmploymentHist"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="submitEmploymentHist">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="viewEmploymentCert" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Eligibility / Seminars / Trainings Information</h4>
            </div>
            <div class="modal-body">
                <div class="viewEmploymentCert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="viewJobTransfer" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Job Transfer Report</h4>
            </div>
            <div class="modal-body">
                <div class="viewJobTransfer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="addBlacklist" class="modal fade">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Blacklist History</h4>
            </div>
            <div class="modal-body">
                <div class="addBlacklist"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="submitBlacklist()">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="view201File" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><span class="201-title"></span></h4>
            </div>
            <div class="modal-body">
                <div class="view201File"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="upload201Files" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload 201 Files</h4>
            </div>
            <form action="" id="data201File" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="upload201Files"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="upload_201files_btn">Upload</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="addSupervisor" class="modal fade">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Supervisor(s)</h4>
            </div>
            <div class="modal-body">
                <div class="addSupervisor"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="submitSupervisor()">Save Supervisor(s)</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="addUserAccount" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add New User Account</h4>
            </div>
            <div class="modal-body">
                <div class="addUserAccount"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="submit_user_account" onclick="submitUserAccount()">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>