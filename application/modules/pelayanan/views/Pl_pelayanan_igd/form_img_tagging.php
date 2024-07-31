<link rel="stylesheet" href="<?php echo base_url()?>assets/img-tagging/css/demo.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/img-tagging/css/tagging-photo.css">
<link rel="shortcut icon" href="<?php echo base_url()?>assets/img-tagging/images/favicon.png" type="image/x-icon">
<link rel="icon" href="<?php echo base_url()?>assets/img-tagging/images/favicon.png" type="image/x-icon">

<main role="main">
    <div class="album py-5 bg-light" id="Demo">
        <div class="container">
            <div class="row">
              <center>
                <div class="col-md-12 col-lg-12">
                    <div class="card mb-4 shadow-sm">
                        <img src="<?php echo base_url()?>assets/img-tagging/images/anatomi.jpg" style="max-width: 500px" data-points-color="red" class="tagging-photo bd-placeholder-img card-img-top" data-allow-add-tags="true" data-show-all-on-hover="true" data-show-tags-buttons="true" data-points-tooltip-follow="down" data-points='[{"top":1,"left":244,"text":"Bagian Kepala"},{"top":166,"left":320,"text":"Bagian Tangan"},{"top":387,"left":294,"text":"Bagian Kaki"}]' alt="">
                    </div>
                </div>
              </center>
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

    $('.btn-go-demo').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top
        }, 1200);
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
