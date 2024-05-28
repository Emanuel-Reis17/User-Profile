<?php
require_once dirname(__FILE__, 1) . '\src\connection.php';
$result = consultar();

if (isset($_POST['confirmar']) and sizeof($_POST) > 0) 
{
    $fotoAtual = consultar("SELECT `foto` FROM `usuarios` WHERE id = 1")[0];
    $path = $fotoAtual['foto'];
    
    if (isset($_FILES['foto']) and $_FILES['foto']['error'] == 0)
    {
        if ($fotoAtual['foto']) 
            unlink($fotoAtual['foto']);
        
        $file = $_FILES['foto'];
        $folder = 'src/arquivo/';
        $fileName = $file['name'];
        $newFileName = uniqid();
        $extensao = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($extensao != 'jpg' && $extensao != 'png' && $extensao != 'jpeg' && $extensao != 'webp')
        {
            echo 'Tipo de arquivo não suportado!';
            unset($_POST);
            header('Location: index.php');
            die();
        }

        $path = $folder . $newFileName . '.'. $extensao;
        move_uploaded_file($file['tmp_name'], $path);
    }

    if (sizeof($result) == 0) 
    {
        $sql = 'INSERT INTO `usuarios` (nome, idade, rua, bairro, estado, biografia, foto) VALUES (?, ?, ?, ?, ?, ?, ?)';
        executar($sql, $_POST['nome'], $_POST['idade'], $_POST['rua'], $_POST['bairro'], $_POST['estado'], $_POST['biografia'], $path);
    } 
    else 
    {
        $sql = 'UPDATE `usuarios` SET nome = ?, 
        idade = ?, 
        rua = ?, 
        bairro = ?, 
        estado = ?, 
        biografia = ?, 
        foto = ? WHERE id = 1';
        executar($sql, $_POST['nome'], $_POST['idade'], $_POST['rua'], $_POST['bairro'], $_POST['estado'], $_POST['biografia'], $path);
    }
    unset($_POST);
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" type="text/css" href="./assets/css/index.css">
    <script type="application/javascript" src="./assets/js/main.js" defer></script>
    <title>Perfil do Usuário</title>
</head>

<body>
    <main class="main">
        <div class="perfil">
            <div class="perfil__background"></div>
            <div class="perfil__content">
                <div class="perfil__img">
                    <?php
                    if (isset($result[0]['foto']) and strlen($result[0]['foto']) != 0) { ?>
                        <img src="<?php echo $result[0]['foto']; ?>" alt="">
                    <?php } else {
                        echo strtoupper(substr($result[0]['nome'], 0, 2));
                     } ?>
                </div>
                <div>
                    <h1 class="perfil__username perfil__dados"><?php echo isset($result[0]['nome']) ? $result[0]['nome'] : 'Nickname'; ?></h1>
                    <button title="Editar perfil" class="perfil__btn perfil__btn--editar" type="button"><img src="./assets/imgs/editing.png" alt=""></button>
                </div>
                <div class="perfil__container">
                    <div class="perfil__campos">
                        <h3 class="perfil__label">Idade: </h3>
                        <p class="perfil__campo perfil__dados"><?php echo isset($result[0]['idade']) ? $result[0]['idade'] : 'Sua Idade'; ?></p>
                    </div>
                    <div class="perfil__campos">
                        <h3 class="perfil__label">Rua: </h3>
                        <p class="perfil__campo perfil__dados"><?php echo isset($result[0]['rua']) ? $result[0]['rua'] : 'Sua Rua'; ?></p>
                    </div>
                    <div class="perfil__campos">
                        <h3 class="perfil__label">Bairro: </h3>
                        <p class="perfil__campo perfil__dados"><?php echo isset($result[0]['bairro']) ? $result[0]['bairro'] : 'Seu Bairro'; ?></p>
                    </div>
                    <div class="perfil__campos">
                        <h3 class="perfil__label">Estado: </h3>
                        <p class="perfil__campo perfil__dados"><?php echo isset($result[0]['estado']) ? $result[0]['estado'] : 'Seu Estado'; ?></p>
                    </div>
                </div>

                <div class="perfil__campos perfil__campos--biografia">
                    <h3 class="perfil__label">Biografia: </h3>
                    <p class="perfil__campo perfil__dados"><?php echo isset($result[0]['biografia']) ? $result[0]['biografia'] : 'Sua Biografia'; ?></p>
                </div>
            </div>
            <dialog class="perfil__form">
                <span title="Fechar formulário" class="form__btn--close">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ececec" onclick="closeModal()">
                        <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                    </svg>
                </span>
                <h1 class="form__titulo">Editar Perfil</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <p class="form__campo">
                        <label for="file" class="form__label--file"><span class="material-symbols-rounded">add</span> <span>Adicionar foto</span></label>
                        <input type="file" name="foto" id="file" style="display: none;">
                    </p>
                    <p class="form__campo">
                        <label class="form__label" for="">Nome: </label>
                        <input class="form__input" type="text" name="nome" id="" value="<?php if (isset($result[0]['nome'])) echo $result[0]['nome']; ?>">
                    </p>
                    <p class="form__campo">
                        <label class="form__label" for="">Idade: </label>
                        <input class="form__input" type="number" name="idade" id="" value="<?php if (isset($result[0]['idade'])) echo $result[0]['idade']; ?>">
                    </p>
                    <p class="form__campo">
                        <label class="form__label" for="">Rua: </label>
                        <input class="form__input" type="text" name="rua" id="" value="<?php if (isset($result[0]['rua'])) echo $result[0]['rua']; ?>">
                    </p>
                    <p class="form__campo">
                        <label class="form__label" for="">Bairro: </label>
                        <input class="form__input" type="text" name="bairro" id="" value="<?php if (isset($result[0]['bairro'])) echo $result[0]['bairro']; ?>">
                    </p>
                    <p class="form__campo">
                        <label class="form__label" for="">Estado: </label>
                        <select name="estado" id="form__estados" class="form__input">
                            <?php if (isset($result[0]['estado'])) { ?>
                                <option value="<?php echo $result[0]['estado']; ?>" selected><?php echo $result[0]['estado']; ?></option>
                            <?php } else { ?>
                                <option value="" disabled selected>Estado</option>
                            <?php } ?>
                        </select>
                    </p>
                    <p class="form__campo">
                        <label class="form__label" for="">Biografia</label>
                        <textarea class="form__text" name="biografia" id=""></textarea>
                    </p>
                    <button class="form__btn" type="submit" name="confirmar">Concluir</button>
                </form>
            </dialog>
        </div>
    </main>
    <script>
        document.querySelector("textarea").innerText = "<?php if (isset($result[0]['biografia'])) echo $result[0]['biografia']; ?>";
    </script>
</body>

</html>