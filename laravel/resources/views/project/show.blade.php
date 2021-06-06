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
              <li class="breadcrumb-item active" aria-current="page">Visualizar</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          @if ($surgery->status == "Concluído")
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ratingModal">Avaliar cirúrgia</button>
          @else
            <a href="{{ route('company.surgery.edit', ['surgery' => $surgery->id]) }}" class="btn btn-primary">Editar cirúrgia</a>
          @endif
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
              <h3 class="mb-0">Visualizar cirúrgia </h3>
            </div>
            <div class="col-4 text-right">
              <span class="badge badge-dot">
                <i class="{{ $translate['status'][$surgery->status] }}"></i>
                <span>{{ $surgery->status }}</span>
              </span>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h6 class="heading-small text-muted mb-4">Dados cirúrgicos</h6>
              <div class="row">
                <div class="col-md-3">
                  <p>
                    <strong>Especialidade</strong> <br>
                    {{ $surgery->specialty->name }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Instrumentador</strong> <br>
                    {{ $surgery->instrumentalist ? $surgery->instrumentalist->user->name : null }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Data de inicio</strong> <br>
                    {{ helperChangeDateFormat($surgery->start_date, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Data de fim</strong> <br>
                    @if ($surgery->finish_date)
                      {{ helperChangeDateFormat($surgery->finish_date, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}
                    @endif
                  </p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <p>
                    <strong>Paciente</strong> <br>
                    {{ $surgery->patient_name }}
                  </p>
                </div>
                <div class="col-md-6">
                  <p>
                    <strong>Descrição</strong> <br>
                    {{ $surgery->description }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Pós cirúrgico</strong> <br>
                    @if ($surgery->after_surgery_file)
                    Para visualizar <a href="{{ asset($surgery->after_surgery_file) }}" target="_blank">clique aqui.</a>
                    @endif
                  </p>
                </div>
              </div>
            </div>
          </div>
          <hr class="my-4" />
          <div class="row">
            <div class="col-12">
              <h6 class="heading-small text-muted mb-4">Dados dos materiais</h6>
              @foreach ($surgery->surgeryMaterials as $material)
              <div class="row">
                <div class="col-md-3">
                  <p>
                    <strong>Nome</strong> <br>
                    {{ $material->name }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Quantidade</strong> <br>
                    {{ $material->amount }}
                  </p>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          <hr class="my-4" />
          <div class="row">
            <div class="col-12">
              <h6 class="heading-small text-muted mb-4">Dados do local</h6>
              <div class="row">
                <div class="col-md-3">
                  <p>
                    <strong>Nome</strong> <br>
                    {{ $surgery->surgeryAddress->name }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>CEP</strong> <br>
                    {{ $surgery->surgeryAddress->zip_code }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Endereço</strong> <br>
                    {{ $surgery->surgeryAddress->address }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Número</strong> <br>
                    {{ $surgery->surgeryAddress->number }}
                  </p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <p>
                    <strong>Complemento</strong> <br>
                    {{ $surgery->surgeryAddress->complement }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Estado</strong> <br>
                    {{ $surgery->surgeryAddress->state->title }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Cidade</strong> <br>
                    {{ $surgery->surgeryAddress->city->title }}
                  </p>
                </div>
                <div class="col-md-3">
                  <p>
                    <strong>Imagem</strong> <br>
                    @if ($surgery->surgeryAddress->image)
                    Para visualizar <a href="{{ asset($surgery->surgeryAddress->image) }}" target="_blank">clique aqui.</a>
                    @endif
                  </p>
                </div>
              </div>
            </div>
          </div>
          @if ($surgery->status == "Aguardando início")
          <hr class="my-4" />
          <div class="row">
            <div class="col-12 text-center">
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#destroyModal">Cancelar cirúrgia</button>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  @include('includes.footer')
</div>
@if ($surgery->status == "Aguardando início")
<div class="modal fade" id="destroyModal" tabindex="-1" role="dialog" aria-labelledby="destroyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroyModalLabel">Cancelar cirúrgia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" class="validate" method="post" action="{{ route('company.surgery.destroy', ['surgery' => $surgery->id]) }}">
          @csrf
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label" for="input-reason">Motivo do cancelamento</label>
                <textarea class="form-control" placeholder="Motivo do cancelamento" id="input-reason" name="reason_cancellation" required>{{ old('reason') }}</textarea>
              </div>
            </div>
          </div>
          <hr class="my-4" />
          <div class="row">
            <div class="col-12 text-right">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-danger">Cancelar cirúrgia</button>      
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endif

@if ($surgery->status == "Concluído")
<div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ratingModalLabel">Avaliação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('company.surgery.rating', ['surgery' => $surgery->id]) }}">
          @csrf
          <div class="row">
            <div class="col-12 text-center">
              <fieldset class="rating">
                <input type="radio" id="star5" name="rating" value="5" /><label class="full fas fa-star" for="star5" title="Awesome - 5 stars"></label> 
                <input type="radio" id="star4" name="rating" value="4" /><label class="full fas fa-star" for="star4" title="Pretty good - 4 stars"></label> 
                <input type="radio" id="star3" name="rating" value="3" /><label class="full fas fa-star" for="star3" title="Meh - 3 stars"></label> 
                <input type="radio" id="star2" name="rating" value="2" /><label class="full fas fa-star" for="star2" title="Kinda bad - 2 stars"></label> 
                <input type="radio" id="star1" name="rating" value="1" /><label class="full fas fa-star" for="star1" title="Sucks big time - 1 star"></label> 
              </fieldset>
            </div>
          </div>
          <hr class="my-4" />
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label" for="input-observation">Observação</label>
                <textarea rows="5" class="form-control" placeholder="Observação da cirúrgia" id="input-observation" name="observation">{{ old('observation') }}</textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-right">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Avaliar</button>      
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endif
@endsection