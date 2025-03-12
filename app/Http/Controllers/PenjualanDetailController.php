<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Detail Penjualan',
            'list' => ['Home', 'Penjualan Detail']
        ];

        $page = (object) [
            'title' => 'Detail transaksi penjualan'
        ];

        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list()
    {
        $penjualanDetail = PenjualanDetailModel::with('penjualan', 'barang')->select('detail_id', 'penjualan_id', 'barang_id', 'jumlah', 'harga');

        return DataTables::of($penjualanDetail)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Detail Penjualan',
            'list' => ['Home', 'Penjualan Detail', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah detail transaksi penjualan'
        ];

        $penjualan = PenjualanModel::all();
        $barang = BarangModel::all();
        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:1'
        ]);

        PenjualanDetailModel::create($request->all());

        return redirect('/penjualan_detail')->with('success', 'Detail penjualan berhasil disimpan');
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
        return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terdapat tabel lain yang terkait');
    }
}

}
