<?php include 'db.php'; ?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>CRUD Tarefas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">Lista de Tarefas</h2>
  <a href="nova.php" class="btn btn-success mb-3">+ Nova Tarefa</a>
  
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Título</th>
        <th>Descrição</th>
        <th>Data</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $pdo->query("SELECT * FROM tarefas ORDER BY data DESC");
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['titulo']}</td>
                <td>{$row['descricao']}</td>
                <td>{$row['data']}</td>
                <td>{$row['status']}</td>
                <td>
                  <a href='editar.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                  <a href='excluir.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Excluir tarefa?\")'>Excluir</a>
                </td>
              </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
