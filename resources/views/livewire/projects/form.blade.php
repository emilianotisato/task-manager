<?php

use Livewire\Volt\Component;

new class extends Component {
    #[\Livewire\Attributes\Validate('required|min:3')]
    public $name = '';


    public function save()
    {
        $this->validate();

        \App\Models\Project::create(
            $this->only(['name'])
        );

        session()->flash('status', 'Project successfully updated.');

        return $this->redirect(route('projects.index'));
    }

};
?>

<div>
<form wire:submit="save">
    <flux:input description="Project name" :invalid="$errors->has('name')" wire:model="name" />


    <div class="flex justify-end">
        <flux:button type="submit" class="mt-4">Save</flux:button>
    </div>
</form>
</div>
