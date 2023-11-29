<div>
    <div class="mb-1">
        <label class="form-label col-form-label">{{ $label }}</label>
        <input onClick="this.select();" type="text" class="form-control" name="search" wire:model="search"
            placeholder="{{ $search_name }}" wire:focus="$set('hide',false)" wire:focusout="$set('hide',true)" />
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" autocomplete="off" />
    </div>

    <div style="position:absolute" class="@if ($hide) hide @endif">
        <div class="row ">
            <div class="col"><br><i wire:click="$set('hide',true)"
                    class="dropdown-close-btn fa fa-times-circle fa-2x pointer"></i>
            </div>
        </div>
        <span class="dropdown-content">
            <div>
                @foreach ($list as $item_)
                    <a class="pointer" wire:click='$set("value",{{ $item_['value'] }})'>{{ $item_['name'] }}
                    </a>
                @endforeach
            </div>
        </span>

    </div>
</div>
