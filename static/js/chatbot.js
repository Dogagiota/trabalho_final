const chat = document.getElementById('chat');
const userInput = document.getElementById('userInput');

let respostas = {};
let perguntasPendentes = [];

// --- CONFIGURAÇÃO DOS TIMES DE ARQUITETURA ---
const arquitetos = {
  'Residencial Geral': ['Arq. João', 'Arq. Maria'],
  'Residencial Moderno': ['Arq. Carla', 'Arq. Pedro'],
  'Comercial Industrial': ['Arq. Lucas', 'Arq. Fábio'],
  'Comercial/Prédios': ['Arq. Ana'],
  'Interiores': ['Arq. Sofia', 'Arq. Bruno'],
  'Paisagismo': ['Arq. Ricardo', 'Arq. Laura'],
  'Corporativo': ['Arq. Helena'],
  'Estrutural': ['Eng. Carlos', 'Arq. Sênior Roberto'],
  
  // Times de Estilos Específicos
  'Estilo Clássico/Tradicional': ['Arq. Roberto', 'Arq. Célia'],
  'Estilo Rústico/Natural': ['Arq. Tiago'],
  'Projetos Futuristas': ['Arq. Elon'],
  'Sustentável/Verde': ['Arq. Gaia'],
  'Minimalista': ['Arq. Kenji']
};

// --- MAPEAMENTO DE CHAVES (API -> TIME) ---
const mapaEspecialidade = {
  // ... (Mapeamento de especialidade é o mesmo) ...
  "estrutural": "Estrutural",
  "residencial|moderno": "Residencial Moderno",
  "residencial|contemporaneo": "Residencial Moderno",
  "residencial|minimalista": "Minimalista",
  "residencial|tradicional": "Estilo Clássico/Tradicional",
  "residencial|classico": "Estilo Clássico/Tradicional",
  "residencial|rustico": "Estilo Rústico/Natural",
  "residencial|natural": "Estilo Rústico/Natural",
  "residencial|estrutural": "Estrutural",
  "comercial|industrial": "Comercial Industrial",
  "comercial|moderno": "Comercial/Prédios",
  "comercial|futurista": "Projetos Futuristas",
  "comercial|verde": "Sustentável/Verde",
  "comercial|tradicional": "Estilo Clássico/Tradicional",
  "comercial|estrutural": "Estrutural",
  "corporativo": "Corporativo",
  "residencial": "Residencial Geral",
  "comercial": "Comercial/Prédios", 
  "paisagismo": "Paisagismo",
  "interiores": "Interiores",
  "exterior": "Paisagismo"
};

// --- FUNÇÕES DE INTERFACE ---
function mostrarBot(txt) {
  const chat = document.getElementById('chat');
  let container = document.createElement("div");
  container.className = "flex justify-start animate-fade-in-up";
  let bubble = document.createElement("div");
  bubble.className = "bg-white text-gray-800 px-4 py-3 rounded-2xl rounded-tl-none shadow-sm max-w-[85%] border border-gray-200 text-sm leading-relaxed";
  bubble.innerText = txt;
  container.appendChild(bubble);
  chat.appendChild(container);
  chat.scrollTop = chat.scrollHeight;
}

function mostrarUser(txt) {
  const chat = document.getElementById('chat');
  let container = document.createElement("div");
  container.className = "flex justify-end animate-fade-in-up";
  let bubble = document.createElement("div");
  bubble.className = "bg-slate-800 text-white px-4 py-3 rounded-2xl rounded-tr-none shadow-md max-w-[85%] text-sm leading-relaxed";
  bubble.innerText = txt;
  container.appendChild(bubble);
  chat.appendChild(container);
  chat.scrollTop = chat.scrollHeight;
}

// --- LÓGICA PRINCIPAL DE ENVIO ---

async function enviar() {
  const txt = userInput.value.trim();
  if (!txt) return;
  if (txt.length < 4) {
    mostrarUser(txt);
    userInput.value = "";
    mostrarBot("Por favor, digite uma descrição mais completa para que eu possa entender seu projeto.");
    return;
  }
  mostrarUser(txt);
  userInput.value = "";
  
  // Se é a primeira interação ou não há perguntas pendentes
  if (Object.keys(respostas).length === 0 && perguntasPendentes.length === 0) {
    mostrarBot("Processando sua descrição...");

    try {
      const r = await fetch("/classificar", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ texto: txt })
      });

      const data = await r.json();
      
      // --- BLOQUEIO LÓGICO DE LIXO NA PRIMEIRA INTERAÇÃO (CORRIGIDO LIMITE) ---
      // Baixamos o limite para 0.4 para ser mais sensível
      if (data.resultado.lixo && data.resultado.lixo > 0.4) {
          mostrarBot("Desculpe, a descrição que você enviou não se parece com um projeto de arquitetura. Por favor, descreva o projeto que você deseja (Ex: 'Quero construir uma loja moderna').");
          respostas = {};
          perguntasPendentes = [];
          return;
      }

      respostas = data.resultado; // Atualiza respostas SÓ se não for LIXO

      // 1. Validação de TIPO
      if (!respostas.residencial && !respostas.comercial && !respostas.corporativo && !respostas.paisagismo) {
        perguntasPendentes.push("O projeto é residencial, comercial ou corporativo?");
      }

      // 2. Validação de ESCOPO (Estrutural vs Interiores vs Exterior)
      if (!respostas.estrutural && !respostas.interiores && !respostas.exterior && !respostas.paisagismo) {
        perguntasPendentes.push("Qual o escopo da obra: Construção do zero (Estrutural), Reforma de Interiores ou Área Externa?");
      }

      if (perguntasPendentes.length > 0) {
        mostrarBot(perguntasPendentes[0]);
      } else {
        finalizar();
      }
    } catch (error) {
      mostrarBot("Erro ao conectar com o servidor. Verifique se o app.py está rodando.");
      console.error(error);
    }
    return;
  }

  // Se já existe um diálogo em andamento (segunda ou terceira pergunta)
  processarRespostaDoUsuario(txt);
}

// --- PROCESSAMENTO DE RESPOSTAS DO USUÁRIO ---

async function processarRespostaDoUsuario(txt) {
    
    // --- BLOQUEIO LÓGICO DE LIXO EM RESPOSTAS SEQUENCIAIS (NOVO) ---
    // Checagem obrigatória se a resposta é lixo, pois o usuário pode mandar lixo na 2ª pergunta.
    try {
      const r = await fetch("/classificar", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ texto: txt })
      });

      const data = await r.json();
      
      if (data.resultado.lixo && data.resultado.lixo > 0.4) {
          mostrarBot("Não consegui processar essa resposta. Por favor, responda à pergunta com o termo exato que ela pede (Ex: 'Residencial', 'Estrutural').");
          // Não avança o diálogo, devolve a pergunta atual para o início do array
          return; 
      }
    } catch (error) {
        // Ignora erro de fetch para não interromper o fluxo se o lixo falhar
    }
    // FIM DO BLOQUEIO DE LIXO SEQUENCIAL

    const pergunta = perguntasPendentes.shift();
    const textoUser = txt.toLowerCase();

    let respostaRelevanteDetectada = false;

    // 1. Lógica para TIPO
    if (pergunta.includes("residencial") || pergunta.includes("comercial") || pergunta.includes("corporativo")) {
      if (textoUser.includes("residencial")) {respostas["residencial"] = 1; respostaRelevanteDetectada = true;}
      else if (textoUser.includes("comercial")) {respostas["comercial"] = 1; respostaRelevanteDetectada = true;}
      else if (textoUser.includes("corporativo")) {respostas["corporativo"] = 1; respostaRelevanteDetectada = true;}
      else if (textoUser.includes("paisagismo")) {respostas["paisagismo"] = 1; respostaRelevanteDetectada = true;}
    }

    // 2. Lógica para ESCOPO (Estrutural / Interior / Exterior)
    else if (pergunta.includes("escopo") || pergunta.includes("construção") || pergunta.includes("externa")) {
      
      // Opção A: Estrutural / Construção
      if (textoUser.includes("estrutural") || textoUser.includes("constru") || textoUser.includes("zero") || textoUser.includes("obra")) {
        respostas["estrutural"] = 1; respostas["interiores"] = 0; respostas["exterior"] = 0; respostas["completo"] = 1;
        respostaRelevanteDetectada = true;
      } 
      // Opção B: Interiores
      else if (textoUser.includes("interior") || textoUser.includes("dentro") || textoUser.includes("decora") || textoUser.includes("reforma")) {
        respostas["interiores"] = 1; respostas["estrutural"] = 0; respostas["exterior"] = 0; respostas["completo"] = 0;
        respostaRelevanteDetectada = true;
      } 
      // Opção C: Exterior
      else if (textoUser.includes("extern") || textoUser.includes("fora") || textoUser.includes("jardim") || textoUser.includes("fachada")) {
        respostas["exterior"] = 1; respostas["estrutural"] = 0; respostas["interiores"] = 0; respostas["completo"] = 0;
        respostaRelevanteDetectada = true;
      }
    }

    // Se a resposta do usuário não foi relevante, empurra a pergunta de volta e pede para responder direito.
    if (!respostaRelevanteDetectada) {
        mostrarBot("Não entendi sua resposta. Por favor, tente responder com uma das opções sugeridas na pergunta.");
        perguntasPendentes.unshift(pergunta); // Coloca a pergunta de volta no topo
    }


    if (perguntasPendentes.length > 0) {
      mostrarBot(perguntasPendentes[0]);
    } else {
      finalizar();
    }
}

// --- FINALIZAÇÃO E SELEÇÃO DO ARQUITETO ---

function finalizar() {
  const tipos = ["residencial", "comercial", "corporativo", "paisagismo"];
  const estilos = ["moderno", "tradicional", "minimalista", "contemporaneo", "industrial", "rustico", "classico", "futurista", "natural", "verde"];
  const partes = ["estrutural", "interiores", "exterior", "completo"];
    
  let tipoFinal = tipos.find(t => respostas[t] >= 0.35);
  let estiloFinal = estilos.find(e => respostas[e] >= 0.35);
  let parteFinal = partes.find(p => respostas[p] >= 0.35);

  let chave = "";
  let especialidade = "";

  if (respostas["estrutural"] >= 0.35) {
     especialidade = "Estrutural";
  } else {
    if (tipoFinal && estiloFinal) {
      chave = `${tipoFinal}|${estiloFinal}`;
      if (mapaEspecialidade[chave]) especialidade = mapaEspecialidade[chave];
    } 
    
    if (!especialidade && tipoFinal) {
      chave = tipoFinal;
      if (mapaEspecialidade[chave]) especialidade = mapaEspecialidade[chave];
    }
    
    if (!especialidade && parteFinal) {
      chave = parteFinal;
      if (mapaEspecialidade[chave]) especialidade = mapaEspecialidade[chave];
    }
  }
    
  if (!especialidade) especialidade = "Residencial Geral";

  const arqs = arquitetos[especialidade];
  const escolhido = arqs ? arqs[Math.floor(Math.random() * arqs.length)] : "nossa equipe";

  mostrarBot(`Você será atendido por ${escolhido}, especialista em: Arquitetura ${especialidade}.`);
  
  const dadosCaptados = Object.keys(respostas)
    .filter(key => respostas[key] > 0.3)
    .map(key => `${key} (${(respostas[key] * 100).toFixed(0)}%)`)
    .join(", ");

  mostrarBot(`📝 [Relatório do Sistema]: Identificamos os seguintes padrões: ${dadosCaptados || "Nenhum padrão forte detectado"}.`);

  respostas = {};
  perguntasPendentes = [];
}

// Mensagem inicial
mostrarBot("Olá! Me diga em poucas palavras o que você deseja (Ex: 'Construir uma casa moderna', 'Reformar interiores').");