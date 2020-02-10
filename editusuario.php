<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Editar Usuário</title>
</head>

<?php

if (isset($_POST["edit-usuario"]) and file_exists('usuarios.json')) {
    $usuarioscadast = file_get_contents("usuarios.json");
    $usuarioscadast = json_decode($usuarioscadast, true);
    $posicao = array_search($_POST["edit-usuario"], array_column($usuarioscadast, 'email'));
}

if (isset($_POST["editar"])) {
    $dadosatuais = file_get_contents("usuarios.json");
    $temporario = json_decode($dadosatuais, true);
    $posicao = array_search($_POST["edit-usuario"], array_column($temporario, 'email'));
    if ($_POST["senha-usuario"] != $_POST["confirm-senha"]) {
        $senhadif = true;
    } else {
        $usuarioedit = array(
            "nome" => $_POST["nome-usuario"],
            "email" => $_POST["email-usuario"],
            "senha" => password_hash($_POST["senha-usuario"], PASSWORD_DEFAULT)
        );
        
        if (file_exists('usuarios.json')) {
            $dadosatuais = file_get_contents("usuarios.json");
            $temporario = json_decode($dadosatuais, true);
            $posicao = array_search($_POST["edit-usuario"], array_column($temporario, 'email'));
            $temporario[$posicao] = $usuarioedit;
            $jsonData = json_encode($temporario);
            file_put_contents("usuarios.json", $jsonData);
            header('Location: createusuario.php');
            exit;
        }
    }
}
?>


<body>
    <?php require('./includes/navbar.php'); 
    //se não tiver logado redireciona para a pagina de login
    if(!isset($_SESSION["email"])){
        header("Location: login.php");
    }
    ?>
    
    <div class="container">
        <h3 class="mt-4 font-weight-bold">Editar usuário</h3>
        <form method="post">

            <div class="my-3">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="nome-usuario">Nome</label>
                        <input type="text" class="form-control" id="nome-usuario" name="nome-usuario" value="<?php if(isset($usuarioscadast[$posicao]["nome"])){echo $usuarioscadast[$posicao]["nome"];} ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="email-usuario">E-mail</label>
                        <input type="email" class="form-control" id="email-usuario" name="email-usuario" value="<?php if(isset($usuarioscadast[$posicao]["email"])){echo $usuarioscadast[$posicao]["email"];} ?>">
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
                    </div>
                </div>
            </div>
            <input class="btn btn-warning btn-block" type="submit" value="Editar" name="editar">
        </form>
    </div>
</body>
</html>