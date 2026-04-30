<?php

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] #[Title('User Management')] class extends Component {
    use WithPagination;

    public bool $isEditing = false;
    public ?int $userId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'customer';

    public function create(): void
    {
        $this->reset(['name', 'email', 'password', 'role', 'userId', 'isEditing']);
        $this->resetValidation();
        $this->role = UserRole::CUSTOMER->value;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $user = User::withTrashed()->findOrFail($id);

        $this->userId    = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->role      = $user->role;
        $this->password  = ''; // Don't show password
        $this->isEditing = true;
    }

    public function save(): void
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'role'  => ['required', Rule::enum(UserRole::class)],
        ];

        if (!$this->isEditing || $this->password) {
            $rules['password'] = $this->isEditing ? 'nullable|min:8' : 'required|min:8';
        }

        $validated = $this->validate($rules);

        if ($this->isEditing) {
            $user = User::withTrashed()->findOrFail($this->userId);
            $user->update([
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $this->role,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }
        } else {
            User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'role'     => $this->role,
                'password' => Hash::make($this->password),
            ]);
        }

        $this->dispatch('close-modal', 'user-modal');
        \Flux::toast('User successfully ' . ($this->isEditing ? 'updated' : 'created') . '.');
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            \Flux::toast('You cannot delete yourself.', variant: 'danger');
            return;
        }

        $user->delete();
        \Flux::toast('User "' . $user->name . '" is now inactive (soft deleted).');
    }

    public function restore(int $id): void
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        \Flux::toast('User "' . $user->name . '" has been restored.');
    }

    public function forceDelete(int $id): void
    {
        $user = User::withTrashed()->findOrFail($id);
        
        if ($user->id === auth()->id()) {
            \Flux::toast('You cannot permanently delete yourself.', variant: 'danger');
            return;
        }

        $user->forceDelete();
        \Flux::toast('User "' . $user->name . '" has been permanently deleted from the database.');
    }

    public function with(): array
    {
        return [
            'users' => User::query()
                ->withTrashed()
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'roles' => UserRole::cases(),
        ];
    }
}; ?>

<div>
    <div class="max-w-[1200px] mx-auto px-6 pt-12 pb-24">

        {{-- Page header --}}
        <div class="mb-10 flex items-end justify-between gap-6">
            <div>
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-3">
                    {{ __('Admin — CMS') }}
                </div>
                <h1 class="text-3xl md:text-4xl font-semibold tracking-[-0.8px] text-[#0d0d0d] dark:text-white">
                    {{ __('User Management') }}
                </h1>
            </div>

            <flux:modal.trigger name="user-modal">
                <button wire:click="create" class="flex items-center gap-2 bg-[#0d0d0d] dark:bg-white text-white dark:text-[#0d0d0d] hover:opacity-80 text-[14px] font-medium px-6 py-2.5 rounded-full shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    {{ __('New User') }}
                </button>
            </flux:modal.trigger>
        </div>

        {{-- Table --}}
        <div class="rounded-[16px] border border-black/[0.05] dark:border-white/[0.08] bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-[14px] text-left">
                    <thead class="border-b border-black/[0.05] dark:border-white/[0.06]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('User') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Email') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Role') }}</th>
                            <th scope="col" class="px-6 py-4 text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Status') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.04] dark:divide-white/[0.04]">
                        @foreach ($users as $user)
                            <tr class="group hover:bg-[#fafafa] dark:hover:bg-zinc-800/50">
                                {{-- User Info --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#f0fdf4] dark:bg-zinc-800 border border-black/[0.05] dark:border-white/[0.05] flex items-center justify-center text-[13px] font-bold text-[#0fa76e]">
                                            {{ $user->initials() }}
                                        </div>
                                        <div>
                                            <span class="font-medium text-[#0d0d0d] dark:text-white group-hover:text-[#0fa76e]">
                                                {{ $user->name }}
                                            </span>
                                            @if ($user->id === auth()->id())
                                                <span class="text-[10px] ml-1 px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500 uppercase tracking-tighter">{{ __('You') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                {{-- Email --}}
                                <td class="px-6 py-4 text-[#666666] dark:text-zinc-400">
                                    {{ $user->email }}
                                </td>
                                {{-- Role --}}
                                <td class="px-6 py-4">
                                    @if ($user->role === \App\Enums\UserRole::ADMIN->value)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[12px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[12px] font-medium bg-zinc-50 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if ($user->trashed())
                                        <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-medium bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20 min-w-[70px]">
                                            {{ __('Deleted') }}
                                        </span>
                                    @else
                                        <span class="inline-flex justify-center px-3 py-1.5 rounded-full text-[12px] font-medium bg-[#d4fae8] dark:bg-[#0fa76e]/20 text-[#0fa76e] dark:text-[#18E299] border border-[#18E299]/20 min-w-[70px]">
                                            {{ __('Active') }}
                                        </span>
                                    @endif
                                </td>
                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center gap-3 justify-end text-[#cccccc] dark:text-zinc-600 group-hover:text-[#999999] dark:group-hover:text-zinc-400">
                                        @if ($user->trashed())
                                            <button wire:click="restore({{ $user->id }})" class="hover:text-[#0fa76e] transition-colors" title="{{ __('Restore') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/></svg>
                                            </button>
                                            <button wire:confirm="Are you sure you want to PERMANENTLY delete this user? This cannot be undone." wire:click="forceDelete({{ $user->id }})" class="hover:text-red-600 transition-colors" title="{{ __('Delete Permanently') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 9-6 6"/><path d="m9 9 6 6"/><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/></svg>
                                            </button>
                                        @else
                                            <flux:modal.trigger name="user-modal">
                                                <button wire:click="edit({{ $user->id }})" class="hover:text-[#0d0d0d] dark:hover:text-white transition-colors" title="{{ __('Edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </button>
                                            </flux:modal.trigger>
                                            <button wire:click="delete({{ $user->id }})" class="hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-[#999999] text-[14px]">{{ __('No users found.') }}</p>
                </div>
            @endif

            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-black/[0.05] dark:border-white/[0.06]">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal --}}
    <flux:modal name="user-modal" class="md:w-[500px]">
        <form wire:submit.prevent="save">
            <div class="mb-6">
                <div class="text-[11px] font-mono text-[#0fa76e] tracking-widest uppercase mb-1">
                    {{ $isEditing ? __('Edit') : __('New') }}
                </div>
                <h2 class="text-2xl font-semibold tracking-[-0.5px] text-[#0d0d0d] dark:text-white">
                    {{ $isEditing ? __('Edit User') : __('New User') }}
                </h2>
            </div>

            <div class="space-y-5 mb-8">
                {{-- Name --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Name') }}</flux:label>
                    <flux:input wire:model="name" placeholder="{{ __('e.g. John Doe') }}" />
                    <flux:error name="name" />
                </flux:field>

                {{-- Email --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Email address') }}</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="{{ __('john@example.com') }}" />
                    <flux:error name="email" />
                </flux:field>

                {{-- Role --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">{{ __('Role') }}</flux:label>
                    <flux:select wire:model="role">
                        @foreach ($roles as $r)
                            <flux:select.option value="{{ $r->value }}">{{ ucfirst($r->value) }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="role" />
                </flux:field>

                {{-- Password --}}
                <flux:field>
                    <flux:label class="text-[13px] font-medium">
                        {{ __('Password') }}
                        @if ($isEditing)
                            <span class="text-[11px] font-normal text-[#999999] ml-1">({{ __('leave blank to keep current') }})</span>
                        @endif
                    </flux:label>
                    <flux:input wire:model="password" type="password" placeholder="••••••••" />
                    <flux:error name="password" />
                </flux:field>
            </div>

            <div class="flex items-center gap-3 justify-end">
                <flux:modal.close>
                    <button type="button" class="text-[14px] font-medium text-[#666666] hover:text-[#0d0d0d] dark:hover:text-white px-4 py-2">
                        {{ __('Cancel') }}
                    </button>
                </flux:modal.close>
                <button type="submit" class="bg-[#0d0d0d] dark:bg-[#18E299] hover:opacity-80 text-white dark:text-[#0d0d0d] text-[14px] font-medium px-6 py-2.5 rounded-full shadow-sm">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </flux:modal>
</div>
