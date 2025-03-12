<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar transaksi penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
{
    $penjualan = PenjualanModel::with('user')->select(
        'penjualan_id',
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal'
    );

    return DataTables::of($penjualan)
        ->addIndexColumn()
        ->addColumn('user', function ($penjualan) { // Tambahkan kolom user
            return $penjualan->user ? $penjualan->user->nama : '-';
        })
        ->addColumn('aksi', function ($penjualan) {
            $btn = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '" class="btn btn-info btn-sm">Detail</a>';
            $btn .= '<a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
            $btn .= '<form class="d-inline-block" method="POST" action="' . url('/penjualan/' . $penjualan->penjualan_id) . '">' .
                csrf_field() . method_field('DELETE') .
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}




    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah transaksi penjualan baru'
        ];

        $users = UserModel::all();
        $activeMenu = 'penjualan';

        return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'users' => $users, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:50',
            'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date'
        ]);

        PenjualanModel::create($request->all());

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }

    public function show(string $id)
{
    $penjualan = PenjualanModel::with('user')->find($id);
    $penjualanDetail = PenjualanDetailModel::where('penjualan_id', $id)->with('barang')->get();

    if (!$penjualan) {
        return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
    }

    $breadcrumb = (object) [
        'title' => 'Detail Penjualan',
        'list' => ['Home', 'Penjualan', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail informasi penjualan'
    ];

    $activeMenu = 'penjualan';

    return view('penjualan.show', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'penjualan' => $penjualan,
        'penjualanDetail' => $penjualanDetail,
        'activeMenu' => $activeMenu
    ]);
}


public function edit($id)
{
    $penjualan = PenjualanModel::find($id);
    $users = UserModel::all(); // Ambil semua user

    if (!$penjualan) {
        return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
    }

    $breadcrumb = (object) [
        'title' => 'Edit Penjualan',
        'list' => ['Home', 'Penjualan', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit Data Penjualan'
    ];

    $activeMenu = 'penjualan';

    return view('penjualan.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'penjualan' => $penjualan,
        'users' => $users, // Kirim data user ke view
        'activeMenu' => $activeMenu
    ]);
}



public function update(Request $request, string $id)
{
    $request->validate([
        'user_id' => 'required|integer',
        'pembeli' => 'required|string|max:50',
        'penjualan_kode' => 'required|string|max:20',
        'penjualan_tanggal' => 'required|date'
    ]);

    $penjualan = PenjualanModel::find($id);
    
    if (!$penjualan) {
        return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
    }

    $penjualan->update([
        'user_id' => $request->user_id,
        'pembeli' => $request->pembeli,
        'penjualan_kode' => $request->penjualan_kode,
        'penjualan_tanggal' => $request->penjualan_tanggal
    ]);

    return redirect('/penjualan')->with('success', 'Data penjualan berhasil diperbarui');
}

public function destroy(string $id)
{
    $penjualan = PenjualanModel::find($id);

    if (!$penjualan) {
        return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
    }

    try {
        // Hapus detail penjualan terlebih dahulu
        PenjualanDetailModel::where('penjualan_id', $id)->delete();

        // Hapus data penjualan utama
        $penjualan->delete();

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terkait dengan tabel lain');
    }
}


}
