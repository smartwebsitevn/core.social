<?php

echo macro('tpl::user/macros')->login();
?>


<style type="text/css">
    /*btn-social*/
    .btn-social {
        overflow: hidden;
        padding-left: 40px;
        position: relative;
        text-align: left;
        text-overflow: ellipsis;
        white-space: nowrap;
        /*width: 49%;*/
        width: 100%;
    }
    .btn-social > *:first-child {
        border-right: 1px solid rgba(0, 0, 0, 0.2);
        bottom: 0;
        font-size: 1.6em;
        left: 0;
        line-height: 34px;
        position: absolute;
        text-align: center;
        top: 0;
        width: 32px;
    }

    .btn-facebook {
        background-color: #3b5998;
        border-color: rgba(0, 0, 0, 0.2);
        color: #fff;
    }
    .btn-facebook:hover, .btn-facebook:focus, .btn-facebook:active, .btn-facebook.active, .open > .dropdown-toggle.btn-facebook {
        background-color: #2d4373;
        border-color: rgba(0, 0, 0, 0.2);
        color: #fff;
    }

    .btn-google-plus {
        background-color: #dd4b39;
        border-color: rgba(0, 0, 0, 0.2);
        color: #fff;
    }
    .btn-google-plus:hover, .btn-google-plus:focus, .btn-google-plus:active, .btn-google-plus.active, .open > .dropdown-toggle.btn-google-plus {
        background-color: #c23321;
        border-color: rgba(0, 0, 0, 0.2);
        color: #fff;
    }
</style>

