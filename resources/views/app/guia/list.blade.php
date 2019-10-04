@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">
	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ $contador }}</td>
			<td>{{ $value->serie.'-'.$value->numero }}</td>
			<td>{{ $value->doc_identidad }}</td>
			<td>{{ $value->nombres_destinatario }}</td>
			<td>{{ $value->fecha_emision }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div> Ver', array('onclick' => 'modal (\''.URL::route($ruta["verdetalle_g"], array($value->id)).'\', \''.'Detalle Guia de Remision'.'\', this);', 'class' => 'btn btn-xs btn-info')) !!}</td>
			<td>{!! Form::button('<i class="glyphicon glyphicon-download-alt"></i> PDF', array('class' => 'btn btn-primary waves-effect waves-light btn-sm', 'id' => 'btnPDF'.$entidad, 'onclick' => 'imprimirpdf(\''.URL::route($ruta["pdfGuia"], array('guia_id'=>$value->id)).'\');')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.': '.$value->serie_doc.'-'.$value->numero_doc.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function() {
	
}); 
function imprimirpdf(url_pdf) {
		//console.log("ruta: "+url_pdf);
		var a = document.createElement("a");
		a.target = "_blank";
		a.href = url_pdf;
		a.click();
	}
</script>
@endif

