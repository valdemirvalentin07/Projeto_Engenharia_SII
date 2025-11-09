<?php
session_start();
require_once '../Classes/db.php';

// 🔒 Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../codigos/index.php");
  exit;
}

// 🔹 Dados carregados da sessão (vindos do login)
$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = $_SESSION['usuario_nome'];
$email_usuario = $_SESSION['usuario_email'];
$data_cadastro = $_SESSION['usuario_data_cadastro'];
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Day.ai | Meu Perfil</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
:root {
  --violet: #5e35b1;
  --violet-dark: #4527a0;
  --violet-light: #7e57c2;
  --light-bg: #f9f9fb;
}

/* ===== Body ===== */
body {
  background: var(--light-bg);
  font-family: 'Poppins', sans-serif;
  padding-top: 120px; /* ✅ compensação da navbar maior */
}

/* ===== Navbar ===== */
.navbar {
  background: linear-gradient(135deg, #7e57c2, #5e35b1, #4527a0);
  backdrop-filter: blur(8px);
  transition: all 0.4s ease;
  padding-top: 1.25rem !important;  /* ≈ 20px */
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

/* ===== Card Perfil ===== */
.card-profile {
  border-radius: 1rem;
  background: #fff;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  overflow: hidden;
  transition: transform 0.3s ease;
  margin-top: 2rem;
}
.card-profile:hover {
  transform: translateY(-5px);
}
.card-header {
  background: linear-gradient(135deg, var(--violet), var(--violet-dark));
  color: #fff;
}
.card-body p {
  font-size: 1rem;
}

/* ===== Modal ===== */
.modal-content {
  border-radius: 1rem;
  border: none;
}
.modal-header {
  background: linear-gradient(135deg, var(--violet), var(--violet-dark));
  color: #fff;
}
.btn-primary {
  background-color: var(--violet);
  border: none;
}
.btn-primary:hover {
  background-color: var(--violet-dark);
}

/* ===== Footer ===== */
footer {
  background: linear-gradient(135deg, #4527a0, #311b92);
  color: white;
  padding: 1rem 0;
  box-shadow: 0 -4px 10px rgba(0,0,0,0.2);
}
footer p { margin: 0; font-size: .95rem; }
</style>
</head>
<body>

<!-- ===== Navbar ===== -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <img src="imagens/logo.png" alt="logo" height="50" class="me-2">
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="../codigos/tarefa.php">
            <i class="bi bi-list-task me-1"></i>Painel
          </a>
        </li>
      </ul>
      <div class="dropdown">
        <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle"></i> <?= htmlspecialchars($nome_usuario); ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item active" href="#"><i class="bi bi-gear"></i> Perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="../codigos/index.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- ===== Conteúdo do Perfil ===== -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card card-profile text-center">
        <div class="card-header py-3">
          <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i> Meu Perfil</h4>
        </div>
        <div class="card-body py-4">
          <i class="bi bi-person-bounding-box display-1 text-muted mb-3"></i>
          <h5 class="fw-bold border-bottom pb-2 mb-3 text-dark">Informações Pessoais</h5>
          <p><strong>Nome:</strong> <?= htmlspecialchars($nome_usuario) ?></p>
          <p><strong>E-mail:</strong> <?= htmlspecialchars($email_usuario) ?></p>
          <p><strong>Membro Desde:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($data_cadastro))) ?></p>

          <div class="d-grid gap-2 mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
              <i class="bi bi-pencil-square"></i> Editar Dados
            </button>
            <a href="../codigos/tarefa.php" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left"></i> Voltar ao Painel
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== Modal Editar Perfil ===== -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5><i class="bi bi-pencil-square"></i> Editar Perfil</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="../CRUD/update_usuario.php" method="post">
          <input type="hidden" name="id" value="<?= $usuario_id ?>">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($nome_usuario) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email_usuario) ?>" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ===== Footer ===== -->
<footer class="mt-5 text-center">
  <p><strong>Day.ai</strong> | Descomplique seu dia 💡 — &copy; 2025</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('scroll', () => {
  document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 50);
});
</script>
</body>
</html>
