<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial del Paciente</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; background: #fff; }
        .header { background: #17a2b8; color: white; padding: 20px 30px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; font-weight: bold; margin-bottom: 4px; }
        .header p { font-size: 11px; opacity: 0.85; }
        .header .fecha { float: right; text-align: right; font-size: 11px; margin-top: -38px; }
        .seccion { margin: 0 30px 20px 30px; }
        .seccion-titulo { background-color: #17a2b8; color: white; padding: 7px 14px; font-size: 13px; font-weight: bold; border-radius: 4px; margin-bottom: 10px; }
        .datos-grid { width: 100%; border-collapse: collapse; }
        .datos-grid td { padding: 6px 10px; font-size: 11px; border-bottom: 1px solid #e9ecef; }
        .datos-grid td.label { font-weight: bold; color: #555; width: 15%; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-activo    { background:#d4edda; color:#155724; }
        .badge-inactivo  { background:#f8d7da; color:#721c24; }
        .badge-si        { background:#d4edda; color:#155724; }
        .badge-no        { background:#e2e3e5; color:#383d41; }
        .badge-atendido  { background:#d4edda; color:#155724; }
        .badge-pendiente { background:#fff3cd; color:#856404; }
        .enfermedades { padding: 6px 10px; }
        .enfermedad-tag { display: inline-block; background: #e8f8fb; color: #17a2b8; border: 1px solid #17a2b8; padding: 3px 10px; border-radius: 10px; font-size: 10px; margin: 2px; }
        .tabla-citas { width: 100%; border-collapse: collapse; font-size: 10.5px; }
        .tabla-citas thead tr { background-color: #17a2b8; color: white; }
        .tabla-citas thead th { padding: 8px 10px; text-align: left; font-weight: bold; }
        .tabla-citas tbody tr:nth-child(even) { background-color: #f0fafc; }
        .tabla-citas tbody tr:nth-child(odd) { background-color: #ffffff; }
        .tabla-citas tbody td { padding: 7px 10px; border-bottom: 1px solid #e9ecef; vertical-align: top; }
        .sin-citas { text-align: center; padding: 20px; color: #999; font-style: italic; }
        .diagnostico-box { background: #f8f9fa; border-left: 4px solid #17a2b8; padding: 10px 14px; font-size: 11px; border-radius: 0 4px 4px 0; color: #444; }
        .footer { margin: 30px 30px 0 30px; border-top: 2px solid #17a2b8; padding-top: 10px; text-align: center; font-size: 10px; color: #999; }
        .resumen-grid { width: 100%; border-collapse: collapse; }
        .resumen-grid td { width: 33%; text-align: center; padding: 10px; border: 1px solid #e9ecef; }
        .resumen-grid .numero { font-size: 22px; font-weight: bold; color: #17a2b8; }
        .resumen-grid .etiqueta { font-size: 10px; color: #777; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="header">
        <h1>VitalSys</h1>
        <p>Historial Clinico del Paciente</p>
        <div class="fecha">
            Fecha de emision:<br>
            <strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</strong>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Datos Personales</div>
        <table class="datos-grid">
            <tr>
                <td class="label">Codigo:</td>
                <td>{{ $paciente->cod_paciente }}</td>
                <td class="label">N Documento:</td>
                <td>{{ $paciente->num_doc }}</td>
            </tr>
            <tr>
                <td class="label">Nombre:</td>
                <td>{{ $paciente->nombre }} {{ $paciente->apellido }}</td>
                <td class="label">Sexo:</td>
                <td>{{ $paciente->sexo }}</td>
            </tr>
            <tr>
                <td class="label">Ciudad:</td>
                <td>{{ $paciente->ciudad }}</td>
                <td class="label">Departamento:</td>
                <td>{{ $paciente->departamento }}</td>
            </tr>
            <tr>
                <td class="label">Direccion:</td>
                <td>{{ $paciente->direccion ?? '-' }}</td>
                <td class="label">Edad:</td>
                <td>{{ $paciente->edad ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tiene IPS:</td>
                <td>
                    <span class="badge {{ $paciente->tiene_ips == 'si' ? 'badge-si' : 'badge-no' }}">
                        {{ strtoupper($paciente->tiene_ips ?? '-') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    @if($paciente->diagnostico)
    <div class="seccion">
        <div class="seccion-titulo">Diagnostico</div>
        <div class="diagnostico-box">{{ $paciente->diagnostico }}</div>
    </div>
    @endif

    @if($paciente->comentario)
    <div class="seccion">
        <div class="seccion-titulo">Comentario</div>
        <div class="diagnostico-box">{{ $paciente->comentario }}</div>
    </div>
    @endif

    <div class="seccion">
        <div class="seccion-titulo">Tipos de Enfermedad</div>
        <div class="enfermedades">
            @forelse($paciente->tiposDeEnfermedad as $enfermedad)
                <span class="enfermedad-tag">{{ $enfermedad->tipo_de_enfermedad }} - {{ $enfermedad->etapa_enfermedad }}</span>
            @empty
                <span style="color:#999; font-style:italic;">Sin enfermedades registradas</span>
            @endforelse
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Resumen de Consultas</div>
        <table class="resumen-grid">
            <tr>
                <td>
                    <div class="numero">{{ $paciente->citas->count() }}</div>
                    <div class="etiqueta">Total Citas</div>
                </td>
                <td>
                    <div class="numero" style="color:#28a745;">{{ $paciente->citas->where('estado', 'atendido')->count() }}</div>
                    <div class="etiqueta">Atendidas</div>
                </td>
                <td>
                    <div class="numero" style="color:#ffc107;">{{ $paciente->citas->where('estado', 'Pendiente')->count() }}</div>
                    <div class="etiqueta">Pendientes</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Historial de Consultas</div>
        @if($paciente->citas->count() > 0)
        <table class="tabla-citas">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Medico</th>
                    <th>Sala</th>
                    <th>Tipo Consulta</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paciente->citas->sortByDesc('fec_inicio') as $cita)
                <tr>
                    <td>{{ $cita->id_cita }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fec_inicio)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fec_fin)->format('d/m/Y H:i') }}</td>
                    <td>{{ $cita->medico->nombre ?? '-' }}</td>
                    <td>{{ isset($cita->sala) ? $cita->sala->tipo_sala.' - '.$cita->sala->num_sala : '-' }}</td>
                    <td>{{ $cita->tipoConsulta->descripcion ?? '-' }}</td>
                    <td>{{ $cita->observaciones ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $cita->estado == 'atendido' ? 'badge-atendido' : 'badge-pendiente' }}">
                            {{ strtoupper($cita->estado) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="sin-citas">No hay consultas registradas para este paciente.</div>
        @endif
    </div>

    <div class="footer">
        <p>Documento generado automaticamente por el sistema VS - VitalSyS</p>
        <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} - Confidencial</p>
    </div>

</body>
</html>