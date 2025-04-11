<div>
    @if(auth()->id() == 3)
        id = {{auth()->id()}}, operator phone
    @elseif(auth()->id() == 4)
        id = {{auth()->id()}}, operator news
    @endif
</div>
