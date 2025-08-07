<div>
    @foreach($this->tasks as $task)
    <div class="my-0.5">
    <a href="#" aria-label="{{ $task->title }}" :key="$task->id" class="mt-8">
    <flux:card size="sm" class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
        <flux:heading class="flex items-center gap-2">
                <flux:badge color="orange">{{ strtoupper($task->project->name) }}</flux:badge> - {{ $task->title }} -
                    <flux:badge variant="pill" icon="fire">{{ $task->severity_points }}</flux:badge>
                    <flux:badge variant="pill" icon="clock">{{ $task->postponed_times }}</flux:badge>
                <flux:badge class="ml-auto text-zinc-400" color="{{ $task->due_date <= now() ? 'red' : 'zinc' }}" size="{{ $task->due_date <= now() ? '' : 'sm' }}">{{ $task->due_date->format('d/m') }}</flux:badge>
        </flux:heading>
        <flux:text class="mt-2">{{ $task->description }}</flux:text>
    </flux:card>
    </a>
    </div>
    @endforeach
</div>
