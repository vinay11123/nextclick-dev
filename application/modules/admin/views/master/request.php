 

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>request</title>
    <style>
        button#btnAdd {
            background-color: #ff5722;
            border: none;
            position: relative;
            top: 29px;
        }
        
        button.btn.btn-danger.remove::before {
            content: "\03a7" !important;
            font-size: 14px;
        }
        
        label.form-label,
        label.control-label {
            color: #ff5722 !important;
            font-weight: 700 !important;
        }
        
        button.btn.btn-danger.remove {
            border: 50% !important;
            border-radius: 50%;
        }
        
        .table.table-responsive {
            overflow-x: hidden;
        }
        
        td.trtdbg {
            background-color: #343434;
            color: white;
        }
        
        .bgtextclorbrd {
            border: 2px solid #f4433636;
            Padding: 20px;
        }
        
        button.mysuces {
            background-color: #343434;
            border: none;
            padding: 7px;
            border-radius: 4px;
            color: white;
            margin: 12px;
        }

    </style>
</head>

<body>




    <section class="container">
        <div class="bgtextclorbrd">
            <from action="" method="" id="form3">
                <div class="table table-responsive">
                    <table class="table table-responsive table-striped table-bordered">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Request Type</label>
                                    <div class="selectContainer">
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                            <select name="department" class="form-control selectpicker">
                                                <option value="DEFAULT">Select your Request</option>
                                                <option value="PT"> Product Type</option>
                                                <option value="FD">Forgot pwd</option>
                                                <option value="DR">Date Rise</option>
                                                <option value="SO">Status Open</option>
                                                <option value="SC">Status Close</option>
                                                <option value="P">Process</option>
                                                <option value="PE">Process End</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-label">Request Id</label>
                                    <input id="sub" class="input-text form-control" type="text" placeholder="RequestIdnumber" required>

                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-label">Date 1</label>

                                    <input type="date" data-date-format="dd/mm/yyyy" name="fromdate" id="frmDt" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Date 2</label>
                                    <input type="date" data-date-format="dd/mm/yyyy" name="todate" id="toDt" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button id="btnAdd" type="button" class="btn btn-primary" data-toggle="tooltip" data-original-title="Add more controls"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp; Add&nbsp;</button>

                            </div>

                            <div class="thead">

                            </div>


                        </div>


                        <tbody id="TextBoxContainer">

                            <tr>
                                <td class="trtdbg">CustomerId</td>
                                <td class="trtdbg">PhoneNo</td>
                                <td class="trtdbg">EmailId</td>
                                <td class="trtdbg">Subject</td>
                                <td class="trtdbg">Message</td>

                                <!-- <td>TextBox</td>
                                <td>Dropdown List</td>
                                <td>Radio</td>-->
                                <td class="trtdbg">CheckBox</td>
                                <td class="trtdbg">RemoveList</td>
                            </tr>
                        </tbody>

                        <tfoot>


                        </tfoot>
                    </table>
                    <button class="mysuces" type="submit" form="form3" value="Submit">Submit</button>
                </div>
            </from>
        </div>
    </section>



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        $(document).ready(function() {


            $(function() {
                $("#btnAdd").bind("click", function() {
                    var div = $("<tr />");
                    div.html(GetDynamicTextBox(""));
                    $("#TextBoxContainer").append(div);
                });
                $("body").on("click", ".remove", function() {
                    $(this).closest("tr").remove();
                });
            });

            function GetDynamicTextBox(value) {
                return '<td><input name = "DynamicTextBox" type="id" placeholder="Id Nnumber" required value = "' + value + '" class="form-control" /></td>' + '<td><input name = "DynamicTextBox" type="tel" placeholder="Phone Nnumber" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" maxlength="12" required value = "' + value + '" class="form-control" /></td>' + '<td><input name = "DynamicTextBox" type="email" placeholder="Email Id" required value = "' + value + '" class="form-control" /></td>' + '<td><input name = "DynamicTextBox" type="subject" placeholder="Subject" maxlength="250" value = "' + value + '" class="form-control" /></td>' + '<td><input name = "DynamicTextBox" type="message" placeholder="Message" maxlength="250" value = "' + value + '" class="form-control" /></td>' + '<td><input name = "DynamicTextBox" type="checkbox" value = "' + value + '" /></td>' + '<td><button type="button" class="btn btn-danger remove"></button></td>'
            }


        });

    </script>

 