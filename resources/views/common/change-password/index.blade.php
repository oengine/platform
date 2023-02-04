<div class="p-2">
    <div class="mb-2">
        <label for="ctrPassword" class="form-label">{{ __('core::screens.change-password.password') }}</label>
        <input type="password" class="form-control" wire:model.defer='password' id="ctrPassword"
            placeholder="{{ __('core::screens.change-password.password') }}">
    </div>
    <div class="mb-2">
        <label for="ctrPassword" class="form-label">{{ __('core::screens.change-password.password2') }}</label>
        <input type="password" class="form-control" wire:model.defer='password2' id="ctrPassword"
            placeholder="{{ __('core::screens.change-password.password2') }}">
    </div>
    <div class="text-center">
        <button wire:click='DoWork()' type="button"
            class="btn btn-primary">{{ __('core::screens.change-password.change-password') }}</button>
    </div>
</div>
