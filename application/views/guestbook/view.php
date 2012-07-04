<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Simple Bulletin Board System</title>

        <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>public/css/board.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/bootstrap.css" media="screen, projection, handheld" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/bootstrap-responsive.css" media="screen, projection, handheld" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/jquery-ui-1.7.3.custom.css" media="screen, projection, handheld" />

        <script src="<?php echo base_url();?>public/js/jquery-1.3.2.min.js"></script>
        <script src="<?php echo base_url();?>public/js/jquery-ui-1.7.3.custom.min.js" type="text/javascript"></script>    

    </head>

    <body>
        <div id="wrap">
            <div id="content">
                <h2>Board View</h2>
                <ul class="board_detail" style="list-style:none;">
                    <li>
                        <dl>
                            <dt>Subject</dt>
                            <dd> <?php echo $subject ?> </dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Writer</dt>
                            <dd> <?php echo $writer ?> </dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Date</dt>
                        <dd> <?php echo date('d/m/Y', strtotime($registerTime))?> </dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt> Message </dt>
                            <dd class="message"> <?php echo $message ?> </dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>File</dt>
                            <dd>
                                 <a class="thumbnail" style="width: 245px;height: 185px;" href="<?php echo base_url() . 'image.php?h=365&w=400&path=' .$filename; ?>" title="<?php echo ucwords($filename); ?>">
                                    <img src="<?php echo base_url() . 'image.php?h=185&w=245&path=' .$filename; ?>" alt="" />
                                </a>
                            </dd>
                        </dl>
                    </li>
                    <li class="submit">
                        <input type="button" value="List" class="btn btn-info" id="btn_list" name="btn_list" onclick="javascript: window.location.href='<?php echo site_url() ."lists"; ?>'"/>
                        <input type="button" value="Modify" class="btn btn-primary" id="btn_modify" name="btn_modify" onclick="javascript: window.location.href='<?php echo site_url() ."modify/index/".$id; ?>'" />
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>