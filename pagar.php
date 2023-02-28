<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>

<?php 
if($_POST){

    $total=0;
    $SID=session_id();
    $Correo=$_POST['email'];

    foreach($_SESSION['CARRITO'] as $indice=>$producto){

        $total=$total+($producto['PRECIO']*$producto['CANTIDAD']);
    }
            $sentencia=$pdo->prepare("INSERT INTO `tblventas` 
                    (`id`, `clavetransaccion`, `pypaldatos`, `fecha`, `correo`, `total`, `status`) 
            VALUES (NULL,:clavetransaccion,'', NOW(),:correo,:total,'pendiente');");
            

            $sentencia->bindParam(":clavetransaccion",$SID);
            $sentencia->bindParam(":correo",$Correo);
            $sentencia->bindParam(":total",$total);
            $sentencia->execute();
            $idVenta=$pdo->lastInsertId();

            foreach($_SESSION['CARRITO'] as $indice=>$producto){
            $sentencia=$pdo->prepare("INSERT INTO 
            `tbldetalleventa` (`id`, `idventa`, `idproducto`, `preciounitario`, `cantidad`, `descargado`) 
            VALUES (NULL,:idventa,:idproducto,:preciounitario,:cantidad,'0');");

                        $sentencia->bindParam(":idventa",$idVenta);
                        $sentencia->bindParam(":idproducto",$producto['ID']);
                        $sentencia->bindParam(":preciounitario",$producto['PRECIO']);
                        $sentencia->bindParam(":cantidad",$producto['CANTIDAD']);
                        $sentencia->execute();
                        

            }
    echo "<h3>".$total."</h3>";
        }
?>


<div class="jumbotron text-center">
    <h1 class="display-4">¡Paso Final!</h1>
    <hr class="my-4">
    <p class="lead">Muy bien, tu compra se realizó por la cantidad de....
        <h4>$<?php echo number_format($total,2); ?></h4>
    </p>
    
    <p>Los productos podran ser empaquetados una vez que se procese el pago :)</br>
    <p>Que disfrutes de tu compra.</p></br>
    <p>Tu pedido llegara en un máximo de 2 días hábiles</p></br>
    <strong>(Para aclaraciones :danielgs.itc19@gmail.com)</strong>
    <p><a href="index.php" class="btn btn-primary ">Volver a comprar</a>
            </p>
    </p>
</div>



</script>
    <?php
include 'templates/pie.php';
?>