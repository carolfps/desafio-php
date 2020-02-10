<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Lista de Produtos</title>
</head>

<body>
    <?php require('./includes/navbar.php');?>
    <?php 
    //se não tiver logado redireciona para a pagina de login
    if(!isset($_SESSION["email"])){
        header("Location: /desafio-php/login.php");
    }
    ?>
    
    <div class="container">
        <h3 class="mt-4 font-weight-bold">Lista de Produtos</b></h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (file_exists('cadastprodutos.json')) {
                        $produtoscadast = file_get_contents("cadastprodutos.json");
                        $produtoscadast = json_decode($produtoscadast, true);
                        foreach($produtoscadast as $produto){
                ?>
                <tr>
                    <th class="align-middle"><?php echo $produto["id"];?></th>
                    <td class="align-middle"><?php echo $produto["nome"];?></td>
                    <td class="align-middle"><?php echo $produto["descricao"];?></td>
                    <td class="align-middle">R$<?php echo $produto["preco"];?></td>
                    <td>
                        <a href="editproduto.php?id=<?php echo $produto["id"];?>" name="editar" class="btn btn-info" style="width: 80px;">Editar</a>
                        <a href="showproduto.php?id=<?php echo $produto["id"];?>" name="excluir" class="btn btn-danger" style="width: 80px;">Excluir</a>
                    </td>
                </tr>
                <?php }}?>                
            </tbody>
        </table>
    </div>
</body>

</html>