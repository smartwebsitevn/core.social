<div id="modal-report-common" class="modal fade" tabindex="-1" role="dialog"     >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true"></span></button>
                <h4 class="modal-title">Thông báo vi phạm</h4>
            </div>
            <div class="modal-body">
                <form class="form_action" method="post" action="">
                    <div class="form-group">
                        <textarea name="content" placeholder="Nội dung thông báo gửi Admin" rows="4"
                                  class="form-control"></textarea>

                        <div name="content_error" class="error"></div>
                    </div>
                    <div class="form-group row ">
                        <div class="col-md-6">
                            <input name="email" type="text" placeholder="Email" class="form-control">

                            <div name="email_error" class="error"></div>
                        </div>
                        <div class="col-md-6">
                            <input name="phone" type="text" placeholder="So dien thoai " class="form-control">

                            <div name="phone_error" class="error"></div>
                        </div>
                    </div>

                    <button class="btn btn-default" type="submit">Send</button>
                    <button data-dismiss="modal" class="btn btn-link" type="button">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>