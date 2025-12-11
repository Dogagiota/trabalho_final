<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>

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

    <!-- POPUP DE ERRO -->
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

    <!-- POPUP DE SUCESSO -->
    <div id="popup_sucesso" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl shadow-xl fade-in w-72">
            <h3 class="text-lg font-semibold text-green-600 mb-2">Email Enviado</h3>
            <p class="text-gray-700 mb-4">Se o email existir no sistema, enviaremos um link de recuperação.</p>
            <button onclick="fecharPopupSucesso()" 
                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                Fechar
            </button>
        </div>
    </div>

    <!-- CAIXA DE RECUPERAÇÃO -->
    <div class="bg-white p-8 rounded-xl shadow-lg w-80">
        <h2 class="text-2xl font-semibold text-center mb-6">Recuperar Senha</h2>

        <form onsubmit="validarFormulario(event)">
            <label class="text-sm font-medium">Email cadastrado</label>
            <input type="email" id="email"
                class="w-full px-3 py-2 mt-1 mb-6 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Digite seu email">

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Enviar
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-600">
            Lembrou a senha?
            <a href="login.php" class="text-blue-600 font-medium hover:underline">Voltar ao login</a>
        </p>
    </div>

    <script>
        function fecharPopup() {
            document.getElementById("popup_erro").classList.add("hidden");
        }

        function fecharPopupSucesso() {
            document.getElementById("popup_sucesso").classList.add("hidden");
        }

        function validarFormulario(evento) {
            evento.preventDefault();

            const email = document.getElementById("email").value;
            const mensagem = document.getElementById("mensagem_erro");

            if (!email) {
                mensagem.innerText = "Digite um email válido.";
                document.getElementById("popup_erro").classList.remove("hidden");
                return;
            }

            // simulação visual
            document.getElementById("popup_sucesso").classList.remove("hidden");
        }
    </script>

</body>
</html>
