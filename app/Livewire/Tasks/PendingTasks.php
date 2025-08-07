<?php

namespace App\Livewire\Tasks;

use Livewire\Component;

class PendingTasks extends Component
{
    public $sortBy = 'due_date';

    public $sortDirection = 'asc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[\Livewire\Attributes\Computed]
    public function tasks()
    {
        return \App\Models\Task::query()
            ->with('project')
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->get();
    }

    public function render()
    {
        return view('livewire.tasks.pending-tasks');
    }
}
