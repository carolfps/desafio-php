<?php
session_start();
//se não tiver logado redireciona para a pagina de login
if(!isset($_SESSION["email"])){
    header("Location: login.php");
}
if (isset($_POST["nome-usuario"])) {
    //verifica se a senha eh igual a confirmacao da senha
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

        //se o email inserido já existe no base de cadastros (usuarios.json) a var existenabase recebe o valor 1
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
        }
    }
}
//deletando o usuario quando aperta o botao excluir
if(isset($_POST["excluir"])){
    $usuarioscadast = file_get_contents("usuarios.json");
    $usuarioscadast = json_decode($usuarioscadast, true);
    $posicao = array_search($_POST["usuario"], array_column($usuarioscadast, 'email')); 
    
    //deletando dados do usuario
    unset($usuarioscadast[$posicao]);
    //reordenando o array
    $usuariosord = array_values($usuarioscadast);
    $jsonData = json_encode($usuariosord);
    file_put_contents("usuarios.json", $jsonData);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Cadastro</title>
</head>

<body>
    <?php require('./includes/navbar.php'); ?>
    <div class="container">
        <div class="row mb-3">
            <div class=" list-group-flush col-md-4 border rounded mt-3">
                <h3 class="my-1 font-weight-bold">Usuários</h3>
                <?php
                if (filesize('usuarios.json')>2) {
                    $usuarioscadast = file_get_contents("usuarios.json");
                    $usuarioscadast = json_decode($usuarioscadast, true);
                    foreach ($usuarioscadast as $usuario) {
                ?>
                <div class="list-group-item flex-column pl-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1"><?php echo $usuario["nome"]; ?></p>
                            <p class="mb-1"><?php echo $usuario["email"]; ?></p>
                        </div>
                        <div class="d-flex align-items-end flex-column">
                            <form action="editusuario.php" method="post">
                                <input type="hidden" name="edit-usuario" value="<?php echo $usuario["email"];?>">
                                <input type="submit" value="Editar" class="btn btn-info my-1" style="width: 80px;">
                            </form>
                            <form method="post">
                                <input type="hidden" name="usuario" value="<?php echo $usuario["email"];?>">
                                <input type="submit" class="btn btn-danger my-1" style="width:80px;" name="excluir" value="Excluir">
                            </form>
                        </div>
                    </div>
                </div>
                <?php }} elseif(filesize('usuarios.json')<=2){?>
                    <div class="list-group-item flex-column pl-0">
                    <p class="mb-1 font-italic text-muted">Não há usuários cadastrados</p>
                    </div>
                <?php }?>

            </div>

            <div class="col-md-8 mt-3">
                <h3 class="my-1 font-weight-bold">Adicionar usuários</h3>
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
                                <?php if (isset($existenabase) and $existenabase == 1) { ?><small class="form-text text-danger">Este email já tem cadastro!</small><?php } ?>
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-primary btn-block" type="submit" value="Adicionar" name="submit">
                </form>
            </div>
        </div>
    </div>

</body>

</html>