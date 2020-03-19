$(document).ready(function(){
  
    $('select[name="provinceId"]').change(function () {
        if ($(this).val()) {
            $.getJSON("<?php echo site_url('Templates/References/getRegencyByProvince') ?>/" + $(this).val(), '', function (data) {
                $('#regencyId option').remove();
                $('<option value="">-Pilih Kab/Kota-</option>').appendTo($('#regencyId'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#regencyId'));
                });

            });
        } else {
            $('#regencyId option').remove()
        }
    });

    $('select[name="regencyId"]').change(function () {
        if ($(this).val()) {
            $.getJSON("<?php echo site_url('Templates/References/getDistrictByRegency') ?>/" + $(this).val(), '', function (data) {
                $('#districtId option').remove();
                $('<option value="">-Pilih Kecamatan-</option>').appendTo($('#districtId'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#districtId'));
                });

            });
        } else {
            $('#regencyId option').remove()
        }
    });

    $('select[name="districtId"]').change(function () {
        if ($(this).val()) {
            $.getJSON("<?php echo site_url('Templates/References/getVillageByDistrict') ?>/" + $(this).val(), '', function (data) {
                $('#villageId option').remove();
                $('<option value="">-Pilih Kelurahan-</option>').appendTo($('#villageId'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#villageId'));
                });

            });
        } else {
            $('#villageId option').remove()
        }
    });

})