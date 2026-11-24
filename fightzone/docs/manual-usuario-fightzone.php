<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light">
  <title>Manual do usuário | FightZone</title>
  <style>
    :root {
      --ink: #171816;
      --paper: #fffefa;
      --muted: #66675f;
      --line: #dedbd4;
      --orange: #d8432e;
      --orange-dark: #a92d20;
      --soft: #f8e9e4;
      font-family: "Segoe UI", Arial, sans-serif;
      color: var(--ink);
      background: var(--paper);
      font-size: 10pt;
      line-height: 1.52;
    }
    * { box-sizing: border-box; }
    body { margin: 0; background: var(--paper); }
    @page {
      size: A4;
      margin: 16mm 17mm 19mm;
      @bottom-left {
        content: "FIGHTZONE  •  MANUAL DO USUÁRIO";
        color: #777870;
        font: 8pt "Segoe UI", Arial, sans-serif;
      }
      @bottom-right {
        content: counter(page);
        color: #777870;
        font: 8pt "Segoe UI", Arial, sans-serif;
      }
    }
    .cover {
      min-height: 260mm;
      display: flex;
      flex-direction: column;
      padding: 25mm 21mm 19mm;
      overflow: hidden;
      position: relative;
      color: var(--paper);
      background: linear-gradient(145deg,#1d1e1b 0%,#171816 58%,#10110f 100%);
      page-break-after: always;
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
    .cover-rule { width:76px; height:5px; background:#e45a42; margin-top:42mm; }
    .cover-kicker { color:#f0a08f; font-size:9pt; font-weight:800; letter-spacing:.2em; text-transform:uppercase; margin:9mm 0 4mm; }
    .cover h1 { max-width:160mm; margin:0; color:#fffefa; font-size:36pt; line-height:1.04; letter-spacing:-.04em; }
    .cover h1 span { color:#e45a42; }
    .cover-intro { max-width:142mm; color:#c1c0b8; font-size:13pt; margin-top:7mm; }
    .cover-meta { margin-top:auto; padding-top:9mm; border-top:1px solid #393832; position:relative; z-index:1; }
    .cover-meta strong { color:#fffefa; }
    .cover-meta .label { display:block; color:#a6a59e; font-size:8pt; font-weight:800; letter-spacing:.16em; text-transform:uppercase; margin-bottom:2mm; }
    .team { display:flex; gap:18mm; margin:5mm 0 8mm; font-size:11pt; font-weight:700; }
    .cover-note { color:#aaa9a1; font-size:8pt; }
    .page { page-break-before:always; break-before:page; }
    .page.first-content { page-break-before:auto; break-before:auto; }
    .eyebrow { color:var(--orange-dark); font-size:8pt; font-weight:800; letter-spacing:.18em; text-transform:uppercase; margin:0 0 2mm; }
    h1,h2,h3 { color:var(--ink); line-height:1.2; }
    h2 { font-size:21pt; margin:0 0 5mm; letter-spacing:-.025em; }
    h3 { font-size:12pt; margin:5mm 0 1.5mm; }
    h4 { font-size:10pt; margin:3mm 0 1mm; }
    p { margin:0 0 3mm; }
    .lead { font-size:11.5pt; color:#44453f; }
    .callout { border-left:4px solid var(--orange); background:#f7f5f0; padding:3.5mm 4mm; margin:3mm 0; }
    .callout strong { color:var(--orange-dark); }
    .warning { border-left-color:#a92d20; background:#f8e9e4; }
    .grid { display:grid; grid-template-columns:1fr 1fr; gap:3mm; margin:3mm 0; }
    .card { border:1px solid var(--line); background:var(--paper); border-radius:3mm; padding:3.5mm; break-inside:avoid; }
    .card h3 { margin:0 0 1.5mm; font-size:10.5pt; }
    .card p:last-child { margin-bottom:0; }
    ul,ol { margin:1.5mm 0 3mm; padding-left:6mm; }
    li { margin:1mm 0; }
    .compact li { margin:.6mm 0; }
    .toc { list-style:none; padding:0; margin:4mm 0 0; }
    .toc li { border-bottom:1px solid var(--line); padding:2mm 0; display:flex; gap:4mm; }
    .toc b { color:var(--orange); min-width:8mm; }
    .toc a { color:var(--ink); text-decoration:none; font-weight:650; }
    table { width:100%; border-collapse:collapse; margin:2.5mm 0 4mm; font-size:8.2pt; }
    th { color:#fffefa; background:#292a26; text-align:left; font-weight:700; }
    th,td { border:1px solid var(--line); padding:1.8mm 2mm; vertical-align:top; }
    tbody tr:nth-child(even) { background:#f7f5f0; }
    code { font-family:Consolas, "Courier New", monospace; font-size:.92em; }
    figure { margin:3mm 0 4mm; break-inside:avoid; }
    figure img { width:100%; max-height:155mm; object-fit:contain; display:block; }
    .screen img { max-height:125mm; border:1px solid var(--line); }
    figcaption { color:var(--muted); font-size:7.7pt; margin-top:1.5mm; text-align:center; }
    .steps { counter-reset:manual-step; list-style:none; padding-left:0; }
    .steps li { counter-increment:manual-step; position:relative; padding-left:10mm; margin:2.5mm 0; }
    .steps li::before {
      content:counter(manual-step, decimal-leading-zero);
      position:absolute; left:0; top:0; width:7mm; height:7mm;
      display:grid; place-items:center; border-radius:50%;
      background:var(--soft); color:var(--orange-dark); font-size:7.5pt; font-weight:800;
    }
    .small { color:var(--muted); font-size:8pt; }
    .end-note { margin-top:7mm; padding-top:3mm; border-top:2px solid var(--orange); color:var(--muted); font-size:8pt; }
    .status { white-space:nowrap; font-weight:700; color:var(--orange-dark); }
    .two-col { columns:2; column-gap:9mm; }
    .two-col li { break-inside:avoid; }
    .next { margin-top:5mm; padding-top:3mm; border-top:1px solid var(--line); color:var(--muted); font-size:8pt; }
    @media screen {
      body { background:#e5e3dd; padding:18px; }
      .cover,.page { width:210mm; min-height:297mm; margin:0 auto 18px; padding:16mm 17mm 19mm; background:var(--paper); box-shadow:0 8px 26px #0002; }
      .cover { padding:27mm 24mm 22mm; background:linear-gradient(145deg,#1d1e1b 0%,#171816 58%,#10110f 100%); }
      .page.first-content { padding-top:16mm; }
    }
  </style>
</head>
<body>
  <section class="cover">
    <div class="brand"><span class="mark">FZ</span><span class="brand-name">FIGHT<span>ZONE</span></span></div>
    <div class="cover-rule"></div>
    <p class="cover-kicker">Loja virtual de artigos de boxe</p>
    <h1>Manual do usuário <span>FightZone</span></h1>
    <p class="cover-intro">Guia prático para navegar pela loja, criar sua conta, montar pedidos e consultar as ferramentas disponíveis para a equipe.</p>
    <div class="cover-meta">
      <span class="label">Equipe desenvolvedora</span>
      <div class="team"><span>Diego Filipe</span><span>Eduardo Cavalcante</span></div>
      <p><strong>Versão:</strong> 1.0 &nbsp; • &nbsp; <strong>Edição:</strong> Novembro de 2026</p>
      <p class="cover-note">Material acadêmico. Telas ilustradas são protótipos, não capturas de uma sessão real.</p>
    </div>
  </section>

  <section class="page first-content" id="sumario">
    <p class="eyebrow">Comece por aqui</p>
    <h2>Como usar este manual</h2>
    <p class="lead">Este guia acompanha as funções existentes na FightZone e explica os caminhos para clientes e para usuários autorizados da equipe.</p>
    <p>As telas variam um pouco conforme o tamanho do navegador e o perfil conectado. Os quadros visuais ajudam a reconhecer as áreas, mas são protótipos demonstrativos: nomes, produtos e valores podem diferir do banco de demonstração.</p>
    <div class="callout warning"><strong>Ambiente acadêmico:</strong> a FightZone registra pedidos para demonstração; não processa pagamentos nem contrata entrega. Não informe número de cartão, senha bancária ou dados pessoais reais. A escolha de pagamento é apenas registrada no pedido.</div>
    <ol class="toc">
      <li><b>01</b><a href="#acesso">Acessar a loja e entender os perfis</a></li>
      <li><b>02</b><a href="#conta">Criar uma conta de cliente</a></li>
      <li><b>03</b><a href="#entrar">Entrar, Minha conta e sair</a></li>
      <li><b>04</b><a href="#catalogo">Navegar e pesquisar produtos</a></li>
      <li><b>05</b><a href="#carrinho">Adicionar e revisar o carrinho</a></li>
      <li><b>06</b><a href="#frete">Calcular a estimativa de frete</a></li>
      <li><b>07</b><a href="#checkout">Informar endereço e revisar o pedido</a></li>
      <li><b>08</b><a href="#pagamento">Escolher pagamento e finalizar</a></li>
      <li><b>09</b><a href="#confirmacao">Confirmação e resumo em PDF</a></li>
      <li><b>10</b><a href="#pedidos">Consultar pedidos e estados</a></li>
      <li><b>11</b><a href="#cancelar">Cancelar um pedido elegível</a></li>
      <li><b>12</b><a href="#equipe">Acesso da equipe e painel</a></li>
      <li><b>13</b><a href="#operacao">Pedidos e relatórios da equipe</a></li>
      <li><b>14</b><a href="#produtos">Produtos: cadastrar e editar</a></li>
      <li><b>15</b><a href="#categorias">Categorias da loja</a></li>
      <li><b>16</b><a href="#estoque">Registrar e consultar estoque</a></li>
      <li><b>17</b><a href="#duvidas">Dúvidas frequentes e limites</a></li>
    </ol>
  </section>

  <section class="page" id="acesso">
    <p class="eyebrow">01 • Acesso</p>
    <h2>Acessar a loja</h2>
    <p>Abra o endereço da FightZone fornecido por quem administra a demonstração. No ambiente XAMPP da escola, o endereço local costuma ser <code>http://localhost/fightzone/</code>; ele só funciona no computador onde a aplicação está instalada e com Apache e MySQL iniciados.</p>
    <figure><img src="fluxo-cliente.svg" alt="Passos do cliente, desde criar conta até consultar o pedido"><figcaption>Figura 1 — Caminho resumido de uma compra como cliente. Os pedidos exigem uma conta conectada.</figcaption></figure>
    <h3>Perfis disponíveis</h3>
    <table><thead><tr><th>Perfil</th><th>O que pode fazer</th><th>Como recebe acesso</th></tr></thead><tbody>
      <tr><td>Visitante</td><td>Consultar e pesquisar produtos disponíveis.</td><td>Acesso público à loja.</td></tr>
      <tr><td>Cliente</td><td>Montar carrinho, enviar pedido, ver seus próprios pedidos e solicitar cancelamento elegível.</td><td>Cria conta pela opção “Criar conta”.</td></tr>
      <tr><td>Vendedor</td><td>Ver painel, lista de pedidos e relatórios.</td><td>Conta de equipe fornecida pelo responsável da demonstração.</td></tr>
      <tr><td>Administrador</td><td>Funções de equipe e manutenção de produtos, categorias e entradas de estoque.</td><td>Conta criada pelo responsável; não existe cadastro público de equipe.</td></tr>
    </tbody></table>
    <div class="callout"><strong>Separação de áreas:</strong> o cliente usa a loja e “Meus pedidos”. Vendedor e administrador entram pela mesma tela de login, mas são encaminhados ao painel de gestão conforme o perfil.</div>
  </section>

  <section class="page" id="conta">
    <p class="eyebrow">02 • Conta do cliente</p>
    <h2>Criar uma conta</h2>
    <p>O cadastro é gratuito e permite usar carrinho, checkout e histórico de pedidos. O formulário público cria somente uma conta de cliente.</p>
    <ol class="steps">
      <li>Na loja, selecione <strong>Entrar</strong> no menu.</li>
      <li>Na tela de acesso, clique em <strong>Criar conta</strong>.</li>
      <li>Preencha nome completo, e-mail válido e uma senha com pelo menos oito caracteres.</li>
      <li>Digite a senha novamente no campo de confirmação e selecione <strong>Criar conta</strong>.</li>
      <li>Quando o cadastro for aceito, a conta é iniciada e você retorna à loja.</li>
    </ol>
    <h3>Se o cadastro não for aceito</h3>
    <ul class="compact">
      <li>Confira se o e-mail está no formato correto e se as duas senhas são idênticas.</li>
      <li>Use no mínimo oito caracteres para a senha.</li>
      <li>Se o e-mail já possui cadastro, volte à tela de entrada e tente acessar essa conta.</li>
      <li>Se a loja informar que não consegue conectar, peça ao responsável para verificar o serviço e o banco de demonstração.</li>
    </ul>
    <div class="callout warning"><strong>Esqueceu a senha?</strong> A tela não oferece recuperação automática. Procure o responsável pela instalação; não compartilhe a senha com outras pessoas.</div>
    <p class="small">Para uma apresentação acadêmica, use endereço de e-mail e dados fictícios ou autorizados pela escola.</p>
  </section>

  <section class="page" id="entrar">
    <p class="eyebrow">03 • Conta do cliente</p>
    <h2>Entrar, Minha conta e sair</h2>
    <h3>Entrar na loja</h3>
    <ol class="steps">
      <li>Selecione <strong>Entrar</strong>.</li>
      <li>Informe o e-mail e a senha usados no cadastro.</li>
      <li>Selecione <strong>Entrar</strong>. Conta de cliente abre a loja; conta autorizada da equipe abre o painel.</li>
    </ol>
    <p>Quando há erro de autenticação, o aviso é “E-mail ou senha inválidos”. Confira o endereço e tente novamente. Contas inativas não podem entrar.</p>
    <h3>Minha conta</h3>
    <p>Com cliente conectado, o menu apresenta <strong>Minha conta</strong>. Essa tela identifica a conta ativa e oferece atalhos para <strong>Meus pedidos</strong>, continuar comprando e sair. Ela não tem formulário para editar o cadastro nem redefinir a senha.</p>
    <h3>Sair da conta</h3>
    <p>Use <strong>Sair da conta</strong> em Minha conta ou o link de saída do cabeçalho de Meus pedidos. Ao sair, o carrinho e a estimativa de frete guardados naquela sessão são removidos; pedidos já enviados permanecem no histórico da conta.</p>
    <div class="callout"><strong>Privacidade em computador compartilhado:</strong> encerre a sessão antes de fechar o navegador e não salve credenciais em computadores públicos.</div>
  </section>

  <section class="page" id="catalogo">
    <p class="eyebrow">04 • Loja</p>
    <h2>Navegar e pesquisar produtos</h2>
    <figure class="screen"><img src="../figma/01-loja.svg" alt="Protótipo ilustrativo do catálogo da FightZone"><figcaption>Figura 2 — Referência visual do catálogo. É um protótipo com itens e preços fictícios, não uma captura do site em execução.</figcaption></figure>
    <ol class="steps">
      <li>Na página inicial, percorra a seção de equipamentos disponíveis.</li>
      <li>Leia o nome, a descrição, a categoria, o preço e, quando cadastrada, a imagem do item.</li>
      <li>Para localizar um item, use o campo <strong>Buscar equipamento</strong> e digite uma palavra do nome, da descrição ou da categoria.</li>
      <li>Pressione o botão de busca. Se a busca não encontrar itens, escolha <strong>Ver todos os produtos</strong> para retornar à vitrine.</li>
      <li>Quando encontrar um item, escolha <strong>Adicionar</strong>. É necessário estar conectado como cliente; caso contrário, a loja pede para entrar.</li>
    </ol>
    <div class="callout"><strong>Disponibilidade:</strong> a vitrine exibe produtos ativos, com estoque positivo e categoria ativa. Se o produto não aparecer, pode estar esgotado, inativo ou não corresponder ao termo pesquisado.</div>
    <p class="small">A tela atual não oferece filtros independentes por faixa de preço, marca ou categoria. A busca textual verifica nome, descrição e categoria.</p>
  </section>

  <section class="page" id="carrinho">
    <p class="eyebrow">05 • Compra</p>
    <h2>Adicionar e revisar o carrinho</h2>
    <figure class="screen"><img src="../figma/02-carrinho.svg" alt="Protótipo ilustrativo da tela de carrinho"><figcaption>Figura 3 — O protótipo ilustra quantidades, subtotal e frete estimado; os dados são exemplos.</figcaption></figure>
    <ol class="steps">
      <li>Conecte-se como cliente e escolha <strong>Adicionar</strong> em um produto da loja.</li>
      <li>O sistema inclui uma unidade e abre o carrinho.</li>
      <li>Confira nome, preço unitário, quantidade e total de cada linha.</li>
      <li>Para alterar uma quantidade, digite o novo número e escolha <strong>Atualizar carrinho</strong>.</li>
      <li>Para remover um item, informe quantidade <strong>0</strong> e atualize.</li>
      <li>Use <strong>Continuar comprando</strong> para voltar ao catálogo. Os itens ficam na sessão atual do navegador enquanto você permanece conectado.</li>
    </ol>
    <div class="callout"><strong>Limite de estoque:</strong> a quantidade não pode superar o saldo disponível. Se o estoque mudar antes de finalizar, o carrinho ou checkout pode pedir que você revise os itens.</div>
    <p>O subtotal representa apenas os produtos. O frete aparece à parte depois do cálculo pelo CEP.</p>
  </section>

  <section class="page" id="frete">
    <p class="eyebrow">06 • Compra</p>
    <h2>Calcular a estimativa de frete</h2>
    <ol class="steps">
      <li>No resumo do carrinho, localize <strong>Calcular estimativa de frete</strong>.</li>
      <li>Digite o CEP de entrega com oito números; pode usar hífen ou não.</li>
      <li>Selecione <strong>Calcular</strong>.</li>
      <li>Confira o valor de frete estimado e o total estimado no resumo. O botão para continuar à entrega fica disponível após o cálculo.</li>
    </ol>
    <h3>O que o cálculo representa</h3>
    <ul class="compact">
      <li>É uma estimativa interna baseada em faixas do CEP e peso/dimensões cadastrados para os produtos.</li>
      <li>Não consulta transportadora, não gera etiqueta e não confirma prazo ou disponibilidade de entrega.</li>
      <li>O checkout recalcula o valor no servidor a partir do CEP informado na entrega.</li>
    </ul>
    <div class="callout warning"><strong>Não é cotação oficial:</strong> considere o frete exibido como valor demonstrativo. O projeto não conecta a transportadora nem rastreia encomendas.</div>
    <h3>CEP não aceito</h3>
    <p>Confira se o CEP contém oito dígitos. Se o carrinho estiver vazio, volte à loja e adicione um produto disponível.</p>
  </section>

  <section class="page" id="checkout">
    <p class="eyebrow">07 • Compra</p>
    <h2>Informar os dados de entrega</h2>
    <figure class="screen"><img src="../figma/04-checkout.svg" alt="Protótipo ilustrativo da tela de entrega e pagamento"><figcaption>Figura 4 — Protótipo do checkout. Alguns campos e o resumo podem variar conforme a conta e a configuração local.</figcaption></figure>
    <ol class="steps">
      <li>No carrinho, depois de calcular o frete, selecione <strong>Continuar para entrega</strong>.</li>
      <li>Confira nome e e-mail, preenchidos a partir da conta e não editáveis nessa página.</li>
      <li>Se necessário, informe telefone e documento; são opcionais no formulário.</li>
      <li>Preencha CEP, rua/avenida, número, cidade e UF. Complemento é opcional.</li>
      <li>Ao inserir CEP válido, o navegador pode tentar preencher rua, cidade e UF pelo ViaCEP. Confira todos os dados; se a consulta não funcionar, preencha os campos manualmente.</li>
      <li>Confira itens, subtotal, frete estimado e total antes de escolher o pagamento.</li>
    </ol>
    <div class="callout warning"><strong>Dados pessoais:</strong> use informações fictícias ou autorizadas no ambiente escolar. O cadastro de telefone/documento não é necessário para demonstrar a navegação básica.</div>
  </section>

  <section class="page" id="pagamento">
    <p class="eyebrow">08 • Compra</p>
    <h2>Escolher pagamento e finalizar</h2>
    <p>O checkout oferece as opções PIX, boleto e cartão. Selecione uma delas e escolha <strong>Finalizar pedido</strong> para registrar a compra.</p>
    <table><thead><tr><th>Opção escolhida</th><th>O que acontece na FightZone</th><th>O que não acontece</th></tr></thead><tbody>
      <tr><td>PIX</td><td>O pedido guarda PIX como forma escolhida. Se uma chave foi configurada para a demonstração, ela pode aparecer na tela de confirmação.</td><td>Não cria cobrança, não verifica transferência e não confirma pagamento.</td></tr>
      <tr><td>Boleto</td><td>A preferência fica associada ao pedido.</td><td>Não emite boleto bancário.</td></tr>
      <tr><td>Cartão</td><td>A preferência fica associada ao pedido.</td><td>Não pede dados do cartão, não autoriza compra e não cobra.</td></tr>
    </tbody></table>
    <ol class="steps">
      <li>Selecione uma forma de pagamento.</li>
      <li>Revise endereço e valores mais uma vez.</li>
      <li>Pressione <strong>Finalizar pedido</strong> uma única vez e aguarde a tela de confirmação.</li>
      <li>Se o sistema informar que o estoque mudou, volte ao carrinho, ajuste os itens e tente novamente.</li>
    </ol>
    <div class="callout warning"><strong>Atenção:</strong> nenhum dado de cartão deve ser digitado. O sistema registra o pedido e reduz o estoque mesmo com pagamento pendente; isso não significa que houve pagamento.</div>
  </section>

  <section class="page" id="confirmacao">
    <p class="eyebrow">09 • Após o pedido</p>
    <h2>Confirmação e resumo em PDF</h2>
    <p>Depois de um envio aceito, a tela informa o número do pedido e o total registrado. O status inicial é <strong>aguardando confirmação do pagamento</strong>.</p>
    <ol class="steps">
      <li>Confira o número e o valor total do pedido na confirmação.</li>
      <li>Quando disponível, use <strong>Baixar comprovante em PDF</strong> para salvar o resumo da compra.</li>
      <li>Também é possível consultar o pedido em <strong>Meus pedidos</strong>.</li>
    </ol>
    <div class="callout warning"><strong>Sobre o PDF:</strong> é um resumo demonstrativo do pedido. Não é nota fiscal eletrônica, DANFE autorizado, comprovante bancário nem confirmação de pagamento.</div>
    <h3>Se escolheu PIX</h3>
    <p>A tela pode mostrar a chave local configurada pelo responsável. Antes de qualquer transferência, confira cuidadosamente titular e valor no aplicativo do banco. Para a apresentação acadêmica, não é necessário realizar pagamento real.</p>
    <p class="small">Se a chave não estiver configurada, a tela informa que ela não está disponível. O responsável pelo ambiente é quem configura esse dado localmente.</p>
  </section>

  <section class="page" id="pedidos">
    <p class="eyebrow">10 • Após o pedido</p>
    <h2>Consultar “Meus pedidos”</h2>
    <ol class="steps">
      <li>Entre na conta usada para registrar a compra.</li>
      <li>No cabeçalho da loja, selecione <strong>Meus pedidos</strong>.</li>
      <li>Abra o cartão do pedido para conferir data, itens, quantidades, endereço, CEP, subtotal, frete, total, forma de pagamento e situação registrada.</li>
      <li>Use <strong>Baixar resumo do pedido</strong> para obter o PDF demonstrativo.</li>
    </ol>
    <table><thead><tr><th>Estado mostrado</th><th>Significado na interface</th><th>Observação</th></tr></thead><tbody>
      <tr><td class="status">Aguardando pagamento</td><td>Pedido registrado, sem pagamento confirmado.</td><td>É o estado inicial; cancelamento pode estar disponível.</td></tr>
      <tr><td class="status">Pagamento confirmado</td><td>O registro indica situação paga.</td><td>A demonstração não confirma pagamento por gateway.</td></tr>
      <tr><td class="status">Em preparação</td><td>Estado operacional indicado no pedido.</td><td>Não há atualização automática ou rastreamento.</td></tr>
      <tr><td class="status">Enviado / Entregue</td><td>Estado informado para o pedido.</td><td>Não há integração de transportadora.</td></tr>
      <tr><td class="status">Cancelado</td><td>Pedido cancelado.</td><td>Itens elegíveis retornam ao saldo de estoque.</td></tr>
    </tbody></table>
    <p>A data de entrega exibida é uma previsão calculada em sete dias úteis a partir do registro, sem considerar feriados ou prazo real de transporte. Pedido cancelado não apresenta previsão.</p>
    <div class="callout"><strong>Atualização manual:</strong> status não é atualizado por pagamento ou transportadora. A tela apresenta o estado que está gravado no sistema.</div>
  </section>

  <section class="page" id="cancelar">
    <p class="eyebrow">11 • Após o pedido</p>
    <h2>Cancelar um pedido elegível</h2>
    <p>O cancelamento pelo cliente é oferecido somente quando o pedido está <strong>aguardando pagamento</strong> e a situação do pagamento está <strong>pendente</strong>.</p>
    <ol class="steps">
      <li>Abra <strong>Meus pedidos</strong> na conta que criou o pedido.</li>
      <li>Localize o pedido elegível e escolha <strong>Cancelar pedido</strong>.</li>
      <li>Leia a confirmação: o cancelamento não pode ser desfeito e as unidades retornarão ao estoque.</li>
      <li>Escolha <strong>Sim, cancelar pedido</strong> para confirmar ou <strong>Não, voltar</strong> para manter o pedido.</li>
      <li>Confira a mensagem e o novo estado cancelado na lista.</li>
    </ol>
    <div class="callout warning"><strong>Sem opção de cancelamento?</strong> O pedido pode já estar pago, em outro estado, cancelado ou pertencer a uma conta diferente. A equipe não dispõe de uma tela para alterar status nesta versão.</div>
    <p>Se o sistema informar que não é possível cancelar, nenhum estorno bancário é realizado: não há pagamento integrado. Para um pedido demonstrativo, não faça transferência real.</p>
  </section>

  <section class="page" id="equipe">
    <p class="eyebrow">12 • Usuários da equipe</p>
    <h2>Acessar o painel e ler a visão geral</h2>
    <p>O painel destina-se a contas de equipe previamente criadas. A opção pública “Criar conta” é apenas para clientes.</p>
    <ol class="steps">
      <li>Abra <strong>Entrar</strong> pela loja.</li>
      <li>Informe as credenciais de vendedor ou administrador fornecidas pelo responsável da demonstração.</li>
      <li>Depois do acesso, a aplicação abre o painel da equipe.</li>
      <li>Use o menu lateral para abrir as áreas liberadas ao seu perfil; <strong>Abrir loja</strong> volta ao catálogo público.</li>
    </ol>
    <figure class="screen"><img src="dashboard-guia.svg" alt="Ilustração da visão geral do painel FightZone"><figcaption>Figura 5 — Ilustração do painel com valores de exemplo; indicadores reais vêm dos dados da demonstração.</figcaption></figure>
    <table><thead><tr><th>Área do painel</th><th>Administrador</th><th>Vendedor</th></tr></thead><tbody>
      <tr><td>Visão geral</td><td>Acessa</td><td>Acessa</td></tr>
      <tr><td>Pedidos e relatórios</td><td>Consulta</td><td>Consulta</td></tr>
      <tr><td>Produtos e estoque</td><td>Gerencia</td><td>Sem acesso de edição</td></tr>
      <tr><td>Entradas de estoque e categorias</td><td>Gerencia</td><td>Sem acesso</td></tr>
    </tbody></table>
    <p class="small">O painel mostra pedidos do mês, somatório de pedidos marcados pagos, pedidos aguardando, produtos ativos e produtos com estoque igual ou inferior a cinco unidades.</p>
  </section>

  <section class="page" id="operacao">
    <p class="eyebrow">13 • Usuários da equipe</p>
    <h2>Consultar pedidos e relatórios</h2>
    <h3>Lista de pedidos</h3>
    <ol class="steps">
      <li>No menu do painel, selecione <strong>Pedidos</strong>.</li>
      <li>Consulte número, data, cliente, e-mail, forma de pagamento, estado e total.</li>
      <li>Use o painel para voltar à visão geral.</li>
    </ol>
    <p>Esta tela é de consulta. Ela não confirma pagamento nem oferece ação para alterar estado, registrar envio ou emitir nota fiscal.</p>
    <h3>Relatórios por período</h3>
    <ol class="steps">
      <li>Selecione <strong>Relatórios</strong>.</li>
      <li>Informe as datas em <strong>De</strong> e <strong>Até</strong>; inicialmente o período corresponde ao mês atual até hoje.</li>
      <li>Escolha <strong>Aplicar</strong> para atualizar a tabela e os indicadores.</li>
    </ol>
    <p>O relatório apresenta quantidade e soma dos pedidos encontrados no intervalo, quantidade de produtos ativos e unidades em estoque. A listagem inclui os estados de pedido registrados, inclusive cancelados; a soma não é faturamento confirmado.</p>
    <div class="callout"><strong>Para diferenciar:</strong> “Faturamento confirmado” no painel soma pedidos com status pago no mês. “Valor total dos pedidos” no relatório soma os pedidos do intervalo sem filtrar pelo estado de pagamento.</div>
    <p>Se o período estiver invertido ou com data inválida, revise as datas e aplique novamente.</p>
  </section>

  <section class="page" id="produtos">
    <p class="eyebrow">14 • Administração</p>
    <h2>Cadastrar e editar produtos</h2>
    <p>Esta área é exclusiva do perfil administrador. Em <strong>Produtos e estoque</strong>, o formulário e a lista aparecem na mesma página.</p>
    <ol class="steps">
      <li>Escolha <strong>Novo produto</strong> (ou abra <strong>Editar</strong> na linha desejada).</li>
      <li>Preencha nome, categoria, marca, preço maior que zero e saldo inicial igual ou superior a zero.</li>
      <li>Informe peso em quilos e altura, largura e comprimento do pacote em centímetros; esses dados participam da estimativa de frete.</li>
      <li>Adicione descrição se desejar. A foto é opcional; se enviar, use JPG, PNG ou WebP de até 5 MB.</li>
      <li>Confira os campos e selecione <strong>Salvar produto</strong>. O SKU é criado automaticamente.</li>
    </ol>
    <h3>Editar e remover da vitrine</h3>
    <ul class="compact">
      <li>Use <strong>Editar</strong> na linha do produto, altere os campos necessários e salve.</li>
      <li>Se não selecionar uma nova foto ao editar, a imagem atual é mantida.</li>
      <li><strong>Remover</strong> pede confirmação e inativa o registro: o produto deixa de aparecer na loja, mas seu histórico é preservado.</li>
      <li>Uma linha inativa pode ser aberta em edição; salvar o formulário a reativa. Confira os dados antes de salvar.</li>
    </ul>
    <div class="callout warning"><strong>Cuidado com estoque:</strong> o campo de saldo do produto define diretamente a quantidade disponível. Para registrar uma reposição mantendo histórico, use a área específica de entradas.</div>
  </section>

  <section class="page" id="categorias">
    <p class="eyebrow">15 • Administração</p>
    <h2>Manter categorias da loja</h2>
    <p>O administrador pode criar, renomear, ativar e inativar categorias. Elas organizam os produtos e podem ser usadas na pesquisa do catálogo.</p>
    <ol class="steps">
      <li>No painel, escolha <strong>Categorias</strong>.</li>
      <li>Para criar uma categoria, digite um nome e escolha <strong>Salvar</strong>.</li>
      <li>Para renomear, selecione <strong>Editar</strong>, altere o nome e salve.</li>
      <li>Para ocultar uma categoria sem apagar seus registros, escolha <strong>Inativar</strong> e confirme.</li>
      <li>Para voltar a publicá-la, escolha <strong>Ativar</strong> na linha da categoria.</li>
    </ol>
    <div class="callout"><strong>Categoria inativa:</strong> os produtos ligados a ela não aparecem na vitrine pública enquanto a categoria permanecer desativada. Reative-a para voltar a disponibilizar esses itens, desde que os produtos também estejam ativos e tenham estoque.</div>
    <p>A lista mostra identificador, nome e situação. Cadastre nomes claros e evite duplicar categorias.</p>
  </section>

  <section class="page" id="estoque">
    <p class="eyebrow">16 • Administração</p>
    <h2>Registrar uma entrada de estoque</h2>
    <p>Use uma entrada para registrar uma reposição e manter o histórico do movimento. Disponível para administradores.</p>
    <ol class="steps">
      <li>Abra <strong>Entradas de estoque</strong> no menu do painel.</li>
      <li>Escolha <strong>Registrar entrada</strong>.</li>
      <li>Selecione um produto ativo, digite uma quantidade inteira maior que zero e, se necessário, escreva uma observação.</li>
      <li>Confirme em <strong>Registrar entrada</strong>.</li>
      <li>Volte a <strong>Movimentações</strong> para conferir data, produto, tipo, quantidade, responsável e observação.</li>
    </ol>
    <figure class="screen"><img src="../figma/03-painel.svg" alt="Protótipo ilustrativo do painel FightZone"><figcaption>Figura 6 — Referência visual do painel administrativo. Os dados e controles ilustrados são exemplificativos.</figcaption></figure>
    <div class="callout"><strong>Observação:</strong> o histórico também pode mostrar saídas de pedidos e entradas de cancelamentos. Uma linha “Loja” pode indicar movimento de checkout sem usuário de equipe associado.</div>
  </section>

  <section class="page" id="duvidas">
    <p class="eyebrow">17 • Ajuda</p>
    <h2>Dúvidas frequentes e limites</h2>
    <table><thead><tr><th>Situação</th><th>O que fazer</th></tr></thead><tbody>
      <tr><td>Não consigo adicionar ao carrinho.</td><td>Entre como cliente e confirme se o produto está disponível. O sistema não aceita itens sem estoque.</td></tr>
      <tr><td>O botão para entrega não aparece.</td><td>Confira se o carrinho tem itens e calcule o frete com um CEP de oito números.</td></tr>
      <tr><td>O CEP não preenche rua/cidade.</td><td>A consulta é opcional e depende do ViaCEP. Confira o CEP e preencha o endereço manualmente.</td></tr>
      <tr><td>O valor mudou ou o estoque é insuficiente.</td><td>Retorne ao carrinho, atualize quantidades e confira o total antes de tentar novamente.</td></tr>
      <tr><td>O pedido está aguardando pagamento.</td><td>Esse é o estado inicial. A aplicação não valida transferência nem atualiza status por gateway.</td></tr>
      <tr><td>Não vejo a opção de cancelar.</td><td>O cliente só pode cancelar pedido próprio aguardando e com pagamento pendente.</td></tr>
      <tr><td>Preciso de uma nota fiscal.</td><td>O PDF da FightZone é apenas resumo. A aplicação não emite documento fiscal eletrônico.</td></tr>
      <tr><td>Não consigo acessar uma área do painel.</td><td>Confirme com o responsável se a conta é de equipe e se tem o perfil administrativo necessário.</td></tr>
      <tr><td>A loja não abre.</td><td>No ambiente local, peça ao responsável para verificar Apache, MySQL, banco FightZone e configuração do projeto.</td></tr>
    </tbody></table>
    <h3>Funções que não existem nesta versão</h3>
    <ul class="two-col compact">
      <li>Pagamento real por cartão, PIX ou boleto.</li>
      <li>Cotação oficial ou rastreamento de transportadora.</li>
      <li>Emissão de NF-e ou DANFE fiscal.</li>
      <li>Recuperação automática de senha.</li>
      <li>Cadastro de fornecedor ou funcionário pela interface.</li>
      <li>Alteração de situação do pedido pelo painel da equipe.</li>
      <li>Edição de dados cadastrais do cliente na tela Minha conta.</li>
      <li>Filtros avançados do catálogo por marca/preço.</li>
    </ul>
    <div class="callout warning"><strong>Proteja seus dados:</strong> por ser uma demonstração acadêmica, não use a loja para dados de cartão, pagamentos verdadeiros ou informações pessoais sensíveis.</div>
    <div class="end-note">FightZone • Manual do usuário • Equipe: Diego Filipe e Eduardo Cavalcante.</div>
  </section>
</body>
</html>
