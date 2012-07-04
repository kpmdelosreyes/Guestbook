<script src="<?php echo base_url();?>public/js/bbs.js" type="text/javascript"></script> 
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
                foreach ($data as $val): ?>    
            <tr class="event_mouse_over">
                <td><?php echo $val['id']?></td>
                <td class="subject">
                    <input type="checkbox" id="" name="delete[]" value="<?php echo $val['id']?>" title="delete" />
                    <a href="<?php echo site_url();?>view/index/<?php echo $val['id']?>"><?php echo $val['subject']?></a>
                </td>
                <td><?php echo $val['writer']?></td>                     
                <td><?php echo $val['registerTime']?></td>                     
            </tr>
        <?php endforeach; }?>
        </tbody>
    </table>
</div>  