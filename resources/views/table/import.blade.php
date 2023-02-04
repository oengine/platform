<div class="import-{{$module}} import-form">
    <div class="p-2">File Excel:</div>
    <div class="p-2"><input type="file" wire:model="filename" maxlength="20" class="form-control-file" /></div>
    <div class="text-center"> <button class="btn btn-success btn-sm mb-2" wire:click="ImportExcel">{{__("core::table.button.import")}}</button></div>
</div>