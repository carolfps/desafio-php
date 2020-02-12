<?php

session_start();

//se não tiver logado redireciona para a pagina de login
if(!isset($_SESSION["email"])){
    header("Location: login.php");
}

//verifica se o id foi passado por get e existe o json com os dados dos produtos
if ($_GET["id"]) {

    $dadosatuais = file_get_contents("cadastprodutos.json");
    $dadosatuais = json_decode($dadosatuais, true);
    $posicao = array_search($_GET["id"], array_column($dadosatuais, 'id'));

}

if ($_POST) {

    //inserindo os dados editados do produto
    $produtoEdit = array(
        "nome" => $_POST["nome"],
        "preco" => $_POST["preco"],
        "descricao" => $_POST["descricao"],
        "id" => $_GET["id"],
        "Enderecofoto" => $dadosatuais[$posicao]["Enderecofoto"]
    );    

    //se uma nova foto for inserida
    if ($_FILES["fotoProd"]["error"]==0) {

        //renomeando o arquivo e salvando ele na pasta uploads
        $ext = strtolower(pathinfo($_FILES["fotoProd"]["name"], PATHINFO_EXTENSION));
        $nome = "Produto".$produtoEdit["id"].".".$ext;
        $arquivo = $_FILES["fotoProd"]["tmp_name"];
        $caminho = "uploads\\" . $nome;
        $produtoEdit["Enderecofoto"] = $caminho;

        $uploadOk = 1;
        
        //permite apenas determinadas extensões de arquivo
        if ($ext != "jpg" && $ext != "png" && $ext != "jpeg" && $ext != "gif") {
            $uploadOk = 0; 
        } else {
            //se o arquivo tiver a extensão desejada
            $movendo = move_uploaded_file($arquivo, $caminho);
        }
    }
    
    //se o upload da foto der certo, insere no json os dados do novo produto cadastrado
    if ($uploadOk == 1) {

        //atualizando os dados do produto
        $dadosatuais[$posicao] = $produtoEdit;

        //inserindo os dados no json
        $jsonData = json_encode($dadosatuais);
        file_put_contents("cadastprodutos.json", $jsonData);

        //após enviar os dados para o json redireciona para a página index
        header('Location: indexprodutos.php');
        exit;

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
    <?php require('./includes/navbar.php'); ?>
    <div class="container">
        <h3 class="mt-4 font-weight-bold">Editar Produto</h3>
        <form method="post" enctype="multipart/form-data" class="">
            <div class="my-3">
                <div class="form-row">
                    <div class="col-md-6">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nome" value="<?php if($dadosatuais[$posicao]["nome"]){echo $dadosatuais[$posicao]["nome"];} ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="preco">Preço</label>
                        <input type="text" pattern="([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$" class="form-control" name="preco" id="preco" value="<?php if($dadosatuais[$posicao]["preco"]){echo $dadosatuais[$posicao]["preco"];} ?>" title="O número deve ter o formato 9.999,99">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" rows="4" name="descricao"><?php if($dadosatuais[$posicao]["descricao"]){echo $dadosatuais[$posicao]["descricao"];} ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <img src="<?php if($dadosatuais[$posicao]["Enderecofoto"]){echo $dadosatuais[$posicao]["Enderecofoto"];} ?>" class="img-fluid" style="object-fit: cover; object-position: middle;width: 100%; max-height: 300px; border-radius: 3px;" alt="produto">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fotoProd" name="fotoProd" >
                            <label class="custom-file-label" for="fotoProd" data-browse="Buscar">Selecione a foto</label>
                            <?php if (isset($uploadOk) and $uploadOk == 0) { ?><small class="form-text text-danger">Desculpa, não foi possível subir o seu arquivo. Formatos aceitos: JPG, JPEG, PNG, GIF.</small><?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-warning btn-block mb-3" type="submit">Editar</button>
        </form>
    </div>
</body>
</html>