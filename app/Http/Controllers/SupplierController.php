<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
// use Illuminate\Support\Facades\Hash;


class SupplierController extends Controller
{
    // Menampilkan halaman awal supplier
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar supplier',
            'list' => ['Home', 'supplier']
        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data supplier dalam bentuk json untuk datatables

    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama');

        return DataTables::of($suppliers)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
                // $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a>';
                // $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">' .
                //     csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                // return $btn;
                $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Menampilkan halaman form tambah supplier
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah supplier',
            'list' => ['Home', 'supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];

       
        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data supplier baru
    public function store(Request $request)
    {
        $request->validate([
            // supplier_kode harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_supplier kolom supplier_kode
            'supplier_kode' => 'required|string|min:3|unique:m_supplier,supplier_kode,',
            'supplier_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
           
        ]);

        SupplierModel::create([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }

    // Menampilkan detail supplier
    public function show(string $id)
    {
        $supplier = SupplierModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail supplier',
            'list' => ['Home', 'supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail supplier'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }
    // Menampilkan halaman form edit supplier
    public function edit(string $id)
    {
        $supplier = SupplierModel::find($id);
    

        $breadcrumb = (object) [
            'title' => 'Edit supplier',
            'list' => ['Home', 'supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit supplier'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan perubahan data supplier
    public function update(Request $request, string $id)
    {
        $request->validate([
            // supplier_kode harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_supplier kolom supplier_kode kecuali untuk supplier dengan id yang sedang diedit
            'supplier_kode' => 'required|string|min:3|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
            'supplier_nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
        ]);

        SupplierModel::find($id)->update([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
    }

    public function destroy(string $id)
{
    $check = SupplierModel::find($id);
    
    if (!$check) {
        // Mengecek apakah data supplier dengan ID yang dimaksud ada atau tidak
        return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
    }
    
    try {
        SupplierModel::destroy($id);
        return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        // Jika terjadi error ketika menghapus data,  kembali ke halaman dengan pesan error
        return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}

public function create_ajax()
{
    $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();

    return view('supplier.create_ajax', ['supplier' => $supplier]);
}

public function store_ajax(Request $request)
{
    //cek apakah ada request berupa ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
        ];

        // use Illuminate\Support\Facades\Validator
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        SupplierModel::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Data supplier berhasil disimpan'
        ]);
    }
}

public function edit_ajax(string $id)
{
    $supplier = SupplierModel::find($id);
    return view('supplier.edit_ajax', ['supplier' => $supplier]);
}

public function update_ajax(Request $request, $id)
{
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
           'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
        'supplier_nama' => 'required|string|max:100',
        ];
        // use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false, // respon json, true: berhasil, false: gagal
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors() // menunjukkan field mana yang error
            ]);
        }
        $check = SupplierModel::find($id);
        if ($check) {
            $check->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/supplier');
}

public function confirm_ajax(string $id)
{
    $supplier = SupplierModel::find($id);

    return view('supplier.confirm_ajax', ['supplier' => $supplier]);
}

public function delete_ajax(Request $request, $id)
{
// cek apakah request dari ajax
if ($request->ajax() || $request->wantsJson()) {
    $supplier = SupplierModel::find($id);

    if ($supplier) {
        try {
            $supplier->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak dapat dihapus karena masih terhubung dengan data lain.'
            ]);
        }
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}
return redirect('/');
}



public function show_ajax($id)
{
    $supplier = SupplierModel::find($id);
    return view('supplier.show_ajax', compact('supplier'));

    
}

}