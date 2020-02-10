<?php
require('./includes/navbar.php');
session_start();
if (isset($_POST["entrar"])) {

    $usuarios = file_get_contents("usuarios.json");
    $usuarios = json_decode($usuarios, true);
    if (!isset($_SESSION["senhaok"])) {
        $_SESSION["senhaok"] = 0;
    }
    if (isset($_SESSION)) {
        foreach ($usuarios as $usuario) {
            //a chave para buscar a senha do usuario eh o email
            if ($usuario["email"] == $_POST["email"]) {
                //se a senha que a pessoa inseriu for igual a senha cadastrada salva no json, a var session recebe os dados do usuario
                if (password_verify($_POST["senha"], $usuario["senha"])) {
                    $_SESSION["email"] = $usuario["email"];
                    $_SESSION["senha"] = $usuario["senha"];
                    $_SESSION["nome"] = $usuario["nome"];
                    $_SESSION["senhaok"] = 1;
                    header("Location: indexprodutos.php");
                }
            }
        }
    }
}
//se ja existe uma sessao aberta, redireciona para a pagina indexprodutos.php (home do sistema)
if (isset($_SESSION["senhaok"])) {
    if ($_SESSION["senhaok"] == 1) {
        header("Location: indexprodutos.php");
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<?php require('./includes/head.php'); ?>

<head>
    <title>Login</title>
</head>

<body class="bg-light">
    <main>
        <div class="d-flex justify-content-center">

            <div class="col-sm-4 py-3 px-4">
                <h4 class="text-center font-weight-bold my-4">LOGIN</h4>
                <form method="post">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control rounded-pill" id="usuario" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control rounded-pill" id="senha" name="senha" required>
                    </div>
                    <?php
                    if (isset($_SESSION["senhaok"])) {
                        if ($_SESSION["senhaok"] == 0) { ?>
                            <small class="text-danger">Usuário ou senha incorretos!</small><br>
                    <?php }
                    } ?>
                    <input class="btn btn-primary mt-4 btn-block rounded-pill" type="submit" value="Entrar" name="entrar">
                </form>
                <small>Ainda não tem cadastro? <a href="cadastro.php" style="display: inline-block;">Clique aqui e registre-se.</a></small>

            </div>
        </div>
    </main>
</body>

</html>