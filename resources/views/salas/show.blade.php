@extends('adminlte::page')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-md-offset-2">
      <div class="card d-flex flex-column align-items-center justify-content-center">
        <div class="panel panel-default">
          <div class="panel-body p-3 mt-2">
            <dl class="row">
              <dt class="col-md-4">Tipo de Sala:</dt>
              <dd class="col-md-8">{{ $sala->tipo_sala }}</dd>

              <dt class="col-md-4">Número de Sala:</dt>
              <dd class="col-md-8">{{ $sala->num_sala }}</dd>
            </dl>

            <div class="form-group">
              <div class="col-md-12 text-center">
                <a href="{{ route('salas.edit', $sala->id_sala) }}" class="btn btn-primary">Editar</a>
                <form action="{{ route('salas.delete', $sala->id_sala) }}" method="POST" style="display: inline-block;">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
