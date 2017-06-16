<hr/>
<h3 class="text-primary text-center"><?php echo lang("no")?>: <?php echo $row->no ?></h3>
<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <label class="col-sm-3"><?php echo lang("no")?>:</label>
            <div class="col-sm-9 tracking_no">
               <?php echo $row->no ?>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-3"><?php echo lang("status")?>:</label>
            <div class="col-sm-9 status_<?php echo $row->status ? $tracking_status[$row->status] : ''?>">
                <?php echo $row->status ? lang('tracking_'.$tracking_status[$row->status]) : lang('dont_know') ?>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-3"><?php echo lang("customer")?>:</label>
            <div class="col-sm-9">
                <?php echo $row->customer ?>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-3"><?php echo lang("content")?>:</label>
            <div class="col-sm-9">
                <?php echo $row->content ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <label class="col-sm-4"><?php echo lang("created")?>:</label>
            <div class="col-sm-8">
                <?php echo $row->_created_time ? $row->_created_time : lang('dont_know')?>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-4"><?php echo lang("delivery")?>:</label>
            <div class="col-sm-8">
                <?php echo $row->_delivery_time ? $row->_delivery_time : lang('dont_know') ?>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-4"><?php echo lang("address_from")?>:</label>
            <div class="col-sm-8">
                <?php echo $row->address_from ?>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-4"><?php echo lang("address_to")?>:</label>
            <div class="col-sm-8">
                <?php echo $row->address_to ?>
            </div>
        </div>
    </div>
</div>

<p><?php echo lang("journey")?></p>
<?php if(isset($row->progress) && isset($row->progress_vehicle) && $row->progress_vehicle){ ?>
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $row->progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $row->progress ?>%;">
            <div class="progress_vehicle_<?php echo $row->progress_vehicle?>"></div>
        </div>
    </div>
<?php } ?>
<table class="table table-bordered table-striped table-hover">
    <tr>
        <th><?php echo lang("stt")?></th>
        <th><?php echo lang("from")?></th>
        <th><?php echo lang("to")?></th>
        <th><?php echo lang("content")?></th>
        <th><?php echo lang("vehicle")?></th>
        <th><?php echo lang("reference")?></th>
        <th><?php echo lang("status")?></th>
        <th><?php echo lang("time_start")?></th>
        <th><?php echo lang("time_end")?></th>
    </tr>
    <?php $i=0; foreach($row->data as $r){ $i++;?>
        <tr>
            <td><?php echo $i?></td>
            <td><?php echo $r->tracking_from ?></td>
            <td><?php echo $r->tracking_to?></td>
            <td><?php echo $r->tracking_content?></td>
            <td class="vehicle_<?php echo $r->_tracking_vehicle?>"><?php echo $r->_tracking_vehicle ? lang('tracking_'.$r->_tracking_vehicle) : lang('dont_know') ?></td>
            <td><?php echo $r->tracking_reference ?></td>
            <td class="status_<?php echo $r->_tracking_status?>"><?php echo $r->_tracking_status? lang('tracking_'.$r->_tracking_status) : lang('dont_know') ?></td>
            <td><?php echo $r->_tracking_timestart ? $r->_tracking_timestart : lang('dont_know')?></td>
            <td><?php echo $r->_tracking_timeend ? $r->_tracking_timeend : lang('dont_know')?></td>
        </tr>
    <?php } ?>
</table>