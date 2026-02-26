<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Executive</th>
            <th>Vendor Type</th>
            <th>City</th>
            <th>Permissions</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($exec_list as $row): ?>
            <tr>
                <td><?= $row->executive_name ?></td>
                <td><?= $row->vendor_type ?></td>
                <td><?= $row->city_name ?></td>
                <td>
                    <?php if (!empty($row->permission_list)): ?>
                        <ul style="padding-left:15px;">
                            <?php foreach ($row->permission_list as $p): ?>
                                <li><?= $p ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <span class="text-danger">No Permissions</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d-m-Y', strtotime($row->created_at)) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
