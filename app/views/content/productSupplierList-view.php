<div class="container is-fluid mb-6">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle"><i class="fas fa-boxes fa-fw"></i> &nbsp; Productos por proveedor</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        use app\controllers\productSupplierController;
        $insProductSupplier = new productSupplierController();
    ?>
    <div class="columns">



        <div class="column is-one-third">
            <h2 class="title has-text-centered">Proveedores</h2>
            <?php

                $datos_proveedores=$insProductSupplier->seleccionarDatos("Normal","proveedor","*",0);

                if($datos_proveedores->rowCount()>0){
                    $datos_proveedores=$datos_proveedores->fetchAll();
                    foreach($datos_proveedores as $row){
                        echo '<a href="'.APP_URL.$url[0].'/'.$row['proveedor_id'].'/" class="button is-link is-inverted is-fullwidth">'.$row['proveedor_nombre'].'</a>';
                    }
                }else{
                    echo '<p class="has-text-centered" >No hay proveedores registrados</p>';
                }
            ?>
        </div>



        <div class="column pb-6">
            <?php
                $proveedor_id=(isset($url[1])) ? $url[1] : 0;

                $proveedor=$insProductSupplier->seleccionarDatos("Unico","proveedor","proveedor_id",$proveedor_id);
                if($proveedor->rowCount()>0){

                    $proveedor=$proveedor->fetch();

                    echo '
                        <h2 class="title has-text-centered">'.$proveedor['proveedor_nombre'].'</h2>
                        <p class="has-text-centered pb-6" >'.$proveedor['proveedor_contacto'].'</p>
                    ';

                    echo $insProductSupplier->listarProductoProveedorControlador($url[2],10,$url[0],"",$url[1]);
                }else{
                    echo '
                    <p class="has-text-centered pb-6"><i class="far fa-grin-wink fa-5x"></i></p>
                    <h2 class="has-text-centered title" >Seleccione un proveedor para empezar</h2>';
                }
            ?>
        </div>

    </div>
</div>
