<div id="main">
    <section class="slide-text">
        <div class="container">
            <div id="slide-1" class="carousel slide" data-ride="carousel">
              <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#slide-1" data-slide-to="0" class="active"></li>
                    <li data-target="#slide-1" data-slide-to="1"></li>
                    <li data-target="#slide-1" data-slide-to="2"></li>
                    <li data-target="#slide-1" data-slide-to="3"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <span>Raising the skill </span>
                        <span>levels of students in home </span>
                        <span>convenience</span>
                    </div>
                    <div class="item">
                        <span>Raising the skill </span>
                        <span>levels of students in home </span>
                        <span>convenience</span>
                    </div>
                    <div class="item">
                        <span>Raising the skill </span>
                        <span>levels of students in home </span>
                        <span>convenience</span>
                    </div>
                    <div class="item">
                        <span>Raising the skill </span>
                        <span>levels of students in home </span>
                        <span>convenience</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="select-user">
        <div class="container2">
            <div class="row2">
                <div class="col-md-62 col-sm-62 left">
                    <?php echo widget('site')->ads( 'home_left', 'tpl::_widget/ads/home_left' ) ?>
                    <p class="title">Bạn là học viên ?</p>
                    <ul class="list-unstyled">
                        <li><a href="#">Find a tutor in your area</a></li>
                        <li><a href="#">Contact and arrange lessons with tutors</a></li>
                        <li><a href="#">Provide and read tutor feedback</a></li>
                    </ul>

                </div>
                <div class="col-md-62 col-sm-62 right">
                    <?php echo widget('site')->ads( 'home_right', 'tpl::_widget/ads/home_right' ) ?>
                    <p class="title">Bạn là giáo viên ?</p>
                    <ul class="list-unstyled">
                        <li><a href="#">Find a tutor in your area</a></li>
                        <li><a href="#">Contact and arrange lessons with tutors</a></li>
                        <li><a href="#">Provide and read tutor feedback</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>