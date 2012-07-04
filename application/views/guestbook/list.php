<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Simple Bulletin Board System</title>

        <link rel="stylesheet" href="<?php echo base_url();?>public/css/redmond/jquery-ui-1.7.3.custom.css" type="text/css" media="all" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>public/css/board.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/bootstrap.css" media="screen, projection, handheld" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/bootstrap-responsive.css" media="screen, projection, handheld" />

        <script src="<?php echo base_url();?>public/js/jquery-1.3.2.min.js"></script>
        <script src="<?php echo base_url();?>public/js/jquery-ui-1.7.3.custom.min.js" type="text/javascript"></script> 
        <script src="<?php echo base_url();?>public/js/jqueryShiftCheckbox.js" type="text/javascript"></script> 
        <script src="<?php echo base_url();?>public/js/bbs.js" type="text/javascript"></script> 
    </head>
    <body>
        <div id="wrap">
            <div id="content">
                <h2>Board List</h2>
                <div class="search">
                    <select name="search_type" id="search_type">
                        <option value="subject">subject</option>
                        <option value="message">message</option>
                    </select>
                    <input type="text" value="" name="search_query" id="search_query" />
                    <input type="button" value="Search" id="btn_search" name="btn_search" />
                
                </div>
                
                <div id="div_list">
                    <p class="pull-right"><strong>Today: </strong><?php echo date('Y-m-d')?> / <strong>Total: </strong>  <?php echo count($data); ?></p>
                    <div class="alert alert-success" style="text-align: center;display: none;">
                        Deleted.
                    </div>
                    <div class="alert alert-error" style="text-align: center;display: none;">
                        Oh snap! Change a few things up and try submitting again.
                    </div>
                    <table class="board_list table table-striped">
                        <caption>&nbsp;</caption>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Subject</th>
                                <th>Writer</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($data == false){
                             echo '<tr class="event_mouse_over">';
                             echo '<td colspan="4" align="center"> No record(s) found.</td></tr>';

                        } else { 
                                $listcount = count($data);
                                foreach ($data as $val): ?>    
                            <tr class="event_mouse_over">
                                <td><?php echo $listcount; ?></td>
                                <td class="subject">
                                    <input type="checkbox" id="" name="delete[]" value="<?php echo $val['id']?>" title="delete" />
                                    <a href="<?php echo site_url();?>view/index/<?php echo $val['id']?>"><?php echo $val['subject']?></a>
                                </td>
                                <td><?php echo $val['writer']?></td>                     
                                <td><?php echo $val['registerTime']?></td>                     
                            </tr>
                        <?php $listcount--; endforeach; }?>
                        </tbody>
                    </table>
                </div>  
                
                <div style="display:inline;position:relative">
                    <div class="pull-left"> <?php echo $paginate;?> </div>
                    <div class="pull-right">
                        <input type="button" name="btn_delete" id="btn_delete" class="btn btn-danger" value="Delete" />
                        <input type="button" name="btn_write" id="btn_write" class="btn btn-primary" value="Write" onclick="window.location.href='<?php echo site_url();?>write'"/>
                        <input type="hidden" name="url" id="url" value="<?php echo site_url();?>" />
                     </div>
                </div>
        </div>
    </body>
</html>

