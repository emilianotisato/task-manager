<?php

use Livewire\Volt\Component;

new class extends Component {
    public $isOpen = false;
    public $search = '';
    
    protected $listeners = ['openCommandPalette' => 'open'];
    
    public function open()
    {
        $this->isOpen = true;
        $this->search = '';
        $this->dispatch('focus-search');
    }
    
    public function close()
    {
        $this->isOpen = false;
        $this->search = '';
    }
    
    public function createNewTask()
    {
        $this->close();
        return $this->redirect(route('tasks.create'));
    }
    
    public function searchSnoozed()
    {
        $this->close();
        // TODO: Implement snoozed tasks search
        session()->flash('status', 'Searching snoozed tasks...');
    }
    
    public function applyFilters()
    {
        $this->close();
        // TODO: Implement filters modal
        session()->flash('status', 'Opening filters...');
    }
    
    public function sortByPriority()
    {
        $this->close();
        // TODO: Implement priority sorting
        session()->flash('status', 'Sorting by priority...');
    }
    
    #[\Livewire\Attributes\Computed]
    public function filteredCommands()
    {
        $commands = [
            [
                'id' => 'create-task',
                'title' => 'Create new task',
                'description' => 'Add a new task to your list',
                'icon' => 'plus',
                'action' => 'createNewTask',
                'shortcut' => 'Alt+N'
            ],
            [
                'id' => 'search-snoozed',
                'title' => 'Search snoozed',
                'description' => 'Find snoozed tasks',
                'icon' => 'clock',
                'action' => 'searchSnoozed',
                'shortcut' => 'Alt+S'
            ],
            [
                'id' => 'apply-filters',
                'title' => 'Apply filters',
                'description' => 'Filter tasks by criteria',
                'icon' => 'funnel',
                'action' => 'applyFilters',
                'shortcut' => 'Alt+F'
            ],
            [
                'id' => 'sort-priority',
                'title' => 'Sort by priority',
                'description' => 'Sort tasks by priority level',
                'icon' => 'arrows-up-down',
                'action' => 'sortByPriority',
                'shortcut' => 'Alt+P'
            ]
        ];
        
        if (empty($this->search)) {
            return $commands;
        }
        
        return collect($commands)->filter(function ($command) {
            return str_contains(strtolower($command['title']), strtolower($this->search)) ||
                   str_contains(strtolower($command['description']), strtolower($this->search));
        })->values()->toArray();
    }
}; ?>

<div data-command-palette-open="{{ $isOpen ? 'true' : 'false' }}">
    @if($isOpen)
        <div class="fixed inset-0 z-50 bg-black/50" wire:click="close"></div>
        
        <div class="fixed left-1/2 top-1/4 z-50 w-full max-w-md -translate-x-1/2 transform">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <flux:command>
                    <flux:command.input 
                        wire:model.live="search"
                        placeholder="Search commands..."
                        clearable
                        closable
                    />
                    
                    @foreach($this->filteredCommands as $command)
                        <flux:command.item 
                            wire:click="{{ $command['action'] }}"
                            :key="$command['id']"
                            :icon="$command['icon']"
                            :kbd="$command['shortcut']"
                        >
                            <div class="flex-1">
                                <div class="font-medium">{{ $command['title'] }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $command['description'] }}</div>
                            </div>
                        </flux:command.item>
                    @endforeach
                    
                    @if(empty($this->filteredCommands))
                        <div class="p-8 text-center">
                            <flux:icon name="magnifying-glass" class="mx-auto size-8 text-zinc-400" />
                            <div class="mt-2 font-medium">No commands found</div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Try searching for something else</div>
                        </div>
                    @endif
                </flux:command>
            </div>
        </div>
    @endif
    
    <script>
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                @this.call('open');
            }
            
            if (e.key === 'Escape') {
                @this.call('close');
            }
        });
        
        // Handle Alt shortcuts when command palette is open
        document.addEventListener('keydown', function(e) {
            const commandPalette = document.querySelector('[data-command-palette-open]');
            const isOpen = commandPalette && commandPalette.getAttribute('data-command-palette-open') === 'true';
            
            if (isOpen && e.altKey && !e.ctrlKey && !e.shiftKey) {
                const key = e.key.toLowerCase();
                if (['n', 's', 'f', 'p'].includes(key)) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    switch(key) {
                        case 'n':
                            @this.call('createNewTask');
                            break;
                        case 's':
                            @this.call('searchSnoozed');
                            break;
                        case 'f':
                            @this.call('applyFilters');
                            break;
                        case 'p':
                            @this.call('sortByPriority');
                            break;
                    }
                }
            }
        });
        
        document.addEventListener('focus-search', function() {
            setTimeout(() => {
                const searchInput = document.querySelector('[data-flux-command-input] input');
                if (searchInput) {
                    searchInput.focus();
                }
            }, 100);
        });
    </script>
</div>
