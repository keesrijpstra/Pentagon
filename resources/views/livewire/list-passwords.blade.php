<div>
    {{ $this->table }}
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('clipboard-copy', ({ text }) => {
            navigator.clipboard.writeText(text);
        });
    });
</script>