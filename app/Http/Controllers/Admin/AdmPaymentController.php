<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;

class AdmPaymentController extends Controller
{
    /**
     * -------------------------------------------------------------------
     * pembayaran() - Index [GET]
     * -------------------------------------------------------------------
     * Method untuk menampilkan daftar pembayaran
     * @return view
     */
    public function pembayaran()
    {
        $db = DB::table('payment');
        $data = $db->get();
        foreach ($data as $payment => $value) {
            $value->amount = $this->formatRupiah($value->amount, 'Rp. ');
        }
        foreach ($data as $phone => $value) {
            if (substr($value->phone, 0, 1) == '0') {
                $value->phone = '62' . substr($value->phone, 1);
            }
        }

        $title = 'Daftar Pembayaran';

        return view('admin.pembayaran.index', compact('data', 'title'));
    }

    /**
     * -------------------------------------------------------------------
     * detailPembayaran($id) - Detail [GET]
     * -------------------------------------------------------------------
     * Method untuk menampilkan detail pembayaran berdasarkan ID Pembayaran
     * @return view
     */
    public function detailPembayaran($id)
    {
        // Check if pembayar is lembaga or not
        $check = DB::table('payment')->where('id', $id)->get()[0];
        $boolean = DB::table('lembaga')->where('code', $check->nik)->get();
        if (count($boolean) > 0) {
            $isLembaga = true;
        } else {
            $isLembaga = false;
        }
        $payment = DB::table('payment')->where('id', $id)->get()->first();

        $provinces  = DB::table('address')->where(DB::raw('CHAR_LENGTH(id)'), '=', 2)->get();
        $districts  = DB::table('address')->where(DB::raw('CHAR_LENGTH(id)'), '=', 5)->where('id', 'LIKE', substr($payment->address, 0, 2) . '%')->get();
        $regencies  = DB::table('address')->where(DB::raw('CHAR_LENGTH(id)'), '=', 8)->where('id', 'LIKE', substr($payment->address, 0, 5) . '%')->get();
        $villages  = DB::table('address')->where(DB::raw('CHAR_LENGTH(id)'), '=', 13)->where('id', 'LIKE', substr($payment->address, 0, 8) . '%')->get();


        $lembaga = DB::table('lembaga')->get();


        $payment_type = [
            'MAAL' => '',
            'INFAQ' => '',
            'FITRAH' => 'disabled',
            'FIDYAH' => 'disabled',
            'QURBAN' => 'disabled'
        ];

        $title = 'Detail Pembayaran';
        return view('admin.pembayaran.detail', compact('payment', 'isLembaga', 'provinces', 'districts', 'regencies', 'villages', 'lembaga', 'payment_type', 'title'));
    }

    /**
     * -------------------------------------------------------------------
     * createPembayaran() - Create [GET]
     * -------------------------------------------------------------------
     * Method untuk menampilkan form buat data pembayaran baru
     * @return view
     */
    public function createPembayaran()
    {
        $provinces  = DB::table('address')->where(DB::raw('CHAR_LENGTH(id)'), '=', 2)->get();
        $lembaga = DB::table('lembaga')->where('type', 'PEMBAYAR')->orWhere('type', 'PEMBAYAR_PENERIMA')->get();
        $title = 'Tambah Pembayaran';

        return view('admin.pembayaran.add', compact('provinces', 'lembaga', 'title'));
    }

    /**
     * -------------------------------------------------------------------
     * paymentStore() - Create [POST]
     * -------------------------------------------------------------------
     * Method untuk membuat data pembayaran baru ke dalam database
     * @return view
     */
    public function paymentStore()
    {
        // Custom Error Message for Validation
        $messages = [
            'required' => 'Data :attribute harus diisi',
            'in'      => 'Data :attribute harus bertipe :values',
            'nik.min' => 'Panjang NIK minimal 16 karakter',
            'nik.max' => 'Panjang NIK maksimal 16 karakter',
            'amount.min' => 'Jumlah besaran minimal Rp.10.000',
            'proof_of_payment.required' => 'Bukti pembayaran harus diisi',
            'proof_of_payment.mimes' => 'Bukti pembayaran harus bertpe gambar :values',
            'proof_of_payment.max' => 'Bukti pembayaran maksimal berukuran :max KB',
        ];

        // Set Rules for Form Input
        $rules = [
            'name' => 'required|string',
            'nik' => 'required|string|min:16|max:16',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'province' => 'required',
            'district' => 'required',
            'regency' => 'required',
            'village' => 'required',

            'type' => 'required',
            'amount' => 'required|int|min:10000',
            'proof_of_payment' => 'required|max:5000|mimes:png,jpg,jpeg',
        ];

        // Adding rule if Lembaga is Set
        if (request('lembaga')) {
            unset($rules['name']);
            unset($rules['nik']);
            unset($rules['gender']);

            $lembaga = DB::table('lembaga')->where('code', request('lembaga'))->first();
            $nik = $lembaga->code;
            $name = $lembaga->name;
            $gender = "";
        } else {
            $nik = request('nik');
            $name = request('name');
            $gender = request('gender');
        }

        //  amount ✅
        $pattern = ['/Rp/', '/[^\p{L}\p{N}\s]/u', '/ /'];
        $amount = preg_replace($pattern, '', request('amount'));

        // change amount data into integer
        request()->merge([
            'amount' => $amount,
        ]);
        // DATA VALIDATION ✅🔐
        $validator = Validator::make(request()->all(), $rules, $messages);
        if ($validator->fails()) {
            session()->flash('title', 'Gagal');
            session()->flash('message', 'Data gagal dikirim. Silakan cek kembali form yang Anda isi');
            session()->flash('status', 'error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //  amount ✅
        $pattern = ['/Rp/', '/[^\p{L}\p{N}\s]/u', '/ /'];
        $amount = preg_replace($pattern, '', request('amount'));


        // proof_of_payment ✅
        $file = request()->file('proof_of_payment');
        $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
        $pop = 'uploads/bayar/' . $filename;

        // created_at ✅
        date_default_timezone_set("Asia/Jakarta");
        $created_at = date('Y-m-d H:i:s');

        $date = date("dmy");
        $uniqueId = "PAY" . uniqid() . $date;

        //  INSERT ✅
        $data = [
            'id' => $uniqueId,
            'name' => $name,
            'nik' => $nik,
            'gender' => $gender,
            'phone' => request('phone'),
            'email' => request('email'),
            'address' => request('village'),

            'type' => request('type'),
            'amount' => $amount,
            'created_by' => 'ADMIN',
            'created_at' => $created_at,
            'proof_of_payment' => $pop,
        ];

        $file->move(public_path('uploads/bayar'), $filename);
        Payment::insert($data);

        // Success ✅   
        session()->flash('title', 'Sukses');
        session()->flash('message', 'Data berhasil dikirim');
        session()->flash('status', 'success');
        return redirect('/admin/pembayaran');
    }

    /**
     * -------------------------------------------------------------------
     * paymentUpdateStore() - Create [POST]
     * -------------------------------------------------------------------
     * Method untuk membuat data pembayaran baru ke dalam database
     * @return view
     */
    public function paymentUpdateStore()
    {
        // Custom Error Message for Validation
        $messages = [
            'required' => 'Data :attribute harus diisi',
            'in'      => 'Data :attribute harus bertipe :values',
            'nik.min' => 'Panjang NIK minimal 16 karakter',
            'nik.max' => 'Panjang NIK maksimal 16 karakter',
            'amount.min' => 'Jumlah besaran minimal Rp.10.000',
        ];

        // Set Rules for Form Input
        $rules = [
            'name' => 'required|string',
            'nik' => 'required|string|min:16|max:16',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'province' => 'required',
            'district' => 'required',
            'regency' => 'required',
            'village' => 'required',

            'type' => 'required',
            'amount' => 'required|int|min:10000',
        ];

        // Adding rule if Lembaga is Set
        if (request('lembaga')) {
            unset($rules['name']);
            unset($rules['nik']);

            $lembaga = DB::table('lembaga')->where('code', request('lembaga'))->first();
            $nik = $lembaga->code;
            $name = $lembaga->name;
        } else {
            $nik = request('nik');
            $name = request('name');
        }

        //  amount ✅
        $pattern = ['/Rp/', '/[^\p{L}\p{N}\s]/u', '/ /'];
        $amount = preg_replace($pattern, '', request('amount'));

        // change amount data into integer
        request()->merge([
            'amount' => $amount,
        ]);
        // DATA VALIDATION ✅🔐
        $validator = Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {
            session()->flash('title', 'Gagal');
            session()->flash('message', 'Data gagal diubah. Silakan cek kembali form yang Anda isi');
            session()->flash('status', 'error');
            return redirect()->back()->withErrors($validator)->withInput();
        }


        //  amount ✅
        $pattern = ['/Rp/', '/[^\p{L}\p{N}\s]/u', '/ /'];
        $amount = preg_replace($pattern, '', request('amount'));


        // proof_of_payment ✅
        if (request()->file('proof_of_payment') == null) {
            $pop = Payment::find(request('id'))->proof_of_payment;
        } else {
            $file = request()->file('proof_of_payment');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $pop = 'uploads/bayar/' . $filename;

            // ! Deactivated
            $file->move(public_path('uploads/bayar'), $filename);
        }

        // created_at ✅
        date_default_timezone_set("Asia/Jakarta");
        $created_at = date('Y-m-d H:i:s');

        //  UPDATE ✅
        $data = [
            'id' => request('id'),
            'name' => $name,
            'nik' => $nik,
            'gender' => request('gender'),
            'phone' => request('phone'),
            'email' => request('email'),
            'address' => request('village'),

            'type' => request('type'),
            'amount' => $amount,
            'valid' => 'UNCHECKED',
            'created_by' => 'ADMIN',
            'created_at' => $created_at,
            'proof_of_payment' => $pop,
        ];

        // Check Code in Property 'nik' | Payment Table
        $payment = Payment::where('id', $data['id']);
        if (count($payment->get()) > 0) {
            $payment->update($data);
        }

        // Success ✅   
        session()->flash('title', 'Sukses');
        session()->flash('message', 'Data berhasil diubah');
        session()->flash('status', 'success');
        return redirect('/admin/pembayaran');
    }

    /**
     * -------------------------------------------------------------------
     * setVisibility() 
     * -------------------------------------------------------------------
     * Set visibility data pembayaran
     * HIDDEN - SHOW
     */
    public function setVisibility(Request $request)
    {
        $id = $request->get('id');
        $visibility = $request->get('visibility');
        if ($visibility == 'HIDDEN') {
            $visibility = 'SHOW';
        } else {
            $visibility = 'HIDDEN';
        }
        DB::table('payment')->where('id', $id)->update(['visible' => $visibility]);
    }

    /**
     * -------------------------------------------------------------------
     * formatRupiah($angka, $prefix) 
     * -------------------------------------------------------------------
     * Method to set angka to format currency
     * Rp. $angka
     */
    public static function formatRupiah($angka, $prefix = "")
    {
        $number_string = preg_replace("/[^,\d]/", "", $angka);
        $split = explode(",", $number_string);
        $sisa = strlen($split[0]) % 3;
        $rupiah = substr($split[0], 0, $sisa);
        $ribuan = substr($split[0], $sisa);
        $ribuan = preg_match_all("/\d{3}/", $ribuan, $match);
        if ($ribuan) {
            $separator = $sisa ? "." : "";
            $rupiah .= $separator . implode(".", $match[0]);
        }
        $rupiah = isset($split[1]) ? $rupiah . "," . $split[1] : $rupiah;
        return $prefix == "" ? $rupiah : "Rp. " . $rupiah;
    }

    public function destroyPembayaran($id)
    {
        // Check if data is already available 
        $data = Payment::where('id', $id)->get();

        // If data > 0, that means data has already stored in database
        // If request code and new_code same, that means it updates itself
        if (count($data) > 0) {
            if (file_exists($data[0]->proof_of_payment)) {
                unlink($data[0]->proof_of_payment);
            }
            Payment::where('id', $id)->delete();
            // Success ✅   
            session()->flash('title', 'Sukses');
            session()->flash('message', 'Data Pembayaran berhasil dihapus');
            session()->flash('status', 'success');
            return redirect('/admin/pembayaran/')->with('success', 'Data Pembayaran berhasil dihapus');
        } else {
            session()->flash('title', 'Gagal');
            session()->flash('message', 'Data Pembayaran tidak ditemukan');
            session()->flash('status', 'error');
            return redirect('/admin/pembayaran/')->with('danger', 'Data Pembayaran tidak ditemukan');
        }
    }

    public function validatePembayaran($id, $value)
    {
        // Check if data is already available 
        $data = Payment::where('id', $id);

        // If data > 0, that means data has already stored in database
        if (count($data->get()) > 0) {
            $data->update([
                'valid' => $value,
            ]);
            // Success ✅   
            session()->flash('title', 'Sukses');
            session()->flash('message', 'Data Pembayaran berhasil diubah: ' . $value);
            session()->flash('status', 'success');
            return redirect('/admin/pembayaran/')->with('success', 'Data Pembayaran berhasil diubah: ' . $value);
        } else {
            session()->flash('title', 'Gagal');
            session()->flash('message', 'Data Pembayaran tidak ditemukan');
            session()->flash('status', 'error');
            return redirect('/admin/pembayaran/')->with('danger', 'Data Pembayaran tidak ditemukan');
        }
    }
}
