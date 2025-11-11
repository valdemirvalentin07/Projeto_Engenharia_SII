<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../codigos/index.php");
    exit();
}

require_once '../Classes/db.php';

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Usuário';

try {
    $db = new DB('tarefas');
    $tarefas = $db->readByUser($usuario_id);
} catch (Exception $e) {
    $tarefas = [];
    error_log("Erro ao buscar tarefas: " . $e->getMessage());
}

$total_tarefas = count($tarefas);
$pendentes = $concluidas = $alta_prioridade = 0;
foreach ($tarefas as $t) {
    if ($t['status'] === 'pendente') $pendentes++;
    if ($t['status'] === 'concluida') $concluidas++;
    if ($t['cor'] === 'vermelho' && $t['status'] === 'pendente') $alta_prioridade++;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Day.ai | Painel de Tarefas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
:root {
  --violet: #5e35b1;
  --violet-dark: #4527a0;
  --violet-light: #7e57c2;
  --light-bg: #f9f9fb;
}

body {
  background: var(--light-bg);
  font-family: 'Poppins', sans-serif;
  padding-top: 120px;
}

.navbar {
  background: linear-gradient(135deg, #7e57c2, #5e35b1, #4527a0);
  backdrop-filter: blur(8px);
  transition: all 0.4s ease;
  padding-top: 1.25rem !important;
  padding-bottom: 1.25rem !important;
}
.navbar.scrolled {
  background: linear-gradient(135deg, #4527a0, #311b92);
  box-shadow: 0 3px 15px rgba(0,0,0,0.3);
}
.nav-link {
  color: #fff !important;
  font-weight: 500;
  font-size: 1.05rem;
  transition: transform .2s;
}
.nav-link:hover {
  color: #ffeb3b !important;
  transform: scale(1.05);
}

.card-stat-modern {
  border-radius: 1rem;
  color: #fff;
  transition: all 0.4s ease;
  text-align: center;
  padding: 1.5rem 1rem;
}
.card-stat-modern:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
.icon-box {
  font-size: 2.5rem;
  opacity: 0.9;
  transition: transform 0.3s ease;
}
.card-stat-modern:hover .icon-box {
  transform: scale(1.2);
}
.gradient-warning {
  background: linear-gradient(135deg, #fbc02d, #f57c00);
}
.gradient-success {
  background: linear-gradient(135deg, #43a047, #2e7d32);
}
.gradient-danger {
  background: linear-gradient(135deg, #e53935, #b71c1c);
}
.gradient-info {
  background: linear-gradient(135deg, #5e35b1, #311b92);
}

.table {
  border-radius: 1rem;
  overflow: hidden;
  background-color: #fff;
}
.table thead {
  background-color: var(--violet);
  color: white;
}
.table-hover tbody tr:hover {
  background-color: #f1e8ff;
}

.modal-content {
  border-radius: 1rem;
  border: none;
}
.modal-header {
  background: linear-gradient(135deg, #5e35b1, #4527a0);
  color: #fff;
}
.btn-primary {
  background-color: var(--violet);
  border: none;
}
.btn-primary:hover {
  background-color: var(--violet-dark);
}

footer {
  background: linear-gradient(135deg, #4527a0, #311b92);
  color: white;
  padding: 1rem 0;
  box-shadow: 0 -4px 10px rgba(0,0,0,0.2);
}
footer p {
  margin: 0;
  font-size: .95rem;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top py-4">
  <div class="container">
    <img src="imagens/logo.png" alt="logo" height="50" class="me-2">
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addTarefa">
            <i class="bi bi-plus-circle me-1"></i> Nova
          </a>
        </li>
      </ul>
      <div class="dropdown">
        <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle"></i> <?= htmlspecialchars($usuario_nome); ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-gear"></i> Perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="index.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="container">
  <h2 class="fw-bold mb-4 text-dark"><i class="bi bi-bar-chart-fill me-2"></i>Resumo</h2>
  <div class="row g-4 mb-5">
    <div class="col-md-3 col-sm-6">
      <div class="card-stat-modern gradient-warning shadow">
        <div class="icon-box"><i class="bi bi-hourglass-split"></i></div>
        <h6 class="fw-semibold mt-2">Pendentes</h6>
        <h2 class="fw-bold mb-0"><?= $pendentes; ?></h2>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card-stat-modern gradient-success shadow">
        <div class="icon-box"><i class="bi bi-check-circle-fill"></i></div>
        <h6 class="fw-semibold mt-2">Concluídas</h6>
        <h2 class="fw-bold mb-0"><?= $concluidas; ?></h2>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card-stat-modern gradient-danger shadow">
        <div class="icon-box"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <h6 class="fw-semibold mt-2">Alta Prioridade</h6>
        <h2 class="fw-bold mb-0"><?= $alta_prioridade; ?></h2>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card-stat-modern gradient-info shadow">
        <div class="icon-box"><i class="bi bi-list-task"></i></div>
        <h6 class="fw-semibold mt-2">Total</h6>
        <h2 class="fw-bold mb-0"><?= $total_tarefas; ?></h2>
      </div>
    </div>
  </div>

  <section id="tarefas" class="bg-white shadow p-4 rounded-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-list-task me-2"></i>Suas Tarefas</h3>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr><th>Status</th><th>Título</th><th>Prazo</th><th>Prioridade</th><th>Ações</th></tr>
        </thead>
        <tbody>
        <?php if (empty($tarefas)): ?>
          <tr><td colspan="5" class="text-center text-muted py-4">Nenhuma tarefa cadastrada ainda.</td></tr>
        <?php else: foreach ($tarefas as $t):
            $statusClass = $t['status']==='concluida'?'bg-success':'bg-warning text-dark';
            $statusTxt = $t['status']==='concluida'?'Concluída':'Pendente';
            $priorClass = $t['cor']==='vermelho'?'bg-danger':($t['cor']==='azul'?'bg-primary':'bg-secondary');
            $priorTxt = $t['cor']==='vermelho'?'Alta':($t['cor']==='azul'?'Média':'Baixa');
        ?>
          <tr>
            <td><span class="badge <?= $statusClass; ?>"><?= $statusTxt; ?></span></td>
            <td><?= htmlspecialchars($t['titulo']); ?></td>
            <td><?= date('d/m/Y', strtotime($t['data'])); ?></td>
            <td><span class="badge <?= $priorClass; ?>"><?= $priorTxt; ?></span></td>
            <td>
              <a href="../CRUD/concluir_tarefa.php?id=<?= $t['id']; ?>" class="btn btn-sm btn-outline-success" title="Concluir"><i class="bi bi-check2-circle"></i></a>
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $t['id']; ?>"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $t['id']; ?>"><i class="bi bi-trash"></i></button>
            </td>
          </tr>

          <!-- Modal Editar -->
          <div class="modal fade" id="editModal<?= $t['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                  <h5 class="modal-title"><i class="bi bi-pencil"></i> Editar Tarefa</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="../CRUD/editar_tarefa.php">
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $t['id']; ?>">
                    <div class="mb-3">
                      <label class="form-label">Título</label>
                      <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($t['titulo']); ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Prioridade</label>
                      <select name="prioridade" class="form-select">
                        <option value="Baixa" <?= $t['cor']==='cinza'?'selected':''; ?>>Baixa</option>
                        <option value="Média" <?= $t['cor']==='azul'?'selected':''; ?>>Média</option>
                        <option value="Alta" <?= $t['cor']==='vermelho'?'selected':''; ?>>Alta</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Prazo</label>
                      <input type="date" name="prazo" class="form-control" value="<?= date('Y-m-d', strtotime($t['data'])); ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Modal Excluir -->
          <div class="modal fade" id="deleteModal<?= $t['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
              <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                  <h5 class="modal-title"><i class="bi bi-trash"></i> Excluir</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                  <p>Deseja excluir "<strong><?= htmlspecialchars($t['titulo']); ?></strong>"?</p>
                  <a href="../CRUD/excluir_tarefa.php?id=<?= $t['id']; ?>" class="btn btn-danger">Sim</a>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                </div>
              </div>
            </div>
          </div>

        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<!-- Modal Adicionar -->
<div class="modal fade" id="addTarefa" tabindex="-1" aria-labelledby="addTarefaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header" style="background: linear-gradient(135deg, #5e35b1, #4527a0); color:white;">
        <h5 class="modal-title" id="addTarefaLabel"><i class="bi bi-plus-circle"></i> Nova Tarefa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="../CRUD/adicionar_tarefa.php">
          <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" class="form-control" name="titulo" placeholder="Ex: Revisar relatório" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Prioridade</label>
            <select name="prioridade" class="form-select">
              <option value="Baixa">Baixa</option>
              <option value="Média" selected>Média</option>
              <option value="Alta">Alta</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Prazo</label>
            <input type="date" name="prazo" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-save"></i> Salvar Tarefa
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<footer class="mt-5 text-center">
  <p><strong>Day.ai</strong> | Descomplique seu dia 💡 — &copy; 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('scroll',()=> {
  document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY>50);
});
</script>
</body>
</html>
