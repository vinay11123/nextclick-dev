<div class="row">
    <div class="col-12">
        <h4 class="ven">Edit Delivery Insentive</h4>
        <form role="form" method="post" action="<?php echo site_url() ?>update-delivery_insentive_config" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $delivery_insentive_config['id'] ?>" name="delivery_insentive_config_id">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="state_id">State</label>
                    <select class="form-control " id="state_id" name="state_id" required="">
                        <option value="" selected disabled>Select</option>
                        <?php foreach ($state as $item) : ?>
                            <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $delivery_insentive_config['state']) {
                                                                            echo "selected";
                                                                        } ?>><?php echo $item['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="district">District</label>
                    <select class="form-control " id="district_id" name="district">
                        <?php
                        if ($item['district'] !== '') {
                            if($delivery_insentive_config['district']){
                            $districtdata = $this->district_model->get($delivery_insentive_config['district']);
                        ?>
                            <option value="<?php echo $districtdata['id'] ?>" <?php if ($districtdata['id'] == $delivery_insentive_config['district_id']) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo $districtdata['name'] ?>
                            </option>
                        <?php } else{ ?>
                            <option value="">All</option>
                        <?php } }?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="constituency">Constituency</label>
                    <select class="form-control " id="constituancy_id" name="constituancy">
                        <?php
                        if ($item['constituencies'] !== '') {
                            if($delivery_insentive_config['constituency']){
                            $constidata = $this->constituency_model->get($delivery_insentive_config['constituency']);

                        ?>
                            <option value="<?php echo $constidata['id'] ?>" <?php if ($constidata['id'] == $delivery_insentive_config['constituencies']) {
                                                                                echo "selected";
                                                                            } ?>><?php echo $constidata['name'] ?></option>
                        <?php }else{ ?>
                            <option >All</option>
                        <?php }
                            }
                        ?>

                    </select>
                </div>
            </div>
            <?php foreach ($shift as $shiftObj) :
                $delivery_insentive_config_obj = $delivery_insentive_config['shift_config'][$shiftObj['id']];
            ?>
                <div class="card">
                    <div class="card-body m-t-5">
                        <h5 class="card-title">Config - <?php echo $shiftObj['name'] ?> </h5>
                        <input type="hidden" value="<?php echo $shiftObj['id'] ?>" name="shift[]">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="allowed_delivery_boys_count">Allowed Delivery Boys</label>
                                <input type="number" value="<?php echo $delivery_insentive_config_obj['allowed_delivery_boys_count'] ?>" class="form-control" id="allowed_delivery_boys_count" name="allowed_delivery_boys_count_<?php echo $shiftObj['id'] ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="min_touch_points">Minimum Touch Points</label>
                                <input type="number" value="<?php echo $delivery_insentive_config_obj['min_touch_points'] ?>" class="form-control" id="min_touch_points" name="min_touch_points_<?php echo $shiftObj['id'] ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="req_ontime_delivery_percentage">Required On-time Delivery %</label>
                                <input type="number" value="<?php echo $delivery_insentive_config_obj['req_ontime_delivery_percentage'] ?>" class="form-control" id="req_ontime_delivery_percentage" name="req_ontime_delivery_percentage_<?php echo $shiftObj['id'] ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="amount_for_addtional_touch_point">Amount for Touch Point</label>
                                <input type="number" value="<?php echo $delivery_insentive_config_obj['amount_for_addtional_touch_point'] ?>" class="form-control" id="amount_for_addtional_touch_point" name="amount_for_addtional_touch_point_<?php echo $shiftObj['id'] ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="max_limit">Max. Cap Amount</label>
                                <input type="number" value="<?php echo $delivery_insentive_config_obj['max_limit'] ?>" class="form-control" id="max_limit" name="max_limit_<?php echo $shiftObj['id'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="card">
                <div class="card-body m-t-5">
                    <h5 class="card-title">Extra Insentives - Ratings</h5>
                    <div class="form-row align-items-center">
                        <?php
                        for ($i = 5; $i > 2; $i--) : ?>
                            <div class="form-group col-auto">
                                <label for="ratings_<?php echo $i ?>">Rating - <?php echo $i; ?></label>
                                <input type="number" value="<?php echo $delivery_insentive_config['rating_config'][$i]['amount'] ?>" class="form-control" id="ratings_<?php echo $i ?>" name="ratings_<?php echo $i ?>">
                                <input type="hidden" value="<?php echo $i ?>" name="ratings[]">
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>