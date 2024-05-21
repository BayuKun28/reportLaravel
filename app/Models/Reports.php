<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Reports extends Model
{
    use HasFactory;

    public static function getMasterOrganisasi()
    {
        $query = DB::table('masterorganisasi')->get();
        return $query;
    }
    public static function header($request)
    {
        $requiredParams = ['kodeklasifikasi', 'kodeopd', 'tahun'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $tahun = $request['tahun'];
        $kodeopd = $request['kodeopd'];
        $kodeklasifikasi = $request['kodeklasifikasi'];

        $query = "SELECT 
                COALESCE(m.organisasi, '-') AS organisasi, 
                COALESCE(k.klasifikasi, '-') AS klasifikasi
              FROM masterorganisasi m 
              LEFT JOIN masterklasifikasi k ON k.kodeklasifikasi::TEXT = '$kodeklasifikasi'
              WHERE (kodeurusan, kodesuburusan, kodeorganisasi, kodeunit, kodesubunit) = row(
                    COALESCE(NULLIF(split_part('$kodeopd', '.', 1), '')::integer, 0),
                    COALESCE(NULLIF(split_part('$kodeopd', '.', 2), '')::integer, 0),
                    COALESCE(NULLIF(split_part('$kodeopd', '.', 3), '')::integer, 0),
                    COALESCE(NULLIF(split_part('$kodeopd', '.', 4), '')::integer, 0),
                    COALESCE(NULLIF(split_part('$kodeopd', '.', 5), '')::integer, 0)
                ) 
                AND tahunorganisasi = $tahun";
        try {
            $result = DB::select($query);
            if (empty($result)) {
                return [
                    (object) [
                        'organisasi' => '-',
                        'klasifikasi' => '-'
                    ]
                ];
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }


    public static function BUKU_INVENTARIS($request)
    {
        $requiredParams = ['kodeklasifikasi', 'kodeopd', 'tahun'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $tahun = $request['tahun'];
        $kodeopd = $request['kodeopd'];
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $query = "SELECT z.organisasi, k.klasifikasi, b.* 
            FROM rep_108_buku_inventaris('$kodeklasifikasi', '$tahun', 
                coalesce(nullif(split_part('$kodeopd', '.', 1), '')::integer, 0), 
                coalesce(nullif(split_part('$kodeopd', '.', 2), '')::integer, 0), 
                coalesce(nullif(split_part('$kodeopd', '.', 3), '')::integer, 0), 
                coalesce(nullif(split_part('$kodeopd', '.', 4), '')::integer, 0), 
                coalesce(nullif(split_part('$kodeopd', '.', 5), '')::integer, 0)
            ) AS b
            LEFT JOIN (
                SELECT m.organisasi, 1 AS zid 
                FROM masterorganisasi m 
                WHERE (kodeurusan, kodesuburusan, kodeorganisasi, kodeunit, kodesubunit) = row(
                        coalesce(nullif(split_part('$kodeopd', '.', 1), '')::integer, 0),
                        coalesce(nullif(split_part('$kodeopd', '.', 2), '')::integer, 0),
                        coalesce(nullif(split_part('$kodeopd', '.', 3), '')::integer, 0),
                        coalesce(nullif(split_part('$kodeopd', '.', 4), '')::integer, 0),
                        coalesce(nullif(split_part('$kodeopd', '.', 5), '')::integer, 0)
                    ) 
                    AND tahunorganisasi = '$tahun'
            ) z ON z.zid = 1
            LEFT JOIN masterklasifikasi k  ON k.kodeklasifikasi::TEXT = '$kodeklasifikasi'";

        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function BUKU_INVENTARIS_HEADER($request)
    {
        return self::header($request);
    }
    public static function DAFTAR_PENYUSUTAN($request)
    {
        $requiredParams = ['kodeklasifikasi', 'kodeopd', 'tahun'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }

        $tahun = $request['tahun'];
        $kodeopd = $request['kodeopd'];
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $kodeopdArray = array_filter(explode('.', $kodeopd));


        $xkodeurusan = 'null';
        $xkodesuburusan = 'null';
        $xkodeorganisasi = 'null';
        $xkodeunit = 'null';
        $xkodesubunit = 'null';

        if ((!empty($kodeopdArray[1])) and (empty($kodeopdArray[2]))) {
            $xkodeurusan = 'null';
            $xkodesuburusan = 'null';
            $xkodeorganisasi = 'null';
            $xkodeunit = 'null';
            $xkodesubunit = 'null';
        } else if ((!empty($kodeopdArray[2])) and (empty($kodeopdArray[3]))) {
            $xkodeurusan = $kodeopdArray[0];
            $xkodesuburusan = $kodeopdArray[1];
            $xkodeorganisasi = $kodeopdArray[2];
            $xkodeunit = 'null';
            $xkodesubunit = 'null';
        } else if ((!empty($kodeopdArray[3])) and (empty($kodeopdArray[4]))) {
            $xkodeurusan = $kodeopdArray[0];
            $xkodesuburusan = $kodeopdArray[1];
            $xkodeorganisasi = $kodeopdArray[2];
            $xkodeunit = $kodeopdArray[3];
            $xkodesubunit = 'null';
        } else {
            $xkodeurusan = $kodeopdArray[0];
            $xkodesuburusan = $kodeopdArray[1];
            $xkodeorganisasi = $kodeopdArray[2];
            $xkodeunit = $kodeopdArray[3];
            $xkodesubunit = $kodeopdArray[4];
        }
        $query = "SELECT * FROM rep_rekappenyusutansampaisub_new($tahun,$kodeklasifikasi,$xkodeurusan,$xkodesuburusan,$xkodeorganisasi,$xkodeunit,$xkodesubunit)
                  ORDER BY kodegolongan, kodebidang, kodekelompok, kodesub,kodesubsub, tahunperolehan, kodekib, penyusutanpertahun DESC ";

        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function DAFTAR_PENYUSUTAN_HEADER($request)
    {
        return self::header($request);
    }
    public static function LAPORAN_INVENTARIS_RUANG($request)
    {
        $requiredParams = ['kodeklasifikasi', 'tahun', 'koderuang', 'kodeopd'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $tahun = $request['tahun'];
        $koderuang = $request['koderuang'];
        $kodeopd = $request['kodeopd'];

        $query = "SELECT 
                        k.uraibarang,
                        k.kodegolongan,
                        k.kodebidang,
                        k.kodekelompok,
                        k.kodesub,
                        k.kodesubsub,
                        k.koderegister ,
                        k.tahunperolehan,
                        k.tahunpembuatan,
                        k.merktype,
                        k.nopabrik,
                        k.ukuran,
                        k.bahan,
                        k.kodekondisi as kodekondisi,
                        mk.kondisi as kondisi,
                        k.nolokasi,
                        k.nilaiakumulasibarang,
                        k.keterangan,
                        k.norangka, 
                        k.nomesin, 
                        k.nobpkb,
                        k.nopolisi,
                        k.judul,
                        (select ruang from masterruang where koderuang = k.koderuang) ,
                        format_kodebarang_108( k.kodegolongan,
                        k.kodebidang,
                        k.kodekelompok,
                        k.kodesub,
                        k.kodesubsub) brg,
                        k.kodekib,
                        k.kodekondisi,
                        (CASE WHEN k.kodekondisi = 1 THEN 'BAIK' ELSE '' END) AS kondisib,
                        (CASE WHEN (k.kodekondisi = 2  OR k.kodekondisi = 3)  THEN 'KURANG BAIK' ELSE '' END) AS kondisikb,
                        (CASE WHEN (k.kodekondisi = 4  OR k.kodekondisi = 5)  THEN 'RUSAK BERAT' ELSE '' END) AS kondisirb
                    FROM
                        public.kib k left join public.masterkondisi mk
                        on k.kodekondisi = mk.kodekondisi
                    WHERE
                        k.tahunorganisasi = $tahun AND
                        k.koderuang = $koderuang AND                  
                        k.statusdata = 'aktif' AND
                        ($kodeklasifikasi is null or kodeklasifikasi = $kodeklasifikasi)
                    order by k.kodegolongan,k.kodebidang, k.kodekelompok,k.kodesub,k.kodesubsub, k.tahunperolehan";

        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function LAPORAN_INVENTARIS_RUANG_HEADER($request)
    {
        return self::header($request);
    }
    public static function LAPORAN_KIBA($request)
    {
        $requiredParams = ['kodeklasifikasi', 'tahun', 'kodegolongan', 'kodeopd'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $tahun = $request['tahun'];
        $kodegolongan = $request['kodegolongan'];
        $kodeopd = $request['kodeopd'];
        $kodeopdArray = array_filter(explode('.', $kodeopd));
        $kodeklasifikasiArray = array_filter(explode('.', $kodeklasifikasi));

        if (count($kodeklasifikasiArray) > 1) {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasiArray[0] . ' and kodeklasifikasi_u = ' . $kodeklasifikasiArray[1] . ' and ';
        } else if ($kodeklasifikasi == 0) {
            $sFilterKlasifikasi = ' and ';
        } else {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasi . '  and ';
        }


        if ($kodeopd = '0') {
            $sFilter = '';
        } else if ((!empty($kodeopdArray[2])) and (empty($kodeopdArray[3]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . '';
        } else if ((!empty($kodeopdArray[3])) and (empty($kodeopdArray[4]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . '';
        } else {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . ' and kodesubunit = ' . $kodeopdArray[4] . '';
        }


        $query = " SELECT qrcode,
                        kodeurusan||'.'||kodesuburusan||'.'||kodesuburusan||'.'||kodeorganisasi||'.'||kodeunit||'.'||kodesubunit||'.' as kodeopd, uraiorganisasi, 
                        format_kodebarang_108(k.kodegolongan, k.kodebidang, k.kodekelompok, k.kodesub, k.kodesubsub) as kodebarang,                                                  
                            k.uraibarang, koderegister,luas,tahunperolehan,alamat,hak,tglsertifikat,penggunaan, 
                            ma.asalusul, 
                            case when kodeklasifikasi = 1 and kodeklasifikasi_u = 1 then 'Intra Komptabel'         
                                when kodeklasifikasi = 2 then 'Ekstra Komptabel'   
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 1 then 'Aset Lainnya (Intra)'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 2 then 'Aset Lainnya (Ekstra)'   
                            else ''                              
                            end as klasifikasi, nilaiakumulasibarang, deskripsibarang, keterangan                                                       
                    from kib k 
                    left join masterhak h on k.kodehak = h.kodehak 
                    left join masterasalusul ma on k.kodeasalusul = ma.kodeasalusul
                    where tahunorganisasi = $tahun
                            $sFilterKlasifikasi
                            statusdata = 'aktif' and
                            kodegolongan = $kodegolongan
                            $sFilter
                    ";
        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function LAPORAN_KIBA_HEADER($request)
    {
        return self::header($request);
    }
    public static function LAPORAN_KIBB($request)
    {
        $requiredParams = ['kodeklasifikasi', 'tahun', 'kodegolongan', 'kodeopd'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $kodeklasifikasiArray = array_filter(explode('.', $kodeklasifikasi));
        $tahun = $request['tahun'];
        $kodegolongan = $request['kodegolongan'];
        $kodeopd = $request['kodeopd'];
        $kodeopdArray = array_filter(explode('.', $kodeopd));

        if (count($kodeklasifikasiArray) > 1) {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasiArray[0] . ' and kodeklasifikasi_u = ' . $kodeklasifikasiArray[1] . ' and ';
        } else if ($kodeklasifikasi == 0) {
            $sFilterKlasifikasi = ' and ';
        } else {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasi . '  and ';
        }


        if ((!empty($kodeopdArray[1])) and (empty($kodeopdArray[2]))) {
            $sFilter = '';
        } else if ((!empty($kodeopdArray[2])) and (empty($kodeopdArray[3]))) {
            $sFilter = ' and k.kodeurusan = ' . $kodeopdArray[0] . ' and k.kodesuburusan = ' . $kodeopdArray[1] . ' and k.kodeorganisasi = ' . $kodeopdArray[2] . '';
        } else if ((!empty($kodeopdArray[3])) and (empty($kodeopdArray[4]))) {
            $sFilter = ' and k.kodeurusan = ' . $kodeopdArray[0] . ' and k.kodesuburusan = ' . $kodeopdArray[1] . ' and k.kodeorganisasi = ' . $kodeopdArray[2] . ' and  k.kodeunit = ' . $kodeopdArray[3] . '';
        } else {
            $sFilter = ' and k.kodeurusan = ' . $kodeopdArray[0] . ' and k.kodesuburusan = ' . $kodeopdArray[1] . ' and k.kodeorganisasi = ' . $kodeopdArray[2] . ' and  k.kodeunit = ' . $kodeopdArray[3] . ' and k.kodesubunit = ' . $kodeopdArray[4] . '';
        }


        $query = " SELECT qrcode,k.kodekib,k.kodeurusan||'.'||k.kodesuburusan||'.'||k.kodesuburusan||'.'||k.kodeorganisasi||'.'||k.kodeunit||'.'||k.kodesubunit||'.' as kodeopd, k.uraiorganisasi, 
                    format_kodebarang_108(k.kodegolongan, k.kodebidang, k.kodekelompok, k.kodesub, k.kodesubsub) as kodebarang,                       
                        k.uraibarang, koderegister,merktype,bahan,tahunperolehan,nopabrik,norangka,nomesin,nopolisi,nobpkb, 
                        ma.asalusul, 
                        case when kodeklasifikasi = 1 and kodeklasifikasi_u = 1 then 'Intra Komptabel'         
                                when kodeklasifikasi = 2 then 'Ekstra Komptabel'   
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 1 then 'Aset Lainnya (Intra)'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 2 then 'Aset Lainnya (Ekstra)'   
                        else  ''                  
                        end as klasifikasi, nilaiakumulasibarang, deskripsibarang, k.keterangan, mr.ruang                              
                    from kib k 
                    left join masterhak h on k.kodehak = h.kodehak 
                    left join masterasalusul ma on k.kodeasalusul = ma.kodeasalusul 
                    left join masterruang mr on k.koderuang = mr.koderuang  
                    where k.tahunorganisasi = $tahun
                        $sFilterKlasifikasi
                        k.statusdata = 'aktif' and 
                        kodegolongan = $kodegolongan
                        $sFilter
                    ";
        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function LAPORAN_KIBB_HEADER($request)
    {
        return self::header($request);
    }
    public static function LAPORAN_KIBC($request)
    {
        $requiredParams = ['kodeklasifikasi', 'tahun', 'kodegolongan', 'kodeopd'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $tahun = $request['tahun'];
        $kodegolongan = $request['kodegolongan'];
        $kodeopd = $request['kodeopd'];
        $kodeopdArray = array_filter(explode('.', $kodeopd));
        $kodeklasifikasiArray = array_filter(explode('.', $kodeklasifikasi));

        if (count($kodeklasifikasiArray) > 1) {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasiArray[0] . ' and kodeklasifikasi_u = ' . $kodeklasifikasiArray[1] . ' and ';
        } else if ($kodeklasifikasi == 0) {
            $sFilterKlasifikasi = ' and ';
        } else {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasi . '  and ';
        }


        if ($kodeopd = '0') {
            $sFilter = '';
        } else if ((!empty($kodeopdArray[2])) and (empty($kodeopdArray[3]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . '';
        } else if ((!empty($kodeopdArray[3])) and (empty($kodeopdArray[4]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . '';
        } else {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . ' and kodesubunit = ' . $kodeopdArray[4] . '';
        }


        $query = " SELECT  qrcode,kodeurusan||'.'||kodesuburusan||'.'||kodesuburusan||'.'||kodeorganisasi||'.'||kodeunit||'.'||kodesubunit||'.' as kodeopd, uraiorganisasi, 
                        format_kodebarang_108(k.kodegolongan, k.kodebidang, k.kodekelompok, k.kodesub, k.kodesubsub) as kodebarang,                       
                            k.uraibarang, koderegister,mk.kondisi, k.bertingkat, k.beton, alamat, k.luaslantai,tgldok,nodokumen,luas,                          
                            ms.statustanah, ma.asalusul, k.kodekibtanah,tahunperolehan,                          
                            case when kodeklasifikasi = 1 and kodeklasifikasi_u = 1 then 'Intra Komptabel'         
                                when kodeklasifikasi = 2 then 'Ekstra Komptabel'   
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 1 then 'Aset Lainnya (Intra)'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 2 then 'Aset Lainnya (Ekstra)'   
                            else ''         
                            end as klasifikasi, nilaiakumulasibarang, deskripsibarang, keterangan                                                       
                    from kib k 
                    left join masterhak h on k.kodehak = h.kodehak 
                    left join masterasalusul ma on k.kodeasalusul = ma.kodeasalusul 
                    left join masterkondisi mk on mk.kodekondisi = k.kodekondisi 
                    left join masterstatustanah ms on ms.kodestatustanah = k.kodestatustanah   
                    where tahunorganisasi = $tahun
                            $sFilterKlasifikasi
                            statusdata = 'aktif' and
                            kodegolongan = $kodegolongan
                            $sFilter
                    ";
        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function LAPORAN_KIBC_HEADER($request)
    {
        return self::header($request);
    }
    public static function LAPORAN_KIBD($request)
    {
        $requiredParams = ['kodeklasifikasi', 'tahun', 'kodegolongan', 'kodeopd'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $tahun = $request['tahun'];
        $kodegolongan = $request['kodegolongan'];
        $kodeopd = $request['kodeopd'];
        $kodeopdArray = array_filter(explode('.', $kodeopd));
        $kodeklasifikasiArray = array_filter(explode('.', $kodeklasifikasi));

        if (count($kodeklasifikasiArray) > 1) {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasiArray[0] . ' and kodeklasifikasi_u = ' . $kodeklasifikasiArray[1] . ' and ';
        } else if ($kodeklasifikasi == 0) {
            $sFilterKlasifikasi = ' and ';
        } else {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasi . '  and ';
        }


        if ($kodeopd = '0') {
            $sFilter = '';
        } else if ((!empty($kodeopdArray[2])) and (empty($kodeopdArray[3]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . '';
        } else if ((!empty($kodeopdArray[3])) and (empty($kodeopdArray[4]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . '';
        } else {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . ' and kodesubunit = ' . $kodeopdArray[4] . '';
        }


        $query = " SELECT qrcode,kodeurusan||'.'||kodesuburusan||'.'||kodesuburusan||'.'||kodeorganisasi||'.'||kodeunit||'.'||kodesubunit||'.' as kodeopd, uraiorganisasi, 
                        format_kodebarang_108(k.kodegolongan, k.kodebidang, k.kodekelompok, k.kodesub, k.kodesubsub) as kodebarang,                                                                                                                                                                                                                                                                                  
                            k.uraibarang, koderegister,konstruksi,panjang,lebar,luas,alamat,tgldok,nodokumen,mk.kondisi,                         
                            ms.statustanah, ma.asalusul, k.kodekibtanah,tahunperolehan,                          
                            case when kodeklasifikasi = 1 and kodeklasifikasi_u = 1 then 'Intra Komptabel'         
                                when kodeklasifikasi = 2 then 'Ekstra Komptabel'   
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 1 then 'Aset Lainnya (Intra)'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 2 then 'Aset Lainnya (Ekstra)'   
                            else ''                                
                            end as klasifikasi, nilaiakumulasibarang, deskripsibarang, keterangan                                                       
                    from kib k 
                    left join masterhak h on k.kodehak = h.kodehak 
                    left join masterasalusul ma on k.kodeasalusul = ma.kodeasalusul 
                    left join masterkondisi mk on mk.kodekondisi = k.kodekondisi 
                    left join masterstatustanah ms on ms.kodestatustanah = k.kodestatustanah
                    where tahunorganisasi = $tahun
                            $sFilterKlasifikasi
                            statusdata = 'aktif' and
                            kodegolongan = $kodegolongan
                            $sFilter
                    ";
        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function LAPORAN_KIBD_HEADER($request)
    {
        return self::header($request);
    }
    public static function LAPORAN_KIBE($request)
    {
        $requiredParams = ['kodeklasifikasi', 'tahun', 'kodegolongan', 'kodeopd'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $tahun = $request['tahun'];
        $kodegolongan = $request['kodegolongan'];
        $kodeopd = $request['kodeopd'];
        $kodeopdArray = array_filter(explode('.', $kodeopd));
        $kodeklasifikasiArray = array_filter(explode('.', $kodeklasifikasi));

        if (count($kodeklasifikasiArray) > 1) {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasiArray[0] . ' and kodeklasifikasi_u = ' . $kodeklasifikasiArray[1] . ' and ';
        } else if ($kodeklasifikasi == 0) {
            $sFilterKlasifikasi = ' and ';
        } else {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasi . '  and ';
        }


        if ($kodeopd = '0') {
            $sFilter = '';
        } else if ((!empty($kodeopdArray[2])) and (empty($kodeopdArray[3]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . '';
        } else if ((!empty($kodeopdArray[3])) and (empty($kodeopdArray[4]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . '';
        } else {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . ' and kodesubunit = ' . $kodeopdArray[4] . '';
        }


        $query = " SELECT
                        qrcode,
                        k.kodekib,
                        kodeurusan || '.' || kodesuburusan || '.' || kodesuburusan || '.' || kodeorganisasi || '.' || kodeunit || '.' || kodesubunit || '.' AS kodeopd,
                        uraiorganisasi,
                        format_kodebarang_108(k.kodegolongan,k.kodebidang,k.kodekelompok,k.kodesub,k.kodesubsub) AS kodebarang,k.uraibarang,koderegister,concat(judul,' / ',pencipta) AS judulpencipta,
                        pencipta,
                        spesifikasi,
                        asaldaerah,
                        k.bahan,
                        k.jenis,
                        k.ukuran,
                        mk.kondisi,
                        ms.statustanah,
                        ma.asalusul,
                        k.kodekibtanah,
                        tahunperolehan,
                        CASE
                            WHEN kodeklasifikasi = 1 AND kodeklasifikasi_u = 1 THEN 'Intra Komptabel'
                            WHEN kodeklasifikasi = 2 THEN 'Ekstra Komptabel'
                            WHEN kodeklasifikasi = 3 AND kodeklasifikasi_u = 1 THEN 'Aset Lainnya (Intra)'
                            WHEN kodeklasifikasi = 3 AND kodeklasifikasi_u = 2 THEN 'Aset Lainnya (Ekstra)'
                            ELSE ''
                        END AS klasifikasi,
                        nilaiakumulasibarang,
                        deskripsibarang,
                        keterangan
                    FROM
                        kib k
                    LEFT JOIN masterhak h ON
                        k.kodehak = h.kodehak
                    LEFT JOIN masterasalusul ma ON
                        k.kodeasalusul = ma.kodeasalusul
                    LEFT JOIN masterkondisi mk ON
                        mk.kodekondisi = k.kodekondisi
                    LEFT JOIN masterstatustanah ms ON
                        ms.kodestatustanah = k.kodestatustanah
                    where tahunorganisasi = $tahun
                            $sFilterKlasifikasi
                            statusdata = 'aktif' and
                            kodegolongan = $kodegolongan
                            $sFilter
                    ";
        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function LAPORAN_KIBE_HEADER($request)
    {
        return self::header($request);
    }
    public static function LAPORAN_KIBF($request)
    {
        $requiredParams = ['kodeklasifikasi', 'tahun', 'kodegolongan', 'kodeopd'];
        foreach ($requiredParams as $param) {
            if (empty($request[$param])) {
                echo "Error: Parameter '{$param}' tidak boleh kosong.";
                die();
            }
        }
        $kodeklasifikasi = $request['kodeklasifikasi'];
        $tahun = $request['tahun'];
        $kodegolongan = $request['kodegolongan'];
        $kodeopd = $request['kodeopd'];
        $kodeopdArray = array_filter(explode('.', $kodeopd));
        $kodeklasifikasiArray = array_filter(explode('.', $kodeklasifikasi));

        if (count($kodeklasifikasiArray) > 1) {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasiArray[0] . ' and kodeklasifikasi_u = ' . $kodeklasifikasiArray[1] . ' and ';
        } else if ($kodeklasifikasi == 0) {
            $sFilterKlasifikasi = ' and ';
        } else {
            $sFilterKlasifikasi = ' and kodeklasifikasi = ' . $kodeklasifikasi . '  and ';
        }


        if ($kodeopd = '0') {
            $sFilter = '';
        } else if ((!empty($kodeopdArray[2])) and (empty($kodeopdArray[3]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . '';
        } else if ((!empty($kodeopdArray[3])) and (empty($kodeopdArray[4]))) {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . '';
        } else {
            $sFilter = ' and kodeurusan = ' . $kodeopdArray[0] . ' and kodesuburusan = ' . $kodeopdArray[1] . ' and kodeorganisasi = ' . $kodeopdArray[2] . ' and  kodeunit = ' . $kodeopdArray[3] . ' and kodesubunit = ' . $kodeopdArray[4] . '';
        }


        $query = " SELECT qrcode,kodeurusan||'.'||kodesuburusan||'.'||kodesuburusan||'.'||kodeorganisasi||'.'||kodeunit||'.'||kodesubunit||'.' as kodeopd, uraiorganisasi, 
                        format_kodebarang_108(k.kodegolongan, k.kodebidang, k.kodekelompok, k.kodesub, k.kodesubsub) as kodebarang,                                              
                            k.uraibarang, koderegister,k.bertingkat, k.beton, k.luas, k.alamat, k.tgldok, k.nodokumen, k.tglmulai,                          
                            ms.statustanah, ma.asalusul, nolokasi_kib(k.kodekibtanah) as nolokasitanah,tahunperolehan,mj.jenisbangunan,mk.kondisi,                          
                            case when kodeklasifikasi = 1 and kodeklasifikasi_u = 1 then 'Intra Komptabel'         
                                when kodeklasifikasi = 2 then 'Ekstra Komptabel'   
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 1 then 'Aset Lainnya (Intra)'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                                when kodeklasifikasi = 3 and kodeklasifikasi_u = 2 then 'Aset Lainnya (Ekstra)'   
                            else ''                                
                            end as klasifikasi, nilaiakumulasibarang, deskripsibarang, keterangan                                                       
                    from kib k 
                    left join masterhak h on k.kodehak = h.kodehak 
                    left join masterasalusul ma on k.kodeasalusul = ma.kodeasalusul 
                    left join masterkondisi mk on mk.kodekondisi = k.kodekondisi 
                    left join masterjenisbangunan mj on mj.kodejenisbangunan = k.kodejenisbangunan                          
                    left join masterstatustanah ms on ms.kodestatustanah = k.kodestatustanah 
                    where tahunorganisasi = $tahun
                            $sFilterKlasifikasi
                            statusdata = 'aktif' and
                            kodegolongan = $kodegolongan
                            $sFilter
                    ";
        try {
            $result = DB::select($query);
            return $result;
        } catch (\Exception $e) {
            echo "Cek Penulisan Parameter! ";
            die();
        }
    }
    public static function LAPORAN_KIBF_HEADER($request)
    {
        return self::header($request);
    }
}
