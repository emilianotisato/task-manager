<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Carbon;

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
        return Task::query()
            ->with('project')
            ->pending()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->get();
    }

    // Selected state handled per-modal in the Blade view

    public function increaseSeverity(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        $task->severity_points = (int) $task->severity_points + 1;
        $task->save();
    }

    public function postpone(int $taskId, int $days = 1): void
    {
        // Assumption: postponing adds N days to due_date and increments postponed_times
        $task = Task::findOrFail($taskId);
        $task->postponed_times = (int) $task->postponed_times + 1;
        $task->due_date = Carbon::parse($task->due_date)->addDays($days);
        $task->save();
    }

    public function toggleDone(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        $task->completed = ! (bool) $task->completed;
        $task->save();

        // If marked done, close modal as it will drop out of the pending list
        if ($task->completed) {
            $this->dispatch('close-modal', 'task-detail-' . $taskId);
        }
    }

    public function render()
    {
        return view('livewire.tasks.pending-tasks');
    }
}
