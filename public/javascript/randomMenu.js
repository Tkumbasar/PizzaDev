document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/menus')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('menu-container');
            container.innerHTML = data.map(menu => `
                <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/3 px-4 py-10 flex justify-center items-center">
                    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex flex-col h-full w-full max-h-[400px] max-w-[300px]">
                        <img class="rounded-t-lg w-full h-48 object-cover" src="/images/menu_images/${menu.picture}" />
                        <div class="p-5 flex-grow flex flex-col">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">${menu.title}</h5>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">${menu.description}</p>
                            <div class="flex-grow"></div>
                            <div class="mt-auto">
                                <p class="mb-3 font-bold text-gray-900 dark:text-white">${menu.price} €</p>
                                <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Commander</a>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error fetching menus:', error);
        });
});
