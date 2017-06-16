<div id="urlUpload" >
        <div class="urlUploadMain ui-corner-all">
            <div class="urlUploadMainInternal contentPageWrapper">

                <!-- url uploader -->
                <div>
                    <div id="urlFileUploader">
                        <form action="<?php echo base_url('upload/fromurl')?>" method="POST" enctype="multipart/form-data">
                            <div class="initialUploadText">

                                <div class="inputElement">
                                    <textarea name="urlList" id="urlList" class="urlList" placeholder="<?php echo base_url()?>filedemo.rar"></textarea>
                                    <div class="clear"><!-- --></div>
                                </div>
                            </div>
                            <div class="urlUploadFooter">
                                <div id="transferFilesButton" class="transferFilesButton" title="transfer files" onClick="urlUploadFiles();
                                            return false;"><!-- --></div>
                                <div class="baseText">
                                  <?php echo lang('title_enter_url_file')?></div>
                                <div class="clear"><!-- --></div>
                            </div>
                            <div class="clear"><!-- --></div>
                        </form>
                    </div>

                    <div id="urlFileListingWrapper" class="urlFileListingWrapper hidden">
                        <div class="fileSection">
                            <table id="urls" class="urls" width="100%">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="clearLeft"><!-- --></div>

                        <div class="fileSectionFooterText hidden">

                            <div class="baseText">
                                <?php echo lang('title_file_transfer_completed')?>
                            </div>
                            <div class="clear"><!-- --></div>
                        </div>
                    </div>
                    
                </div>
                <!-- end url uploader -->

            </div>

            <div class="clear"><!-- --></div>
        </div>
    </div>