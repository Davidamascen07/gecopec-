<?php if(isset($_GET['page']) && $_GET['page'] != 'login'): ?>
        </main>
    </div>

    <!-- Modal Container -->
    <div id="modal-container" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div id="modal-content">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <i class="fas fa-spinner fa-spin text-2xl text-indigo-600"></i>
            <span class="ml-2">Carregando...</span>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        });
        
        // Notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // Modal functions
        function openModal(content) {
            const modalContainer = document.getElementById('modal-container');
            const modalContent = document.getElementById('modal-content');
            
            modalContent.innerHTML = content;
            modalContainer.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal-container').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('modal-container').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Loading spinner functions
        function showLoading() {
            document.getElementById('loading-spinner').classList.remove('hidden');
        }
        
        function hideLoading() {
            document.getElementById('loading-spinner').classList.add('hidden');
        }
    </script>
<?php endif; ?>
</body>
</html>
