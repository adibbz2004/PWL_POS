@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('supplier
        ') }}" class="form-horizontal">
            @csrf
           
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kode supplier
                    
                </label>
                <div class="col-11">
                    <input type="text" class="form-control" id="supplier
                    _kode" name="supplier
                    _kode" value="{{ old('supplier
                    _kode') }}" required>
                    @error('supplier
                    _kode')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama supplier
                    
                </label>
                <div class="col-11">
                    <input type="text" class="form-control" id="supplier
                    _nama" name="supplier
                    _nama" value="{{ old('supplier
                    _nama') }}" required>
                    @error('supplier
                    _nama')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-default btn-sm" href="{{ url('supplier
                    ') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('css')
@endpush

@push('js')
@endpush