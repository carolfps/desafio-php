<?php
    session_start();
    //se não tiver logado redireciona para a pagina de login
    if(!isset($_SESSION["email"])){
        header("Location: login.php");
    }

    if($_GET["id"] and file_exists('cadastprodutos.json')){
        $produtoscadast = file_get_contents("cadastprodutos.json");
        $produtoscadast = json_decode($produtoscadast, true);
        $posicao = array_search($_GET["id"], array_column($produtoscadast, 'id'));        
    }
    
    //se a pessoa clicar em excluir, os dados do produto serão apagados e a foto do produto também
    if(isset($_POST["excluir"])){
        $produtoscadast = file_get_contents("cadastprodutos.json");
        $produtoscadast = json_decode($produtoscadast, true);
        $posicao = array_search($_GET["id"], array_column($produtoscadast, 'id')); 
        //deletando foto do produto
        $fotoproduto = $produtoscadast[$posicao]["Enderecofoto"];
        unlink($fotoproduto);
        //deletando dados do produto
        unset($produtoscadast[$posicao]);
        //reordenando o array
        $produtosord=array_values($produtoscadast);
        $jsonData = json_encode($produtosord);
        file_put_contents("cadastprodutos.json", $jsonData);
        header('Location: indexprodutos.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>
<head>
    <title>Excluir Produto</title>
    <style>
    body{
        background: #F5FFE6; 
    }
    </style>
</head>
<body>
    <?php require('./includes/navbar.php'); ?>
    <div class="container">
    <h3 class="mt-4 font-weight-bold">Excluir Produto</h3>
        <div class="card mb-3" style="width: 100%;">
        <img src="<?php if($produtoscadast[$posicao]["Enderecofoto"]){echo $produtoscadast[$posicao]["Enderecofoto"];} ?>" class="img-fluid" style="object-fit: cover; object-position: middle;width: 100%; max-height: 300px;" alt="produto">
            <div class="card-body">
                <h5 class="card-title"><?php if($produtoscadast[$posicao]["nome"]){echo $produtoscadast[$posicao]["nome"];} ?></h5>
                <p class="card-text"><?php if($produtoscadast[$posicao]["descricao"]){echo $produtoscadast[$posicao]["descricao"];} ?></p>
                <p class="card-text">Preço: R$<?php if($produtoscadast[$posicao]["preco"]){echo $produtoscadast[$posicao]["preco"];} ?></p>
                <form method="post">
                    <input class="btn btn-danger btn-block" name="excluir" type="submit" value="Excluir">
                </form>
            </div>
        </div>
    </div>

</body>

</html>