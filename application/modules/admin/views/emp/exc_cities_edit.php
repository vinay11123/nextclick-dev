<h3>Edit City Details</h3>

<form action="<?= base_url('admin/exc_cities/u'); ?>" method="post">

    <input type="hidden" name="id" value="<?= $city['id']; ?>">

    <div class="form-row">

        <div class="form-group col-md-4">
            <label><strong>City Name</strong></label>
            <input type="text" name="city_name" 
                   value="<?= $city['city_name']; ?>" 
                   class="form-control" required>
        </div>

        <div class="form-group col-md-4">
            <label><strong>Circle</strong></label>
            <input type="text" name="circle" 
                   value="<?= $city['circle']; ?>" 
                   class="form-control">
        </div>

        <div class="form-group col-md-4">
            <label><strong>Ward</strong></label>
            <input type="text" name="ward" 
                   value="<?= $city['ward']; ?>" 
                   class="form-control">
        </div>

    </div>

    <button class="btn btn-primary mt-2">
        <i class="fa fa-save"></i> Update
    </button>

    <a href="<?= base_url('admin/exc_cities/r'); ?>" 
       class="btn btn-secondary mt-2">
        Cancel
    </a>

</form>
