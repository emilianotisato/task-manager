<div>
    @foreach($this->tasks as $task)
        <div class="my-0.5">
            <flux:modal.trigger name="task-detail-{{ $task->id }}">
            <button type="button"
                    aria-label="{{ $task->title }}"
                    x-data
                    x-on:click.prevent="$dispatch('open-modal', 'task-detail-{{ $task->id }}')"
                    class="w-full text-left">
                <flux:card size="sm" class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                    <flux:heading class="flex items-center gap-2">
                        <flux:badge :color="$task->project->color">{{ strtoupper($task->project->name) }}</flux:badge> - {{ $task->title }} -
                        <flux:badge variant="pill" icon="fire">{{ $task->severity_points }}</flux:badge>
                        <flux:badge variant="pill" icon="clock">{{ $task->postponed_times }}</flux:badge>
                        <flux:badge class="ml-auto text-zinc-400" color="{{ $task->due_date <= now() ? 'red' : 'zinc' }}" size="{{ $task->due_date <= now() ? '' : 'sm' }}">{{ $task->due_date->format('d/m') }}</flux:badge>
                    </flux:heading>
                    <flux:text class="mt-2 line-clamp-2 text-zinc-500">{{ $task->description }}</flux:text>
                </flux:card>
            </button>
            </flux:modal.trigger>

            {{-- Per-task details modal --}}
            <flux:modal name="task-detail-{{ $task->id }}" focusable class="max-w-2xl">
                <div class="p-4 sm:p-6">
                    <div class="flex items-start gap-3">
                        <flux:badge :color="$task->project->color">{{ strtoupper($task->project->name) }}</flux:badge>
                        <flux:heading class="text-lg font-semibold">{{ $task->title }}</flux:heading>
                        <flux:badge class="ml-auto text-zinc-400" color="{{ $task->due_date <= now() ? 'red' : 'zinc' }}" size="{{ $task->due_date <= now() ? '' : 'sm' }}">{{ $task->due_date->format('d/m/Y') }}</flux:badge>
                    </div>

                    <div class="mt-2 flex items-center gap-2">
                        <flux:badge variant="pill" icon="fire">{{ $task->severity_points }}</flux:badge>
                        <flux:badge variant="pill" icon="clock">{{ $task->postponed_times }}</flux:badge>
                        @if($task->completed)
                            <flux:badge color="green" icon="check">Done</flux:badge>
                        @endif
                    </div>

                    <div class="prose prose-zinc dark:prose-invert mt-4 max-w-none">
                        {!! \Illuminate\Support\Str::markdown($task->description ?? '') !!}
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <flux:button variant="subtle" icon="fire" wire:click="increaseSeverity({{ $task->id }})">Increase severity</flux:button>
                        <flux:button variant="subtle" icon="clock" wire:click="postpone({{ $task->id }}, 1)">Postpone 1d</flux:button>
                        <flux:button variant="subtle" icon="clock" wire:click="postpone({{ $task->id }}, 7)">Postpone 1w</flux:button>
                        @if($task->completed)
                            <flux:button variant="primary" icon="x" wire:click="toggleDone({{ $task->id }})">Mark as undone</flux:button>
                        @else
                            <flux:modal.close>
                                <flux:button variant="primary" color="green" icon="check" wire:click="toggleDone({{ $task->id }})">Mark as done</flux:button>
                            </flux:modal.close>
                        @endif
                    </div>

                    <div class="mt-4 text-right">
                        <flux:modal.close>
                            <flux:button variant="ghost">Close</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </flux:modal>
        </div>
    @endforeach
</div>
