@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="{{ route('auth.login') }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Clientes</a></li>
              <li class="breadcrumb-item active" aria-current="page">Visualizar</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{ route('client.edit', ['client_id' => $client->id]) }}" class="btn btn-secondary">Editar cliente</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Visualizar cliente</h3>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h6 class="heading-small text-muted mb-4">Dados do cliente</h6>
              <div class="row">
                <div class="col-md-3">
                  <p>
                    <strong>CPF</strong> <br>
                    {{ $client->CPF }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Nome</strong> <br>
                    {{ $client->name }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <hr class="my-4" />
          <div class="row">
            <div class="col-12 text-center">
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#destroyModal">Excluir cliente</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  @include('includes.footer')
</div>
<div class="modal fade" id="destroyModal" tabindex="-1" role="dialog" aria-labelledby="destroyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroyModalLabel">Excluir cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Tem certeza que deseja excluir este cliente?</p>
        <form role="form" class="validate mt-4" method="post" action="{{ route('client.destroy', ['client_id' => $client->id]) }}">
          @csrf
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-danger">Sim</button>      
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection