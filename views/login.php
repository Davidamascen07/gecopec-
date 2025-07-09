<!-- Conteúdo principal do login -->
<div class="bg-gradient-to-br from-indigo-900 to-purple-800 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden w-full max-w-md animate-fade-in">
        <div class="p-8">
            <!-- Logo Section -->
            <div class="flex justify-center mb-8">
                <div class="bg-white p-3 rounded-full shadow-lg">
                    <i class="fas fa-graduation-cap text-indigo-600 text-2xl"></i>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-center text-white mb-2">Bem-vindo ao <?php echo APP_NAME; ?></h1>
            <p class="text-center text-white/80 mb-8">Faça login para acessar sua conta</p>
            
            <!-- Exibição de mensagem de erro -->
            <?php if(isset($error)): ?>
                <div class="bg-red-500/20 text-white p-3 rounded-lg mb-6 border border-red-400/30">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form id="loginForm" action="index.php?page=login" method="POST" class="space-y-6">
                <!-- Email Field -->
                <div class="relative">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="input-field w-full px-4 py-3 bg-white/20 text-white rounded-lg border border-white/30 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-200 outline-none transition duration-200 placeholder-transparent peer" 
                        placeholder=" "
                        required
                    />
                    <label 
                        for="email" 
                        class="absolute left-4 top-3 text-white/70 text-sm transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:text-white/50 peer-placeholder-shown:top-3 pointer-events-none"
                    >
                        Email
                    </label>
                    <div class="absolute right-3 top-3 text-white/50">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-field w-full px-4 py-3 bg-white/20 text-white rounded-lg border border-white/30 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-200 outline-none transition duration-200 placeholder-transparent peer" 
                        placeholder=" "
                        required
                    />
                    <label 
                        for="password" 
                        class="absolute left-4 top-3 text-white/70 text-sm transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:text-white/50 peer-placeholder-shown:top-3 pointer-events-none"
                    >
                        Senha
                    </label>
                    <button 
                        type="button" 
                        class="password-toggle absolute right-3 top-3 text-white/50 hover:text-white transition-colors"
                        onclick="togglePasswordVisibility()"
                    >
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-white/80">
                            Lembrar-me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-white hover:text-indigo-200 transition-colors">
                            Esqueceu a senha?
                        </a>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]"
                >
                    Entrar
                </button>
            </form>
            
            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-white/70">
                    © <?php echo date('Y'); ?> - <?php echo APP_NAME; ?>. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos da página de login -->
<script>
    // Toggle password visibility
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.password-toggle i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    
    // Add animation to form elements
    document.addEventListener('DOMContentLoaded', () => {
        const formElements = document.querySelectorAll('.input-field, button, .password-toggle, a');
        formElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
            el.classList.add('animate-fade-in');
        });
    });
</script>
