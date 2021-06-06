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
              <li class="breadcrumb-item active" aria-current="page">Atendente</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{ route('company.surgery.create') }}" class="btn btn-primary">Nova cirúrgia</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Lista de cirúrgias</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col" class="sort" data-sort="specialty">Especialidade</th>
                <th scope="col" class="sort" data-sort="company">Empresa</th>
                <th scope="col" class="sort" data-sort="instrumentalist">Instrumentador</th>
                <th scope="col" class="sort" data-sort="state">Estado</th>
                <th scope="col" class="sort" data-sort="city">Cidade</th>
                <th scope="col" class="sort" data-sort="status">Status</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody class="list">
              @foreach ($surgeries as $surgery)
              <tr>
                <th>
                  {{ $surgery->specialty->name }}
                </th>
                <td>
                  {{ $surgery->company->user->name }}
                </td>
                <td>
                  {{ !$surgery->instrumentalist ? null : $surgery->instrumentalist->user->name }}
                </td>
                <td>
                  {{ $surgery->surgeryAddress->state->title }}
                </td>
                <td>
                  {{ $surgery->surgeryAddress->city->title }}
                </td>
                <td>
                  <span class="badge badge-dot mr-4">
                    <i class="{{ $translate['status'][$surgery->status] }}"></i>
                    <span>{{ $surgery->status }}</span>
                  </span>
                </td>
                <td class="text-right">
                  <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      <a class="dropdown-item" href="{{ route('company.surgery.show', ['surgery' => $surgery->id]) }}">Visualizar</a>
                      <a class="dropdown-item" href="{{ route('company.surgery.edit', ['surgery' => $surgery->id]) }}">Editar</a>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-4">
          {!! $surgeries->links('includes.paginator') !!}
          {{--
          <nav aria-label="...">
            <ul class="pagination justify-content-end mb-0">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">
                  <i class="fas fa-angle-left"></i>
                  <span class="sr-only">Previous</span>
                </a>
              </li>
              <li class="page-item active">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
              </li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#">
                  <i class="fas fa-angle-right"></i>
                  <span class="sr-only">Next</span>
                </a>
              </li>
            </ul>
          </nav>
          --}}
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  @include('includes.footer')
</div>
@endsection