<div class="row card-box">
    <div class="form-group col-9 col-md-9">
        {!! Form::label('cboCliente', 'Cliente: ', array('class' => '')) !!}
        {!! Form::select('cboCliente', array('0'=>'Seleccione'), '0' , array('class' => 'form-control input-sm', 'id' => 'cboCliente')) !!}
    </div>
    <div class="form-group col-2 col-md-2" style="margin-left: 3px">
        {!! Form::label('btnadd', 'Nuevo: ', array('class' => 'control-label col-12 col-md-12')) !!}
        {{-- {!! Form::button('<i class="fa fa-plus fa-lg"></i> Registrar Nuevo', array('class' => 'btn btn-success btn-sm', 'id' => 'btnadd', 'onclick' => '')) !!} --}}
        {!! Form::button('<i class="glyphicon glyphicon-plus"></i> ', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-md', 'id' => 'btnNuevocli', 'onclick' => 'modal (\''.URL::route($ruta["create_new"], array('listar'=>'SI')).'\', \''."Registrar Cliente".'\', this);')) !!}
    </div>
    <div class="form-group col-6 col-md-6">
        {!! Form::label('cboForma_pago', 'Forma de pago: ', array('class' => '')) !!}
        {!! Form::select('cboForma_pago', $cboFormaPago, '0' , array('class' => 'form-control input-sm', 'id' => 'cboForma_pago')) !!}
    </div>
    <div class="form-group col-6 col-md-6"  style="margin-left: 3px">
        {!! Form::label('cboComprobante', 'Comprobante Pago: ', array('class' => '')) !!}
        {!! Form::select('cboComprobante', $cboComprobante, '0' , array('class' => 'form-control input-sm', 'id' => 'cboComprobante')) !!}
    </div>
</div>