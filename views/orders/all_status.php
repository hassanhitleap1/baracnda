
<div class="orders-view">
        <div class="row">
            <div class="" align="center">
                <?php foreach ($status as $statu):?>
                    <button type="button" class="btn btn-primary btn-lg change-all-status"  att_status_id="<?= $statu->id?>" name_status="<?= $statu["name"]?>"><?= $statu["name"]?></button>
                <?php endforeach;?>
            </div>
        </div>
</div>