<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="A basic demo of Cropper.">
  <meta name="keywords" content="HTML, CSS, JS, JavaScript, image cropping, image crop, image move, image zoom, image rotate, image scale, front-end, frontend, web development">
  <meta name="author" content="Fengyuan Chen">
  <title>Cropper</title>
<link rel="shortcut icon" href="<?php echo $path_assets ?>admin/images/icon.png" type="image/x-icon"/>
   <link rel="stylesheet" type="text/css" href="<?php echo $path_assets ?>js/jquery/cropperjs/assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $path_assets ?>js/jquery/cropperjs/assets/css/bootstrap.min.css"" />
     <link rel="stylesheet" type="text/css" href="<?php echo $path_assets ?>js/jquery/cropperjs/cropper.css" />
     <link rel="stylesheet" type="text/css" href="<?php echo $path_assets ?>js/jquery/cropperjs/main.css" />     


  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <!-- header -->
  <?php /*?>
  <header class="navbar navbar-inverse navbar-static-top docs-header" id="top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-target="#navbar-collapse-1" data-toggle="collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo $media_url_back?>"><?php echo $button_back?></a>
      </div>

    </div>
  </header>
<?php */?>
  <!-- Content -->
  <div class="container">
    <div class="row">
      <div class="col-md-9">
        <!-- <h3 class="page-header">Demo:</h3> -->
        <div class="img-container">
          <img src="<?php echo $image?>" alt="Picture">
        </div>
      </div>
      <div class="col-md-3">
        <!-- <h3 class="page-header">Preview:</h3> -->
        <div class="docs-preview clearfix">
          <div class="img-preview preview-lg"></div>
          <div class="img-preview preview-md"></div>
          <div class="img-preview preview-sm"></div>
          <div class="img-preview preview-xs"></div>
        </div>

        <!-- <h3 class="page-header">Data:</h3> -->
        <div class="docs-data">
          <div class="input-group input-group-sm">
            <label class="input-group-addon" for="dataX">X</label>
            <input type="text" class="form-control" id="dataX" placeholder="x">
            <span class="input-group-addon">px</span>
          </div>
          <div class="input-group input-group-sm">
            <label class="input-group-addon" for="dataY">Y</label>
            <input type="text" class="form-control" id="dataY" placeholder="y">
            <span class="input-group-addon">px</span>
          </div>
          <div class="input-group input-group-sm">
            <label class="input-group-addon" for="dataWidth">Width</label>
            <input type="text" class="form-control" id="dataWidth" placeholder="width">
            <span class="input-group-addon">px</span>
          </div>
          <div class="input-group input-group-sm">
            <label class="input-group-addon" for="dataHeight">Height</label>
            <input type="text" class="form-control" id="dataHeight" placeholder="height">
            <span class="input-group-addon">px</span>
          </div>
          <div class="input-group input-group-sm">
            <label class="input-group-addon" for="dataRotate">Rotate</label>
            <input type="text" class="form-control" id="dataRotate" placeholder="rotate">
            <span class="input-group-addon">deg</span>
          </div>
          <div class="input-group input-group-sm">
            <label class="input-group-addon" for="dataScaleX">ScaleX</label>
            <input type="text" class="form-control" id="dataScaleX" placeholder="scaleX">
          </div>
          <div class="input-group input-group-sm">
            <label class="input-group-addon" for="dataScaleY">ScaleY</label>
            <input type="text" class="form-control" id="dataScaleY" placeholder="scaleY">
          </div>
        </div>
      </div>
    </div>
    <div class="row" id="actions">
      <div class="col-md-9 docs-buttons">
        <!-- <h3 class="page-header">Toolbar:</h3> -->
        <div class="btn-group btn-group-justified" data-toggle="buttons">
          <label class="btn btn-primary active" data-method="setAspectRatio" data-option="1.7777777777777777" title="Set Aspect Ratio">
            <input type="radio" class="sr-only" id="aspestRatio1" name="aspestRatio" value="1.7777777777777777">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setAspectRatio(16 / 9)">
              16:9
            </span>
          </label>
          <label class="btn btn-primary" data-method="setAspectRatio" data-option="1.3333333333333333" title="Set Aspect Ratio">
            <input type="radio" class="sr-only" id="aspestRatio2" name="aspestRatio" value="1.3333333333333333">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setAspectRatio(4 / 3)">
              4:3
            </span>
          </label>
          <label class="btn btn-primary" data-method="setAspectRatio" data-option="1" title="Set Aspect Ratio">
            <input type="radio" class="sr-only" id="aspestRatio3" name="aspestRatio" value="1">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setAspectRatio(1 / 1)">
              1:1
            </span>
          </label>
          <label class="btn btn-primary" data-method="setAspectRatio" data-option="0.6666666666666666" title="Set Aspect Ratio">
            <input type="radio" class="sr-only" id="aspestRatio4" name="aspestRatio" value="0.6666666666666666">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setAspectRatio(2 / 3)">
              2:3
            </span>
          </label>
          <label class="btn btn-primary" data-method="setAspectRatio" data-option="NaN" title="Set Aspect Ratio">
            <input type="radio" class="sr-only" id="aspestRatio5" name="aspestRatio" value="NaN">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setAspectRatio(NaN)">
              Free
            </span>
          </label>
        </div>
       
        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(-45)">
              <span class="fa fa-rotate-left"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(45)">
              <span class="fa fa-rotate-right"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-flip="horizontal" data-method="scale" data-option="-1" data-second-option="1" title="Flip Horizontal">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scale(-1, 1)">
              <span class="fa fa-arrows-h"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-flip="vertical" data-method="scale" data-option="1" data-second-option="-1" title="Flip Vertical">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scale(1, -1)">
              <span class="fa fa-arrows-v"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.crop()">
              <span class="fa fa-check"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="clear" title="Clear">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.clear()">
              <span class="fa fa-remove"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="disable" title="Disable">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.disable()">
              <span class="fa fa-lock"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="enable" title="Enable">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.enable()">
              <span class="fa fa-unlock"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
        
          <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.reset()">
              <span class="fa fa-refresh"></span>
            </span>
          </button>
          <?php /*?>
          <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
            <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
            <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
              <span class="fa fa-upload"></span>
            </span>
          </label>
          <button type="button" class="btn btn-primary" data-method="destroy" title="Destroy">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.destroy()">
              <span class="fa fa-power-off"></span>
            </span>
          </button>
          <?php */?>
        </div>

        <div class="btn-group btn-group-crop">
          <button type="button" class="btn btn-primary" data-method="getCroppedCanvas">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas()">
              Get Cropped Canvas
            </span>
          </button>
          <?php /*?>
          <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 160, height: 90 })">
              160&times;90
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
            <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 320, height: 180 })">
              320&times;180
            </span>
          </button>
          <?php */?>
        </div>

        <!-- Show the cropped image in modal -->
        <div class="modal fade docs-cropped" id="getCroppedCanvasModal" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
              </div>
              <div class="modal-body"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <a class="btn btn-primary" id="update" href="javascript:void(0);" >Update</a>
              </div>
            </div>
          </div>
        </div><!-- /.modal -->
	
      </div><!-- /.docs-buttons -->

      
    </div>
  </div>

  <!-- Scripts -->
<script type="text/javascript">
  var cropper =null;
  var result=null;   
</script>
<script src="<?php echo $path_assets ?>js/jquery/cropperjs/assets/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $path_assets ?>js/jquery/cropperjs/assets/js/bootstrap.min.js" type="text/javascript"></script>
<script  src="<?php echo $path_assets ?>js/jquery/cropperjs/cropper.js" type="text/javascript"></script>
<script  src="<?php echo $path_assets ?>js/jquery/cropperjs/main.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
     $('#update').click(function(){
       	var info= cropper.getData();
        console.log(info)
         $('body').append('<div id="overlay"></div><div id="preloader">Đang xử lý..</div>');
         $('#overlay, #preloader').hide().fadeIn('fast');
         $.ajax({
                    url: '<?php echo $media_url_modify?>',
                    type: 'post',
                    data: {"_submit" :true,"image":"<?php echo $image_name?>","image_data":result.toDataURL()},
                    dataType: 'json',
                    success: function(json) {
                    	$('#overlay, #preloader').fadeOut('fast', function(){$(this).remove()});
                    	 window.opener.location.reload();
                    	 window.close();
                    	
                       },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('Error!');
                    }

                });
     })
  })
  </script>
</body>
</html>
