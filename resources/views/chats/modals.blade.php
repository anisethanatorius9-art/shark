<!-- Add Photos & Files Modal -->
<div id="modalAddFiles" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-semibold mb-4">Add Photos & Files</h3>
        <input type="file" name="files[]" multiple class="w-full border p-2 rounded mb-4">
        <button @click="document.getElementById('modalAddFiles').classList.add('hidden')" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Close</button>
    </div>
</div>

<!-- Create Thinking Modal -->
<div id="modalThinking" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-semibold mb-4">Create Thinking</h3>
        <textarea class="w-full p-2 border rounded mb-4" placeholder="Write your ideas..."></textarea>
        <button @click="document.getElementById('modalThinking').classList.add('hidden')" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Close</button>
    </div>
</div>

<!-- Deep Chatting Modal -->
<div id="modalDeepChat" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-semibold mb-4">Deep Chatting</h3>
        <p class="mb-4">Start a more advanced conversation here. Use prompts, notes, or references to guide the chat.</p>
        <button @click="document.getElementById('modalDeepChat').classList.add('hidden')" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Close</button>
    </div>
</div>

<!-- Web Search Modal -->
<div id="modalWebSearch" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg w-96">
        <h3 class="text-lg font-semibold mb-4">Web Search</h3>
        <input type="text" class="w-full p-2 border rounded mb-4" placeholder="Search the web...">
        <button @click="document.getElementById('modalWebSearch').classList.add('hidden')" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Close</button>
    </div>
</div>
