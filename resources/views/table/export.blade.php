<div class="export-{{$module}} export-form">
    <div class="p-2">Filename:</div>
    <div class="p-2"><input type="text" wire:model="filename" maxlength="20" class="form-control" /></div>
    <div class="text-center"> <button class="btn btn-success btn-sm  mb-2" wire:click="ExportExcel">{{__("core::table.button.export")}}</button></div>
</div>