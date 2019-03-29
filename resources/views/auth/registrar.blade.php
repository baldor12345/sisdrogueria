@include('auth.header')
<div class="wrapper-page" style="margin: 7.5% auto;max-width: 840px;">
    <div class="card-box">
        <div class="panel-body">


            <form class="form-horizontal"  id="registro" role="form" method="POST" action="{{ url('/registro') }}">
                {{ csrf_field() }}

            <div id="divMensajeError"></div>
            

            <div class="col-lg-6 col-md-6 col-sm-6">
            

            <div class="text-center">
                <a class="logo-lg"><i class="md md-equalizer"></i> <span>Registrar Empresa</span> </a>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif



                <div class="form-group{{ $errors->has('ruc') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <input name="ruc" class="form-control" type="text" placeholder="RUC" autofocus>
                        <i class="md md-account-circle form-control-feedback l-h-34"></i>
                        @if ($errors->has('ruc'))
                            <span class="help-block">
                                <strong>{{ $errors->first('ruc') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="razonsocial" class="form-control" type="text" placeholder="Razón Social">
                        <i class="md md-info-outline form-control-feedback l-h-34"></i>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <input id="emailempresa" type="email" class="form-control" name="emailempresa" value="{{ $email or old('email') }}" placeholder="Correo Electrónico"  autofocus>
                        <i class="md md-mail form-control-feedback l-h-34"></i>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="telefonosucursal" class="form-control" type="text" placeholder="Teléfono">
                        <i class="md md-phone form-control-feedback l-h-34"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="direccion" class="form-control" type="text" placeholder="Dirección">
                        <i class="md md-place form-control-feedback l-h-34"></i>
                    </div>
                </div>


        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">


            <div class="text-center">
                <a class="logo-lg"><i class="md md-equalizer"></i> <span>Registrar Administrador</span> </a>
            </div>

                <div class="form-group{{ $errors->has('dni') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <input name="dni" class="form-control" type="text" placeholder="DNI" autofocus>
                        <i class="md md-account-circle form-control-feedback l-h-34"></i>
                        @if ($errors->has('dni'))
                            <span class="help-block">
                                <strong>{{ $errors->first('dni') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="nombres" class="form-control" type="text" placeholder="Nombres" autofocus>
                        <i class="md md-info-outline form-control-feedback l-h-34"></i>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="apellidos" class="form-control" type="text"  placeholder="Apellidos">
                        <i class="md md-info-outline form-control-feedback l-h-34"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="telefono" class="form-control" type="text" placeholder="Celular">
                        <i class="md md-phone form-control-feedback l-h-34"></i>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="Correo Electrónico"  autofocus>
                        <i class="md md-mail form-control-feedback l-h-34"></i>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" >
                        <i class="md md-vpn-key form-control-feedback l-h-34"></i>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirme Contraseña" >
                        <i class="md md-vpn-key form-control-feedback l-h-34"></i>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                
            </div>
            <div class="form-group" style="text-align: center">
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                        <a class="btnGuardar btn btn-primary btn-custom w-md waves-effect waves-light" onclick="guardarRegistro('{{ url('/registrovalidator') }}'); ">
                            Registrar
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ url('/login') }}" class="btn btn-success btn-custom w-md waves-effect waves-light">
                            Cancelar
                        </a>
                    </div>
                    <div class="col-md-3"></div>
                </div>

            </form>
        </div>    
    </div>
</div>


<script>
/*$('.btnGuardar').on('click', function(){
        guardarRegistro("{{ url('/registrovalidator') }}");    
});*/
    /*
$('#departamento').change(function(event){
    $.get("provincias/"+event.target.value+"",function(response, departamento){
        $('#provincia').empty();
        $("#provincia").append("<option disabled selected>SELECCIONE PROVINCIA</option>");
        for(i=0; i<response.length; i++){
            $("#provincia").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
        }
    }, 'json');
});

$('#provincia').change(function(event){
    $.get("distritos/"+event.target.value+"",function(response, provincia){
        $('#distrito').empty();
        $("#distrito").append("<option disabled selected>SELECCIONE DISTRITO</option>");
        for(i=0; i<response.length; i++){
            $("#distrito").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
        }
    }, 'json');
});

*/

</script>

@include('auth.footer')
