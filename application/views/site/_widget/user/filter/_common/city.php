<!-- city-->
<div class="dropdown search-dropdown">
    <div class="dropdown-toggle"  type="button" data-toggle="dropdown" >
        <span class="search-rendered">Địa điểm</span>
        <span class="search-caret"></span>
    </div>
    <span class="search-remove"></span>
    <div class="dropdown-menu dropdown-2colums clearfix" >
        <div class="dropdown-menu dropdown-menu-left ">
            <div class="form-group">
                <input type="text"  class="searachSelect form-control lg">
            </div>
            <div class="slimscroll">
                <?php foreach($citys as $row){ ?>
                    <div class="search-results checkbox <?php echo (isset($filter['working_city'])&& is_array($filter['working_city']) && in_array($row->id, $filter['working_city'])) ? 'active_filter' : ''?>">
                        <label>
                            <input type="checkbox" name="working_city[]" value="<?php echo $row->id ?>"> <span><?php echo $row->name ?></span>
                        </label>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php  /* ?>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="form-group">
                                    <input type="text" placeholder="<?php echo lang("city_out_the_country")?>" class="form-control lg searachSelect">
                                </div>
                                <div class="slimscroll limit-height">
                                    <ul>
                                        <?php $path = public_url().'/img/world/'; ?>
                                        <?php foreach ($countrys as $v): ?>
                                            <?php ?>
                                    <li class="search-results active act-filter <?php echo (isset($filter['groupcountries']) && $row->id == $filter['groupcountries']) ? 'active_filter' : ''?>" data-name="groupcountries" data-value="<?php echo $group->id; ?>">
                                        <a class="search-results-option" href="#"><?php echo $group->name; ?></a>
                                    </li>
                                     <?php  ?>

                                            <?php //foreach ($group->countries as $v): ?>
                                            <li class="search-results act-filter <?php echo (isset($filter['country']) && $row->id == $filter['country']) ? 'active_filter' : ''?>" data-name="country" data-value="<?php echo $v->id; ?>">
                                                <a class="search-results-option" href="#"  data-value="<?php echo $v->id; ?>">
                                                    <img src="<?php echo $path.strtolower($v->id).'.gif'?>"> <?php echo $v->name; ?></a>
                                            </li>
                                            <?php //endforeach; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php  */ ?>

    </div>
</div>