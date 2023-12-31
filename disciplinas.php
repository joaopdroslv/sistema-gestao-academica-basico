<?php
session_start();

if (!isset($_SESSION['loggedin'])){
  header('Location: index.php');	
}

require("sidemenu.php");

require_once('connection.php');

$mysql_query = "SELECT d.*, p.nome AS nome_professor, t.codigo AS codigo_turma, t.curso AS curso_turma
                          FROM disciplina d
                          LEFT JOIN professor p ON d.professor_codigo = p.codigo
                          LEFT JOIN turma t ON d.turma_codigo = t.codigo";

if (isset($_GET['filtrar'])) {
  $filtro = $_GET['filtro'];
  $valor = $_GET['valor'];

  if (!empty($filtro) && !empty($valor)) {
    if ($filtro == "codigo") {
      $mysql_query .= " WHERE d.codigo = '{$valor}'";
    } elseif ($filtro == "nome") {
      $mysql_query .= " WHERE d.nome LIKE '%{$valor}%'";
    }
  }
}

$mysql_query .= " ORDER BY d.codigo";

$result = $conn -> query($mysql_query);

mysqli_close($conn);
?>  

<div class="container p-3">
  <header>
    <h2>Disciplinas</h2>
    <p>Listagem de disciplinas cadastradas.</p>
  </header>
  <hr>
  <section class="d-flex justify-content-between">
    <a href="insert-disciplina.php"><button type="button" class="btn btn-dark">Adicionar Disciplina</button></a>
    <form method="get" class="d-flex justify-content-between" style="width: 40%;">
      <select class="form-select" style="width: 30%;" name="filtro" id="filtro">
        <option value="" <?php echo empty($_GET['filtro']) ? 'selected' : ''; ?>>...</option>
        <option value="codigo" <?php echo $_GET['filtro'] === 'codigo' ? 'selected' : ''; ?>>Codigo</option>
        <option value="nome" <?php echo $_GET['filtro'] === 'nome' ? 'selected' : ''; ?>>Nome</option>
      </select>
      <input type="text" name="valor" id="valor" class="form-control" style="width: 45%;" value="<?php echo isset($_GET['valor']) ? $_GET['valor'] : ''; ?>"><br>
      <input type="submit" value="Filtrar" name="filtrar" class="btn btn-dark" style="width: 20%;">
    </form>
  </section>
  <br>
  <table class="table table-striped table-bordered table-hover">
    <thead>
      <tr class="table-dark" style="text-align:center">
        <th scope="col" style="width: 10%;">Código</th>
        <th scope="col">Nome</th>
        <th scope="col" style="width: 20%;">Ação</th>
      </tr>
    </thead>
    <tbody>
      <?php while($data = mysqli_fetch_array($result)) { ?> 
      <tr> 
        <th scope="row" style="text-align:center"><?php echo $data['codigo']; ?></th>
        <td style="text-align:center"><?php echo $data['nome']; ?></td> 
        <td style="text-align:center">
          <a href="details-disciplina.php?codigo=<?php echo $data['codigo']; ?>">
            <button type="button" class="btn btn-dark">Detalhes</button></a>
        </td> 
      </tr> 
      <?php } ?>       
    </tbody>
  </table>
</div>

