<?php

use Livewire\Volt\Component;

new class extends Component {
    #[\Livewire\Attributes\Validate('required|min:3')]
    public $title = '';
    
    #[\Livewire\Attributes\Validate('required')]
    public $description = '';
    
    #[\Livewire\Attributes\Validate('required|date|after:today')]
    public $due_date = '';
    
    #[\Livewire\Attributes\Validate('required|exists:projects,id')]
    public $project_id = '';
    
    #[\Livewire\Attributes\Validate('required|integer|min:1|max:5')]
    public $severity_points = 1;
    
    public function mount()
    {
        $this->due_date = now()->addDay()->format('Y-m-d');
        $this->dispatch('focus-first-input');
    }
    
    #[\Livewire\Attributes\Computed]
    public function projects()
    {
        return \App\Models\Project::where('enabled', true)->get();
    }

    public function save()
    {
        $this->validate();

        \App\Models\Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'project_id' => $this->project_id,
            'severity_points' => $this->severity_points,
            'completed' => false,
        ]);

        session()->flash('status', 'Task created successfully.');

        return $this->redirect(route('dashboard'));
    }
        
}; ?>

<div>
    <div class="mb-6">
        <flux:heading>Create New Task</flux:heading>
        <flux:subheading>Add a new task to your list</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <flux:input 
            wire:model="title" 
            label="Task Title" 
            placeholder="Enter task title"
            :invalid="$errors->has('title')"
            :error="$errors->first('title')"
            id="task-title-input"
        />
        
        <flux:textarea 
            wire:model="description" 
            label="Description" 
            placeholder="Describe the task"
            :invalid="$errors->has('description')"
            :error="$errors->first('description')"
        />
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input 
                        type="date"
                        wire:model="due_date" 
                        label="Due Date"
                        placeholder="YYYY-MM-DD"
                    />
            
            <flux:select 
                wire:model="project_id" 
                label="Project"
                :invalid="$errors->has('project_id')"
                :error="$errors->first('project_id')"
                placeholder="Select a project"
            >
                @foreach($this->projects as $project)
                    <flux:select.option :value="$project->id">{{ $project->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
        
        <flux:select 
            wire:model="severity_points" 
            label="Priority"
            :invalid="$errors->has('severity_points')"
            :error="$errors->first('severity_points')"
            placeholder="Select priority"
        >
            <flux:select.option :value="1">Low (1)</flux:select.option>
            <flux:select.option :value="2">Medium (2)</flux:select.option>
            <flux:select.option :value="3">High (3)</flux:select.option>
            <flux:select.option :value="4">Critical (4)</flux:select.option>
            <flux:select.option :value="5">Urgent (5)</flux:select.option>
        </flux:select>

        <div class="flex justify-end gap-3">
            <flux:button type="submit">
                Create Task
            </flux:button>
        </div>
    </form>
    
    <script>
        document.addEventListener('focus-first-input', function() {
            setTimeout(() => {
                const titleInput = document.getElementById('task-title-input');
                if (titleInput) {
                    titleInput.focus();
                }
            }, 100);
        });
    </script>
</div>
