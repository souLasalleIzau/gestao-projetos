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
              <li class="breadcrumb-item active" aria-current="page">Cadastrar</li>
            </ol>
          </nav>
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
              <h3 class="mb-0">Cadastrar cliente </h3>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form role="form" class="validate" method="post" action="{{ route('client.create') }}">
            @csrf
            <div class="row">
              <div class="col-lg-12">
                <h6 class="heading-small text-muted mb-4">Informações do cliente</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-control-label" for="input-CPF">CPF</label>
                        <input class="form-control" placeholder="Informe o CPF" type="text" id="input-CPF" name="CPF" value="{{ old('CPF') }}" required>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-control-label" for="input-name">Nome</label>
                        <input class="form-control" placeholder="Informe o nome" type="text" id="input-name" name="name" value="{{ old('name') }}" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="my-4" />
            <div class="text-right">
              <button type="submit" class="btn btn-primary">Cadastrar cliente</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  @include('includes.footer')
</div>
@endsection