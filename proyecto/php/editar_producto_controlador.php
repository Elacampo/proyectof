<?php
    include("conecta.php");
    include("phpqrcode/qrlib.php");

    if(isset($_POST['btnEnviar'])){

        $id = $_POST['producto_id'];

        $nombre = ucfirst(($_POST['nombre']));
        $descripcion = ucfirst(($_POST['descripcion']));
        $categoria = $_POST['categoria']; // <- Este es el SELECT dinamico para las categorias
        $unidad_medida = $_POST['unidad_medida'];
        $precio = $_POST['precio_producto']; // <- Este es el SELECT dinamico para los precios
        

        $sql_anterior = "SELECT ubicacion FROM producto WHERE id = $id";
        $resultado = mysqli_query($conectar, $sql_anterior);
        $row = mysqli_fetch_assoc($resultado);
        $ubicacionAnterior = $row['ubicacion'];

        $area = isset($_POST['area']) ? $_POST['area'] : $ubicacionAnterior;
        
           
        $fecha_ingreso = date('Y-m-d', strtotime($_POST['fecha_ingreso']));
        $fecha_vencimiento = date('Y-m-d', strtotime($_POST['fecha_vencimiento']));
        $proveedor = $_POST['proveedor'];

         // Verificar si se subió un archivo de imagen
        if ($_FILES['imagen']['size'] > 0) {
            $nombreImagen = $_FILES['imagen']['name']; // Nombre original del archivo
            $rutaTempImagen = $_FILES['imagen']['tmp_name']; // Ruta temporal del archivo en el servidor
            $rutaDestinoImagen = '../../img/productos/' . $nombreImagen; // Ruta donde se desea guardar la imagen
            move_uploaded_file($rutaTempImagen, $rutaDestinoImagen);
        } else {    
            // Si no se subió un archivo nuevo, mantener la imagen existente
            $sqlImagenActual = "SELECT imagen_producto FROM producto WHERE id = $id";
            $resultadoImagenActual = mysqli_query($conectar, $sqlImagenActual);
            $filaImagenActual = mysqli_fetch_assoc($resultadoImagenActual);
            $nombreImagen = $filaImagenActual['imagen_producto'];
        }

        $verificar_sql = "SELECT * FROM producto WHERE nombre_producto='$nombre' AND id != $id";
        
        $verificar_resultado = mysqli_query($conectar, $verificar_sql);
        if(mysqli_num_rows($verificar_resultado) > 0){
            echo "<script>
                Swal.fire({
                    title: 'Error al actualizar producto!',
                    text: 'El nombre del producto ya se encuentra registrado en el sistema!',
                    icon: 'error'
                });   
            </script>";
        } else{

            $sql_stock = "SELECT producto FROM stock WHERE producto = $id";
            $resultado_stock = mysqli_query($conectar, $sql_stock);
            $sql_capacidad = "SELECT capacidad_max FROM espacio WHERE id_bodega = '$area'";
            $resultado_capacidad = mysqli_query($conectar, $sql_capacidad);
            $row_capacidad = mysqli_fetch_assoc($resultado_capacidad);

            $sql_verificar_espacio = "SELECT ubicacion FROM producto WHERE id = $id ";
            $resultado_verificar_espacio = mysqli_query($conectar, $sql_verificar_espacio);
            $row_verificar_espacio = mysqli_fetch_assoc($resultado_verificar_espacio);
            $espacio_actual_producto = $row_verificar_espacio['ubicacion'];

            

            if (mysqli_num_rows($resultado_stock) > 0) {
                $stock_producto = $_POST['stock'];
                $sql = "UPDATE producto AS p
                JOIN stock AS s ON p.id = s.producto
                SET p.nombre_producto = '$nombre', 
                    p.categoria = '$categoria', 
                    p.unidad_medida = '$unidad_medida', 
                    p.precio_producto = '$precio', 
                    p.ubicacion = '$area', 
                    p.fecha_ingreso = '$fecha_ingreso', 
                    p.fecha_ven = '$fecha_vencimiento', 
                    p.proveedor = '$proveedor', 
                    p.descripcion = '$descripcion', 
                    p.imagen_producto = '$nombreImagen', 
                    s.stock_actual = '$stock_producto'
                WHERE p.id = $id";

            if ($area === $ubicacionAnterior) {
                // Actualizar el estado anterior del espacio
                if ($stock_producto >= $row_capacidad['capacidad_max']) {
                    $sql_actualizar_estado_anterior = "UPDATE espacio SET estado = 'Completo' WHERE id_bodega = '$ubicacionAnterior'";
                } else {
                    $sql_actualizar_estado_anterior = "UPDATE espacio SET estado = 'Ocupado' WHERE id_bodega = '$ubicacionAnterior'";
                }
                $resultado_actualizar_estado_anterior = mysqli_query($conectar, $sql_actualizar_estado_anterior);
            }else{
                $sql_actualizar_estado_anterior = "UPDATE espacio set estado = 'Disponible' WHERE id_bodega = '$ubicacionAnterior'";
                $resultado_actualizar_estado_anterior = mysqli_query($conectar, $sql_actualizar_estado_anterior);
            }
     
           
            if($stock_producto >= $row_capacidad['capacidad_max']){
                $sql_actualizar_estado = "UPDATE espacio SET estado = 'Completo' WHERE id_bodega = '$area'";
                $resultado_actualizar_estado_actual = mysqli_query($conectar, $sql_actualizar_estado);
            }else{            
                $sql_actualizar_estado = "UPDATE espacio SET estado = 'Ocupado' WHERE id_bodega = '$area'";
                $resultado_actualizar_estado_actual = mysqli_query($conectar, $sql_actualizar_estado);
            }
                
            } else {

                if($espacio_actual_producto === null){
                    $sql = "UPDATE producto SET
                        nombre_producto = '$nombre', 
                        categoria = '$categoria', 
                        unidad_medida = '$unidad_medida', 
                        precio_producto = '$precio',  
                        fecha_ingreso = '$fecha_ingreso', 
                        fecha_ven = '$fecha_vencimiento', 
                        proveedor = '$proveedor', 
                        descripcion = '$descripcion', 
                        imagen_producto = '$nombreImagen'
                        WHERE id = $id"; 
                } else{
                    $sql = "UPDATE producto SET
                            nombre_producto = '$nombre', 
                            categoria = '$categoria', 
                            unidad_medida = '$unidad_medida', 
                            precio_producto = '$precio', 
                            ubicacion = '$area', 
                            fecha_ingreso = '$fecha_ingreso', 
                            fecha_ven = '$fecha_vencimiento', 
                            proveedor = '$proveedor', 
                            descripcion = '$descripcion', 
                            imagen_producto = '$nombreImagen'
                            WHERE id = $id"; 
                }    
                         
            }  
            
            $resultado = mysqli_query($conectar, $sql);

            if($resultado){
                $sql = "SELECT * FROM producto WHERE id='$id'";
                $resultado = mysqli_query($conectar, $sql);
                $row = mysqli_fetch_assoc($resultado);

                $nombre = $row['nombre_producto'];  
                $unidad_medida = $row['unidad_medida'];
                
                $precio =  $row['precio_producto'];
                $imagen = $row['imagen_producto'];
                $descripcion = $row['descripcion'];

                $fecha_vencimiento = $row['fecha_ven'];

                if (empty($imagen) || $imagen == NULL) {
                    $imagen = 'no-imagen.jpg';
                }

                $sql_comprobar_stock = "SELECT producto FROM stock WHERE producto = $id";
                $resultado_stock = mysqli_query($conectar, $sql_comprobar_stock);

                $activarH4 = FALSE;
                if(mysqli_num_rows($resultado_stock) > 0){
                    $activarH4 = FALSE; 
                }else{
                    $activarH4 = TRUE; 
                }
                
                //Consulta para obtener todas las categorias activas. 
                $sql_categorias = "SELECT id_cat, nombre FROM categoria WHERE activo = 1";
                $resultCategorias = $conectar ->query($sql_categorias);
                
                //Consulta para obtener el cod de bodega
                $sql_espacio = "SELECT id_bodega, area, (CASE WHEN id_bodega = '" . $row['ubicacion'] . "' THEN 'selected' ELSE '' END) AS selected_area FROM espacio WHERE estado = 'Disponible' OR id_bodega = '" . $row['ubicacion'] . "'";
                $result_espacio = $conectar->query($sql_espacio);

                // Consultar para obtener el stock actual
                $sqlStock = "SELECT SUM(stock_actual) AS stock_total FROM stock WHERE producto = " . $row['id'];
                $resultStock = $conectar->query($sqlStock);
                $stock_row = $resultStock->fetch_assoc();
                $stock_actual = $stock_row['stock_total'] ?? 0;

                //Consulta para obtener el nombre del proveedor
                $sql_proveedor = "SELECT proveedor_id, nombre FROM proveedores";
                $resultProveedor = $conectar->query($sql_proveedor);

                

                echo "<script>
                    Swal.fire({
                        title: 'Se ha actualizado el producto!',
                        text: 'Los datos se han actualizado correctamente!',
                        icon: 'success',
                        didClose: () => { 
                            window.location.href = 'productos.php';
                        }
                       
                    });   
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'No se ha actualizado el producto!',
                        text: 'Los datos no se han podido actualizar correctamente!',
                        icon: 'error'
                    });
                    window.location.assign('productos.php');
                </script>";
            }
        }

        mysqli_close($conectar);

    } elseif(isset($_POST['btnGenerar'])){
    
        $path = '../../img/codigos_qr/';
        $qrcode = $path.time().".png";
        $qr_image = time().".png";
        
        $id = $_POST['producto_id'];

        $qr_texto = 'http://localhost/pi_dos/pages/qr_verificar.php?id='.$id; // CAMBIAR URL AL SUBIR A UN SERVIDOR
        $sql_qr = "UPDATE producto SET codigo = '$qr_texto', qr_imagen = '$qr_image' WHERE id = $id";
        $resultado_qr = mysqli_query($conectar, $sql_qr);
        if($resultado_qr){
            $sql = "SELECT * FROM producto WHERE id='$id'";
            $resultado = mysqli_query($conectar, $sql);
            $row = mysqli_fetch_assoc($resultado);
            

            $nombre = $row['nombre_producto'];  
            $unidad_medida = $row['unidad_medida'];
            
            $precio =  $row['precio_producto'];
            $imagen = $row['imagen_producto'];
            $descripcion = $row['descripcion'];

            $fecha_vencimiento = $row['fecha_ven'];

            if (empty($imagen) || $imagen == NULL) {
                $imagen = 'no-imagen.jpg';
            }

            $sql_comprobar_stock = "SELECT producto FROM stock WHERE producto = $id";
            $resultado_stock = mysqli_query($conectar, $sql_comprobar_stock);

            $activarH4 = FALSE;
            if(mysqli_num_rows($resultado_stock) > 0){
                $activarH4 = FALSE; 
            }else{
                $activarH4 = TRUE; 
            }

            //Consulta para obtener todas las categorias activas. 
            $sql_categorias = "SELECT id_cat, nombre FROM categoria WHERE activo = 1";
            $resultCategorias = $conectar ->query($sql_categorias);
            
            //Consulta para obtener el cod de bodega
            $sql_espacio = "SELECT id_bodega, area, (CASE WHEN id_bodega = '" . $row['ubicacion'] . "' THEN 'selected' ELSE '' END) AS selected_area FROM espacio WHERE estado = 'Disponible' OR id_bodega = '" . $row['ubicacion'] . "'";
            $result_espacio = $conectar->query($sql_espacio);

            // Consultar para obtener el stock actual
            $sqlStock = "SELECT SUM(stock_actual) AS stock_total FROM stock WHERE producto = " . $row['id'];
            $resultStock = $conectar->query($sqlStock);
            $stock_row = $resultStock->fetch_assoc();
            $stock_actual = $stock_row['stock_total'] ?? 0;

            //Consulta para obtener el nombre del proveedor
            $sql_proveedor = "SELECT proveedor_id, nombre FROM proveedores";
            $resultProveedor = $conectar->query($sql_proveedor);
           
            echo
            "<script>
                    Swal.fire({
                        title: 'Se ha generado el QR!',
                        text: 'El QR se ha generado correctamente!',
                        icon: 'success'
                    });   
                </script>";
        }
        QRcode :: png($qr_texto,$qrcode, 'H',4,4);      

        
    } else{

        $id = $_GET['id'];
        $sql = "SELECT * FROM producto WHERE id='$id'";
        $resultado = mysqli_query($conectar, $sql);
        $row = mysqli_fetch_assoc($resultado);
        

        $nombre = $row['nombre_producto'];  
        $unidad_medida = $row['unidad_medida'];
        
        $precio =  $row['precio_producto'];
        
        $imagen = $row['imagen_producto'];
        $descripcion = $row['descripcion'];

        $fecha_vencimiento = $row['fecha_ven'];

        if (empty($imagen) || $imagen == NULL) {
            $imagen = 'no-imagen.jpg';
        }

        $sql_comprobar_stock = "SELECT producto FROM stock WHERE producto = $id";
        $resultado_stock = mysqli_query($conectar, $sql_comprobar_stock);

        $activarH4 = FALSE;
        if(mysqli_num_rows($resultado_stock) > 0){
            $activarH4 = FALSE; 
        }else{
            $activarH4 = TRUE; 
        }

        //Consulta para obtener todas las categorias activas. 
        $sql_categorias = "SELECT id_cat, nombre FROM categoria WHERE activo = 1";
        $resultCategorias = $conectar ->query($sql_categorias);
        
        //Consulta para obtener el cod de bodega
        $sql_espacio = "SELECT id_bodega, area FROM espacio WHERE estado = 'Disponible'";
        $result_espacio = $conectar->query($sql_espacio);

        // Consultar para obtener el stock actual
        $sqlStock = "SELECT SUM(stock_actual) AS stock_total FROM stock WHERE producto = " . $row['id'];
        $resultStock = $conectar->query($sqlStock);
        $stock_row = $resultStock->fetch_assoc();
        $stock_actual = $stock_row['stock_total'] ?? 0;

        //Consulta para obtener el nombre del proveedor
        $sql_proveedor = "SELECT proveedor_id, nombre FROM proveedores";
        $resultProveedor = $conectar->query($sql_proveedor);


        mysqli_close($conectar);
    }

?>