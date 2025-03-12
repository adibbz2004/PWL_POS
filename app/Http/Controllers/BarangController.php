<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    private $breadcrumb;
    private $page;
    private $activeMenu;

    public function __construct()
    {
        $this->breadcrumb = (object) [
            'title' => '',
            'list' => []
        ];

        $this->page = (object) [
            'title' => ''
        ];

        $this->activeMenu = 'barang';
    }

    public function index()
    {
        $this->breadcrumb->title = 'Daftar Barang';
        $this->breadcrumb->list = ['Home', 'Barang'];
        $this->page->title = 'Daftar barang yang terdaftar dalam sistem';

        return view('barang.index', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function list()
{
    $barang = BarangModel::with('kategori')
                ->select(
                    'barang_id',
                    'kategori_id',
                    'barang_kode',
                    'barang_nama',
                    'harga_beli',
                    'harga_jual'
                );

    return DataTables::of($barang)
        ->addIndexColumn()
        ->addColumn('nama_kategori', function ($barang) {
            return $barang->kategori ? $barang->kategori->nama_kategori : '-';
        })
        ->addColumn('aksi', function ($barang) {
            $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
            $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
            $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                . csrf_field() . method_field('DELETE') .
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}
    public function create()
    {
        $this->breadcrumb->title = 'Tambah Barang';
        $this->breadcrumb->list = ['Home', 'Barang', 'Tambah'];
        $this->page->title = 'Tambah barang baru';

        return view('barang.create', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_nama' => 'required|string|max:100|unique:m_barang,barang_nama',
            'deskripsi_barang' => 'required|string',
            'harga_barang' => 'required|numeric',
            'id_kategori' => 'required|integer',
            'id_supplier' => 'required|integer',
        ]);

        BarangModel::create([
            'barang_nama' => $request->barang_nama,
            'deskripsi_barang' => $request->deskripsi_barang,
            'harga_barang' => $request->harga_barang,
            'id_kategori' => $request->id_kategori,
            'id_supplier' => $request->id_supplier,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    public function show(string $id)
{
    $barang = BarangModel::with('kategori')->where('barang_id', $id)->first();

    if (!$barang) {
        return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
    }

    $this->breadcrumb->title = 'Detail Barang';
    $this->breadcrumb->list = ['Home', 'Barang', 'Detail'];
    $this->page->title = 'Detail barang';

    return view('barang.show', [
        'breadcrumb' => $this->breadcrumb,
        'page' => $this->page,
        'barang' => $barang,
        'activeMenu' => $this->activeMenu
    ]);
}


    public function edit(string $id)
    {
        $barang = BarangModel::find($id);

        $this->breadcrumb->title = 'Edit Barang';
        $this->breadcrumb->list = ['Home', 'Barang', 'Edit'];
        $this->page->title = 'Edit barang';

        return view('barang.edit', [
            'breadcrumb' => $this->breadcrumb,
            'page' => $this->page,
            'barang' => $barang,
            'activeMenu' => $this->activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_nama' => 'required|string|max:100|unique:m_barang,barang_nama,' . $id . ',id',
            'deskripsi_barang' => 'required|string',
            'harga_barang' => 'required|numeric',
            'id_kategori' => 'required|integer',
            'id_supplier' => 'required|integer',
        ]);

        $barang = BarangModel::where('id', $id)->first();
        $barang->update([
            'barang_nama' => $request->barang_nama,
            'deskripsi_barang' => $request->deskripsi_barang,
            'harga_barang' => $request->harga_barang,
            'id_kategori' => $request->id_kategori,
            'id_supplier' => $request->id_supplier,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = BarangModel::find($id);

        if (!$check) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (QueryException $e) {
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}