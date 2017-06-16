<!-- The file upload form used as target for the file upload widget -->
<?php $_rd = random_string('unique'); //pr($upload_url);?>
<script type="text/javascript">
    $(function () {
        'use strict';
        // Initialize the jQuery File Upload widget:
        $('#fileupload<?php echo $_rd?>').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
             //  maxChunkSize: 100000, // 1 MB
            // maxChunkSize: 10000000 // 10 MB
             maxChunkSize: 1 * 1024 * 1024, // 1 MB
            url: '<?php echo $upload_url['upload']?>'
            })
            .on('fileuploadadd', function (e, data) {
            })
            .on('fileuploadstart', function (e, data) {
                // hide/show sections
            })
            .on('fileuploadstop', function (e, data) {
                // finished uploading
            })
            .on('fileuploadprogressall', function (e, data) {
                // progress bar
            })
            .on('fileuploaddone', function (e, data) {
                // keep a copy of the urls globally
                alert('upload hoan thanh')
                $.ajax({
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    url: '<?php echo $upload_url['upload'].'&completed=true'?>',
                    type: "POST",
                    dataType: 'html',
                    success: function (rs) {
                       // alert(rs)
                    }
                })
            })
            .on('fileuploaddestroy', function (e, data) {
                return confirm("Delete this file ?");
            })
            .on('fileuploadfail', function (e, data) {
            })
                /*
                Event Chunk
                .on('fileuploadchunksend', function (e, data) {
                })

                .on('fileuploadchunkdone', function (e, data) {
                    alert('upload chunk hoan thanh')
                })
                .on('fileuploadchunkfail', function (e, data) {
                })
                .on('fileuploadchunkalways', function (e, data) {
                });*/
        ;

        // Enable iframe cross-domain access via redirect option:
        $('#fileupload<?php echo $_rd?>').fileupload(
            'option',
            'redirect',
            window.location.href.replace(
                /\/[^\/]*$/,
                '/cors/result.html?%s'
            )
        );

        // Load existing files:
        $('#fileupload<?php echo $_rd?>').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload<?php echo $_rd?>').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload<?php echo $_rd?>')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });

    });

</script>
<div id="fileupload<?php echo $_rd ?>">
    <div class="row fileupload-buttonbar">
        <div class="col-lg-12 text-right">
            <!-- The global file processing state -->
            <span class="fileupload-process"></span>
            <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
            <button type="submit" class="btn btn-primary start">
                <i class="glyphicon glyphicon-upload"></i>
                <span>Start upload</span>
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span>Cancel upload</span>
            </button>
            <button type="button" class="btn btn-danger delete">
                <i class="glyphicon glyphicon-trash"></i>
                <span>Delete</span>
            </button>

        </div>
        <!-- The global progress state -->
        <div class="col-lg-12 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped">
        <tbody class="files"></tbody>
    </table>
</div>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
    <?php /* ?>
        <td>
            <span class="preview"></span>
        </td>
     <?php */ ?>

        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td width="25%">
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}







</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
            <?php /* ?>

        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
             <?php */ ?>

        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
         <td width="25%">
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}







</script>