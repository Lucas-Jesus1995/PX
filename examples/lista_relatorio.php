<?php
include "bd/funcoesGerais.php";
include "bd/conexao.php";
$con = bancoMysqli();

$pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
$qnt_result_pg = filter_input(INPUT_POST, 'qnt_result_pg', FILTER_SANITIZE_NUMBER_INT);
//calcular o inicio visualização
$inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

//consultar no banco de dados
$result_usuario = "SELECT * FROM relatorio ORDER BY id DESC LIMIT $inicio, $qnt_result_pg";
$resultado_usuario = mysqli_query($con, $result_usuario);


//Verificar se encontrou resultado na tabela "usuarios"
if(($resultado_usuario) AND ($resultado_usuario->num_rows != 0)){
  ?>
  <table class="table tablesorter " id="">
    <thead class=" text-primary">
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      while($registro = mysqli_fetch_assoc($resultado_usuario)){
        ?>
        <tr>
          <td><?php echo $registro['protocolo']; ?></td>
          <td><?php echo $registro['nome']; ?></td>
          <td><?php echo $registro['empresa']; ?></td>
          <td><?php echo $registro['categoria']; ?></td>
          <td><?php echo $registro['motivo']; ?></td>
          <td><?php echo $registro['prioridade']; ?></td>
          <td><?php echo $registro['status']; ?></td>
        <td>
                    <button type="button" class="btn btn-primary btn-link btn-sm" data-toggle="modal" data-target="#myModal<?php echo $registro['protocolo']; ?>"><i class="tim-icons icon-notes"></i></button>

                    <form action="editRelatorio.php" method="post">
                    <button name="editar" type="submit" class="btn btn-warning btn-link btn-sm" value="<?php echo $registro['protocolo']; ?>"><i class="tim-icons icon-pencil"></i></button></form>

                    <form action="comentario.php" method="post">
                    <button name="chat" value="<?php echo $registro['protocolo']; ?>"  type="submit" class="btn btn-link btn-sm btn-info"><i class="tim-icons icon-chat-33"></i></button></form>


                    <button id="<?php echo $registro['protocolo']; ?>" data-toggle="modal" data-target="#myModal2<?php echo $registro['protocolo']; ?>" type="button" class="btn btn-link btn-sm btn-danger"><i class="tim-icons icon-trash-simple"></i></button>
                    
                  </td>
                      </tr>
                      <!-- Inicio Modal Visualizar-->
                <div class="modal fade" id="myModal<?php echo $registro['protocolo']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-center" id="myModalLabel"><?php echo $registro['protocolo']; ?></h4>
                      </div>
                      <div class="modal-body">
                        
                        <p><?php echo $registro['nome']; ?></p>
                        <p><?php echo $registro['descricao']; ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fim Modal Visualizar-->

                <!-- Inicio Modal -->
                <div class="modal fade" id="myModal2<?php echo $registro['protocolo']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-center" id="myModalLabel2">Apagar Relatório: <?php echo $registro['protocolo']; ?></h4>
                      </div>
                      <div class="modal-body">
                        <form method="POST" action="processo/comenta.php" enctype="multipart/form-data">
          <div class="form-group">
          <strong>Tem certeza de que deseja excluir o item selecionado?</strong>
          <hr>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Apagar</button>
       
        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fim Modal -->


        <?php
      }?>
    </tbody>
  </table>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Curso</h4>
        </div>
        <div class="modal-body">
        <form method="POST" action="processo/comenta.php" enctype="multipart/form-data">
          <div class="form-group">
          <label for="recipient-name" class="control-label">Nome:</label>
          <input name="nome" type="text" class="form-control" id="nome">
          </div>
          <div class="form-group">
          <label for="message-text" class="control-label">Detalhes:</label>
          <textarea name="comentario" class="form-control" id="comentario"></textarea>
          </div>
        
        
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">Alterar</button>
       
        </form>
        </div>
        
      </div>
      </div>
    </div>

  
  <?php
  //Paginação - Somar a quantidade de usuários
  $result_pg = "SELECT COUNT(id) AS num_result FROM relatorio";
  $resultado_pg = mysqli_query($con, $result_pg);
  $row_pg = mysqli_fetch_assoc($resultado_pg);

  //Quantidade de pagina
  $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

  //Limitar os link antes depois
  $max_links = 2;

  echo '<nav aria-label="paginacao">';
  echo '<ul class="pagination">';
  echo '<li class="page-item">';
  echo "<span class='page-link'><a href='#' onclick='listar_relatorio(1, $qnt_result_pg)'>Primeira</a> </span>";
  echo '</li>';
  for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
    if($pag_ant >= 1){
      echo "<li class='page-item'><a class='page-link' href='#' onclick='listar_relatorio($pag_ant, $qnt_result_pg)'>$pag_ant </a></li>";
    }
  }
  echo '<li class="page-item active">';
  echo '<span class="page-link">';
  echo "$pagina";
  echo '</span>';
  echo '</li>';

  for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
    if($pag_dep <= $quantidade_pg){
      echo "<li class='page-item'><a class='page-link' href='#' onclick='listar_relatorio($pag_dep, $qnt_result_pg)'>$pag_dep</a></li>";
    }
  }
  echo '<li class="page-item">';
  echo "<span class='page-link'><a href='#' onclick='listar_relatorio($quantidade_pg, $qnt_result_pg)'>Última</a></span>";
  echo '</li>';
  echo '</ul>';
  echo '</nav>';

}else{
  echo "<div class='alert alert-danger' role='alert'>Nenhum usuário encontrado!</div>";
}
