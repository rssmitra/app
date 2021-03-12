
<script>
$("#example-basic").treetable({
    expandable:     true,
});

$("#example-basic").treetable('expandAll');

$("#example-basic tbody").on("mousedown", "tr", function() {
    $(".selected").not(this).removeClass("selected");
    $(this).toggleClass("selected");
});
</script>

<style>
table.treetable span {
    padding: -0.8em 0 .2em 1.5em !important;
}

</style>

<table id="example-basic" class="table">
    <caption>
    <a href="#" onclick="jQuery('#example-basic').treetable('expandAll');  return false;" class="label label-sm label-success">Expand all</a>
    <a href="#" onclick="jQuery('#example-basic').treetable('collapseAll'); return false;" class="label label-sm label-primary">Collapse all</a>
    </caption>
    <thead>
    <tr>
        <th width="150px">Kode</th>
        <th width="30%">Nama Akun</th>
        <th width="100px">Tipe</th>
        <th width="100px">Level</th>
        <th>Last update</th>
    </tr>
    </thead>
    <tbody>
    <?php 
        foreach ($transaksi as $key => $val) :
        $parent_id = ($val->acc_ref == '')?'':'data-tt-parent-id="'.$val->acc_ref.'"';
    ?>
    <tr data-tt-id="<?php echo $val->acc_no; ?>" <?php echo $parent_id; ?> >
        <td><?php echo $val->acc_no?></td>
        <td><?php echo $val->acc_nama?></td>
        <td><?php echo $val->acc_type?></td>
        <td><?php echo $val->level_coa?></td>
        <td><?php echo $val->tgl_update?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>



