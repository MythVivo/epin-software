<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use File;
use mysql_xdevapi\Table;

class CariController extends Controller
{

    public function index()
    {
        return view('back.pages.cari.index');
    }

    public function grup()
    {
        return view('back.pages.cari.grup');
    }

    public function tur()
    {
        return view('back.pages.cari.tur');
    }

    public function hesap()
    {
        return view('back.pages.cari.hesap');
    }

    public function kur()
    {
        return view('back.pages.cari.kur');
    }

    public function fisler()
    {
        return view('back.pages.cari.fisler');
    }

    public function rapor()
    {
        return view('back.pages.cari.rapor');
    }


    public function cari_api(Request $request)
    {

        if ($request->truncate == 316) {
            DB::select("delete from cariy_fisler where id<>1");
            DB::select("update cariy_hesaplar set bakiye=0 where id<>1");
            DB::select("update cariy_hesaplar set bakiye=5000 where id=1");
            DB::select("truncate table cariy_log ");
            return back();
        }

#-------------------------------------------------------------
//        if ($request->kur_al == 316) {
//
//            if($request->kay!=1) { // TL kur id 1 olmalı
//                $kkur = DB::select("select * from cari_kurlar where id <>1='$request->kay'");
//            }
//            if($request->hed!=1) { // TL kur id 1 olmalı
//                $hkur = DB::select("select * from cari_kurlar where id ='$request->hed'");
//            }
//                if ($kkur->id != 1) {
//                    return response($kurs->kur . '_' . $kurs->oran . '_' . $kurs->created_at);
//                }
//            }


#-------------------------------------------------------------
        if ($request->fis_ekle == 316) {
            if ($request->baglanti > 0) {
                DB::table('cariy_fisler')->insert([
                    'kaynak_cari' => $request->kay,
                    'hedef_cari' => $request->hed,
                    'cikan_tutar' => $request->ctut,
                    'aktarilan_tutar' => $request->atut,
                    'aciklama' => $request->acik,
                    'turu' => $request->tur,
                    'baglantili_fis' => $request->baglanti,
                    'json_info' => null,
                    'admin_id' => Auth::user()->id,
                    'created_at' => date('YmdHis')
                ]);
                $last_id = DB::getPdo()->lastInsertId();

                #Giriş hesabı komisyon log
                $ob = (object) DB::select("select bakiye from cariy_hesaplar where id='$request->hed'")[0]; // önceki bakiye
                DB::table('cariy_log')->insert(['aciklama'=>'Para Girişi','hesap_id'=> $request->hed,'fis_id'=> $last_id,'onceki_bak'=> $ob->bakiye,'sonraki_bak'=> $ob->bakiye+$request->atut,'user'=> Auth::user()->id,'created_at'=> date('YmdHis')]);

                DB::select("update cariy_hesaplar set bakiye = bakiye+$request->atut  where id= $request->hed"); #komisyon hes.
                DB::table('cariy_fisler')->where('id', $request->baglanti)->update(['baglantili_fis' => $last_id]);  # Ana fiş baglantı alanını guncelle

            } else {
                DB::table('cariy_fisler')->insert([
                    'kaynak_cari' => $request->kay,
                    'hedef_cari' => $request->hed,
                    'cikan_tutar' => $request->ctut,
                    'aktarilan_tutar' => $request->atut,
                    'aciklama' => $request->acik,
                    'turu' => $request->tur,
                    'json_info' => null,
                    'admin_id' => Auth::user()->id,
                    'created_at' => date('YmdHis')
                ]);
                $last_id = DB::getPdo()->lastInsertId();

                #loglama
                #Çıkış hesabı
                $ob = (object) DB::select("select bakiye from cariy_hesaplar where id='$request->kay'")[0]; // önceki bakiye
                DB::table('cariy_log')->insert(['aciklama'=>'Para Çıkışı','hesap_id'=> $request->kay,'fis_id'=> $last_id,'onceki_bak'=> $ob->bakiye,'sonraki_bak'=> $ob->bakiye-$request->ctut,'user'=> Auth::user()->id,'created_at'=> date('YmdHis')]);
                #Giriş hesabı
                $ob = (object) DB::select("select bakiye from cariy_hesaplar where id='$request->hed'")[0]; // önceki bakiye
                DB::table('cariy_log')->insert(['aciklama'=>'Para Girişi','hesap_id'=> $request->hed,'fis_id'=> $last_id,'onceki_bak'=> $ob->bakiye,'sonraki_bak'=> $ob->bakiye+$request->atut,'user'=> Auth::user()->id,'created_at'=> date('YmdHis')]);


                DB::select("update cariy_hesaplar set bakiye = bakiye-$request->ctut  where id= $request->kay");
                DB::select("update cariy_hesaplar set bakiye = bakiye+$request->atut  where id= $request->hed");

                return response($last_id);
            }

        }
#-----------------------------------------------------------------
        if ($request->fis_oku == 316) {

            if (isset($request->id)) {
                $al = DB::table('cariy_fisler')->where('id', $request->id)->get();
                return response(json_encode((object)$al));
            }
            return back();
        }

#-----------------------------------------------------------------

        if ($request->fis_dzn == 316) {
            if ($request->sil == 316) {
                $eski = (object) DB::select("select * from cariy_fisler where id='$request->id'")[0];   # Silinecek fişteki değerleri al
                $eski_bag = (object) DB::select("select * from cariy_fisler where id='$eski->baglantili_fis'")[0];   # Silinecek baglantili fişteki değerleri al

                $obk = (object) DB::select("select bakiye from cariy_hesaplar where id='$eski->kaynak_cari'")[0]; // önceki bakiye kaynak
                $obh = (object) DB::select("select bakiye from cariy_hesaplar where id='$eski->hedef_cari'")[0]; // önceki bakiye hedef
                $obb = (object) DB::select("select bakiye from cariy_hesaplar where id='$eski_bag->hedef_cari'")[0]; // önceki bakiye komisyon

                DB::select("update cariy_hesaplar set bakiye=bakiye+'$eski->cikan_tutar' where id='$eski->kaynak_cari'"); # yerine koy kaynak c.
                DB::select("update cariy_hesaplar set bakiye=bakiye-'$eski->aktarilan_tutar' where id='$eski->hedef_cari'"); # yerine koy hedef c.
                DB::select("update cariy_hesaplar set bakiye=bakiye-'$eski_bag->aktarilan_tutar' where id='$eski_bag->hedef_cari'"); # yerine koy komisyon.


                DB::table('cariy_fisler')->where('id', $request->id)->update(['deleted_at' => date('YmdHis')]); # sil
                DB::table('cariy_fisler')->where('baglantili_fis', $request->id)->update(['deleted_at' => date('YmdHis')]); #sil

                #Burası etkilenen hesaplar için loglama
                DB::table('cariy_log')->insert(['aciklama'=>'Fiş İptali','hesap_id'=> $eski->kaynak_cari,'fis_id'=> $request->id,'onceki_bak'=> $obk->bakiye,'sonraki_bak'=> $obk->bakiye+$eski->cikan_tutar,    'user'=> Auth::user()->id,'created_at'=> date('YmdHis')]);
                DB::table('cariy_log')->insert(['aciklama'=>'Fiş İptali','hesap_id'=> $eski->hedef_cari, 'fis_id'=> $request->id,'onceki_bak'=> $obh->bakiye,'sonraki_bak'=> $obh->bakiye-$eski->aktarilan_tutar,'user'=> Auth::user()->id,'created_at'=> date('YmdHis')]);
                DB::table('cariy_log')->insert(['aciklama'=>'Fiş İptali','hesap_id'=> $eski_bag->hedef_cari, 'fis_id'=> $eski_bag->id,'onceki_bak'=> $obb->bakiye,'sonraki_bak'=> $obb->bakiye-$eski_bag->aktarilan_tutar,'user'=> Auth::user()->id,'created_at'=> date('YmdHis')]);


            } else {
                #iptal
            }
        }

#-----------------------------------------------------------------

        if ($request->hes_ekle == 316) {

            $fat_info = json_encode(array('VD' => $request->vd, 'VN' => $request->vn, 'ADRES' => $request->adres));

            DB::table('cariy_hesaplar')->insert([
                'grup' => $request->gr,
                'unvan' => $request->unvan,
                'para_birimi' => $request->brim,
                'bakiye' => $request->bak,
                'fatura_info' => $fat_info,
                'created_at' => date('YmdHis')
            ]);

            # açılış bakiyesi ekle

            $last_id = DB::getPdo()->lastInsertId();
            if ($request->bak > 0) {

                DB::table('cariy_fisler')->insert([
                    'kaynak_cari' => 0,
                    'hedef_cari' => $last_id,
                    'cikan_tutar' => 0,
                    'aktarilan_tutar' => $request->bak,
                    'aciklama' => 'Açılış Bakiyesi',
                    'json_info' => null,
                    'admin_id' => Auth::user()->id,
                    'created_at' => date('YmdHis')
                ]);
            }
            return back();

        }
#-----------------------------------------------------------------
        if ($request->hes_dzn == 316) {
            if ($request->sil == 316) {
                DB::table('cariy_hesaplar')->where('id', $request->id)->update(['deleted_at' => date('YmdHis')]);
            } else {
                $fat_info = json_encode(array('VD' => $request->vd, 'VN' => $request->vn, 'ADRES' => $request->adres));
                DB::table('cariy_hesaplar')->where('id', $request->id)->update([
                    'grup' => $request->gr,
                    'unvan' => $request->unvan,
                    'para_birimi' => $request->brim,
                    'fatura_info' => $fat_info,
                    'user_id' => $request->uid
                ]);
            }
            return response('200');
        }
#-----------------------------------------------------------------
        if ($request->hes_oku == 316) {

            if($request->live==316){ // live search istenirse
                $al = DB::select("select id,unvan,bakiye,(select ad from cariy_kurlar where id=para_birimi) ad, (select kur from cariy_kurlar where id=para_birimi) brm from cariy_hesaplar where deleted_at is null");
                $op = "<option name='h' value=''>Tüm Hesaplar</option>";
                foreach ($al as $a) {
                    $op .= "<option data-tokens='$a->unvan'  ad='$a->ad' value='$a->id'>$a->unvan ($a->bakiye - $a->brm)</option>";
                }
               return response($op);
            }

            if ($request->all == 316) { // tüm hesaplar istenirse
                $al = DB::select("select id,unvan,bakiye,(select ad from cariy_kurlar where id=para_birimi) ad, (select kur from cariy_kurlar where id=para_birimi) brm,(select oran from cariy_kurlar where id=para_birimi) orn from cariy_hesaplar where deleted_at is null");
                $op = '';
                foreach ($al as $a) {
                    // $ek=$a->bakiye<1?'disabled':'';
                    $op .= "<option kur='$a->orn' ad='$a->ad' value='$a->id'>$a->unvan ($a->bakiye - $a->brm)</option>";
                }
                return response($op);
            }

            if(isset($request->gid)){ // gruba göre istenirse
                $al = DB::select("select id,unvan,bakiye,(select ad from cariy_kurlar where id=para_birimi) ad, (select kur from cariy_kurlar where id=para_birimi) brm,(select oran from cariy_kurlar where id=para_birimi) orn from cariy_hesaplar where deleted_at is null and grup='$request->gid'");
                $op = "";
                foreach ($al as $a) {
                    $op .= "<option kur='$a->orn' ad='$a->ad' value='$a->id'>$a->unvan ($a->bakiye - $a->brm)</option>";
                }
                return response($op);
            }

            if (isset($request->id)) { // ID ile istenirse duzen icin
                $al = DB::select("select * from cariy_hesaplar where deleted_at is null and id='$request->id'");
                return response(json_encode((object)$al));
            }
            return back();
        }

#-----------------------------------------------------------------
        if ($request->gr_ekle == 316) {
            DB::table('cariy_grup')->insert(['ad' => $request->ad, 'created_at' => date('YmdHis')]);
            return back();
        }
#-----------------------------------------------------------------
        if ($request->tur_ekle == 316) {
            DB::table('cariy_tur')->insert(['ad' => $request->ad, 'created_at' => date('YmdHis')]);
            return back();
        }

        if ($request->tur_sil == 316) {
            DB::table('cariy_tur')->where('id', $request->id)->update(['deleted_at' => date('YmdHis')]);
            return back();
        }

        if ($request->tur_oku == 316) {

            if (isset($request->id)) {
                $al = DB::table('cariy_tur')->where('id', $request->id)->get();
                return response($al[0]->ad);
            } else {
                $al = DB::table('cariy_tur')->whereNull('deleted_at')->get();
                $op = '';
                foreach ($al as $a) {
                    $op .= "<option value='$a->id'>$a->ad</option>";
                }
                return response($op);
            }
        }

#-----------------------------------------------------------------


        if ($request->gr_dzn == 316) {
            if ($request->sil == 316) {
                DB::table('cariy_grup')->where('id', $request->id)->update(['deleted_at' => date('YmdHis')]);
            } else {
                DB::table('cariy_grup')->where('id', $request->id)->update(['ad' => $request->ad]);
            }
            return response('200');
        }

#-----------------------------------------------------------------
        if ($request->gr_oku == 316) {

            if (isset($request->id)) {
                $al = DB::table('cariy_grup')->where('id', $request->id)->get();
                return response($al[0]->ad);
            } else {
                $al = DB::table('cariy_grup')->whereNull('deleted_at')->get();
                $op = '';
                foreach ($al as $a) {
                    $op .= "<option value='$a->id'>$a->ad</option>";
                }
                return response($op);
            }
        }

#-----------------------------------------------------------------
        if ($request->kur_ekle == 316) {
            DB::table('cariy_kurlar')->insert(['kur'=>$request->sem, 'ad' => $request->ad, 'oran' => $request->oran, 'created_at' => date('YmdHis')]);
            return back();
        }

#-----------------------------------------------------------------
        if ($request->kur_dzn == 316) {
            if ($request->sil == 316) {
                DB::table('cariy_kurlar')->where('id', $request->id)->update(['deleted_at' => date('YmdHis')]);
            } else {
                DB::table('cariy_kurlar')->where('id', $request->id)->update(['ad' => $request->ad, 'kur' => $request->sem, 'oran' => $request->oran, 'created_at'=>date('YmdHis')]);
            }
            return response('200');
        }

#-----------------------------------------------------------------
        if ($request->kur_oku == 316) {

            if (isset($request->id)) {
                $al = DB::table('cariy_kurlar')->where('id', $request->id)->get();
                return response(json_encode((object)$al));
            } else {
                $al = DB::table('cariy_kurlar')->whereNull('deleted_at')->get();
                $op = '';
                foreach ($al as $a) {
                    $op .= "<option value='$a->id'>$a->kur - $a->ad</option>";
                }
                return response($op);
            }
        }
    }

}
