<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>
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

    function toggleRiwayat(targetId, value) {
        var el = document.getElementById(targetId);
        if (!el) return;
        if (value === 'ada') {
            el.style.display = 'block';
            el.querySelector('textarea').focus();
        } else {
            el.style.display = 'none';
            el.querySelector('textarea').value = '';
        }
    }

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

    /* ── Stopwatch — Waktu Pelayanan ────────────────────────── */
    var _swInterval  = null;
    var _swRunning   = false;
    var _swElapsed   = 0; // total detik berjalan

    function _swRender() {
        var m  = Math.floor(_swElapsed / 60);
        var s  = _swElapsed % 60;
        $('#sw-minutes').text(m < 10 ? '0' + m : m);
        $('#sw-seconds').text(s < 10 ? '0' + s : s);
    }

    function startStopWatch() {
        if (_swRunning) return;
        _swRunning = true;
        $('#sw-btn-start').hide();
        $('#sw-btn-pause').show();
        $('#sw-status').text('Berjalan').css('color','#16a34a');
        _swInterval = setInterval(function () {
            _swElapsed++;
            _swRender();
        }, 1000);
    }

    function pauseStopWatch() {
        if (!_swRunning) return;
        _swRunning = false;
        clearInterval(_swInterval);
        _swInterval = null;
        $('#sw-btn-start').show();
        $('#sw-btn-pause').hide();
        $('#sw-status').text('Dijeda').css('color','#d97706');
    }

    function resetStopWatch() {
        pauseStopWatch();
        _swElapsed = 0;
        _swRender();
        $('#sw-status').text('Siap').css('color','#64748b');
        $('#sw-btn-start').show();
        $('#sw-btn-pause').hide();
    }

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
                    url: "templates/references/getObatByBagianAutoCompleteNoInfoStok",
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
                url: "templates/references/getObatByBagianAutoCompleteNoInfoStok",
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

/* ── Pemeriksaan Penunjang Medis Functions ─────────────────────── */
var pmOrderCount = 0;

function submitOrderPM(){
    var pmTujuan = $('#pm_tujuan_modal').val();
    var pmTujuanText = $('#pm_tujuan_modal option:selected').text();
    var jenisLayanan = $('input[name="jenis_layanan_pm_modal"]:checked').val();
    var keterangan = $('#keterangan_pm_modal').val();

    if(!pmTujuan || pmTujuan === ''){
        $.achtung({message: '<i class="fa fa-warning"></i> Silahkan pilih Unit Tujuan Penunjang', timeout: 4, className: 'achtungFail'});
        return;
    }

    var formData = {
        noMrHidden: $('#noMrHidden').val(),
        no_registrasi_rujuk: $('#no_registrasi').val(),
        klas_rujuk: $('#kode_klas_val').val() || 16,
        bagian_asal: $('#kode_bagian_val').val(),
        asal_pasien_pm: $('#kode_bagian_val').val(),
        pm_tujuan: pmTujuan,
        jenis_layanan_pm: jenisLayanan,
        keterangan_pm: keterangan,
        kode_perusahaan_hidden: $('#kode_perusahaan_val').val() || '',
        kode_kelompok_hidden: '',
        umur_saat_pelayanan_hidden: $('#umur_saat_pelayanan_hidden').val() || '',
        nama_pasien_hidden: $('#nama_pasien_hidden').val() || ''
    };

    $.ajax({
        url: 'registration/Reg_pm/process',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function(){
            $('.fdd-pm-btn-submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        },
        complete: function(xhr){
            $('.fdd-pm-btn-submit').prop('disabled', false).html('<i class="fa fa-check"></i> Submit Order');
            var data = xhr.responseText;
            var json = JSON.parse(data);
            if(json.status === 200){
                $.achtung({message: '<i class="fa fa-check-circle"></i> Order Penunjang Medis berhasil disimpan', timeout: 4, className: 'achtungSuccess'});
                // Reset modal form
                $('#pm_tujuan_modal').val('');
                $('input[name="jenis_layanan_pm_modal"][value="0"]').prop('checked', true);
                $('#keterangan_pm_modal').val('');
                $('#modal-penunjang-medis').modal('hide');

                // Set radio to Ya and show list
                $('input[name="ada_penunjang"][value="Y"]').prop('checked', true);
                $('#fdd_pm_list').slideDown(200);

                // Reload PM order list
                loadPMOrders();
            } else {
                $.achtung({message: json.message, timeout: 5, className: 'achtungFail'});
            }
        }
    });
}

function loadPMOrders(){
    var noMr = $('#noMrHidden').val();
    var bagianAsal = $('#kode_bagian_val').val();
    var noReg = $('#no_registrasi').val();

    if(!noMr || !noReg) return;

    $.ajax({
        url: 'pelayanan/Pl_pelayanan_pm/get_data_order?search_by=no_mr&keyword=' + noMr + '&bagian_asal=' + bagianAsal + '&no_reg=' + noReg,
        type: 'POST',
        data: { draw: 1, start: 0, length: 50, search: {value: ''} },
        dataType: 'json',
        success: function(res){
            var container = $('#fdd_pm_cards');
            container.empty();
            pmOrderCount = 0;

            if(res.data && res.data.length > 0){
                $('input[name="ada_penunjang"][value="Y"]').prop('checked', true);
                $('#fdd_pm_list').show();

                $.each(res.data, function(i, row){
                    pmOrderCount++;
                    /*
                     * [0] no_registrasi  [1] no_kunjungan  [2] str_type
                     * [3] id_pm_tc_penunjang  [4] kode_bagian_tujuan  [5] ''
                     * [6] dropdown HTML  [7] tgl_masuk  [8] no_mr+nama
                     * [9] penjamin  [10] asal->tujuan HTML  [11] status HTML
                     * [12] pengantar buttons HTML
                     */
                    var noKunjungan = row[1] || '';
                    var idPm = row[3] || '';
                    var kodeBagTujuan = row[4] || '';
                    var asalTujuan = row[10] || '';
                    var statusHtml = row[11] || '';
                    var klas = $('#kode_klas_val').val() || 16;
                    var btnPengantar = buildPengantarButtons(noKunjungan, idPm, kodeBagTujuan, bagianAsal, noMr, klas);

                    // Determine unit icon & label
                    var unitIcon = 'fa-flask', unitLabel = 'Penunjang Medis';
                    if(kodeBagTujuan === '050101'){ unitIcon = 'fa-flask'; unitLabel = 'Laboratorium'; }
                    else if(kodeBagTujuan === '050201'){ unitIcon = 'fa-x-ray'; unitLabel = 'Radiologi'; }
                    else if(kodeBagTujuan === '050301'){ unitIcon = 'fa-heartbeat'; unitLabel = 'Fisioterapi'; }

                    var card = '<div class="fdd-pm-card" id="fdd_pm_card_' + idPm + '">' +
                        '<div class="fdd-pm-card-hdr">' +
                            '<div class="fdd-pm-card-info">' +
                                '<span class="fdd-pm-card-num">' + pmOrderCount + '</span>' +
                                '<div>' +
                                    '<div class="fdd-pm-card-unit"><i class="fa ' + unitIcon + '"></i> ' + unitLabel + '</div>' +
                                    '<div class="fdd-pm-card-tujuan">' + asalTujuan + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="fdd-pm-card-actions">' +
                                statusHtml +
                                ' <button type="button" class="fdd-pm-btn-del" onclick="deletePMOrder(\'' + row[0] + '\',\'' + noKunjungan + '\')" title="Hapus"><i class="fa fa-trash"></i></button>' +
                            '</div>' +
                        '</div>' +
                        '<div class="fdd-pm-card-detail" id="fdd_pm_detail_' + idPm + '">' +
                            '<div class="fdd-pm-detail-loading"><i class="fa fa-spinner fa-spin"></i> Memuat rincian...</div>' +
                        '</div>' +
                        '<div class="fdd-pm-card-footer">' + btnPengantar + '</div>' +
                    '</div>';

                    container.append(card);

                    // Load examination details for this order
                    loadPMDetail(idPm, kodeBagTujuan, noMr);
                });
            } else {
                container.html('<div class="fdd-pm-empty-state"><i class="fa fa-inbox"></i> Belum ada order penunjang medis</div>');
                syncPengobatanTextarea();
            }
        }
    });
}

function loadPMDetail(idPm, kodeBag, noMr){
    var el = $('#fdd_pm_detail_' + idPm);
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_pm/get_order_detail_json',
        type: 'GET',
        data: { id_pm_tc_penunjang: idPm },
        dataType: 'json',
        cache: false,   // Paksa fresh response, hindari browser cache
        success: function(res){
            if(res.status === 200 && res.data && res.data.length > 0){
                var chips = '';
                $.each(res.data, function(j, item){
                    chips += '<span class="fdd-pm-chip"><i class="fa fa-check-circle"></i> ' + item.nama_tarif + '</span>';
                });
                el.html('<div class="fdd-pm-detail-label"><i class="fa fa-list-ul"></i> Rincian Pemeriksaan (' + res.data.length + ')</div><div class="fdd-pm-chips">' + chips + '</div>');
            } else {
                el.html('<div class="fdd-pm-detail-empty"><i class="fa fa-info-circle"></i> Belum ada pemeriksaan yang dipilih</div>');
            }
            syncPengobatanTextarea();
        },
        error: function(){
            el.html('<div class="fdd-pm-detail-empty"><i class="fa fa-info-circle"></i> Belum ada pemeriksaan yang dipilih</div>');
            syncPengobatanTextarea();
        }
    });
}

function buildPengantarButtons(noKunjungan, idPm, kodeBagTujuan, bagianAsal, noMr, klas){
    var labelBuat = 'Buat Pengantar';
    var formUrl = '';

    var modalTitle = '';

    if(kodeBagTujuan === '050101'){
        labelBuat = '<i class="fa fa-file-text-o"></i> Pengantar Lab';
        modalTitle = 'Form Permintaan Pemeriksaan Laboratorium';
        formUrl = 'pelayanan/Pl_pelayanan/form_lab_detail/' + noKunjungan + '/' + idPm +
                  '?type=PM&kode_bag=' + kodeBagTujuan + '&kode_bag_asal=' + bagianAsal +
                  '&no_mr=' + noMr + '&klas=' + klas;
    } else if(kodeBagTujuan === '050201'){
        labelBuat = '<i class="fa fa-file-text-o"></i> Pengantar Rad';
        modalTitle = 'Form Permintaan Pemeriksaan Radiologi';
        formUrl = 'pelayanan/Pl_pelayanan/form_order_radiologi/' + noKunjungan + '/' + idPm +
                  '?type=PM&kode_bag=' + kodeBagTujuan + '&kode_bag_asal=' + bagianAsal +
                  '&no_mr=' + noMr + '&klas=' + klas;
    } else if(kodeBagTujuan === '050301'){
        labelBuat = '<i class="fa fa-file-text-o"></i> Pengantar Fisio';
        modalTitle = 'Form Permintaan Pemeriksaan Fisioterapi';
        formUrl = 'pelayanan/Pl_pelayanan/form_order_fisio/' + noKunjungan + '/' + idPm +
                  '?type=PM&kode_bag=' + kodeBagTujuan + '&kode_bag_asal=' + bagianAsal +
                  '&no_mr=' + noMr + '&klas=' + klas;
    } else {
        labelBuat = '<i class="fa fa-file-text-o"></i> Buat Pengantar';
        modalTitle = 'Form Pengantar Penunjang Medis';
        formUrl = 'pelayanan/Pl_pelayanan/form_lab_detail/' + noKunjungan + '/' + idPm +
                  '?type=PM&kode_bag=' + kodeBagTujuan + '&kode_bag_asal=' + bagianAsal +
                  '&no_mr=' + noMr + '&klas=' + klas;
    }

    var cetakUrl = 'pelayanan/Pl_pelayanan_pm/preview_pengantar_penunjang/' + noKunjungan +
                   '?id_pm_tc_penunjang=' + idPm + '&type=PM&kode_bagian=' + kodeBagTujuan +
                   '&kode_bag_asal=' + bagianAsal + '&no_mr=' + noMr + '&klas=' + klas;

    var html = '<a href="#" onclick="openFormPengantar(\'' + formUrl + '\', \'' + modalTitle + '\')" class="fdd-pm-btn-pengantar">' + labelBuat + '</a>' +
               '<a href="#" onclick="PopupCenter(\'' + cetakUrl + '\', \'Cetak Pengantar PM\', 800, 600)" class="fdd-pm-btn-cetak"><i class="fa fa-print"></i> Cetak Pengantar</a>';
    return html;
}

function openFormPengantar(url, title){
    title = title || 'Form Pengantar Penunjang Medis';
    var body = $('#modal-pengantar-pm-body');
    $('#modal-pengantar-pm-title').html('<i class="fa fa-file-text-o"></i> ' + title);
    body.html('<div style="text-align:center;padding:40px"><i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i><p style="margin-top:10px;color:#64748b;font-size:13px">Memuat form pengantar...</p></div>');
    $('#modal-pengantar-pm').modal('show');

    $.get(url, function(html){
        body.html(html);
        // Override the original btn_proses_order_lab click handler
        overridePengantarSubmit();
    }).fail(function(){
        body.html('<div class="alert alert-danger" style="margin:20px;font-size:13px"><i class="fa fa-exclamation-triangle"></i> Gagal memuat form pengantar. Silahkan coba lagi.</div>');
    });
}

function overridePengantarSubmit(){
    // Cegah form di dalam modal melakukan submit biasa (page navigation)
    $('#modal-pengantar-pm-body form').off('submit').on('submit', function(e){
        e.preventDefault();
    });
    // Unbind semua handler termasuk inline onclick, lalu rebind dengan AJAX handler
    $(document).off('click', '#btn_proses_order_lab');
    $('#btn_proses_order_lab').off('click').removeAttr('onclick').on('click', function(e){
        e.preventDefault();

        // Serialize all inputs inside the modal body + append parent form fields
        var formData = $('#modal-pengantar-pm-body :input').serialize();
        formData += '&noMrHidden=' + encodeURIComponent($('#noMrHidden').val());
        formData += '&no_registrasi=' + encodeURIComponent($('#no_registrasi').val());
        formData += '&kode_perusahaan=' + encodeURIComponent($('#kode_perusahaan_val').val());
        formData += '&kode_kelompok=' + encodeURIComponent($('input[name="kode_kelompok"]').val());
        formData += '&dokter_pemeriksa=' + encodeURIComponent($('#dokter_pemeriksa').val());

        $.ajax({
            url: 'pelayanan/Pl_pelayanan_pm/process_order_lab',
            data: formData,
            dataType: 'json',
            type: 'POST',
            beforeSend: function(){
                $('#btn_proses_order_lab').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function(response){
                $('#btn_proses_order_lab').prop('disabled', false)
                    .html('<i class="fa fa-save"></i> Simpan dan Proses Permintaan Penunjang');

                if(response.status == 200){
                    $('#modal-pengantar-pm').modal('hide');
                    loadPMOrders(); // Refresh kartu & chips segera setelah simpan
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Permintaan penunjang medis berhasil disimpan.',
                        confirmButtonColor: '#0891b2',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        loadPMOrders(); // Refresh sekali lagi setelah user klik OK
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Terjadi kesalahan saat menyimpan.',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(){
                $('#btn_proses_order_lab').prop('disabled', false)
                    .html('<i class="fa fa-save"></i> Simpan dan Proses Permintaan Penunjang');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Koneksi gagal. Silahkan coba lagi.',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'OK'
                });
            }
        });

    });

    // ── Override btn_add_tindakan (Radiologi) & btn_add_tindakan_fisio (Fisioterapi) ──
    // Setiap kali pemeriksaan ditambahkan, langsung refresh chips di planning penunjang
    // dan sync ke textarea pl_pengobatan — sama seperti perilaku input resep obat.
    function _overrideAddTindakan(btnId, extraResets) {
        $(document).off('click', '#' + btnId);
        $('#' + btnId).off('click').on('click', function(e) {
            e.preventDefault();
            var idPm    = $('#id_pm_tc_penunjang').val();
            var noMr    = $('#noMrHidden').val();
            var kodeBag = $('#kode_bagian_pm').val();
            $.ajax({
                url: 'pelayanan/Pl_pelayanan_pm/process_order_penunjang',
                data: $('#form_pelayanan').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(response) {
                    if (response.status == 200) {
                        $('#no_kunjungan_pm').val(response.no_kunjungan);
                        $('#InputKeyTindakan').val('').focus();
                        $('#pl_kode_tindakan_hidden').val('');
                        $('#pl_keterangan_tindakan').val('');
                        if (typeof extraResets === 'function') extraResets();
                        reset_table();
                        // Refresh chips di planning penunjang & sync pl_pengobatan
                        loadPMDetail(idPm, kodeBag, noMr);
                    } else {
                        alert('Silahkan cari pasien !');
                    }
                }
            });
        });
    }

    _overrideAddTindakan('btn_add_tindakan', null);
    _overrideAddTindakan('btn_add_tindakan_fisio', function() {
        $('#xray_foto').val('');
        $('#kontra_indikasi').val('');
        $('#pl_diagnosa').val('');
        $('#pl_diagnosa_hidden').val('');
    });

    // ── Override delete_transaksi ──
    // Refresh chips di planning penunjang setelah menghapus pemeriksaan
    window.delete_transaksi = function(myid) {
        if (!confirm('Are you sure?')) return false;
        var idPm    = $('#id_pm_tc_penunjang').val();
        var noMr    = $('#noMrHidden').val();
        var kodeBag = $('#kode_bagian_pm').val();
        $.ajax({
            url: 'pelayanan/Pl_pelayanan_pm/delete_order',
            type: 'post',
            data: { ID: myid },
            dataType: 'json',
            beforeSend: function() { achtungShowLoader(); },
            complete: function(xhr) {
                var jsonResponse = JSON.parse(xhr.responseText);
                if (jsonResponse.status === 200) {
                    $.achtung({ message: jsonResponse.message, timeout: 5 });
                    reset_table();
                    loadPMDetail(idPm, kodeBag, noMr);
                } else {
                    $.achtung({ message: jsonResponse.message, timeout: 5 });
                }
                achtungHideLoader();
            }
        });
    };
}

function deletePMOrder(noReg, noKunjungan){
    if(!confirm('Yakin ingin menghapus order penunjang medis ini?')) return;
    $.ajax({
        url: 'registration/Reg_pasien/delete_registrasi',
        type: 'POST',
        data: { ID: noReg, KunjunganID: noKunjungan },
        dataType: 'json',
        complete: function(xhr){
            var data = xhr.responseText;
            var json = JSON.parse(data);
            if(json.status === 200){
                $.achtung({message: '<i class="fa fa-check-circle"></i> Order berhasil dihapus', timeout: 4, className: 'achtungSuccess'});
                loadPMOrders();
            } else {
                $.achtung({message: json.message, timeout: 5, className: 'achtungFail'});
            }
        }
    });
}

// ── Pemeriksaan Penunjang toggle & init ──
$('input[name="ada_penunjang"]').on('change', function(){
    if($(this).val() === 'Y'){
        $('#fdd_pm_list').slideDown(200);
    } else {
        $('#fdd_pm_list').slideUp(200);
    }
});

// Load existing PM orders on page load
loadPMOrders();
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

/* ── SOAP Form Redesign — scoped to #fdd-wrap ─────────────────── */
#fdd-wrap { font-family: 'Segoe UI', system-ui, Arial, sans-serif; font-size: 13px; }
#fdd-wrap * { box-sizing: border-box; }

.fdd-patient-hdr {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  border: 1px solid #bae6fd; border-radius: 10px;
  padding: 14px 18px; margin-bottom: 16px;
  display: flex; align-items: center; gap: 16px;
  flex-wrap: wrap;
}
.fdd-soap-badge {
  font-size: 16px; font-weight: 900; letter-spacing: 5px;
  color: #0369a1; background: #fff;
  border: 2px solid #bae6fd; border-radius: 8px;
  padding: 8px 14px; flex-shrink: 0; white-space: nowrap;
}
.fdd-patient-name { font-size: 15px; font-weight: 700; color: #0f172a; }
.fdd-patient-meta { font-size: 12px; color: #64748b; margin-top: 4px; }
.fdd-patient-meta i { color: #0ea5e9; }

.fdd-iter-alert {
  display: flex; align-items: center; gap: 14px;
  background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
  border: 1.5px solid #feb9b4; border-left: 4px solid #f6615c;
  border-radius: 10px; padding: 12px 18px; margin-bottom: 14px;
  animation: fddIterPulse 2s ease-in-out infinite;
}
@keyframes fddIterPulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(139,92,246,0); }
  50% { box-shadow: 0 0 0 4px rgba(139,92,246,0.1); }
}
.fdd-iter-icon {
  width: 40px; height: 40px; border-radius: 10px;
  background: linear-gradient(135deg, #f65c5c, #d92828);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 18px; flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(246, 92, 92, 0.3);
}
.fdd-iter-body { flex: 1; min-width: 0; }
.fdd-iter-title {
  font-size: 13px; font-weight: 700; color: #b62121; margin-bottom: 2px;
}
.fdd-iter-desc {
  font-size: 12px; color: #6b7280; line-height: 1.5;
}
.fdd-iter-desc strong {
  color: #b62121; font-size: 14px;
}

.fdd-section {
  border-radius: 10px; border: 1px solid #e2e8f0;
  overflow: visible; margin-bottom: 14px;
  background: #fff; box-shadow: 0 1px 5px rgba(0,0,0,.05);
}
.fdd-section-hdr {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 16px; border-bottom: 1px solid rgba(0,0,0,.06);
  border-radius: 10px 10px 0 0;
}
.fdd-section-tag {
  width: 32px; height: 32px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; font-weight: 900; flex-shrink: 0;
}
.fdd-section-title { font-size: 13px; font-weight: 700; }
.fdd-section-sub { font-size: 11px; color: #94a3b8; margin-left: auto; white-space: nowrap; }
.fdd-section-body { padding: 14px 16px; }

.fdd-section-s .fdd-section-hdr  { background: #eff6ff; border-bottom-color: #bfdbfe; }
.fdd-section-s .fdd-section-tag  { background: #3b82f6; color: #fff; }
.fdd-section-s .fdd-section-title{ color: #1d4ed8; }
.fdd-section-o .fdd-section-hdr  { background: #ecfeff; border-bottom-color: #a5f3fc; }
.fdd-section-o .fdd-section-tag  { background: #0891b2; color: #fff; }
.fdd-section-o .fdd-section-title{ color: #0e7490; }
.fdd-section-a .fdd-section-hdr  { background: #fffbeb; border-bottom-color: #fde68a; }
.fdd-section-a .fdd-section-tag  { background: #d97706; color: #fff; }
.fdd-section-a .fdd-section-title{ color: #92400e; }
.fdd-section-p .fdd-section-hdr  { background: #f0fdf4; border-bottom-color: #bbf7d0; }
.fdd-section-p .fdd-section-tag  { background: #16a34a; color: #fff; }
.fdd-section-p .fdd-section-title{ color: #15803d; }
.fdd-section-pulang .fdd-section-hdr  { background: #faf5ff; border-bottom-color: #e9d5ff; }
.fdd-section-pulang .fdd-section-tag  { background: #9333ea; color: #fff; font-size: 13px; }
.fdd-section-pulang .fdd-section-title{ color: #6b21a8; }

#fdd-wrap .fdd-label {
  display: block; font-size: 12px; font-weight: 700;
  color: #374151; margin-bottom: 5px; margin-top: 0;
}
#fdd-wrap .fdd-hint {
  display: block; font-size: 11px; color: #9ca3af;
  font-weight: 400; margin-top: 1px;
}
#fdd-wrap .fdd-required { color: #ef4444; margin-left: 2px; }

/* ── Riwayat radio group ── */
.fdd-riwayat-group {
  border-top: 1px solid #f1f5f9;
  padding-top: 10px;
}
.fdd-radio-row {
  display: flex;
  gap: 16px;
  margin-top: 4px;
}
.fdd-radio-opt {
  display: flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  font-size: 12.5px;
  font-weight: 500;
  color: #374151;
  margin: 0;
  padding: 5px 12px;
  border-radius: 6px;
  border: 1.5px solid #e2e8f0;
  background: #f8fafc;
  transition: border-color .15s, background .15s;
}
.fdd-radio-opt:has(input:checked) {
  border-color: #0ea5e9;
  background: #f0f9ff;
  color: #0369a1;
}
.fdd-radio-opt input[type="radio"] {
  accent-color: #0ea5e9;
  width: 14px;
  height: 14px;
  margin: 0;
  cursor: pointer;
}

.fdd-vitals {
  display: grid; grid-template-columns: repeat(5,1fr);
  gap: 8px; margin-bottom: 14px;
}
@media(max-width:900px){ .fdd-vitals{ grid-template-columns:repeat(3,1fr); } }
@media(max-width:600px){ .fdd-vitals{ grid-template-columns:repeat(2,1fr); } }
.fdd-vital-card {
  background: #f8fafc; border: 1.5px solid #e2e8f0;
  border-radius: 9px; padding: 9px 11px;
  display: flex; flex-direction: column; gap: 3px;
  transition: border-color .15s, background .15s;
}
.fdd-vital-card:focus-within { border-color: #0891b2; background: #ecfeff; }
.fdd-vital-label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; line-height: 1.4; }
.fdd-vital-unit  { font-size: 10px; color: #94a3b8; font-weight: 400; }
#fdd-wrap .fdd-vital-input {
  border: none !important; background: transparent !important;
  padding: 2px 0 !important; font-size: 16px !important;
  font-weight: 700 !important; color: #0e7490 !important;
  width: 100% !important; outline: none !important;
  box-shadow: none !important; height: auto !important;
  line-height: 1.3 !important;
}

.fdd-lokalis-toggle {
  display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
  margin: 14px 0 10px; padding: 10px 12px;
  background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 8px;
}
.fdd-lokalis-toggle label { margin: 0; cursor: pointer; font-size: 13px; }

#pl_diagnosa_sekunder_hidden_txt {
  padding: 6px 8px; border: 1.5px solid #e2e8f0;
  border-radius: 8px; min-height: 34px;
  margin-top: 6px; line-height: 26px; background: #f8fafc;
}

.fdd-footer {
  display: flex; align-items: center; justify-content: flex-end;
  padding: 6px 0 16px; gap: 10px;
}
.fdd-btn-save {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 28px;
  background: linear-gradient(135deg, #1d4ed8, #0ea5e9);
  color: #fff !important; border: none; border-radius: 9px;
  font-size: 14px; font-weight: 700; cursor: pointer;
  font-family: inherit; box-shadow: 0 3px 12px rgba(29,78,216,.3);
  transition: all .18s; text-decoration: none;
}
.fdd-btn-save:hover {
  background: linear-gradient(135deg,#1e40af,#0284c7);
  box-shadow: 0 4px 16px rgba(29,78,216,.4);
  transform: translateY(-1px); color: #fff !important;
}

/* ── Pemeriksaan Penunjang — .fdd-pm-* ────────────────────────── */
.fdd-pm-section {
  background: #f0fdfa; border: 1.5px solid #99f6e4; border-radius: 10px;
  padding: 14px 16px;
}
.fdd-pm-question {
  display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
}
.fdd-pm-label {
  font-size: 13px; font-weight: 700; color: #0f766e;
  display: flex; align-items: center; gap: 6px;
}
.fdd-pm-toggle {
  display: flex; gap: 6px;
}
.fdd-pm-radio {
  display: flex; align-items: center; gap: 5px;
  padding: 5px 14px; border-radius: 7px;
  border: 1.5px solid #e2e8f0; background: #fff;
  font-size: 12.5px; font-weight: 600; color: #64748b;
  cursor: pointer; margin: 0; transition: all .15s;
}
.fdd-pm-radio:has(input:checked) {
  border-color: #0d9488; background: #ccfbf1; color: #0f766e;
}
.fdd-pm-radio input[type="radio"] {
  accent-color: #0d9488; width: 14px; height: 14px; margin: 0; cursor: pointer;
}
.fdd-pm-radio-text { white-space: nowrap; }

.fdd-pm-list-hdr {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 8px;
  font-size: 12px; font-weight: 700; color: #334155;
}
.fdd-pm-list-hdr i { color: #0891b2; margin-right: 4px; }
.fdd-pm-btn-add {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 12px; border: none; border-radius: 7px;
  background: linear-gradient(135deg, #0d9488, #0891b2);
  color: #fff; font-size: 12px; font-weight: 600;
  cursor: pointer; transition: all .15s;
}
.fdd-pm-btn-add:hover { background: linear-gradient(135deg, #0f766e, #0e7490); transform: translateY(-1px); }

/* ── PM Order Cards ── */
.fdd-pm-card {
  border: 1.5px solid #e2e8f0; border-radius: 10px;
  background: #fff; margin-bottom: 10px; overflow: hidden;
  transition: border-color .15s;
}
.fdd-pm-card:hover { border-color: #99f6e4; }
.fdd-pm-card-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 14px; background: #f8fafc;
  border-bottom: 1px solid #f1f5f9; gap: 8px; flex-wrap: wrap;
}
.fdd-pm-card-info {
  display: flex; align-items: center; gap: 10px; min-width: 0;
}
.fdd-pm-card-num {
  width: 26px; height: 26px; border-radius: 7px;
  background: linear-gradient(135deg, #0d9488, #0891b2);
  color: #fff; font-size: 12px; font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.fdd-pm-card-unit {
  font-size: 13px; font-weight: 700; color: #0f766e;
}
.fdd-pm-card-unit i { margin-right: 4px; }
.fdd-pm-card-tujuan {
  font-size: 11px; color: #64748b; margin-top: 1px;
}
.fdd-pm-card-actions {
  display: flex; align-items: center; gap: 6px; flex-shrink: 0;
}
.fdd-pm-card-detail {
  padding: 10px 14px;
}
.fdd-pm-card-footer {
  display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
  padding: 8px 14px; background: #f8fafc;
  border-top: 1px solid #f1f5f9;
}
.fdd-pm-detail-label {
  font-size: 11px; font-weight: 700; color: #475569;
  margin-bottom: 6px; text-transform: uppercase; letter-spacing: .3px;
}
.fdd-pm-detail-label i { color: #0891b2; margin-right: 3px; }
.fdd-pm-chips {
  display: flex; flex-wrap: wrap; gap: 5px;
}
.fdd-pm-chip {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 10px; border-radius: 6px;
  background: #ecfdf5; border: 1px solid #a7f3d0;
  color: #065f46; font-size: 11px; font-weight: 600;
  white-space: nowrap;
}
.fdd-pm-chip i { color: #10b981; font-size: 10px; }
.fdd-pm-detail-loading {
  font-size: 11px; color: #94a3b8; font-style: italic;
  padding: 4px 0;
}
.fdd-pm-detail-loading i { margin-right: 4px; }
.fdd-pm-detail-empty {
  font-size: 11px; color: #94a3b8; font-style: italic;
  padding: 4px 0;
}
.fdd-pm-detail-empty i { margin-right: 4px; color: #cbd5e1; }
.fdd-pm-empty-state {
  text-align: center; color: #94a3b8; font-style: italic;
  padding: 20px 10px; font-size: 13px;
}
.fdd-pm-empty-state i { font-size: 20px; display: block; margin-bottom: 6px; color: #cbd5e1; }
.fdd-pm-btn-del {
  display: inline-flex; align-items: center; justify-content: center;
  width: 26px; height: 26px; border: none; border-radius: 6px;
  background: #fee2e2; color: #dc2626; font-size: 12px;
  cursor: pointer; transition: all .15s;
}
.fdd-pm-btn-del:hover { background: #fecaca; transform: scale(1.1); }
.fdd-pm-badge-biasa {
  display: inline-block; padding: 2px 8px; border-radius: 5px;
  background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 600;
}
.fdd-pm-badge-cito {
  display: inline-block; padding: 2px 8px; border-radius: 5px;
  background: #fee2e2; color: #dc2626; font-size: 11px; font-weight: 700;
}

/* ── Modal Penunjang Medis ── */
#modal-penunjang-medis .modal-header {
  background: linear-gradient(135deg, #0d9488, #0891b2);
  color: #fff; border-radius: 8px 8px 0 0; padding: 14px 18px;
  border-bottom: none;
}
#modal-penunjang-medis .modal-header .modal-title {
  font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px;
}
#modal-penunjang-medis .modal-header .close {
  color: #fff; opacity: .8; text-shadow: none; font-size: 22px;
}
#modal-penunjang-medis .modal-header .close:hover { opacity: 1; }
#modal-penunjang-medis .modal-content {
  border: none; border-radius: 10px;
  box-shadow: 0 10px 40px rgba(0,0,0,.2);
}
#modal-penunjang-medis .modal-body { padding: 18px 20px; }
#modal-penunjang-medis .modal-footer {
  border-top: 1px solid #f1f5f9; padding: 12px 20px;
}
.fdd-pm-form-group { margin-bottom: 14px; }
.fdd-pm-form-label {
  display: block; font-size: 12px; font-weight: 700;
  color: #374151; margin-bottom: 5px;
}
.fdd-pm-form-label .fdd-required { color: #ef4444; margin-left: 2px; }
.fdd-pm-form-select {
  width: 100%; padding: 0px 0px; border: 1.5px solid #e2e8f0;
  border-radius: 8px; font-size: 13px; color: #334155;
  background: #fff; transition: border-color .15s;
}
.fdd-pm-form-select:focus { border-color: #0d9488; outline: none; box-shadow: 0 0 0 3px rgba(13,148,136,.1); }
.fdd-pm-form-textarea {
  width: 100%; padding: 8px 10px; border: 1.5px solid #e2e8f0;
  border-radius: 8px; font-size: 13px; color: #334155;
  background: #fff; resize: vertical; min-height: 60px; transition: border-color .15s;
}
.fdd-pm-form-textarea:focus { border-color: #0d9488; outline: none; box-shadow: 0 0 0 3px rgba(13,148,136,.1); }
.fdd-pm-jenis-row {
  display: flex; gap: 8px; margin-top: 4px;
}
.fdd-pm-jenis-opt {
  display: flex; align-items: center; gap: 5px;
  padding: 6px 14px; border-radius: 7px;
  border: 1.5px solid #e2e8f0; background: #f8fafc;
  font-size: 12.5px; font-weight: 600; color: #64748b;
  cursor: pointer; margin: 0; transition: all .15s;
}
.fdd-pm-jenis-opt:has(input:checked) {
  border-color: #0d9488; background: #ccfbf1; color: #0f766e;
}
.fdd-pm-jenis-opt input[type="radio"] {
  accent-color: #0d9488; width: 14px; height: 14px; margin: 0; cursor: pointer;
}
.fdd-pm-btn-submit {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 20px; border: none; border-radius: 8px;
  background: linear-gradient(135deg, #0d9488, #0891b2);
  color: #fff; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all .15s;
}
.fdd-pm-btn-submit:hover { background: linear-gradient(135deg, #0f766e, #0e7490); transform: translateY(-1px); }
.fdd-pm-btn-cancel {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border: 1.5px solid #e2e8f0; border-radius: 8px;
  background: #fff; color: #64748b; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: all .15s;
}
.fdd-pm-btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; }

/* ── Pengantar PM buttons in table ── */
.fdd-pm-btn-pengantar {
  display: inline-block; padding: 4px 10px; border-radius: 5px;
  background: linear-gradient(135deg, #1d4ed8, #2563eb);
  color: #fff !important; font-size: 11px; font-weight: 600;
  text-decoration: none !important; white-space: nowrap;
  transition: all .15s; margin-bottom: 4px;
}
.fdd-pm-btn-pengantar:hover {
  background: linear-gradient(135deg, #1e40af, #1d4ed8);
  transform: translateY(-1px); color: #fff !important;
}
.fdd-pm-btn-cetak {
  display: inline-block; padding: 4px 10px; border-radius: 5px;
  background: linear-gradient(135deg, #059669, #10b981);
  color: #fff !important; font-size: 11px; font-weight: 600;
  text-decoration: none !important; white-space: nowrap;
  transition: all .15s;
}
.fdd-pm-btn-cetak:hover {
  background: linear-gradient(135deg, #047857, #059669);
  transform: translateY(-1px); color: #fff !important;
}

/* ── Modal Pengantar PM ── */
.fdd-pengantar-modal-hdr {
  background: linear-gradient(135deg, #1d4ed8, #0891b2);
  color: #fff; border-radius: 8px 8px 0 0; padding: 14px 18px;
  border-bottom: none;
}
.fdd-pengantar-modal-hdr .modal-title {
  font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px;
}
.fdd-pengantar-modal-hdr .close {
  color: #fff; opacity: .8; text-shadow: none; font-size: 22px;
}
.fdd-pengantar-modal-hdr .close:hover { opacity: 1; }
#modal-pengantar-pm .modal-content {
  border: none; border-radius: 10px;
  box-shadow: 0 10px 40px rgba(0,0,0,.2);
}
</style>

<!-- input type hidden -->
<input type="hidden" name="jenis_form" id="jenis_form" value="<?php echo isset($form_rm->jenis_form)?$form_rm->jenis_form:''?>">
<input type="hidden" name="cppt_id" id="cppt_id" value="<?php echo isset($form_rm->id)?$form_rm->id:''?>">

<audio id="container" autoplay=""></audio>

<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;margin-bottom:8px;">

  <!-- Timer display -->
  <div style="display:flex;align-items:center;gap:6px;">
    <span style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Waktu Pelayanan</span>
    <div style="display:flex;align-items:center;gap:2px;background:#fff;border:1.5px solid #cbd5e1;border-radius:8px;padding:4px 12px;">
      <span id="sw-minutes" style="font-size:22px;font-weight:900;color:#0f172a;font-family:monospace;min-width:28px;text-align:center;">00</span>
      <span style="font-size:20px;font-weight:700;color:#94a3b8;margin:0 1px;">:</span>
      <span id="sw-seconds" style="font-size:22px;font-weight:900;color:#0f172a;font-family:monospace;min-width:28px;text-align:center;">00</span>
    </div>
    <span id="sw-status" style="font-size:11px;font-weight:600;color:#64748b;">Siap</span>
  </div>

  <!-- Timer controls -->
  <div style="display:flex;align-items:center;gap:6px;margin-left:auto;">
    <button type="button" id="sw-btn-start" onclick="startStopWatch()" style="display:flex;align-items:center;gap:5px;padding:6px 12px;border:none;border-radius:7px;background:#16a34a;color:#fff;font-size:12px;font-weight:600;cursor:pointer;">
      <i class="fa fa-play"></i> Mulai
    </button>
    <button type="button" id="sw-btn-pause" onclick="pauseStopWatch()" style="display:none;align-items:center;gap:5px;padding:6px 12px;border:none;border-radius:7px;background:#d97706;color:#fff;font-size:12px;font-weight:600;cursor:pointer;">
      <i class="fa fa-pause"></i> Jeda
    </button>
    <button type="button" onclick="resetStopWatch()" style="display:flex;align-items:center;gap:5px;padding:6px 10px;border:1.5px solid #e2e8f0;border-radius:7px;background:#fff;color:#64748b;font-size:12px;font-weight:600;cursor:pointer;" title="Reset timer">
      <i class="fa fa-refresh"></i>
    </button>
    <button type="button" onclick="speak()" id="callPatientPoli" style="display:flex;align-items:center;gap:5px;padding:6px 12px;border:none;border-radius:7px;background:#0369a1;color:#fff;font-size:12px;font-weight:600;cursor:pointer;">
      <i class="fa fa-bullhorn"></i> Panggil
    </button>
  </div>

</div>

<div class="hr dotted"></div>

<div class="widget-box transparent ui-sortable-handle collapsed" id="widget-box-12" style="display: block">
    <div class="widget-header">
        <span style="font-style: italic; font-size: 14px" class="widget-title lighter">Pemanggilan Pasien</h4>
        <div class="widget-toolbar no-border">
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
        </div>
    </div>

    <div class="widget-body" style="display: none;">
        <form style="padding: 10px">
            <label>Text to speech</label>
            <input type="text" class="txt" style="width: 100%" value="<?php echo $txt_call_patient?>">
            <div class="col-md-6 no-padding">
                <label for="rate">Rate</label><input type="range" min="0.5" max="2" value="1" step="0.1" id="rate">
                <div class="rate-value">1</div>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-6">
                <label for="pitch">Pitch</label><input type="range" min="0" max="2" value="1" step="0.1" id="pitch">
                <div class="pitch-value">1</div>
                <div class="clearfix"></div>
            </div>
            <label>Language</label><br>
            <select id="tts_language" style="width: 100%"></select>
        </form>
    </div>
</div>

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


<div id="fdd-wrap">

  <!-- Patient + Doctor Header -->
  <div class="fdd-patient-hdr">
    <span class="fdd-soap-badge">S O A P</span>
    <div>
      <div class="fdd-patient-name"><?php echo isset($value->no_mr)?$value->no_mr.' &mdash; ':''?><?php echo isset($value->nama_pasien)?$value->nama_pasien:''?></div>
      <div class="fdd-patient-meta">
        <i class="fa fa-user-md"></i> <?php echo isset($value->nama_pegawai)?$value->nama_pegawai:''?>
        &nbsp;&bull;&nbsp;
        <i class="fa fa-calendar"></i> <?php echo isset($value->tgl_keluar_poli)?$this->tanggal->formatDateTimeFormDmy($value->tgl_keluar_poli) : $this->tanggal->formatDateTimeFormDmy($value->tgl_jam_poli)?>
      </div>
    </div>
  </div>

  
  <!-- S — Subjective -->
  <div class="fdd-section fdd-section-s">
    <div class="fdd-section-hdr">
      <span class="fdd-section-tag">S</span>
      <div>
        <div class="fdd-section-title">Subjective</div>
      </div>
      <span class="fdd-section-sub">Keluhan &amp; anamnesa pasien</span>
    </div>
    <div class="fdd-section-body">
      <label class="fdd-label">Anamnesa / Keluhan Pasien <span class="fdd-required">*</span>
        <span class="fdd-hint">Masukan anamnesa minimal 8 karakter</span>
      </label>
      <textarea class="form-control" name="pl_anamnesa" style="height:100px!important" id="pl_anamnesa"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?></textarea>
      <input type="hidden" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">

      <!-- Riwayat Penyakit Dahulu -->
      <div class="fdd-riwayat-group">
        <label class="fdd-label" style="margin-top:12px;">Riwayat Penyakit Dahulu</label>
        <div class="fdd-radio-row">
          <label class="fdd-radio-opt">
            <input type="radio" name="riwayat_penyakit_dahulu" value="tidak" <?php echo (!isset($riwayat->riwayat_penyakit_dahulu) || $riwayat->riwayat_penyakit_dahulu == '' || $riwayat->riwayat_penyakit_dahulu == 'tidak') ? 'checked' : ''; ?> onchange="toggleRiwayat('riwayat_penyakit_dahulu_txt', this.value)">
            <span>Tidak Ada</span>
          </label>
          <label class="fdd-radio-opt">
            <input type="radio" name="riwayat_penyakit_dahulu" value="ada" <?php echo (isset($riwayat->riwayat_penyakit_dahulu) && $riwayat->riwayat_penyakit_dahulu == 'ada') ? 'checked' : ''; ?> onchange="toggleRiwayat('riwayat_penyakit_dahulu_txt', this.value)">
            <span>Ada</span>
          </label>
        </div>
        <div id="riwayat_penyakit_dahulu_txt" style="<?php echo (isset($riwayat->riwayat_penyakit_dahulu) && $riwayat->riwayat_penyakit_dahulu == 'ada') ? '' : 'display:none;'; ?>margin-top:6px;">
          <textarea class="form-control" name="riwayat_penyakit_dahulu_ket" id="riwayat_penyakit_dahulu_ket" placeholder="Jelaskan riwayat penyakit dahulu..." style="height:70px!important"><?php echo isset($riwayat->riwayat_penyakit_dahulu_ket) ? $riwayat->riwayat_penyakit_dahulu_ket : ''; ?></textarea>
        </div>
      </div>

      <!-- Riwayat Operasi Sebelumnya -->
      <div class="fdd-riwayat-group">
        <label class="fdd-label" style="margin-top:12px;">Riwayat Operasi Sebelumnya</label>
        <div class="fdd-radio-row">
          <label class="fdd-radio-opt">
            <input type="radio" name="riwayat_operasi" value="tidak" <?php echo (!isset($riwayat->riwayat_operasi) || $riwayat->riwayat_operasi == '' || $riwayat->riwayat_operasi == 'tidak') ? 'checked' : ''; ?> onchange="toggleRiwayat('riwayat_operasi_txt', this.value)">
            <span>Tidak Ada</span>
          </label>
          <label class="fdd-radio-opt">
            <input type="radio" name="riwayat_operasi" value="ada" <?php echo (isset($riwayat->riwayat_operasi) && $riwayat->riwayat_operasi == 'ada') ? 'checked' : ''; ?> onchange="toggleRiwayat('riwayat_operasi_txt', this.value)">
            <span>Ada</span>
          </label>
        </div>
        <div id="riwayat_operasi_txt" style="<?php echo (isset($riwayat->riwayat_operasi) && $riwayat->riwayat_operasi == 'ada') ? '' : 'display:none;'; ?>margin-top:6px;">
          <textarea class="form-control" name="riwayat_operasi_ket" id="riwayat_operasi_ket" placeholder="Jelaskan riwayat operasi sebelumnya..." style="height:70px!important"><?php echo isset($riwayat->riwayat_operasi_ket) ? $riwayat->riwayat_operasi_ket : ''; ?></textarea>
        </div>
      </div>

      <!-- Riwayat Alergi -->
      <div class="fdd-riwayat-group">
        <label class="fdd-label" style="margin-top:12px;">Riwayat Alergi</label>
        <div class="fdd-radio-row">
          <label class="fdd-radio-opt">
            <input type="radio" name="riwayat_alergi" value="tidak" <?php echo (!isset($riwayat->riwayat_alergi) || $riwayat->riwayat_alergi == '' || $riwayat->riwayat_alergi == 'tidak') ? 'checked' : ''; ?> onchange="toggleRiwayat('riwayat_alergi_txt', this.value)">
            <span>Tidak Ada</span>
          </label>
          <label class="fdd-radio-opt">
            <input type="radio" name="riwayat_alergi" value="ada" <?php echo (isset($riwayat->riwayat_alergi) && $riwayat->riwayat_alergi == 'ada') ? 'checked' : ''; ?> onchange="toggleRiwayat('riwayat_alergi_txt', this.value)">
            <span>Ada</span>
          </label>
        </div>
        <div id="riwayat_alergi_txt" style="<?php echo (isset($riwayat->riwayat_alergi) && $riwayat->riwayat_alergi == 'ada') ? '' : 'display:none;'; ?>margin-top:6px;">
          <textarea class="form-control" name="riwayat_alergi_ket" id="riwayat_alergi_ket" placeholder="Jelaskan jenis alergi (obat, makanan, dll)..." style="height:70px!important"><?php echo isset($riwayat->riwayat_alergi_ket) ? $riwayat->riwayat_alergi_ket : ''; ?></textarea>
        </div>
      </div>

    </div>
  </div>

  <!-- O — Objective -->
  <div class="fdd-section fdd-section-o">
    <div class="fdd-section-hdr">
      <span class="fdd-section-tag">O</span>
      <div>
        <div class="fdd-section-title">Objective</div>
      </div>
      <span class="fdd-section-sub">Pemeriksaan fisik &amp; tanda vital</span>
    </div>
    <div class="fdd-section-body">

      <label class="fdd-label">Tanda Vital (Vital Sign)
        <span class="fdd-hint">Masukan tanda-tanda vital pasien</span>
      </label>
      <div class="fdd-vitals">
        <div class="fdd-vital-card">
          <span class="fdd-vital-label">Tinggi Badan <span class="fdd-vital-unit">cm</span></span>
          <input type="text" class="fdd-vital-input" name="pl_dr_tb" placeholder="—" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
        </div>
        <div class="fdd-vital-card">
          <span class="fdd-vital-label">Berat Badan <span class="fdd-vital-unit">kg</span></span>
          <input type="text" class="fdd-vital-input" name="pl_dr_bb" placeholder="—" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
        </div>
        <div class="fdd-vital-card">
          <span class="fdd-vital-label">Tekanan Darah <span class="fdd-vital-unit">mmHg</span></span>
          <input type="text" class="fdd-vital-input" name="pl_dr_td" placeholder="—" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
        </div>
        <div class="fdd-vital-card">
          <span class="fdd-vital-label">Nadi <span class="fdd-vital-unit">bpm</span></span>
          <input type="text" class="fdd-vital-input" name="pl_dr_nadi" placeholder="—" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
        </div>
        <div class="fdd-vital-card">
          <span class="fdd-vital-label">Suhu Tubuh <span class="fdd-vital-unit">&deg;C</span></span>
          <input type="text" class="fdd-vital-input" name="pl_dr_suhu" placeholder="—" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
        </div>
      </div>

      <label class="fdd-label">Pemeriksaan Fisik
        <span class="fdd-hint">Mohon dijelaskan kondisi fisik pasien (Keadaan umum, Kesadaran dan Status Generalis) </span>
      </label>
      <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height:100px!important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?></textarea>
      <input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>">

      <div class="fdd-lokalis-toggle">
        <label>
          <input type="checkbox" class="ace" name="checklist_status_lokaslis" id="checklist_status_lokaslis" <?php echo(isset($riwayat->anatomi_tagging) && $riwayat->anatomi_tagging != null)?'checked':''?>>
          <span class="lbl"> <i class="fa fa-map-marker" style="color:#0891b2"></i> <b>Status Lokalis</b></span>
        </label>
        <span style="font-size:11px;color:#94a3b8">Tagging status lokalis pada gambar anatomi tubuh pasien</span>
      </div>

      <!-- status lokalis -->
      <div id="form_status_lokalis" <?php echo(isset($riwayat->anatomi_tagging) && $riwayat->anatomi_tagging != null)?'':'style="display:none;"'?>>
        <div class="form-group">
          <label class="control-label col-sm-2">Anatomi</label>
          <div class="col-md-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anatomi', 'is_active' => 'Y')), isset($riwayat->anatomi_img)?$riwayat->anatomi_img:0 , 'anatomi', 'anatomi', 'form-control', 'onchange="changeAnatomiImage(this)"', '');?>
          </div>
        </div>
        <br>
        <div class="col-md-12">
          <center><span style="font-weight:bold;font-size:18px">VISUALISASI STATUS LOKALIS</span></center>
          <div style="display:flex;justify-content:center;align-items:flex-start;">
            <div id="anatomi-tag-list-left" style="min-width:180px;max-width:220px;position:relative;"></div>
            <div id="anatomi-tagging-container" style="position:relative;display:inline-block;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.08);border-radius:8px;padding:8px;">
              <div id="tag-modal-overlay" style="display:none;"></div>
              <div id="tag-input-modal" style="display:none;">
                <div style="padding:20px 18px 16px 18px;display:flex;flex-direction:column;align-items:center;gap:12px;">
                  <input type="text" id="tag-label-input" class="form-control" placeholder="Label lokasi..." style="width:100%;max-width:320px;font-size:15px;">
                  <div style="display:flex;gap:10px;width:100%;justify-content:flex-end;">
                    <button type="button" id="tag-save-btn" class="btn btn-sm btn-primary" style="min-width:80px;">Simpan</button>
                    <button type="button" id="tag-cancel-btn" class="btn btn-sm btn-default" style="min-width:80px;">Batal</button>
                  </div>
                </div>
              </div>
              <div style="margin:10px 0 0 0;text-align:center;">
                <label style="font-weight:500;">Mode:</label>
                <select id="draw-mode" style="width:120px;margin:0 8px;">
                  <option value="#">Pilih mode</option>
                  <option value="rect">Rectangle</option>
                  <option value="freehand">Freehand</option>
                </select>
                <input type="color" id="draw-color" value="#ff0000" style="margin-left:8px;vertical-align:middle;">
                <span id="draw-instruction" style="margin-left:10px;color:#888;font-size:12px;"></span>
                <button type="button" id="draw-cancel-btn" class="btn btn-xs btn-default" style="display:none;margin-left:8px;">Batal Gambar</button>
              </div>
              <?php $img_anatomi = isset($riwayat->anatomi_img)?'anatomi_'.$riwayat->anatomi_img.'.png':'anatomi_0.png'; ?>
              <div style="position:relative;width:500px;height:auto;">
                <img src="<?php echo base_url('assets/img-tagging/images/'.$img_anatomi.'')?>" id="anatomi-img" style="width:500px;height:auto;display:block;border-radius:8px;">
                <svg id="anatomi-svg-lines" style="position:absolute;left:0;top:0;width:100%;height:100%;pointer-events:none;"></svg>
                <svg id="anatomi-svg-areas" style="position:absolute;left:0;top:0;width:100%;height:100%;pointer-events:none;"></svg>
                <canvas id="anatomi-draw-canvas" width="500" height="650" style="position:absolute;left:0;top:0;width:500px;height:650px;pointer-events:auto;z-index:20;background:transparent;border-radius:8px;"></canvas>
              </div>
            </div>
            <div id="anatomi-tag-list-right" style="min-width:180px;max-width:220px;position:relative;"></div>
          </div>
          <input type="hidden" name="anatomi_tagging" id="anatomi_tagging" value="">
          <textarea name="anatomi_tagging_exist" id="anatomi_tagging_exist" style="width:100%!important;display:none;"><?php echo $riwayat->anatomi_tagging?></textarea>
        </div>
      </div>

    </div>
  </div>

  <!-- A — Assessment -->
  <div class="fdd-section fdd-section-a">
    <div class="fdd-section-hdr">
      <span class="fdd-section-tag">A</span>
      <div>
        <div class="fdd-section-title">Assessment</div>
      </div>
      <span class="fdd-section-sub">Diagnosa &amp; prosedur tindakan</span>
    </div>
    <div class="fdd-section-body">

      <label class="fdd-label">Diagnosa Primer (ICD-10) <span class="fdd-required">*</span>
        <span class="fdd-hint">Wajib mengisi menggunakan kode ICD-10</span>
      </label>
      <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
      <input type="hidden" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">

      <label class="fdd-label" style="margin-top:12px">Diagnosa Sekunder
        <span class="fdd-hint">Klik <b>Enter</b> untuk menambahkan, dapat diisi lebih dari satu</span>
      </label>
      <input type="text" class="form-control" name="pl_diagnosa_sekunder" id="pl_diagnosa_sekunder" placeholder="Masukan keyword ICD 10" value="">
      <div id="pl_diagnosa_sekunder_hidden_txt">
        <?php
            $arr_text = isset($riwayat->diagnosa_sekunder) ? explode('|',$riwayat->diagnosa_sekunder) : array();
            $no_ds = 1;
            foreach ($arr_text as $k => $v) {
                $len = strlen(trim($v));
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
      <input type="hidden" name="konten_diagnosa_sekunder" id="konten_diagnosa_sekunder" value="<?php echo isset($riwayat->diagnosa_sekunder)?$riwayat->diagnosa_sekunder:''?>">

      <label class="fdd-label" style="margin-top:12px">Prosedur / Tindakan (ICD-9) <span class="fdd-required">*</span>
        <span class="fdd-hint">Wajib mengisi menggunakan kode ICD-9</span>
      </label>
      <input type="text" class="form-control" name="pl_procedure" id="pl_procedure" placeholder="Masukan keyword ICD 9" value="<?php echo isset($riwayat->text_icd9)?$riwayat->text_icd9:' Other consultation'?>">
      <input type="hidden" name="pl_procedure_hidden" id="pl_procedure_hidden" value="<?php echo isset($riwayat->kode_icd9)?$riwayat->kode_icd9:'89.08'?>">

      <label class="fdd-label" style="margin-top:12px">Keterangan / Catatan Assesmen Lainnya
        <span class="fdd-hint">Catatan klinis tambahan selain diagnosa dan prosedur di atas</span>
      </label>
      <textarea name="pl_catatan_assesmen" id="pl_catatan_assesmen" class="form-control" rows="3" style="height:80px!important" placeholder="Tulis catatan assesmen tambahan jika diperlukan..."><?php echo isset($riwayat->catatan_assesmen) ? htmlspecialchars($riwayat->catatan_assesmen) : '' ?></textarea>

    </div>
  </div>

  <!-- P — Planning -->
  <div class="fdd-section fdd-section-p">
    <div class="fdd-section-hdr">
      <span class="fdd-section-tag">P</span>
      <div>
        <div class="fdd-section-title">Planning</div>
      </div>
      <span class="fdd-section-sub">Rencana tatalaksana &amp; kontrol</span>
    </div>
    <div class="fdd-section-body">

      <!-- Pemeriksaan Penunjang -->
      <div class="fdd-pm-section" style="margin-top:14px">
        <div class="fdd-pm-question">
          <div class="fdd-pm-label">
            <i class="fa fa-flask" style="color:#0891b2"></i> Apakah ada Pemeriksaan Penunjang?
          </div>
          <div class="fdd-pm-toggle">
            <label class="fdd-pm-radio">
              <input type="radio" name="ada_penunjang" value="N" checked>
              <span class="fdd-pm-radio-text">Tidak</span>
            </label>
            <label class="fdd-pm-radio">
              <input type="radio" name="ada_penunjang" value="Y" onclick="$('#modal-penunjang-medis').modal('show')">
              <span class="fdd-pm-radio-text">Ya</span>
            </label>
          </div>
        </div>
        <div id="fdd_pm_list" style="display:none;margin-top:10px">
          <div class="fdd-pm-list-hdr">
            <span><i class="fa fa-list"></i> Daftar Order Penunjang Medis</span>
            <button type="button" class="fdd-pm-btn-add" onclick="$('#modal-penunjang-medis').modal('show')">
              <i class="fa fa-plus"></i> Tambah
            </button>
          </div>
          <div id="fdd_pm_cards"></div>
        </div>
      </div>

      <?php
        $_resep_hdr = $this->db->where('no_kunjungan', $value->no_kunjungan)
            ->where('source', 'SOAP')
            ->order_by('kode_pesan_resep','DESC')->limit(1)->get('fr_tc_pesan_resep')->row();
        $_has_resep = $_resep_hdr ? true : false;
      ?>
      <!-- Resep Dokter -->
      <div class="fdd-pm-section" style="margin-top:14px">

        <!-- Resep Iter Alert -->
        <?php if(isset($riwayat->resep_iter) && $riwayat->resep_iter == 'Y') :?>
        <div class="fdd-iter-alert">
          <div class="fdd-iter-icon">
            <i class="fa fa-medkit"></i>
          </div>
          <div class="fdd-iter-body">
            <div class="fdd-iter-title">Pasien Memiliki Resep Iter</div>
            <div class="fdd-iter-desc">Resep iterasi sebanyak <strong><?php echo isset($riwayat->jumlah_iter) ? $riwayat->jumlah_iter : '-'?>x</strong> pengulangan telah dicatat oleh perawat pada saat input TTV.</div>
          </div>
        </div>
        <?php endif;?>
        
        <div class="fdd-pm-question">
          <div class="fdd-pm-label">
            <i class="fa fa-medkit" style="color:#0891b2"></i> Apakah ada Resep Dokter?
          </div>
          <div class="fdd-pm-toggle">
            <label class="fdd-pm-radio">
              <input type="radio" name="ada_resep" value="N" onclick="$('#fdd_resep_list').hide()"
                <?php echo $_has_resep ? '' : 'checked'?>>
              <span class="fdd-pm-radio-text">Tidak</span>
            </label>
            <label class="fdd-pm-radio">
              <input type="radio" name="ada_resep" value="Y" onclick="$('#fdd_resep_list').show();openModalResep()"
                <?php echo $_has_resep ? 'checked' : ''?>>
              <span class="fdd-pm-radio-text">Ya</span>
            </label>
          </div>
        </div>
        <div id="fdd_resep_list" style="<?php echo $_has_resep ? '' : 'display:none;'?>margin-top:10px">
          <?php $_resep_locked = ($_resep_hdr && !empty($_resep_hdr->lock_eresep) && $_resep_hdr->lock_eresep == 1); ?>
          <div class="fdd-pm-list-hdr">
            <span><i class="fa fa-medkit"></i> Resep Dokter &mdash; <span id="soap-resep-count">0 obat</span></span>
            <div style="display:inline-flex;align-items:center;gap:8px;">
              <?php if($_resep_locked): ?>
                <span style="display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;letter-spacing:.3px;box-shadow:0 1px 6px rgba(220,38,38,.3);">
                  <i class="fa fa-lock"></i> e-Resep Dikunci
                </span>
              <?php endif; ?>
              <button type="button" class="fdd-pm-btn-add"
                <?php echo $_resep_locked ? 'disabled style="opacity:.45;cursor:not-allowed;pointer-events:none;"' : 'onclick="openModalResep()"'?>>
                <i class="fa fa-<?php echo $_resep_locked ? 'lock' : 'pencil'?>"></i>
                <?php echo $_resep_locked ? 'Resep Dikunci' : 'Ubah / Tambah Resep'?>
              </button>
            </div>
          </div>
          <div id="soap-resep-items"></div>
          <div id="resep-selesai-wrap" style="margin-top:10px;display:none">
            <button type="button" class="btn-resep-selesai" id="btn-resep-selesai" onclick="prosesResepSelesai()" style="display:none">
              <i class="fa fa-check-circle"></i> Resep Selesai
            </button>
            <div class="resep-status-done" id="resep-status-done" style="display:none">
              <i class="fa fa-check-circle"></i> Resep sudah diproses
            </div>
          </div>
        </div>
      </div>
      <br>
      
      <label class="fdd-label">Rencana Asuhan / Anjuran Dokter
        <span class="fdd-hint">Mohon dijelaskan rencana asuhan pasien dan tindak lanjutnya</span>
      </label>
      <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height:200px!important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?></textarea>

      <div class="row" style="margin-top:12px">
        <div class="col-md-4">
          <label class="fdd-label">Tanggal Kontrol Kembali
            <span class="fdd-hint">Default BPJS: 31 hari ke depan</span>
          </label>
          <input type="text" class="date-picker form-control" data-date-format="yyyy-mm-dd" name="pl_tgl_kontrol_kembali" id="pl_tgl_kontrol_kembali" style="width:100%!important" placeholder="<?php echo date('Y-m-d')?>" value="<?php $next_date = date('Y-m-d', strtotime("+31 days")); echo isset($riwayat->tgl_kontrol_kembali)?$riwayat->tgl_kontrol_kembali:$next_date?>">
        </div>
        <div class="col-md-8">
          <label class="fdd-label">Catatan Kontrol</label>
          <textarea name="pl_catatan_kontrol" id="pl_catatan_kontrol" class="form-control" style="height:70px!important" placeholder="ex. Mohon membawa hasil LAB saat kontrol kembali"><?php echo isset($riwayat->catatan_kontrol_kembali)?$this->master->br2nl($riwayat->catatan_kontrol_kembali):''?></textarea>
        </div>
      </div>


  </div>

  <!-- Informasi Pasien Pulang -->
  <div class="fdd-section fdd-section-pulang">
    <div class="fdd-section-hdr">
      <span class="fdd-section-tag"><i class="fa fa-sign-out" style="font-size:13px"></i></span>
      <div>
        <div class="fdd-section-title">Informasi Pasien Pulang</div>
      </div>
    </div>
    <div class="fdd-section-body">
      <div class="row">
        <div class="col-md-6">
          <label class="fdd-label">Cara Keluar Pasien</label>
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'cara_keluar')), ($value->cara_keluar_pasien) ? $value->cara_keluar_pasien : 'Atas Persetujuan Dokter' , 'cara_keluar', 'cara_keluar', 'form-control', '', '') ?>
        </div>
        <div class="col-md-6">
          <label class="fdd-label">Pasca Pulang</label>
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), (isset($riwayat->pasca_pulang) && $riwayat->pasca_pulang) ? $riwayat->pasca_pulang : 'Dalam Masa Pengobatan' , 'pasca_pulang', 'pasca_pulang', 'form-control', '', '') ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer / Save -->
  <div class="fdd-footer">
    <?php if(isset($_GET['form']) && $_GET['form'] == 'billing_entry') : ?>
      <div class="alert alert-danger" style="margin:0;flex:1"><strong>Peringatan!</strong> Session anda bukan sebagai dokter, anda tidak dapat mengubah SOAP</div>
    <?php else: ?>
      <button type="submit" name="submit" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>" class="fdd-btn-save" id="btn_save_data">
        <i class="fa fa-save"></i> Simpan Data SOAP
      </button>
    <?php endif; ?>
  </div>

</div><!-- /#fdd-wrap -->

<!-- ================================================================
     Modal Pemeriksaan Penunjang Medis
     ================================================================ -->
<div class="modal fade" id="modal-penunjang-medis" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-flask"></i> Order Pemeriksaan Penunjang Medis</h4>
      </div>
      <div class="modal-body">
        <div class="fdd-pm-form-group">
          <label class="fdd-pm-form-label">Unit Tujuan Penunjang <span class="fdd-required">*</span></label>
          <?php echo $this->master->custom_selection(
            array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'validasi' => '0500')),
            '', 'pm_tujuan_modal', 'pm_tujuan_modal', 'fdd-pm-form-select', '', ''
          ) ?>
        </div>
        <div class="fdd-pm-form-group">
          <label class="fdd-pm-form-label">Jenis Layanan</label>
          <div class="fdd-pm-jenis-row">
            <label class="fdd-pm-jenis-opt">
              <input type="radio" name="jenis_layanan_pm_modal" value="0" checked>
              <span>Biasa</span>
            </label>
            <label class="fdd-pm-jenis-opt">
              <input type="radio" name="jenis_layanan_pm_modal" value="1">
              <span>Cito</span>
            </label>
          </div>
        </div>
        <div class="fdd-pm-form-group">
          <label class="fdd-pm-form-label">Keterangan</label>
          <textarea class="fdd-pm-form-textarea" id="keterangan_pm_modal" placeholder="Keterangan pemeriksaan (opsional)"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="fdd-pm-btn-cancel" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button type="button" class="fdd-pm-btn-submit" onclick="submitOrderPM()"><i class="fa fa-check"></i> Submit Order</button>
      </div>
    </div>
  </div>
</div>

<!-- ================================================================
     Modal Pengantar Penunjang Medis (Form Lab/Rad/Fisio)
     ================================================================ -->
<div class="modal fade" id="modal-pengantar-pm" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width:85%">
    <div class="modal-content">
      <div class="modal-header fdd-pengantar-modal-hdr">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal-pengantar-pm-title"><i class="fa fa-file-text-o"></i> Form Pengantar Penunjang Medis</h4>
      </div>
      <div class="modal-body" id="modal-pengantar-pm-body" style="max-height:75vh;overflow-y:auto;padding:16px 20px">
        <div style="text-align:center;padding:40px">
          <i class="fa fa-spinner fa-spin fa-2x" style="color:#0891b2"></i>
          <p style="margin-top:10px;color:#64748b;font-size:13px">Memuat form pengantar...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url()?>assets/js/custom/counter_poli.js"></script>

<!-- ================================================================
     AI ASSISTANT DOKTER — Floating Panel
     Voice-to-Text + Claude AI SOAP Structuring
     ================================================================ -->
<style>
/* ---- Scoped to #ai-assistant-wrap ---- */
#ai-assistant-wrap * { box-sizing: border-box; }

/* Floating toggle button */
#ai-toggle-btn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9998;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    background: linear-gradient(135deg, #7c3aed, #06b6d4);
    color: #fff;
    border: none;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(124,58,237,0.4);
    transition: all .2s ease;
}
#ai-toggle-btn:hover { background: linear-gradient(135deg,#6d28d9,#0891b2); transform: translateY(-2px); box-shadow: 0 6px 22px rgba(124,58,237,0.55); }
#ai-toggle-btn.ai-recording { animation: ai-pulse 1.4s infinite; }
@keyframes ai-pulse {
    0%,100% { box-shadow: 0 4px 18px rgba(239,68,68,.4); }
    50%      { box-shadow: 0 4px 28px rgba(239,68,68,.8); }
}

/* Panel */
#ai-panel {
    position: fixed;
    bottom: 88px;
    right: 28px;
    z-index: 9999;
    width: 340px;
    max-height: 88vh;
    overflow-y: auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 40px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.08);
    display: none;
    flex-direction: column;
    border: 1px solid #e2e8f0;
}
#ai-panel.ai-panel-open { display: flex; }

/* Panel header */
.ai-panel-hdr {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 13px 16px;
    background: linear-gradient(135deg, #7c3aed, #06b6d4);
    color: #fff;
    border-radius: 14px 14px 0 0;
    font-weight: 700;
    font-size: 14px;
}
.ai-panel-hdr .ai-panel-close {
    margin-left: auto;
    background: rgba(255,255,255,.18);
    border: none;
    color: #fff;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    font-size: 16px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ai-panel-hdr .ai-panel-close:hover { background: rgba(255,255,255,.3); }

/* Mode tabs */
#ai-panel .ai-mode-tabs {
    display: flex;
    background: #f1f5f9;
    border-bottom: 1px solid #dde3ed;
    padding: 6px 6px 0;
    gap: 4px;
}
#ai-panel .ai-tab {
    flex: 1;
    padding: 8px 6px;
    border: 1px solid transparent !important;
    border-bottom: none !important;
    border-radius: 7px 7px 0 0 !important;
    background: transparent !important;
    box-shadow: none !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #64748b !important;
    cursor: pointer;
    text-align: center !important;
    line-height: 1.4 !important;
    white-space: nowrap;
    outline: none !important;
    transition: background .15s, color .15s;
    margin-bottom: -1px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
#ai-panel .ai-tab:hover {
    background: #ede9fe !important;
    color: #7c3aed !important;
}
#ai-panel .ai-tab.active {
    background: #fff !important;
    color: #7c3aed !important;
    border-color: #dde3ed !important;
    border-bottom-color: #fff !important;
    position: relative;
    z-index: 1;
}
#ai-panel .ai-tab:focus { outline: none !important; box-shadow: none !important; }

/* Tab content */
.ai-tab-content { padding: 14px; }

/* Field selector */
.ai-field-selector { margin-bottom: 12px; }
.ai-field-selector label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .04em;
    display: block;
    margin-bottom: 5px;
}
#ai-panel .ai-field-selector select {
    width: 100% !important;
    padding: 8px 32px 8px 11px !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    font-size: 13px !important;
    color: #1e293b !important;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='7' viewBox='0 0 12 7'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2364748b' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 10px center !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    height: auto !important;
    line-height: 1.4 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,.04) !important;
    cursor: pointer;
    outline: none !important;
    transition: border-color .15s;
}
#ai-panel .ai-field-selector select:focus {
    border-color: #7c3aed !important;
    box-shadow: 0 0 0 3px rgba(124,58,237,.12) !important;
}

/* Transcript box */
.ai-transcript-box {
    min-height: 80px;
    max-height: 140px;
    overflow-y: auto;
    padding: 9px 11px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    background: #f8fafc;
    font-size: 13px;
    color: #1e293b;
    line-height: 1.55;
    font-family: inherit;
    white-space: pre-wrap;
    word-break: break-word;
    margin-bottom: 10px;
}
.ai-transcript-box:empty::before { content: attr(placeholder); color: #94a3b8; font-style: italic; }

/* Action buttons */
.ai-actions { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 2px; }
.ai-btn-primary  { padding:7px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600; display:flex;align-items:center;gap:5px; background:linear-gradient(135deg,#0a2d5a,#00669F); color:#fff; }
.ai-btn-primary:hover  { background:linear-gradient(135deg,#0d3872,#0080c0); }
.ai-btn-secondary{ padding:7px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600; display:flex;align-items:center;gap:5px; background:#64748b; color:#fff; }
.ai-btn-secondary:hover { background:#475569; }
.ai-btn-success  { padding:7px 13px; border-radius:7px; border:none; cursor:pointer; font-size:12px; font-weight:600; display:flex;align-items:center;gap:5px; background:#16a34a; color:#fff; }
.ai-btn-success:hover   { background:#15803d; }
.ai-btn-ghost    { padding:7px 11px; border-radius:7px; border:1.5px solid #e2e8f0; cursor:pointer; font-size:12px; font-weight:500; background:#fff; color:#64748b; display:flex;align-items:center;gap:4px; }
.ai-btn-ghost:hover     { background:#f1f5f9; }

/* Record status */
.ai-rec-idle    { text-align:center; padding:7px; border-radius:7px; background:#f1f5f9; color:#64748b; font-size:12px; font-weight:600; margin-bottom:8px; }
.ai-rec-active  { text-align:center; padding:7px; border-radius:7px; background:#fef2f2; color:#dc2626; font-size:12px; font-weight:600; margin-bottom:8px; animation: ai-pulse-txt 1s infinite; }
.ai-rec-processing { text-align:center; padding:7px; border-radius:7px; background:#fffbeb; color:#d97706; font-size:12px; font-weight:600; margin-bottom:8px; }
@keyframes ai-pulse-txt { 0%,100%{opacity:1} 50%{opacity:.6} }

/* Notice */
.ai-notice { font-size:12px; color:#64748b; background:#f0f9ff; border:1px solid #bae6fd; border-radius:7px; padding:8px 10px; margin-bottom:10px; display:flex; gap:6px; align-items:flex-start; }

/* SOAP result */
.ai-result-hdr { font-size:12px; font-weight:700; color:#0a2d5a; padding:8px 0 6px; border-top:1px solid #e2e8f0; margin-top:8px; }
.ai-soap-row { margin-bottom:8px; }
.ai-soap-row label { font-size:11px; font-weight:700; color:#64748b; display:block; margin-bottom:3px; }
.ai-soap-row textarea { width:100%; padding:7px 9px; border:1.5px solid #cbd5e1; border-radius:7px; font-size:12px; color:#1e293b; resize:vertical; font-family:inherit; }
.ai-soap-row textarea:focus { outline:none; border-color:#00669F; }

/* Fallback textarea */
.ai-fallback-wrap label { font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:4px; }
.ai-fallback-wrap textarea { width:100%; padding:8px 10px; border:1.5px solid #cbd5e1; border-radius:8px; font-size:13px; color:#1e293b; min-height:90px; resize:vertical; font-family:inherit; }
.ai-fallback-wrap textarea:focus { outline:none; border-color:#00669F; }

/* Footer */
.ai-panel-footer { padding:8px 14px; font-size:11px; color:#94a3b8; border-top:1px solid #f1f5f9; text-align:center; }

/* Spinner */
.ai-spinner { display:inline-block; width:14px; height:14px; border:2px solid #fff; border-top-color:transparent; border-radius:50%; animation:ai-spin .7s linear infinite; vertical-align:middle; }
@keyframes ai-spin { to{transform:rotate(360deg)} }

@media(max-width:480px) {
    #ai-panel { width:calc(100vw - 24px); right:12px; bottom:76px; }
    #ai-toggle-btn { right:12px; bottom:16px; }
}
</style>

<!-- HTML Panel -->
<div id="ai-assistant-wrap">
    <button id="ai-toggle-btn" type="button" title="AI Asisten Dokter">
        <i class="fa fa-microphone"></i>
        <span class="ai-btn-label">AI Asisten</span>
    </button>

    <div id="ai-panel">
        <div class="ai-panel-hdr">
            <i class="fa fa-microphone"></i> AI Asisten Dokter
            <button class="ai-panel-close" type="button" id="ai-panel-close" title="Tutup">×</button>
        </div>

        <!-- Mode tabs -->
        <div class="ai-mode-tabs">
            <button class="ai-tab active" data-tab="dictate" type="button">
                <i class="fa fa-pencil"></i> Dikte Cepat
            </button>
            <button class="ai-tab" data-tab="record" type="button">
                <i class="fa fa-circle" style="color:#ef4444"></i> Rekam &amp; Susun SOAP
            </button>
        </div>

        <!-- Tab: Dictate -->
        <div id="ai-tab-dictate" class="ai-tab-content">
            <div class="ai-field-selector">
                <label>Masukkan ke field:</label>
                <select id="ai-target-field">
                    <option value="pl_anamnesa">S — Anamnesa / Keluhan</option>
                    <option value="pl_pemeriksaan">O — Pemeriksaan Fisik</option>
                    <option value="pl_pengobatan">P — Rencana Asuhan</option>
                </select>
            </div>
            <div id="ai-live-text" class="ai-transcript-box" placeholder="Tekan Mulai lalu bicara..."></div>
            <div class="ai-actions">
                <button id="btn-dictate-start" type="button" class="ai-btn-primary">
                    <i class="fa fa-microphone"></i> Mulai
                </button>
                <button id="btn-dictate-stop" type="button" class="ai-btn-secondary" style="display:none">
                    <i class="fa fa-stop"></i> Stop
                </button>
                <button id="btn-dictate-insert" type="button" class="ai-btn-success" style="display:none">
                    <i class="fa fa-check"></i> Masukkan ke Form
                </button>
                <button id="btn-dictate-clear" type="button" class="ai-btn-ghost" title="Hapus teks">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <!-- Fallback: no SpeechRecognition -->
            <div id="ai-dictate-fallback" class="ai-fallback-wrap" style="display:none;margin-top:10px">
                <label>Ketik catatan manual:</label>
                <textarea id="ai-dictate-fallback-text" placeholder="Browser tidak mendukung voice. Ketik catatan di sini..."></textarea>
                <div class="ai-actions" style="margin-top:6px">
                    <button id="btn-dictate-fallback-insert" type="button" class="ai-btn-success">
                        <i class="fa fa-check"></i> Masukkan ke Form
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab: Record & Structure -->
        <div id="ai-tab-record" class="ai-tab-content" style="display:none">
            <p class="ai-notice">
                <i class="fa fa-info-circle" style="color:#0ea5e9;margin-top:1px"></i>
                <span>Rekam percakapan dokter–pasien. AI akan menyusun SOAP secara otomatis. Teks dikirim ke Claude AI.</span>
            </p>
            <div id="ai-rec-status" class="ai-rec-idle">Siap merekam</div>
            <div id="ai-rec-transcript" class="ai-transcript-box" style="min-height:80px" placeholder="Transkripsi akan muncul di sini..."></div>
            <div class="ai-actions">
                <button id="btn-rec-start" type="button" class="ai-btn-primary">
                    <i class="fa fa-circle" style="color:#ef4444"></i> Mulai Rekam
                </button>
                <button id="btn-rec-stop" type="button" class="ai-btn-secondary" style="display:none">
                    <i class="fa fa-stop"></i> Stop &amp; Proses AI
                </button>
            </div>
            <!-- Fallback -->
            <div id="ai-rec-fallback" class="ai-fallback-wrap" style="display:none;margin-top:10px">
                <label>Ketik atau tempel teks percakapan:</label>
                <textarea id="ai-rec-fallback-text" placeholder="Browser tidak mendukung voice. Tempel transkripsi di sini..."></textarea>
                <div class="ai-actions" style="margin-top:6px">
                    <button id="btn-rec-fallback-process" type="button" class="ai-btn-primary">
                        <i class="fa fa-magic"></i> Proses dengan AI
                    </button>
                </div>
            </div>
            <!-- AI Result -->
            <div id="ai-soap-result" style="display:none">
                <div class="ai-result-hdr"><i class="fa fa-magic"></i> Hasil AI — Review sebelum memasukkan:</div>
                <div class="ai-soap-row">
                    <label>S — Subjektif (Anamnesa):</label>
                    <textarea id="ai-res-s" rows="2"></textarea>
                </div>
                <div class="ai-soap-row">
                    <label>O — Objektif (Pemeriksaan):</label>
                    <textarea id="ai-res-o" rows="2"></textarea>
                </div>
                <div class="ai-soap-row">
                    <label>A — Assessment <small style="font-weight:400;color:#94a3b8">(referensi, isi manual di form ICD10)</small>:</label>
                    <textarea id="ai-res-a" rows="2" style="background:#fafafa;color:#64748b"></textarea>
                </div>
                <div class="ai-soap-row">
                    <label>P — Planning (Rencana Asuhan):</label>
                    <textarea id="ai-res-p" rows="2"></textarea>
                </div>
                <div class="ai-actions" style="margin-top:8px">
                    <button id="btn-ai-insert-all" type="button" class="ai-btn-success">
                        <i class="fa fa-check-circle"></i> Masukkan S, O, P ke Form
                    </button>
                    <button id="btn-ai-discard" type="button" class="ai-btn-ghost">
                        Buang
                    </button>
                </div>
            </div>
        </div>

        <div class="ai-panel-footer">
            <i class="fa fa-lock"></i> Powered by Claude AI &middot; Rekaman tidak disimpan
        </div>
    </div>
</div>

<style>
/* AI Comparison Modal */
#modal-ai-soap .modal-dialog { max-width:900px; margin:30px auto; }
#ai-modal-hdr { background:linear-gradient(135deg,#7c3aed,#06b6d4); color:#fff; border-radius:8px 8px 0 0; padding:14px 18px; }
#ai-modal-hdr .modal-title { font-size:15px; font-weight:700; color:#fff; }
#ai-modal-hdr .close { color:#fff; opacity:.8; font-size:22px; margin-top:-2px; text-shadow:none; }
#ai-modal-hdr .close:hover { opacity:1; }
#ai-modal-body { padding:16px 18px; }
.ai-modal-col-hdr { padding:7px 12px; border-radius:7px 7px 0 0; font-size:12px; font-weight:700; border:1px solid #e2e8f0; border-bottom:none; margin-bottom:0; }
.ai-modal-current-hdr { background:#f0f9ff; color:#0369a1; border-color:#bae6fd; }
.ai-modal-ai-hdr { background:linear-gradient(135deg,#f5f3ff,#ecfeff); color:#7c3aed; border-color:#ddd6fe; }
.ai-modal-ai-hdr small { font-weight:400; color:#94a3b8; font-size:10px; margin-left:5px; }
.ai-modal-col-body { border:1px solid #e2e8f0; border-radius:0 0 7px 7px; padding:10px 12px; }
.ai-modal-section { margin-bottom:10px; }
.ai-modal-section:last-child { margin-bottom:0; }
.ai-modal-label { font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px; }
.ai-modal-current-val { background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:7px 10px; min-height:44px; font-size:12px; color:#334155; white-space:pre-wrap; word-break:break-word; line-height:1.5; }
.ai-modal-current-val.ai-empty { color:#cbd5e1; font-style:italic; }
.ai-modal-textarea { width:100%; border:1.5px solid #e2e8f0; border-radius:6px; padding:7px 10px; font-size:12px; resize:vertical; color:#334155; background:#fff; font-family:inherit; line-height:1.5; box-sizing:border-box; height: 100px !important }
.ai-modal-textarea:focus { outline:none; border-color:#7c3aed; box-shadow:0 0 0 2px rgba(124,58,237,.12); }
.ai-modal-textarea-ref { background:#fafafa !important; color:#94a3b8 !important; }
#ai-modal-footer { background:#f8fafc; border-top:1px solid #e2e8f0; padding:10px 16px; border-radius:0 0 8px 8px; }
#ai-modal-transcript-row { margin-top:10px; padding:8px 12px; background:#f8fafc; border-radius:7px; border:1px solid #e2e8f0; font-size:11px; color:#64748b; }
</style>

<!-- AI SOAP Comparison Modal -->
<div class="modal fade" id="modal-ai-soap" tabindex="-1" role="dialog" aria-labelledby="ai-modal-title">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:8px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.25);">
      <div class="modal-header" id="ai-modal-hdr" style="border-bottom:none;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span>&times;</span></button>
        <h4 class="modal-title" id="ai-modal-title">
          <i class="fa fa-magic"></i> Hasil Analisa AI &mdash; Perbandingan SOAP
        </h4>
      </div>
      <div class="modal-body" id="ai-modal-body">
        <div class="row">
          <!-- Left: Current SOAP -->
          <div class="col-md-6">
            <div class="ai-modal-col-hdr ai-modal-current-hdr">
              <i class="fa fa-history"></i> SOAP Kunjungan Sebelumnya <small id="ai-modal-prev-date" style="font-weight:400;font-size:10px;color:#94a3b8;margin-left:4px"></small>
            </div>
            <div class="ai-modal-col-body">
              <div id="ai-modal-no-prev" style="display:none;text-align:center;padding:18px 8px;color:#94a3b8;font-size:12px"><i class="fa fa-info-circle" style="display:block;font-size:22px;margin-bottom:6px"></i>Tidak ada riwayat SOAP<br>dengan dokter ini</div>
              <div class="ai-modal-section">
                <div class="ai-modal-label">S &mdash; Subjektif (Anamnesa)</div>
                <div id="modal-cur-s" class="ai-modal-current-val"></div>
              </div>
              <div class="ai-modal-section">
                <div class="ai-modal-label">O &mdash; Objektif (Pemeriksaan)</div>
                <div id="modal-cur-o" class="ai-modal-current-val"></div>
              </div>
              <div class="ai-modal-section">
                <div class="ai-modal-label">A &mdash; Assessment (Diagnosis)</div>
                <div id="modal-cur-a" class="ai-modal-current-val"></div>
              </div>
              <div class="ai-modal-section">
                <div class="ai-modal-label">P &mdash; Planning (Pengobatan)</div>
                <div id="modal-cur-p" class="ai-modal-current-val"></div>
              </div>
            </div>
          </div>
          <!-- Right: AI Results -->
          <div class="col-md-6">
            <div class="ai-modal-col-hdr ai-modal-ai-hdr">
              <i class="fa fa-magic"></i> Hasil Analisa AI
              <small>Dapat diedit sebelum dimasukkan</small>
            </div>
            <div class="ai-modal-col-body">
              <div class="ai-modal-section">
                <div class="ai-modal-label">S &mdash; Subjektif</div>
                <textarea id="modal-ai-s" class="ai-modal-textarea" rows="3" placeholder="(kosong)"></textarea>
              </div>
              <div class="ai-modal-section">
                <div class="ai-modal-label">O &mdash; Objektif</div>
                <textarea id="modal-ai-o" class="ai-modal-textarea" rows="3" placeholder="(kosong)"></textarea>
              </div>
              <div class="ai-modal-section">
                <div class="ai-modal-label">A &mdash; Assessment <small style="text-transform:none;letter-spacing:0;font-weight:400;color:#94a3b8">(referensi, isi ICD10 di form)</small></div>
                <textarea id="modal-ai-a" class="ai-modal-textarea ai-modal-textarea-ref" rows="2" readonly placeholder="(kosong)"></textarea>
              </div>
              <div class="ai-modal-section">
                <div class="ai-modal-label">P &mdash; Planning</div>
                <textarea id="modal-ai-p" class="ai-modal-textarea" rows="3" placeholder="(kosong)"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div id="ai-modal-transcript-row" style="display:none">
          <strong><i class="fa fa-microphone"></i> Transkripsi:</strong> <span id="ai-modal-transcript-text" style="word-break:break-word"></span>
        </div>
      </div>
      <div class="modal-footer" id="ai-modal-footer">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:space-between;width:100%">
          <div style="font-size:11px;color:#94a3b8"><i class="fa fa-info-circle"></i> Field A tidak diisi otomatis &mdash; gunakan typeahead ICD10 di form</div>
          <div style="display:flex;gap:8px">
            <!-- <button type="button" class="btn btn-xs btn-default" data-dismiss="modal" style="font-size:13px">Tutup</button> -->
            <button type="button" id="btn-modal-ai-insert" class="btn btn-xs" style="background:linear-gradient(135deg,#16a34a,#0ea5e9);color:#fff;font-weight:700;font-size:13px;border:none;border-radius:6px">
              <i class="fa fa-check-circle"></i> Masukkan ke dalam Form SOAP
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
    'use strict';

    /* ---- State ---- */
    var panelOpen      = false;
    var dictateRunning = false;
    var recRunning     = false;
    var dictateText    = '';
    var recText        = '';
    var hasSpeech      = !!(window.SpeechRecognition || window.webkitSpeechRecognition);

    /* ---- DOM refs ---- */
    var $panel         = $('#ai-panel');
    var $toggleBtn     = $('#ai-toggle-btn');
    var $tabs          = $('.ai-tab');
    var $tabDictate    = $('#ai-tab-dictate');
    var $tabRecord     = $('#ai-tab-record');
    var $liveText      = $('#ai-live-text');
    var $recTranscript = $('#ai-rec-transcript');
    var $recStatus     = $('#ai-rec-status');
    var $soapResult    = $('#ai-soap-result');

    /* ---- Fallback if no SpeechRecognition ---- */
    if (!hasSpeech) {
        $('#ai-dictate-fallback').show();
        $('#btn-dictate-start').hide();
        $('#ai-rec-fallback').show();
        $('#btn-rec-start').hide();
        $('#btn-rec-stop').hide();
    }

    /* ---- Panel toggle ---- */
    $toggleBtn.on('click', function() {
        panelOpen = !panelOpen;
        $panel.toggleClass('ai-panel-open', panelOpen);
    });
    $('#ai-panel-close').on('click', function() {
        panelOpen = false;
        $panel.removeClass('ai-panel-open');
    });

    /* ---- Mode tabs ---- */
    $tabs.on('click', function() {
        var tab = $(this).data('tab');
        $tabs.removeClass('active');
        $(this).addClass('active');
        $tabDictate.toggle(tab === 'dictate');
        $tabRecord.toggle(tab === 'record');
    });

    /* ============================================================
       MODE 1: DICTATE — Real-time voice → target SOAP field
       ============================================================ */
    var dictateRecog = null;
    var dictateFinal = '';
    var dictateInterim = '';

    if (hasSpeech) {
        var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        dictateRecog = new SR();
        dictateRecog.lang = 'id-ID';
        dictateRecog.interimResults = true;
        dictateRecog.continuous = true;
        dictateRecog.maxAlternatives = 1;

        dictateRecog.onresult = function(e) {
            var interim = '';
            for (var i = e.resultIndex; i < e.results.length; i++) {
                var t = e.results[i][0].transcript;
                if (e.results[i].isFinal) {
                    dictateFinal += t + ' ';
                } else {
                    interim += t;
                }
            }
            dictateInterim = interim;
            $liveText.text(dictateFinal + (interim ? '[' + interim + ']' : ''));
        };

        dictateRecog.onerror = function(e) {
            if (e.error === 'not-allowed' || e.error === 'service-not-allowed') {
                $('#ai-dictate-fallback').show();
                $('#btn-dictate-start, #btn-dictate-stop').hide();
                aiAlert('warning', 'Izin mikrofon ditolak. Gunakan input manual.');
            } else {
                aiAlert('warning', 'Mic error: ' + e.error);
            }
            stopDictate();
        };

        dictateRecog.onend = function() {
            if (dictateRunning) {
                // auto-restart to keep continuous
                try { dictateRecog.start(); } catch(ex) {}
            }
        };
    }

    function startDictate() {
        dictateFinal = '';
        dictateInterim = '';
        $liveText.text('');
        dictateRunning = true;
        $toggleBtn.addClass('ai-recording');
        $('#btn-dictate-start').hide();
        $('#btn-dictate-stop').show();
        $('#btn-dictate-insert').hide();
        try { dictateRecog.start(); } catch(ex) {}
    }

    function stopDictate() {
        dictateRunning = false;
        $toggleBtn.removeClass('ai-recording');
        $('#btn-dictate-start').show();
        $('#btn-dictate-stop').hide();
        try { dictateRecog.stop(); } catch(ex) {}
        var result = $.trim(dictateFinal + dictateInterim);
        $liveText.text(result);
        if (result.length > 0) {
            $('#btn-dictate-insert').show();
        }
    }

    $('#btn-dictate-start').on('click', startDictate);
    $('#btn-dictate-stop').on('click', stopDictate);

    $('#btn-dictate-insert').on('click', function() {
        var target = $('#ai-target-field').val();
        var text   = $.trim($liveText.text());
        if (!target || !text) return;
        var $field = $('#' + target);
        if ($field.length) {
            var existing = $.trim($field.val());
            $field.val(existing ? existing + '\n' + text : text);
            $field.focus();
            aiAlert('success', 'Teks berhasil dimasukkan ke form.');
            dictateFinal = '';
            $liveText.text('');
            $('#btn-dictate-insert').hide();
        }
    });

    $('#btn-dictate-clear').on('click', function() {
        dictateFinal = '';
        dictateInterim = '';
        $liveText.text('');
        $('#btn-dictate-insert').hide();
    });

    /* Fallback insert (no speech) */
    $('#btn-dictate-fallback-insert').on('click', function() {
        var target = $('#ai-target-field').val();
        var text   = $.trim($('#ai-dictate-fallback-text').val());
        if (!target || !text) { aiAlert('warning', 'Pilih field dan masukkan teks.'); return; }
        var $field = $('#' + target);
        if ($field.length) {
            var existing = $.trim($field.val());
            $field.val(existing ? existing + '\n' + text : text);
            $field.focus();
            $('#ai-dictate-fallback-text').val('');
            aiAlert('success', 'Teks berhasil dimasukkan ke form.');
        }
    });

    /* ============================================================
       MODE 2: RECORD & STRUCTURE — voice → Claude → SOAP
       ============================================================ */
    var recRecog   = null;
    var recFinal   = '';

    if (hasSpeech) {
        var SR2 = window.SpeechRecognition || window.webkitSpeechRecognition;
        recRecog = new SR2();
        recRecog.lang = 'id-ID';
        recRecog.interimResults = true;
        recRecog.continuous = true;
        recRecog.maxAlternatives = 1;

        recRecog.onresult = function(e) {
            var interim = '';
            for (var i = e.resultIndex; i < e.results.length; i++) {
                var t = e.results[i][0].transcript;
                if (e.results[i].isFinal) {
                    recFinal += t + ' ';
                } else {
                    interim += t;
                }
            }
            $recTranscript.text(recFinal + (interim ? '[' + interim + ']' : ''));
        };

        recRecog.onerror = function(e) {
            if (e.error === 'not-allowed' || e.error === 'service-not-allowed') {
                $('#ai-rec-fallback').show();
                $('#btn-rec-start, #btn-rec-stop').hide();
                aiAlert('warning', 'Izin mikrofon ditolak. Gunakan input manual.');
            } else {
                aiAlert('warning', 'Mic error: ' + e.error);
            }
            stopRec(false);
        };

        recRecog.onend = function() {
            if (recRunning) {
                try { recRecog.start(); } catch(ex) {}
            }
        };
    }

    function startRec() {
        recFinal = '';
        $recTranscript.text('');
        $soapResult.hide();
        recRunning = true;
        $toggleBtn.addClass('ai-recording');
        $recStatus.removeClass('ai-rec-idle ai-rec-processing').addClass('ai-rec-active').text('● Merekam...');
        $('#btn-rec-start').hide();
        $('#btn-rec-stop').show();
        try { recRecog.start(); } catch(ex) {}
    }

    function stopRec(doProcess) {
        recRunning = false;
        $toggleBtn.removeClass('ai-recording');
        $('#btn-rec-start').show();
        $('#btn-rec-stop').hide();
        try { recRecog.stop(); } catch(ex) {}
        var text = $.trim(recFinal);
        $recTranscript.text(text);
        if (doProcess && text.length > 0) {
            callClaudeAPI(text);
        } else if (doProcess && text.length === 0) {
            $recStatus.removeClass('ai-rec-active ai-rec-processing').addClass('ai-rec-idle').text('Siap merekam');
            aiAlert('warning', 'Tidak ada teks yang direkam.');
        } else {
            $recStatus.removeClass('ai-rec-active ai-rec-processing').addClass('ai-rec-idle').text('Siap merekam');
        }
    }

    $('#btn-rec-start').on('click', startRec);
    $('#btn-rec-stop').on('click', function() { stopRec(true); });

    /* Fallback process */
    $('#btn-rec-fallback-process').on('click', function() {
        var text = $.trim($('#ai-rec-fallback-text').val());
        if (!text) { aiAlert('warning', 'Masukkan teks transkripsi terlebih dahulu.'); return; }

        callClaudeAPI(text);
    });

    /* ---- Claude API call ---- */
    function callClaudeAPI(text) {
        $recStatus.removeClass('ai-rec-idle ai-rec-active').addClass('ai-rec-processing').html('<span class="ai-spinner"></span> Memproses AI...');
        $soapResult.hide();

        $.ajax({
            url: '<?php echo site_url("pelayanan/Pl_ai_assistant/structure_soap") ?>',
            type: 'POST',
            data: { transcription: text, no_mr: $('#noMrHidden').val(), kode_dokter: $('#kode_dokter_poli').val(), no_kunjungan: $('#no_kunjungan').val() },
            dataType: 'json',
            timeout: 35000,
            success: function(res) {
                $recStatus.removeClass('ai-rec-processing').addClass('ai-rec-idle').text('Siap merekam');
                if (res.status === 200 && res.data) {
                    $('#ai-res-s').val(res.data.subjektif  || '');
                    $('#ai-res-o').val(res.data.objektif   || '');
                    $('#ai-res-a').val(res.data.assessment || '');
                    $('#ai-res-p').val(res.data.planning   || '');
                    openAIModal(res.data, text, res.soap_sebelumnya || null);
                } else {
                    aiAlert('error', res.message || 'Gagal memproses AI.');
                }
            },
            error: function(xhr) {
                $recStatus.removeClass('ai-rec-processing').addClass('ai-rec-idle').text('Siap merekam');
                var msg = 'Gagal menghubungi server.';
                try { var r = JSON.parse(xhr.responseText); msg = r.message || msg; } catch(e) {}
                aiAlert('error', msg);
            }
        });
    }

    /* ---- Open AI Comparison Modal ---- */
    function openAIModal(aiData, transcription, soapPrev) {
        function setVal(id, val) {
            var $el = $(id);
            if (val) { $el.removeClass('ai-empty').text(val); }
            else      { $el.addClass('ai-empty').text('(tidak ada data)'); }
        }

        if (soapPrev) {
            setVal('#modal-cur-s', soapPrev.subjektif);
            setVal('#modal-cur-o', soapPrev.objektif);
            setVal('#modal-cur-a', soapPrev.assessment);
            setVal('#modal-cur-p', soapPrev.planning);
            if (soapPrev.tgl_kunjungan) {
                var d   = new Date(soapPrev.tgl_kunjungan);
                var fmt = (d.getDate() < 10 ? '0' : '') + d.getDate() + '/' +
                          (d.getMonth() + 1 < 10 ? '0' : '') + (d.getMonth() + 1) + '/' + d.getFullYear();
                $('#ai-modal-prev-date').text('(' + fmt + ')');
            } else {
                $('#ai-modal-prev-date').text('');
            }
            $('#ai-modal-no-prev').hide();
        } else {
            setVal('#modal-cur-s', '');
            setVal('#modal-cur-o', '');
            setVal('#modal-cur-a', '');
            setVal('#modal-cur-p', '');
            $('#ai-modal-prev-date').text('');
            $('#ai-modal-no-prev').show();
        }

        $('#modal-ai-s').val(aiData.subjektif  || '');
        $('#modal-ai-o').val(aiData.objektif   || '');
        $('#modal-ai-a').val(aiData.assessment || '');
        $('#modal-ai-p').val(aiData.planning   || '');

        if (transcription && transcription.length > 0) {
            var preview = transcription.length > 250 ? transcription.substring(0, 250) + '\u2026' : transcription;
            $('#ai-modal-transcript-text').text(preview);
            $('#ai-modal-transcript-row').show();
        } else {
            $('#ai-modal-transcript-row').hide();
        }

        $('#modal-ai-soap').modal('show');
    }

    /* ---- Modal: Insert S, O, P to form ---- */
    $('#btn-modal-ai-insert').on('click', function() {
        var s = $.trim($('#modal-ai-s').val());
        var o = $.trim($('#modal-ai-o').val());
        var a = $.trim($('#modal-ai-a').val());
        var p = $.trim($('#modal-ai-p').val());

        if (s) {
            var ex = $.trim($('#pl_anamnesa').val());
            $('#pl_anamnesa').val(ex ? ex + '\n' + s : s);
        }
        if (o) {
            var ex = $.trim($('#pl_pemeriksaan').val());
            $('#pl_pemeriksaan').val(ex ? ex + '\n' + o : o);
        }
        if (a) {
            var ex = $.trim($('#pl_catatan_assesmen').val());
            $('#pl_catatan_assesmen').val(ex ? ex + '\n' + a : a);
        }
        if (p) {
            var ex = $.trim($('#pl_pengobatan').val());
            $('#pl_pengobatan').val(ex ? ex + '\n' + p : p);
        }

        $('#modal-ai-soap').modal('hide');
        aiAlert('success', 'Data SOAP berhasil dimasukkan ke form.');
        recFinal = '';
        $recTranscript.text('');
        $soapResult.hide();
    });

    /* ---- Insert all SOAP results to form ---- */
    $('#btn-ai-insert-all').on('click', function() {
        var s = $.trim($('#ai-res-s').val());
        var o = $.trim($('#ai-res-o').val());
        var p = $.trim($('#ai-res-p').val());

        if (s) {
            var ex = $.trim($('#pl_anamnesa').val());
            $('#pl_anamnesa').val(ex ? ex + '\n' + s : s);
        }
        if (o) {
            var ex = $.trim($('#pl_pemeriksaan').val());
            $('#pl_pemeriksaan').val(ex ? ex + '\n' + o : o);
        }
        if (p) {
            var ex = $.trim($('#pl_pengobatan').val());
            $('#pl_pengobatan').val(ex ? ex + '\n' + p : p);
        }

        aiAlert('success', 'Data SOAP berhasil dimasukkan ke form.');
        $soapResult.hide();
        recFinal = '';
        $recTranscript.text('');
    });

    $('#btn-ai-discard').on('click', function() {
        $soapResult.hide();
        recFinal = '';
        $recTranscript.text('');
    });

    /* ---- Helper: alert ---- */
    function aiAlert(type, msg) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true, position: 'bottom-end', showConfirmButton: false,
                timer: 3500, timerProgressBar: true,
                icon: type, title: msg,
                customClass: { popup: 'swal-ai-toast' }
            });
        } else {
            alert(msg);
        }
    }

})();
</script>

<!-- ================================================================
     Modal Order Resep Dokter
     ================================================================ -->
<style>


.reg-grid { display:grid;grid-template-columns:2fr .5fr .5fr .65fr 1fr .5fr .5fr;gap:6px;align-items:end;margin-bottom:6px; }
.reg-grid2 { display:grid;grid-template-columns:1fr auto;gap:6px;align-items:end;margin-bottom:12px; }
.reg-grid .rg-col,.reg-grid2 .rg-col { display:flex;flex-direction:column; }
.reg-grid .rg-lbl,.reg-grid2 .rg-lbl { font-size:11px;font-weight:600;color:#64748b;margin-bottom:3px; }
.reg-grid input,.reg-grid2 input { padding:5px 8px;border:1px solid #cbd5e1;border-radius:4px;font-size:13px;width:100%; }
.reg-grid input:focus,.reg-grid2 input:focus { outline:none;border-color:#0891b2;box-shadow:0 0 0 2px rgba(8,145,178,.15); }

#resep-drug-table { width:100%;border-collapse:collapse;font-size:12.5px; }
#resep-drug-table thead th { background:#0f172a;color:#fff;padding:7px 10px;font-weight:600;font-size:11.5px;text-align:left; }
#resep-drug-table tbody td { padding:6px 10px;border-bottom:1px solid #f1f5f9;vertical-align:middle; }
#resep-drug-table tbody tr:hover { background:#f8fafc; }
.btn-del-drug { background:none;border:1px solid #fca5a5;color:#ef4444;border-radius:4px;padding:2px 7px;cursor:pointer;font-size:12px; }
.btn-del-drug:hover { background:#ef4444;color:#fff; }
.resep-empty-msg { text-align:center;color:#94a3b8;padding:16px;font-size:13px; }
.resep-kode-info { font-size:11px;color:#64748b; }
#resep-status-msg { display:none;padding:8px 12px;border-radius:4px;font-size:13px;margin-top:8px; }
.btn-resep-selesai { background:#16a34a;color:#fff;border:none;border-radius:5px;padding:7px 18px;font-size:12px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:5px; }
.btn-resep-selesai:hover { background:#15803d; }
.btn-resep-selesai:disabled { opacity:.5;cursor:not-allowed; }
.resep-status-done { display:inline-flex;align-items:center;gap:5px;background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;border-radius:5px;padding:7px 14px;font-size:12px;font-weight:600; }
#resep-status-msg.msg-ok { background:#d1fae5;color:#065f46;border:1px solid #6ee7b7; }
#resep-status-msg.msg-err { background:#fee2e2;color:#991b1b;border:1px solid #fca5a5; }

/* Resep Tabs */
.resep-tabs { display:flex;list-style:none;padding:0;margin:0 0 12px 0;gap:4px;border-bottom:2px solid #e2e8f0; }
.resep-tab { padding:8px 16px;font-size:12.5px;font-weight:600;color:#64748b;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .15s;display:flex;align-items:center;gap:5px; }
.resep-tab:hover { color:#0f766e; }
.resep-tab.active { color:#0f766e;border-bottom-color:#0d9488; }
.resep-tab-content { display:none; }
.resep-tab-content.active { display:block; }

/* Racikan grid layouts */
.reg-grid-racikan { display:grid;grid-template-columns:2fr .6fr 1fr .5fr .5fr 1.2fr;gap:6px;align-items:end;margin-bottom:6px; }
.reg-grid-racikan .rg-col { display:flex;flex-direction:column; }
.reg-grid-racikan .rg-lbl { font-size:11px;font-weight:600;color:#64748b;margin-bottom:3px; }
.reg-grid-racikan input,.reg-grid-racikan select { padding:5px 8px;border:1px solid #cbd5e1;border-radius:4px;font-size:13px;width:100%;box-sizing:border-box; }
.reg-grid-racikan input:focus,.reg-grid-racikan select:focus { outline:none;border-color:#0891b2;box-shadow:0 0 0 2px rgba(8,145,178,.15); }
.reg-grid-racikan-child { display:grid;grid-template-columns:2fr .8fr .8fr 1.5fr auto;gap:6px;align-items:end; }
.reg-grid-racikan-child .rg-col { display:flex;flex-direction:column; }
.reg-grid-racikan-child .rg-lbl { font-size:11px;font-weight:600;color:#64748b;margin-bottom:3px; }
.reg-grid-racikan-child input { padding:5px 8px;border:1px solid #cbd5e1;border-radius:4px;font-size:13px;width:100%;box-sizing:border-box; }
.reg-grid-racikan-child input:focus { outline:none;border-color:#0891b2;box-shadow:0 0 0 2px rgba(8,145,178,.15); }

/* Racikan rows in drug table */
#resep-drug-table .racikan-hdr-row td { background:#f8fffe;vertical-align:top; }
.racikan-bahan-wrap { padding:6px 10px;margin-top:4px; }
.racikan-bahan-wrap .rb-label { font-size:11px;font-style:italic;color:#64748b;margin-bottom:2px; }
.racikan-bahan-wrap .rb-item { font-size:11px;line-height:1.9; }
.racikan-bahan-wrap .rb-item .fa-times { color:#ef4444;cursor:pointer;margin-right:4px;font-size:12px; }
.racikan-bahan-wrap .rb-item .fa-times:hover { opacity:.6; }
.racikan-bahan-empty { font-size:11px;color:#94a3b8;font-style:italic;padding:4px 0; }
.btn-edit-racikan { background:none;border:1px solid #f59e0b;color:#d97706;border-radius:4px;padding:2px 7px;cursor:pointer;font-size:12px;margin-right:3px; }
.btn-edit-racikan:hover { background:#f59e0b;color:#fff; }
.btn-add-bahan { background:none;border:1px solid #0891b2;color:#0891b2;border-radius:4px;padding:2px 7px;cursor:pointer;font-size:12px;margin-right:3px; }
.btn-add-bahan:hover { background:#0891b2;color:#fff; }
</style>

<div class="modal fade" id="modal-resep-dokter" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width:88%;max-width:960px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="closeResepModal()">&times;</button>
        <h4 class="modal-title">
          <i class="fa fa-medkit"></i> Order Resep Dokter
          <small style="font-weight:400;margin-left:8px;font-size:13px;opacity:.85">
            <?php echo isset($value->no_mr) ? htmlspecialchars($value->no_mr) : ''?> &mdash; <?php echo isset($value->nama_pasien) ? htmlspecialchars($value->nama_pasien) : ''?>
          </small>
        </h4>
      </div>
      <div class="modal-body">

        <!-- Resep header settings -->
        <div class="rsb-wrap">
          <div class="rsb-group">
            <span class="rsb-label">Jenis Resep</span>
            <div class="rsb-radios">
              <label class="rsb-radio-opt">
                <input type="radio" name="resep_jenis_m" value="non_prb"
                  <?php echo ($_resep_hdr && $_resep_hdr->jenis_resep === 'prb') ? '' : 'checked'?>> Non PRB
              </label>
              <label class="rsb-radio-opt">
                <input type="radio" name="resep_jenis_m" value="prb"
                  <?php echo ($_resep_hdr && $_resep_hdr->jenis_resep === 'prb') ? 'checked' : ''?>> PRB
              </label>
            </div>
          </div>
          <?php
            // Determine iter value: existing header > TTV riwayat > default 0
            if ($_resep_hdr && $_resep_hdr->resep_iter) {
                $_iter_val = $_resep_hdr->resep_iter;
            } elseif (isset($riwayat->resep_iter) && $riwayat->resep_iter == 'Y' && isset($riwayat->jumlah_iter) && $riwayat->jumlah_iter > 0) {
                $_iter_val = $riwayat->jumlah_iter;
            } else {
                $_iter_val = '0';
            }
          ?>
          <div class="rsb-group">
            <?php $_is_prb = ($_resep_hdr && $_resep_hdr->jenis_resep === 'prb'); ?>
            <span class="rsb-label">Resep Iter</span>
            <div class="rsb-radios">
              <label class="rsb-radio-opt">
                <input type="radio" name="resep_iter_m" value="0"
                  <?php echo ($_iter_val == '0' || $_is_prb) ? 'checked' : ''?>
                  <?php echo $_is_prb ? 'disabled' : ''?>> Tidak
              </label>
              <label class="rsb-radio-opt">
                <input type="radio" name="resep_iter_m" value="1"
                  <?php echo (!$_is_prb && $_iter_val == '1') ? 'checked' : ''?>
                  <?php echo $_is_prb ? 'disabled' : ''?>> 1x
              </label>
              <label class="rsb-radio-opt">
                <input type="radio" name="resep_iter_m" value="2"
                  <?php echo (!$_is_prb && $_iter_val == '2') ? 'checked' : ''?>
                  <?php echo $_is_prb ? 'disabled' : ''?>> 2x
              </label>
            </div>
          </div>
          <div class="rsb-group rsb-ket">
            <span class="rsb-label">Keterangan Resep</span>
            <input type="text" id="resep_ket_modal" placeholder="Keterangan (opsional)"
              value="<?php echo $_resep_hdr ? htmlspecialchars($_resep_hdr->keterangan ?: '') : ''?>">
          </div>
        </div>

        <!-- Tab navigation -->
        <ul class="resep-tabs" id="resep-tab-nav">
          <li class="resep-tab active" data-tab="resep-tab-biasa"><i class="fa fa-pills"></i> Obat Biasa</li>
          <li class="resep-tab" data-tab="resep-tab-racikan"><i class="fa fa-flask"></i> Racikan</li>
        </ul>

        <!-- TAB 1: Obat Biasa -->
        <div class="resep-tab-content active" id="resep-tab-biasa">
          <div class="reg-grid">
            <div class="rg-col">
              <span class="rg-lbl">Nama Obat <span style="color:#ef4444">*</span></span>
              <input type="text" id="resep_obat_keyword" placeholder="Cari nama obat...">
              <input type="hidden" id="resep_kode_brg">
            </div>
            <div class="rg-col">
              <span class="rg-lbl">DD <span style="color:#ef4444">*</span></span>
              <input type="number" id="resep_jml_dosis" placeholder="2" min="1" oninput="calcResepJml()">
            </div>
            <div class="rg-col">
              <span class="rg-lbl">Jml/Dos</span>
              <input type="number" id="resep_jml_dosis_obat" placeholder="1" min="1" oninput="calcResepJml()">
            </div>
            <div class="rg-col">
              <span class="rg-lbl">Satuan</span>
              <input type="text" id="resep_satuan_obat" value="Tab">
            </div>
            <div class="rg-col">
              <span class="rg-lbl">Aturan Pakai</span>
              <input type="text" id="resep_aturan_pakai" value="Sesudah Makan">
            </div>
            <div class="rg-col">
              <span class="rg-lbl">Hari</span>
              <input type="number" id="resep_jml_hari" placeholder="3" min="1" oninput="calcResepJml()">
            </div>
            <div class="rg-col">
              <span class="rg-lbl">Jml Total</span>
              <input type="number" id="resep_jml_pesan" min="1">
            </div>
          </div>
          <div class="reg-grid2">
            <div class="rg-col">
              <span class="rg-lbl">Keterangan Obat</span>
              <input type="text" id="resep_ket_obat" placeholder="Keterangan (opsional)">
            </div>
            <div>
              <button type="button" onclick="addObatResepModal()" class="fdd-pm-btn-add" style="margin-top:20px">
                <i class="fa fa-plus"></i> Tambah Obat
              </button>
            </div>
          </div>
        </div>

        <!-- TAB 2: Racikan -->
        <div class="resep-tab-content" id="resep-tab-racikan">
          <!-- Racikan header form -->
          <div id="racikan-header-form">
            <div class="reg-grid-racikan">
              <div class="rg-col">
                <span class="rg-lbl">Nama Racikan <span style="color:#ef4444">*</span></span>
                <input type="text" id="resep_nama_racikan" placeholder="Nama racikan...">
              </div>
              <div class="rg-col">
                <span class="rg-lbl">Qty <span style="color:#ef4444">*</span></span>
                <input type="number" id="resep_jml_racikan" placeholder="1" min="1">
              </div>
              <div class="rg-col">
                <span class="rg-lbl">Satuan</span>
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'Bks', 'resep_satuan_racikan', 'resep_satuan_racikan', '', '', '');?>
              </div>
              <div class="rg-col">
                <span class="rg-lbl">DD</span>
                <input type="number" id="resep_dd_racikan" placeholder="3" min="1">
              </div>
              <div class="rg-col">
                <span class="rg-lbl">x Jml</span>
                <input type="number" id="resep_jmldos_racikan" placeholder="1" min="1">
              </div>
              <div class="rg-col">
                <span class="rg-lbl">Aturan Pakai</span>
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan', 'resep_anjuran_racikan', 'resep_anjuran_racikan', '', '', '');?>
              </div>
            </div>
            <div class="reg-grid2">
              <div class="rg-col">
                <span class="rg-lbl">Keterangan</span>
                <input type="text" id="resep_ket_racikan" placeholder="Keterangan (opsional)">
              </div>
              <div style="display:flex;gap:4px;align-items:end">
                <button type="button" onclick="saveRacikanHeader()" class="fdd-pm-btn-add" id="btn-save-racikan-hdr" style="margin-top:20px">
                  <i class="fa fa-save"></i> Simpan Racikan
                </button>
                <button type="button" onclick="updateRacikanHeader()" class="fdd-pm-btn-add" id="btn-update-racikan-hdr" style="margin-top:20px;display:none;background:#16a34a">
                  <i class="fa fa-pencil"></i> Update Racikan
                </button>
                <button type="button" onclick="resetRacikanForm()" class="fdd-pm-btn-cancel" style="margin-top:20px">
                  <i class="fa fa-refresh"></i> Baru
                </button>
              </div>
            </div>
          </div>

          <!-- Racikan child form (shown after header save) -->
          <div id="racikan-child-form" style="display:none;background:#f1f5f9;padding:10px 12px;border-radius:6px;margin-top:8px">
            <div style="font-size:12px;font-weight:700;color:#334155;margin-bottom:6px">
              <i class="fa fa-flask" style="color:#0891b2"></i>
              Bahan Obat Racikan <span id="racikan-child-label" style="color:#0891b2"></span>
            </div>
            <div class="reg-grid-racikan-child">
              <div class="rg-col">
                <span class="rg-lbl">Cari Obat <span style="color:#ef4444">*</span></span>
                <input type="text" id="resep_obat_racikan_keyword" placeholder="Cari obat racikan...">
                <input type="hidden" id="resep_kode_brg_racikan">
              </div>
              <div class="rg-col">
                <span class="rg-lbl">Dosis <span style="color:#ef4444">*</span></span>
                <input type="text" id="resep_dosis_racikan" placeholder="Dosis">
              </div>
              <div class="rg-col">
                <span class="rg-lbl">Satuan</span>
                <input type="text" id="resep_satuan_racik_child" placeholder="g/mg">
              </div>
              <div class="rg-col">
                <span class="rg-lbl">Keterangan</span>
                <input type="text" id="resep_ket_racik_child" placeholder="Ket (opsional)">
              </div>
              <div>
                <button type="button" onclick="addRacikanChild()" class="fdd-pm-btn-add" style="margin-top:20px">
                  <i class="fa fa-plus"></i> Tambahkan
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Drug list table -->
        <table id="resep-drug-table">
          <thead>
            <tr>
              <th style="width:36%">Nama Obat</th>
              <th>Signa</th>
              <th style="width:65px;text-align:center">Jml</th>
              <th>Keterangan</th>
              <th style="width:46px"></th>
            </tr>
          </thead>
          <tbody id="resep-drug-tbody">
            <tr><td colspan="5" class="resep-empty-msg">Belum ada obat ditambahkan</td></tr>
          </tbody>
        </table>

        <div id="resep-status-msg"></div>
      </div>
      <div class="modal-footer">
        <span class="resep-kode-info" id="resep-kode-info"></span>
        <button type="button" class="fdd-pm-btn-cancel" onclick="closeResepModal()"><i class="fa fa-times"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
/* ================================================================
   Order Resep Dokter — Modal SOAP
   ================================================================ */
var resepKodePesan    = <?php echo $_resep_hdr ? json_encode($_resep_hdr->kode_pesan_resep) : 'null'?>;
var resepEResepStatus = <?php echo ($_resep_hdr && $_resep_hdr->e_resep == 1) ? '1' : 'null'?>;
var resepHasDrugs     = false;
var _resepNoKunj     = '<?php echo addslashes($value->no_kunjungan)?>';
var _resepNoReg      = '<?php echo addslashes($value->no_registrasi)?>';
var _resepNoMr       = '<?php echo addslashes($value->no_mr)?>';
var _resepKodePerush = '<?php echo addslashes($value->kode_perusahaan)?>';
var _resepKodeKelom  = '<?php echo addslashes($value->kode_kelompok)?>';
var _resepKodeBagian = '<?php echo addslashes($value->kode_bagian)?>';
var _resepKodeDokter = '<?php echo isset($value->kode_dokter) ? addslashes($value->kode_dokter) : ''?>';
var _resepKodeKlas   = '<?php echo isset($kode_klas) ? $kode_klas : 16?>';
var _resepKodeProfit = '<?php echo isset($kode_profit) ? $kode_profit : 2000?>';

$(document).ready(function() {
    // Typeahead drug search in resep modal
    if (typeof $.fn.typeahead !== 'undefined') {
        $('#resep_obat_keyword').typeahead({
            source: function(query, result) {
                $.ajax({
                    url: 'templates/references/getObatByBagianAutoCompleteNoInfoStok',
                    data: { keyword: query, bag: '060101' },
                    dataType: 'json',
                    type: 'POST',
                    success: function(resp) {
                        result($.map(resp, function(item) { return item; }));
                    }
                });
            },
            afterSelect: function(item) {
                var parts = item.split(':');
                $('#resep_kode_brg').val($.trim(parts[0]));
                $('#resep_obat_keyword').val($.trim(parts.slice(1).join(':')));
                $('#resep_jml_dosis').focus();
            }
        });
    }

    // Pre-populate table on page load via endpoint
    if (resepKodePesan) {
        $('#resep-kode-info').text('Kode Resep: ' + resepKodePesan);
        loadResepDrugsModal();
    }

    // Keyboard: Enter moves to next field / submits
    $('#resep_obat_keyword').on('keypress', function(e) { if (e.which===13){e.preventDefault();$('#resep_jml_dosis').focus();} });
    $('#resep_jml_dosis').on('keypress', function(e) { if (e.which===13){e.preventDefault();$('#resep_jml_dosis_obat').focus();} });
    $('#resep_jml_dosis_obat').on('keypress', function(e) { if (e.which===13){e.preventDefault();$('#resep_satuan_obat').focus();} });
    $('#resep_satuan_obat').on('keypress', function(e) { if (e.which===13){e.preventDefault();$('#resep_aturan_pakai').focus();} });
    $('#resep_aturan_pakai').on('keypress', function(e) { if (e.which===13){e.preventDefault();$('#resep_jml_hari').focus();} });
    $('#resep_jml_hari').on('keypress', function(e) { if (e.which===13){e.preventDefault();$('#resep_ket_obat').focus();} });
    $('#resep_ket_obat').on('keypress', function(e) { if (e.which===13){e.preventDefault();addObatResepModal();} });

    // ── Tab switching for resep modal ──
    $('#resep-tab-nav .resep-tab').on('click', function() {
        var targetId = $(this).data('tab');
        $('#resep-tab-nav .resep-tab').removeClass('active');
        $(this).addClass('active');
        $('.resep-tab-content').removeClass('active').hide();
        $('#' + targetId).addClass('active').show();
    });

    // ── PRB: nonaktifkan iter ketika PRB dipilih ──
    function syncResepIterState() {
        var isPrb = $('input[name="resep_jenis_m"]:checked').val() === 'prb';
        var $iterInputs = $('input[name="resep_iter_m"]');
        if (isPrb) {
            $iterInputs.prop('disabled', true);
            $iterInputs.filter('[value="0"]').prop('checked', true);
        } else {
            $iterInputs.prop('disabled', false);
        }
    }
    $('input[name="resep_jenis_m"]').on('change', syncResepIterState);
    syncResepIterState();

    // ── Racikan typeahead init for child obat ──
    if (typeof $.fn.typeahead !== 'undefined') {
        $('#resep_obat_racikan_keyword').typeahead({
            source: function(query, result) {
                $.ajax({
                    url: 'templates/references/getObatByBagianAutoCompleteNoInfoStok',
                    data: { keyword: query, bag: '060101' },
                    dataType: 'json', type: 'POST',
                    success: function(resp) {
                        result($.map(resp, function(item) { return item; }));
                    }
                });
            },
            afterSelect: function(item) {
                var parts = item.split(':');
                $('#resep_kode_brg_racikan').val($.trim(parts[0]));
                $('#resep_obat_racikan_keyword').val($.trim(parts.slice(1).join(':')));
                $('#resep_dosis_racikan').focus();
            }
        });
    }

    // ── Racikan keyboard navigation ──
    $('#resep_nama_racikan').on('keypress', function(e) { if(e.which===13){e.preventDefault();$('#resep_jml_racikan').focus();}});
    $('#resep_jml_racikan').on('keypress', function(e) { if(e.which===13){e.preventDefault();$('#resep_dd_racikan').focus();}});
    $('#resep_dd_racikan').on('keypress', function(e) { if(e.which===13){e.preventDefault();$('#resep_jmldos_racikan').focus();}});
    $('#resep_jmldos_racikan').on('keypress', function(e) { if(e.which===13){e.preventDefault();$('#resep_ket_racikan').focus();}});
    $('#resep_ket_racikan').on('keypress', function(e) { if(e.which===13){e.preventDefault();saveRacikanHeader();}});
    $('#resep_obat_racikan_keyword').on('keypress', function(e) { if(e.which===13){e.preventDefault();$('#resep_dosis_racikan').focus();}});
    $('#resep_dosis_racikan').on('keypress', function(e) { if(e.which===13){e.preventDefault();$('#resep_satuan_racik_child').focus();}});
    $('#resep_ket_racik_child').on('keypress', function(e) { if(e.which===13){e.preventDefault();addRacikanChild();}});

});



function updateResepSelesaiStatus(eResep, drugs) {
    resepEResepStatus = eResep;
    resepHasDrugs     = (drugs && drugs.length > 0);
    var wrap = $('#resep-selesai-wrap');
    var btn = $('#btn-resep-selesai');
    var lbl = $('#resep-status-done');
    if (!resepHasDrugs) {
        wrap.hide();
        return;
    }
    wrap.show();
    if (eResep == 1) {
        btn.hide();
        lbl.show();
    } else {
        btn.show();
        lbl.hide();
    }
}

function renderResepDrugTable(drugs) {
    if (!drugs || drugs.length === 0) {
        $('#resep-drug-tbody').html('<tr><td colspan="5" class="resep-empty-msg">Belum ada obat ditambahkan</td></tr>');
        return;
    }
    var html = '';
    $.each(drugs, function(i, d) {
        if (d.tipe_obat === 'racikan') {
            // Racikan — single row, children inside Nama Obat cell
            var signa = (d.jml_dosis||'') + ' x ' + (d.jml_dosis_obat||'') + '&nbsp;' + escHtml(d.satuan_obat||'') + ' ' + escHtml(d.aturan_pakai||'');
            html += '<tr id="rdr-' + d.id + '" class="racikan-hdr-row">';
            // Nama Obat cell — header + bahan racik list
            html += '<td>' + escHtml(d.nama_brg||'').toUpperCase();
            html += '<div class="racikan-bahan-wrap">';
            html += '<div class="rb-label">bahan racik :</div>';
            if (d.children && d.children.length > 0) {
                $.each(d.children, function(j, c) {
                    html += '<span class="rb-item" id="rdr-' + c.id + '">';
                    html += '<i class="fa fa-times" onclick="deleteObatResepModal(' + c.id + ')" title="Hapus bahan"></i> ';
                    html += escHtml(c.nama_brg||'') + ' &nbsp; (' + (c.jml_pesan||'') + '&nbsp;' + escHtml(c.satuan_obat||'') + ')';
                    html += '<br></span>';
                });
            } else {
                html += '<div class="racikan-bahan-empty">Belum ada bahan obat</div>';
            }
            html += '</div></td>';
            html += '<td>' + signa + '</td>';
            html += '<td style="text-align:center">' + (d.jml_pesan||'-') + '</td>';
            html += '<td>' + escHtml(d.keterangan||'-') + '</td>';
            html += '<td>';
            html += '<button type="button" class="btn-edit-racikan" onclick="editRacikanFromTable(' + d.id + ')" title="Edit nama racikan"><i class="fa fa-pencil"></i></button>';
            html += '<button type="button" class="btn-add-bahan" onclick="addBahanToRacikan(\'' + escHtml(d.kode_brg||'') + '\', \'' + escHtml(d.nama_brg||'') + '\')" title="Tambah bahan obat"><i class="fa fa-plus"></i></button>';
            html += '<button type="button" class="btn-del-drug" onclick="deleteObatResepModal(' + d.id + ')" title="Hapus racikan"><i class="fa fa-trash"></i></button>';
            html += '</td>';
            html += '</tr>';
        } else {
            // Non-racikan row
            var signa = (d.jml_dosis||'') + ' &times; ' + (d.jml_dosis_obat||'') + ' ' + escHtml(d.satuan_obat||'') + ' ' + escHtml(d.aturan_pakai||'');
            html += '<tr id="rdr-' + d.id + '">';
            html += '<td>' + escHtml(d.nama_brg||'') + '</td>';
            html += '<td>' + signa + '</td>';
            html += '<td style="text-align:center">' + (d.jml_pesan||'-') + '</td>';
            html += '<td>' + escHtml(d.keterangan||'-') + '</td>';
            html += '<td><button type="button" class="btn-del-drug" onclick="deleteObatResepModal(' + d.id + ')" title="Hapus"><i class="fa fa-trash"></i></button></td>';
            html += '</tr>';
        }
    });
    $('#resep-drug-tbody').html(html);
}

function deleteObatResepModal(drugId) {
    if (!confirm('Hapus obat ini dari resep?')) return;
    $.ajax({
        url: 'farmasi/E_resep/deleterowresep',
        data: { ID: drugId, kode_pesan_resep: resepKodePesan },
        dataType: 'json',
        type: 'POST',
        success: function(res) {
            if (res.status === 200) {
                $('#rdr-' + drugId).remove();
                if ($('#resep-drug-tbody tr').length === 0) {
                    $('#resep-drug-tbody').html('<tr><td colspan="5" class="resep-empty-msg">Belum ada obat ditambahkan</td></tr>');
                }
                loadResepDrugsModal();
            }
        }
    });
}

function resetResepObatForm() {
    $('#resep_kode_brg').val('');
    $('#resep_obat_keyword').val('').focus();
    $('#resep_jml_dosis').val('');
    $('#resep_jml_dosis_obat').val('');
    $('#resep_satuan_obat').val('Tab');
    $('#resep_aturan_pakai').val('Sesudah Makan');
    $('#resep_jml_hari').val('');
    $('#resep_jml_pesan').val('');
    $('#resep_ket_obat').val('');
}

// ── Racikan Functions ──────────────────────────────────────────────
var _racikanParentKodeBrg = null;
var _racikanEditId = 0; // 0 = insert mode, >0 = update mode (id of existing row)

function saveRacikanHeader() {
    var nama = $.trim($('#resep_nama_racikan').val());
    var qty  = $.trim($('#resep_jml_racikan').val());
    if (!nama) { showResepMsg('err', 'Isi nama racikan'); $('#resep_nama_racikan').focus(); return; }
    if (!qty)  { showResepMsg('err', 'Isi qty racikan'); $('#resep_jml_racikan').focus(); return; }

    ensureResepHeader(function(kodePesan) {
        var fd = {
            submit:                'header',
            id_template:           0,
            id_pesan_resep_detail: 0,
            kode_pesan_resep:      kodePesan,
            no_registrasi:         $('#no_registrasi').val() || _resepNoReg,
            no_kunjungan:          _resepNoKunj,
            kode_brg:              '0',
            nama_brg:              nama,
            jml_pesan:             qty,
            satuan_obat:           $('#resep_satuan_racikan').val() || 'Bks',
            no_mr:                 _resepNoMr,
            jml_dosis:             $('#resep_dd_racikan').val() || '0',
            jml_dosis_obat:        $('#resep_jmldos_racikan').val() || '0',
            aturan_pakai:          $('#resep_anjuran_racikan').val() || 'Sesudah Makan',
            keterangan:            $('#resep_ket_racikan').val(),
            jml_hari:              0,
            tipe_obat:             'racikan',
            tipe_racik:            'dtd',
            parent:                '0'
        };
        $.ajax({
            url: 'farmasi/E_resep/add_resep_obat',
            data: fd, dataType: 'json', type: 'POST',
            success: function(res) {
                if (res.status === 200) {
                    _racikanParentKodeBrg = res.parent;
                    showResepMsg('ok', 'Racikan disimpan. Tambahkan bahan obat.');
                    $('#racikan-child-form').slideDown(200);
                    $('#racikan-child-label').text('[ ' + nama + ' ]');
                    $('#btn-save-racikan-hdr').prop('disabled', true).css('opacity', '.5');
                    loadResepDrugsModal();
                    $('#resep_obat_racikan_keyword').focus();
                } else {
                    showResepMsg('err', res.message || 'Gagal simpan racikan');
                }
            },
            error: function() { showResepMsg('err', 'Gagal menghubungi server'); }
        });
    });
}

function addRacikanChild() {
    if (!_racikanParentKodeBrg) {
        showResepMsg('err', 'Simpan header racikan terlebih dahulu');
        return;
    }
    var namaObat = $.trim($('#resep_obat_racikan_keyword').val());
    var dosis    = $.trim($('#resep_dosis_racikan').val());
    if (!namaObat) { showResepMsg('err', 'Pilih obat racikan'); $('#resep_obat_racikan_keyword').focus(); return; }
    if (!dosis)    { showResepMsg('err', 'Isi dosis obat'); $('#resep_dosis_racikan').focus(); return; }

    ensureResepHeader(function(kodePesan) {
        var fd = {
            submit:                'racikan_detail',
            id_template:           0,
            id_pesan_resep_detail: 0,
            kode_pesan_resep:      kodePesan,
            no_registrasi:         $('#no_registrasi').val() || _resepNoReg,
            no_kunjungan:          _resepNoKunj,
            kode_brg:              $('#resep_kode_brg_racikan').val(),
            nama_brg:              namaObat,
            jml_pesan:             dosis,
            satuan_obat:           $('#resep_satuan_racik_child').val() || '',
            no_mr:                 _resepNoMr,
            jml_dosis:             0,
            jml_dosis_obat:        0,
            aturan_pakai:          '',
            keterangan:            $('#resep_ket_racik_child').val(),
            jml_hari:              0,
            tipe_obat:             'racikan',
            parent:                _racikanParentKodeBrg
        };
        $.ajax({
            url: 'farmasi/E_resep/add_resep_obat',
            data: fd, dataType: 'json', type: 'POST',
            success: function(res) {
                if (res.status === 200) {
                    showResepMsg('ok', 'Bahan obat ditambahkan');
                    resetRacikanChildForm();
                    loadResepDrugsModal();
                } else {
                    showResepMsg('err', res.message || 'Gagal menambah bahan racikan');
                }
            },
            error: function() { showResepMsg('err', 'Gagal menghubungi server'); }
        });
    });
}

function resetRacikanChildForm() {
    $('#resep_kode_brg_racikan').val('');
    $('#resep_obat_racikan_keyword').val('').focus();
    $('#resep_dosis_racikan').val('');
    $('#resep_satuan_racik_child').val('');
    $('#resep_ket_racik_child').val('');
}

function resetRacikanForm() {
    _racikanParentKodeBrg = null;
    _racikanEditId = 0;
    $('#resep_nama_racikan').val('');
    $('#resep_jml_racikan').val('');
    $('#resep_satuan_racikan').val('Bks');
    $('#resep_dd_racikan').val('');
    $('#resep_jmldos_racikan').val('');
    $('#resep_anjuran_racikan').val('Sesudah Makan');
    $('#resep_ket_racikan').val('');
    $('#racikan-child-form').slideUp(200);
    $('#racikan-child-label').text('');
    // Reset buttons: show Simpan, hide Update
    $('#btn-save-racikan-hdr').show().prop('disabled', false).css('opacity', '1');
    $('#btn-update-racikan-hdr').hide();
    resetRacikanChildForm();
}

function editRacikanFromTable(id) {
    // Switch to racikan tab
    $('#resep-tab-nav .resep-tab').removeClass('active');
    $('.resep-tab[data-tab="resep-tab-racikan"]').addClass('active');
    $('.resep-tab-content').removeClass('active').hide();
    $('#resep-tab-racikan').addClass('active').show();
    // Reset form first
    resetRacikanForm();
    // Fetch row data and populate form
    $.getJSON('farmasi/E_resep/getrowresep', { ID: id }, function(res) {
        if (!res) { showResepMsg('err', 'Data tidak ditemukan'); return; }
        _racikanEditId = res.id;
        _racikanParentKodeBrg = res.kode_brg;
        $('#resep_nama_racikan').val(res.nama_brg);
        $('#resep_jml_racikan').val(res.jml_pesan);
        $('#resep_satuan_racikan').val(res.satuan_obat);
        $('#resep_dd_racikan').val(res.jml_dosis);
        $('#resep_jmldos_racikan').val(res.jml_dosis_obat);
        $('#resep_anjuran_racikan').val(res.aturan_pakai);
        $('#resep_ket_racikan').val(res.keterangan);
        // Show update button, hide simpan button
        $('#btn-save-racikan-hdr').hide();
        $('#btn-update-racikan-hdr').show();
        // Show child form ready for adding bahan
        $('#racikan-child-form').slideDown(200);
        $('#racikan-child-label').text('[ ' + res.nama_brg + ' ]');
    });
}

function updateRacikanHeader() {
    var nama = $.trim($('#resep_nama_racikan').val());
    var qty  = $.trim($('#resep_jml_racikan').val());
    if (!nama) { showResepMsg('err', 'Isi nama racikan'); $('#resep_nama_racikan').focus(); return; }
    if (!qty)  { showResepMsg('err', 'Isi qty racikan'); $('#resep_jml_racikan').focus(); return; }

    var fd = {
        submit:                'header',
        id_template:           0,
        id_pesan_resep_detail: _racikanEditId,
        kode_pesan_resep:      resepKodePesan,
        no_registrasi:         $('#no_registrasi').val() || _resepNoReg,
        no_kunjungan:          _resepNoKunj,
        kode_brg:              _racikanParentKodeBrg,
        nama_brg:              nama,
        jml_pesan:             qty,
        satuan_obat:           $('#resep_satuan_racikan').val() || 'Bks',
        no_mr:                 _resepNoMr,
        jml_dosis:             $('#resep_dd_racikan').val() || '0',
        jml_dosis_obat:        $('#resep_jmldos_racikan').val() || '0',
        aturan_pakai:          $('#resep_anjuran_racikan').val() || 'Sesudah Makan',
        keterangan:            $('#resep_ket_racikan').val(),
        jml_hari:              0,
        tipe_obat:             'racikan',
        tipe_racik:            'dtd',
        parent:                '0'
    };
    $.ajax({
        url: 'farmasi/E_resep/add_resep_obat',
        data: fd, dataType: 'json', type: 'POST',
        success: function(res) {
            if (res.status === 200) {
                showResepMsg('ok', 'Racikan berhasil diupdate');
                $('#racikan-child-label').text('[ ' + nama + ' ]');
                loadResepDrugsModal();
            } else {
                showResepMsg('err', res.message || 'Gagal update racikan');
            }
        },
        error: function() { showResepMsg('err', 'Gagal menghubungi server'); }
    });
}

function addBahanToRacikan(kodeBrg, namaRacikan) {
    // Switch to racikan tab
    $('#resep-tab-nav .resep-tab').removeClass('active');
    $('.resep-tab[data-tab="resep-tab-racikan"]').addClass('active');
    $('.resep-tab-content').removeClass('active').hide();
    $('#resep-tab-racikan').addClass('active').show();
    // Set parent and show child form directly
    _racikanParentKodeBrg = kodeBrg;
    $('#resep_nama_racikan').val(namaRacikan);
    $('#btn-save-racikan-hdr').prop('disabled', true).css('opacity', '.5');
    $('#racikan-child-form').slideDown(200);
    $('#racikan-child-label').text('[ ' + namaRacikan + ' ]');
    $('#resep_obat_racikan_keyword').focus();
}

function calcResepJml() {
    var dd   = parseInt($('#resep_jml_dosis').val()) || 0;
    var qty  = parseInt($('#resep_jml_dosis_obat').val()) || 0;
    var hari = parseInt($('#resep_jml_hari').val()) || 0;
    if (dd && qty && hari) $('#resep_jml_pesan').val(dd * qty * hari);
}

function updateResepSoapPanel(drugs) {
    var count = (drugs && drugs.length) ? drugs.length : 0;
    $('#soap-resep-count').text(count + ' obat');
    if (count > 0) {
        $('input[name="ada_resep"][value="Y"]').prop('checked', true);
        $('#fdd_resep_list').show();
        var html = '<table style="width:100%;font-size:12px;margin-top:6px;border-collapse:collapse">';
        $.each(drugs, function(i, d) {
            if (d.tipe_obat === 'racikan') {
                html += '<tr>';
                html += '<td style="padding:3px 8px;width:40%;vertical-align:top"><i class="fa fa-flask" style="color:#0891b2;font-size:10px"></i> <strong>' + escHtml(d.nama_brg||'').toUpperCase() + '</strong> <span style="color:#94a3b8;font-size:10px">[Racikan]</span>';
                if (d.children && d.children.length > 0) {
                    html += '<div style="padding:2px 0 0 12px;margin-top:2px"><div style="font-size:10px;font-style:italic;color:#64748b">bahan racik :</div>';
                    $.each(d.children, function(j, c) {
                        html += '<div style="font-size:11px;color:#475569;line-height:1.8">&bull; ' + escHtml(c.nama_brg||'') + ' (' + (c.jml_pesan||'') + ' ' + escHtml(c.satuan_obat||'') + ')</div>';
                    });
                    html += '</div>';
                } else {
                    html += '<div style="font-size:10px;font-style:italic;color:#94a3b8;padding-left:12px;margin-top:2px">Belum ada bahan obat</div>';
                }
                html += '</td>';
                html += '<td style="padding:3px 8px;color:#475569;vertical-align:top">' + (d.jml_dosis||'') + ' &times; ' + (d.jml_dosis_obat||'') + ' ' + escHtml(d.satuan_obat||'') + ' ' + escHtml(d.aturan_pakai||'') + '</td>';
                html += '<td style="padding:3px 8px;color:#64748b;width:55px;vertical-align:top">' + (d.jml_pesan||'') + '</td>';
                html += '</tr>';
            } else {
                html += '<tr>';
                html += '<td style="padding:3px 8px;width:40%">' + escHtml(d.nama_brg||'') + '</td>';
                html += '<td style="padding:3px 8px;color:#475569">' + (d.jml_dosis||'') + ' &times; ' + (d.jml_dosis_obat||'') + ' ' + escHtml(d.satuan_obat||'') + ' ' + escHtml(d.aturan_pakai||'') + '</td>';
                html += '<td style="padding:3px 8px;color:#64748b;width:55px">' + (d.jml_pesan||'') + '</td>';
                html += '</tr>';
            }
        });
        html += '</table>';
        $('#soap-resep-items').html(html);
    } else {
        $('#soap-resep-count').text('0 obat');
    }
    syncPengobatanTextarea();
}

function prosesResepSelesai() {
    if (!resepKodePesan) { alert('Belum ada resep yang dibuat'); return; }
    if (!confirm('Apakah anda yakin akan memproses resep ini?')) return;

    $('#btn-resep-selesai').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

    $.ajax({
        url: 'farmasi/Farmasi_pesan_resep/process',
        data: {
            no_registrasi:          $('#no_registrasi').val() || _resepNoReg,
            no_kunjungan:           _resepNoKunj,
            no_mr:                  _resepNoMr,
            kode_perusahaan:        _resepKodePerush,
            kode_kelompok:          _resepKodeKelom,
            kode_klas:              _resepKodeKlas,
            kode_profit:            _resepKodeProfit,
            kode_bagian_asal:       _resepKodeBagian,
            kode_dokter:            _resepKodeDokter,
            jenis_resep:            $('input[name="resep_jenis_m"]:checked').val() || 'non_prb',
            resep_iter:             $('input[name="resep_iter_m"]:checked').val() || '0',
            keterangan_pesan_resep: $('#resep_ket_modal').val() || '',
            lokasi_tebus:           '1',
            jumlah_r:               '1',
            kode_pesan_resep:       resepKodePesan,
            e_resep:                1,
            source:                 'SOAP'
        },
        dataType: 'json',
        type: 'POST',
        success: function(res) {
            if (res.status === 200) {
                resepEResepStatus = 1; // tandai selesai di state global
                // Switch to "done" label
                $('#btn-resep-selesai').hide();
                $('#resep-status-done').show();
                loadResepDrugsModal();
            } else {
                alert(res.message || 'Gagal memproses resep');
            }
        },
        error: function() { alert('Gagal menghubungi server'); },
        complete: function() {
            $('#btn-resep-selesai').prop('disabled', false).html('<i class="fa fa-check-circle"></i> Resep Selesai');
        }
    });
}

function showResepMsg(type, msg) {
    var cls = type === 'ok' ? 'msg-ok' : 'msg-err';
    var ico = type === 'ok' ? 'check' : 'exclamation-triangle';
    $('#resep-status-msg').attr('class', cls).html('<i class="fa fa-' + ico + '"></i> ' + msg).show();
    setTimeout(function() { $('#resep-status-msg').fadeOut(); }, 4000);
}

function escHtml(s) {
    return $('<span>').text(String(s)).html();
}

// ── Sync Penunjang + Resep into pl_pengobatan textarea ───────
function syncPengobatanTextarea() {
    var lines = [];

    // 1. Collect Penunjang Medis from cards
    var pmItems = [];
    $('#fdd_pm_cards .fdd-pm-card').each(function() {
        var unitLabel = $(this).find('.fdd-pm-card-unit').text().trim();
        var chips = [];
        $(this).find('.fdd-pm-chip').each(function() {
            chips.push($(this).text().trim());
        });
        if (chips.length > 0) {
            pmItems.push('- ' + unitLabel + ': ' + chips.join(', '));
        } else if (unitLabel) {
            pmItems.push('- ' + unitLabel);
        }
    });
    if (pmItems.length > 0) {
        lines.push('Pemeriksaan Penunjang:');
        lines = lines.concat(pmItems);
    }

    // 2. Collect Resep from drug table
    var resepItems = [];
    $('#resep-drug-tbody tr').each(function() {
        if ($(this).find('.resep-empty-msg').length) return;
        var row = $(this);
        if (row.hasClass('racikan-hdr-row')) {
            var namaRacikan = row.find('td:first').clone().children('.racikan-bahan-wrap').remove().end().text().trim();
            var bahanList = [];
            row.find('.rb-item').each(function() {
                var t = $(this).clone().children('.fa-times').remove().end().text().trim();
                if (t) bahanList.push(t);
            });
            var signa = row.find('td:eq(1)').text().trim();
            var qty = row.find('td:eq(2)').text().trim();
            var entry = '- [Racikan] ' + namaRacikan + ' ' + signa + ' (' + qty + ')';
            if (bahanList.length > 0) {
                entry += '\n  Bahan: ' + bahanList.join('; ');
            }
            resepItems.push(entry);
        } else {
            var nama = row.find('td:eq(0)').text().trim();
            var signa = row.find('td:eq(1)').text().trim();
            var qty = row.find('td:eq(2)').text().trim();
            if (nama) {
                resepItems.push('- ' + nama + ' ' + signa + ' (' + qty + ')');
            }
        }
    });
    if (resepItems.length > 0) {
        if (lines.length > 0) lines.push('');
        lines.push('Resep Obat:');
        lines = lines.concat(resepItems);
    }

    $('#pl_pengobatan').val(lines.join('\n'));
}

/* ── Validasi Simpan SOAP: Ada Penunjang & Ada Resep ────────────────
   Ada Penunjang = Y  → wajib:
     (a) minimal 1 order card di #fdd_pm_cards, DAN
     (b) minimal 1 ceklist pemeriksaan (.fdd-pm-chip) di card manapun
         ATAU minimal 1 pengantar lab sudah terisi (.fdd-pm-detail-label)
   Ada Resep = Y      → wajib ada minimal 1 obat di #soap-resep-items
   ─────────────────────────────────────────────────────────────────── */
$(document).ready(function() {

    $('#btn_save_data').on('click', function(e) {
        var errors   = [];
        var scrollTo = null;

        /* ── 1. Cek Ada Penunjang ── */
        if ($('input[name="ada_penunjang"]:checked').val() === 'Y') {

            var pmCardCount    = $('#fdd_pm_cards .fdd-pm-card').length;
            var pmChipCount    = $('#fdd_pm_cards .fdd-pm-chip').length;
            var pmPengantarCount = $('#fdd_pm_cards .fdd-pm-detail-label').length;

            if (pmCardCount === 0) {
                /* Belum ada order penunjang sama sekali */
                errors.push(
                    '<i class="fa fa-flask" style="margin-right:5px"></i>' +
                    '<b>Penunjang Medis</b>: Anda memilih <b>Ada Penunjang</b> ' +
                    'namun belum ada order pemeriksaan yang ditambahkan. ' +
                    'Klik tombol <b>Tambah</b> untuk membuat order, atau ubah pilihan ke <b>Tidak</b>.'
                );
                if (!scrollTo) scrollTo = $('#fdd_pm_list');

            } else if (pmChipCount === 0 && pmPengantarCount === 0) {
                /* Order sudah dibuat tapi form pengantar belum diisi / ceklist kosong */
                errors.push(
                    '<i class="fa fa-flask" style="margin-right:5px"></i>' +
                    '<b>Penunjang Medis</b>: Order penunjang sudah dibuat, ' +
                    'namun belum ada <b>ceklist pemeriksaan</b> atau <b>ceklist pengantar</b> yang terisi. ' +
                    'Silahkan buka form <b>Pengantar Lab / Rad / Fisio</b> dan pilih minimal 1 item pemeriksaan.'
                );
                if (!scrollTo) scrollTo = $('#fdd_pm_cards');
            }
        }

        /* ── 2. Cek Ada Resep ── */
        if ($('input[name="ada_resep"]:checked').val() === 'Y') {
            if ($('#soap-resep-items table').length === 0) {
                errors.push(
                    '<i class="fa fa-medkit" style="margin-right:5px"></i>' +
                    '<b>Resep Dokter</b>: Anda memilih <b>Ada Resep</b> ' +
                    'namun belum ada obat yang di-order. ' +
                    'Silahkan tambahkan obat melalui modal resep atau ubah pilihan ke <b>Tidak</b>.'
                );
                if (!scrollTo) scrollTo = $('#fdd_resep_list');
            } else if (resepHasDrugs && resepEResepStatus != 1) {
                errors.push(
                    '<i class="fa fa-check-circle" style="margin-right:5px"></i>' +
                    '<b>Resep Dokter</b>: Terdapat obat pada resep namun <b>Resep Selesai</b> belum diklik. ' +
                    'Klik tombol <b style="color:#16a34a">Resep Selesai</b> terlebih dahulu, setelah itu baru simpan data SOAP.'
                );
                if (!scrollTo) scrollTo = $('#resep-selesai-wrap');
            }
        }

        if (errors.length > 0) {
            e.preventDefault();
            $.achtung({
                message : errors.join('<br><br>'),
                timeout : 9,
                className: 'achtungFail'
            });
            if (scrollTo && scrollTo.length) {
                $('html,body').animate({ scrollTop: scrollTo.offset().top - 80 }, 350);
            }
            return false;
        }
    });

});

</script>

