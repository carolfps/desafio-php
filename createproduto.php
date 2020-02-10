<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Adicionar Produto</title>
</head>

<?php


if ($_POST) {
    if (!isset($_POST["descricao-produto"])) {
        $_POST["descricao-produto"] = "";
    }
    $produto = array(
        "nome" => $_POST["nome-produto"],
        "preco" => $_POST["preco-produto"],
        "descricao" => $_POST["descricao-produto"]
    );

    if (file_exists('cadastprodutos.json')) {

        $dadosatuais = file_get_contents("cadastprodutos.json");
        $temporario = json_decode($dadosatuais, true);

        if (!isset($temporario[0]["id"])) {
            $produto["id"] = 1;
        } else {
            $maiorindice = count($temporario) - 1;
            $proximoid = $temporario[$maiorindice]["id"] + 1;
            $produto["id"] = $proximoid;
        }

        if ($_FILES) {
            $ext = pathinfo($_FILES["fotoProd"]["name"], PATHINFO_EXTENSION);
            $nome = "Produto".$produto["id"].".".$ext;
            $arquivo = $_FILES["fotoProd"]["tmp_name"];
            $caminho = "uploads\\" . $nome;
            $produto["Enderecofoto"] = $caminho;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
            //checa se o arquivo ja existe
            if (file_exists($caminho)) {
                $uploadOk = 0;
            }
            //permitindo apenas determinadas extensões de arquivo
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

        $existenabase = 0;

        // if (sizeof($temporario)>1) {
        //     foreach ($dadosatuais as $prodcadastrado) {
        //         if ($prodcadastrado["nome"] == $produto["nome"]) {
        //             $existenabase = 1;
        //             echo "Este produto já está cadastrado!";
        //         }
        //     }
        // }

        if (file_exists('cadastprodutos.json') ) {
            $dadosatuais = file_get_contents("cadastprodutos.json");
            $temporario = json_decode($dadosatuais, true);
            $temporario[] = $produto;
            $jsonData = json_encode($temporario);
            file_put_contents("cadastprodutos.json", $jsonData);
            header("Location: /desafio-php/indexprodutos.php");
        }
    }

}
?>

<body>
    <?php require('./includes/navbar.php'); 
    //se não tiver logado redireciona para a pagina de login
    if(!isset($_SESSION["email"])){
        header("Location: /desafio-php/login.php");
    }
    ?>
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
                        <textarea class="form-control" id="descricao-produto" rows="8"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fotoProd" name="fotoProd">
                            <label class="custom-file-label" for="fotoProd" data-browse="Buscar">Selecione a foto</label>
                        </div>
                    </div>
                </div>
            </div>
            <input class="btn btn-primary btn-block" type="submit" value="Adicionar" name="submit">
        </form>


    </div>


</body>

</html>