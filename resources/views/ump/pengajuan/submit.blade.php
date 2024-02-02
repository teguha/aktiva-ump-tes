@extends('layouts.modal')

@section('action', route($routes.'.submitSave', $record->id))

@section('modal-body')
	@method('POST')
    Submit Pengajuan UMP?
@endsection
