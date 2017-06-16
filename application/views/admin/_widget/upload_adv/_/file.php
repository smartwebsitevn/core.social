

<div id="fileUpload">
        <div class="fileUploadMain ui-corner-all">
            <div class="fileUploadMainInternal contentPageWrapper" >

                <!-- uploader -->
                <div id="uploaderContainer" class="uploaderContainer">

                    <div id="fileupload">                        
                            <div class="fileupload-buttonbar hiddenAlt">
                            <form action="<?php echo admin_url('upload/fromfile')?>?r=localhost&p=http" method="POST" enctype="multipart/form-data" id="upload_formfile">
                                <label class="fileinput-button">
                                    <span><?php echo lang('title_add_files')?>...</span>
                                    <!-- <input id="add_files_btn" type="file" name="files" multiple> -->
                                    <input id="add_files_btn" type="file" name="files">                                
                                </label>
                                <button id="start_upload_btn" type="submit" class="start">Start upload</button>
                                <button id="cancel_upload_btn" type="reset" class="cancel">Cancel upload</button>
                            </form>
                            </div>
                            <div class="fileupload-content">
                                <label for="add_files_btn">
                                    <div id="initialUploadSection" class="initialUploadSection" onClick="$('#add_files_btn').click(); return false;">
                                        <div class="initialUploadText">

                                            <div class="clearLeft"><!-- --></div>

                                            <div class="uploadElement">
                                                <div class="internal">
                                                 <?php echo lang('title_drag_drop_file')?>
                                                 <br>
                                          <?php echo lang('title_max_files_size')?>: <?php echo $config['maxfilesize']?> MB.                                         </div>
                                        
                                            </div>
                                        </div>

                                    </div>
                                </label>
                                <div id="fileListingWrapper" class="fileListingWrapper hidden">

                                    <div class="fileSection">
                                        <table id="files" class="files" width="100%"><tbody></tbody></table>
                                        <div id="processQueueSection" class="fileSectionFooterText">
	                                        <a href="#" onClick="$('#add_files_btn').click();
                                                return false;">
                                                        <label for="add_files_btn">
                                                            <?php echo lang('title_add_files')?>                                                        </label>
                                                    </a>
	                                        
	                                    </div>
                                    </div>
                                  <div id="notice_fileupload_success" class="hide">
                                  	<span></span> 
                                  </div>

                                    <div id="processingQueueSection" class="fileSectionFooterText hidden">
                                        <div class="globalProgressWrapper">
                                            <div id="progress" class="progress progress-success progress-striped">
                                                <div class="bar"></div>
                                            </div>
                                            <div id="fileupload-progresstext" class="fileupload-progresstext">
                                                <div id="fileupload-progresstextRight"><!-- --></div>
                                                <div id="fileupload-progresstextLeft"><!-- --></div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <hr>
									<?php //$this->load->view('admin/_widget/upload_adv/_update_information');?>
									
                                </div>
                                                                
                            </div>                        
                    </div>
                    <script id="template-upload" type="text/x-jquery-tmpl">
                        {% for (var i=0, file; file=o.files[i]; i++) { %}
                        <tr class="template-upload{% if (file.error) { %} errorText{% } %}" id="fileUploadRow{%=i%}">
                        <td class="cancel">
                        <a href="#" onClick="return false;">
                        <img src="<?php echo public_url('site/upload/themes/blue_v2/images/ico_delete2.png'); ?>" height="10" width="10" alt="delete"/>
                        </a>
                        </td>
                        <td class="name">{%=file.name%}&nbsp;&nbsp;{%=o.formatFileSize(file.size)%}
                        {% if (!file.error) { %}
                        <div class="start hidden"><button>start</button></div>
                        {% } %}
                        <div class="cancel hidden"><button>cancel</button></div>
                        </td>
                        {% if (file.error) { %}
                        <td colspan="2" class="error">Error:
                        {%=file.error%}
                        </td>
                        {% } else { %}
                        <td colspan="2" class="preview"><span class="fade"></span></td>
                        {% } %}
                        </tr>
                        {% } %}
                    </script>

                    <script id="template-download" type="text/x-jquery-tmpl">
                    </script>

                </div>
                <!-- end uploader -->

            </div>

            <div class="clear"><!-- --></div>
        </div>
        
       
       
    </div>