<div class="container is-fluid mb-6">
    <h1 class="title">Proveedores</h1>
    <h2 class="subtitle"><i class="fas fa-truck-loading fa-fw"></i> &nbsp; Lista de proveedores</h2>
</div>

<div class="container pb-6 pt-6">

    <div class="form-rest mb-6 mt-6"></div>

    <?php
        use app\controllers\providerController; 

        $insProveedor = new providerController();

        $pagina_actual = (isset($url[1]) && is_numeric($url[1])) ? (int) $url[1] : 1;
        $url_base = $url[0];

        echo $insProveedor->listarProveedorControlador($pagina_actual, 15, $url_base, "");
    ?>
</div>