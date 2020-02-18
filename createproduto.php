<?php

session_start();

//se não tiver logado redireciona para a pagina de login
if(!isset($_SESSION["email"])){
    header("Location: login.php");
}

if ($_POST) {
    
    $produto = array(
        "nome" => $_POST["nome-produto"],
        "preco" => $_POST["preco-produto"],
        "descricao" => $_POST["descricao-produto"]
    );

    $dadosatuais = file_get_contents("cadastprodutos.json");
    $temporario = json_decode($dadosatuais, true);

    //se esse for o primeiro produto cadastrado no json o id dele será 1, se não será o (último id) + 1
    if (!isset($temporario[0]["id"])) {

        $produto["id"] = 1;

    } else {

        $maiorindice = count($temporario) - 1;
        $proximoid = $temporario[$maiorindice]["id"] + 1;
        $produto["id"] = $proximoid;

    }

    if ($_FILES) {
        
        //renomeando a foto para o padrao Produto1, Produto2, ...
        $ext = strtolower(pathinfo($_FILES["fotoProd"]["name"], PATHINFO_EXTENSION));
        $nome = "Produto".$produto["id"].".".$ext;
        $arquivo = $_FILES["fotoProd"]["tmp_name"];
        $caminho = "uploads\\" . $nome;
        $produto["Enderecofoto"] = $caminho;

        $uploadOk = 1;

        //permitindo apenas determinadas extensões de arquivo
        if ($ext != "jpg" && $ext != "png" && $ext != "jpeg" && $ext != "gif") {
            $uploadOk = 0;               
        } else {
            //se tudo estiver OK tenta subir o arquivo
            $movendo = move_uploaded_file($arquivo, $caminho);
        }

    }

    //se o upload da foto der certo, insere no json os dados do novo produto cadastrado
    if ($uploadOk == 1) {

        $dadosatuais = file_get_contents("cadastprodutos.json");
        $temporario = json_decode($dadosatuais, true);
        $temporario[] = $produto;
        $jsonData = json_encode($temporario);
        file_put_contents("cadastprodutos.json", $jsonData);
        header("Location: indexprodutos.php");
        
    }    

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Adicionar Produto</title>
    <style>
    body{
        background: #F5FFE6; 
    }
    </style>
</head>

<body>
    <?php require('./includes/navbar.php');?>
    <div class="container">
        <h3 class="mt-4 font-weight-bold">Adicionar Produto</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="my-3">
                <div class="form-row">
                    <div class="col-md-6">
                        <label for="nome-produto">Nome</label>
                        <input type="text" class="form-control" name="nome-produto" id="nome-produto">
                    </div>
                    <div class="col-md-6">
                        <label for="preco-produto">Preço</label>
                        <input type="text" pattern="([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$" class="form-control" name="preco-produto" id="preco-produto" title="O número deve ter o formato 9.999,99">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descricao-produto">Descrição</label>
                        <textarea class="form-control" id="descricao-produto" rows="8" name="descricao-produto"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fotoProd" name="fotoProd">
                            <label class="custom-file-label" for="fotoProd" data-browse="Buscar">Selecione a foto</label>
                            <?php if (isset($uploadOk) and $uploadOk == 0) { ?><small class="form-text text-danger">Desculpa, não foi possível subir o seu arquivo. Formatos aceitos: JPG, JPEG, PNG, GIF.</small><?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <input class="btn btn-primary btn-block" type="submit" value="Adicionar" name="submit">
        </form>
    </div>
</body>
</html>