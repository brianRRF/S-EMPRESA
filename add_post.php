<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Post</title>
    <link rel="stylesheet" href="blognew.css">
</head>
<body>
    <header>
        <h1>Agregar Nuevo Post</h1>
    </header>
    <main>
        <form action="guardar_post.php" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="5" required></textarea>

            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="tech">Tech</option>
                <option value="designado">Designado</option>
                <option value="mobile">Mobile</option>
            </select>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>

            <button type="submit">Enviar Post</button>
        </form>
    </main>
</body>
</html>