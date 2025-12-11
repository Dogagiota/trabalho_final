<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
            <h3 class="text-lg font-semibold text-red-600 mb-2">Erro no Login</h3>
            <p class="text-gray-700 mb-4">Usuário ou senha incorretos.</p>
            <button onclick="fecharPopup()" 
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                Fechar
            </button>
        </div>
    </div>

    <!-- CAIXA DE LOGIN -->
    <div class="bg-white p-8 rounded-xl shadow-lg w-80">
        <h2 class="text-2xl font-semibold text-center mb-6">Login</h2>

        <form method="POST" action="">
            <label class="text-sm font-medium">Email</label>
            <input type="email"
                class="w-full px-3 py-2 mt-1 mb-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Digite seu email">

            <label class="text-sm font-medium">Senha</label>
            <input type="password"
                class="w-full px-3 py-2 mt-1 mb-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Digite sua senha">

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Entrar
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-600">
            Não tem conta?
            <a href="criar_conta.php" class="text-blue-600 font-medium hover:underline">Criar conta</a>
        </p>
        <p class="text-center text-sm mt-4 text-gray-600">
            Esqueceu sua senha?
            <a href="recuperar_senha.php" class="text-blue-600 font-medium hover:underline">Recuperar a senha</a>
        </p>
    </div>


    <script>
        function fecharPopup() {
            document.getElementById("popup_erro").classList.add("hidden");
        }

        // Verifica se veio com ?erro=1 na URL
        const parametros = new URLSearchParams(window.location.search);
        if (parametros.get("erro") == "1") {
            document.getElementById("popup_erro").classList.remove("hidden");
        }
    </script>

</body>
</html>
