<tr>
    <td>{{$product['code']}}</td>
    <td>{{$product['description']}}</td>
    <td>
        <x-form.number name="qty" step="0.1" :value="$item['qty']" />
    </td>
    <td class="actions">       
        <!-- Modal Delete -->
        <a class="pointer" wire:click="$emitUp('deleteRecipe',{{$item['id']}})">
            <i class="far fa-trash-alt"></i>
        </a>       
        <!-- Modal Delete End -->
    </td>
</tr>