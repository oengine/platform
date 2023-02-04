<div class="dropdown open mr-2">
    <p class=" p-2" type="button" data-bs-toggle="dropdown"  aria-expanded="false">
        {{ $fullname }}
    </p>
    <ul class="dropdown-menu dropdown-menu-end">
        <li class="user-header w-100">
            <p class="text-center  w-100">
                {{ $fullname }} 
            </p>
        </li>
        <li class="w-100">
            <div class="text-center p-1">
                <a href="#" wire:component='core::common.change-password'
                    class="btn btn-sm btn-primary m-auto">Change Password</a>
            </div>
        </li>
        <li class="w-100">
            <div class="text-center  p-1">
                <a href="#" wire:click="DoLogout()" class="btn btn-sm btn-danger m-auto">Sign out</a>
            </div>
        </li>
    </ul>
</div>
