<?php

use Livewire\Volt\Component;

new class extends Component {
    public \App\Models\Task $task;

    public function mounted(App\Models\Task $task)
    {
        $this->task = $task;
    }

}; ?>

<div>
    <livewire:tasks.form :task="$task"/>
</div>
