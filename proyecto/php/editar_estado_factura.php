

<?php
include('conecta.php');


$alerta = '';
if (isset($_POST['btnActualizar'])) {
    $id_factura = $_POST['id_factura'];
    $estado_factura = $_POST['estado_factura'];


    $update_estado = $conectar->prepare("UPDATE factura SET estado_factura = ? WHERE id_factura = ?");
    $update_estado->bind_param('si', $estado_factura, $id_factura);
    

    if($update_estado->execute()){
        $alerta = "<script>
        Swal.fire({
            title: 'Se ha actualizado el estado!',
            text: 'El estado de la factura se ha actualizado correctamente!',
            icon: 'success',
            didClose: () => { 
                window.location.href = '../pages/admin/facturas.php';
            }
        });   
    </script>";
    }

    
} else {
    $id_factura = $_GET['id_factura'];
    $sql = "SELECT estado_factura FROM factura WHERE id_factura = $id_factura";
    $resultado = mysqli_query($conectar, $sql);
    $row = mysqli_fetch_assoc($resultado);
    $estado = $row['estado_factura'];

    $estados_posibles = ['Pendiente','Pagado','Vencida','Rechazada'];
    
    
}
?>
