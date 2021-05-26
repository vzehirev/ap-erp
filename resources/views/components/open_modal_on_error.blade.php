@if (count($viewErrorBag->getBags()) > 0)
    <script>
        showModal("{{ array_key_first($viewErrorBag->getBags()) }}");

    </script>
@endif
