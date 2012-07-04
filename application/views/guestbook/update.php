<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Simple Bulletin Board System</title>

        <link rel="stylesheet" href="<?php echo base_url();?>public/css/redmond/jquery-ui-1.7.3.custom.css" type="text/css" media="all" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>public/css/board.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/bootstrap.css" media="screen, projection, handheld" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/bootstrap-responsive.css" media="screen, projection, handheld" />

        <script src="<?php echo base_url();?>public/js/jquery-1.3.2.min.js"></script>
        <script src="<?php echo base_url();?>public/js/jquery-ui-1.7.3.custom.min.js" type="text/javascript"></script> 
        <script type="text/javascript">
        $('document').ready(function() 
        {
            $( "#dialog" ).dialog({
                autoOpen: false
            });
            $( "#dialog_success" ).dialog({
                autoOpen: false
            });

            $('#btn_submit').click(function() {
                var writer = $("#writer").val();
                var subject = $("#subject").val();
                var message = $("#message").val();
                var error = false;
                var require = new Array();

                if (writer == "") {
                    require.push("writer");
                    error = true;
                }

                if (subject == "") {
                    require.push("subject");
                    error = true;
                }

                if (message == "") {
                    require.push("message");
                    error = true;
                }

                if (require.length > 0) {
                    $('.alert-error').show().fadeOut(50000);
                }

                if (error == false) {
                    $('form').submit();
                    $('.alert-success').show().fadeOut(10000000);
                }
            });

            $('#btn_cancel').click(function() {
                window.location.href="<?php echo site_url() ?>view/index/<?php echo $id?>";
            });
        });
        </script>
    </head>

    <body>
        <div id="wrap">
            <div id="content">
                <h2>Board Modify</h2>
                <div class="alert alert-success" style="text-align: center;display: none;">
                    Well done! You successfully saved.
                </div>
                <div class="alert alert-error" style="text-align: center;display: none;">
                    Oh snap! Change a few things up and try submitting again.
                </div>
                    <form action="<?php echo site_url();?>modify/update/<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                        <ul class="board_detail" style="list-style:none">
                            <li>
                                <dl>
                                    <dt>Writer</dt>
                                    <dd><input type="text" name="writer" id="writer" maxlength="15" value="<?php echo $writer ?>"/></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>Subject</dt>
                                    <dd><input type="text" name="subject" id="subject" value="<?php echo $subject ?>"/></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>Message</dt>
                                    <dd class="message"><textarea rows="8" cols="40" name="message" id="message"><?php echo $message ?></textarea></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>File</dt>
                                    <dd><input type="file" class="fileupload" name="file_upload" id="file_upload" /></dd>
                                </dl>
                            </li>
                            <li class="submit">
                                <input type="button" value="Save" class="btn btn-primary" name="btn_submit" id="btn_submit"/>
                                <input type="button" value="Cancel" class="btn btn-inverse" id="goList" onclick="javascript: window.location.href='<?php echo site_url() . 'lists';?>'" />
                            </li>
                        </ul>
                    </form>   
            </div>
        </div>
    </body>
</html>
