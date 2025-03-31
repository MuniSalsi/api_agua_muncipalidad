@extends('layouts.app')
@section('contenido')
    <form action="{{ route('process-txt') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="txt_file" accept=".txt" required>
        <button type="submit">Subir archivo</button>
    </form>
@endsection
