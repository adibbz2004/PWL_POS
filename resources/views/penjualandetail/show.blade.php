@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('penjualan-detail/' . $detail->detail_id) }}" class="form-horizontal">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Kode Penjualan</label>
                <div class="col-10">
                    <select class="form-control" name="penjualan_id" required>
                        @foreach($penjualan as $item)
                            <option value="{{ $item->penjualan_id }}" {{ $item->penjualan_id == $detail->penjualan_id ? 'selected' : '' }}>
                                {{ $item->penjualan_kode }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Barang</label>
                <div class="col-10">
                    <select class="form-control" name="barang_id" required>
                        @foreach($barang as $item)
                            <option value="{{ $item->barang_id }}" {{ $item->barang_id == $detail->barang_id ? 'selected' : '' }}>
                                {{ $item->barang_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Jumlah</label>
                <div class="col-10">
                    <input type="number" class="form-control" name="jumlah" value="{{ $detail->jumlah }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Harga</label>
                <div class="col-10">
                    <input type="number" class="form-control" name="harga" value="{{ $detail->harga }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-10 offset-2">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('penjualan-detail') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
