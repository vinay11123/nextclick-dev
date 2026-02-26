<!DOCTYPE html>
<?php $this->load->view('executive_app/header'); ?>
<?php $this->load->view('executive_app/navbar'); ?>
<?php $this->load->view('executive_app/sidebar'); ?>
<html>
<head>
    <title>Date Range Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .report-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: auto;
        }

        .report-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .date-range {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #2c3e50;
            color: #ffffff;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr:hover {
            background-color: #e6f2ff;
        }

        .footer {
            margin-top: 15px;
            font-size: 13px;
            text-align: right;
        }

        .date-inputs {
            margin-bottom: 15px;
            text-align: center;
        }

        .date-inputs input {
            padding: 5px;
            margin: 0 5px;
        }

        .btn {
            padding: 6px 12px;
            background-color: #2c3e50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>

<div class="report-container">
    
    <div class="report-title">
    REPORT
    </div>

<div class="date-inputs">
    <form method="get" action="<?= base_url('executive/reports'); ?>">
        From: <input type="date" name="from_date" value="<?= $this->input->get('from_date'); ?>">
        To: <input type="date" name="to_date" value="<?= $this->input->get('to_date'); ?>">
        <select name="filter_type">
        <option value="">-- Select Filter --</option>
        <option value="weekly" <?= $this->input->get('filter_type')=='weekly'?'selected':''; ?>>Weekly</option>
        <option value="monthly" <?= $this->input->get('filter_type')=='monthly'?'selected':''; ?>>Monthly</option>
        <option value="yearly" <?= $this->input->get('filter_type')=='yearly'?'selected':''; ?>>Yearly</option>
        </select>

        <button type="submit" class="btn">Generate Report</button>
          <a href="<?= base_url('executive/reports'); ?>" class="btn" 
           style="background-color:#888;">Clear</a>
    </form>
</div>

  
<table class="table table-striped table-hover" id="tableExport"
							style="width: 100%;">
							<thead>
								<tr>
									<th>Sno</th>
									<th>Executive ID</th>
									<th>Name</th>
									<th>email</th>
									<th>Created On</th>
									<th>Status</th>

								</tr>
							</thead>
							<tbody>
    							<?php  $sno = 1; foreach ($executives as $executive): ?>
    								<tr>
									<td><?php echo $sno++;?></td>
									<td><?php echo $executive['id'];?></td>
									<td><?php echo $executive['name'];?></td>
									<td><?php echo $executive['email'];?></td>
									<td><?php echo $executive['created_at'];?></td>
									<td> <?php if($executive['status'] == 1){
									       echo "Approve";
									}else{
									    echo "Disapprove";
									} ?>
    									</td>
								
								</tr>
    							<?php endforeach;?>
							
							
							</tbody>
						</table>

</div>

</body>
</html>
<?php $this->load->view('executive_app/scripts'); ?>
<?php $this->load->view('executive_app/footer'); ?>