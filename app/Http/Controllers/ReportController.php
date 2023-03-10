<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\ReportsExport;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function exportExcel()
     { 
    // nama file yang akan terdownload
    // selain .xlsx juga bisa .csv
     $file_name = 'data_keseluruhan_pengaduan'.'.xlsx'; 
     //memanggil file ReportsExport dan mendownload dengan nama seperti $file_name
     return Excel::download(new ReportsExport, $file_name); 
     }
     

    public function exportPDf()
    {
        // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        $data = Report::with('response')->get()->toArray(); 
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
        view()->share('reports',$data); 
        // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf'); 
    }

    public function createdPDF($id)
    {
        // ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jangan gunakan pagination
        $data = Report::with('response')->where('id',$id)->get()->toArray(); 
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial 
        view()->share('reports',$data); 
        // panggil view blade yg akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan.pdf');
    }

    
     public function index()
    {
        //ASC : ascending -> terkecil ke terbesar 1-100/a-z
        //DESC : descending -> terbesar ke terkecil 100-1/z-a
        //orderBy untuk mengurutkan data
        //created_at nama kolom di database

        $reports = Report::orderBy('created_at', 'DESC')->simplePaginate(2);
        return view('index', compact('reports')); 
    }


    //Request $request ditambahkan karena pada halaman data ada fitur searchnya, 
    // dan akan mengambil text yang diinput search
    public function data(Request $request)
    {
        // ambil data yang diinput ke input yg name nya search
        $search = $request->search;
        // where akan mencari data berdasarkan column nama
        // data yang diambil merupakan data yang 'LIKE' (terdapat) text yang dimasukin ke input search
        // LIKE berfungsi untuk mencari data text (berdasarkan 1 kata)
        // contoh : ngisi input search dengan 'fem'
        // % berfungsi untuk jika $search berisi data inputan search, bakal nyari dari data dari yang di search
        // yang ada kata depannya 'fem'
        // bakal nyari ke db yang column nama yang ada isi 'fem'nya
        $reports = Report::with('response')->where('nama','LIKE', '%' . $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data', compact('reports'));
    }


    public function dataPetugas(Request $request)
    {
        // ambil data yang diinput ke input yg name nya search
        $search = $request->search;
        // where akan mencari data berdasarkan column nama
        // data yang diambil merupakan data yang 'LIKE' (terdapat) text yang dimasukin ke input search
        // LIKE berfungsi untuk mencari data text (berdasarkan 1 kata)
        // contoh : ngisi input search dengan 'fem'
        // % berfungsi untuk jika $search berisi data inputan search, bakal nyari dari data dari yang di search
        // yang ada kata depannya 'fem'
        // bakal nyari ke db yang column nama yang ada isi 'fem'nya
        //with : ambil relasi (nama fungsi hasOne/hasMany/belongsTo dimodelnya). ambil data dari relasi itu
        //Report::with('response') : data table report ingin di ambil berasama table response
        $reports = Report::with('response')->where('nama','LIKE', '%' . $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data_petugas', compact('reports'));
    }


    public function auth(Request $request)
    {
        //Request $request menyimpan data dari inputannya
        $request->validate([
            'email' => 'required|email|:dns',
            'password' => 'required|min:4',
        ]);

        $user = $request->only('email', 'password');
        // simpandata tersebut ke fitur auth sebagai indentitas
        if (Auth::attempt($user)){
            if (Auth::user()->role == 'admin') {
                return redirect()->route('data');
            }elseif (Auth::user()->role == 'petugas') {
                return redirect()->route('data.petugas');
        }else {
          return redirect()->back()->with('gagal', "Gagal Login, coba lagi");
        }
    }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|numeric',
            'pengaduan' => 'required|min:5',
            'foto' => 'required|image|mimes:jpeg,jpg,png,svg',
        ]);

        //panggil folder tempat simpen gambar
        $path = public_path('assets/image/');
         //ambil file yg diupload di input yg name nya foto
         $image = $request->file('foto');
         //ubah nama file jadi random extensi
         $imgName = rand() . '.' . $image->extension();
         //pindahin gambar yg di upload dan udah di rename ke folder tadi
         $image->move($path, $imgName);

         Report::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'pengaduan' => $request->pengaduan,
            'foto' => $imgName,
        ]);

        return redirect()->route('home')->with('success', 'Berhasil menambahkan data baru');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // cari data yang dimaksud
        // funsi firstorFail jika datanya ga ada bakal menghasilkan null jika ada akan mengambil satu baris data
        $data = Report::where('id', $id)->firstorFail();
        //$data isinya -> nik sampai foto dari pengaaduan
        // bikin variable yang isinya ngarah ke file foto terkait
        // public_path nyari file di folder public yang namanya sama kaya $data bagian foto
        $image = public_path('assets/image/' .$data['foto']);
        // kalau sudah nemu posisi fotonya, tinggal di hapus fotonya pake unlink
        unlink($image);
        // hapus $data yang isinya data nik-foto tadi, hapus di database
        $data->delete();
        // setelahnya kembalikan ke halaman awal
        Response::where('report_id' , $id)->delete();
         return redirect()->back();
    }
}
