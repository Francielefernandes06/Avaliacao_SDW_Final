<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
  // Se não estiver logado, redirecionar para a página de login
  header("Location: login.php");
  exit();
}

// Conectar ao banco de dados MySQL
$mysqli = new mysqli("localhost", "root", "123456789", "praticaFinal");

// Verificar a conexão com o banco de dados
if ($mysqli->connect_error) {
  die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Consultar produtos no banco de dados
$result = $mysqli->query("SELECT produtos.*, tipos_produtos.nome AS nome_tipo FROM produtos JOIN tipos_produtos ON produtos.tipo_id = tipos_produtos.id");


$produtos = array();

while ($row = $result->fetch_assoc()) {
  $produtos[] = $row; // Armazena cada linha como um elemento na matriz
}


// Verificar o tipo de usuário (admin ou comum)
$tipo_usuario = $_SESSION["usuario_tipo"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página Inicial</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
  <div class="container mt-5">
    <div class="mb-4 d-flex justify-content-between">
      <h1 class="">Bem-vindo, <?php echo $_SESSION["usuario_nome"]; ?></h1>

      <a href="../backend/logout.php" class="btn btn-danger btn-sm d-flex align-items-center">Logout <i class=" ms-2  fs-4 bi bi-box-arrow-right"></i></a>
    </div>


    <?php if ($tipo_usuario === "admin") : ?>


      <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#produtoModal">
        Cadastrar Produto
      </button>

      <div class="modal fade" id="produtoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Cadastrar Produto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php
              $query = "SELECT * FROM tipos_produtos";
              $result = $mysqli->query($query);
              $num_tipos = $result->num_rows;
              if ($num_tipos > 0) {
              ?>
                <form action="../backend/cadastro_produto.php" method="POST">
                  <div class="mb-3">
                    <label for="nomeProduto" class="form-label">Nome do Produto:</label>
                    <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" required>
                  </div>
                  <div class="mb-3">
                    <label for="descricaoProduto" class="form-label">Descrição:</label>
                    <textarea class="form-control" id="descricaoProduto" name="descricaoProduto" required></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="precoProduto" class="form-label">Preço:</label>
                    <input type="text" class="form-control" id="precoProduto" name="precoProduto" required>
                  </div>
                  <div class="mb-3">
                    <label for="tipoProduto" class="form-label">Tipo de Produto:</label>
                    <select class="form-select" id="tipoProduto" name="tipoProduto" required>
                      <?php
                      while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
              <?php
              } else {
                echo "<p>Nenhum tipo de produto cadastrado. Cadastre pelo menos um tipo de produto primeiro.</p>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>


     
      <button type="button" class="btn btn-primary  mb-5" data-bs-toggle="modal" data-bs-target="#tipoProdutoModal">
        Cadastrar Tipo de Produto
      </button>




      <div class="modal fade" id="tipoProdutoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Cadastrar Tipo de Produto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="../backend/cadastro_tipo_produto.php" method="POST">
                <div class="mb-3">
                  <label for="nomeTipoProduto" class="form-label">Nome do Tipo:</label>
                  <input type="text" class="form-control" id="nomeTipoProduto" name="nomeTipoProduto" required>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    <?php endif; ?>


    <?php
    $query = "SELECT * FROM tipos_produtos";
    $result = $mysqli->query($query);

    $tiposProdutos = array();

    while ($row = $result->fetch_assoc()) {
      $tiposProdutos[] = $row;
    }
    ?>
    <h2 class="mb-3">Tipos de produtos Cadastrados</h2>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome do Tipo de Produto</th>
          <?php if ($tipo_usuario === "admin") : ?>
            <th>Ações</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tiposProdutos as $tipoProduto) : ?>
          <tr>
            <td><?php echo $tipoProduto["id"]; ?></td>
            <td><?php echo $tipoProduto["nome"]; ?></td>
            <?php if ($tipo_usuario === "admin") : ?>
              <td>
                <button class="btn btn-primary editar-tipo-produto" data-id="<?php echo $tipoProduto["id"]; ?>" data-toggle="modal" data-target="#editarTipoProdutoModal">Editar</button>
                <button class="btn btn-danger excluir-tipo-produto" data-id="<?php echo $tipoProduto["id"]; ?>">Excluir</button>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="modal fade" id="tipoProdutoModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Tipo de Produto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="../backend/edicao_tipo_produto.php" method="POST">
              <input type="hidden" id="tipo_produto_id" name="tipo_produto_id" value="">
              <div class="mb-3">
                <label for="nomeTipoProduto" class="form-label">Nome do Tipo:</label>
                <input type="text" class="form-control" id="nomeTipoProdutoEdit" name="nomeTipoProdutoEdit" required>
              </div>
              <button type="submit" class="btn btn-primary">Editar</button>
            </form>
          </div>
        </div>
      </div>
    </div>


    <h2 class="mb-3">Produtos Cadastrados</h2>
    <?php if (count($produtos) > 0) : ?>
      <table class="table">
        <thead>
          <tr>
            <th>Nome do Produto</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Tipo</th>
            <?php if ($tipo_usuario === "admin") : ?>
              <th>Ações</th>
            <?php endif; ?>

          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $produto) : ?>
            <tr>
              <td><?php echo $produto["nome"]; ?></td>
              <td><?php echo $produto["descricao"]; ?></td>
              <td><?php echo $produto["preco"]; ?></td>
              <td><?php echo $produto["nome_tipo"]; ?></td>
              <?php if ($tipo_usuario === "admin") : ?>
                <td>
                  <button class="btn btn-primary editar-produto" data-id="<?php echo $produto["id"]; ?>" data-toggle="modal" data-target="#editarModal">Editar</button>

                  <button class="btn btn-danger deletar-produto" data-id="<?php echo $produto["id"]; ?>">Excluir</button>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar Produto</h5>
              <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


              <form action="../backend/edicao_produto.php" method="POST">
                <input type="hidden" id="produto_id" name="produto_id" value="">
                <div class="mb-3">
                  <label for="nomeProdutoEditar" class="form-label">Nome do Produto:</label>
                  <input type="text" class="form-control" id="nomeProdutoEditar" name="nomeProdutoEditar" required>
                </div>
                <div class="mb-3">
                  <label for="descricaoProdutoEditar" class="form-label">Descrição:</label>
                  <textarea class="form-control" id="descricaoProdutoEditar" name="descricaoProdutoEditar" required></textarea>
                </div>
                <div class="mb-3">
                  <label for="precoProdutoEditar" class="form-label">Preço:</label>
                  <input type="text" class="form-control" id="precoProdutoEditar" name="precoProdutoEditar" required>
                </div>
                <div class="mb-3">
                  <label for="tipoProdutoEditar" class="form-label">Tipo de Produto:</label>
                  <select class="form-select" id="tipoProdutoEditar" name="tipoProdutoEditar" required>

                  </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php else : ?>
      <p>Nenhum produto cadastrado.</p>
    <?php endif; ?>





  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


  <script>

    function excluirProduto(id) {
      if (confirm("Tem certeza de que deseja excluir este produto?")) {
        $.ajax({
          url: '../backend/excluir_produto.php', 
          type: 'POST',
          data: {
            produto_id: id
          },
          success: function(response) {
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(error);
            alert("Ocorreu um erro ao excluir o produto.");
          }
        });
      }
    }



    $(document).ready(function() {
      $(".deletar-produto").click(function() {
        var produtoId = $(this).data("id");
        excluirProduto(produtoId);
      });
    });

    function excluirTipoProduto(id) {
      console.log(id);
      if (confirm('Deseja realmente apagar esse tipo de produto ?')) {

   
        $.ajax({
          url: '../backend/excluir_tipo_produto.php', 
          type: 'POST',
          data: {
            tipo_produto_id: id
          },
          success: function(response) {
            
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(error);
            alert("Ocorreu um erro ao excluir o tipo de produto. Produto relacionado com esse tipo.");
          }
        });
      }
    }

    $(document).ready(function() {
      $(".excluir-tipo-produto").click(function() {
        var tipoProdutoId = $(this).data("id");
        excluirTipoProduto(tipoProdutoId);
      });
    });



    $(document).ready(function() {
      $(".editar-produto").click(function() {
        var produtoId = $(this).data("id");
        exibirModalEdicao(produtoId);
      });
    });

    
    function exibirModalEdicao(produtoId) {


      $.ajax({
        url: '../backend/buscar_detalhes_produto.php', 
        type: 'POST',
        data: {
          produto_id: produtoId
        },
        success: function(response) {
          var detalhesProduto = JSON.parse(response);

          $("#produto_id").val(produtoId);
          $("#nomeProdutoEditar").val(detalhesProduto.nome);
          $("#descricaoProdutoEditar").val(detalhesProduto.descricao);
          $("#precoProdutoEditar").val(detalhesProduto.preco);
          $("#tipoProdutoEditar").empty();

          $.ajax({
            url: '../backend/buscar_tipos_produtos.php', 
            type: 'GET',
            success: function(response) {
              var tiposProdutos = JSON.parse(response);

              $.each(tiposProdutos, function(index, tipo) {
                $("#tipoProdutoEditar").append($('<option>', {
                  value: tipo.id,
                  text: tipo.nome
                }));
              });
            },
            error: function(xhr, status, error) {
              console.error(error);
              alert("Ocorreu um erro ao buscar os tipos de produtos.");
            }
          });

          $('#editarModal').modal('show');
        },
        error: function(xhr, status, error) {
          console.error(error);
          alert("Ocorreu um erro ao buscar os detalhes do produto.");
        }
      });

    }


    $(document).ready(function() {
      $(".editar-tipo-produto").click(function() {
        var tipoProdutoId = $(this).data("id");
        exibirModalEdicaoTipoProduto(tipoProdutoId);
      });
    });

    function exibirModalEdicaoTipoProduto(tipoProdutoId) {


      $.ajax({
        url: '../backend/buscar_detalhes_tipos_produtos.php', 
        type: 'POST',
        data: {
          tipo_produto_id: tipoProdutoId
        },
        success: function(response) {
          var detalhesTipoProduto = JSON.parse(response);

          $("#tipo_produto_id").val(tipoProdutoId);
          $("#nomeTipoProdutoEdit").val(detalhesTipoProduto.nome);



         
          $('#tipoProdutoModalEdit').modal('show');
        },
        error: function(xhr, status, error) {
          console.error(error);
          alert("Ocorreu um erro ao buscar os detalhes do produto.");
        }
      });

    }
  </script>
</body>

</html>

<?php
$mysqli->close();
?>