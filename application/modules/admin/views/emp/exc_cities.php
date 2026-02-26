<h3>Add City Details</h3>

<form action="<?= base_url('admin/exc_cities/c'); ?>" method="post">

    <div class="form-row">

        <div class="form-group col-md-4">
            <label><strong>City Name</strong></label>
            <input type="text" name="city_name" class="form-control" placeholder="Enter city name" required>
        </div>

        <div class="form-group col-md-4">
            <label><strong>Circle</strong></label>
            <input type="text" name="circle" class="form-control" placeholder="Enter circle">
        </div>

        <div class="form-group col-md-4">
            <label><strong>Ward</strong></label>
            <input type="text" name="ward" class="form-control" placeholder="Enter ward">
        </div>

    </div>

    <button class="btn btn-primary mt-2">
        <i class="fa fa-save"></i> Submit
    </button>

</form>

<hr>

<h4 class="mt-4">List of Cities</h4>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover text-center">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>City Name</th>
            <th>Circle</th>
            <th>Ward</th>
            <th>Status</th>
            <th width="180">Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($cities)): ?>
            <?php $i = 1; foreach ($cities as $row): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= ucfirst($row['city_name']); ?></td>
                <td><?= ucfirst($row['circle']); ?></td>
                <td><?= ucfirst($row['ward']); ?></td>

                <td>
                    <?php if ($row['status'] == 1): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactive</span>
                    <?php endif; ?>
                </td>

                <td>

                    <!-- STATUS TOGGLE -->
                    <a href="<?= base_url('admin/exc_cities/status?id=' . $row['id']); ?>"
                       class="btn btn-sm btn-warning">
                        <?= ($row['status'] == 1) ? 'Deactivate' : 'Activate'; ?>
                    </a>

                    <!-- EDIT -->
                    <a href="<?= base_url('admin/exc_cities/edit?id=' . $row['id']); ?>"
                       class="btn btn-sm btn-info">
                        Edit
                    </a>

                    <!-- DELETE -->
                    <a href="<?= base_url('admin/exc_cities/d'); ?>"
                       class="btn btn-sm btn-danger deleteCity"
                       data-id="<?= $row['id']; ?>">
                       Delete
                    </a>

                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-danger">No cities found</td>
            </tr>
        <?php endif; ?>
    </tbody>

</table>
</div>

<script>
    $(".deleteCity").click(function (e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete this city?")) {
            let id = $(this).data("id");
            $.post("<?= base_url('admin/exc_cities/d'); ?>", { id: id }, function() {
                location.reload();
            });
        }
    });
</script>
