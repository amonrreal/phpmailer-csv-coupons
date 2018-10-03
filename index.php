<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>Bviral Email</title>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
        <script src="js/jquery.js"></script>
    </head>
    <body>
        <h1>Bviral Email</h1>
        <div id="main" class="col-sm-12 col-md-6 col-lg-6">
            <div id="csv_sec">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="csv_data" class="csv_upload"/>
                    <input type="submit" id="csv_upload" value="Upload CSV file"  />
                </form>
            </div>

            <?php
            $ext_error = "";
            $data = "";
            $csv_data = array();
            if (isset($_FILES) && (bool) $_FILES) {

                // Define allowed extensions
                $allowedExtentsoins = "csv";
                $file_name = $_FILES['csv_data']['name'];
                $temp_name = $_FILES['csv_data']['tmp_name'];
                $path_part = pathinfo($file_name);
                $ext = $path_part['extension'];

                // Checking for extension of attached files
                if ($ext != $allowedExtentsoins) {
                    echo "<script>alert('Sorry!!! ." . $ext . " file is not allowed!!! Try Again.')</script>";
                    $ext_error = TRUE;
                } else {
                    $ext_error = FALSE;
                }
                if ($ext_error == FALSE) {
                    echo "<script>alert('File successfully uploaded!!! Continue...')</script>";

                    // Store attached files in uploads folder
                    $file_path = dirname(__FILE__) . "/uploads/" . $path_part['basename'];
                    move_uploaded_file($temp_name, $file_path);

                    // Retrieve data from the CSV file and storing in $csv_data
                    $file = new SplFileObject($file_path);
                    $file->setFlags(SplFileObject::READ_CSV);
                    foreach ($file as $row) {
                       // Remove empty row and empty values from the uploaded csv data
                        $csv_data[] = array_filter($row);
                    }
                    $csv_data = array_filter($csv_data);
                    $data = htmlspecialchars(json_encode($csv_data));
                    // hide div of csv file
                    echo "<style type='text/css'>#csv_sec{display:none;}</style>"
                    ?>
                    <div id="login">
                        <h2>Message Box</h2>
                        <hr>
                        <form action="Mail.php" method="post">
                            <!-- <label>Subject : </label> -->
                            <input type="hidden" name="uploaded_file_path" value="<?php echo $file_path; ?>" />
                            <input type="hidden" name="user_list" value="<?php echo $data; ?>" />
                            <!-- <input type="text" name="email_sub" class="email_sub" /> -->
                            <?php
                            $input_array = array("CODE1", "CODE2", "CODE3", "CODE4", "CODE5");
                            ?>
                            <label for="exampleFormControlSelect1">Example select</label>
                            <select name="gift" class="form-control" id="exampleFormControlSelect1">
                              <option value="<?php echo $input_array[rand(0,sizeof($input_array)-1)]; ?>">25 dlls</option>
                              <option>50 dlls</option>
                              <option>100 dlls</option>
                              <option>200 dlls</option>
                              <option>250 dlls</option>
                            </select>
                            <!-- <label>Message : </label>
                            <textarea name="box_msg" rows="10" cols="30" class="box_msg">Message...</textarea> -->
                            <input type="submit" value="Send" id="submit"/>
                        </form>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <script>
            jQuery("#csv_upload").click(function(e) {
                var upload = jQuery('.csv_upload').val();
                if (upload == "") {
                    alert('Please Upload a CSV file!!!');
                    e.preventDefault();
                }
            });
            // jQuery("#submit").click(function(e) {
            //     var email_sub = jQuery('.email_sub').val();
            //     var box_msg = jQuery('.box_msg').val();
            //     if (email_sub == "") {
            //         alert('Subject is required!!!');
            //         e.preventDefault();
            //     }
            //     if (box_msg == "") {
            //         alert('Message is required!!!');
            //         e.preventDefault();
            //     }
            // });
        </script>
    </body>
</html>
