<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Graph_master {

    function get_graph($mod, $params) {
        
        $data = $this->setting_module($params);

        return $data;
        
    }

    function setting_module($params) {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        /*total klaim berdasarkan nomor sep per tahun existing*/
        /*based query*/
        if($params['prefix']==1){
            $query = "SELECT MONTH(created_date) AS bulan, COUNT(id) AS total FROM log WHERE YEAR(created_date)=".date('Y')." GROUP BY MONTH(created_date)";    
            $fields = array('User_Activity'=>'total');
            $title = '<span style="font-size:13.5px">Log History Penggunaan Aplikasi Oleh User Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==2){
            $query = "SELECT TOP 10 MONTH(log.created_date) AS bulan, tmp_mst_menu.name, COUNT(id) as total FROM LOG LEFT JOIN tmp_mst_menu ON tmp_mst_menu.menu_id=log.menu_id WHERE YEAR(log.created_date)=".date('Y')." AND log.modul_id !=0 AND log.menu_id!=0 GROUP BY log.menu_id, MONTH(log.created_date), tmp_mst_menu.name ORDER BY COUNT(id) DESC";   
            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">5 Modul yang sering diakses oleh pengguna</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==3){
            $query = "SELECT TOP 10 content,COUNT(id) AS total FROM LOG GROUP BY content ORDER BY COUNT(id) DESC";  
            $fields = array('Activity' => 'content', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">10 Fungsi yang sering diakses oleh pengguna</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        /*line*/
        if($params['prefix']==111){
            $query = "SELECT MONTH(tgl_jam_masuk) AS bulan, COUNT(no_registrasi) AS total 
                        FROM tc_registrasi 
                        WHERE YEAR(tgl_jam_masuk)=".date('Y')." GROUP BY MONTH(tgl_jam_masuk)"; 
            $fields = array('Total Pasien'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Pendaftaran Pasien Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==51){
            $query = "SELECT MONTH(tgl_jam_poli) AS bulan, COUNT(kode_poli) AS total 
                        FROM pl_tc_poli 
                        WHERE YEAR(tgl_jam_poli)=".date('Y')." AND status_batal is null GROUP BY MONTH(tgl_jam_poli) "; 
            $fields = array('Total Pasien'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Kunjungan Poli Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==251){
            $query = "SELECT MONTH(tgl_masuk) AS bulan, COUNT(kode_ri) AS total 
                        FROM ri_tc_rawatinap 
                        WHERE YEAR(tgl_masuk)=".date('Y')." GROUP BY MONTH(tgl_masuk) ";    
            $fields = array('Total Pasien'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Kunjungan Pasien Rawat Inap  Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }


        /*pie chart*/
        if($params['prefix']==112){
            $query = "SELECT substring(a.kode_bagian, 1, 2) as substr,a.kode_bagian, a.nama_bagian as bagian,COUNT(b.no_registrasi) AS total 
                        FROM tc_registrasi b
                        left join mt_bagian a on a.kode_bagian=b.kode_bagian_masuk 
                        WHERE CAST(b.tgl_jam_masuk as DATE) = '".date('Y-m-d')."'
                        GROUP BY a.kode_bagian, a.nama_bagian 
                        ORDER BY COUNT(b.no_registrasi) DESC";  
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $getData[$value['substr']][] = $value;
            }

            foreach ($getData as $k => $v) {
                switch ($k) {
                    case '01':
                        # code...
                        $title = 'Rawat Jalan';
                        break;
                    case '02':
                        # code...
                        $title = 'IGD';
                        break;
                    case '03':
                        # code...
                        $title = 'Rawat Inap';
                        break;
                    case '05':
                        # code...
                        $title = 'Penunjang Medis';
                        break;
                }
                $data[] = array( 'name' => $title, 'total' => array_sum(array_column($v,'total')) );
            }
            
            //echo '<pre>';print_r($data);die;

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">Persentase Jumlah Pengunjung Berdasarkan Instalasi</span>';
            $subtitle = 'Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).'';
        }

        if($params['prefix']==52){
            $query = "SELECT substring(a.kode_bagian, 1, 2) as substr,a.kode_bagian, a.nama_bagian as bagian,COUNT(b.kode_poli) AS total 
                        FROM pl_tc_poli b
                        left join mt_bagian a on a.kode_bagian=b.kode_bagian 
                        WHERE YEAR(b.tgl_jam_poli) = ".date('Y')." and month(b.tgl_jam_poli)=".date('m')." and day(b.tgl_jam_poli)=".date('d')." AND b.status_batal IS NULL
                        GROUP BY a.kode_bagian, a.nama_bagian 
                        ORDER BY COUNT(b.kode_poli) DESC";  
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['bagian'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">Persentase Jumlah Pasien Berdasarkan Poli/Klinik</span>';
            $subtitle = 'Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).'';
        }

        if($params['prefix']==252){
            $query = "SELECT substring(a.kode_bagian, 1, 2) as substr,a.kode_bagian, a.nama_bagian as bagian,COUNT(b.kode_ri) AS total 
                        FROM ri_tc_rawatinap b
                        left join mt_bagian a on a.kode_bagian=b.bag_pas
                        WHERE YEAR(b.tgl_masuk) = ".date('Y')." and month(b.tgl_masuk)=".date('m')."
                        GROUP BY a.kode_bagian, a.nama_bagian 
                        ORDER BY COUNT(b.kode_ri) DESC";    
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['bagian'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">Persentase Jumlah Pasien Rawat Inap Berdasarkan Ruangan</span>';
            $subtitle = 'Data Pasien RI Bulan '.$CI->tanggal->getBulan(date('m')).'';
        }

        /*table*/
        if($params['prefix']==113){
            $query = "SELECT a.nama_bagian as bagian,COUNT(b.no_registrasi) AS total 
                        FROM tc_registrasi b
                        left join mt_bagian a on a.kode_bagian=b.kode_bagian_masuk 
                        WHERE CAST(b.tgl_jam_masuk as DATE) = '".date('Y-m-d')."'
                        GROUP BY a.nama_bagian 
                        ORDER BY COUNT(b.no_registrasi) DESC";  
            $fields = array('Nama Bagian' => 'bagian', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">Total Pasien Terdaftar Hari Ini<br><small style="font-size:12px !important">Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==53){
            $query = "SELECT a.nama_bagian as bagian,COUNT(b.no_registrasi) AS total 
                        FROM tc_registrasi b
                        left join mt_bagian a on a.kode_bagian=b.kode_bagian_masuk 
                        WHERE CAST(b.tgl_jam_masuk as DATE) = '".date('Y-m-d')."' AND SUBSTRING(b.kode_bagian_masuk, 1,2) = '01' and b.kode_bagian_masuk != '010901'
                        GROUP BY a.nama_bagian 
                        ORDER BY COUNT(b.no_registrasi) DESC";  
            $fields = array('Nama Bagian' => 'bagian', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">Total Kunjungan Pasien Poliklinik Spesialis Rawat Jalan <br><small style="font-size:12px !important">Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==54){
            $query = "SELECT a.nama_pegawai as nama_dokter, COUNT(b.no_registrasi) AS total 
                        FROM tc_registrasi b
                        left join mt_karyawan a on a.kode_dokter=b.kode_dokter
                        WHERE CAST(b.tgl_jam_masuk as DATE) = '".date('Y-m-d')."' AND a.nama_pegawai is not null AND SUBSTRING(b.kode_bagian_masuk, 1,2) = '01' and b.kode_bagian_masuk != '010901'
                        GROUP BY a.nama_pegawai 
                        ORDER BY COUNT(b.no_registrasi) DESC";  
            $fields = array('Nama Dokter' => 'nama_dokter', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">Total Kunjungan Pasien RJ Berdasarkan DPJP <br><small style="font-size:12px !important">Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==55){
            $query = "SELECT 'Pasien Batal' as label, COUNT(b.no_registrasi) AS total 
                        FROM tc_registrasi b
                        WHERE CAST(b.tgl_jam_masuk as DATE) = '".date('Y-m-d')."' AND b.status_batal = 1 
                        ORDER BY COUNT(b.no_registrasi) DESC";  
            $fields = array('Jumlah Pasien Batal' => 'label', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">Jumlah Pasien Batal Berkunjung Hari Ini<br><small style="font-size:12px !important">Tanggal '.$CI->tanggal->formatDate(date('Y-m-d')).' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }
        
        // modul purchasing line chart
        if($params['prefix']==321){
            $query = "SELECT MONTH(tgl_permohonan) AS bulan, COUNT(kode_permohonan) AS total FROM tc_permohonan WHERE YEAR(tgl_permohonan)=".date('Y')." GROUP BY MONTH(tgl_permohonan)";   
            $fields = array('Total_Permintaan_Medis'=>'total');
            $title = '<span style="font-size:13.5px">Total Permintaan Barang Medis Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // modul purchasing table chart
        if($params['prefix']==323){
            $query = "select t_m.bulan, CAST(total_m as INT) as total_m, CAST(total_nm as INT) as total_nm from (

                select MONTH(b.tgl_penerimaan) as bulan, 
                SUM((a.harga_net * a.jumlah_kirim_decimal)) as total_m
                from tc_penerimaan_barang_detail a 
                left join tc_penerimaan_barang b on b.id_penerimaan=a.id_penerimaan
                where YEAR(b.tgl_penerimaan)=".date('Y')." GROUP BY month(b.tgl_penerimaan) 
                ) as t_m
                LEFT JOIN (
                select MONTH(b.tgl_penerimaan) as bulan, 
                SUM((a.harga_net * a.jumlah_kirim_decimal)) as total_nm
                from tc_penerimaan_barang_nm_detail a 
                left join tc_penerimaan_barang_nm b on b.id_penerimaan=a.id_penerimaan
                where YEAR(b.tgl_penerimaan)=".date('Y')." GROUP BY month(b.tgl_penerimaan)
                ) as t_nm on t_nm.bulan = t_m.bulan
                order by t_m.bulan ASC
                ";
                        
            $fields = array('Bulan' => 'bulan', 'Total_Medis' => 'total_m', 'Total_Non_Medis' => 'total_nm');
            $title = '<span style="font-size:13.5px">Total Pembelian Barang Berdasarkan Data Penerimaan Barang Tahun '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==324){
            $query = "select e.namasupplier as supplier, MONTH(b.tgl_penerimaan) as bulan, SUM((a.harga_net * a.jumlah_kirim_decimal)) as total_format_money
                        from tc_penerimaan_barang_detail a 
                        left join mt_barang c on c.kode_brg=a.kode_brg
                        left join tc_penerimaan_barang b on b.id_penerimaan=a.id_penerimaan
                        left join tc_po d on d.id_tc_po=b.id_tc_po
                        left join mt_supplier e on e.kodesupplier=d.kodesupplier
                        where YEAR(b.tgl_penerimaan)=".date('Y')."
                        GROUP BY e.kodesupplier, e.namasupplier, MONTH(b.tgl_penerimaan)
                        ORDER BY SUM((a.harga_net * a.jumlah_kirim_decimal)) DESC";
                        
            $fields = array('Supplier' => 'supplier', 'Total' => 'total_format_money', 'Bulan' => 'bulan');
            $title = '<span style="font-size:13.5px">Total Pembelian <b>Barang Medis</b> Berdasarkan Supplier<br>Tahun <b>'.date('Y').'</b> Bulan <b>'.$CI->tanggal->getBulan(date('m')).'</b></span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==326){
            $query = "select e.namasupplier as supplier, MONTH(b.tgl_penerimaan) as bulan, SUM((a.harga_net * a.jumlah_kirim_decimal)) as total_format_money
                        from tc_penerimaan_barang_nm_detail a 
                        left join mt_barang_nm c on c.kode_brg=a.kode_brg
                        left join tc_penerimaan_barang_nm b on b.id_penerimaan=a.id_penerimaan
                        left join tc_po_nm d on d.id_tc_po=b.id_tc_po
                        left join mt_supplier e on e.kodesupplier=d.kodesupplier
                        where YEAR(b.tgl_penerimaan)=".date('Y')." 
                        GROUP BY e.kodesupplier, e.namasupplier, MONTH(b.tgl_penerimaan)
                        ORDER BY SUM((a.harga_net * a.jumlah_kirim_decimal)) DESC";
                        
            $fields = array('Supplier' => 'supplier', 'Total' => 'total_format_money', 'Bulan' => 'bulan');
            $title = '<span style="font-size:13.5px">Total Pembelian <b>Barang Non Medis</b> Berdasarkan Supplier<br>Tahun <b>'.date('Y').'</b> Bulan <b>'.$CI->tanggal->getBulan(date('m')).'</b></span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // modul purchasing pie chart
        if($params['prefix']==322){
            $query = "select top 10 e.namasupplier as supplier, SUM((a.harga_net * a.jumlah_kirim_decimal)) as total
                        from tc_penerimaan_barang_detail a 
                        left join mt_barang c on c.kode_brg=a.kode_brg
                        left join tc_penerimaan_barang b on b.id_penerimaan=a.id_penerimaan
                        left join tc_po d on d.id_tc_po=b.id_tc_po
                        left join mt_supplier e on e.kodesupplier=d.kodesupplier
                        where YEAR(b.tgl_penerimaan)=".date('Y')." and MONTH(b.tgl_penerimaan)=".date('m')." 
                        GROUP BY e.kodesupplier, e.namasupplier
                        ORDER BY SUM((a.harga_net * a.jumlah_kirim_decimal)) DESC";
                            
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['supplier'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">10 Top Supplier Barang Medis Berdasarkan PO </span>';
            $subtitle = 'Data PO Tahun '.date('Y').'';
        }

        if($params['prefix']==325){
        $query = "select top 20 e.namasupplier as supplier, SUM((a.harga_net * a.jumlah_kirim_decimal)) as total 
                    from tc_penerimaan_barang_nm_detail a 
                    left join mt_barang_nm c on c.kode_brg=a.kode_brg
                    left join tc_penerimaan_barang_nm b on b.id_penerimaan=a.id_penerimaan
                    left join tc_po_nm d on d.id_tc_po=b.id_tc_po
                    left join mt_supplier e on e.kodesupplier=d.kodesupplier
                    where YEAR(b.tgl_penerimaan)=".date('Y')." and MONTH(b.tgl_penerimaan)=".date('m')." 
                    GROUP BY e.kodesupplier, e.namasupplier
                    ORDER BY SUM((a.harga_net * a.jumlah_kirim_decimal)) DESC";
                            
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['supplier'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">10 Top Supplier Barang Non Medis Berdasarkan PO </span>';
            $subtitle = 'Data PO Tahun '.date('Y').'';
        }

        if($params['prefix']==328){
            $query = "select top 20 c.nama_brg as nama_brg, c.satuan_kecil, CAST(SUM(a.jumlah_kirim_decimal) as INT) as jumlah_order, SUM((a.harga_net * a.jumlah_kirim_decimal)) as total_format_money
                        from tc_penerimaan_barang_detail a 
                        left join mt_barang c on c.kode_brg=a.kode_brg
                        left join tc_penerimaan_barang b on b.id_penerimaan=a.id_penerimaan
                        left join tc_po d on d.id_tc_po=b.id_tc_po
                        left join mt_supplier e on e.kodesupplier=d.kodesupplier
                        where YEAR(b.tgl_penerimaan)=".date('Y')." and MONTH(b.tgl_penerimaan)=".date('m')." 
                        GROUP BY c.nama_brg, c.satuan_kecil
                        ORDER BY SUM((a.jumlah_kirim_decimal)) DESC";
                        
            $fields = array('Nama_Barang' => 'nama_brg', 'Jumlah_Brg' => 'jumlah_order', 'Satuan' => 'satuan_kecil', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">20 Penerimaan <b>Barang Medis</b> Terbanyak Berdasarkan Nama Barang<br>Tahun <b>'.date('Y').'</b> Bulan <b>'.$CI->tanggal->getBulan(date('m')).'</b></span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==327){
            $query = "select top 20 c.nama_brg as nama_brg, c.satuan_kecil, CAST(SUM(a.jumlah_kirim_decimal) as INT) as jumlah_order, SUM((a.harga_net * a.jumlah_kirim_decimal)) as total_format_money
                        from tc_penerimaan_barang_nm_detail a 
                        left join mt_barang_nm c on c.kode_brg=a.kode_brg
                        left join tc_penerimaan_barang_nm b on b.id_penerimaan=a.id_penerimaan
                        left join tc_po_nm d on d.id_tc_po=b.id_tc_po
                        left join mt_supplier e on e.kodesupplier=d.kodesupplier
                        where YEAR(b.tgl_penerimaan)=".date('Y')." and MONTH(b.tgl_penerimaan)=".date('m')." 
                        GROUP BY c.nama_brg, c.satuan_kecil
                        ORDER BY SUM((a.jumlah_kirim_decimal)) DESC";
                        
            $fields = array('Nama_Barang' => 'nama_brg', 'Jumlah_Brg' => 'jumlah_order', 'Satuan' => 'satuan_kecil', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">20 Penerimaan <b>Barang Non Medis</b> Terbanyak Berdasarkan Nama Barang<br>Tahun <b>'.date('Y').'</b> Bulan <b>'.$CI->tanggal->getBulan(date('m')).'</b></span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }







        // MODUL ADM PASIEN //
        // modul purchasing line chart
        if($params['prefix']==201){
            $query = "SELECT MONTH(tgl_transaksi) AS bulan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2)) AS total FROM tc_trans_pelayanan WHERE no_registrasi in (SELECT no_registrasi from tc_registrasi WHERE YEAR(tgl_jam_keluar) = ".date('Y')." AND MONTH(tgl_jam_keluar) <= ".date('m')." AND (status_batal IS NULL OR status_batal = 0) ) AND YEAR(tgl_transaksi) = ".date('Y')." GROUP BY MONTH(tgl_transaksi)";   
            $fields = array('Total_Pendapatan_RS'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Pendapatan Rumah Sakit Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();

        }

        // modul purchasing pie chart
        if($params['prefix']==202){
            $query = "SELECT TOP 10 a.nama_perusahaan as nama_perusahaan, SUM(b.bill) AS total 
                        FROM tc_trans_kasir b
                        left join mt_perusahaan a on a.kode_perusahaan=b.kode_perusahaan
                        WHERE YEAR(b.tgl_jam) = ".date('Y')." and month(b.tgl_jam)=".date('m')."
                        GROUP BY a.nama_perusahaan ORDER BY SUM(b.bill) DESC";  
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => (!empty($value['nama_perusahaan']))?$value['nama_perusahaan']:'UMUM', 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">10 Perusahaan Penjamin Pasien Terbesar</span>';
            $subtitle = 'Pendapatan RS Berdasarkan Perusahaan Penjamin Tahun '.date('Y').'';
        }
        
        // modul purchasing table chart
        if($params['prefix']==203){
            $query = "SELECT MONTH(tgl_transaksi) AS bulan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2)) AS total_format_money FROM tc_trans_pelayanan WHERE no_registrasi in (SELECT no_registrasi from tc_registrasi WHERE YEAR(tgl_jam_keluar) = ".date('Y')." AND MONTH(tgl_jam_keluar) <= ".date('m')." AND (status_batal IS NULL OR status_batal = 0) ) AND YEAR(tgl_transaksi) = ".date('Y')." GROUP BY MONTH(tgl_transaksi)";      
 
            $fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">Total Pendapatan RS Tahun '.date('Y').' s/d Bulan '.$CI->tanggal->getBulan(date('m')).' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // MODUL CASEMIX
        // modul purchasing line chart
        if($params['prefix']==341){
            $title = '<span style="font-size:13.5px">Total Dokumen Pengajuan Klaim BPJS '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';

            // data 1
            $query = "SELECT MONTH(tgl_transaksi_kasir) AS bulan, COUNT(csm_dokumen_klaim.no_registrasi) AS total FROM csm_dokumen_klaim INNER JOIN csm_reg_pasien ON csm_reg_pasien.no_registrasi=csm_dokumen_klaim.no_registrasi WHERE YEAR(tgl_transaksi_kasir)=".date('Y')." AND kode_perusahaan=120 GROUP BY MONTH(tgl_transaksi_kasir)";  
            $fields[0] = array('Total_Dokumen_Klaim'=>'total');
            $data[0] = $db->query($query)->result_array();

            // data2
            $query2 = "SELECT MONTH(tgl_jam_masuk) AS bulan, COUNT(no_registrasi) AS total FROM tc_registrasi WHERE YEAR(tgl_jam_masuk)=".date('Y')." AND kode_perusahaan=120 GROUP BY MONTH(tgl_jam_masuk)";   
            $fields[1] = array('Total_Pasien_BPJS'=>'total');
            $data[1] = $db->query($query2)->result_array();

        }

        // modul casemix pie chart
        if($params['prefix']==342){
            $query = "SELECT TOP 5 a.nama_perusahaan as nama_perusahaan, SUM(b.bill) AS total 
                        FROM tc_trans_kasir b
                        left join mt_perusahaan a on a.kode_perusahaan=b.kode_perusahaan
                        WHERE YEAR(b.tgl_jam) = ".date('Y')." and month(b.tgl_jam)=".date('m')."
                        GROUP BY a.nama_perusahaan ORDER BY SUM(b.bill) DESC";  
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => (!empty($value['nama_perusahaan']))?$value['nama_perusahaan']:'UMUM', 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">5 Perusahaan Penjamin Pasien Terbesar</span>';
            $subtitle = 'Persentase Pendapatan RS Berdasarkan Perusahaan Penjamin Pasien Tahun '.date('Y').'';
        }
        
        // modul casemix table chart
        if($params['prefix']==343){
            $query = "SELECT month(a.tgl_transaksi_kasir) as bulan, SUM(a.csm_dk_total_klaim) AS total_format_money 
                        FROM csm_dokumen_klaim a
                        WHERE YEAR(a.tgl_transaksi_kasir) = ".date('Y')."
                        GROUP BY month(a.tgl_transaksi_kasir) ORDER BY month(a.tgl_transaksi_kasir) ASC";   
            $fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">Total Pengajuan Klaim s/d Bulan '.$CI->tanggal->getBulan(date('m')).' '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==344){
            $query = "SELECT month(a.tgl_transaksi_kasir) as bulan, COUNT(a.no_registrasi) AS total_format_money 
                        FROM csm_dokumen_klaim a
                        WHERE YEAR(a.tgl_transaksi_kasir) = ".date('Y')."
                        GROUP BY month(a.tgl_transaksi_kasir) ORDER BY month(a.tgl_transaksi_kasir) ASC";   
            $fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">Total Dokumen Klaim  s/d Bulan '.$CI->tanggal->getBulan(date('m')).' '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==345){
            $query = "SELECT created_by as petugas, COUNT(a.no_registrasi) AS total_format_money 
                        FROM csm_dokumen_klaim a
                        WHERE YEAR(a.tgl_transaksi_kasir) = ".date('Y')." AND MONTH(a.tgl_transaksi_kasir) = ".date('m')."
                        GROUP BY created_by ORDER BY created_by ASC";   
            $fields = array('Nama_Petugas' => 'petugas', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">Total Costing Bulan '.$CI->tanggal->getBulan(date('m')).' '.date('Y').'</span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // modul farmasi
        // line chart
        if($params['prefix']==241){
            $title = '<span style="font-size:13.5px">Grafik Resep Farmasi Berdasarkan Jenisnya tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';

            // RJ
            $query = "SELECT MONTH(tgl_trans) AS bulan, COUNT(fr_tc_far.kode_trans_far) AS total FROM fr_tc_far  WHERE YEAR(tgl_trans)=".date('Y')." AND kode_profit=2000 GROUP BY MONTH(tgl_trans)";   
            $fields[0] = array('Total_Resep_RJ'=>'total');
            $data[0] = $db->query($query)->result_array();

            // RI
            $query2 = "SELECT MONTH(tgl_trans) AS bulan, COUNT(fr_tc_far.kode_trans_far) AS total FROM fr_tc_far  WHERE YEAR(tgl_trans)=".date('Y')." AND kode_profit=1000 GROUP BY MONTH(tgl_trans)";  
            $fields[1] = array('Total_Resep_RI'=>'total');
            $data[1] = $db->query($query2)->result_array();

            // Bebas
            $query3 = "SELECT MONTH(tgl_trans) AS bulan, COUNT(fr_tc_far.kode_trans_far) AS total FROM fr_tc_far  WHERE YEAR(tgl_trans)=".date('Y')." AND kode_profit=4000 GROUP BY MONTH(tgl_trans)";  
            $fields[2] = array('Total_Resep_Bebas'=>'total');
            $data[2] = $db->query($query3)->result_array();

            // Luar
            $query4 = "SELECT MONTH(tgl_trans) AS bulan, COUNT(fr_tc_far.kode_trans_far) AS total FROM fr_tc_far  WHERE YEAR(tgl_trans)=".date('Y')." AND kode_profit=3000 GROUP BY MONTH(tgl_trans)";  
            $fields[3] = array('Total_Resep_Luar'=>'total');
            $data[3] = $db->query($query4)->result_array();


        }
        // table
        if($params['prefix']==243){
            $query = "SELECT TOP 10 c.nama_brg, COUNT(a.kode_brg) AS total_format_money 
                        FROM fr_tc_far_detail a
                        LEFT JOIN mt_barang c ON c.kode_brg=a.kode_brg
                        LEFT JOIN fr_tc_far b ON b.kode_trans_far=a.kode_trans_far
                        WHERE YEAR(b.tgl_trans) = ".date('Y')."
                        GROUP BY c.nama_brg, a.kode_brg ORDER BY COUNT(a.kode_brg) DESC";   
            $fields = array('Nama_Obat' => 'nama_brg', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">10 Jenis Obat terbanyak berdasarkan resep biasa <br>Tahun '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }
        // pie
        if($params['prefix']==242){
            $query = "SELECT a.nama_pelayanan as jenis_resep, COUNT(b.kode_trans_far) AS total 
                        FROM fr_tc_far b
                        left join fr_mt_profit_margin a on a.kode_profit=b.kode_profit
                        WHERE YEAR(b.tgl_trans) = ".date('Y')." and month(b.tgl_trans)=".date('m')."
                        GROUP BY a.kode_profit, a.nama_pelayanan ORDER BY COUNT(b.kode_trans_far) DESC";    
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['jenis_resep'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">Persentase Resep Farmasi Berdasarkan Jenis Resep Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
        }
        // table
        if($params['prefix']==244){
            $query = "select MONTH(tgl_transaksi)as bulan, (SUM(bill_rs) + SUM(lain_lain)) as total_format_money
                        from tc_trans_pelayanan
                        where kode_trans_far is not null and YEAR(tgl_transaksi)=".date('Y')." and status_selesai=3
                        GROUP BY MONTH(tgl_transaksi)"; 
            $fields = array('Bulan' => 'bulan', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">Total Pendapatan Farmasi  s/d Bulan '.$CI->tanggal->getBulan(date('m')).' <br> Tahun '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }
        // table
        if($params['prefix']==245){
            $query = "SELECT TOP 10 c.nama_bagian, COUNT(b.kode_trans_far) AS total_format_money 
                        FROM fr_tc_far b
                        LEFT JOIN mt_bagian c ON c.kode_bagian=b.kode_bagian_asal
                        WHERE YEAR(b.tgl_trans) = ".date('Y')." 
                        GROUP BY c.nama_bagian ORDER BY COUNT(b.kode_trans_far) DESC";  
            $fields = array('Unit' => 'nama_bagian', 'Total' => 'total_format_money');
            $title = '<span style="font-size:13.5px">10 Unit/Poli Terbanyak Membuat Resep <br>Tahun '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }


        // modul laboratorium
        if($params['prefix']==261){
            $query = "SELECT MONTH(tgl_daftar) AS bulan, COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang 
                        WHERE YEAR(tgl_daftar)=".date('Y')." AND kode_bagian = '050101' GROUP BY MONTH(tgl_daftar)";   
            $fields = array('Kunjungan Pasien Lab'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Kunjungan Pasien Lab Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==262){
            $query = "SELECT TOP 10 b.nama_tindakan, COUNT(b.kode_tarif) AS total 
                        FROM pm_hasilpasien_v b
                        WHERE YEAR(b.tgl_periksa) = ".date('Y')." AND kode_bagian = '050101'
                        GROUP BY b.kode_tarif, b.nama_tindakan ORDER BY COUNT(b.kode_tarif) DESC";  
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['nama_tindakan'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">10 Jenis Pemeriksaan Lab Terbanyak Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
        }

        if($params['prefix']==263){
            $title = '<span style="font-size:18px; font-weight: bold">Rekapitulasi Data Pasien Laboratorium Hari Ini, '.date('D, d/m/Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            // query kunjungan pasien hari ini
            $query_1 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang a
                        LEFT JOIN tc_kunjungan b on b.no_kunjungan=a.no_kunjungan
                        LEFT JOIN tc_registrasi c on c.no_registrasi=b.no_registrasi
                        WHERE CAST(tgl_daftar as DATE)='".date('Y-m-d')."' AND kode_bagian = '050101' AND c.kode_perusahaan=120 GROUP BY MONTH(tgl_daftar)";
            $exc_qry_1 = $db->query($query_1)->row();   
            $fields['kunjungan_bpjs'] = array('flag' => 'Kunjungan Pasien BPJS ', 'total' => $exc_qry_1->total);

            $query_4 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang a
                        LEFT JOIN tc_kunjungan b on b.no_kunjungan=a.no_kunjungan
                        LEFT JOIN tc_registrasi c on c.no_registrasi=b.no_registrasi
                        WHERE CAST(tgl_daftar as DATE)='".date('Y-m-d')."' AND kode_bagian = '050101' AND c.kode_perusahaan != 120 GROUP BY MONTH(tgl_daftar)";
            $exc_qry_4 = $db->query($query_4)->row();   
            $fields['kunjungan'] = array('flag' => 'Kunjungan Pasien Umum ', 'total' => $exc_qry_4->total);

            // pemeriksaan
            $query_2 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang 
                        WHERE CAST(tgl_periksa as DATE)='".date('Y-m-d')."' AND kode_bagian = '050101' GROUP BY MONTH(tgl_periksa)";
            $exc_qry_2 = $db->query($query_2)->row();   
            $fields['pemeriksaan'] = array('flag' => 'Pengambilan Sampel', 'total' => $exc_qry_2->total);

            // isi hasil
            $query_3 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang 
                        WHERE CAST(tgl_isihasil as DATE)='".date('Y-m-d')."' AND kode_bagian = '050101' GROUP BY MONTH(tgl_isihasil)";
            $exc_qry_3 = $db->query($query_3)->row();   
            $fields['isi_hasil'] = array('flag' => 'Pengisian Hasil Pemeriksaan', 'total' => $exc_qry_3->total);

            // isi hasil
            $query_5 = "SELECT SUM(bill_rs) AS total 
                        FROM tc_trans_pelayanan 
                        WHERE CAST(tgl_transaksi as DATE)='".date('Y-m-d')."' and kode_bagian='050101'";
            $exc_qry_5 = $db->query($query_5)->row();   
            $fields['total_pendapatan'] = array('flag' => 'Total Pendapatan Lab', 'total' => $exc_qry_5->total);
            
            /*excecute query*/
            $data = $fields;
        }

        if($params['prefix']==264){
            $query = "SELECT TOP 20 b.nama_tindakan, COUNT(b.kode_tarif) AS total 
                        FROM pm_hasilpasien_v b
                        WHERE YEAR(b.tgl_periksa) = ".date('Y')." AND kode_bagian = '050101'
                        GROUP BY b.kode_tarif, b.nama_tindakan ORDER BY COUNT(b.kode_tarif) DESC";  
            $fields = array('Nama_Pemeriksaan' => 'nama_tindakan', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">20 Jenis Pemeriksaan Lab Terbanyak  s/d Bulan '.$CI->tanggal->getBulan(date('m')).' Tahun '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // modul laboratorium
        if($params['prefix']==291){
            $query = "SELECT MONTH(tgl_daftar) AS bulan, COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang 
                        WHERE YEAR(tgl_daftar)=".date('Y')." AND kode_bagian = '050201' GROUP BY MONTH(tgl_daftar)";   
            $fields = array('Kunjungan Pasien Radiologi'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Kunjungan Pasien Radiologi Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==292){
            $query = "SELECT TOP 10 b.nama_tindakan, COUNT(b.kode_tarif) AS total 
                        FROM pm_hasilpasien_v b
                        WHERE YEAR(b.tgl_periksa) = ".date('Y')." AND kode_bagian = '050201'
                        GROUP BY b.kode_tarif, b.nama_tindakan ORDER BY COUNT(b.kode_tarif) DESC";  
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['nama_tindakan'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">10 Jenis Pemeriksaan Radiologi Terbanyak Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
        }

        if($params['prefix']==293){
            $title = '<span style="font-size:18px; font-weight: bold">Rekapitulasi Data Pasien Radiologi Hari Ini, '.date('D, d/m/Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            // query kunjungan pasien hari ini
            $query_1 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang a
                        LEFT JOIN tc_kunjungan b on b.no_kunjungan=a.no_kunjungan
                        LEFT JOIN tc_registrasi c on c.no_registrasi=b.no_registrasi
                        WHERE CAST(tgl_daftar as DATE)='".date('Y-m-d')."' AND kode_bagian = '050201' AND c.kode_perusahaan=120 GROUP BY MONTH(tgl_daftar)";
            $exc_qry_1 = $db->query($query_1)->row();   
            $fields['kunjungan_bpjs'] = array('flag' => 'Kunjungan Pasien BPJS ', 'total' => $exc_qry_1->total);

            $query_4 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang a
                        LEFT JOIN tc_kunjungan b on b.no_kunjungan=a.no_kunjungan
                        LEFT JOIN tc_registrasi c on c.no_registrasi=b.no_registrasi
                        WHERE CAST(tgl_daftar as DATE)='".date('Y-m-d')."' AND kode_bagian = '050201' AND c.kode_perusahaan != 120 GROUP BY MONTH(tgl_daftar)";
            $exc_qry_4 = $db->query($query_4)->row();   
            $fields['kunjungan'] = array('flag' => 'Kunjungan Pasien Umum ', 'total' => $exc_qry_4->total);

            // pemeriksaan
            $query_2 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang 
                        WHERE CAST(tgl_periksa as DATE)='".date('Y-m-d')."' AND kode_bagian = '050201' GROUP BY MONTH(tgl_periksa)";
            $exc_qry_2 = $db->query($query_2)->row();   
            $fields['pemeriksaan'] = array('flag' => 'Pengambilan Sampel', 'total' => $exc_qry_2->total);

            // isi hasil
            $query_3 = "SELECT COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang 
                        WHERE CAST(tgl_isihasil as DATE)='".date('Y-m-d')."' AND kode_bagian = '050201' GROUP BY MONTH(tgl_isihasil)";
            $exc_qry_3 = $db->query($query_3)->row();   
            $fields['isi_hasil'] = array('flag' => 'Pengisian Hasil Pemeriksaan', 'total' => $exc_qry_3->total);

            // isi hasil
            $query_5 = "SELECT SUM(bill_rs) AS total 
                        FROM tc_trans_pelayanan 
                        WHERE CAST(tgl_transaksi as DATE)='".date('Y-m-d')."' AND kode_bagian = '050201' and kode_bagian='050101'";
            $exc_qry_5 = $db->query($query_5)->row();   
            $fields['total_pendapatan'] = array('flag' => 'Total Pendapatan Radiologi', 'total' => $exc_qry_5->total);
            
            /*excecute query*/
            $data = $fields;
        }

        if($params['prefix']==294){
            $query = "SELECT TOP 20 b.nama_tindakan, COUNT(b.kode_tarif) AS total 
                        FROM pm_hasilpasien_v b
                        WHERE YEAR(b.tgl_periksa) = ".date('Y')." AND kode_bagian = '050201'
                        GROUP BY b.kode_tarif, b.nama_tindakan ORDER BY COUNT(b.kode_tarif) DESC";  
            $fields = array('Nama_Pemeriksaan' => 'nama_tindakan', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">20 Jenis Pemeriksaan Radiologi Terbanyak  s/d Bulan '.$CI->tanggal->getBulan(date('m')).' Tahun '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // modul eksekutif
        if($params['prefix']==351){
            $query = "SELECT MONTH(tgl_daftar) AS bulan, COUNT(kode_penunjang) AS total 
                        FROM pm_tc_penunjang 
                        WHERE YEAR(tgl_daftar)=".date('Y')." GROUP BY MONTH(tgl_daftar)";   
            $fields = array('Kunjungan Pasien Lab'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Kunjungan Pasien Lab Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        if($params['prefix']==352){
            $query = "SELECT TOP 10 b.nama_tindakan, COUNT(b.kode_tarif) AS total 
                        FROM pm_hasilpasien_v b
                        WHERE YEAR(b.tgl_periksa) = ".date('Y')."
                        GROUP BY b.kode_tarif, b.nama_tindakan ORDER BY COUNT(b.kode_tarif) DESC";  
            $data_qry = $CI->db->query($query)->result_array();
            $getData = [];
            foreach ($data_qry as $key => $value) {
                $data[] = array( 'name' => $value['nama_tindakan'], 'total' => $value['total'] );
            }

            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">10 Jenis Pemeriksaan Lab Terbanyak Tahun '.date('Y').'</span>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
        }

        if($params['prefix']==353){
            $title = '<span style="font-size:18px; font-weight: bold">Resume Pendaftaran Pasien Hari Ini, '.date('D, d/m/Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            // query kunjungan pasien hari ini
            $query_1 = "SELECT COUNT(no_registrasi) AS total 
                        FROM tc_registrasi a
                        WHERE CAST(tgl_jam_masuk as DATE)='".date('Y-m-d')."' AND a.kode_perusahaan=120 GROUP BY MONTH(tgl_jam_masuk)";
            $exc_qry_1 = $db->query($query_1)->row();   
            $fields['kunjungan_bpjs'] = array('flag' => 'Pendaftaran Pasien BPJS ', 'total' => $exc_qry_1->total);

            $query_4 = "SELECT COUNT(no_registrasi) AS total 
                        FROM tc_registrasi a
                        WHERE CAST(tgl_jam_masuk as DATE)='".date('Y-m-d')."' AND a.kode_perusahaan != 120 GROUP BY MONTH(tgl_jam_masuk)";
            $exc_qry_4 = $db->query($query_4)->row();   
            $fields['kunjungan'] = array('flag' => 'Pendaftaran Pasien Umum & Asuransi ', 'total' => $exc_qry_4->total);

            // submit kasir
            $query_2 = "SELECT COUNT(no_registrasi) AS total 
                        FROM tc_trans_kasir 
                        WHERE no_registrasi IN (SELECT no_registrasi
                        FROM tc_registrasi a
                        WHERE CAST(tgl_jam_masuk as DATE)='".date('Y-m-d')."' GROUP BY no_registrasi)";
            $exc_qry_2 = $db->query($query_2)->row();   
            $fields['pemeriksaan'] = array('flag' => 'Submit Kasir', 'total' => $exc_qry_2->total);

            // isi hasil
            $query_5 = "SELECT SUM(bill_rs) AS total 
                        FROM tc_trans_pelayanan 
                        WHERE CAST(tgl_transaksi as DATE)='".date('Y-m-d')."' GROUP BY CAST(tgl_transaksi as DATE)";
            $exc_qry_5 = $db->query($query_5)->row();   
            $fields['total_pendapatan'] = array('flag' => 'Total Pendapatan RS', 'total' => $exc_qry_5->total);
            
            /*excecute query*/
            $data = $fields;
        }

        if($params['prefix']==354){
            $query = "SELECT TOP 20 b.nama_tindakan, COUNT(b.kode_tarif) AS total 
                        FROM pm_hasilpasien_v b
                        WHERE YEAR(b.tgl_periksa) = ".date('Y')."
                        GROUP BY b.kode_tarif, b.nama_tindakan ORDER BY COUNT(b.kode_tarif) DESC";  
            $fields = array('Nama_Pemeriksaan' => 'nama_tindakan', 'Total' => 'total');
            $title = '<span style="font-size:13.5px">20 Jenis Pemeriksaan Lab Terbanyak  s/d Bulan '.$CI->tanggal->getBulan(date('m')).' Tahun '.date('Y').' </span></small>';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // modul kepegawaian
        if($params['prefix']==80){
            $query = "SELECT TOP 20 b.nama_tindakan, COUNT(b.kode_tarif) AS total 
                        FROM pm_hasilpasien_v b
                        WHERE YEAR(b.tgl_periksa) = ".date('Y')."
                        GROUP BY b.kode_tarif, b.nama_tindakan ORDER BY COUNT(b.kode_tarif) DESC";  
            $fields = array('Nama_Pemeriksaan' => 'nama_tindakan', 'Total' => 'total');
            $title = '';
            $subtitle = 'Source: '.APPS_NAME_LONG.'';
            /*excecute query*/
            $data = $db->query($query)->result_array();
        }

        // end modul laboratorium
        /*find and set type chart*/
        $chart = $this->chartTypeData($params['TypeChart'], $fields, $params, $data);
        $chart_data = array(
            'title'     => $title,
            'subtitle'  => $subtitle,
            'xAxis'     => isset($chart['xAxis'])?$chart['xAxis']:'',
            'series'    => isset($chart['series'])?$chart['series']:'',
            );

        return $chart_data;
        
    }

    public function chartTypeData($style, $fields, $params, $data){

        // echo '<pre>';print_r($style);
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($params);
        // echo '<pre>';print_r($data);
        // die;
        switch ($style) {
            case 'column':
                /*lanjutkan buat function jika ada style yang lain*/
                if ($params['style']==1) {
                    return $this->ColumnStyleOneData($fields, $params, $data);
                }
                break;
            case 'pie':
                if ($params['style']==1) {
                    return $this->PieStyleOneData($fields, $params, $data);
                }
                break;
            case 'line':
                if ($params['style']==1) {
                    return $this->LineStyleOneData($fields, $params, $data);
                }
                if ($params['style']==2) {
                    return $this->LineStyleTwoData($fields, $params, $data);
                }
                if ($params['style']==21) {
                    return $this->LineStyleTwoDataByDate($fields, $params, $data);
                }
                if ($params['style']==4) {
                    return $this->LineStyleFourData($fields, $params, $data);
                }
                if ($params['style']==5) {
                    return $this->LineStyleDynamicData($fields, $params, $data);
                }
                if ($params['style']==6) {
                    return $this->LineStyleDynamicFields($fields, $params, $data);
                }
                break;
            case 'table':
                if ($params['style']==1) {
                    return $this->TableStyleOneData($fields, $params, $data);
                }

                if ($params['style']==263) {
                    return $this->TableStyleCustom263($fields, $params, $data);
                }

                // custom table resume kunjungan pasien
                if ($params['style']=='TableResumeKunjungan') {
                    return $this->TableResumeKunjungan($fields, $params, $data);
                }
                
                if ($params['style']=='TableResumePendaftaran') {
                    return $this->TableResumePendaftaran($fields, $params, $data);
                }

                if ($params['style']=='TableResumeKunjunganHarian') {
                    return $this->TableResumeKunjunganHarian($fields, $params, $data);
                }

                if ($params['style']=='TableResumePasienHarian') {
                    return $this->TableResumePasienHarian($fields, $params, $data);
                }
                
                if ($params['style']=='TableResumeKunjunganPasien') {
                    return $this->TableResumeKunjunganPasien($fields, $params, $data);
                }
                
                if ($params['style']=='TableResumeKunjunganPasienAsuransi') {
                    return $this->TableResumeKunjunganPasienAsuransi($fields, $params, $data);
                }
                
                if ($params['style']=='TableResumeKinerjaDokter') {
                    return $this->TableResumeKinerjaDokter($fields, $params, $data);
                }

                if ($params['style']=='TableKinerjaDokter') {
                    return $this->TableKinerjaDokter($fields, $params, $data);
                }

                if ($params['style']=='TableResumeHutang') {
                    return $this->TableResumeHutang($fields, $params, $data);
                }
                
                if ($params['style']=='TableResumePiutang') {
                    return $this->TableResumePiutang($fields, $params, $data);
                }

                if ($params['style']=='TableResumeByJurnal') {
                    return $this->TableResumeByJurnal($fields, $params, $data);
                }

                if ($params['style']=='TableSensusRJ') {
                    return $this->TableSensusRJ($fields, $params, $data);
                }

                if ($params['style']=='TableSensusTrendKunjungan') {
                    return $this->TableSensusTrendKunjungan($fields, $params, $data);
                }

                if ($params['style']=='TableSensusIGD') {
                    return $this->TableSensusIGD($fields, $params, $data);
                }

                if ($params['style']=='TableSensusFr') {
                    return $this->TableSensusFr($fields, $params, $data);
                }

                if ($params['style']=='TableSensusPM') {
                    return $this->TableSensusPM($fields, $params, $data);
                }

                if ($params['style']=='TableSensusRI') {
                    return $this->TableSensusRI($fields, $params, $data);
                }

                if ($params['style']=='TableSupplierPerMonth') {
                    return $this->TableSupplierPerMonth($fields, $params, $data);
                }

            break;
            case 'custom':
                if ($params['style']=='profilePegawai') {
                    return $this->profilePegawai($fields, $params, $data);
                }
            break;
            case 'custom-antrol':
                return $this->customDashboardAntrol($fields, $params, $data);
            break;
            default:
                # code...
                break;
        }
    }

    public function ColumnStyleOneData($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $getData = array();
        foreach($data as $key=>$row){
            foreach ($fields as $kf => $vf) {
                $getData[$kf][$row['bulan']-1] = (int)$row[$vf];
            }
        }
        
        for ($i=0; $i < 12; $i++) { 
            foreach ($fields as $kf2 => $vf2) {
                if(!isset($getData[$kf2][$i])){
                    $getData[$kf2][$i] = 0;
                }
                ksort($getData[$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        foreach ($getData as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }
        
        $chart_data = array(
            'xAxis'     => array('categories' => $categories),
            'series'    => $series,
        );
        return $chart_data;
    }

    public function PieStyleOneData($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($params);
        // echo '<pre>';print_r($data);
        // die;

        $getData = array();
        foreach($data as $key=>$row){
            foreach ($fields as $kf => $vf) {
                $getData[$row[$kf]][] = (int)$row[$vf];
            }
        }

        foreach ($getData as $k => $r) {
            $series[] = array($k, array_sum($r));
        }
        $chart_data = array(
            'series'    => $series,
        );
        return $chart_data;
    }

    public function LineStyleOneData($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // echo '<pre>';print_r($fields);
        
        $getData = array();
        foreach($data as $key=>$row){
            foreach ($fields as $kf => $vf) {
                $getData[$kf][$row['bulan']-1] = round($row['total'], 2);
            }
        }
        // echo '<pre>';print_r($data);
        

        for ($i=0; $i < 12; $i++) { 
            foreach ($fields as $kf2 => $vf2) {
                if(!isset($getData[$kf2][$i])){
                    $getData[$kf2][$i] = 0;
                }
                ksort($getData[$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        foreach ($getData as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }
        
        $chart_data = array(
            'xAxis'     => array('categories' => $categories),
            'series'    => $series,
        );
        return $chart_data;
    }
    
    public function LineStyleTwoData($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // echo '<pre>';print_r($fields);
        
        $getData[0] = array();
        foreach($data[0] as $key=>$row){
            foreach ($fields[0] as $kf => $vf) {
                $getData[0][$kf][$row['bulan']-1] = round($row['total'], 2);
            }
        }

        $getData[1] = array();
        foreach($data[1] as $key=>$row){
            foreach ($fields[1] as $kf => $vf) {
                $getData[1][$kf][$row['bulan']-1] = round($row['total'], 2);
            }
        }
        // echo '<pre>';print_r($data);
        

        for ($i=0; $i < 12; $i++) { 
            foreach ($fields[0] as $kf2 => $vf2) {
                if(!isset($getData[0][$kf2][$i])){
                    $getData[0][$kf2][$i] = 0;
                }
                ksort($getData[0][$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        for ($i=0; $i < 12; $i++) { 
            foreach ($fields[1] as $kf2 => $vf2) {
                if(!isset($getData[1][$kf2][$i])){
                    $getData[1][$kf2][$i] = 0;
                }
                ksort($getData[1][$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        foreach ($getData[0] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }

        foreach ($getData[1] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }
        
        $chart_data = array(
            'xAxis'     => array('categories' => $categories),
            'series'    => $series,
        );
        return $chart_data;
    }

    public function LineStyleTwoDataByDate($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // echo '<pre>';print_r($fields);
        
        $getData[0] = array();
        foreach($data[0] as $key=>$row){
            foreach ($fields[0] as $kf => $vf) {
                $getData[0][$kf][$row['tgl']] = round($row['total'], 2);
            }
        }

        $getData[1] = array();
        foreach($data[1] as $key=>$row){
            foreach ($fields[1] as $kf => $vf) {
                $getData[1][$kf][$row['tgl']] = round($row['total'], 2);
            }
        }
        // echo '<pre>';print_r($data);
        

        for ($i=0; $i < 32; $i++) { 
            foreach ($fields[0] as $kf2 => $vf2) {
                if(!isset($getData[0][$kf2][$i])){
                    $getData[0][$kf2][$i] = 0;
                }
                ksort($getData[0][$kf2]);
            }
            $categories[] = $i;
            
        }

        for ($i=0; $i < 32; $i++) { 
            foreach ($fields[1] as $kf2 => $vf2) {
                if(!isset($getData[1][$kf2][$i])){
                    $getData[1][$kf2][$i] = 0;
                }
                ksort($getData[1][$kf2]);
            }
            $categories[] = $i;
            
        }

        foreach ($getData[0] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }

        foreach ($getData[1] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }
        
        $chart_data = array(
            'xAxis'     => array('categories' => $categories),
            'series'    => $series,
        );

        // echo '<pre>';print_r($chart_data);die;
        return $chart_data;
    }
    
    public function LineStyleFourData($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // echo '<pre>';print_r($fields);
        
        $getData[0] = array();
        foreach($data[0] as $key=>$row){
            foreach ($fields[0] as $kf => $vf) {
                $getData[0][$kf][$row['bulan']-1] = round($row['total'], 2);
            }
        }

        $getData[1] = array();
        foreach($data[1] as $key=>$row){
            foreach ($fields[1] as $kf => $vf) {
                $getData[1][$kf][$row['bulan']-1] = round($row['total'], 2);
            }
        }

        $getData[2] = array();
        foreach($data[2] as $key=>$row){
            foreach ($fields[2] as $kf => $vf) {
                $getData[2][$kf][$row['bulan']-1] = round($row['total'], 2);
            }
        }

        $getData[3] = array();
        foreach($data[3] as $key=>$row){
            foreach ($fields[3] as $kf => $vf) {
                $getData[3][$kf][$row['bulan']-1] = round($row['total'], 2);
            }
        }
        // echo '<pre>';print_r($data);
        

        for ($i=0; $i < 12; $i++) { 
            foreach ($fields[0] as $kf2 => $vf2) {
                if(!isset($getData[0][$kf2][$i])){
                    $getData[0][$kf2][$i] = 0;
                }
                ksort($getData[0][$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        for ($i=0; $i < 12; $i++) { 
            foreach ($fields[1] as $kf2 => $vf2) {
                if(!isset($getData[1][$kf2][$i])){
                    $getData[1][$kf2][$i] = 0;
                }
                ksort($getData[1][$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        for ($i=0; $i < 12; $i++) { 
            foreach ($fields[2] as $kf2 => $vf2) {
                if(!isset($getData[2][$kf2][$i])){
                    $getData[2][$kf2][$i] = 0;
                }
                ksort($getData[2][$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        for ($i=0; $i < 12; $i++) { 
            foreach ($fields[3] as $kf2 => $vf2) {
                if(!isset($getData[3][$kf2][$i])){
                    $getData[3][$kf2][$i] = 0;
                }
                ksort($getData[3][$kf2]);
            }
            $categories[] = $CI->tanggal->getBulan($i+1);
            
        }

        foreach ($getData[0] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }

        foreach ($getData[1] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }

        foreach ($getData[2] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }
        foreach ($getData[3] as $k => $r) {
            $series[] = array('name' => $k, 'data' => $r );
        }
        
        $chart_data = array(
            'xAxis'     => array('categories' => $categories),
            'series'    => $series,
        );
        return $chart_data;
    }

    public function LineStyleDynamicData($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $total_data = count($fields);
        // echo '<pre>';print_r($fields);die;
        
        for($td=0; $td<=($total_data-1); $td++){
            $getData[$td] = array();
            foreach($data[$td] as $key=>$row){
                foreach ($fields[$td] as $kf => $vf) {
                    $getData[$td][$kf][$row['txt_y']] = round($row['total'], 2);
                }
            }

            for ($i=0; $i < 32; $i++) { 
                foreach ($fields[$td] as $kf2 => $vf2) {
                    if(!isset($getData[$td][$kf2][$i])){
                        $getData[$td][$kf2][$i] = 0;
                    }
                    ksort($getData[$td][$kf2]);
                }
            }

            foreach ($getData[$td] as $k => $r) {
                $series[] = array('name' => $k, 'data' => $r );
            }

        }

        for ($i=0; $i < 32; $i++) { 
            $categories[] = $i;
        }
        
        $chart_data = array(
            'xAxis'     => array('categories' => $categories),
            'series'    => $series,
        );

        // echo "<pre>"; print_r($chart_data);die;
        return $chart_data;
    }

    public function LineStyleDynamicFields($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $total_data = count($fields);
        // echo '<pre>';print_r($fields);die;
        // echo '<pre>';print_r($data[0]);die;

        foreach ($data[0] as $ky => $rw) {
            $categories[] = $rw['txt_y'];
        }

        $getDataX = [];
        for($td=0; $td<=($total_data-1); $td++){
                $CI =&get_instance();
                $db = $CI->load->database('default', TRUE);
                $total_data = count($fields);

                // Ambil semua kategori unik dari seluruh data
                $categories = array();
                foreach ($data as $dataSet) {
                    foreach ($dataSet as $row) {
                        if (!in_array($row['txt_y'], $categories)) {
                            $categories[] = $row['txt_y'];
                        }
                    }
                }
            sort($categories);

            $series = array();
            for ($td = 0; $td < $total_data; $td++) {
                foreach ($fields[$td] as $fieldKey => $fieldName) {
                    $dataSeries = array();
                    foreach ($categories as $cat) {
                        // Cari data yang sesuai kategori
                        $found = false;
                        foreach ($data[$td] as $row) {
                            if ($row['txt_y'] == $cat) {
                                $dataSeries[] = round($row['total'], 2);
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $dataSeries[] = 0;
                        }
                    }
                    $series[] = array('name' => $fieldKey, 'data' => $dataSeries);
                }
            }

            
            $chart_data = array(
                'xAxis'     => array('categories' => $categories),
                'series'    => $series,
            );
            // echo '<pre>';print_r($chart_data);die;
            return $chart_data;
        }
    }

    public function TableStyleCustom263($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($params);
        // echo '<pre>';print_r($data);
        // die;

        $html = '';
        $html .= '<table class="table">';
        $html .= '<tr>';
        foreach ($data as $key => $value) {
            $html .= '<td>'.$value['flag'].': <br><span style="font-size: 36px">'.number_format($value['total']).'</span> </td>';
        }
        $html .= '</tr>';
        $html .= '</table>';
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableResumePendaftaran($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // load view
        $result = array(
            'value' => $data,
        );
        // echo '<pre>';print_r($result);
        // die;
        $html = $CI->load->view('eksekutif/Eks_pendaftaran/TableResumeKunjungan', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }
    
    public function TableResumeKunjungan($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($params);
        

        // load view
        $result = array(
            'value' => $data,
        );
        // echo '<pre>';print_r($result);
        // die;
        $html = $CI->load->view('eksekutif/Eks_poli/TableResumeKunjungan', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableResumeKunjunganHarian($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($data);
        

        // load view
        $result = array(
            'value' => $data,
        );
        // echo '<pre>';print_r($result);
        // die;
        $html = $CI->load->view('eksekutif/Eks_poli/TableResumeKunjunganHarian', $result, true);
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableResumePasienHarian($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($data);
        
        // load view
        $result = array(
            'value' => $data,
        );
        // echo '<pre>';print_r($data['data_ri']);
        // die;
        if($data['jenis_kunjungan'] == 'ri'){
            $html = $CI->load->view('eksekutif/Eks_poli/TableResumePasienHarianRI', $result, true);      
        }else{
            $html = $CI->load->view('eksekutif/Eks_poli/TableResumePasienHarian', $result, true);      
        }
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }
    
    public function TableResumeKunjunganPasien($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($data);
        

        // load view
        $result = array(
            'value' => $data,
        );
        // echo '<pre>';print_r($result);
        // die;
        $html = $CI->load->view('eksekutif/Eks_poli/TableResumeKunjunganPasien', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }
    
    public function TableResumeKunjunganPasienAsuransi($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // load view
        $result = array(
            'value' => $data,
        );
        // echo '<pre>';print_r($result);
        // die;
        $html = $CI->load->view('eksekutif/Eks_poli/TableResumeKunjunganPasienAsuransi', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }
    
    public function TableResumeKinerjaDokter($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // master nama dokter
        $dok = $db->get('mt_dokter_v')->result();
        foreach($dok as $row){
            $dokData[$row->kode_dokter] = $row->nama_pegawai;
        }
        foreach($data['prd_dt'] as $key_data=>$row_data){
            // jumlah kunjungan
            
            $getData[$key_data]['nama_dokter'] = $dokData[$row_data[0]->kode_dokter];
            $getData[$key_data]['jml_kunjungan'] = count($row_data);
            foreach ($row_data as $key => $value) {
                # code...
                $bill_dr1 = ($value->kode_dokter1 == $key_data) ? (int)$value->total_bill_dr : 0;
                $bill_dr2 = ($value->kode_dokter2 == $key_data) ? (int)$value->total_bill_dr2 : 0;
                $total_bill_dr[$key_data][] = $bill_dr1 + $bill_dr2;
            }
            
            
            $getData[$key_data]['jml_billing'] = array_sum($total_bill_dr[$key_data]);
        }
        
        // load view
        $result = array(
            'value' => $getData,
        );
        // echo '<pre>'; print_r($result);die;
        $html = $CI->load->view('eksekutif/Eks_poli/TableResumeKinerjaDokter', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableKinerjaDokter($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        // load view
        $result = array(
            'value' => $data['result'],
            'export' => isset($_GET['export'])?$_GET['export']:false,
        );

        if(isset($_GET['export']) AND $_GET['export'] == 'excel'){
            // echo 'Export Here'; exit;
            if(empty($_GET['poliklinik']) AND !empty($_GET['select_dokter'])){
                $CI->load->view('eksekutif/Eks_kinerja_dokter/TableKinerjaDokter_2_excel', $result);
            }else{
                $CI->load->view('eksekutif/Eks_kinerja_dokter/TableKinerjaDokter_excel', $result);
            }
        }else{
            if(empty($_GET['poliklinik']) AND !empty($_GET['select_dokter'])){
                $html = $CI->load->view('eksekutif/Eks_kinerja_dokter/TableKinerjaDokter_2', $result, true);
            }elseif(!empty($_GET['poliklinik']) AND empty($_GET['select_dokter'])){
                $html = $CI->load->view('eksekutif/Eks_kinerja_dokter/TableKinerjaDokter_2', $result, true);
            }else{
                $html = $CI->load->view('eksekutif/Eks_kinerja_dokter/TableKinerjaDokter', $result, true);
            }
    
            $chart_data = array(
                'xAxis'     => 0,
                'series'    => $html,
            );
            return $chart_data;
        }

        
    }

     public function TableResumeHutang($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($params);
        

        // load view
        $result = array(
            'value' => $data,
        );
        // echo '<pre>';print_r($result);
  //    die;
        $html = $CI->load->view('eksekutif/Eks_hutang_usaha/TableResumeHutang', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }
    
    public function TableResumePiutang($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($fields);
        // echo '<pre>';print_r($params);
        
        // load view
        $result = array(
            'value' => $data,
        );
        
        $html = $CI->load->view('eksekutif/Eks_piutang/TableResumePiutang', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableResumeByJurnal($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // load view
        $result = array(
            'value' => $data,
        );

        // echo '<pre>'; print_r($result);die;
        
        $html = $CI->load->view('eksekutif/Eks_poli/TableResumeByJurnal', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableSensusRJ($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        
        // master unit
        foreach ($data['result'] as $key => $value) {
            $getData[$value->nama_bagian][] = $value;
            $getDataStatusPasien[$value->nama_bagian][strtolower($value->stat_pasien)][] = $value;
            $getDataDokter[$value->nama_bagian][$value->nama_pegawai][] = $value;
            $getDataDokterBatal[$value->nama_bagian][$value->nama_pegawai][$value->status_batal][] = $value;
            $getDataFaskes[$value->nama_faskes][] = $value;
            $getDataPerusahaan[$value->nama_perusahaan][] = $value;
            $getDataPasienBatal[$value->nama_bagian][$value->status_batal][] = $value;

            if($value->kode_perusahaan == 120){
                $getDataPenjamin[$value->nama_bagian][120][strtolower($value->stat_pasien)][] = $value;
            }elseif ($value->kode_perusahaan == 0) {
                $getDataPenjamin[$value->nama_bagian][0][strtolower($value->stat_pasien)][] = $value;
            }else{
                $getDataPenjamin[$value->nama_bagian][1][strtolower($value->stat_pasien)][] = $value;
            }
        }
        $getDtTtl = [];
        $getTtlPasien = [];
        foreach ($getData as $k => $v) {
            foreach ($v as $rw) {
                $getTtlPasien[$k][$rw->no_mr] = 1;
            }
            $getDtTtl[$k][] = isset($getTtlPasien[$k]) ? count($getTtlPasien[$k]) : 0;
        }
        // echo "<pre>"; print_r($getDtTtl);die;
        $result = [
            'total' => count($data['result']),
            'poli' => $getData,
            'status_pasien' => $getDataStatusPasien,
            'penjamin' => $getDataPenjamin,
            'dokter' => $getDataDokter,
            'dokter_batal' => $getDataDokterBatal,
            'faskes' => $getDataFaskes,
            'perusahaan' => $getDataPerusahaan,
            'batal' => $getDataPasienBatal,
            'diagnosa' => $data['diagnosa'],
            'tindakan' => $data['tindakan'],
            'pasien' => $getDtTtl,
        ];
        


        
        $html = $CI->load->view('eksekutif/Eks_rm/TableSensusRJ', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableSensusTrendKunjungan($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo "<pre>"; print_r($data['date_range']['list_date']);die;
        
        foreach ($data['date_range']['list_date'] as $key => $value) {
            // convert to date/month
            $explode = explode('/', $value);
            $dm = (int)$explode[0].'/'.(int)$explode[1];
            $list_date_range[] = $dm;
            $getRJ[$dm] = isset($data['rj'][$dm])?$data['rj'][$dm]:0;
            $getIGD[$dm] = isset($data['igd'][$dm])?$data['igd'][$dm]:0;
            $getPM[$dm] = isset($data['pm'][$dm])?$data['pm'][$dm]:0;
            $getRI[$dm] = isset($data['ri'][$dm])?$data['ri'][$dm]:0;
        }
        // echo "<pre>"; print_r($getRJ);die;

        $result = [
            'range_date' => $list_date_range,
            'rj' => $getRJ,
            'igd' => $getIGD,
            'pm' => $getPM,
            'ri' => $getRI,
            'from_tgl' => $data['from_tgl'],
            'to_tgl' => $data['to_tgl'],
        ];
        // echo "<pre>"; print_r($result);die;

        $html = $CI->load->view('eksekutif/Eks_poli/TableSensusTrendKunjungan', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableSensusIGD($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $getDataStatusPasien = [];
        $getDataPenjamin = [];
        $getDataDokter = [];
        $getDataDokterBatal = [];
        $getDataDokterKonvRI = [];
        $getDataPerusahaan = [];
        $getDataPasienBatal = [];
        $kategori_usia = [];
        $caraKeluarPasien = [];
        $getDataUmur = [];

        // master unit
        foreach ($data['result'] as $key => $value) {
            $getData[$value->nama_bagian][] = $value;
            $getDataStatusPasien[$value->nama_bagian][strtolower($value->stat_pasien)][] = $value;
            $getDataDokter[$value->nama_bagian][$value->nama_pegawai][] = $value;
            $getDataDokterBatal[$value->nama_bagian][$value->nama_pegawai][$value->status_batal][] = $value;
            $getDataDokterKonvRI[$value->nama_bagian][$value->nama_pegawai][$value->cara_keluar_pasien][] = $value;
            $getDataPerusahaan[$value->nama_perusahaan][] = $value;
            $getDataPasienBatal[$value->nama_bagian][$value->status_batal][] = $value;
            $cara_kp = ($value->cara_keluar_pasien != '')?$value->cara_keluar_pasien:'Atas kemauan sendiri';
            $caraKeluarPasien[$cara_kp][] = $value;

            if($value->kode_perusahaan == 120){
                $getDataPenjamin[$value->nama_bagian][120][strtolower($value->stat_pasien)][] = $value;
            }elseif ($value->kode_perusahaan == 0) {
                $getDataPenjamin[$value->nama_bagian][0][strtolower($value->stat_pasien)][] = $value;
            }else{
                $getDataPenjamin[$value->nama_bagian][1][strtolower($value->stat_pasien)][] = $value;
            }

            $getDataUmur[$value->umur][] = $value;
        }

        $key_umur = array_keys($getDataUmur);
        // echo "<pre>";print_r(array_keys($getDataUmur));die;

        foreach($key_umur as $val_u){
            $getDataUmurX[$val_u] = count($getDataUmur[$val_u]);
        }

        // get kategori usia
        $kategori_usia = $CI->master->getKategoriUsia($getDataUmurX);

        $result = [
            'total' => count($data['result']),
            'poli' => $getData,
            'status_pasien' => $getDataStatusPasien,
            'penjamin' => $getDataPenjamin,
            'dokter' => $getDataDokter,
            'dokter_batal' => $getDataDokterBatal,
            'dokter_konv_ri' => $getDataDokterKonvRI,
            'perusahaan' => $getDataPerusahaan,
            'batal' => $getDataPasienBatal,
            'umur' => $kategori_usia,
            'cara_keluar' => $caraKeluarPasien,
            'diagnosa' => $data['diagnosa'],
        ];

        
        // echo "<pre>";print_r($kategori_usia);die;

        
        $html = $CI->load->view('eksekutif/Eks_rm/TableSensusIGD', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableSensusFr($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $getDataResep = [];
        $getDataResepDiantar = [];
        $getDataPenjamin = [];
        $getDataResepRacikan = [];

        // master unit
        foreach ($data['result'] as $key => $value) {
            $getDataResep[$value->flag_trans][] = $value;
            if($value->kode_perusahaan == 120){
                $getDataPenjamin[$value->flag_trans][120][] = 1;
                $getDataResepDiantar[$value->flag_trans][120][$value->resep_diantar][] = 1;
            }elseif ($value->kode_perusahaan == 0) {
                $getDataPenjamin[$value->flag_trans][0][] = 1;
                $getDataResepDiantar[$value->flag_trans][0][$value->resep_diantar][] = 1;
            }else{
                $getDataPenjamin[$value->flag_trans][1][] = 1;
                $getDataResepDiantar[$value->flag_trans][1][$value->resep_diantar][] = 1;
            }

            // bagian asal
            $getDataResepBagianAsal[$value->bagian_asal][] = 1;
        }

        foreach ($data['racikan'] as $k => $v) {
            $getDataResepRacikan[$v->flag_trans][] = 1;
            $getDataResepRacikanBagianAsal[$v->bagian_asal][] = 1;
        }

        foreach($getDataResepBagianAsal as $k=>$v){
            $total_racikan_bag_asal = isset($getDataResepRacikanBagianAsal[$k])?count($getDataResepRacikanBagianAsal[$k]):0;
            $bagian_asal[$k] = ['total_racikan' => $total_racikan_bag_asal, 'total_resep' => count($v)];
        }
        arsort($bagian_asal);

        $result = [
            'total' => count($data['result']),
            'resep' => $getDataResep,
            'penjamin' => $getDataPenjamin,
            'resep_diantar' => $getDataResepDiantar,
            'resep_racikan' => $getDataResepRacikan,
            'bagian_asal' => $bagian_asal,
        ];

        
        // echo "<pre>";print_r($bagian_asal);die;

        
        $html = $CI->load->view('eksekutif/Eks_rm/TableSensusFr', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }
    

    public function TableSensusPM($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        $getDataStatusPasien = [];
        $getDataDokter = [];
        $getDataDokterBatal = [];
        $getDataDokterKonvRI = [];
        $getDataPerusahaan = [];
        $getDataPasienBatal = [];

        // master unit
        foreach ($data['result'] as $key => $value) {
            $getData[$value->nama_bagian][] = $value;
            $getDataStatusPasien[$value->nama_bagian][strtolower($value->stat_pasien)][] = $value;
            $getDataBagianAsal[$value->bagian_asal][] = $value;
            $getDataPerusahaan[$value->nama_perusahaan][] = $value;
            $getDataPasienBatal[$value->nama_bagian][$value->status_batal][] = $value;
            $getDataBagianAsalperPM[$value->prefix_kode_bagian][$value->kode_bagian_tujuan][] = $value;

            if($value->kode_perusahaan == 120){
                $getDataPenjamin[$value->nama_bagian][120][strtolower($value->stat_pasien)][] = $value;
            }elseif ($value->kode_perusahaan == 0) {
                $getDataPenjamin[$value->nama_bagian][0][strtolower($value->stat_pasien)][] = $value;
            }else{
                $getDataPenjamin[$value->nama_bagian][1][strtolower($value->stat_pasien)][] = $value;
            }
        }

        foreach($data['pemeriksaan'] as $key_p => $val_p){
            $getDataPemeriksaan[$val_p->kode_bagian][] = $val_p;
        }

        $result = [
            'total' => count($data['result']),
            'unit' => $getData,
            'status_pasien' => $getDataStatusPasien,
            'penjamin' => $getDataPenjamin,
            'perusahaan' => $getDataPerusahaan,
            'bagian_asal' => $getDataBagianAsal,
            'bagian_asal_perpm' => $getDataBagianAsalperPM,
            'batal' => $getDataPasienBatal,
            'pemeriksaan' => $getDataPemeriksaan,
        ];
        // echo "<pre>"; print_r($getDataPemeriksaan);die;


        
        $html = $CI->load->view('eksekutif/Eks_rm/TableSensusPM', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableSensusRI($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo "<pre>"; print_r($data['result']);die;
        // master unit
        $getDataBagianAsal = [];
        $getDataStatusPasien = [];
        $getDataPenjamin = [];
        $getDataDokter = [];
        $getDataDokterPengirim = [];
        $getDataPerusahaan = [];


        foreach ($data['result'] as $key => $value) {
            $getDataBagianAsal[strtoupper($value->bagian_asal)][] = $value;
            $getDataStatusPasien[strtoupper($value->bagian_asal)][strtolower($value->stat_pasien)][] = $value;
            $getDataPenjamin[strtoupper($value->bagian_asal)][$value->kode_perusahaan][] = $value;
            $getDataDokter[$value->nama_pegawai][] = $value;
            $getDataDokterPengirim[$value->dr_pengirim][] = count($value);
            $getDataPerusahaan[$value->nama_perusahaan][] = $value;
        }

        // echo "<pre>"; print_r($getDataDokterPengirim);die;
        $result = [
            'total' => count($data['result']),
            'bagian_asal' => $getDataBagianAsal,
            'status_pasien' => $getDataStatusPasien,
            'penjamin' => $getDataPenjamin,
            'dokter' => $getDataDokter,
            'dokter_pengirim' => $getDataDokterPengirim,
            'perusahaan' => $getDataPerusahaan,
            'diagnosa' => $data['diagnosa'],
        ];


        
        $html = $CI->load->view('eksekutif/Eks_rm/TableSensusRI', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function TableSupplierPerMonth($fields, $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo "<pre>"; print_r($data);die;
        // master unit
        foreach ($data as $key => $value) {
            $getData[$value['supplier']][$value['bulan']] = $value['total_format_money'];
        }

        // echo "<pre>"; print_r($getData);die;

        $result = [
            'total' => count($getData),
            'result' => $getData,
        ];


        
        $html = $CI->load->view('eksekutif/Eks_hutang_usaha/TableSupplierPerMonth', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function customDashboardAntrol($fields='', $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        // echo '<pre>';print_r($data);die;
        $getData['sumberdata'] = [];
        $getData['poli'] = [];
        $getData['status'] = [];
        foreach ($data as $key => $value) {
            // group by sumberdata
            if(isset($value->sumberdata)){
                $getData['sumberdata'][$value->sumberdata][] = isset($value->jumlah_antrean)?$value->jumlah_antrean:1;
            }
            if(isset($value->kodepoli)){
                // group by poli
                $getData['poli'][$value->kodepoli][] = isset($value->jumlah_antrean)?$value->jumlah_antrean:1;
            }
            if(isset($value->status)){
                // group by status
                $status = str_replace(" ", "_", $value->status);
                $getData['status'][$status][] = isset($value->jumlah_antrean)?$value->jumlah_antrean:1;
            }
        }


        // load view
        $result = array(
            'tgl' => $params['tgl'],
            'sumberdata' => $getData['sumberdata'],
            'poli' => $getData['poli'],
            'status' => $getData['status'],
            'data' => $data,
        );
        // echo '<pre>';print_r($getData['poli']); die;
        $html = $CI->load->view('ws_bpjs/Ws_index/DashboardAntrolView', $result, true);
        
        
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function profilePegawai($fields='', $params, $data){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // echo '<pre>';print_r($CI->session->all_userdata()); die;
        $result = array();
        if(isset($CI->session->userdata('user_profile')->no_ktp)){
            $result['profile'] = $CI->db->get_where('view_dt_pegawai', array('nik' => $CI->session->userdata('user_profile')->no_ktp) )->row();
            // cuti
            $result['cuti'] = $this->getJumlahCuti($CI->session->userdata('user_profile')->kepeg_id);
            $result['gaji'] = $this->getPayroll($CI->session->userdata('user_profile')->no_induk);
            $result['lembur'] = $this->getJumlahLembur($CI->session->userdata('user_profile')->kepeg_id);
        }
        // echo '<pre>';print_r($result); die;

        
        $html = $CI->load->view('kepegawaian/Kepeg_dashboard/profile_view', $result, true);
        $chart_data = array(
            'xAxis'     => 0,
            'series'    => $html,
        );
        return $chart_data;
    }

    public function getJumlahCuti($kepeg_id){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // get all data 
        $query = $CI->db->get_where('kepeg_pengajuan_cuti', array('kepeg_id' => $kepeg_id, 'status_acc' => 'Y'))->result();
        $jumlah_cuti = [];
        foreach ($query as $key => $value) {
            // jumlah hari cuti
            $jumlah_cuti[] = $CI->tanggal->getRangeDay($value->cuti_dari_tgl, $value->cuti_sd_tgl);
        }
        // echo array_sum($jumlah_cuti); exit;
        return array_sum($jumlah_cuti);
    }

    public function getPayroll($nip, $mth = ''){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // get all data 
        // current month
        $query = $CI->db->join('kepeg_gaji', 'kepeg_gaji.kg_id=kepeg_rincian_gaji.kg_id','left')->order_by('kg_periode_bln', 'DESC')->get_where('kepeg_rincian_gaji', array('kepeg_rincian_gaji.nip' => $nip, 'kg_periode_thn' => date('Y')))->row();
        // echo '<pre>';print_r($query); die;
        
        return $query;
    }

    public function getJumlahLembur($kepeg_id){
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        // get all data 
        // current month
        $month = date('m');
        $query = $CI->db->join('kepeg_pengajuan_lembur', 'kepeg_pengajuan_lembur.pengajuan_lembur_id=kepeg_pengajuan_lembur_rincian.pengajuan_lembur_id','left')->get_where('kepeg_pengajuan_lembur_rincian', array('kepeg_pengajuan_lembur.kepeg_id' => $kepeg_id, 'periode_lembur_bln' => $month))->result();
        $jumlah_lembur = [];
        foreach ($query as $key => $value) {
            # code...
            $export_array = explode(" ", $value->jml_jam_lembur);
            $convert_to_minute = (int)($export_array[0]) * 60;
            $add_h_m = $convert_to_minute + (int)($export_array[1]);
            $jumlah_lembur[] = $add_h_m;
        }
        $minutes = array_sum($jumlah_lembur);
        $hours = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60).'';
        // echo '<pre>';print_r($hours); die;
        
        return $hours;
    }
    
}

?> 
