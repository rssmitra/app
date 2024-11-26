<link rel="stylesheet" href="<?php echo base_url()?>assets/img-tagging/css/demo.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/img-tagging/css/tagging-photo.css">
<style>
    .card img.tagging-photo {
    margin: 15px 15px 0px 15px;
    display: block;
    width: calc(75% - 0px);
    border-radius: 5px;
}
</style>
<main role="main">
    <div class="album py-5 bg-light" id="Demo">
        <div class="container">
            <div class="row">
              <center>
                <form action="#" method="post" id="form_img_tagging" enctype="multipart/form-data">
                    <div class="col-md-12 col-lg-12">
                        <div class="card mb-4 shadow-sm">
                            <img src="<?php echo base_url()?>assets/img-tagging/images/anatomi.jpg" data-points-color="red" class="tagging-photo bd-placeholder-img card-img-top" data-allow-add-tags="true" data-show-all-on-hover="true" data-show-tags-buttons="true" data-points-tooltip-follow="down" data-points='<?php echo isset($img_tagging->data_points)?$img_tagging->data_points:''?>' data-value='' alt="">
                            <input type="hidden" name="no_kunjungan_img_tag" id="no_kunjungan_img_tag" value='<?php echo $no_kunjungan?>'>
                            <input type="hidden" name="img_tag_id" id="img_tag_id" value='<?php echo isset($img_tagging->img_tag_id)?$img_tagging->img_tag_id:''?>'>
                            <input type="hidden" name="cppt_id_img_tag" id="cppt_id_img_tag" value='<?php echo isset($img_tagging->cppt_id)?$img_tagging->cppt_id:''?>'>
                        </div>
                    </div>
                </form>
                <div id="msg_success"></div>
              </center>

              <div>
                <a href="#" class="btn" id="btn_save_img_tagging" style="background: #428bca; color: white; padding: 7px; text-decoration: none; font-family: arial; font-size: 12px;  ">Save Image</a>
              </div>
            </div>
        </div>
</main>

<script src="<?php echo base_url()?>assets/img-tagging/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo base_url()?>assets/img-tagging/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/img-tagging/js/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<script src="<?php echo base_url()?>assets/img-tagging/js/tagging-photo.js"></script>
<script>

    $.fn.scrollToTop = function () {
        if ($(window).scrollTop() >= 500) {
            $(this).fadeIn("slow");
        }
        var scrollDiv = $(this);
        $(window).scroll(function () {
            if ($(window).scrollTop() <= 500) {
                $(scrollDiv).fadeOut("fast");
            } else {
                $(scrollDiv).fadeIn(800);
            }
        });
        $(this).on('click', function () {
            $("html, body").animate({
                scrollTop: 0
            }, 800);
        });
    };


    $('#btn_save_img_tagging').on('click', function (e) {
        e.preventDefault();
        var content = JSON.parse($('div.photo-tagging').attr('data-points'));
        // save
        $.ajax({
            url : '<?php echo base_url()?>pelayanan/Pl_pelayanan_igd/save_img_tagging',
            data: {data_points : content, no_kunjungan : $('#no_kunjungan_img_tag').val(), cppt_id : $('#cppt_id_img_tag').val(), img_tag_id : $('#img_tag_id').val() },
            type: "POST",
            dataType: "JSON",      
            success: function(response) {  
                $('#img_tag_id').val(response.newId);
                $('#msg_success').html('<div class="alert alert-success"><b><i class="fa fa-check green bigger-150"></i></b> Berhasil menyimpan data.</div>');
            },
        });

    });

    $('.go-up').scrollToTop();

    $(window).on('load', function () {
        taggingPhoto.init($('img.tagging-photo'), {
            onAddTag: function (points) {
                console.log(points)
            }
        });
    });

</script>
