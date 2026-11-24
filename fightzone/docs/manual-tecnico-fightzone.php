<?php
$sqlFile = __DIR__ . '/../database/fightzone.sql';
$sqlScript = file_get_contents($sqlFile);
if ($sqlScript === false) {
    http_response_code(500);
    throw new RuntimeException('Não foi possível carregar database/fightzone.sql.');
}

$e = static function (string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$dicionario = [
    'usuarios' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['nome', 'VARCHAR(100)', 'Não', 'Nome de exibição da conta.'],
        ['email', 'VARCHAR(150)', 'Não', 'E-mail único usado no login.'],
        ['senha', 'VARCHAR(255)', 'Não', 'Hash da senha; nunca armazenar senha em texto puro.'],
        ['perfil', "ENUM('admin','vendedor','cliente')", 'Não', 'Papel de acesso; padrão cliente.'],
        ['ativo', 'TINYINT(1)', 'Não', 'Indica se a conta pode acessar o sistema; padrão 1.'],
        ['created_at', 'DATETIME', 'Não', 'Data e hora de criação da conta.'],
    ],
    'categorias' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['nome', 'VARCHAR(80)', 'Não', 'Nome apresentado no catálogo.'],
        ['slug', 'VARCHAR(100)', 'Não', 'Identificador único da categoria.'],
        ['ativo', 'TINYINT(1)', 'Não', 'Controla a publicação da categoria; padrão 1.'],
    ],
    'produtos' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['categoria_id', 'INT', 'Não', 'Categoria do produto; FK para categorias.id.'],
        ['nome', 'VARCHAR(150)', 'Não', 'Nome comercial.'],
        ['slug', 'VARCHAR(170)', 'Não', 'Identificador único; usado também como SKU.'],
        ['marca', 'VARCHAR(80)', 'Não', 'Marca do artigo esportivo.'],
        ['descricao', 'TEXT', 'Sim', 'Descrição comercial.'],
        ['preco', 'DECIMAL(10,2)', 'Não', 'Preço em reais.'],
        ['peso', 'DECIMAL(7,2)', 'Não', 'Peso para cálculo estimado de frete; padrão 0,50 kg.'],
        ['altura', 'DECIMAL(7,2)', 'Não', 'Altura do pacote em centímetros; padrão 10.'],
        ['largura', 'DECIMAL(7,2)', 'Não', 'Largura do pacote em centímetros; padrão 10.'],
        ['comprimento', 'DECIMAL(7,2)', 'Não', 'Comprimento do pacote em centímetros; padrão 10.'],
        ['estoque', 'INT', 'Não', 'Unidades disponíveis; padrão 0.'],
        ['imagem', 'VARCHAR(255)', 'Sim', 'Caminho relativo da imagem enviada.'],
        ['ativo', 'TINYINT(1)', 'Não', 'Publicação lógica do produto; padrão 1.'],
        ['created_at', 'DATETIME', 'Não', 'Data e hora de cadastro.'],
    ],
    'clientes' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['usuario_id', 'INT', 'Não', 'Conta vinculada; FK única para usuarios.id.'],
        ['nome', 'VARCHAR(100)', 'Não', 'Nome do cliente no cadastro.'],
        ['cpf', 'VARCHAR(14)', 'Sim', 'Documento informado pelo cliente.'],
        ['telefone', 'VARCHAR(20)', 'Sim', 'Telefone de contato.'],
        ['cep', 'VARCHAR(9)', 'Sim', 'CEP cadastrado.'],
        ['logradouro', 'VARCHAR(150)', 'Sim', 'Rua, avenida ou logradouro.'],
        ['numero', 'VARCHAR(20)', 'Sim', 'Número do endereço.'],
        ['complemento', 'VARCHAR(100)', 'Sim', 'Complemento do endereço.'],
        ['bairro', 'VARCHAR(100)', 'Sim', 'Bairro.'],
        ['cidade', 'VARCHAR(100)', 'Sim', 'Cidade.'],
        ['uf', 'CHAR(2)', 'Sim', 'Unidade federativa.'],
    ],
    'pedidos' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['usuario_id', 'INT', 'Não', 'Titular do pedido; FK para usuarios.id.'],
        ['subtotal', 'DECIMAL(10,2)', 'Não', 'Soma dos itens antes do frete.'],
        ['frete', 'DECIMAL(10,2)', 'Não', 'Valor de frete estimado no checkout.'],
        ['total', 'DECIMAL(10,2)', 'Não', 'Total registrado para o pedido.'],
        ['status', "ENUM('aguardando','pago','separacao','enviado','entregue','cancelado')", 'Não', 'Estado operacional; padrão aguardando.'],
        ['pagamento', "ENUM('pix','cartao','boleto')", 'Não', 'Forma de pagamento escolhida.'],
        ['cep_entrega', 'VARCHAR(9)', 'Sim', 'CEP informado para entrega.'],
        ['endereco_entrega', 'TEXT', 'Sim', 'Endereço de entrega registrado.'],
        ['created_at', 'DATETIME', 'Não', 'Data e hora de criação.'],
        ['codigo_acesso', 'CHAR(48)', 'Sim', 'Código opcional para acesso ao resumo do pedido.'],
        ['cliente_nome', 'VARCHAR(160)', 'Sim', 'Snapshot do nome no momento da compra.'],
        ['cliente_email', 'VARCHAR(190)', 'Sim', 'Snapshot do e-mail no momento da compra.'],
        ['cliente_documento', 'VARCHAR(20)', 'Sim', 'Snapshot de documento do comprador.'],
        ['cliente_telefone', 'VARCHAR(30)', 'Sim', 'Snapshot de telefone do comprador.'],
        ['status_pagamento', 'VARCHAR(30)', 'Sim', 'Situação de pagamento registrada pelo sistema.'],
    ],
    'itens_pedido' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['pedido_id', 'INT', 'Não', 'Pedido ao qual o item pertence; FK para pedidos.id.'],
        ['produto_id', 'INT', 'Não', 'Produto referenciado; FK para produtos.id.'],
        ['quantidade', 'INT', 'Não', 'Quantidade comprada.'],
        ['preco_unitario', 'DECIMAL(10,2)', 'Não', 'Preço unitário registrado no pedido.'],
        ['produto_nome', 'VARCHAR(150)', 'Sim', 'Snapshot do nome do produto para histórico.'],
    ],
    'movimentos_estoque' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['produto_id', 'INT', 'Não', 'Produto movimentado; FK para produtos.id.'],
        ['tipo', "ENUM('entrada','saida','ajuste')", 'Não', 'Natureza do movimento.'],
        ['quantidade', 'INT', 'Não', 'Quantidade movimentada.'],
        ['observacao', 'VARCHAR(255)', 'Sim', 'Descrição complementar.'],
        ['usuario_id', 'INT', 'Sim', 'Responsável pelo registro; FK opcional para usuarios.id.'],
        ['created_at', 'DATETIME', 'Não', 'Data e hora da movimentação.'],
    ],
    'notas_fiscais' => [
        ['id', 'INT', 'Não', 'Chave primária auto incremento.'],
        ['pedido_id', 'INT', 'Não', 'Pedido associado; FK para pedidos.id, único.'],
        ['numero', 'VARCHAR(50)', 'Não', 'Número identificador, único.'],
        ['valor_total', 'DECIMAL(10,2)', 'Não', 'Valor registrado para o documento.'],
        ['created_at', 'DATETIME', 'Não', 'Data e hora de registro.'],
    ],
];

$mostrarDicionario = static function (array $tabelas) use ($e): void {
    foreach ($tabelas as $tabela => $campos) {
        echo '<h3 class="table-name">' . $e($tabela) . '</h3>';
        echo '<table class="dictionary"><thead><tr><th>Campo</th><th>Tipo</th><th>Aceita nulo?</th><th>Descrição / regra</th></tr></thead><tbody>';
        foreach ($campos as $campo) {
            echo '<tr><td><code>' . $e($campo[0]) . '</code></td><td><code>' . $e($campo[1]) . '</code></td><td>' .
                $e($campo[2]) . '</td><td>' . $e($campo[3]) . '</td></tr>';
        }
        echo '</tbody></table>';
    }
};
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light">
  <title>Manual técnico | FightZone</title>
  <style>
    :root {
      --ink: #171816;
      --paper: #fffefa;
      --canvas: #f4f1eb;
      --muted: #66675f;
      --line: #dedbd4;
      --orange: #d8432e;
      --orange-dark: #a92d20;
      --soft: #f8e9e4;
      font-family: "Segoe UI", Arial, sans-serif;
      color: var(--ink);
      background: var(--paper);
      font-size: 10pt;
      line-height: 1.55;
    }
    * { box-sizing: border-box; }
    body { margin: 0; background: var(--paper); }
    @page {
      size: A4;
      margin: 17mm 17mm 19mm;
      @bottom-left {
        content: "FIGHTZONE  •  MANUAL TÉCNICO";
        color: #777870;
        font: 8pt "Segoe UI", Arial, sans-serif;
      }
      @bottom-right {
        content: counter(page);
        color: #d8432e;
        font: bold 9pt "Segoe UI", Arial, sans-serif;
      }
    }
    @page:first { margin: 0; @bottom-left { content: none; } @bottom-right { content: none; } }
    .cover {
      height: 297mm;
      padding: 27mm 24mm 22mm;
      display: flex;
      flex-direction: column;
      color: #fffefa;
      background:
        radial-gradient(ellipse at 87% 86%, rgba(216,67,46,.18), transparent 34%),
        linear-gradient(145deg, #1d1e1b 0%, #171816 58%, #10110f 100%);
      page-break-after: always;
      position: relative;
      overflow: hidden;
    }
    .cover::after {
      content: "FZ";
      position: absolute;
      right: 12mm;
      bottom: 8mm;
      color: rgba(255,254,250,.035);
      font-size: 150pt;
      font-weight: 900;
      letter-spacing: -.12em;
      line-height: .8;
    }
    .brand { display:flex; align-items:center; gap:12px; font-weight:900; letter-spacing:.18em; position:relative; z-index:1; }
    .mark { width:42px; height:42px; display:grid; place-items:center; background:var(--orange); border-radius:11px; font-size:15px; letter-spacing:0; }
    .brand-name span { color:#e45a42; }
    .cover-rule { width:76px; height:5px; background:#e45a42; margin-top:39mm; }
    .cover-kicker { color:#f0a08f; font-size:9pt; font-weight:800; letter-spacing:.2em; text-transform:uppercase; margin:9mm 0 4mm; }
    .cover h1 { max-width:160mm; margin:0; color:#fffefa; font-size:34pt; line-height:1.04; letter-spacing:-.04em; }
    .cover h1 span { color:#e45a42; }
    .cover-intro { max-width:142mm; color:#c1c0b8; font-size:13pt; margin-top:7mm; }
    .cover-meta { margin-top:auto; padding-top:9mm; border-top:1px solid #393832; position:relative; z-index:1; }
    .cover-meta strong { color:#fffefa; }
    .cover-meta .label { display:block; color:#a6a59e; font-size:8pt; font-weight:800; letter-spacing:.16em; text-transform:uppercase; margin-bottom:2mm; }
    .team { display:flex; gap:18mm; margin:5mm 0 8mm; font-size:11pt; font-weight:700; }
    .cover-note { color:#aaa9a1; font-size:8pt; }
    .page { page-break-before: always; }
    .page.first-content { page-break-before: auto; }
    .eyebrow { color:var(--orange-dark); font-size:8pt; font-weight:800; letter-spacing:.18em; text-transform:uppercase; margin:0 0 2mm; }
    h1,h2,h3 { color:var(--ink); line-height:1.2; }
    h2 { font-size:21pt; margin:0 0 6mm; letter-spacing:-.025em; }
    h3 { font-size:13pt; margin:7mm 0 2mm; }
    h4 { font-size:10pt; margin:4mm 0 1.5mm; }
    p { margin:0 0 3.2mm; }
    .lead { font-size:12pt; color:#44453f; }
    .callout { border-left:4px solid var(--orange); background:#f7f5f0; padding:4mm 5mm; margin:4mm 0; }
    .callout strong { color:var(--orange-dark); }
    .grid { display:grid; grid-template-columns:1fr 1fr; gap:4mm; margin:4mm 0; }
    .card { border:1px solid var(--line); background:var(--paper); border-radius:3mm; padding:4mm; break-inside:avoid; }
    .card h3 { margin:0 0 2mm; font-size:11pt; }
    .card p:last-child { margin-bottom:0; }
    ul,ol { margin:1.5mm 0 4mm; padding-left:6mm; }
    li { margin:1.2mm 0; }
    .compact li { margin:.6mm 0; }
    .toc { list-style:none; padding:0; margin:5mm 0 0; }
    .toc li { border-bottom:1px solid var(--line); padding:2.5mm 0; display:flex; gap:4mm; }
    .toc b { color:var(--orange); min-width:8mm; }
    .toc a { color:var(--ink); text-decoration:none; font-weight:650; }
    table { width:100%; border-collapse:collapse; margin:3mm 0 5mm; font-size:8.5pt; }
    th { color:#fffefa; background:#292a26; text-align:left; font-weight:700; }
    th,td { border:1px solid var(--line); padding:2.2mm 2.5mm; vertical-align:top; }
    tbody tr:nth-child(even) { background:#f7f5f0; }
    .fivew th:first-child { width:24mm; color:#fffefa; background:var(--orange-dark); }
    .dictionary { font-size:7.7pt; margin-top:0; }
    .dictionary th { background:#34352f; }
    .dictionary td { padding:1.45mm 1.8mm; }
    .dictionary td:nth-child(1) { width:27mm; }
    .dictionary td:nth-child(2) { width:43mm; }
    .dictionary td:nth-child(3) { width:22mm; white-space:nowrap; }
    .table-name { color:var(--orange-dark); border-bottom:2px solid var(--orange); padding-bottom:1.5mm; margin:6mm 0 2mm; break-after:avoid; }
    code { font-family:Consolas, "Courier New", monospace; font-size:.92em; }
    figure { margin:4mm 0 5mm; break-inside:avoid; }
    figure img { width:100%; max-height:190mm; object-fit:contain; display:block; }
    figcaption { color:var(--muted); font-size:8pt; margin-top:2mm; text-align:center; }
    .screen img { max-height:132mm; border:1px solid var(--line); }
    .sql { background:#171816; color:#f0eee7; padding:4mm; border-radius:2mm; font:6.4pt/1.38 Consolas, "Courier New", monospace; white-space:pre-wrap; overflow-wrap:anywhere; tab-size:2; }
    .sql-intro { color:var(--muted); font-size:8.5pt; }
    .legend { display:flex; gap:6mm; color:var(--muted); font-size:8pt; margin:2mm 0; }
    .mono { font-family:Consolas, "Courier New", monospace; }
    .small { color:var(--muted); font-size:8pt; }
    .end-note { margin-top:10mm; padding-top:4mm; border-top:2px solid var(--orange); color:var(--muted); font-size:8pt; }
    .no-break { break-inside:avoid; }
    .use-case { border-top:3px solid var(--orange); padding-top:3mm; }
    .use-case-meta { display:grid; grid-template-columns:1fr 1fr; gap:2mm 7mm; margin:4mm 0; padding:4mm; background:#f7f5f0; border-radius:2mm; }
    .use-case-meta p { margin:0; }
    .sequence img { max-height:190mm; }
    .test-matrix { font-size:7.8pt; }
    .test-matrix td:first-child { white-space:nowrap; font-weight:700; color:var(--orange-dark); }
    .trace-table td:first-child { white-space:nowrap; font-weight:700; }
    .route-table code { white-space:nowrap; }
    .code-flow { margin:3mm 0 5mm; padding:4mm; border-left:4px solid var(--orange); background:#f7f5f0; font:8pt/1.5 Consolas, "Courier New", monospace; white-space:pre-wrap; overflow-wrap:anywhere; }
    .risk { border-left-color:#a92d20; background:#f8e9e4; }
    @media screen {
      body { background:#e5e3dd; padding:18px; }
      .cover,.page { width:210mm; min-height:297mm; margin:0 auto 18px; padding:17mm; background:var(--paper); box-shadow:0 8px 26px #0002; }
      .cover { padding:27mm 24mm 22mm; background:linear-gradient(145deg,#1d1e1b 0%,#171816 58%,#10110f 100%); }
      .page.first-content { padding-top:17mm; }
    }
  </style>
</head>
<body>
  <section class="cover">
    <div class="brand"><span class="mark">FZ</span><span class="brand-name">FIGHT<span>ZONE</span></span></div>
    <div class="cover-rule"></div>
    <p class="cover-kicker">Projeto acadêmico • loja virtual de artigos de boxe</p>
    <h1>Manual técnico do sistema <span>FightZone</span></h1>
    <p class="cover-intro">Análise, casos de uso, fluxos transacionais, permissões, plano de testes, modelagem UML, DER, dicionário e script SQL integral.</p>
    <div class="cover-meta">
      <span class="label">Equipe desenvolvedora</span>
      <div class="team"><span>Diego Filipe</span><span>Eduardo Cavalcante</span></div>
      <p><strong>Versão:</strong> 1.1 &nbsp; • &nbsp; <strong>Edição:</strong> Novembro de 2026</p>
      <p class="cover-note">Material técnico para avaliação acadêmica. A interface e a documentação usam a identidade visual FightZone.</p>
    </div>
  </section>

  <section class="page first-content" id="sumario">
    <p class="eyebrow">Guia de leitura</p>
    <h2>Sumário</h2>
    <p>Este volume documenta os aspectos técnicos e de análise do projeto. O manual de uso para clientes e operadores será tratado em um volume separado.</p>
    <ol class="toc">
      <li><b>01</b><a href="#apresentacao">Apresentação, objetivo e escopo</a></li>
      <li><b>02</b><a href="#estudo">Estudo de caso e minimundo</a></li>
      <li><b>03</b><a href="#requisitos">Atores, requisitos e regras de negócio</a></li>
      <li><b>04</b><a href="#plano">Plano de ação — metodologia 5W2H</a></li>
      <li><b>05</b><a href="#arquitetura">Arquitetura e organização do sistema</a></li>
      <li><b>06</b><a href="#uml">Modelagem UML: casos de uso, classes e atividades</a></li>
      <li><b>07</b><a href="#der">Modelo de dados — DER</a></li>
      <li><b>08</b><a href="#dicionario">Dicionário de dados</a></li>
      <li><b>09</b><a href="#execucao">Ambiente, instalação e operação técnica</a></li>
      <li><b>10</b><a href="#prototipo">Prototipação e arquivos para o Figma</a></li>
      <li><b>11</b><a href="#limites">Limites conhecidos e melhorias futuras</a></li>
      <li><b>12</b><a href="#casos-de-uso">Especificação detalhada dos casos de uso</a></li>
      <li><b>13</b><a href="#sequencias">Diagramas UML de sequência</a></li>
      <li><b>14</b><a href="#fluxos-tecnicos">Fluxos transacionais e estados</a></li>
      <li><b>15</b><a href="#mapa-codigo">Mapa do código e permissões</a></li>
      <li><b>16</b><a href="#testes">Plano de testes e rastreabilidade</a></li>
      <li><b>17</b><a href="#glossario">Glossário, referências e evidências</a></li>
      <li><b>A</b><a href="#sql">Apêndice A — script SQL integral</a></li>
    </ol>
    <div class="callout"><strong>Nota de escopo:</strong> este é o manual técnico. Ele descreve a solução e seus artefatos; o <code>docs/manual-usuario-fightzone.pdf</code> complementa este volume com instruções operacionais passo a passo para clientes e equipe.</div>
  </section>

  <section class="page" id="apresentacao">
    <p class="eyebrow">01 • Contexto do projeto</p>
    <h2>Apresentação, objetivo e escopo</h2>
    <p class="lead">FightZone é uma aplicação web acadêmica para uma loja virtual de artigos de boxe, com catálogo público, compra por clientes e ferramentas de apoio à operação da loja.</p>
    <h3>Objetivo geral</h3>
    <p>Centralizar a consulta de produtos, a criação de pedidos e o controle administrativo de catálogo, estoque e vendas em um sistema web desenvolvido com PHP e MySQL.</p>
    <h3>Objetivos específicos</h3>
    <ul class="compact">
      <li>Apresentar produtos ativos e disponíveis, organizados por categoria.</li>
      <li>Permitir cadastro e autenticação de clientes e acesso restrito da equipe.</li>
      <li>Registrar carrinho, estimativa de frete, dados de entrega e forma de pagamento escolhida.</li>
      <li>Persistir pedidos e itens, consultar o histórico do cliente e controlar estados do pedido.</li>
      <li>Disponibilizar operações administrativas para produtos, categorias, estoque, pedidos e relatórios.</li>
      <li>Manter o histórico relevante por meio de chaves relacionais e snapshots do pedido.</li>
    </ul>
    <div class="grid">
      <article class="card"><h3>Dentro do escopo</h3><p>Loja web responsiva, autenticação por perfil, catálogo, carrinho, checkout demonstrativo, painel de gestão e banco relacional.</p></article>
      <article class="card"><h3>Fora do escopo atual</h3><p>Gateway de pagamento real, cotação oficial de transportadora, rastreamento, emissão fiscal eletrônica e operação comercial em produção.</p></article>
    </div>
    <h3>Identificação</h3>
    <table><tbody>
      <tr><th>Projeto</th><td>FightZone — comércio eletrônico de artigos para boxe</td></tr>
      <tr><th>Desenvolvedores</th><td>Diego Filipe e Eduardo Cavalcante</td></tr>
      <tr><th>Tecnologias</th><td>PHP 8, MySQL/MariaDB, PDO, HTML, CSS e XAMPP</td></tr>
      <tr><th>Organização</th><td>Aplicação PHP em padrão MVC, com controladores, modelos, visões e recursos estáticos</td></tr>
    </tbody></table>
  </section>

  <section class="page" id="estudo">
    <p class="eyebrow">02 • Análise do domínio</p>
    <h2>Estudo de caso e minimundo</h2>
    <h3>Estudo de caso: compra e gestão de artigos de boxe</h3>
    <p>Uma loja especializada precisa apresentar luvas, bandagens, proteções, roupas e acessórios. O cliente deve localizar itens, montar um carrinho e solicitar a compra. A equipe precisa manter os dados do catálogo, registrar reposições e consultar os pedidos. O sistema FightZone reúne esses fluxos num único ambiente web.</p>
    <div class="grid">
      <article class="card"><h3>Situação-problema</h3><p>Sem um catálogo centralizado e um registro estruturado das compras, torna-se difícil manter informações consistentes de produtos, quantidades disponíveis e pedidos dos clientes.</p></article>
      <article class="card"><h3>Resposta proposta</h3><p>Um catálogo conectado a um banco relacional, com sessões de cliente, carrinho, persistência de pedidos e área administrativa protegida por perfil.</p></article>
    </div>
    <h3>Fluxo principal do caso</h3>
    <ol>
      <li>O visitante consulta o catálogo e seleciona produtos ativos com estoque.</li>
      <li>Para concluir a compra, cria uma conta ou entra como cliente.</li>
      <li>Revê o carrinho, informa o CEP e recebe uma estimativa de frete.</li>
      <li>Informa a entrega e escolhe PIX, cartão ou boleto como preferência.</li>
      <li>O sistema valida os itens, registra pedido e snapshots de preço/nome, e atualiza o estoque.</li>
      <li>O cliente acompanha o pedido e, enquanto estiver aguardando, pode solicitar cancelamento.</li>
      <li>Usuários da equipe administram o catálogo e consultam pedidos e relatórios de acordo com o perfil.</li>
    </ol>
    <h3>Minimundo</h3>
    <p>A loja comercializa produtos pertencentes a categorias. Cada produto possui preço, quantidade em estoque, dados de embalagem e estado de publicação. Usuários podem ter perfil de cliente, vendedor ou administrador. Um cliente possui um cadastro associado a uma conta e pode gerar vários pedidos. Cada pedido registra valores, endereço de entrega, forma de pagamento escolhida e situação. Um pedido contém um ou mais itens, cada qual ligado a um produto e com quantidade e preço unitário registrados. Entradas, saídas e ajustes são representados por movimentos de estoque associados a um produto e, quando conhecido, ao usuário responsável. A estrutura reserva ainda uma tabela de notas fiscais, sem representar integração fiscal efetiva.</p>
    <div class="callout"><strong>Regra de histórico:</strong> nome do produto e dados do cliente podem ser copiados para o pedido/itens no momento da compra. Esses snapshots preservam o contexto comercial mesmo quando um cadastro é alterado posteriormente.</div>
  </section>

  <section class="page" id="requisitos">
    <p class="eyebrow">03 • Especificação</p>
    <h2>Atores, requisitos e regras</h2>
    <h3>Atores do sistema</h3>
    <table><thead><tr><th>Ator</th><th>Responsabilidade</th><th>Acesso principal</th></tr></thead><tbody>
      <tr><td>Visitante</td><td>Explora a loja sem autenticação.</td><td>Catálogo e pesquisa.</td></tr>
      <tr><td>Cliente</td><td>Compra e acompanha seus próprios pedidos.</td><td>Conta, carrinho, checkout, histórico e cancelamento permitido.</td></tr>
      <tr><td>Vendedor</td><td>Atende a operação de vendas.</td><td>Painel, pedidos e relatórios conforme as permissões implementadas.</td></tr>
      <tr><td>Administrador</td><td>Configura e mantém a loja.</td><td>Produtos, categorias, entradas de estoque e demais recursos administrativos.</td></tr>
    </tbody></table>
    <h3>Requisitos funcionais</h3>
    <table><thead><tr><th>ID</th><th>Requisito</th><th>Prioridade</th></tr></thead><tbody>
      <tr><td>RF01</td><td>Exibir e pesquisar produtos ativos e disponíveis por categoria.</td><td>Essencial</td></tr>
      <tr><td>RF02</td><td>Cadastrar cliente e autenticar contas ativas segundo seu perfil.</td><td>Essencial</td></tr>
      <tr><td>RF03</td><td>Adicionar produtos ao carrinho, alterar quantidades e calcular frete estimado.</td><td>Essencial</td></tr>
      <tr><td>RF04</td><td>Validar checkout, criar pedido e itens e atualizar o estoque.</td><td>Essencial</td></tr>
      <tr><td>RF05</td><td>Listar pedidos do cliente e permitir cancelamento apenas nos estados autorizados.</td><td>Essencial</td></tr>
      <tr><td>RF06</td><td>Cadastrar, editar e inativar produtos e categorias.</td><td>Administrativo</td></tr>
      <tr><td>RF07</td><td>Registrar entradas de estoque e manter seus movimentos.</td><td>Administrativo</td></tr>
      <tr><td>RF08</td><td>Consultar pedidos, indicadores e relatórios da loja.</td><td>Administrativo</td></tr>
      <tr><td>RF09</td><td>Gerar um resumo demonstrativo do pedido em PDF.</td><td>Complementar</td></tr>
    </tbody></table>
    <h3>Requisitos não funcionais e regras</h3>
    <ul class="compact">
      <li><strong>RNF01 — Persistência:</strong> dados relacionais armazenados em MySQL/MariaDB com InnoDB, integridade referencial e codificação utf8mb4.</li>
      <li><strong>RNF02 — Acesso:</strong> separar as sessões de cliente e equipe; o perfil registrado determina o acesso ao painel.</li>
      <li><strong>RNF03 — Senhas:</strong> armazenar hashes e verificar as credenciais com as funções de password do PHP.</li>
      <li><strong>RNF04 — Integridade de formulários:</strong> operações POST da conta validam token CSRF; consultas de dados usam PDO e parâmetros preparados nos modelos.</li>
      <li><strong>RNF05 — Imagens:</strong> upload de imagem de produto limitado a tipos JPG, PNG e WebP, com tamanho máximo indicado de 5 MB.</li>
      <li><strong>RNF06 — Transparência:</strong> não apresentar estimativa de frete, seleção de pagamento ou PDF demonstrativo como serviço real ou documento fiscal.</li>
      <li><strong>RNF07 — Implantação:</strong> ambiente acadêmico local em XAMPP; configuração de produção, monitoramento e hardening não fazem parte deste escopo.</li>
    </ul>
    <div class="callout"><strong>Regra de negócio:</strong> pedidos pendentes já reservam/baixam itens do estoque no momento do registro. O cancelamento permitido devolve as quantidades ao estoque; a seleção de pagamento não confirma o pagamento.</div>
  </section>

  <section class="page" id="plano">
    <p class="eyebrow">04 • Planejamento</p>
    <h2>Plano de ação — metodologia 5W2H</h2>
    <p>O quadro abaixo organiza as frentes necessárias para conceber, implementar e apresentar o FightZone. Ele descreve um plano acadêmico de execução, não um histórico de datas efetivamente realizadas.</p>
    <table class="fivew"><thead><tr><th>Elemento</th><th>Plano de ação</th></tr></thead><tbody>
      <tr><th>What<br>(O quê?)</th><td>Desenvolver uma loja virtual de artigos de boxe com catálogo, conta de cliente, pedidos, estoque e painel administrativo.</td></tr>
      <tr><th>Why<br>(Por quê?)</th><td>Organizar a jornada de compra e demonstrar a aplicação integrada de análise de requisitos, modelagem, desenvolvimento web e banco de dados.</td></tr>
      <tr><th>Who<br>(Quem?)</th><td>Equipe desenvolvedora: Diego Filipe e Eduardo Cavalcante. A avaliação e validação acadêmica cabem aos professores responsáveis.</td></tr>
      <tr><th>Where<br>(Onde?)</th><td>Desenvolvimento e demonstração em ambiente local com XAMPP; aplicação acessada pelo navegador.</td></tr>
      <tr><th>When<br>(Quando?)</th><td>Etapas sequenciais do projeto: levantamento e estudo do domínio; modelagem; implementação; testes e revisão; preparação da demonstração e documentação.</td></tr>
      <tr><th>How<br>(Como?)</th><td>PHP organizado em MVC, persistência MySQL via PDO, sessões para autenticação, HTML/CSS para a interface, modelagem UML/DER e validação manual dos fluxos.</td></tr>
      <tr><th>How much<br>(Quanto?)</th><td>Plano baseado em ferramentas locais e de código aberto, sem custo de licença previsto para o protótipo. Hospedagem, gateway, frete real e emissão fiscal ficam fora do orçamento e do escopo atuais.</td></tr>
    </tbody></table>
    <h3>Marcos sugeridos para o projeto</h3>
    <table><thead><tr><th>Marco</th><th>Entregável verificável</th><th>Critério de conclusão</th></tr></thead><tbody>
      <tr><td>1. Análise</td><td>Minimundo, caso de uso e requisitos</td><td>Escopo, atores e regras de negócio descritos.</td></tr>
      <tr><td>2. Modelagem</td><td>Diagramas UML e DER; dicionário</td><td>Entidades, relações e responsabilidades coerentes com a implementação.</td></tr>
      <tr><td>3. Implementação</td><td>Loja, painel e estrutura MySQL</td><td>Fluxos principais acessíveis no ambiente local.</td></tr>
      <tr><td>4. Validação</td><td>Testes manuais e revisão do conteúdo</td><td>Compra demonstrativa, perfis e operações administrativas verificados.</td></tr>
      <tr><td>5. Apresentação</td><td>Manual técnico, protótipos e demonstração</td><td>Artefatos atualizados e limitações comunicadas claramente.</td></tr>
    </tbody></table>
  </section>

  <section class="page" id="arquitetura">
    <p class="eyebrow">05 • Projeto técnico</p>
    <h2>Arquitetura e organização</h2>
    <p>A aplicação segue uma organização MVC simples: a entrada frontal <code>index.php</code> seleciona controlador e ação; controladores coordenam validações e fluxo; modelos encapsulam operações no banco; visões PHP apresentam a interface; CSS e imagens ficam em recursos públicos.</p>
    <table><thead><tr><th>Camada / diretório</th><th>Responsabilidade</th><th>Exemplos do projeto</th></tr></thead><tbody>
      <tr><td>Entrada e roteamento</td><td>Inicializar a aplicação e encaminhar requisições.</td><td><code>index.php</code>, parâmetros <code>controller</code> e <code>action</code>.</td></tr>
      <tr><td>Controladores</td><td>Validar requisições, sessão/perfil e coordenar modelos e visões.</td><td><code>controllers/ClienteController.php</code>, <code>LojaController.php</code>, <code>ProdutoController.php</code>.</td></tr>
      <tr><td>Modelos</td><td>Executar consultas e regras de persistência no banco.</td><td><code>models/Compra.php</code>, <code>Produto</code>, <code>ClienteConta.php</code>.</td></tr>
      <tr><td>Visões</td><td>Renderizar páginas, formulários e conteúdo HTML.</td><td><code>views/loja.php</code>, <code>views/conta.php</code>, <code>views/dashboard.php</code>.</td></tr>
      <tr><td>Configuração</td><td>Conexão de banco e configuração de pagamento de demonstração.</td><td><code>config/db.php</code>, <code>config/pagamento.php</code>.</td></tr>
      <tr><td>Banco e recursos</td><td>Instalação SQL, CSS, imagens e documentos.</td><td><code>database/fightzone.sql</code>, <code>public/assets/</code>, <code>uploads/</code>, <code>docs/</code>.</td></tr>
    </tbody></table>
    <h3>Fluxo de uma requisição</h3>
    <ol>
      <li>O navegador solicita a rota ao ponto de entrada PHP.</li>
      <li>O controlador seleciona o fluxo e verifica método, sessão, perfil e dados recebidos.</li>
      <li>O modelo consulta ou altera o MySQL por meio da conexão compartilhada em <code>config/db.php</code>.</li>
      <li>A visão apresenta o resultado; ações de formulário podem redirecionar após atualizar a sessão.</li>
    </ol>
    <h3>Persistência e segurança no desenho atual</h3>
    <ul class="compact">
      <li>A conexão usa PDO, modo de erro por exceção e resultados associativos.</li>
      <li>O fluxo de conta utiliza hash/verificação de senha, sessão regenerada no login e token CSRF nas requisições POST protegidas.</li>
      <li>Produtos/categorias removidos do catálogo são inativados, preservando referências históricas.</li>
      <li>A compra e o cancelamento tratam a alteração do pedido e do estoque em operações transacionais no modelo de compra.</li>
      <li>As credenciais e configurações padrão do XAMPP são exclusivamente de desenvolvimento e devem ser substituídas antes de qualquer implantação pública.</li>
    </ul>
    <div class="callout"><strong>Limite de disponibilidade:</strong> a conexão padrão em <code>config/db.php</code> é local (<code>localhost</code>, usuário <code>root</code>, senha vazia). Não reutilizar essa configuração em ambiente exposto.</div>
  </section>

  <section class="page" id="uml">
    <p class="eyebrow">06 • Modelagem UML</p>
    <h2>Casos de uso e classes</h2>
    <p>Os diagramas abaixo são modelos de análise do domínio e dos principais fluxos, mantidos como SVGs junto à documentação. O diagrama de classes é intencionalmente simplificado; o DER e o dicionário descrevem o esquema físico em mais detalhe.</p>
    <figure>
      <img src="uml-casos-de-uso.svg" alt="Diagrama UML dos casos de uso e atores da FightZone">
      <figcaption>Figura 1 — Atores e casos de uso da aplicação.</figcaption>
    </figure>
    <figure>
      <img src="uml-classes.svg" alt="Diagrama UML simplificado de classes de domínio da FightZone">
      <figcaption>Figura 2 — Classes de domínio e associações principais.</figcaption>
    </figure>
    <h3>Leitura do modelo de classes</h3>
    <ul class="compact">
      <li><strong>Usuario / Cliente:</strong> identidade e dados de cliente associados à conta; a implementação também distingue a sessão da equipe.</li>
      <li><strong>Categoria / Produto:</strong> organização do catálogo; uma categoria agrupa produtos e cada produto pertence a uma categoria.</li>
      <li><strong>Pedido / ItemPedido:</strong> pedido reúne itens, quantidades e preços registrados; cada item aponta para o produto original.</li>
      <li><strong>MovimentoEstoque:</strong> registra ocorrências de entrada, saída ou ajuste relacionadas a produto e, opcionalmente, a usuário.</li>
    </ul>
    <div class="callout"><strong>Convenção UML:</strong> os números próximos às associações indicam multiplicidade. A imagem representa o domínio; os nomes das tabelas concretas usam snake_case, conforme o esquema SQL.</div>
  </section>

  <section class="page">
    <p class="eyebrow">06 • Modelagem UML</p>
    <h2>Atividades do processo de compra</h2>
    <p>O diagrama de atividades detalha a sequência de compra, incluindo a necessidade de autenticação e uma decisão para corrigir dados ou estoque inválidos antes de confirmar o pedido.</p>
    <figure>
      <img src="uml-atividade-compra.svg" alt="Diagrama UML de atividades do processo de compra">
      <figcaption>Figura 3 — Fluxo de atividades do checkout demonstrativo.</figcaption>
    </figure>
    <h3>Estados de pedido armazenados</h3>
    <table><thead><tr><th>Estado</th><th>Significado no domínio</th></tr></thead><tbody>
      <tr><td><code>aguardando</code></td><td>Pedido registrado, aguardando a evolução operacional/pagamento.</td></tr>
      <tr><td><code>pago</code></td><td>Estado de pagamento indicado no sistema; não é confirmação automática de gateway.</td></tr>
      <tr><td><code>separacao</code></td><td>Pedido em preparação.</td></tr>
      <tr><td><code>enviado</code></td><td>Pedido marcado como enviado; não existe rastreamento integrado.</td></tr>
      <tr><td><code>entregue</code></td><td>Pedido marcado como entregue.</td></tr>
      <tr><td><code>cancelado</code></td><td>Pedido cancelado; o fluxo permitido pelo cliente devolve itens ao estoque.</td></tr>
    </tbody></table>
    <p class="small">Os estados acima refletem os valores definidos para <code>pedidos.status</code> no banco. Não confundir o estado operacional com confirmação externa de pagamento.</p>
  </section>

  <section class="page" id="der">
    <p class="eyebrow">07 • Modelagem de dados</p>
    <h2>Diagrama entidade-relacionamento</h2>
    <p>O DER apresenta as oito tabelas criadas pelo script de instalação e suas chaves principais e estrangeiras. Os campos adicionados posteriormente à tabela <code>pedidos</code> e a <code>itens_pedido</code> aparecem no dicionário.</p>
    <div class="legend"><span>PK — chave primária</span><span>FK — chave estrangeira</span><span>1:N — um para muitos</span></div>
    <figure><img src="uml-der.svg" alt="Diagrama entidade-relacionamento das oito tabelas FightZone"><figcaption>Figura 4 — DER simplificado das tabelas FightZone. A tabela notas_fiscais é estrutural e não representa emissão fiscal.</figcaption></figure>
    <h3>Relacionamentos e integridade referencial</h3>
    <ul class="compact">
      <li><code>usuarios 1 — 0..1 clientes</code>: <code>clientes.usuario_id</code> é único e referencia uma conta.</li>
      <li><code>usuarios 1 — N pedidos</code>: o titular é identificado por <code>pedidos.usuario_id</code>.</li>
      <li><code>categorias 1 — N produtos</code>: <code>produtos.categoria_id</code> referencia a categoria.</li>
      <li><code>pedidos 1 — N itens_pedido</code>: cada item pertence a um pedido.</li>
      <li><code>produtos 1 — N itens_pedido</code>: cada item aponta ao produto comprado.</li>
      <li><code>produtos 1 — N movimentos_estoque</code>; <code>usuarios 0..1 — N movimentos_estoque</code>.</li>
      <li><code>pedidos 1 — 0..1 notas_fiscais</code>: a tabela impõe pedido único por registro e número de documento único.</li>
    </ul>
    <p class="small">As relações são protegidas por constraints de chave estrangeira InnoDB. O script não declara ações ON DELETE em cascata.</p>
  </section>

  <section class="page" id="dicionario">
    <p class="eyebrow">08 • Referência de dados</p>
    <h2>Dicionário de dados</h2>
    <p>Tipos, nulabilidade e descrições abaixo refletem a definição em <code>database/fightzone.sql</code>. As colunas acrescentadas por <code>ALTER TABLE</code> estão incluídas. “Sim” indica que a coluna permite <code>NULL</code>; padrões aplicáveis são indicados na descrição.</p>
    <?php $mostrarDicionario($dicionario); ?>
  </section>

  <section class="page" id="execucao">
    <p class="eyebrow">09 • Ambiente e implantação</p>
    <h2>Instalação e operação técnica</h2>
    <h3>Ambiente de desenvolvimento previsto</h3>
    <ul class="compact">
      <li>Windows com XAMPP, Apache e MySQL/MariaDB iniciados.</li>
      <li>PHP 8.x com PDO MySQL habilitado; navegador atualizado.</li>
      <li>Projeto em <code>C:\xampp\htdocs\fightzone</code>.</li>
      <li>Base configurada como <code>fightzone</code>, charset <code>utf8mb4</code>.</li>
    </ul>
    <h3>Procedimento de instalação</h3>
    <ol>
      <li>Copiar a pasta do projeto para o diretório do Apache.</li>
      <li>Iniciar os serviços Apache e MySQL pelo painel do XAMPP.</li>
      <li>Importar <code>database/fightzone.sql</code> no phpMyAdmin. O script cria a base/tabelas, aplica colunas adicionais e popula dados de demonstração; leia a seção de dados iniciais antes de executá-lo em uma base com informações importantes.</li>
      <li>Conferir a conexão em <code>config/db.php</code> e a configuração de pagamento demonstrativo, sem inserir segredos reais em arquivos públicos.</li>
      <li>Acessar <code>http://localhost/fightzone/</code> e validar o fluxo de loja e painel com contas de demonstração.</li>
    </ol>
    <h3>Artefatos e responsabilidades</h3>
    <table><thead><tr><th>Artefato</th><th>Finalidade</th></tr></thead><tbody>
      <tr><td><code>database/fightzone.sql</code></td><td>Criação e atualização de tabelas, índices, relações e dados de demonstração.</td></tr>
      <tr><td><code>config/db.php</code></td><td>Conexão PDO para MySQL.</td></tr>
      <tr><td><code>controllers/</code>, <code>models/</code>, <code>views/</code></td><td>Fluxos MVC da loja, conta, pedidos e painel.</td></tr>
      <tr><td><code>public/assets/</code></td><td>CSS e recursos estáticos da interface.</td></tr>
      <tr><td><code>uploads/</code></td><td>Imagens de produtos referenciadas pelo catálogo.</td></tr>
      <tr><td><code>docs/</code></td><td>Manual, apresentação e diagramas do projeto.</td></tr>
    </tbody></table>
    <div class="callout"><strong>Importante:</strong> o SQL cria/atualiza uma conta administrativa e produtos demonstrativos. A senha da conta é armazenada como hash no banco. As credenciais de demonstração não devem ser usadas em produção; consulte o README para os dados de acesso do ambiente de apresentação.</div>
  </section>

  <section class="page" id="prototipo">
    <p class="eyebrow">10 • Design de interface</p>
    <h2>Prototipação e arquivos para o Figma</h2>
    <p>A pasta <code>figma/</code> reúne três telas em SVG vetorial, prontas para importação no Figma como referências editáveis. São protótipos visuais sem integração com o backend; não há um visualizador HTML local no projeto.</p>
    <h3>Tela de loja</h3>
    <figure class="screen"><img src="../figma/01-loja.svg" alt="Protótipo FightZone da página de loja"><figcaption>Figura 5 — Catálogo e identidade visual do protótipo.</figcaption></figure>
    <h3>Arquivos do protótipo</h3>
    <ul class="compact">
      <li><code>figma/01-loja.svg</code> — tela de catálogo.</li>
      <li><code>figma/02-carrinho.svg</code> — tela de revisão do carrinho e frete estimado.</li>
      <li><code>figma/03-painel.svg</code> — painel administrativo demonstrativo.</li>
    </ul>
    <p>Para levar as telas ao Figma, use <em>File → Place image</em> ou arraste cada SVG para uma página do arquivo. O Figma importa os elementos vetoriais para edição; então, conecte os quadros na área de prototipação para definir interações entre as telas.</p>
  </section>

  <section class="page">
    <p class="eyebrow">10 • Design de interface</p>
    <h2>Protótipos: carrinho e painel</h2>
    <figure class="screen"><img src="../figma/02-carrinho.svg" alt="Protótipo FightZone da tela do carrinho"><figcaption>Figura 6 — Carrinho com estimativa de frete e resumo de compra.</figcaption></figure>
    <figure class="screen"><img src="../figma/03-painel.svg" alt="Protótipo FightZone do painel administrativo"><figcaption>Figura 7 — Painel administrativo com indicadores de demonstração.</figcaption></figure>
    <div class="callout"><strong>Valores ilustrativos:</strong> números, estados e ações visuais dos quadros são exemplos de prototipação; não são dados reais nem confirmação de operações no sistema.</div>
  </section>

  <section class="page" id="limites">
    <p class="eyebrow">11 • Transparência e continuidade</p>
    <h2>Limites conhecidos e melhorias futuras</h2>
    <h3>Comportamentos demonstrativos atuais</h3>
    <ul>
      <li><strong>Frete:</strong> estimativa calculada localmente a partir de CEP, peso e dimensões; não é cotação de transportadora. O ViaCEP pode auxiliar no preenchimento do endereço.</li>
      <li><strong>Pagamento:</strong> PIX, cartão ou boleto representam uma preferência registrada. Não há cobrança nem confirmação automática por gateway.</li>
      <li><strong>Pedido:</strong> o estoque é atualizado ao registrar a compra, mesmo quando o pagamento segue pendente.</li>
      <li><strong>Entrega:</strong> previsão ilustrativa de sete dias úteis, sem feriados, transportadora ou rastreamento.</li>
      <li><strong>PDF do pedido:</strong> resumo demonstrativo, sem comprovação de pagamento e sem validade fiscal; não é NF-e nem DANFE fiscal.</li>
      <li><strong>Notas fiscais:</strong> a tabela <code>notas_fiscais</code> existe no esquema, mas não equivale à integração de emissão autorizada.</li>
    </ul>
    <h3>Evoluções sugeridas</h3>
    <table><thead><tr><th>Frente</th><th>Próxima evolução</th><th>Dependência / cuidado</th></tr></thead><tbody>
      <tr><td>Pagamentos</td><td>Integrar provedor e webhooks idempotentes.</td><td>Credenciais protegidas e homologação do provedor.</td></tr>
      <tr><td>Entrega</td><td>Conectar cotação e rastreamento de transportadora.</td><td>Contrato, regras de frete e tratamento de falhas externas.</td></tr>
      <tr><td>Fiscal</td><td>Integrar emissor fiscal autorizado.</td><td>Requisitos legais, certificado e ambiente fiscal adequado.</td></tr>
      <tr><td>Produção</td><td>Configuração segura, logs, backups e testes automatizados.</td><td>Segredos fora do código, HTTPS e revisão operacional.</td></tr>
      <tr><td>Documentação</td><td>Produzir manual separado para clientes e equipe usuária.</td><td>Validar passos e imagens com a interface atual.</td></tr>
    </tbody></table>
    <div class="end-note">Fim da análise inicial. As especificações funcionais e operacionais continuam nos capítulos seguintes; o script SQL integral está no apêndice ao final deste volume.</div>
  </section>

  <section class="page" id="casos-de-uso">
    <p class="eyebrow">12 • Especificação funcional</p>
    <h2>Casos de uso detalhados</h2>
    <p>As fichas a seguir registram a intenção do ator, as condições de início, o fluxo esperado e as respostas a situações alternativas. Elas descrevem o comportamento encontrado no código, incluindo as limitações do protótipo.</p>
    <table><thead><tr><th>Identificador</th><th>Caso de uso</th><th>Atores principais</th><th>Requisitos relacionados</th></tr></thead><tbody>
      <tr><td>UC01</td><td>Criar conta de cliente</td><td>Visitante</td><td>RF02</td></tr>
      <tr><td>UC02</td><td>Autenticar e abrir sessão</td><td>Cliente, vendedor, administrador</td><td>RF02</td></tr>
      <tr><td>UC03</td><td>Consultar e pesquisar catálogo</td><td>Visitante, cliente</td><td>RF01</td></tr>
      <tr><td>UC04</td><td>Montar carrinho e estimar frete</td><td>Cliente</td><td>RF03</td></tr>
      <tr><td>UC05</td><td>Finalizar compra</td><td>Cliente</td><td>RF04, RF09</td></tr>
      <tr><td>UC06</td><td>Consultar e cancelar pedido próprio</td><td>Cliente</td><td>RF05</td></tr>
      <tr><td>UC07</td><td>Manter produtos e categorias</td><td>Administrador</td><td>RF06</td></tr>
      <tr><td>UC08</td><td>Registrar entrada de estoque</td><td>Administrador</td><td>RF07</td></tr>
      <tr><td>UC09</td><td>Consultar painel, pedidos e relatórios</td><td>Vendedor, administrador</td><td>RF08</td></tr>
    </tbody></table>
    <div class="callout"><strong>Vocabulário:</strong> “pós-condição” significa o resultado observável quando o caso termina com sucesso. Um fluxo alternativo descreve erros esperados; não afirma que esses cenários foram executados como teste.</div>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC01</p>
    <h2>Criar conta de cliente</h2>
    <div class="use-case-meta">
      <p><strong>Ator principal:</strong> Visitante</p><p><strong>Objetivo:</strong> criar credenciais e cadastro inicial.</p>
      <p><strong>Pré-condições:</strong> visitante não autenticado; aplicação e banco disponíveis.</p><p><strong>Disparo:</strong> seleciona “Criar conta”.</p>
      <p><strong>Resultado de sucesso:</strong> usuário e cadastro de cliente persistidos; sessão iniciada.</p><p><strong>Requisito:</strong> RF02.</p>
    </div>
    <h3 class="use-case">Fluxo principal</h3>
    <ol>
      <li>O sistema exibe formulário com nome, e-mail, senha e confirmação, junto ao token CSRF da sessão.</li>
      <li>O visitante informa os campos e envia o formulário.</li>
      <li><code>ClienteController::criar</code> exige POST válido e compara o token com <code>hash_equals</code>.</li>
      <li>O sistema remove espaços externos, normaliza o e-mail para minúsculas e confere nome, formato/comprimento do e-mail, senha com pelo menos oito caracteres e igualdade da confirmação.</li>
      <li>O controlador verifica se o e-mail já está cadastrado.</li>
      <li><code>ClienteConta::cadastrar</code> abre transação; cria a conta com perfil <code>cliente</code> e senha processada por <code>password_hash</code>; cria o registro associado em <code>clientes</code>.</li>
      <li>Com ambas as gravações bem-sucedidas, confirma a transação, regenera o ID de sessão e direciona o cliente à loja.</li>
    </ol>
    <h3>Fluxos alternativos e exceções</h3>
    <ul class="compact">
      <li><strong>A1 — Dados inválidos:</strong> exibe mensagem, não cria a conta e retorna ao cadastro.</li>
      <li><strong>A2 — E-mail já utilizado:</strong> informa a duplicidade e solicita entrar ou escolher outro e-mail.</li>
      <li><strong>A3 — Corrida de duplicidade:</strong> se outra requisição ocupar o e-mail após a verificação, a restrição única do banco produz conflito; o sistema trata o código de integridade conhecido.</li>
      <li><strong>A4 — Token ou método incorreto:</strong> responde com HTTP 403 e interrompe a operação.</li>
      <li><strong>E1 — Falha de banco:</strong> reverte a transação de usuário/cliente e propaga o erro não tratado como duplicidade.</li>
    </ul>
    <p class="small"><strong>Componentes:</strong> <code>views/conta.php</code>, <code>ClienteController::criar</code> e <code>models/ClienteConta.php</code>. O cadastro público não permite criar conta de vendedor ou administrador.</p>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC02</p>
    <h2>Autenticar e abrir sessão</h2>
    <div class="use-case-meta">
      <p><strong>Atores principais:</strong> Cliente, vendedor e administrador</p><p><strong>Objetivo:</strong> validar a conta e encaminhar ao ambiente apropriado.</p>
      <p><strong>Pré-condição:</strong> conta previamente cadastrada e ativa.</p><p><strong>Disparo:</strong> envio do formulário de entrada.</p>
      <p><strong>Resultado:</strong> sessão de cliente ou de equipe, quando a autenticação é válida.</p><p><strong>Requisito:</strong> RF02.</p>
    </div>
    <h3 class="use-case">Fluxo principal</h3>
    <ol>
      <li>O usuário informa e-mail e senha no formulário compartilhado.</li>
      <li><code>ClienteController::login</code> valida requisição POST e token CSRF; normaliza o e-mail.</li>
      <li><code>ClienteConta::buscarPorEmail</code> procura a conta por consulta parametrizada.</li>
      <li>O controlador exige conta existente, ativa, de perfil reconhecido e senha correspondente ao hash.</li>
      <li>Antes de completar o login, o identificador da sessão é regenerado.</li>
      <li>Conta <code>cliente</code>: limpa variáveis da sessão de equipe, grava o ID do cliente e emite token novo; redireciona à loja.</li>
      <li>Conta <code>admin</code> ou <code>vendedor</code>: limpa carrinho/sessão de cliente, grava ID, perfil e nome de equipe; redireciona ao painel.</li>
    </ol>
    <h3>Alternativas e resultado observável</h3>
    <ul class="compact">
      <li><strong>A1 — E-mail não localizado, conta inativa, perfil inesperado ou senha incorreta:</strong> apresenta a mesma mensagem genérica para não indicar qual credencial falhou.</li>
      <li><strong>A2 — Cliente com outra sessão cliente já ativa:</strong> ao trocar para outro ID, o carrinho e a estimativa de frete anteriores são removidos.</li>
      <li><strong>A3 — Conta da equipe encontrada:</strong> direciona ao painel, não ao catálogo como conta de cliente.</li>
      <li><strong>A4 — Usuário solicita sair:</strong> o fluxo de cliente remove variáveis de sessão e regenera o ID; a saída de equipe destrói a sessão e retorna à entrada.</li>
    </ul>
    <div class="callout risk"><strong>Observação de implementação:</strong> a interface principal envia o login a <code>ClienteController::login</code>. Existe também <code>AuthController::login</code>, uma rota legada com fluxo diferente; a autenticação da equipe deve ser centralizada e as proteções harmonizadas antes de uma implantação pública.</div>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC03</p>
    <h2>Consultar e pesquisar o catálogo</h2>
    <div class="use-case-meta">
      <p><strong>Atores:</strong> Visitante e cliente</p><p><strong>Objetivo:</strong> localizar produtos que a loja disponibiliza.</p>
      <p><strong>Pré-condição:</strong> rota da loja acessível.</p><p><strong>Disparo:</strong> abertura do catálogo ou envio do campo de busca.</p>
      <p><strong>Pós-condição:</strong> lista apresentada; a consulta não altera dados do catálogo.</p><p><strong>Requisito:</strong> RF01.</p>
    </div>
    <h3 class="use-case">Fluxo principal</h3>
    <ol>
      <li>O navegador solicita <code>controller=loja&amp;action=catalogo</code>; sem parâmetros, essa é a rota inicial.</li>
      <li>O controlador pede ao modelo <code>Produto::listarDisponiveis</code> os produtos ativos com estoque positivo e categoria ativa.</li>
      <li>Se a busca <code>q</code> foi preenchida, compara o termo, sem diferenciar maiúsculas/minúsculas, com nome, descrição e nome da categoria.</li>
      <li>A visão apresenta nome, identificador/SKU, descrição, categoria, preço e, quando cadastrada, imagem do produto.</li>
      <li>O usuário pode escolher um produto e solicitar adicioná-lo ao carrinho, fluxo detalhado em UC04.</li>
    </ol>
    <h3>Alternativas</h3>
    <ul class="compact">
      <li><strong>A1 — Resultado vazio:</strong> exibe mensagem de catálogo vazio ou de nenhum resultado e, quando há termo, permite limpar a pesquisa.</li>
      <li><strong>A2 — Produto inativo, categoria inativa ou estoque zero:</strong> não aparece na listagem pública de disponíveis.</li>
      <li><strong>A3 — Falha de conexão:</strong> a consulta não pode completar e a página não consegue carregar os produtos.</li>
    </ul>
    <p class="small"><strong>Nota de implementação:</strong> a filtragem textual do termo ocorre em PHP após carregar a lista disponível; não é uma pesquisa SQL de texto completo.</p>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC04</p>
    <h2>Montar carrinho e estimar frete</h2>
    <div class="use-case-meta">
      <p><strong>Ator:</strong> Cliente autenticado</p><p><strong>Objetivo:</strong> preparar os itens e visualizar um valor de entrega estimado.</p>
      <p><strong>Pré-condição:</strong> sessão cliente válida e produto ativo com estoque.</p><p><strong>Disparo:</strong> adicionar item, mudar quantidade ou enviar CEP.</p>
      <p><strong>Persistência:</strong> carrinho e CEP permanecem na sessão; não criam pedido.</p><p><strong>Requisito:</strong> RF03.</p>
    </div>
    <h3 class="use-case">Fluxo principal</h3>
    <ol>
      <li>O cliente seleciona adicionar produto. A requisição é POST e carrega o token CSRF.</li>
      <li>O controlador exige perfil de cliente, busca o produto e verifica publicação e disponibilidade.</li>
      <li>O item é acrescentado ao carrinho em sessão; a quantidade é limitada ao estoque informado e o navegador segue para o carrinho.</li>
      <li>No carrinho, o sistema reconsulta o catálogo disponível e recalibra quantidades que ultrapassarem o estoque atual.</li>
      <li>O cliente pode enviar nova quantidade; valor zero remove o item. Quantidades positivas continuam limitadas ao estoque.</li>
      <li>Para estimar o frete, o cliente envia CEP; o sistema remove caracteres não numéricos e exige oito dígitos.</li>
      <li>O controlador escolhe a tarifa-base pelo prefixo de dois dígitos do CEP; caso não corresponda a faixa cadastrada, usa R$ 24,90.</li>
      <li>Para cada item calcula peso tarifável como o maior valor entre peso real e volume (altura × largura × comprimento ÷ 6.000), multiplica pela quantidade e soma.</li>
      <li>O valor demonstrativo resulta em base + <code>máximo(0, peso total − 1) × R$ 3,50</code>, arredondado a centavos.</li>
    </ol>
    <div class="callout"><strong>Fórmula de documentação:</strong> <code>peso_total = Σ(max(peso, altura×largura×comprimento/6000) × quantidade)</code>; <code>frete = arredondar(base(CEP) + max(0, peso_total−1)×3,50; 2)</code>. É uma regra local demonstrativa, não uma proposta comercial de transportadora.</div>
    <h3>Alternativas</h3>
    <ul class="compact">
      <li>Perfil ausente ou inválido direciona à tela de autenticação.</li>
      <li>Produto removido/inativo ou indisponível não entra no carrinho; produto que deixa de estar disponível pode ser removido ao recarregar a lista.</li>
      <li>CEP diferente de oito dígitos apresenta mensagem e preserva o carrinho.</li>
      <li>Carrinho vazio não permite estimar frete nem seguir ao checkout.</li>
    </ul>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC05</p>
    <h2>Finalizar compra</h2>
    <div class="use-case-meta">
      <p><strong>Ator:</strong> Cliente autenticado</p><p><strong>Objetivo:</strong> registrar pedido com itens, endereço e pagamento escolhido.</p>
      <p><strong>Pré-condições:</strong> carrinho não vazio, sessão válida e produtos ainda disponíveis.</p><p><strong>Disparo:</strong> confirmação do formulário de checkout.</p>
      <p><strong>Resultado de sucesso:</strong> pedido e itens persistidos; estoque decrementado; carrinho limpo.</p><p><strong>Requisitos:</strong> RF04 e RF09.</p>
    </div>
    <h3 class="use-case">Fluxo principal</h3>
    <ol>
      <li>O controlador atualiza o perfil do cliente e reconstrói os itens atuais do carrinho a partir do catálogo.</li>
      <li>O checkout mostra os itens, a estimativa de frete e o formulário de entrega e forma de pagamento.</li>
      <li>O cliente informa CEP, endereço, número, cidade e UF; telefone/documento podem compor seus dados; escolhe PIX, cartão ou boleto.</li>
      <li>O servidor confere token CSRF, campos obrigatórios, CEP com oito dígitos, UF com duas letras, dados do endereço e forma de pagamento permitida.</li>
      <li>O servidor recalcula o frete a partir do CEP enviado, sem confiar no valor calculado/apresentado no navegador.</li>
      <li><code>Compra::finalizar</code> inicia transação e relê cada produto ativo com <code>FOR UPDATE</code>; rejeita produto indisponível, quantidade inválida, estoque insuficiente ou carrinho vazio.</li>
      <li>Atualiza/cria os dados de entrega do cliente e gera código de acesso aleatório de 48 caracteres hexadecimais.</li>
      <li>Insere pedido em <code>aguardando</code>, pagamento em <code>pendente</code>, totais, endereço e snapshot dos dados do comprador.</li>
      <li>Para cada item grava o nome/preço unitário do momento, decrementa o estoque e registra saída em <code>movimentos_estoque</code>.</li>
      <li>Cria linha de identificação <code>PED-&lt;id&gt;</code> em <code>notas_fiscais</code>; esse registro não emite documento fiscal.</li>
      <li>Se todas as operações completam, confirma a transação; controlador limpa carrinho/frete e redireciona a uma confirmação pelo identificador e token do pedido.</li>
      <li>O PDF opcional é montado por <code>InvoicePdf</code> como resumo explicitamente sem valor fiscal.</li>
    </ol>
    <h3>Fluxos alternativos e limites</h3>
    <ul class="compact">
      <li>Dados, token, CEP ou pagamento inválidos: interrompe sem gravar o pedido.</li>
      <li>Estoque mudou depois da montagem do carrinho: a transação falha e é revertida; o cliente deve revisar o carrinho.</li>
      <li>Falha de banco ao salvar pedido/item/movimentação: rollback para não deixar gravações parciais.</li>
      <li>A opção de pagamento registra a preferência; não integra adquirente, não gera cobrança e não confirma o pagamento.</li>
    </ul>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC06</p>
    <h2>Consultar e cancelar pedido próprio</h2>
    <div class="use-case-meta">
      <p><strong>Ator:</strong> Cliente</p><p><strong>Objetivo:</strong> revisar compras próprias e cancelar pedido elegível.</p>
      <p><strong>Pré-condição:</strong> cliente autenticado.</p><p><strong>Disparos:</strong> abre “Meus pedidos” ou envia “Cancelar”.</p>
      <p><strong>Resultado:</strong> mostra pedidos do cliente; se cancelado, estoque retorna integralmente.</p><p><strong>Requisito:</strong> RF05.</p>
    </div>
    <h3 class="use-case">Fluxo de consulta</h3>
    <ol>
      <li><code>ClienteController::perfilAtual</code> confirma que a conta ainda é de cliente ativa.</li>
      <li><code>Compra::listarDoCliente</code> consulta pedidos filtrados pelo ID do cliente da sessão e agrupa itens.</li>
      <li>A visão apresenta estado, pagamento, totais, endereço, produtos e previsão ilustrativa calculada em sete dias úteis sem feriados.</li>
      <li>Link do resumo PDF utiliza identificador mais código aleatório do pedido; o modelo só o retorna quando ambos correspondem.</li>
    </ol>
    <h3>Fluxo principal de cancelamento</h3>
    <ol>
      <li>Cliente envia pedido e token CSRF em POST.</li>
      <li>O controlador exige cliente válido e ID inteiro positivo; repassa ID do pedido e ID do proprietário ao modelo.</li>
      <li>O modelo inicia transação e bloqueia com <code>FOR UPDATE</code> um pedido que corresponda simultaneamente ao ID e ao cliente.</li>
      <li>Exige status <code>aguardando</code> e pagamento <code>pendente</code>; bloqueia itens e estoque associados.</li>
      <li>Para cada item devolve a quantidade ao produto e grava movimento <code>entrada</code> com observação de cancelamento e usuário responsável.</li>
      <li>Altera estado do pedido/pagamento para <code>cancelado</code>, confirma a transação e mostra mensagem de sucesso.</li>
    </ol>
    <h3>Alternativas</h3>
    <ul class="compact">
      <li>Pedido inexistente, de outra conta, sem itens, pago ou em outro estado: operação não altera estoque; informa impossibilidade.</li>
      <li>Falha durante devolução, registro de movimento ou atualização do pedido: transação reverte todas as alterações.</li>
      <li>ID inválido resulta em HTTP 422; requisição sem CSRF resulta em HTTP 403.</li>
    </ul>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC07</p>
    <h2>Manter produtos e categorias</h2>
    <div class="use-case-meta">
      <p><strong>Ator:</strong> Administrador</p><p><strong>Objetivo:</strong> manter informações que compõem a vitrine.</p>
      <p><strong>Pré-condição:</strong> sessão de equipe válida; perfil admin para alteração.</p><p><strong>Disparo:</strong> cria/edita ou inativa registro.</p>
      <p><strong>Resultado:</strong> catálogo e flag de publicação atualizados; históricos preservados.</p><p><strong>Requisito:</strong> RF06.</p>
    </div>
    <h3>Produto — fluxo principal</h3>
    <ol>
      <li>O administrador abre a área de produtos; listagem mostra produtos, categorias, SKU, preço e quantidade.</li>
      <li>Preenche nome, categoria, marca, preço, estoque inicial, peso e dimensões; descrição e imagem são opcionais.</li>
      <li>O controlador confere campos obrigatórios, preço/peso/dimensões positivos e estoque não negativo.</li>
      <li>Quando há imagem, rejeita erro de envio, tamanho acima de 5 MB ou MIME diferente de JPEG, PNG e WebP; cria nome aleatório antes de mover para <code>uploads/</code>.</li>
      <li>No cadastro, gera SKU <code>FZ-</code> mais identificador numérico do produto, com busca por colisão; em edição mantém o identificador.</li>
      <li>Ao remover, não apaga linha: marca produto inativo, preservando relações com pedidos.</li>
    </ol>
    <h3>Categoria — fluxo principal</h3>
    <ol>
      <li>Administrador cadastra categoria ou seleciona uma existente para editar.</li>
      <li>O nome não pode ser vazio; o modelo deriva um slug utilizado como identificador único.</li>
      <li>Para deixar de publicar categoria, alterna a flag <code>ativo</code>; a categoria não é removida fisicamente.</li>
    </ol>
    <h3>Alternativas e restrições</h3>
    <ul class="compact">
      <li>Vendedor pode consultar algumas listagens, mas operações de gravação de produtos exigem perfil administrador.</li>
      <li>Categoria que viola slug único resulta em conflito de integridade no banco.</li>
      <li>Arquivo fora dos formatos/tamanho não deve ser persistido; preço zero ou estoque negativo são rejeitados.</li>
    </ul>
    <div class="callout risk"><strong>Melhoria técnica a registrar:</strong> o controle CSRF é validado no salvamento/inativação de produtos, mas o controlador de categoria não implementa a mesma validação e sua alternância de publicação é uma ação GET. Padronizar POST com token CSRF é uma evolução necessária antes de uso público.</div>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC08</p>
    <h2>Registrar entrada de estoque</h2>
    <div class="use-case-meta">
      <p><strong>Ator:</strong> Administrador</p><p><strong>Objetivo:</strong> somar uma reposição mantendo seu histórico.</p>
      <p><strong>Pré-condições:</strong> administrador autenticado; produto ativo cadastrado.</p><p><strong>Disparo:</strong> confirma formulário de nova entrada.</p>
      <p><strong>Resultado:</strong> estoque incrementado e movimento gravado, em conjunto.</p><p><strong>Requisito:</strong> RF07.</p>
    </div>
    <h3 class="use-case">Fluxo principal</h3>
    <ol>
      <li>O administrador escolhe um produto ativo, informa quantidade inteira positiva e, opcionalmente, observação até o limite apresentado pelo campo.</li>
      <li><code>EntradaController::store</code> verifica sessão da equipe, perfil administrador, método POST, token CSRF, produto e quantidade.</li>
      <li><code>Entrada::registrar</code> inicia transação e bloqueia a linha do produto ativo por meio de <code>FOR UPDATE</code>.</li>
      <li>Insere um movimento com tipo <code>entrada</code>, quantidade, observação (ou “Reposição de estoque”), responsável e data/hora.</li>
      <li>Incrementa a quantidade atual de estoque e confirma a transação.</li>
      <li>A tela de histórico mostra data, produto, tipo, quantidade, responsável e observação.</li>
    </ol>
    <h3>Fluxos alternativos</h3>
    <ul class="compact">
      <li>Perfil de vendedor, conta ausente ou sessão inválida: bloqueia ou direciona à autenticação.</li>
      <li>Quantidade zero/negativa ou produto inválido: não registra a movimentação.</li>
      <li>Produto inativado antes da gravação: rejeita a entrada durante a consulta bloqueada.</li>
      <li>Falha de banco ao escrever movimento ou atualizar estoque: rollback preserva ambos os valores.</li>
    </ul>
    <p class="small"><strong>Distinção importante:</strong> o cadastro/edição do produto permite preencher quantidade inicial diretamente; o fluxo de entrada é o procedimento que também cria uma linha explícita em <code>movimentos_estoque</code>.</p>
  </section>

  <section class="page">
    <p class="eyebrow">12 • Caso de uso UC09</p>
    <h2>Consultar painel, pedidos e relatórios</h2>
    <div class="use-case-meta">
      <p><strong>Atores:</strong> Vendedor e administrador</p><p><strong>Objetivo:</strong> acompanhar os registros operacionais.</p>
      <p><strong>Pré-condição:</strong> usuário autenticado como equipe.</p><p><strong>Disparo:</strong> abre o painel, pedidos ou relatórios.</p>
      <p><strong>Resultado:</strong> consulta de dados; este fluxo não altera o status de pedido.</p><p><strong>Requisito:</strong> RF08.</p>
    </div>
    <h3>Fluxo do painel</h3>
    <ul class="compact">
      <li>O controlador verifica se <code>usuario_id</code> está presente na sessão.</li>
      <li><code>Compra::resumoDashboard</code> calcula pedidos não cancelados no mês, total de pedidos, somatório de pedidos no estado <code>pago</code> no mês corrente, pedidos aguardando, produtos ativos com estoque ≤ 5 e total de produtos ativos.</li>
      <li>A visão apresenta indicadores e menu ajustado ao perfil. Valores são provenientes do banco; a tela do protótipo na pasta <code>figma/</code> usa exemplos ilustrativos.</li>
    </ul>
    <h3>Fluxo de relatórios</h3>
    <ol>
      <li>Abre a página com início padrão no primeiro dia do mês e fim na data corrente.</li>
      <li>Pode informar intervalo de datas; controlador valida formato ISO de data e exige início ≤ fim.</li>
      <li>O modelo lista pedidos com data entre os limites, organizados do mais recente; a tela calcula contagem e soma dos valores exibidos, apresenta quantidade de produtos ativos e soma de unidades em estoque.</li>
    </ol>
    <h3>Alternativas e distinções</h3>
    <ul class="compact">
      <li>Período inválido resulta em HTTP 422; página do painel/relatório exige sessão de equipe.</li>
      <li>A lista de pedidos de equipe é de consulta. O controlador de vendas disponível implementa listagem, não fluxo de atualização de status.</li>
      <li>O relatório soma todos os pedidos encontrados no intervalo, independentemente do status. O cartão de faturamento do painel considera apenas estado <code>pago</code> no mês.</li>
    </ul>
    <div class="callout"><strong>Interpretação:</strong> contagem e valor do relatório são volume de pedidos registrados, não faturamento líquido comprovado. A demonstração não possui gateway que confirme pagamentos.</div>
  </section>

  <section class="page" id="sequencias">
    <p class="eyebrow">13 • UML comportamental</p>
    <h2>Sequência de autenticação</h2>
    <p>O diagrama representa a entrada compartilhada. A verificação do token CSRF, da situação ativa e do hash precede a criação das variáveis de sessão específicas do perfil.</p>
    <figure class="sequence"><img src="uml-sequencia-login.svg" alt="Diagrama UML de sequência do login FightZone"><figcaption>Figura 8 — Sequência do login da interface principal e encaminhamento por perfil.</figcaption></figure>
    <div class="callout risk"><strong>Escopo:</strong> o diagrama descreve <code>ClienteController::login</code>, acionado pela tela atual. Uma ação de autenticação legada em <code>AuthController</code> existe separadamente e necessita harmonização para que todas as rotas tenham o mesmo fluxo.</div>
  </section>

  <section class="page">
    <p class="eyebrow">13 • UML comportamental</p>
    <h2>Sequência de finalização da compra</h2>
    <p>A fronteira transacional é parte essencial do caso: a compra relê e bloqueia os produtos no banco, valida estoque no momento da gravação e efetua pedido, itens, movimentos e alteração de saldo na mesma transação.</p>
    <figure class="sequence"><img src="uml-sequencia-checkout.svg" alt="Diagrama UML de sequência do checkout transacional"><figcaption>Figura 9 — Revalidação de estoque, snapshots, movimentos e confirmação atômica do pedido.</figcaption></figure>
    <p class="small">Snapshots referem-se ao nome do produto, preço unitário e dados do comprador registrados com o pedido. A reserva/baixa de estoque ocorre antes de qualquer confirmação externa de pagamento.</p>
  </section>

  <section class="page">
    <p class="eyebrow">13 • UML comportamental</p>
    <h2>Sequência de cancelamento</h2>
    <p>O cancelamento é restrito ao pedido do usuário autenticado, sob bloqueio transacional e enquanto pagamento e pedido mantêm seus estados iniciais elegíveis.</p>
    <figure class="sequence"><img src="uml-sequencia-cancelamento.svg" alt="Diagrama UML de sequência do cancelamento de pedido"><figcaption>Figura 10 — Verificação de titularidade, devolução do estoque e reversão em caso de falha.</figcaption></figure>
    <div class="callout"><strong>Idempotência operacional:</strong> depois que o primeiro cancelamento conclui e altera o estado, nova tentativa não passa novamente pela condição de elegibilidade; não devolve estoque pela segunda vez.</div>
  </section>

  <section class="page" id="fluxos-tecnicos">
    <p class="eyebrow">14 • Regras operacionais</p>
    <h2>Transações, estoque e estados</h2>
    <h3>Atomicidade nas operações de estoque</h3>
    <table><thead><tr><th>Operação</th><th>Leitura/bloqueio</th><th>Alterações no mesmo commit</th><th>Resultado se ocorrer falha</th></tr></thead><tbody>
      <tr><td>Nova compra</td><td>Produto ativo e estoque por <code>FOR UPDATE</code>.</td><td>Cliente; pedido; itens/snapshots; subtração de estoque; movimentos de saída; registro interno <code>PED-</code>.</td><td>Rollback da operação de compra.</td></tr>
      <tr><td>Cancelamento</td><td>Pedido do próprio cliente e seus itens bloqueados; exige estados elegíveis.</td><td>Reposição de estoque; movimentos de entrada; estados cancelados.</td><td>Rollback se a devolução ou transição falhar.</td></tr>
      <tr><td>Reposição</td><td>Produto ativo bloqueado.</td><td>Movimento de entrada e incremento de estoque.</td><td>Rollback preserva saldo e histórico anteriores.</td></tr>
    </tbody></table>
    <h3>Vocabulário de estado no banco</h3>
    <table><thead><tr><th>Valor armazenado</th><th>Interpretação</th><th>Evidência de transição no código examinado</th></tr></thead><tbody>
      <tr><td><code>aguardando</code></td><td>Pedido recém-registrado.</td><td>Definido na criação; elegível para cancelamento pelo cliente se pagamento pendente.</td></tr>
      <tr><td><code>pago</code></td><td>Pedido marcado como pago.</td><td>Considerado em métricas do painel; não há gateway que o marque automaticamente.</td></tr>
      <tr><td><code>separacao</code>, <code>enviado</code>, <code>entregue</code></td><td>Vocabulário operacional aceito pelo ENUM.</td><td>Valores previstos no esquema; a interface de vendas examinada somente lista pedidos.</td></tr>
      <tr><td><code>cancelado</code></td><td>Pedido cancelado.</td><td>Condição de sucesso do cancelamento do próprio cliente; pagamento vira cancelado.</td></tr>
    </tbody></table>
    <div class="callout risk"><strong>Limite do fluxo:</strong> enumeração de estados na tabela não significa que exista rotina de mudança disponível na interface. As telas administrativas encontradas não implementam atualização de pagamento ou transições de envio/entrega.</div>
  </section>

  <section class="page">
    <p class="eyebrow">14 • Regras operacionais</p>
    <h2>Regras de negócio e precedência</h2>
    <table><thead><tr><th>ID</th><th>Regra observável</th><th>Camada de aplicação</th><th>Efeito</th></tr></thead><tbody>
      <tr><td>RN01</td><td>Cadastro público cria exclusivamente perfil cliente.</td><td><code>ClienteConta::cadastrar</code></td><td>Evita conceder privilégios de equipe pelo formulário público.</td></tr>
      <tr><td>RN02</td><td>Somente produto ativo, estoque positivo e categoria ativa aparece na loja.</td><td><code>Produto::listarDisponiveis</code></td><td>Filtro comum à vitrine e carrinho atualizado.</td></tr>
      <tr><td>RN03</td><td>Quantidades solicitadas não excedem estoque disponível.</td><td><code>LojaController</code> e <code>Compra</code></td><td>Limite na sessão/UI e revalidação com bloqueio antes de comprar.</td></tr>
      <tr><td>RN04</td><td>CEP de frete e entrega deve conter oito algarismos após normalização.</td><td><code>LojaController</code></td><td>Formatação visual não substitui verificação no servidor.</td></tr>
      <tr><td>RN05</td><td>Totais e frete da compra são calculados novamente no servidor.</td><td><code>LojaController::checkout</code>, <code>Compra</code></td><td>O navegador apenas apresenta estimativa e não define o valor persistido.</td></tr>
      <tr><td>RN06</td><td>Cliente só cancela pedido próprio, aguardando e com pagamento pendente.</td><td><code>Compra::cancelarPedidoCliente</code></td><td>Pedido de outra conta ou já avançado não recebe devolução.</td></tr>
      <tr><td>RN07</td><td>Inativar produto/categoria preserva registro em vez de apagar.</td><td><code>Produto</code>, <code>Categoria</code></td><td>Referências e histórico permanecem no banco.</td></tr>
      <tr><td>RN08</td><td>Preço e nome dos itens, e dados de comprador, podem ser registrados como snapshots.</td><td>Gravação do pedido</td><td>Resumo mantém informações do instante da compra.</td></tr>
      <tr><td>RN09</td><td>Atividade de pagamento e entrega não é confirmada por integração.</td><td>Escopo do produto</td><td>Informações de previsão são demonstrativas; não afirmar pagamento real.</td></tr>
      <tr><td>RN10</td><td>Entrada registrada pelo fluxo próprio exige quantidade positiva.</td><td><code>EntradaController</code> e <code>Entrada</code></td><td>Movimento e incremento são persistidos juntos.</td></tr>
    </tbody></table>
    <h3>Limites e condições de consistência</h3>
    <ul class="compact">
      <li>A tabela <code>itens_pedido</code> mantém FK para produto original e snapshot de nome, mas não define unicidade de produto por pedido; cada linha é um registro individual.</li>
      <li>A tabela <code>notas_fiscais</code> possui número/documento e vínculo único com pedido no esquema; no fluxo de compra é populada com código <code>PED-&lt;id&gt;</code>, sem processo fiscal.</li>
      <li>O carrinho reside em sessão PHP, não numa tabela; trocar de navegador/sessão não garante persistência do carrinho.</li>
      <li>Fotografias de produto ficam em arquivos sob <code>uploads/</code>; MySQL armazena referência textual, não o binário da imagem.</li>
    </ul>
  </section>

  <section class="page" id="mapa-codigo">
    <p class="eyebrow">15 • Arquitetura executável</p>
    <h2>Rotas e responsabilidades no código</h2>
    <p>O front controller <code>index.php</code> lê os parâmetros <code>controller</code>/<code>action</code>, instancia controlador conhecido, verifica se o método existe e o invoca. A rota padrão é loja/catálogo; ação ou controlador desconhecidos terminam com mensagem de erro.</p>
    <table class="route-table"><thead><tr><th>Área</th><th>Rotas / ações representativas</th><th>Controlador</th><th>Modelo / resultado</th></tr></thead><tbody>
      <tr><td>Catálogo</td><td><code>loja/catalogo</code></td><td><code>LojaController</code></td><td><code>Produto</code>; <code>views/loja.php</code>.</td></tr>
      <tr><td>Carrinho</td><td><code>loja/adicionar</code>, <code>carrinho</code>, <code>atualizarCarrinho</code>, <code>frete</code></td><td><code>LojaController</code></td><td>Carrinho e CEP na sessão; produtos relidos pelo modelo.</td></tr>
      <tr><td>Checkout</td><td><code>loja/checkout</code>, <code>confirmacao</code>, <code>notaFiscal</code></td><td><code>LojaController</code></td><td><code>Compra</code>, <code>InvoicePdf</code> e visão loja.</td></tr>
      <tr><td>Conta cliente</td><td><code>cliente/index</code>, <code>cadastro</code>, <code>criar</code>, <code>login</code>, <code>sair</code></td><td><code>ClienteController</code></td><td><code>ClienteConta</code>; visão <code>conta.php</code>.</td></tr>
      <tr><td>Pedidos cliente</td><td><code>cliente/meusPedidos</code>, <code>cancelarPedido</code></td><td><code>ClienteController</code></td><td><code>Compra</code>; visão <code>pedidos_cliente.php</code>.</td></tr>
      <tr><td>Painel</td><td><code>auth/dashboard</code>, <code>auth/logout</code></td><td><code>AuthController</code></td><td><code>Compra::resumoDashboard</code>; visão <code>dashboard.php</code>.</td></tr>
      <tr><td>Produtos</td><td><code>produto/index</code>, <code>salvar</code>, <code>deletar</code></td><td><code>ProdutoController</code></td><td><code>Produto</code>; visão <code>produtos.php</code>.</td></tr>
      <tr><td>Reposição</td><td><code>entrada/index</code>, <code>criar</code>, <code>store</code></td><td><code>EntradaController</code></td><td><code>Entrada</code>; visões <code>views/entradas/</code>.</td></tr>
      <tr><td>Categorias</td><td><code>categoria/index</code>, <code>salvar</code>, <code>toggle</code></td><td><code>CategoriaController</code></td><td><code>Categoria</code>; visão <code>categorias.php</code>.</td></tr>
      <tr><td>Pedidos equipe</td><td><code>venda/index</code></td><td><code>VendaController</code></td><td><code>Venda</code>; lista sem edição de estado.</td></tr>
      <tr><td>Relatórios</td><td><code>relatorio/index</code></td><td><code>RelatorioController</code></td><td><code>Venda</code> e <code>Produto</code>; visão <code>relatorios.php</code>.</td></tr>
    </tbody></table>
    <h3>Estratégia de permissões implementada</h3>
    <table><thead><tr><th>Operação</th><th>Visitante</th><th>Cliente</th><th>Vendedor</th><th>Administrador</th></tr></thead><tbody>
      <tr><td>Consultar catálogo</td><td>Sim</td><td>Sim</td><td>Por rota pública</td><td>Por rota pública</td></tr>
      <tr><td>Carrinho e checkout</td><td>Não</td><td>Sim</td><td>Não</td><td>Não pela sessão de equipe</td></tr>
      <tr><td>Painel, lista de pedidos e relatórios</td><td>Não</td><td>Não</td><td>Sim, com sessão equipe</td><td>Sim</td></tr>
      <tr><td>Listar produtos / movimentos de estoque</td><td>Não</td><td>Não</td><td>Sim em rotas com sessão</td><td>Sim</td></tr>
      <tr><td>Alterar/inativar produtos e registrar entrada</td><td>Não</td><td>Não</td><td>Negado</td><td>Sim</td></tr>
      <tr><td>Administrar categorias</td><td>Não</td><td>Não</td><td>Negado</td><td>Sim</td></tr>
      <tr><td>Consultar/cancelar pedido como cliente</td><td>Não</td><td>Somente os próprios; cancelamento condicionado</td><td>Não</td><td>Não pela sessão de equipe</td></tr>
    </tbody></table>
    <p class="small">Há controladores antigos e rotas com diferentes mecanismos de proteção. A matriz resume a separação de telas e verificações presentes; não significa que todos os endpoints adotem política uniforme.</p>
  </section>

  <section class="page">
    <p class="eyebrow">15 • Arquitetura executável</p>
    <h2>Dependências e divisão de responsabilidades</h2>
    <table><thead><tr><th>Componente</th><th>Responsabilidade</th><th>Decisões/regras encontradas</th></tr></thead><tbody>
      <tr><td><code>index.php</code></td><td>Sessão, conexão inicial e roteamento.</td><td>Lista explícita de controladores; ações devem existir no objeto instanciado.</td></tr>
      <tr><td><code>config/db.php</code></td><td>Fornecer conexão MySQL singleton por PDO.</td><td>Exceções habilitadas; fetch associativo; parâmetros padrão para XAMPP local.</td></tr>
      <tr><td><code>ClienteController</code></td><td>Cadastro, login da interface compartilhada, perfil, cancelamento e sessões.</td><td>Validação de token; sessão cliente distinta da sessão de equipe.</td></tr>
      <tr><td><code>LojaController</code></td><td>Vitrine, busca, carrinho, frete, checkout e arquivos do pedido.</td><td>Recomposição de itens do catálogo; validação final no servidor.</td></tr>
      <tr><td><code>Produto</code></td><td>Consultas CRUD e SKU.</td><td>Uso de prepared statements; remoção lógica; criação transacional com SKU resultante do ID.</td></tr>
      <tr><td><code>Compra</code></td><td>Registro transacional, leitura de pedidos, cancelamento e resumo.</td><td>Lock de linha de produto/pedido com InnoDB; snapshot de itens.</td></tr>
      <tr><td><code>Entrada</code></td><td>Movimentos e reposição.</td><td>Bloqueio da linha de produto antes da atualização.</td></tr>
      <tr><td><code>views/</code></td><td>Renderização de HTML e dos formulários.</td><td>Escape contextual com <code>htmlspecialchars</code> nas visões da loja e painel.</td></tr>
      <tr><td><code>InvoicePdf</code></td><td>Gerar resumo PDF de pedido diretamente em PHP.</td><td>Dados do pedido e itens; indicação explícita de documento demonstrativo sem validade fiscal.</td></tr>
    </tbody></table>
    <h3>Representação simplificada do controle de uma ação</h3>
    <pre class="code-flow">Navegador
  └── GET/POST index.php?controller=...&amp;action=...
        ├── sessão PHP e conexão PDO (index.php / config/db.php)
        ├── controlador valida perfil, método, token e campos
        ├── modelo executa SELECT/INSERT/UPDATE por PDO
        └── visão HTML ou redirecionamento para a próxima rota

Fluxo de checkout:
POST checkout → revalidar perfil + CSRF + endereço
  → reconstruir carrinho e calcular frete no servidor
  → Compra::finalizar (BEGIN / locks / valida estoque / writes)
  → COMMIT → limpar sessão → confirmação com código aleatório</pre>
    <h3>Dados de sessão que afetam a experiência do cliente</h3>
    <table><thead><tr><th>Chave de sessão</th><th>Propósito</th><th>Remoção/renovação</th></tr></thead><tbody>
      <tr><td><code>cliente_usuario_id</code></td><td>Conta usada para validar operações de compra como cliente.</td><td>Removida ao sair, alternar perfil ou invalidar conta.</td></tr>
      <tr><td><code>carrinho</code></td><td>Mapa de ID de produto para quantidade.</td><td>Limpo após pedido, saída cliente ou troca de conta.</td></tr>
      <tr><td><code>cep_frete</code>, <code>frete_estimado</code></td><td>CEP e valor da estimativa atual do carrinho.</td><td>Removidos com limpeza do carrinho ou sessão.</td></tr>
      <tr><td><code>csrf_loja</code>, <code>csrf_cliente</code></td><td>Tokens de formulário gerados por sessão.</td><td>Gerados quando ausentes; o token cliente é renovado ao iniciar a sessão cliente.</td></tr>
      <tr><td><code>usuario_id</code>, <code>perfil</code>, <code>nome</code></td><td>Conta e papel de equipe para o painel.</td><td>Substituídos no login de equipe; sessão destruída ao sair pela rota da equipe.</td></tr>
    </tbody></table>
  </section>

  <section class="page" id="testes">
    <p class="eyebrow">16 • Verificação de qualidade</p>
    <h2>Plano de testes</h2>
    <p>Casos propostos para validação manual do sistema em base de demonstração. Os passos e resultados esperados orientam a apresentação e uma futura bateria automatizada.</p>
    <div class="callout risk"><strong>Registro honesto:</strong> esta documentação não executou esses casos de ponta a ponta. No ambiente disponível durante a elaboração, o MySQL local não estava acessível; portanto, a tabela não marca casos como aprovados nem substitui evidência de testes executados.</div>
    <table><thead><tr><th>ID</th><th>Cenário / preparação</th><th>Resultado esperado</th><th>Tipo</th></tr></thead><tbody>
      <tr><td>CT01</td><td>Abrir catálogo sem termo, com produtos ativos e com estoque.</td><td>Lista apenas itens disponíveis vinculados a categorias ativas.</td><td>Funcional</td></tr>
      <tr><td>CT02</td><td>Buscar por trecho em nome/descrição e alternar caixa alta/baixa.</td><td>Retorna coincidências sem diferenciar caixa; demais não aparecem.</td><td>Funcional</td></tr>
      <tr><td>CT03</td><td>Buscar termo sem resultado.</td><td>Apresenta mensagem vazia e alternativa para ver catálogo completo.</td><td>Interface</td></tr>
      <tr><td>CT04</td><td>Enviar cadastro com nome, e-mail válido e senha coincidente com 8+ caracteres.</td><td>Cria usuário somente como cliente, cria cadastro vinculado e inicia sessão.</td><td>Funcional</td></tr>
      <tr><td>CT05</td><td>Repetir cadastro com e-mail existente ou formato inválido.</td><td>Não duplica conta; apresenta erro; não grava cadastro parcial.</td><td>Negativo</td></tr>
      <tr><td>CT06</td><td>Login cliente ativo e login com senha errada/conta inativa.</td><td>Conta válida retorna à loja; falha mostra mensagem genérica sem iniciar sessão.</td><td>Funcional</td></tr>
      <tr><td>CT07</td><td>Login de administrador/vendedor ativo.</td><td>Abre painel de equipe; não mantém variáveis do carrinho da sessão cliente anterior.</td><td>Autorização</td></tr>
      <tr><td>CT08</td><td>Submeter sem token ou com token alterado formulários protegidos de conta, loja, produto ou estoque.</td><td>Rotas POST que validam token rejeitam com HTTP 403 e não persistem a operação; categoria fica fora deste caso até a proteção ser padronizada.</td><td>Segurança</td></tr>
      <tr><td>CT09</td><td>Adicionar item disponível ao carrinho como cliente autenticado.</td><td>Quantidade inicial um, página do carrinho apresenta produto atual.</td><td>Funcional</td></tr>
      <tr><td>CT10</td><td>Solicitar quantidade superior ao estoque ou atualizar quantidade para zero.</td><td>Quantidade não ultrapassa saldo; zero remove o item.</td><td>Limite</td></tr>
      <tr><td>CT11</td><td>Enviar CEP inválido e CEP de prefixos tarifários diferentes.</td><td>CEP inválido não gera cálculo; prefixos aplicam base configurada.</td><td>Validação</td></tr>
      <tr><td>CT12</td><td>Alterar peso/dimensões e quantidade dos itens antes de recalcular.</td><td>Frete estimado incorpora o maior entre peso real/cubado por unidade.</td><td>Cálculo</td></tr>
    </tbody></table>
    <p class="small">Para cada execução, registrar data, ambiente, responsável, resultado observado e referência a captura sem dados pessoais reais.</p>
  </section>

  <section class="page">
    <p class="eyebrow">16 • Verificação de qualidade</p>
    <h2>Testes de compra, painel e relatórios</h2>
    <table class="test-matrix"><thead><tr><th>ID</th><th>Cenário / preparação</th><th>Resultado esperado</th><th>Tipo</th></tr></thead><tbody>
      <tr><td>CT13</td><td>Finalizar carrinho válido, endereço completo e cada forma de pagamento aceita.</td><td>Pedido aguardando/pendente, snapshots corretos, estoque decrementado, movimento de saída e carrinho limpo.</td><td>Transação</td></tr>
      <tr><td>CT14</td><td>Reduzir estoque no banco depois de adicionar item e antes do POST do checkout.</td><td>Revalidação com bloqueio rejeita estoque insuficiente; nenhuma gravação parcial.</td><td>Concorrência</td></tr>
      <tr><td>CT15</td><td>Submeter checkout sem itens, com endereço inválido ou pagamento fora do conjunto permitido.</td><td>Pedido não criado e total não aceito do navegador.</td><td>Negativo</td></tr>
      <tr><td>CT16</td><td>Alterar nome/preço do produto após compra já registrada.</td><td>Resumo do pedido conserva snapshots de nome e preço unitário do momento da compra.</td><td>Histórico</td></tr>
      <tr><td>CT17</td><td>Listar pedidos como cliente; abrir confirmação/PDF com token válido e repetir com ID/código incorreto.</td><td>Lista contém apenas pedidos próprios; código válido retorna o pedido correspondente; incorreto responde 404.</td><td>Autorização</td></tr>
      <tr><td>CT18</td><td>Cliente cancela pedido próprio aguardando e pendente, com pelo menos um item.</td><td>Estado cancelado, reposição integral do estoque e movimentos de entrada criados uma vez.</td><td>Transação</td></tr>
      <tr><td>CT19</td><td>Tentar cancelar pedido de outro cliente, pago, avançado ou já cancelado.</td><td>Sem mudança de estoque/pedido; mensagem de não elegibilidade.</td><td>Autorização</td></tr>
      <tr><td>CT20</td><td>Adicionar imagem JPEG/PNG/WebP ≤ 5 MB, maior que 5 MB e formato não aceito.</td><td>Arquivos válidos são nomeados com valor aleatório; excesso/formato inválido é rejeitado.</td><td>Validação</td></tr>
      <tr><td>CT21</td><td>Vendedor tenta gravar produto ou registrar reposição; administrador realiza operações.</td><td>Vendedor recebe bloqueio; administrador realiza somente ações autorizadas.</td><td>Permissão</td></tr>
      <tr><td>CT22</td><td>Administrador registra reposição positiva em produto ativo.</td><td>Uma linha de movimento entrada e aumento correspondente, confirmados juntos.</td><td>Transação</td></tr>
      <tr><td>CT23</td><td>Enviar intervalo inválido, datas invertidas e intervalo sem pedidos ao relatório.</td><td>Data inválida retorna 422; intervalo válido apresenta os registros e mensagem vazia quando aplicável.</td><td>Validação</td></tr>
      <tr><td>CT24</td><td>Administrador cadastra, edita e inativa categoria; vendedor tenta acessar a administração.</td><td>Dados válidos persistem e categoria inativa deixa de aparecer na vitrine; acesso da equipe sem permissão é negado.</td><td>Permissão</td></tr>
      <tr><td>CT25</td><td>Preparar pedidos pagos, aguardando e cancelados; montar produtos ativos/inativos e estoque baixo.</td><td>Indicadores do painel seguem seus filtros; relatório mostra todos os pedidos do intervalo, independentemente do estado.</td><td>Consulta</td></tr>
      <tr><td>CT26</td><td>Gerar manual e comparar texto SQL do apêndice com a fonte de banco.</td><td>Bloco SQL no PDF corresponde integralmente ao script usado na instalação.</td><td>Documentação</td></tr>
    </tbody></table>
    <h3>Critérios de aceite propostos</h3>
    <ul class="compact">
      <li>Todos os casos funcionais aplicáveis passam em banco isolado com dados descartáveis.</li>
      <li>Operações com falha preservam a atomicidade de dados de pedidos e estoque.</li>
      <li>Ator cliente não consulta dados privados de outro cliente; usuário de equipe sem permissão não executa função administrativa.</li>
      <li>As limitações de pagamento, transporte e documento fiscal ficam claras nas telas da apresentação.</li>
      <li>Registrar falha ou comportamento divergente antes de aprovar: resultado esperado não conta como resultado observado.</li>
    </ul>
    <div class="callout"><strong>Ferramentas na versão documentada:</strong> o projeto contém PHP, SQL, HTML/CSS e diagramas, sem diretório de testes automatizados ou configuração de CI identificados na estrutura lida. Este plano pode ser convertido posteriormente em testes PHP/integrados após preparar uma base descartável.</div>
  </section>

  <section class="page">
    <p class="eyebrow">16 • Rastreabilidade</p>
    <h2>Matriz de requisitos, casos de uso e testes</h2>
    <p>A matriz liga cada requisito funcional aos casos de uso e aos testes planejados, para orientar revisão e execução. A coluna de teste indica cobertura proposta, não aprovação.</p>
    <table class="trace-table"><thead><tr><th>Requisito</th><th>Comportamento especificado</th><th>Casos de uso</th><th>Testes previstos</th><th>Evidência / ressalva</th></tr></thead><tbody>
      <tr><td>RF01</td><td>Catálogo, disponibilidade e busca.</td><td>UC03</td><td>CT01–CT03</td><td>Listagem e filtro do catálogo.</td></tr>
      <tr><td>RF02</td><td>Cadastro e autenticação por perfil.</td><td>UC01, UC02</td><td>CT04–CT08</td><td>Cadastro/entrada e controles CSRF das rotas cobertas.</td></tr>
      <tr><td>RF03</td><td>Carrinho, quantidades e frete estimado.</td><td>UC04</td><td>CT09–CT12</td><td>Estado do carrinho na sessão e fórmula local de frete.</td></tr>
      <tr><td>RF04</td><td>Validar checkout, criar pedido e atualizar estoque.</td><td>UC05</td><td>CT13–CT16</td><td>Transação, rejeição de estoque e snapshots.</td></tr>
      <tr><td>RF05</td><td>Listar pedidos próprios e cancelamento condicionado.</td><td>UC06</td><td>CT17–CT19</td><td>Propriedade, estado e devolução transacional.</td></tr>
      <tr><td>RF06</td><td>Manter produtos e categorias.</td><td>UC07</td><td>CT20, CT21, CT24</td><td>Imagem, permissão e publicação. Categoria requer padronização CSRF.</td></tr>
      <tr><td>RF07</td><td>Entrada de estoque e histórico de movimentos.</td><td>UC08; UC05; UC06</td><td>CT13, CT18–CT19, CT21–CT22</td><td>Entrada, saída e devolução gravadas com o saldo correspondente.</td></tr>
      <tr><td>RF08</td><td>Consultar pedidos, indicadores e relatórios.</td><td>UC09</td><td>CT23, CT25</td><td>Painel usa filtros próprios; relatório lista estados diversos.</td></tr>
      <tr><td>RF09</td><td>Gerar resumo demonstrativo do pedido em PDF.</td><td>UC05; UC06</td><td>CT17</td><td>Arquivo protegido por identificador e código; não é documento fiscal.</td></tr>
    </tbody></table>
    <div class="callout"><strong>Critério de fechamento:</strong> registrar resultado, data, responsável, ambiente e evidência por ID. Marcar um requisito como validado somente depois da execução dos testes aplicáveis e da resolução das divergências observadas.</div>
  </section>

  <section class="page" id="glossario">
    <p class="eyebrow">17 • Consulta</p>
    <h2>Glossário técnico</h2>
    <table><thead><tr><th>Termo</th><th>Definição no contexto FightZone</th></tr></thead><tbody>
      <tr><td>Ator</td><td>Papel externo que interage com as funcionalidades (visitante, cliente, vendedor ou administrador).</td></tr>
      <tr><td>CSRF</td><td>Falsificação de requisição entre sites; token de sessão pode vincular um formulário a uma sessão esperada.</td></tr>
      <tr><td>DER</td><td>Diagrama entidade-relacionamento que representa entidades/tabelas, chaves e vínculos.</td></tr>
      <tr><td>ENUM</td><td>Tipo de coluna MySQL limitado a valores enumerados, usado para perfis, estados e opções de pagamento.</td></tr>
      <tr><td>FK</td><td>Chave estrangeira que referencia uma linha de outra tabela e reforça integridade relacional.</td></tr>
      <tr><td>InnoDB</td><td>Motor MySQL usado no script, com transações e bloqueios de linha.</td></tr>
      <tr><td>MVC</td><td>Organização em modelo (persistência), visão (interface) e controlador (fluxo da requisição).</td></tr>
      <tr><td>PDO</td><td>Abstração PHP para acesso a banco de dados; usada nos modelos do projeto.</td></tr>
      <tr><td>PK</td><td>Chave primária, identificador único de cada linha da tabela.</td></tr>
      <tr><td>RF / RNF</td><td>Requisito funcional / requisito não funcional: o que o sistema faz / condição de qualidade ou operação.</td></tr>
      <tr><td>Snapshot</td><td>Cópia de informação comercial junto ao pedido, por exemplo nome e preço, para preservar o estado histórico.</td></tr>
      <tr><td>SKU / slug</td><td>Identificador textual único no produto; no cadastro é atribuído conforme o padrão FightZone.</td></tr>
      <tr><td>Transação</td><td>Conjunto de alterações no banco que são confirmadas juntas ou revertidas em caso de erro.</td></tr>
      <tr><td>UC</td><td>Caso de uso; descrição do objetivo do ator e das respostas do sistema.</td></tr>
      <tr><td>UML</td><td>Linguagem de modelagem utilizada nos diagramas de casos de uso, atividades, classes e sequência.</td></tr>
      <tr><td>5W2H</td><td>Estrutura de planejamento formada por What, Why, Who, Where, When, How e How much.</td></tr>
    </tbody></table>
  </section>

  <section class="page">
    <p class="eyebrow">17 • Consulta e evidências</p>
    <h2>Referências e material visual</h2>
    <h3>Referências técnicas</h3>
    <ul>
      <li>FightZone. <em>README do projeto.</em> Procedimentos de execução local, catálogo, painel e limitações: <code>README.md</code>.</li>
      <li>FightZone. <em>Script de banco de dados.</em> Criação e carga do banco: <code>database/fightzone.sql</code>.</li>
      <li>PHP Documentation. <em>PDO</em>: <code>php.net/manual/pt_BR/book.pdo.php</code>.</li>
      <li>PHP Documentation. <em>password_hash</em> e <em>password_verify</em>: <code>php.net/manual/pt_BR/function.password-hash.php</code> e <code>php.net/manual/pt_BR/function.password-verify.php</code>.</li>
      <li>MySQL Reference Manual. <em>InnoDB transaction model</em>: <code>dev.mysql.com/doc/refman/8.0/en/innodb-transaction-model.html</code>.</li>
      <li>Object Management Group. <em>OMG Unified Modeling Language (OMG UML), versão 2.5.1</em>: <code>omg.org/spec/UML/2.5.1</code>.</li>
    </ul>
    <h3>Artefatos visuais produzidos</h3>
    <table><thead><tr><th>Arquivo</th><th>Objetivo</th><th>Natureza</th></tr></thead><tbody>
      <tr><td><code>docs/uml-casos-de-uso.svg</code></td><td>Atores e funções principais.</td><td>Modelo UML de análise.</td></tr>
      <tr><td><code>docs/uml-classes.svg</code></td><td>Responsabilidades de domínio.</td><td>Visão conceitual simplificada.</td></tr>
      <tr><td><code>docs/uml-atividade-compra.svg</code></td><td>Fluxo e decisões do checkout.</td><td>Modelo UML comportamental.</td></tr>
      <tr><td><code>docs/uml-der.svg</code></td><td>Tabelas e relações do esquema.</td><td>Diagrama de dados.</td></tr>
      <tr><td><code>docs/uml-sequencia-login.svg</code></td><td>Comunicação das camadas na autenticação principal.</td><td>Modelo UML de sequência.</td></tr>
      <tr><td><code>docs/uml-sequencia-checkout.svg</code></td><td>Leitura bloqueada e gravação atômica do pedido.</td><td>Modelo UML de sequência.</td></tr>
      <tr><td><code>docs/uml-sequencia-cancelamento.svg</code></td><td>Validação de titularidade e reposição do estoque.</td><td>Modelo UML de sequência.</td></tr>
      <tr><td><code>figma/*.svg</code></td><td>Catálogo, carrinho, checkout e painel.</td><td>Protótipos visuais editáveis; não são capturas de uma sessão real nem arquivo Figma nativo.</td></tr>
    </tbody></table>
    <div class="callout risk"><strong>Imagens da aplicação em execução:</strong> os quadros da pasta <code>figma/</code> são protótipos criados para este material e usam valores ilustrativos. Não os identificar como captura de tela ou dado real. Para anexar capturas reais, iniciar a aplicação com um banco de demonstração, remover/mascarar dados pessoais e registrar data, tela e cenário utilizado.</div>
  </section>

  <section class="page" id="sql">
    <p class="eyebrow">Apêndice A • Banco de dados</p>
    <h2>Script SQL integral</h2>
    <p class="sql-intro">Transcrição gerada diretamente de <code>database/fightzone.sql</code> para manter o apêndice sincronizado com o arquivo de instalação do projeto. Inclui criação da base, tabelas, alterações compatíveis, conta de demonstração e carga inicial de categorias/produtos.</p>
    <pre class="sql"><?= $e($sqlScript) ?></pre>
    <div class="end-note">FightZone • Manual técnico • Equipe: Diego Filipe e Eduardo Cavalcante.</div>
  </section>
</body>
</html>
