
<?php
    $servicio = $this->editar();
?>

<h2 align="center">Edición de SERVICIOS</h2>
<div class="crud-container">
    
    <div class="crud-box row align-items-md-stretch">
        <div class="crud-box col-md-5">
            <div class="h-100 p-5 text-dark bg-light border rounded-3 form-container">
                <form  action="?controlador=servicios&accion=guardarCambios" method="post">
                    <div class="input-group">
                        <div class="input-group" class="mb-3">
                            <label for="" class="form-label">Nombre</label>
                            <input type="hidden" name="id" value="<?php echo $servicio[0]->id_servicio; ?>"/>
                            <input
                                type="text"
                                class="form-control"
                                name="nombre"
                                id=""
                                aria-describedby="helpId"
                                placeholder="Ingrese nombre del servicio"
                                value="<?php echo $servicio[0]->nombre; ?>"
                            />
                        </div>
                        
                        <div class="input-group" class="mb-3">
                            <label for="" class="form-label">Descripción</label>
                            <input
                                type="text"
                                class="form-control"
                                name="descripcion"
                                id=""
                                aria-describedby="helpId"
                                placeholder="Ingrese la descripción del servicio"
                                value="<?php echo $servicio[0]->descripcion; ?>"
                            />
                        </div>
                        
                        <div class="input-group" class="mb-3">
                            <label for="" class="form-label">Precio</label>
                            <input
                                type="number"
                                class="form-control"
                                name="precio"
                                id=""
                                aria-describedby="helpId"
                                placeholder="Ingrese el precio del servicio"
                                value="<?php echo $servicio[0]->precio; ?>"
                            />
                            
                        </div>


                        
                        <div class="input-group mb-3 justify-content-center gap-5" >
                            <button type="submit" name="guardar" class="crud-button">
                                Guardar
                            </button>

                            <a class ="btn crud-buttonC" href="?controlador=servicios&accion=inicio"> Cancelar </a>
                            
                        </div>
                    
                </form>
                
            </div>
        </div>
    </div>
</div>
