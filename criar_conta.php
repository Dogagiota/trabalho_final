<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .fade-in {
            animation: fadeIn .2s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div id="popup_erro" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl shadow-xl fade-in w-72">
            <h3 class="text-lg font-semibold text-red-600 mb-2">Erro</h3>
            <p id="mensagem_erro" class="text-gray-700 mb-4"></p>
            <button onclick="fecharPopup()" 
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                Fechar
            </button>
        </div>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-lg w-80">
        <h2 class="text-2xl font-semibold text-center mb-6">Criar Conta</h2>

        <form action="criar_conta_acao.php" method="POST" onsubmit="return validarFormulario()">
            
            <label class="text-sm font-medium">Nome completo</label>
            <input type="text" id="nome"
                class="w-full px-3 py-2 mt-1 mb-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Seu nome">

            <label class="text-sm font-medium">Email</label>
            <input type="email" id="email"
                class="w-full px-3 py-2 mt-1 mb-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Digite seu email">

            <label class="text-sm font-medium">Telefone</label>
            <input type="text" id="telefone"
                class="w-full px-3 py-2 mt-1 mb-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="(00) 00000-0000" maxlength="15" oninput="formatarTelefone(this)">

            <label class="text-sm font-medium">Senha</label>
            <div class="relative mb-4">
                <input type="password" id="senha"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Crie uma senha">
                <button type="button" id="btnSenha" onclick="toggleSenha()"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    👁️
                </button>
            </div>

            <label class="text-sm font-medium">Confirmar Senha</label>
            <div class="relative mb-6">
                <input type="password" id="confirmar_senha"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Repita a senha">
                <button type="button" id="btnConfirmarSenha" onclick="toggleConfirmarSenha()"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    👁️
                </button>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Criar conta
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-600">
            Já tem conta?
            <a href="login.php" class="text-blue-600 font-medium hover:underline">Entrar</a>
        </p>
    </div>

    <script>
        function fecharPopup() {
            document.getElementById("popup_erro").classList.add("hidden");
        }

        function validarFormulario() {
            const nome = document.getElementById("nome").value;
            const email = document.getElementById("email").value;
            const senha = document.getElementById("senha").value;
            const confirmar = document.getElementById("confirmar_senha").value;
            const mensagem = document.getElementById("mensagem_erro");

            if (!nome || !email || !senha || !confirmar) {
                mensagem.innerText = "Preencha todos os campos.";
                document.getElementById("popup_erro").classList.remove("hidden");
                return false;
            }

            if (senha.length < 6) {
                mensagem.innerText = "A senha deve ter pelo menos 6 caracteres.";
                document.getElementById("popup_erro").classList.remove("hidden");
                return false;
            }

            if (senha !== confirmar) {
                mensagem.innerText = "As senhas não coincidem.";
                document.getElementById("popup_erro").classList.remove("hidden");
                return false;
            }

            return true;
        }

        function formatarTelefone(input) {
            let valor = input.value.replace(/\D/g, "");
            valor = valor.substring(0, 11);
            valor = valor.replace(/^(\d{0,2})/, "($1");
            valor = valor.replace(/^(\(\d{2})(\d)/, "$1) $2");
            valor = valor.replace(/(\d{5})(\d)/, "$1-$2");
            input.value = valor;
        }

        function toggleSenha() {
            const input = document.getElementById("senha");
            const btn = document.getElementById("btnSenha");

            const aberto = input.type === "text";
            input.type = aberto ? "password" : "text";
            btn.textContent = aberto ? "👁️" : "🙈";
        }

        function toggleConfirmarSenha() {
            const input = document.getElementById("confirmar_senha");
            const btn = document.getElementById("btnConfirmarSenha");

            const aberto = input.type === "text";
            input.type = aberto ? "password" : "text";
            btn.textContent = aberto ? "👁️" : "🙈";
        }
    </script>

</body>
</html>
