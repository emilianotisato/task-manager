<?php

use Livewire\Volt\Component;


new class extends Component {

    use \Livewire\WithPagination;

    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[\Livewire\Attributes\Computed]
    public function projects()
    {
        return \App\Models\Project::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(25);
    }
}; ?>

<div>
<div class="flex justify-end">
<flux:button variant="primary" color="green" href="{{route('projects.create')}}">Create</flux:button>
</div>
<flux:table :paginate="$this->projects">
    <flux:table.columns>
        <flux:table.column>Project</flux:table.column>
        <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Date</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @foreach ($this->projects as $project)
            <flux:table.row :key="$project->id">
                <flux:table.cell class="flex items-center gap-3">
                    <flux:badge :color="$project->color" size="sm">{{ strtoupper($project->name) }}</flux:badge>
                </flux:table.cell>

                <flux:table.cell class="whitespace-nowrap">{{ $project->created_at }}</flux:table.cell>

                <flux:table.cell>
                    <flux:badge size="sm" :color="$project->enabled ? 'teal' : 'amber'" inset="top bottom">{{ $project->enabled ? 'Enabled' : 'Archived' }}</flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                </flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table.rows>
</flux:table>



</div>
