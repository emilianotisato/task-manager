<?php

use Livewire\Volt\Component;

new class extends Component {
    #[\Livewire\Attributes\Validate('required|min:3')]
    public $name = '';

    #[\Livewire\Attributes\Validate('required')]
    public $color = 'zinc';

    public function save()
    {
        $this->validate();

        \App\Models\Project::create(
            $this->only(['name', 'color'])
        );

        session()->flash('status', 'Project successfully created.');

        return $this->redirect(route('projects.index'));
    }

};
?>

<div>
<form wire:submit="save">
    <flux:input description="Project name" :invalid="$errors->has('name')" wire:model="name" />

    <div class="mt-4">
        <flux:field description="Badge color" :invalid="$errors->has('color')">
            <div class="grid grid-cols-3 gap-2 sm:grid-cols-6 lg:grid-cols-9">
                @php
                    $colors = [
                        'zinc' => 'Zinc',
                        'red' => 'Red', 
                        'orange' => 'Orange',
                        'amber' => 'Amber',
                        'yellow' => 'Yellow',
                        'lime' => 'Lime',
                        'green' => 'Green',
                        'emerald' => 'Emerald',
                        'teal' => 'Teal',
                        'cyan' => 'Cyan',
                        'sky' => 'Sky',
                        'blue' => 'Blue',
                        'indigo' => 'Indigo',
                        'violet' => 'Violet',
                        'purple' => 'Purple',
                        'fuchsia' => 'Fuchsia',
                        'pink' => 'Pink',
                        'rose' => 'Rose'
                    ];
                @endphp
                
                @foreach($colors as $colorKey => $colorName)
                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            wire:model="color" 
                            value="{{ $colorKey }}" 
                            class="sr-only"
                        >
                        <flux:badge 
                            :color="$colorKey" 
                            wire:click="$set('color', '{{ $colorKey }}')"
                            class="w-full text-center transition-all duration-200 {{ $this->color === $colorKey ? 'ring-2 ring-blue-500 ring-offset-2' : 'hover:scale-105' }}"
                        >
                            {{ $colorName }}
                        </flux:badge>
                    </label>
                @endforeach
            </div>
        </flux:field>
    </div>

    <div class="flex justify-end">
        <flux:button type="submit" class="mt-4">Save</flux:button>
    </div>
</form>
</div>
