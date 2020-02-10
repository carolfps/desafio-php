
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <?php session_start();?>
        <a class="navbar-brand" href="#">&lt;Desafio PHP/&gt;</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php if(($_SERVER['REQUEST_URI']!="/desafio-php/login.php")and($_SERVER['REQUEST_URI']!="/desafio-php/cadastro.php")){?>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="indexprodutos.php">Home </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="createproduto.php">Adicionar produto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="createusuario.php">Usuários</a>
                </li>
            </ul>
            <div class="navbar-nav">
                <form method="post">
                    <input class="btn btn-dark" type="submit" name="encerrar" value="Logout">
                </form>
            </div> 
                
        </div>
        <?php }?>
    </div>
</nav>
<?php 
if(isset($_POST["encerrar"])){
    session_unset();
    session_destroy();
    header("Location: /desafio-php/login.php");
}?>