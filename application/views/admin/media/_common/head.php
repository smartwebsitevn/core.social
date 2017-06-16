<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="shortcut icon" href="<?php echo $path_assets ?>admin/images/icon.png" type="image/x-icon"/>
<link rel="stylesheet" type="text/css" href="<?php echo $path_assets ?>js/jquery/filemanager/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $path_assets ?>js/jquery/cropperjs/cropper.css" />


<script src="<?php echo $path_assets ?>js/jquery/filemanager/jquery.js" type="text/javascript"></script>
<script src="<?php echo $path_assets ?>js/jquery/filemanager/jquery-ui.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $path_assets ?>js/jquery/filemanager/ui/external/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="<?php echo $path_assets ?>js/jquery/filemanager/jstree/jquery.tree.min.js"></script>
<script type="text/javascript" src="<?php echo $path_assets ?>js/jquery/filemanager/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo $path_assets ?>js/jquery/zclip/jquery.zclip.js"></script>
<script type="text/javascript" src="<?php echo $path_assets ?>js/jquery/lazyload/jquery.lazyload.min.js" ></script>
<script type="text/javascript" >
    $(function() {
        $("img.lazy").lazyload();
    });
</script>
        <style type="text/css">

            body {

                padding: 0;

                margin: 0;

                background: #F7F7F7;

                font-family: Verdana, Arial, Helvetica, sans-serif;

                font-size: 11px;

            }

            img {

                border: 0;

            }

            #container {

                padding: 0px 10px 7px 10px;

                height: 340px;

            }

            #menu {

                clear: both;

                height: 29px;

                margin-bottom: 3px;

                position: relative;

            }

            #column-left {

                background: #FFF;

                border: 1px solid #CCC;

                float: left;

                width: 20%;

                height: 420px;

                overflow: auto;

            }

            #column-right {

                background: #FFF;

                border: 1px solid #CCC;

                float: right;

                width: 78%;

                height: 420px;

                overflow: auto;

                text-align: center;

            }



            #column-right div {

                text-align: left;

                padding: 5px;

            }

            #column-right a {

                display: inline-block;

                text-align: center;

                border: 1px solid #EEEEEE;

                cursor: pointer;

                margin: 3px;

                padding: 3px;

                width: 120px;

                height: 130px;

                float: left;

            }

            #column-right a img{

                width: 100px;

                height: 100px;

            }

            #column-right a.selected {

                border: 1px solid #7DA2CE;

                background: #EBF4FD;

            }

            #column-right input {

                display: none;

            }

            #dialog {

                display: none;

                width: 600px ;

            }

            #dialog .mylink{

                color: blue;

                border: none !important;

            }

            #dialog .mylink:focus, #dialog .mylink:active{

                border: none !important;

            }

            .button {

                display: block;

                float: left;

                padding: 7px 3px 8px 24px;

                margin-right: 0;

                background-position: 5px 6px;

                background-repeat: no-repeat;

                cursor: pointer;

            }

            .button:hover {

                /*background-color: #EEEEEE;*/

            }

            .thumb {

                padding: 5px;

                width: 105px;

                height: 105px;

                background: #F7F7F7;

                border: 1px solid #CCCCCC;

                cursor: pointer;

                cursor: move;

                position: relative;

            }



            <?php if (!$fckeditor): ?>

                #column-left,

                #column-right{

                    height: 640px;

                }

            <?php endif; ?> 

            .ui-dialog {

                text-align: center;

            }



            .status{

                font-weight: bold;

                background: #D2F0B2;

                line-height: 27px;

                width: 110px;

                height: 32px;

                float: left;

                padding-left: 5px;

                color: #039;

            }
            .clr,.clear {
        	clear: both;
        	overflow: hidden;
        	height: 0;
        }
        </style>
