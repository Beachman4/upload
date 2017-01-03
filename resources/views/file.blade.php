<html>
{{ dd($_SERVER) }}
    <body>
        @if($type == 0)
            <img src="/files/{{ $file }}">
        @elseif($type == 1)
            <video src="/files/{{ $file }}"></video>
        @elseif($type == 2)
            <audio src="/files/{{ $file }}"></audio>
        @endif
    </body>
</html>