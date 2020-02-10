<?php

if ($_GET["id"] and file_exists('cadastprodutos.json')) {
    $produtoscadast = file_get_contents("cadastprodutos.json");
    $produtoscadast = json_decode($produtoscadast, true);
    $posicao = array_search($_GET["id"], array_column($produtoscadast, 'id'));
}

if ($_POST) {
    if (!isset($_POST["descricao-produto"])) {
        $_POST["descricao-produto"] = "";
    }
    $produtoEdit = array(
        "nome" => $_POST["nome-produto"],
        "preco" => $_POST["preco-produto"],
        "descricao" => $_POST["descricao-produto"],
        "id" => $_GET["id"]
    );

    if (file_exists('cadastprodutos.json')) {

        $dadosatuais = file_get_contents("cadastprodutos.json");
        $temporario = json_decode($dadosatuais, true);

        
        if (isset($_FILES)) {
            $ext = pathinfo($_FILES["fotoProd"]["name"], PATHINFO_EXTENSION);
            $nome = "Produto".$produtoEdit["id"].".".$ext;
            $arquivo = $_FILES["fotoProd"]["tmp_name"];
            $caminho = "uploads\\" . $nome;
            $produtoEdit["Enderecofoto"] = $caminho;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
            
            //permite apenas determinadas extensões de arquivo
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $uploadOk = 0;
            }
            //checa se $uploadOk é zero, ou seja, se teve algum erro
            if ($uploadOk == 0) {
                echo "Desculpa, não foi possível subir o seu arquivo.";
                
                //se tudo estiver OK tenta subir o arquivo
            } else {
                $movendo = move_uploaded_file($arquivo, $caminho);
            }
        }

        if (file_exists('cadastprodutos.json')) {
            $dadosatuais = file_get_contents("cadastprodutos.json");
            $temporario = json_decode($dadosatuais, true);
            $posicao = array_search($_GET["id"], array_column($temporario, 'id')); 
            $temporario[$posicao] = $produtoEdit;
            $jsonData = json_encode($temporario);
            file_put_contents("cadastprodutos.json", $jsonData);
            header('Location: /desafio-php/indexprodutos.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Editar Produto</title>
</head>

<body>
    <?php require('./includes/navbar.php'); 
    //se não tiver logado redireciona para a pagina de login
    if(!isset($_SESSION["email"])){
        header("Location: /desafio-php/login.php");
    }
    ?>
    <div class="container">
        <h3 class="mt-4 font-weight-bold">Editar Produto</h3>
        <form method="post" enctype="multipart/form-data" class="">
            <div class="my-3">
                <div class="form-row">
                    <div class="col-md-6">
                        <label for="nome-produto">Nome</label>
                        <input type="text" class="form-control" name="nome-produto" id="nome-produto" value="<?php if($produtoscadast[$posicao]["nome"]){echo $produtoscadast[$posicao]["nome"];} ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="preco-produto">Preço</label>
                        <input type="text" pattern="([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$" class="form-control" name="preco-produto" id="preco-produto" value="<?php if($produtoscadast[$posicao]["preco"]){echo $produtoscadast[$posicao]["preco"];} ?>" title="O número deve ter o formato 9.999,99">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descricao-produto">Descrição</label>
                        <textarea class="form-control" id="descricao-produto" rows="4"><?php if($produtoscadast[$posicao]["descricao"]){echo $produtoscadast[$posicao]["descricao"];} ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <img src="<?php if($produtoscadast[$posicao]["Enderecofoto"]){echo $produtoscadast[$posicao]["Enderecofoto"];} ?>" class="img-fluid" style="object-fit: cover; object-position: bottom;width: 100%; max-height: 300px; border-radius: 3px;" alt="produto">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom-file">
                        <input type="file" class="custom-file-input" id="fotoProd" name="fotoProd" required>
                        <label class="custom-file-label" for="fotoProd" data-browse="Buscar">Selecione a foto</label>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-warning btn-block mb-3" type="submit">Editar</button>
        </form>


    </div>


</body>

</html>