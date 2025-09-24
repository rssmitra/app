<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script>
    $(document).ready(function(){

        if($('#cppt_id').val() != ''){
            show_form_rekam_medis($('#cppt_id').val());
        }

        // --- AREA DRAWING & COLOR TAGGING ---
        var tagData = [];
        var currentTagPos = {x:0, y:0};
        var drawMode = 'point';
        var drawColor = '#ff0000';
        var drawing = false;
        var drawPoints = [];
        var drawCanvas = document.getElementById('anatomi-draw-canvas');
        var drawCtx = drawCanvas.getContext('2d');

        // Inisialisasi data tagging dari database jika ada
        var exist = $('#anatomi_tagging_exist').val();
        if (exist) {
            $('#anatomi_tagging').val(exist);
            try {
                var arr = JSON.parse(exist);
                if(Array.isArray(arr)) tagData = arr;
            } catch(e) {}
        }
        renderAllTagMarkers();

        function updateTagDataInput() {
            $('#anatomi_tagging').val(JSON.stringify(tagData));
        }

        function setDrawInstruction() {
            var instr = '';
            if(drawMode==='rect') instr = 'Klik & drag untuk rectangle';
            else if(drawMode==='polygon') instr = 'Klik beberapa titik, klik dua kali untuk selesai';
            else if(drawMode==='freehand') instr = 'Klik & drag untuk menggambar bebas';
            else instr = 'Klik pada gambar untuk menambah tag titik';
            $('#draw-instruction').text(instr);
        }

        $('#draw-mode').off('change').on('change', function(){
            drawMode = $(this).val();
            setDrawInstruction();
        });
        $('#draw-color').off('change').on('change', function(){
            drawColor = $(this).val();
        });
        setDrawInstruction();

        // Drawing interaction
        $(drawCanvas).off('mousedown').on('mousedown', function(e){
            var rect = drawCanvas.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            if(drawMode==='point') return;
            drawing = true;
            drawPoints = [{x:x, y:y}];
            if(drawMode==='rect') {
                drawCanvas.onmousemove = function(ev){
                    var rx = ev.clientX - rect.left, ry = ev.clientY - rect.top;
                    drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                    drawCtx.strokeStyle = drawColor;
                    drawCtx.lineWidth = 2;
                    drawCtx.strokeRect(drawPoints[0].x, drawPoints[0].y, rx-drawPoints[0].x, ry-drawPoints[0].y);
                };
            } else if(drawMode==='polygon') {
                drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                drawCtx.beginPath();
                drawCtx.moveTo(x,y);
                drawCanvas.onmousemove = function(ev) {
                    var px = ev.clientX - rect.left, py = ev.clientY - rect.top;
                    drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                    drawCtx.beginPath();
                    drawCtx.moveTo(drawPoints[0].x, drawPoints[0].y);
                    for(var i=1;i<drawPoints.length;i++) drawCtx.lineTo(drawPoints[i].x, drawPoints[i].y);
                    drawCtx.lineTo(px, py);
                    drawCtx.strokeStyle = drawColor;
                    drawCtx.lineWidth = 2;
                    drawCtx.closePath();
                    drawCtx.stroke();
                };
            } else if(drawMode==='freehand') {
                drawCtx.beginPath();
                drawCtx.moveTo(x,y);
                drawCanvas.onmousemove = function(ev){
                    if(!drawing) return;
                    var px = ev.clientX - rect.left, py = ev.clientY - rect.top;
                    drawPoints.push({x:px, y:py});
                    drawCtx.lineTo(px, py);
                    drawCtx.strokeStyle = drawColor;
                    drawCtx.lineWidth = 2;
                    drawCtx.stroke();
                };
            }
            $('#draw-cancel-btn').show();
        });

        $(drawCanvas).on('mouseup', function(e){
            if(!drawing) return;
            var rect = drawCanvas.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            if(drawMode==='rect') {
                drawPoints.push({x:x, y:y});
                drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                showAreaLabelModal('rect', drawPoints.slice(), drawColor);
            } else if(drawMode==='freehand') {
                drawCtx.closePath();
                showAreaLabelModal('freehand', drawPoints.slice(), drawColor);
            }
            drawing = false;
            drawCanvas.onmousemove = null;
        });

        $(drawCanvas).on('dblclick', function(e){
            if(drawMode==='polygon' && drawPoints.length>=3) {
                drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                showAreaLabelModal('polygon', drawPoints.slice(), drawColor);
                drawing = false;
                drawCanvas.onmousemove = null;
            }
        });

        $(drawCanvas).on('mousemove', function(e){
            if(!drawing || drawMode!=='polygon') return;
            var rect = drawCanvas.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            if(drawPoints.length>0) {
                drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                drawCtx.beginPath();
                drawCtx.moveTo(drawPoints[0].x, drawPoints[0].y);
                for(var i=1;i<drawPoints.length;i++) drawCtx.lineTo(drawPoints[i].x, drawPoints[i].y);
                drawCtx.lineTo(x, y);
                drawCtx.strokeStyle = drawColor;
                drawCtx.lineWidth = 2;
                drawCtx.closePath();
                drawCtx.stroke();
            }
        });

        $(drawCanvas).on('click', function(e){
            if(drawMode==='polygon' && drawing) {
                var rect = drawCanvas.getBoundingClientRect();
                var x = e.clientX - rect.left;
                var y = e.clientY - rect.top;
                drawPoints.push({x:x, y:y});
            }
        });

        $('#draw-cancel-btn').off('click').on('click', function(){
            drawing = false;
            drawPoints = [];
            drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
            $('#draw-cancel-btn').hide();
        });

        // Modal label input
        function showAreaLabelModal(type, points, color) {
            $('#tag-label-input').val('').focus();
            $('#tag-modal-overlay').show();
            $('#tag-input-modal').css({left:'50%',top:'50%',transform:'translate(-50%,-50%)'}).show();
            $('#tag-save-btn').off('click').on('click', function(){
                var label = $('#tag-label-input').val();
                if(label.trim()==='') return;
                var areaTag = {type:type, points:points, color:color, label:label};
                tagData.push(areaTag);
                updateTagDataInput();
                renderAllTagMarkers();
                $('#tag-input-modal').hide();
                $('#tag-modal-overlay').hide();
                drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                $('#draw-cancel-btn').hide();
            });
            $('#tag-cancel-btn').off('click').on('click', function(){
                $('#tag-input-modal').hide();
                $('#tag-modal-overlay').hide();
                drawCtx.clearRect(0,0,drawCanvas.width,drawCanvas.height);
                $('#draw-cancel-btn').hide();
            });
        }

        // Tagging titik (point)
        $('#anatomi-img').off('click').on('click', function(e){
            if(drawMode!=='point') return;
            var offset = $(this).offset();
            var x = e.pageX - offset.left;
            var y = e.pageY - offset.top;
            currentTagPos = {x: x, y: y};
            $('#tag-modal-overlay').show();
            $('#tag-input-modal').css({
                left: offset.left + x + 10,
                top: offset.top + y - 10
            }).show();
            $('#tag-label-input').val('').focus();
            $('#tag-save-btn').off('click').on('click', function(){
                var label = $('#tag-label-input').val();
                if(label.trim() === '') return;
                var img = document.getElementById('anatomi-img');
                var side = (currentTagPos.x < img.width/2) ? 'left' : 'right';
                var tag = {x: currentTagPos.x, y: currentTagPos.y, label: label, side: side, type:'point'};
                tagData.push(tag);
                updateTagDataInput();
                renderAllTagMarkers();
                $('#tag-input-modal').hide();
                $('#tag-modal-overlay').hide();
            });
            $('#tag-cancel-btn').off('click').on('click', function(){
                $('#tag-input-modal').hide();
                $('#tag-modal-overlay').hide();
            });
        });

        // Enter pada input label
        $('#tag-label-input').off('keypress').on('keypress', function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which);
            if(keycode ==13){
                event.preventDefault();
                $('#tag-save-btn').click();
                return false;
            }
        });

        // Fungsi ganti gambar anatomi
        function changeAnatomiImage(select) {
            var val = (select.value) ? select.value : '0';
            var imgSrc = '<?php echo base_url()?>assets/img-tagging/images/anatomi_'+val+'.png';
            document.getElementById('anatomi-img').src = imgSrc;
            tagData = [];
            updateTagDataInput();
            renderAllTagMarkers();
        }
        window.changeAnatomiImage = changeAnatomiImage;

        // Fungsi render marker dan area
        function addTagMarker(tag, idx) {
            if(tag.type && tag.type!=='point') return;
            var marker = $('<div class="anatomi-marker" title="'+tag.label+'"></div>');
            marker.css({
                position: 'absolute',
                left: tag.x-7,
                top: tag.y-7,
                width:'14px',height:'14px',
                background: '#007bff',
                border:'2px solid #fff',
                borderRadius: '50%',
                cursor: 'pointer',
                zIndex: 10
            });
            marker.click(function(e){
                e.stopPropagation();
                if(confirm('Hapus tag ini?')) {
                    tagData.splice(idx,1);
                    updateTagDataInput();
                    renderAllTagMarkers();
                }
            });
            $('#anatomi-tagging-container').append(marker);
        }

        function renderAllTagMarkers() {
            $('#anatomi-tagging-container .anatomi-marker').remove();
            $('#anatomi-svg-lines').empty();
            $('#anatomi-svg-areas').empty();
            $('#anatomi-tag-list-left').empty();
            $('#anatomi-tag-list-right').empty();
            var data = $('#anatomi_tagging').val();
            if(!data) return;
            try {
                var arr = JSON.parse(data);
                if(Array.isArray(arr)) {
                    var img = document.getElementById('anatomi-img');
                    var imgW = img.width;
                    var leftPanelY = 0;
                    var rightPanelY = 0;
                    arr.forEach(function(tag, idx) {
                        if(tag.type && tag.type!=='point') {
                            // Area (rectangle, polygon, freehand)
                            var svg = document.getElementById('anatomi-svg-areas');
                            var areaEl;
                            var centroid = {x:0, y:0};
                            if(tag.type==='rect' && tag.points.length===2) {
                                var x = tag.points[0].x, y = tag.points[0].y;
                                var w = tag.points[1].x - x, h = tag.points[1].y - y;
                                areaEl = document.createElementNS('http://www.w3.org/2000/svg','rect');
                                areaEl.setAttribute('x', x);
                                areaEl.setAttribute('y', y);
                                areaEl.setAttribute('width', w);
                                areaEl.setAttribute('height', h);
                                centroid.x = x + w/2;
                                centroid.y = y + h/2;
                            } else if(tag.type==='polygon' && tag.points.length>=3) {
                                var pts = tag.points.map(p=>p.x+','+p.y).join(' ');
                                areaEl = document.createElementNS('http://www.w3.org/2000/svg','polygon');
                                areaEl.setAttribute('points', pts);
                                // Centroid for polygon
                                var cx=0, cy=0;
                                tag.points.forEach(function(p){ cx+=p.x; cy+=p.y; });
                                centroid.x = cx/tag.points.length;
                                centroid.y = cy/tag.points.length;
                            } else if(tag.type==='freehand' && tag.points.length>=2) {
                                var d = 'M'+tag.points[0].x+','+tag.points[0].y;
                                for(var i=1;i<tag.points.length;i++) d+=' L'+tag.points[i].x+','+tag.points[i].y;
                                areaEl = document.createElementNS('http://www.w3.org/2000/svg','path');
                                areaEl.setAttribute('d', d);
                                // Centroid for freehand
                                var cx=0, cy=0;
                                tag.points.forEach(function(p){ cx+=p.x; cy+=p.y; });
                                centroid.x = cx/tag.points.length;
                                centroid.y = cy/tag.points.length;
                            }
                            if(areaEl) {
                                areaEl.setAttribute('fill', tag.color||'#ff0000');
                                areaEl.setAttribute('fill-opacity', 0.3);
                                areaEl.setAttribute('stroke', tag.color||'#ff0000');
                                areaEl.setAttribute('stroke-width', 2);
                                areaEl.style.cursor = 'pointer';
                                areaEl.addEventListener('click', function(){
                                    if(confirm('Hapus area ini?')) {
                                        tagData.splice(idx,1);
                                        updateTagDataInput();
                                        renderAllTagMarkers();
                                    }
                                });
                                svg.appendChild(areaEl);

                                // Label & garis
                                var tagDiv = $('<div class="anatomi-tag-label" style="background:'+tag.color+';color:#fff;cursor:pointer;">'+tag.label+'</div>');
                                tagDiv.click(function(){
                                    if(confirm('Hapus area ini?')) {
                                        tagData.splice(idx,1);
                                        updateTagDataInput();
                                        renderAllTagMarkers();
                                    }
                                });
                                var svgLines = document.getElementById('anatomi-svg-lines');
                                var labelPanel, labelX, labelY;
                                if(centroid.x < imgW/2) {
                                    labelPanel = $('#anatomi-tag-list-left');
                                    labelPanel.append(tagDiv);
                                    tagDiv.css({right:'8px',top:leftPanelY+'px',textAlign:'right'});
                                    leftPanelY += tagDiv.outerHeight() + 8;
                                } else {
                                    labelPanel = $('#anatomi-tag-list-right');
                                    labelPanel.append(tagDiv);
                                    tagDiv.css({left:'8px',top:rightPanelY+'px',textAlign:'left'});
                                    rightPanelY += tagDiv.outerHeight() + 8;
                                }
                                // Ambil posisi label relatif ke container
                                var containerOffset = $('#anatomi-tagging-container').offset();
                                var labelOffset = tagDiv.offset();
                                var labelHeight = tagDiv.outerHeight();
                                var labelY = labelOffset.top - containerOffset.top + (labelHeight/2);
                                if(centroid.x < imgW/2) {
                                    // Kiri: ujung kanan label
                                    labelX = labelOffset.left - containerOffset.left + tagDiv.outerWidth();
                                } else {
                                    // Kanan: ujung kiri label
                                    labelX = labelOffset.left - containerOffset.left;
                                }
                                // Gambar garis
                                var line = document.createElementNS('http://www.w3.org/2000/svg','line');
                                line.setAttribute('x1', centroid.x);
                                line.setAttribute('y1', centroid.y);
                                line.setAttribute('x2', labelX);
                                line.setAttribute('y2', labelY);
                                line.setAttribute('stroke', '#007bff');
                                line.setAttribute('stroke-width', '2');
                                svgLines.appendChild(line);
                            }
                        } else {
                            // Titik
                            addTagMarker(tag, idx);
                            var tagId = 'tag-label-'+idx;
                            var tagDiv = $('<div id="'+tagId+'" class="anatomi-tag-label" style="background:#f5f5f5;color:#222;">'+(tag.label||'Label')+'</div>');
                            tagDiv.click(function(){
                                if(confirm('Hapus tag ini?')) {
                                    tagData.splice(idx,1);
                                    updateTagDataInput();
                                    renderAllTagMarkers();
                                }
                            });
                            var markerX = tag.x;
                            var markerY = tag.y;
                            var svg = document.getElementById('anatomi-svg-lines');
                            var labelPanel, labelX, labelY;
                            if(tag.side === 'left') {
                                labelPanel = $('#anatomi-tag-list-left');
                                labelPanel.append(tagDiv);
                                tagDiv.css({right:'8px',top:leftPanelY+'px',textAlign:'right'});
                                leftPanelY += tagDiv.outerHeight() + 8;
                            } else {
                                labelPanel = $('#anatomi-tag-list-right');
                                labelPanel.append(tagDiv);
                                tagDiv.css({left:'8px',top:rightPanelY+'px',textAlign:'left'});
                                rightPanelY += tagDiv.outerHeight() + 8;
                            }
                            var containerOffset = $('#anatomi-tagging-container').offset();
                            var labelOffset = tagDiv.offset();
                            var labelHeight = tagDiv.outerHeight();
                            labelY = labelOffset.top - containerOffset.top + (labelHeight/2);
                            if(tag.side === 'left') {
                                labelX = labelOffset.left - containerOffset.left + tagDiv.outerWidth();
                            } else {
                                labelX = labelOffset.left - containerOffset.left;
                            }
                            // Garis dari marker ke label
                            var line = document.createElementNS('http://www.w3.org/2000/svg','line');
                            line.setAttribute('x1', markerX);
                            line.setAttribute('y1', markerY);
                            line.setAttribute('x2', labelX);
                            line.setAttribute('y2', labelY);
                            line.setAttribute('stroke', '#007bff');
                            line.setAttribute('stroke-width', '2');
                            svg.appendChild(line);
                        }
                    });
                    tagData = arr;
                }
            } catch(e) {}
        }

        function show_form_rekam_medis(myid){
            preventDefault();
            $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_cppt_dt') ?>", {id: myid} , function (response) {    
                // show data
                var obj = response.result;
                $('#cppt_id').val(myid);
                $('#jenis_form').val(obj.jenis_form);
                // $('#anatomi_tagging_28').val(response.anatomi_tagging);
                $('#form_rekam_medis_special_case').html(obj.catatan_pengkajian);
                $('#header_form').css('display', 'none');
                $('#footer_form').css('display', 'none');
                // set value input
                var value_form = response.value_form;
                console.log(value_form);
                $.each(value_form, function(i, item) {
                    var text = item;
                    text = text.replace(/\+/g, ' ');
                    key = i.replace(/\+/g, ' ');
                    $('#'+key).val(text);
                });
                

            }); 
        }
    });
</script>

<script type="text/javascript">
    
    jQuery(function($) {  

        $('.date-picker').datepicker({    

        autoclose: true,    

        todayHighlight: true    

        })  

        //show datepicker when clicking on the icon

        .next().on(ace.click_event, function(){    

        $(this).prev().focus();    

        });  

    });

    // var minutesCount = 0; 
    // var secondCount = 0; 
    // var centiSecondCount = 0;
    // var minutes = document.getElementById("minutes");
    // var second = document.getElementById("second");
    // var centiSecond = document.getElementById("centiSecond");

    $('#pl_diagnosa').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
                data: 'keyword=' + query,            
                dataType: "json",
                type: "POST",
                success: function (response) {
                result($.map(response, function (item) {
                        return item;
                    }));
                
                }
            });
        },
        afterSelect: function (item) {
        // do what is needed with item
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#pl_diagnosa').val(label_item);
        $('#pl_diagnosa_hidden').val(val_item);
        }

    });

    $('#pl_diagnosa_sekunder').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
                data: 'keyword=' + query,            
                dataType: "json",
                type: "POST",
                success: function (response) {
                result($.map(response, function (item) {
                        return item;
                    }));
                
                }
            });
        },
        afterSelect: function (item) {
        // do what is needed with item
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#pl_diagnosa_sekunder').val('');
        $('<span class="multi-typeahead" id="txt_icd_'+val_item.trim().replace('.', '_')+'"><a href="#" onclick="remove_icd('+"'"+val_item.trim().replace('.', '_')+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt');
        }

    });

    $( "#pl_diagnosa_sekunder" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            var val_item = 1 + Math.floor(Math.random() * 100);
            console.log(val_item);
            var item = $('#pl_diagnosa_sekunder').val();
            $('<span class="multi-typeahead" id="txt_icd_'+val_item+'"><a href="#" onclick="remove_icd('+"'"+val_item+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt'); 
          }          
          return $('#pl_diagnosa_sekunder').val('');                 
        }    
    });

    function remove_icd(icd){
        preventDefault();
        $('#txt_icd_'+icd+'').html('');
        $('#txt_icd_'+icd+'').hide();
    }

    <?php for ( $ix = 0; $ix < 30; $ix++) :?>
        $('#keyword_obat<?php echo $ix?>').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "templates/references/getObatByBagianAutoComplete",
                    data: { keyword:query, bag: '060101'},            
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                    }
                });
            },
            afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            var label_item=item.split(':')[1];
            console.log(val_item);
            $('#keyword_obat<?php echo $ix?>').val(label_item);

            }
        });
    <?php endfor; ?>

    $("#check_resep").change(function() {
        if(this.checked) {
            $('#form_input_resep').show();
        }else{
            $('#form_input_resep').hide();
        }
    });

    counterfile = <?php $j=2;echo $j.";";?>

    function hapus_file(a, b)
    {
        preventDefault();
        if(b != 0){
            /*$.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
                document.getElementById("file"+a).innerHTML = "";
                greatComplate(data);
            });*/
        }else{
            y = a ;
            document.getElementById("file"+a).innerHTML = "";
        }
    }

    function tambah_file()
    {
        preventDefault();
        counternextfile = counterfile + 1;
        counterIdfile = counterfile + 1;
        if(counternextfile > 3){
            var marginTop = 'style="margin-top:5px"';
        }else{
            var marginTop = '';
        }
        var html = "<div id=\"file"+counternextfile+"\" class='clonning_form'>\
                        <table "+marginTop+">\
                            <tr>\
                                <td><input type='text' class='inputKeyObat form-control' name='keyword_obat[]' id='keyword_obat"+counternextfile+"' placeholder='Masukan keyword obat' value='' style='width:330px'></td>\
                                <td><input type='text' class='form-control' name='dosis' id='dosis' value=''  placeholder='EX. 3 x 1' style='width: 94px;margin-left: 10px;'></td>\
                                <td><input type='text' class='form-control' name='jumlah_obat' id='jumlah_obat' value=''  placeholder='ex. 10 TAB' style='text-transform: uppercase; width: 94px;margin-left: 10px;'></td>\
                                <td>\
                                <a style='margin-left: 4px' href='#' class='btn btn-xs btn-primary' onClick='tambah_file()'><i class='fa fa-plus'></i></a>\
                                <a href='#' onclick='hapus_file("+counternextfile+",0)' class='btn btn-xs btn-danger'><i class='fa fa-times'></i></a>\
                                </td>\
                            </tr>\
                        </table>\
                    </div>\
                    <div id=\"input_file"+counternextfile+"\"></div>";

        document.getElementById("input_file"+counterfile).innerHTML = html;
        counterfile++;
    }

    $('#callPatientPoli').click(function (e) {  
      e.preventDefault();
      // setTimeout(playAudioDing(1, 2), 5000);
      var params = {
        no_kunjungan : $('#no_kunjungan').val(),
        dokter : $('#kode_dokter_poli').val(),
        poli : $('#kode_bagian_val').val(),
      };
      $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan/callPatient') ?>", params , function (response) { 
           // no action
      })
    });

    $('#inputKeyObat').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getObatByBagianAutoComplete",
                data: { keyword:query, bag: '060101'},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                }
            });
        },
        afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          var label_item=item.split(':')[1];
          console.log(val_item);
          $('#kode_brg_obat').val(val_item);
          $('#inputKeyObat').val(label_item);
          $('#jml_dosis').focus();

        }
    });

    function add_resep_obat(){

        preventDefault();  
        var formData = {
            id_pesan_resep_detail : $('#id_pesan_resep_detail').val(),
            no_registrasi : $('#no_registrasi').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            kode_brg : $('#kode_brg_obat').val(),
            nama_brg : $('#inputKeyObat').val(),
            jml_hari : $('#jml_hari').val(),
            jml_pesan : $('#jml_pesan').val(),
            jml_dosis : $('#jml_dosis').val(),
            jml_dosis_obat : $('#jml_dosis_obat').val(),
            satuan_obat : $('#satuan_obat').val(),
            aturan_pakai : $('#aturan_pakai').val(),
            no_mr : $('#no_mr_resep').val(),
            keterangan : $('#keterangan_resep').val(),
        };
        $.ajax({
            url: "pelayanan/Pl_pelayanan/add_resep_obat",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  
                    // hide row first
                    $('#row_'+jsonResponse.newId+'').remove();
                    // add to tabel
                    $('<tr id="row_'+jsonResponse.newId+'"><td align="center">'+jsonResponse.newId+'<input type="hidden" name="idobatdetail[]" value="'+jsonResponse.newId+'"></td><td>'+formData.kode_brg+' : '+formData.nama_brg+'</td><td>'+formData.jml_dosis+' x '+formData.jml_dosis_obat+' '+formData.satuan_obat+' '+formData.aturan_pakai+'</td><td>'+formData.keterangan+'</td><td align="center"><a href="#" class="btn btn-xs btn-warning" onclick="clickedit('+jsonResponse.newId+')"><i class="fa fa-pencil"></i></a> <a href="#" class="btn btn-xs btn-danger" onclick="deleterow('+jsonResponse.newId+')"><i class="fa fa-trash"></i></a></td></tr>').appendTo($('#dt_add_resep_obat')); 
                    $('#inputKeyObat').focus();
                    // reset form
                    reset_form_resep();
                }else{          
                    $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
                } 
                achtungHideLoader();
            }
        });

    }

    $( "#keterangan_resep" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#btn_add_resep_obat').click();            
          }          
          return false;                 
        }    
    });

    $( "#inputKeyObat" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_dosis').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_dosis" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_dosis_obat').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_dosis_obat" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#satuan_obat').focus();            
          }          
          return false;                 
        }    
    });

    $( "#satuan_obat" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#aturan_pakai').focus();            
          }          
          return false;                 
        }    
    });

    $( "#aturan_pakai" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_hari').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_hari" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_pesan').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_pesan" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#keterangan_resep').focus();            
          }          
          return false;                 
        }    
    });

    function reset_form_resep(){
        var jml_hari = ( $('#kode_perusahaan_val').val() == 120 ) ? 30 : "";
        $('#id_pesan_resep_detail').val("");
        $('#kode_brg_obat').val("");
        $('#inputKeyObat').val("");
        $('#jml_pesan').val("");
        $('#jml_hari').val(jml_hari);
        $('#jml_dosis').val("");
        $('#jml_dosis_obat').val("");
        $('#satuan_obat').val("Tab");
        $('#aturan_pakai').val("Sesudah Makan");
        $('#keterangan_resep').val("");
    }

    function clickedit(id){
        preventDefault();
        $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan/getrowresep') ?>", {ID: id} , function (response) {      
            console.log(response);
            $('#id_pesan_resep_detail').val(response.id);
            $('#kode_brg_obat').val(response.kode_brg);
            $('#inputKeyObat').val(response.nama_brg);
            $('#jml_hari').val(response.jml_hari);
            $('#jml_pesan').val(response.jml_pesan);
            $('#jml_dosis').val(response.jml_dosis);
            $('#jml_dosis_obat').val(response.jml_dosis_obat);
            $('#satuan_obat').val(response.satuan_obat);
            $('#aturan_pakai').val(response.aturan_pakai);
            $('#keterangan_resep').val(response.keterangan);
        })
    }

    function deleterow(id){
        preventDefault();
        $('#row_'+id+'').remove();
        $.ajax({
            url: "pelayanan/Pl_pelayanan/deleterowresep",
            data: { ID : id},            
            dataType: "json",
            type: "POST",
            success: function (response) {
                return false;
            }
        });
    }

    function countJmlObat(){
        var jml_hari = parseInt($('#jml_hari').val());
        var jml_obat = parseInt($('#jml_dosis_obat').val());
        // jml obat
        var ttl_pesan = jml_hari * jml_obat;
        $('#jml_pesan').val(ttl_pesan);
    }

    $('#pl_procedure').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefProcedure",
                  data: 'keyword=' + query,            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          },
          afterSelect: function (item) {
            // do what is needed with item
            var label_item=item.split('-')[1];
            var val_item=item.split('-')[0];
            console.log(val_item);
            $('#pl_procedure').val(label_item);
            $('#pl_procedure_hidden').val(val_item);
          }
    });

    function hide_assesment(){
        $('#form_rekam_medis_special_case').hide();
    }

    // Toggle tampil/hidden
    $('#checklist_status_lokaslis').on('change', function(){
        if($(this).is(':checked')){
        $('#form_status_lokalis').show();
        } else {
        $('#form_status_lokalis').hide(120);
        }
    });


</script>
<script src="<?php echo base_url()?>assets/tts/script.js"></script>
<!-- hidden form -->

<style>
    
.anatomi-marker {
  position: absolute;
  transition: 0.2s;
  box-shadow: 0 1px 4px rgba(0,0,0,0.15);
  z-index: 10;
}

#anatomi-tag-list-left, #anatomi-tag-list-right {
  position: relative;
  min-height: 350px;
  width: 200px;
  max-width: 100%;
  padding: 16px 0;
  overflow-x: hidden;
  overflow-y: auto;
  box-sizing: border-box;
}
.anatomi-tag-label {
  position: absolute;
  margin-bottom: 8px;
  padding: 7px 14px;
  border-radius: 4px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.07);
  min-width: 80px;
  max-width: 96%;
  word-break: break-word;
  white-space: pre-line;
  overflow: hidden;
  text-overflow: ellipsis;
  z-index: 2;
  font-size: 13px;
  display: block;
  left: 0;
  right: 0;
}
#anatomi-tagging-container { min-width:500px; }
#anatomi-draw-canvas { border-radius:8px; }
#anatomi-svg-lines, #anatomi-svg-areas {
  pointer-events: none;
  z-index: 5;
}
@media (max-width: 900px) {
  #anatomi-tag-list-left, #anatomi-tag-list-right {
    min-width: 120px;
    width: 120px;
    max-width: 40vw;
    font-size: 12px;
  }
  .anatomi-tag-label {
    font-size: 12px;
    max-width: 92%;
    padding: 6px 8px;
  }
}
@media (max-width: 600px) {
  #anatomi-tag-list-left, #anatomi-tag-list-right {
    min-width: 80px;
    width: 80px;
    max-width: 30vw;
    font-size: 11px;
    padding: 8px 0;
  }
  .anatomi-tag-label {
    font-size: 11px;
    max-width: 90%;
    padding: 5px 6px;
  }
}


#tag-modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.25);
  z-index: 105;
  transition: opacity 0.2s;
  display: none;
}
#tag-input-modal {
  transition: box-shadow 0.2s, transform 0.2s;
  max-width: 95vw;
  min-width: 240px;
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%,-50%);
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.18);
  border: 1px solid #007bff;
  padding: 0;
  z-index: 110;
  display: none;
}
#tag-input-modal input[type="text"] {
  font-size: 15px;
  padding: 8px 10px;
  border-radius: 6px;
  border: 1px solid #d5d5d5;
  width: 100%;
}
#tag-input-modal .btn {
  font-size: 14px;
  border-radius: 6px;
  min-width: 80px;
}
@media (max-width: 600px) {
  #tag-input-modal {
    min-width: 90vw;
    max-width: 98vw;
    padding: 0;
  }
  #tag-input-modal input[type="text"] {
    font-size: 14px;
  }
}

audio, canvas, progress, video {
    border: 0px !important;
}
</style>

<div class="widget-box transparent ui-sortable-handle collapsed" id="widget-box-12" style="display: block">
    <div class="widget-header">
        <span style="font-style: italic; font-size: 14px" class="widget-title lighter">Pengkajian Awal Keperawatan Pasien Rawat Jalan</h4>
        <div class="widget-toolbar no-border">
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
        </div>
    </div>

    <div class="widget-body" style="display: none;">
        <div id="form_rekam_medis_special_case">
            <div class="alert alert-danger"><b>Pemberitahuan!</b><br>Tidak ada File Ditemukan</div>
        </div>
    </div>
</div>


<div class="col-md-12" style="margin-top: 14px; margin-bottom: 20px">
    <span style="font-weight: bold; font-size: 16px"><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:''?></span> <br>
    <span>Tanggal periksa. <?php echo isset($value->tgl_keluar_poli)?$this->tanggal->formatDateTimeFormDmy($value->tgl_keluar_poli) : $this->tanggal->formatDateTimeFormDmy($value->tgl_jam_poli)?></span> <br>
</div>

<hr>
<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px; margin-top: 20px">(Subjective)</span>
<div style="margin-top: 6px">
    <label for="form-field-8"> <b>Anamnesa / Keluhan Pasien</b> <span style="color:red">* </span> </label> <br>
    <?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?>
    <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
</div>
<br>

<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px">(Objective)</span>

<div style="margin-top: 6px">
    <label for="form-field-8"> <i><b>Vital Sign</b></i><br></label>
    <table class="table">
        <tr style="font-size: 11px; background: beige;">
            <th>Tinggi Badan (Cm)</th>
            <th>Berat Badan (Kg)</th>
            <th>Tekanan Darah (mmHg)</th>
            <th>Nadi (bpm)</th>
            <th>Suhu Tubuh (C&deg;)</th>
        </tr>
        <tbody>
        <tr style="background: aliceblue;">
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_tb" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_bb" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_td" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_nadi" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_suhu" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
            </td>
        </tr>
        </tbody>
    </table>

    <label for="form-field-8"> <b>Pemeriksaan Fisik</b><br></label><br>
    <?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?>
    <input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>"><br>
    <br>
    
    <label for="form-field-8">
        <label><input type="checkbox" class="ace" name="checklist_status_lokaslis" id="checklist_status_lokaslis" <?php echo($riwayat->anatomi_tagging != null)?'checked':''?> ><span class="lbl"> <b>Status Lokalis</b></span></label>
         <br><span style="font-size: 11px; font-style: italic">(Mohon di<i>tagging</i> status lokalis pada gambar anatomi tubuh pasien)</span>
    </label>
    <!-- status lokalis -->
    <div id="form_status_lokalis" <?php echo($riwayat->anatomi_tagging != null)?'':'style="display: none;"'?>>
        <div class="form-group">
            <label class="control-label col-sm-2">Anatomi</label>
            <div class="col-md-4">
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anatomi', 'is_active' => 'Y')), isset($riwayat->anatomi_img)?$riwayat->anatomi_img:0 , 'anatomi', 'anatomi', 'form-control', 'onchange="changeAnatomiImage(this)"', '');?>
            </div>
        </div>  
        <br>
        
        <div class="col-md-12">
            <center><span style="font-weight: bold;font-size: 18px">VISUALISASI STATUS LOKALIS</span></center>
            <div style="display:flex;justify-content:center;align-items:flex-start;">
                <div id="anatomi-tag-list-left" style="min-width:180px;max-width:220px;position:relative;"></div>
                <div id="anatomi-tagging-container" style="position:relative; display:inline-block; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); border-radius:8px; padding:8px;">
                    <!-- Overlay dan Modal Input Label Tag -->
                    <div id="tag-modal-overlay" style="display:none;"></div>
                    <div id="tag-input-modal" style="display:none;">
                        <div style="padding:20px 18px 16px 18px; display:flex; flex-direction:column; align-items:center; gap:12px;">
                            <input type="text" id="tag-label-input" class="form-control" placeholder="Label lokasi..." style="width:100%; max-width:320px; font-size:15px;">
                            <div style="display:flex; gap:10px; width:100%; justify-content:flex-end;">
                            <button type="button" id="tag-save-btn" class="btn btn-sm btn-primary" style="min-width:80px;">Simpan</button>
                            <button type="button" id="tag-cancel-btn" class="btn btn-sm btn-default" style="min-width:80px;">Batal</button>
                            </div>
                        </div>
                    </div>

                    <div style="margin:10px 0 0 0; text-align:center;">
                        <label style="font-weight:500;">Mode:</label>
                        <select id="draw-mode" style="width:120px; margin:0 8px;">
                            <!-- <option value="point">Titik</option> -->
                            <option value="#">Pilih mode</option>
                            <option value="rect">Rectangle</option>
                            <!-- <option value="polygon">Polygon</option> -->
                            <option value="freehand">Freehand</option>
                        </select>
                        <input type="color" id="draw-color" value="#ff0000" style="margin-left:8px; vertical-align:middle;">
                        <span id="draw-instruction" style="margin-left:10px;color:#888;font-size:12px;"></span>
                        <button type="button" id="draw-cancel-btn" class="btn btn-xs btn-default" style="display:none; margin-left:8px;">Batal Gambar</button>
                    </div>

                    <?php
                        $img_anatomi = isset($riwayat->anatomi_img)?'anatomi_'.$riwayat->anatomi_img.'.png':'anatomi_0.png';
                    ?>
                    <div style="position:relative; width:500px; height:auto;">
                        <img src="<?php echo base_url('assets/img-tagging/images/'.$img_anatomi.'')?>" id="anatomi-img" style="width:500px; height:auto; display:block; border-radius:8px;">
                        <svg id="anatomi-svg-lines" style="position:absolute;left:0;top:0;width:100%;height:100%;pointer-events:none;"></svg>
                        <svg id="anatomi-svg-areas" style="position:absolute;left:0;top:0;width:100%;height:100%;pointer-events:none;"></svg>
                        <canvas id="anatomi-draw-canvas" width="500" height="650" style="position:absolute;left:0;top:0;width:500px; height:650px;pointer-events:auto;z-index:20;background:transparent; border-radius:8px;"></canvas>
                    </div>
                    
                </div>
                <div id="anatomi-tag-list-right" style="min-width:180px;max-width:220px;position:relative;"></div>
            </div>
            <input type="hidden" name="anatomi_tagging" id="anatomi_tagging" value="">
            <textarea name="anatomi_tagging_exist" id="anatomi_tagging_exist" style="width: 100% !important; display: none"><?php echo $riwayat->anatomi_tagging?></textarea>
        </div>
    </div>

</div>
<br>

<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px; ">(Assesment)</span>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Diagnosa Primer(ICD10)</b> <span style="color:red">* </span><br></label><br>
    <?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>
    <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Diagnosa Sekunder</b> <br></label>
    <div id="pl_diagnosa_sekunder_hidden_txt" style="padding: 2px; line-height: 23px; border: 1px solid #d5d5d5; min-height: 25px; margin-top: 2px">
        <?php
            $arr_text = isset($riwayat->diagnosa_sekunder) ? explode('|',$riwayat->diagnosa_sekunder) : [];
            // echo "<pre>";print_r($arr_text);
            $no_ds = 1;
            foreach ($arr_text as $k => $v) {
                $len = strlen(trim($v));
                // echo $len;
                if($len > 0){
                    $no_ds++;
                    $split = explode(':',$v);
                    if(count($split) > 1){
                        echo '<span class="multi-typeahead" id="txt_icd_'.trim(str_replace('.','_',$split[0])).'"><a href="#" onclick="remove_icd('."'".trim(str_replace('.','_',$split[0]))."'".')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                    }else{
                        echo '<span class="multi-typeahead" id="txt_icd_'.$no_ds.'"><a href="#" onclick="remove_icd('."'".$no_ds."'".')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                    }
                }
                
            }
        ?>
    </div>
    <input type="hidden" class="form-control" name="konten_diagnosa_sekunder" id="konten_diagnosa_sekunder" value="<?php echo isset($riwayat->diagnosa_sekunder)?$riwayat->diagnosa_sekunder:''?>">
</div>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Prosedur/ Tindakan(ICD9)</b> <span style="color:red">* </span></label><br>
    <?php echo isset($riwayat->text_icd9)?$riwayat->text_icd9:' Other consultation'?>
    <input type="hidden" class="form-control" name="pl_procedure_hidden" id="pl_procedure_hidden" value="<?php echo isset($riwayat->kode_icd9)?$riwayat->kode_icd9:'89.08'?>">
</div>

<br>
<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px">(Planning)</span>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Rencana Asuhan / Anjuran Dokter</b><br></label><br>
    <?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?>
</div>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Tanggal Kontrol Kembali</b><br></label><br>
    <?php $next_date = date('d/m/Y', strtotime("+31 days")); echo isset($riwayat->tgl_kontrol_kembali)?$this->tanggal->formatDate($riwayat->tgl_kontrol_kembali):$next_date?>
</div>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Catatan Kontrol</b></label><br>
    <?php echo isset($riwayat->catatan_kontrol_kembali)?$this->master->br2nl($riwayat->catatan_kontrol_kembali):'Tidak ada catatan'?>
</div>
<br>

<label for="form-field-8"><b>Informasi Pasien Pulang </b></label>
<label>Cara Keluar Pasien : </label><br>
    <i class="fa fa-arrow-right"></i> <?php echo $value->cara_keluar_pasien?>
<br>
<br>
<label>Pasca Pulang : </label><br>
    <i class="fa fa-arrow-right"></i> <?php echo $riwayat->pasca_pulang?>
<br>

<br>
<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px">(Resep Dokter)</span>
<div style="margin-top: 6px">
    <?php
        $eresep_result = isset($eresep[$value->no_registrasi][$value->no_kunjungan])?$eresep[$value->no_registrasi][$value->no_kunjungan]:array();
        // echo "<pre>"; print_r($eresep_result);die;
        $html = '';
        foreach($eresep_result as $key_er=>$val_er){
        $html .= '<small>Tanggal resep. <i>('.$this->tanggal->formatDateTime($val_er[0]->created_date).')</i></small>';
        $html .= '<br>';
        $html .= '<table class="table" id="dt_add_resep_obat">
            <thead>
            <tr>
                <th width="30px">No</th>
                <th>Nama Obat</th>
            </tr>
            </thead>
            <tbody style="background: white">';
            $no = 0;
            
            foreach ($val_er as $ker => $ver) {
            
            $no++;
            // get child racikan
            $child_racikan = $this->master->get_child_racikan_data($ver->kode_pesan_resep, $ver->kode_brg);
            $html_racikan = ($child_racikan != '') ? '<br><div style="padding:10px"><span style="font-size:11px; font-style: italic">bahan racik :</span><br>'.$child_racikan.'</div>' : '' ;
            $html .= '<tr>';
            $html .= '<td align="center" valign="top">'.$no.'</td>';
            $html .= '<td>'.strtoupper($ver->nama_brg).''.$html_racikan.'<br>'.$ver->jml_dosis.' x '.$ver->jml_dosis_obat.' '.$ver->satuan_obat.' '.$ver->aturan_pakai.'<br>Qty. '.$ver->jml_pesan.' '.$ver->satuan_obat.'<br>'.$ver->keterangan.'</td>';
            $html .= '</tr>';

            }
            // $html .= '<tr><td colspan="2" align="center"><a href="#" class="btn btn-xs btn-primary" onclick="resepkan_ulang('.$ver->kode_pesan_resep.')">Resepkan Kembali</a></td></tr>';

            $html .= '</tbody></table>';
        }
        echo $html;
    ?>
</div>

<br>
<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px">(File Pengkajian Pasien)</span><br>
<label for="form-field-8"><b>File Pengkajian Pasien per Periode Kunjungan </b></label><br>
<?php echo $html_file; ?>
<br>

<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px">(File Upload)</span><br>
<label for="form-field-8"><b>File Rekam Medis yang di upload per Periode Kunjungan </b></label><br>
<?php echo $html_file_rm; ?>

<br>
<span style="font-weight: bold; font-style: italic; color: blue; font-size: 14px">(Pemeriksaan Penunjang)</span>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Rencana Asuhan / Anjuran Dokter</b><br></label><br>
    
</div>

