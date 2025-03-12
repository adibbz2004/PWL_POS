<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok barang'
        ];

        $activeMenu = 'stok';

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list()
    {
        $stok = StokModel::with('barang', 'user')->select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah');
    
        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return '<a href="' . url('/stok/' . $row->stok_id) . '" class="btn btn-info btn-sm">Detail</a> 
                        <a href="' . url('/stok/' . $row->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> 
                        <form action="' . url('/stok/' . $row->stok_id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus?\');">Hapus</button>
                        </form>';
            })
            ->rawColumns(['aksi']) // Menandakan bahwa 'aksi' berisi HTML
            ->make(true);
    }
    
    


    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah stok baru'
        ];

        $barang = BarangModel::all();
        $users = UserModel::all();
        $activeMenu = 'stok';

        return view('stok.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'users' => $users, 'activeMenu' => $activeMenu]);
    }

    

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1'
        ]);

        StokModel::create($request->all());

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }


    public function show(string $id)
{
    $stok = StokModel::with('barang', 'user')->find($id);

    if (!$stok) {
        return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
    }

    $breadcrumb = (object) [
        'title' => 'Detail Stok',
        'list' => ['Home', 'Stok', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail informasi stok'
    ];

    $activeMenu = 'stok';

    return view('stok.show', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'stok' => $stok,
        'activeMenu' => $activeMenu
    ]);
}

public function edit($id)
{
    $stok = StokModel::find($id);
    $barang = BarangModel::all(); // Ambil semua data barang

    if (!$stok) {
        return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
    }

    $breadcrumb = (object) [
        'title' => 'Edit Stok',
        'list' => ['Home', 'Stok', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit Stok'
    ];

    $activeMenu = 'stok'; // Set menu yang sedang aktif

    return view('stok.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'stok' => $stok,
        'barang' => $barang, // Kirim data barang ke view
        'activeMenu' => $activeMenu
    ]);
}


public function update(Request $request, string $id)
{
    $request->validate([
        'barang_id' => 'required|integer',
        'user_id' => 'required|integer',
        'stok_tanggal' => 'required|date',
        'stok_jumlah' => 'required|integer|min:1'
    ]);

    $stok = StokModel::find($id);
    
    if (!$stok) {
        return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
    }

    $stok->update([
        'barang_id' => $request->barang_id,
        'user_id' => $request->user_id,
        'stok_tanggal' => $request->stok_tanggal,
        'stok_jumlah' => $request->stok_jumlah
    ]);

    return redirect('/stok')->with('success', 'Data stok berhasil diperbarui');
}

public function destroy(string $id)
{
    $stok = StokModel::find($id);

    if (!$stok) {
        return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
    }

    try {
        $stok->delete();
        return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terkait dengan tabel lain');
    }
}



    
}
