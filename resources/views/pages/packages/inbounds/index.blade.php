@extends('layouts.app')
@section('content')
	<div class="page-header mb-3">
		<h1 class="d-inline">Caixa de Entrada</h1>
	</div>
	<div class="container-fluid">
		<table id="datatable" class="datatable table table-striped" cellspacing="0" width="100%">
			<thead>
				<tr>
                    <th><center>Remetente</center></th>
					<th><center>Título</center></th>
					<th><center>Data Envio</center></th>
					<th><center>Data Limite</center></th>
					<th><center>Arquivos</center></th>
					<th class="noorder"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($packages as $package)
					<tr class="@if($package->new) table-success @endif @if($package->expires_at)@if($package->expires_at->isPast()) table-danger @endif @endif">
                        <td><center>{{ $package->sender->name }}</center></td>
						<td><center>{{ $package->title }}</center></td>
                        <td><center>{{ $package->created_at->format('d/m/Y') }}</center></td>
                        <td><center>
							@if(empty($package->expires_at))
								-
							@else
								{{$package->expires_at->format('d/m/Y')}}
							@endif
						</center></td>
						<td><center>{{ $package->files->count() }}</center></td>
						<td class="toolbox">
                            <center>
                                <i class="fas fa-download fa-lg btn-outbounds-download pr-1" title="Baixar Arquivos do Pacote" onclick='javascript:location.href="{{ route('outbounds.download.package',['package' => $package->id]) }}"'></i>
                                <i data-id={{$package->id}} class="fas fa-eye fa-lg btn-inbounds-show pr-1" title="Exibir"></i>
                                @if($package->directLink)
                                    <i data-key={{$package->key}} class="fas fa-link fa-lg btn-inbounds-link pr-1" title="Gerar Link Direto"></i>
                                @endif
                            </center>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@stop