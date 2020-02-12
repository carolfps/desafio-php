<?php

session_start();

//se não tiver logado redireciona para a pagina de login
if(!isset($_SESSION["email"])){
    header("Location: login.php");
}

//coloca o email do usuario que quer editar na session. Desta forma, se o usuario errar na validacao da senha nao perde o email do usuario cujos dados serao editados
if(isset($_POST["email-usuario"])){
    $_SESSION["email-edit"]=$_POST["email-usuario"];
}

$usuarioscadast = file_get_contents("usuarios.json");
$usuarioscadast = json_decode($usuarioscadast, true);

//descobrindo a posicao do usuario que sera editado
$posicao = array_search($_SESSION["email-edit"], array_column($usuarioscadast, 'email'));

if (isset($_POST["editar"])) {

    //verificando senha
    if ($_POST["senha"] != $_POST["confirm-senha"]) {

        $senhadif = true;

    } else {

        $usuarioedit = array(
            "nome" => $_POST["nome"],
            "email" => $_POST["email"],
        );

        //se usuario entrar com uma nova senha
        if($_POST["senha"]){

            $usuarioedit["senha"] = password_hash($_POST["senha"], PASSWORD_DEFAULT);

        } else{

            $usuarioedit["senha"] = $usuarioscadast[$posicao]["senha"];

        }
        
        
        //se o email inserido já existe no base de cadastros (usuarios.json) a variavel existenabase recebe o valor 1
        $existenabase = 0;

        for($indice=0; $indice<count($usuarioscadast); $indice++){
            
            //verifica se o email editado pelo usuario existe em alguma posicao que nao seja a dele proprio
            if(($indice != $posicao) and ($usuarioscadast[$indice]["email"] == $usuarioedit["email"])){
                
                $existenabase = 1;  
                
            }
        }   

        //se o email inserido pelo usuario ainda nao existe na base de cadastros, salva as alteracoes no json
        if ($existenabase == 0) {
            
            //substituindo dados do usuario pelos dados editados
            $usuarioscadast[$posicao] = $usuarioedit;

            //inserindo dados no json
            $jsonData = json_encode($usuarioscadast);
            file_put_contents("usuarios.json", $jsonData);

            //depois de salvar as alteracoes no json redireciona para a pagina createusuario.php
            header('Location: createusuario.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Editar Usuário</title>
</head>

<body>
    <?php require('./includes/navbar.php'); ?>
    
    <div class="container">
        <h3 class="mt-4 font-weight-bold">Editar usuário</h3>
        <form method="post">

            <div class="my-3">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php if(isset($usuarioscadast[$posicao]["nome"])){echo $usuarioscadast[$posicao]["nome"];} ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($usuarioscadast[$posicao]["email"])){echo $usuarioscadast[$posicao]["email"];} ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" minlength="6">
                        <small class="form-text text-muted">Mínimo 6 caracteres</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="confirm-senha">Confirmar senha</label>
                        <input type="password" class="form-control" id="confirm-senha" name="confirm-senha" minlength="6">
                        <?php if (isset($senhadif) and $senhadif == 1) { ?><small class="form-text text-danger">Senha não confere!</small><?php } ?>
                        <?php if (isset($existenabase) and $existenabase == 1) { ?><small class="form-text text-danger">Este email já tem cadastro!</small><?php } ?>
                    </div>
                </div>
            </div>
            <input class="btn btn-warning btn-block" type="submit" value="Editar" name="editar">
        </form>
    </div>
</body>
</html>