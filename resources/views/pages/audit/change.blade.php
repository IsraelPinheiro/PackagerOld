@extends('layouts.app')
@section('content')
	<div class="page-header mb-3">
		<h1 class="d-inline">Auditoria de Mudanças</h1>
		@if($userPermissions->download)
			<button class="btn btn-primary btn-circle btn-audit-access-download-add float-right d-inline" title="Novo" type="button">
				<i class="fas fa-download"></i>
			</button>
		@endif
	</div>
	<div class="container-fluid">
        <table id="datatable" class="datatable table table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><center>Usuário</center></th>
                    <th><center>Data</center></th>
                    <th><center>Origem</center></th>
                </tr>
            </thead>
            <tbody>
                @foreach($audits as $audit)
                    <tr>
                        <td><center>{{ $audit->user->name }}</center></td>
                        <td><center>{{ $audit->accessed_at }}</center></td>
                        <td><center>{{ $audit->origin }}</center></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop