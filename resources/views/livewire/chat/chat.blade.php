<div>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside
            class="hidden md:flex absolute w-full md:w-80 p-1 overflow-y-auto border top-16 right-0 z-40 h-screen border-gray-200">
            <livewire:chat.chat-list :selectedConversation="$selectedConversation" :query="$query"/>
        </aside>

        <main class="p-4 md:mr-80 mr-0 h-full">
            <livewire:chat.chat-box :selectedConversation="$selectedConversation"/>
        </main>
    </div>

</div>
