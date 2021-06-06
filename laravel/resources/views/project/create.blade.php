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
              <h3 class="mb-0">Cadastrar cirúrgia </h3>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form role="form" class="validate" method="post" action="{{ route('company.surgery.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-6">
                <h6 class="heading-small text-muted mb-4">Informações da cirúrgia</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-specialty">Especialidade</label>
                        <select class="form-control custom-select" id="input-specialty" placeholder="Especialidade" name="specialty" required>
                          <option>Selecione uma especialidade</option>
                          @foreach ($specialties as $specialty)
                            @if (old('specialty') == $specialty->id)
                              <option value="{{ $specialty->id }}" selected>{{ $specialty->name }}</option>
                            @else
                              <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-patient-name">Nome do paciente</label>
                        <input class="form-control" placeholder="Informe o nome do paciente" type="text" id="input-patient-name" name="patient_name" value="{{ old('patient_name') }}" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-start-date">Início</label>
                        <input class="form-control" placeholder="Informe data e horario" type="text" id="input-start-date" data-masked="datetime" name="start_date" value="{{ old('start_date') }}" required>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-description">Descrição</label>
                        <textarea class="form-control" placeholder="Descrição da cirúrgia" id="input-description" name="description" required>{{ old('description') }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Materiais</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label" for="input-material-file">Arquivo do material</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="input-material-file" name="material_file">
                          <label class="form-control custom-file-label" for="input-material-file">Arquivo do material</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row repeater">
                    <div class="col-12">
                      <div repeater-cdz-list>
                        @if (old('material_name'))
                          @foreach (old('material_name') as $key => $material)
                            <div class="row" repeater-cdz-item>
                              <div class="col-7">
                                <div class="form-group">
                                  <label class="form-control-label has-repeater" for="input-material-{{ $key ? $key : 'var' }}">Material</label>
                                  <input class="form-control has-repeater" placeholder="Informe o material" type="text" id="input-material-{{ $key ? $key : 'var' }}" name="material_name[]" value="{{ $material }}" required>
                                </div>
                              </div>
                              <div class="col-3">
                                <div class="form-group">
                                  <label class="form-control-label has-repeater" for="input-amount-{{ $key ? $key : 'var' }}">Quantidade</label>
                                  <input class="form-control has-repeater" placeholder="quantidade" type="number" id="input-amount-{{ $key ? $key : 'var' }}" name="material_amount[]" value="{{ old('material_amount')[$key] }}" required>
                                </div>
                              </div>
                              <div class="col-2">
                                <label class="form-control-label">Excluir</label>
                                <button repeater-cdz-delete type="button" class="btn btn-outline-danger">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                                  </svg>
                                </button>
                              </div>
                            </div>
                          @endforeach
                        @else
                        <div class="row" repeater-cdz-item>
                          <div class="col-7">
                            <div class="form-group">
                              <label class="form-control-label has-repeater" for="input-material-var">Material</label>
                              <input class="form-control has-repeater" placeholder="Informe o material" type="text" id="input-material-var" name="material_name[]" required>
                            </div>
                          </div>
                          <div class="col-3">
                            <div class="form-group">
                              <label class="form-control-label has-repeater" for="input-amount-var">Quantidade</label>
                              <input class="form-control has-repeater" placeholder="quantidade" type="number" id="input-amount-var" name="material_amount[]" required>
                            </div>
                          </div>
                          <div class="col-2">
                            <label class="form-control-label">Excluir</label>
                            <button repeater-cdz-delete type="button" class="btn btn-outline-danger">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                              </svg>
                            </button>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                    <div class="col-12">
                      <button repeater-cdz-create type="button" class="btn btn-sm btn-success">Adicionar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="my-4" />
            <!-- Address -->
            <h6 class="heading-small text-muted mb-4">Endereço</h6>
            <div class="pl-lg-4">
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-address-name">Nome</label>
                    <input class="form-control" id="input-address-name" placeholder="Nome" type="text" name="address_name" value="{{ old('address_name') }}" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-zip-code">CEP</label>
                    <input class="form-control {{ old('zip_code') ? 'set-change' : '' }}" placeholder="Informe seu CEP" type="text" id="input-zip-code" data-masked="zip_code" name="zip_code" value="{{ old('zip_code') }}" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-address">Endereco</label>
                    <input class="form-control" id="input-address" placeholder="Endereço" type="text" name="address" value="{{ old('address') }}" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-number">Número</label>
                    <input class="form-control" id="input-number" placeholder="Número" type="number" data-masked="number" name="number" value="{{ old('number') }}" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-state">Estado</label>
                    <select class="form-control custom-select" id="input-state" placeholder="Estado" name="state" required>
                      <option>Selecione um estado</option>
                      @foreach ($states as $state)
                        <option value="{{ $state->id }}" data-letter="{{ $state->letter }}">{{ $state->title }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-city">Cidade</label>
                    <select class="form-control custom-select" id="input-city" placeholder="Cidade" name="city" required>
                      <option>Selecione uma cidade</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-complement">Complemento</label>
                    <input class="form-control" id="input-complement" placeholder="Complemento" type="text" name="complement" value="{{ old('complement') }}">
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="form-control-label" for="input-image">Imagem</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="input-image" name="image">
                      <label class="form-control custom-file-label" for="input-image">Imagem do local</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-right">
              <button type="submit" class="btn btn-primary">Cadastrar cirúrgia</button>
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