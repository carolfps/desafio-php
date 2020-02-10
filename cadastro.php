<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Cadastro</title>
</head>

<?php

if (isset($_POST["nome-usuario"])) {

    if ($_POST["senha-usuario"] != $_POST["confirm-senha"]) {
        $senhadif = true;
    } else {
        $usuario = array(
            "nome" => $_POST["nome-usuario"],
            "email" => $_POST["email-usuario"],
            "senha" => password_hash($_POST["senha-usuario"], PASSWORD_DEFAULT)
        );      

        $bancodecadastro = file_get_contents("usuarios.json");
        $bancodecadastro = json_decode($bancodecadastro, true);
        $existenabase = 0;

        //verificando se o email inserido já existe no base de cadastros
        if(!empty($bancodecadastro)){
            foreach ($bancodecadastro as $dadocadastrado) {
                if ($dadocadastrado["email"] == $usuario["email"]) {
                    $existenabase = 1;
                }
            }
        }     


        if (file_exists('usuarios.json') and !empty($usuario) and ($existenabase == 0)) {
            $dadosatuais = file_get_contents("usuarios.json");
            $temporario = json_decode($dadosatuais, true);
            $temporario[] = $usuario;
            $jsonData = json_encode($temporario);
            file_put_contents("usuarios.json", $jsonData);
            header("Location: /desafio-php/login.php");
        }
    }
}



?>

<body>
    <?php require('./includes/navbar.php'); ?>
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-md-8 mt-3">
                <h3 class="my-1 font-weight-bold">Criar conta</h3>
                <form method="post">
                    <div class="my-3">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="nome-usuario">Nome</label>
                                <input type="text" class="form-control" id="nome-usuario" name="nome-usuario" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="email-usuario">E-mail</label>
                                <input type="email" class="form-control" id="email-usuario" name="email-usuario" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="senha-usuario">Senha</label>
                                <input type="password" class="form-control" id="senha-usuario" name="senha-usuario" minlength="6" required>
                                <small class="form-text text-muted">Mínimo 6 caracteres</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="confirm-senha">Confirmar senha</label>
                                <input type="password" class="form-control" id="confirm-senha" name="confirm-senha" minlength="6" required>
                                <?php if (isset($senhadif) and $senhadif == 1) { ?><small class="form-text text-danger">Senha não confere!</small><?php } ?>
                                <?php if (isset($existenabase) and $existenabase == 1) { ?><small class="form-text text-danger">Este email já tem cadastro! <?php if(!isset($_SESSION["email"])){ ?> <a href="login.php">Fazer login.</a><?php }?> </small><?php } ?>
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-primary btn-block" type="submit" value="Criar" name="submit">
                </form>
            </div>
        </div>
    </div>

</body>

</html>