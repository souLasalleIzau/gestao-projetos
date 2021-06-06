@extends('layouts.company')

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
              <li class="breadcrumb-item"><a href="{{ route('company.index') }}">Empresa</a></li>
              <li class="breadcrumb-item"><a href="{{ route('company.surgery.index') }}">Cirúrgias</a></li>
              <li class="breadcrumb-item active" aria-current="page">Editar</li>
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
              <h3 class="mb-0">Editar cirúrgia </h3>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form role="form" class="validate" method="post" action="{{ route('company.surgery.update', ['surgery' => $surgery->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-12">
                <h6 class="heading-small text-muted mb-4">Informações da cirúrgia</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="form-control-label" for="input-start-date">Início</label>
                        <input class="form-control" placeholder="Informe data e horario" type="text" id="input-start-date" data-masked="datetime" name="start_date" value="{{ helperChangeDateFormat($surgery->start_date, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}" required>
                      </div>
                      <div class="form-group">
                        @if ($surgery->surgeryAddress->image)
                          <label class="form-control-label">Para visualizar <a href="{{ asset($surgery->surgeryAddress->image) }}" target="_blank">clique aqui.</a></label>
                        @else
                          <label class="form-control-label" for="input-image">Imagem</label>
                        @endif
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="input-image" name="image">
                          <label class="form-control custom-file-label" for="input-image">Imagem do local</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-9">
                      <div class="form-group">
                        <label class="form-control-label" for="input-description">Descrição</label>
                        <textarea class="form-control" rows="6" placeholder="Descrição da cirúrgia" id="input-description" name="description" required>{{ $surgery->description }}</textarea>
                      </div>
                    </div>
                  </div>                  
                </div>
              </div>
            </div>
            <div class="text-right">
              <button type="submit" class="btn btn-primary">Atualizar cirúrgia</button>
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